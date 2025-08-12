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
$page_title=$l_faqupload;
$page="faq_upload";
require_once('./heading.php');
if(!$upload_avail)
	die("Function not available");
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($admin_rights < 2)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
if(isset($mode))
{
	if($mode=="upload")
	{
		$errors=0;
		if($new_global_handling)
			$tmp_file=$_FILES['faqlist']['tmp_name'];
		else
			$tmp_file=$HTTP_POST_FILES['faqlist']['tmp_name'];
		if(!is_uploaded_file($tmp_file))
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nofile</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$actmode=0;
			$numadded=0;
			$faqfile=fopen($tmp_file,"r");
			$maxsize=filesize($tmp_file);
			while($fileLine=fgets($faqfile,$maxsize))
			{
				if(eregi("^\{faqlist\}",$fileLine))
					$actmode=1;
				else if($actmode==1)
				{
					if(eregi("^\{".$upload_hash."\}",$fileLine))
						$actmode=2;
				}
				else if($actmode==2)
				{
					if(eregi("^\{/faqlist\}",$fileLine))
						$actmode=0;
					if(eregi("^\{faqentry\}",$fileLine))
					{
						$faqnr=0;
						$category=0;
						$subcategory=0;
						$heading="";
						$question="";
						$answer="";
						$actmode=3;
						$local_urlautoencode=true;
						$local_enablespcode=true;
						$disablehtml=false;
						$nobrtransquestion=false;
						$nobrtransanswer=false;
					}
				}
				else if($actmode==3)
				{
					if(eregi("^\{faqnr\}",$fileLine))
					{
						$fileLine=fgets($faqfile,$maxsize);
						if($fileLine)
						{
							$faqnr=trim($fileLine);
						}
						$fileLine=fgets($faqfile,$maxsize);
						if($fileLine)
						{
							if(!eregi("^\{/faqnr\}",$fileLine))
							{
								die("<tr class=\"errorrow\" align=\"center\"><td>corrupted list file");
							}
						}
					}
					if(eregi("^\{category\}",$fileLine))
					{
						$fileLine=fgets($faqfile,$maxsize);
						if($fileLine)
						{
							$category=trim($fileLine);
						}
						$fileLine=fgets($faqfile,$maxsize);
						if($fileLine)
						{
							if(!eregi("^\{/category\}",$fileLine))
							{
								die("<tr class=\"errorrow\" align=\"center\"><td>corrupted list file");
							}
						}
					}
					if(eregi("^\{subcategory\}",$fileLine))
					{
						$fileLine=fgets($faqfile,$maxsize);
						if($fileLine)
						{
							$subcategory=trim($fileLine);
						}
						$fileLine=fgets($faqfile,$maxsize);
						if($fileLine)
						{
							if(!eregi("^\{/subcategory\}",$fileLine))
							{
								die("<tr class=\"errorrow\" align=\"center\"><td>corrupted list file");
							}
						}
					}
					if(eregi("^\{options\}",$fileLine))
					{
						$fileLine=fgets($kbfile,$maxsize);
						if($fileLine)
						{
							$options=trim($fileLine);
							$options=explode("§",strtolower($options));
							for($i=0;$i<count($options);$i++)
							{
								list($optionname,$optionvalue)=explode("|",$options[$i]);
								if($optionname=="urlautoencode")
									$local_urlautoencode=strtobool($optionvalue);
								if($optionname=="enablebbc")
									$local_enablespcode=strtobool($optionvalue);
								if($optionname=="nobrtransquestion")
									$nobrtransquestion=strtobool($optionvalue);
								if($optionname=="nobrtransanswer")
									$nobrtransanswer=strtobool($optionvalue);
								if($optionname=="disablehtml")
									$disablehtml=strtobool($optionvalue);
							}
						}
						$fileLine=fgets($kbfile,$maxsize);
						if($fileLine)
						{
							if(!eregi("^\{/options\}",$fileLine))
								die("<tr class=\"errorrow\" align=\"center\"><td>corrupted list file");
						}
					}
					if(eregi("^\{heading\}",$fileLine))
					{
						$actmode=4;
						while(($fileLine=fgets($faqfile,$maxsize)) && ($actmode==4))
						{
							if(eregi("^\{/heading\}",$fileLine))
								$actmode=3;
							else
								$heading.=trim($fileLine);
						}
					}
					if(eregi("^\{question\}",$fileLine))
					{
						$actmode=4;
						while(($fileLine=fgets($faqfile,$maxsize)) && ($actmode==4))
						{
							if(eregi("^\{/question\}",$fileLine))
								$actmode=3;
							else
								$question.=trim($fileLine)."\n";
						}
					}
					if(eregi("^\{answer\}",$fileLine))
					{
						$actmode=4;
						while(($fileLine=fgets($faqfile,$maxsize)) && ($actmode==4))
						{
							if(eregi("^\{/answer\}",$fileLine))
								$actmode=3;
							else
								$answer.=trim($fileLine)."\n";
						}
					}
					if(eregi("^\{/faqentry\}",$fileLine))
					{
						$actmode=2;
						$question=stripslashes($question);
						if($disablehtml)
						{
							$question=htmlspecialchars($question);
							$question=undo_html_ampersand($question);
						}
						if($local_urlautoencode)
							$question = make_clickable($question);
						if($local_enablespcode)
							$question = bbencode($question);
						$question = do_htmlentities($question);
						if(!$nobrtransquestion)
						{
							$question = str_replace("\n", "<BR>", $question);
							$question = str_replace("\r", "", $question);
						}
						$question=addslashes($question);
						if($disablehtml)
						{
							$answer=htmlspecialchars($answer);
							$answer=undo_html_ampersand($answer);
						}
						$answer=stripslashes($answer);
						if($local_urlautoencode)
							$answer = make_clickable($answer);
						if($local_enablespcode)
							$answer = bbencode($answer);
						$answer = do_htmlentities($answer);
						if(!$nobrtransanswer)
						{
							$answer = str_replace("\n", "<BR>", $answer);
							$answer = str_replace("\r", "", $answer);
						}
						$answer=addslashes($answer);
						$heading=stripslashes($heading);
						$heading=do_htmlentities($heading);
						$heading=addslashes($heading);
						$editor=$userdata["username"];
						if($editor==-1)
							$editor="unknown";
						else
							$editor=addslashes($editor);
						$actdate = date("Y-m-d");
						if($faqnr>0)
						{
							$sql = "select * from ".$tableprefix."_data where faqnr=$faqnr";
							if(!$result = faqe_db_query($sql, $db))
							    die("Unable to connect to database.");
							if($myrow=faqe_db_fetch_array($result))
							{
								$oldcat=$myrow["category"];
								if($oldcat!=$category)
								{
									$sql = "UPDATE ".$tableprefix."_category SET numfaqs = numfaqs + 1 WHERE (catnr = $category)";
									@faqe_db_query($sql, $db);
									$sql = "UPDATE ".$tableprefix."_category SET numfaqs = numfaqs - 1 WHERE (catnr = $oldcat)";
									@faqe_db_query($sql, $db);
								}
								$sql = "update ".$tableprefix."_data set heading='$heading', questiontext='$question', answertext='$answer', ";
								$sql.= "editor='$editor', editdate='$actdate', category=$category, subcategory=$subcategory ";
								$sql.= "where faqnr=$faqnr";
								if(!$result = faqe_db_query($sql, $db))
								    die("<tr class=\"errorrow\" align=\"center\"><td>Unable to update FAQ in database.");
							}
							else
								$faqnr=0;
						}
						if($faqnr==0)
						{
							$sql = "UPDATE ".$tableprefix."_category SET numfaqs = numfaqs + 1 WHERE (catnr = $category)";
							@faqe_db_query($sql, $db);
							$sql = "select max(displaypos) as newdisplaypos from ".$tableprefix."_data where category=$category";
							if(!$result = faqe_db_query($sql, $db))
							    die("<tr class=\"errorrow\" align=\"center\"><td>Unable to add FAQ to database.");
							if($myrow=faqe_db_fetch_array($result))
								$displaypos=$myrow["newdisplaypos"]+1;
							else
								$displaypos=1;
							$sql = "INSERT INTO ".$tableprefix."_data (heading, category, questiontext, answertext, editor, editdate, displaypos, subcategory) ";
							$sql .="VALUES ('$heading', $category, '$question', '$answer', '$editor', '$actdate', $displaypos, $subcategory)";
							if(!$result = faqe_db_query($sql, $db))
							    die("<tr class=\"errorrow\" align=\"center\"><td>Unable to add FAQ to database.");
						}
						$numadded++;
					}
				}
			}
			fclose($faqfile);
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$numadded $l_faqsadded";
			echo "</td></tr></table></td></tr></table>";
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
<form name="inputform" onsubmit="return checkform();" ENCTYPE="multipart/form-data" method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_file?>:</td>
<td><input class="faqeinput" type="file" name="faqlist"></td></tr>
<tr class="actionrow"><td align="center" colspan="2">
<input type="hidden" name="mode" value="upload">
<input class="faqebutton" type="submit" value="<?php echo $l_ok?>"></td></tr>
</form>
</table></td></tr></table>
<?php
}
echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("faq.php?$langvar=$act_lang")."\">$l_faqlist</a></div>";
include('./trailer.php');
?>