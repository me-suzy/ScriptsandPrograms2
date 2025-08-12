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

function addstory($submit, $catid, $gid, $rid, $charid, $storyfile, $storytext, $wid, $title, $chapter, $summary, $uid, $sid, $numchars, $numgenres)
{
	global $tableprefix, $numcats, $adminarea, $level, $display, $admin, $logo, $home, $recent, $catslink, $authors, $help, $search, $login, $useruid, $storiespath;
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
		$output .= "<center><h4>"._ADDSTORY."</h4></center>";

		if(($submit == "Add Story") && ((($rid == "") || ($title == "") || ($chapter == "") || ($summary == "")) || (($charid == "") && ($numchars != "0")) || (($gid == "") && ($numgenres != "0"))))
		{
			$submit = ""._PREVIEW."";
		}
		if($submit == ""._PREVIEW."")
		{
			if ((($storyfile == "") || ($storyfile == "none")) && ($storytext == ""))
			{
				$output .= "<center>"._NOSTORYTEXT."</center>";
			}
			else
			{
				if($charid != "")
				{
					$count = 0;
					foreach ($charid as $character)
					{
						if($count != 0)
							$charstring .= ", ";
						$charstring = $charstring . $character;
						$count++;
					}
				}
				if($gid != "")
				{
					$count2 = 0;
					foreach ($gid as $genre)
					{
						if($count2 != 0)
							$genrestring .= ", ";
						$genrestring = $genrestring . $genre;
						$count2++;
					}
				}
				if($wid != "")
					{
						$count3 = 0;
						foreach ($wid as $warning)
						{
							if($count3 != 0)
								$warningstring .= ", ";
							$warningstring = $warningstring . $warning;
							$count3++;
						}
					}
				if((($rid == "") || ($title == "") || ($chapter == "") || ($summary == "")) || (($charid == "") && ($numchars != "0")) || (($gid == "") && ($numgenres != "0")))
				{
					$output .= "<font style=\"color:red\">"._MISSINGFIELDS."</font><br><br>";
				}
				if(($storyfile != "") && ($storyfile != "none"))
				{
					if (($_FILES['storyfile']['type'] != ('text/html')) && ($_FILES['storyfile']['type'] != ('text/plain'))) {
   						echo ""._INVALIDUPLOAD."";
					}
					else
					{
						$texts = file($storyfile);

						foreach ($texts as $text) {
	    				$story .= nl2br(stripslashes(strip_tags($text, '<br><b><i><u><center><img><a><hr><p><ul><li><ol>')));
						}
					}

				}
				else if($storytext != "")
				{
					$story = nl2br(stripslashes(strip_tags($storytext, '<br><b><i><u><center><img><a><hr><p><ul><li><ol>')));
				}
				$title = stripslashes($title);
				$chapter = stripslashes($chapter);
				$summary = stripslashes($summary);
				$charstring = stripslashes($charstring);
				$genrestring = stripslashes($genrestring);
				$warningstring = stripslashes($warningstring);
				$output .= ""._TITLE.": $title<br>
				"._CHAPTERTITLE.": $chapter<br>
				"._CHARACTERS.": $charstring<br>
				"._GENRES.": $genrestring<br>
				"._RATING.": $rid<br>
				"._WARNINGS.": $warningstring<br>
				"._SUMMARY.": $summary<br><br>";

				$output .= "$story";
				$authorquery = mysql_query("SELECT penname, uid FROM ".$tableprefix."fanfiction_authors ORDER BY penname");
				$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"admin.php?action=addstory\">
					<table><tr><td>
					"._AUTHOR.":
					</td><td><select name=\"uid\">";
					while($authorresult = mysql_fetch_array($authorquery))
					{
						$output .= "<option value=\"$authorresult[uid]\"";
						if($authorresult[uid] == $uid)
							$output .= " selected";
						$output .= ">$authorresult[penname]</option>";
					}
					$output .= "</select></td><td>
					</td></tr><tr><td>";
					if ($numcats != '1')
					{
						$output .= ""._CATEGORY.":
						</td><td>";
						$result = mysql_query("SELECT category FROM ".$tableprefix."fanfiction_categories WHERE catid = '$catid'");
						$catinfo = mysql_fetch_array($result);
						$output .= "$catinfo[category] <INPUT type=\"hidden\" name=\"catid\" value=\"$catid\">
						</td><td>

						</td></tr><tr><td>";
					}
					else
					{
						$output .= "<INPUT type=\"hidden\" name=\"catid\" value=\"$catid\">";
					}
					if($title == "")
						$output .= "<font style=\"color:red\">";
					$output .= "
					"._TITLE.":";
					if($title == "")
						$output .= "</font>";
					$output .= "</td><td>
					<INPUT name=\"title\" size=\"30\" value=\"$title\">
					</td><td>

					</td></tr><tr><td>";
					if($chapter == "")
						$output .= "<font style=\"color:red\">";
					$output .= ""._CHAPTERTITLE.":";
					if($chapter == "")
						$output .= "</font>";
					$output .= "</td><td>
					<INPUT name=\"chapter\" size=\"30\" value=\"$chapter\">
					</td><td>

					</td></tr><tr><td>";
					if($summary == "")
						$output .= "<font style=\"color:red\">";
					$output .= ""._SUMMARY.":";
					if($summary == "")
						$output .= "</font>";
					$output .= "</td><td>
					<TEXTAREA name=\"summary\" cols=\"45\" rows=\"4\">$summary</textarea>
					</td><td>

					</td></tr><tr><td>";
					if(isset($sid))
					{
						$output .= "<INPUT type=\"hidden\" name=\"sid\" value=\"$sid\">";
					}
					$result = mysql_query("SELECT gid, genre FROM ".$tableprefix."fanfiction_genres");
					$numgenres = mysql_num_rows($result);
					$output .= "<INPUT type=\"hidden\" name=\"numgenres\" value=\"$numgenres\">";
					if($numgenres != "0")
					{
						if($gid == "")
							$output .= "<font style=\"color:red\">";
						$output .= ""._GENRES.":";
						if($gid == "")
							$output .= "</font>";
						$output .= "</td><td>";

						$output .= "<select name=\"gid[]\" size=\"5\" multiple>";
						while ($genreresults = mysql_fetch_array($result))
						{
							$output .= "<option value=\"$genreresults[genre]\"";
							if($gid != "")
							{
								if(in_array($genreresults[genre], $gid))
									$output .= " selected";
							}
							$output .= ">$genreresults[genre]</option>";
						}
					}
					$output .= "
					</td><td>

					</td></tr><tr><td>";
					$result = mysql_query("SELECT charid, charname FROM ".$tableprefix."fanfiction_characters WHERE catid = '$catid'");
					$numchars = mysql_num_rows($result);
					$output .= "<INPUT type=\"hidden\" name=\"numchars\" value=\"$numchars\">";
					if($numchars != "0")
					{
						if($charid == "")
							$output .= "<font style=\"color:red\">";
						$output .= ""._CHARACTERS.":";
						if($charid == "")
							$output .= "</font>";
						$output .= "</td><td>";
						$output .= "<select name=\"charid[]\" size=\"5\" multiple>";
						while ($charresults = mysql_fetch_array($result))
							{
								$output .= "<option value=\"$charresults[charname]\"";
								if($charid != "")
								{
									if(in_array($charresults[charname], $charid))
										$output .= " selected";
								}
								$output .= ">$charresults[charname]</option>";
							}
					}
					$output .= "
					</td><td>

					</td></tr><tr><td>
					"._RATING.":
					</td><td>
					<select name=\"rid\">";
					$result = mysql_query("SELECT rid, rating FROM ".$tableprefix."fanfiction_ratings");
					while ($ratingresults = mysql_fetch_array($result))
						{
							$output .= "<option value=\"$ratingresults[rating]\"";
							if($ratingresults[rating] == "$rid")
								$output .= " selected";
							$output .= ">$ratingresults[rating]</option>";
						}
					$output .= "
					</td><td>

					</td></tr>";
					$newstorytext = strip_tags($story, '<b><i><u><center><img><a><hr><p><ul><li><ol>');
					$result = mysql_query("SELECT wid, warning FROM ".$tableprefix."fanfiction_warnings");
					$numwarnings = mysql_num_rows($result);
					if($numwarnings != "0")
					{
						$output .= "<tr><td>
						"._WARNINGS.":
						</td><td>";
						$output .= "<select name=\"wid[]\" size=\"5\" multiple>";

						while ($warningresults = mysql_fetch_array($result))
							{
								$output .= "<option value=\"$warningresults[warning]\"";
								if($wid != "")
								{
									if(in_array($warningresults[warning], $wid))
										$output .= " selected";
								}
								$output .= ">$warningresults[warning]</option>";
							}

						$output .= "
						</td><td>

						</td></tr>";
					}
					$output .= "<tr><td>
					"._STORYTEXTTEXT.":
					</td><td>
					<TEXTAREA name=\"storytext\" cols=\"60\" rows=\"10\">$newstorytext</TEXTAREA>
					</td><td valign=\"top\">
					"._STORYTEXTCLEANUP."
					</td></tr><tr><td colspan=\"3\" align=\"center\">
					<INPUT type=\"submit\" value=\""._PREVIEW."\" name=\"submit\"> <INPUT type=\"submit\" value=\""._ADDSTORY."\" name=\"submit\">
					</table></form>";

			}
		}
		else if($submit == ""._ADDSTORY."")
		{
			if($charid != "")
			{
				$count = 0;
				foreach ($charid as $character)
				{
					if($count != 0)
						$charstring .= ", ";
					$charstring = $charstring . $character;
					$count++;
				}
			}
			if($gid != "")
			{
				$count2 = 0;
				foreach ($gid as $genre)
				{
					if($count2 != 0)
						$genrestring .= ", ";
					$genrestring = $genrestring . $genre;
					$count2++;
				}
			}
			if($wid != "")
					{
						$count3 = 0;
						foreach ($wid as $warning)
						{
							if($count3 != 0)
								$warningstring .= ", ";
							$warningstring = $warningstring . $warning;
							$count3++;
						}
					}
			//$wordcount = str_word_count($storytext);
			$wordcount = count( preg_split("/[\W]+/", $storytext) );
			if($store == "mysql")
			{
				if(isset($sid))
				{
					$number = mysql_query("SELECT psid FROM ".$tableprefix."fanfiction_stories WHERE psid = '$sid' AND sid != psid");
					$inorder = (mysql_num_rows($number)) + 1;
					mysql_query("INSERT INTO ".$tableprefix."fanfiction_stories (title, chapter, summary, catid, gid, charid, wid, rid, date, uid, validated, storytext, psid, inorder, updated, wordcount) VALUES ('$title', '$chapter', '$summary', '$catid', '$genrestring', '$charstring', '$warningstring', '$rid', now(), '$uid', '1', '$storytext', '$sid', '$inorder', now(), '$wordcount')");
					mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET updated = now() WHERE sid = '$sid'");
				}
				else
				{
					mysql_query("INSERT INTO ".$tableprefix."fanfiction_stories (title, chapter, summary, catid, gid, charid, wid, rid, date, uid, validated, storytext, updated, wordcount) VALUES ('$title', '$chapter', '$summary', '$catid', '$genrestring', '$charstring', '$warningstring', '$rid', now(), '$uid', '1', '$storytext', now(), '$wordcount')");
					$id = mysql_insert_id();
					mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET psid = '$id' WHERE sid = '$id'");
				}
				$output .= ""._STORYADDED."";
			}
			else
			{
				if(isset($sid))
				{
					$number = mysql_query("SELECT psid FROM ".$tableprefix."fanfiction_stories WHERE psid = '$sid' AND sid != psid");
					$inorder = (mysql_num_rows($number)) + 1;
					$query = mysql_query("SELECT psid FROM ".$tableprefix."fanfiction_stories WHERE psid = '$sid'");
					$parent = mysql_fetch_array($query);
					mysql_query("INSERT INTO ".$tableprefix."fanfiction_stories (title, chapter, summary, catid, gid, charid, wid, rid, date, uid, validated, psid, inorder, updated, wordcount) VALUES ('$title', '$chapter', '$summary', '$catid', '$genrestring', '$charstring', '$warningstring', '$rid', now(), '$uid', '1', '$sid', '$inorder', now(), '$wordcount')");
					$storyid = mysql_insert_id();
					mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET updated = now() WHERE sid = '$sid'");
				}
				else
				{
					$insertstory = mysql_query("INSERT INTO ".$tableprefix."fanfiction_stories (title, chapter, summary, catid, gid, charid, wid, rid, date, uid, validated, updated, wordcount) VALUES ('$title', '$chapter', '$summary', '$catid', '$genrestring', '$charstring', '$warningstring', '$rid', now(), '$uid', '1', now(), '$wordcount')");
					$storyid = mysql_insert_id();
					mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET psid = '$storyid' WHERE sid = '$storyid'");
					include ("functions.php");
					categoryitems($catid, 1);
				}
				$query = mysql_query("SELECT penname FROM ".$tableprefix."fanfiction_authors WHERE uid = '$uid'");
				$author = mysql_fetch_array($query);
				$username = $author[penname];

				if( !file_exists( "$storiespath/$username/" ) )
				{
					mkdir("$storiespath/$username", 0755);
					chmod("$storiespath/$username", 0777);
				}

				$handle = fopen("$storiespath/$username/$storyid.txt", 'w');

				if ($handle)
				{
					fwrite($handle, stripslashes ($storytext));
					fclose($handle);
					$storytext = "";
				}
				if(isset($sid))
				{
					$sid = $parent[psid];
				}
				else
					$sid = $storyid;
				$output .= ""._STORYCHAPTER." Would you like to <a href=\"admin.php?action=addstory&sid=$sid&submit=Select\">add a chapter</a> to this story?";
			}
		}
		else if(($submit == "Select") || ($numcats == '1'))
		{

			if(isset($sid))
			{
				$query = mysql_query("SELECT carry FROM ".$tableprefix."fanfiction_authors WHERE uid = '$useruid'");
				$carry = mysql_fetch_array($query);
				$titlequery = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_stories WHERE sid = '$sid'");
				$titleresult = mysql_fetch_array($titlequery);
				if($carry[carry] == "1")
				{
					$orchapter = $titleresult[chapter];
					$orsummary = $titleresult[summary];
					$orrating = $titleresult[rid];
				}
			}
			$authorquery = mysql_query("SELECT penname, uid FROM ".$tableprefix."fanfiction_authors ORDER BY penname");
			$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"admin.php?action=addstory\">
					<table><tr>";
					if(isset($sid))
					{
						$output .= "<INPUT type=\"hidden\" name=\"sid\" value=\"$sid\">";
					}
					$output .= "<td>"._AUTHOR.":
					</td><td><select name=\"uid\">";
					while($authorresult = mysql_fetch_array($authorquery))
					{
						$output .= "<option value=\"$authorresult[uid]\"";
						if(isset($sid))
						{
							if($titleresult[uid] == $authorresult[uid])
								$output .= " selected";
						}
						$output .= ">$authorresult[penname]</option>";
					}
					$output .= "</select></td><td>
					</td></tr><tr><td>";
					if ($numcats != '1')
					{
						$output .= ""._CATEGORY.":
						</td><td>";
						if(isset($sid))
							$catid = $titleresult[catid];
						else
							$catid = $catid;
						$result = mysql_query("SELECT category FROM ".$tableprefix."fanfiction_categories WHERE catid = '$catid'");
						$catinfo = mysql_fetch_array($result);
						$output .= "$catinfo[category] <INPUT type=\"hidden\" name=\"catid\" value=\"$catid\">
						</td><td>

						</td></tr><tr><td>";
					}
					else
					{
						$catquery = mysql_query("SELECT catid FROM ".$tableprefix."fanfiction_categories");
						$catresult = mysql_fetch_array($catquery);
						$catid = $catresult[catid];
						$output .= "<INPUT type=\"hidden\" name=\"catid\" value=\"$catid\">";
					}
					$output .= "
					"._TITLE.":
					</td><td>
					<INPUT name=\"title\" size=\"30\"";
					if(isset($sid))
					{
						$output .= " value=\"$titleresult[title]\"";
					}

					$output .= "></td><td>

					</td></tr><tr><td>
					"._CHAPTERTITLE.":
					</td><td>
					<INPUT name=\"chapter\" size=\"30\" value=\"$orchapter\">
					</td><td>

					</td></tr><tr><td>
					"._SUMMARY.":
					</td><td>
					<TEXTAREA name=\"summary\" cols=\"45\" rows=\"4\">$orsummary</textarea>
					</td><td>

					</td></tr>";
					$result = mysql_query("SELECT gid, genre FROM ".$tableprefix."fanfiction_genres");
					$numgenres = mysql_num_rows($result);
					$output .= "<INPUT type=\"hidden\" name=\"numgenres\" value=\"$numgenres\">";
					if($numgenres != "0")
					{
						$output .= "<tr><td>
						"._GENRES.":
						</td><td>";
						$output .= "<select name=\"gid[]\" size=\"5\" multiple>";

						while ($genreresults = mysql_fetch_array($result))
							{
								$output .= "<option value=\"$genreresults[genre]\"";
										if($carry[carry] == "1")
										{
											if(strstr($titleresult[gid], $genreresults[genre]))
											$output .= " selected";
										}
										$output .= ">$genreresults[genre]</option>";
							}
						$output .= "
						</select></td><td>

						</td></tr>";
					}
					$result = mysql_query("SELECT charid, charname FROM ".$tableprefix."fanfiction_characters WHERE catid = '$catid'");
					$numchars = mysql_num_rows($result);
					$output .= "<INPUT type=\"hidden\" name=\"numchars\" value=\"$numchars\">";
					if($numchars != "0")
					{
						$output .= "<tr><td>
						"._CHARACTERS.":
						</td><td>";
						$output .= "<select name=\"charid[]\" size=\"5\" multiple>";

						while ($charresults = mysql_fetch_array($result))
							{
								$output .= "<option value=\"$charresults[charname]\"";
										if($carry[carry] == "1")
										{
											if(strstr($titleresult[charid], $charresults[charname]))
											$output .= " selected";
										}
										$output .= ">$charresults[charname]</option>";
							}

						$output .= "
						</select></td><td>

						</td></tr>";
					}
					$output .= "<tr><td>
					"._RATING.":
					</td><td>
					<select name=\"rid\">";
					$result = mysql_query("SELECT rid, rating FROM ".$tableprefix."fanfiction_ratings");
					while ($ratingresults = mysql_fetch_array($result))
					{
						$output .= "<option value=\"$ratingresults[rating]\"";
						if("$orrating" == "$ratingresults[rating]")
							$output .= " selected";
						$output .= ">$ratingresults[rating]</option>";
					}
					$output .= "
					</select></td><td>

					</td></tr>";

					$result = mysql_query("SELECT wid, warning FROM ".$tableprefix."fanfiction_warnings");
					$numwarnings = mysql_num_rows($result);
					if($numwarnings != "0")
					{
						$output .= "<tr><td>
						"._WARNINGS.":
						</td><td>";
						$output .= "<select name=\"wid[]\" size=\"5\" multiple>";

						while ($warningresults = mysql_fetch_array($result))
							{
								$output .= "<option value=\"$warningresults[warning]\"";
										if($carry[carry] == "1")
										{
											if(strstr($titleresult[wid], $warningresults[warning]))
											$output .= " selected";
										}
										$output .= ">$warningresults[warning]</option>";
							}
						$output .= "
						</select></td><td>

						</td></tr>";
					}

					$output .= "<tr><td>
					"._STORYTEXTTEXT.":
					</td><td>
					<TEXTAREA name=\"storytext\" cols=\"60\" rows=\"10\" onClick=\"this.form.storyfile.disabled=true\"></TEXTAREA>
					</td><td rowspan=\"2\" valign=\"top\">
					"._STORYTEXTINFO."
					</td></tr><tr><td>
					"._STORYTEXTFILE.":
					</td><td>
					<INPUT type=\"file\" name=\"storyfile\" onClick=\"this.form.storytext.disabled=true\">
					</td></tr><tr><td colspan=\"3\" align=\"center\">
					<INPUT type=\"submit\" value=\""._PREVIEW."\" name=\"submit\">
					</table></form>";
		}
		else
		{
			$result = mysql_query("SELECT catid, parentcatid, category, leveldown FROM ".$tableprefix."fanfiction_categories ORDER BY displayorder");
			$numcats = mysql_num_rows($result);

			if($numcats == "0")
			{
				$output .= "<center>"._CATMUSTBEADDED."</center>";
			}
			else
			{
				$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"admin.php?action=addstory\"><table align=\"center\"><tr><td valign=\"top\">"._CATEGORY.": <select name=\"catid\">";
				while($categorychoose = mysql_fetch_array($result))
				{
					$space = "";
					if ($categorychoose[parentcatid] != "-1")
					{
						$parent = mysql_query("select leveldown,category from ".$tableprefix."fanfiction_categories WHERE catid='$categorychoose[parentcatid]'");
						$parentresult = mysql_fetch_array($parent);
	
						for ($count = 0; $count <= $categorychoose[leveldown]; $count++)
						{
						    $space .= "&nbsp;&nbsp;&nbsp;";
						}
					}
					else
						$space = "";
					$output .= "<option value=\"".$categorychoose[catid]."\">";
					$output .= "$space-$categorychoose[category]";
					$output .= "</option>";
				}
				$output .= "</select>";
				$output .= "</td><td valign=\"top\"><INPUT type=\"submit\" name=\"submit\" value=\""._SELECT."\"></form></td></tr></table>";
	
				$output .= "<center><h4>"._ADDCHAPTER."</h4></center>";
	
	
				$query = mysql_query("SELECT title,sid FROM ".$tableprefix."fanfiction_stories WHERE sid = psid ORDER BY title");
				$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"admin.php?action=addstory\"><table align=\"center\"><tr><td valign=\"top\">"._TITLES.": <select name=\"sid\">";
				while($titles = mysql_fetch_array($query))
				{
					$output .= "<option value=\"$titles[sid]\">$titles[title]</option>";
				}
				$output .= "</select>";
				$output .= "</td><td valign=\"top\"><INPUT type=\"submit\" name=\"submit\" value=\""._SELECT."\"></form></td></tr></table>";
			}
		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function submitted()
{
	global $tableprefix, $level, $adminarea, $logo, $home, $recent, $catslink, $authors, $help, $search, $login, $admincats;
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
		$output .= "<center><h4>"._SUBMITTED."</h4></center>";
		$result = mysql_query("SELECT title,chapter,uid,sid,catid from ".$tableprefix."fanfiction_stories WHERE validated = '0' ORDER BY catid");
		$output .= "<table class=\"tblborder\" cellspacing=\"0\" cellpadding=\"3\" align=\"center\"><tr class=\"tblborder\"><td class=\"tblborder\">&nbsp;</td><td class=\"tblborder\"><b>"._TITLE."</b></td><td class=\"tblborder\"><b>"._AUTHOR."</b></td><td class=\"tblborder\"><b>"._CATEGORY."</b></td><td class=\"tblborder\"><b>"._OPTIONS."</b></td>";
		$array = split(",", $admincats);
		while ($storyresults = mysql_fetch_array($result))
		{
			
			$result2 = mysql_query("SELECT penname FROM ".$tableprefix."fanfiction_authors WHERE uid = '$storyresults[uid]'");
			$author = mysql_fetch_array($result2);
			$result3 = mysql_query("SELECT category FROM ".$tableprefix."fanfiction_categories WHERE catid = '$storyresults[catid]'");
			$cat = mysql_fetch_array($result3);
			$output .= "<tr class=\"tblborder\"><td class=\"tblborder\">";
			if((in_array($storyresults[catid], $array)) || ($admincats == "0"))
				$output .= "<img src=\"images/star.gif\">";
			else
				$output .= "&nbsp;";
			$output .= "</td><td class=\"tblborder\">$storyresults[title]";
			if($storyresults[chapter] != "")
				$output .= ": $storyresults[chapter]";
			$output .= "</td><td class=\"tblborder\">$author[penname]</td>";
			$output .= "<td class=\"tblborder\">$cat[category]</td>";
			$output .= "<td class=\"tblborder\"><a href=\"admin.php?action=validate&sid=$storyresults[sid]\">"._VALIDATE."</a> | <a href=\"stories.php?action=deletestory&sid=$storyresults[sid]\">"._DELETE."</a> | <a href=\"javascript:myopen('admin.php?action=yesletter&uid=$storyresults[uid]&sid=$storyresults[sid]','windowName','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=400,height=330')\">"._YESLETTER."</a> | <a href=\"javascript:myopen('admin.php?action=noletter&uid=$storyresults[uid]&sid=$storyresults[sid]','windowName','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=400,height=330')\">"._NOLETTER."</a></td></tr>";
		}
		$output .= "</table>";

	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function validate($sid, $validate, $submit, $storytext, $title, $chapter, $summary, $catid, $penname)
{
	global $tableprefix, $level, $adminarea, $logo, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $store, $storiespath;
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
		$output .= "<center><h4>"._VIEWSUBMITTED."</h4></center>";
		if($validate == "yes")
		{
			mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET validated = '1' WHERE sid = '$sid'");
			$output .= "<center><b>"._STORYVALIDATED."</b></center>";
			$query3 = mysql_query("SELECT catid,psid,sid FROM ".$tableprefix."fanfiction_stories WHERE sid = '$sid'");
			$cat = mysql_fetch_array($query3);
			if("$cat[sid]" == "$cat[psid]")
			{	
				include("functions.php");
				categoryitems($cat[catid], 1);
			}
		}
		else
		{
			if($submit)
			{

				if($store == "files")
				{
					mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET title='$title', chapter='$chapter', summary='$summary', catid='$catid' WHERE sid = '$sid'");
					$handle = fopen("$storiespath/$penname/$sid.txt", 'w');

					if ($handle)
					{
						fwrite($handle, stripslashes ($storytext));
						fclose($handle);
					}
				}
				else if ($store == "mysql")
				{
					mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET storytext = '$storytext' WHERE sid = '$sid'");
				}
			}

			$result = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_stories WHERE sid = '$sid'");
			$story = mysql_fetch_array($result);
			$result4 = mysql_query("SELECT penname FROM ".$tableprefix."fanfiction_authors WHERE uid = '$story[uid]'");
			$author = mysql_fetch_array($result4);
			$output .= "<b>"._AUTHOR.":</b> $author[penname]<br>";
			$output .= "<b>"._TITLE.":</b> $story[title]";
			if($story[chapter] != "")
				$output .= ": $story[chapter]";
			$output .= "<br>";
			$result2 = mysql_query("SELECT category FROM ".$tableprefix."fanfiction_categories WHERE catid = '$story[catid]'");
			$category = mysql_fetch_array($result2);
			$output .= "<b>"._CATEGORY.":</b> $category[category]<br>";

			$output .= "<b>"._GENRES.":</b> $story[gid]";
			$output .= "<br>";
			$output .= "<b>"._RATING.":</b> $story[rid]<br>";
			if($story[wid] != "")
			{
				$output .= "<b>"._WARNINGS.":</b> $story[wid]";
				$output .= "<br>";
			}
			$output .= "<b>"._CHARACTERS.":</b> $story[charid]";
			$output .= "<br>";
			$output .= "<b>"._SUMMARY.":</b> $story[summary]<br>";
			$output .= "<a href=\"admin.php?action=validate&sid=$story[sid]&validate=yes\">"._VALIDATEYES."</a> | <a href=\"stories.php?action=deletestory&sid=$story[sid]\">"._DELETE."</a> | <a href=\"javascript:myopen('admin.php?action=yesletter&uid=$story[uid]&sid=$story[sid]','windowName','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=400,height=330')\">"._YESLETTER."</a> | <a href=\"javascript:myopen('admin.php?action=noletter&uid=$story[uid]&sid=$story[sid]','windowName','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=400,height=330')\">"._NOLETTER."</a><br><br>";
			if($store == "files")
			{
				$file = "$storiespath/$author[penname]/$story[sid].txt";
				$log_file = fopen($file, "r");
				$file_contents = fread($log_file, filesize($file));
				$output .= (nl2br(strip_tags($file_contents, '<br><b><i><u><center><img><a><hr><p><ul><li><ol>')));
				fclose($log_file);
			}
			else if($store == "mysql")
			{
				$output .= (nl2br(strip_tags($story[storytext], '<b><i><u><center><img><a><hr><p><ul><li><ol>')));
			}
			$output .= "<table><form method=\"POST\" enctype=\"multipart/form-data\" action=\"admin.php?action=validate\">
			<tr><td>"._TITLE.": </td><td><INPUT name=\"title\" value=\"$story[title]\"><br>
			</td></tr><tr><td>"._CHAPTERTITLE.": </td><td><INPUT name=\"chapter\" value=\"$story[chapter]\"><br>
			</td></tr><tr><td>"._SUMMARY.": </td><td><textarea name=\"summary\" cols=\"40\" rows=\"4\">$story[summary]</textarea><br>
			</td></tr><tr><td>"._CATEGORY.": </td><td><select name=\"catid\">";
			$result3 = mysql_query("SELECT catid, parentcatid, category, leveldown FROM ".$tableprefix."fanfiction_categories ORDER BY displayorder");
			while($categorychoose = mysql_fetch_array($result3))
			{
				$space = "";
				if ($categorychoose[parentcatid] != "-1")
				{
					$parent = mysql_query("select leveldown,category from ".$tableprefix."fanfiction_categories WHERE catid='$categorychoose[parentcatid]'");
					$parentresult = mysql_fetch_array($parent);

					for ($count = 0; $count <= $categorychoose[leveldown]; $count++)
					{
					    $space .= "&nbsp;&nbsp;&nbsp;";
					}
				}
				else
					$space = "";
				$output .= "<option value=\"".$categorychoose[catid]."\"";
				if($categorychoose[catid] == $story[catid])
					$output .= " selected";
				$output .= ">";
				$output .= "$space-$categorychoose[category]";
				$output .= "</option>";
			}
			$output .= "</select>";
			$output .= "</td></tr><tr><td>"._STORYTEXT.": </td><td><textarea name=\"storytext\" cols=\"70\" rows=\"10\">";
			if($store == "files")
			{
				$file = "$storiespath/$author[penname]/$story[sid].txt";
				$log_file = fopen($file, "r");
				$file_contents = fread($log_file, filesize($file));
				$output .= (strip_tags($file_contents, '<b><i><u><center><img><a><hr><p><ul><li><ol>'));
				fclose($log_file);
			}
			else if($store == "mysql")
			{
				$output .= (nl2br(strip_tags($story[storytext], '<b><i><u><center><img><a><hr><p><ul><li><ol>')));
			}
			$output .= "</textarea></td></tr><tr><td colspan=\"2\"><INPUT type=\"hidden\" name=\"sid\" value=\"$sid\"><INPUT type=\"hidden\" name=\"penname\" value=\"$author[penname]\"><INPUT type=\"submit\" name=\"submit\" value=\""._EDIT."\"></form></td></tr></table>";
			$output .= "<br><br>";
			$output .= "<a href=\"admin.php?action=validate&sid=$story[sid]&validate=yes\">Validate</a> | <a href=\"stories.php?action=deletestory&sid=$story[sid]\">Delete</a> | <a href=\"javascript:myopen('admin.php?action=yesletter&uid=$story[uid]&sid=$story[sid]','windowName','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=400,height=330')\">Yes Letter</a> | <a href=\"javascript:myopen('admin.php?action=noletter&uid=$story[uid]&sid=$story[sid]','windowName','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=400,height=330')\">No Letter</a><br>";
		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function yesletter($sid, $uid, $submit, $email, $ademail, $letter, $subject)
{
	global $tableprefix, $level, $adminemail, $sitename, $siteemail;
	include ("adminheader.php");
	if (($_SESSION['adminloggedin'] != "1") || (($level != '1') && ($level != '2') && ($level != '3')))
	{
		$output .= ""._NOTALLOWED."<BR><BR>";
	}
	else
	{
		if($submit)
		{

			if($ademail == "")
				$ademail = $siteemail;
			$subject = stripslashes($subject);
			$letter = stripslashes($letter);
			
		$headers .= "From: $ademail<$ademail>\n";
		$headers .= "X-Sender: <$ademail>\n";
		$headers .= "X-Mailer: PHP\n"; //mailer
		$headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal
		$headers .= "Return-Path: <$ademail>\n";
		
		mail($email, $subject, $letter, $headers);

			echo ""._EMAILSENT."";
		}
		else
		{
			$authorquery = mysql_query("SELECT email,penname FROM ".$tableprefix."fanfiction_authors WHERE uid = '$uid'");
			$author = mysql_fetch_array($authorquery);
			$storyquery = mysql_query("SELECT title, chapter FROM ".$tableprefix."fanfiction_stories WHERE sid = '$sid'");
			$story = mysql_fetch_array($storyquery);
			$letterquery = mysql_query("SELECT thankyou FROM ".$tableprefix."fanfiction_settings");
			$letter = mysql_fetch_array($letterquery);
			echo "<body>";
			echo "<table><tr><td>Story:</td><td>$story[title]: $story[chapter]</td></tr>";
			echo "<tr><td>By:</td><td>$author[penname]</td></tr>";
			echo "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"admin.php?action=yesletter\">
				<tr><td>To:</td><td><INPUT name=\"email\" value=\"$author[email]\"></td></tr>
				<tr><td>From:</td><td><INPUT name=\"ademail\" value=\"$adminemail\"></td></tr>
				tr><td>Subject:</td><td><INPUT name=\"subject\" value=\"Your Submission to $sitename\"></td></tr>
				<tr><td colspan=\"2\"><TEXTAREA name=\"letter\" cols=\"60\" rows=\"8\">$letter[thankyou]</TEXTAREA></td></tr><tr><td colspan=\"2\"><INPUT type=\"submit\" name=\"submit\" value=\""._SUBMIT."\"></form></td></tr></table></body></html>";
		}
	}
}

function noletter($sid, $uid, $submit, $email, $ademail, $letter, $subject)
{
	global $tableprefix, $level, $adminemail, $sitename, $siteemail;
	include ("adminheader.php");
	if (($_SESSION['adminloggedin'] != "1") || (($level != '1') && ($level != '2') && ($level != '3')))
	{
		$output .= ""._NOTALLOWED."<BR><BR>";
	}
	else
	{
		if($submit)
		{

			$subject = stripslashes($subject);
			$letter = stripslashes($letter);
			if($ademail == "")
				$ademail = $siteemail;

		$headers .= "From: $ademail<$ademail>\n";
		$headers .= "X-Sender: <$ademail>\n";
		$headers .= "X-Mailer: PHP\n"; //mailer
		$headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal
		$headers .= "Return-Path: <$ademail>\n";
		
		mail($email, $subject, $letter, $headers);

			echo ""._EMAILSENT."";
		}
		else
		{
			$authorquery = mysql_query("SELECT email,penname FROM ".$tableprefix."fanfiction_authors WHERE uid = '$uid'");
			$author = mysql_fetch_array($authorquery);
			$storyquery = mysql_query("SELECT title, chapter FROM ".$tableprefix."fanfiction_stories WHERE sid = '$sid'");
			$story = mysql_fetch_array($storyquery);
			$letterquery = mysql_query("SELECT nothankyou FROM ".$tableprefix."fanfiction_settings");
			$letter = mysql_fetch_array($letterquery);
			echo "<body>";
			echo "<table><tr><td>Story:</td><td>$story[title]: $story[chapter]</td></tr>";
			echo "<tr><td>By:</td><td>$author[penname]</td></tr>";
			echo "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"admin.php?action=yesletter\">
				<tr><td>To:</td><td><INPUT name=\"email\" value=\"$author[email]\"></td></tr>
				<tr><td>From:</td><td><INPUT name=\"ademail\" value=\"$adminemail\"></td></tr>
				tr><td>Subject:</td><td><INPUT name=\"subject\" value=\"Your Submission to $sitename\"></td></tr>
				<tr><td colspan=\"2\"><TEXTAREA name=\"letter\" cols=\"60\" rows=\"8\">$letter[nothankyou]</TEXTAREA></td></tr><tr><td colspan=\"2\"><INPUT type=\"submit\" name=\"submit\" value=\""._SUBMIT."\"></form></td></tr></table></body></html>";
		}
	}
}

?>