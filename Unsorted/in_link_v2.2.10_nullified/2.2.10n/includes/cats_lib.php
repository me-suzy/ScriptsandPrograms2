<?php
//cur user side
function print_cats($query)
{
global $conn, $cols, $lu_no_categories, $cat_data, $cat, $pend;
if($pend==1){$toparse="pend_cats";}
else{$toparse="list_cats";}

$width = round(100 / $cols);
$ret = "<table border=0 cellspacing=2 width=\"100%\"><tr><td valign=\"top\" width=\"$width%\">";

$rs = &$conn->Execute($query);

if ($rs && !$rs->EOF)
	{
	$numtot = $rs->RecordCount();
	$numto = 1.0 / $cols;
	$step = 1.0 / $numtot;
	$numcat = 0.0;
	
	do
	{	$cat_data = $rs->fields;
		$numcat += $step;
        $ret .= parse($toparse);
        if (($numcat+0.0000001)>=$numto) {
			$ret.= "</td><td valign=\"top\" width=\"$width%\">";
			$numcat -= $numto;
        }
	$rs->MoveNext();

     } while ($rs && !$rs->EOF);
	 
	$ret=substr($ret,0,(strlen($ret)-29));
	$ret .= "</tr></table>";
}
else
	$ret = "<span class='sys_message'>$lu_no_categories</span>";
	
   return $ret;
}

function get_cat_data($cat_id)
{	global $conn;
	if($cat_id==0)
	{	global $rootperm;
		return array(0,0,"Home",0,"Root category",0,0,0,0,$rootperm,0,0,0,0,0,0);
	}
	
	$query="SELECT cat_links,cat_image,cat_name,cat_cats,cat_desc,cat_sub,cat_id, cat_date, cat_pick, cat_perm, cust1,cust2,cust3,cust4,cust5,cust6, meta_keywords, meta_desc, cat_vis, cat_user FROM inl_cats LEFT JOIN inl_custom ON cat_cust=cust_id WHERE cat_id=$cat_id";
	$rs = &$conn->Execute($query);
	if($rs && !$rs->EOF)
	{	$cat_data = $rs->fields;
	}
	
	return $cat_data;
}

//print move categories
function printmovecat($cat, $id, $type, $query, $file) 
{
global $conn, $cols, $cats, $la_no_categories, $la_added, $la_new, $la_pick, $datefmt, $cat_new, $la_disabled, $catfrom, $sid, $session_get;

if($sid && $session_get)
	$att_sid="sid=$sid&";

$width = round(100 / $cols);
$cats = "<table border=0 cellspacing=2 width=\"100%\"><tr><td valign=\"top\" width=\"$width%\">";
$rs = &$conn->Execute($query);

if ($rs && !$rs->EOF) {
  $numtot = $rs->RecordCount();
  $numto = ($numtot / $cols);
  $numcat = "0";
  do {
    $numcat++;

    $cat_id = $rs->fields[0];
    $cat_name = stripslashes($rs->fields[1]);
    $caturl = "$file.php?$att_sid"."cat=$cat_id&id=$id&type=$type&catfrom=$catfrom";
    $cat_links = $rs->fields[3];
    $cat_cats = $rs->fields[4];
    $cat_desc = stripslashes($rs->fields[2]);
	
	//pick
	if($rs->fields[5]==1)
		$cat_pick=$la_pick;
	else
		$cat_pick="";

	$cat_date = $rs->fields[6];
	$cat_date = date($datefmt, $cat_date); 
    $tem=mktime(0,0,0,date("m"),date("d")-$cat_new,date("Y"));
	if ($cat_date >= $tem)
		$cat_new .= $la_new;
    else
		$cat_new="";
	if ($rs->fields[7] == 0)
		$disabled = "<span class=\"error2\"> $la_disabled</span>";
	else
		$disabled = "";

$cats .= "

<P>
<A href=\"$caturl\"><IMG src=\"images/folder.gif\" border=\"0\"></A>
<A href=\"$caturl\" class=\"cat\">$cat_name</A> 
<SPAN class=\"cat_pick\">$cat_pick</SPAN> 
<SPAN class=\"cat_new\">$cat_new</SPAN> 

<SPAN class=\"cat_no\">($cat_cats/$cat_links)</SPAN><BR>
<SPAN class=\"cat_desc\">$cat_desc</SPAN><BR>
<SPAN class=\"cat_detail\">($la_added: $cat_date)</SPAN>
<br><br>";

    if (($numcat >= $numto) && ($cols != 0)) {
      $cats.= "</td><td valign=\"top\" width=\"$width%\">";
      $cols -= 1;
      $numcat = 0;
      }
	 $rs->MoveNext();
    } while ($rs && !$rs->EOF);
  $cats .= "</tr></table>";
  } else {
  $cats = $la_no_categories;
  }
}


function masscatapprove($pendcats) {
	global $conn;
	while (list ($cat_id, $val) = each ($pendcats)) {
		$conn->Execute("update inl_cats set cat_pend=0 where cat_id='$cat_id'");
	}
	update_all_cat_count(0);
}
function masscatdelete($pendcats) {
	global $conn;
	while (list ($cat_id, $val) = each ($pendcats)) {
		$conn->Execute("delete from inl_cats where cat_id='$cat_id'");
	}
}

//validate cat values
function validatecat() 
{
global $error, $cat_name, $cat_desc, $cat_month, $cat_day, $cat_year, $meta_keywords, $meta_desc, $cust1, $cust2, $cust3, $cust4, $cust5, $cust6, $admin, $cat_user;
$error = 0;
settype($cat_month, "integer");
settype($cat_year, "integer");
settype($cat_day, "integer");
if ($cat_name == "") {
    $error = 1;
} elseif ((is_int($cat_month) == false) || ($cat_month > 12)) {
    $error = 3;
} elseif ((is_int($cat_day) == false) || ($cat_day > 31)) {
    $error = 4;
} elseif (is_int($cat_year) == false) {
    $error = 5;
}
elseif($admin==1)
{
	if(!$cat_user)
		$error=6;
}

$cat_name = inl_escape($cat_name);
$cat_desc = inl_escape($cat_desc);
$meta_keywords = inl_escape($meta_keywords);
$meta_desc = inl_escape($meta_desc);
$cust1 = inl_escape($cust1);
$cust2 = inl_escape($cust2);
$cust3 = inl_escape($cust3);
$cust4 = inl_escape($cust4);
$cust5 = inl_escape($cust5);
$cust6 = inl_escape($cust6);
}


//number of cats update, all cats, top down
function update_all_cat_count($sub=0) 
{	global $conn;
	$query="select cat_id from inl_cats where cat_sub=$sub and cat_pend=0";
	$val=0;
	$rs = &$conn->Execute($query);
	while(!$rs->EOF)
	{	$t=update_all_cat_count($rs->fields[0]);
		$val=1+$val+$t;
		$query="Update inl_cats set cat_cats=$t where cat_id = ".$rs->fields[0];
		$conn->Execute($query);
		$rs->MoveNext();
	}
	return $val; 
}

//number of cats and links in the category and all parents, bottom up
function update_cat_count($cat) //assumes that parent to be updated is sent
{	global $conn;
	if($cat) //not root
	{	//select all children of the parent, assumes they are fixed
		$numcats=0;
		$numlinks=0;
		$rs = &$conn->Execute("select cat_id, cat_cats, cat_links from inl_cats where cat_sub=$cat and cat_pend=0");
		while(!$rs->EOF) //add up all of the children
		{	$numcats+=$rs->fields[1];
			$numcats+=1; //update cats of itself
			$numlinks+=$rs->fields[2];
			$rs->MoveNext();
		}
		//links of itself
		$rs = &$conn->Execute("select count(link_id) from inl_lc where cat_id=$cat and link_pend='0'");
		if($rs && !$rs->EOF) 
			$numlinks+=$rs->fields[0];

		//update number of sub cats for current cat
		$conn->Execute("update inl_cats set cat_cats=$numcats, cat_links=$numlinks where cat_id='$cat'");
		//get parent & recurse
		$rs = &$conn->Execute("select cat_sub from inl_cats where cat_id=$cat and cat_pend=0");
		if($rs && !$rs->EOF)
			update_cat_count($rs->fields[0]);
	}
}

function reset_cat_perm($cat, $perm) //assumes that parent to be updated is sent
{	global $conn;
	//update perm of children
	$conn->Execute("UPDATE inl_cats SET cat_perm='$perm' WHERE cat_sub='$cat' and cat_pend='0'");
	//select all children of the parent
	$rs = &$conn->Execute("SELECT cat_id FROM inl_cats WHERE cat_sub='$cat' and cat_pend='0'");
	while(!$rs->EOF) //go through all children
	{	//recurse
		reset_cat_perm($rs->fields[0],$perm);
		$rs->MoveNext();
	}
}


//add category
function addcat($cat) 
{	global $conn, $cat_name, $cat_desc, $cat_user, $cat_vis, $cat_month, $cat_day, $cat_year, $cat_pick, $cat_image, $cust1, $cust2, $cust3, $cust4, $cust5, $cust6, $admin, $meta_keywords, $meta_desc, $reg_cat_perm, $all_cat_perm, $ses, $root_link_perm, $sql_type, $cat_list;

settype($cat,"integer");

//data validation
if($admin==1)
	$cat_date = mktime(0,0,0,$cat_month,$cat_day,$cat_year);
else
{	
	global $month, $day, $year;
	$cat_date = mktime(0,0,0,$month,$day,$year);
}
$cat_name = inl_escape($cat_name);
$cat_desc = inl_escape($cat_desc);
$meta_keywords=inl_escape($meta_keywords);
$meta_desc=inl_escape($meta_desc);

if(!$cat_user) //no user - default user parent cat; no parent - no user
{
	$rs=&$conn->Execute("SELECT cat_user FROM inl_cats WHERE cat_id=$cat"); //get parent user
	if($rs && !$rs->EOF)
		$cat_user=$rs->fields[0];
}

if($admin==1)
{	if ($cat_vis == "on")
		$cat_vis = 1;
	else
		$cat_vis = 0;
	if ($cat_pick == "on")
		$cat_pick = 1;
	else 
		$cat_pick = 0;

	if (($cust1) || ($cust2) || ($cust3) || ($cust4) || ($cust5) || ($cust6)) 
	{	$query="insert into inl_custom (cust1, cust2, cust3, cust4, cust5, cust6) values ('$cust1', '$cust2', '$cust3', '$cust4', '$cust5', '$cust6')";
		$conn->Execute($query);
		$cat_cust = $conn->Insert_ID("inl_custom","cust_id");
	}
	else 
		$cat_cust=0;

	//permissions
	$cat_perm=$reg_cat_perm*3 + $all_cat_perm;

	$query="insert into inl_cats (cat_name, cat_desc, cat_sub, cat_user, cat_perm, cat_vis, cat_date, cat_pick, cat_image, cat_cust, meta_keywords, meta_desc, cat_pend) values ('$cat_name', '$cat_desc', $cat, $cat_user, $cat_perm, $cat_vis, $cat_date, $cat_pick, '$cat_image', $cat_cust,'$meta_keywords', '$meta_desc',0)";
	$rs=$conn->Execute($query);
	//echo $query;
	if($rs)
		$id=$conn->Insert_ID("inl_cats","cat_id");
	else
		$id=-1;

	//related categories
	if($id>0)
	{
		$conn->Execute("Delete from inl_rel_cats where cat_id='$id'");
		$rel_cats=split(",",$cat_list);
		end($rel_cats); 
		$last_i = key($rel_cats); 
		for($i=0;$i<$last_i;$i++)
		{
			$conn->Execute("INSERT INTO inl_rel_cats (cat_id, rel_id) VALUES ('$id','$rel_cats[$i]')");
		}
	}

}
else
{	$p=check_perm(-1,"cat");
	if($p==2)
		$p=0;
	
	settype($cat_user,"integer");
	if(!$cat_user)
		$cat_user=$ses["user_id"];
	$query="insert into inl_cats (cat_name, cat_desc, cat_sub, cat_pend, cat_date, cat_vis, cat_user, cat_perm) values ('$cat_name', '$cat_desc', $cat, $p, $cat_date, 1, $cat_user, '$root_link_perm') ";
	$conn->Execute($query);
	//echo $query;
	$id=$conn->Insert_ID("inl_cats","cat_id");
}

	global $email_perm, $subject, $from, $reply, $user_data, $email_cat;
	if($email_perm[3]==1 && $admin!=1){
		$query="select * from inl_cats where cat_id=$id";
		$rs = $conn->Execute($query);
		$email_cat = $rs->fields;
		$query = "select * from inl_users where user_id=".$rs->fields[3];
		$rs = $conn->Execute($query);
		$user_data = $rs->fields;
		include("includes/admin_email_lib.php");
		$body=email_parse("mail_admin_new_cat");
		@mail(get_admin_emails(), $subject, $body, "From:$from<$reply>\r\nReply-to:$reply");
	}


update_cat_count($cat);
}
//delete category
function delcat($cat_id)
{	global $conn;
	$rs=&$conn->Execute("select cat_sub from inl_cats where cat_id=$cat_id"); //get parent
	if($rs && !$rs->EOF)
	{	delcat2($cat_id); //delete all cats recursively
		update_cat_count($rs->fields[0]); //updates count of cats and links
		return "";
	}
	else
	{	//db problem
		return stripslashes("$la_error_db".$conn->ErrorMsg());
	}
}

function delcat2($cat)
{	global $conn;
	//delete all links only in this cat
	$rs = &$conn->Execute("SELECT link_id FROM inl_lc WHERE cat_id=$cat"); 
	while(!$rs->EOF) 
	{	//$res2=@mysql_query("select link_id, count(link_id) from inl_lc where link_id=$row[0] group by link_id");
		$rs2=&$conn->Execute("SELECT inl_lc.link_id, COUNT(inl_lc.link_id), link_cust FROM inl_lc, inl_links WHERE inl_lc.link_id='".$rs->fields[0]."' and inl_links.link_id='".$rs->fields[0]."' group by inl_lc.link_id, link_cust ");
		if($rs && !$rs->EOF)
			if($rs2->fields[1]<2) //link only in one cat, which must be this cat
			{	$conn->Execute("DELETE FROM inl_links WHERE link_id=".$rs->fields[0]); //delete the link
				$conn->Execute("DELETE FROM inl_reviews WHERE rev_link=".$rs->fields[0]); //delete the review
				$conn->Execute("DELETE FROM inl_votes WHERE vote_link=".$rs->fields[0]); //delete the votes
				if($rs2->fields[2] > 0) //custom fields exist
					$conn->Execute("DELETE FROM inl_custom WHERE cust_id=".$rs->fields[2]); //delete the custom fields
			}
	$rs->MoveNext();
    }
	//delete all link references to this cat
	$conn->Execute("DELETE FROM inl_lc WHERE cat_id=$cat");
	
	//delete cat custom, if any
	$rs=&$conn->Execute("SELECT cat_cust FROM inl_cats WHERE cat_id=$cat");
	if($rs && !$rs->EOF)
		if($rs->fields[0] > 0) //custom fields exist
			$conn->Execute("DELETE FROM inl_custom WHERE cust_id=".$rs->fields[0]);
	
	//delete related cats
    $conn->Execute("DELETE FROM inl_rel_cats WHERE cat_id='$cat'");
	$conn->Execute("DELETE FROM inl_rel_cats WHERE rel_id='$cat'");

	//delete cat itself
    $conn->Execute("DELETE FROM inl_cats WHERE cat_id='$cat'");

	//find all sub cats
	$rs = &$conn->Execute("SELECT cat_id FROM inl_cats WHERE cat_sub=$cat");
	//recurse & delete
    while ($rs && !$rs->EOF)
	{
		delcat2($rs->fields[0]);
		$rs->MoveNext();
	}
}


function getcat($id) {
	global $conn, $cat_name, $cat_desc, $cat_month, $cat_day, $cat_year, $cat_pick, $cat_vis, $cat_pend, $cat_image, $cat_cust, $cust1, $cust2, $cust3, $cust4, $cust5, $cust6, $cat_cust, $cat_user, $meta_keywords, $meta_desc, $reg_cat_perm, $all_cat_perm, $cat_list;

	$rs = &$conn->Execute("select * from inl_cats where cat_id='$id'");
	if ($rs && !$rs->EOF) 
	{
		$cat_name = $rs->fields[1];
		$cat_desc = $rs->fields[2];
		$cat_date = $rs->fields[10];
		$cat_pend = $rs->fields[6];
		$cat_month = date("n", $cat_date);
		$cat_day = date("j", $cat_date);
		$cat_year = date("Y", $cat_date);
		if ($rs->fields[11] == 1) {
			$cat_pick = "on";
		} else {
			$cat_pick = "off";
		}
		if ($rs->fields[7] == 1) {
			$cat_vis = "on";
		} else {
			$cat_vis = "off";
		}
		$all_cat_perm = $rs->fields[5]%3;
		$reg_cat_perm = floor($rs->fields[5]/3);
		$cat_image = $rs->fields[12];
		$cat_user = $rs->fields[3];
		$cat_cust = $rs->fields[13];
		$meta_keywords = $rs->fields[14];
		$meta_desc = $rs->fields[15];
		if ($cat_cust) {
			$rs2 = &$conn->Execute("select * from inl_custom where cust_id='$cat_cust'");
			if (!$rs2->EOF) {
				$cust1 = $rs2->fields[1];
				$cust2 = $rs2->fields[2];
				$cust3 = $rs2->fields[3];
				$cust4 = $rs2->fields[4];
				$cust5 = $rs2->fields[5];
				$cust6 = $rs2->fields[6];
			}
		}
		$rs = &$conn->Execute("SELECT rel_id FROM inl_rel_cats WHERE cat_id=$id");
		while($rs && !$rs->EOF) 
		{
			$cat_list.=$rs->fields[0].",";
			$rs->MoveNext();
		}
	}
}
// edit category
function editcat($id)
{	global $conn, $cat_name, $cat_desc, $cat_month, $cat_day, $cat_year, $cat_pick, $cat_vis, $cat_cust, $cat_image, $cust1, $cust2, $cust3, $cust4, $cust5, $cust6, $cat_user, $reg_cat_perm, $all_cat_perm, $meta_keywords, $meta_desc, $apply_cat_perm, $all_users, $keep_editors, $cat_list;
	$cat_date = mktime(0,0,0,$cat_month,$cat_day,$cat_year);
	$cat_name = inl_escape($cat_name);
	$cat_url = inl_escape($cat_url);
	$cat_desc = inl_escape($cat_desc);
	$cat_image = inl_escape($cat_image);
	$meta_keywords=inl_escape($meta_keywords);
	$meta_desc=inl_escape($meta_desc);
	if ($cat_vis == "on") {$cat_vis = 1;} else {$cat_vis = 0;}
	if ($cat_pick == "on") {$cat_pick = 1;} else {$cat_pick = 0;}
	//permisisons
	$cat_perm=$reg_cat_perm*3 + $all_cat_perm;
	if($apply_cat_perm==1) //reset permissions on all child cats
		reset_cat_perm($id,$cat_perm);

	if($all_users==1) //reset all users
		reset_cat_users($id, $cat_user, $keep_editors);
	
	//related categories
	if($id>0)
	{
		$conn->Execute("Delete from inl_rel_cats where cat_id='$id'");
		$rel_cats=split(",",$cat_list);
		end($rel_cats); 
		$last_i = key($rel_cats); 
		for($i=0;$i<$last_i;$i++)
		{
			$conn->Execute("INSERT INTO inl_rel_cats (cat_id, rel_id) VALUES ('$id','$rel_cats[$i]')");
		}
	}


	//echo "$cat_perm, $reg_cat_perm, $all_cat_perm;";
	if (($cust1) || ($cust2) || ($cust3) || ($cust4) || ($cust5) || ($cust6)) 
	{	$cust1 = inl_escape($cust1);
		$cust2 = inl_escape($cust2);
		$cust3 = inl_escape($cust3);
		$cust4 = inl_escape($cust4);
		$cust5 = inl_escape($cust5);
		$cust6 = inl_escape($cust6);
		if ($cat_cust == 0) 
		{	$conn->Execute("insert into inl_custom (cust1, cust2, cust3, cust4, cust5, cust6) values ('$cust1', '$cust2', '$cust3', '$cust4', '$cust5', '$cust6')");
			$cat_cust = $conn->Insert_ID("inl_custom","cust_id");
		}
		else {
			$conn->Execute("update inl_custom set cust1='$cust1', cust2='$cust2', cust3='$cust3', cust4='$cust4', cust5='$cust5', cust6='$cust6' where cust_id='$cat_cust'");
		}
	}
	else //clean up cust record
	{	$rs = &$conn->Execute("SELECT cat_cust FROM inl_cats WHERE cat_id=$id");
		if($rs && !$rs->EOF)
			if($rs->fields[0]!=0)
				$conn->Execute("DELETE FROM inl_custom WHERE cust_id=".$rs->fields[0]);
	}
	$query = "UPDATE inl_cats SET cat_name='$cat_name', cat_desc='$cat_desc', cat_date='$cat_date', cat_vis='$cat_vis', cat_pick='$cat_pick', cat_image='$cat_image', cat_user='$cat_user' , cat_cust='$cat_cust', meta_keywords='$meta_keywords', meta_desc='$meta_desc', cat_perm='$cat_perm' WHERE cat_id='$id'";
	$conn->Execute($query);
	if (!$cat_vis)
		$cat_vis = 0;
	update_all_cats_for_visibility($id); 
}		

function update_all_cats_for_visibility($c)
{	global $conn, $cat_vis;
	
	$sql = "SELECT cat_id FROM inl_cats WHERE cat_sub=$c";
	$rs = &$conn->Execute($sql);
	while ($rs && !$rs->EOF)
	{
		$i = $rs->fields[0];
		$q = "UPDATE inl_cats SET cat_vis='$cat_vis' WHERE cat_id=$i";
		$conn->Execute($q);
		update_all_cats_for_visibility($i);
		$rs->MoveNext();
	}
}

function print_cat_subs($id)
{
	global $conn, $admin, $theme, $sid, $session_get, $subcat_order, $subcat_sort;

	if($subcat_order && $subcat_sort)
		$orderby=" order by $subcat_order $subcat_sort";

	if($sid && $session_get)
		$att_sid="sid=$sid&";

	if($admin==1)
		$query="SELECT cat_id, cat_name from inl_cats where cat_sub=$id and cat_pend=0 $orderby";
	else{
		$query="SELECT cat_id, cat_name from inl_cats where cat_sub=$id and cat_pend=0 and cat_vis=1 $orderby";
	}
	$rs=&$conn->SelectLimit($query,3,0);
	$count=1;
	while(!$rs->EOF)
	{
		if($admin!=1)
		{	if(file_exists($filedir . "themes/" . $theme . "/".$rs->fields[0].".tpl"))
			{
				$more="t=".$rs->fields[0];	
			}
			else
				$more="t=sub_pages";
		}
		if($count < $rs->RecordCount())
			$ret=$ret."<a class=\"catsub\" href=\"../../index.php?$att_sid"."cat=".$rs->fields[0]."&$more\">".$rs->fields[1]."</a>, ";
		else
			$ret=$ret."<a class=\"catsub\" href=\"../../index.php?$att_sid"."cat=".$rs->fields[0]."&$more\">".$rs->fields[1]."</a>";
		$count++;
		if($count==4){$ret=$ret." ...";return $ret;}
	$rs->MoveNext();
	}
	return $ret;
}

function print_drop_cats($parent_cat,$cat_list)
{	global $conn, $lu_cat_no_permission_marker,$la_cat_no_permission_marker, $admin;
	//admin vs. user languages
	if($admin==1)
		$l_cat_no_permission_marker=$la_cat_no_permission_marker;
	else
		$l_cat_no_permission_marker=$lu_cat_no_permission_marker;

	if(!$parent_cat)
		$parent_cat=0;
	$query="select cat_id, cat_name, cat_perm from inl_cats where cat_pend=0 and cat_sub=$parent_cat ";
	
	if($admin!=1){$query.="and cat_vis=1 ";}
	$query.="order by cat_name";
	
	$rs = &$conn->Execute($query);
	
	$count=1;
	$ret="";
	while(!$rs->EOF)
	{	if($rs->fields[2]<1)
			$rs->fields[1].=$l_cat_no_permission_marker;
		if($count==1)
		{	$ret.="<option value='".$rs->fields[0]."' selected>".$rs->fields[1]."</option>\n";
			$count=0;
		}
		else
			$ret.="<option value='".$rs->fields[0]."'>".$rs->fields[1]."</option>\n";
	$rs->MoveNext();
	}
	return $ret;	
}

function print_addto_cats($cat_list)
{	global $conn, $cat_data, $la_nav_home, $lu_nav_home;
	if($admin==1)
		$l_Home=$la_nav_home;
	else
		$l_Home=$lu_nav_home;
	$ret="";
	$cats=split(",",$cat_list);
	
	end($cats); 
	$last_i = key($cats); 
	for($i=0;$i<$last_i;$i++)
	{	if($cats[$i]=="Home")
		{	$cat_data[0]=0;
			$cat_data[1]=$l_Home;
			$ret.=parse("add_link_cats");
		}
		elseif($cats[$i])
		{	$query="select cat_id, cat_name from inl_cats where cat_id=$cats[$i]";
			$rs = &$conn->Execute($query);
			if($rs && !$rs->EOF)
				$cat_data=$rs->fields;
			$ret.=parse("add_link_cats");
		}
		
	}
	return $ret;
}

//move links, cats to category
function movecat($cat_to,$cat)
{	global $conn;	
	$rs = &$conn->Execute("select cat_sub from inl_cats where cat_id='$cat'");
	if ($rs && !$rs->EOF)
	{	$cat_from = $rs->fields[0];
		//move to new cat, which moves all underneath & links
		$conn->Execute("update inl_cats set cat_sub=$cat_to where cat_id='$cat'");

		//update sub counts on 'from' and 'to' cats
		update_cat_count($cat_to);
		update_cat_count($cat_from);
	}

}

//copy links, cats to category
function copycat($cat_to,$cat)
{	global $conn;	

	// create copy and add it to cat_to
	$rs=&$conn->Execute("SELECT cat_name, cat_desc, cat_sub, cat_user, cat_perm, cat_vis, cat_date, cat_pick, cat_image, cat_cust, meta_keywords, meta_desc, cat_pend, cat_links, cat_cats FROM inl_cats WHERE cat_id=$cat");

	if($rs && !$rs->EOF)
	{
		extract($rs->fields);

		$conn->Execute("INSERT INTO inl_cats (cat_name, cat_desc, cat_sub, cat_user, cat_perm, cat_vis, cat_date, cat_pick, cat_image, cat_cust, meta_keywords, meta_desc, cat_pend, cat_links, cat_cats) VALUES ('$cat_name', '$cat_desc', '$cat_to', '$cat_user', '$cat_perm', '$cat_vis', '$cat_date', '$cat_pick', '$cat_image', '$cat_cust', '$meta_keywords', '$meta_desc', '$cat_pend', '$cat_links', '$cat_cats')");

		$new_cat_id=$conn->Insert_ID("inl_cats","cat_id");

		// create copy of custom info
		$rs=&$conn->Execute("SELECT cat_cust FROM inl_cats WHERE cat_id=$cat");
		$cust_id = $rs->fields[0];
		
		if($cust_id != 0) // custom info exists
		{
			$rs=&$conn->Execute("SELECT cust1, cust2, cust3, cust4, cust5, cust6 FROM inl_custom WHERE cust_id=$cust_id");

			if($rs && !$rs->EOF)
			{
				extract($rs->fields);

				$conn->Execute("INSERT INTO inl_custom (cust1, cust2, cust3, cust4, cust5, cust6) VALUES ('$cust1', '$cust2', '$cust3', '$cust4', '$cust5', '$cust6')");

				$new_cust_id=$conn->Insert_ID("inl_cust","cust_id");

				$conn->Execute("UPDATE inl_cats SET cat_cust='$new_cust_id' WHERE cat_id=$new_cat_id");
			}
		}

		// add all links from cat to new_cat
		$rs = &$conn->Execute("SELECT link_id, cat_id, link_pend FROM inl_lc WHERE cat_id=$cat AND link_pend=0");	
		
		while ($rs && !$rs->EOF) // for each link
		{	
			$rs->fields["cat_id"]=$new_cat_id;
			extract($rs->fields);

			$query = "INSERT INTO inl_lc (link_id, cat_id, link_pend) VALUES ('$link_id', '$cat_id', '$link_pend')";
			
			$conn->Execute($query);
			
			$rs->MoveNext();
		}
		//add all related categories
		
		$rs = &$conn->Execute("SELECT rel_id FROM inl_rel_cats WHERE cat_id=$cat");
		while($rs && !$rs->EOF) 
		{
			if($rs->fields[0]>0)
				$conn->Execute("INSERT INTO inl_rel_cats (cat_id, rel_id) VALUES ('$new_cat_id','".$rs->fields[0]."')");
			$rs->MoveNext();
		}

		// add all sub categories from cat to new_cat
		$rs = &$conn->Execute("SELECT cat_id FROM inl_cats WHERE cat_sub=$cat AND cat_pend=0");	

		while ($rs && !$rs->EOF) 
		{	
			copycat($new_cat_id, $rs->fields[0]);

			$rs->MoveNext();
		}
	}
}

/*	Resets users for all subcategories of a category
	Has an option to preserve editors
*/
function reset_cat_users($id, $cat_user, $ke)
{	global $conn;
	//echo "<br>$id, $cat_user, $ke";
	$change=0;
	//update perm of children
	if($ke==1)
	{	$rs=&$conn->Execute("SELECT inl_users.user_perm FROM inl_cats LEFT JOIN inl_users ON inl_cats.cat_user=inl_users.user_id WHERE cat_sub=$id"); 	//get current user & perm
		if($rs && !$rs->EOF)
			if($rs->fields[0]!=5)
				$change=1;
	}
	else
		$change=1;

	if($change)	
		$conn->Execute("UPDATE inl_cats SET cat_user=$cat_user WHERE cat_sub=$id");

	//select all children of the parent
	$rs = &$conn->Execute("SELECT cat_id FROM inl_cats WHERE cat_sub=$id");
	while($rs && !$rs->EOF) //go through all children
	{	//recurse
		reset_cat_users($rs->fields[0],$cat_user,$ke);
		$rs->MoveNext();
	}
}
?>