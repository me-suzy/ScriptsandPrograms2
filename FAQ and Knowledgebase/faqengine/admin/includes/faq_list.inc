<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	if(!isset($sorting) || ($sorting<0))
	{
		if($faqlistshortcuts==1)
			$sorting=21;
		else
			$sorting=31;
	}
	if($admin_rights>1)
	{
?>
<tr class="actionrow"><td colspan="6" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_newfaq?></a></td></tr>
<?php
if(isset($filterprog) && ($filterprog>=0))
{
	echo "<tr class=\"actionrow\"><td colspan=\"6\" align=\"center\">";
	$myurl="$act_script_url?$langvar=$act_lang&sorting=100&filterprog=$filterprog&filtercat=-1&filterlang=none";
	if($admstorefaqfilters==1)
		$myurl.="&storefaqfilter=1";
	echo "<a href=\"".do_url_session($myurl)."\">";
	echo "$l_specialsorting</a></td></tr>";
}
?>
</table></td></tr></table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	}
	if(($faqlistshortcuts==1) && ($sorting==1))
	{
		echo "<tr class=\"shortcutbar\"><td colspan=\"7\" align=\"center\">";
		for($i=65;$i<91;$i++)
			echo "<a class=\"shortcutbar\" href=\"#".chr($i)."\">".chr($i)."</a> ";
		echo "</td></tr>";
		$startchar="";
	}
// Display list of actual FAQ
	$sql = "select dat.* from ".$tableprefix."_data dat, ".$tableprefix."_category cat, ".$tableprefix."_programm prog where dat.category=cat.catnr ";
	if(isset($filterprog) && ($filterprog>0))
		$sql.=" and cat.programm=prog.prognr and prog.prognr=$filterprog ";
	if(isset($filterlang) && ($filterlang!="none"))
	{
		if(isset($filterprog) && ($filterprog>0))
			$sql.="and prog.language='$filterlang' ";
		else
			$sql.="and cat.programm=prog.prognr and prog.language='$filterlang' ";
	}
	if(isset($filtercat) && ($filtercat>0))
	{
		if(isset($filterprog) && ($filterprog>0))
			$sql.="and cat.catnr=$filtercat ";
		else if (isset($filterlang) && ($filterlang!="none"))
			$sql.="and cat.catnr=$filtercat ";
		else
			$sql.="and cat.catnr=$filtercat ";
		
	}
	$sql.="group by dat.faqnr ";
	switch($sorting)
	{
		case 12:
			$sql.="order by dat.faqnr desc";
			break;
		case 21:
			$sql.="order by dat.heading asc";
			break;
		case 22:
			$sql.="order by dat.heading desc";
			break;
		case 31:
			$sql.="order by cat.categoryname asc";
			break;
		case 32:
			$sql.="order by cat.categoryname desc";
			break;
		case 41:
			$sql.="order by dat.views asc";
			break;
		case 42:
			$sql.="order by dat.views desc";
			break;
		default:
			$sql.="order by dat.faqnr asc";
			break;
	}
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
	echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\" width=\"5%\">";
	$baseurl="$act_script_url?$langvar=$act_lang";
	if(isset($filterprog))
		$baseurl.="&filterprog=$filterprog";
	if(isset($filtercat))
		$baseurl.="&filtercat=$filtercat";
	if(isset($filterlang))
		$baseurl.="&filterlang=$filterlang";
	if($admstorefaqfilters==1)
		$baseurl.="&storefaqfilter=1";
	$maxsortcol=4;
	$sorturl=getSortURL($sorting, 1, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>#</b></a>";
	echo getSortMarker($sorting, 1, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\" width=\"30%\">";
	$sorturl=getSortURL($sorting, 2, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_heading</b></a>";
	echo getSortMarker($sorting, 2, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\" width=\"10%\">";
	$sorturl=getSortURL($sorting, 3, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_category</b></a>";
	echo getSortMarker($sorting, 3, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\" width=\"10%\"><b>$l_programm</b></td>";
	echo "<td align=\"center\" width=\"10%\"><b>$l_language</b></td>";
	echo "<td align=\"center\" width=\"10%\"><b>";
	$sorturl=getSortURL($sorting, 4, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "$l_views</b></a>";
	echo getSortMarker($sorting, 4, $maxsortcol);
	echo "</td><td>&nbsp;</td></tr>";
	do {
		if($admin_rights > 1)
		{
			$modsql="select * from ".$tableprefix."_category_admins where (catnr=".$myrow["category"].") and (usernr=".$userdata["usernr"].")";
			if(!$modresult = faqe_db_query($modsql, $db))
			    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if(faqe_db_num_rows($modresult,$db)>0)
				$ismod=1;
			else
				$ismod=0;
		}
		if(($admin_rights == 2) && ($ismod==0) && ($hideunassigned==1))
			continue;
		$tempsql = "select * from ".$tableprefix."_category where (catnr=".$myrow["category"].")";
		if(!$tempresult = faqe_db_query($tempsql, $db)) {
		    die("Could not connect to the database.");
		}
		if (!$temprow = faqe_db_fetch_array($tempresult))
		{
			$prognr=0;
			$catname=$l_undefined;
		}
		else
		{
			$prognr=$temprow["programm"];
			$catname=display_encoded($temprow["categoryname"]);
		}
		$tempsql = "select * from ".$tableprefix."_programm where (prognr=$prognr)";
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
		$act_id=$myrow["faqnr"];
		if($myrow["editdate"]>$userdata["lastlogin"])
			echo "<tr class=\"displayrownew\">";
		else
			echo "<tr class=\"displayrow\">";
		if(($faqlistshortcuts==1) && ($sorting==1))
		{
			$asc_heading=undo_htmlentities($myrow["heading"]);
			$firstchar=substr($asc_heading,0,1);
			$firstchar=strtoupper($firstchar);
			if(($firstchar!=$startchar) && (ord($firstchar)>64) && (ord($firstchar)<91))
			{
				$startchar=$firstchar;
				echo "<a name=\"#$startchar\"></a>";
			}
		}
		if($myrow["subcategory"]>0)
		{
			$subcatsql="select * from ".$tableprefix."_subcategory where catnr=".$myrow["subcategory"];
			if(!$subcatresult = faqe_db_query($subcatsql, $db))
			    die("Unable to connect to database.");
			if($subcatrow=faqe_db_fetch_array($subcatresult))
				$subcatname=display_encoded($subcatrow["categoryname"]);
		}
		else
			$subcatname="";
		echo "<td align=\"center\">".$myrow["faqnr"]."</td>";
		echo "<td>".undo_html_ampersand(stripslashes($myrow["heading"]));
		if($myrow["linkedfaq"]!=0)
			echo " <img src=\"gfx/link.gif\" border=\"0\" align=\"absmiddle\">";
		echo "</td>";
		if($subcatname)
			echo "<td align=\"center\">$catname : $subcatname</td>";
		else
			echo "<td align=\"center\">$catname</td>";
		echo "<td align=\"center\">$progname</td>";
		echo "<td align=\"center\">$proglang</td>";
		echo "<td align=\"right\">";
		if($myrow["linkedfaq"]==0)
			echo $myrow["views"];
		else
			echo "---";
		echo "</td>";
		echo "<td>";
		if($myrow["linkedfaq"]==0)
		{
			echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=display&input_faqnr=$act_id&$langvar=$act_lang")."\">";
			echo "<img src=\"gfx/view.gif\" border=\"0\" title=\"$l_display\" alt=\"$l_display\"></a>&nbsp; ";
		}
		if($admin_rights > 1)
		{
			if(($ismod==1) || ($admin_rights > 2))
			{
				$dellink=do_url_session("$act_script_url?mode=delete&input_faqnr=$act_id&$langvar=$act_lang&oldcat=".$myrow["category"]);
				if($admdelconfirm==2)
					echo "<a class=\"listlink2\" href=\"javascript:confirmDel('FAQ #$act_id','$dellink')\">";
				else
					echo "<a class=\"listlink2\" href=\"$dellink\">";
				echo "<img src=\"gfx/delete.gif\" border=\"0\" alt=\"$l_delete\" title=\"$l_delete\"></a>&nbsp; ";
				if($myrow["linkedfaq"]==0)
				{
					echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=edit&input_faqnr=$act_id&$langvar=$act_lang&oldcat=".$myrow["category"])."\">";
					echo "<img src=\"gfx/edit.gif\" border=\"0\" title=\"$l_edit\" alt=\"$l_edit\"></a>&nbsp; ";
					if(($admin_rights > 2) && ($myrow["views"]>0))
					{
						echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=resetviews&input_faqnr=$act_id&$langvar=$act_lang")."\">";
						echo "<img src=\"gfx/clear.gif\" border=\"0\" title=\"$l_resetviews\" alt=\"$l_resetviews\"></a>&nbsp; ";
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
					echo "<img src=\"gfx/linktarget.gif\" border=\"0\" title=\"$l_mkfaqlink\" alt=\"$l_mkfaqlink\"></a> ";
					echo "<a class=\"listlink2\" href=\"".do_url_session("transfer2kb.php?input_faqnr=$act_id&$langvar=$act_lang")."\">";
					echo "<img src=\"gfx/kbcopy.gif\" border=\"0\" title=\"$l_transfer2kb\" alt=\"$l_transfer2kb\"></a>";
				}
			}
		}
		echo "</td></tr>";
   } while($myrow = faqe_db_fetch_array($result));
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