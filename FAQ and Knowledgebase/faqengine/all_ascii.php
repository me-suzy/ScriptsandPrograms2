<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('./config.php');
require_once('./functions.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$default_lang;
else
	$act_lang=$$langvar;
include_once('./includes/get_settings.inc');
require_once('./includes/block_leacher.inc');
if(!language_avail($act_lang))
	die ("Language <b>$act_lang</b> not configured");
include_once('./language/lang_'.$act_lang.'.php');
if($blockoldbrowser==1)
{
	if(is_ns3() || is_msie3())
	{
		$sql="select * from ".$tableprefix."_texts where textid='oldbrowser' and lang='$act_lang'";
		if(!$result = mysql_query($sql, $db))
		    die("Could not connect to the database.");
		if($myrow = mysql_fetch_array($result))
			echo strip_tags(undo_htmlspecialchars($myrow["text"]));
		else
			echo $l_oldbrowser;
		exit;
	}
}
if((@fopen("./config.php", "a")) && !$noseccheck)
{
	die($l_config_writeable);
}
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
if($asclistmimetype==0)
{
	$content_type="Content-Type: ".$avail_mimetypes[$asclistmimetype]."; charset=".$asclistcharset."\n";
	header($content_type);
	header('Content-Disposition: filename="faqlist.txt"\n');
}
else
{
	$content_type="Content-Type: ".$avail_mimetypes[$asclistmimetype]."\n";
	header($content_type);
	header('Content-Disposition: filename="faqlist.txt"\n');
}
$data="";
header("Content-Transfer-Encoding: binary\n");
$sql = "select * from ".$tableprefix."_misc";
if(!$result = faqe_db_query($sql, $db)) {
    die("Could not connect to the database.");
}
if ($myrow = faqe_db_fetch_array($result))
{
	if($myrow["shutdown"]==1)
	{
		$shutdowntext=stripslashes($myrow["shutdowntext"]);
		$shutdowntext = undo_htmlspecialchars($shutdowntext);
		echo $shutdowntext;
		exit;
	}
}
if(!isset($prog))
	die($l_calling_error);
if($allowlists!=1)
	die($l_function_disabled);
if($useascheader==1)
{
	$ascheader=str_replace("\n", "\r\n", $ascheader);
	if(($ascheaderfile) && ($ascheaderfilepos==0))
		$data.=read_headerfile($ascheaderfile).$crlf;
	$data.=$ascheader.$crlf;
	if(($ascheaderfile) && ($ascheaderfilepos==1))
		$data.=read_headerfile($ascheaderfile).$crlf;
}
$sql = "select * from ".$tableprefix."_programm where (progid='$prog') and (language='$act_lang')";
if(!$result = faqe_db_query($sql, $db))
   	die("Could not connect to the database.");
if (!$myrow = faqe_db_fetch_array($result))
   	die($l_nosuchprog);
else
	$prognr=$myrow["prognr"];
$data.="$l_heading$crlf";
$data.="$l_progname: ".undo_htmlentities(stripslashes($myrow["programmname"]))."$crlf$crlf";
$sql = "select * from ".$tableprefix."_category where (programm='$prognr') order by displaypos";
if(!$result = faqe_db_query($sql, $db))
   	die("Could not connect to the database.");
if (!$myrow = faqe_db_fetch_array($result))
   	die($l_noentries);
$faqcount=1;
do{
	$data.=$myrow["categoryname"]."$crlf";
	$sql = "select * from ".$tableprefix."_data where (category=".$myrow["catnr"].") and subcategory = 0";
	if($faqsortmethod==0)
		$sql.=" order by editdate desc";
	else
		$sql.=" order by displaypos asc";
	if(!$result2 = faqe_db_query($sql, $db))
	   	die("Could not connect to the database.");
	if (!$myrow2 = faqe_db_fetch_array($result2))
		$data.=undo_htmlentities($l_noentries).$crlf;
	else
	{
		do{
			$headingtext=stripslashes($myrow2["heading"]);
			$headingtext=undo_htmlentities(stripslashes($headingtext));
			if($asclinelength>0)
			{
				if($ascforcewrap==1)
					$headingtext=wordwrap($headingtext,$asclinelength,$crlf,1);
				else
					$headingtext=wordwrap($headingtext,$asclinelength,$crlf);
			}
			$data.="$faqcount. ".$headingtext."$crlf";
			$faqcount+=1;
		}while($myrow2 = faqe_db_fetch_array($result2));
	}
	$data.=$crlf;
	$sql = "select * from ".$tableprefix."_subcategory where category=".$myrow["catnr"]." order by displaypos asc";
	if(!$result2 = faqe_db_query($sql, $db))
	   	die("Could not connect to the database.");
	if ($myrow2 = faqe_db_fetch_array($result2))
	{
		do{
		$data.=" ".undo_htmlentities(stripslashes($myrow2["categoryname"]))."$crlf";
		$sql = "select * from ".$tableprefix."_data where subcategory=".$myrow2["catnr"]." order by displaypos asc";
		if(!$result3 = faqe_db_query($sql, $db))
		   	die("Could not connect to the database.");
		if (!$myrow3 = faqe_db_fetch_array($result3))
			$data.=undo_htmlentities($l_noentries).$crlf;
		else
		{
			do{
				$headingtext=stripslashes($myrow3["heading"]);
				$headingtext=undo_htmlentities(stripslashes($headingtext));
				if($asclinelength>0)
				{
					if($ascforcewrap==1)
						$headingtext=wordwrap($headingtext,$asclinelength,$crlf,1);
					else
						$headingtext=wordwrap($headingtext,$asclinelength,$crlf);
				}
				$data.="$faqcount. ".$headingtext."$crlf";
				$faqcount+=1;
			}while($myrow3 = faqe_db_fetch_array($result3));
		}
		}while($myrow2 = faqe_db_fetch_array($result2));
		$data.=$crlf;
	}
} while($myrow = faqe_db_fetch_array($result));
$data.="$crlf$crlf";
$sql = "select * from ".$tableprefix."_category where (programm='$prognr') order by displaypos asc";
if(!$result = faqe_db_query($sql, $db))
   	die("Could not connect to the database.");
if (!$myrow = faqe_db_fetch_array($result))
   	die($l_noentries);
$faqcount=1;
do{
	$data.=undo_htmlentities(stripslashes($myrow["categoryname"]))."$crlf";
	$sql = "select * from ".$tableprefix."_data where (category=".$myrow["catnr"].") and subcategory=0";
	if($faqsortmethod==0)
		$sql.=" order by editdate desc";
	else
		$sql.=" order by displaypos asc";
	if(!$result2 = faqe_db_query($sql, $db))
	   	die("Could not connect to the database.");
	if (!$myrow2 = faqe_db_fetch_array($result2))
		$data.=undo_htmlentities($l_noentries).$crlf.$crlf;
	else
	{
		do{
			if($myrow2["linkedfaq"]!=0)
			{
				$tmpsql="select * from ".$tableprefix."_data where faqnr=".$myrow2["linkedfaq"];
				if(!$tmpresult = faqe_db_query($tmpsql, $db))
					die("Could not connect to the database.");
				if(!$tmprow = faqe_db_fetch_array($tmpresult))
					die("corrupted database");
				$entrydata=$tmprow;
			}
			else
				$entrydata=$myrow2;
			$headingtext=stripslashes($myrow2["heading"]);
			$headingtext=undo_htmlentities($headingtext);
			if($asclinelength>0)
			{
				if($ascforcewrap==1)
					$headingtext=wordwrap($headingtext,$asclinelength,$crlf,1);
				else
					$headingtext=wordwrap($headingtext,$asclinelength,$crlf);
			}
			$questiontext = stripslashes($entrydata["questiontext"]);
			$questiontext = str_replace("<BR>", $crlf, $questiontext);
			$questiontext = undo_htmlentities($questiontext);
			$questiontext = strip_tags($questiontext);
			$questiontext = str_replace("{bbc_code}",$l_bbccode,$questiontext);
			$questiontext = str_replace("{bbc_quote}",$l_bbcquote,$questiontext);
			$questiontext = undo_htmlentities($questiontext);
			if($asclinelength>0)
			{
				if($ascforcewrap==1)
					$questiontext=wordwrap($questiontext,$asclinelength,$crlf,1);
				else
					$questiontext=wordwrap($questiontext,$asclinelength,$crlf);
			}
			$answertext=stripslashes($entrydata["answertext"]);
			$answertext = str_replace("<BR>", $crlf, $answertext);
			$answertext = undo_htmlentities($answertext);
			$answertext = strip_tags($answertext);
			$answertext = str_replace("{bbc_code}",$l_bbccode,$answertext);
			$answertext = str_replace("{bbc_quote}",$l_bbcquote,$answertext);
			$answertext = undo_htmlentities($answertext);
			if($asclinelength>0)
			{
				if($ascforcewrap==1)
					$answertext=wordwrap($answertext,$asclinelength,$crlf,1);
				else
					$answertext=wordwrap($answertext,$asclinelength,$crlf,0);
			}
			$data.="$faqcount. ".$headingtext."$crlf";
			$data.="$l_question:$crlf";
			$data.=$questiontext."$crlf";
			$data.="$l_answer:$crlf";
			$data.=$answertext."$crlf$crlf";
			$faqcount+=1;
		}while($myrow2 = faqe_db_fetch_array($result2));
	}
	$sql = "select * from ".$tableprefix."_subcategory where category=".$myrow["catnr"]." order by displaypos asc";
	if(!$result2 = faqe_db_query($sql, $db))
	   	die("Could not connect to the database.");
	if ($myrow2 = faqe_db_fetch_array($result2))
	{
		do{
		$data.=" ".undo_htmlentities(stripslashes($myrow2["categoryname"]))."$crlf";
		$sql = "select * from ".$tableprefix."_data where subcategory=".$myrow2["catnr"];
		if($faqsortmethod==0)
			$sql.=" order by editdate desc";
		else
			$sql.=" order by displaypos asc";
		if(!$result3 = faqe_db_query($sql, $db))
		   	die("Could not connect to the database.");
		if (!$myrow3 = faqe_db_fetch_array($result3))
			$data.=undo_htmlentities($l_noentries).$crlf.$crlf;
		else
		{
			do{
				if($myrow3["linkedfaq"]!=0)
				{
					$tmpsql="select * from ".$tableprefix."_data where faqnr=".$myrow3["linkedfaq"];
					if(!$tmpresult = faqe_db_query($tmpsql, $db))
						die("Could not connect to the database.");
					if(!$tmprow = faqe_db_fetch_array($tmpresult))
						die("corrupted database");
					$entrydata=$tmprow;
				}
				else
					$entrydata=$myrow3;
				$headingtext=stripslashes($myrow3["heading"]);
				$headingtext=undo_htmlentities($headingtext);
				if($asclinelength>0)
				{
					if($ascforcewrap==1)
						$headingtext=wordwrap($headingtext,$asclinelength,$crlf,1);
					else
						$headingtext=wordwrap($headingtext,$asclinelength,$crlf);
				}
				$questiontext=stripslashes($entrydata["questiontext"]);
				$questiontext = str_replace("<BR>", $crlf, $questiontext);
				$questiontext = undo_htmlentities($questiontext);
				$questiontext = strip_tags($questiontext);
				$questiontext = str_replace("{bbc_code}",$l_bbccode,$questiontext);
				$questiontext = str_replace("{bbc_quote}",$l_bbcquote,$questiontext);
				$questiontext = undo_htmlentities($questiontext);
				if($asclinelength>0)
				{
					if($ascforcewrap==1)
						$questiontext=wordwrap($questiontext,$asclinelength,$crlf,1);
					else
						$questiontext=wordwrap($questiontext,$asclinelength,$crlf);
				}
				$answertext=stripslashes($entrydata["answertext"]);
				$answertext = str_replace("<BR>", $crlf, $answertext);
				$answertext = undo_htmlentities($answertext);
				$answertext = strip_tags($answertext);
				$answertext = str_replace("{bbc_code}",$l_bbccode,$answertext);
				$answertext = str_replace("{bbc_quote}",$l_bbcquote,$answertext);
				$answertext = undo_htmlentities($answertext);
				if($asclinelength>0)
				{
					if($ascforcewrap==1)
						$answertext=wordwrap($answertext,$asclinelength,$crlf,1);
					else
						$answertext=wordwrap($answertext,$asclinelength,$crlf,0);
				}
				$data.="$faqcount. ".$headingtext."$crlf";
				$data.="$l_question:$crlf";
				$data.=$questiontext."$crlf";
				$data.="$l_answer:$crlf";
				$data.=$answertext."$crlf$crlf";
				$faqcount+=1;
			}while($myrow3 = faqe_db_fetch_array($result3));
		}
	} while($myrow2 = faqe_db_fetch_array($result2));
	}
} while($myrow = faqe_db_fetch_array($result));
$actdate=date("$dateformat H:i");
$data.=undo_htmlentities($l_generated).": $actdate$crlf";
if($showtimezone==1)
{
	$tmpmsg="$l_timezone_note ";
	$tmpmsg.=timezonename($server_timezone);
	$gmtoffset=tzgmtoffset($server_timezone);
	if($gmtoffset)
		$tmpmsg.=" (".$gmtoffset.")";
	$data.=undo_htmlentities($tmpmsg)."$crlf";
}
$copyrightline="";
if($contentcopy)
	$copyrightline=undo_htmlentities("$l_content ".undo_htmlspecialchars($contentcopy)).$crlf;
else
	$copyrightline=undo_htmlentities("$l_content ".$faqsitename).$crlf;
$copyrightline.=undo_htmlentities($l_generated_with)." FAQEngine v$faqeversion, ".$copyright_asc;
if($l_translationnote)
	$copyrightline.=$crlf.undo_htmlentities($l_translationnote);
if($asclinelength>0)
{
	if($ascforcewrap==1)
		$copyrightline=wordwrap($copyrightline,$asclinelength,$crlf,1);
	else
		$copyrightline=wordwrap($copyrightline,$asclinelength,$crlf);
}
$data.=$copyrightline.$crlf;
header("Content-length: " . strlen($data) . "\n");
print($data);
?>
