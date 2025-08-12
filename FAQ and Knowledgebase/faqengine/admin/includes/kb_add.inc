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
		// Add new kb article to database
		$errors=0;
		if(!$heading)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noheading</td></tr>";
			$errors=1;
		}
		if($programm<0)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noprogramm</td></tr>";
			$errors=1;
		}
		if(!$article)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noarticletext</td></tr>";
			$errors=1;
		}
		if(!$keywords)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nokeywords</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
				if($subcategory>0)
				{
					$sql="select * from ".$tableprefix."_kb_subcat where catnr=$subcategory";
					if(!$result = faqe_db_query($sql, $db))
						die("<tr class=\"errorrow\"><td>Could not connect to the database (3).");
					if($myrow=faqe_db_fetch_array($result))
						$category=$myrow["category"];
				}
				else
					$subcategory=0;
				$heading=do_htmlentities($heading);
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
					$article = stripslashes($article);
					$article = do_htmlentities($article);
					if(!isset($no_br))
					{
						$article = str_replace("\n", "<BR>", $article);
						$article = str_replace("\r", "", $article);
					}
					$article = addslashes($article);
					$article = addslashes($article);
				}
				$actdate = date("Y-m-d");
				$sql = "select max(displaypos) as newdisplaypos from ".$tableprefix."_kb_articles";
				if(!$result = faqe_db_query($sql, $db))
				    die("tr class=\"errorrow\"><td>Unable to add knowledge base article to database.");
				if($myrow=faqe_db_fetch_array($result))
					$displaypos=$myrow["newdisplaypos"]+1;
				else
					$displaypos=1;
				$sql = "INSERT INTO ".$tableprefix."_kb_articles (heading, programm, article, editor, lastedited, category, displaypos, subcategory) ";
				$sql .="VALUES ('$heading', $programm, '$article', '$editor', '$actdate', $category, $displaypos, $subcategory)";
				if(!$result = faqe_db_query($sql, $db))
				    die("tr class=\"errorrow\"><td>Unable to add knowledge base article to database.");
				$articlenr=faqe_db_insert_id($db);
				if(isset($new_files))
				{
					while(list($null, $actattach) = each($_POST["new_files"]))
					{
						$sql="insert into ".$tableprefix."_kb_attachs (attachnr,articlenr) values ('$actattach','$articlenr')";
						@faqe_db_query($sql, $db);
					}
				}
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
							    die("tr class=\"errorrow\"><td>Unable to check keyword in database.");
							if(!$myrow=faqe_db_fetch_array($result))
							{
								$sql = "insert into ".$tableprefix."_keywords (keyword) values ('$keyword')";
								if(!$result = faqe_db_query($sql, $db))
								    die("tr class=\"errorrow\"><td>Unable to add keyword to database.");
								$kwnr=faqe_db_insert_id($db);
							}
							else
								$kwnr=$myrow["keywordnr"];
							$sql = "insert into ".$tableprefix."_kb_keywords (articlenr, keywordnr) values ($articlenr, $kwnr)";
							if(!$result = faqe_db_query($sql, $db))
							    die("tr class=\"errorrow\"><td>Unable to connect keyword with article in database.");
						}
					}
				}
				if(isset($kbpvs))
				{
					while(list($null, $kbpv) = each($_POST["kbpvs"]))
					{
						$sql = "insert into ".$tableprefix."_kb_prog_version (articlenr, progversion) values ($articlenr, $kbpv)";
	    			   		if(!$result=faqe_db_query($sql, $db))
						    die("<tr class=\"errorrow\"><td>Unable to add version relation to database.");
					}
				}
				if(isset($os))
				{
					while(list($null, $actos) = each($_POST["os"]))
					{
						$sql = "insert into ".$tableprefix."_kb_os (articlenr, osnr) values ($articlenr, $actos)";
	    			   		if(!$result=faqe_db_query($sql, $db))
						    die("<tr class=\"errorrow\"><td>Unable to add os relation to database.");
					}
				}
				echo "<tr class=\"displayrow\" align=\"center\"><td>";
				echo "$l_kbarticleadded";
				echo "</td></tr></table></td></tr></table>";
				echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?mode=new&$langvar=$act_lang")."\">$l_newkbarticle</a></div>";
				echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_articlelist</a></div>";

		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
?>