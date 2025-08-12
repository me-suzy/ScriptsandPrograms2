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

function categories($submit, $category, $parentcatid, $description, $locked, $image, $go, $displayorder, $catid, $orderafter)
{
	global $tableprefix, $numcats, $adminarea, $logo, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $level;
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
		$output .= "<center><h4>"._CATMAINT."</h4></center>";
		if ($submit)
		{
			if($parentcatid != "-1")
			{
				$parentquery = mysql_query("SELECT leveldown FROM ".$tableprefix."fanfiction_categories WHERE catid = '$parentcatid'");
				$parentresult = mysql_fetch_array($parentquery);
				$leveldown = $parentresult[leveldown] + 1;
			}
			else
				$leveldown = 0;
			if($locked == "on")
				$locked = "1";
			else
				$locked = "0";


			mysql_query("UPDATE ".$tableprefix."fanfiction_categories SET displayorder = (displayorder + 1) WHERE displayorder > '$orderafter'");

			$displayorder = $orderafter + 1;
			mysql_query("INSERT INTO ".$tableprefix."fanfiction_categories (category, parentcatid, description, locked, image, leveldown, displayorder) VALUES ('$category', '$parentcatid', '$description', '$locked', '$image', '$leveldown', '$displayorder')");
			$output .= "<center>"._BTCATMAINT."</center>";

		}
		else
		{
			if($go == "up")
			{

				$oneabove = $displayorder - 1;
				mysql_query("UPDATE ".$tableprefix."fanfiction_categories SET displayorder = '-1' WHERE displayorder = '$displayorder'");
				mysql_query("UPDATE ".$tableprefix."fanfiction_categories SET displayorder = '$displayorder' WHERE displayorder = '$oneabove'");
				mysql_query("UPDATE ".$tableprefix."fanfiction_categories SET displayorder = '$oneabove' WHERE displayorder = '-1'");
			}
			if($go == "down")
			{
				$oneabove = $displayorder + 1;
				mysql_query("UPDATE ".$tableprefix."fanfiction_categories SET displayorder = '-1' WHERE displayorder = '$displayorder'");
				mysql_query("UPDATE ".$tableprefix."fanfiction_categories SET displayorder = '$displayorder' WHERE displayorder = '$oneabove'");
				mysql_query("UPDATE ".$tableprefix."fanfiction_categories SET displayorder = '$oneabove' WHERE displayorder = '-1'");
			}
			$result = mysql_query("SELECT * from ".$tableprefix."fanfiction_categories ORDER BY displayorder");

			//Add new category

			$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"admin.php?action=categories\">
			<table align=\"center\">
			<tr><td colspan=\"2\">
			<center><b>"._NEWCAT."</b></center>
			</td></tr>
			<tr><td>
			"._CATNAME.": <A HREF=\"javascript:n_window('docs/adminmanual.htm#category');\">[?]</A>
			</td><td>
			<INPUT name=\"category\">
			</td></tr>
			<tr><td>
			"._CATDESC.": <A HREF=\"javascript:n_window('docs/adminmanual.htm#categorydesc');\">[?]</A>
			</td><td>
			<textarea name=\"description\" cols=\"35\" rows=\"4\"></textarea>
			</td></tr>
			<tr><td>
			"._CATLEVEL.": <A HREF=\"javascript:n_window('docs/adminmanual.htm#categorylevel');\">[?]</A>
			</td><td>
			<select name=\"parentcatid\">
			<option value=\"-1\">"._TOPLEVEL."</option>";
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
			$output .= "
			</select>
			</td></tr>";
			$query = mysql_query("SELECT * from ".$tableprefix."fanfiction_categories ORDER BY displayorder");
			$output .= "<tr><td>
			"._ORDERAFTER.": <A HREF=\"javascript:n_window('docs/adminmanual.htm#categorylevel');\">[?]</A>
			</td><td>
			<select name=\"orderafter\">";
			while($categoryselect = mysql_fetch_array($query))
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
			$output .= "
			</select>
			</td></tr>

			<tr><td>
			"._LOCKED."? <A HREF=\"javascript:n_window('docs/adminmanual.htm#locked');\">[?]</A>
			</td><td>
			<INPUT type=\"checkbox\" name=\"locked\">
			</td></tr>
			<tr><td>
			"._IMAGE.": <A HREF=\"javascript:n_window('docs/adminmanual.htm#categoryimage');\">[?]</A>
			</td><td>
			<INPUT name=\"image\">
			</td></tr>
			<tr><td colspan=\"2\" align=\"center\">
			<INPUT type=\"submit\" value=\""._SUBMIT."\" name=\"submit\">
			</td></tr>
			</table></form>";
			
			//Fix Category Counts
			
			$output .= "<hr>";
			
			$output .= "<center>"._FIXCATCOUNTS."</center><br>";
			$output .= "<center><form method=\"POST\" enctype=\"multipart/form-data\" action=\"admin.php?action=catcounts\">";
			$output .= "<INPUT type=\"submit\" name=\"submit\" value=\""._COUNTCATS."\"></form></center>";
			

			//List of current categories

			$output .= "<hr>";

			$output .= "<table class=\"tblborder\" align=\"center\" cellspacing=\"0\" cellpadding=\"3\">
			<tr><td colspan=\"5\" class=\"tblborder\">
			<center><b>"._CURRENTCATS." <A HREF=\"javascript:n_window('docs/adminmanual.htm#currentcategories');\">[?]</A></b></center>
			</td></tr>
			<tr>
			<td class=\"tblborder\"><b>"._ORDER."</b></td><td class=\"tblborder\"><b>"._CATEGORY."</b></td><td class=\"tblborder\" colspan=\"2\"><b>"._MOVE."</b></td><td class=\"tblborder\"><b>"._OPTIONS."</b></td>
			</tr>";
			$result2 = mysql_query("SELECT * from ".$tableprefix."fanfiction_categories ORDER BY displayorder");
			$numrow = mysql_num_rows($result2);
			while ($catresults2 = mysql_fetch_array($result2))
			{
				$output .= "<tr><td class=\"tblborder\"><b>$catresults2[displayorder]</b></td><td class=\"tblborder\">";
				$space = "";
				for ($count = 0; $count < $catresults2[leveldown]; $count++)
				{
				    $space .= "&nbsp;&nbsp;&nbsp;";
				}
				$output .= "$space$catresults2[category]</td><td class=\"tblborder\">";
				if($catresults2[displayorder] != "1")
				$output .= "<a href=\"admin.php?action=categories&go=up&displayorder=$catresults2[displayorder]\"><img src=\"images/arrowup.gif\" width=\"13\" height=\"18\" border=\"0\"></a>";
				else
					$output .= "&nbsp;";
				$output .= "</td><td class=\"tblborder\" width=\"13\">";
				if($catresults2[displayorder] != "$numrow")
					$output .=  " <a href=\"admin.php?action=categories&go=down&displayorder=$catresults2[displayorder]\"><img src=\"images/arrowdown.gif\" width=\"13\" height=\"18\" border=\"0\"></a>";
				$output .= "</td><td class=\"tblborder\"><a href=\"admin.php?action=categoryedit&amp;catid=$catresults2[catid]\">"._EDIT."</a> | <a href=\"admin.php?action=categorydelete&amp;catid=$catresults2[catid]\">"._DELETE."</td></tr>";
			}
			$output .= "</table>";

		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function catcounts()
{
	global $tableprefix, $numcats, $adminarea, $logo, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $level;
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
		$output .= "<center><h4>"._EDITCAT."</h4></center>";
		mysql_query("UPDATE ".$tableprefix."fanfiction_categories SET numitems = '0'");
		$query = mysql_query("SELECT catid FROM ".$tableprefix."fanfiction_stories WHERE sid = psid AND validated = '1'");
	
		while($result = mysql_fetch_array($query))
		{
			//add one to the parent category2				
			mysql_query("UPDATE ".$tableprefix."fanfiction_categories SET numitems = (numitems + 1) WHERE catid = '$result[catid]'");
			//and then get the parentcategory so we can check it for a parent
			
			$catquery = mysql_query("SELECT catid, parentcatid FROM ".$tableprefix."fanfiction_categories WHERE catid = '$result[catid]'");
			$thiscat = mysql_fetch_array($catquery);
			
			//while there is a parent category
			while($thiscat[parentcatid] != "-1")
			{
				//add one to the parent category2				
				mysql_query("UPDATE ".$tableprefix."fanfiction_categories SET numitems = (numitems + 1) WHERE catid = '$thiscat[parentcatid]'");
				//and then get the parentcategory so we can check it for a parent
				$catquery2 = mysql_query("SELECT parentcatid, catid FROM ".$tableprefix."fanfiction_categories WHERE catid = '$thiscat[parentcatid]'");
				$thiscat = mysql_fetch_array($catquery2);
			}
		}	

		$output .= "<center>"._CATCOUNTSUPDATED." "._BTCATMAINT."</center>";
		$tpl->assign( "output", $output );
		$tpl->printToScreen();
	}
}

function categoryedit($submit, $catid, $category, $description, $locked, $parentcatid, $image)
{
	global $tableprefix, $numcats, $adminarea, $logo, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $level;
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
		$output .= "<center><h4>"._EDITCAT."</h4></center>";
		if ($submit)
		{
			if($parentcatid != "-1")
			{
				$parentquery = mysql_query("SELECT leveldown FROM ".$tableprefix."fanfiction_categories WHERE catid = '$parentcatid'");
				$parentresult = mysql_fetch_array($parentquery);
				$leveldown = $parentresult[leveldown] + 1;
			}
			else
				$leveldown = 0;
			mysql_query("UPDATE ".$tableprefix."fanfiction_categories SET category = '$category', description = '$description', locked = '$locked', parentcatid = '$parentcatid', image = '$image', leveldown = '$leveldown' WHERE catid = '$catid'");
			$output .= "<center>"._BTCATMAINT."</center>";
		}
		else
		{
			//do sql query here
			$result = mysql_query("SELECT * from ".$tableprefix."fanfiction_categories WHERE catid = '$catid'");
			$catresults = mysql_fetch_array($result);
			$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"admin.php?action=categoryedit\">
			<table align=\"center\">
			<tr><td>
			"._CATNAME.":
			</td><td>
			<INPUT name=\"category\" value=\"$catresults[category]\">
			</td></tr>
			<tr><td>
			"._CATDESC.":
			</td><td>
			<textarea name=\"description\" cols=\"35\" rows=\"4\">$catresults[description]</textarea>
			</td></tr>
			<tr><td>
			"._CATLEVEL.":
			</td><td>
			<select name=\"parentcatid\">
			<option value=\"-1\"";
			$output .= ">"._TOPLEVEL."</option>";
			$result2 = mysql_query("SELECT * from ".$tableprefix."fanfiction_categories ORDER BY displayorder");
			while($categorychoose = mysql_fetch_array($result2))
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
				if($categorychoose[catid] == $catresults[parentcatid])
					$output .= " selected";
				$output .= ">";
				$output .= "$space-$categorychoose[category]";
				$output .= "</option>";
			}
			$output .= "
			</select>
			</td></tr>
			<tr><td>
			"._LOCKED."?
			</td><td><select name=\"locked\">";
			$output .= "<option value=\"0\"";
			if ($catresults[locked] == "0")
				$output .= "selected";
			$output .= ">"._NO."</option>";
			$output .= "<option value=\"1\"";
			if ($catresults[locked] == "1")
				$output .= "selected";
			$output .= ">"._YES."</option>
			</select>
			</td></tr>
			<tr><td>"._IMAGE.":</td><td><INPUT name=\"image\" value=\"$catresults[image]\"></td></tr>
			<tr><td colspan=\"2\" align=\"center\">
			<INPUT type=\"hidden\" value=\"$catid\" name=\"catid\">
			<INPUT type=\"submit\" value=\""._SUBMIT."\" name=\"submit\">
			</td></tr>
			</table>";

		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function categorydelete($delete, $catid)
{
	global $tableprefix, $numcats, $adminarea, $logo, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $level;
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
		$output .= "<center><h4>"._DELETECAT."</h4></center>";
		if($delete == "yes")
		{
			$query = mysql_query("SELECT displayorder,leveldown FROM ".$tableprefix."fanfiction_categories WHERE catid = '$catid'");
			$cat = mysql_fetch_array($query);
			mysql_query("UPDATE ".$tableprefix."fanfiction_categories SET displayorder = (displayorder - '1') WHERE displayorder > '$cat[displayorder]'");
			mysql_query("DELETE FROM ".$tableprefix."fanfiction_categories WHERE catid = '$catid'");
			mysql_query("DELETE FROM ".$tableprefix."fanfiction_categories WHERE parentcatid = '$catid'");
			$output .= "<center>"._BTCATMAINT."</center>";
		}
		else if ($delete == "no")
		{
			$output .= "<center>"._CATNOTDELETED."</center>";
		}
		else
		{
			$output .= "<center>"._CATSUREDEL."<BR><BR>";
			$output .= "[ <a href=\"admin.php?action=categorydelete&delete=yes&catid=$catid\">"._YES."</a> | <a href=\"admin.php?action=categorydelete&delete=no\">"._NO."</a> ]</center>";
		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function characters($submit, $charname, $catid, $transmit, $copy, $checkbox)
{
	global $tableprefix, $numcats, $adminarea, $logo, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $level;
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
	if (($_SESSION['adminloggedin'] != "1") || (($level != '1') && ($level != '2')))
	{
		$output .= ""._NOTALLOWED."<BR><BR>";
	}
	else
	{
		$output .= adminmenu();
		$output .= "<center><h4>"._CHARMAINT."</h4></center>";
		if ($submit)
		{
			mysql_query("INSERT INTO ".$tableprefix."fanfiction_characters (charname, catid) VALUES ('$charname', '$catid')");
			$output .= "<center>"._BTCHARMAINT."</center>";
		}
		else if ($transmit)
		{
			$result = mysql_query("SELECT * from ".$tableprefix."fanfiction_characters GROUP BY charname");
			$output .= "<center>"._SELECTCOPY."<center><br>";
			$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"admin.php?action=characters\">";
			$output .= "<table align=\"center\" class=\"tblborder\" cellpadding=\"3\" cellspacing=\"0\">";
			while($characters = mysql_fetch_array($result))
			{
				$output .= "<tr><td class=\"tblborder\"><INPUT type=\"checkbox\" name=\"checkbox[]\" value=\"$characters[charname]\"></td><td class=\"tblborder\">$characters[charname]</td></tr>";

			}
			$output .= "<tr><td class=\"tblborder\" colspan=\"2\" align=\"center\"><INPUT type=\"hidden\" name=\"catid\" value=\"$catid\"><INPUT type=\"submit\" name=\"copy\" value=\""._SUBMIT."\"></td></tr></table></form>";
		}
		else if($copy)
		{
			foreach($_POST["checkbox"] as $charname)
			{
				mysql_query("INSERT INTO ".$tableprefix."fanfiction_characters (charname, catid) VALUES ('$charname', '$catid')");
			}
			$output .= "<center>"._CHARSCOPIED."</center>";
		}
		else
		{

			$result2 = mysql_query("SELECT catid, parentcatid, category, leveldown from ".$tableprefix."fanfiction_categories ORDER BY displayorder");

			//Add new character

			$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"admin.php?action=characters\">
			<table align=\"center\">
			<tr><td colspan=\"2\" align=\"center\">
			<b>"._NEWCHAR."</b>
			</td></tr>
			<tr><td>
			"._CHARACTER.": <A HREF=\"javascript:n_window('docs/adminmanual.htm#charactername');\">[?]</A>
			</td><td>
			<INPUT name=\"charname\">
			</td></tr>
			<tr><td>
			"._CHARCAT.": <A HREF=\"javascript:n_window('docs/adminmanual.htm#charactercategory');\">[?]</A>
			</td><td>
			<select name=\"catid\">";
			while($categorychoose = mysql_fetch_array($result2))
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


//					$parentname = $space;
				}
				else
					$space = "";
				$output .= "<option value=\"".$categorychoose[catid]."\">";
				$output .= "$space-$categorychoose[category]";
				$output .= "</option>";
			}
			$output .= "
			</select>
			</td></tr>
			<tr><td colspan=\"2\" align=\"center\">
			<INPUT type=\"submit\" value=\""._SUBMIT."\" name=\"submit\">
			</td></tr>
			</table></form>";

			//Copy characters to category

			$output .= "<hr>";
			$output .= "<center><b>"._COPYCHARS." <A HREF=\"javascript:n_window('docs/adminmanual.htm#copycharacters');\">[?]</A></b><br><br>";
			$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"admin.php?action=characters\">";
			$output .= "<table><tr><td>";
			$result3 = mysql_query("SELECT catid, parentcatid, category, leveldown from ".$tableprefix."fanfiction_categories ORDER BY displayorder");
			$output .= ""._CATTOCOPY.": <select name=\"catid\">";
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
				$output .= "<option value=\"".$categorychoose[catid]."\">";
				$output .= "$space-$categorychoose[category]";
				$output .= "</option>";
			}
			$output .= "</select> ";
			$output .= "<INPUT type=\"submit\" name=\"transmit\" value=\""._SUBMIT."\">";
			$output .= "</td></tr></table></form></center>";


			//List of current characters

			$output .= "<hr>";
			$result4 = mysql_query("SELECT * from ".$tableprefix."fanfiction_characters ORDER BY catid,charname");
			$output .= "<table class=\"tblborder\" cellspacing=\"0\" cellpadding=\"3\" align=\"center\">
			<tr><td colspan=\"3\" align=\"center\" class=\"tblborder\">
			<b>"._CURRENTCHARS."</b>
			</td></tr>
			<tr>
			<td class=\"tblborder\">"._CHARACTER."</td><td class=\"tblborder\">"._CATEGORY."</td><td class=\"tblborder\">"._OPTIONS."</td>
			</tr>";
			while ($charresults = mysql_fetch_array($result4))
			{
				$output .= "<tr><td class=\"tblborder\">";
				$output .= "$charresults[charname]</td><td class=\"tblborder\">";
				$result3 = mysql_query("select category from ".$tableprefix."fanfiction_categories WHERE catid=$charresults[catid]");
				$catresults = mysql_fetch_array($result3);
				$output .= "$catresults[category]";
				$output .= "</td><td class=\"tblborder\"><a href=\"admin.php?action=characteredit&amp;charid=$charresults[charid]\">"._EDIT."</a> | <a href=\"admin.php?action=characterdelete&amp;charid=$charresults[charid]\">"._DELETE."</td></tr>";
			}
			$output .= "</table>";

		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function characteredit($submit, $charid, $catid, $charname, $oldname, $oldcat)
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
		$output .= "<center><h4>"._EDITCHAR."</h4></center>";
		if ($submit)
		{
			mysql_query("UPDATE ".$tableprefix."fanfiction_characters SET charname = '$charname', catid = '$catid' WHERE charid = '$charid'");
			$newquery = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_stories WHERE charid LIKE '%$oldname%' AND catid = '$oldcat'");
			while($charresult = mysql_fetch_array($newquery))
			{
				$newcharid = ereg_replace($oldname, $charname, $charresult[charid]);

				mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET charid = '$newcharid' WHERE sid = '$charresult[sid]' AND catid = '$oldcat'");
			}
			if($oldcat != $catid)
			{
				$newquery2 = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_stories WHERE charid LIKE '%$oldname%' AND catid = '$oldcat'");
				while($charresult = mysql_fetch_array($newquery2))
				{
					$tok = strtok($charresult[charid], ", ");// tokenize the old list of names
					$newString = "";// the new list of good names
					while($tok)
					{
						if( $tok != $oldname )// oldname is the thing that is going away
						{
							// It's a keeper, so decide if it is first or not for comma-age, then add it in
							if( $newString != "" )
								$newString .= ", ";
							$newString .= $tok;
						}
						$tok = strtok(", "); //advance to the next token
					}
					mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET charid = '$newString' WHERE sid = '$charresult[sid]' AND catid = '$oldcat'");
				}
			}

			$output .= "<center>"._CHAREDITED."</center>";
		}
		else
		{
			$result = mysql_query("SELECT * from ".$tableprefix."fanfiction_characters WHERE charid = '$charid'");
			$result2 = mysql_query("SELECT catid, parentcatid, category, leveldown from ".$tableprefix."fanfiction_categories ORDER BY displayorder");
			$charresults = mysql_fetch_array($result);

			$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"admin.php?action=characteredit\">
			<table align=\"center\">
			<tr><td>
			"._CHARACTER.":
			</td><td>
			<INPUT name=\"charname\" value=\"$charresults[charname]\">
			</td></tr>
			<tr><td>
			"._CATEGORY.":
			</td><td>
			<select name=\"catid\">";
			while($categorychoose = mysql_fetch_array($result2))
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


//					$parentname = $space;
				}
				else
					$space = "";
				$output .= "<option value=\"".$categorychoose[catid]."\">";
				$output .= "$space-$categorychoose[category]";
				$output .= "</option>";
			}
			$output .= "
			</select>
			</td></tr>
			<tr><td colspan=\"2\" align=\"center\">
			<INPUT type=\"hidden\" value=\"$charid\" name=\"charid\">
			<INPUT type=\"hidden\" value=\"$charresults[charname]\" name=\"oldname\">
			<INPUT type=\"hidden\" value=\"$charresults[catid]\" name=\"oldcat\">
			<INPUT type=\"submit\" value=\""._SUBMIT."\" name=\"submit\">
			</td></tr>
			</table>";

		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function characterdelete($delete, $charid)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $level;
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
	if (($_SESSION['adminloggedin'] != "1") || (($level != '1') && ($level != '2')))
	{
		$output .= ""._NOTALLOWED."<BR><BR>";
	}
	else
	{
		$output .= adminmenu();
		$output .= "<center><h4>"._DELETECHAR."</h4></center>";
		if($delete == "yes")
		{
			$result5 = mysql_query("SELECT catid, charname FROM ".$tableprefix."fanfiction_characters WHERE charid = '$charid'");
			$chars = mysql_fetch_array($result5);
			mysql_query("DELETE FROM ".$tableprefix."fanfiction_characters where charid = '$charid'");
			$newquery5 = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_stories WHERE charid LIKE '%$chars[charname]%' AND catid = '$chars[catid]'");
				while($charresult = mysql_fetch_array($newquery5))
				{
					$tok = strtok($charresult[charid], ", ");// tokenize the old list of names
					$newString = "";// the new list of good names
					while($tok)
					{
						if( $tok != $chars[charname] )// oldname is the thing that is going away
						{
							// It's a keeper, so decide if it is first or not for comma-age, then add it in
							if( $newString != "" )
								$newString .= ", ";
							$newString .= $tok;
						}
						$tok = strtok(", "); //advance to the next token
					}
					mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET charid = '$newString' WHERE sid = '$charresult[sid]' AND catid = '$chars[catid]'");
				}
				$output .= "<center>"._BTCHARMAINT."</center>";
		}
		else if ($delete == "no")
		{
			$output .= "<center>"._CHARNOTDEL."</center>";
		}
		else
		{
			$output .= "<center>"._CHARSUREDEL."<br><br>";
			$output .= "[ <a href=\"admin.php?action=characterdelete&delete=yes&charid=$charid\">"._YES."</a> | <a href=\"admin.php?action=characterdelete&delete=no\">"._NO."</a> ]</center>";
		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function ratings($submit, $rating, $ratingwarning, $warningtext)
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
		$output .= "<center><h4>"._RATMAINT."</h4></center>";
		if ($submit)
		{
			if($ratingwarning == "on")
				$ratingwarning = "1";
			mysql_query("INSERT INTO ".$tableprefix."fanfiction_ratings (rating, ratingwarning, warningtext) VALUES ('$rating', '$ratingwarning', '$warningtext')");
			$output .= "<center>"._BTRATMAINT."</center>";
		}
		else
		{
			$result = mysql_query("SELECT * from ".$tableprefix."fanfiction_ratings");

			//Add new rating

			$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"admin.php?action=ratings\">
			<table align=\"center\">
			<tr><td colspan=\"2\">
			<b>"._NEWRAT."</b>
			</td></tr>
			<tr><td>
			"._RATING.": <A HREF=\"javascript:n_window('docs/adminmanual.htm#rating');\">[?]</A>
			</td><td>
			<INPUT name=\"rating\">
			</td></tr>
			<tr><td>
			"._WARNINGPOP.": <A HREF=\"javascript:n_window('docs/adminmanual.htm#popup');\">[?]</A>
			</td><td>
			<INPUT type=\"checkbox\" name=\"ratingwarning\">
			</td></tr>
			<tr><td>
			"._WARNINGTEXT.": <A HREF=\"javascript:n_window('docs/adminmanual.htm#popuptext');\">[?]</A>
			</td><td>
			<TEXTAREA name=\"warningtext\" cols=\"35\" rows=\"4\"></TEXTAREA>
			</td></tr>
			<tr><td colspan=\"2\">
			<INPUT type=\"submit\" value=\""._SUBMIT."\" name=\"submit\">
			</td></tr>
			</table>";

			//List of current ratings

			$output .= "<hr>";

			$output .= "<table class=\"tblborder\" cellspacing=\"0\" cellpadding=\"3\" align=\"center\">
			<tr><td colspan=\"3\" align=\"center\" class=\"tblborder\">
			<b>"._CURRENTRATS."</b>
			</td></tr>";
			$output .= "<tr><td class=\"tblborder\"><b>"._RATING."</b></td><td class=\"tblborder\"><b>"._WARNING."?</b></td><td class=\"tblborder\"><b>"._OPTIONS."</b></td></tr>";
			while ($ratingresults = mysql_fetch_array($result))
			{
				$output .= "<tr><td class=\"tblborder\">$ratingresults[rating]";
				if($ratingresults[ratingwarning] == "1")
					$output .= "</td><td class=\"tblborder\">"._YES."";
				else
					$output .= "</td><td class=\"tblborder\">"._NO."";
				$output .= "</td><td class=\"tblborder\"><a href=\"admin.php?action=ratingedit&amp;rid=$ratingresults[rid]\">"._EDIT."</a> | <a href=\"admin.php?action=ratingdelete&amp;rid=$ratingresults[rid]\">"._DELETE."</td></tr>";
			}
			$output .= "</table>";

		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function ratingedit($submit, $rating, $rid, $ratingwarning, $warningtext, $oldrating)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $level;
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
	if (($_SESSION['adminloggedin'] != "1") || (($level != '1') && ($level != '2')))
	{
		$output .= ""._NOTALLOWED."<BR><BR>";
	}
	else
	{
		$output .= adminmenu();
		$output .= "<center><h4>"._EDITRAT."</h4></center>";
		if ($submit)
		{
			if($ratingwarning == "on")
				$ratingwarning = "1";
			else
				$ratingwarning = "0";
			mysql_query("UPDATE ".$tableprefix."fanfiction_ratings SET rating = '$rating', ratingwarning = '$ratingwarning', warningtext = '$warningtext' WHERE rid = '$rid'");
			$newquery = mysql_query("SELECT sid, rid FROM ".$tableprefix."fanfiction_stories WHERE rid LIKE '%$oldrating%'");
			while($ratingresult = mysql_fetch_array($newquery))
			{
				$newrid = ereg_replace($oldrating, $rating, $ratingresult[rid]);

				mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET rid = '$newrid' WHERE sid = '$ratingresult[sid]'");
			}

			$output .= "<center>"._BTRATMAINT."</center>";
		}
		else
		{
			$result = mysql_query("SELECT * from ".$tableprefix."fanfiction_ratings WHERE rid = '$rid'");
			$ratingresults = mysql_fetch_array($result);
			$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"admin.php?action=ratingedit\">
			<table align=\"center\">
			<tr><td>
			"._RATING.":
			</td><td>
			<INPUT name=\"rating\" value=\"$ratingresults[rating]\">
			</td></tr>
			<tr><td>
			"._WARNINGPOP.":
			</td><td>
			<INPUT type=\"checkbox\" name=\"ratingwarning\"";
			if($ratingresults[ratingwarning] == "1")
				$output .= " checked";
			$output .= ">
			</td></tr>
			<tr><td>
			"._WARNINGTEXT.":
			</td><td>
			<TEXTAREA name=\"warningtext\" cols=\"35\" rows=\"4\">$ratingresults[warningtext]</TEXTAREA>
			</td></tr>
			<tr><td colspan=\"2\">
			<INPUT type=\"hidden\" value=\"$rid\" name=\"rid\">
			<INPUT type=\"hidden\" value=\"$ratingresults[rating]\" name=\"oldrating\">
			<INPUT type=\"submit\" value=\""._SUBMIT."\" name=\"submit\">
			</td></tr>
			</table></form>";

		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function ratingdelete($delete, $rid)
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
		$output .= "<center><h4>"._DELETERAT."</h4></center>";
		if($delete == "yes")
		{
			$result5 = mysql_query("SELECT rating FROM ".$tableprefix."fanfiction_ratings WHERE rid = '$rid'");
			$ratings = mysql_fetch_array($result5);
			$newquery5 = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_stories WHERE rid LIKE '%$ratings[rating]%'");
				while($ratingresult = mysql_fetch_array($newquery5))
				{
					$tok = strtok($ratingresult[rid], ", ");// tokenize the old list of names
					$newString = "";// the new list of good names
					while($tok)
					{
						if( $tok != $ratings[rating] )// oldname is the thing that is going away
						{
							// It's a keeper, so decide if it is first or not for comma-age, then add it in
							if( $newString != "" )
								$newString .= ", ";
							$newString .= $tok;
						}
						$tok = strtok(", "); //advance to the next token
					}
					mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET rid = '$newString' WHERE sid = '$ratingresult[sid]'");
				}
			mysql_query("DELETE FROM ".$tableprefix."fanfiction_ratings WHERE rid = '$rid'");
			$output .= "<center>"._BTRATMAINT."</center>";
		}
		else if ($delete == "no")
		{
			$output .= "<center>"._RATNOTDEL."</center>";
		}
		else
		{
			$output .= "<center>"._RATSUREDEL."<BR><BR>";
			$output .= "[ <a href=\"admin.php?action=ratingdelete&delete=yes&rid=$rid\">".YES."</a> | <a href=\"admin.php?action=ratingdelete&delete=no\">"._NO."</a> ]</center>";
		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function genres($submit, $genre)
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
		$output .= "<center><h4>"._GENMAINT."</h4></center>";
		if ($submit)
		{
			mysql_query("INSERT INTO ".$tableprefix."fanfiction_genres (genre) VALUES ('$genre')");
			$output .= "<center>"._BTGENMAINT."</center>";
		}
		else
		{
			$result = mysql_query("SELECT * from ".$tableprefix."fanfiction_genres");

			//Add new genre

			$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"admin.php?action=genres\">
			<table align=\"center\">
			<tr><td colspan=\"2\">
			<b>"._NEWGENRE."</b>
			</td></tr>
			<tr><td>
			"._GENRE.": <A HREF=\"javascript:n_window('docs/adminmanual.htm#genres');\">[?]</A>
			</td><td>
			<INPUT name=\"genre\">
			</td></tr>
			<tr><td colspan=\"2\">
			<INPUT type=\"submit\" value=\""._SUBMIT."\" name=\"submit\">
			</td></tr>
			</table></form>";

			//List of current genres

			$output .= "<hr>";

			$output .= "<table class=\"tblborder\" cellpadding=\"3\" cellspacing=\"0\" align=\"center\">
			<tr><td colspan=\"2\" class=\"tblborder\">
			<b>"._CURRENTGENS."</b>
			</td></tr>";
			while ($genreresults = mysql_fetch_array($result))
			{
				$output .= "<tr><td class=\"tblborder\">$genreresults[genre]";
				$output .= "</td><td class=\"tblborder\"><a href=\"admin.php?action=genreedit&amp;gid=$genreresults[gid]\">"._EDIT."</a> | <a href=\"admin.php?action=genredelete&amp;gid=$genreresults[gid]\">"._DELETE."</td></tr>";
			}
			$output .= "</table>";

		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function genreedit($submit, $genre, $gid, $oldgenre)
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
		$output .= "<center><h4>"._EDITGEN."</h4></center>";
		if ($submit)
		{
			mysql_query("UPDATE ".$tableprefix."fanfiction_genres SET genre = '$genre' WHERE gid = '$gid'");
			$newquery = mysql_query("SELECT sid, gid FROM ".$tableprefix."fanfiction_stories WHERE gid LIKE '%$oldgenre%'");
			while($genreresult = mysql_fetch_array($newquery))
			{
				$newgid = ereg_replace($oldgenre, $genre, $genreresult[gid]);

				mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET gid = '$newgid' WHERE sid = '$genreresult[sid]'");
			}
			$output .= "<center>"._BTGENMAINT."</center>";
		}
		else
		{
			$result = mysql_query("SELECT * from ".$tableprefix."fanfiction_genres WHERE gid = '$gid'");
			$genreresults = mysql_fetch_array($result);
			$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"admin.php?action=genreedit\">
			<table align=\"center\">
			<tr><td>
			"._GENRE.":
			</td><td>
			<INPUT name=\"genre\" value=\"$genreresults[genre]\">
			</td></tr>
			<tr><td colspan=\"2\">
			<INPUT type=\"hidden\" value=\"$gid\" name=\"gid\">
			<INPUT type=\"hidden\" value=\"$genreresults[genre]\" name=\"oldgenre\">
			<INPUT type=\"submit\" value=\""._SUBMIT."\" name=\"submit\">
			</td></tr>
			</table></form>";

		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function genredelete($delete, $gid)
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
		$output .= "<center><h4>"._DELETEGEN."</h4></center>";
		if($delete == "yes")
		{
			$result5 = mysql_query("SELECT genre FROM ".$tableprefix."fanfiction_genres WHERE gid = '$gid'");
			$genres = mysql_fetch_array($result5);
			$newquery5 = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_stories WHERE gid LIKE '%$genres[genre]%'");
				while($genreresult = mysql_fetch_array($newquery5))
				{
					$tok = strtok($genreresult[gid], ", ");// tokenize the old list of names
					$newString = "";// the new list of good names
					while($tok)
					{
						if( $tok != $genres[genre] )// oldname is the thing that is going away
						{
							// It's a keeper, so decide if it is first or not for comma-age, then add it in
							if( $newString != "" )
								$newString .= ", ";
							$newString .= $tok;
						}
						$tok = strtok(", "); //advance to the next token
					}
					mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET gid = '$newString' WHERE sid = '$genreresult[sid]'");
				}
			mysql_query("DELETE FROM ".$tableprefix."fanfiction_genres where gid = '$gid'");
			$output .= "<center>"._BTGENMAINT."</center>";
		}
		else if ($delete == "no")
		{
			$output .= "<center>"._GENNOTDEL."</center>";
		}
		else
		{
			$output .= "<center>"._GENSUREDEL."<br><br>";
			$output .= "[ <a href=\"admin.php?action=genredelete&delete=yes&gid=$gid\">"._YES."</a> | <a href=\"admin.php?action=genredelete&delete=no\">".NO."</a> ]</center>";
		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function warnings($submit, $warning)
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
		$output .= "<center><h4>"._WARNMAINT."</h4></center>";
		if ($submit)
		{
			mysql_query("INSERT INTO ".$tableprefix."fanfiction_warnings (warning) VALUES ('$warning')");
			$output .=  "<center>"._BTWARNMAINT."</center>";
		}
		else
		{
			$result = mysql_query("SELECT * from ".$tableprefix."fanfiction_warnings");

			//Add new warning

			$output .=  "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"admin.php?action=warnings\">
			<table align=\"center\">
			<tr><td colspan=\"2\">
			<b>"._NEWWARNING."</b>
			</td></tr>
			<tr><td>
			"._WARNING.": <A HREF=\"javascript:n_window('docs/adminmanual.htm#warnings');\">[?]</A>
			</td><td>
			<INPUT name=\"warning\">
			</td></tr>
			<tr><td colspan=\"2\">
			<INPUT type=\"submit\" value=\""._SUBMIT."\" name=\"submit\">
			</td></tr>
			</table></form>";

			//List of current warnings

			$output .=  "<hr>";

			$output .=  "<table class=\"tblborder\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">
			<tr><td colspan=\"2\" class=\"tblborder\">
			<b>"._CURRENTWARNINGS."</b>
			</td></tr>";
			while ($warningresults = mysql_fetch_array($result))
			{
				$output .=  "<tr><td class=\"tblborder\">$warningresults[warning]";
				$output .=  "</td><td class=\"tblborder\"><a href=\"admin.php?action=warningedit&amp;wid=$warningresults[wid]\">"._EDIT."</a> | <a href=\"admin.php?action=warningdelete&amp;wid=$warningresults[wid]\">"._DELETE."</td></tr>";
			}
			$output .=  "</table>";

		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function warningedit($submit, $warning, $wid, $oldwarning)
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
		$output .= "<center><h4>"._EDITWARNING."</h4></center>";
		if ($submit)
		{
			mysql_query("UPDATE ".$tableprefix."fanfiction_warnings SET warning = '$warning' WHERE wid = '$wid'");
			$newquery = mysql_query("SELECT sid, wid FROM ".$tableprefix."fanfiction_stories WHERE wid LIKE '%$oldwarning%'");
			while($warningresult = mysql_fetch_array($newquery))
			{
				$newwid = ereg_replace($oldwarning, $warning, $warningresult[wid]);

				mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET wid = '$newwid' WHERE sid = '$warningresult[sid]'");
			}
			$output .= "<center>"._BTWARNMAINT."</center>";
		}
		else
		{
			$result = mysql_query("SELECT * from ".$tableprefix."fanfiction_warnings WHERE wid = '$wid'");
			$warningresults = mysql_fetch_array($result);
			$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"admin.php?action=warningedit\">
			<table align=\"center\">
			<tr><td>
			"._WARNING.":
			</td><td>
			<INPUT name=\"warning\" value=\"$warningresults[warning]\">
			</td></tr>
			<tr><td colspan=\"2\">
			<INPUT type=\"hidden\" value=\"$wid\" name=\"wid\">
			<INPUT type=\"hidden\" value=\"$warningresults[warning]\" name=\"oldwarning\">
			<INPUT type=\"submit\" value=\""._SUBMIT."\" name=\"submit\">
			</td></tr>
			</table></form>";

		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function warningdelete($delete, $wid)
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
		$output .= "<center><h4>"._DELETEWARNING."</h4></center>";
		if($delete == "yes")
		{
			$result5 = mysql_query("SELECT warning FROM ".$tableprefix."fanfiction_warnings WHERE wid = '$wid'");
			$warnings = mysql_fetch_array($result5);
			$newquery5 = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_stories WHERE wid LIKE '%$warnings[warning]%'");
				while($warningresult = mysql_fetch_array($newquery5))
				{
					$tok = strtok($warningresult[wid], ", ");// tokenize the old list of names
					$newString = "";// the new list of good names
					while($tok)
					{
						if( $tok != $warnings[warning] )// oldname is the thing that is going away
						{
							// It's a keeper, so decide if it is first or not for comma-age, then add it in
							if( $newString != "" )
								$newString .= ", ";
							$newString .= $tok;
						}
						$tok = strtok(", "); //advance to the next token
					}
					mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET wid = '$newString' WHERE sid = '$warningresult[sid]'");
				}
			mysql_query("DELETE FROM ".$tableprefix."fanfiction_warnings where wid = '$wid'");
			$output .= "<center>"._BTWARNMAINT."</center>";
		}
		else if ($delete == "no")
		{
			$output .= "<center>"._WARNNOTDEL."</center>";
		}
		else
		{
			$output .= "<center>"._WARNSUREDEL."<br><br>";
			$output .= "[ <a href=\"admin.php?action=warningdelete&delete=yes&wid=$wid\">"._YES."</a> | <a href=\"admin.php?action=warningdelete&delete=no\">".NO."</a> ]</center>";
		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

?>