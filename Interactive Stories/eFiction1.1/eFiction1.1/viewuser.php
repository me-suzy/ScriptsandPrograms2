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

function main($uid)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $itemsperpage, $ratings, $favorites, $useruid;
	include ("header.php");
	$result = mysql_query("SELECT copyright FROM ".$tableprefix."fanfiction_settings");
	$settings = mysql_fetch_array($result);

	//make a new TemplatePower object
	$tpl = new TemplatePower( "skins/$skin/user.tpl" );
	$tpl->assignInclude( "storyblock", "./skins/$skin/storyblock.tpl" );

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

	include ("timefunctions.php");
	
	$result2 = mysql_query("SELECT *,DATE_FORMAT(date, '$datum') as date FROM ".$tableprefix."fanfiction_authors WHERE uid = '$uid'");
	$userinfo = mysql_fetch_array($result2);

	$output = "<br><table width=\"100%\" class=\"tblborder\" cellspacing=\"0\" cellpadding=\"2\">";
	$output .= "<tr><td class=\"tblborder\" width=\"100\"><b>"._PENNAME.":</b></td><td class=\"tblborder\">$userinfo[penname]";

	if($userinfo[email] != "")
	$output .= " [<a href=\"viewuser.php?action=contact&uid=$uid\">"._CONTACT."</a>]";
	if(($favorites == "1") && (($_SESSION['loggedin'] == "1")))
	{
		$output .= " [<a href=\"viewuser.php?uid=$useruid&action=favauth&favuid=$uid\">"._ADDTOFAVES."</a>]";
	}
	$output .= "</td></tr>";
	$output .= "<tr><td class=\"tblborder\"><b>"._MEMBERSINCE.":</b></td><td class=\"tblborder\">$userinfo[date]</td></tr>";
	if($userinfo[realname] != "")
		$output .= "<tr><td class=\"tblborder\"><b>"._REALNAME.":</b></td><td class=\"tblborder\">$userinfo[realname]</td></tr>";
	if($userinfo[website] != "")
		$output .= "<tr><td class=\"tblborder\"><b>"._WEBSITE.":</b></td><td class=\"tblborder\"><a href=\"$userinfo[website]\" target=\"_blank\">$userinfo[website]</a></td></tr>";
	if($userinfo[bio] != "")
	{
		if($userinfo[image] != "")
			$output .= "<tr><td class=\"tblborder\" width=\"10%\"><img src=\"$userinfo[image]\"></td><td class=\"tblborder\" valign=\"top\">";
		else
			$output .= "<tr><td class=\"tblborder\" colspan=\"2\">";
		$bio = nl2br($userinfo[bio]);	
		$output .= "$bio</td></tr>";
	}
	else
	{
		if($userinfo[image] != "")
			$output .= "<tr><td class=\"tblborder\" width=\"10%\"><img src=\"$userinfo[image]\"></td><td class=\"tblborder\" valign=\"top\">&nbsp;</td></tr>";
	}
	$output .= "</table>";
	if($favorites == "1")
	{
		$output .= "<br><table width=\"100%\" class=\"tblborder\" cellpadding=\"2\" cellspacing=\"0\"><tr><td class=\"tblborder\"><b><a href=\"viewuser.php?uid=$userinfo[uid]\" class=\"tbllink\">"._STORIESBY." $userinfo[penname]</a></b></td><td class=\"tblbackground\"><b><a class=\"tbllink\" href=\"viewuser.php?action=favstor&uid=$userinfo[uid]\">"._FAVORITESTORIES."</a></b></td><td class=\"tblbackground\"><b><a class=\"tbllink\" href=\"viewuser.php?action=favauth&uid=$userinfo[uid]\">"._FAVORITEAUTHORS."</a></b></td></tr></table>";
	}
	
	
	$tpl->assign( "output", $output );

	$result3 = mysql_query("SELECT title, psid, sid, catid, numreviews, uid, summary, rid, gid, wid, charid, completed,wordcount,DATE_FORMAT(date, '%m/%d/%Y')as date, DATE_FORMAT(updated, '%m/%d/%Y') as updated FROM ".$tableprefix."fanfiction_stories WHERE uid = '$uid' AND sid = psid AND validated = '1' ORDER BY title ");
	while($stories = mysql_fetch_array($result3))
	{

		$tpl->newBlock("storyblock");

		include("storyblock.php");

		$summary = stripslashes($stories[summary]);
		$tpl->assign("title"   , $title );
		$tpl->assign("author"   , $author );
		$tpl->assign("summary"   , $summary );
		$tpl->assign("rating"   , $stories[rid] );
		$tpl->assign("genres"   , $stories[gid] );
		$tpl->assign("warnings"   , $warning );
		$tpl->assign("characters"   , $stories[charid] );
		$tpl->assign("category"   , $categorylinks );
		$tpl->assign("completed"   , $completed );
		$tpl->assign("roundrobin"   , $roundrobin );
		$tpl->assign("ratingpics"   , $ratingpics );
		$tpl->assign("reviews"   , $reviews );
		$tpl->assign("numchapters"   , $numchapters[chapters] );
		$tpl->assign("updated"   , $stories[updated] );
		$tpl->assign("published"   , $stories[date] );
		$tpl->assign("wordcount"   , $numchapters[words] );
		$tpl->assign("numreviews"   , $numreviews );
	}

	$tpl->printToScreen();

}

function contact($uid, $submit, $subject, $email, $comments)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $sitename;
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
	$output .= "<center><h4>"._CONTACTAUTHOR."</h4></center>";

	if($submit)
	{

		if (!ereg("^[-!#$%&'*+\./0-9=?A-Z^_`a-z{|}~]+@{1}([a-z0-9]{1}[a-z0-9-]*[a-z0-9]{1}\.{1})+([a-z]+\.){0,1}([a-z]){2,4}$", trim($email)))
		{
			$output .= ""._EMAILREQUIRED."";
		}
		else
		{

		$result2 = mysql_query("SELECT email FROM ".$tableprefix."fanfiction_authors WHERE uid='$uid'");
		$userinfo = mysql_fetch_array($result2);


		$subject = stripslashes($subject);
		$comments = stripslashes($comments);
		
		$headers .= "From: $email<$email>\n";
		$headers .= "X-Sender: <$email>\n";
		$headers .= "X-Mailer: PHP\n"; //mailer
		$headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal
		$headers .= "Return-Path: <$email>\n";
		
		mail($userinfo[email], $subject, $comments, $headers);
		$output .= "<center>"._COMMENTSSENT."</center>";
	}
	}
	else
	{
		$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"viewuser.php?action=contact\">
		<table align=\"center\">
		<tr><td colspan=\"2\">"._REQUIREDFIELDS3."</td></tr>
		<tr><td>"._YOUREMAIL.":</td><td><INPUT name=\"email\"></td></tr>
		<tr><td>"._SUBJECT.":</td><td><INPUT name=\"subject\"></td></tr>
		<tr><td>"._COMMENTS.":</td><td><TEXTAREA name=\"comments\" cols=\"50\" rows=\"6\"></TEXTAREA></td></tr>
		<tr><td colspan=\"2\"><INPUT name=\"submit\" type=\"submit\" value=\"submit\"><INPUT type=\"hidden\" name=\"uid\" value=\"$uid\"></td></tr></table></form>";
	}


	$tpl->assign( "output", $output );
	$tpl->printToScreen();

}

function favstor($uid, $sid)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $itemsperpage, $ratings, $favorites;
	include ("header.php");
	$result = mysql_query("SELECT copyright FROM ".$tableprefix."fanfiction_settings");
	$settings = mysql_fetch_array($result);

	//make a new TemplatePower object
	$tpl = new TemplatePower( "skins/$skin/user.tpl" );
	$tpl->assignInclude( "storyblock", "./skins/$skin/storyblock.tpl" );

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

	if(isset($sid) && ($favorites == "1") && (($_SESSION['loggedin'] == "1")))
	{
		$check = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_favstor WHERE uid = '$useruid' AND sid = '$sid'");
		$check2 = mysql_num_rows($check);
		if($check2 == "")
			mysql_query("INSERT INTO ".$tableprefix."fanfiction_favstor (uid, sid) VALUES ('$useruid', '$sid')");
	}
	
	include ("timefunctions.php");
	
	$result2 = mysql_query("SELECT *,DATE_FORMAT(date, '$datum') as date FROM ".$tableprefix."fanfiction_authors WHERE uid = '$uid'");
	$userinfo = mysql_fetch_array($result2);

	$output = "<br><table width=\"100%\" class=\"tblborder\" cellspacing=\"0\" cellpadding=\"2\">";
	$output .= "<tr><td class=\"tblborder\" width=\"100\"><b>"._PENNAME.":</b></td><td class=\"tblborder\">$userinfo[penname]";

	if($userinfo[email] != "")
	$output .= " [<a href=\"viewuser.php?action=contact&uid=$uid\">"._CONTACT."</a>]";
	if(($favorites == "1") && (($_SESSION['loggedin'] == "1")))
	{
		$output .= " [<a href=\"viewuser.php?uid=$useruid&action=favauth&favuid=$uid\">"._ADDTOFAVES."</a>]";
	}
	$output .= "</td></tr>";
	$output .= "<tr><td class=\"tblborder\"><b>"._MEMBERSINCE.":</b></td><td class=\"tblborder\">$userinfo[date]</td></tr>";
	if($userinfo[realname] != "")
		$output .= "<tr><td class=\"tblborder\"><b>"._REALNAME.":</b></td><td class=\"tblborder\">$userinfo[realname]</td></tr>";
	if($userinfo[website] != "")
		$output .= "<tr><td class=\"tblborder\"><b>"._WEBSITE.":</b></td><td class=\"tblborder\"><a href=\"$userinfo[website]\" target=\"_blank\">$userinfo[website]</a></td></tr>";
	if($userinfo[bio] != "")
	{
		if($userinfo[image] != "")
			$output .= "<tr><td class=\"tblborder\" width=\"10%\"><img src=\"$userinfo[image]\"></td><td class=\"tblborder\" valign=\"top\">";
		else
			$output .= "<tr><td class=\"tblborder\" colspan=\"2\">";
		$bio = nl2br($userinfo[bio]);
		$output .= "$bio</td></tr>";
	}
	else
	{
		if($userinfo[image] != "")
			$output .= "<tr><td class=\"tblborder\" width=\"10%\"><img src=\"$userinfo[image]\"></td><td class=\"tblborder\" valign=\"top\">&nbsp;</td></tr>";
	}
	$output .= "</table>";
	if($favorites == "1")
	{
		$output .= "<br><table width=\"100%\" class=\"tblborder\" cellpadding=\"2\" cellspacing=\"0\"><tr><td class=\"tblbackground\"><b><a href=\"viewuser.php?uid=$userinfo[uid]\" class=\"tbllink\">"._STORIESBY." $userinfo[penname]</a></b></td><td class=\"tblborder\"><b><a class=\"tbllink\" href=\"viewuser.php?action=favstor&uid=$userinfo[uid]\">Favorite Stories</a></b></td><td class=\"tblbackground\"><b><a class=\"tbllink\" href=\"viewuser.php?action=favauth&uid=$userinfo[uid]\">Favorite Authors</a></b></td></tr></table>";
	}
	
	
	$tpl->assign( "output", $output );
	
	$gack = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_favstor WHERE uid = '$uid'");
	while($faves = mysql_fetch_array($gack))
	{
		$result3 = mysql_query("SELECT title, psid, sid, catid, numreviews, uid, summary, rid, gid, wid, charid, completed,wordcount,DATE_FORMAT(date, '%m/%d/%Y')as date, DATE_FORMAT(updated, '%m/%d/%Y') as updated FROM ".$tableprefix."fanfiction_stories WHERE sid = '$faves[sid]' ORDER BY title ");
		$stories = mysql_fetch_array($result3);
		
		$tpl->newBlock("storyblock");

		include("storyblock.php");

		$summary = stripslashes($stories[summary]);
		$tpl->assign("title"   , $title );
		$tpl->assign("author"   , $author );
		$tpl->assign("summary"   , $summary );
		$tpl->assign("rating"   , $stories[rid] );
		$tpl->assign("genres"   , $stories[gid] );
		$tpl->assign("warnings"   , $warning );
		$tpl->assign("characters"   , $stories[charid] );
		$tpl->assign("category"   , $categorylinks );
		$tpl->assign("completed"   , $completed );
		$tpl->assign("roundrobin"   , $roundrobin );
		$tpl->assign("ratingpics"   , $ratingpics );
		$tpl->assign("reviews"   , $reviews );
		$tpl->assign("numchapters"   , $numchapters[chapters] );
		$tpl->assign("updated"   , $stories[updated] );
		$tpl->assign("published"   , $stories[date] );
		$tpl->assign("wordcount"   , $numchapters[words] );
		$tpl->assign("numreviews"   , $numreviews );
	}

	$tpl->printToScreen();

}

function favauth($uid, $favuid)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $itemsperpage, $ratings, $favorites, $useruid;
	include ("header.php");
	$result = mysql_query("SELECT copyright FROM ".$tableprefix."fanfiction_settings");
	$settings = mysql_fetch_array($result);

	//make a new TemplatePower object
	$tpl = new TemplatePower( "skins/$skin/user.tpl" );
	$tpl->assignInclude( "storyblock", "./skins/$skin/storyblock.tpl" );

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

	include ("timefunctions.php");
	
	if(isset($favuid) && ($favorites == "1") && (($_SESSION['loggedin'] == "1")))
	{
		$check = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_favauth WHERE uid = '$useruid' AND favuid = '$favuid'");
		$check2 = mysql_num_rows($check);
		if($check2 == "")
			mysql_query("INSERT INTO ".$tableprefix."fanfiction_favauth (uid, favuid) VALUES ('$useruid', '$favuid')");
	}
	
	$result2 = mysql_query("SELECT *,DATE_FORMAT(date, '$datum') as date FROM ".$tableprefix."fanfiction_authors WHERE uid = '$uid'");
	$userinfo = mysql_fetch_array($result2);

	$output = "<br><table width=\"100%\" class=\"tblborder\" cellspacing=\"0\" cellpadding=\"2\">";
	$output .= "<tr><td class=\"tblborder\" width=\"100\"><b>"._PENNAME.":</b></td><td class=\"tblborder\">$userinfo[penname]";

	if($userinfo[email] != "")
	$output .= " [<a href=\"viewuser.php?action=contact&uid=$uid\">"._CONTACT."</a>]";
	if(($favorites == "1") && (($_SESSION['loggedin'] == "1")))
	{
		$output .= " [<a href=\"viewuser.php?uid=$useruid&action=favauth&favuid=$uid\">"._ADDTOFAVES."</a>]";
	}
	$output .= "</td></tr>";
	$output .= "<tr><td class=\"tblborder\"><b>"._MEMBERSINCE.":</b></td><td class=\"tblborder\">$userinfo[date]</td></tr>";
	if($userinfo[realname] != "")
		$output .= "<tr><td class=\"tblborder\"><b>"._REALNAME.":</b></td><td class=\"tblborder\">$userinfo[realname]</td></tr>";
	if($userinfo[website] != "")
		$output .= "<tr><td class=\"tblborder\"><b>"._WEBSITE.":</b></td><td class=\"tblborder\"><a href=\"$userinfo[website]\" target=\"_blank\">$userinfo[website]</a></td></tr>";
	if($userinfo[bio] != "")
	{
		if($userinfo[image] != "")
			$output .= "<tr><td class=\"tblborder\" width=\"10%\"><img src=\"$userinfo[image]\"></td><td class=\"tblborder\" valign=\"top\">";
		else
			$output .= "<tr><td class=\"tblborder\" colspan=\"2\">";
		$bio = nl2br($userinfo[bio]);
		$output .= "$bio</td></tr>";
	}
	else
	{
		if($userinfo[image] != "")
			$output .= "<tr><td class=\"tblborder\" width=\"10%\"><img src=\"$userinfo[image]\"></td><td class=\"tblborder\" valign=\"top\">&nbsp;</td></tr>";
	}
	$output .= "</table>";
	if($favorites == "1")
	{
		$output .= "<br><table width=\"100%\" class=\"tblborder\" cellpadding=\"2\" cellspacing=\"0\"><tr><td class=\"tblbackground\"><b><a href=\"viewuser.php?uid=$userinfo[uid]\" class=\"tbllink\">"._STORIESBY." $userinfo[penname]</a></b></td><td class=\"tblbackground\"><b><a class=\"tbllink\" href=\"viewuser.php?action=favstor&uid=$userinfo[uid]\">Favorite Stories</a></b></td><td class=\"tblborder\"><b><a class=\"tbllink\" href=\"viewuser.php?action=favauth&uid=$userinfo[uid]\">Favorite Authors</a></b></td></tr></table><br>";
	}
	
	$gack = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_favauth WHERE uid = '$uid'");
	$count = 1;
	while($faves = mysql_fetch_array($gack))
	{
		$query5 = mysql_query("SELECT penname FROM ".$tableprefix."fanfiction_authors WHERE uid = '$faves[favuid]'");
		$authors = mysql_fetch_array($query5);
		$output .= "$count. <a href=\"viewuser.php?uid=$faves[favuid]\">$authors[penname]</a><br><br>";
		$count++;
	}

	$tpl->assign( "output", $output );
	
	$tpl->printToScreen();
}

switch ($action)
{

	case "contact":
		contact($uid, $submit, $subject, $email, $comments);
	break;
	
	case "favstor":
		favstor($uid, $sid);
	break;
	
	case "favauth":
		favauth($uid, $favuid);
	break;

	default:
		main($uid);
		break;
}

?>