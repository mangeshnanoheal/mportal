<?php

/* 
Revision history:

Date        Who     What
----        ---     ----
16-Sep-03   NL      Creation.
              
*/

    ob_start(); // avoid indavertantly sending output (before HTTP Headers sent) 

include_once ( 'htmlhead.php'        );
include_once ( 'htmlfoot.php'        );  

    function newlines($n)
    {
        for ($i = 0; $i < $n; $i++)
        {
            echo "<br>\n";
        }
    }

    function display_content()
    { 
    
        /*  The following HTML was generated by OpenOffice from the Word doc
            with the following changes:
            1.  Remove all references to below, shown below, seen below, and see below.
            2.  Remove all images; For form buttons, replace with text.
            3.  Nest lists as necessary.
            4.  Remove hyperlinks.
        */    
        
        $msg = <<< HERE

<H3 CLASS="western">Send e-mail</H3>
<P CLASS="western">Once you have entered and reviewed all e-mail
addresses for the users you want to distribute the ASI client via
e-mail, you are ready to send the distribution and installation
message. 
<P CLASS="western">On this page, before sending the e-mail messages
to the addresses added on the <FONT SIZE=2 STYLE="font-size: 11pt"><FONT FACE="Verdana, sans-serif"><FONT COLOR="#333399">Add
Email Addresses for &lt;Site Name&gt;</FONT></FONT></FONT> page you
can edit:</P>
<UL>
	<LI><P CLASS="ww-list-bullet1-western"><B><FONT FACE="Verdana, sans-serif">Sender
	(default)</FONT></B> &ndash; This is the e-mail address that will be
	displayed as the sending and reply-to address in the ASI client
	distribution and installation e-mail message. 
	</P>
	<LI><P CLASS="ww-list-bullet1-western"><FONT FACE="Verdana, sans-serif"><B>Extra
	headers (default)</B> </FONT>&ndash; In addition to the e-mail
	address the ASI client distribution and installation e-mail message
	is being sent to, the sending e-mail address, and the subject line,
	you can add other headers in this field, each on a different line.
	Following are some common headers and their format:</P>
    <UL>
    	<LI><P CLASS="ww-list-bullet-21-western">X-Priority: (integer
    	between 1, highest and 5)</P>
    	<LI><P CLASS="ww-list-bullet-21-western">Cc: &lt;e-mail address&gt;
    	(if more than one address is entered, addresses should be separated
    	by a comma with no spaces)</P>
    	<LI><P CLASS="ww-list-bullet-21-western">Bcc: &lt;e-mail address&gt;
    	(if more than one address is entered, addresses should be separated
    	by a comma with no spaces)</P>
    </UL>
</UL>
<UL>
	<LI><P CLASS="ww-list-bullet1-western"><B><FONT FACE="Verdana, sans-serif">Subject
	(default)</FONT></B> &ndash; Here you enter the subject of the ASI
	client distribution and installation e-mail message.</P>
	<LI><P CLASS="ww-list-bullet1-western"><FONT FACE="Verdana, sans-serif"><B>Email
	distribution message</B> <B>(default)</B></FONT><B><FONT SIZE=1 STYLE="font-size: 7pt">
	</FONT></B><FONT FACE="Verdana, sans-serif">&ndash; </FONT>In this
	field you can enter content for the default ASI client installation
	e-mail message sent by the email distribution management module.</P>
	<LI><P CLASS="ww-list-bullet1-western"><FONT FACE="Verdana, sans-serif"><B>Download
	URL</B> <B>(default)</B></FONT><B><FONT SIZE=1 STYLE="font-size: 7pt">
	</FONT></B><FONT FACE="Verdana, sans-serif">&ndash; </FONT>In this
	field, you have the option enter the default URL for downloading the
	ASI client installation executable. 
	</P>
	<LI><P CLASS="ww-list-bullet1-western"><FONT FACE="Verdana, sans-serif"><B>Bounce
	email</B> <B>(default)</B></FONT><B><FONT SIZE=1 STYLE="font-size: 7pt">
	</FONT></B><FONT FACE="Verdana, sans-serif">&ndash; </FONT>In this
	field, you have the option to enter a site specific e-mail address
	to be used by the email distribution management module as the return
	destination for the ASI client installation e-mail messages that did
	not reach their original destination.</P>
</UL>
<P CLASS="western">By default, the value of these fields is that
entered in the record of the site whose <FONT COLOR="#0000ff"><U><FONT FACE="Verdana, sans-serif">[manage
email distribution]</FONT></U></FONT><FONT SIZE=1 STYLE="font-size: 7pt"><FONT FACE="Verdana, sans-serif">
</FONT></FONT>link you clicked on. 
</P>
<P CLASS="western">Once you are satisfied that the values of these
entries are as you want them to be for the site where the ASI client
will be installed, you are ready to send the ASI client distribution
and installation messages to the recipients whose e-mail address you
entered on the <FONT SIZE=2 STYLE="font-size: 11pt"><FONT FACE="Verdana, sans-serif"><FONT COLOR="#333399">Add
Email Addresses for &lt;Site Name&gt;</FONT></FONT></FONT> page.</P>
<P CLASS="western">
To do this, click in the box to the left of the <FONT FACE="Verdana, sans-serif">Send
all pending (unsent) email</FONT> label, then click on the 
<FONT FACE="Verdana, sans-serif"><B>Update Content / Send Emails</B></FONT>
button. Doing this will take you to the <FONT SIZE=2 STYLE="font-size: 11pt"><FONT FACE="Verdana, sans-serif"><FONT COLOR="#333399">Sending
Email for &lt;Site Name&gt;</FONT></FONT></FONT> page.</P>
<P CLASS="western">If you only want to edit
the content of the fields on the <FONT SIZE=2 STYLE="font-size: 11pt"><FONT FACE="Verdana, sans-serif"><FONT COLOR="#333399">Send
Email for &lt;Site Name&gt;</FONT></FONT></FONT> page, simply leave
the box to the left of the <FONT FACE="Verdana, sans-serif">Send all
pending (unsent) email</FONT> label unchecked, and click on the 
<FONT FACE="Verdana, sans-serif"><B>Update Content / Send Emails</B></FONT>
button. Doing this will take you to the <FONT SIZE=2 STYLE="font-size: 11pt"><FONT FACE="Verdana, sans-serif"><FONT COLOR="#333399">Updating Content
for &lt;Site Name&gt;</FONT></FONT></FONT> page.</P>
<P CLASS="western">Clicking on the 
<FONT FACE="Verdana, sans-serif"><B>Help</B></FONT>
button on the <FONT SIZE=2 STYLE="font-size: 11pt"><FONT FACE="Verdana, sans-serif"><FONT COLOR="#333399">Send
Email for &lt;Site Name&gt;</FONT></FONT></FONT> page opens the <FONT SIZE=2 STYLE="font-size: 11pt"><FONT FACE="Verdana, sans-serif"><FONT COLOR="#333399">Send
Email Help</FONT></FONT></FONT> page in a new browser window.</P>

    
HERE;

    echo $msg;
    }


   /*
    |  Main program
    */
    
/*    $db = db_connect();
    db_change($GLOBALS['PREFIX'].'install',$db);
    $authuser = install_login($db);
    $comp = component_installed();
  
    $action = get_argument('action',0,'edit'); // non-admin user clicks on user navbar link 
    $title   = ucwords($action) . ' User Help';
    
    $user   = install_user($authuser,$db);    
    $admin  = @ ($user['priv_admin'])  ? 1 : 0;    
    $serv   = @ ($user['priv_servers'])  ? 1 : 0; 
    if ($id == 0) $id = $user['installuserid'];
*/    
    $msg = ob_get_contents();           // save the buffered output so we can...
    ob_end_clean();                     // (now dump the buffer) 
    echo html_header();
    if (trim($msg)) debug_note($msg);   // ...display any errors to debug users  

     
        
    newlines(1);   
    
    display_content();
    
    echo html_footer();
?>
