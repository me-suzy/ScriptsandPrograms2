<?php

// ----------------------------------------------------------------------
// Fanfiction Program
// Copyright (C) 2003 by Rebecca Smallwood.
// http://orodruin.sourceforge.net/
// ----------------------------------------------------------------------
// LICENSE
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

function main($penname, $password, $submit, $loggedin)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $imageupload, $submissionsoff, $databasepath, $level, $reviewsallowed, $favorites;
	session_start();
	if((!isset($_SESSION['loggedin'])) || ($_SESSION['loggedin'] == "0"))
	{
		if(isset($submit))
		{
			include("config.php");
			include ($databasepath."/dbconfig.php");
			$result = mysql_query("SELECT password,penname,uid,userskin,level,email,categories FROM ".$tableprefix."fanfiction_authors WHERE penname='$penname'");
			$passwd = mysql_fetch_array($result);
			$encryptedpassword = md5($password);

			if($passwd[password] == $encryptedpassword)
			{
				$_SESSION['loggedin'] = 1;
				$_SESSION['penname'] = "$passwd[penname]";
				$_SESSION['uid'] = "$passwd[uid]";
				$_SESSION['userskin'] = "$passwd[userskin]";
				$_SESSION['level'] = "$passwd[level]";
				if(($passwd[level] != 0) && ($passwd[level] != 4))
				{
					$_SESSION['adminloggedin'] = 1;
					$_SESSION['email'] = "$passwd[email]";
					$_SESSION['admincats'] = "$passwd[categories]";
		     	}
				header("Location: user.php");
			}
			else
			{
				include ("header.php");
				$result = mysql_query("SELECT copyright FROM ".$tableprefix."fanfiction_settings");
				$settings = mysql_fetch_array($result);
				//make a new TemplatePower object
				$tpl = new TemplatePower( "skins/$skin/default.tpl" );

				//let TemplatePower do its thing, parsing etc.
				$tpl->prepare();

				//assign a value to {name}
				$tpl->assign( "footer", $settings[copyright] );
				$tpl->assign( "logo", $logo );
				$tpl->assign( "home", $home );
				$tpl->assign( "recent", $recent );
				$tpl->assign( "catslink", $catslink );
				$tpl->assign( "authors", $authors );
				$tpl->assign( "help", $help );
				$tpl->assign( "search", $search );
				$tpl->assign( "login", $login );
				$tpl->assign( "adminarea", $adminarea );
				$tpl->assign( "titles", $titles );
				$tpl->assign( "logout", $logout );
				$output .= "<center><h4>"._MEMBERLOGIN."</h4></center>";
				$output .= "<center>"._WRONGPASSWORD."</center>";
				$tpl->assign( "output", $output );
				$tpl->printToScreen();
			}
		}
		else
		{		include ("header.php");
				$result = mysql_query("SELECT copyright FROM ".$tableprefix."fanfiction_settings");
				$settings = mysql_fetch_array($result);
				//make a new TemplatePower object
				$tpl = new TemplatePower( "skins/$skin/default.tpl" );

				//let TemplatePower do its thing, parsing etc.
				$tpl->prepare();

				//assign a value to {name}
				$tpl->assign( "footer", $settings[copyright] );
				$tpl->assign( "logo", $logo );
				$tpl->assign( "home", $home );
				$tpl->assign( "recent", $recent );
				$tpl->assign( "catslink", $catslink );
				$tpl->assign( "authors", $authors );
				$tpl->assign( "help", $help );
				$tpl->assign( "search", $search );
				$tpl->assign( "login", $login );
				$tpl->assign( "adminarea", $adminarea );
				$tpl->assign( "titles", $titles );
				$tpl->assign( "logout", $logout );
				$output .= "<center><h4>"._MEMBERLOGIN."</h4></center>";
				$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"user.php\">
				<table align=\"center\"><tr><td>
				"._PENNAME.":
				</td><td>
				<INPUT name=\"penname\">
				</td></tr><tr><td>
				"._PASSWORD.":
				</td><td>
				<INPUT type=\"password\" name=\"password\">
				</td></tr><tr><td colspan=\"2\" align=\"center\">
				<INPUT type=\"submit\" name=\"submit\" value=\""._SUBMIT."\">
				</form>
				</td></tr></table>";
				$output .= "<center><a href=\"user.php?action=newaccount\">"._REGISTER."</a> | ";
				$output .= "<a href=\"user.php?action=lostpassword\">"._LOSTPASSWORD."</a></center>";
				$tpl->assign( "output", $output );
				$tpl->printToScreen();
			}
	}
	else
	{
		include ("header.php");
		$result = mysql_query("SELECT copyright FROM ".$tableprefix."fanfiction_settings");
		$settings = mysql_fetch_array($result);
		//make a new TemplatePower object
		$tpl = new TemplatePower( "skins/$skin/default.tpl" );

		//let TemplatePower do its thing, parsing etc.
		$tpl->prepare();

		//assign a value to {name}
		$tpl->assign( "footer", $settings[copyright] );
		$tpl->assign( "logo", $logo );
		$tpl->assign( "home", $home );
		$tpl->assign( "recent", $recent );
		$tpl->assign( "catslink", $catslink );
		$tpl->assign( "authors", $authors );
		$tpl->assign( "help", $help );
		$tpl->assign( "search", $search );
		$tpl->assign( "login", $login );
		$tpl->assign( "adminarea", $adminarea );
		$tpl->assign( "titles", $titles );
		$tpl->assign( "logout", $logout );
		$output .= "<center><h4>"._USERACCOUNT."</h4></center>";
		$output .= "<table class=\"tblborder\" cellspacing=\"0\" cellpadding=\"4\" align=\"center\">";
		if(($submissionsoff != "1") || (($level == "1") || ($level == "2") || ($level == "3") || ($level == "4"))) 
		{
			$output .= "<tr><td><a href=\"stories.php?action=newstory\">"._ADDNEWSTORY."</a></td></tr>";
			$output .= "<tr><td><a href=\"stories.php?action=addchapter\">"._ADDNEWCHAPTER."</a></td></tr>";
			$output .= "<tr><td><a href=\"stories.php?action=viewstories\">"._EDITDELSTORIES."</a></td></tr>";
		}
		$output .= "<tr><td><a href=\"user.php?action=editbio\">"._EDITPERSONAL."</a></td></tr>";
		if(($imageupload == "1") && ($submissionsoff != "1"))
			$output .= "<tr><td><a href=\"user.php?action=manageimages\">"._MANAGEIMAGES."</a></td></tr>";
		if($reviewsallowed == "1")
			$output .= "<tr><td><a href=\"user.php?action=viewreviews\">"._VIEWREVIEWS."</a></td></tr>";
		if($favorites == "1")
			$output .= "<tr><td><a href=\"user.php?action=manfaves\">"._MANAGEFAVORITES."</a></td></tr>";	
		$output .= "<tr><td><a href=\"user.php?action=logout\">"._LOGOUT."</a></td></tr>";
		$output .= "</table>";
		$tpl->assign( "output", $output );

		$tpl->printToScreen();
	}
}

function editbio($useruid, $submit, $email, $penname, $realname, $bio, $website, $image, $password, $password2, $newreviews, $skinnew, $uid, $carry)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $imageupload, $userpenname, $useruid;
	include ("header.php");
	$result = mysql_query("SELECT copyright FROM ".$tableprefix."fanfiction_settings");
	$settings = mysql_fetch_array($result);

	//make a new TemplatePower object
	$tpl = new TemplatePower( "skins/$skin/default.tpl" );

	//let TemplatePower do its thing, parsing etc.
	$tpl->prepare();

	//assign a value to {name}
	$tpl->assign( "footer", $settings[copyright] );
	$tpl->assign( "logo", $logo );
	$tpl->assign( "home", $home );
	$tpl->assign( "recent", $recent );
	$tpl->assign( "catslink", $catslink );
	$tpl->assign( "authors", $authors );
	$tpl->assign( "help", $help );
	$tpl->assign( "search", $search );
	$tpl->assign( "login", $login );
	$tpl->assign( "adminarea", $adminarea );
	$tpl->assign( "titles", $titles );
	$tpl->assign( "logout", $logout );
	$output .= "<center><h4>"._EDITPERSONAL."</h4></center>";
	if(($_SESSION['loggedin'] == "1") || ($_SESSION['adminloggedin'] == "1"))
	{
		if($submit)
		{
			if($newreviews == "on")
				$newreviews = "1";
			else
				$newreviews = "0";
				
			if($carry == "on")
				$carry = "1";
			else
				$carry = "0";	
			if((!$email) && ($_SESSION['adminloggedin'] != "1"))
			{
				$output .= "<center>"._EMAILREQUIRED."</center>";
			}
			else
			{
				if(($password) || ($password2))
				{
					if($password == $password2)
					{
						$encryppassword = md5($password);
						mysql_query("UPDATE ".$tableprefix."fanfiction_authors SET realname='$realname', password='$encryppassword', email='$email', website='$website', bio='$bio', image='$image', newreviews='$newreviews', userskin='$skinnew', carry='$carry' WHERE uid = '$uid'");
						$output .= "<center>"._PERSONALUPDATED."</center>";
					}
					else
					{
						$output .= "<center>"._PASSWORDTWICE."</center>";
					}
				}
				else
				{
					mysql_query("UPDATE ".$tableprefix."fanfiction_authors SET realname='$realname', email='$email', website='$website', bio='$bio', image='$image', newreviews='$newreviews', userskin='$skinnew', carry='$carry' WHERE uid = '$uid'");
					$output .= "<center>"._PERSONALUPDATED."</center>";
				}
			}



		}
		else
		{
			if(($_SESSION['adminloggedin'] == "1") && (isset($uid)))
				$result = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_authors WHERE uid = '$uid'");
			else
				$result = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_authors WHERE uid = '$useruid'");
			$user = mysql_fetch_array($result);
			$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"user.php?action=editbio\">
		 	<table align=\"center\" width=\"400\">
		 	<tr><td width=\"150\">"._PENNAME.":</td><td>$user[penname]</td></tr>
		 	<tr><td>"._REALNAME.":</td><td><INPUT name=\"realname\" value=\"$user[realname]\"></td></tr>
		 	<tr><td>"._EMAIL.":<font color=\"red\">*</font></td><td><INPUT name=\"email\" value=\"$user[email]\"></td></tr>
		 	<tr><td>"._WEBSITE.":</td><td><INPUT name=\"website\" value=\"$user[website]\"></td></tr>
		 	<tr><td>"._BIO.":</td><td><TEXTAREA name=\"bio\" cols=\"50\" rows=\"6\">$user[bio]</TEXTAREA></td></tr>";
		 	if($imageupload == "1")
		 		$output .= "<tr><td>"._IMAGE.":</td><td><INPUT name=\"image\" value=\"$user[image]\"></td></tr>";

		 	$output .= "<tr><td>"._PASSWORD.":</td><td><INPUT name=\"password\" value=\"\" type=\"password\"> <INPUT name=\"password2\" value=\"\" type=\"password\"></td></tr>";
		 	$output .= "<tr><td>"._CONTACTREVIEWS.":</td><td><INPUT name=\"newreviews\" type=\"checkbox\"";
		 	if($user[newreviews] == "1")
		 		$output .= " checked";
		 	$output .= "></td></tr>";
		 	$output .= "<tr><td>"._CARRYOVER.":</td><td><INPUT name=\"carry\" type=\"checkbox\"";
		 	if($user[carry] == "1")
		 		$output .= " checked";
		 	$output .= "></td></tr>";
		 	$output .= "<tr><td>"._SKIN.":</td><td><select name=\"skinnew\">";

			$folder = "skins";

			$directory = opendir("$folder");
			while($filename = readdir($directory))
			{
				if($filename=="." or $filename=="..") continue;
				$output .= "<option value=\"$filename\"";
				if($user[userskin] == $filename)
					$output .= " selected";
				$output .= ">$filename</option>";

			}
			closedir($directory);
			$output .= "</select></td></tr>";
		 	$output .= "<tr><td colspan=\"2\"><INPUT type=\"hidden\" name=\"uid\" value=\"$user[uid]\"><INPUT type=\"submit\" name=\"submit\" value=\""._SUBMIT."\">";
		 	if($_SESSION['adminloggedin'] == "1")
		 	{
			 	$output .= " [<a href=\"admin.php?action=deleteuser&uid=$uid\">"._DELETE."</a>]";
		 	}
		 	$output .= "</form></td></tr>";
			$output .= "<tr><td colspan=\"2\"><font color=\"red\">*</font> "._REQUIREDFIELDS1."</td></tr></table>";
		}
	}
	else
	{
		$output .= "<center>"._PLEASELOGIN."";
	}

	$tpl->assign( "output", $output );
	$tpl->printToScreen();

}

function manageimages($delete, $upload, $submit, $upfile)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $imageupload, $userpenname, $imageheight, $imagewidth;
	include ("header.php");
	$result = mysql_query("SELECT copyright FROM ".$tableprefix."fanfiction_settings");
	$settings = mysql_fetch_array($result);

	//make a new TemplatePower object
	  $tpl = new TemplatePower( "skins/$skin/default.tpl" );

	 //let TemplatePower do its thing, parsing etc.
	  $tpl->prepare();

	 //assign a value to {name}
	  $tpl->assign( "footer", $settings[copyright] );
	  $tpl->assign( "logo", $logo );
	  $tpl->assign( "home", $home );
	  $tpl->assign( "recent", $recent );
	  $tpl->assign( "catslink", $catslink );
	  $tpl->assign( "authors", $authors );
	  $tpl->assign( "help", $help );
	  $tpl->assign( "search", $search );
	  $tpl->assign( "login", $login );
	  $tpl->assign( "adminarea", $adminarea );
	  $tpl->assign( "titles", $titles );
	  $tpl->assign( "logout", $logout );
	  $output .= "<center><h4>"._MANAGEIMAGES."</h4></center>";
	if($_SESSION['loggedin'] == "1")
	{
		if($upload)
		{
			if($submit)
			{
				$safe = ini_get('safe_mode');

				$upfile_name = $_FILES['upfile']['name'];

				$dir = "stories/$userpenname/images";

				// if no file was uploaded
				if (($_FILES['upfile']['name'] == "")) 
				{
				   $output .= "<center>"._NOFILE."</center>";
					$tpl->assign( "output", $output );
					$tpl->printToScreen();
					return;
				}

				$image_info = getimagesize($upfile);
				   $width = $image_info[0];
				   $height = $image_info[1];
				   $filetype = $image_info[2];

				if(($filetype != "1") && ($filetype != "2"))
				{
					$output .= "<center>"._BADIMAGE."</center>";
					$tpl->assign( "output", $output );
					$tpl->printToScreen();
					return;
				}

				if(($width > $imagewidth) || ($height > $imageheight))
				{
					$output .= "<center>"._IMAGETOOBIG."</center>";
					$tpl->assign( "output", $output );
					$tpl->printToScreen();
					return;
				}

				// if a file with that name already exists on server
				if (file_exists("$dir/$upfile_name")) {
				   $output .= "<center>"._FILEEXISTS."</center>";
				}
				else
				{

				// upload file
				   if (is_uploaded_file($upfile)) 
				   {
					  if(($safe == "1") || ($safe == "On"))
					  {
						  move_uploaded_file($_FILES['upfile']['tmp_name'], "$dir/$upfile_name"); 
					  }
					  else
					  {
				      	  copy($_FILES['upfile']['tmp_name'], "$dir/$upfile_name");
			      	  }
				      $output .= "<center>"._FILEUPLOADED."</center>";
				   }

				}

			}
			else
			{
				$output .= "<form method=\"post\" action=\"user.php?action=manageimages&upload=upload\" ENCTYPE=\"multipart/form-data\">";

				$output .= "<table align=\"center\"><tr><td>"._IMAGE.":</td><td><input type=\"file\" name=\"upfile\"></td></tr><tr><td colspan=\"2\" align=\"center\"><input name=\"submit\" type=\"submit\" value=\"upload\"></td></tr></table></form></center>";

			}
		}
		else if($delete)
		{
			$folder = "stories/$userpenname/images";
			unlink($folder ."/". $delete);
			$output .= "<center>"._IMAGEDELETED."";
		}
		else
		{
			$output .= "<b><a href=\"user.php?action=manageimages&upload=upload\">"._UPLOADIMAGE."</a></b><br><br>";

			$folder1 = "stories/$userpenname";
			if(!file_exists($folder1))
			{
				mkdir($folder1, 0755);
				chmod($folder1, 0777);
			}

			$folder = "stories/$userpenname/images";
			if (!file_exists($folder))
			{
				mkdir($folder, 0755);
				chmod($folder, 0777);
			}

			$directory = opendir("$folder");
			while($filename = readdir($directory))
			{
				if($filename=="." or $filename=="..") continue;
				$output .= "<a href=\"javascript:myopen('$folder/$filename','windowName','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=$imagewidth,height=$imageheight')\">$filename</a> [<a href=\"user.php?action=manageimages&delete=$filename\">"._DELETE."</a>]<br>";
				$output .= ""._IMAGECODE.": &#60;img src=\"$folder/$filename\"&#62;<br><br>";
			}
		}
	}
	else
	{
		$output .= "<center>"._PLEASELOGIN."";
	}

	$tpl->assign( "output", $output );
	$tpl->printToScreen();

}

function newaccount($submit, $penname, $email, $bio, $realname, $website)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $imageupload, $sitename, $skin, $siteemail;
	include ("header.php");
	$result = mysql_query("SELECT copyright FROM ".$tableprefix."fanfiction_settings");
	$settings = mysql_fetch_array($result);

	//make a new TemplatePower object
	  $tpl = new TemplatePower( "skins/$skin/default.tpl" );

	 //let TemplatePower do its thing, parsing etc.
	  $tpl->prepare();

	 //assign a value to {name}
	  $tpl->assign( "footer", $settings[copyright] );
	  $tpl->assign( "logo", $logo );
	  $tpl->assign( "home", $home );
	  $tpl->assign( "recent", $recent );
	  $tpl->assign( "catslink", $catslink );
	  $tpl->assign( "authors", $authors );
	  $tpl->assign( "help", $help );
	  $tpl->assign( "search", $search );
	  $tpl->assign( "login", $login );
	  $tpl->assign( "adminarea", $adminarea );
	  $tpl->assign( "titles", $titles );
	  $tpl->assign( "logout", $logout );

	  $output .= "<center><h4>"._NEWACCOUNT."</h4></center>";
	  if($submit)
	  {
		  if((!$penname) || (!$email))
		  {
			  $output .= "<center>"._PENEMAILREQUIRED."</center>";
		  }
		  else
		  {
			if(preg_match("!^[a-z0-9_ ]{3,30}$!i", $penname))
			{

				$result = mysql_query("SELECT penname FROM ".$tableprefix."fanfiction_authors WHERE penname = '$penname'");
				$testname = mysql_fetch_array($result);
				$result2 = mysql_query("SELECT email FROM ".$tableprefix."fanfiction_authors WHERE email = '$email'");
				$testemail = mysql_fetch_array($result2);

				if(($testname[penname] == "") && ($testemail[email] == ""))
				{
				 	mt_srand((double)microtime() * 1000000);
					$charset = '23456789' . 'abcdefghijkmnpqrstuvwxyz' . 'ABCDEFGHJKLMNPQRSTUVWXYZ';
					$pass = random_string($charset, 10);
					$encryppass = md5($pass);
					mysql_query("INSERT INTO ".$tableprefix."fanfiction_authors (penname, realname, bio, email, website, date, password, userskin) VALUES ('$penname', '$realname', '$bio', '$email', '$website', now(), '$encryppass', '$skin')");

					$subject = "Welcome to $sitename";
					$mailtext = "Hello, you or someone using your email has signed you up at $sitename. Your login and password are below:\n\nLogin: $penname\nPassword: $pass\n\nIt is recommended that you go to Your Account and change the password to something easier for you to remember.";
					$headers .= "From: $siteemail<$siteemail>\n";
					$headers .= "X-Sender: <$siteemail>\n";
					$headers .= "X-Mailer: PHP\n"; //mailer
					$headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal
					$headers .= "Return-Path: <$siteemail>\n";
					
					mail($email, $subject, $mailtext, $headers);

					$output .= "<center>"._SIGNUPTHANKS."</center>";
				}
				else if($testname[penname] != "")
				{
					$output .= "<center>"._PENNAMEINUSE."</center>";
				}
				else if($testemail[email] != "")
				{
					$output .= "<center>This email address has already been used to sign up for an account. If you've lost your password, please generate a new one by using the <a href=\"user.php?action=lostpassword\">lost password</a> feature.</center>";
				}
			}
			else
			{
				$output .= ""._BADUSERNAME."";
			}

		}
	  }
	  else
	  {
		$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"user.php?action=newaccount\">
		<table align=\"center\" width=\"275\">
		<tr><td>"._PENNAME.":<font color=\"red\">*</font></td><td><INPUT name=\"penname\"></td></tr>
		<tr><td>"._REALNAME.":</td><td><INPUT name=\"realname\" value=\"$user[realname]\"></td></tr>
		<tr><td>"._EMAIL.":<font color=\"red\">*</font></td><td><INPUT name=\"email\" value=\"$user[email]\"></td></tr>
		<tr><td>"._WEBSITE.":</td><td><INPUT name=\"website\" value=\"$user[website]\"></td></tr>
		<tr><td>"._BIO.":</td><td><TEXTAREA name=\"bio\" cols=\"50\" rows=\"6\">$user[bio]</TEXTAREA></td></tr>";
		$output .= "<tr><td colspan=\"2\"><INPUT type=\"submit\" name=\"submit\" value=\""._SUBMIT."\"></form></td></tr>";
		$output .= "<tr><td colspan=\"2\"><font color=\"red\">*</font>"._INDICATES."</td></tr></table>";
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();

}

function lostpassword($submit, $email)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $imageupload, $sitename;
	include ("header.php");
	$result = mysql_query("SELECT copyright FROM ".$tableprefix."fanfiction_settings");
	$settings = mysql_fetch_array($result);

	//make a new TemplatePower object
	  $tpl = new TemplatePower( "skins/$skin/default.tpl" );

	 //let TemplatePower do its thing, parsing etc.
	  $tpl->prepare();

	 //assign a value to {name}
	  $tpl->assign( "footer", $settings[copyright] );
	  $tpl->assign( "logo", $logo );
	  $tpl->assign( "home", $home );
	  $tpl->assign( "recent", $recent );
	  $tpl->assign( "catslink", $catslink );
	  $tpl->assign( "authors", $authors );
	  $tpl->assign( "help", $help );
	  $tpl->assign( "search", $search );
	  $tpl->assign( "login", $login );
	  $tpl->assign( "adminarea", $adminarea );
	  $tpl->assign( "titles", $titles );
	  $tpl->assign( "logout", $logout );
	  $output .= "<center><h4>"._LOSTPASSWORD."</h4></center>";

	  if($_POST)
	  {
		  $result = mysql_query("SELECT uid FROM ".$tableprefix."fanfiction_authors WHERE email = '$email'");
		  $uid = mysql_fetch_array($result);
		  if($uid[uid] == "")
		  {
			  $output .= "<center>"._BADEMAIL."</center>";
		  }
		  else
		  {
			mt_srand((double)microtime() * 1000000);
			$charset = '23456789' . 'abcdefghijkmnpqrstuvwxyz' . 'ABCDEFGHJKLMNPQRSTUVWXYZ';
			$pass = random_string($charset, 10);
			$encryppass = md5($pass);
			//$headers = "From: $sitename\n";
			mysql_query("UPDATE ".$tableprefix."fanfiction_authors SET password='$encryppass' WHERE uid = '$uid[uid]'");
			//mail($email, "Welcome to $sitename", "Hello, you appear to have requested a new password for $sitename. Your new password is:\n\nPassword: $pass\n\nIt is recommended that you go to Your Account and change the password to something easier for you to remember.", $headers);
			$subject = "New Password for $sitename";
			$mailtext = "Hello, you appear to have requested a new password for $sitename. Your new password is:\n\nPassword: $pass\n\nIt is recommended that you go to Your Account and change the password to something easier for you to remember.";
			
			$headers .= "From: $siteemail<$siteemail>\n";
			$headers .= "X-Sender: <$siteemail>\n";
			$headers .= "X-Mailer: PHP\n"; //mailer
			$headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal
			$headers .= "Return-Path: <$siteemail>\n";
			
			mail($email, $subject, $mailtext, $headers);

			$output .= "<center>"._PASSWORDSENT."</center>";
		}

	  }
	else
	{
		$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"user.php?action=lostpassword\">
		<table align=\"center\" width=\"300\">
		<tr><td>"._ENTEREMAIL."</td></tr>
		<tr><td><INPUT name=\"email\"><INPUT type=\"submit\" name=\"submit\" value=\""._SUBMIT."\"></form>
		</td></tr></table>";
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();

}

function viewreviews($offset)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $userpenname, $useruid;
	include ("header.php");
	$result = mysql_query("SELECT copyright FROM ".$tableprefix."fanfiction_settings");
	$settings = mysql_fetch_array($result);
	
	//make a new TemplatePower object
	$tpl = new TemplatePower( "skins/$skin/default.tpl" );
	
	//let TemplatePower do its thing, parsing etc.
	$tpl->prepare();
	
	//assign a value to {name}
	$tpl->assign( "footer", $settings[copyright] );
	$tpl->assign( "logo", $logo );
	$tpl->assign( "home", $home );
	$tpl->assign( "recent", $recent );
	$tpl->assign( "catslink", $catslink );
	$tpl->assign( "authors", $authors );
	$tpl->assign( "help", $help );
	$tpl->assign( "search", $search );
	$tpl->assign( "login", $login );
	$tpl->assign( "adminarea", $adminarea );
	$tpl->assign( "titles", $titles );
	$tpl->assign( "logout", $logout );
	$output .= "<center><h4>"._MANAGEREVIEWS."</h4></center>";
	if($_SESSION['loggedin'] == "1")
	{
		if (empty($offset) || $offset < 0)
		{
			$offset=0;
		}
		if (empty($index)) $index=0;
		$limit = 4;

		include ("timefunctions.php");
		
		$count = mysql_query("select count(sid) from ".$tableprefix."fanfiction_stories WHERE uid = '$useruid' AND sid = psid");
		list($numrows)= mysql_fetch_array($count);
		$query = mysql_query("SELECT sid, title FROM ".$tableprefix."fanfiction_stories WHERE uid = '$useruid' AND sid = psid LIMIT $offset,$limit");
		while($stories = mysql_fetch_array($query))
		{
			$output .= "<b>$stories[title]</b><br>";
			$query2 = mysql_query("SELECT *,DATE_FORMAT(date, '$datim')as date FROM ".$tableprefix."fanfiction_reviews WHERE psid = '$stories[sid]'");
			$numreviews = mysql_num_rows($query2);
			if(($numreviews != "0") || ($numreviews != ""))
			{
				while($reviews = mysql_fetch_array($query2))
				{
	
					$output .= "<blockquote><table class=\"tblborder\" cellpadding=\"2\" cellspacing=\"0\" width=\"80%\"><tr><td class=\"tblborder\" width=\"70\">";
					
					if($reviews[member] == "1")
					{
						$query3 = mysql_query("SELECT uid FROM ".$tableprefix."fanfiction_authors WHERE penname = '$reviews[reviewer]'");
						$user = mysql_fetch_array($query3);
						$output .= "<a href=\"viewuser.php?uid=$user[uid]\">$reviews[reviewer]</a>";
					}
					else
					{
						$output .= "$reviews[reviewer]";
					}
					
					$output .= "</td><td class=\"tblborder\">$reviews[review]</td><td class=\"tblborder\" width=\"30\">$reviews[date]</td><td class=\"tblborder\" width=\"30\"><a href=\"user.php?action=revres&rid=$reviews[reviewid]\">"._RESPOND."</a></td></tr></table></blockquote>";
				}
			}
			else
			{
				$output .= "<blockquote>"._NOREVIEWS."</blockquote>";
			}	
			
		}
			$index++; /* Increment the line index by 1 */

		$output .= "<br><center>";
		if ($numrows>$limit) {
		if ($offset>0) {
		$output .= "<a href=\"user.php?action=viewreviews&offset=".($offset-$limit)."\">["._PREVIOUS."]</a> ";
		} else $output .= "["._PREVIOUS."]";
		$totpages=ceil($numrows/$limit);
		$curpage=floor($offset/$limit)+1;
		for ($i=0;$i<$totpages;$i++) {
		if ($i+1!=$curpage) $output .= "<a href=\"user.php?action=viewreviews&offset=".($i*$limit)."\">".($i+1)."</a> ";
		else $output .= ($i+1).' ';
		}
		if ($curpage<$totpages) {
		$output .= "<a href=\"user.php?action=viewreviews&offset=".($offset+$limit)."\">["._NEXT."]</a>";
		} else $output .= "["._NEXT."]";
		}
		
	}
	else
	{
		$output .= "<center>"._PLEASELOGIN."";
	}

	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function revres($rid, $submit, $response)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $userpenname, $useruid;
	include ("header.php");
	$result = mysql_query("SELECT copyright FROM ".$tableprefix."fanfiction_settings");
	$settings = mysql_fetch_array($result);
	
	//make a new TemplatePower object
	$tpl = new TemplatePower( "skins/$skin/default.tpl" );
	
	//let TemplatePower do its thing, parsing etc.
	$tpl->prepare();
	
	//assign a value to {name}
	$tpl->assign( "footer", $settings[copyright] );
	$tpl->assign( "logo", $logo );
	$tpl->assign( "home", $home );
	$tpl->assign( "recent", $recent );
	$tpl->assign( "catslink", $catslink );
	$tpl->assign( "authors", $authors );
	$tpl->assign( "help", $help );
	$tpl->assign( "search", $search );
	$tpl->assign( "login", $login );
	$tpl->assign( "adminarea", $adminarea );
	$tpl->assign( "titles", $titles );
	$tpl->assign( "logout", $logout );
	$output .= "<center><h4>"._MANAGEREVIEWS."</h4></center>";
	if($_SESSION['loggedin'] == "1")
	{
		if($submit)
		{
			$query = mysql_query("SELECT review FROM ".$tableprefix."fanfiction_reviews WHERE reviewid = '$rid'");
			$item = mysql_fetch_array($query);
			$review = addslashes($item[review]);
			$updated = $review . "<br><br><i>"._AUTHORSRESPONSE.": " . $response."</i>";
			mysql_query("UPDATE ".$tableprefix."fanfiction_reviews SET review = '$updated' WHERE reviewid = '$rid'");
			$output .= "<center>"._RESPONSEAPPENDED." <a href=\"user.php?action=viewreviews\">"._BACKTOREVIEWS."</a></center>";
		}
		else
		{
			include ("timefunctions.php");
			$query = mysql_query("SELECT *,DATE_FORMAT(date, '$datim')as date FROM ".$tableprefix."fanfiction_reviews WHERE reviewid = '$rid'");
			$reviews = mysql_fetch_array($query);
			$query2 = mysql_query("SELECT uid FROM ".$tableprefix."fanfiction_stories WHERE sid = '$reviews[sid]'");
			$user = mysql_fetch_array($query2);
			if($useruid == $user[uid])
			{
				$output .= "<table class=\"tblborder\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\"><tr><td class=\"tblborder\" width=\"70\">";
				
				if($reviews[member] == "1")
				{
					$query3 = mysql_query("SELECT uid FROM ".$tableprefix."fanfiction_authors WHERE penname = '$reviews[reviewer]'");
					$user = mysql_fetch_array($query3);
					$output .= "<a href=\"viewuser.php?uid=$user[uid]\">$reviews[reviewer]</a>";
				}
				else
				{
					$output .= "$reviews[reviewer]";
				}
				
				$output .= "</td><td class=\"tblborder\">$reviews[review]</td><td class=\"tblborder\">$reviews[date]</td></tr></table>";
				
				$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"user.php?action=revres\">";
				$output .= "<center>"._RESPONSE.":<br><br><INPUT type=\"hidden\" name=\"rid\" value=\"$rid\"><TEXTAREA name=\"response\" cols=\"40\" rows=\"5\"></TEXTAREA><br><br><INPUT type=\"submit\" name=\"submit\" value=\""._SUBMIT."\"></form>";
			}
			else
			{
				$output .= "<center>"._NOTYOURSTORY."</center>";
			}
		}
	}
	else
	{
		$output .= "<center>"._PLEASELOGIN."";
	}

	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function manfaves($del, $favuid, $sid)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $userpenname;
	include ("header.php");
	$result = mysql_query("SELECT copyright FROM ".$tableprefix."fanfiction_settings");
	$settings = mysql_fetch_array($result);
	
	//make a new TemplatePower object
	$tpl = new TemplatePower( "skins/$skin/default.tpl" );
	
	//let TemplatePower do its thing, parsing etc.
	$tpl->prepare();
	
	//assign a value to {name}
	$tpl->assign( "footer", $settings[copyright] );
	$tpl->assign( "logo", $logo );
	$tpl->assign( "home", $home );
	$tpl->assign( "recent", $recent );
	$tpl->assign( "catslink", $catslink );
	$tpl->assign( "authors", $authors );
	$tpl->assign( "help", $help );
	$tpl->assign( "search", $search );
	$tpl->assign( "login", $login );
	$tpl->assign( "adminarea", $adminarea );
	$tpl->assign( "titles", $titles );
	$tpl->assign( "logout", $logout );
	$output .= "<center><h4>"._MANAGEFAVORITES."</h4></center>";
	if($_SESSION['loggedin'] == "1")
	{
		if(isset($del))
		{
			if(isset($favuid))
			{
				mysql_query("DELETE FROM ".$tableprefix."fanfiction_favauth WHERE uid = '$useruid' AND favuid = '$favuid'");
			}
			else if(isset($sid))
			{
				mysql_query("DELETE FROM ".$tableprefix."fanfiction_favstor WHERE uid = '$useruid' AND sid = '$sid'");
			}
		}
		$output .= "<b>"._FAVORITEAUTHORS."</b><br><br>";
		$gack = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_favauth WHERE uid = '$useruid'");
		$count = 1;
		while($faves = mysql_fetch_array($gack))
		{
			$query5 = mysql_query("SELECT penname FROM ".$tableprefix."fanfiction_authors WHERE uid = '$faves[favuid]'");
			$authors = mysql_fetch_array($query5);
			$output .= "$count. <a href=\"viewuser.php?uid=$faves[favuid]\">$authors[penname]</a>";
			if($_SESSION['loggedin'] == "1")
			{
				$output .= " [<a href=\"user.php?action=manfaves&uid=$useruid&favuid=$faves[favuid]&del=1\">"._REMOVE."</a>]";
			}
			$output .= "<br><br>";
			$count++;
		}
		$output .= "<b>"._FAVORITESTORIES."</b><br><br>";
		$gack2 = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_favstor WHERE uid = '$useruid'");
		$count = 1;
		while($faves = mysql_fetch_array($gack2))
		{
			$query5 = mysql_query("SELECT title FROM ".$tableprefix."fanfiction_stories WHERE sid = '$faves[sid]'");
			$titles = mysql_fetch_array($query5);
			$output .= "$count. <a href=\"viewstory.php?sid=$faves[sid]\">$titles[title]</a>";
			if($_SESSION['loggedin'] == "1")
			{
				$output .= " [<a href=\"user.php?action=manfaves&uid=$useruid&sid=$faves[sid]&del=1\">"._REMOVE."</a>]";
			}
			$output .= "<br><br>";
			$count++;
		}
	}
	else
	{
		$output .= "<center>"._PLEASELOGIN."";
	}

	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function random_char($string)
{
	$length = strlen($string);
	$position = mt_rand(0, $length - 1);
	return($string[$position]);
}

function random_string ($charset_string, $length)
{
	$return_string = random_char($charset_string);
	for ($x = 1; $x < $length; $x++)
	$return_string .= random_char($charset_string);
	return($return_string);
}

function logout()
{
	session_start();
	unset($_SESSION['loggedin']);
	$_SESSION['loggedin'] = 0;
	unset($_SESSION['adminloggedin']);
	$_SESSION['adminloggedin'] = 0;
	header("Location: index.php");
}



switch ($action)
{

	case "editbio":
		editbio($useruid, $submit, $email, $penname, $realname, $bio, $website, $image, $password, $password2, $newreviews, $skinnew, $uid, $carry);
	break;

	case "newaccount":
		newaccount($submit, $penname, $email, $bio, $realname, $website);
	break;

	case "lostpassword":
		lostpassword($submit, $email);
	break;

	case "manageimages":
		manageimages($delete, $upload, $submit, $upfile);
	break;
	
	case "manfaves":
		manfaves($del, $favuid, $sid);
	break;
	
	case "viewreviews":
		viewreviews($offset);
	break;
	
	case "revres":
		revres($rid, $submit, $response);
	break;

	case "logout":
		logout();
	break;

	default:
		main($penname, $password, $submit, $loggedin);
		break;
}

?>