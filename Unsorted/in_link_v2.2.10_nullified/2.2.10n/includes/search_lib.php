<?php
function do_search_lighting($tag_name, $in_data)
{	global $conn, $having, $high_lighting_tag1, $high_lighting_tag2, $field_name, $field_data;
//	$field_name = "";
//	$field_data = "";
	$replace_this_tag = false;
	$out_data = "";
	
	$que = "SELECT log_keyword, log_search  FROM inl_search_log where log_id='$having'";
	$rs = &$conn->Execute($que);
	if ($rs && !$rs->EOF) 
	{
		$h = stripslashes($rs->fields[0]);
		$search_type = $rs->fields[1];
	}
	if ($search_type == 1)	
	{
		$where = do_search_where();
		foreach ($where as $w)
			if ($tag_name == $w)
			{
				$key = get_keywords($h);
				foreach ($key as $k)
					if ($in_data = preg_replace("/($k)/Ui","$high_lighting_tag1\\1$high_lighting_tag2", $in_data))
						$out_data = $in_data;
			}
	}
	elseif ($search_type == 2)
	{
		avd_get_keywords($h);
		foreach($field_name as $k => $w)
			if ($tag_name == $w)
			{
				$out_data = @eregi_replace($field_data[$k], $high_lighting_tag1.$field_data[$k].$high_lighting_tag2, $in_data);
				break;
			}
	}
	if (strlen($out_data)<1)
		$out_data = $in_data;
	return $out_data; 
}

function do_search_where()
{	global $do_link_name, $do_link_desc, $do_link_url, $do_link_image, $do_link_cust1, $do_link_cust2,	$do_link_cust3, $do_link_cust4, $do_link_cust5, $do_link_cust6;
	$j = 0;
	if ($do_link_name)
	{	$simple_search_array[$j] = "link_name";	$j++;	}
	if ($do_link_desc)
	{	$simple_search_array[$j] = "link_desc";	$j++;	}
	if ($do_link_url)
	{	$simple_search_array[$j] = "link_url";	$j++;	}
	if ($do_link_image)
	{	$simple_search_array[$j] = "link_image";	$j++;	}
	if ($do_link_cust1) 
	{	$simple_search_array[$j] = "cust1";	$j++;	}
	if ($do_link_cust2) 
	{	$simple_search_array[$j] = "cust2";	$j++;	}
	if ($do_link_cust3) 
	{	$simple_search_array[$j] = "cust3";	$j++;	}
	if ($do_link_cust4) 
	{	$simple_search_array[$j] = "cust4";	$j++;	}
	if ($do_link_cust5) 
	{	$simple_search_array[$j] = "cust5";	$j++;	}
	if ($do_link_cust6) 
	{	$simple_search_array[$j] = "cust6";	$j++;	}
	
	return $simple_search_array;
}

function do_search_where_cats()
{	global $do_cat_name, $do_cat_desc, $do_cat_image, $do_cat_cust1, $do_cat_cust2,
			$do_cat_cust3, $do_cat_cust4, $do_cat_cust5, $do_cat_cust6;
	$j = 0;
	if ($do_cat_name)
	{	$simple_search_array[$j] = "cat_name";	$j++;	}
	if ($do_cat_desc)
	{	$simple_search_array[$j] = "cat_desc";	$j++;	}
	if ($do_cat_image)
	{	$simple_search_array[$j] = "cat_image";	$j++;	}
	if ($do_cat_cust1) 
	{	$simple_search_array[$j] = "cust1";	$j++;	}
	if ($do_cat_cust2) 
	{	$simple_search_array[$j] = "cust2";	$j++;	}
	if ($do_cat_cust3) 
	{	$simple_search_array[$j] = "cust3";	$j++;	}
	if ($do_cat_cust4) 
	{	$simple_search_array[$j] = "cust4";	$j++;	}
	if ($do_cat_cust5) 
	{	$simple_search_array[$j] = "cust5";	$j++;	}
	if ($do_cat_cust6) 
	{	$simple_search_array[$j] = "cust6";	$j++;	}
	
	return $simple_search_array;
}


function get_where_string($fld_array, $key, $value1, $value2)
{
	foreach($key as $k => $v)
	{
		$q.= "(";
		foreach($fld_array as $fld)
			$q.=" $fld like '%".$v."%' $value1";
		$q.= ") $value2 ";
	}
	return $q;
}

function do_extended_search($phrase, $cat_id, $search_in_cats)
{	global $admin, $cat_ids, $sid, $conn, $multiple_search_instances, $sql_type;
	$query = "CREATE TABLE inl_$sid (search_order int NOT NULL default 3, index(search_order),  index(link_name), index(link_desc(10)), index(link_image), index(cust1), index(cust2), index(cust3), index(cust4(10)), index(cust5(10)), index(cust6(10)), index(link_url(20))) SELECT inl_links.link_id, link_name, link_pick, link_desc, link_date, link_hits, link_rating, link_votes, link_numrevs, link_image, cust1, cust2, cust3, cust4, cust5, cust6, link_user, link_url, cat_id, link_vis, user_name, email FROM inl_links LEFT JOIN inl_lc ON inl_lc.link_id=inl_links.link_id LEFT JOIN inl_custom ON inl_links.link_cust=inl_custom.cust_id LEFT JOIN inl_users ON inl_links.link_user=inl_users.user_id";
	
	
	
	if(!$multiple_search_instances)
		if($sql_type!="mssql")
		{
			$query=ereg_replace("SELECT","SELECT DISTINCT",$query);
			$query=ereg_replace("cat_id, ","inl_links.link_id as cat2, ",$query);
		}

	if ($admin == 1)
		$vis = "";
	else 
	{
		$query.= " LEFT JOIN inl_cats ON inl_lc.cat_id=inl_cats.cat_id";
		if ($multiple_search_instances)
			$query = ereg_replace("cat_id, ","inl_cats.cat_id, ",$query);
		$vis = " AND inl_links.link_vis=1 AND inl_lc.link_pend<1 AND (inl_cats.cat_vis=1 OR inl_cats.cat_vis IS NULL)";

	}

	$phrase = ereg_replace("%","\%",$phrase);
	$phrase = ereg_replace("_","\%",$phrase);
	$cat_ids = "";
	if ($search_in_cats == 1)
	{
		cat_subs($cat_id);
		$cat_ids = ereg_replace(", $", "", $cat_ids);
		$in = " AND inl_links.link_id=inl_lc.link_id AND inl_lc.cat_id IN ($cat_ids)";
	}
	elseif ($search_in_cats == 2)
		$in = " AND inl_links.link_id=inl_lc.link_id AND inl_lc.cat_id IN ($cat_id)";
	
	
	
	$w_array = get_keywords($phrase);
	$simple_search_array = do_search_where();
	$string = get_where_string($simple_search_array, $w_array, "OR", "OR");
	$string = ereg_replace("OR)",")", $string);
	$string = ereg_replace(" OR $","", $string);

	$q = " WHERE (".$string.")".$in.$vis;
	$query.= $q;
	$conn->Execute($query);
	

	//GETTING CAT_ID for links results 
	if(!$multiple_search_instances)
		if($sql_type!="mssql")
		{
			$conn->Execute("ALTER TABLE inl_$sid CHANGE `cat2` `cat_id` INT(11) DEFAULT '0' NOT NULL");
			$query = "SELECT link_id FROM inl_$sid";
			$result = &$conn->Execute($query);
			while ($result && !$result->EOF)
			{
				$link_id = $result->fields[0];
				$res = &$conn->Execute("SELECT cat_id FROM inl_lc WHERE link_id=$link_id");
				if ($res)
				{
					$cat_id = $res->fields[0];
					$conn->Execute("UPDATE inl_$sid SET cat_id=$cat_id WHERE link_id=$link_id");
				}
				$result->MoveNext();
			}
		}
	//-----------------------------------
	
	$q = "";
	
	///// Sort 1
	$string = get_where_string($simple_search_array, $w_array, "OR", "AND");
	$string = ereg_replace("OR)",")", $string);
	$string = ereg_replace(" AND $","", $string);
	$query  = "UPDATE inl_$sid SET search_order=1 WHERE ".$string;
	$conn->Execute($query);
	
	///// Sort 2	
	$string = ereg_replace("AND","OR", $string);
	$query = "UPDATE inl_$sid SET search_order=2 WHERE search_order!=1 AND (".$string.")";
	$conn->Execute($query);
	return true;
}

function avd_get_keywords($phrase)
{	global $field_name, $field_data;
	$t_len = strlen($phrase);
	for ($i=0; $i<$t_len; $i++)
	{	#search for next special tag
		switch ($phrase[$i])
		{
			case "|":
					$field_close = strpos($phrase,":", $i+1);
					$exact_word = substr($phrase, $i+1, ($field_close-$i)-1);
					$i = $field_close;
					if ($exact_word)
					{	
						$field_name[$ce] = $exact_word;
						$word_close = strpos($phrase,"|", $i+1);
						$exact_word = substr($phrase, $i+1, ($word_close-$i)-1);
						if ($exact_word || $exact_word=="0")
						{
							$field_data[$ce] = addslashes($exact_word);
							if ($field_name[$ce] == "type")
								$i = $t_len;
							else 
							{
								$i = $word_close-1;
								$ce++;
							}
						}
					}
				break;
		}
	}
}

function adv_search_parsing($phrase, $link_or_cat, $pend=0)
{	global $field_name, $field_data, $admin, $ses; 
	
	$h = ""; $q = "";
	$vis = "";
	avd_get_keywords($phrase);
	$seperator = $field_data[count($field_name)-1];
	foreach ($field_name as $k=>$v)
	{
		switch ($v)
		{
			case "$link_or_cat"."_date1":
			case "$link_or_cat"."_rating1":
			case "$link_or_cat"."_votes1":
			case "$link_or_cat"."_hits1":
				$f = substr($field_name[$k], 0, strlen($field_name[$k])-1);
				if (strlen($h)>1)
					$h.= $seperator." ".$f."<".$field_data[$k]." "; 
				else 
					$h.= $f.">".$field_data[$k]." "; 
				break;
			case "$link_or_cat"."_date2":
			case "$link_or_cat"."_rating2":
			case "$link_or_cat"."_votes2":
			case "$link_or_cat"."_hits2":
				$f = substr($field_name[$k], 0, strlen($field_name[$k])-1);
				if (strlen($h)>1)
					$h.= $seperator." ".$f.">".$field_data[$k]." "; 
				else 
					$h.= $f."<".$field_data[$k]." "; 
				break;	
			case "$link_or_cat"."_pick":
			case "$link_or_cat"."_vis":
				$f = $field_name[$k];
				if (strlen($h)>1)
					$h.= $seperator." ".$f."=".$field_data[$k]." "; 
				else 
					$h.= $f."=".$field_data[$k]." "; 	
				break;	
			case "type":
				break;
			default:
				$f = $field_name[$k];
				if (strlen($h)>1)
					$h.= $seperator." ".$f." like '%".$field_data[$k]."%' "; 
				else 
					$h.= $f." like '%".$field_data[$k]."%' "; 
				break;
		}
	}
	$h = ereg_replace(" $","", $h);
	
	if ($link_or_cat == "link")
	{
		if ($admin==1) 
		{
			if ($pend==1)
			{
				if($ses["user_perm"]==5) // editor
				{		
					$q = "LEFT JOIN inl_cats ON inl_lc.cat_id=inl_cats.cat_id ";
					$vis = " AND inl_cats.cat_user=".$ses["user_id"];
				}
				$vis.= " AND inl_lc.link_pend!=0";
			}
			else
				$vis = " AND inl_lc.link_pend<1";

		}
		else 
		{
			$q = "LEFT JOIN inl_cats ON inl_lc.cat_id=inl_cats.cat_id ";
			$vis = " AND inl_links.link_vis=1 AND inl_lc.link_pend<1 AND (inl_cats.cat_vis=1 OR inl_cats.cat_vis IS NULL)";
		}
	}
	elseif ($link_or_cat == "cat") 
	{
		if ($admin==1)
		{
			if ($pend==1)
			{
				if($ses["user_perm"]==5)
				{
					$sub_cats = pending_cats_in_list_for_editor();
					if ($sub_cats == "")
						$sub_cats = -1;
					$vis = " AND cat_sub IN (".$sub_cats.")";
				}
				$vis.= " AND inl_cats.cat_pend!=0";	
			}
			else
				$vis = " AND inl_cats.cat_pend<1";
		}
		else 
		{
			$vis = " AND inl_cats.cat_vis=1 AND inl_cats.cat_pend<1";
		}
	}

	$h = $q."WHERE (".$h.")".$vis;
	return $h;
}


function get_keywords($phrase)
{	global $conn;
	$t_len = strlen($phrase);
	$ce=0;
	for ($i=0; $i<$t_len; $i++)
	{	#search for next special tag
		switch ($phrase[$i])
		{
			/*case "'":
					$exact_match_close = strpos($phrase,"'", $i+1);
					if(!$exact_match_close)
						break;
					$exact_word=substr($phrase, $i+1, ($exact_match_close-$i)-1);
					$i=$exact_match_close;
					if($exact_word)
					{	$w_array[$ce]=$exact_word;
						$ce++;
						$exact_word="";
					}	
					break;*/
			case "\"":
					$exact_match_close = strpos($phrase,"\"", $i+1);
					if(!$exact_match_close)
						break;
					$exact_word=substr($phrase, $i+1, ($exact_match_close-$i)-1);
					$i=$exact_match_close;
					if($exact_word)
					{	$w_array[$ce]=addslashes($exact_word);
						$ce++;
						$exact_word="";
					}
					break;
					
			case "+":
			case " ":
			case ",":
				if($exact_word)
				{	$w_array[$ce]=addslashes($exact_word);
					$ce++;
					$exact_word="";
					
				}
				break;

			default:
				$exact_word.=$phrase[$i];
		}
	}
	if($exact_word)
		$w_array[$ce]=addslashes($exact_word);
	
	$query="Select * from inl_keywords order by keyword asc";
	$rs=&$conn->Execute($query);
	while ($rs && !$rs->EOF)
	{	$key_data = $rs->fields;
		for($i=0;$i<count($w_array);$i++)
			if(strtolower($key_data["keyword"])==strtolower($w_array[$i]))
				unset($w_array[$i]);
		$rs->MoveNext();
	}
	return $w_array;
}



function cat_subs($c)
{	global $conn, $cat_ids;
	
	$sql = "SELECT cat_id FROM inl_cats WHERE cat_sub=$c";
	$rs = &$conn->Execute($sql);
	$cat_ids.= $c.", ";
	while ($rs && !$rs->EOF)
	{
		$i = $rs->fields[0];
		cat_subs($i);
		$rs->MoveNext();
	}
}		

function do_simple_search($phrase, $cat_id, $search_in_cats)
{	global $cat_ids, $sid, $admin, $multiple_search_instances, $sql_type;
	$phrase = ereg_replace("%","\%",$phrase);
	$phrase = ereg_replace("_","\_",$phrase);
	$in = "";
	$cat_ids = "";
	
	
	
	if ($admin == 1)
		$vis = "";
	else 
	{
		$q = "LEFT JOIN inl_cats ON inl_lc.cat_id=inl_cats.cat_id";
		$vis = " AND inl_links.link_vis=1 AND inl_lc.link_pend<1 AND (inl_cats.cat_vis=1 OR inl_cats.cat_vis IS NULL)";
	}
	
	
	
	if ($search_in_cats == 1)
	{
		cat_subs($cat_id);
		$cat_ids = ereg_replace(", $", "", $cat_ids);
		$in = " AND inl_links.link_id=inl_lc.link_id AND inl_lc.cat_id IN ($cat_ids)";
	}
	elseif ($search_in_cats == 2)
		$in = " AND inl_links.link_id=inl_lc.link_id AND inl_lc.cat_id IN ($cat_id)";
	
	$w_array = get_keywords($phrase);
	$simple_search_array = do_search_where();
	$string = get_where_string($simple_search_array, $w_array, "OR", "OR");
	$string = ereg_replace("OR)",")", $string);
	$string = ereg_replace(" OR $","", $string);
	
	$q.= " WHERE (".$string.")".$in.$vis;
	return $q;

}

function searchforlinks($word, $cat)
{	global $conn, $table;
	
	if (!get_magic_quotes_gpc())
		$word = addslashes($word);
	settype($cat, "integer");
	$t = time();
	$word = trim($word);
	if (($table=="link1") || ($table=="link2"))
		$a = substr($table, strlen($table)-1, 1);
	else 
		$a = 0;
	$SQL = "INSERT inl_search_log (log_type, log_date, log_search, search_action, log_keyword, search_cat) VALUES (2, $t, 1, $a, '$word', $cat)";
	$conn->Execute($SQL);
	return $conn->Insert_ID("inl_search_log","log_id");
}	

function searchforcats($word)
{
	global $conn;
	$t=time();
	if (!get_magic_quotes_gpc())
		$word = addslashes($word);
	$word=trim($word);
	$word=ereg_replace("[[:space:]]*(\+)[[:space:]]*","+",$word);
	
	$SQL = "insert into inl_search_log (log_type, log_date, log_search, log_keyword) values (1, $t, 1, '$word')";
	$conn->Execute($SQL);
	return $conn->Insert_ID("inl_search_log","log_id");
}	

function getadvcatsearch()
{	global $conn,
	$form_input_search_cat_name, $form_input_search_cat_desc, $form_input_search_cat_fday, $form_input_search_cat_fmonth, $form_input_search_cat_fyear, $form_input_search_cat_lday, $form_input_search_cat_lmonth, $form_input_search_cat_lyear, $form_radio_search_cat_pick, $form_input_search_sep, $form_input_search_cat_ccust1, $form_input_search_cat_ccust2, $form_input_search_cat_ccust3, $form_input_search_cat_ccust4, $form_input_search_cat_ccust5, $form_input_search_cat_ccust6;

	$cat_name=search_escape(inl_escape($form_input_search_cat_name));
	$cat_desc=search_escape(inl_escape($form_input_search_cat_desc));
	$fday=search_escape(inl_escape($form_input_search_cat_fday));
	$fmonth=search_escape(inl_escape($form_input_search_cat_fmonth));
	$fyear=search_escape(inl_escape($form_input_search_cat_fyear));
	$lday=search_escape(inl_escape($form_input_search_cat_lday));
	$lmonth=search_escape(inl_escape($form_input_search_cat_lmonth));
	$lyear=search_escape(inl_escape($form_input_search_cat_lyear));
	$sep=search_escape(inl_escape($form_input_search_sep));
	$cat_pick=search_escape(inl_escape($form_radio_search_cat_pick));
	$ccust1=search_escape(inl_escape($form_input_search_cat_ccust1));
	$ccust2=search_escape(inl_escape($form_input_search_cat_ccust2));
	$ccust3=search_escape(inl_escape($form_input_search_cat_ccust3));
	$ccust4=search_escape(inl_escape($form_input_search_cat_ccust4));
	$ccust5=search_escape(inl_escape($form_input_search_cat_ccust5));
	$ccust6=search_escape(inl_escape($form_input_search_cat_ccust6));
	$log="";
	if (strlen($cat_name) > 0)
		$log.= "|cat_name:$cat_name";
	if (strlen($cat_desc) > 0)
		$log.= "|cat_desc:$cat_desc";
	if	($fday>0 && $fday<32 && $fmonth>0 && $fmonth<13 && $fyear>1900 && $fyear<4000)
		$log.= "|cat_date2:".mktime(0,0,0,$fmonth,$fday,$fyear);
	if	($lday>0 && $lday<32 && $lmonth>0 && $lmonth<13 && $lyear>1900 && $lyear<4000)
		$log.= "|cat_date1:".mktime(0,0,0,$lmonth,$lday,$lyear);
	if (strlen($cat_pick) > 0)
		$log.= "|cat_pick:$cat_pick";
	if (strlen($ccust1) > 0)
		$log.= "|cust1:$ccust1";
	if (strlen($ccust2) > 0)
		$log.= "|cust2:$ccust2";
	if (strlen($ccust3) > 0)
		$log.= "|cust3:$ccust3";
	if (strlen($ccust4) > 0)
		$log.= "|cust4:$ccust4";
	if (strlen($ccust5) > 0)
		$log.= "|cust5:$ccust5";
	if (strlen($ccust6) > 0)
		$log.= "|cust6:$ccust6";
	if (strlen($log) > 0)
		$log.= "|type:$sep|";
	
	if ($log == "")
	{
		$message = base64_encode("Please enter at least 1 keyword for search");
		$destin = "index.php?t=error&message=$message";
		inl_header($destin);	
	}

	$t = time();
	$SQL="INSERT INTO inl_search_log (log_type, log_date, log_search, log_keyword) values (1, $t, 2, ".$conn->qstr($log).")";
	$conn->Execute($SQL);
	return $conn->Insert_ID("inl_search_log","log_id");
}

function getadvlinksearch()
{	global $conn,
	$form_input_search_link_name, $form_input_search_link_desc,    $form_input_search_link_fdayl, $form_input_search_link_fmonthl, $form_input_search_link_fyearl,  $form_input_search_link_ldayl, $form_input_search_link_lmonthl, $form_input_search_link_lyearl, $form_input_search_link_rating_l, $form_input_search_link_rating_f, $form_input_search_link_votes_l, $form_input_search_link_votes_f, $form_input_search_link_hits_l, $form_input_search_link_hits_f,   $form_radio_search_link_pick,   $form_input_search_link_lcust1, $form_input_search_link_lcust2, $form_input_search_link_lcust3, $form_input_search_link_lcust4, $form_input_search_link_lcust5,  $form_input_search_link_lcust6,
	$form_input_search_sep;

	$link_name=search_escape(inl_escape($form_input_search_link_name));
	$link_desc=search_escape(inl_escape($form_input_search_link_desc));
	$fday=search_escape(inl_escape($form_input_search_link_fdayl));
	$fmonth=search_escape(inl_escape($form_input_search_link_fmonthl));
	$fyear=search_escape(inl_escape($form_input_search_link_fyearl));
	$lyear=search_escape(inl_escape($form_input_search_link_lyearl));
	$lday=search_escape(inl_escape($form_input_search_link_ldayl));
	$lmonth=search_escape(inl_escape($form_input_search_link_lmonthl));
	$link_rating_l=search_escape(inl_escape($form_input_search_link_rating_l));
	$link_rating_f=search_escape(inl_escape($form_input_search_link_rating_f));
	$link_votes_l=search_escape(inl_escape($form_input_search_link_votes_l));
	$link_votes_f=search_escape(inl_escape($form_input_search_link_votes_f));
	$link_hits_l=search_escape(inl_escape($form_input_search_link_hits_l));
	$link_hits_f=search_escape(inl_escape($form_input_search_link_hits_f));
	$link_pick=search_escape(inl_escape($form_radio_search_link_pick));
	$sep=search_escape(inl_escape($form_input_search_sep));
	$lcust1=search_escape(inl_escape($form_input_search_link_lcust1));
	$lcust2=search_escape(inl_escape($form_input_search_link_lcust2));
	$lcust3=search_escape(inl_escape($form_input_search_link_lcust3));
	$lcust4=search_escape(inl_escape($form_input_search_link_lcust4));
	$lcust5=search_escape(inl_escape($form_input_search_link_lcust5));
	$lcust6=search_escape(inl_escape($form_input_search_link_lcust6));
	
	$log="";
	if (strlen($link_name) > 0)
		$log.= "|link_name:$link_name";
	if (strlen($link_desc) > 0)
		$log.= "|link_desc:$link_desc";
	if	($fday>0 && $fday<32 && $fmonth>0 && $fmonth<13 && $fyear>1900 && $fyear<4000)
		$log.= "|link_date2:".mktime(0,0,0,$fmonth,$fday,$fyear);
	if	($lday>0 && $lday<32 && $lmonth>0 && $lmonth<13 && $lyear>1900 && $lyear<4000)
		$log.= "|link_date1:".mktime(0,0,0,$lmonth,$lday,$lyear);
	if (strlen($link_rating_l) > 0)
		$log.= "|link_rating1:$link_rating_l";
	if (strlen($link_rating_f) > 0)
		$log.= "|link_rating2:$link_rating_f";
	if (strlen($link_votes_l) > 0)
		$log.= "|link_votes1:$link_votes_l";
	if (strlen($link_votes_f) > 0)
		$log.= "|link_votes2:$link_votes_f";
	if (strlen($link_hits_l) > 0)
		$log.= "|link_hits1:$link_hits_l";
	if (strlen($link_hits_f) > 0)
		$log.= "|link_hits2:$link_hits_f";
	if (strlen($link_pick) > 0)
		$log.= "|link_pick:$link_pick";
	if (strlen($lcust1) > 0)
		$log.= "|cust1:$lcust1";
	if (strlen($lcust2) > 0)
		$log.= "|cust2:$lcust2";
	if (strlen($lcust3) > 0)
		$log.= "|cust3:$lcust3";
	if (strlen($lcust4) > 0)
		$log.= "|cust4:$lcust4";
	if (strlen($lcust5) > 0)
		$log.= "|cust5:$lcust5";
	if (strlen($lcust6) > 0)
		$log.= "|cust6:$lcust6";
	if (strlen($log) > 0)
		$log.= "|type:$sep|";

	if ($log == "")	
	{
		$message = base64_encode("Please enter at least 1 keyword for search");
		$destin = "index.php?t=error&message=$message";
		inl_header($destin);	
	}

	$t = time();
	$SQL="INSERT INTO inl_search_log (log_type, log_date, log_search, log_keyword) values (2, $t, 2, ".$conn->qstr($log).")";
	$conn->Execute($SQL);
	return $conn->Insert_ID("inl_search_log","log_id");
}

?>