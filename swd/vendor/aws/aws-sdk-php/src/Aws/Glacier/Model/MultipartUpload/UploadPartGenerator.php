<?php
/**
 * Copyright 2010-2013 Amazon.com, Inc. or its affiliates. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License").
 * You may not use this file except in compliance with the License.
 * A copy of the License is located at
 *
 * http://aws.amazon.com/apache2.0
 *
 * or in the "license" file accompanying this file. This file is distributed
 * on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 */

namespace Aws\Glacier\Model\MultipartUpload;

use Aws\Common\Enum\Size;
use Aws\Common\Exception\InvalidArgumentException;
use Aws\Common\Exception\OutOfBoundsException;
use Aws\Common\Exception\OverflowException;
use Aws\Common\Exception\RuntimeException;
use Aws\Common\Hash\TreeHash;
use Guzzle\Http\EntityBody;
use Guzzle\Http\EntityBodyInterface;

/**
 * Generates UploadPart objects from a string/stream that encapsulate the data needed for upload requests
 */
class UploadPartGenerator implements \Serializable, \IteratorAggregate, \Countable
{
    const MAX_NUM_PARTS = 10000;

    /**
     * @var string The root checksum (tree hash) of the entire entity body
     */
    protected $rootChecksum;

    /**
     * @var array List of upload parts generated by this helper
     */
    protected $uploadParts;

    /**
     * @var int The total size of the entire upload body
     */
    protected $archiveSize;

    /**
     * @var int Size of upload parts
     */
    protected $partSize;

    /**
     * Creates a UploadPartGenerator and wraps the upload body in a Guzzle EntityBody object
     *
     * @param string|resource|EntityBodyInterface $body     The upload body
     * @param int                                 $partSize The size of parts to split the upload into
     *
     * @return UploadPartGenerator
     */
    public static function factory($body, $partSize)
    {
        return new static(EntityBody::factory($body), $partSize);
    }

    /**
     * Creates a single upload part (up to 4GB) useful for the UploadArchive operation
     *
     * @param string|resource|EntityBodyInterface $body The upload body
     *
     * @return UploadPart
     * @throws RuntimeException if the body ends up being larger than 4GB
     */
    public static function createSingleUploadPart($body)
    {
        $generator = new static(EntityBody::factory($body), 4 * Size::GB);
        if (safe_count($generator) > 1) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException('You cannot create a single upload that is larger than 4 GB.');
            // @codeCoverageIgnoreEnd
        }

        return $generator->getUploadPart(1);
    }

    /**
     * @param EntityBodyInterface $body     The upload body
     * @param int                 $partSize The size of parts to split the upload into. Default is the 4GB max
     *
     * @throws InvalidArgumentException when the part size is invalid (i.e. not a power of 2 of 1MB)
     * @throws InvalidArgumentException when the body is not seekable (must be able to rewind after calculating hashes)
     * @throws InvalidArgumentException when the archive size is less than one byte
     */
    public function __construct(EntityBodyInterface $body, $partSize)
    {
        $this->partSize = $partSize;

       // Make sure the part size is valid
        $validPartSizes = array_map(function ($value) {return pow(2, $value) * Size::MB;}, range(0, 12));
        if (!in_array($this->partSize, $validPartSizes)) {
            throw new InvalidArgumentException('The part size must be a megabyte multiplied by a power of 2 and no '
                . 'greater than 4 gigabytes.');
        }

        // Validate body
        if (!$body->isSeekable()) {
            throw new InvalidArgumentException('The upload body must be seekable.');
        }

        $this->generateUploadParts($body);

        // Validate archive size
        if ($this->archiveSize < 1) {
            throw new InvalidArgumentException('The archive size must be at least 1 byte.');
        }
    }

    /**
     * Returns a single upload part from the calculated uploads by part number. By default it returns the first, which
     * is useful behavior if there is only one upload.
     *
     * @param int $partNumber The numerical index of the upload
     *
     * @return UploadPart
     * @throws OutOfBoundsException if the index of the upload doesn't exist
     */
    public function getUploadPart($partNumber)
    {
        $partNumber = (int) $partNumber;

        // Get the upload at the index if it exists
        if (isset($this->uploadParts[$partNumber - 1])) {
            return $this->uploadParts[$partNumber - 1];
        } else {
            throw new OutOfBoundsException("An upload part with part number {$partNumber} at index did not exist.");
        }
    }
    /**
     * @return array
     */
    public function getAllParts()
    {
        return $this->uploadParts;
    }

    /**
     * @return array
     */
    public function getArchiveSize()
    {
        return $this->archiveSize;
    }

    /**
     * @return string
     */
    public function getRootChecksum()
    {
        if (!$this->rootChecksum) {
            $this->rootChecksum = TreeHash::fromChecksums(array_map(function (UploadPart $part) {
                return $part->getChecksum();
            }, $this->uploadParts))->getHash();
        }

        return $this->rootChecksum;
    }

    /**
     * @return string
     */
    public function getPartSize()
    {
        return $this->partSize;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize(array(
            'uploadParts' => $this->uploadParts,
            'archiveSize' => $this->archiveSize,
            'partSize'    => $this->partSize
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        // Unserialize data
        $data = unserialize($serialized);

        // Set properties
        foreach (array('uploadParts', 'archiveSize', 'partSize') as $property) {
            if (isset($data[$property])) {
                $this->{$property} = $data[$property];
            } else {
                throw new RuntimeException(sprintf('Cannot unserialize the %s class. The %s property is missing.',
                    __CLASS__, $property
                ));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->uploadParts);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return safe_count($this->uploadParts);
    }

    /**
     * Performs the work of reading the body stream, creating tree hashes, and creating UploadPartContext objects
     *
     * @param EntityBodyInterface $body The body to create parts from
     */
    protected function generateUploadParts(EntityBodyInterface $body)
    {
        // Rewind the body stream
        $body->seek(0);

        // Initialize variables for tracking data for upload
        $uploadContext = new UploadPartContext($this->partSize, $body->ftell());

        // Read the data from the streamed body in 1MB chunks
        $data = $this->readPart($body);
        while (strlen($data) > 0) {
            // Add data to the hashes and size calculations
            $uploadContext->addData($data);

            // If the upload part is complete, generate an upload object and reset the currently tracked upload data
            if ($uploadContext->isFull()) {
                $this->updateTotals($uploadContext->generatePart());
                $uploadContext = new UploadPartContext($this->partSize, $body->ftell());
            }

            $data = $this->readPart($body);
        }

        // Handle any leftover data
        if (!$uploadContext->isEmpty()) {
            $this->updateTotals($uploadContext->generatePart());
        }

        // Rewind the body stream
        $body->seek(0);
    }

    /**
     * Updated the upload helper running totals and tree hash with the data from a complete upload part
     *
     * @param UploadPart $part The newly completed upload part
     *
     * @throws OverflowException if the maximum number of allowed upload parts is exceeded
     */
    protected function updateTotals(UploadPart $part)
    {
        // Throw an exception if there are more parts than total allowed
        if ($part->getPartNumber() > self::MAX_NUM_PARTS) {
            // @codeCoverageIgnoreStart
            throw new OverflowException('An archive must be uploaded in ' . self::MAX_NUM_PARTS . ' parts or less.');
            // @codeCoverageIgnoreEnd
        }

        $this->uploadParts[] = $part;
        $this->archiveSize += $part->getSize();
    }

    private function readPart(EntityBodyInterface $body, $max = Size::MB)
    {
        return $body->read(min($this->partSize, $max));
    }
}
