<?
	$name= $d_table->get_attribute("name");
	$caption = utf8_decode($d_table->get_attribute("caption"));
	$parent_id_ref = utf8_decode($d_table->get_attribute("parent_id_ref"));
		
	if ($caption=="") $caption=$name;
	//old method
   	$arr_tables[$name]=array($caption,$parent_id_ref);
	
	//new method
	$arr_tables[$name]['parent_id_ref'] =$parent_id_ref;
	
	if ($parent_id_ref!="")
		{
		$new_field =  $dom->create_element("d_field");
		 $d_table->append_child($new_field);
		$new_field->set_attribute("type", "select");
		$new_field->set_attribute("name", $parent_id_ref);
		$new_field->set_attribute("caption", "Parent");
		
		$field_nodes =  $d_table->child_nodes();
		$caption_node=$field_nodes[1];
		$caption_field= $caption_node->get_attribute("name");
		
		$table_ref=$dom->create_element("table_ref");
		$table_ref->set_attribute("d_table", $name);
		$table_ref->set_attribute("caption_field", $caption_field);
		
		$new_field = $new_field->append_child($table_ref);
		
		}
?>