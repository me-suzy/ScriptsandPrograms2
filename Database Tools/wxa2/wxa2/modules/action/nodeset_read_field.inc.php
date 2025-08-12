<?    
  
$arr_field_attr=array();
$arr_field_attr["name"]= $d_field->get_attribute("name");
$arr_field_attr["caption"] = utf8_decode($d_field->get_attribute("caption"));
$arr_field_attr["type"]= $d_field->get_attribute("type");
$arr_field_attr["lst_order"]=$d_field->get_attribute("lst_order");
$arr_field_attr["must_be"]=$d_field->get_attribute("must_be");
$arr_field_attr["lst_row_order"]=$d_field->get_attribute("lst_row_order");
if ($arr_field_attr["caption"]=="") 
	$arr_field_attr["caption"]=$arr_field_attr["name"];

$xpath_this_field=$xpath_d_fields . "[@name='" .$arr_field_attr["name"] . "']"; 

if ($arr_field_attr["type"]=="select" || $arr_field_attr["type"]=="select_multiple")
	{
	
	//table_ref
	if ($xpathObj2 = @$ctx->xpath_eval($xpath_this_field . "/table_ref"))
			{
			while(list($ix2, $node_table_ref) = each($xpathObj2->nodeset))
				{
			$arr_field_attr["table_ref"]=$node_table_ref->get_attribute("d_table");
$arr_field_attr["caption_field"]=$node_table_ref->get_attribute("caption_field");

//find a little more about the table_ref from xml 
$xpath_table_ref = $xpath_d_tables  ."[@name='" . $arr_field_attr["table_ref"] . "']";

if ($xpathObj2 = @$ctx->xpath_eval($xpath_table_ref ))
		{
		while(list($ix2, $node_table_ref) = each($xpathObj2->nodeset))
				{
				$attributes = $node_table_ref->get_attribute("parent_id_ref") ;
				//print_r($attributes);
				if ($attributes!="")
					$arr_field_attr["ref_parent_id"] = $attributes;
				
				}
		}

					}
				 }
	//xml items
		
	if ($xpathObj2 = @$ctx->xpath_eval($xpath_this_field . "/item"))
		{
		while(list($ix2, $item) = each($xpathObj2->nodeset))
			{
			$arr_field_attr["item"][$item->get_attribute("value")]=			$text=$item->get_content();
			}
		} 
				 
	}

//associated field types
switch($arr_field_attr["type"])
{
	case "date":
	case "datetime":
	$arr_field_attr["sql_type"]="date";break;
 case "select":
 case "select_multiple":
 	$arr_field_attr["sql_type"]="int(11)";break;
 case "textarea":
 case "texthtml":
 	$arr_field_attr["sql_type"]="text";break;
default:
 	$arr_field_attr["sql_type"]="varchar(255)";	break;
}


	
?>	