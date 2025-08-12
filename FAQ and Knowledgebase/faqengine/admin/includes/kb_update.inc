<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		echo "<div align=\"center\">";
		$errors=0;
		if(!$heading)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_noheading</td></tr>";
			$errors=1;
		}
		else
		{
			$heading=stripslashes($heading);
			$heading=strip_tags($heading);
			$heading=do_htmlentities($heading);
			$heading=addslashes($heading);
		}
		if(!$article)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_noarticletext</td></tr>";
			$errors=1;
		}
		if(!$keywords)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nokeywords</td></tr>";
			$errors=1;
		}
		if($programm<0)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_noprogramm</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
				if($subcategory>0)
				{
					$sql="select * from ".$tableprefix."_kb_subcat where catnr=$subcategory";
					if(!$result = faqe_db_query($sql, $db))
						db_die("<tr class=\"errorrow\"><td>Unable to get subcategory from DB.");
					if($myrow=faqe_db_fetch_array($result))
						$category=$myrow["category"];
				}
				else
					$subcategory=0;
				$editor=$userdata["username"];
				if($editor==-1)
					$editor="unknown";
				else
					$editor=addslashes($editor);
				if(!isset($local_urlautoencode))
					$urlautoencode=0;
				else
					$urlautoencode=1;
				if(!isset($local_enablespcode))
					$enablespcode=0;
				else
					$enablespcode=1;
				if($article)
				{
					if(isset($disablehtml))
					{
						$article=htmlspecialchars($article);
						$article=undo_html_ampersand($article);
					}
					if($urlautoencode==1)
						$article = make_clickable($article);
					if($enablespcode==1)
						$article = bbencode($article);
					$article=stripslashes($article);
					$article = do_htmlentities($article);
					if(!isset($no_br))
					{
						$article = str_replace("\n", "<BR>", $article);
						$article = str_replace("\r", "", $article);
					}
					$article = addslashes($article);
					$article = addslashes($article);
				}
				if(isset($keepviews))
					$newviews=$oldviews;
				else
					$newviews=0;
				if(isset($keepratings))
				{
					$newrating=$oldrating;
					$newratingcount=$oldratingcount;
				}
				else
				{
					$newrating=0;
					$newratingcount=0;
				}
				if(isset($keepeditdate))
				{
					$actdate=$oldeditdate;
				}
				else
				{
					if($input_editdate)
						$actdate=$input_editdate;
					else
						$actdate = date("Y-m-d");
				}
				if(isset($delattach))
				{
					while(list($null, $actattach) = each($_POST["delattach"]))
					{
						$sql="delete from ".$tableprefix."_kb_attachs where entrynr=".$actattach;
						@faqe_db_query($sql, $db);
					}
				}
				if(isset($new_files))
				{
					while(list($null, $actattach) = each($_POST["new_files"]))
					{
						$sql="insert into ".$tableprefix."_kb_attachs (attachnr,articlenr) values ('$actattach','$input_articlenr')";
						@faqe_db_query($sql, $db);
					}
				}
				$sql ="UPDATE ".$tableprefix."_kb_articles SET programm=$programm, heading='$heading', ";
				$sql.="editor='$editor', article='$article', category=$category, subcategory=$subcategory, ";
				$sql.="lastedited = '$actdate', ratingcount=$newratingcount, rating=$newrating, views=$newviews ";
				$sql.="WHERE (articlenr = $input_articlenr)";
				if(!$result = faqe_db_query($sql, $db))
					db_die("<tr class=\"errorrow\" align=\"center\"><td>Unable to update article in DB.");
				if(isset($rem_kbpvs))
				{
					while(list($null, $kbpv) = each($_POST["rem_kbpvs"]))
					{
						$rem_query = "DELETE FROM ".$tableprefix."_kb_prog_version WHERE articlenr = '$input_articlenr' AND progversion = '$kbpv'";
	       					if(!faqe_db_query($rem_query,$db))
							db_die("<tr class=\"errorrow\"><td>Unable to delete affected version from DB.");
					}
			
				}	
				if(isset($kbpvs))
				{
					while(list($null, $kbpv) = each($_POST["kbpvs"]))
					{
						$versionquery = "insert into ".$tableprefix."_kb_prog_version (articlenr, progversion) values ('$input_articlenr','$kbpv')";
	       					if(!faqe_db_query($versionquery,$db))
							db_die("<tr class=\"errorrow\"><td>Unable to add affected version in DB.");
					}
			
				}
				if(isset($os))
				{
					while(list($null, $os) = each ($_POST["os"]))
					{
						$os_query = "INSERT INTO ".$tableprefix."_kb_os (osnr, articlenr) VALUES ('$os', '$input_articlenr')";
						if(!faqe_db_query($os_query, $db))
							db_die("<tr class=\"errorrow\"><td>Unable to add affected OS in DB.");
					}
				}
				if(isset($rem_os))
				{
					while(list($null, $os) = each($_POST["rem_os"]))
					{
						$rem_query = "DELETE FROM ".$tableprefix."_kb_os WHERE articlenr = '$input_articlenr' AND osnr='$os'";
						if(!faqe_db_query($rem_query,$db))
							db_die("<tr class=\"errorrow\"><td>Unable to remove affected OS from DB.");
					}
				}
				$rem_query = "DELETE FROM ".$tableprefix."_kb_keywords WHERE articlenr = '$input_articlenr'";
       				if(!faqe_db_query($rem_query,$db))
					db_die("<tr class=\"errorrow\"><td>Unable to delete keywords for article from DB (1).");
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
								db_die("tr class=\"errorrow\"><td>Unable to check keyword in database.");
							if(!$myrow=faqe_db_fetch_array($result))
							{
								$sql = "insert into ".$tableprefix."_keywords (keyword) values ('$keyword')";
								if(!$result = faqe_db_query($sql, $db))
									db_die("tr class=\"errorrow\"><td>Unable to add keyword to database.");
								$kwnr=faqe_db_insert_id($db);
							}
							else
								$kwnr=$myrow["keywordnr"];
							$sql = "insert into ".$tableprefix."_kb_keywords (articlenr, keywordnr) values ($input_articlenr, $kwnr)";
							if(!$result = faqe_db_query($sql, $db))
									db_die("tr class=\"errorrow\"><td>Unable to connect keyword with article in database.");
							purge_keywords($db);
						}
					}
				}
				echo "<tr class=\"displayrow\" align=\"center\"><td>";
				echo "$l_articleupdated";
				echo "</td></tr></table></td></tr></table>";
				echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_articlelist</a></div>";
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
?>