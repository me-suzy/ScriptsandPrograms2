<?php
if($admin==1 || $prev_admin==1)
{
	include("../includes/cats_lib.php");
	include("../includes/links_lib.php");
	include("../includes/hierarchy_lib.php");
	include("../includes/review_lib.php");
	include("../includes/stats_lib.php");
	include_once("../includes/search_lib.php");

}
else{
	include("includes/cats_lib.php");
	include("includes/links_lib.php");
	include("includes/hierarchy_lib.php");
	include("includes/review_lib.php");
	include("includes/stats_lib.php");
	include_once("includes/search_lib.php");
}
		
$t_cache[]="";

static $link_query="SELECT inl_links.link_id, link_name, link_pick, link_desc, link_date, link_hits, link_rating, link_votes, link_numrevs, link_image, cust1, cust2, cust3, cust4, cust5, cust6, link_user, link_url, cat_id, link_vis, user_name, email FROM inl_lc LEFT JOIN inl_links ON inl_lc.link_id=inl_links.link_id LEFT JOIN inl_custom ON inl_links.link_cust=inl_custom.cust_id LEFT JOIN inl_users ON inl_links.link_user=inl_users.user_id ";

$cat_query="SELECT cat_links,cat_image,cat_name,cat_cats,cat_desc,cat_sub,cat_id, cat_date, cat_pick, cat_perm, cust1,cust2,cust3,cust4,cust5,cust6, meta_keywords, meta_desc,cat_vis,cat_user FROM inl_cats LEFT JOIN inl_custom ON cat_cust=cust_id ";

	//retrieve cat info
	if($cat && $cat!=$cat_data[6] && $t_name!="list_cats" && $t_name!="add_link_cats") 
	//current cat id exists and does not match cat_data and not inside sub_list
		$cat_data=get_cat_data($cat);
	
	//retrieve link info
	if($id && $id!=$link_data[0] && $t_name!="list_links" && $t_name!="list_mod_links" && $t_name!="list_pick_links") 
	//current cat id exists and does not match cat_data and not inside sub_list
		$link_data=get_link_data($id);



function template($file) 
{	global $cat, $filedir, $ses, $admin,$lu_error_file_not_found,$la_error_file_not_found, $fileh;
	if($admin==1)
		$fileh=$filedir."admin/templ/$file.tpl";
	else
		$fileh=$filedir . "themes/" . $ses["theme"] . "/$file.tpl";

	if(file_exists($fileh))
	{	$fd = fopen($fileh, "r");
		$ret=fread($fd, filesize($fileh));
		fclose($fd);
		return $ret;
	}
	else
	{	if($admin==1)
			$ret="$lu_error_file_not_found $fileh";
		else
			$ret="$la_error_file_not_found $fileh";
	}
}

//main parsing func
function parse($t_name,$visited="")
{
	global $t_cache, $filedir, $parse_email, $email_body;
	//check for recursion in templates
	if(!$parse_email)
	{	
		if(ereg("%$t_name%",$visited))
		{	
			echo "***Error: recursion in branch $visited, node $t_name. Aborting template.";
			return;
		}
		else
			$visited.="%$t_name%";

		if($t_cache[$t_name] == "") //check if cashed
			$t_cache[$t_name]=template($t_name); //load if not cashed
		
		$t=$t_cache[$t_name]; //get local copy of the cash - speed 
	}
	else
	{
		$t=$email_body;
		$parse_email=false;
	}


	#main parsing loop
	$t_len=strlen($t);
	$o=""; //output HTML & PHP
	//tags parse
	for($i=0;$i<$t_len;$i++)
	{	#search for next special tag
		$tagOpen=strpos($t,"<%",$i);
		
		if ($tagOpen===false) 
		{	#only HTML left
			$o.=substr($t,$i);
			break;
		}
		else
		{	//get end of tag
			$tagClose=strpos($t,"%>",$tagOpen);
			$tag=substr($t,$tagOpen+2,$tagClose-$tagOpen-2);
			//add all HTML before
			$o.=substr($t,$i,$tagOpen-$i);
			//process tag
			if($col_pos=strpos($tag,":"))
			{	$vtag_name=substr($tag,0,$col_pos);
				$vtag_value=substr($tag,$col_pos+1);
				
				switch($vtag_name)
				{
					case "language":
						global $$vtag_value;
						$o.=$$vtag_value;
						break;
					case "include":
						$o.=parse(substr($tag,8),$visited);
						break;
					case "nav":
						$o.=nav($vtag_value);
						break;
					case "inl_language":
						$o.=inl_language($vtag_value);
						break;
					case "drop_lang":
						$o.=drop_lang($vtag_value);
						break;
					case "drop_theme":
						$o.=drop_theme($vtag_value);
						break;
					case "drop_results":
						$o.=drop_results($vtag_value);
						break;
				}
			}
			elseif(function_exists($tag))
				$o.=$tag();
			elseif(file_exists($filedir . "mods/$tag.mod"))
			{	include_once($filedir . "mods/$tag.mod");
				$func_name="runmod_$tag";
				if(function_exists($func_name))
					$o.=$func_name();
			}
			else
				$o.=$tag;
			//move ptr
			$i=$tagClose+1;
		}
	}
	return $o;
}

function cat_num_cats()
{	global $cat_data;
	return $cat_data[3];
}
function insert_list_links()
{
	global $ses, $cat, $lim, $link_order_c,$link_order,$link_sort,$link_sort_c, $force_pick, $admin, $link_query, $start;

	if(!$cat)
		$cat=0;
	if($ses["num_res"]=="all")
		$lim="";
	else
	{	if($ses["num_res"])
			$lim=$ses["num_res"];
	}

	//sort vars
	if($link_order_c)			$link_order=$link_order_c;
	if($link_sort_c)			$link_sort=$link_sort_c;

	if($force_pick)
		$orderby=" ORDER BY link_pick desc, ".$ses["link_order"]." ".$ses["link_sort"]." ";	
	else	
		$orderby=" ORDER BY ".$ses["link_order"]." ".$ses["link_sort"]." ";

	if($admin==1)
		$vis="";
	else
		$vis="AND link_vis=1 ";
	$link_query.="WHERE inl_lc.cat_id=$cat AND inl_lc.link_pend=0 $vis";

	return print_links($link_query.$orderby, "list_links",$lim,$start);
}


function insert_top_links()
{
	global $cat, $lim, $link_order_c,$link_order,$link_sort,$link_sort_c, $force_pick, $admin, $link_query, $start, $sql_type, $link_top, $ses, $sid;

	if(!$cat)					$cat=0;
	if($ses["num_res"]=="all")
		$lim="";
	else
	{	if($ses["num_res"])
			$lim=$ses["num_res"];
	}

	//sort vars
	if($link_order_c)			$link_order=$link_order_c;
	if($link_sort_c)			$link_sort=$link_sort_c;

	if($force_pick)
		$orderby=" ORDER BY link_pick desc, ".$ses["link_order"]." ".$ses["link_sort"]." ";	
	else	
		$orderby=" ORDER BY ".$ses["link_order"]." ".$ses["link_sort"]." ";

	$link_query.=get_top_links();
	//code below fixed multple link listings

	if($sql_type!="mssql")
	{	$link_query=ereg_replace("SELECT","SELECT DISTINCT",$link_query);
		$link_query=ereg_replace("cat_id, ","inl_links.link_id, ",$link_query);
	}
	
	return print_links($link_query.$orderby, "list_links", $lim, $start);

	
}
function insert_count_rel()
{
	global $cat, $conn;
	$rs = &$conn->Execute("SELECT count(rel_id) FROM inl_rel_cats WHERE cat_id='$cat'");
	if($rs && !$rs->EOF) 
	{
		if($rs->fields[0]>0)
			$ret=$rs->fields[0];
		else
			$ret="0";

	}
	else
			$ret="0";
	return $ret;
}

function by_name($a, $b)
{
	return ($a[2] > $b[2])  ? -1 :1 ;
}

function insert_rel_cats()
{
	global $cat, $conn, $cat_data, $rcols;
	$rel_query="SELECT cat_links,cat_image,cat_name,cat_cats,cat_desc,cat_sub,cat_id, cat_date, cat_pick, cat_perm, cust1,cust2,cust3,cust4,cust5,cust6, meta_keywords, meta_desc,cat_vis,cat_user FROM inl_cats LEFT JOIN inl_custom ON cat_cust=cust_id where (";
	$rs = &$conn->Execute("SELECT rel_id FROM inl_rel_cats WHERE cat_id='$cat'");
	$categories=array();
	$i=0;
	while($rs && !$rs->EOF) 
	{
		
		if($rs->fields[0]>0)
		{  
			$rel_query.=" cat_id=".$rs->fields[0]." or";
		}
		#!!! below in Case need to display the relational catalog with link to home
		elseif($rs->fields[0] =="0")
		{ 
			#$rel_query.=" cat_id=".$rs->fields[0]." or";
			$categories[0][6]=0;
			$categories[0][2]="Home";
			$categories[0][0]="0";
			$i++;
		}	
		#above segment needs to be looked at to safety 
		$rs->MoveNext();
	}

	
	$rel_query=ereg_replace("or$",")", $rel_query);	

	$rs = &$conn->Execute($rel_query);
	while ($rs && !$rs->EOF)
	{	$categories[$i]=$rs->fields;
		$i++;
		$rs->MoveNext();
	}


	if ($i)
	{
		$rel_width = round(100 / $rcols);
		$ret = "<table border=0 cellspacing=2 width=\"100%\"><tr><td valign=\"top\" width=\"$rel_width%\">";
		$rel_numtot = $i;
		$rel_numto = 1.0 / $rcols;
		$rel_step = 1.0 / $rel_numtot;
		$rel_numcat = 0.0;

		# sorts the array by name of the category in reverse order
		usort($categories, by_name);

		do
		{	$i--;
			$cat_data = $categories[$i];
			$rel_numcat += $rel_step;
			$ret .= parse("list_rel_cats");
			
			
			if (($rel_numcat+0.0000001)>=$rel_numto) 
			{
				$ret.= "</td><td valign=\"top\" width=\"$rel_width%\">";
				$rel_numcat -= $rel_numto;
			}
			//$rs->MoveNext();
			
		} while ($i>0);
		ereg_replace("</td><td valign=\"top\" width=\"$rel_width%\">$","",$ret);
		$ret .= "</td></tr></table>";
	}
	return $ret;
}
function insert_pop_links()
{
	global $ses, $cat, $lim, $link_order_c,$link_order,$link_sort,$link_sort_c, $force_pick, $admin, $link_query, $start, $sql_type, $link_pop;

	if(!$cat)					$cat=0;
	if($ses["num_res"]=="all")
		$lim="";
	else
	{	if($ses["num_res"])
			$lim=$ses["num_res"];
	}

	//sort vars
	if($link_order_c)			$link_order=$link_order_c;
	if($link_sort_c)			$link_sort=$link_sort_c;
	
	if($force_pick)
		$orderby=" ORDER BY link_pick desc, ".$ses["link_order"]." ".$ses["link_sort"]." ";	
	else	
		$orderby=" ORDER BY ".$ses["link_order"]." ".$ses["link_sort"]." ";
			
	$link_query.=get_pop_links();
	
	if($sql_type!="mssql")
	{
		$link_query=ereg_replace("SELECT","SELECT DISTINCT",$link_query);
		$link_query=ereg_replace("cat_id, ","inl_links.link_id, ",$link_query);
	}

	return print_links($link_query.$orderby, "list_links", $lim, $start);
}
function insert_new_links()
{
	global $ses, $cat, $lim, $link_order_c,$link_order,$link_sort,$link_sort_c, $force_pick, $admin, $link_query, $start, $sql_type;

	if(!$cat)					$cat=0;
	if($ses["num_res"]=="all")
		$lim="";
	else
	{	if($ses["num_res"])
			$lim=$ses["num_res"];
	}

	//sort vars
	if($link_order_c)			$link_order=$link_order_c;
	if($link_sort_c)			$link_sort=$link_sort_c;
	
	if($force_pick)
		$orderby=" ORDER BY link_pick desc, ".$ses["link_order"]." ".$ses["link_sort"]." ";	
	else	
		$orderby=" ORDER BY ".$ses["link_order"]." ".$ses["link_sort"]." ";
			
	$link_query.=get_new_links();
	//code below fixed multple link listings
	if($sql_type!="mssql")
	{
		$link_query=ereg_replace("SELECT","SELECT DISTINCT",$link_query);
		$link_query=ereg_replace("cat_id, ","inl_links.link_id, ",$link_query);
	}
	return print_links($link_query.$orderby, "list_links", $lim, $start);
}
function insert_pick_links()
{
	global $ses, $cat, $lim, $link_order_c,$link_order,$link_sort,$link_sort_c, $force_pick, $admin, $link_query, $start, $sql_type;

	if(!$cat)					$cat=0;
	if($ses["num_res"]=="all")
		$lim="";
	else
	{	if($ses["num_res"])
			$lim=$ses["num_res"];
	}

	//sort vars
	if($link_order_c)			$link_order=$link_order_c;
	if($link_sort_c)			$link_sort=$link_sort_c;
	
	if($force_pick)
		$orderby=" ORDER BY link_pick desc, ".$ses["link_order"]." ".$ses["link_sort"]." ";	
	else	
		$orderby=" ORDER BY ".$ses["link_order"]." ".$ses["link_sort"]." ";
			
	$link_query.=get_pick_links();
	//code below fixed multple link listings
	if($sql_type!="mssql")
	{
		$link_query=ereg_replace("SELECT","SELECT DISTINCT",$link_query);
		$link_query=ereg_replace("cat_id, ","inl_links.link_id, ",$link_query);
	}
	return print_links($link_query.$orderby, "list_pick_links", $lim, $start);
}
function insert_search_links()
{
	global $ses, $cat, $lim, $link_order_c,$link_order,$link_sort,$link_sort_c, $force_pick, $admin, $link_query, $start, $having, $conn, $sql_type, $multiple_search_instances, $sid, $extended_search;
	
	if(!$cat)
		$cat=0;
	
	if($ses["num_res"]=="all")
		$lim="";
	else
	{	
		if($ses["num_res"])
			$lim=$ses["num_res"];
	}

	//sort vars
	if($link_order_c)			$link_order=$link_order_c;
	if($link_sort_c)			$link_sort=$link_sort_c;
	$query = $link_query;
	$que="SELECT log_keyword, log_search, search_action, search_cat FROM inl_search_log where log_id='$having'";
	$rs = &$conn->Execute($que);
	if ($rs && !$rs->EOF) 
	{
		$search_word = $rs->fields[0];
		$search_type = $rs->fields[1];
		$act = $rs->fields[2];
		$cat = $rs->fields[3];
	}	
	if ($search_type == 1)
	{	
		if ($extended_search == 1)
		{
			if (is_table_exist("inl_$sid") == false)
				do_extended_search($search_word, $cat, $act);
			$search_link_query = "SELECT link_id, link_name, link_pick, link_desc, link_date, link_hits, link_rating, link_votes, link_numrevs, link_image, cust1, cust2, cust3, cust4, cust5, cust6, link_user, link_url, cat_id, link_vis, user_name, email FROM inl_$sid";
		//	$search_link_query. = WHERE ;
			$search_order = "search_order, ";
		}
		elseif ($extended_search == 0)
		{
			if(!$multiple_search_instances)
				if($sql_type!="mssql")
				{
					$query = ereg_replace("SELECT","SELECT DISTINCT", $query);
					$query = ereg_replace("cat_id, ","inl_links.link_id, ", $query);
				}
			if (!$admin)
				$query = ereg_replace("cat_id, ","inl_cats.cat_id, ",$query);

			$search_link_query = $query.do_simple_search($search_word, $cat, $act);
		}
	}
	elseif ($search_type == 2)
	{
		if(!$multiple_search_instances)
			if($sql_type!="mssql")
			{
				$query = ereg_replace("SELECT","SELECT DISTINCT", $query);
				$query = ereg_replace("cat_id, ","inl_links.link_id, ", $query);
			}
		if (!$admin)
			$query = ereg_replace("cat_id, ","inl_cats.cat_id, ",$query);
		
		$search_link_query = $query.adv_search_parsing($search_word, "link");


	}
	else 
		{;}

	if($force_pick)
		$orderby=" ORDER BY ".$search_order."link_pick desc, ".$ses["link_order"]." ".$ses["link_sort"]." ";	
	else	
		$orderby=" ORDER BY ".$search_order.$ses["link_order"]." ".$ses["link_sort"]." ";

	return print_links($search_link_query.$orderby, "list_search_links", $lim, $start, $search_word);

}
function insert_mod_links()
{
	global $ses, $cat, $lim, $link_order_c,$link_order,$link_sort,$link_sort_c, $force_pick, $admin, $link_query, $start, $sql_type;

	if(!$cat)					$cat=0;
	if($ses["num_res"]=="all")
		$lim="";
	else
	{	if($ses["num_res"])
			$lim=$ses["num_res"];
	}

	//sort vars
	if($link_order_c)			$link_order=$link_order_c;
	if($link_sort_c)			$link_sort=$link_sort_c;
	
	if($force_pick)
		$orderby=" ORDER BY link_pick desc, ".$ses["link_order"]." ".$ses["link_sort"]." ";	
	else	
		$orderby=" ORDER BY ".$ses["link_order"]." ".$ses["link_sort"]." ";
			
	$link_query.=get_user_links();
	//code below fixed multple link listings

	if($sql_type!="mssql")
	{
		$link_query=ereg_replace("SELECT","SELECT DISTINCT",$link_query);
		$link_query=ereg_replace("cat_id, ","inl_links.link_id, ",$link_query);
	}

	return print_links($link_query.$orderby, "list_mod_links", $lim, $start);
}
function insert_fav_links()
{
	global $ses, $cat, $lim, $link_order_c,$link_order,$link_sort,$link_sort_c, $force_pick, $admin, $link_query, $start, $sql_type;

	if(!$cat)					$cat=0;
	if($ses["num_res"]=="all")
		$lim="";
	else
	{	if($ses["num_res"])
			$lim=$ses["num_res"];
	}

	//sort vars
	if($link_order_c)			$link_order=$link_order_c;
	if($link_sort_c)			$link_sort=$link_sort_c;
	
	if($force_pick)
		$orderby=" ORDER BY link_pick desc, ".$ses["link_order"]." ".$ses["link_sort"]." ";	
	else	
		$orderby=" ORDER BY ".$ses["link_order"]." ".$ses["link_sort"]." ";
			
	$link_query.=get_fav_links();
	//code below fixed multple link listings

	if($sql_type!="mssql")
	{
		$link_query=ereg_replace("SELECT","SELECT DISTINCT",$link_query);
		$link_query=ereg_replace("cat_id, ","inl_links.link_id, ",$link_query);
	}
	return print_links($link_query.$orderby, "list_fav_links", $lim, $start);
}
function insert_link_count()
{	global $cat, $admin, $t, $link_query, $having, $conn, $sql_type, $ses,
	$sid, $extended_search, $multiple_search_instances;
	if(!$cat)
		$cat=0;
	
	$query = $link_query;

	if($t=="top")
		$query=$link_query.get_top_links();
	elseif($t=="pop")
		$query=$link_query.get_pop_links();
	elseif($t=="new")
		$query=$link_query.get_new_links();
	elseif($t=="pick")
		$query=$link_query.get_pick_links();
	elseif($t=="modify")
		$query=$link_query.get_user_links();
	elseif($t=="favorites")
		$query=$link_query.get_fav_links();
	
	elseif($t=="display_link_search" || $t=="search_links")
	{	
		
		$query = $link_query;
		$que = "SELECT log_keyword, log_search, search_action, search_cat FROM inl_search_log where log_id='$having'";
		$rs = &$conn->Execute($que);
		if ($rs && !$rs->EOF) 
		{
			$h = stripslashes($rs->fields[0]);
			$search_type = $rs->fields[1];
			$act = $rs->fields[2];
			$cat = $rs->fields[3];
		}
		if ($search_type == 1)
		{
			if ($extended_search == 1)
			{
				if (!is_table_exist("inl_$sid"))
					do_extended_search($h, $cat, $act);
				$query = "SELECT * FROM inl_$sid";
			}
			elseif ($extended_search == 0)
			{
				if(!$multiple_search_instances)
				if($sql_type!="mssql")
				{
					$query = ereg_replace("SELECT","SELECT DISTINCT", $query);
					$query = ereg_replace("cat_id, ","inl_links.link_id, ", $query);
				}
				if (!$admin)
					$query = ereg_replace("cat_id, ","inl_cats.cat_id, ",$query);
				$query = $query.do_simple_search($h, $cat, $act);
			}
		}
		elseif ($search_type == 2)
		{
			if(!$multiple_search_instances)
				if($sql_type!="mssql")
				{
					$query = ereg_replace("SELECT","SELECT DISTINCT", $query);
					$query = ereg_replace("cat_id, ","inl_links.link_id, ", $query);
				}
				if (!$admin)
				$query = ereg_replace("cat_id, ","inl_cats.cat_id, ",$query);		

				$query = $query.adv_search_parsing($h, "link");
		}

	}
	elseif($t=="pending_links")
	{
		$query = $link_query;
		
		if ($having)
		{
			$que = "SELECT log_keyword, log_search from inl_search_log where log_id='$having'";
			$rs = &$conn->Execute($que);
			if ($rs && !$rs->EOF) 
			{
				$search_word = stripslashes($rs->fields[0]);
				$search_type = $rs->fields[1];
			}
				if ($search_type == 1)
				{
					$array = get_keywords($search_word);
					$field_array = do_search_where();
					$string = get_where_string($field_array, $array, "OR", "OR");
					$string = ereg_replace("OR)",")", $string);
					$string = ereg_replace(" OR $","", $string);
					if ($admin == 1)
						$vis = " AND inl_lc.link_pend!=0";
					if($ses["user_perm"]==5) // editor
					{
						$query = ereg_replace("cat_id","inl_lc.cat_id", $query);
						$query.= "LEFT JOIN inl_cats ON inl_lc.cat_id=inl_cats.cat_id ";
						$vis = " AND inl_lc.link_pend!=0 AND inl_cats.cat_user=".$ses["user_id"];
					}
					$string = "WHERE (".$string.")".$vis;
					$query.= $string;
				}
				elseif ($search_type==2)
				{
					if($sql_type!="mssql")
					{
						$query = ereg_replace("SELECT","SELECT DISTINCT", $query);
						$query = ereg_replace("cat_id, ","inl_lc.cat_id, ", $query);
					}
					$query.= adv_search_parsing($search_word, "link", 1);				
				}
		}
		else 
		{
			if($ses["user_perm"]==5) // editor
			{
				$query = ereg_replace("cat_id","inl_lc.cat_id", $query);
				$query.= "LEFT JOIN inl_cats ON inl_lc.cat_id=inl_cats.cat_id WHERE inl_lc.link_pend!=0 AND inl_cats.cat_user = ".$ses["user_id"]." ";
			}
			else
				$query.= " WHERE inl_lc.link_pend!=0  ";
		}
	}

	elseif($t=="links_prev"){
		$query="SELECT distinct inl_links.link_id, link_name, link_pick, link_desc, link_date, link_hits, link_rating, link_votes, link_numrevs, link_image, link_user, link_url FROM inl_links LEFT JOIN inl_lc USING (link_id) LEFT JOIN inl_reviews ON inl_links.link_id=inl_reviews.rev_link where rev_pend=1";
	}
	else
		$query.= "WHERE inl_lc.cat_id=$cat AND inl_lc.link_pend=0 $vis ";
	
	if ($sql_type!="mssql" && ($t=="top" || $t=="pop" || $t=="new" || $t=="pick" || $t=="modify"))
	{	
		$query=ereg_replace("SELECT","SELECT DISTINCT",$query);
		$query=ereg_replace("cat_id, ","inl_links.link_id, ",$query);
	}



	$rs = &$conn->Execute($query);
	if($rs && !$rs->EOF) 
		$recordCount = $rs->RecordCount();
	else
		$recordCount = 0;
	
	if($t=="modify")
	{
		$rs = &$conn->Execute("SELECT DISTINCT inl_lc.link_id FROM inl_lc,inl_links WHERE inl_lc.link_id = inl_links.link_id AND link_pend<0 AND link_user=".$ses["user_id"]);
		
		if($rs && !$rs->EOF) 
			$recordCount -= $rs->RecordCount();
	}

	return $recordCount;

}
function link_link()
{	global $admin, $link_data, $sid, $session_get, $show_status_url;

	if($sid && $session_get)
		$att_sid="sid=$sid&";
	
	if($show_status_url)
		$url="url=".$link_data[17].'&';
	else
		$url="";

	if($admin==1)
		return "../action.php?$att_sid$url"."action=go&id=".$link_data[0];
	else
		return "../../action.php?$att_sid$url"."action=go&id=".$link_data[0];
	
}
function link_name()
{	global $link_data, $t;
	if ($t == "display_link_search" || $t == "search_links")
		return stripslashes(do_search_lighting("link_name", $link_data[1]));
	else
		return stripslashes($link_data[1]);
}
function link_url()
{	global $link_data;
	return "".$link_data[17];
}
function link_pick()
{
	global $la_pick, $admin,$lu_link_pick, $link_data;
	if($admin==1){$o=$la_pick;}else{$o=$lu_link_pick;}
	if ($link_data[2] == 1)
		return $o;
	else
		return "";
}
function link_desc()
{	global $link_data, $t;
	if ($t == "display_link_search" || $t == "search_links")
		return nl2br(do_search_lighting("link_desc", $link_data[3]));
	else
		return nl2br($link_data[3]);
}
function link_image()
{	global $default_image,$link_data;
	if(!$link_data[9])
		return $default_image;
	else
		return $link_data[9];
}
function link_date()
{	global $datefmt, $link_data;
	return date($datefmt,$link_data[4]);
}
function link_hits()
{	global $link_data;
	return $link_data[5];
}
function link_rating_txt()
{	global $link_data;
	return sprintf ("%01.2f", $link_data[6]);
}
function link_rating_img()
{	global $link_data;
	return "rating/".num_to_image($link_data[6]).".gif";
}
function link_votes()
{	global $link_data;
	return $link_data[7];
}
function link_reviews()
{	global $conn, $id, $t, $link_data;
	if($t=="list_pend_reviews")
	{	$query="select count(rev_id) from inl_reviews where rev_link=$id and rev_pend=1";
		$rs = &$conn->Execute($query);
		return $rs->fields[0];
	}
	else
		return $link_data[8];
}
function link_pend_review()
{	global $link_data, $toprate, $tophits, $sid, $session_get;
	
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	return "navigate.php?$att_sid"."id=$link_data[0]&t=list_pend_reviews&toprate=$toprate&tophits=$tophits";
}
function link_review_link()
{	global $admin, $link_data, $toprate, $tophits, $attach, $having, $sid, $session_get, $ses, $cat, $t;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	if($admin==1)							
		$ret="navigate.php?$att_sid"."id=$link_data[0]&t=reviews&toprate=$toprate&tophits=$tophits";
	else
		$ret="../../index.php?$att_sid"."id=$link_data[0]&t=reviews&toprate=$toprate&tophits=$tophits";

	if(strlen($attach)>0){$ret.="&attach=$attach";}
	if(strlen($having)>0){$ret.="&having=$having";}
	return $ret;
}
function insert_pagenav()
{	global $t, $cat, $having, $ses, $link_query, $thisfile, $start, $pagenav, $review_query, $conn, $sid, $extended_search, $admin, $multiple_search_instances, $id;
		
	if ($t)
	 $ar["t"] = $t;
	if ($id)
	 $ar["id"] = $id;
	if (!$cat) 
		$cat=0;
	
	$query = $link_query;

	if ($t=="pending_links")
	{
		if ($having)
		{
			$que = "SELECT log_keyword, log_search from inl_search_log where log_id='$having'";
			$rs = &$conn->Execute($que);
			if ($rs && !$rs->EOF) 
			{
				$search_word = stripslashes($rs->fields[0]);
				$search_type = $rs->fields[1];
			}
				if ($search_type == 1)
				{
					$array = get_keywords($search_word);
					$field_array = do_search_where();
					$string = get_where_string($field_array, $array, "OR", "OR");
					$string = ereg_replace("OR)",")", $string);
					$string = ereg_replace(" OR $","", $string);
					if ($admin == 1)
						$vis = " AND inl_lc.link_pend!=0";
					if($ses["user_perm"]==5) // editor
					{
						$query = ereg_replace("cat_id","inl_lc.cat_id", $query);
						$query.= "LEFT JOIN inl_cats ON inl_lc.cat_id=inl_cats.cat_id";
						$vis = " AND inl_lc.link_pend!=0 AND inl_cats.cat_user = ".$ses["user_id"];
					}
					$string = "WHERE (".$string.")".$vis;
					$query.= $string;
				}
				elseif ($search_type==2)
				{
					if($sql_type!="mssql")
					{
						$query = ereg_replace("SELECT","SELECT DISTINCT", $query);
						$query = ereg_replace("cat_id, ","inl_cats.cat_id, ", $query);
					}
					$query.= adv_search_parsing($search_word, "link", 1);				
				}
		}
		else 
		{
			if($ses["user_perm"]==5) // editor
			{
				$query = ereg_replace("cat_id","inl_lc.cat_id", $query);
				$query.= "LEFT JOIN inl_cats ON inl_lc.cat_id=inl_cats.cat_id WHERE inl_lc.link_pend!=0 AND inl_cats.cat_user = ".$ses["user_id"]." ";
			}
			else
				$query.= " WHERE inl_lc.link_pend!=0  ";
		}	
	}
	// SEARCH PART
	elseif ($t=="display_link_search" || $t=="display_cat_search" || $t=="search_links" || $t=="search_cats")
	{
		$que = "SELECT log_keyword, log_search, log_type, search_action, search_cat FROM inl_search_log where log_id='$having'";
		$rs = &$conn->Execute($que);
		if ($rs && !$rs->EOF) 
		{
			$search_word = stripslashes($rs->fields[0]);
			$search_type = $rs->fields[1];
			$search_cat_or_link = $rs->fields[2];
			$act = $rs->fields[3];
			$cat = $rs->fields[4];
		}
		
		if ($search_type == 1)
		{
			if ($extended_search == 1)
			{
				if ($search_cat_or_link == 2)
				{
					//	Navigation for LINKS ->> SIMPLE-EXTENDED
					if (!is_table_exist("inl_$sid"))
						do_extended_search($search_word, $cat, $act);
					$query = "SELECT link_id, link_name, link_pick, link_desc, link_date, link_hits, link_rating, link_votes, link_numrevs, link_image, cust1, cust2, cust3, cust4, cust5, cust6, link_user, link_url, cat_id, link_vis, user_name, email FROM inl_$sid";
					
				}
			}
			elseif ($extended_search == 0)
			{	
				if ($search_cat_or_link == 2)
				{
					//	Navigation for LINKS ->> SIMPLE-SIMPLE
					if(!$multiple_search_instances)
						if($sql_type!="mssql")
						{
							$query = ereg_replace("SELECT","SELECT DISTINCT", $query);
							$query = ereg_replace("cat_id, ","inl_links.link_id, ", $query);
						}
						if (!$admin)
						$query = ereg_replace("cat_id, ","inl_cats.cat_id, ",$query);
										
						$have = do_simple_search($search_word, $cat, $act);
				}
			}
		}
		elseif ($search_type == 2)
		{
			if ($search_cat_or_link == 2)
			{
				//	Navigation for LINKS ->> ADVANCED
				if(!$multiple_search_instances)
					if($sql_type!="mssql")
					{
						$query = ereg_replace("SELECT","SELECT DISTINCT", $query);
						$query = ereg_replace("cat_id, ","inl_links.link_id, ", $query);
					}
				if (!$admin)
					$query = ereg_replace("cat_id, ","inl_cats.cat_id, ",$query);
						
				
				$have = adv_search_parsing($search_word, "link");
			}
		}
		
		$query.= $have;
	}
	elseif ($t=="links_prev" || $t=="reviews" || $t=="list_pend_reviews") 
		$query = $review_query;
	else
	{	
		$pas_query = $link_query;
	}
	
	if ($having)
		$ar["having"] = $having;

	if ($ses["num_res"]!="all")
		pagenav($cat, $query.$having1, $thisfile, $start, $ar);
	return $pagenav."";
}
function link_rate_link()
{	global $link_data,$toprate, $tophits, $attach, $having, $sid, $session_get, $t;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	$ret="../../index.php?$att_sid"."id=$link_data[0]&t=rate&toprate=$toprate&tophits=$tophits";


	if(strlen($attach)>0){$ret.="&attach=$attach";}
	if(strlen($having)>0){$ret.="&having=$having";}
	return $ret;
}
function link_top()
{
	global $la_top, $admin, $lu_link_top, $link_data, $toprate;
	if($admin==1){$o=$la_top;}else{$o=$lu_link_top;}
	if ($link_data[6] >= $toprate)
		return $o;
	else
		return "";
}
function link_remove_fav()
{
	global $HTTP_GET_VARS,$link_data;
	if ($HTTP_GET_VARS && is_array($HTTP_GET_VARS))
	{	reset($HTTP_GET_VARS);
		while (list ($key, $value) = each($HTTP_GET_VARS))	
			if($key!="fav")
				$form_values.="&$key=" . rawurlencode(inl_escape($value));
	}
	return "../../action.php?action=del_fav&fav=$link_data[0]$form_values";
}
function link_add_fav()
{	global $HTTP_GET_VARS,$link_data;
	if ($HTTP_GET_VARS && is_array($HTTP_GET_VARS))
	{	reset($HTTP_GET_VARS);
		while (list ($key, $value) = each($HTTP_GET_VARS))	
			if($key!="fav")
				$form_values.="&$key=" . rawurlencode(inl_escape($value));
	}
	return "../../action.php?action=add_fav&fav=$link_data[0]$form_values";
}
function link_pop()
{
	global $la_pop, $admin,$lu_link_pop,$link_data,$tophits;
	if($admin==1){$o=$la_pop;}else{$o=$lu_link_pop;}
	if ($link_data[5] >= $tophits)
		return $o;
	else
		return "";
}
function  link_new()
{
	global $la_new, $admin, $lu_link_new, $link_data,$link_new;
	if($admin==1){$o=$la_new;}else{$o=$lu_link_new;}
	if ($link_data[4] >= mktime(0,0,0,date("m"),date("d")-$link_new,date("Y")))
		return $o;
	else
		return "";
}
function link_cust1()
{	global $link_data;
	return $link_data[10];
}
function link_cust2()
{	global $link_data;
	return $link_data[11];
}
function link_cust3()
{	global $link_data;
	return $link_data[12];
}
function link_cust4()
{	global $link_data;
	return $link_data[13];
}
function link_cust5()
{	global $link_data;
	return $link_data[14];
}
function link_cust6()
{	global $link_data;
	return $link_data[15];
}		
function link_path()
{	global $link_data, $lu_nav_home, $la_nav_home, $admin, $multiple_search_instances, $t, $extended_search;

	//if !multiple_search_instances and searching, don't show link path
	if((($t== "list_search_links") || ($t == "display_link_search")) && !$multiple_search_instances && !$extended_search)
		return "";
	if($admin==1)
		$l_h=$la_nav_home;
	else	
		$l_h=$lu_nav_home;
	if($link_data[18]==0)
		return $l_h;
	elseif($link_data[18])
		return linkpath($link_data[18]);
	else 
		return "";
}
function link_user_name()
{	global $link_data;
	return $link_data[20];			
}
function link_user_email()
{	global $link_data;
	return $link_data[21];
}


function insert_list_cats()
{	global $ses, $cat, $admin,$cat_order, $cat_order_c , $cat_sort, $cat_sort_c, $cat_query;
	if(!$cat)			$cat=0;
	if($cat_order_c)	$cat_order=$cat_order_c;
	if($cat_sort_c)		$cat_sort=$cat_sort_c;
	if($admin==1){$vis="";}else{$vis="AND cat_vis=1 ";}
	$cat_query.=" WHERE cat_sub=$cat AND cat_pend=0 $vis";
	
	$orderby=" ORDER BY ".$ses["cat_order"]." ".$ses["cat_sort"];

	return print_cats($cat_query.$orderby);
}
function insert_search_cats()
{	global $ses, $cat, $admin, $cat_order, $cat_order_c , $cat_sort, $cat_sort_c, $cat_query, $having, $conn;
	if (!$cat)
		$cat=0;
	if ($cat_order_c)
		$cat_order=$cat_order_c;
	if ($cat_sort_c)
		$cat_sort=$cat_sort_c;
	
	$que = "SELECT log_keyword, log_search FROM inl_search_log where log_id='$having'";
	$rs = &$conn->Execute($que);
	if ($rs && !$rs->EOF) 
	{
		$search_word = $rs->fields[0];
		$search_type = $rs->fields[1];
	}
	if ($search_type == 1)
	{
		$array = get_keywords($search_word);
		$field_array = do_search_where_cats();
		$string = get_where_string($field_array, $array, "OR", "OR");
		$string = ereg_replace("OR)",")", $string);
		$string = ereg_replace(" OR $","", $string);

		if ($admin == 1)
			$vis = "";
		else 
			$vis=" AND inl_cats.cat_vis=1 and inl_cats.cat_pend<1";
		
		$string = "WHERE (".$string.$vis.")";
		$search_cat_query = $cat_query.$string;
		
	}
	elseif ($search_type == 2)
		$search_cat_query = $cat_query.adv_search_parsing($search_word, "cat");

	$orderby=" ORDER BY ".$ses["cat_order"]." ".$ses["cat_sort"];

	return print_cats($search_cat_query.$orderby);
}

function insert_cat_count()
{	global $conn, $cat, $having, $admin, $cat_query, $t, $admin, $ses; 
	if(!$cat)
		$cat=0;
	$query = $cat_query;

	if($t=="display_cat_search" || $t=="search_cats")
	{	
		$que = "SELECT log_keyword, log_search FROM inl_search_log where log_id='$having'";
		$rs = &$conn->Execute($que);
		if ($rs && !$rs->EOF) 
		{
			$search_word = $rs->fields[0];
			$search_type = $rs->fields[1];
		}
		if ($search_type == 1)
		{
			$array = get_keywords($search_word);
			$field_array = do_search_where_cats();
			$string = get_where_string($field_array, $array, "OR", "OR");
			$string = ereg_replace("OR)",")", $string);
			$string = ereg_replace(" OR $","", $string);
			
			if ($admin == 1)
				$vis = "";
			else 
				$vis=" AND inl_cats.cat_vis=1 and inl_cats.cat_pend<1";

			$string = "WHERE (".$string.$vis.")";
			$query.= $string;
		}
		elseif ($search_type == 2)
			$query.= adv_search_parsing($search_word, "cat");
	}
	elseif($t=="pending_cats")
	{	
				
		if ($having)
		{
			$que = "SELECT log_keyword, log_search FROM inl_search_log where log_id='$having'";
			$rs = &$conn->Execute($que);
			if ($rs && !$rs->EOF) 
			{
				$search_word = $rs->fields[0];
				$search_type = $rs->fields[1];
			}
			if ($search_type == 1)
			{
				$array = get_keywords($search_word);
				$field_array = do_search_where_cats();
				$string = get_where_string($field_array, $array, "OR", "OR");
				$string = ereg_replace("OR)",")", $string);
				$string = ereg_replace(" OR $","", $string);
				
				if($ses["user_perm"]==5)
				{
					$sub_cats = pending_cats_in_list_for_editor();
					if ($sub_cats == "")
						$sub_cats = -1;
					$vis = " AND inl_cats.cat_sub IN (".$sub_cats.")";
				}
				$vis.= " AND inl_cats.cat_pend!=0";	
				$query.= "WHERE (".$string.")".$vis;
			}	
			elseif ($search_type == 2)
				$query.= adv_search_parsing($search_word, "cat", 1);
	//		echo $query;
		}
		else
		{
			if($ses["user_perm"]==5)
			{
				$sub_cats = pending_cats_in_list_for_editor();
				if ($sub_cats == "")
					$sub_cats = -1;
				$vis = " inl_cats.cat_sub IN (".$sub_cats.") AND";
			}
			$vis.= " inl_cats.cat_pend!=0" ;	
			$query.= "WHERE".$vis;
			
		}
	}
	else
	{	
		if ($admin==1)
			$vis="";
		else
			$vis="AND cat_vis=1 ";
		$query.= "WHERE cat_sub=$cat AND cat_pend=0 $vis";
	}
	$rs = &$conn->Execute($query); 
	if ($rs && !$rs->EOF)
		return $rs->RecordCount();
	else
		return "0";
}
function insert_pending_links()
{	global $ses, $pend, $cat, $lim, $link_order_c,$link_order,$link_sort,$link_sort_c, $force_pick, $admin, $link_query, $start , $having, $conn, $sql_type, $multiple_search_instances;
	
	$pend = 1;
	
	if($ses["num_res"]=="all")
		$lim="";
	else
	{	if($ses["num_res"])
			$lim=$ses["num_res"];
	}

	//sort vars
	if($link_order_c)			$link_order=$link_order_c;
	if($link_sort_c)			$link_sort=$link_sort_c;
	
	if($force_pick)
		$orderby=" ORDER BY link_pick desc, ".$ses["link_order"]." ".$ses["link_sort"]." ";	
	else	
		$orderby=" ORDER BY ".$ses["link_order"]." ".$ses["link_sort"]." ";
				
	$query = $link_query;
		
	if ($having)
	{
		$que = "SELECT log_keyword, log_search from inl_search_log where log_id='$having'";
		$rs = &$conn->Execute($que);
		if ($rs && !$rs->EOF) 
		{
			$search_word = stripslashes($rs->fields[0]);
			$search_type = $rs->fields[1];
		}
			if ($search_type == 1)
			{
				$array = get_keywords($search_word);
				$field_array = do_search_where();
				$string = get_where_string($field_array, $array, "OR", "OR");
				$string = ereg_replace("OR)",")", $string);
				$string = ereg_replace(" OR $","", $string);
				if ($admin == 1)
						$vis = " AND inl_lc.link_pend!=0";
				if($ses["user_perm"]==5) // editor
				{
					$query = ereg_replace("cat_id","inl_lc.cat_id", $query);
					$query.= "LEFT JOIN inl_cats ON inl_lc.cat_id=inl_cats.cat_id ";
					$vis = " AND inl_lc.link_pend!=0 AND inl_cats.cat_user=".$ses["user_id"];
				}
				$string = "WHERE (".$string.")".$vis;
				$query.= $string;
			}
			elseif ($search_type==2)
			{
				if($sql_type!="mssql")
				{
					$query = ereg_replace("SELECT","SELECT DISTINCT", $query);
					$query = ereg_replace("cat_id, ","inl_lc.cat_id, ", $query);
				}
				$query.= adv_search_parsing($search_word, "link", 1);				
			}
	}
	else 
	{
		if($ses["user_perm"]==5) // editor
		{
			$query = ereg_replace("cat_id","inl_lc.cat_id", $query);
			$query.= "LEFT JOIN inl_cats ON inl_lc.cat_id=inl_cats.cat_id";
			$query.= " WHERE inl_lc.link_pend!=0 AND inl_cats.cat_user = ".$ses["user_id"];
		}
		else
			$query.= " WHERE inl_lc.link_pend!=0  ";
	}

	/*
	if($sql_type!="mssql")
	{	$link_query = ereg_replace("SELECT","SELECT DISTINCT",$link_query);
		$link_query = ereg_replace("cat_id, ","inl_links.link_id, ",$link_query);
		
		if($sql_type!="postgres7")
			$link_query.=" GROUP BY inl_links.link_id";
	}
	*/

	if($sql_type!="postgres7")
		return print_links($query.$orderby, "pend_links", $lim, $start);
	else
	{
		$new_link_query=ereg_replace("SELECT DISTINCT","SELECT DISTINCT ON (link_id)",$query);
		$inside_orderby=ereg_replace("ORDER BY ","ORDER BY  link_id,",$orderby);
		//return print_links("SELECT * FROM (".$new_link_query.$having1.$inside_orderby.") AS foo ".$orderby, "pend_links", $lim, $start);
		return print_links("SELECT * FROM (".$new_link_query.$inside_orderby.") AS foo ".$orderby, "pend_links", $lim, $start);
	}
}
function insert_pending_cats()
{
	global $ses, $pend, $cat, $admin, $cat_order, $cat_order_c , $cat_sort, $cat_sort_c, $cat_query, $having, $conn;
	$pend=1;
	if($cat_order_c)	$cat_order=$cat_order_c;
	if($cat_sort_c)		$cat_sort=$cat_sort_c;
	
	$query = $cat_query;

	$orderby=" ORDER BY ".$ses["cat_order"]." ".$ses["cat_sort"];
	
	if ($having)
	{
		$que = "SELECT log_keyword, log_search FROM inl_search_log where log_id='$having'";
		$rs = &$conn->Execute($que);
		if ($rs && !$rs->EOF) 
		{
			$search_word = $rs->fields[0];
			$search_type = $rs->fields[1];
		}
		if ($search_type == 1)
		{
			$array = get_keywords($search_word);
			$field_array = do_search_where_cats();
			$string = get_where_string($field_array, $array, "OR", "OR");
			$string = ereg_replace("OR)",")", $string);
			$string = ereg_replace(" OR $","", $string);
			
			if($ses["user_perm"]==5)
			{
				$sub_cats = pending_cats_in_list_for_editor();
				if ($sub_cats == "")
					$sub_cats = -1;
				$vis = " AND inl_cats.cat_sub IN (".$sub_cats.")";
			}
			$vis.= " AND inl_cats.cat_pend!=0";	
					
			$string = "WHERE (".$string.")".$vis;
			$query.= $string;
		}	
		elseif ($search_type == 2)
			$query.= adv_search_parsing($search_word, "cat", 1);
	}
	else 
	{
		if ($admin == 1)
		{
			if($ses["user_perm"]==5)
			{
				$sub_cats = pending_cats_in_list_for_editor();
				if ($sub_cats == "")
					$sub_cats = -1;
				$vis = "inl_cats.cat_sub IN (".$sub_cats.") AND ";
			}
			$vis.= "inl_cats.cat_pend!=0";
			$query.= "WHERE (".$vis.")";
		}
	}
	return print_cats($query.$orderby);
}

function insert_title()
{	global $sitename;
	return $sitename;
}

function preserve_order()
{	global $sid, $ses, $session_get, $cat, $t, $having;

	if(!$sid)
		$sid=init_session();

	if($sid && $session_get)
		$att_sid="sid=$sid&";

	if($having)
		$att_hav="&having=$having";

	$ses["destin"]="$att_sid"."t=$t&cat=$cat$att_hav";
	save_session($sid);
	
	return "";
}
function form_action_login()
{	global $attach, $having, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	$ret="../../action.php?$att_sid"."action=login";
	if(strlen($attach)>0){$ret.="&attach=$attach";}
	if(strlen($having)>0){$ret.="&having=$having";}
	return $ret;
}
function insert_login()
{	global $ses;
	if($ses["user_id"]>0)
		return "";
	else
		return parse("box_login");
}
function msg_welcome()
{	global $ses,$lu_welcome, $lu_not_logged_in, $conn;
	if($ses["user_id"]>0)
	{	$rs=&$conn->Execute("SELECt user_name FROM inl_users WHERE user_id=".$ses["user_id"]);
		if($rs && !$rs->EOF)
			return "$lu_welcome ".$rs->fields[0];
		else
			return $lu_not_logged_in;
	}
	else
		return $lu_not_logged_in;
}
function stats_links()
{	return stats_num_links();
}
function stats_cats()
{	return stats_num_cats();
}
function stats_hits()
{	return stats_num_linkhits();
}
function cat_num_links()
{	global $cat_data;
	return $cat_data[0];
}
function cat_image()
{	global $cat_data, $default_image;
	if(!$cat_data[1])
		return $default_image;
	else
		return $cat_data[1];
}
function cat_name()
{	global $cat_data, $lu_nav_home;
	if(!$cat_data[2])
		return $lu_nav_home;
	else
		return stripslashes($cat_data[2]);
}
function cat_desc()
{	global $cat_data;
	return nl2br(stripslashes($cat_data[4]));
}
function cat_sub_cats()
{	global $cat_data;
	return "".print_cat_subs($cat_data[6]);
}
function cat_link()
{	global $cat_data, $admin, $filedir, $theme, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	if($admin==1)
		return "navigate.php?$att_sid"."cat=".$cat_data[6];
	elseif(file_exists($filedir . "themes/" . $theme . "/$cat_data[6].tpl"))
		return "../../index.php?$att_sid"."t=$cat_data[6]&cat=$cat_data[6]";
	else
		return "../../index.php?$att_sid"."t=sub_pages&cat=$cat_data[6]";
}
function cat_new()
{	global $cat_data, $admin,$la_cat_new, $lu_cat_new, $cat_new;
	if($admin==1)
		$l_cat_new=$la_cat_new;
	else
		$l_cat_new=$lu_cat_new;
	$tem=mktime(0,0,0,date("m"),date("d")-$cat_new,date("Y"));
	if($cat_data[7]>=$tem)
		return $l_cat_new;
	else
		return "";
}
function cat_date()
{	global $cat_data,$datefmt; 
	return date($datefmt,$cat_data[7]);
}
function cat_path()
{	global $cat_data;
	return linkpath($cat_data[5]);
}
function cat_pick()
{	global $admin,$la_cat_pick,$lu_cat_pick, $cat_data;
	if($admin==1)
		$l_cat_pick=$la_cat_pick;
	else
		$l_cat_pick=$lu_cat_pick;

	if ($cat_data[8] == 1)
		return $l_cat_pick;
	else
		return "";
}

function name_cat_cust1()
{	global $cc1, $lu_categories_custom_fields1;
	if(!$cc1)
		return $lu_categories_custom_fields1;
	else 
		return $cc1;
}

function name_cat_cust2()
{	global $cc2, $lu_categories_custom_fields2;
	if(!$cc2)
		return $lu_categories_custom_fields2;
	else 
		return $cc2;
}

function name_cat_cust3()
{	global $cc3, $lu_categories_custom_fields3;
	if(!$cc3)
		return $lu_categories_custom_fields3;
	else 
		return $cc3;
}

function name_cat_cust4()
{	global $cc4, $lu_categories_custom_fields4;
	if(!$cc4)
		return $lu_categories_custom_fields4;
	else 
		return $cc4;
}

function name_cat_cust5()
{	global $cc5, $lu_categories_custom_fields5;
	if(!$cc5)
		return $lu_categories_custom_fields5;
	else 
		return $cc5;
}

function name_cat_cust6()
{	global $cc6, $lu_categories_custom_fields6;
	if(!$cc6)
		return $lu_categories_custom_fields6;
	else 
		return $cc6;
}

function cat_cust1()
{	global $cat_data;
	return $cat_data[10];
}

function cat_cust2()
{	global $cat_data;
	return $cat_data[11];
}
function cat_cust3()
{	global $cat_data;
	return $cat_data[12];
}
function cat_cust4()
{	global $cat_data;
	return $cat_data[13];
}
function cat_cust5()
{	global $cat_data;
	return $cat_data[14];
}
function cat_cust6()
{	global $cat_data;
	return $cat_data[15];
}

function insert_navbar()
{	
	global $cat_data, $search, $la_nav10, $cat, $thisfile, $navbar;
	if($search=="yes")
		return $la_nav10;
	else
	{	if(!isset($cat) && isset($cat_data[6]))
			$nav_cat=$cat_data[6]; //for cat sepecific calls
		elseif(!isset($cat) && !isset($cat_data[6]) && isset($link_data[18]))
			$nav_cat=$link_data[18];
		else
			$nav_cat=$cat;
		navbar($nav_cat,$thisfile);
		return $navbar;
	}
}
	
function form_action_suggestsite()
{	global $attach, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	$ret="../../action.php?$att_sid"."action=suggest";
	if(strlen($attach)>0){$ret.="&attach=$attach";}
	return $ret;
}
function del_link()
{	global $link_data, $cat, $attach, $having, $sid, $session_get,$t;
	
	if($sid && $session_get)
		$att_sid="sid=$sid&";

	$ret= "confirm.php?$att_sid"."action=dellink&cat=$link_data[18]&id=$link_data[0]";
	
	if(strlen($attach)>0){$ret.="&attach=$attach";}
	if(strlen($having)>0){$ret.="&having=$having";}
	return $ret;
}
function edit_link()
{	global $admin, $link_data, $attach, $having, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	if($admin==1)
		$ret="addlink.php?$att_sid"."editlink=yes&cat=$link_data[18]&id=$link_data[0]";
	else
		$ret="../../index.php?$att_sid"."t=modify_link&id=$link_data[0]";
			
	if(strlen($attach)>0){$ret.="&attach=$attach";}
	if(strlen($having)>0){$ret.="&having=$having";}
	return $ret;
}
function move_link()
{	global $cat, $link_data, $attach, $having, $sid, $session_get, $conn;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	if(!$cat)
	{
		$query="SELECT cat_id from inl_lc where link_id=$link_data[0] and pend=0";
		$rs=&$conn->Execute($query);
		if($rs && !$rs->EOF)
			$catfrom=$rs->fields[0];
	}
	else
		$catfrom=$cat;

	$ret="move.php?$att_sid"."cat=$cat&catfrom=$catfrom&id=$link_data[0]&type=link";
	if(strlen($attach)>0){$ret.="&attach=$attach";}
	if(strlen($having)>0){$ret.="&having=$having";}
	return $ret;
}
function add_link()
{	global $cat, $having, $attach,$la_button_add_link,$cat_data, $ses, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	//admin or root  or (editor and category user == current user)
	if($ses["user_perm"]==2 || $ses["user_perm"]==1 || ($ses["user_perm"]==5 && $cat_data[19]==$ses["user_id"])) 
	{	if(!$cat)
			$cat_pass="Home,";
		else
			$cat_pass="$cat,";

		return " <form action='addlink.php?$att_sid"."having=$having&attach=$attach' method='post'><input type='hidden' name='cat_list' value='$cat_pass'><input type='submit' name='Button' value='$la_button_add_link' class='button'></form>";
	}
	else
		return ""; //no rights
	
}
function del_cat()
{	global $cat, $cat_data, $having, $attach, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	if(!$cat){$cat=0;}
		$ret= "confirm.php?$att_sid"."action=delcat&cat=$cat&id=$cat_data[6]";
	if(strlen($attach)>0){$ret.="&attach=$attach";}
	if(strlen($having)>0){$ret.="&having=$having";}			
	return $ret;
}
function approve_cat()
{	global $cat_data, $having, $attach, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	$ret= "confirm.php?$att_sid"."action=approvecat&pendcats[$cat_data[6]]=ON";
	if(strlen($attach)>0){$ret.="&attach=$attach";}
	if(strlen($having)>0){$ret.="&having=$having";}
	return $ret;
}
function approve_link()
{
	global $link_data, $having, $attach, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	$ret= "confirm.php?$att_sid"."action=approvelink&pendlinks[$link_data[0]]=$link_data[18]";
	if(strlen($attach)>0){$ret.="&attach=$attach";}
	if(strlen($having)>0){$ret.="&having=$having";}
	return $ret;
}
function edit_cat()
{	global $cat, $cat_data, $having, $attach, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	$ret= "addcategory.php?$att_sid"."cat=$cat&id=$cat_data[6]";
	if(strlen($attach)>0){$ret.="&attach=$attach";}
	if(strlen($having)>0){$ret.="&having=$having";}
	return $ret;
}
function cat_id()
{	global $cat_data;
	return "$cat_data[6]";
}
function link_id()
{	global $link_data;
	return "$link_data[0]";
}
function move_cat()
{	global $cat, $cat_data, $attach, $having, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	$ret= "move.php?$att_sid"."cat=$cat&id=$cat_data[6]&type=cat";
	if(strlen($attach)>0){$ret.="&attach=$attach";}
	if(strlen($having)>0){$ret.="&having=$having";}
	return $ret;
}
function copy_cat()
{	global $cat, $cat_data, $attach, $having, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	$ret= "copy.php?$att_sid"."cat=$cat&id=$cat_data[6]&type=cat";
	if(strlen($attach)>0){$ret.="&attach=$attach";}
	if(strlen($having)>0){$ret.="&having=$having";}
	return $ret;
}
function add_cat()
{	global $cat, $having, $attach, $la_button_add_cat, $cat_data, $ses, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	//admin or root  or (editor and category user == current user)
	if($ses["user_perm"]==2 || $ses["user_perm"]==1 || ($ses["user_perm"]==5 && $cat_data[19]==$ses["user_id"])) 
		return "<form action='addcategory.php?$att_sid"."having=$having&attach=$attach' method='post'><input type='hidden' name='cat' value='$cat'><input type='submit' name='Button' value='$la_button_add_cat' class='button'></form>";
	else
		return ""; //no rights
	
}
function cat_drop_name()
{	global $ses, $admin, $la_drop_name , $lu_drop_name;
	if($ses["cat_order"]=="cat_name")
		$sel=" selected";
	else
		$sel="";
	if($admin==1)
		return "<option value='cat_name'$sel>$la_drop_name</option>";
	else
		return "<option value='cat_name'$sel>$lu_drop_name</option>";
}
function cat_drop_date()
{	global $ses, $admin, $la_drop_date , $lu_drop_date;
	if($ses["cat_order"]=="cat_date")
		$sel=" selected";
	else
		$sel="";
	if($admin==1)
		return "<option value='cat_date'$sel>$la_drop_date</option>";
	else
		return "<option value='cat_date'$sel>$lu_drop_date</option>";
}
function cat_drop_description()
{	global $ses, $admin, $la_drop_desc , $lu_drop_desc;
	if($ses["cat_order"]=="cat_desc")
		$sel=" selected";
	else
		$sel="";
	if($admin==1)
		return "<option value='cat_desc'$sel>$la_drop_desc</option>";
	else
		return "<option value='cat_desc'$sel>$lu_drop_desc</option>";
}
function cat_drop_user()
{	global $ses, $admin, $la_drop_user , $lu_drop_user;
	if($ses["cat_order"]=="cat_user")
		$sel=" selected";
	else
		$sel="";
	if($admin==1)
		return "<option value='cat_user'$sel>$la_drop_user</option>";
	else
		return "<option value='cat_user'$sel>$lu_drop_user</option>";
}
function cat_drop_numsubs()
{	global $ses, $admin, $la_drop_numsubs , $lu_drop_numsubs;
	if($ses["cat_order"]=="cat_cats")
		$sel=" selected";
	else
		$sel="";
	if($admin==1)
		return "<option value='cat_cats'$sel>$la_drop_numsubs</option>";
	else
		return "<option value='cat_cats'$sel>$lu_drop_numsubs</option>";
}
function cat_drop_perm()
{	global $ses, $admin, $la_drop_perm , $lu_drop_perm;
	if($ses["cat_order"]=="cat_perm")
		$sel=" selected";
	else
		$sel="";
	if($admin==1)
		return "<option value='cat_perm'$sel>$la_drop_perm</option>";
	else
		return "<option value='cat_perm'$sel>$lu_drop_perm</option>";
}
function cat_drop_vis()
{	global $ses, $admin, $la_drop_vis , $lu_drop_vis;
	if($ses["cat_order"]=="cat_vis")
		$sel=" selected";
	else
		$sel="";
	if($admin==1)
		return "<option value='cat_vis'$sel>$la_drop_vis</option>";
	else
		return "<option value='cat_vis'$sel>$lu_drop_vis</option>";
}
function cat_drop_numlinks()
{	global $ses, $admin, $la_drop_numlinks, $lu_drop_numlinks;
	if($ses["cat_order"]=="cat_links")
		$sel=" selected";
	else
		$sel="";
	if($admin==1)
		return "<option value='cat_links'$sel>$la_drop_numlinks</option>";
	else
		return "<option value='cat_links'$sel>$lu_drop_numlinks</option>";
}
function cat_drop_editors_pick()
{	global $ses, $admin, $la_drop_editors_pick, $lu_drop_editors_pick;
	if($ses["cat_order"]=="cat_pick")
		$sel=" selected";
	else
		$sel="";
	if($admin==1)
		return "<option value='cat_pick'$sel>$la_drop_editors_pick</option>";
	else
		return "<option value='cat_pick'$sel>$lu_drop_editors_pick</option>";
}
function cat_drop_image()
{	global $ses, $admin, $la_drop_image, $lu_drop_image;
	if($ses["cat_order"]=="cat_image")
		$sel=" selected";
	else
		$sel="";
	if($admin==1)
		return "<option value='cat_image'$sel>$la_drop_image</option>";
	else
		return "<option value='cat_image'$sel>$lu_drop_image</option>";		
}
function cat_drop_ascending()
{	global $ses, $admin, $la_drop_ascending, $lu_drop_ascending;
	if($ses["cat_sort"]=="asc")
		$sel=" selected";
	else
		$sel="";
	if($admin==1)
		return "<option value='asc'$sel>$la_drop_ascending</option>";
	else
		return "<option value='asc'$sel>$lu_drop_ascending</option>";
}
function cat_drop_descending()
{	global $ses, $admin,$la_drop_descending, $lu_drop_descending;
	if($ses["cat_sort"]=="desc")
		$sel=" selected";
	else
		$sel="";
	if($admin==1)
		return "<option value='desc'$sel>$la_drop_descending</option>";
	else
		return "<option value='desc'$sel>$lu_drop_descending</option>";
}
function link_drop_name()
{	global $ses, $admin,$la_drop_name, $lu_drop_name;
	if($ses["link_order"]=="link_name")
		$sel=" selected";
	else
		$sel="";
	if($admin==1)
		return "<option value='link_name'$sel>$la_drop_name</option>";
	else
		return "<option value='link_name'$sel>$lu_drop_name</option>";
}
function link_drop_date()
{	global $ses, $admin,$la_drop_date, $lu_drop_date;
	if($ses["link_order"]=="link_date")
		$sel=" selected";
	else
		$sel="";
	if($admin==1)
		return "<option value='link_date'$sel>$la_drop_date</option>";
	else
		return "<option value='link_date'$sel>$lu_drop_date</option>";
}
function link_drop_description()
{	global $ses, $admin,$la_drop_desc, $lu_drop_desc;
	if($ses["link_order"]=="link_desc")
		$sel=" selected";
	else
		$sel="";
	if($admin==1)
		return "<option value='link_desc'$sel>$la_drop_desc</option>";
	else
		return "<option value='link_desc'$sel>$lu_drop_desc</option>";
}
function link_drop_user()
{	global $ses, $admin, $la_drop_user, $lu_drop_user;
	if($ses["link_order"]=="link_user")
		$sel=" selected";
	else
		$sel="";
	if($admin==1)
		return "<option value='link_user'$sel>$la_drop_user</option>";
	else
		return "<option value='link_user'$sel>$lu_drop_user</option>";
}
function link_drop_vis()
{	global $ses, $admin, $la_drop_vis, $lu_drop_vis;
	if($ses["link_order"]=="link_vis")
		$sel=" selected";
	else
		$sel="";
	if($admin==1)
		return "<option value='link_vis'$sel>$la_drop_vis</option>";
	else
		return "<option value='link_vis'$sel>$lu_drop_vis</option>";
}
function link_drop_editors_pick()
{	global $ses, $admin, $la_drop_editors_pick, $lu_drop_editors_pick;
	if($ses["link_order"]=="link_pick")
		$sel=" selected";
	else
		$sel="";
	if($admin==1)
		return "<option value='link_pick'$sel>$la_drop_editors_pick</option>";
	else
		return"<option value='link_pick'$sel>$lu_drop_editors_pick</option>";
}
function link_drop_image()
{	global $ses, $admin, $la_drop_image, $lu_drop_image;
	if($ses["link_order"]=="link_image")
		$sel=" selected";
	else
		$sel="";
	if($admin==1)
		return "<option value='link_image'$sel>$la_drop_image</option>";
	else
		return "<option value='link_image'$sel>$lu_drop_image</option>";
}
function link_drop_url()
{	global $ses, $admin, $la_drop_url, $lu_drop_url;
	if($ses["link_order"]=="link_url")
		$sel=" selected";
	else
		$sel="";
	if($admin==1)
		return "<option value='link_url'$sel>$la_drop_url</option>";
	else
		return "<option value='link_url'$sel>$lu_drop_url</option>";
}
function link_drop_rating()
{	global $ses, $admin, $la_drop_rating, $lu_drop_rating;
	if($ses["link_order"]=="link_rating")
		$sel=" selected";
	else
		$sel="";
	if($admin==1)
		return "<option value='link_rating'$sel>$la_drop_rating</option>";
	else
		return "<option value='link_rating'$sel>$lu_drop_rating</option>";
}
function link_drop_votes()
{	global $ses, $admin, $la_drop_votes, $lu_drop_votes;
	if($ses["link_order"]=="link_votes")
		$sel=" selected";
	else
		$sel="";
	if($admin==1)
		return "<option value='link_votes'$sel>$la_drop_votes</option>";
	else
		return "<option value='link_votes'$sel>$lu_drop_votes</option>";
}
function link_drop_hits()
{	global $ses, $admin, $la_drop_hits,$lu_drop_hits;
	if($ses["link_order"]=="link_hits")
		$sel=" selected";
	else
		$sel="";
	if($admin==1)
		return "<option value='link_hits'$sel>$la_drop_hits</option>";
	else
		return "<option value='link_hits'$sel>$lu_drop_hits</option>";
}
function link_drop_ascending()
{	global $ses, $admin, $la_drop_ascending, $lu_drop_ascending;
	if($ses["link_sort"]=="asc")
		$sel=" selected";
	else
		$sel="";
	if($admin==1)
		return "<option value='asc'$sel>$la_drop_ascending</option>";
	else
		return "<option value='asc'$sel>$lu_drop_ascending</option>";
}
function link_drop_descending()
{	global $ses, $admin, $la_drop_descending, $lu_drop_descending;
	if($ses["link_sort"]=="desc")
		$sel=" selected";
	else
		$sel="";
	if($admin==1)
		return "<option value='desc'$sel>$la_drop_descending</option>";
	else
		return "<option value='desc'$sel>$lu_drop_descending</option>";
}
function insert_list_reviews()
{
	global $review_sort, $review_order, $cat, $ses, $lim, $start, $t,$review_sort, $review_order, $id, $review_query;
	if(!$cat)$cat=0;
	if($ses["num_res"]=="all")
		$lim="";
	else
	{	if($ses["num_res"])
			$lim=$ses["num_res"];
	}

	//sort vars
	if($t=="list_pend_reviews")
		$p="1";
	else
		$p="0";
	$orderby=" ORDER BY $review_order  $review_sort ";	
	$review_query="select rev_id, rev_text, rev_user, rev_date, rev_pend, rev_link from inl_reviews where rev_link=$id and rev_pend=$p";
	return print_reviews($review_query.$orderby,$lim,$start);
}

function insert_rev_count()
{
	global $lim, $start, $t,$ses,  $id, $conn;

	if($ses["num_res"]=="all")
		$lim="";
	else
	{	if($ses["num_res"])
			$lim=$ses["num_res"];
	}
	//sort vars
	if($t=="list_pend_reviews")
		$p="1";
	else
		$p="0";
	
	$query="select rev_id from inl_reviews where rev_link=$id and rev_pend=$p";

	settype($lim,"integer");
	settype($start,"integer");

	if($lim)
		$rs=&$conn->SelectLimit($query,$lim,$start);
	else
		$rs = &$conn->Execute($query);

	if($rs && !$rs->EOF)
		return $rs->RecordCount();
	else
		return 0;
}

function review_text()
{	global $rev_data;
	return nl2br($rev_data[1]);
}
function approve_review()
{	global $rev_data, $id,$toprate, $tophits, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	return "confirm.php?$att_sid"."action=approverev&rev_id=$rev_data[0]&id=$id&t=list_pend_reviews&toprate=$toprate&tophits=$tophits";
}
function del_review()
{
	global $rev_data, $id,$toprate, $tophits, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	return "confirm.php?$att_sid"."action=delreview&deleteid=$rev_data[0]&id=$id&t=$t&toprate=$toprate&tophits=$tophits";
}
function edit_review()
{	global $rev_data, $id,$toprate, $tophits,$attach, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	return "addreview.php?$att_sid"."rev_id=$rev_data[0]&id=$id&$t&toprate=$toprate&tophits=$tophits&attach=$attach";
}
function insert_preview_links()
{	global $ses, $cat, $lim, $link_order_c,$link_order,$link_sort,$link_sort_c, $force_pick, $admin, $start, $review_query, $sql_type;
	if($ses["num_res"]=="all")
	{	$lim=0;
		$start=0;
	}
	else
	{	if($ses["num_res"])
			$lim=$ses["num_res"];
	}

	//sort vars
	if($link_order_c)			$link_order=$link_order_c;
	if($link_sort_c)			$link_sort=$link_sort_c;
	
	if($force_pick)
		$orderby=" ORDER BY link_pick desc, ".$ses["link_order"]." ".$ses["link_sort"]." ";	
	else	
		$orderby=" ORDER BY ".$ses["link_order"]." ".$ses["link_sort"]." ";

	if($sql_type=="mssql")
		$review_query="SELECT inl_links.link_id, link_name, link_pick, link_desc, link_date, link_hits, link_rating, link_votes, link_numrevs, link_image, link_user, link_url FROM inl_links LEFT JOIN inl_lc ON inl_lc.link_id=inl_links.link_id LEFT JOIN inl_reviews ON inl_links.link_id=inl_reviews.rev_link where rev_pend=1 ";
	else
		$review_query="SELECT distinct inl_links.link_id, link_name, link_pick, link_desc, link_date, link_hits, link_rating, link_votes, link_numrevs, link_image, link_user, link_url FROM inl_links LEFT JOIN inl_lc ON inl_lc.link_id=inl_links.link_id LEFT JOIN inl_reviews ON inl_links.link_id=inl_reviews.rev_link where rev_pend=1 ";

	return print_links($review_query.$orderby, "pend_rev", $lim, $start);
}
function review_date()
{	global $datefmt, $rev_data;
	return date($datefmt,$rev_data[3]);
}
function review_user_email()
{	global $rev_data;
	if($rev_data[4])
		return $rev_data[5];
	else 
		return "";
}
function review_user()
{	global $rev_data,$lu_guest, $la_guest, $admin;
	if($rev_data[4])
		return $rev_data[4];
	else 
	{
		if($admin==1)
			return $la_guest;
		else
			return $lu_guest;
	}
}


function maillist()
{
	global $email, $load;
	if($load==2 || $load==3)
		return $email;
	else
		return "";
}

function drop_results($vtag_value)
{	global $ses,$admin ,$la_drop_as_many_as_found,$lu_drop_as_many_as_found;

	if($vtag_value=="all")
	{	if($vtag_value==$ses["num_res"])
			$sel=" selected";
		else
			$sel="";
		if($admin==1)
			return "<option value='$vtag_value'$sel>$la_drop_as_many_as_found</option>";
		else
			return "<option value='$vtag_value'$sel>$lu_drop_as_many_as_found</option>";
	}
	else
	{
		if($vtag_value==$ses["num_res"])
			$sel=" selected";
		else
			$sel="";
		return "<option value='$vtag_value'$sel>$vtag_value</option>";
	}
}

function nav($vtag_value)
{	global $cat, $cat_data, $ses, $lu_error_addto_cat_not_allowed, $lu_error_login_to_add, $lu_error_user_not_allowed, $link_data, $lu_error_addreview_not_allowed, $having, $attach, $t, $sid, $session_get, $lu_error_registered;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	switch($vtag_value)
	{	case "add_link":
			if($cat==0)
				$addlink_cur_cat="Home,";
			else
				$addlink_cur_cat="$cat,";
			if($cat)
			{	$cat_data=get_cat_data($cat);
				$cat_perm=$cat_data[9];					
			}
			else
				$cat_perm=-1;
			if(check_perm($cat_perm, "link")) 
				$ret="../../index.php?$att_sid"."t=$vtag_value&addlink_cats=$addlink_cur_cat&addlink_cur_cat=$cat";
			else
			{
				if($ses["user_id"]>0 && $cat_perm<3)
					$msg=base64_encode($lu_error_addto_cat_not_allowed);
				elseif($cat_perm<3)
					$msg=base64_encode($lu_error_addto_cat_not_allowed);
				elseif(!$ses["user_id"] && $cat_perm%3==0)	
					$msg=base64_encode($lu_error_login_to_add);
				$ret="../../index.php?$att_sid"."t=error&message=$msg";
			}
			break;
		case "modify":
			if($ses["user_id"]) 
				$ret="../../index.php?$att_sid"."t=$vtag_value";
			else
				$ret="../../index.php?$att_sid"."t=login&attach=t=modify";
			break;
		case "suggest_cat":
			if(check_perm(-1, "cat")) 
				$ret="../../index.php?$att_sid"."t=$vtag_value&cat=$cat&attach=$attach";
			else
			{	$message=base64_encode($lu_error_addto_cat_not_allowed);
				$ret="../../index.php?$att_sid"."t=error&message=$message";
			}
			break;
		case "registration":
			if($ses["user_id"]>0)
			{	$message=base64_encode($lu_error_registered);
				$ret="../../index.php?$att_sid"."t=error&message=$message";
			}
			else
			{
				$perm = check_perm(-1, "user");
	
				if($perm) 
				{	
					if($perm == 3)
						$ret="../../index.php?$att_sid"."t=r$vtag_value&cat=$cat";
					else
						$ret="../../index.php?$att_sid"."t=$vtag_value&cat=$cat";
				}
				else
				{	$message=base64_encode($lu_error_user_not_allowed);
					$ret="../../index.php?$att_sid"."t=error&message=$message";
				}
			}
			break;
		case "add_review":
			if($admin==1) //admin setting
				$ret="navigate.php?$att_sid"."id=$link_data[0]&t=add_review";
			else
				if(check_perm(-1, "review"))
				{
					$ret="../../index.php?$att_sid"."id=$link_data[0]&t=add_review";
					$ses["destin"]="$att_sid"."id=$link_data[0]&t=reviews&toprate=$toprate&tophits=$tophits";
					save_session($sid);
				}
				else
				{	$message=base64_encode($lu_error_addreview_not_allowed);
					$ret="../../index.php?$att_sid"."t=error&message=$message";
				}
			break;
		case "favorites":
			if($ses["user_id"]) 
				$ret="../../index.php?$att_sid"."t=$vtag_value";
			else
				$ret="../../index.php?$att_sid"."t=login&attach=t=$vtag_value";
			break;
		default:
			$ret="../../index.php?$att_sid"."t=$vtag_value";
			if(strlen($attach)>0){$ret.="&attach=$attach";}
			if(strlen($having)>0){$ret.="&having=$having";}
			break;
		}
	return $ret;
}
function drop_lang($vtag_value)
{	global $ses;

	if($vtag_value==$ses["lang"])
		$sel=" selected";
	else
		$sel="";
	if($vtag_value=="default" && !$ses["lang"])
		$sel=" selected";
	return "<option value='$vtag_value'$sel>$vtag_value</option>";
}
function drop_theme($vtag_value)
{
	global $ses;
	if($vtag_value==$ses["theme"])
		$sel=" selected";
	else
		$sel="";
	if($vtag_value=="default" && !$ses["theme"])
		$sel=" selected";
	return "<option value='$vtag_value'$sel>$vtag_value</option>";
}


function form_action_registration()
{	global $attach, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	$ret="../../action.php?$att_sid"."action=register";
	if(strlen($attach)>0){$ret.="&attach=$attach";}
	return $ret;
}

function form_action_registration_no_password()
{	global $attach, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	$ret="../../action.php?$att_sid"."action=registerr";
	if(strlen($attach)>0){$ret.="&attach=$attach";}
	return $ret;
}

function form_action_send_password()
{	global $attach, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	$ret="../../action.php?$att_sid"."action=send_password";
	if(strlen($attach)>0){$ret.="&attach=$attach";}
	return $ret;
}

function form_action_profile()
{	global $attach, $load, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	if($load==3)
		;
	else
	{	
		include("includes/user_lib.php");
		get_user();
	}
	$ret="../../action.php?$att_sid"."action=profile";
	if(strlen($attach)>0){$ret.="&attach=$attach";}
	return $ret;
}
		
function error_username()
{	global $err_user_name;
	return $err_user_name;
}

function error_send_password()
{	global $err_send_password;
	return $err_send_password;
}

function error_password()
{	global $err_user_pass;
	return $err_user_pass;
}

function error_first()
{	global $err_first;
	return $err_first;
}

function error_last()
{	global $err_last;
	return $err_last;
}

function error_email()
{	global $err_email;
	return $err_email;
}

function error_re_pass()
{	global $err_re_pass;
	return $err_re_pass;
}

function val_user_name()
{	global $form_input_registration_user_name;
	if (get_magic_quotes_gpc())
		return stripslashes($form_input_registration_user_name);
	else
		return $form_input_registration_user_name;
}

function val_first()
{	global $form_input_registration_first;
	if (get_magic_quotes_gpc())
		return stripslashes($form_input_registration_first);
	else
		return $form_input_registration_first;
}

function val_last()
{	global $form_input_registration_last;
	if (get_magic_quotes_gpc())
		return stripslashes($form_input_registration_last);
	else
		return $form_input_registration_last;
}

function val_email()
{	global $form_input_registration_email;
	if (get_magic_quotes_gpc())
		return stripslashes($form_input_registration_email);
	else
		return $form_input_registration_email;
}

function val_user_cust1()
{	global $form_input_registration_cust1;
	if (get_magic_quotes_gpc())
		return stripslashes($form_input_registration_cust1);
	else
		return $form_input_registration_cust1;
}

function val_user_cust2()
{	global $form_input_registration_cust2;
	if (get_magic_quotes_gpc())
		return stripslashes($form_input_registration_cust2);
	else
		return $form_input_registration_cust2;
}

function val_user_cust3()
{	global $form_input_registration_cust3;
	if (get_magic_quotes_gpc())
		return stripslashes($form_input_registration_cust3);
	else
		return $form_input_registration_cust3;
}

function val_user_cust4()
{	global $form_input_registration_cust4;
	if (get_magic_quotes_gpc())
		return stripslashes($form_input_registration_cust4);
	else
		return $form_input_registration_cust4;
}

function val_user_cust5()
{	global $form_input_registration_cust5;
	if (get_magic_quotes_gpc())
		return stripslashes($form_input_registration_cust5);
	else
		return $form_input_registration_cust5;
}

function val_user_cust6()
{	global $form_input_registration_cust6;
	if (get_magic_quotes_gpc())
		return stripslashes($form_input_registration_cust6);
	else
		return $form_input_registration_cust6;
}

function action_logout()
{	global $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	return "../../action.php?$att_sid"."action=logout";
}

function username_used()
{	global $err_username_used;
	return $err_username_used;
}

function email_used()
{	global $err_email_used;
	return $err_email_used;
}

function form_action_rate()
{	global $id, $attach, $having, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	$ret="../../action.php?$att_sid"."action=rate&linkid=$id";
	if(strlen($attach)>0){$ret.="&attach=$attach";}
	if(strlen($having)>0){$ret.="&having=$having";}
	return $ret;
}

function error_message()
{	global $message;
	return base64_decode($message);
}

function confirm_message()
{	global $message, $pendmsg;
	global $lu_confirm_approval;
	if ($pendmsg != 1)
		return base64_decode($message);
	else
		//return $message.$lu_confirm_approval;
		return base64_decode($message).$lu_confirm_approval;
}

function confirm_action()
{	global $go;
	if($go)
		return "$go";
}

function confirm_attach()
{	global $attach, $ses, $sid;
	if ($attach != "")
	{	
		$attach=ereg_replace("\|","&",$attach);
		return "&$attach";
	} 
	else
	{	$temp=$ses["destin"];
		//$ses["destin"]="";
		//save_session($sid);
		return $temp;
	}
}

function form_action_search()
{	global $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	return "../../action.php?$att_sid"."action=search";
}

function form_action_themes()
{	global $sid, $session_get,$t,$cat;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	if($t)
		$att_t="t=$t&";
	if($cat)
		$att_cat="cat=$cat&";
	return "../../index.php?$att_sid".$att_t.$att_cat;
}

function form_action_languages()
{	global $sid, $session_get,$t,$cat;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	if($t)
		$att_t="t=$t&";
	if($cat)
		$att_cat="cat=$cat&";
	return "../../index.php?$att_sid".$att_t.$att_cat;
}

function form_action_search_advanced()
{	global $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	return "../../action.php?$att_sid"."action=advsearch";
}

function err_link_name()
{	global $err_link_name, $load;
	if($load==1)
		return "$err_link_name";
	else
		return "";
}

function err_link_desc()
{	global $err_link_desc, $load; 
	if($load==1)
		return "$err_link_desc";
	else
		return "";
}

function err_link_url()
{	global $err_link_url, $load; 
	if($load==1)
		return "$err_link_url";
	else
		return "";
}

function val_link_name()
{	global $link_name;
	if (get_magic_quotes_gpc())
		return stripslashes($link_name);
	else
		return $link_name; 
}

function val_link_url()
{	global $link_url;
	if (get_magic_quotes_gpc())
		return stripslashes($link_url);
	else
		return $link_url;
}

function val_link_desc()
{	global $link_desc;
	if (get_magic_quotes_gpc())
		return stripslashes($link_desc);
	else
		return $link_desc;
}

function val_link_image()
{	global $link_image;
	if (get_magic_quotes_gpc())
		return stripslashes($link_image);
	else
		return $link_image;
}

function val_link_cust1()
{	global $link_cust1;
	if (get_magic_quotes_gpc())
		return stripslashes($link_cust1);
	else
		return $link_cust1;
}

function val_link_cust2()
{	global $link_cust2;
	if (get_magic_quotes_gpc())
		return stripslashes($link_cust2);
	else
		return $link_cust2;
}

function val_link_cust3()
{	global $link_cust3;
	if (get_magic_quotes_gpc())
		return stripslashes($link_cust3);
	else
		return $link_cust3;
}

function val_link_cust4()
{	global $link_cust4;
	if (get_magic_quotes_gpc())
		return stripslashes($link_cust4);
	else
		return $link_cust4;
}

function val_link_cust5()
{	global $link_cust5;
	if (get_magic_quotes_gpc())
		return stripslashes($link_cust5);
	else
		return $link_cust5;
}

function val_link_cust6()
{	global $link_cust6;
	if (get_magic_quotes_gpc())
		return stripslashes($link_cust6);
	else
		return $link_cust6;
}

function name_link_cust1()
{	global $lc1, $lu_links_custom_fields1;
	if (!$lc1)
		return "$lu_links_custom_fields1 &nbsp;";
	else
		return "$lc1 &nbsp;";
}

function name_link_cust2()
{	global $lc2, $lu_links_custom_fields2;
	if (!$lc2)
		return "$lu_links_custom_fields2 &nbsp;";
	else
		return "$lc2 &nbsp;";
}

function name_link_cust3()
{	global $lc3, $lu_links_custom_fields3;
	if (!$lc3)
		return "$lu_links_custom_fields3 &nbsp;";
	else
		return "$lc3 &nbsp;";
}

function name_link_cust4()
{	global $lc4, $lu_links_custom_fields4;
	if (!$lc4)
		return "$lu_links_custom_fields4 &nbsp;";
	else
		return "$lc4 &nbsp;";
}

function name_link_cust5()
{	global $lc5, $lu_links_custom_fields5;
	if (!$lc5)
		return "$lu_links_custom_fields5 &nbsp;";
	else
		return "$lc5 &nbsp;";
}

function name_link_cust6()
{	global $lc6, $lu_links_custom_fields6;
	if (!$lc6)
		return "$lu_links_custom_fields6 &nbsp;";
	else
		return "$lc6 &nbsp;";
}

function insert_base_ref()
{	global $filepath, $server, $ses;
	return "<base href=\"http://$server"."$filepath"."themes/".$ses["theme"]."/\">";
}

function insert_drop_add_link_cat()
{	global $addlink_cur_cat, $addlink_cats,$lu_nav_home,$conn;
	if($addlink_cur_cat<0)
		$ret="<option value=\"0\">$lu_nav_home</option>";
	else if($addlink_cur_cat==0)
	{	$ret="<option value=\"_-1\">&lt;&lt;----</option>";
		$ret.=print_drop_cats($addlink_cur_cat,$addlink_cats);
	}
	else
	{
		$rs = $conn->Execute("SELECT cat_name,cat_sub FROM inl_cats WHERE cat_id='$addlink_cur_cat'");
		$parent_id = $rs -> fields["cat_sub"];
		$ret= "<option value=\"_$parent_id\">&lt;&lt;----</option>";
		$ret.=print_drop_cats($addlink_cur_cat,$addlink_cats);
	}

	return $ret;
}

function insert_add_link_cats()
{	global $error, $addlink_cats;
	if($error==10)
		return parse("add_link_cat_error");
	else
		return print_addto_cats($addlink_cats);
}
function insert_cur_cat_path()
{	global $lu_navbar_seperator, $addlink_cur_cat;
	if($addlink_cur_cat>-1)
		return linkpath($addlink_cur_cat).$lu_navbar_seperator;
	else
		return "";
}
function add_link_cat_name()
{	global $cat_data; $addlink_cur_cat;
	if($cat_data[1])
		return linkpath($cat_data[0]);
	else
		return linkpath($addlink_cur_cat);
}

function form_button_add_link_cats_delcat()
{	global $cat_data;
	return "form_button_add_link_cats_delcat$cat_data[0]";
}

function form_action_add_link()
{	global $addlink_cats, $addlink_cur_cat, $attach, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	$ret="../../action.php?$att_sid"."action=add_link&addlink_cats=$addlink_cats&addlink_cur_cat=$addlink_cur_cat";
	if(strlen($attach)>0)
		$ret.="&attach=$attach";
	return $ret;
}

function form_action_add_review()
{	global $admin, $link_data, $toprate, $tophits, $attach, $having, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	if($admin==1)
	{
	$ret="addreview.php?$att_sid"."id=$link_data[0]&toprate=$toprate&tophits=$tophits";
	if(strlen($attach)>0){$ret.="&attach=$attach";}
	if(strlen($having)>0){$ret.="&having=$having";}
	}
	else
	{
	$ret="../../action.php?$att_sid"."action=add_review&id=$link_data[0]&toprate=$toprate&tophits=$tophits";
	if(strlen($attach)>0){$ret.="&attach=$attach";}
	if(strlen($having)>0){$ret.="&having=$having";}
	}
	return $ret;
}

function meta_keywords()
{	global $cat_data, $default_meta_keywords;
	if($cat_data[16])
	return $cat_data[16];
	else
	return $default_meta_keywords;
}

function meta_desc()
{	global $cat_data, $default_meta_desc;
	if($cat_data[17])
		return $cat_data[17];
	else
		return $default_meta_desc;
}

function select_links()
{	global $lu_link_in_current, $lu_link_in_subcats, $lu_link_in_entire;
		return "<option value='link'>$lu_link_in_entire</option>				
				<option value='link2'>$lu_link_in_current</option>
				<option value='link1'>$lu_link_in_subcats</option>";
}

function select_cats()
{	global $lu_cat;
		return "<option value='cat'>$lu_cat</option>";
}

function user_status()
{	global $lu_not_logged_in, $lu_mailing_list, $lu_not_mailing_list, $lu_status, $ses;
	if(!$ses["user_id"])
		return "$lu_status: $lu_not_logged_in";
	elseif($ses["user_perm"]==3)
		return "$lu_status: $lu_not_mailing_list";
	else
		return "$lu_status: $lu_mailing_list";
	
}

function form_button_subscribed()
{	global $lu_button_unsubscribe, $lu_button_subscribe, $ses;
	if(!$ses["user_id"])
		return "$lu_button_subscribe";
	elseif($ses["user_perm"]==3)
		return "$lu_button_subscribe";
	else
		return "$lu_button_unsubscribe";
}

function form_action_subscribe()
{	global $ses, $attach, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	if($ses["user_id"]==0)
		$ret =  "../../index.php?$att_sid"."t=login";
	else
		$ret = "../../action.php?$att_sid"."action=subscribe";
	if(strlen($attach)>0)
		$ret.="&attach=$attach";
	return $ret;
}

function form_action_suggest_cat()
{	global $cat, $attach, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	$ret="../../action.php?$att_sid"."action=suggest_cat&cat=$cat";
	if(strlen($attach)>0){$ret.="&attach=$attach";}
	return $ret;
}

function form_action_modify_link()
{	global $load, $link_data, $id, $attach, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	if($load!=1){
	global $link_name, $link_url, $link_desc, $link_image, $link_cust1, $link_cust2, $link_cust3, $link_cust4, $link_cust5, $link_cust6;
	$link_name=$link_data[1];
	$link_url=$link_data[17];
	$link_desc=$link_data[3];
	$link_image=$link_data[9];
	$link_cust1=$link_data[10];
	$link_cust2=$link_data[11];
	$link_cust3=$link_data[12];
	$link_cust4=$link_data[13];
	$link_cust5=$link_data[14];
	$link_cust6=$link_data[15];
	}
	$ret="../../action.php?$att_sid"."action=modify_link&id=$id";
	if(strlen($attach)>0)
		$ret.="&attach=$attach";
	return $ret;
}

function form_action_sort_cats()
{	global $attach, $admin, $having, $sid, $session_get, $ses;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	$attach1=ereg_replace("\|","&",$attach);
	if($admin==1)
		//$ret="navigate.php?$att_sid"."$attach1";
		$ret="navigate.php?".$ses["destin"];
	else
		//$ret="../../index.php?$att_sid"."$attach1";
		$ret="../../index.php?".$ses["destin"];

	if(strlen($having)>0){$ret.="&having=$having";}
		return $ret;	
}

function form_action_sort_links()
{	global $attach, $admin, $having, $sid, $session_get, $ses;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	
	$attach1=ereg_replace("\|","&",$attach);
	
	if($admin==1)
		//$ret="navigate.php?$att_sid"."$attach1";
		$ret="navigate.php?".$ses["destin"];
	else
		//$ret="../../index.php?$att_sid"."$attach1";
		$ret="../../index.php?".$ses["destin"];

	if(strlen($having)>0){$ret.="&having=$having";}
		return $ret;
}

function getting_rated_link()
{	return parse("getrate");
}

function form_action_getting_rate()
{	global $server, $filepath, $id, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	return "http://$server"."$filepath"."action.php?action=rate&linkid=$id";
}

function link_getting_rated()
{	global $id, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	return "../../index.php?$att_sid"."t=getting_rated&id=$id";
}

function attach()
{	global $attach, $having;
	return "having=$having&attach=$attach";
}

function link_cat()
{	global $link_data;
	return $link_data[18];
}

function cat_disabled()
{	global $la_disabled, $cat_data;
	if($cat_data[18]==1)
		return "";
	else
		return $la_disabled;
}
	
function link_disabled()
{	global $la_disabled, $link_data;
	if($link_data[19]==1)
		return "";
	else
		return $la_disabled;
}

function user_cust1()
{	global $uc1, $lu_users_custom_fields1;
	if(!$uc1)
		return "$lu_users_custom_fields1 &nbsp;";
	else
		return "$uc1 &nbsp;";
}

function user_cust2()
{	global $uc2, $lu_users_custom_fields2;
	if(!$uc2)
		return "$lu_users_custom_fields2 &nbsp;";
	else
		return "$uc2 &nbsp;";
}

function user_cust3()
{	global $uc3, $lu_users_custom_fields3;
	if(!$uc3)
		return "$lu_users_custom_fields3 &nbsp;";
	else
		return "$uc3 &nbsp;";
}

function user_cust4()
{	global $uc4, $lu_users_custom_fields4;
	if(!$uc4)
		return "$lu_users_custom_fields4 &nbsp;";
	else
		return "$uc4 &nbsp;";
}

function user_cust5()
{	global $uc5, $lu_users_custom_fields5;
	if(!$uc5)
		return "$lu_users_custom_fields5 &nbsp;";
	else
		return "$uc5 &nbsp;";
}

function user_cust6()
{	global $uc6, $lu_users_custom_fields6;
	if(!$uc6)
		return "$lu_users_custom_fields6 &nbsp;";
	else
		return "$uc6 &nbsp;";
}

function report_dead_link()
{	global $link_data, $attach, $t, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	return "../../action.php?$att_sid"."action=dead&link_id=".$link_data[0]."&attach=$attach&t=$t";
}


function insert_move_cat()
{	global $cat_data, $ses;
	//admin or root  or (editor and category user == current user)
	if($ses["user_perm"]==2 || $ses["user_perm"]==1 || ($ses["user_perm"]==5 && $cat_data[19]==$ses["user_id"])) 
		return parse("el_move_cat"); //show the button
	else
		return ""; //no rights
}
function insert_copy_cat()
{	global $cat_data, $ses;
	//admin or root  or (editor and category user == current user)
	if($ses["user_perm"]==2 || $ses["user_perm"]==1 || ($ses["user_perm"]==5 && $cat_data[19]==$ses["user_id"])) 
		return parse("el_copy_cat"); //show the button
	else
		return ""; //no rights
}
function insert_del_cat()
{	global $cat_data, $ses;
	//admin or root  or (editor and category user == current user)
	if($ses["user_perm"]==2 || $ses["user_perm"]==1 || ($ses["user_perm"]==5 && $cat_data[19]==$ses["user_id"])) 
		return parse("el_del_cat"); //show the button
	else
		return ""; //no rights
}

function insert_edit_cat()
{	global $cat_data, $ses;
	//admin or root  or (editor and category user == current user)
	if($ses["user_perm"]==2 || $ses["user_perm"]==1 || ($ses["user_perm"]==5 && $cat_data[19]==$ses["user_id"])) 
		return parse("el_edit_cat"); //show the button
	else
		return ""; //no rights
	
}

function get_cat_id_for_link($link_id)
{ global $conn, $ses;
	$res = &$conn->Execute("SELECT cat_id FROM inl_lc WHERE link_id=$link_id");
	if ($res)
	{
		$current_cat_id = $res->fields[0];
		
		$res = &$conn->Execute("SELECT cat_user FROM inl_cats WHERE cat_id=$current_cat_id");
		if ($res)
			$current_cat_user = $res->fields[0];
	}
		
	if ($ses["user_id"] == $current_cat_user)
		return true;
	else 
		return false;
}

function insert_move_link()
{	global $cat_data, $ses, $cat, $t, $link_data;
	//admin or root  or (editor and category user == current user)
	if (($ses["user_perm"]==2 || $ses["user_perm"]==1) || ($ses["user_perm"]==5 && $cat_data[19]==$ses["user_id"] && $t!="search_links"))
		return parse("el_move_link"); //show the button
	elseif ($ses["user_perm"]==5 && $t == "search_links" && get_cat_id_for_link($link_data[0]))
		return parse("el_move_link");
	else 
		return ""; //no rights
}

function insert_del_link()
{	global $cat_data, $ses, $t, $link_data;
	//admin or root  or (editor and category user == current user)
	if (($ses["user_perm"]==2 || $ses["user_perm"]==1) || ($ses["user_perm"]==5 && $cat_data[19]==$ses["user_id"] && $t!="search_links"))
		return parse("el_del_link"); //show the button
	elseif ($ses["user_perm"]==5 && $t == "search_links" && get_cat_id_for_link($link_data[0]))
		return parse("el_del_link"); //show the button
	else
		return ""; //no rights
}

function insert_edit_link()
{	global $cat_data, $ses, $t, $link_data;
	//admin or root  or (editor and category user == current user)
	
	if (($ses["user_perm"]==2 || $ses["user_perm"]==1) || ($ses["user_perm"]==5 && $cat_data[19]==$ses["user_id"] && $t!="search_links"))
		return parse("el_edit_link"); //show the button
	elseif ($ses["user_perm"]==5 && $t == "search_links" && get_cat_id_for_link($link_data[0]))
		return parse("el_edit_link"); //show the button
	else
		return ""; //no rights
	
}

function insert_review_link()
{	global $cat_data, $ses, $t, $link_data;
	//admin or root  or (editor and category user == current user)
	if (($ses["user_perm"]==2 || $ses["user_perm"]==1) || ($ses["user_perm"]==5 && $cat_data[19]==$ses["user_id"] && $t!="search_links"))
		return parse("el_review_link"); //show the button
	elseif ($ses["user_perm"]==5 && $t == "search_links" && get_cat_id_for_link($link_data[0]))
		return parse("el_review_link"); //show the button
	else
		return ""; //no rights
	
}

function insert_sid()
{	global $sid, $session_get;
	if($sid && $session_get)
		return "sid=$sid&";
}

function inl_language($value)
{	global $t, $cat, $sid, $session_get, $having;
	
	$att_sid="";
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	if($t)
		$att_sid.="t=$t&";
	if($cat)
		$att_sid.="cat=$cat&";
	if($having)
		$att_sid.="having=$having&";
	if($value)
		$att_sid.="inl_language=$value";

	return "../../index.php?$att_sid";

}

function insert_current_cat_id()
{	global $cat;
	return $cat;
}

function insert_cat_id()
{ global $cat;
	return $cat;
}
function is_table_exist($table)
{	global $conn;
	$rs = &$conn->MetaTables();
	foreach ($rs as $tables_in_database)
		if($tables_in_database == $table)
			return true;
	return false;
}

function pending_cats_in_list_for_editor()
{global $conn, $ses;

	$rs = &$conn->Execute("SELECT cat_id FROM inl_cats WHERE cat_user=".$ses["user_id"]); 
	if ($rs)
		$row = $rs->fields;
	if ($row)
	{
		foreach ($row as $ce)
		{
			$sub_cats = ereg_replace($ce.", ", "", $sub_cats);
			$sub_cats.= $ce.", ";
		}
		$sub_cats = ereg_replace(", $", "", $sub_cats);
	}
	return $sub_cats;

}
function getSearchKeyword()
{
	global $having, $conn;
	$que="SELECT log_keyword, log_search, search_action, search_cat FROM inl_search_log where log_id='$having'";
	$rs = &$conn->Execute($que);
	if ($rs && !$rs->EOF) 
	{
		$search_word = $rs->fields[0];
		$search_type = $rs->fields[1];
		if($search_type==1)
			return "$search_word";
		else
			return "";
	}
	else
		return "";

}

#Following was written after 8/23/02
#additional functions to help suggest a link to a friend
function suggest_friend_from_email()
{
	global $ses, $conn;
	if($ses["user_id"])
	{
		$que="SELECT  email FROM inl_users where user_id=".$ses["user_id"] ;
		$rs= &$conn->Execute($que);

		if ($rs && !$rs->EOF)
			$email = $rs->fields[0];
		return $email;
	}
	else
		return "";
}
function suggest_friend_from_name()
{
	global $ses, $conn;
	if($ses["user_id"])
	{
		$que="SELECT first, last FROM inl_users where user_id=".$ses["user_id"] ;
		$rs= &$conn->Execute($que);

		if ($rs && !$rs->EOF)
		{
			$firstname = $rs->fields[0];
			$lastname = $rs->fields[1];

		}
		return $firstname. " " . $lastname;
	}
	else
		return "";
}
function suggest_friend_subject()
{
	global $lu_suggest_subject,$sitename;
	$lu_suggest_subject=@ereg_replace('<%site_name%>',$sitename,$lu_suggest_subject);
	return $lu_suggest_subject;

}

#Copied from profile and modified.
function form_action_suggest_friend()
{	global $attach, $load, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	$ret="../../action.php?$att_sid"."action=suggest_friend";
	if(strlen($attach)>0){$ret.="&attach=$attach";}
	
	return $ret;
}
function confirm_message_friend()
{	global $message, $pendmsg;
	global $lu_confirm_approval_friend;
	if ($pendmsg != 1)
		return base64_decode($message);
	else
		//return $message.$lu_confirm_approval;
		return base64_decode($message).$lu_confirm_approval_friend;
}
# The following is a new feature to the version 2.2.10
function link_suggest_friend()
{	global $link_data, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";
	return "../../index.php?$att_sid"."t=link_suggest&id=".$link_data["link_id"];
}
function body_suggest_link()
{
	global $filedir, $ses, $parse_email, $email_body;
	$fileh=$filedir . "languages/".$ses["lang"]."/mail_friend_suggest_link.tpl";
	if(file_exists($fileh))
	{	$fd = fopen($fileh, "r");
		$email_body=fread($fd, filesize($fileh));
		fclose($fd);
		$parse_email=true;
		return parse("email");
	}

}
function suggest_frind_name()
{
	global $form_input_suggest_friend_name;
	return $form_input_suggest_friend_name;
}
#the Above is new to the version
?>
