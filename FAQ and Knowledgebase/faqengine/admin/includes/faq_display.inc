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
	$sql = "select * from ".$tableprefix."_data where (faqnr=$input_faqnr)";
	if(!$result = faqe_db_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	if (!$myrow = faqe_db_fetch_array($result))
		die("<tr class=\"errorrow\"><td>no such entry");
?>
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_displayfaq?></b></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_heading?>:</td>
<td><?php echo undo_html_ampersand(stripslashes($myrow["heading"]))?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_category?>:</td>
<td>
<?php
	$sql = "select * from ".$tableprefix."_category where (catnr=".$myrow["category"].")";
	if(!$result = faqe_db_query($sql, $db)) {
		die("<tr class=\"errorrow\"><td>Could not connect to database (3).");
	}
	if ($temprow = faqe_db_fetch_array($result))
	{
		$catname=display_encoded($temprow["categoryname"]);
		$prognr=$temprow["programm"];
	}
	else
	{
		$catname=$l_undefined;
		$prognr=0;
	}
	$sql = "select * from ".$tableprefix."_programm where (prognr=$prognr)";
	if(!$result = faqe_db_query($sql, $db)) {
		die("<tr class=\"errorrow\"><td>Could not connect to database (3).");
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
	echo "$catname ($progname [$proglang])";
	$questiontext = stripslashes($myrow["questiontext"]);
	$questiontext = undo_htmlspecialchars($questiontext);
	$questiontext = str_replace("{lang}","$langvar=$act_lang",$questiontext);
	$questiontext = str_replace("{url_faqengine}",$url_faqengine,$questiontext);
	$questiontext = str_replace("{onlynewfaq}",0,$questiontext);
	$questiontext = str_replace("{bbc_code}",$l_bbccode,$questiontext);
	$questiontext = str_replace("{bbc_quote}",$l_bbcquote,$questiontext);
	$answertext = stripslashes($myrow["answertext"]);
	$answertext = undo_htmlspecialchars($answertext);
	$answertext = str_replace("{lang}","$langvar=$act_lang",$answertext);
	$answertext = str_replace("{url_faqengine}",$url_faqengine,$answertext);
	$answertext = str_replace("{onlynewfaq}",0,$answertext);
	$answertext = str_replace("{bbc_code}",$l_bbccode,$answertext);
	$answertext = str_replace("{bbc_quote}",$l_bbcquote,$answertext);
	$sql = "select kw.* from ".$tableprefix."_keywords kw, ".$tableprefix."_faq_keywords fk where fk.faqnr=$input_faqnr and kw.keywordnr=fk.keywordnr";
	if(!$result = faqe_db_query($sql, $db)) {
		die("<tr class=\"errorrow\"><td>Could not connect to database (4).");
	}
	$displaykeywords="&nbsp;";
	if($temprow=faqe_db_fetch_array($result))
	{
		$displaykeywords="<ul class=\"kwlist\">";
		do{
			$displaykeywords.="<li>".$temprow["keyword"];
		}while($temprow=faqe_db_fetch_array($result));
		$displaykeywords.="</ul>";
	}
	$sql = "select version.* from ".$tableprefix."_programm_version version, ".$tableprefix."_faq_prog_version fpv where fpv.faqnr=$input_faqnr and version.entrynr=fpv.progversion";
	if(!$result = faqe_db_query($sql, $db)) {
		die("<tr class=\"errorrow\"><td>Could not connect to database (4).");
	}
	$displayversions="&nbsp;";
	if($temprow=faqe_db_fetch_array($result))
	{
		$displayversions="<ul class=\"versionlist\">";
		do{
			$displayversions.="<li>".stripslashes($temprow["version"]);
		}while($temprow=faqe_db_fetch_array($result));
		$displayversions.="</ul>";
	}
	$sql = "select os.* from ".$tableprefix."_os os, ".$tableprefix."_faq_os fos where fos.faqnr=$input_faqnr and os.osnr=fos.osnr";
	if(!$result = faqe_db_query($sql, $db)) {
		die("<tr class=\"errorrow\"><td>Could not connect to database (4).");
	}
	$displayos="&nbsp;";
	if($temprow=faqe_db_fetch_array($result))
	{
		$displayos="<ul class=\"oslist\">";
		do{
			$displayos.="<li>".stripslashes($temprow["osname"]);
		}while($temprow=faqe_db_fetch_array($result));
		$displayos.="</ul>";
	}
?>
</td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_question?>:</td>
<td><?php echo $questiontext?></td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_answer?>:</td>
<td><?php echo $answertext?></td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_keywords?>:</td>
<td><?php echo $displaykeywords?></td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_relatedfaq?>:</td>
<td>
<?php
	$sql = "select dat.* from ".$tableprefix."_related_faq rf, ".$tableprefix."_data dat where rf.srcfaq=$input_faqnr and dat.faqnr=rf.destfaq";
	if(!$result = faqe_db_query($sql, $db)) {
		die("<tr class=\"errorrow\"><td>Could not connect to the database (3).");
	}
	if (!$temprow = faqe_db_fetch_array($result))
	{
		echo $l_none;
	}
	else
	{
		do{
			echo undo_html_ampersand(stripslashes($temprow["heading"]))."<br>";
		}while($temprow=faqe_db_fetch_array($result));
	}
?>
</td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_referenced_faq?>:</td>
<td>
<?php
	$sql = "select ref.language, dat.* from ".$tableprefix."_faq_ref ref, ".$tableprefix."_data dat where (ref.srcfaqnr=$input_faqnr) and (dat.faqnr=ref.destfaqnr)";
	if(!$result = faqe_db_query($sql, $db)) {
		die("<tr class=\"errorrow\"><td>Could not connect to the database (3).");
	}
	if (!$temprow = faqe_db_fetch_array($result))
	{
		echo $l_none;
	}
	else
	{
		do{
			echo undo_html_ampersand(stripslashes($temprow["heading"]))." (".$temprow["language"].")<br>";
		}while($temprow=faqe_db_fetch_array($result));
	}
?>
</td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_affected_versions?>:</td>
<td><?php echo $displayversions?></td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_affected_os?>:</td>
<td><?php echo $displayos?></td></tr>
<tr class="displayrow"><td align="center" colspan="2"><?php echo $l_lastedited?> <i><?php echo $myrow["editor"]?></i>, <i><?php echo $myrow["editdate"]?></i></td></tr>
</table></tr></td></table>
<?php
	echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_faqlist</a></div>";
?>