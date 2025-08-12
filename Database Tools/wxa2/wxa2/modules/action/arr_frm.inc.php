<?

$class="form_element_entry";
if ($arr_field_attr["must_be"]) $class.="_must_be";


if ($index==0)
	{ 
	$str_fields_frm=array();
	$str_fields_valid_submit=array();
	$arr_fields_lst=array();
	if ($g_i!="")
	$str_fields_frm[] = "<div class=$class ><li class=form_element_entry><input type=hidden name=id value=$g_i>id $g_i</li></div>";


	}
	



if ($arr_field_attr["lst_order"])	
	{
	$arr_fields_lst[$arr_field_attr["lst_order"]][$arr_field_attr["name"]]=$arr_field_attr["caption"];
	}
$str_fields_frm[]="<div class=$class >";
$str_fields_frm[]="<li>" . $arr_field_attr["caption"]. " : <br>";

$field_value=$arr_field_values[t_name($g_t) . "_" .  $arr_field_attr["name"]];

if ($field_value=="") $field_value=$arr_post[$arr_field_attr["name"]];

$multiple="";
switch ($arr_field_attr["type"])

{
	case "select_multiple":
		$multiple=" multiple ";
		$ref_table = t_name("ref" . "_" .$arr_field_attr["table_ref"]."_" .  $g_t);
		$arr_multiple_values=array();
		if ($g_i!="")
			{$db_sel=new DB_Sql;
			$str_sql_sel="select " . $arr_field_attr["table_ref"] . "_id from " . $ref_table . " where  $g_t" . "_id = " . $g_i; 
			$db_sel->query($str_sql_sel);
			while ($db_sel->next_record())	
				{
				array_push($arr_multiple_values, $db_sel->f($arr_field_attr["table_ref"] . "_id"));
				}
			$field_value=$arr_multiple_values;
			
			}
	case "select":
	$str_fields_frm[]="<select name=" . $arr_field_attr["name"] ;
	if ($multiple!="") $str_fields_frm[]="[]";
	$str_fields_frm[]="  class=form_element_entry $multiple>";
	if ($multiple=="")
		 $str_fields_frm[]="<option value=''>--> </option>";
	if (is_array($arr_field_attr["item"]))
		{
		reset($arr_field_attr["item"]);
		
		while (list($item, $caption) =each($arr_field_attr["item"]))	
			{
			$str_selected=$field_value==$item?" selected ":"";
			$str_fields_frm[]="<option value=$item $str_selected>$caption</option>";
			}
		}
	
	if (isset($arr_field_attr["table_ref"]))
		{
		$str_fields_frm[]=html_select($arr_field_attr, $field_value);
		} // end table_ref

	$str_fields_frm[]="</select>";
	break;
	
	case 'checkbox':
			
			$str_fields_frm[] = "<input type=hidden name=" . $arr_field_attr["name"] . " value=0>";
			$str_fields_frm[] = "<input type=checkbox name=" . $arr_field_attr["name"] . " value=1>";
			break;
			
	case 'image':
		
			$val = $arr_field_values[$field_name];
			$str_fields_frm[] = "<input type=text name=" . $arr_field_attr["name"] . " class=form_element_entry value=\"" . $field_value . "\" size=35 > <a href=# onclick=\"javascript:launchwin(document." . $record_form_name . ", '" .$arr_field_attr["name"] . "','$libdir')\"><img src=i/buttons/dir.gif  width=20 height=20 border=0></a>";
			break;
		
	case 'hidden':
	$str_fields_frm[]= "<input type=hidden name=" . $arr_field_attr["name"] . " value=\"" . $arr_field_values[$field_name] . "\">";
	
	
	case "texthtml":
	require_once("modules/thirdparty/fckeditor/fckeditor.php") ;
	
	$oFCKeditor = new FCKeditor( $arr_field_attr["name"] ) ;
	$oFCKeditor->BasePath	= "modules/thirdparty/fckeditor/" ;
	$oFCKeditor->Value		= $field_value ;
	$str_fields_frm[] = $oFCKeditor->CreateHtml() ;
	break;
	
	case "textarea":
	$str_fields_frm[]="<textarea  name=" . $arr_field_attr["name"] . "  class=form_element_entry rows=5 cols=80>" . $field_value;
	$str_fields_frm[]="</textarea>";
	break;

	case "date":
	if ($field_value=="" && $arr_field_attr["must_be"]) $field_value=date("Y-m-d");
	$str_fields_frm[]="<input type=text name=" . $arr_field_attr["name"] . "  class=form_element_entry size=12 value=\"" . $field_value  . "\"> (" . msg("date_format") . ")";
	break;
	
	case "":
	default:
	$str_fields_frm[]="<input type=text name=" . $arr_field_attr["name"] . "  class=form_element_entry value=\"" . $field_value . "\">";
	break;

}
$str_fields_frm[]="</li></div>";


//valid_submit
if ( $arr_field_attr["must_be"] ==1)
switch ($arr_field_attr["type"])
{
	case "select":
		break;
	case "textarea":
	case "texthtml":
	default:
		$str_fields_valid_submit[]= "if (frm." . $arr_field_attr["name"]. ".value=='') str_msg += \"\\n- " .utf8_decode($arr_field_attr["caption"]) . "\";";
	break;
}
?>
