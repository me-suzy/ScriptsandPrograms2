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
$page_title=$l_kbupload;
$page="faq_upload";
require_once('./heading.php');
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if(!$upload_avail)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotavail");
}

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
			$tmp_file=$_FILES['kblist']['tmp_name'];
		else
			$tmp_file=$HTTP_POST_FILES['kblist']['tmp_name'];
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
			$kbfile=fopen($tmp_file,"r");
			$maxsize=filesize($tmp_file);
			while($fileLine=fgets($kbfile,$maxsize))
			{
				if(eregi("^\{kblist\}",$fileLine))
					$actmode=1;
				else if($actmode==1)
				{
					if(eregi("^\{".$upload_hash."\}",$fileLine))
						$actmode=2;
				}
				else if($actmode==2)
				{
					if(eregi("^\{filedesc}",$fileLine))
					{
						$actmode=4;
						$fileLine=fgets($kbfile,$maxsize);
						if($fileLine)
						{
							$ftype=trim($fileLine);
						}
						else
						{
							die("<tr class=\"errorrow\" align=\"center\"><td>corrupted list file");
						}
						$fileLine=fgets($kbfile,$maxsize);
						if($fileLine)
						{
							$fver=trim($fileLine);
						}
						else
						{
							die("<tr class=\"errorrow\" align=\"center\"><td>corrupted list file");
						}
						$fileLine=fgets($kbfile,$maxsize);
						if($fileLine)
						{
							if(!eregi("^\{/filedesc\}",$fileLine))
								die("<tr class=\"errorrow\" align=\"center\"><td>corrupted list file");
						}
						if(strcasecmp($ftype,"kbuploadlist")!=0)
							die("<tr class=\"errorrow\" align=\"center\"><td>corrupted list file");
						if($fver!=$kbofflistversion)
							die("<tr class=\"errorrow\" align=\"center\"><td>$l_wrongfileversion");
					}
				}
				else if($actmode==4)
				{
					if(eregi("^\{kbentry\}",$fileLine))
					{
						$articlenr=0;
						$category=0;
						$subcategory=0;
						$program=0;
						$heading="";
						$text="";
						$keywords="";
						$affectedOS=array();
						$affectedVersions=array();
						$actmode=5;
						$local_urlautoencode=true;
						$local_enablespcode=true;
						$disablehtml=false;
						$nobrtrans=false;
					}
				}
				else if($actmode==5)
				{
					if(eregi("^\{/kblist\}",$fileLine))
						$actmode=0;
					if(eregi("^\{kbnr\}",$fileLine))
					{
						$fileLine=fgets($kbfile,$maxsize);
						if($fileLine)
						{
							$articlenr=trim($fileLine);
						}
						$fileLine=fgets($kbfile,$maxsize);
						if($fileLine)
						{
							if(!eregi("^\{/kbnr\}",$fileLine))
								die("<tr class=\"errorrow\" align=\"center\"><td>corrupted list file");
						}
					}
					if(eregi("^\{program\}",$fileLine))
					{
						$fileLine=fgets($kbfile,$maxsize);
						if($fileLine)
						{
							$program=trim($fileLine);
						}
						$fileLine=fgets($kbfile,$maxsize);
						if($fileLine)
						{
							if(!eregi("^\{/program\}",$fileLine))
								die("<tr class=\"errorrow\" align=\"center\"><td>corrupted list file");
						}
					}
					if(eregi("^\{category\}",$fileLine))
					{
						$fileLine=fgets($kbfile,$maxsize);
						if($fileLine)
						{
							$category=trim($fileLine);
						}
						$fileLine=fgets($kbfile,$maxsize);
						if($fileLine)
						{
							if(!eregi("^\{/category\}",$fileLine))
								die("<tr class=\"errorrow\" align=\"center\"><td>corrupted list file");
						}
					}
					if(eregi("^\{subcategory\}",$fileLine))
					{
						$fileLine=fgets($kbfile,$maxsize);
						if($fileLine)
						{
							$subcategory=trim($fileLine);
						}
						$fileLine=fgets($kbfile,$maxsize);
						if($fileLine)
						{
							if(!eregi("^\{/subcategory\}",$fileLine))
								die("<tr class=\"errorrow\" align=\"center\"><td>corrupted list file");
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
								if($optionname=="nobrtrans")
									$nobrtrans=strtobool($optionvalue);
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
					if(eregi("^\{keywords\}",$fileLine))
					{
						$actmode=6;
						while(($fileLine=fgets($kbfile,$maxsize)) && ($actmode==6))
						{
							if(eregi("^\{/keywords\}",$fileLine))
								$actmode=5;
							else
							{
								$keywords.=trim($fileLine)."\n";
							}
						}
					}
					if(eregi("^\{os\}",$fileLine))
					{
						$actmode=6;
						while(($fileLine=fgets($kbfile,$maxsize)) && ($actmode==6))
						{
							if(eregi("^\{/os\}",$fileLine))
								$actmode=5;
							else
							{
								array_push($affectedOS,trim($fileLine));
							}
						}
					}
					if(eregi("^\{progvers\}",$fileLine))
					{
						$actmode=6;
						while(($fileLine=fgets($kbfile,$maxsize)) && ($actmode==6))
						{
							if(eregi("^\{/progvers\}",$fileLine))
								$actmode=5;
							else
							{
								array_push($affectedVersions,trim($fileLine));
							}
						}
					}
					if(eregi("^\{heading\}",$fileLine))
					{
						$actmode=6;
						while(($fileLine=fgets($kbfile,$maxsize)) && ($actmode==6))
						{
							if(eregi("^\{/heading\}",$fileLine))
								$actmode=5;
							else
								$heading.=trim($fileLine);
						}
					}
					if(eregi("^\{text\}",$fileLine))
					{
						$actmode=6;
						while(($fileLine=fgets($kbfile,$maxsize)) && ($actmode==6))
						{
							if(eregi("^\{/text\}",$fileLine))
								$actmode=5;
							else
								$text.=trim($fileLine)."\n";
						}
					}
					if(eregi("^\{/kbentry\}",$fileLine))
					{
						$actmode=4;
						$text=stripslashes($text);
						if($disablehtml)
						{
							$text=htmlspecialchars($text);
							$text=undo_html_ampersand($text);
						}
						if($local_urlautoencode)
							$text = make_clickable($text);
						if($local_enablespcode)
							$text = bbencode($text);
						$text = do_htmlentities($text);
						if(!$nobrtrans)
						{
							$text = str_replace("\n", "<BR>", $text);
							$text = str_replace("\r", "", $text);
						}
						$text=addslashes($text);
						$heading=stripslashes($heading);
						$heading=do_htmlentities($heading);
						$heading=addslashes($heading);
						$editor=$userdata["username"];
						if($editor==-1)
							$editor="unknown";
						else
							$editor=addslashes($editor);
						$actdate = date("Y-m-d");
						if($articlenr>0)
						{
							$sql = "select * from ".$tableprefix."_kb_articles where articlenr=$articlenr";
							if(!$result = faqe_db_query($sql, $db))
								db_die("<tr class=\"errorrow\"><td>Unable to connect to database.");
							if($myrow=faqe_db_fetch_array($result))
							{
								$sql = "update ".$tableprefix."_kb_articles set heading='$heading', article='$text', ";
								$sql.= "editor='$editor', lastedited='$actdate', category=$category, subcategory=$subcategory, programm=$program ";
								$sql.= "where articlenr=$articlenr";
								if(!$result = faqe_db_query($sql, $db))
									db_die("<tr class=\"errorrow\" align=\"center\"><td>Unable to update article in database.");
							}
							else
								$articlenr=0;
						}
						if($articlenr==0)
						{
							$sql = "select max(displaypos) as newdisplaypos from ".$tableprefix."_kb_articles where programm=$program";
							if(!$result = faqe_db_query($sql, $db))
								db_die("<tr class=\"errorrow\" align=\"center\"><td>Unable to add FAQ to database.");
							if($myrow=faqe_db_fetch_array($result))
								$displaypos=$myrow["newdisplaypos"]+1;
							else
								$displaypos=1;
							$sql = "INSERT INTO ".$tableprefix."_kb_articles (heading, category, article, editor, lastedited, displaypos, subcategory, programm) ";
							$sql .="VALUES ('$heading', $category, '$text', '$editor', '$actdate', $displaypos, $subcategory, $program)";
							if(!$result = faqe_db_query($sql, $db))
								db_die("<tr class=\"errorrow\" align=\"center\"><td>Unable to add FAQ to database.");
							$articlenr=faqe_db_insert_id($db);
						}
						$rem_query = "delete from ".$tableprefix."_kb_keywords where articlenr=$articlenr";
						if(!faqe_db_query($rem_query,$db))
							db_die("<tr class=\"errorrow\"><td>Unable to delete keywords for article from DB.");
						$rem_query = "delete from ".$tableprefix."_kb_os where articlenr=$articlenr";
						if(!faqe_db_query($rem_query,$db))
							db_die("<tr class=\"errorrow\"><td>Unable to delete affected os for article from DB.");
						$rem_query = "delete from ".$tableprefix."_kb_prog_version where articlenr=$articlenr";
						if(!faqe_db_query($rem_query,$db))
							db_die("<tr class=\"errorrow\"><td>Unable to delete affected program versions for article from DB.");
						if($keywords)
						{
							$enteredkeywords = split("[|]",$keywords);
							foreach($enteredkeywords as $keyword)
							{
								$keyword=trim($keyword);
								if(strlen($keyword)>0)
								{
									$sql = "select * from ".$tableprefix."_keywords where LCASE(keyword)=LCASE('$keyword')";
									if(!$result = faqe_db_query($sql, $db))
										db_die("<tr class=\"errorrow\"><td>Unable to check keyword in database.");
									if(!$myrow=faqe_db_fetch_array($result))
									{
										$sql = "insert into ".$tableprefix."_keywords (keyword) values ('$keyword')";
										if(!$result = faqe_db_query($sql, $db))
											db_die("<tr class=\"errorrow\"><td>Unable to add keyword to database.");
										$kwnr=faqe_db_insert_id($db);
									}
									else
										$kwnr=$myrow["keywordnr"];
									$sql = "insert into ".$tableprefix."_kb_keywords (articlenr, keywordnr) values ($articlenr, $kwnr)";
									if(!$result = faqe_db_query($sql, $db))
										db_die("<tr class=\"errorrow\"><td>Unable to connect keyword with article in database.");
								}
							}
						}
						for($i=0;$i<count($affectedOS);$i++)
						{
							$sql="insert into ".$tableprefix."_kb_os (articlenr, osnr) values ($articlenr, ".$affectedOS[$i].")";
							if(!$result = faqe_db_query($sql, $db))
								db_die("<tr class=\"errorrow\"><td>Unable to connect os with article in database.");
						}
						for($i=0;$i<count($affectedVersions);$i++)
						{
							$sql="insert into ".$tableprefix."_kb_prog_version (articlenr, progversion) values ($articlenr, ".$affectedVersions[$i].")";
							if(!$result = faqe_db_query($sql, $db))
								db_die("<tr class=\"errorrow\"><td>Unable to connect program version with article in database.");
						}
						$numadded++;
					}
				}
			}
			fclose($kbfile);
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$numadded $l_kbsadded";
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
<td><input class="faqeinput" type="file" name="kblist"></td></tr>
<tr class="actionrow"><td align="center" colspan="2">
<input type="hidden" name="mode" value="upload">
<input class="faqebutton" type="submit" value="<?php echo $l_ok?>"></td></tr>
</form>
</table></td></tr></table>
<?php
}
echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("kb.php?$langvar=$act_lang")."\">$l_articlelist</a></div>";
include('./trailer.php');
?>