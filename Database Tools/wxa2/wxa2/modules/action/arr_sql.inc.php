<?
if ($index==0)
	{ 
	$arr_sql_create=array();
	$arr_sql_create_ref=array();

		
	$arr_sql_select_fields=array(t_name($g_t) . ".id as " . t_name($g_t) . "_id");
	$arr_sql_select_join_tables=array();
	$arr_sql_select_where_frm=array();
	$arr_sql_select_where_lst=array();
	$arr_sql_insert_values=array();
	$arr_sql_insert_cols=array();	
	$arr_sql_enr_refs=array();
	$arr_sql_update=array();
	}

//create
switch ($arr_field_attr["type"])
{
case "select_multiple":
array_push($arr_sql_create_ref,array($arr_field_attr["table_ref"] , $g_t));
break;
default:
array_push($arr_sql_create,$arr_field_attr["name"]." ".$arr_field_attr["sql_type"]);
break;
}


//Select
switch ($arr_field_attr["type"])
{
	case "select_multiple":
	break;
	case "select":
		if ($arr_field_attr["table_ref"]!="" && $arr_field_attr["table_ref"]!=$g_t)
			{
		array_push($arr_sql_select_fields, t_name($arr_field_attr["table_ref"]) . "." . $arr_field_attr["caption_field"]  . " as " . t_name($arr_field_attr["table_ref"]) . "_" . $arr_field_attr["caption_field"]);
		array_push($arr_sql_select_join_tables," left join " . t_name( $arr_field_attr["table_ref"]) . " on " .  t_name( $arr_field_attr["table_ref"] ) . ".id = " .  t_name($g_t) . "."  . $arr_field_attr["name"]);
			}
		$arr_field_attr["query_field"] = t_name($arr_field_attr["table_ref"]) . "_" . $arr_field_attr["caption_field"];
	case "":
	default:
	if (!isset($arr_query[$arr_field_attr["name"]]))
		$arr_field_attr["query_field"] = t_name($g_t) . "_" . $arr_field_attr["name"];
	array_push($arr_sql_select_fields, t_name($g_t) . "." .$arr_field_attr["name"] . " as " . t_name($g_t) . "_" . $arr_field_attr["name"]);
}


//save
if ($g_a=="enr" || $g_a=="enr_close" || $g_a=="sup")
 {
 if ($arr_field_attr["type"]=="select_multiple")
	{
	$ref_table = t_name("ref" . "_" .$arr_field_attr["table_ref"]."_" .  $g_t);
	$str_ref_delete = "delete from " . $ref_table . " where $g_t" . "_id = --g_i-- ";
array_push( $arr_sql_enr_refs, $str_ref_delete);

	}
 }
 
if ($g_a=="enr" || $g_a=="enr_close")
{
 if (isset($arr_post[$arr_field_attr["name"]]))
  {
	switch ($arr_field_attr["type"])
	{
	case "select_multiple":
		$arr_multiple=$arr_post[$arr_field_attr["name"]];
		
		
		reset($arr_multiple);
		while (list($k,$v)=each($arr_multiple))
			{
			$str_ref_ins = "insert into  $ref_table(" . $arr_field_attr["table_ref"] . "_id , $g_t" . "_id) values ($v, --g_i--)";
			array_push( $arr_sql_enr_refs, $str_ref_ins);
			}
	break;
	default:
	$val = "'" . $arr_post[$arr_field_attr["name"]] . "'";
	array_push($arr_sql_insert_cols,$arr_field_attr["name"] );
	array_push($arr_sql_insert_values,$val);
	array_push($arr_sql_update, $arr_field_attr["name"] . " = $val");
	break;
	}
  }
 }
 
 
if ($arr_field_attr["lst_row_order"] && $g_o=="")
	{
	$g_o= $arr_field_attr["name"];
	$g_od=$arr_field_attr["lst_row_order"];
	}

if ($g_o==$arr_field_attr["name"])
	{
	if ($g_od=="") $g_od= "asc"; 
	
	$str_sql_select_order= " order by  " . t_name($g_t) . "." . $g_o . " " . $g_od;
	}
	
	
?>
