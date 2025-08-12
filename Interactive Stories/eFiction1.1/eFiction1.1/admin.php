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

function admin_main()
{
	global $tableprefix, $logo, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $adminarea, $databasepath;
	session_start();
	include ("adminheader.php");
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
	$output .= "<center><h4>"._ADMINAREA."</h4></center>";

	if($_SESSION['adminloggedin'] != "1")
	{
		$output .= "<center>"._PLEASELOGIN."</center>";
	}
	else
	{
		$output .= adminmenu();
		if (file_exists("install.php"))
		{
			$output .= "<br><br><b><center>You have not deleted install.php! Please do so immediately to prevent a security breach.</b></center>";
		}
		$output .= "<br><br><b><center>You are currently running eFiction v.1.1</b></center>";

	}

	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function searchbox($submit, $searchterm, $searchtype, $go, $com, $sid, $inorder, $psid)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $level, $store;
	include ("adminheader.php");
	$tpl = new TemplatePower( "skins/$skin/default.tpl" );
	$tpl->prepare();
	$tpl->assign( "footer", footer() );
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
	if (($_SESSION['adminloggedin'] != "1") || (($level != '1') && ($level != '2') && ($level != '3')))
	{
		$output .= ""._NOTALLOWED."<BR><BR>";
	}
	else
	{
		$output .= adminmenu();
		$output .= "<center><h4>"._SEARCHFORM."</h4></center>";
		if(isset($searchtype))
		{
			if($searchtype == "penname")
			{
				$result = mysql_query("SELECT uid,penname FROM ".$tableprefix."fanfiction_authors WHERE penname LIKE '$searchterm'");
				$author = mysql_fetch_array($result);
				if($author[penname] != "")
				{
					$output .= "<table align=\"center\" width=\"80%\" class=\"tblborder\" cellpadding=\"2\" cellspacing=\"0\">";
					$output .= "<tr><td class=\"tblborder\">$author[penname]</td><td class=\"tblborder\" width=\"100\"><a href=\"user.php?action=editbio&uid=$author[uid]\">"._EDIT."</a> | <a href=\"admin.php?action=deleteuser&uid=$author[uid]\">"._DELETE."</a></td></tr>";
					$output .= "</table><br>";

					if($go == "up")
					{
						$oneabove = $inorder - 1;
						mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET inorder = '$inorder' WHERE psid = '$psid' and inorder = '$oneabove'");
						mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET inorder = '$oneabove' WHERE sid = '$sid'");
					}
					if($go == "down")
					{
						$oneabove = $inorder + 1;
						mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET inorder = '$inorder' WHERE psid = '$psid' and inorder = '$oneabove'");
						mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET inorder = '$oneabove' WHERE sid = '$sid'");
					}

					if($com == "yes")
					{
						mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET completed = 1 WHERE sid = '$sid'");
					}

					if($com == "no")
					{
						mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET completed = 0 WHERE sid = '$sid'");
					}

					$result = mysql_query("SELECT title,sid,completed FROM ".$tableprefix."fanfiction_stories WHERE uid = '$author[uid]' AND psid=sid ORDER BY title");
					$output .= "<table align=\"center\" width=\"80%\" class=\"tblborder\" cellpadding=\"2\" cellspacing=\"0\">";
					while($topstories = mysql_fetch_array($result))
					{
						$output .= "<tr><td class=\"tblborder\"><b>$topstories[title]</b> ("._FIRSTCHAPTER.") "._COMPLETED.": ";
						$output .= " <a href=\"admin.php?action=searchbox&searchtype=$searchtype&searchterm=$searchterm&sid=$topstories[sid]&com=";
						if($topstories[completed] == "1")
							$output .= "no\">"._YES."";
						else
							$output .= "yes\">"._NO."";
						$output .="</a></td><td class=\"tblborder\" colspan=\"2\">&nbsp;</td><td class=\"tblborder\" width=\"165\"><a href=\"stories.php?action=editstory&sid=$topstories[sid]\">"._EDIT."</a> | <a href=\"stories.php?action=deletestory&sid=$topstories[sid]\">"._DELETE."</a>  | <a href=\"admin.php?action=addstory&sid=$topstories[sid]&submit=Select\">"._ADDCHAPTER."</a></td></tr>";
						$result2 = mysql_query("SELECT chapter,sid,psid,inorder FROM ".$tableprefix."fanfiction_stories WHERE psid = '$topstories[sid]' AND psid != sid ORDER BY inorder");
						$numrow = mysql_num_rows($result2);
						while($chapters = mysql_fetch_array($result2))
						{
							$output .= "<tr><td class=\"tblborder\">&nbsp;&nbsp;&nbsp;&nbsp;$chapters[chapter]</td><td class=\"tblborder\" width=\"13\">";
							if($chapters[inorder] != "1")
								$output .= "<a href=\"admin.php?action=searchbox&searchtype=$searchtype&searchterm=$searchterm&go=up&sid=$chapters[sid]&psid=$chapters[psid]&inorder=$chapters[inorder]\"><img src=\"images/arrowup.gif\" width=\"13\" height=\"18\" border=\"0\"></a>";
							else
								$output .= "&nbsp;";
							$output .= "</td><td class=\"tblborder\" width=\"13\">";
							if($chapters[inorder] != "$numrow")
								$output .=  " <a href=\"admin.php?action=searchbox&searchtype=$searchtype&searchterm=$searchterm&go=down&sid=$chapters[sid]&psid=$chapters[psid]&inorder=$chapters[inorder]\"><img src=\"images/arrowdown.gif\" width=\"13\" height=\"18\" border=\"0\"></a>";
							else
								$output .= "&nbsp;";
							$output .= "</td><td class=\"tblborder\" width=\"100\"><a href=\"stories.php?action=editstory&sid=$chapters[sid]\">"._EDIT."</a> | <a href=\"stories.php?action=deletestory&sid=$chapters[sid]\">"._DELETE."</a></td></tr>";
						}
					}
					$output .= "</table>";
				}
				else
					$output .= "<center>"._NORESULTS."</center>";
			}
			if($searchtype == "storytitle")
			{
					if($go == "up")
					{
						$oneabove = $inorder - 1;
						mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET inorder = '$inorder' WHERE psid = '$psid' and inorder = '$oneabove'");
						mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET inorder = '$oneabove' WHERE sid = '$sid'");
					}
					if($go == "down")
					{
						$oneabove = $inorder + 1;
						mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET inorder = '$inorder' WHERE psid = '$psid' and inorder = '$oneabove'");
						mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET inorder = '$oneabove' WHERE sid = '$sid'");
					}

					if($com == "yes")
					{
						mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET completed = 1 WHERE sid = '$sid'");
					}

					if($com == "no")
					{
						mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET completed = 0 WHERE sid = '$sid'");
					}

					$storyquery = mysql_query("SELECT title,sid,completed FROM ".$tableprefix."fanfiction_stories WHERE title LIKE '%$searchterm%' AND psid=sid");
					$numrows = mysql_num_rows($storyquery);
					if($numrows != "")
					{
						$output .= "<table align=\"center\" width=\"80%\" class=\"tblborder\" cellpadding=\"2\" cellspacing=\"0\">";
						while($topstories = mysql_fetch_array($storyquery))
						{
							$output .= "<tr><td class=\"tblborder\"><b>$topstories[title]</b> ("._FIRSTCHAPTER.") "._COMPLETED.": ";
							$output .= " <a href=\"admin.php?action=searchbox&searchtype=$searchtype&searchterm=$searchterm&sid=$topstories[sid]&com=";
							if($topstories[completed] == "1")
								$output .= "no\">"._YES."";
							else
								$output .= "yes\">"._NO."";
							$output .="</a></td><td class=\"tblborder\" colspan=\"2\">&nbsp;</td><td class=\"tblborder\" width=\"165\"><a href=\"stories.php?action=editstory&sid=$topstories[sid]\">"._EDIT."</a> | <a href=\"stories.php?action=deletestory&sid=$topstories[sid]\">"._DELETE."</a>  | <a href=\"admin.php?action=addstory&sid=$topstories[sid]&submit=Select\">"._ADDCHAPTER."</a></td></tr>";
							$result2 = mysql_query("SELECT chapter,sid,psid,inorder FROM ".$tableprefix."fanfiction_stories WHERE psid = '$topstories[sid]' AND psid != sid ORDER BY inorder");
							$numrow = mysql_num_rows($result2);
							while($chapters = mysql_fetch_array($result2))
							{
								$output .= "<tr><td class=\"tblborder\">&nbsp;&nbsp;&nbsp;&nbsp;$chapters[chapter]</td><td class=\"tblborder\" width=\"13\">";
								if($chapters[inorder] != "1")
									$output .= "<a href=\"admin.php?action=searchbox&searchtype=$searchtype&searchterm=$searchterm&go=up&sid=$chapters[sid]&psid=$chapters[psid]&inorder=$chapters[inorder]\"><img src=\"images/arrowup.gif\" width=\"13\" height=\"18\" border=\"0\"></a>";
								else
									$output .= "&nbsp;";
								$output .= "</td><td class=\"tblborder\" width=\"13\">";
								if($chapters[inorder] != "$numrow")
									$output .=  " <a href=\"admin.php?action=searchbox&searchtype=$searchtype&searchterm=$searchterm&go=down&sid=$chapters[sid]&psid=$chapters[psid]&inorder=$chapters[inorder]\"><img src=\"images/arrowdown.gif\" width=\"13\" height=\"18\" border=\"0\"></a>";
								else
									$output .= "&nbsp;";
								$output .= "</td><td class=\"tblborder\" width=\"100\"><a href=\"stories.php?action=editstory&sid=$chapters[sid]\">"._EDIT."</a> | <a href=\"stories.php?action=deletestory&sid=$chapters[sid]\">"._DELETE."</a></td></tr>";
							}
						}
						$output .= "</table>";
					}
					else
						$output .= "<center>"._NORESULTS."</center>";
				}
			if($searchtype == "chaptertitle")
			{
				$result = mysql_query("SELECT title,sid,chapter FROM ".$tableprefix."fanfiction_stories WHERE chapter LIKE '%$searchterm%' ORDER BY title");
				$numrows = mysql_num_rows($result);
				if($numrows != "")
				{
					$output .= "<table align=\"center\" width=\"80%\" class=\"tblborder\" cellpadding=\"2\" cellspacing=\"0\">";
					while($chapters = mysql_fetch_array($result))
					{
						$output .= "<tr><td class=\"tblborder\">$chapters[title]: $chapters[chapter]</td><td class=\"tblborder\" width=\"100\"><a href=\"stories.php?action=editstory&sid=$chapters[sid]\">"._EDIT."</a> | <a href=\"stories.php?action=deletestory&sid=$chapters[sid]\">"._DELETE."</a></td></tr>";
					}
					$output .= "</table><br>";
				}
				else
						$output .= "<center>"._NORESULTS."</center>";
			}
			if($searchtype == "summary")
			{
				$result = mysql_query("SELECT title,sid FROM ".$tableprefix."fanfiction_stories WHERE summary LIKE '%$searchterm%' ORDER BY title");
				$numrows = mysql_num_rows($result);
				if($numrows != "")
					{
					$output .= "<table align=\"center\" width=\"80%\" class=\"tblborder\" cellpadding=\"2\" cellspacing=\"0\">";
					while($topstories = mysql_fetch_array($result))
					{
						$output .= "<tr><td class=\"tblborder\">$topstories[title]</td><td class=\"tblborder\" width=\"100\"><a href=\"stories.php?action=editstory&sid=$topstories[sid]\">"._EDIT."</a> | <a href=\"stories.php?action=deletestory&sid=$topstories[sid]\">"._DELETE."</a></td></tr>";
					}
					$output .= "</table><br>";
				}
				else
					$output .= "<center>"._NORESULTS."</center>";
			}
			if($searchtype == "fulltext")
			{
				$result = mysql_query("SELECT title,sid FROM ".$tableprefix."fanfiction_stories WHERE storytext LIKE '%$searchterm%' ORDER BY title");
				$numrows = mysql_num_rows($result);
				if($numrows != "")
					{
					$output .= "<table align=\"center\" width=\"80%\" class=\"tblborder\" cellpadding=\"2\" cellspacing=\"0\">";
					while($topstories = mysql_fetch_array($result))
					{
						$output .= "<tr><td class=\"tblborder\">$topstories[title]</td><td class=\"tblborder\" width=\"100\"><a href=\"stories.php?action=editstory&sid=$topstories[sid]\">"._EDIT."</a> | <a href=\"stories.php?action=deletestory&sid=$topstories[sid]\">"._DELETE."</a></td></tr>";
					}
					$output .= "</table><br>";
				}
				else
					$output .= "<center>"._NORESULTS."</center>";
			}
		}
		else
		{
			$output .= "<form method=\"post\" enctype=\"multipart/form-data\" action=\"admin.php?action=searchbox\">
			<table align=\"center\"><tr><td>
			<select name=\"searchtype\">
			<option value=\"penname\">"._PENNAME."</option>
			<option value=\"storytitle\">"._STORYTITLE."</option>
			<option value=\"chaptertitle\">"._CHAPTERTITLE."</option>
			<option value=\"summary\">"._SUMMARY."</option>";
			if($store == "mysql")
				$output .= "<option value=\"fulltext\">"._FULLTEXT."</option>";
			$output .= "</select></td><td>
			<INPUT name=\"searchterm\">";
			$output .= "</td><td> <INPUT type=\"submit\" name=\"submit\" value=\""._SUBMIT."\">
			</td></tr></table></form>";
		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}



function settings($submit, $sitenamenew, $slogannew, $urlnew, $storenew, $autovalidatenew, $numcatsnew, $reviewsallowednew, $ratingsnew, $roundrobinsnew, $submissionsoffnew, $anonreviewsnew, $itemsperpagenew, $imageuploadnew, $imagewidthnew, $imageheightnew, $skinnew, $welcome, $rules, $thankyou, $nothankyou, $copyright, $helptext, $siteemailnew, $databasepathnew, $columnsnew, $tableprefixnew, $newscommentsnew, $numupdatednew, $dateformatnew, $favoritesnew, $newsdatenew, $storiespathnew)
{
	global $tableprefix, $adminarea, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $level, $sitename, $slogan, $url, $store, $autovalidate, $numcats, $reviewsallowed, $ratings, $roundrobins, $submissionsoff, $anonreviews, $itemsperpage, $imageupload, $imagewidth, $imageheight, $skin, $siteemail, $databasepath, $columns, $tableprefix, $newscomments, $numupdated, $dateformat, $favorites, $newsdate, $storiespath;
	include ("adminheader.php");
	$tpl = new TemplatePower( "skins/$skin/default.tpl" );
	$tpl->prepare();
	$tpl->assign( "footer", footer() );
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
	if (($_SESSION['adminloggedin'] != "1") || (($level != '1')))
	{
		$output .= ""._NOTALLOWED."<BR><BR>";
	}
	else
	{
		$output .= adminmenu();
		$output .= "<center><h4>"._SETTINGS."</h4></center>";
		if (isset($submit))
		{
			mysql_query("UPDATE ".$tableprefix."fanfiction_settings SET welcome='$welcome', rules='$rules', thankyou='$thankyou', nothankyou='$nothankyou', copyright='$copyright', help='$helptext'");
			save_settings($sitenamenew, $slogannew, $urlnew, $storenew, $autovalidatenew, $numcatsnew, $reviewsallowednew, $ratingsnew, $roundrobinsnew, $submissionsoffnew, $anonreviewsnew, $itemsperpagenew, $imageuploadnew, $imagewidthnew, $imageheightnew, $skinnew, $store, $siteemailnew, $databasepathnew, $columnsnew, $tableprefixnew, $newscommentsnew, $numupdatednew, $dateformatnew, $favoritesnew, $newsdatenew, $storiespathnew);
			$output .= "<center>"._BTSETTINGS."";
		}
		else
		{
			$result = mysql_query("SELECT * from ".$tableprefix."fanfiction_settings");
			$settings = mysql_fetch_array($result);
			$sitename = stripslashes($sitename);
			$slogan = stripslashes($slogan);
			$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"admin.php?action=settings\">
				<table align=\"center\">
				<tr><td>
				"._SITENAME.": <A HREF=\"javascript:n_window('docs/settingshelp.htm#sitename');\">[?]</A>
				</td><td>
				<INPUT name=\"sitenamenew\" value=\"$sitename\">
				</td></tr><tr><td>
				"._SITESLOGAN.": <A HREF=\"javascript:n_window('docs/settingshelp.htm#slogan');\">[?]</A>
				</td><td>
				<INPUT name=\"slogannew\" value=\"$slogan\">
				</td></tr><tr><td>
				"._SITEURL.": <A HREF=\"javascript:n_window('docs/settingshelp.htm#url');\">[?]</A>
				</td><td>
				<INPUT name=\"urlnew\" value=\"$url\">
				</td></tr><tr><td>
				"._ADMINEMAIL.": <A HREF=\"javascript:n_window('docs/settingshelp.htm#siteemail');\">[?]</A>
				</td><td>
				<INPUT name=\"siteemailnew\" value=\"$siteemail\">
				</td></tr><tr><td>
				"._DBPATH.": <A HREF=\"javascript:n_window('docs/settingshelp.htm#databasepath');\">[?]</A>
				</td><td>
				<INPUT name=\"databasepathnew\" value=\"$databasepath\">
				</td></tr><tr><td>
				"._STORIESPATH.": <A HREF=\"javascript:n_window('docs/settingshelp.htm#storiespath');\">[?]</A>
				</td><td>
				<INPUT name=\"storiespathnew\" value=\"$storiespath\">
				</td></tr><tr><td>
				"._TABLEPREFIX.": <A HREF=\"javascript:n_window('docs/settingshelp.htm#tableprefix');\">[?]</A>
				</td><td>
				<INPUT name=\"tableprefixnew\" value=\"$tableprefix\">
				</td></tr><tr><td>
				"._HOWSTORE."?: <A HREF=\"javascript:n_window('docs/settingshelp.htm#storing');\">[?]</A>
				</td><td>
				<select name=\"storenew\" onChange=\"if (this.disabled) this.selectedIndex=0\" disabled>
				<option value=\"files\"";
				if($store == "files")
					$output .= " selected";
				$output .= ">"._FILES."</option>
				<option value=\"mysql\"";
				if($store == "mysql")
					$output .= " selected";
				$output .= ">"._MYSQL."</option>
				</select> <input type=\"checkbox\" name=\"r1\" onClick=\"this.form.storenew.disabled=false\" checked>
				</td></tr><tr><td>
				"._AUTOVALIDATE.": <A HREF=\"javascript:n_window('docs/settingshelp.htm#validate');\">[?]</A>
				</td><td>
				<select name=\"autovalidatenew\">
				<option value=\"1\"";
				if ($autovalidate == "1")
					$output .= "selected";
				$output .= ">"._YES."</option>
				<option value=\"0\"";
				if ($autovalidate == "0")
					$output .= "selected";
				$output .= ">"._NO."</option>
				</select>
				</td></tr><tr><td>
				"._NUMCATS.":  <A HREF=\"javascript:n_window('docs/settingshelp.htm#numcats');\">[?]</A>
				</td><td>
				<select name=\"numcatsnew\">
				<option value=\"1\"";
				if ($numcats == "1")
					$output .= "selected";
				$output .= ">"._ONLYONE."</option>
				<option value=\"0\"";
				if ($numcats == "0")
					$output .= "selected";
				$output .= ">"._MORETHANONE."</option>
				</select>
				</td></tr><tr><td>
				"._CATSCOLUMN.":  <A HREF=\"javascript:n_window('docs/settingshelp.htm#catscolumn');\">[?]</A>
				</td><td>
				<INPUT name=\"columnsnew\" value=\"$columns\">
				</td></tr><tr><td>
				"._NEWSCOMMENTS.":  <A HREF=\"javascript:n_window('docs/settingshelp.htm#newscomments');\">[?]</A>
				</td><td>
				<select name=\"newscommentsnew\">
				<option value=\"1\"";
				if ($newscomments == "1")
					$output .= "selected";
				$output .= ">"._YES."</option>
				<option value=\"0\"";
				if ($newscomments == "0")
					$output .= "selected";
				$output .= ">"._NO."</option></select>
				</td></tr><tr><td>
				"._FAVORITES.":  <A HREF=\"javascript:n_window('docs/settingshelp.htm#favorites');\">[?]</A>
				</td><td>
				<select name=\"favoritesnew\">
				<option value=\"1\"";
				if ($favorites == "1")
					$output .= "selected";
				$output .= ">"._YES."</option>
				<option value=\"0\"";
				if ($favorites == "0")
					$output .= "selected";
				$output .= ">"._NO."</option></select>
				</td></tr><tr><td>
				"._NUMUPDATED.":  <A HREF=\"javascript:n_window('docs/settingshelp.htm#numupdated');\">[?]</A>
				</td><td>
				<INPUT name=\"numupdatednew\" value=\"$numupdated\">
				</td></tr><tr><td>
				"._DATEFORMAT."?:  <A HREF=\"javascript:n_window('docs/settingshelp.htm#dateformat');\">[?]</A>
				</td><td>
				<select name=\"dateformatnew\">
				<option value=\"1\"";
				if ($dateformat == "1")
					$output .= "selected";
				$output .= ">mm/dd/yy</option>
				<option value=\"2\"";
				if ($dateformat == "2")
					$output .= "selected";
				$output .= ">mm/dd/yyyy</option>
				<option value=\"3\"";
				if ($dateformat == "3")
					$output .= "selected";
				$output .= ">dd/mm/yyyy</option>
				<option value=\"4\"";
				if ($dateformat == "4")
					$output .= "selected";
				$output .= ">dd month yyyy</option>
				<option value=\"5\"";
				if ($dateformat == "5")
					$output .= "selected";
				$output .= ">dd.mm.yy</option>
				<option value=\"6\"";
				if ($dateformat == "6")
					$output .= "selected";
				$output .= ">yyyy.mm.dd</option>
				<option value=\"7\"";
				if ($dateformat == "7")
					$output .= "selected";
				$output .= ">mm.dd.yy</option>
				<option value=\"8\"";
				if ($dateformat == "8")
					$output .= "selected";
				$output .= ">dd-mm-yy</option>
				<option value=\"9\"";
				if ($dateformat == "9")
					$output .= "selected";
				$output .= ">mm-dd-yy</option>
				<option value=\"10\"";
				if ($dateformat == "10")
					$output .= "selected";
				$output .= ">month dd yyyy</option>
				</select>
				</td></tr><tr><td>
				"._NEWSDATE."?:  <A HREF=\"javascript:n_window('docs/settingshelp.htm#dateformat');\">[?]</A>
				</td><td>
				<select name=\"newsdatenew\">
				<option value=\"1\"";
				if ($newsdate == "1")
					$output .= "selected";
				$output .= ">mm/dd/yy</option>
				<option value=\"2\"";
				if ($newsdate == "2")
					$output .= "selected";
				$output .= ">mm/dd/yyyy</option>
				<option value=\"3\"";
				if ($newsdate == "3")
					$output .= "selected";
				$output .= ">dd/mm/yyyy</option>
				<option value=\"4\"";
				if ($newsdate == "4")
					$output .= "selected";
				$output .= ">dd month yyyy</option>
				<option value=\"5\"";
				if ($newsdate == "5")
					$output .= "selected";
				$output .= ">dd.mm.yy</option>
				<option value=\"6\"";
				if ($newsdate == "6")
					$output .= "selected";
				$output .= ">yyyy.mm.dd</option>
				<option value=\"7\"";
				if ($newsdate == "7")
					$output .= "selected";
				$output .= ">mm.dd.yy</option>
				<option value=\"8\"";
				if ($newsdate == "8")
					$output .= "selected";
				$output .= ">dd-mm-yy</option>
				<option value=\"9\"";
				if ($newsdate == "9")
					$output .= "selected";
				$output .= ">mm-dd-yy</option>
				<option value=\"10\"";
				if ($newsdate == "10")
					$output .= "selected";
				$output .= ">month dd yyyy</option>
				</select>
				</td></tr><tr><td>
				"._ONREVIEWS."?:  <A HREF=\"javascript:n_window('docs/settingshelp.htm#reviews');\">[?]</A>
				</td><td>
				<select name=\"reviewsallowednew\">
				<option value=\"1\"";
				if ($reviewsallowed == "1")
					$output .= "selected";
				$output .= ">"._YES."</option>
				<option value=\"0\"";
				if ($reviewsallowed == "0")
					$output .= "selected";
				$output .= ">"._NO."</option>
				</select>
				</td></tr><tr><td>
				"._ANONREVIEWS."? <A HREF=\"javascript:n_window('docs/settingshelp.htm#anonreviews');\">[?]</A>
				</td><td>
				<select name=\"anonreviewsnew\">
				<option value=\"1\"";
				if ($anonreviews == "1")
					$output .= "selected";
				$output .= ">"._YES."</option>
				<option value=\"0\"";
				if ($anonreviews == "0")
					$output .= "selected";
				$output .= ">"._NO."</option>
				</select>
				</td></tr><tr><td>
				"._WHATRATINGS."? <A HREF=\"javascript:n_window('docs/settingshelp.htm#ratings');\">[?]</A>
				</td><td>
				<select name=\"ratingsnew\">
				<option value=\"2\"";
				if ($ratings == "2")
					$output .= "selected";
				$output .= ">"._LIKE."</option>
				<option value=\"1\"";
				if ($ratings == "1")
					$output .= "selected";
				$output .= ">"._STARS."</option>
				<option value=\"0\"";
				if ($ratings == "0")
					$output .= "selected";
				$output .= ">"._NONE."</option>
				</select>
				</td></tr><tr><td>
				"._ALLOWRR."? <A HREF=\"javascript:n_window('docs/settingshelp.htm#roundrobins');\">[?]</A>
				</td><td>
				<select name=\"roundrobinsnew\">
				<option value=\"1\"";
				if ($roundrobins == "1")
					$output .= "selected";
				$output .= ">"._YES."</option>
				<option value=\"0\"";
				if ($roundrobins == "0")
					$output .= "selected";
				$output .= ">"._NO."</option>
				</select>
				</td></tr><tr><td>
				"._NOSUBS."? <A HREF=\"javascript:n_window('docs/settingshelp.htm#submissions');\">[?]</A>
				</td><td>
				<select name=\"submissionsoffnew\">
				<option value=\"1\"";
				if ($submissionsoff == "1")
					$output .= "selected";
				$output .= ">"._YES."</option>
				<option value=\"0\"";
				if ($submissionsoff == "0")
					$output .= "selected";
				$output .= ">"._NO."</option>
				</select>
				</td></tr><tr><td>
				"._NUMITEMS.":  <A HREF=\"javascript:n_window('docs/settingshelp.htm#itemspage');\">[?]</A>
				</td><td>
				<INPUT name=\"itemsperpagenew\" value=\"$itemsperpage\">
				</td></tr><tr><td>
				"._IMAGEUPLOADS."?  <A HREF=\"javascript:n_window('docs/settingshelp.htm#uploads');\">[?]</A>
				</td><td>
				<select name=\"imageuploadnew\">
				<option value=\"1\"";
				if ($imageupload == "1")
					$output .= "selected";
				$output .= ">"._YES."</option>
				<option value=\"0\"";
				if ($imageupload == "0")
					$output .= "selected";
				$output .= ">"._NO."</option>
				</select>
				</td></tr><tr><td>
				"._MAXWIDTH.":  <A HREF=\"javascript:n_window('docs/settingshelp.htm#maximage');\">[?]</A>
				</td><td>
				<INPUT name=\"imagewidthnew\" value=\"$imagewidth\">
				</td></tr><tr><td>
				"._MAXHEIGHT.":
				</td><td>
				<INPUT name=\"imageheightnew\" value=\"$imageheight\">
				</td></tr><tr><td>
				"._DEFAULTSKIN."  <A HREF=\"javascript:n_window('docs/settingshelp.htm#layout');\">[?]</A>
				</td><td>
				<select name=\"skinnew\">";

				$folder = "skins";

				$directory = opendir("$folder");
				while($filename = readdir($directory))
				{
					if($filename=="." or $filename=="..") continue;
					$output .= "<option value=\"$filename\"";
					if($skin == $filename)
						$output .= " selected";
					$output .= ">$filename</option>";

				}
				closedir($directory);
				$output .= "</select>
				</td></tr><tr><td>
				"._WELCOME.":  <A HREF=\"javascript:n_window('docs/settingshelp.htm#welcome');\">[?]</A>
				</td><td>
				<TEXTAREA name=\"welcome\" COLS=\"50\" ROWS=\"6\">$settings[welcome]</textarea>
				</td></tr><tr><td>
				"._RULES.":  <A HREF=\"javascript:n_window('docs/settingshelp.htm#rules');\">[?]</A>
				</td><td>
				<TEXTAREA name=\"rules\" COLS=\"50\" ROWS=\"6\">$settings[rules]</textarea>
				</td></tr><tr><td>
				"._THANKYOU.":  <A HREF=\"javascript:n_window('docs/settingshelp.htm#thankyou');\">[?]</A>
				</td><td>
				<TEXTAREA name=\"thankyou\" COLS=\"50\" ROWS=\"6\">$settings[thankyou]</textarea>
				</td></tr><tr><td>
				"._NOTHANKYOU.":  <A HREF=\"javascript:n_window('docs/settingshelp.htm#nothankyou');\">[?]</A>
				</td><td>
				<TEXTAREA name=\"nothankyou\" COLS=\"50\" ROWS=\"6\">$settings[nothankyou]</textarea>
				</td></tr><tr><td>
				"._COPYRIGHT.":  <A HREF=\"javascript:n_window('docs/settingshelp.htm#copyright');\">[?]</A>
				</td><td>
				<TEXTAREA name=\"copyright\" COLS=\"50\" ROWS=\"6\">$settings[copyright]</textarea>
				</td></tr><tr><td>
				"._HELPPAGE.":  <A HREF=\"javascript:n_window('docs/settingshelp.htm#help');\">[?]</A>
				</td><td>
				<TEXTAREA name=\"helptext\" COLS=\"50\" ROWS=\"6\">$settings[help]</textarea>
				</td></tr><tr><td>
				<tr><td colspan=\"2\" align=\"center\"><INPUT type=\"submit\" name=\"submit\" value=\""._SUBMIT."\">
				</td></tr>
				</form>
				</table>";
			}		
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function save_settings($sitenamenew, $slogannew, $urlnew, $storenew, $autovalidatenew, $numcatsnew, $reviewsallowednew, $ratingsnew, $roundrobinsnew, $submissionsoffnew, $anonreviewsnew, $itemsperpagenew, $imageuploadnew, $imagewidthnew, $imageheightnew, $skinnew, $store, $siteemailnew, $databasepathnew, $columnsnew, $tableprefixnew, $newscommentsnew, $numupdatednew, $dateformatnew, $favoritesnew, $newsdatenew, $storiespathnew)
{
	if(!$fp = fopen("config.php",w))
	{
		echo ""._CONFIGERROR."";
		exit;
	}
	if($storenew == "")
		$storenew = $store;

	$content = "<?php\n"
		."\n"
		."//Config File--you can edit this by hand, but it's preferable to use the admin panel\n"
		."\n"
		."//Sitename\n"
		."\n"
		."\$sitename = \"$sitenamenew\";\n"
		."\n"
		."//Slogan\n"
		."\n"
		."\$slogan = \"$slogannew\";\n"
		."\n"
		."//Site URL\n"
		."\n"
		."\$url = \"$urlnew\";\n"
		."\n"
		."//Admin E-mail\n"
		."\n"
		."\$siteemail = \"$siteemailnew\";\n"
		."\n"
		."//Database Config Path\n"
		."\n"
		."\$databasepath = \"$databasepathnew\";\n"
		."\n"
		."//Database Config Path\n"
		."\n"
		."\$storiespath = \"$storiespathnew\";\n"
		."\n"
		."//Table Prefix\n"
		."\n"
		."\$tableprefix = \"$tableprefixnew\";\n"
		."\n"
		."//News Comments\n"
		."\n"
		."\$newscomments = \"$newscommentsnew\";\n"
		."\n"
		."//Number of Updated Stories\n"
		."\n"
		."\$numupdated = \"$numupdatednew\";\n"
		."\n"
		."//Date Format\n"
		."\n"
		."\$dateformat = \"$dateformatnew\";\n"
		."\n"
		."//News Date Format\n"
		."\n"
		."\$newsdate = \"$newsdatenew\";\n"
		."\n"
		."//Allow Favorites\n"
		."\n"
		."\$favorites = \"$favoritesnew\";\n"
		."\n"
		."//Store stories\n"
		."\n"
		."\$store = \"$storenew\";\n"
		."\n"
		."//Automatically validate stories; yes = 1, no = 0\n"
		."\n"
		."\$autovalidate = \"$autovalidatenew\";\n"
		."\n"
		."//Number of categories; if only one, will shorten some processes\n"
		."\n"
		."\$numcats = \"$numcatsnew\";\n"
		."\n"
		."//Number of columns to display the categories in\n"
		."\n"
		."\$columns = \"$columnsnew\";\n"
		."\n"
		."//Allow readers to submit reviews; yes = 1, no = 0\n"
		."\n"
		."\$reviewsallowed = \"$reviewsallowednew\";\n"
		."\n"
		."//Rating system, in addition to reviews; none = 0, stars = 1, like/dislike = 2\n"
		."\n"
		."\$ratings = \"$ratingsnew\";\n"
		."\n"
		."//Allow Round Robins; yes = 1, no = 0\n"
		."\n"
		."\$roundrobins = \"$roundrobinsnew\";\n"
		."\n"
		."//Turn off submissions completely; yes = 1, no = 0\n"
		."\n"
		."\$submissionsoff = \"$submissionsoffnew\";\n"
		."\n"
		."//Allow Anonymous reviews; yes = 1, no = 0\n"
		."\n"
		."\$anonreviews = \"$anonreviewsnew\";\n"
		."\n"
		."//Number of items per page in search results\n"
		."\n"
		."\$itemsperpage = \"$itemsperpagenew\";\n"
		."\n"
		."//Allow image uploads with stories; yes = 1, no = 0\n"
		."\n"
		."\$imageupload = \"$imageuploadnew\";\n"
		."\n"
		."//Max image height\n"
		."\n"
		."\$imageheight = \"$imageheightnew\";\n"
		."\n"
		."//Max image width\n"
		."\n"
		."\$imagewidth = \"$imagewidthnew\";\n"
		."\n"
		."//Default Skin\n"
		."\n"
		."\$skin = \"$skinnew\";\n"
		."\n"
		.'?>';

	fwrite($fp, $content);
	fclose($fp);
	return;
}

function adminmenu()
{
	global $level;
	$output .= "<table width=\"100%\">
	<tr><td align=\"center\">
	<center>";
	if (($level == '1') || ($level == '2'))
	{
		$output .= "<a href=\"admin.php?action=categories\">"._CATEGORIES."</a>
		| <a href=\"admin.php?action=characters\">"._CHARACTERS."</a>
		| <a href=\"admin.php?action=ratings\">"._RATINGS."</a>
		| <a href=\"admin.php?action=genres\">"._GENRES."</a>
		| <a href=\"admin.php?action=warnings\">"._WARNINGS."</a>
		| <a href=\"admin.php?action=authors\">"._AUTHORS."</a>";
	}

	if ($level == '1')
	{
		$output .= "
		| <a href=\"admin.php?action=settings\">"._SETTINGS."</a>
		| <a href=\"admin.php?action=admins\">"._ADMINS."</a>";
	}

	if (($level == '1') || ($level == '2'))
		$output .= "<br><br>";

	if (($level == '1') || ($level == '2') || ($level == '3'))
	{
		$output .= "
		<a href=\"admin.php?action=submitted\">"._SUBMITTED."</a>
		| <a href=\"admin.php?action=addstory\">"._ADDSTORY."</a>
		| <a href=\"admin.php?action=news\">"._NEWS."</a>
		| <a href=\"admin.php?action=searchbox\">"._SEARCH."</a>";
	}
	if(($level == '1') || ($level == '2'))
	{
		$output .= " | <a href=\"admin.php?action=mailusers\">"._MAILUSERS."</a>";
	}
	$output .= "</center>
	</td></tr></table>";
	return $output;
}

function footer()
{
	global $tableprefix;
	$result = mysql_query("SELECT copyright FROM ".$tableprefix."fanfiction_settings");
	$footer = mysql_fetch_array($result);
	return $footer[copyright];

}

switch ($action)
{
	case "addstory":
		include_once ("adminstories.php");
		addstory($submit, $catid, $gid, $rid, $charid, $storyfile, $storytext, $wid, $title, $chapter, $summary, $uid, $sid, $numchars, $numgenres);
	break;

	case "submitted":
		include_once ("adminstories.php");
		submitted();
	break;

	case "yesletter":
		include_once ("adminstories.php");
		yesletter($sid, $uid, $submit, $email, $ademail, $letter, $subject);
	break;

	case "noletter":
		include_once ("adminstories.php");
		noletter($sid, $uid, $submit, $email, $ademail, $letter, $subject);
	break;

	case "validate":
		include_once ("adminstories.php");
		validate($sid, $validate, $submit, $storytext, $title, $chapter, $summary, $catid, $penname);
	break;

	case "categories":
		include_once ("adminfunctions.php");
		categories($submit, $category, $parentcatid, $description, $locked, $image, $go, $displayorder, $catid, $orderafter);
	break;

	case "categoryedit":
		include_once ("adminfunctions.php");
		categoryedit($submit, $catid, $category, $description, $locked, $parentcatid, $image);
	break;

	case "categorydelete":
		include_once ("adminfunctions.php");
		categorydelete($delete, $catid);
	break;

	case "catcounts":
		include_once ("adminfunctions.php");
		catcounts();
	break;
	
	case "characters":
		include_once ("adminfunctions.php");
		characters($submit, $charname, $catid, $transmit, $copy, $checkbox);
	break;

	case "characteredit":
		include_once ("adminfunctions.php");
		characteredit($submit, $charid, $catid, $charname, $oldname, $oldcat);
	break;

	case "characterdelete":
		include_once ("adminfunctions.php");
		characterdelete($delete, $charid);
	break;

	case "ratings":
		include_once ("adminfunctions.php");
		ratings($submit, $rating, $ratingwarning, $warningtext);
	break;

	case "ratingedit":
		include_once ("adminfunctions.php");
		ratingedit($submit, $rating, $rid, $ratingwarning, $warningtext, $oldrating);
	break;

	case "ratingdelete":
		include_once ("adminfunctions.php");
		ratingdelete($delete, $rid);
	break;

	case "genres":
		include_once ("adminfunctions.php");
		genres($submit, $genre);
	break;

	case "genreedit":
		include_once ("adminfunctions.php");
		genreedit($submit, $genre, $gid, $oldgenre);
	break;

	case "genredelete":
		include_once ("adminfunctions.php");
		genredelete($delete, $gid);
	break;

	case "warnings":
		include_once ("adminfunctions.php");
		warnings($submit, $warning);
	break;

	case "warningedit":
		include_once ("adminfunctions.php");
		warningedit($submit, $warning, $wid, $oldwarning);
	break;

	case "warningdelete":
		include_once ("adminfunctions.php");
		warningdelete($delete, $wid);
	break;

	case "authors":
		include_once ("adminusers.php");
		authors($submit, $uid, $penname, $realname, $email, $website);
	break;

	case "authordelete":
		include_once ("adminusers.php");
		authordelete($delete, $uid);
	break;
	
	case "mailusers":
		include_once ("adminusers.php");
		mailusers($submit, $subject, $mailtext);
	break;

	case "authorrelease":
		include_once ("adminusers.php");
		authorrelease($delete, $uid);
	break;

	case "news":
		include_once ("adminnews.php");
		news($submit, $title, $author, $story, $offset, $index);
	break;

	case "newsedit":
		include_once ("adminnews.php");
		newsedit($nid, $author, $title, $story, $submit);
	break;

	case "newsdelete":
		include_once ("adminnews.php");
		newsdelete($nid, $delete);
	break;

	case "searchbox":
		searchbox($submit, $searchterm, $searchtype, $go, $com, $sid, $inorder, $psid);
	break;

	case "admins":
		include_once ("adminusers.php");
		admins($submit, $admin, $password, $email, $adlevel, $contact, $uid, $categories);
	break;

	case "adminedit":
		include_once ("adminusers.php");
		adminedit($submit, $admin, $password, $password2, $email, $adlevel, $contact, $uid, $categories);
	break;

	case "admindelete":
		include_once ("adminusers.php");
		admindelete($delete, $aid);
	break;

	case "deleteuser":
		include_once ("adminusers.php");
		deleteuser($uid, $delete);
	break;

	case "settings":
	    settings($submit, $sitenamenew, $slogannew, $urlnew, $storenew, $autovalidatenew, $numcatsnew, $reviewsallowednew, $ratingsnew, $roundrobinsnew, $submissionsoffnew, $anonreviewsnew, $itemsperpagenew, $imageuploadnew, $imagewidthnew, $imageheightnew, $skinnew, $welcome, $rules, $thankyou, $nothankyou, $copyright, $helptext, $siteemailnew, $databasepathnew, $columnsnew, $tableprefixnew, $newscommentsnew, $numupdatednew, $dateformatnew, $favoritesnew, $newsdatenew, $storiespathnew);
	break;

	default:
		admin_main();
		break;
}

?>