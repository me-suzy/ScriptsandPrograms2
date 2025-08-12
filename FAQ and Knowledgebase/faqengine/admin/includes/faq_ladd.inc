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
		$errors=0;
		if(($category<0) && ($subcategory<=0))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nocategory</td></tr>";
			$errors=1;
		}
		if(!isset($subcategory))
			$subcategory=0;
		if($errors==0)
		{
			if($subcategory>0)
			{
				$sql="select * from ".$tableprefix."_subcategory where catnr=$subcategory";
				if(!$result = faqe_db_query($sql, $db))
					db_die("<tr class=\"errorrow\"><td align=\"center\">Unable to connect to database.");
				if($myrow=faqe_db_fetch_array($result))
					$category=$myrow["category"];
			}
			$tmpsql = "select heading from ".$tableprefix."_data where faqnr=$linkedfaq";
			if(!$tmpresult = faqe_db_query($tmpsql, $db))
				db_die("<tr class=\"errorrow\"><td align=\"center\">Unable to add FAQ to database.");
			if($tmprow=faqe_db_fetch_array($tmpresult))
				$origheading=$tmprow["heading"];
			else
				$origheading="";
			$actdate = date("Y-m-d");
			$sql = "UPDATE ".$tableprefix."_category SET numfaqs = numfaqs + 1 WHERE (catnr = $category)";
			@faqe_db_query($sql, $db);
			$sql = "select max(displaypos) as newdisplaypos from ".$tableprefix."_data where category=$category";
			if(!$result = faqe_db_query($sql, $db))
				db_die("<tr class=\"errorrow\"><td align=\"center\">Unable to add FAQ to database.");
			if($myrow=faqe_db_fetch_array($result))
				$displaypos=$myrow["newdisplaypos"]+1;
			else
				$displaypos=1;
			$sql = "INSERT INTO ".$tableprefix."_data (heading, category, editdate, displaypos, subcategory, linkedfaq) ";
			$sql .="VALUES ('$origheading', $category, '$actdate', $displaypos, $subcategory, $linkedfaq)";
			if(!$result = faqe_db_query($sql, $db))
				db_die("<tr class=\"errorrow\"><td align=\"center\">Unable to add FAQ to database.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_faqlinkadded";
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