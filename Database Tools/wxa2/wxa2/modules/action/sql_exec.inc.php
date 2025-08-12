<?

//delete action
if ($g_a=="sup")
	{
	$str_sql_delete = "delete from " . t_name($g_t) . " where id=" . $g_i;
	$db->query($str_sql_delete);
	}


if ($g_i!='')
	$where_i = t_name($g_t) . ".id=" . $g_i;
	
if ($g_i!="") 
	{
	if (count($arr_sql_update))
		$sql_input = " update " .  t_name($g_t) . " set " . join (",",$arr_sql_update) . " where " . $where_i;
	}
else
	{if (count($arr_sql_insert_cols)) 
	$sql_input = "insert into  " .  t_name($g_t) . " (" . join(",", $arr_sql_insert_cols) . ") values (" . join(",", $arr_sql_insert_values) . ")";}

if ($sql_input!="" )
	{
	$db=new DB_Sql;
	$db->query($sql_input);
	if ($g_i=="")
		{
		$db->query_next("select max(id) as m from " .  t_name($g_t) );
		$g_i=$db->f("m");
		}
	}

if ($str_sql_delete!="" || $sql_input!="")
	{		
	if (count($arr_sql_enr_refs)>0)
		{
		reset($arr_sql_enr_refs);
		while (list($k,$v)=each($arr_sql_enr_refs))
			{
			$str_sql = str_replace("--g_i--", $g_i, $v);
		
			$db->query($str_sql);
			}
		}
	}
if ($str_sql_delete!="")
	{$g_i="";
	$g_a="";}

if ($g_i!='')
	$where_i = t_name($g_t) . ".id=" . $g_i;
	
if ($g_i!="" && ($g_a=="frm" || $g_a=="enr") )
	array_push($arr_sql_select_where_frm, $where_i);
if ($arr_tables[$g_t]["parent_id_ref"]!=""  )
	array_push($arr_sql_select_where_lst, parent_where($arr_tables[$g_t]["parent_id_ref"], $g_i));
	
	
//select
if (count( $arr_sql_select_join_tables) > 0) 
	$left_join =   join (" ", $arr_sql_select_join_tables);
else $left_join="";

$str_sql_select="select " . join(", ", $arr_sql_select_fields) ; 
$str_sql_count = "select count(" . t_name($g_t) . ".id) as c" ;

$str_sql_select.= " from " . t_name($g_t) . $left_join ;
$str_sql_count .= " from " . t_name($g_t) . $left_join ;


$str_sql_select_lst=$str_sql_select;
$str_sql_select_frm=$str_sql_select;


$str_sql_limit="";
//build arr_values from dbmain
if ($g_i!="" && ($g_a=="frm" || $g_a=="enr"))
	{
	if (count($arr_sql_select_where_frm)>0) 
		$str_sql_select_frm .= " where " . join (" and ", $arr_sql_select_where_frm);
		
	$db_frm = new DB_Sql;
	$db_frm->query($str_sql_select_frm);
	if ($db_frm->next_record())
		{$arr_field_values=$db_frm->Record;}
	}
	
if (($g_a=="enr_close" || $g_a=="") 
	|| 
	( ($g_a=="frm" || $g_a=="enr") && $g_i!="" && $arr_tables[$g_t]["parent_id_ref"]!="") )
	
	{
	if (count($arr_sql_select_where_lst)>0) 
	{
	$str_sql_select_lst .= " where " . join (" and ", $arr_sql_select_where_lst);
	$str_sql_count  .= " where " . join (" and ", $arr_sql_select_where_lst);
	}
	
	
	
	$db_count=new DB_Sql;
	$db_count->query_next($str_sql_count);
	$row_list_count=$db_count->f("c");
	
	if ($g_p==0 || $g_p=="" || !isset($g_p)) $g_p=1;

	$nb_pages = ceil($row_list_count / $nb_rows_per_page);
	
	if ($nb_pages>1)
		{
		$row_list_begin =  ($g_p-1) * $nb_rows_per_page ;
		$row_list_end =($g_p) * $nb_rows_per_page;
		if ($g_p==$nb_pages) $row_list_end = $row_list_count;
		$str_sql_limit=" limit $row_list_begin , $nb_rows_per_page";
		}
	else
		{
		$row_list_begin = 1;
		$row_list_end =  $row_list_count;
		}
	
	$full_sql = $str_sql_select_lst  . $str_sql_select_order . $str_sql_limit;
	
	if ($g_d)add_notice_message($full_sql)	;
	
	$db_lst = new DB_Sql;
	$db_lst->query($full_sql);
	}

?>
