<?
function html_select($arr_field_attr, $field_value, $parent_value='', $arr_current_string='')
{
		global $g_i, $g_t;
		
		$str_fields_frm=array();
		$db_sel=new DB_Sql;
		$str_sql_base = "select id, " . $arr_field_attr["caption_field"]; 
		$str_sql_from = " from " . t_name($arr_field_attr["table_ref"]) ;
		$str_sql_where = "";
		
		if ($arr_field_attr["ref_parent_id"]!="")
			{
			$str_sql_where = " where " .  parent_where($arr_field_attr["ref_parent_id"], $parent_value);
			}

		$str_sql_order= " order by " . $arr_field_attr["caption_field"];
		
		$str_sql = $str_sql_base . $str_sql_from .  $str_sql_where . $str_sql_order;
		
		$db_sel->query($str_sql );		
		while ($db_sel->next_record())
			{
			$item = $db_sel->f("id");
			$caption= $db_sel->f($arr_field_attr["caption_field"]);
			if 
			((is_array($field_value) && in_array($item, $field_value) )
			|| (!is_array($field_value) && $field_value==$item))
				$str_selected=" selected ";
			else $str_selected="";
			
			//test to not be able to set parent to self
			if ($item!=$g_i || $arr_field_attr["table_ref"]!=$g_t)
				$str_fields_frm[]="<option value=$item $str_selected>$arr_current_string $caption </option>";

			if ($arr_field_attr["ref_parent_id"]!="")
				{
				$str_fields_frm[] =  html_select($arr_field_attr, $field_value, $item, $arr_current_string .  $caption . " / " );
				}
			}

return join("",$str_fields_frm);

}

function html_full_path($arr_attr, $selected_id, $url, $separator="&raquo;")
{
$db=new DB_Sql;
$arr_fields = array("id", $arr_attr["caption_field"], $arr_attr["parent_field"]);
$str_sql = " select " . join(",", $arr_fields) . " from  " . t_name($arr_attr["name"]) . " where id=$selected_id";

$db->query_next($str_sql);

if ($db->f("parent_id")!="" && $db->f("parent_id")!="0" ) 
$str .= html_full_path($arr_attr, $db->f("parent_id"), $url, $separator);

$str .= " <a href=" . str_replace("--id--" , $db->f("id"), $url) . " > $separator " ;
$str .= $db->f($arr_attr["caption_field"]) . "</a>";
return $str;
}

function get_full_path($arr_attr, $selected_id)
{
$db=new DB_Sql;
$arr_full_path=array();
$arr_fields = array("id", $arr_attr["caption_field"], $arr_attr["parent_field"]);
$str_sql = " select " . join(",", $arr_fields) . " from  " . t_name($arr_attr["name"]) . " where id=$selected_id";

$db->query_next($str_sql);

if ($db->f("parent_id")!="" && $db->f("parent_id")!="0" ) 
$arr_full_path = array_merge($arr_full_path,get_full_path($arr_attr, $db->f("parent_id")));

array_push($arr_full_path,array($db->f("id"),$db->f($arr_attr["caption_field"])));
return $arr_full_path;
}

function str_cut($str, $cut)
{
if ($pos=strpos($str, $cut))
  return substr($str, 0,$pos) . "...";
else
  return $str;
}
?>
