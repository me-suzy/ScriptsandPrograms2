<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
if(!$prog && ($show_proglist==0))
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	echo "$l_disallowed</td></tr>";
	echo "</table></div>";
	include_once("./includes/bottom.inc");
	exit;
}
if(!isset($onlynewfaq))
	$onlynewfaq=1;
?>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<tr BGCOLOR="<?php echo $subheadingbgcolor?>" ALIGN="CENTER">
<TD class="subheading" ALIGN="CENTER" VALIGN="MIDDLE">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $SubheadingFontColor?>; font-weight: bold">
<?php
if($numlatest>0)
	printf($l_latest_FAQ,$numlatest);
else
	echo $l_latest_FAQ2;
?>
</span></td></tr>
<?php
if($prog)
{
	$sql = "select * from ".$tableprefix."_programm where progid='$prog' and language='$act_lang'";
	if(!$result = faqe_db_query($sql, $db))
		die("Could not connect to the database.".faqe_db_error());
	if($myrow=faqe_db_fetch_array($result))
	{
		echo "<TR BGCOLOR=\"$subheadingbgcolor\" ALIGN=\"center\">";
		echo "<TD class=\"subheading\" ALIGN=\"CENTER\" VALIGN=\"MIDDLE\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize2; color: $SubheadingFontColor; font-weight: bold\">";
		echo "$l_program: ".display_encoded($myrow["programmname"]);
		echo "</span></td></tr>";
	}
}
?>
</table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="<?php echo $tablepadding?>" CELLSPACING="<?php echo $tablespacing?>" WIDTH="100%">
<?php
$sql = "select dat.*, prog.programmname, prog.progid, cat.categoryname from ".$tableprefix."_data dat, ".$tableprefix."_category cat, ".$tableprefix."_programm prog where prog.language='$act_lang' and cat.programm=prog.prognr and dat.category=cat.catnr";
if($prog)
	$sql.= " and prog.progid='$prog'";
if($numlatest>0)
{
	$sql.= " order by dat.editdate desc";
	$sql.= " limit $numlatest";
}
else
{
	$sql.= " and dat.editdate >= DATE_SUB('$actdate', INTERVAL $onlynewfaq DAY)";
	$sql.= " order by dat.editdate desc";
}
if(!$result = faqe_db_query($sql, $db))
	die("Could not connect to the database.".faqe_db_error());
if(!$myrow=faqe_db_fetch_array($result))
{
	echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
	echo "<td class=\"listrow\" align=\"center\" colspan=\"2\">";
	echo "<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
	echo $l_nonewentries;
	echo "</span></td></tr>";
}
else
{
	do{
		echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
		if($altlinkmethod==0)
		{
			if($navframe==1)
				$catlink=$url_faqengine."/faqframe.php";
			else
				$catlink=$act_script_url;
			$catlink.="?$langvar=$act_lang&list=category&onlynewfaq=0&catnr=".$myrow["category"]."&prog=".$myrow["progid"];
			if(isset($layout))
				$catlink.="&layout=$layout";
			if(isset($limitprog))
				$catlink.="&limitprog=$limitprog";
		}
		else
		{
			$catlink=$url_faqengine."/listcat.php?id=".$myrow["category"]."|$act_lang|$layout";
		}
		echo "<td align=\"left\" class=\"listrow\" bgcolor=\"$group_bgcolor\" width=\"30%\"";
		if($hovercells==1)
		{
			echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$group_bgcolor'\"";
			if($navframe==0)
				echo " onclick=\"window.location.href='$catlink'\"";
		}
		echo ">";
		echo "<a class=\"listlink\" href=\"$catlink\" title=\"$l_display_cat\"";
		if($navframe==1)
			echo " target=\"_parent\"";
		echo ">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $GroupFontColor;\">";
		if($prog)
			$displaycat="";
		else
			$displaycat=display_encoded($myrow["programmname"]).":";
		$displaycat.=display_encoded($myrow["categoryname"]);
		echo $displaycat;
		echo "</span></a>";
		echo "</td>";
		if($altlinkmethod==0)
		{
			if($navframe==1)
				$faqlink=$url_faqengine."/faqframe.php";
			else
				$faqlink=$act_script_url;
			if(($myrow["linkedfaq"]==0) || ($lhide==1))
				$faqlink.="?$langvar=$act_lang&display=faq&onlynewfaq=0&catnr=".$myrow["category"]."&faqnr=".$myrow["faqnr"]."&prog=".$myrow["progid"];
			else if($lhide==0)
			{
				$tmpsql="select dat.*, prog.progid from ".$tableprefix."_data dat, ".$tableprefix."_category cat, ".$tableprefix."_programm prog where dat.faqnr=".$myrow["linkedfaq"]." and cat.catnr=dat.category and prog.prognr=cat.programm";
				if(!$tmpresult = faqe_db_query($tmpsql, $db))
					die("Could not connect to the database.");
				if($tmprow=faqe_db_fetch_array($tmpresult))
					$faqlink.="?$langvar=$act_lang&display=faq&onlynewfaq=0&catnr=".$tmprow["category"]."&faqnr=".$tmprow["faqnr"]."&prog=".$tmprow["progid"];
				else
					die("Could not connect to the database.");
			}
			if(isset($limitprog))
				$faqlink.="&limitprog=$limitprog";
			if(isset($layout))
				$faqlink.="&layout=$layout";
		}
		else
		{
			$faqlink=$url_faqengine."/dispfaq.php";
			if($myrow["linkedfaq"]==0)
				$faqlink.="?id=".$myrow["faqnr"]."|$act_lang|$layout";
			else
			{
				$tmpsql="select dat.*, prog.progid from ".$tableprefix."_data dat, ".$tableprefix."_category cat, ".$tableprefix."_programm prog where dat.faqnr=".$myrow["linkedfaq"]." and cat.catnr=dat.category and prog.prognr=cat.programm";
				if(!$tmpresult = faqe_db_query($tmpsql, $db))
					die("Could not connect to the database.");
				if($tmprow=faqe_db_fetch_array($tmpresult))
					$faqlink.="?id=".$tmprow["faqnr"]."|$act_lang|$layout";
				else
					die("Could not connect to the database.");
			}
		}
		echo "<td align=\"left\" class=\"listrow\" bgcolor=\"$row_bgcolor\" width=\"70%\"";
		if($hovercells==1)
		{
			echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$row_bgcolor'\"";
			if($navframe==0)
				echo " onclick=\"window.location.href='$faqlink'\"";
		}
		echo ">";
		echo "<a class=\"listlink\" href=\"$faqlink\" title=\"$l_display_faq\"";
		if($navframe==1)
			echo " target=\"_parent\"";
		echo ">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo undo_html_ampersand(stripslashes($myrow["heading"]));
		echo "</span></a></td>";
		echo "</tr>";
	}while($myrow=faqe_db_fetch_array($result));
}
if($altlinkmethod==0)
{
	$actionlink="$act_script_url?$langvar=$act_lang";
	if($prog)
	{
		$actionlink.="&list=all&prog=$prog";
		if($numlatest>0)
			$actionlink.="&onlynewfaq=0";
	}
	else
	{
		$actionlink.="&list=progs";
		if($numlatest>0)
			$actionlink.="&onlynewfaq=0";
	}
	if($navframe==1)
	{
		$actionlink.="&navframe=1";
		if(isset($limitprog))
			$actionlink.="&limitprog=$limitprog";
	}
	if(isset($layout))
		$actionlink.="&layout=$layout";
}
else
{
	if($prog)
	{
		$actionlink="listallfaq.php?id=$prog|$act_lang|$layout";
	}
	else
	{
		$actionlink="listprogs.php?id=$act_lang|$layout";
	}
}
if($prog)
	$actiontxt=$l_listall;
else
	$actiontxt=$l_listprogs;
echo "</table></td></tr>";
echo "<tr><TD BGCOLOR=\"$table_bgcolor\">";
echo "<TABLE BORDER=\"0\" CELLPADDING=\"1\" CELLSPACING=\"1\" WIDTH=\"100%\">";
echo "<tr class=\"actionline\" bgcolor=\"$actionbgcolor\">";
echo "<td class=\"actionline\" colspan=\"2\" align=\"center\">";
echo "<span style=\"font-face: $FontFace; font-size: $actionlinefontsize; color: $FontColor;\">";
echo "<a class=\"actionline\" href=\"$actionlink\">";
echo $actiontxt;
echo "</a></span></td></tr>";
echo "</table></td></tr></table>";
?>