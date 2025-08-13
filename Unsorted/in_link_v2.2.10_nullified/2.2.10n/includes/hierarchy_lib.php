<?php
//Admin Hierarchy Navigation


//heirarchy navigation bar
function navbar($cat, $file)
{
	global $conn, $navbar, $lu_navbar_seperator,$la_navbar_seperator, $id, $type, $admin, $num_results, $link_order_c, $link_sort_c, $cat_order_c, $cat_sort_c, $thisfile, $catfrom, $t, $filedir, $theme, $la_nav_home, $lu_nav_home, $sid, $session_get;

	if($sid && $session_get)
		$att_sid="sid=$sid&";

	if($admin==1)
		$l_navbar_seperator=$la_navbar_seperator;
	else
		$l_navbar_seperator=$lu_navbar_seperator;

	$catb = $cat;
	$navbar = "";
	
	//set all of the return vars
	if($admin==1)
	{	$back="";
		$l_Home=$la_nav_home;
	}
	else
	{	$back="../../";
		$l_Home=$lu_nav_home;
	}
	if (($id)&&($type)) {$more = "&id=$id&type=$type";}
	if($thisfile=="move"){$more.="&catfrom=$catfrom";}
	if ($cat != "0") 
	{	do
		{	
			$rs = &$conn->Execute("select cat_name, cat_sub from inl_cats where cat_id='$catb'");
			if ($rs && !$rs->EOF)
			{	$this_cat = $rs->fields[0];
				
				if ($catb == $cat) 
					$navbar = "$l_navbar_seperator \n$this_cat" . $navbar;
				else
				{
					if($t && $admin!=1)
						$attach2="&t=sub_pages";
					if (file_exists($filedir . "themes/" . $theme . "/" . $catb . ".tpl") && $admin!=1)
						$attach2="&t=$catb";

					$navbar = "$l_navbar_seperator \n<a class=\"navbar\" href=\"$back$file.php?$att_sid"."cat=$catb$more$attach$attach2\">$this_cat</a>" . $navbar;
				}
				$catb = $rs->fields[1];
			}
			else
				$catb="0"; //to prevent infinite loop
		} while ($catb != "0");
		//add home
		$navbar = "<a href=\"$back$file.php?$att_sid"."cat=0$more$attach\">$l_Home</a>$navbar";
	}
	else
	{
		$navbar = $l_Home;
	}
}


//displays multiple pages navigation
function pagenav($cat, $query, $file, $start, $ar) 
{	global $conn, $pagenav, $lim, $lu_go_to_page,$la_go_to_page, $admin, $sid, $session_get, $duplicatemail,
	$act;
	
	if ($duplicatemail)
		$dup_email = "&duplicatemail=1";
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	
	if ($act)
		$act_attacht = "&act=$act";
	else 
		$act_attacht = "";

	$query = ereg_replace("@\!", "&", $query);
	$pagenav = "";	
	if($admin==1)
	{
		$back="";
		$go_to="$la_go_to_page ";
	}
	else
	{
		$go_to="$lu_go_to_page ";
		$back="../../";
	}
	if (!$start) {
		$start = "0";
  	}
	if ($ar)
	{
		$more ="";
		while (list ($varname, $varval) = each ($ar))
			$more .= "&$varname=$varval";
		if (($id) && ($type)) 
			$more = "&id=$id&type=$type";
	}
	
	settype($start,"integer");
	settype($lim,"integer");
	$rs = &$conn->Execute($query);

	if ($rs && !$rs->EOF)
		$total = $rs->RecordCount();
	else
		$total = 0;
	if ($total > $lim)
	{
	  	$pagenav = $go_to;
		if ($start >=(10 * $lim))
		{
			$startpage = floor($start/$lim);
			if($startpage)
			{
				$st2 = $startpage * $lim-9*$lim;
				$pagenav .= "<a href=\"$back$file.php?$att_sid"."cat=$cat&start=$st2$more".$dup_email.$act_attacht."
				\"><<</a>";
			}
		}
		if ($startpage)
		{
			$num = $startpage * $lim;
			$pagenum = $startpage;
		} 
		else
		{
			$num = "0";
  			$pagenum = "1";
		}
		$pagelinknum = 1;
		if ($total > $lim) 
		{
    			while (($num < $total) && ($pagelinknum <= 10))
				{
      				$endnum = $num + $lim;
      				if ($num == $start)
						$pagenav.= "$pagenum  ";
      				else
						$pagenav.= "<a href=\"$back$file.php?$att_sid"."cat=$cat&start=$num$more".$dup_email.$act_attacht."\">$pagenum</a>  ";
		      		$num = $num + $lim;
      				$pagenum++;
					$pagelinknum++;
      			}
				if (($pagelinknum > 10) && ($num < $total))
				{
				#$startpage = floor($start/$lim);
				#$st2 = ($startpage + 10) * $lim;
					$pagenav .= "<a href=\"$back$file.php?$att_sid"."cat=$cat&start=$num$more".$dup_email.$act_attacht."\">>></a>";
				}
		}
	}
}
//Admin Navigation Functions

function display_admin_nav($current_dir, $nav_names, $nav_links)
{
	$tr="<tr>
      <td bgColor='#f9eeae' class='title'>
        <div align='right' class='tableitem'>";
	for($i=0;$i<count($nav_names);$i++)
	{
		$tr=$tr." | ";
		if($current_dir==$nav_names[$i]){$tr=$tr."<img src='images/arrow1.gif' width='8' height='9'> ";}
		else{ $tr=$tr."<img src='images/spacer.gif' width='1' height='1'>";}
          	$tr=$tr."<a class='tableitem' href='".$nav_links["$nav_names[$i]"]."'>".$nav_names[$i]."</a>"; 
	}
    	$tr=$tr."    </div>
      	</td>
   		 </tr>";
	return $tr;
}
?>