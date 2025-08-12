<?

/*----------------------------------------------------------------------------*/

/* Moblog settings, these will allow you to update your weblog through email! */
/* You can even upload pictures through your email-account! */

include('admin/loadsettings.php');

// get users that have filled out their email-adress, for use witch the $ALLOWED_SENDERS variable
$query = "SELECT email FROM $table_users WHERE email != ''";
$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
while ($row = mysql_fetch_assoc($result))
{
$users[] = $row['email'];
}
if($users){
$allowed = implode('|',$users);
}

$moblog = loadMoblogSettings($link,$table_moblog);

// configuration variables
    $MAIL_SERVER = "$moblog[server]";
    $MAIL_USERNAME = "$moblog[user]";
    $MAIL_PASSWORD = "$moblog[password]";
    $PICTURE_FOLDER = "upload";
    $POSTS_PER_PAGE = 6;
    $PREVIOUS_CAPTION = "Previous Page";
    $NEXT_CAPTION = "Next Page";
    $THUMBNAIL_SIZE = 150;
    $SHARED_EMAIL = $moblog[shared]; // determines if you use this email-adress for other purposes
	// than zomplog, TRUE if the email-adress you filled out is your general email-adress, FALSE if you created it solely for zomplog
    $ALLOWED_SENDERS = "$allowed";
    $DISPLAY_EMAIL = TRUE; // determines if your email-adress should be shown in the entry

 $foto_name=rand(1,9999)."_".time(); 

// create thumbnails of attachments
function create_thumbnail($filename) {
	global $THUMBNAIL_SIZE, $PICTURE_FOLDER;
    $original = imagecreatefromjpeg("$PICTURE_FOLDER/$filename");
    $twidth = $THUMBNAIL_SIZE;
    $owidth = imagesx($original);
    $oheight = imagesy($original);
    $ratio = $owidth / $twidth;
    $theight = $oheight / $ratio;
    $thumbnail = imagecreatetruecolor($twidth, $theight);
    imagecopyresampled($thumbnail, $original, 0, 0, 0, 0, $twidth, $theight, $owidth, $oheight);
    imagejpeg($thumbnail, "$PICTURE_FOLDER/t_$filename");
    imagejpeg($thumbnail, "latest.jpg");
    imagedestroy($original);
    imagedestroy($thumbnail);
}

$BLOG_URL = "http://" . preg_replace("/index.php/", "", $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']);

// determine page to display
    if (! isset($_GET["page"]))
        $page = 0;
    else
        $page = $_GET["page"];
    $prev = $page - 1;
    $next = $page + 1;
    if ($page > 0)
        $PREVIOUS_PAGE = "<a href=\"index.php?page=$prev\">$PREVIOUS_CAPTION</a>";
    else
        $PREVIOUS_PAGE = "";
    if ($page * $POSTS_PER_PAGE < count($titles) - $POSTS_PER_PAGE)
        $NEXT_PAGE = "<a href=\"index.php?page=$next\">$NEXT_CAPTION</a>";
    else
        $NEXT_PAGE = "";
    $template = preg_replace("/{PREVIOUS_PAGE}/U", "$PREVIOUS_PAGE", $template);
    $template = preg_replace("/{NEXT_PAGE}/U", "$NEXT_PAGE", $template);
    $html = explode("-*!", $template);
    echo $html[0];
    if ($page == 0) {
// open mailbox and get emails from server
       if (! $mail = imap_open( "{" . $MAIL_SERVER . ":110/pop3/notls}INBOX", $MAIL_USERNAME, $MAIL_PASSWORD))
            die("could not connect to mailserver. quitting ...");
        $headerstrings = imap_headers($mail);
        foreach ($headerstrings as $headerstring) {
            $multipart = FALSE;
            $imagecounter = 1;
            preg_match("/[0-9]/", $headerstring, $number);
// parse message and sender
            $header = imap_fetchheader($mail, $number[0]);
            $headerinfo = imap_headerinfo($mail, $number[0], 256, 256);
            $email = " &lt;" . $headerinfo->from[0]->mailbox . "@" . $headerinfo->from[0]->host . "&gt;";
			// stripped version of senderemail for later use.
			$senderemail = $headerinfo->from[0]->mailbox . "@" . $headerinfo->from[0]->host;
            $decode = imap_mime_header_decode($headerinfo->from[0]->personal);
            $sender = $decode[0]->text;
            $sender .= $email;
            $decode = imap_mime_header_decode($headerinfo->fetchsubject);
            $subject = $decode[0]->text;
            $imap = imap_fetchstructure($mail, $number[0]);
			
            if (! empty($imap->parts)) {
                for($i = 0, $j = count($imap->parts); $i < $j; $i++) {
                   $msg = imap_fetchbody($mail, $number[0], $i + 1);
                   $part = $imap->parts[$i];
// save image



					if ($part->disposition == ATTACHMENT || $part->type == TYPEIMAGE || ($part->type == TYPEAPPLICATION && $part->subtype <> "SMIL")) {
					if (! $handle = @fopen("$PICTURE_FOLDER/" . "$foto_name" . "_$imagecounter.jpg", "w"))
                                die("no permission to write image. quitting ...");
                            fwrite($handle, imap_base64($msg));
                            fclose($handle);
                            $imagecounter++;	
                            
                    }
                    if (($part->type == TYPETEXT || $part->type == TYPEMULTIPART) && ! $multipart) {
                        $body = $msg;
                        if (preg_match("#/9j/#", $body)) {
                  			preg_match("#.*(/9j/.*)--.*#Ums", $body, $buffer);
                  			$img = $buffer[1];
                            if (! $handle = @fopen("$PICTURE_FOLDER/" . "$foto_name" . "_$imagecounter.jpg", "w"))
                                die("no permission to write image. quitting ...");
                            fwrite($handle, imap_base64($img));
                            fclose($handle);
                            create_thumbnail("$foto_name" . "_$imagecounter.jpg");
                            $imagecounter++;	
                        }   
                        if (preg_match("/#(.*)#/Ums", $body))
                            $multipart = TRUE;
                        else {
                            $body = imap_base64($body);
                        }
                    }
                    if ($part->subtype == "SMIL" && ! $multipart)
                        $body = imap_base64($body);
                }  
            }
            else
                $body = imap_body($mail, $number[0]);
				
// ugly Sprint hack
			preg_match("#\"(http://pictures.sprintpcs.com//shareImage/.*)\"#Umsi", $body, $sp);			
			$sprintpic = preg_replace("/&amp;/Umsi", "&", $sp[1]);
// extract message
    		if (preg_match("/=23/", $body))
            	preg_match("/=23(.*)=23/Ums", $body, $message);
    		else
            	preg_match("/#(.*)#/Ums", $body, $message);
// handle soft and hard CRs
            $message[1] = preg_replace("/ \r\n/", " ", trim($message[1]));
            $message[1] = preg_replace("/\n/", "<br />", trim($message[1]));	
// decode message according to charset
            if (preg_match("/=C3/", $message[1]))
                $message[1] = utf8_decode(quoted_printable_decode($message[1]));
            else
                $message[1] = quoted_printable_decode($message[1]);
            $message[1] = preg_replace("/ = /", " ", $message[1]);
			
			
// match sender email with username

$query = "SELECT * FROM $table_users WHERE email = '$senderemail' LIMIT 1";
$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
$userinfo = mysql_fetch_array($result);
$username = $userinfo[login];			
				
// write message, author and date into appropriate arrays
            if ($message[1] != "" && preg_match("/({$ALLOWED_SENDERS})/", $sender)) {
	            if (!$DISPLAY_EMAIL)
    	    		$sender = preg_replace('/(.+) &lt;.+&gt;/U', '$1', $sender);
				if ($sprintpic <> '')
           		$message[1] = "<p align='center'><img src='$sprintpic' /></p>" . $message[1];
				

if ($imagecounter = 2){
//filename				
$filename = "$foto_name" . "_1.jpg";
}

// hack to handle emails that don't have an attachment
$file = "upload/$filename";
if(file_exists($file)){
// nothing here. move on
}
else
{
$filename = "";
}


// update database
mysql_query("INSERT INTO $table (title,text,image,date,userid,username) VALUES ('$subject','$message[1]','$filename','$date','$userid','$username')") or die (mysql_error());			
									
// mark emails for deletion from server
                imap_delete($mail, $number[0]);
            }
            elseif (! $SHARED_EMAIL)
                imap_delete($mail, $number[0]);
        }
// delete all read emails from server and close connection
        imap_expunge($mail);
        imap_close($mail);
    }
	
/* end moblog system */	
?>