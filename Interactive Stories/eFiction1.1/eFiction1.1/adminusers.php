<?php

// ----------------------------------------------------------------------
// eFiction
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

function authors($submit, $uid, $penname, $realname, $email, $website)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $level;
	include ("adminheader.php");
	$tpl = new TemplatePower( "skins/$skin/default.tpl" );
	$tpl->prepare();
	$tpl->assign( "footer", footer() );
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
	if (($_SESSION['adminloggedin'] != "1") || (($level != '1') && ($level != '2')))
	{
		$output .= ""._NOTALLOWED."<BR><BR>";
	}
	else
	{
		$output .= adminmenu();
		$output .= "<center><h4>"._AUTHMAINT."</h4></center>";
		if ($submit == ""._VALIDATEAUTHOR."")
		{
			mysql_query("UPDATE ".$tableprefix."fanfiction_authors SET validated = '1' WHERE uid = '$uid'");
			$output .= "<center>"._BTAUTHMAINT."</center>";
		}
		else if ($submit == ""._ADDAUTHOR."")
		{
			if(preg_match("!^[a-z0-9_ ]{3,30}$!i", $penname))
			{
				mysql_query("INSERT INTO ".$tableprefix."fanfiction_authors (penname, realname, email, website, admincreated, date) VALUES ('$penname', '$realname', '$email', '$website', '1', now())");
				$output .= "<center>"._BTAUTHMAINT."</center>";
			}
			else
			{
				$output .= "<center>"._BADCHAR."</center>";
			}
		}
		else
		{
			$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"admin.php?action=authors\">
					<table align=\"center\"><tr><td colspan=\"2\">
					<b>"._ADDAUTHOR." <A HREF=\"javascript:n_window('docs/adminmanual.htm#addauthor');\">[?]</A></b>
					</td></tr><tr><td>
					"._PENNAME.":
					</td><td>
					<INPUT name=\"penname\">
					</td></tr><tr><td>
					"._REALNAME.":
					</td><td>
					<INPUT name=\"realname\">
					</td></tr><tr><td>
					"._EMAIL.":
					</td><td>
					<INPUT name=\"email\">
					</td></tr><tr><td>
					"._WEBSITE.":
					</td><td>
					<INPUT name=\"website\">
					</td></tr><tr><td colspan=\"2\">
					<INPUT type=\"submit\" value=\""._ADDAUTHOR."\" name=\"submit\">
					</form></td></tr></table>";
			$output .= "<hr>";

			$result = mysql_query("SELECT uid, penname FROM ".$tableprefix."fanfiction_authors WHERE admincreated = '1'");
			$output .= "<table align=\"center\"><tr><td><b>"._INPUTBYADMIN." <A HREF=\"javascript:n_window('docs/adminmanual.htm#authorinput');\">[?]</A></b></td></tr>";
			while ($authresults = mysql_fetch_array($result))
			{
				$output .= "<tr><td>$authresults[penname]";
				$output .= "</td><td><a href=\"admin.php?action=authorrelease&amp;uid=$authresults[uid]\">"._RELEASE."</td></tr>";
			}

			$output .= "</table><br><br><hr>";

			$result = mysql_query("SELECT uid, penname FROM ".$tableprefix."fanfiction_authors WHERE validated = '0' AND admincreated = '0' ORDER BY penname");
			$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"admin.php?action=authors\"><table align=\"center\"><tr><td colspan=\"2\"><b>"._VALAUTHOR." <A HREF=\"javascript:n_window('docs/adminmanual.htm#validateauthor');\">[?]</A></b></td></tr><tr><td><select name=\"uid\">";
			while ($authresults = mysql_fetch_array($result))
				{
					$output .= "<option value=\"$authresults[uid]\">$authresults[penname]</option>";
				}
			$output .= "</select></td><td>";
			$output .= "<INPUT type=\"submit\" value=\""._VALIDATEAUTHOR."\" name=\"submit\"></td></tr></form></table>";
			$output .= "<br><br><hr>";

			$result = mysql_query("SELECT uid, penname FROM ".$tableprefix."fanfiction_authors WHERE validated = '1' ORDER BY penname");
			$output .= "<table align=\"center\"><tr><td colspan=\"2\"><b>"._CURRENTVALAUTHORS." <A HREF=\"javascript:n_window('docs/adminmanual.htm#validatedauthors');\">[?]</A></b></td></tr>";
			while ($authresults = mysql_fetch_array($result))
			{
				$output .= "<tr><td>$authresults[penname]";
				$output .= "</td><td><a href=\"admin.php?action=authordelete&amp;uid=$authresults[uid]\">"._REMOVE."</a></td></tr>";
			}
			$output .= "</table><br><br><hr>";

			$query = mysql_query("SELECT uid, penname FROM ".$tableprefix."fanfiction_authors ORDER BY penname");

			$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"user.php?action=editbio\"><table align=\"center\"><tr><td colspan=\"2\"><b>"._AUTHORS."</b></td></tr><tr><td><select name=\"uid\">";
			while ($authresults = mysql_fetch_array($query))
				{
					$output .= "<option value=\"$authresults[uid]\">$authresults[penname]</option>";
				}
			$output .= "</select></td><td>";

			$output .= "<INPUT type=\"submit\" value=\""._EDIT."\" name=\"edit\">";


			$output .= "</form></td></tr></table>";
		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function authordelete($delete, $uid)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $level;
	include ("adminheader.php");
	$tpl = new TemplatePower( "skins/$skin/default.tpl" );
	$tpl->prepare();
	$tpl->assign( "footer", footer() );
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
	if (($_SESSION['adminloggedin'] != "1") || (($level != '1') && ($level != '2')))
	{
		$output .= ""._NOTALLOWED."<BR><BR>";
	}
	else
	{
		$output .= adminmenu();
		$output .= "<center><h4>"._DELETEVAL."</h4></center>";
		if($delete == "yes")
		{
			mysql_query("UPDATE ".$tableprefix."fanfiction_authors SET validated = '0' WHERE uid = '$uid'");
			$output .= "<center>"._VALDELETED."</center>";
		}
		else if ($delete == "no")
		{
			$output .= "<center>"._VALNOTDELETED."</center>";
		}
		else
		{
			$output .= "<center>"._VALSUREDEL."<br><br>";
			$output .= "[ <a href=\"admin.php?action=authordelete&delete=yes&uid=$uid\">"._YES."</a> | <a href=\"admin.php?action=authordelete&delete=no\">"._NO."</a> ]</center>";
		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function authorrelease($delete, $uid)
{
	global $tableprefix, $logo, $adminarea, $adminemail, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $level, $sitename;
	include ("adminheader.php");
	$tpl = new TemplatePower( "skins/$skin/default.tpl" );
	$tpl->prepare();
	$tpl->assign( "footer", footer() );
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
	if (($_SESSION['adminloggedin'] != "1") || (($level != '1') && ($level != '2')))
	{
		$output .= ""._NOTALLOWED."<BR><BR>";
	}
	else
	{
		$output .= adminmenu();
		$output .= "<center><h4>"._RELEASEAUTHOR."</h4></center>";
		if($delete == "yes")
		{
			mysql_query("UPDATE ".$tableprefix."fanfiction_authors SET admincreated = '0' WHERE uid = '$uid'");
			$emailquery = mysql_query("SELECT email FROM ".$tableprefix."fanfiction_authors WHERE uid = '$uid'");
			$email = mysql_fetch_array($emailquery);
			$output .= "<center>The author has been removed from the admin created list, and an e-mail has been sent to them with their temporary password. <a href=\"admin.php?action=authors\">Back to Authors Maintenance</a></center>";
			mt_srand((double)microtime() * 1000000);
			$charset = '23456789' . 'abcdefghijkmnpqrstuvwxyz' . 'ABCDEFGHJKLMNPQRSTUVWXYZ';
			$pass = random_string($charset, 10);
			$encryppass = md5($pass);
			//$headers = "From: $sitename\n";
			mysql_query("UPDATE ".$tableprefix."fanfiction_authors SET password='$encryppass' WHERE uid = '$uid'");
			
			$subject = "Welcome to $sitename";
			
			$letter = "Hello, the admins of $sitename have opened your account so that you may add/edit/delete your own stories. Your new password is:\n\nPassword: $pass\n\nIt is recommended that you go to Your Account and change the password to something easier for you to remember.";
			
			$headers .= "From: $adminemail<$adminemail>\n";
			$headers .= "X-Sender: <$adminemail>\n";
			$headers .= "X-Mailer: PHP\n"; //mailer
			$headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal
			$headers .= "Return-Path: <$adminemail>\n";
			
			mail($email[email], $subject, $letter, $headers);

		}
		else if ($delete == "no")
		{
			$output .= "<center>"._AUTHORNOTRELEASED."</center>";
		}
		else
		{
			$output .= "<center>"._AUTHORSUREREL."<br><br>";
			$output .= "[ <a href=\"admin.php?action=authorrelease&delete=yes&uid=$uid\">"._YES."</a> | <a href=\"admin.php?action=authorrelease&delete=no\">"._NO."</a> ]</center>";
		}
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

function admins($submit, $admin, $password, $email, $level, $contact, $uid, $categories)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $adlevel;
	include ("adminheader.php");
	$tpl = new TemplatePower( "skins/$skin/default.tpl" );
	$tpl->prepare();
	$tpl->assign( "footer", footer() );
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
	if (($_SESSION['adminloggedin'] != "1") || (($level != '1')))
	{
		$output .= ""._NOTALLOWED."<BR><BR>";
	}
	else
	{
		$output .= adminmenu();
		$output .= "<center><h4>"._ADMINMAINT."</h4></center>";
		if ($submit)
		{
			if($categories != "")
			{
				$count = 0;
				foreach ($categories as $cat)
				{
					if($count != 0)
						$catstring .= ",";
					$catstring = $catstring . $cat;
					$count++;
				}
			}
			else
			{
				$catstring = "0";
			}
			
			if($contact == "on")
				$contact = "1";
			else
				$contact = "0";
			mysql_query("UPDATE ".$tableprefix."fanfiction_authors SET level = '$adlevel', contact = '$contact', categories = '$catstring' WHERE uid = '$uid'");
			$output .= "<center>"._ADMINCREATED."</center>";
		}
		else
		{
			$query = mysql_query("SELECT penname, uid FROM ".$tableprefix."fanfiction_authors WHERE level = '0' ORDER BY penname");
			$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"admin.php?action=admins\">";
			$output .= "<table align=\"center\"><tr><td>"._PENNAME."</td><td>";
			$output .= "<select name=\"uid\">";
			while($users = mysql_fetch_array($query))
			{
				$output .= "<option value=\"$users[uid]\">$users[penname]</option>";
			}
			$output .= "</select></td></tr>";
			$output .= "<tr><td>"._LEVEL.": <A HREF=\"javascript:n_window('docs/adminmanual.htm#adminlevel');\">[?]</A>
					</td><td>
					<select name=\"adlevel\">
					<option value=\"1\">1</option>
					<option value=\"2\">2</option>
					<option value=\"3\">3</option>
					<option value=\"4\">4</option>
					</select>
					</td></tr>";
			$query2 = mysql_query("SELECT * from ".$tableprefix."fanfiction_categories ORDER BY displayorder");	
			$output .= "<tr><td>"._CATOVERSEE.": <A HREF=\"javascript:n_window('docs/adminmanual.htm#catoversee');\">[?]</A>
					</td><td>
					<select name=\"categories[]\" multiple>
					<option value=\"0\" selected>"._ALLCATEGORIES."</option>";
					while($categoryselect = mysql_fetch_array($query2))
					{
						$space = "";
						if ($categoryselect[parentcatid] != "-1")
						{
							$parent = mysql_query("select leveldown,category from ".$tableprefix."fanfiction_categories WHERE catid='$categoryselect[parentcatid]'");
							$parentresult = mysql_fetch_array($parent);
		
							for ($count = 0; $count <= $categoryselect[leveldown]; $count++)
							{
							    $space .= "&nbsp;&nbsp;&nbsp;";
							}
						}
						else
							$space = "";
						$output .= "<option value=\"".$categoryselect[displayorder]."\">";
						$output .= "$categoryselect[displayorder]. $space-$categoryselect[category]";
						$output .= "</option>";		
					}
					$output .= "</select></td></tr>";
			$output .= "<tr><td>
					"._CONTACT.": <A HREF=\"javascript:n_window('docs/adminmanual.htm#admincontact');\">[?]</A>
					</td><td>
					<INPUT type=\"checkbox\" name=\"contact\">
					</td></tr><tr><td colspan=\"2\">
					<INPUT type=\"submit\" value=\""._ADDADMIN."\" name=\"submit\">
					</form></td></tr></table>";

			$output .= "<hr>";

			$result = mysql_query("SELECT penname, uid FROM ".$tableprefix."fanfiction_authors WHERE level != '0' ORDER BY penname");
			$output .= "<table align=\"center\"><tr><td><b>"._CURRENTADMINS."</b></td></tr>";
			while ($adminresults = mysql_fetch_array($result))
			{
				$output .= "<tr><td>$adminresults[penname]";
				$output .= "</td><td><a href=\"admin.php?action=adminedit&amp;uid=$adminresults[uid]\">"._EDIT."</a></td></tr>";
			}

			$output .= "</table>";
		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function adminedit($submit, $admin, $password, $password2, $email, $adlevel, $contact, $uid, $categories)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $level;
	include ("adminheader.php");
	$tpl = new TemplatePower( "skins/$skin/default.tpl" );
	$tpl->prepare();
	$tpl->assign( "footer", footer() );
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
	if (($_SESSION['adminloggedin'] != "1") || (($level != '1')))
	{
		$output .= ""._NOTALLOWED."<BR><BR>";
	}
	else
	{
		$output .= adminmenu();
		$output .= "<center><h4>"._EDITADMIN."</h4></center>";
		if ($submit)
		{

			if($categories != "")
			{
				$count = 0;
				foreach($_POST['categories'] as $cat)
				{
					if($count != 0)
						$catstring .= ",";
					$catstring = $catstring . $cat;
					$count++;
				}
			}
			else
			{
				$catstring = "0";
			}
			if($contact == "on")
				$contact = "1";
			else
				$contact = "0";
			if($adlevel == "0")
			{
				$contact = "0";
			}
			mysql_query("UPDATE ".$tableprefix."fanfiction_authors SET level = '$adlevel', contact = '$contact', categories = '$catstring' WHERE uid = '$uid'");
			$output .= "<center>"._BTADMINMAINT."</center>";
		}
		else
		{
			$result = mysql_query("SELECT * from ".$tableprefix."fanfiction_authors WHERE uid = '$uid'");
			$adminresults = mysql_fetch_array($result);
			$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"admin.php?action=adminedit\">
					<table align=\"center\"><tr><td>
					"._PENNAME.":
					</td><td>
					$adminresults[penname]
					</td></tr><tr><td>
					"._LEVEL.":
					</td><td>
					<select name=\"adlevel\">
					<option value=\"1\"";
					if($adminresults[level] == "1")
						$output .= " selected";
					$output .= ">1</option>
					<option value=\"2\"";
					if($adminresults[level] == "2")
						$output .= " selected";
					$output .= ">2</option>
					<option value=\"3\"";
					if($adminresults[level] == "3")
						$output .= " selected";
					$output .= ">3</option>";
					$output .= "<option value=\"4\"";
					if($adminresults[level] == "4")
						$output .= " selected";
					$output .= ">4</option>";
					$output .= "<option value=\"0\">"._REMOVEADMIN."</option>";
					$output .= "</select>
					</td></tr>";
					$query2 = mysql_query("SELECT * from ".$tableprefix."fanfiction_categories ORDER BY displayorder");	
					$output .= "<tr><td>"._CATOVERSEE.": <A HREF=\"javascript:n_window('docs/adminmanual.htm#catoversee');\">[?]</A>
					</td><td>
					<select name=\"categories[]\" multiple>
					<option value=\"0\">"._ALLCATEGORIES."</option>";
					while($categoryselect = mysql_fetch_array($query2))
					{
						$array = split(",", $adminresults[categories]);
						$space = "";
						if ($categoryselect[parentcatid] != "-1")
						{
							$parent = mysql_query("select leveldown,category from ".$tableprefix."fanfiction_categories WHERE catid='$categoryselect[parentcatid]'");
							$parentresult = mysql_fetch_array($parent);
		
							for ($count = 0; $count <= $categoryselect[leveldown]; $count++)
							{
							    $space .= "&nbsp;&nbsp;&nbsp;";
							}
						}
						else
							$space = "";
						$output .= "<option value=\"".$categoryselect[displayorder]."\"";
						if(in_array($categoryselect[catid], $array))
							$output .= " selected";
						$output .= ">";
						$output .= "$categoryselect[displayorder]. $space-$categoryselect[category]";
						$output .= "</option>";		
					}
					$output .= "</select></td></tr>";
					$output .= "<tr><td>
					"._CONTACT.":
					</td><td>
					<INPUT type=\"checkbox\" name=\"contact\"";
					if($adminresults[contact] == "1")
						$output .= " checked";
					$output .= ">
					</td></tr><tr><td colspan=\"2\">
					<INPUT type=\"hidden\" name=\"uid\" value=\"$uid\">
					<INPUT type=\"submit\" value=\""._EDITADMIN."\" name=\"submit\">
					</form></td></tr></table>";

		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function admindelete($delete, $aid)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $level;
	include ("adminheader.php");
	$tpl = new TemplatePower( "skins/$skin/default.tpl" );
	$tpl->prepare();
	$tpl->assign( "footer", footer() );
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
	if (($_SESSION['adminloggedin'] != "1") || (($level != '1')))
	{
		$output .= ""._NOTALLOWED."<BR><BR>";
	}
	else
	{
		$output .= adminmenu();
		$output .= "<center><h4>"._DELADMIN."</h4></center>";
		if($delete == "yes")
		{
			mysql_query("DELETE FROM ".$tableprefix."fanfiction_admins where aid = '$aid'");
			$output .= "<center>"._BTADMINMAINT."</center>";
		}
		else if ($delete == "no")
		{
			$output .= "<center>"._ADMINNOTDEL."</center>";
		}
		else
		{
			$output .= "<center>"._ADMINSUREDEL."<br><br>";
			$output .= "[ <a href=\"admin.php?action=admindelete&delete=yes&aid=$aid\">"._YES."</a> | <a href=\"admin.php?action=admindelete&delete=no\">"._NO."</a> ]</center>";
		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function mailusers($submit, $subject, $mailtext)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $level, $siteemail;
	include ("adminheader.php");
	$tpl = new TemplatePower( "skins/$skin/default.tpl" );
	$tpl->prepare();
	$tpl->assign( "footer", footer() );
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
	if (($_SESSION['adminloggedin'] != "1") || (($level != '1') && ($level != '2')))
	{
		$output .= ""._NOTALLOWED."<BR><BR>";
	}
	else
	{
		$output .= adminmenu();
		$output .= "<center><h4>"._MAILUSERS."</h4></center>";
		if($submit)
		{
			$query = mysql_query("SELECT email FROM ".$tableprefix."fanfiction_authors");
			$subject = stripslashes($subject);
			$mailtext = stripslashes($mailtext);
			while($result = mysql_fetch_array($query))
			{
				
				$headers .= "From: $siteemail<$siteemail>\n";
				$headers .= "X-Sender: <$siteemail>\n";
				$headers .= "X-Mailer: PHP\n"; //mailer
				$headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal
				$headers .= "Return-Path: <$siteemail>\n";
				
				mail($result[email], $subject, $mailtext, $headers);
			}
			$output .= "<center>"._EMAILSSENT."</center>";
		}
		else
		{
			$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"admin.php?action=mailusers\">
			<table align=\"center\"><tr><td>"._SUBJECT.": </td><td><INPUT name=\"subject\"></td></tr>
			<tr><td>"._TEXT.": </td><td><TEXTAREA name=\"mailtext\" cols=\"40\" rows=\"6\"></TEXTAREA></td></tr>
			<tr><td><INPUT type=\"submit\" name=\"submit\" value=\""._SUBMIT."\"></td></tr></table></form>";
			$output .= "<br><br><center>"._EMAILWARNING."</center><br>";
		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function deleteuser($uid, $delete)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $level, $store;
	include ("adminheader.php");
	$tpl = new TemplatePower( "skins/$skin/default.tpl" );
	$tpl->prepare();
	$tpl->assign( "footer", footer() );
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
	if (($_SESSION['adminloggedin'] != "1") || (($level != '1') && ($level != '2') && ($level != '3')))
	{
		$output .= ""._NOTALLOWED."<BR><BR>";
	}
	else
	{
		$output .= adminmenu();
		$output .= "<center><h4>"._DELETEUSER."</h4></center>";
		if($delete == "yes")
		{

			if($store == "files")
			{
				$authorquery = mysql_query("SELECT penname FROM ".$tableprefix."fanfiction_authors WHERE uid = '$uid'");
				$author = mysql_fetch_array($authorquery);
				$query = mysql_query("SELECT sid FROM ".$tableprefix."fanfiction_stories WHERE uid = '$uid'");
				while($result = mysql_fetch_array($query))
				{
					unlink("stories/$author[penname]/$result[sid]");
				}
			}
			mysql_query("DELETE FROM ".$tableprefix."fanfiction_authors where uid = '$uid'");
			mysql_query("DELETE FROM ".$tableprefix."fanfiction_stories where uid = '$uid'");
			mysql_query("DELETE FROM ".$tableprefix."fanfiction_favauth WHERE uid = '$uid' OR favuid = '$uid'");
			$output .= "<center>"._USERDELETED."</center>";
		}
		else if ($delete == "no")
		{
			$output .= "<center>"._USERNOTDEL."</center>";
		}
		else
		{
			$output .= "<center>"._USERSUREDEL."<br><br>";
			$output .= "[ <a href=\"admin.php?action=deleteuser&delete=yes&uid=$uid\">"._YES."</a> | <a href=\"admin.php?action=deleteuser&delete=no\">"._NO."</a> ]</center>";
		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

?>