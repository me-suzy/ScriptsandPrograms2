<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
	if($admin_rights < 1)
	{
		echo "<tr class=\"errorrow\"><td align=\"center\">";
		die("$l_functionnotallowed");
	}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	$sql = "select * from ".$tableprefix."_kb_articles where (articlenr=$input_articlenr)";
	if(!$result = faqe_db_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	if (!$myrow = faqe_db_fetch_array($result))
		die("<tr class=\"errorrow\"><td>no such entry");
?>
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_editkbarticle?></b></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_heading?>:</td>
<td><?php echo undo_html_ampersand(stripslashes($myrow["heading"]))?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_programm?>:</td>
<td>
<?php
	$sql = "select * from ".$tableprefix."_programm where (prognr=".$myrow["programm"].")";
	if(!$result = faqe_db_query($sql, $db)) {
		die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database (3).");
	}
	if ($temprow = faqe_db_fetch_array($result))
	{
		$progname=display_encoded($temprow["programmname"]);
		$proglang=$temprow["language"];
	}
	else
	{
		$progname=$l_undefined;
		$proglang=$l_none;
	}
	echo "$progname [$proglang]</td></tr>";
	if($myrow["category"]>0)
	{
		echo "<tr class=\"displayrow\"><td align=\"right\">";
		echo "$l_category:</td><td>";
		$tempsql="select * from ".$tableprefix."_kb_cat where catnr=".$myrow["category"];
		if(!$tempresult = faqe_db_query($tempsql, $db))
			die("<tr bgcolor=\"#cccccc\"><td align=\"center\">Could not connect to the database (4).");
		if($temprow=faqe_db_fetch_array($tempresult))
			echo display_encoded($temprow["catname"]);
		else
			echo $l_none;
		echo "</td></tr>";
	}
	if($myrow["subcategory"]>0)
	{
		echo "<tr class=\"displayrow\"><td align=\"right\">";
		echo "$l_subcategory:</td><td>";
		$tempsql="select * from ".$tableprefix."_kb_subcat where catnr=".$myrow["subcategory"];
		if(!$tempresult = faqe_db_query($tempsql, $db))
			die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database (4).");
		if($temprow=faqe_db_fetch_array($tempresult))
			echo display_encoded($temprow["catname"]);
		else
			echo $l_none;
		echo "</td></tr>";
	}
	$sql = "select kw.* from ".$tableprefix."_keywords kw, ".$tableprefix."_kb_keywords ak where ak.articlenr=$input_articlenr and kw.keywordnr=ak.keywordnr";
	if(!$result = faqe_db_query($sql, $db)) {
		die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database (4).");
	}
	$displaykeywords="&nbsp;";
	if($temprow=faqe_db_fetch_array($result))
	{
		$displaykeywords="<ul>";
		do{
			$displaykeywords.="<li>".$temprow["keyword"];
		}while($temprow=faqe_db_fetch_array($result));
		$displaykeywords.="</ul>";
	}
	$articletext=stripslashes($myrow["article"]);
	$articletext = undo_htmlspecialchars($articletext);
	$articletext = str_replace("{lang}","$langvar=$act_lang",$articletext);
	$articletext = str_replace("{url_faqengine}",$url_faqengine,$articletext);
	$articletext = str_replace("{bbc_code}",$l_bbccode,$articletext);
	$articletext = str_replace("{bbc_quote}",$l_bbcquote,$articletext);
	$affectedos=$l_undefined;
	$sql = "select os.* from ".$tableprefix."_os os, ".$tableprefix."_kb_os kos where kos.articlenr=$input_articlenr and os.osnr=kos.osnr";
	if(!$result = faqe_db_query($sql, $db)) {
		die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database (4).");
	}
	if($temprow=faqe_db_fetch_array($result))
	{
		$affectedos="";
		do{
			$affectedos.=display_encoded($temprow["osname"])."<br>";
		}while($temprow=faqe_db_fetch_array($result));
	}
	$affectedversions=$l_undefined;
	$sql = "select version.* from ".$tableprefix."_programm_version version, ".$tableprefix."_kb_prog_version kbpv where kbpv.articlenr=$input_articlenr and version.entrynr=kbpv.progversion";
	if(!$result = faqe_db_query($sql, $db)) {
		die("<tr class=\"errorrow\"><td>Could not connect to database (4).");
	}
	if($temprow=faqe_db_fetch_array($result))
	{
		$affectedversions="";
		do{
			$affectedversions.=stripslashes($temprow["version"])."<br>";
		}while($temprow=faqe_db_fetch_array($result));
	}
?>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_keywords?>:</td>
<td><?php echo $displaykeywords?></td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_articletext?>:</td>
<td><?php echo $articletext?></td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_affectedos?>:</td>
<td><?php echo $affectedos?></td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_affected_versions?>:</td>
<td><?php echo $affectedversions?></td></tr>
<tr class="displayrow"><td align="center" colspan="2"><?php echo $l_lastedited?> <i><?php echo $myrow["editor"]?></i>, <i><?php echo $myrow["lastedited"]?></i></td></tr>
</table></tr></td></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_articlelist</a></div>";
?>