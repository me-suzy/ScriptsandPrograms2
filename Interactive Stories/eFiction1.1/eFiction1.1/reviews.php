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

function main($sid, $offset, $index, $a)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $anonreviews, $itemsperpage;
	include ("header.php");
	$result = mysql_query("SELECT copyright FROM ".$tableprefix."fanfiction_settings");
	$settings = mysql_fetch_array($result);

	//make a new TemplatePower object
	$tpl = new TemplatePower( "skins/$skin/reviews.tpl" );

	 //let TemplatePower do its thing, parsing etc.
	$tpl->prepare();

	 //assign a value to {name}
	$tpl->assign( "footer", $settings[copyright] );
	$tpl->assign( "logo", $logo );
	$tpl->assign( "home", $home );
	$tpl->assign( "recent", $recent ); $tpl->assign( "catslink", $catslink );
	$tpl->assign( "authors", $authors );
	$tpl->assign( "help", $help );
	$tpl->assign( "search", $search );
	$tpl->assign( "login", $login );
	$tpl->assign( "adminarea", $adminarea );
	$tpl->assign( "titles", $titles );
	$tpl->assign( "logout", $logout );

	$storyquery = mysql_query("SELECT title,sid,psid FROM ".$tableprefix."fanfiction_stories WHERE sid = '$sid'");
	$story = mysql_fetch_array($storyquery);

	$tpl->assign( "title", $story[title] );

	if(($anonreviews == "1") || ($_SESSION['loggedin'] == "1"))
		$reviewslink = "<a href=\"reviews.php?action=addreview&sid=$story[sid]\">"._SUBMITREVIEW."</a>";
	else
		$reviewslink = "";

	$tpl->assign( "reviewslink", $reviewslink );

	if (empty($offset) || $offset < 0)
	{
		$offset=0;
	}
	if (empty($index)) $index=0;
	$limit = $itemsperpage;

	include ("timefunctions.php");
	
	if($a == "1")
	{
		$query = mysql_query("SELECT *,DATE_FORMAT(date, '$datum - %h:%i%p') as date FROM ".$tableprefix."fanfiction_reviews WHERE sid = '$sid' ORDER BY reviewid DESC LIMIT $offset,$limit");
		$count =  mysql_query("SELECT count(reviewid) FROM ".$tableprefix."fanfiction_reviews WHERE sid = '$sid'");
	}
	else
	{

		$query = mysql_query("SELECT *,DATE_FORMAT(date, '$datum - %h:%i%p') as date FROM ".$tableprefix."fanfiction_reviews WHERE psid = '$sid' ORDER BY reviewid DESC LIMIT $offset,$limit");
		$count =  mysql_query("SELECT count(reviewid) FROM ".$tableprefix."fanfiction_reviews WHERE psid = '$sid'");
	}
	list($numrows)= mysql_fetch_array($count);
	$index++;
	while($reviews = mysql_fetch_array($query))
	{
		if($_SESSION['adminloggedin'] == "1")
		{
			$adminlink = "[<a href=\"reviews.php?action=deletereview&reviewid=$reviews[reviewid]\">"._DELETE."</a>]";
		}
		if($reviews[member] != "0")
		{
			$uidquery = mysql_query("SELECT uid FROM ".$tableprefix."fanfiction_authors WHERE penname = '$reviews[reviewer]'");
			$uid = mysql_fetch_array($uidquery);
			$reviewer = "<a href=\"viewuser.php?uid=$uid[uid]\">$reviews[reviewer]</a>";
			$member = ""._SIGNED." $adminlink";
			}
		else
		{
			$reviewer = "$reviews[reviewer]";
			$member = ""._ANONYMOUS." $adminlink";
		}

		$query1 = mysql_query("SELECT chapter,inorder FROM ".$tableprefix."fanfiction_stories WHERE sid = '$reviews[sid]'");
		$chapter = mysql_fetch_array($query1);
		$tpl->newBlock("reviewsblock");
		$tpl->assign("reviewer"   , $reviewer );
		$tpl->assign("review"   , $reviews[review] );
		$tpl->assign("reviewdate", $reviews[date] );
		$tpl->assign("rating", $reviews[rating] );
		$tpl->assign("member", $member );
		$tpl->assign("chapter", $chapter[chapter] );
		$tpl->assign("chapternumber", ($chapter[inorder] + 1) );
	}
	$tpl->gotoBlock( "_ROOT" );

	$index++; /* Increment the line index by 1 */

	if ($numrows>$limit)
	{
		if ($offset>0)
		{
			$reviewpagelinks .= '<a href="reviews.php?sid='.$sid.'&a='.$a.'&offset='.($offset-$limit).'">['._PREVIOUS.']</a> ';
		}
		else
			$reviewpagelinks .=  '['._PREVIOUS.'] ';
		$totpages=ceil($numrows/$limit);
		$curpage=floor($offset/$limit)+1;
		for ($i=0;$i<$totpages;$i++)
		{
			if ($i+1!=$curpage)
				$reviewpagelinks .=  '<a href="reviews.php?sid='.$sid.'&a='.$a.'&offset='.($i*$limit).'">'.($i+1).'</a> ';
			else $reviewpagelinks .=  ($i+1).' ';
		}
		if ($curpage<$totpages)
		{
			$reviewpagelinks .=  '<a href="reviews.php?sid='.$sid.'&a='.$a.'&offset='.($offset+$limit).'">['._NEXT.']</a>';
		}
		else
			$reviewpagelinks .=  '['._NEXT.']';
	}

	$tpl->assign("reviewpagelinks"   , $reviewpagelinks );

	$jumpmenu .= "<form style=\"margin:0\" enctype=\"multipart/form-data\" method=\"post\" action=\"viewstory.php\">";
	$jumpmenu .= "<select name=\"sid\" onChange=\"if (this.selectedIndex >0) window.location=this.options[this.selectedIndex].value\">";
	//$jumpmenu .= "<option value=\"\">"._REVIEWS."</option>";
	$jumpmenu .= "<option value=\"reviews.php?sid=$story[psid]\"";
	if($a == "")
		$jumpmenu .= " selected";
	$jumpmenu .= ">"._VIEWALLREVIEWS."</option>";
	$query = mysql_query("SELECT inorder, chapter, sid FROM ".$tableprefix."fanfiction_stories WHERE psid = '$story[psid]'");
	while($chapters = mysql_fetch_array($query))
	{
		$jumpmenu .= "<option value=\"reviews.php?sid=$chapters[sid]&a=1\"";

		if(("$sid" == "$chapters[sid]") && ($a == "1"))
			$jumpmenu .= " selected";

		$jumpmenu .= ">"._REVIEWSFOR." " . ($chapters[inorder] + 1) .". $chapters[chapter]</option>";
	}
	$jumpmenu .= "</select></form>";

	$tpl->assign("jumpmenu"   , $jumpmenu );

	$tpl->printToScreen();

}

function addreview($sid, $submit, $review, $reviewer, $member, $rating, $psid)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $useruid, $userpenname, $anonreviews, $ratings, $siteemail, $sitename, $url;
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
	$output .= "<center><h4>"._REVIEWSTORY."</h4></center>";
	if($submit)
	{
		if(($reviewer == "") || ($review == ""))
		{
			$output .= "<center>"._MISSINGINFO."</center>";
		}
		else
		{
			mysql_query("INSERT INTO ".$tableprefix."fanfiction_reviews (sid, reviewer, review, rating, date, member, psid) VALUES ('$sid', '$reviewer', '$review', '$rating', now(), '$member', '$psid')");
			$output .= "<center>"._REVTHANKYOU." <a href=\"viewstory.php?sid=$sid\">"._BACKTOSTORY."</a></center>";
	
			mysql_query("UPDATe ".$tableprefix."fanfiction_stories SET numreviews = (numreviews + 1) WHERE sid = '$sid'");
	
			$uidquery = mysql_query("SELECT uid,title FROM ".$tableprefix."fanfiction_stories WHERE sid = '$sid'");
			$uidresult = mysql_fetch_array($uidquery);
	
			$mailquery= mysql_query("SELECT newreviews,email FROM ".$tableprefix."fanfiction_authors WHERE uid = '$uidresult[uid]'");
			$mail = mysql_fetch_array($mailquery);
	
			if($mail[newreviews] == "1")
			{
				//$siteemail = "rivka@danger-room.net";
				//$siteurl = "http://www.rivkashome.com/fanfiction";
				$subject = "New Review at $sitename";
				
				$headers .= "From: $siteemail<$siteemail>\n";
				$headers .= "X-Sender: <$siteemail>\n";
				$headers .= "X-Mailer: PHP\n"; //mailer
				$headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal
				$headers .= "Return-Path: <$siteemail>\n";
				
				$mailtext = "Hello,
  You have received a new review at $sitename for your story\r\n
$uidresult[title]. You can view your new review at $url/reviews.php?sid=$sid\r\n

If you no longer wish to receive e-mails such as this, please go to your account\r\n
on $sitename, and edit your profile.";
				
				mail($mail[email], $subject, $mailtext, $headers);
	
			}
		}
	}
	else
	{
		if(($anonreviews == "0") && ($userpenname == ""))
		{
			$output .= "<center>"._MUSTBEMEMBER."</center>";
		}
		else
		{
			$query = mysql_query("SELECT psid FROM ".$tableprefix."fanfiction_stories WHERE sid = '$sid'");
			$result = mysql_fetch_array($query);
			$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"reviews.php?action=addreview\">
			<table align=\"center\"><tr><td>
			Name:</td><td>";
			if($userpenname != "")
				$output .= "$userpenname <INPUT type=\"hidden\" name=\"reviewer\" value=\"$userpenname\"><INPUT type=\"hidden\" name=\"member\" value=\"$useruid\">";
			else
				$output .= "<INPUT name=\"reviewer\">";
			$output .= "</td></tr>
			<tr><td>"._REVIEW."</td><td>
			<textarea name=\"review\" cols=\"40\" rows=\"5\"></textarea></td></tr>";
			if($ratings == "2")
			{
				$output .= "<tr><td>"._OPINION."</td><td><select name=\"rating\">
				<option value=\"1\">"._LIKED."</option><option value=\"0\">"._DISLIKED."</option></select></td></tr>";
			}
			if($ratings == "1")
			{
				$output .= "<tr><td>"._RATING."?</td><td><select name=\"rating\">
				<option value=\"10\">10</option>
				<option value=\"9\">9</option>
				<option value=\"8\">8</option>
				<option value=\"7\">7</option>
				<option value=\"6\">6</option>
				<option value=\"5\">5</option>
				<option value=\"4\">4</option>
				<option value=\"3\">3</option>
				<option value=\"2\">2</option>
				<option value=\"1\">1</option>
				</select>
				</td></tr>";
			}
			$output .= "<tr><td><INPUT type=\"hidden\" name=\"sid\" value=\"$sid\"><INPUT type=\"hidden\" name=\"psid\" value=\"$result[psid]\"><INPUT name=\"submit\" value=\""._SUBMIT."\" type=\"submit\"></td></tr></table></form>";
		}

	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function deletereview($reviewid, $delete)
{
	global $tableprefix, $numcats, $adminarea, $logo, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $level;
	include ("header.php");
	$result = mysql_query("SELECT copyright FROM ".$tableprefix."fanfiction_settings");
	$settings = mysql_fetch_array($result);
	$tpl = new TemplatePower( "skins/$skin/default.tpl" );
	$tpl->prepare();
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
	if (($_SESSION['adminloggedin'] != "1") || (($level != '1') && ($level != '2') && ($level != '3')))
	{
		$output .= ""._NOTALLOWED."<BR><BR>";
	}
	else
	{
		$output .= "<center><h4>"._DELETEREVIEW."</h4></center>";
		if($delete == "yes")
		{
			$query = mysql_query("SELECT sid FROM ".$tableprefix."fanfiction_reviews WHERE reviewid = '$reviewid'");
			$result = mysql_fetch_array($query);
			mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET numreviews = (numreviews - 1) WHERE sid = '$result[sid]'");
			mysql_query("DELETE FROM ".$tableprefix."fanfiction_reviews WHERE reviewid = '$reviewid'");
			$output .= "<center>"._REVIEWDELETED."</center>";

		}
		else if ($delete == "no")
		{
			$output .= "<center>"._REVIEWNOTDELETED."</center>";
		}
		else
		{
			$output .= "<center>"._SUREDELETE."<BR><BR>";
			$output .= "[ <a href=\"reviews.php?action=deletereview&delete=yes&reviewid=$reviewid\">"._YES."</a> | <a href=\"reviews.php?action=deletereview&delete=no\">"._NO."</a> ]</center>";
		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}


switch ($action)
{

	case "addreview":
		addreview($sid, $submit, $review, $reviewer, $member, $rating, $psid);
	break;

	case "deletereview":
		deletereview($reviewid, $delete);
	break;

	default:
		main($sid, $offset, $index, $a);
		break;
}

?>