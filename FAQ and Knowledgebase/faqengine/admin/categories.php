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
$page_title=$l_category_title;
$page="categories";
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
			if(faqe_array_key_exists($admcookievals,"cat_filterprog"))
				$filterprog=$admcookievals["cat_filterprog"];
			if(faqe_array_key_exists($admcookievals,"cat_filterlang"))
				$filterlang=$admcookievals["cat_filterlang"];
			if(faqe_array_key_exists($admcookievals,"cat_sorting"))
				$sorting=$admcookievals["cat_sorting"];
	}
}
if(!isset($filterprog))
	$filterprog=-1;
if($filterprog<0)
	if(isset($sorting) && ($sorting==99))
		$sorting=0;
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
		$sql = "select * from ".$tableprefix."_category where (catnr=$input_catnr)";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = faqe_db_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_displaycats?></b></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_catname?>:</td><td><?php echo display_encoded($myrow["categoryname"])?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_programm?>:</td>
<td>
<?php
	$sql = "select * from ".$tableprefix."_programm where(prognr=$oldprog)";
	if(!$result = faqe_db_query($sql, $db)) {
		die("<tr class=\"errorrow\"><td>Could not connect to the database (3).");
	}
	if ($temprow = faqe_db_fetch_array($result))
		echo display_encoded($temprow["programmname"])." [".$temprow["language"]."]";
?>
</td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_relatedcats?>:</td>
<td>
<?php
	$sql = "select cat.* from ".$tableprefix."_related_categories rc, ".$tableprefix."_category cat where rc.srccat=$input_catnr and cat.catnr=rc.destcat";
	if(!$result = faqe_db_query($sql, $db)) {
		die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database (3).");
	}
	if (!$temprow = faqe_db_fetch_array($result))
	{
		echo $l_none;
	}
	else
	{
		do{
			echo display_encoded($temprow["categoryname"])."<br>";
		}while($temprow=faqe_db_fetch_array($result));
	}
?>
</td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_referenced_categories?>:</td>
<td>
<?php
	$sql = "select cf.language, cat.* from ".$tableprefix."_category_ref cf, ".$tableprefix."_category cat where (cf.srccatnr=$input_catnr) and (cat.catnr=cf.destcatnr)";
	if(!$result = faqe_db_query($sql, $db)) {
		die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database (3).");
	}
	if (!$temprow = faqe_db_fetch_array($result))
	{
		echo $l_none;
	}
	else
	{
		do{
			echo display_encoded($temprow["categoryname"])." [".$temprow["language"]."]<br>";
		}while($temprow=faqe_db_fetch_array($result));
	}
?>
</td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_admins?>:</td>
<td>
<?php
	$sql = "SELECT u.username, u.usernr FROM ".$tableprefix."_admins u, ".$tableprefix."_category_admins f WHERE f.catnr = '$input_catnr' AND u.usernr = f.usernr order by u.username";
	if(!$r = faqe_db_query($sql, $db))
	    die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database.");
	if ($row = faqe_db_fetch_array($r))
	{
		 do {
		    echo $row["username"]."<BR>";
		 } while($row = faqe_db_fetch_array($r));
	}
	else
		echo "$l_noadmins<br>";
?>
</td></tr>
</table></tr></td></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_catlist</a></div>";
	}
	// Page called with some special mode
	if($mode=="new")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		// Display empty form for entering category
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newcategory?> (1/2)</b></td></tr>
<form name="inputform" onsubmit="return checkform1();" method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_catname?>:</td><td><input class="faqeinput" type="text" name="category" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_programm?>:</td>
<td>
<?php
	$firstarg=true;
	if($admin_rights<3)
	{
		$sql = "select pr.* from ".$tableprefix."_programm pr, ".$tableprefix."_programm_admins pa where pr.prognr = pa.prognr and pa.usernr=$act_usernr ";
		$firstarg=false;
	}
	else
		$sql = "select pr.* from ".$tableprefix."_programm pr ";
	if(bittst($admedoptions,BIT_1))
	{
		if(isset($filterlang) && ($filterlang!="none"))
		{
			if($firstarg)
			{
				$firstarg=false;
				$sql.="where ";
			}
			else
				$sql.="and ";
			$sql.="pr.language='$filterlang' ";
		}
	}
	$sql.="order by pr.language asc ";
	if(bittst($admedoptions,BIT_2))
		$sql.=", pr.programmname asc";
	else
		$sql.=", pr.displaypos asc";
	if(!$result = faqe_db_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database");
	if (!$temprow = faqe_db_fetch_array($result))
	{
		echo "<a href=\"".do_url_session("program.php?mode=new&$langvar=$act_lang")."\" target=\"_blank\">$l_new</a>";
	}
	else
	{
?>
<select name="programm">
<option value="-1">???</option>
<?php
	do {
		echo "<option value=\"".$temprow["prognr"]."\"";
		if(bittst($admedoptions,BIT_1) && isset($filterprog) && ($filterprog>=0) && ($filterprog==$temprow["prognr"]))
			echo " selected";
		echo ">";
		echo display_encoded($temprow["programmname"]);
		echo " | ";
		echo stripslashes($temprow["language"]);
		echo "</option>";
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
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_catlist?></a></div>
<?php
	}
	if($mode=="new2")
	{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		$errors=0;
		if(!$category)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nocatname</td></tr>";
			$errors=1;
		}
		if($programm<1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noprogramm</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
?>
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newcategory?> (2/2)</b></td></tr>
<form method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			if(is_konqueror())
				echo "<tr><td></td></tr>";
?>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_category?></td>
<td><?php echo display_encoded($category)?>
<input type="hidden" name="category" value="<?php echo do_htmlentities(stripslashes($category))?>"></td></tr>
<?php
			$sql = "select * from ".$tableprefix."_programm where prognr=$programm";
		    if(!$result = faqe_db_query($sql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if($myrow=faqe_db_fetch_array($result))
			{
				$progname=display_encoded($myrow["programmname"]);
				$proglang=$myrow["language"];
				$progid=$myrow["progid"];
			}
			else
			{
				$progname=$l_none;
				$proglang=$l_none;
			}
?>
<tr class="displayrow"><td align="right"><?php echo $l_programm?>:</td>
<td><?php echo "$progname [$proglang]"?>
<input type="hidden" name="programm" value="<?php echo $programm?>">
<input type="hidden" name="srclang" value="<?php echo $proglang?>">
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_relatedcats?>:</td>
<td>
<?php
			$sql = "SELECT * from ".$tableprefix."_category WHERE programm=$programm order by displaypos";
		    if(!$result = faqe_db_query($sql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database.".faqe_db_error());
		    if($catrow = faqe_db_fetch_array($result))
		    {
				echo "<SELECT NAME=\"relcats[]\" size=\"5\" multiple>";
				do{
					echo "<OPTION VALUE=\"".$catrow["catnr"]."\" >".display_encoded($catrow["categoryname"])."</OPTION>\n";
				} while($catrow = faqe_db_fetch_array($result));
				echo "</select>";
			}
			else {
				echo "$l_none_avail\n";
			}
?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_referenced_categories?>:</td>
<td>
<?php
			$sql = "SELECT cat.*, prog.language from ".$tableprefix."_category cat, ".$tableprefix."_programm prog WHERE prog.progid='$progid' and prog.language!='$proglang' and cat.programm=prog.prognr order by prog.language";
		    if(!$result = faqe_db_query($sql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database.".faqe_db_error());
		    if($catrow = faqe_db_fetch_array($result))
		    {
				echo "<SELECT NAME=\"cats[]\" size=\"5\" multiple>";
				do{
					echo "<OPTION VALUE=\"".$catrow["catnr"]."|".$catrow["language"]."\" >".$catrow["categoryname"]." [".$catrow["language"]."]</OPTION>\n";
				} while($catrow = faqe_db_fetch_array($result));
				echo "</select>";
			}
			else {
				echo "$l_none_avail\n";
			}
?>
</td></tr>
<?php
			if($admin_rights>2)
			{
?>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_admins?>:</td>
<td>
<?php
				$sql = "SELECT u.usernr, u.username FROM ".$tableprefix."_admins u, ".$tableprefix."_programm_admins pa WHERE pa.prognr=$programm and u.usernr=pa.usernr or u.rights>2 group by u.usernr ";
			    $sql .= "ORDER BY u.username";
			    if(!$result = faqe_db_query($sql, $db))
					die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			    if($modrow = faqe_db_fetch_array($result))
			    {
					echo "<SELECT NAME=\"mods[]\" size=\"5\" multiple>";
					do {
						echo "<OPTION VALUE=\"".$modrow["usernr"]."\" selected>";
						echo $modrow["username"]."</OPTION>\n";
					} while($modrow = faqe_db_fetch_array($result));
					echo "</select>";
				}
				else {
					echo "$l_none_avail\n";
				}
?>
</td></tr>
<?php
			}
			else
			{
				$sql = "SELECT u.usernr, u.username FROM ".$tableprefix."_admins u, ".$tableprefix."_programm_admins pa WHERE pa.prognr=$programm and u.usernr=pa.usernr or u.rights>2 group by u.usernr ";
			    $sql .= "ORDER BY u.username";
			    if(!$result = faqe_db_query($sql, $db))
					die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			    if($modrow = faqe_db_fetch_array($result))
			    {
			    	do{
						echo "<input type=\"hidden\" name=\"mods[]\" value=\"".$modrow["usernr"]."\">";
					}while($modrow=faqe_db_fetch_array($result));
				}
				else
					echo "<input type=\"hidden\" name=\"mods[]\" value=\"$act_usernr\">";
			}
?>
<tr class="actionrow"><td align="center" colspan="2">
<input type="hidden" name="mode" value="add">
<input class="faqebutton" type="submit" value="<?php echo $l_add?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_catlist?></a></div>
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
		if(!$category)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nocatname</td></tr>";
			$errors=1;
		}
		if($programm<1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noprogramm</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$sql = "UPDATE ".$tableprefix."_programm SET numcats = numcats + 1 WHERE (prognr = $programm)";
			@faqe_db_query($sql, $db);
			$sql = "select max(displaypos) as newdisplaypos from ".$tableprefix."_category where programm=$programm";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to add category to database.");
			if($myrow=faqe_db_fetch_array($result))
				$displaypos=$myrow["newdisplaypos"]+1;
			else
				$displaypos=1;
			$category=addslashes($category);
			$sql = "INSERT INTO ".$tableprefix."_category (categoryname, programm, displaypos) ";
			$sql .="VALUES ('$category', '$programm', $displaypos)";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to add category to database.");
			$catnr = faqe_db_insert_id($db);
			if(isset($mods))
			{
	    		while(list($null, $mod) = each($_POST["mods"])) {
					$mod_query = "INSERT INTO ".$tableprefix."_category_admins (catnr, usernr) VALUES ('$catnr', '$mod')";
	    		   	if(!faqe_db_query($mod_query, $db))
					    die("<tr class=\"errorrow\"><td>Unable to update the database.");
				}
			}
			if(isset($relcats))
			{
	    		while(list($null, $refcat) = each($_POST["relcats"])) {
	    			$cat_query = "INSERT INTO ".$tableprefix."_related_categories (srccat, destcat) VALUES ($catnr,$refcat)";
	    		   	if(!faqe_db_query($cat_query, $db))
					    die("<tr class=\"errorrow\"><td>Unable to update the database.");
	    			$cat_query = "INSERT INTO ".$tableprefix."_related_categories (destcat, srccat) VALUES ($catnr,$refcat)";
	    		   	if(!faqe_db_query($cat_query, $db))
					    die("<tr class=\"errorrow\"><td>Unable to update the database.");
	    		}
			}
			if(isset($cats))
			{
	    		while(list($null, $cat) = each($_POST["cats"])) {
	    			list($refcatnr,$destlang)=explode("|",$cat);
	    			$cat_query = "INSERT INTO ".$tableprefix."_category_ref (srccatnr, destcatnr, language) VALUES ($catnr,$refcatnr,'$destlang')";
	    		   	if(!faqe_db_query($cat_query, $db))
					    die("<tr class=\"errorrow\"><td>Unable to update the database.");
					$cat_query = "delete from ".$tableprefix."_category_ref where srccatnr=$refcatnr and language='$srclang'";
	    		   	if(!faqe_db_query($cat_query, $db))
					    die("<tr class=\"errorrow\"><td>Unable to update the database.");
	    			$cat_query = "INSERT INTO ".$tableprefix."_category_ref (srccatnr, destcatnr, language) VALUES ($refcatnr,$catnr,'$srclang')";
	    		   	if(!faqe_db_query($cat_query, $db))
					    die("<tr class=\"errorrow\"><td>Unable to update the database.");
	    		}
			}
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_catadded";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?mode=new&$langvar=$act_lang")."\">$l_newcategory</a></div>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_catlist</a></div>";
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
			$sql = "select count(faqnr) from ".$tableprefix."_data where (category=$input_catnr)";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to connect to database.");
			if ($temprow = faqe_db_fetch_array($result))
				$faqcount=$temprow["count(faqnr)"];
			else
				$faqcount=0;
			if($faq_action=="del")
			{
				$deleteSQL = "delete from ".$tableprefix."_data where (category=$input_catnr)";
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
					$moveSQL = "UPDATE ".$tableprefix."_data set category=$new_cat where (category=$input_catnr)";
					$success = faqe_db_query($moveSQL,$db);
					if (!$success)
						die("<tr class=\"errorrow\"><td>$l_cantmove.");
					$sql = "UPDATE ".$tableprefix."_category SET numfaqs = numfaqs + $faqcount WHERE (catnr = $new_cat)";
					@faqe_db_query($sql, $db);
					echo "<tr class=\"displayrow\" align=\"center\"><td>";
					echo "<i>$faqcount</i> $l_faq $l_moved<br></td></tr>";
				}
			}
		}
		$sql = "select count(faqnr) from ".$tableprefix."_data where (category=$input_catnr)";
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
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo "$l_delcat ($catname)"?></b></td></tr>
<tr><td class="inforow" align="center" colspan="2"><?php echo "$l_faqincat ($faqcount)"?></td></tr>
<form action="<?php echo $act_script_url?>" method="post"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			if(is_konqueror())
				echo "<tr><td></td></tr>";
?>
<input type="hidden" name="mode" value="delete"><input type="hidden" name="input_catnr" value="<?php echo $input_catnr?>">
<input type="hidden" name="catname" value="<?php echo $catname?>"><input type="hidden" name="oldprog" value="<?php echo $oldprog?>">
<tr><td class="inputrow"><input type="radio" name="faq_action" value="del"><?php echo "$l_delfaq"?></td></tr>
<tr><td class="inputrow"><input type="radio" name="faq_action" value="move"><?php echo "$l_movefaq"?> <?php echo $l_to?>:
<?php
	$sql1 = "select * from ".$tableprefix."_category where (catnr != $input_catnr) order by catnr";
	if(!$result1 = faqe_db_query($sql1, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database (3).");
	if (!$temprow = faqe_db_fetch_array($result1))
		echo "$l_noentries";
	else
	{
?>
<select name="new_cat">
<option value="-1">???</option>
<?php
	do {
		$catname=display_encoded($temprow["categoryname"]);
		$prognr=$temprow["programm"];
		$sql = "select * from ".$tableprefix."_programm where (prognr=$prognr) order by language, displaypos";
		if(!$result2 = faqe_db_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database (3).");
		if($temprow2 = faqe_db_fetch_array($result2))
		{
			$progname=display_encoded($temprow2["programmname"]);
			$proglang=$temprow2["language"];
		}
		else
		{
			$progname=$l_undefined;
			$proglang=$l_none;
		}
		echo "<option value=\"".$temprow["catnr"]."\">";
		echo "$catname ($progname [$proglang])";
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
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_catlist?></a></div>
<?php
		}
		else
		{
			$deleteSQL = "delete from ".$tableprefix."_related_categories where srccat=$input_catnr or destcat=$input_catnr";
			$success = faqe_db_query($deleteSQL,$db);
			if (!$success)
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			$deleteSQL = "delete from ".$tableprefix."_category where (catnr=$input_catnr)";
			$success = faqe_db_query($deleteSQL,$db);
			if (!$success)
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			$sql = "UPDATE ".$tableprefix."_programm SET numcats = numcats - 1 WHERE (prognr = $oldprog)";
			@faqe_db_query($sql, $db);
			$deleteSQL = "delete from ".$tableprefix."_category_admins where (catnr=$input_catnr)";
			$success = faqe_db_query($deleteSQL,$db);
			if (!$success)
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			$deleteSQL = "delete from ".$tableprefix."_category_ref where (srccatnr=$input_catnr) or (destcatnr=$input_catnr)";
			$success = faqe_db_query($deleteSQL,$db);
			if (!$success)
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "<i>$catname</i> $l_deleted<br>";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_catlist</a></div>";
		}
	}
	if($mode=="reindex")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_functionnotallowed</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_catlist</a></div>";
			include('./trailer.php');
			exit;
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$tableprefix."_category";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if($myrow=faqe_db_fetch_array($result))
		{
			do{
				$tempsql="select * from ".$tableprefix."_data where category=".$myrow["catnr"];
				if(!$tempresult = faqe_db_query($tempsql, $db))
				    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
				$faqcount=faqe_db_num_rows($tempresult);
				$updatesql="update ".$tableprefix."_category set numfaqs=$faqcount where catnr=".$myrow["catnr"];
				if(!$updateresult = faqe_db_query($updatesql, $db))
				    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			}while($myrow=faqe_db_fetch_array($result));
		}
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_catreindexed";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_catlist</a></div>";
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
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_catlist</a></div>";
			include('./trailer.php');
			exit;
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$tableprefix."_category where (catnr=$input_catnr)";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = faqe_db_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_editcats?> (1/2)</b></td></tr>
<form name="inputform" onsubmit="return checkform1();" method="post" action="categories.php"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<input type="hidden" name="input_catnr" value="<?php echo $myrow["catnr"]?>">
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_catname?>:</td><td><input class="faqeinput" type="text" name="category" size="40" maxlength="80" value="<?php echo display_encoded($myrow["categoryname"])?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_programm?>:</td>
<td><input type="hidden" name="oldprog" value="<?php echo $myrow["programm"]?>">
<?php
	$firstarg=true;
	if($admin_rights<3)
	{
		$sql = "select pr.* from ".$tableprefix."_programm pr, ".$tableprefix."_programm_admins pa where pr.prognr = pa.prognr and pa.usernr=$act_usernr ";
		$firstarg=false;
	}
	else
		$sql = "select pr.* from ".$tableprefix."_programm pr ";
	if(bittst($admedoptions,BIT_1))
	{
		if(isset($filterlang) && ($filterlang!="none"))
		{
			if($firstarg)
			{
				$firstarg=false;
				$sql.="where ";
			}
			else
				$sql.="and ";
			$sql.="pr.language='$filterlang' ";
		}
	}
	$sql.="order by pr.language asc ";
	if(bittst($admedoptions,BIT_2))
		$sql.=", pr.programmname asc";
	else
		$sql.=", pr.displaypos asc";
	if(!$result = faqe_db_query($sql, $db)) {
		die("<tr class=\"errorrow\"><td>Could not connect to the database (3).");
	}
	if (!$temprow = faqe_db_fetch_array($result))
	{
		echo "<a href=\"".do_url_session("program.php?mode=new&$langvar=$act_lang")."\" target=\"_blank\">$l_new</a>";
	}
	else
	{
?>
<select name="programm">
<option value="-1">???</option>
<?php
	do {
		echo "<option value=\"".$temprow["prognr"]."\"";
		if($myrow["programm"]==$temprow["prognr"])
			echo " selected";
		echo ">";
		echo display_encoded($temprow["programmname"]);
		echo " | ";
		echo stripslashes($temprow["language"]);
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
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_catlist?></a></div>
<?php
	}
	if($mode=="edit2")
	{
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
			echo "<tr class=\"displayrow\"><td align=\"center\">";
			echo "$l_functionnotallowed</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_catlist</a></div>";
			include('./trailer.php');
			exit;
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_editcats?> (2/2)</b></td></tr>
<?php
		$errors=0;
		if(!$category)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nocatname</td></tr>";
			$errors=1;
		}
		if($programm<1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noprogramm</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$progsql = "select * from ".$tableprefix."_programm where prognr=$programm";
		    if(!$progresult = faqe_db_query($progsql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if($progrow=faqe_db_fetch_array($progresult))
			{
				$progname=$progrow["programmname"];
				$proglang=$progrow["language"];
				$progid=$progrow["progid"];
			}
			else
			{
				$progname=$l_none;
				$proglang=$l_none;
			}
?>
<form method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			if(is_konqueror())
				echo "<tr><td></td></tr>";
?>
<input type="hidden" name="oldprog" value="<?php echo $oldprog?>">
<input type="hidden" name="input_catnr" value="<?php echo $input_catnr?>">
</td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_catname?>:</td>
<td><?php echo display_encoded($category)?><input type="hidden" name="category" value="<?php echo do_htmlentities(stripslashes($category))?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_programm?>:</td>
<td><?php echo display_encoded($progname)." [$proglang]"?><input type="hidden" name="programm" value="<?php echo $programm?>"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_relatedcats?>:</td>
<td>
<?php
			$catsql = "SELECT cat.* from ".$tableprefix."_category cat, ".$tableprefix."_related_categories rc where rc.srccat=$input_catnr and cat.catnr=rc.destcat order by cat.displaypos";
			if(!$catresult = faqe_db_query($catsql, $db))
			    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if ($catrow = faqe_db_fetch_array($catresult))
			{
				 do {
				    echo display_encoded($catrow["categoryname"])." (<input type=\"checkbox\" name=\"rem_relcats[]\" value=\"".$catrow["catnr"]."\"> $l_remove)<BR>";
				    $current_relcats[] = $catrow["catnr"];
				 } while($catrow = faqe_db_fetch_array($catresult));
				 echo "<br>";
			}
			else
				echo "$l_norelatedcats<br><br>";
			$catsql = "SELECT cat.* from ".$tableprefix."_category cat where cat.catnr != $input_catnr and cat.programm=$programm ";
			if(isset($current_relcats))
			{
    			while(list($null, $currCat) = each($current_relcats)) {
					$catsql .= "and cat.catnr != $currCat ";
    			}
    		}
    		$catsql .="order by cat.displaypos";
    		if(!$catresult = faqe_db_query($catsql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database.");
    		if($catrow = faqe_db_fetch_array($catresult)) {
				echo "<span class=\"inlineheading1\">$l_add:</span><br>";
				echo "<SELECT NAME=\"relcats[]\" size=\"5\" multiple>";
				do {
					echo "<OPTION VALUE=\"".$catrow["catnr"]."\" >".display_encoded($catrow["categoryname"])."</OPTION>\n";
				} while($catrow = faqe_db_fetch_array($catresult));
				echo "</select>";
			}
?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_referenced_categories?>:</td>
<td>
<?php
			$catsql = "SELECT cat.*, ref.language from ".$tableprefix."_category cat, ".$tableprefix."_category_ref ref where ref.srccatnr=$input_catnr and cat.catnr=ref.destcatnr order by cat.displaypos";
			if(!$catresult = faqe_db_query($catsql, $db))
			    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if ($catrow = faqe_db_fetch_array($catresult))
			{
				 do {
				    echo display_encoded($catrow["categoryname"])." [".$catrow["language"]."] (<input type=\"checkbox\" name=\"rem_cats[]\" value=\"".$catrow["catnr"]."\"> $l_remove)<BR>";
				    $current_cats[] = $catrow["catnr"];
				 } while($catrow = faqe_db_fetch_array($catresult));
				 echo "<br>";
			}
			else
				echo "$l_norefs<br><br>";
			$catsql = "SELECT cat.*, prog.language from ".$tableprefix."_category cat, ".$tableprefix."_programm prog where prog.progid='$progid' and prog.language!='$proglang' and cat.programm=prog.prognr ";
			if(isset($current_cats))
			{
    			while(list($null, $currCat) = each($current_cats)) {
					$catsql .= "and cat.catnr != $currCat ";
    			}
    		}
    		$catsql .="order by prog.language, prog.displaypos, cat.displaypos";
    		if(!$catresult = faqe_db_query($catsql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database.");
    		if($catrow = faqe_db_fetch_array($catresult)) {
				echo "<span class=\"inlineheading1\">$l_add:</span><br>";
				echo "<SELECT NAME=\"cats[]\" size=\"5\" multiple>";
				do {
					echo "<OPTION VALUE=\"".$catrow["catnr"]."|".$catrow["language"]."\" >".display_encoded($catrow["categoryname"])." [".$catrow["language"]."]</OPTION>\n";
				} while($catrow = faqe_db_fetch_array($catresult));
				echo "</select>";
			}
?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_admins?>:</td>
<td valign="top">
<?php
			$modsql = "SELECT u.username, u.usernr FROM ".$tableprefix."_admins u, ".$tableprefix."_category_admins f WHERE f.catnr = '$input_catnr' AND u.usernr = f.usernr order by u.username";
			if(!$modresult = faqe_db_query($modsql, $db))
			    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if ($modrow = faqe_db_fetch_array($modresult))
			{
				 do {
				    echo $modrow["username"]." (<input type=\"checkbox\" name=\"rem_mods[]\" value=\"".$modrow["usernr"]."\"> $l_remove)<BR>";
				    $current_mods[] = $modrow["usernr"];
				 } while($modrow = faqe_db_fetch_array($modresult));
				 echo "<br>";
			}
			else
				echo "$l_noadmins<br><br>";
			$modsql = "SELECT u.usernr, u.username FROM ".$tableprefix."_admins u, ".$tableprefix."_programm_admins pa WHERE (pa.prognr=$programm and u.usernr=pa.usernr or u.rights>2) ";
			if(isset($current_mods))
			{
   				while(list($null, $currMod) = each($current_mods)) {
					$modsql .= "AND u.usernr != $currMod ";
   				}
   			}
   			$modsql .= " group by u.usernr ORDER BY u.username";
   			if(!$modresult = faqe_db_query($modsql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database.");
   			if($modrow = faqe_db_fetch_array($modresult)) {
				echo"<b>$l_add:</b><br>";
				echo "<SELECT NAME=\"mods[]\" size=\"5\" multiple>";
				do {
					echo "<OPTION VALUE=\"".$modrow["usernr"]."\" >".$modrow["username"]."</OPTION>\n";
				} while($modrow = faqe_db_fetch_array($modresult));
				echo "</select>";
			}
?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="update">
<input class="faqebutton" type="submit" value="<?php echo $l_update?>"></td></tr>
</form>
</table></tr></td></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_catlist?></a></div>
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
		$modsql="select * from ".$tableprefix."_category_admins where ((catnr=$input_catnr) and (usernr=$act_usernr))";
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
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_catlist</a></div>";
			include('./trailer.php');
			exit;
		}
		$errors=0;
		if(!$category)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nocatname</td></tr>";
			$errors=1;
		}
		if($programm<1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noprogramm</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$sql = "UPDATE ".$tableprefix."_category SET categoryname='$category', programm='$programm' ";
			$sql .=" WHERE (catnr = $input_catnr)";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to update the database.");
			if($programm != $oldprog)
			{
				$sql = "UPDATE ".$tableprefix."_programm SET numcats = numcats + 1 WHERE (prognr = $programm)";
				@faqe_db_query($sql, $db);
				$sql = "UPDATE ".$tableprefix."_programm SET numcats = numcats - 1 WHERE (prognr = $oldprog)";
				@faqe_db_query($sql, $db);
			}
			if(isset($mods))
			{
	    		while(list($null, $mod) = each($_POST["mods"])) {
					$mod_query = "INSERT INTO ".$tableprefix."_category_admins (catnr, usernr) VALUES ('$input_catnr', '$mod')";
	    		   	if(!faqe_db_query($mod_query, $db))
					    die("<tr class=\"errorrow\"><td>Unable to update the database.");
				}
			}
			if(isset($rem_mods))
			{
				while(list($null, $mod) = each($_POST["rem_mods"]))
				{
					$rem_query = "DELETE FROM ".$tableprefix."_category_admins WHERE catnr = '$input_catnr' AND usernr = '$mod'";
	       			if(!faqe_db_query($rem_query,$db))
					    die("<tr class=\"errorrow\"><td>Unable to update the database.");
				}
			}
			if(isset($rem_relcats))
			{
				while(list($null, $cat) = each($_POST["rem_relcats"]))
				{
					$rem_query = "DELETE FROM ".$tableprefix."_related_categories WHERE srccat = '$input_catnr' AND destcat = '$cat'";
	       			if(!faqe_db_query($rem_query,$db))
					    die("<tr class=\"errorrow\"><td>Unable to update the database.");
					$rem_query = "DELETE FROM ".$tableprefix."_related_categories WHERE destcat = '$input_catnr' AND srccat = '$cat'";
	       			if(!faqe_db_query($rem_query,$db))
					    die("<tr class=\"errorrow\"><td>Unable to update the database.");
				}
			}
			if(isset($relcats))
			{
	    		while(list($null, $refcat) = each($_POST["relcats"])) {
	    			$cat_query = "INSERT INTO ".$tableprefix."_related_categories (srccat, destcat) VALUES ($input_catnr,$refcat)";
	    		   	if(!faqe_db_query($cat_query, $db))
					    die("<tr class=\"errorrow\"><td>Unable to update the database.");
	    			$cat_query = "INSERT INTO ".$tableprefix."_related_categories (destcat, srccat) VALUES ($input_catnr,$refcat)";
	    		   	if(!faqe_db_query($cat_query, $db))
					    die("<tr class=\"errorrow\"><td>Unable to update the database.");
	    		}
			}
			if(isset($rem_cats))
			{
				while(list($null, $cat) = each($_POST["rem_cats"]))
				{
					$rem_query = "DELETE FROM ".$tableprefix."_category_ref WHERE srccatnr = '$input_catnr' AND destcatnr = '$cat'";
	       			if(!faqe_db_query($rem_query,$db))
					    die("<tr class=\"errorrow\"><td>Unable to update the database.");
				}
			}
			if(isset($cats))
			{
	    		while(list($null, $cat) = each($_POST["cats"])) {
	    			list($refcatnr,$destlang)=explode("|",$cat);
	    			$cat_query = "delete from ".$tableprefix."_category_ref where srccatnr=$input_catnr and language='$destlang'";
	    		   	if(!faqe_db_query($cat_query, $db))
					    die("<tr class=\"errorrow\"><td>Unable to update the database.");
	    			$cat_query = "INSERT INTO ".$tableprefix."_category_ref (srccatnr, destcatnr, language) VALUES ($input_catnr,$refcatnr,'$destlang')";
	    		   	if(!faqe_db_query($cat_query, $db))
					    die("<tr class=\"errorrow\"><td>Unable to update the database.");
	    		}
			}
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_catupdated";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_catlist</a></div>";
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
<a href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_newcategory?></a>
</table></td></tr></table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	}
if(!isset($sorting))
	$sorting=0;
// Display list of actual categories
$sql = "select cat.* from ".$tableprefix."_category cat, ".$tableprefix."_programm prog where cat.programm=prog.prognr ";
if(isset($filterlang) && ($filterlang!="none"))
	$sql.="and prog.language='$filterlang' ";
if(isset($filterprog) && ($filterprog >=0))
	$sql.= " and prog.prognr=$filterprog ";
switch($sorting)
{
	case 12:
		$sql.="order by catnr desc";
		break;
	case 21:
		$sql.="order by categoryname asc";
		break;
	case 22:
		$sql.="order by categoryname desc";
		break;
	case 31:
		$sql.="order by programm asc";
		break;
	case 32:
		$sql.="order by programm desc";
		break;
	case 41:
		$sql.="order by numfaqs asc";
		break;
	case 42:
		$sql.="order by numfaqs desc";
		break;
	case 99:
		$sql.="order by displaypos asc";
		break;
	default:
		$sql.="order by catnr asc";
}
if(!$result = faqe_db_query($sql, $db)) {
    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
}
if (!$myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
	$baseurl="$act_script_url?$langvar=$act_lang";
	if(isset($filterprog))
		$baseurl.="&filterprog=$filterprog";
	if(isset($filterlang))
		$baseurl.="&filterlang=$filterlang";
	if($admstorefaqfilters==1)
		$baseurl.="&storefaqfilter=1";
	$maxsortcol=4;
	echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\" width=\"10%\">";
	$sorturl=getSortURL($sorting, 1, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>#</b><a>";
	echo getSortMarker($sorting, 1, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\" width=\"30%\">";
	$sorturl=getSortURL($sorting, 2, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_catname</b></a>";
	echo getSortMarker($sorting, 2, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\" width=\"30%\">";
	$sorturl=getSortURL($sorting, 3, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_progname</b></a>";
	echo getSortMarker($sorting, 3, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\" width=\"10%\"><b>$l_language</b></td>";
	echo "<td align=\"center\" width=\"15%\">";
	$sorturl=getSortURL($sorting, 4, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_faq</b></a>";
	echo getSortMarker($sorting, 4, $maxsortcol);
	echo "</td>";
	echo "<td class=\"rowheadings\">";
	if(isset($filterprog) && ($filterprog>=0))
	{
		if($sorting!=99)
		{
			$sorturl="&sorting=99";
			echo "<a class=\"rowheadings\" href=\"".do_url_session($baseurl.$sorturl)."\"><b>";
			echo "$l_sortbydisplaypos</b></a>";
		}
		else
			echo "<span class=\"activesorting\">$l_sortedbydisplaypos</span>";
	}
	else
		echo "&nbsp;";
	echo "</td></tr>";
	do {
		$act_id=$myrow["catnr"];
		$act_prog=$myrow["programm"];
		echo "<tr class=\"displayrow\">";
		echo "<td align=\"right\">".$myrow["catnr"]."</td>";
		echo "<td>".display_encoded($myrow["categoryname"])."</td>";
		$tempsql = "select * from ".$tableprefix."_programm where (prognr=".$myrow["programm"].")";
		if(!$tempresult = faqe_db_query($tempsql, $db)) {
		    die("Could not connect to the database.");
		}
		if (!$temprow = faqe_db_fetch_array($tempresult))
		{
			$progame=$l_undefined;
			$proglang=$l_none;
		}
		else
		{
			$progname=display_encoded($temprow["programmname"]);
			$proglang=$temprow["language"];
		}
		echo "<td>$progname</td>";
		echo "<td align=\"center\">$proglang</td>";
		echo "<td align=\"right\">".$myrow["numfaqs"]."</td>";
		echo "<td>";
		$modsql="select * from ".$tableprefix."_category_admins where catnr=$act_id and usernr=$act_usernr";
		if(!$modresult = faqe_db_query($modsql, $db)) {
		    die("Could not connect to the database.");
		}
		if($modrow=faqe_db_fetch_array($modresult))
			$ismod=1;
		else
			$ismod=0;
		if(($admin_rights>2) || ($ismod==1))
		{
			echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=delete&input_catnr=$act_id&$langvar=$act_lang&catname=".urlencode($myrow["categoryname"])."&oldprog=$act_prog")."\">";
			echo "<img src=\"gfx/delete.gif\" border=\"0\" alt=\"$l_delete\" title=\"$l_delete\"></a>";
			echo "&nbsp; ";
			echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=edit&input_catnr=$act_id&$langvar=$act_lang&oldprog=$act_prog")."\">";
			echo "<img src=\"gfx/edit.gif\" border=\"0\" alt=\"$l_edit\" title=\"$l_edit\"></a>&nbsp; ";
			echo "<a class=\"listlink2\" href=\"".do_url_session("reorder_faq.php?input_catnr=$act_id&$langvar=$act_lang")."\">";
			echo "<img src=\"gfx/reorder.gif\" border=\"0\" alt=\"$l_reorder_faq\" title=\"$l_reorder_faq\"></a>&nbsp; ";
		}
		echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=display&input_catnr=$act_id&$langvar=$act_lang&catname=".urlencode($myrow["categoryname"])."&oldprog=$act_prog")."\">";
		echo "<img src=\"gfx/view.gif\" border=\"0\" title=\"$l_display\" alt=\$l_display\"></a>";
		echo "</td></tr>";
	} while($myrow = faqe_db_fetch_array($result));
	if($admin_rights>2)
	{
		echo "<tr class=\"actionrow\"><td colspan=\"6\" align=\"center\">";
		echo "<a href=\"".do_url_session("$act_script_url?mode=reindex&$langvar=$act_lang")."\">";
		echo "$l_reindex_cat</a></td></tr>";
	}
	echo "</table></tr></td></table>";
}
if($admin_rights > 1)
{
	include('./includes/prog_filterbox.inc');
	include('./includes/language_filterbox.inc');
?>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_newcategory?></a></div>
<?php
}
}
include('./trailer.php');
?>