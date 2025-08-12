<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
function get_faqsashtml($prognr, $progname, $proglang, $l_fm)
{
	global $tableprefix, $db;
	global $l_nosuchprog, $faqeversion;
	global $langvar, $url_faqengine;
	global $copyright_url, $copyright_note;
	global $faqe_fullurl, $faqeversion;
	
	include_once("./includes/get_layout.inc");

	$msg=get_mailstyles();
	$msg.="<table width=\"$TableWidth\" border=\"0\" CELLPADDING=\"1\" CELLSPACING=\"0\" ALIGN=\"CENTER\">";
	$msg.="<tr><TD BGCOLOR=\"$table_bgcolor\">";
	$msg.="<TABLE BORDER=\"0\" CELLPADDING=\"1\" CELLSPACING=\"1\" WIDTH=\"100%\">";
	$msg.="<TR BGCOLOR=\"$heading_bgcolor\" ALIGN=\"CENTER\"><TD ALIGN=\"CENTER\" VALIGN=\"MIDDLE\">";
	$msg.="<span style=\"font-face: $FontFace; font-size: $FontSize3; color: $HeadingFontColor; font-weight: bold;\">";
	$msg.=$l_fm['heading']."</span></td></tr>";
	$msg.="<TR BGCOLOR=\"$subheadingbgcolor\" ALIGN=\"CENTER\"><TD ALIGN=\"CENTER\" VALIGN=\"MIDDLE\">";
	$msg.="<span style=\"font-face: $FontFace; font-size: $FontSize2; color: $SubheadingFontColor; font-weight: bold;\">";
	$msg.=$l_fm['progname'].": ".display_encoded($progname)."</span></td></tr>";
	$sql = "select * from ".$tableprefix."_category where (programm=$prognr) order by displaypos";
	if(!$result = faqe_db_query($sql, $db))
		die("Could not connect to the database.");
	if (!$myrow = faqe_db_fetch_array($result))
		die($l_noentries);
	$catcount=0;
	$msg.="<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"CENTER\"><TD ALIGN=\"center\" VALIGN=\"MIDDLE\">";
	$msg.="<table bgcolor=\"$row_bgcolor\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">";
	$msg.="<tr><td><ul class=\"faqe\">";
	do{
		$catcount++;
		$subcatcount=1;
		$msg.="<li>";
		$msg.="<span style=\"font-face: $FontFace; font-size: $FontSize2; color: $GroupFontColor;\">";
		$msg.="<a href=\"#cat".$catcount."\">";
		$msg.=$catcount.". ".display_encoded($myrow["categoryname"]);
		$msg.="</a></span></li>";
		$sql = "select * from ".$tableprefix."_subcategory where (category=".$myrow["catnr"].")";
		if(!$result2 = faqe_db_query($sql, $db))
			die("Could not connect to the database.");
		$numsubcats=faqe_db_num_rows($result2);
		$sql = "select * from ".$tableprefix."_data where (category=".$myrow["catnr"].") and subcategory=0";
		if($faqsortmethod==0)
			$sql.=" order by editdate desc";
		else
			$sql.=" order by displaypos asc";
		if(!$result2 = faqe_db_query($sql, $db))
			die("Could not connect to the database.");
		if($numsubcats>0)
		{
			$msg.="<ul class=\"faqe\"><li>";
			$msg.="<span style=\"font-face: $FontFace; font-size: $subcatfontsize; color: $subcatfontcolor; font-style: italic;\">";
			$msg.="<a href=\"#subcat".$catcount."_".$subcatcount."\">";
			$msg.=$catcount.".".$subcatcount.". ".$l_fm['withoutsubcat'];
			$msg.="</a></span></li>";
		}
		$msg.="<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		if (!$myrow2 = faqe_db_fetch_array($result2))
		{
			$msg.="<ul class=\"faqe\">";
			$msg.="<li>".$l_fm['noentries']."</li>";
			$msg.="</ul>";
		}
		else
		{
			$faqcount=1;
			$msg.="<ul class=\"faqe\">";
			do{
				$msg.="<li><a href=\"#".$myrow2["faqnr"]."\">$catcount.";
				if($numsubcats>0)
					$msg.="$subcatcount.";
				$msg.="$faqcount ".undo_html_ampersand(stripslashes($myrow2["heading"]))."</a></li>";
				$faqcount+=1;
			}while($myrow2 = faqe_db_fetch_array($result2));
			$msg.="</ul>";
		}
		$msg.="</span>";
		$sql = "select * from ".$tableprefix."_subcategory where category=".$myrow["catnr"]." order by displaypos asc";
		if(!$result2 = faqe_db_query($sql, $db))
			die("Could not connect to the database.");
		if ($myrow2 = faqe_db_fetch_array($result2))
		{
			do{
				$subcatcount++;
				$msg.="<li><span style=\"font-face: $FontFace; font-size: $subcatfontsize; color: $subcatfontcolor; font-style: italic;\">";
				$msg.="<a href=\"#subcat".$catcount."_".$subcatcount."\">";
				$msg.=$catcount.".".$subcatcount.". ".display_encoded($myrow2["categoryname"]);
				$msg.="</a></span><br></li>";
				$msg.="<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor\">";
				$sql = "select * from ".$tableprefix."_data where subcategory=".$myrow2["catnr"];
				if(!$result3 = faqe_db_query($sql, $db))
					die("Could not connect to the database.");
				if (!$myrow3 = faqe_db_fetch_array($result3))
				{
					$msg.="<ul class=\"faqe\">";
					$msg.="<li>".$l_fm['noentries']."</li>";
					$msg.="</ul>";
				}
				else
				{
					$faqcount=1;
					$msg.="<ul class=\"faqe\">";
					do{
						$msg.="<li><a href=\"#".$myrow3["faqnr"]."\">$catcount.";
						if($numsubcats>0)
							$msg.="$subcatcount.";
						$msg.="$faqcount ".undo_html_ampersand(stripslashes($myrow3["heading"]))."</a></li>";
						$faqcount+=1;
					}while($myrow3 = faqe_db_fetch_array($result3));
					$msg.="</ul>";
				}
			}while($myrow2 = faqe_db_fetch_array($result2));
			$msg.="</span>";
		}
		if($numsubcats>0)
			$msg.="</ul>";
	} while($myrow = faqe_db_fetch_array($result));
	$msg.="</ul></td></tr></table>";
	$sql = "select * from ".$tableprefix."_category where (programm=$prognr) order by displaypos";
	if(!$result = faqe_db_query($sql, $db))
		die("Could not connect to the database.");
	if (!$myrow = faqe_db_fetch_array($result))
		die($l_noentries);
	$catcount=0;
	do{
		$subcatcount=1;
		$catcount++;
		$msg.="<TR BGCOLOR=\"$group_bgcolor\" ALIGN=\"CENTER\"><TD ALIGN=\"left\" VALIGN=\"MIDDLE\">";
		$msg.="<span style=\"font-face: $FontFace; font-size: $FontSize2; color: $GroupFontColor;\">";
		$msg.="<a name=\"#cat".$catcount."\">".$catcount.". ".display_encoded($myrow["categoryname"])."</a></span></td></tr>";
		$sql = "select * from ".$tableprefix."_subcategory where (category=".$myrow["catnr"].")";
		if(!$result2 = faqe_db_query($sql, $db))
			die("Could not connect to the database.");
		$numsubcats=faqe_db_num_rows($result2);
		if($numsubcats>0)
		{
			$msg.="<TR BGCOLOR=\"$subcatbgcolor\" ALIGN=\"CENTER\"><TD ALIGN=\"left\" VALIGN=\"MIDDLE\">";
			$msg.="<span style=\"font-face: $FontFace; font-size: $subcatfontsize; color: $subcatfontcolor; font-style: italic;\">";
			$msg.="<a name=\"#subcat".$catcount."_".$subcatcount."\">".$catcount.".".$subcatcount." ".$l_fm['withoutsubcat']."</a></span></td></tr>";
		}
		$sql = "select * from ".$tableprefix."_data where (category=".$myrow["catnr"].") and subcategory=0";
		if($faqsortmethod==0)
			$sql.=" order by editdate desc";
		else
			$sql.=" order by displaypos asc";
		if(!$result2 = faqe_db_query($sql, $db))
			die("Could not connect to the database.");
		$msg.="<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"CENTER\"><TD ALIGN=\"left\" VALIGN=\"MIDDLE\">";
		$msg.="<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		if (!$myrow2 = faqe_db_fetch_array($result2))
			$msg.=$l_fm['noentries'];
		else
		{
			$faqcount=1;
			do{
				$questiontext=stripslashes($myrow2["questiontext"]);
				$questiontext=str_replace("{lang}","$langvar=$proglang",$questiontext);
				$questiontext=str_replace("{url_faqengine}",$url_faqengine,$questiontext);
				$questiontext=str_replace("{onlynewfaq}",0,$questiontext);
				$questiontext = str_replace("{bbc_code}",$l_fm['bbccode'],$questiontext);
				$questiontext = str_replace("{bbc_quote}",$l_fm['bbcquote'],$questiontext);
				$questiontext = undo_htmlspecialchars($questiontext);
				$answertext=stripslashes($myrow2["answertext"]);
				$answertext=str_replace("{lang}","$langvar=$proglang",$answertext);
				$answertext=str_replace("{url_faqengine}",$url_faqengine,$answertext);
				$answertext=str_replace("{onlynewfaq}",0,$answertext);
				$answertext = str_replace("{bbc_code}",$l_fm['bbccode'],$answertext);
				$answertext = str_replace("{bbc_quote}",$l_fm['bbcquote'],$answertext);
				$answertext = undo_htmlspecialchars($answertext);
				$msg.="$catcount.";
				if($numsubcats>0)
					$msg.="$subcatcount.";
				$msg.="$faqcount. <a name=\"#".$myrow2["faqnr"]."\"><b>".undo_html_ampersand(stripslashes($myrow2["heading"]))."</b></a><br>";
				$msg.="<i>".$l_fm['question'].":</i><br>";
				$msg.=$questiontext."<br>";
				$msg.="<i>".$l_fm['answer'].":</i><br>";
				$msg.=$answertext."<br>";
				$attachsql="select f.filename, f.filesize, fa.* from ".$tableprefix."_faq_attachs fa, ".$tableprefix."_files f where f.entrynr=fa.attachnr and fa.faqnr=".$myrow2["faqnr"];
				if(!$attachresult = faqe_db_query($attachsql, $db))
					die("Could not connect to the database.");
				while($attachrow=faqe_db_fetch_array($attachresult))
				{
					$msg.="<a href=\"".$faqe_fullurl."/download.php?attachnr=".$attachrow["attachnr"]."\">".$l_fm['attachement']."</a> (".$attachrow["filename"].", ".$attachrow["filesize"]." Bytes)<br>";
				}
				$msg.="<br>";
				$faqcount+=1;
			}while($myrow2 = faqe_db_fetch_array($result2));
		}
		$msg.="</span></td></tr>";
		$sql = "select * from ".$tableprefix."_subcategory where category=".$myrow["catnr"]." order by displaypos asc";
		if(!$result2 = faqe_db_query($sql, $db))
			die("Could not connect to the database.");
		if ($myrow2 = faqe_db_fetch_array($result2))
		{
			do{
				$subcatcount++;
				$msg.="<TR BGCOLOR=\"$subcatbgcolor\" ALIGN=\"CENTER\"><TD ALIGN=\"left\" VALIGN=\"MIDDLE\">";
				$msg.="<span style=\"font-face: $FontFace; font-size: $subcatfontsize; color: $subcatfontcolor; font-style: italic;\">";
				$msg.="<a name=\"#subcat".$catcount."_".$subcatcount."\">";
				$msg.=$catcount.".".$subcatcount." ".display_encoded($myrow2["categoryname"])."</a></span></td></tr>";
				$sql = "select * from ".$tableprefix."_data where subcategory=".$myrow2["catnr"];
				if($faqsortmethod==0)
					$sql.=" order by editdate desc";
				else
					$sql.=" order by displaypos asc";
				if(!$result3 = faqe_db_query($sql, $db))
					die("Could not connect to the database.");
				$msg.="<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"CENTER\"><TD ALIGN=\"left\" VALIGN=\"MIDDLE\">";
				$msg.="<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
				if (!$myrow3 = faqe_db_fetch_array($result3))
					$msg.=$l_fm['noentries'];
				else
				{
					$faqcount=1;
					do{
						$questiontext=stripslashes($myrow3["questiontext"]);
						$questiontext=str_replace("{lang}","$langvar=$proglang",$questiontext);
						$questiontext=str_replace("{url_faqengine}",$url_faqengine,$questiontext);
						$questiontext = str_replace("{bbc_code}",$l_fm['bbccode'],$questiontext);
						$questiontext = str_replace("{bbc_quote}",$l_fm['bbcquote'],$questiontext);
						$questiontext=str_replace("{onlynewfaq}",0,$questiontext);
						$questiontext = undo_htmlspecialchars($questiontext);
						$answertext=stripslashes($myrow3["answertext"]);
						$answertext=str_replace("{lang}","$langvar=$proglang",$answertext);
						$answertext=str_replace("{url_faqengine}",$url_faqengine,$answertext);
						$answertext=str_replace("{onlynewfaq}",0,$answertext);
						$answertext = str_replace("{bbc_code}",$l_fm['bbccode'],$answertext);
						$answertext = str_replace("{bbc_quote}",$l_fm['bbcquote'],$answertext);
						$answertext = undo_htmlspecialchars($answertext);
						$msg.="$catcount.$subcatcount.$faqcount. <a name=\"#".$myrow3["faqnr"]."\"><b>".undo_html_ampersand(stripslashes($myrow3["heading"]))."</b></a><br>";
						$msg.="<i>".$l_fm['question'].":</i><br>";
						$msg.=$questiontext."<br>";
						$msg.="<i>".$l_fm['answer'].":</i><br>";
						$msg.=$answertext."<br>";
						$attachsql="select f.filename, f.filesize, fa.* from ".$tableprefix."_faq_attachs fa, ".$tableprefix."_files f where f.entrynr=fa.attachnr and fa.faqnr=".$myrow3["faqnr"];
						if(!$attachresult = faqe_db_query($attachsql, $db))
							die("Could not connect to the database.");
						while($attachrow=faqe_db_fetch_array($attachresult))
						{
							$msg.="<a href=\"".$faqe_fullurl."/download.php?attachnr=".$attachrow["attachnr"]."\">".$l_fm['attachement']."</a> (".$attachrow["filename"].", ".$attachrow["filesize"]." Bytes)<br>";
						}
						$msg.="<br>";
						$faqcount+=1;
					}while($myrow3 = faqe_db_fetch_array($result3));
				}
				$msg.="</span></td></tr>";
			} while($myrow2 = faqe_db_fetch_array($result2));
		}
	} while($myrow = faqe_db_fetch_array($result));
	$msg.="</table></td></tr></table>";
	$msg.="<table align=\"center\" bgcolor=\"$copyrightbgcolor\" width=\"$TableWidth\" border=\"0\">";
	$msg.="<tr><td class=\"copyright\" align=\"center\">";
	$msg.="<span style=\"font-face: $FontFace; font-size: $FontSize4; color: $FontColor\">";
	$displaytime=date("H:i");
	$msg.=$l_fm['currtime']." $displaytime<br>";
	$msg.=$l_fm['timezone_note']." ";
	$msg.=timezonename($server_timezone);
	$gmtoffset=tzgmtoffset($server_timezone);
	if($gmtoffset)
		$msg.=" (".$gmtoffset.")";
	$msg.="<br>";
	if($contentcopy)
		$msg.="<br><span class=\"contentcopy\">".$l_fm['content']." ".display_encoded($contentcopy)."</span><br>";
	else
		$msg.="<br><span class=\"contentcopy\">".$l_fm['content']." ".$faqsitename."</span><br>";
	$msg.=$l_fm['generated_with']." FAQEngine v$faqeversion";
	$msg.="</span></td></tr></table>";
	return $msg;
}
?>