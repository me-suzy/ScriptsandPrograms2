<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
if($admin_rights>1)
{
?>
<tr class="actionrow"><td colspan="6" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_newfaq?></a></td></tr>
<?php
	echo "<tr class=\"actionrow\"><td colspan=\"6\" align=\"center\">";
	$sorturl="$act_script_url?$langvar=$act_lang&sorting=-1&filterprog=$filterprog";
	if($admstorefaqfilters==1)
		$sorturl.="&storefaqfilter=1";
	echo "<a href=\"".do_url_session($sorturl)."\">";
	echo "$l_normalsorting</a></td></tr>";
?>
</table></td></tr></table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
}
$tempsql = "select * from ".$tableprefix."_programm where (prognr=$filterprog)";
if(!$tempresult = faqe_db_query($tempsql, $db))
    die("Could not connect to the database.");
if (!$temprow = faqe_db_fetch_array($tempresult))
{
	$proglang=$l_none;
	$progname=$l_none;
}
else
{
	$proglang=$temprow["language"];
	$progname=display_encoded($temprow["programmname"]);
}
$sql = "select * from ".$tableprefix."_category where (programm=$filterprog) order by displaypos asc";
if(!$result = faqe_db_query($sql, $db))
    die("<tr class=\"errorrow\"><td>Could not connect to the database.".faqe_db_error());
if (!$myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
	$baseurl="$act_script_url?$langvar=$act_lang&filterprog=$filterprog";
	if($admstorefaqfilters==1)
		$baseurl.="&storefaqfilter=1";
	echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\" width=\"5%\">";
	if($sorting==101)
		$sorturl="&sorting=102";
	else
		$sorturl="&sorting=101";
	echo "<a class=\"rowheadings\" href=\"".do_url_session($baseurl.$sorturl)."\">";
	echo "<b>#</b></a>";
	if($sorting==101)
		echo "<img src=\"gfx/down2.gif\" border=\"0\" align=\"absmiddle\">";
	if($sorting==102)
		echo "<img src=\"gfx/up2.gif\" border=\"0\" align=\"absmiddle\">";
	echo "</td>";
	echo "<td align=\"center\" width=\"30%\">";
	if($sorting==103)
		$sorturl="&sorting=104";
	else
		$sorturl="&sorting=103";
	echo "<a class=\"rowheadings\" href=\"".do_url_session($baseurl.$sorturl)."\">";
	echo "<b>$l_heading</b></a>";
	if($sorting==103)
		echo "<img src=\"gfx/down2.gif\" border=\"0\" align=\"absmiddle\">";
	if($sorting==104)
		echo "<img src=\"gfx/up2.gif\" border=\"0\" align=\"absmiddle\">";
	echo "</td>";
	echo "<td align=\"center\" width=\"10%\">";
	echo "<b>$l_category</b></td>";
	echo "<td align=\"center\" width=\"10%\"><b>$l_programm</b></td>";
	echo "<td align=\"center\" width=\"10%\"><b>$l_language</b></td>";
	echo "<td align=\"center\" width=\"10%\">";
	if($sorting==105)
		$sorturl="&sorting=106";
	else
		$sorturl="&sorting=105";
	echo "<a class=\"rowheadings\" href=\"".do_url_session($baseurl.$sorturl)."\">";
	echo "<b>$l_views</b></a>";
	if($sorting==105)
		echo "<img src=\"gfx/down2.gif\" border=\"0\" align=\"absmiddle\">";
	if($sorting==106)
		echo "<img src=\"gfx/up2.gif\" border=\"0\" align=\"absmiddle\">";
	echo "</td><td>";
	if($sorting!=100)
	{
		$sorturl="$act_script_url?$langvar=$act_lang&sorting=100&filterprog=$filterprog";
		if($admstorefaqfilters==1)
			$sorturl.="&storefaqfilter=1";
		echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
		echo "<b>$l_sortbydisplaypos</b></a>";
	}
	else
		echo "<span class=\"activesorting\">$l_sortedbydisplaypos</span>";
	echo "</td></tr>";
	do {
		$catname=display_encoded($myrow["categoryname"]);
		echo "<tr class=\"listheading0\"><td colspan=\"7\" align=\"left\">";
		echo $catname;
		echo "</td></tr>";
		$sql2 = "select * from ".$tableprefix."_data where (category=".$myrow["catnr"].") ";
		switch($sorting)
		{
			case 101:
				$sql2.="order by faqnr asc";
				break;
			case 102:
				$sql2.="order by faqnr desc";
				break;
			case 103:
				$sql2.="order by heading asc";
				break;
			case 104:
				$sql2.="order by heading desc";
				break;
			case 105:
				$sql2.="order by views asc";
				break;
			case 106:
				$sql2.="order by views desc";
				break;
			default:
				$sql2.="order by displaypos asc";
				break;
		}
		if(!$result2 = faqe_db_query($sql2, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.".faqe_db_error());
		if (!$myrow2 = faqe_db_fetch_array($result2))
		{
			echo "<tr class=\"displayrow\"><td colspan=\"7\" align=\"center\">";
			echo $l_noentries;
			echo "</td></tr>";
		}
		else
		{
			do {
				if($admin_rights > 1)
				{
					$modsql="select * from ".$tableprefix."_category_admins where (catnr=".$myrow2["category"].") and (usernr=".$userdata["usernr"].")";
					if(!$modresult = faqe_db_query($modsql, $db))
					    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
					if(faqe_db_num_rows($modresult,$db)>0)
						$ismod=1;
					else
						$ismod=0;
				}
				if(($admin_rights == 2) && ($ismod==0) && ($hideunassigned==1))
					continue;
				$act_id=$myrow2["faqnr"];
				if($myrow2["editdate"]>$userdata["lastlogin"])
					echo "<tr class=\"displayrownew\">";
				else
					echo "<tr class=\"displayrow\">";
				if($myrow2["subcategory"]>0)
				{
					$subcatsql="select * from ".$tableprefix."_subcategory where catnr=".$myrow2["subcategory"];
					if(!$subcatresult = faqe_db_query($subcatsql, $db))
					    die("Unable to connect to database.");
					if($subcatrow=faqe_db_fetch_array($subcatresult))
						$subcatname=display_encoded($subcatrow["categoryname"]);
				}
				else
					$subcatname="";
				echo "<td align=\"center\">".$myrow2["faqnr"]."</td>";
				echo "<td>".undo_html_ampersand(stripslashes($myrow2["heading"]));
				if($myrow2["linkedfaq"]!=0)
					echo " <img src=\"gfx/link.gif\" border=\"0\" align=\"absmiddle\">";
				echo "</td>";
				if($subcatname)
					echo "<td align=\"center\">$catname : $subcatname</td>";
				else
					echo "<td align=\"center\">$catname</td>";
				echo "<td align=\"center\">$progname</td>";
				echo "<td align=\"center\">$proglang</td>";
				echo "<td align=\"right\">";
				if($myrow2["linkedfaq"]==0)
					echo $myrow2["views"];
				else
					echo "---";
				echo "</td>";
				echo "<td>";
				if($myrow2["linkedfaq"]==0)
				{
					echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=display&input_faqnr=$act_id&$langvar=$act_lang")."\">";
					echo "<img src=\"gfx/view.gif\" border=\"0\" title=\"$l_display\" alt=\"$l_display\"></a>&nbsp; ";
				}
				if($admin_rights > 1)
				{
					if(($ismod==1) || ($admin_rights > 2))
					{
						$dellink=do_url_session("$act_script_url?mode=delete&input_faqnr=$act_id&$langvar=$act_lang&oldcat=".$myrow2["category"]);
						if($admdelconfirm==2)
							echo "<a class=\"listlink2\" href=\"javascript:confirmDel('FAQ #$act_id','$dellink')\">";
						else
							echo "<a class=\"listlink2\" href=\"$dellink\">";
						echo "<img src=\"gfx/delete.gif\" border=\"0\" alt=\"$l_delete\" title=\"$l_delete\"></a>&nbsp; ";
						if($myrow2["linkedfaq"]==0)
						{
							echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=edit&input_faqnr=$act_id&$langvar=$act_lang&oldcat=".$myrow["catnr"])."\">";
							echo "<img src=\"gfx/edit.gif\" border=\"0\" alt=\"$l_edit\" title=\"$l_edit\"></a>&nbsp; ";
							if(($admin_rights > 2) && ($myrow2["views"]>0))
							{
								echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=resetviews&input_faqnr=$act_id&$langvar=$act_lang")."\">";
								echo "<img src=\"gfx/clear.gif\" border=\"0\" alt=\"$l_resetviews\" title=\"$l_resetviews\"></a>&nbsp; ";
							}
							$attachsql="select * from ".$tableprefix."_faq_attachs where faqnr=$act_id";
							if(!$attachresult = faqe_db_query($attachsql, $db))
								die("<tr class=\"errorrow\"><td>Could not connect to the database.");
							if(faqe_db_num_rows($attachresult)>0)
							{
								$dellink=do_url_session("$act_script_url?mode=delattach&input_faqnr=$act_id&$langvar=$act_lang");
								if($admdelconfirm==2)
									echo "<a class=\"listlink2\" href=\"javascript:confirmDel('".undo_htmlentities($l_attachement)." - FAQ #$act_id','$dellink')\">";
								else
									echo "<a class=\"listlink2\" href=\"$dellink\">";
								echo "<img src=\"gfx/delattach.gif\" border=\"0\" title=\"$l_delattach\" alt=\"$l_delattach\"></a>&nbsp; ";
							}
							$ratingsql="select * from ".$tableprefix."_ratings where faqnr=$act_id";
							if(!$ratingresult = faqe_db_query($ratingsql, $db))
							    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
							if(faqe_db_num_rows($ratingresult)>0)
							{
								echo "<a class=\"listlink2\" href=\"".do_url_session("ratingcomments.php?input_faqnr=$act_id&$langvar=$act_lang")."\">";
								echo "<img src=\"gfx/comment.gif\" border=\"0\" title=\"$l_ratingcomments\" alt=\"$l_ratingcomments\"></a>&nbsp; ";
							}
							echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=mklink&input_faqnr=$act_id&$langvar=$act_lang")."\">";
							echo "<img src=\"gfx/linktarget.gif\" border=\"0\" alt=\"$l_mkfaqlink\" title=\"$l_mkfaqlink\"></a> ";
							echo "<a class=\"listlink2\" href=\"".do_url_session("transfer2kb.php?input_faqnr=$act_id&$langvar=$act_lang")."\">";
							echo "<img src=\"gfx/kbcopy.gif\" border=\"0\" alt=\"$l_transfer2kb\" title=\"$l_transfer2kb\"></a>";
						}
					}
				}
				echo "</td></tr>";
   			} while($myrow2 = faqe_db_fetch_array($result2));
   		}
   	}while($myrow = faqe_db_fetch_array($result));
   echo "<tr><td class=\"inforow\" colspan=\"7\" align=\"left\"><span class=\"remark\"><img src=\"gfx/link.gif\" border=\"0\" align=\"absmiddle\"> = $l_linkedfaq</span></td></tr>";
   echo "</table></tr></td></table>";
}
if($admin_rights > 1)
{
	include("./includes/faq_filterboxes.inc");
?>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_newfaq?></a></div>
<?php
}
?>