<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
function get_faqsasasc($prognr, $progname, $l_fm)
{
	global $tableprefix, $db;
	global $l_nosuchprog, $faqeversion;
	global $langvar, $url_faqengine;
	global $copyright_url, $copyright_note, $crlf;
	global $faqeversion;

	$data="";
	include('../includes/get_settings.inc');

	$data.=undo_htmlentities($l_fm['heading']).$crlf;
	$data.=undo_htmlentities($l_fm['progname']).": ".undo_htmlentities(stripslashes($progname))."$crlf$crlf";
	$sql = "select * from ".$tableprefix."_category where (programm=$prognr) order by displaypos";
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
			$data.=undo_htmlentities($l_fm['noentries']).$crlf;
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
					$data.=undo_htmlentities($l_fm['noentries']).$crlf;
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
	$sql = "select * from ".$tableprefix."_category where (programm=$prognr) order by displaypos asc";
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
			$data.=undo_htmlentities($l_fm['noentries']).$crlf.$crlf;
		else
		{
			do{
				$headingtext=stripslashes($myrow2["heading"]);
				$headingtext=undo_htmlentities($headingtext);
				if($asclinelength>0)
				{
					if($ascforcewrap==1)
						$headingtext=wordwrap($headingtext,$asclinelength,$crlf,1);
					else
						$headingtext=wordwrap($headingtext,$asclinelength,$crlf);
				}
				$questiontext = stripslashes($myrow2["questiontext"]);
				$questiontext = str_replace("<BR>", $crlf, $questiontext);
				$questiontext = undo_htmlentities($questiontext);
				$questiontext = strip_tags($questiontext);
				$questiontext = str_replace("{bbc_code}",undo_htmlentities($l_fm['bbccode']),$questiontext);
				$questiontext = str_replace("{bbc_quote}",undo_htmlentities($l_fm['bbcquote']),$questiontext);
				$questiontext = undo_htmlentities($questiontext);
				if($asclinelength>0)
				{
					if($ascforcewrap==1)
						$questiontext=wordwrap($questiontext,$asclinelength,$crlf,1);
					else
						$questiontext=wordwrap($questiontext,$asclinelength,$crlf);
				}
				$answertext=stripslashes($myrow2["answertext"]);
				$answertext = str_replace("<BR>", $crlf, $answertext);
				$answertext = undo_htmlentities($answertext);
				$answertext = strip_tags($answertext);
				$answertext = str_replace("{bbc_code}",undo_htmlentities($l_fm['bbccode']),$answertext);
				$answertext = str_replace("{bbc_quote}",undo_htmlentities($l_fm['bbcquote']),$answertext);
				$answertext = undo_htmlentities($answertext);
				if($asclinelength>0)
				{
					if($ascforcewrap==1)
						$answertext=wordwrap($answertext,$asclinelength,$crlf,1);
					else
						$answertext=wordwrap($answertext,$asclinelength,$crlf,0);
				}
				$data.="$faqcount. ".$headingtext."$crlf";
				$data.=undo_htmlentities($l_fm['question']).":$crlf";
				$data.=$questiontext."$crlf";
				$data.=undo_htmlentities($l_fm['answer']).":$crlf";
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
					$data.=undo_htmlentities($l_fm['noentries']).$crlf.$crlf;
				else
				{
					do{
						$headingtext=stripslashes($myrow3["heading"]);
						$headingtext=undo_htmlentities($headingtext);
						if($asclinelength>0)
						{
							if($ascforcewrap==1)
								$headingtext=wordwrap($headingtext,$asclinelength,$crlf,1);
							else
								$headingtext=wordwrap($headingtext,$asclinelength,$crlf);
						}
						$questiontext=stripslashes($myrow3["questiontext"]);
						$questiontext = str_replace("<BR>", $crlf, $questiontext);
						$questiontext = undo_htmlentities($questiontext);
						$questiontext = strip_tags($questiontext);
						$questiontext = str_replace("{bbc_code}",undo_htmlentities($l_fm['bbccode']),$questiontext);
						$questiontext = str_replace("{bbc_quote}",undo_htmlentities($l_fm['bbcquote']),$questiontext);
						$questiontext = undo_htmlentities($questiontext);
						if($asclinelength>0)
						{
							if($ascforcewrap==1)
								$questiontext=wordwrap($questiontext,$asclinelength,$crlf,1);
							else
								$questiontext=wordwrap($questiontext,$asclinelength,$crlf);
						}
						$answertext=stripslashes($myrow3["answertext"]);
						$answertext = str_replace("<BR>", $crlf, $answertext);
						$answertext = undo_htmlentities($answertext);
						$answertext = strip_tags($answertext);
						$answertext = str_replace("{bbc_code}",undo_htmlentities($l_fm['bbccode']),$answertext);
						$answertext = str_replace("{bbc_quote}",undo_htmlentities($l_fm['bbcquote']),$answertext);
						$answertext = undo_htmlentities($answertext);
						if($asclinelength>0)
						{
							if($ascforcewrap==1)
								$answertext=wordwrap($answertext,$asclinelength,$crlf,1);
							else
								$answertext=wordwrap($answertext,$asclinelength,$crlf,0);
						}
						$data.="$faqcount. ".$headingtext."$crlf";
						$data.=undo_htmlentities($l_fm['question']).":$crlf";
						$data.=$questiontext."$crlf";
						$data.=undo_htmlentities($l_fm['answer']).":$crlf";
						$data.=$answertext."$crlf$crlf";
						$faqcount+=1;
					}while($myrow3 = faqe_db_fetch_array($result3));
				}
			} while($myrow2 = faqe_db_fetch_array($result2));
		}
	} while($myrow = faqe_db_fetch_array($result));
	$actdate=date("$dateformat H:i");
	$data.=undo_htmlentities($l_fm['generated']).": $actdate$crlf";
	if($showtimezone==1)
	{
		$tmpmsg=$l_fm['timezone_note']." ";
		$tmpmsg.=timezonename($server_timezone);
		$gmtoffset=tzgmtoffset($server_timezone);
		if($gmtoffset)
			$tmpmsg.=" (".$gmtoffset.")";
		$data.=undo_htmlentities($tmpmsg)."$crlf";
	}
	if($contentcopy)
	{
		$tmptxt=undo_htmlentities(stripslashes($contentcopy));
		$tmptxt=strip_tags($tmptxt);
		$data.=undo_htmlentities($l_fm['content'])." ".$tmptxt.$crlf;
	}
	else
		$data.=undo_htmlentities($l_fm['content'])." ".$faqsitename.$crlf;
	$copyrightline=undo_htmlentities($l_fm['generated_with'])." FAQEngine v$faqeversion".$crlf;
	if($asclinelength>0)
	{
		if($ascforcewrap==1)
			$copyrightline=wordwrap($copyrightline,$asclinelength,$crlf,1);
		else
			$copyrightline=wordwrap($copyrightline,$asclinelength,$crlf);
	}
	$data.=$copyrightline;
	return $data;
}
?>
