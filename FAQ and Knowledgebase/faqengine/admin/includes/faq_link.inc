<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
	if($admin_rights < 2)
	{
		echo "<tr class=\"errorrow\"><td align=\"center\">";
		die("$l_functionnotallowed");
	}
	$sql="select * from ".$tableprefix."_data where faqnr=$input_faqnr";
	if(!$result = faqe_db_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database (1).");
	if($myrow=faqe_db_fetch_array($result))
	{
		if($myrow["subcategory"]!=0)
		{
			$origcat=0;
			$origsubcat=$myrow["subcategory"];
		}
		else
		{
			$origsubcat=0;
			$origcat=$myrow["category"];
		}
	}
	else
	{
		$origcat=0;
		$origsubcat=0;
	}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_mkfaqlink?></b></td></tr>
<tr><td class="inforow" align="center" colspan="2"><b><?php echo $l_origfaq.": #".$input_faqnr?></b></td></tr>
<form onSubmit="return checkform2();" name="inputform" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if(is_konqueror())
		echo "<tr><td></td></tr>";
?>
<tr class="inputrow"><td align="right"><?php echo $l_category?>:</td>
<td>
<?php
	if($admin_rights<3)
		$sql1 = "select cat.* from ".$tableprefix."_programm prog, ".$tableprefix."_category cat, ".$tableprefix."_category_admins ca where cat.catnr = ca.catnr and ca.usernr=$act_usernr and cat.catnr!=$origcat and cat.programm=prog.prognr ";
	else
		$sql1 = "select cat.* from ".$tableprefix."_programm prog, ".$tableprefix."_category cat where cat.catnr!=$origcat and cat.programm=prog.prognr ";
	if(bittst($admedoptions,BIT_1))
	{
		if(isset($filterprog) && ($filterprog>=0))
			$sql1.="and prog.prognr=$filterprog ";
		else if(isset($filterlang) && ($filterlang!="none"))
			$sql1.="and prog.language='$filterlang' ";
	}
	$sql1.="group by cat.catnr order by prog.language asc";
	if(bittst($admedoptions,BIT_2))
		$sql1.=", prog.programmname asc, cat.categoryname asc";
	else
		$sql1.=", prog.displaypos asc, cat.displaypos asc";
	if(!$result1 = faqe_db_query($sql1, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database (3).");
	if (!$temprow = faqe_db_fetch_array($result1))
	{
		echo "<a href=\"".do_url_session("categories.php?mode=new?$langvar=$act_lang")."\" target=\"_blank\">$l_new</a>";
	}
	else
	{
?>
<select name="category">
<option value="-1">???</option>
<?php
	do {
		$catname=display_encoded($temprow["categoryname"]);
		$prognr=$temprow["programm"];
		$sql = "select * from ".$tableprefix."_programm where (prognr=$prognr)";
		if(!$result2 = faqe_db_query($sql, $db)) {
			die("Could not connect to the database (3).");
		}
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
<tr class="inputrow"><td align="right"><?php echo $l_or?> <?php echo $l_subcategory?>:</td><td>
<?php
	if($admin_rights<3)
		$sql = "select cat.categoryname as catname, subcat.catnr as subcatnr, subcat.categoryname as subcatname, prog.programmname, prog.language, prog.prognr from ".$tableprefix."_category cat, ".$tableprefix."_category_admins ca, ".$tableprefix."_subcategory subcat, ".$tableprefix."_programm prog  where cat.catnr = ca.catnr and ca.usernr=$act_usernr and subcat.category=cat.catnr and prog.prognr=cat.programm and subcat.catnr!=$origsubcat ";
	else
		$sql = "select cat.categoryname as catname, subcat.catnr as subcatnr, subcat.categoryname as subcatname, prog.programmname, prog.language, prog.prognr from ".$tableprefix."_category cat, ".$tableprefix."_subcategory subcat, ".$tableprefix."_programm prog  where subcat.category=cat.catnr and prog.prognr=cat.programm and subcat.catnr!=$origsubcat ";
	if(bittst($admedoptions,BIT_1))
	{
		if(isset($filterprog) && ($filterprog>=0))
			$sql.="and prog.prognr=$filterprog ";
		else if(isset($filterlang) && ($filterlang!="none"))
			$sql.="and prog.language='$filterlang' ";
	}
	$sql.="group by subcat.catnr order by prog.language asc";
	if(bittst($admedoptions,BIT_2))
		$sql.=", prog.programmname asc, cat.categoryname asc, subcat.categoryname asc";
	else
		$sql.=", prog.displaypos asc, cat.displaypos asc, subcat.displaypos asc";
	if(!$result = faqe_db_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database (3).");
	if (!$temprow = faqe_db_fetch_array($result))
	{
		echo "<a href=\"".do_url_session("subcategories.php?mode=new&$langvar=$act_lang")."\" target=\"_blank\">$l_new</a>";
	}
	else
	{
?>
<select name="subcategory">
<option value="0"><?php echo $l_none?></option>
<?php
		do {
			echo "<option value=\"".$temprow["subcatnr"]."\">";
			$progname=display_encoded($temprow["programmname"]);
			$proglang=$temprow["language"];
			$catname=display_encoded($temprow["catname"]);
			$subcatname=display_encoded($temprow["subcatname"]);
			echo "$subcatname ($catname : $progname [$proglang])";
			echo "</option>";
		} while($temprow = faqe_db_fetch_array($result));
		echo "</select>";
	}
	echo "</td></tr>";
?>
<tr class="actionrow"><td align="center" colspan="2">
<input type="hidden" name="mode" value="addlink">
<input type="hidden" name="linkedfaq" value="<?php echo $input_faqnr?>">
<input class="faqebutton" type="submit" value="<?php echo $l_add?>"></td></tr>
</form>
</table></td></tr></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_faqlist</a></div>";
?>