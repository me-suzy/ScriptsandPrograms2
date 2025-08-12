<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
		if($admin_rights < 2)
		{
			echo "<tr bgcolor=\"#cccccc\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		echo "<div align=\"center\">";
		$errors=0;
		if($subcategory>0)
		{
			$sql="select * from ".$tableprefix."_subcategory where catnr=$subcategory";
			if(!$result = faqe_db_query($sql, $db))
				db_die("<tr class=\"errorrow\"><td>Unable to get subcategory from DB.");
			if($myrow=faqe_db_fetch_array($result))
				$category=$myrow["category"];
		}
		if(($category<0) && ($subcategory==0))
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nocategory</td></tr>";
			$errors=1;
		}
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
		if($category<0)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nocategory</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
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
			if($question)
			{
				if(isset($disablehtml))
				{
					$question=htmlspecialchars($question);
					$question=undo_html_ampersand($question);
				}
				if($urlautoencode==1)
					$question = make_clickable($question);
				if($enablespcode==1)
					$question = bbencode($question);
				$question=stripslashes($question);
				$question = do_htmlentities($question);
				if(!isset($ques_no_br))
				{
					$question = str_replace("\n", "<BR>", $question);
					$question = str_replace("\r", "", $question);
				}
				$question = addslashes($question);
				$question = addslashes($question);
			}
			if($answer)
			{
				if(isset($disablehtml))
				{
					$answer=htmlspecialchars($answer);
					$answer=undo_html_ampersand($answer);
				}
				if($urlautoencode==1)
					$answer = make_clickable($answer);
				if($enablespcode==1)
					$answer = bbencode($answer);
				$answer = stripslashes($answer);
				$answer = do_htmlentities($answer);
				if(!isset($ans_no_br))
				{
					$answer = str_replace("\n", "<BR>", $answer);
					$answer = str_replace("\r", "", $answer);
				}
				$answer = addslashes($answer);
				$answer = addslashes($answer);
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
					$sql="delete from ".$tableprefix."_faq_attachs where entrynr=".$actattach;
					@faqe_db_query($sql, $db);
				}
			}
			if(isset($new_files))
			{
				while(list($null, $actattach) = each($_POST["new_files"]))
				{
					$sql="insert into ".$tableprefix."_faq_attachs (attachnr,faqnr) values ('$actattach','$input_faqnr')";
					@faqe_db_query($sql, $db);
				}
			}
			if($oldcat != $category)
			{
				$sql = "UPDATE ".$tableprefix."_category SET numfaqs = numfaqs - 1 WHERE (catnr = $oldcat)";
				@faqe_db_query($sql, $db);
				$sql = "UPDATE ".$tableprefix."_category SET numfaqs = numfaqs + 1 WHERE (catnr = $category)";
				@faqe_db_query($sql, $db);
			}
			$sql = "UPDATE ".$tableprefix."_data SET subcategory=$subcategory, category=$category, heading='$heading', ";
			$sql .="editor='$editor', questiontext='$question', answertext='$answer',";
			$sql .="editdate = '$actdate', views=$newviews, ratingcount=$newratingcount, rating=$newrating WHERE (faqnr = $input_faqnr)";
			if(!$result = faqe_db_query($sql, $db))
				db_die("<tr class=\"errorrow\" align=\"center\"><td>Unable to update FAQ in DB.");
			if($keywords)
			{
				$enteredkeywords = split("[|]",$keywords);
				$sql = "delete from ".$tableprefix."_faq_keywords where faqnr=$input_faqnr";
				if(!$result = faqe_db_query($sql, $db))
					db_die("tr class=\"errorrow\"><td>Unable to delete keywords from DB.");
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
						$sql = "insert into ".$tableprefix."_faq_keywords (faqnr, keywordnr) values ($input_faqnr, $kwnr)";
						if(!$result = faqe_db_query($sql, $db))
							db_die("tr class=\"errorrow\"><td>Unable to connect keyword with FAQ in database.");
						purge_keywords($db);
					}
				}
			}
			if(isset($rem_oss))
			{
				while(list($null, $os) = each($_POST["rem_oss"]))
				{
					$rem_query = "DELETE FROM ".$tableprefix."_faq_os WHERE faqnr = '$input_faqnr' AND osnr = '$os'";
	       				if(!faqe_db_query($rem_query,$db))
						    db_die("<tr class=\"errorrow\"><td>Unable to delete OS for FAQ from DB.");
				}
			
			}
			if(isset($oss))
			{
				while(list($null, $os) = each($_POST["oss"]))
				{
					$osquery = "insert into ".$tableprefix."_faq_os (faqnr, osnr) values ('$input_faqnr','$os')";
	       				if(!faqe_db_query($osquery,$db))
						    db_die("<tr class=\"errorrow\"><td>Unable to add OS for FAQ in DB.");
				}
			
			}
			if(isset($rem_fpvs))
			{
				while(list($null, $fpv) = each($_POST["rem_fpvs"]))
				{
					$rem_query = "DELETE FROM ".$tableprefix."_faq_prog_version WHERE faqnr = '$input_faqnr' AND progversion = '$fpv'";
	       				if(!faqe_db_query($rem_query,$db))
						    db_die("<tr class=\"errorrow\"><td>Unable to delete program version for FAQ from DB.");
				}
			
			}
			if(isset($fpvs))
			{
				while(list($null, $fpv) = each($_POST["fpvs"]))
				{
					$versionquery = "insert into ".$tableprefix."_faq_prog_version (faqnr, progversion) values ('$input_faqnr','$fpv')";
	       				if(!faqe_db_query($versionquery,$db))
						    db_die("<tr class=\"errorrow\"><td>Unable to add program version for FAQ in DB.");
				}
			
			}
			if(isset($rem_relfaqs))
			{
				while(list($null, $faq) = each($_POST["rem_relfaqs"]))
				{
					$rem_query = "DELETE FROM ".$tableprefix."_related_faq WHERE srcfaq = '$input_faqnr' AND destfaq = '$faq'";
	       				if(!faqe_db_query($rem_query,$db))
						db_die("<tr class=\"errorrow\"><td>Unable to delete related FAQ from DB (1).");
					$rem_query = "DELETE FROM ".$tableprefix."_related_faq WHERE destfaq = '$input_faqnr' AND srcfaq = '$faq'";
	       				if(!faqe_db_query($rem_query,$db))
						db_die("<tr class=\"errorrow\"><td>Unable to delete releated FAQ from DB (2).");
				}
			}
			if(isset($relfaqs))
			{
	    			while(list($null, $relfaq) = each($_POST["relfaqs"])) {
	    				$faq_query = "INSERT INTO ".$tableprefix."_related_faq (srcfaq, destfaq) VALUES ($input_faqnr,$relfaq)";
	    		   		if(!faqe_db_query($faq_query, $db))
						    db_die("<tr class=\"errorrow\"><td>Unable to add related FAQ in DB (1).");
	    				$faq_query = "INSERT INTO ".$tableprefix."_related_faq (destfaq, srcfaq) VALUES ($input_faqnr,$relfaq)";
	    		   		if(!faqe_db_query($faq_query, $db))
						    db_die("<tr class=\"errorrow\"><td>Unable to add related FAQ in DB (2).");
	    			}
			}
			if(isset($rem_faqs))
			{
				while(list($null, $faq) = each($_POST["rem_faqs"]))
				{
					$rem_query = "DELETE FROM ".$tableprefix."_faq_ref WHERE srcfaqnr = '$input_faqnr' AND destfaqnr = '$faq'";
					if(!faqe_db_query($rem_query,$db))
						db_die("<tr class=\"errorrow\"><td>Unable to remove FAQ reference from DB (1).");
				}
			}
			if(isset($faqs))
			{
	    			while(list($null, $faq) = each($_POST["faqs"])) {
	    				list($reffaqnr,$destlang)=explode("|",$faq);
	    				$faq_query = "delete from ".$tableprefix."_faq_ref where srcfaqnr=$input_faqnr and language='$destlang'";
	    			   	if(!faqe_db_query($faq_query, $db))
						db_die("<tr class=\"errorrow\"><td>Unable to remove FAQ reference from DB (2).");
	    				$faq_query = "INSERT INTO ".$tableprefix."_faq_ref (srcfaqnr, destfaqnr, language) VALUES ($input_faqnr,$reffaqnr,'$destlang')";
	    			   	if(!faqe_db_query($faq_query, $db))
						db_die("<tr class=\"errorrow\"><td>Unable to insert FAQ reference in DB.");
	    			}
			}
			$sql="update ".$tableprefix."_data set heading='$heading', editdate = '$actdate' where linkedfaq=$input_faqnr";
			if(!$result = faqe_db_query($sql, $db))
				db_die("<tr class=\"errorrow\" align=\"center\"><td>Unable to update FAQ in DB.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_faqupdated";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_faqlist</a></div>";
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
?>