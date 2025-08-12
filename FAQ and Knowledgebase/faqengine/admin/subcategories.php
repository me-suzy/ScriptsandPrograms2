<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./auth.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
$page_title=$l_subcategory_title;
$page="subcategories";
require_once('./heading.php');
if(!isset($storefaqfilter) && ($admstorefaqfilters==1))
{
	$admcookievals="";
	if($new_global_handling)
	{
		if(isset($_COOKIE[$admcookiename]))
			$admcookievals = $_COOKIE[$admcookiename];
	}
	else
	{
		if(isset($_COOKIE[$admcookiename]))
			$admcookievals = $_COOKIE[$admcookiename];
	}
	if($admcookievals)
	{
		if(faqe_array_key_exists($admcookievals,"subcat_filterprog"))
			$filterprog=$admcookievals["subcat_filterprog"];
		if(faqe_array_key_exists($admcookievals,"subcat_filtercat"))
			$filtercat=$admcookievals["subcat_filtercat"];
		if(faqe_array_key_exists($admcookievals,"subcat_filterlang"))
			$filterlang=$admcookievals["subcat_filterlang"];
		if(faqe_array_key_exists($admcookievals,"subcat_sorting"))
			$sorting=$admcookievals["subcat_sorting"];
	}
}
if(!isset($sorting))
	$sorting=11;
if(!isset($filterprog))
	$filterprog=-1;
if(!isset($filtercat))
	$filtercat=-1;
if(!isset($filterlang))
	$filterlang="none";
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if(isset($mode))
{
	if($mode=="display")
	{
		if($admin_rights < 1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$tableprefix."_subcategory where (catnr=$input_subcatnr)";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.".faqe_db_error());
		if (!$myrow = faqe_db_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
		$displaycatname=stripslashes($myrow["categoryname"]);
		$displaycatname=do_htmlentities($displaycatname);
		$displaycatname=undo_html_ampersand($displaycatname);
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_displaysubcats?></b></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_subcatname?>:</td><td><?php echo $displaycatname?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_category?>:</td>
<td>
<?php
		$sql = "select cat.categoryname, prog.programmname, prog.language from ".$tableprefix."_programm prog, ".$tableprefix."_category cat where prog.prognr=cat.programm and cat.catnr=".$myrow["category"];
		if(!$result = faqe_db_query($sql, $db)) {
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		}
		if ($temprow = faqe_db_fetch_array($result))
		{
			echo undo_html_ampersand(do_htmlentities(stripslashes($temprow["categoryname"])))." : ";
			echo undo_html_ampersand(do_htmlentities(stripslashes($temprow["programmname"])))." [".$temprow["language"]."]";
		}
?>
</td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_relatedsubcats?>:</td>
<td>
<?php
		$sql = "select subcat.* from ".$tableprefix."_related_subcat rsc, ".$tableprefix."_subcategory subcat where rsc.srccat=$input_subcatnr and subcat.catnr=rsc.destcat";
		if(!$result = faqe_db_query($sql, $db)) {
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		}
		if (!$temprow = faqe_db_fetch_array($result))
		{
			echo $l_none;
		}
		else
		{
			do{
				echo $temprow["categoryname"]."<br>";
			}while($temprow=faqe_db_fetch_array($result));
		}
?>
</td></tr>
</table></tr></td></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_subcatlist</a></div>";
	}
	// Page called with some special mode
	if($mode=="new")
	{
		if($admin_rights < 2)
		{
			echo "<tr bgcolor=\"#cccccc\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		// Display empty form for entering subcategory
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newsubcategory?> (1/2)</b></td></tr>
<form name="inputform" onsubmit="return checkform1();" method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_subcatname?>:</td><td><input class="faqeinput" type="text" name="subcategory" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_category?>:</td>
<td>
<?php
	if($admin_rights<3)
		$sql = "select cat.*, prog.programmname, prog.language from ".$tableprefix."_programm prog, ".$tableprefix."_category cat, ".$tableprefix."_category_admins ca where cat.catnr = ca.catnr and ca.usernr=$act_usernr and prog.prognr=cat.programm ";
	else
		$sql = "select cat.*, prog.programmname, prog.language from ".$tableprefix."_programm prog, ".$tableprefix."_category cat where prog.prognr=cat.programm  ";
	if(bittst($admedoptions,BIT_1))
	{
		if(isset($filterprog) && ($filterprog>=0))
			$sql.="and prog.prognr=$filterprog ";
		if(isset($filterlang) && ($filterlang!="none"))
			$sql.="and prog.language='$filterlang' ";
	}
	$sql.="order by prog.language asc";
	if(bittst($admedoptions,BIT_2))
		$sql.=", prog.programmname asc, cat.categoryname asc";
	else
		$sql.=", prog.displaypos asc, cat.displaypos asc";
	if(!$result = faqe_db_query($sql, $db))
		die("Could not connect to the database.".faqe_db_error());
	if (!$temprow = faqe_db_fetch_array($result))
	{
		echo "<a href=\"".do_url_session("categories.php?mode=new&$langvar=$act_lang")."\" target=\"_blank\">$l_new</a>";
	}
	else
	{
?>
<select name="category">
<option value="-1">???</option>
<?php
	do {
		echo "<option value=\"".$temprow["catnr"]."\"";
		if(bittst($admedoptions,BIT_1) && isset($filtercat) && ($filtercat>0) && ($filtercat==$temprow["catnr"]))
			echo " selected";
		echo ">";
		echo display_encoded($temprow["categoryname"]);
		echo " (";
		echo display_encoded($temprow["programmname"]);
		echo " [";
		echo stripslashes($temprow["language"]);
		echo "])</option>";
	} while($temprow = faqe_db_fetch_array($result));
?>
</select>
<?php
	}
?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2">
<input type="hidden" name="mode" value="new2">
<input class="faqebutton" type="submit" value="<?php echo $l_continue?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_subcatlist?></a></div>
<?php
	}
	if($mode=="new2")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newsubcategory?> (2/2)</b></td></tr>
<?php
		$errors=0;
		if($category<1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nocategory</td></tr>";
			$errors=1;
		}
		if(!$subcategory)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nosubcatname</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
?>
<form method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_subcatname?>:</td><td><?php echo display_encoded($subcategory)?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_category?>:</td>
<td>
<?php
			$sql = "select cat.categoryname, prog.prognr, prog.programmname, prog.language from ".$tableprefix."_programm prog, ".$tableprefix."_category cat where prog.prognr=cat.programm and cat.catnr=".$category;
			if(!$result = faqe_db_query($sql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database (3).");
			if ($temprow = faqe_db_fetch_array($result))
			{
				$prognr=$temprow["prognr"];
				echo display_encoded($temprow["categoryname"])." : ";
				echo display_encoded($temprow["programmname"])." [".$temprow["language"]."]";
			}
?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_relatedsubcats?>:</td>
<td>
<?php
			$sql = "SELECT subcat.*, cat.categoryname as maincat from ".$tableprefix."_subcategory subcat, ".$tableprefix."_category cat WHERE cat.programm=$prognr and subcat.category=cat.catnr order by cat.displaypos, subcat.displaypos";
		    if(!$result = faqe_db_query($sql, $db))
				die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database.".faqe_db_error());
		    if($catrow = faqe_db_fetch_array($result))
		    {
				echo "<SELECT NAME=\"relsubcats[]\" size=\"5\" multiple>";
				do{
					echo "<OPTION VALUE=\"".$catrow["catnr"]."\" >".display_encoded($catrow["categoryname"])." (".display_encoded($catrow["maincat"]).")</OPTION>\n";
				} while($catrow = faqe_db_fetch_array($result));
				echo "</select>";
			}
			else {
				echo "$l_none_avail\n";
			}
?>
</td></tr>
<input type="hidden" name="category" value="<?php echo $category?>">
<input type="hidden" name="subcategory" value="<?php echo do_htmlentities($subcategory)?>">
<tr class="actionrow"><td align="center" colspan="2">
<input type="hidden" name="mode" value="add">
<input class="faqebutton" type="submit" value="<?php echo $l_continue?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_subcatlist?></a></div>
<?php
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
	if($mode=="add")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		// Add new category to database
		$errors=0;
		if($category<1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nocategory</td></tr>";
			$errors=1;
		}
		if(!$subcategory)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nosubcatname</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$sql = "select max(displaypos) as newdisplaypos from ".$tableprefix."_subcategory where category=$category";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to add subcategory to database.");
			if($myrow=faqe_db_fetch_array($result))
				$displaypos=$myrow["newdisplaypos"]+1;
			else
				$displaypos=1;
			$category=addslashes($category);
			$sql = "INSERT INTO ".$tableprefix."_subcategory (categoryname, category, displaypos) ";
			$sql .="VALUES ('$subcategory', '$category', $displaypos)";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to add subcategory to database.");
			$subcatnr = faqe_db_insert_id($db);
			if(isset($relsubcats))
			{
	    		while(list($null, $refsubcat) = each($_POST["relsubcats"])) {
	    			$cat_query = "INSERT INTO ".$tableprefix."_related_subcat (srccat, destcat) VALUES ($subcatnr,$refsubcat)";
	    		   	if(!faqe_db_query($cat_query, $db))
					    die("<tr class=\"errorrow\"><td>Unable to update the database.");
	    			$cat_query = "INSERT INTO ".$tableprefix."_related_subcat (destcat, srccat) VALUES ($subcatnr,$refsubcat)";
	    		   	if(!faqe_db_query($cat_query, $db))
					    die("<tr class=\"errorrow\"><td>Unable to update the database.");
	    		}
			}
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_subcatadded";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?mode=new&$langvar=$act_lang")."\">$l_newsubcategory</a></div>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_subcatlist</a></div>";
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
	if($mode=="delete")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		if(isset($faq_action))
		{
			$sql = "select count(faqnr) from ".$tableprefix."_data where (subcategory=$input_subcatnr)";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to connect to database.");
			if ($temprow = faqe_db_fetch_array($result))
				$faqcount=$temprow["count(faqnr)"];
			else
				$faqcount=0;
			if($faq_action=="moveup")
			{
				$deleteSQL = "update ".$tableprefix."_data set subcategory=0 where (subcategory=$input_subcatnr)";
				$success = faqe_db_query($deleteSQL,$db);
				if (!$success)
					die("<tr class=\"errorrow\"><td>$l_cantmove.");
				echo "<tr class=\"displayrow\" align=\"center\"><td>";
				echo "<i>$faqcount</i>$l_faq $l_moved<br></td></tr>";
			}
			if($faq_action=="del")
			{
				$deleteSQL = "delete from ".$tableprefix."_data where (subcategory=$input_subcatnr)";
				$success = faqe_db_query($deleteSQL,$db);
				if (!$success)
					die("<tr class=\"errorrow\"><td>$l_cantdelete.");
				echo "<tr class=\"displayrow\" align=\"center\"><td>";
				echo "<i>$faqcount</i>$l_faq $l_deleted<br></td></tr>";
			}
			if($faq_action=="move")
			{
				if($new_cat>0)
				{
					$moveSQL = "UPDATE ".$tableprefix."_data set subcategory=$new_cat where (subcategory=$input_subcatnr)";
					$success = faqe_db_query($moveSQL,$db);
					if (!$success)
						die("<tr class=\"errorrow\"><td>$l_cantmove.");
					$sql = "UPDATE ".$tableprefix."_category SET numfaqs = numfaqs + $faqcount WHERE (catnr = $new_maincat)";
					@faqe_db_query($sql, $db);
					echo "<tr class=\"displayrow\" align=\"center\"><td>";
					echo "<i>$faqcount</i> $l_faq $l_moved<br></td></tr>";
				}
			}
		}
		$sql = "select count(faqnr) from ".$tableprefix."_data where (subcategory=$input_subcatnr)";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.");
		if ($temprow = faqe_db_fetch_array($result))
			$faqcount=$temprow["count(faqnr)"];
		else
			$faqcount=0;
		if($faqcount>0)
		{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo "$l_delsubcat ($catname)"?></b></td></tr>
<tr><td class="inforow" align="center" colspan="2"><?php echo "$l_faqincat ($faqcount)"?></td></tr>
<form action="<?php echo $act_script_url?>" method="post"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			if(is_konqueror())
				echo "<tr><td></td></tr>";
?>
<input type="hidden" name="mode" value="delete">
<input type="hidden" name="input_catnr" value="<?php echo $input_catnr?>">
<input type="hidden" name="input_subcatnr" value="<?php echo $input_subcatnr?>">
<input type="hidden" name="catname" value="<?php echo $catname?>"><input type="hidden" name="oldprog" value="<?php echo $oldprog?>">
<tr><td class="inputrow"><input type="radio" name="faq_action" value="del"><?php echo "$l_delfaq"?></td></tr>
<tr><td class="inputrow"><input type="radio" name="faq_action" value="moveup"><?php echo "$l_movefaq"?> <?php echo $l_to?> <?php echo $l_maincat?></td></tr>
<tr><td class="inputrow"><input type="radio" name="faq_action" value="move"><?php echo "$l_movefaq"?> <?php echo $l_to?>:
<?php
	$sql1 = "select * from ".$tableprefix."_subcategory where (catnr != $input_subcatnr) and (category = $input_catnr) order by displaypos";
	if(!$result1 = faqe_db_query($sql1, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	if (!$temprow = faqe_db_fetch_array($result1))
		echo "$l_noentries";
	else
	{
?>
<select name="new_cat">
<option value="-1">???</option>
<?php
	do {
		$subcatname=display_encoded($temprow["categoryname"]);
		$catnr=$temprow["category"];
		$sql2 = "select cat.categoryname, prog.programmname, prog.language from ".$tableprefix."_programm prog, ".$tableprefix."_category cat where prog.prognr=cat.programm and cat.catnr=$catnr order by prog.language, prog.displaypos, cat.displaypos";
		if(!$result2 = faqe_db_query($sql2, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if($temprow2 = faqe_db_fetch_array($result2))
		{
			$catname=display_encoded($temprow2["categoryname"]);
			$progname=display_encoded($temprow2["programmname"]);
			$proglang=$temprow2["language"];
		}
		else
		{
			$catname=$l_undefined;
			$progname=$l_undefined;
			$proglang=$l_none;
		}
		echo "<option value=\"".$temprow["catnr"]."\">";
		echo "$subcatname ($catname : $progname [$proglang])";
		echo "</option>";
	} while($temprow = faqe_db_fetch_array($result1));
?>
</select>
<?php
	}
?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2">
<input class="faqebutton" type="submit" value="<?php echo $l_ok?>"></td></tr>
</form>
</table></tr></td></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_subcatlist?></a></div>
<?php
		}
		else
		{
			$deleteSQL = "delete from ".$tableprefix."_subcategory where (catnr=$input_subcatnr)";
			$success = faqe_db_query($deleteSQL,$db);
			if (!$success)
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			$deleteSQL = "delete from ".$tableprefix."_related_subcat where srccat=$input_subcatnr or destcat=$input_catnr";
			$success = faqe_db_query($deleteSQL,$db);
			if (!$success)
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "<i>$catname</i> $l_deleted<br>";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_subcatlist</a></div>";
		}
	}
	if($mode=="edit")
	{
		$modsql="select * from ".$tableprefix."_category_admins where catnr=$input_catnr and usernr=$act_usernr";
		if(!$modresult = faqe_db_query($modsql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if($modrow=faqe_db_fetch_array($modresult))
			$ismod=1;
		else
			$ismod=0;
		if(($admin_rights < 2) || (($admin_rights < 3) && ($ismod==0)))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_functionnotallowed</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_subcatlist</a></div>";
			include('./trailer.php');
			exit;
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$tableprefix."_subcategory where (catnr=$input_subcatnr)";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = faqe_db_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_editsubcats?> (1/2)</b></td></tr>
<form name="inputform" onsubmit="return checkform1();" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<input type="hidden" name="input_catnr" value="<?php echo $input_catnr?>">
<input type="hidden" name="input_subcatnr" value="<?php echo $myrow["catnr"]?>">
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_catname?>:</td><td><input class="faqeinput" type="text" name="subcategory" size="40" maxlength="80" value="<?php echo display_encoded($myrow["categoryname"])?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_category?>:</td>
<td>
<?php
	if($admin_rights<3)
		$sql = "select pr.programmname, pr.language, cat.categoryname, cat.catnr from ".$tableprefix."_programm pr, ".$tableprefix."_category cat, ".$tableprefix."_category_admins ca where pr.prognr = cat.programm and ca.usernr=".$userdata["usernr"]." ";
	else
		$sql = "select pr.programmname, pr.language, cat.categoryname, cat.catnr from ".$tableprefix."_programm pr, ".$tableprefix."_category cat where pr.prognr=cat.programm ";
	if(bittst($admedoptions,BIT_1))
	{
		if(isset($filterprog) && ($filterprog>=0))
			$sql.="and pr.prognr=$filterprog ";
		if(isset($filterlang) && ($filterlang!="none"))
			$sql.="and pr.language='$filterlang' ";
	}
	$sql.="order by pr.language asc";
	if(bittst($admedoptions,BIT_2))
		$sql.=", pr.programmname asc, cat.categoryname asc";
	else
		$sql.=", pr.displaypos asc, cat.displaypos asc";
	if(!$result = faqe_db_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database (3).");
	if (!$temprow = faqe_db_fetch_array($result))
	{
		echo "<a href=\"".do_url_session("category.php?mode=new&$langvar=$act_lang")."\" target=\"_blank\">$l_new</a>";
	}
	else
	{
?>
<select name="category">
<option value="-1">???</option>
<?php
	do {
		echo "<option value=\"".$temprow["catnr"]."\"";
		if($myrow["category"]==$temprow["catnr"])
			echo " selected";
		echo ">";
		echo display_encoded($temprow["categoryname"]);
		echo " (";
		echo display_encoded($temprow["programmname"]);
		echo " [";
		echo stripslashes($temprow["language"]);
		echo "])";
		echo "</option>";
	} while($temprow = faqe_db_fetch_array($result));
?>
</select>
<?php
	}
?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="edit2">
<input class="faqebutton" type="submit" value="<?php echo $l_continue?>"></td></tr>
</form>
</table></tr></td></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_subcatlist?></a></div>
<?php
	}
	if($mode=="edit2")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_editsubcats?> (2/2)</b></td></tr>
<?php
		$errors=0;
		if($category<1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nocategory</td></tr>";
			$errors=1;
		}
		if(!$subcategory)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nosubcatname</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
?>
<form method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
		$displaycatname=display_encoded($subcategory);
?>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_subcatname?>:</td><td><?php echo $displaycatname?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_category?>:</td>
<td>
<?php
			$sql = "select cat.categoryname, prog.prognr, prog.programmname, prog.language from ".$tableprefix."_programm prog, ".$tableprefix."_category cat where prog.prognr=cat.programm and cat.catnr=".$category;
			if(!$result = faqe_db_query($sql, $db))
				die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database (3).");
			if ($temprow = faqe_db_fetch_array($result))
			{
				$prognr=$temprow["prognr"];
				echo display_encoded($temprow["categoryname"])." : ".display_encoded($temprow["programmname"])." [".$temprow["language"]."]";
			}
?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_relatedsubcats?>:</td>
<td>
<?php
			$catsql = "SELECT subcat.*, cat.categoryname as maincat from ".$tableprefix."_subcategory subcat, ".$tableprefix."_related_subcat rsc, ".$tableprefix."_category cat where rsc.srccat=$input_subcatnr and subcat.catnr=rsc.destcat and cat.catnr=subcat.category order by subcat.displaypos";
			if(!$catresult = faqe_db_query($catsql, $db))
			    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if ($catrow = faqe_db_fetch_array($catresult))
			{
				 do {
				    echo display_encoded($catrow["categoryname"])." (".display_encoded($catrow["maincat"]).") (<input type=\"checkbox\" name=\"rem_relsubcats[]\" value=\"".$catrow["catnr"]."\"> $l_remove)<BR>";
				    $current_relsubcats[] = $catrow["catnr"];
				 } while($catrow = faqe_db_fetch_array($catresult));
				 echo "<br>";
			}
			else
				echo "$l_norelatedsubcats<br><br>";
			$catsql = "SELECT subcat.*, cat.categoryname as maincat from ".$tableprefix."_subcategory subcat, ".$tableprefix."_category cat WHERE cat.programm=$prognr and subcat.category=cat.catnr and subcat.catnr!=$input_subcatnr ";
			if(isset($current_relsubcats))
			{
    			while(list($null, $currCat) = each($current_relsubcats)) {
					$catsql .= "and subcat.catnr != $currCat ";
    			}
    		}
    		$catsql .="order by subcat.displaypos";
    		if(!$catresult = faqe_db_query($catsql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database.".faqe_db_error());
    		if($catrow = faqe_db_fetch_array($catresult)) {
				echo "<span class=\"inlineheading1\">$l_add:</span><br>";
				echo "<SELECT NAME=\"relsubcats[]\" size=\"5\" multiple>";
				do {
					echo "<OPTION VALUE=\"".$catrow["catnr"]."\" >".display_encoded($catrow["categoryname"])."(".display_encoded($catrow["maincat"]).")</OPTION>\n";
				} while($catrow = faqe_db_fetch_array($catresult));
				echo "</select>";
			}
?>
</td></tr>
<input type="hidden" name="category" value="<?php echo $category?>">
<input type="hidden" name="subcategory" value="<?php echo do_htmlentities($subcategory)?>">
<input type="hidden" name="input_catnr" value="<?php echo $input_catnr?>">
<input type="hidden" name="input_subcatnr" value="<?php echo $input_subcatnr?>">
<tr class="actionrow"><td align="center" colspan="2">
<input type="hidden" name="mode" value="update">
<input class="faqebutton" type="submit" value="<?php echo $l_continue?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_subcatlist?></a></div>
<?php
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
	if($mode=="update")
	{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$modsql="select * from ".$tableprefix."_category_admins where catnr=$input_catnr and usernr=$act_usernr";
		if(!$modresult = faqe_db_query($modsql, $db)) {
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		}
		if($modrow=faqe_db_fetch_array($modresult))
			$ismod=1;
		else
			$ismod=0;
		if(($admin_rights < 2) || (($admin_rights < 3) && ($ismod==0)))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_functionnotallowed</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_subcatlist</a></div>";
			include('./trailer.php');
			exit;
		}
		$errors=0;
		if($category<1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nocategory</td></tr>";
			$errors=1;
		}
		if(!$subcategory)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nosubcatname</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$sql = "UPDATE ".$tableprefix."_subcategory SET categoryname='$subcategory', category='$category' ";
			$sql .=" WHERE (catnr = $input_subcatnr)";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to update the database.");
			if(isset($rem_relsubcats))
			{
				while(list($null, $subcat) = each($_POST["rem_relsubcats"]))
				{
					$rem_query = "DELETE FROM ".$tableprefix."_related_subcat WHERE srccat = '$input_subcatnr' AND destcat = '$subcat'";
	       			if(!faqe_db_query($rem_query,$db))
					    die("<tr class=\"errorrow\"><td>Unable to update the database.");
					$rem_query = "DELETE FROM ".$tableprefix."_related_subcat WHERE destcat = '$input_subcatnr' AND srccat = '$subcat'";
	       			if(!faqe_db_query($rem_query,$db))
					    die("<tr class=\"errorrow\"><td>Unable to update the database.");
				}
			}
			if(isset($relsubcats))
			{
	    		while(list($null, $relsubcat) = each($_POST["relsubcats"])) {
	    			$cat_query = "INSERT INTO ".$tableprefix."_related_subcat (srccat, destcat) VALUES ($input_subcatnr,$relsubcat)";
	    		   	if(!faqe_db_query($cat_query, $db))
					    die("<tr class=\"errorrow\"><td>Unable to update the database.");
	    			$cat_query = "INSERT INTO ".$tableprefix."_related_subcat (destcat, srccat) VALUES ($input_subcatnr,$relsubcat)";
	    		   	if(!faqe_db_query($cat_query, $db))
					    die("<tr class=\"errorrow\"><td>Unable to update the database.");
	    		}
			}

			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_subcatupdated";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_subcatlist</a></div>";
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
}
else
{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	if($admin_rights>1)
	{
?>
<tr class="actionrow"><td colspan="6" align="center">
<a href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_newsubcategory?></a>
</table></td></tr></table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	}
// Display list of actual subcategories
$sql="select subcat.* from ".$tableprefix."_subcategory subcat, ".$tableprefix."_category cat, ".$tableprefix."_programm prog where subcat.category=cat.catnr and cat.programm=prog.prognr ";
if(isset($filtercat) && ($filtercat >=0))
	$sql.= "and cat.catnr=$filtercat ";
if(isset($filterprog) && ($filterprog >=0))
	$sql.="and prog.prognr=$filterprog ";
if(isset($filterlang) && ($filterlang!="none"))
	$sql.="and prog.language='$filterlang' ";
$sql.=" group by subcat.catnr ";
switch($sorting)
{
	case 12:
		$sql.="order by subcat.catnr desc";
		break;
	case 21:
		$sql.="order by subcat.categoryname asc";
		break;
	case 22:
		$sql.="order by subcat.categoryname desc";
		break;
	case 31:
		$sql.="order by subcat.category asc";
		break;
	case 32:
		$sql.="order by subcat.category desc";
		break;
	default:
		$sql.="order by subcat.catnr asc";
		break;
}
if(!$result = faqe_db_query($sql, $db)) {
    die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error()."<br>".$sql);
}
if (!$myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
	$maxsortcol=3;
	$baseurl="$act_script_url?$langvar=$act_lang";
	if(isset($filterprog))
		$baseurl.="&filterprog=$filterprog";
	if(isset($filtercat))
		$baseurl.="&filtercat=$filtercat";
	if(isset($filterlang))
		$baseurl.="&filterlang=$filterlang";
	if($admstorefaqfilters==1)
		$baseurl.="&storefaqfilter=1";
	echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\" width=\"5%\">";
	$sorturl=getSortURL($sorting, 1, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>#</b></a>";
	echo getSortMarker($sorting, 1, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\" width=\"50%\">";
	$sorturl=getSortURL($sorting, 2, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_subcatname</b></a>";
	echo getSortMarker($sorting, 2, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\" width=\"40%\">";
	$sorturl=getSortURL($sorting, 3, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_category</b></a>";
	echo getSortMarker($sorting, 3, $maxsortcol);
	echo "</td>";
	echo "<td>&nbsp;</td></tr>";
	do {
		$act_id=$myrow["catnr"];
		$act_cat=$myrow["category"];
		echo "<tr class=\"displayrow\">";
		echo "<td align=\"right\">".$myrow["catnr"]."</td>";
		$displaycatname=display_encoded($myrow["categoryname"]);
		echo "<td>".$displaycatname."</td>";
		$tempsql = "select cat.categoryname, prog.programmname, prog.language from ".$tableprefix."_programm prog, ".$tableprefix."_category cat where prog.prognr=cat.programm and cat.catnr=".$myrow["category"];
		if(!$tempresult = faqe_db_query($tempsql, $db)) {
		    die("Could not connect to the database.");
		}
		if (!$temprow = faqe_db_fetch_array($tempresult))
		{
			$progame=$l_undefined;
			$proglang=$l_none;
			$catname=$l_undefined;
		}
		else
		{
			$catname=display_encoded($temprow["categoryname"]);
			$progname=display_encoded($temprow["programmname"]);
			$proglang=$temprow["language"];
		}
		echo "<td>$progname [$proglang]: $catname</td>";
		echo "<td>";
		$modsql="select * from ".$tableprefix."_category_admins where catnr=$act_cat and usernr=".$userdata["usernr"];
		if(!$modresult = faqe_db_query($modsql, $db)) {
		    die("Could not connect to the database.");
		}
		if($modrow=faqe_db_fetch_array($modresult))
			$ismod=1;
		else
			$ismod=0;
		if(($admin_rights>2) || ($ismod==1))
		{
			echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=delete&input_subcatnr=$act_id&$langvar=$act_lang&catname=".urlencode($myrow["categoryname"])."&input_catnr=".$myrow["category"])."\">";
			echo "<img src=\"gfx/delete.gif\" border=\"0\" alt=\"$l_delete\" title=\"$l_delete\"></a>";
			echo "&nbsp; ";
			echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=edit&input_subcatnr=$act_id&$langvar=$act_lang&input_catnr=".$myrow["category"])."\">";
			echo "<img src=\"gfx/edit.gif\" border=\"0\" alt=\"$l_edit\" title=\"$l_edit\"></a>&nbsp; ";
		}
		echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=display&input_subcatnr=$act_id&$langvar=$act_lang&catname=".urlencode($myrow["categoryname"]))."\">";
		echo "<img src=\"gfx/view.gif\" border=\"0\" alt=\"$l_display\" title=\"$l_display\"></a>";
		echo "</td></tr>";
	} while($myrow = faqe_db_fetch_array($result));
	echo "</table></tr></td></table>";
}
if($admin_rights > 1)
{
	include("./includes/faq_filterboxes.inc");
?>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_newsubcategory?></a></div>
<?php
}
}
include('./trailer.php');
?>