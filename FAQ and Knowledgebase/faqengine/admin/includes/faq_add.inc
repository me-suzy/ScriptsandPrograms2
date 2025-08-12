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
		// Add new FAQ to database
		$errors=0;
		if(!$heading)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
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
		if(($category<0) && ($subcategory<=0))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
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
				$question = stripslashes($question);
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
					$answer = htmlspecialchars($answer);
					$answer = undo_html_ampersand($answer);
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
			$actdate = date("Y-m-d");
			$sql = "UPDATE ".$tableprefix."_category SET numfaqs = numfaqs + 1 WHERE (catnr = $category)";
			@faqe_db_query($sql, $db);
			$sql = "select max(displaypos) as newdisplaypos from ".$tableprefix."_data where category=$category";
			if(!$result = faqe_db_query($sql, $db))
				db_die("<tr class=\"errorrow\"><td align=\"center\">Unable to get displaypos for FAQ from DB.");
			if($myrow=faqe_db_fetch_array($result))
				$displaypos=$myrow["newdisplaypos"]+1;
			else
				$displaypos=1;
			$sql = "INSERT INTO ".$tableprefix."_data (heading, category, questiontext, answertext, editor, editdate, displaypos, subcategory) ";
			$sql .="VALUES ('$heading', $category, '$question', '$answer', '$editor', '$actdate', $displaypos, $subcategory)";
			if(!$result = faqe_db_query($sql, $db))
				db_die("<tr class=\"errorrow\"><td align=\"center\">Unable to add FAQ to database.");
			$faqnr=faqe_db_insert_id($db);
			if(isset($new_files))
			{
				while(list($null, $actattach) = each($_POST["new_files"]))
				{
					$sql="insert into ".$tableprefix."_faq_attachs (attachnr,faqnr) values ('$actattach','$faqnr')";
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
							db_die("<tr class=\"errorrow\"><td align=\"center\">Unable to check keyword in database.");
						if(!$myrow=faqe_db_fetch_array($result))
						{
							$sql = "insert into ".$tableprefix."_keywords (keyword) values ('$keyword')";
							if(!$result = faqe_db_query($sql, $db))
								db_die("<tr class=\"errorrow\"><td align=\"center\">Unable to add keyword to database.");
							$kwnr=faqe_db_insert_id($db);
						}
						else
							$kwnr=$myrow["keywordnr"];
						$sql = "insert into ".$tableprefix."_faq_keywords (faqnr, keywordnr) values ($faqnr, $kwnr)";
						if(!$result = faqe_db_query($sql, $db))
							db_die("<tr class=\"errorrow\"><td align=\"center\">Unable to connect keyword with faq in database.");
					}
				}
			}
			if(isset($oss))
			{
	    			while(list($null, $os) = each($_POST["oss"])) {
	    				$osquery = "INSERT INTO ".$tableprefix."_faq_os (faqnr, osnr) VALUES ($faqnr,$os)";
	    			   	if(!faqe_db_query($osquery, $db))
						    db_die("<tr class=\"errorrow\"><td align=\"center\">Unable to add related OS in DB.");
	    			}
			}
			if(isset($fpvs))
			{
	    			while(list($null, $fpv) = each($_POST["fpvs"])) {
	    				$versionquery = "INSERT INTO ".$tableprefix."_faq_prog_version (faqnr, progversion) VALUES ($faqnr,$fpv)";
	    			   	if(!faqe_db_query($versionquery, $db))
						    db_die("<tr class=\"errorrow\"><td align=\"center\">Unable to add related version in DB.");
	    			}
			}
			if(isset($faqs))
			{
	    			while(list($null, $faq) = each($_POST["faqs"])) {
	    				list($reffaqnr,$destlang)=explode("|",$faq);
	    				$faq_query = "INSERT INTO ".$tableprefix."_faq_ref (srcfaqnr, destfaqnr, language) VALUES ($faqnr,$reffaqnr,'$destlang')";
	    			   	if(!faqe_db_query($faq_query, $db))
						db_die("<tr class=\"errorrow\"><td align=\"center\">Unable to add referenced FAQ in DB (1).");
					$faq_query = "delete from ".$tableprefix."_faq_ref where srcfaqnr=$reffaqnr and language='$srclang'";
	    			   	if(!faqe_db_query($faq_query, $db))
						db_die("<tr class=\"errorrow\"><td align=\"center\">Unable to add referenced FAQ in DB (2).");
	    				$faq_query = "INSERT INTO ".$tableprefix."_faq_ref (srcfaqnr, destfaqnr, language) VALUES ($reffaqnr,$faqnr,'$srclang')";
	    			   	if(!faqe_db_query($faq_query, $db))
						db_die("<tr class=\"errorrow\"><td align=\"center\">Unable to add referenced FAQ in DB (3).");
	    			}
			}
			if(isset($relfaqs))
			{
	    			while(list($null, $relfaq) = each($_POST["relfaqs"])) {
	    				$faq_query = "INSERT INTO ".$tableprefix."_related_faq (srcfaq, destfaq) VALUES ($faqnr,$relfaq)";
	    			   	if(!faqe_db_query($faq_query, $db))
						db_die("<tr class=\"errorrow\"><td align=\"center\">Unable to add related FAQ in DB (1).");
	    				$faq_query = "INSERT INTO ".$tableprefix."_related_faq (destfaq, srcfaq) VALUES ($faqnr,$relfaq)";
	    			   	if(!faqe_db_query($faq_query, $db))
						db_die("<tr class=\"errorrow\"><td align=\"center\">Unable to add related FAQ in DB (2).");
	    			}
			}

			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_faqadded";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?mode=new&$langvar=$act_lang")."\">$l_newfaq</a></div>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_faqlist</a></div>";
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
?>