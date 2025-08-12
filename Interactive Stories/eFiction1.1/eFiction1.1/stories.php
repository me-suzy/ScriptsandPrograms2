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

function newstory($submit, $catid, $gid, $rid, $charid, $storyfile, $storytext, $wid, $title, $chapter, $summary, $uid, $rr, $mailinglist, $numchars, $numgenres)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $store, $useruid, $userpenname, $numcats, $autovalidate, $sitename, $siteemail, $url, $roundrobins, $storiespath;
	include ("header.php");
	$result = mysql_query("SELECT copyright,rules FROM ".$tableprefix."fanfiction_settings");
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
	$output .= "<center><h4>"._ADDNEWSTORY."</h4></center>";
	if($_SESSION['loggedin'] == "1")
	{
		if(($submit == ""._ADDSTORY."") && ((($rid == "") || ($title == "") || ($chapter == "") || ($summary == "")) || (($charid == "") && ($numchars != "0")) || (($gid == "") && ($numgenres != "0"))))
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
					if (($_FILES['storyfile']['type'] != ('text/html')) && ($_FILES['storyfile']['type'] != ('text/plain')))
					{
 						$output .= "<b>"._INVALIDUPLOAD."</b><br><br>";
					}
					else
					{
						$texts = file($storyfile);

						foreach ($texts as $text) {
	  				$story .= nl2br(stripslashes(strip_tags($text, '<b><i><u><center><img><a><hr><p><ul><li><ol>')));
						}
					}

				}
				else if($storytext != "")
				{
					$story = nl2br(stripslashes(strip_tags($storytext, '<b><i><u><center><img><a><hr><p><ul><li><ol>')));
				}
				$title = stripslashes($title);
				$chapter = stripslashes($chapter);
				$summary = stripslashes($summary);
				$charstring = stripslashes($charstring);
				$genrestring = stripslashes($genrestring);
				$warningstring = stripslashes($warningstring);
				$output .= $_FILES['storyfile']['type'];
				$output .= ""._TITLE.": $title<br>
				"._CHAPTERTITLE.": $chapter<br>
				"._CHARACTERS.": $charstring<br>
				"._GENRES.": $genrestring<br>
				"._RATING.": $rid<br>
				"._WARNINGS.": $warningstring<br>
				"._SUMMARY.": $summary<br><br>";

				$output .= "$story";
				$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"stories.php?action=newstory\">
					<table><tr><td>
					"._AUTHOR.":
					</td><td>$userpenname <INPUT type=\"hidden\" name=\"uid\" value=\"$useruid\">
					</td><td>
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
					</td><td>"._GENREINFO."

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
					</td><td>"._CHARINFO."

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
						</td><td>"._WARNINGINFO."

						</td></tr>";
					}
					if($roundrobins == "1")
					{
						$output .= "<tr><td>
						"._ROUNDROBIN."?
						</td><td>
						<INPUT type=\"checkbox\" name=\"rr\"";
						if($rr == "on")
							$output .= " checked";
						$output .= ">
						</td><td>

						</td></tr>";
					}
					$output .= "<tr><td>
					"._POSTTOLIST.":
					</td><td>
					<INPUT name=\"mailinglist\" value=\"$mailinglist\">
					</td><td>"._MAILINFO."

					</td></tr><tr><td>
					"._STORYTEXTTEXT.":
					</td><td>
					<TEXTAREA name=\"storytext\" cols=\"60\" rows=\"10\">$newstorytext</TEXTAREA>
					</td><td valign=\"top\">
					"._STORYTEXTINFO."
					</td></tr><tr><td colspan=\"3\" align=\"center\"><INPUT type=\"hidden\" name=\"uid\" value=\"$useruid\">
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
			if($rr == "on")
				$rr = "1";


			$result2 = mysql_query("SELECT validated,email FROM ".$tableprefix."fanfiction_authors WHERE uid = '$useruid'");
			$user = mysql_fetch_array($result2);
			if($autovalidate == "1")
			{
				$validated = "1";
				include ("functions.php");
				categoryitems($catid, 1);
			}
			else
			{
				if($user[validated] == "1")
				{
					$validated = "1";
					include ("functions.php");
					categoryitems($catid, 1);
				}
				else
				{
					$validated = "0";
					$adminquery = mysql_query("SELECT email,contact,categories FROM ".$tableprefix."fanfiction_authors WHERE level != '0' AND level != '4'");
					while($admins = mysql_fetch_array($adminquery))
					{
						if($admins[contact] == "1")
						{
							if($admins[categories] == "0")
							{
								$subject = ""._NEWSTORYAT." $sitename";
								$mailtext = ""._NEWSTORYAT2." $sitename. $url/admin.php?action=submitted";
								
								$headers .= "From: $siteemail<$siteemail>\n";
								$headers .= "X-Sender: <$siteemail>\n";
								$headers .= "X-Mailer: PHP\n"; //mailer
								$headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal
								$headers .= "Return-Path: <$siteemail>\n";
								
								mail($admins[email], $subject, $mailtext, $headers);
							}
							else
							{
								$array = split(",", $admins[categories]);
								if(in_array($catid, $array))
								{
									$subject = ""._NEWSTORYAT." $sitename";
									$mailtext = ""._NEWSTORYAT2." $sitename. $url/admin.php?action=submitted";
									$headers .= "From: $siteemail<$siteemail>\n";
									$headers .= "X-Sender: <$siteemail>\n";
									$headers .= "X-Mailer: PHP\n"; //mailer
									$headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal
									$headers .= "Return-Path: <$siteemail>\n";
									
									mail($admins[email], $subject, $mailtext, $headers);
								}
							}
						}
					}
				}
			}
			$wordcount = count( preg_split("/[\W]+/", $storytext) );
			if($store == "mysql")
			{
				mysql_query("INSERT INTO ".$tableprefix."fanfiction_stories (title, chapter, summary, catid, gid, charid, wid, rid, date, updated, uid, validated, storytext, rr, wordcount) VALUES ('$title', '$chapter', '$summary', '$catid', '$genrestring', '$charstring', '$warningstring', '$rid', now(), now(), '$uid', '$validated', '$storytext', '$rr', '$wordcount')");
				$id = mysql_insert_id();
				mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET psid = '$id' WHERE sid = '$id'");
				$output .= ""._STORYADDED."";
			}
			else
			{
				$insertstory = mysql_query("INSERT INTO ".$tableprefix."fanfiction_stories (title, chapter, summary, catid, gid, charid, wid, rid, date, updated, uid, validated, rr, wordcount) VALUES ('$title', '$chapter', '$summary', '$catid', '$genrestring', '$charstring', '$warningstring', '$rid', now(), now(), '$uid', '$validated', '$rr', '$wordcount')");
				$storyid = mysql_insert_id();
				mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET psid = '$storyid' WHERE sid = '$storyid'");
				if( !file_exists( "$storiespath/$userpenname/" ) )
				{
					mkdir("$storiespath/$userpenname", 0755);
					chmod("$storiespath/$userpenname", 0777);
				}

				$handle = fopen("$storiespath/$userpenname/$storyid.txt", 'w');

				if ($handle)
				{
					fwrite($handle, stripslashes ($storytext));
					fclose($handle);
				}
				chmod("$storiespath/$userpenname/$storyid.txt", 0644);
				$output .= ""._STORYADDED."";
			}
			if((isset($mailinglist)) && ($mailinglist != ""))
			{
				$storytext = ereg_replace ("(<br />|<br/>)","", $storytext);
				$storytext = stripslashes($storytext);
				$subject = stripslashes("$title: $chapter");
				
				$headers .= "From: $user[email]<$user[email]>\n";
				$headers .= "X-Sender: <$user[email]>\n";
				$headers .= "X-Mailer: PHP\n"; //mailer
				$headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal
				$headers .= "Return-Path: <$user[email]>\n";
				
				mail($mailinglist, $subject, $storytext, $headers);
			}			
		}
		else if(($submit == ""._SELECT."") || ($numcats == '1'))
		{
			if($catid == "bad")
			{
				$output .= "<center>"._BACKCATEGORY."</center>";
			}
			else
			{
				$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"stories.php?action=newstory\">
					<table><tr>";
					if($numcats == "1")
					{
						$output .= "<td colspan=\"3\">$settings[rules]<br><br></td></tr><tr>";
					}
					$output .= "<td>"._AUTHOR.":
					</td><td>$userpenname <INPUT type=\"hidden\" name=\"uid\" value=\"$useruid\">
					</td><td>
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
						$catquery = mysql_query("SELECT catid FROM ".$tableprefix."fanfiction_categories");
						$catresult = mysql_fetch_array($catquery);
						$catid = $catresult[catid];
						$output .= "<INPUT type=\"hidden\" name=\"catid\" value=\"$catid\">";
					}
					$output .= "
					"._TITLE.":
					</td><td>
					<INPUT name=\"title\" size=\"30\">
					</td><td>

					</td></tr><tr><td>
					"._CHAPTERTITLE.":
					</td><td>
					<INPUT name=\"chapter\" size=\"30\">
					</td><td>

					</td></tr><tr><td>
					"._SUMMARY.":
					</td><td>
					<TEXTAREA name=\"summary\" cols=\"45\" rows=\"4\"></textarea>
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
								$output .= "<option value=\"$genreresults[genre]\">$genreresults[genre]</option>";
							}
						$output .= "
						</select></td><td>"._GENREINFO."

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
								$output .= "<option value=\"$charresults[charname]\">$charresults[charname]</option>";
							}

						$output .= "
						</select></td><td>"._CHARINFO."

						</td></tr>";
					}
					$output .= "<tr><td>
					"._RATING.":
					</td><td>
					<select name=\"rid\">";
					$result = mysql_query("SELECT rid, rating FROM ".$tableprefix."fanfiction_ratings");
					while ($ratingresults = mysql_fetch_array($result))
						{
							$output .= "<option value=\"$ratingresults[rating]\">$ratingresults[rating]</option>";
						}
					$output .= "
					</td><td>

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
								$output .= "<option value=\"$warningresults[warning]\">$warningresults[warning]</option>";
							}
						$output .= "
						</select></td><td>"._WARNINGINFO."

						</td></tr>";
					}
					if($roundrobins == "1")
					{
						$output .= "<tr><td>
						"._ROUNDROBIN."?
						</td><td>
						<INPUT type=\"checkbox\" name=\"rr\">
						</td><td>

						</td></tr>";
					}

					$output .= "<tr><td>
					"._POSTTOLIST.":
					</td><td>
					<INPUT name=\"mailinglist\">
					</td><td>"._MAILINFO."

					</td></tr><tr><td>
					"._STORYTEXTTEXT.":
					</td><td>
					<TEXTAREA name=\"storytext\" cols=\"60\" rows=\"10\" onClick=\"this.form.storyfile.disabled=true\"></TEXTAREA>
					</td><td rowspan=\"2\" valign=\"top\">
					"._STORYTEXTNEW."
					</td></tr><tr><td>
					"._STORYTEXTFILE.":
					</td><td>
					<INPUT type=\"file\" name=\"storyfile\" onClick=\"this.form.storytext.disabled=true\">
					</td></tr><tr><td colspan=\"3\" align=\"center\">
					<INPUT type=\"submit\" value=\""._PREVIEW."\" name=\"submit\">
					</table></form>";
				}
		}
		else
		{
			$result = mysql_query("SELECT catid, parentcatid, category, leveldown, locked FROM ".$tableprefix."fanfiction_categories ORDER BY displayorder");
			$numcats = mysql_num_rows($result);
			if($numcats == "0")
			{
				$output .= "<center>"._CATMUSTBEADDED."</center>";
			}
			else
			{
				$output .= "$settings[rules]";
	
				$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"stories.php?action=newstory\"><table align=\"center\"><tr><td valign=\"top\">Category: ";
	
				$output .= "<select name=\"catid\" onChange=\"disableSubmit(this)\">";
				$output .= "<option value=\"bad\" id=\"disable\">"._CHOOSECATEGORY."</option>";
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
					$output .= "<option value=\"".$categorychoose[catid]."\"";
					if($categorychoose[locked] == "1")
						$output .= " id=\"disable\" class=\"locked\"";
					else
						$output .= " id=\"enable\" class=\"notlocked\"";
					$output .= ">";
					$output .= "$space-$categorychoose[category]";
					$output .= "</option>";
				}
				$output .= "</select>";
				$output .= "</td><td valign=\"top\"><INPUT type=\"submit\" name=\"submit\" value=\""._SELECT."\"></form></td></tr></table>";
			}
		}
	}
	else
	{
		$output .= "<center>".PLEASELOGIN."";
	}

	$tpl->assign( "output", $output );
	$tpl->printToScreen();

}

function addchapter($submit, $catid, $gid, $rid, $charid, $storyfile, $storytext, $wid, $title, $chapter, $summary, $uid, $sid, $psid, $rr, $mailinglist, $numchars, $numgenres)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $store, $useruid, $userpenname, $numcats, $autovalidate, $sitename, $siteemail, $url, $storiespath;
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
	$output .= "<center><h4>"._ADDNEWCHAPTER."</h4></center>";
	if($_SESSION['loggedin'] == "1")
	{
		if(($submit == ""._ADDSTORY."") && ((($rid == "") || ($title == "") || ($chapter == "") || ($summary == "")) || (($charid == "") && ($numchars != "0")) || (($gid == "") && ($numgenres != "0"))))
		{
			$submit = ""._PREVIEW."";
		}
		if($submit == ""._PREVIEW."")
		{
				if ((($storyfile == "") || ($storyfile == "none")) && ($storytext == ""))
				{
					$output .= "<center><a href=\"stories.php?action=addchapter&add=add&sid=$sid&submit=newchapter\">"._TRYAGAIN."</a>";
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
	 						echo "Invalid Upload";
						}
						else
						{
							$texts = file($storyfile);

							foreach ($texts as $text)
							{
		  					$story .= nl2br(stripslashes(strip_tags($text, '<b><i><u><center><img><a><hr><p><ul><li><ol>')));
							}
						}

					}
					else if($storytext != "")
					{
						$story = nl2br(stripslashes(strip_tags($storytext, '<b><i><u><center><img><a><hr><p><ul><li><ol>')));
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
					$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"stories.php?action=addchapter\">
						<table><tr><td>
						"._AUTHOR.":
						</td><td>$userpenname <INPUT type=\"hidden\" name=\"uid\" value=\"$useruid\">
						</td><td>
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
						if(isset($sid))
						{
							$output .= "<INPUT type=\"hidden\" name=\"sid\" value=\"$sid\">";
						}
						if($title == "")
							$output .= "<font style=\"color:red\">";
						$output .= "
						"._TITLE.":";
						if($title == "")
							$output .= "</font>";
						$output .= "</td><td>$title
						<INPUT name=\"title\" type=\"hidden\" value=\"$title\">
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
						</td><td>"._GENREINFO."

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
						</td><td>"._CHARINFO."

						</td></tr><tr><td>
						"._RATING."
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

						</td></tr><tr><td>
						"._WARNINGS.":
						</td><td>";
						$result = mysql_query("SELECT wid, warning FROM ".$tableprefix."fanfiction_warnings");
						$numrows = mysql_num_rows($result);
						if($numrows != 0)
						{
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
						}
						$newstorytext = strip_tags($story, '<b><i><u><center><img><a><hr><p><ul><li><ol>');
						$output .= "
						</td><td>"._WARNINGINFO."

						</td></tr><tr><td>
						"._POSTTOLIST.":
						</td><td>
						<INPUT name=\"mailinglist\" value=\"$mailinglist\">
						</td><td>"._MAILINFO."

						</td></tr><tr><td>
						"._STORYTEXTTEXT.":
						</td><td>
						<INPUT name=\"rr\" value=\"$rr\" type=\"hidden\">
						<TEXTAREA name=\"storytext\" cols=\"60\" rows=\"10\">$newstorytext</TEXTAREA>
						</td><td valign=\"top\">
						"._STORYTEXTCLEANUP."
						</td></tr><tr><td colspan=\"3\" align=\"center\"><INPUT type=\"hidden\" name=\"uid\" value=\"$useruid\"><INPUT type=\"hidden\" name=\"psid\" value=\"$psid\">
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

				$result2 = mysql_query("SELECT validated,email FROM ".$tableprefix."fanfiction_authors WHERE uid = '$useruid'");
				$user = mysql_fetch_array($result2);
				$number = mysql_query("SELECT psid FROM ".$tableprefix."fanfiction_stories WHERE psid = '$psid' AND sid != psid");
				$inorder = (mysql_num_rows($number)) + 1;
				if($autovalidate == "1")
					$validated = "1";
				else
				{
					if($user[validated] == "1")
					{
						$validated = "1";
					}
					else
					{
						$validated = "0";
						$adminquery = mysql_query("SELECT email,contact,categories FROM ".$tableprefix."fanfiction_authors WHERE level != '0' AND level != '4'");
						while($admins = mysql_fetch_array($adminquery))
						{
							if($admins[contact] == "1")
							{
								if($admins[categories] == "0")
								{
									$subject = ""._NEWSTORYAT." $sitename";
									$mailtext = ""._NEWSTORYAT2." $sitename. $url/admin.php?action=submitted";
									$headers .= "From: $siteemail<$siteemail>\n";
									$headers .= "X-Sender: <$siteemail>\n";
									$headers .= "X-Mailer: PHP\n"; //mailer
									$headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal
									$headers .= "Return-Path: <$siteemail>\n";
									
									mail($admins[email], $subject, $mailtext, $headers);
								}
								else
								{
									$array = split(",", $admins[categories]);
									if(in_array($catid, $array))
									{
										$subject = ""._NEWSTORYAT." $sitename";
										$mailtext = ""._NEWSTORYAT2." $sitename. $url/admin.php?action=submitted";
										$headers .= "From: $siteemail<$siteemail>\n";
										$headers .= "X-Sender: <$siteemail>\n";
										$headers .= "X-Mailer: PHP\n"; //mailer
										$headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal
										$headers .= "Return-Path: <$siteemail>\n";
										
										mail($admins[email], $subject, $mailtext, $headers);
									}
								}
							}
						}
					}
				}
				$wordcount = count( preg_split("/[\W]+/", $storytext) );
				if($store == "mysql")
				{
					mysql_query("INSERT INTO ".$tableprefix."fanfiction_stories (title, chapter, summary, catid, gid, charid, wid, rid, date, uid, validated, storytext, psid, inorder, wordcount, updated) VALUES ('$title', '$chapter', '$summary', '$catid', '$genrestring', '$charstring', '$warningstring', '$rid', now(), '$uid', '$validated', '$storytext', '$psid', '$inorder', '$wordcount', now())");
					$output .= ""._STORYADDED."";
				}
				else
				{
					$insertstory = mysql_query("INSERT INTO ".$tableprefix."fanfiction_stories (title, chapter, summary, catid, gid, charid, wid, rid, date, uid, validated, psid, inorder, rr, wordcount, updated) VALUES ('$title', '$chapter', '$summary', '$catid', '$genrestring', '$charstring', '$warningstring', '$rid', now(), '$uid', '$validated', '$psid', '$inorder', '$rr', '$wordcount', now())");
					$storyid = mysql_insert_id();
					if( !file_exists( "$storiespath/$userpenname/" ) )
					{
						mkdir("$storiespath/$userpenname", 0755);
						chmod("$storiespath/$userpenname", 0777);
					}

					$handle = fopen("$storiespath/$userpenname/$storyid.txt", 'w');

					if ($handle)
					{
						fwrite($handle, stripslashes ($storytext));
						fclose($handle);
					}
					$output .= ""._STORYADDED."";
				}
				if((isset($mailinglist)) && ($mailinglist != ""))
				{
					$storytext = ereg_replace ("(<br />|<br/>)","", $storytext);
					$storytext = stripslashes($storytext);
					$subject = stripslashes("$title: $chapter");
					
					$headers .= "From: $user[email]<$user[email]>\n";
					$headers .= "X-Sender: <$user[email]>\n";
					$headers .= "X-Mailer: PHP\n"; //mailer
					$headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal
					$headers .= "Return-Path: <$user[email]>\n";
					
					mail($mailinglist, $subject, $storytext, $headers);
				}
				mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET updated = now() WHERE sid = '$psid'");
			}
			else if($submit == "newchapter")
			{
				$query = mysql_query("SELECT carry FROM ".$tableprefix."fanfiction_authors WHERE uid = '$useruid'");
				$carry = mysql_fetch_array($query);
				$result4 = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_stories WHERE sid = '$sid'");
				$newchapter = mysql_fetch_array($result4);
				if($carry[carry] == "1")
				{
					$orchapter = $newchapter[chapter];
					$orsummary = $newchapter[summary];
					$orrating = $newchapter[rid];
				}
				if(($newchapter[rr] == "1") || ($newchapter[uid] == $useruid))
				{
					$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"stories.php?action=addchapter\">
							<table><tr><td>
							"._AUTHOR.":
							</td><td>$userpenname <INPUT type=\"hidden\" name=\"uid\" value=\"$useruid\">
							</td><td>
							</td></tr><tr><td>";
							if ($numcats != '1')
							{
								$output .= ""._CATEGORY.":
								</td><td>";
								$result = mysql_query("SELECT category FROM ".$tableprefix."fanfiction_categories WHERE catid = '$newchapter[catid]'");
								$catinfo = mysql_fetch_array($result);
								$output .= "$catinfo[category] <INPUT type=\"hidden\" name=\"catid\" value=\"$newchapter[catid]\">
								</td><td>

								</td></tr><tr><td>";
							}
							else
							{
								$output .= "<INPUT type=\"hidden\" name=\"catid\" value=\"$newchapter[catid]\">";
							}
							$output .= "
							"._TITLE.":
							</td><td>$newchapter[title]
							<INPUT type=\"hidden\" name=\"title\" value=\"$newchapter[title]\" size=\"30\">
							</td><td>

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
											if(strstr($newchapter[gid], $genreresults[genre]))
											$output .= " selected";
										}
										$output .= ">$genreresults[genre]</option>";
									}
									

								$output .= "
								</select></td><td>"._GENREINFO."

								</td></tr>";
							}
							$result = mysql_query("SELECT charid, charname FROM ".$tableprefix."fanfiction_characters WHERE catid = '$newchapter[catid]'");
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
											if(strstr($newchapter[charid], $charresults[charname]))
											$output .= " selected";
										}
										$output .= ">$charresults[charname]</option>";
									}

								$output .= "
								</select></td><td>"._CHARINFO."

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
							</td><td>

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
											if(strstr($newchapter[wid], $warningresults[warning]))
											$output .= " selected";
										}
										$output .= ">$warningresults[warning]</option>";
									}
								$output .= "
								</select></td><td>"._WARNINGINFO."

								</td></tr>";
							}
							$output .= "<tr><td>
							"._POSTTOLIST.":
							</td><td>
							<INPUT name=\"mailinglist\">
							</td><td>"._MAILINFO."

							</td></tr><tr><td>
							"._STORYTEXTTEXT.":
							</td><td>
							<TEXTAREA name=\"storytext\" cols=\"60\" rows=\"10\" onClick=\"this.form.storyfile.disabled=true\"></TEXTAREA>
							</td><td rowspan=\"2\" valign=\"top\">
							"._STORYTEXTNEW."
							</td></tr><tr><td>
							"._STORYTEXTFILE.":
							</td><td>
							<INPUT type=\"hidden\" name=\"rr\" value=\"$newchapter[rr]\">
							<INPUT type=\"file\" name=\"storyfile\" onClick=\"this.form.storytext.disabled=true\">
							</td></tr><tr><td colspan=\"3\" align=\"center\"><INPUT type=\"hidden\" name=\"psid\" value=\"$sid\">
							<INPUT type=\"submit\" value=\""._PREVIEW."\" name=\"submit\">
							</table></form>";
						}
						else
						{
							$output .= "<center>"._NOTAUTHOR2."</center>";
						}
			}
			else
			{
				$result2 = mysql_query("SELECT title,sid FROM ".$tableprefix."fanfiction_stories WHERE uid = '$useruid' AND psid = sid ORDER BY title");
				$output .= "<table align=\"center\" width=\"80%\" class=\"tblborder\" cellpadding=\"2\" cellspacing=\"0\">";
				$output .= "<tr><td class=\"tblborder\"><b>"._TITLE."</b></td><td class=\"tblborder\"><b>"._CHAPTERS."</b></td><td class=\"tblborder\"><b>"._OPTIONS."</b></td></tr>";
				while ($topstories = mysql_fetch_array($result2))
				{
					$result3 = mysql_query("SELECT psid FROM ".$tableprefix."fanfiction_stories WHERE psid = '$topstories[sid]'");
					$numrows = (mysql_num_rows($result3));
					$output .= "<tr><td class=\"tblborder\">$topstories[title]</td><td class=\"tblborder\">$numrows</td><td class=\"tblborder\"><a href=\"stories.php?action=addchapter&add=add&sid=$topstories[sid]&submit=newchapter\">"._ADDCHAPTER."</a></td></tr>";
				}
				$output .= "</table>";
			}
		}
	else
	{
		$output .= "<center>".PLEASELOGIN."";
	}

	$tpl->assign( "output", $output );
	$tpl->printToScreen();

}

function viewstories($go, $sid, $psid, $inorder, $com)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $useruid, $storiespath;
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

	$output .= "<center><h4>"._MANAGESTORIES."</h4></center>";
	if($_SESSION['loggedin'] == "1")
	{
		$result4 = mysql_query("SELECT uid FROM ".$tableprefix."fanfiction_stories WHERE sid = '$sid'");
		$edit = mysql_fetch_array($result4);
		if(($go == "up") && ($useruid == $edit[uid]))
		{
			$oneabove = $inorder - 1;
			mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET inorder = '$inorder' WHERE psid = '$psid' and inorder = '$oneabove'");
			mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET inorder = '$oneabove' WHERE sid = '$sid'");
		}
		if(($go == "down") && ($useruid == $edit[uid]))
		{
			$oneabove = $inorder + 1;
			mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET inorder = '$inorder' WHERE psid = '$psid' and inorder = '$oneabove'");
			mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET inorder = '$oneabove' WHERE sid = '$sid'");
		}

		if(($com == "yes") && ($useruid == $edit[uid]))
		{
			mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET completed = 1 WHERE sid = '$sid'");
		}

		if(($com == "no") && ($useruid == $edit[uid]))
		{
			mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET completed = 0 WHERE sid = '$sid'");
		}

		$result = mysql_query("SELECT title,sid,completed,counter FROM ".$tableprefix."fanfiction_stories WHERE uid = '$useruid' AND psid=sid ORDER BY title");
		$output .= "<table align=\"center\" width=\"80%\" class=\"tblborder\" cellpadding=\"2\" cellspacing=\"0\">";
		$output .= "<tr><td class=\"tblborder\"><b>"._TITLE."</b></td><td class=\"tblborder\" colspan=\"2\"><b>"._MOVE."</b></td><td class=\"tblborder\"><b>"._OPTIONS."</b></td><td class=\"tblborder\"><b>"._READS."</b></td></tr>";
		while($topstories = mysql_fetch_array($result))
		{
			$output .= "<tr><td class=\"tblborder\"><b>$topstories[title]</b> "._CHAPTERINFO.": ";
			$output .= " <a href=\"stories.php?action=viewstories&sid=$topstories[sid]&com=";
			if($topstories[completed] == "1")
				$output .= "no\">"._YES."";
			else
				$output .= "yes\">"._NO."";
			$output .="</a></td><td class=\"tblborder\" colspan=\"2\">&nbsp;</td><td class=\"tblborder\" width=\"165\"><a href=\"stories.php?action=editstory&sid=$topstories[sid]\">"._EDIT."</a> | <a href=\"stories.php?action=deletestory&sid=$topstories[sid]\">"._DELETE."</a>  | <a href=\"stories.php?action=addchapter&add=add&sid=$topstories[sid]&submit=newchapter\">"._ADDCHAPTER."</a></td><td class=\"tblborder\">$topstories[counter]</td></tr>";
			$result2 = mysql_query("SELECT chapter,sid,psid,inorder,counter FROM ".$tableprefix."fanfiction_stories WHERE psid = '$topstories[sid]' AND psid != sid AND uid = '$useruid' ORDER BY inorder");
			$numrow = mysql_num_rows($result2);
			while($chapters = mysql_fetch_array($result2))
			{
				$output .= "<tr><td class=\"tblborder\">&nbsp;&nbsp;&nbsp;&nbsp;$chapters[chapter]</td><td class=\"tblborder\" width=\"13\">";
				if($chapters[inorder] != "1")
					$output .= "<a href=\"stories.php?action=viewstories&go=up&sid=$chapters[sid]&psid=$chapters[psid]&inorder=$chapters[inorder]\"><img src=\"images/arrowup.gif\" width=\"13\" height=\"18\" border=\"0\"></a>";
				else
					$output .= "&nbsp;";
				$output .= "</td><td class=\"tblborder\" width=\"13\">";
				if($chapters[inorder] != "$numrow")
					$output .=  " <a href=\"stories.php?action=viewstories&go=down&sid=$chapters[sid]&psid=$chapters[psid]&inorder=$chapters[inorder]\"><img src=\"images/arrowdown.gif\" width=\"13\" height=\"18\" border=\"0\"></a>";
				else
					$output .= "&nbsp;";
				$output .= "</td><td class=\"tblborder\" width=\"100\"><a href=\"stories.php?action=editstory&sid=$chapters[sid]\">"._EDIT."</a> | <a href=\"stories.php?action=deletestory&sid=$chapters[sid]\">"._DELETE."</a></td><td class=\"tblborder\">$chapters[counter]</td></tr>";
			}
		}
		$output .= "</table><br>";
	}
	else
	{
		$output .= "<center>".PLEASELOGIN."";
	}

	$tpl->assign( "output", $output );
	$tpl->printToScreen();

}

function editstory($submit, $catid, $gid, $rid, $charid, $storyfile, $storytext, $wid, $title, $chapter, $summary, $uid, $sid, $featured, $numchars, $numgenres, $newcat, $psid)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $store, $useruid, $userpenname, $numcats, $autovalidate, $storiespath;
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
	$output .= "<center><h4>"._EDITSTORY."</h4></center>";
	if(($_SESSION['loggedin'] == "1") || ($_SESSION['adminloggedin'] == "1"))
	{
	 	if(($submit == ""._ADDSTORY."") && ((($rid == "") || ($title == "") || ($chapter == "") || ($summary == "")) || (($charid == "") && ($numchars != "0")) || (($gid == "") && ($numgenres != "0"))))
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
				$title = stripslashes($title);
				$chapter = stripslashes($chapter);
				$summary = stripslashes($summary);
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
	  				$story .= nl2br(stripslashes(strip_tags($text, '<b><i><u><center><img><a><hr><p><ul><li><ol>')));
						}
					}

				}
				else if($storytext != "")
				{
					$story = nl2br(stripslashes(strip_tags($storytext, '<b><i><u><center><img><a><hr><p><ul><li><ol>')));
				}
				$output .= ""._TITLE.": $title<br>
				"._CHAPTERTITLE.": $chapter<br>
				"._CHARACTERS.": $charstring<br>
				"._GENRES.": $genrestring<br>
				"._RATING.": $rid<br>
				"._WARNINGS.": $warningstring<br>
				"._SUMMARY.": $summary<br><br>";

				$output .= "$story";
				$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"stories.php?action=editstory\">
					<table><tr><td>
					"._AUTHOR.":
					</td><td>";
						if($_SESSION['adminloggedin'] == "1")
						{
							$authorquery = mysql_query("SELECT penname FROM ".$tableprefix."fanfiction_authors WHERE uid = '$uid'");
							$authorname = mysql_fetch_array($authorquery);
							$output .= "$authorname[penname] <INPUT type=\"hidden\" name=\"uid\" value=\"$uid\">";
						}
						else
						{
							$output .= "$userpenname <INPUT type=\"hidden\" name=\"uid\" value=\"$useruid\">";
						}
					$output .= "</td><td>
					</td></tr><tr><td>";
					if ($numcats != '1')
					{
						$output .= ""._CATEGORY.":
						</td><td>";
						if($newcat == "on")
						{
							$result = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_categories ORDER BY displayorder");
							$output .= "<select name=\"catid\" onChange=\"disableSubmit(this)\">";
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
								$output .= "<option value=\"".$categorychoose[catid]."\"";
								if($categorychoose[locked] == "1")
									$output .= " id=\"disable\" class=\"locked\"";
								else
									$output .= " id=\"enable\" class=\"notlocked\"";
								if($categorychoose[catid] == $catid)
									$output .= " selected";	
								$output .= ">";
								$output .= "$space-$categorychoose[category]";
								$output .= "</option>";
							}
							$output .= "</select>";
							$output .= "<INPUT type=\"hidden\" name=\"newcat\" value=\"on\"><INPUT type=\"hidden\" name=\"psid\" value=\"$psid\">";
						}
						else
						{
							$result = mysql_query("SELECT category FROM ".$tableprefix."fanfiction_categories WHERE catid = '$catid'");
							$cat = mysql_fetch_array($result);
							$output .= "$cat[category] <INPUT type=\"hidden\" name=\"catid\" value=\"$catid\">";
						}
						$output .= "</td><td>

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
					$output .= "<tr><td>";
					if($_SESSION['adminloggedin'] == "1")
					{
						$output .= ""._FEATURED.":
						</td><td>
						<INPUT type=\"checkbox\" name=\"featured\"";
						if($featured == "on")
							$output .= " checked";
						$output .= "></td><td></td></tr><tr><td>";

					}
					else
					{
							$output .= "<INPUT type=\"hidden\" name=\"featured\" value=\"$featured\">";
					}
					$newstorytext = strip_tags($story, '<b><i><u><center><img><a><hr><p><ul><li><ol>');
					$output .= "
					"._STORYTEXTTEXT.":
					</td><td>
					<TEXTAREA name=\"storytext\" cols=\"60\" rows=\"10\">$newstorytext</TEXTAREA>
					</td><td valign=\"top\">
					"._STORYTEXTCLEANUP."
					</td></tr><tr><td colspan=\"3\" align=\"center\"><INPUT type=\"hidden\" name=\"uid\" value=\"$uid\"><INPUT type=\"hidden\" name=\"sid\" value=\"$sid\">
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

			if(($featured == "on") || ($featured == "1"))
				$featured = "1";
			else
				$featured = "0";

			if($newcat != "")
			{
				$query = mysql_query("SELECT catid FROM ".$tableprefix."fanfiction_stories WHERE sid = '$sid'");
				$category = mysql_fetch_array($query);
				include("functions.php");
				categoryitems($catid, 1);
				categoryitems($category[catid], -1);
				mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET catid = '$catid' WHERE psid = '$psid'");
			}	
				
			if($store == "mysql")
			{
				mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET title = '$title', chapter = '$chapter', summary = '$summary', gid = '$genrestring', charid = '$charstring', wid = '$warningstring', rid = '$rid', storytext = '$storytext', featured = '$featured', wordcount = '$wordcount', catid = '$catid' WHERE sid = '$sid'");
				$output .= ""._STORYUPDATED."";
			}
			else
			{
				$result2 = mysql_query("SELECT penname FROM ".$tableprefix."fanfiction_authors WHERE uid = '$uid'");
				$user = mysql_fetch_array($result2);
				$insertstory = mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET title = '$title', chapter = '$chapter', summary = '$summary', gid = '$genrestring', charid = '$charstring', wid = '$warningstring', rid = '$rid', featured = '$featured', wordcount = '$wordcount', catid = '$catid' WHERE sid = '$sid'");
				if( !file_exists( "$storiespath/$user[penname]/" ) )
				{
					mkdir("$storiespath/$user[penname]", 0755);
					chmod("$storiespath/$user[penname]", 0777);
				}
				$handle = fopen("$storiespath/$user[penname]/$sid.txt", 'w+');

				if ($handle)
				{
					fwrite($handle, stripslashes ($storytext));
					fclose($handle);
					$storytext = "";
				}
				$output .= ""._STORYUPDATED."";
			}
		}
		else
		{
			$result4 = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_stories WHERE sid = '$sid'");
			$edit = mysql_fetch_array($result4);
			if(($edit[uid] == $useruid) || ($_SESSION['adminloggedin'] == "1"))
			{
				$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"stories.php?action=editstory\">
						<table><tr><td>
						"._AUTHOR.":
						</td><td>";
						if($_SESSION['adminloggedin'] == "1")
						{
							$authorquery = mysql_query("SELECT penname FROM ".$tableprefix."fanfiction_authors WHERE uid = '$edit[uid]'");
							$authorname = mysql_fetch_array($authorquery);
							$output .= "$authorname[penname] <INPUT type=\"hidden\" name=\"uid\" value=\"$edit[uid]\">";
						}
						else
						{
							$output .= "$userpenname <INPUT type=\"hidden\" name=\"uid\" value=\"$useruid\">";
						}
						$output .= "</td><td>
						</td></tr><tr><td>";
						if ($numcats != '1')
						{
							$output .= ""._CATEGORY.":
							</td><td>";
							if("$edit[sid]" == "$edit[psid]")
							{
								$result = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_categories ORDER BY displayorder");
								$output .= "<select name=\"catid\" onChange=\"disableSubmit(this)\">";
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
									$output .= "<option value=\"".$categorychoose[catid]."\"";
									if($categorychoose[locked] == "1")
										$output .= " id=\"disable\" class=\"locked\"";
									else
										$output .= " id=\"enable\" class=\"notlocked\"";
									if($edit[catid] == $categorychoose[catid])
										$output .= " selected";	
									$output .= ">";
									$output .= "$space-$categorychoose[category]";
									$output .= "</option>";
								}
								$output .= "</select>";
								$output .= "<INPUT type=\"hidden\" name=\"newcat\" value=\"on\"><INPUT type=\"hidden\" name=\"psid\" value=\"$edit[psid]\">";
							}
							else
							{
								$result = mysql_query("SELECT category FROM ".$tableprefix."fanfiction_categories WHERE catid = '$edit[catid]'");
								$cat = mysql_fetch_array($result);
								$output .= "$cat[category] <INPUT type=\"hidden\" name=\"catid\" value=\"$edit[catid]\">";
							}

							$output .=  "</td></tr><tr><td>";
						}
						else
						{
							$output .= "<INPUT type=\"hidden\" name=\"catid\" value=\"$edit[catid]\">";
						}
						$output .= "
						"._TITLE.":
						</td><td><INPUT name=\"title\" value=\"$edit[title]\" size=\"30\">
						</td><td>

						</td></tr><tr><td>
						"._CHAPTERTITLE.":
						</td><td>
						<INPUT name=\"chapter\" size=\"30\" value=\"$edit[chapter]\">
						</td><td>

						</td></tr><tr><td>
						"._SUMMARY.":
						</td><td>
						<TEXTAREA name=\"summary\" cols=\"45\" rows=\"4\">$edit[summary]</textarea>
						</td><td>

						</td></tr>";
						$result5 = mysql_query("SELECT gid, genre FROM ".$tableprefix."fanfiction_genres");
						$numgenres = mysql_num_rows($result5);
						$output .= "<INPUT type=\"hidden\" name=\"numgenres\" value=\"$numgenres\">";
						if($numgenres != "0")
						{
							$output .= "<tr><td>
							"._GENRES.":
							</td><td>";
							$output .= "<select name=\"gid[]\" size=\"5\" multiple>";

							while ($genreresults = mysql_fetch_array($result5))
								{
									$output .= "<option value=\"$genreresults[genre]\"";
									if(strstr($edit[gid], $genreresults[genre]))
										$output .= " selected";
									$output .= ">$genreresults[genre]</option>";
								}
							$output .= "
							</td><td>

							</td></tr>";
						}

						$result = mysql_query("SELECT charid, charname FROM ".$tableprefix."fanfiction_characters WHERE catid = '$edit[catid]' ORDER by charname");
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
									if(strstr($edit[charid], $charresults[charname]))
										$output .= " selected";
									$output .= ">$charresults[charname]</option>";
								}

							$output .= "
							</td><td>

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
								if($edit[rid] == $ratingresults[rating])
									$output .= " selected";
								$output .= ">$ratingresults[rating]</option>";
							}
						$output .= "
						</td><td>

						</td></tr>";

						$result7 = mysql_query("SELECT wid, warning FROM ".$tableprefix."fanfiction_warnings");
						$numwarnings = mysql_num_rows($result7);
						if($numwarnings != "0")
						{
							$output .= "<tr><td>
							"._WARNINGS.":
							</td><td>";
							$output .= "<select name=\"wid[]\" size=\"5\" multiple>";
							while ($warningresults = mysql_fetch_array($result7))
							{
								$output .= "<option value=\"$warningresults[warning]\"";
								if(strstr($edit[wid], $warningresults[warning]))
									$output .= " selected";
								$output .= ">$warningresults[warning]</option>";
							}
							$output .= "
							</td><td>

							</td></tr>";
						}

						$output .= "<tr><td>";
						if($_SESSION['adminloggedin'] == "1")
						{
							$output .= ""._FEATURED.":
							</td><td>
							<INPUT type=\"checkbox\" name=\"featured\"";
							if($edit[featured] == "1")
								$output .= " checked";
							$output .= "></td><td></td></tr><tr><td>";

						}
						else
						{
							$output .= "<INPUT type=\"hidden\" name=\"featured\" value=\"$edit[featured]\">";
						}
						$output .= "
						"._STORYTEXTTEXT.":
						</td><td>
						<TEXTAREA name=\"storytext\" cols=\"60\" rows=\"10\" onClick=\"this.form.storyfile.disabled=true\">";
						if($store == "mysql")
						{
							$output .= "$edit[storytext]";
						}
						if($store == "files")
						{
							if($_SESSION['adminloggedin'] == "1")
							{
								$penname = $authorname[penname];
							}
							else
								$penname = $userpenname;

							$out = fopen ("$storiespath/$penname/$sid.txt", "r");
							while (!feof($out)) 
							{

								$output .= fgets($out, 10000);

							}

						}

						$output .= "</TEXTAREA>
						</td><td rowspan=\"2\" valign=\"top\">
						"._STORYTEXTCLEANUP."
						</td></tr><tr><td>
						Story Text (upload):
						</td><td>
						<INPUT type=\"file\" name=\"storyfile\" onClick=\"this.form.storytext.disabled=true\">
						</td></tr><tr><td colspan=\"3\" align=\"center\"><INPUT type=\"hidden\" name=\"sid\" value=\"$sid\">
						<INPUT type=\"submit\" value=\""._PREVIEW."\" name=\"submit\">
						</table></form>";
					}
					else
					{
						$output .= "<center>"._NOTAUTHOR1."</center>";
					}
		}
	}
	else
	{
		$output .= "<center>".PLEASELOGIN."";
	}

	$tpl->assign( "output", $output );
	$tpl->printToScreen();

}

function deletestory($sid, $delete)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $store, $userpenname, $useruid, $storiespath;
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
	$result2 = mysql_query("SELECT psid,uid,inorder,catid,sid FROM ".$tableprefix."fanfiction_stories WHERE sid = '$sid'");
	$status = mysql_fetch_array($result2);
	$output .= "<center><h4>"._DELETESTORY."</h4></center>";
	$result4 = mysql_query("SELECT uid FROM ".$tableprefix."fanfiction_stories WHERE sid = '$sid'");
	$edit = mysql_fetch_array($result4);
	if((($_SESSION['loggedin'] == "1") && ($useruid == $edit[uid])) || ($_SESSION['adminloggedin'] == "1"))
	{
		if($delete == "yes")
		{
			$result5 = mysql_query("SELECT penname FROM ".$tableprefix."fanfiction_authors WHERE uid = '$edit[uid]'");
			$author = mysql_fetch_array($result5);
			if(($status[uid] == $useruid) || ($_SESSION['adminloggedin'] == "1"))
			{
				if("$status[psid]" == "$status[sid]")
				{
					include("functions.php");
					categoryitems($status[catid], -1);
					$result3 = mysql_query("SELECT sid FROM ".$tableprefix."fanfiction_stories WHERE psid = '$sid'");
					mysql_query("DELETE FROM ".$tableprefix."fanfiction_stories WHERE psid = '$sid'");
					if($store == "files")
					{
						while($chapters = mysql_fetch_array($result3))
						{
							unlink("$storiespath/$author[penname]/$chapters[sid].txt");
						}
					}
				}
				else
				{
					mysql_query("DELETE FROM ".$tableprefix."fanfiction_stories WHERE sid = '$sid'");
					if($store == "files")
					{
						unlink("$storiespath/$author[penname]/".$sid.".txt");
					}
				}

				mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET inorder = (inorder - 1) WHERE psid = '$status[psid]' AND inorder > '$status[inorder]'");
				mysql_query("DELETE FROM ".$tableprefix."fanfiction_favstor WHERE sid = '$sid'");

				$output .= "<center>"._STORYDELETED."</center>";
			}
			else
			{
				$output .= "<center>"._NOTAUTHOR2."</center>";
			}
		}
		else if ($delete == "no")
		{
			$output .= "<center>"._STORYNOTDELETED."</center>";
		}
		else
		{

			if($status[psid] == $sid)
			{
				$output .= "<center>"._DELETESTORY1."<BR><BR>";
				$output .= "[ <a href=\"stories.php?action=deletestory&delete=yes&sid=$sid\">"._YES."</a> | <a href=\"stories.php?action=deletestory&delete=no\">".NO."</a> ]</center>";
			}
			else
			{
				$output .= "<center>"._DELETESTORY2."<BR><BR>";
				$output .= "[ <a href=\"stories.php?action=deletestory&delete=yes&sid=$sid\">"._YES."</a> | <a href=\"stories.php?action=deletestory&delete=no\">".NO."</a> ]</center>";
			}
		}
	}
	else
	{
		$output .= "<center>".PLEASELOGIN."";
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();

}


switch ($action)
{

	case "newstory":
		newstory($submit, $catid, $gid, $rid, $charid, $storyfile, $storytext, $wid, $title, $chapter, $summary, $uid, $rr, $mailinglist, $numchars, $numgenres);
	break;

	case "addchapter":
		addchapter($submit, $catid, $gid, $rid, $charid, $storyfile, $storytext, $wid, $title, $chapter, $summary, $uid, $sid, $psid, $rr, $mailinglist, $numchars, $numgenres);
	break;

	case "deletestory":
		deletestory($sid, $delete);
	break;

	case "editstory":
		editstory($submit, $catid, $gid, $rid, $charid, $storyfile, $storytext, $wid, $title, $chapter, $summary, $uid, $sid, $featured, $numchars, $numgenres, $newcat, $psid);
	break;

	default:
		viewstories($go, $sid, $psid, $inorder, $com);
		break;
}

?>