<?php 
/* 
*   Blog Page Processor
	(c) 2005 Philip Shaddock, www.wizardinteractive.com
	This file is part of the Wizard Site Framework.

    This file is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    It is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with the Wizard Site Framework; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function MAILMAN($fromname, $fromaddress, $toname, $toaddress, $subject, $message)
{
   $headers  = "MIME-Version: 1.0\n";
   $headers .= "Content-type: text/plain; charset=iso-8859-1\n";
   $headers .= "X-Priority: 3\n";
   $headers .= "X-MSMail-Priority: Normal\n";
   $headers .= "X-Mailer: php\n";
   $headers .= "From: \"".$fromname."\" <".$fromaddress.">\n";
   return mail($toaddress, $subject, $message, $headers);
}	

// main configuration file
	include_once '../config_cms/configuration.php';
// database class
	include_once '../db/db.php';
// validation class
	include_once 'validate.input.form.php';


$page = $_POST['page'];
$username = $_POST['username'];
$contact = $_POST['contact'];
$comment = $_POST['comment'];
$subject = $_POST['subject'];
$id = $_POST['id'];

$page = explode("?", $page); 
$page = $page[0]; //strips the url encoding from the url path


//prevent multiple posts and flooding...

	$db = new DB();
	$db->query("SELECT * from ". DB_PREPEND . "comments WHERE ip = ".$_SERVER['REMOTE_ADDR']."");
	
    while($c3 = $db->next_record()) {
	  	$difference = time() - $c3['time'];
	 	if($difference < 60) 
		{ $message = "Error: To prevent spamming, there is a 60 sec wait.";
			$location = CMS_WWW . $page . "?username=$username&contact=$contact&subject=$subject&comment=$comment&page=$page&message=$message&id=$id";
			header("Location: $location");
			exit;
		}
		
  	} //end while

  		if(!$username) { 
			$message = "Error: Please enter a username.";
			$location = CMS_WWW . $page . "?username=$username&contact=$contact&subject=$subject&comment=$comment&page=$page&message=$message&id=$id";
			header("Location: $location");
			exit;
		}
	
  		if(!$contact)  { $message = "Error: Please enter a valid contact address. It will not be displayed on the page.";
		$location = CMS_WWW . $page . "?username=$username&contact=$contact&subject=$subject&comment=$comment&page=$page&message=$message&id=$id";
		
			header("Location: $location");
			exit;
		}
  		if(!$subject)  { $message = "Error: Please enter a message subject.";
		$location = CMS_WWW . $page . "?username=$username&contact=$contact&subject=$subject&comment=$comment&page=$page&message=$message&id=$id";
			header("Location: $location");
			exit;
		}
  		if(!$comment)  { $message = "Error: Missing comment.";
			$location = CMS_WWW . $page . "?username=$username&contact=$contact&subject=$subject&comment=$comment&page=$page&message=$message&id=$id";
			header("Location: $location");
			exit;
		}
		
//this is for a valid contact 
  	if(substr($_POST['contact'],0,7) != 'mailto:' && !strstr($_POST['contact'],'//')) {
        if(strstr($_POST['contact'],'@'))
             $_POST['contact'] = "mailto:".$_POST['contact']."";
   	else
        $_POST['contact'] = "http://".$_POST['contact']."";
   } //end valid contact


$filename = substr(strrchr($page, "/"), 1);  //filename of the page used to find the article title below
//find the article title
$db = new DB();
$db->query("SELECT title FROM ". DB_PREPEND . "pages WHERE filename='$filename'");
$titlename = $db->next_record();


//add comment
	$db = new DB();
	
	$db->query("INSERT INTO ". DB_PREPEND . "comments (article_id, title, page, date, time, username, ip, contact, subject, comment) VALUES ('".$_GET['id']."', '".$titlename['title']."', '".$page."', '".$_POST['date']."', '".$_POST['time']."', '".addslashes(htmlspecialchars($_POST['username']))."', '".$_SERVER['REMOTE_ADDR']."', '".addslashes(htmlspecialchars($_POST['contact']))."', '".addslashes(htmlspecialchars($_POST['subject']))."', '".addslashes(htmlspecialchars($_POST['comment']))."')"); 
	


$fromaddress = addslashes(htmlspecialchars($_POST['contact']));
//notifies webmaster of a post to comments, if activate
$db = new DB();
$db->query("SELECT email,name FROM ". DB_PREPEND . "config LIMIT 1");
$i = $db->next_record();
$toname = $i['name'];
$toaddress = $i['email'];
$contact = addslashes(htmlspecialchars($_POST['contact']));
$subject = $i['name']. ": A comment was posted.";
$fromname = addslashes(htmlspecialchars($_POST['username'])); 
$message = "New comment on article: ".$titlename['title']."\r\n\r\nFrom: " . $fromname . "\r\nSubject: " . addslashes(htmlspecialchars($_POST['subject'])) . " \r\nContact: " . $contact ." \r\n\r\nComment:\r\n" . $comment ." \r\n";

MAILMAN($fromname, $fromaddress, $toname, $toaddress, $subject, $message);

//return to page

			$message = "Thank-you for your comment.";
			$location = CMS_WWW . $page . "?id=" . $id . "&message=" . $message;
			header("Location: $location");
			exit;
?>