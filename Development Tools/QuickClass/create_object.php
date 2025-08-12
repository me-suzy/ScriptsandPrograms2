<?
function space($characters)
{
	$return_string = "";

	if($characters > 0)
	{
		for($i = 1; $i <= $characters; $i++)
		{	
			$return_string .= " ";
		}
	}
	
	return $return_string;
}

if($_POST["submitted"] == "1")
{
	$max_count = 0;
	$fields_array = array();
	
	$tok = strtok($_POST["fields"], "\n");
	
	while ($tok)
	{
		array_push($fields_array, trim($tok));
		$tok = strtok("\n");
	}
	
	foreach($fields_array as $field)
	{
		if(strlen($field) > $max_count) $max_count = strlen($field);
	}
	
	print "<pre>\n";
	
	print "-------------------- Begin Object File --------------------\n";
	
	print "class " . $_POST["object"] . "_obj\n";
	print "{\n";

	#---------- Vars ----------
	foreach($fields_array as $field)
	{
		print "\tvar \$" . trim($field) . ";\n";
	}
	
	print "\n";
	
	#---------- Add ----------
	print "\t#add an object record\n";
    print "\tfunction add()\n";
	print "\t{\n";
	print "\t\t\$fields = array(\"" . $fields_array[0] . "\"";
	for($i = 1; $i < count($fields_array); $i++)
	{
        print ",\n\t\t\t\t\t\t\"" . $fields_array[$i] . "\"";
	}
	print ");\n\n";
	
	print "\t\t\$values = array(\$this->" . $fields_array[0] . "";
	for($i = 1; $i < count($fields_array); $i++)
	{
        print ",\n\t\t\t\t\t\t\$this->" . $fields_array[$i] . "";
	}
	print ");\n\n";
	
	print "\t\treturn \$GLOBALS[\"obj_data\"]->add_record(\$GLOBALS[\"g_db\"], \"" . $_POST["db"] . "\", \$fields, \$values);\n\n";
    print "\t}\n\n";
	
	#---------- Update ----------
	print "\t#update an object record\n";
    print "\tfunction update()\n";
	print "\t{\n";
	print "\t\t\$fields = array(\"" . $fields_array[0] . "\"";
	for($i = 1; $i < count($fields_array); $i++)
	{
        print ",\n\t\t\t\t\t\t\"" . $fields_array[$i] . "\"";
	}
	print ");\n\n";
	
	print "\t\t\$values = array(\$this->" . $fields_array[0] . "";
	for($i = 1; $i < count($fields_array); $i++)
	{
        print ",\n\t\t\t\t\t\t\$this->" . $fields_array[$i] . "";
	}
	print ");\n\n";
	
	print "\t\treturn \$GLOBALS[\"obj_data\"]->update_record(\$GLOBALS[\"g_db\"], \"" . $_POST["db"] . "\", \"" . $fields_array[0] . " = \" . \$this->" . $fields_array[0] . ", \$fields, \$values);\n";
    print "\t}\n\n";
	
	#---------- Delete ----------
	print "\t#delete an object record\n";
    print "\tfunction delete(\$id)\n";
    print "\t{\n";
    print "\t\t\$GLOBALS[\"obj_data\"]->delete_record(\$GLOBALS[\"g_db\"], \"DELETE FROM " . $_POST["db"] . " WHERE " . $fields_array[0] . " = \" . \$id);\n\n";
	print "\t\treturn;\n";
    print "\t}\n\n";
	
	#---------- Populate ----------
	print "\t#populate the object\n";
    print "\tfunction populate(\$id)\n";
    print "\t{\n";
    print "\t\t\$records = \$GLOBALS[\"obj_data\"]->get_records(\$GLOBALS[\"g_db\"], \"SELECT * FROM " . $_POST["db"] . " WHERE " . $fields_array[0] . " = \$id LIMIT 1\");\n";
    print "\t\tforeach(\$records as \$record)\n";
	print "\t\t{\n";
	foreach($fields_array as $field)
	{
		print "\t\t\t\$this->" . $field . space($max_count - strlen($field)) . " = \$record[\"" . $field . "\"];\n";
	}
	print "\n\t\t\treturn;\n";
	print "\t\t}\n\n";
	print "\t\treturn;\n";
	print "\t}\n\n";
	
	#---------- Clear ----------
	print "\t#clear the object\n";
    print "\tfunction clear()\n";
    print "\t{\n";
	foreach($fields_array as $field)
	{
		print "\t\t\$this->" . $field . space($max_count - strlen($field)) . " = \"\";\n";
	}
	print "\t}\n\n";
	
	#---------- Get ----------
	print "\t#get multiple records of the object\n";
    print "\tfunction get(\$where = \"1\")\n";
    print "\t{\n";
    print "\t\treturn \$GLOBALS[\"obj_data\"]->get_records(\$GLOBALS[\"g_db\"], \"SELECT * FROM " . $_POST["db"] . " WHERE \$where\");\n";
    print "\t}\n";
	
	print "}\n";	
	print "-------------------- End Object File --------------------\n\n\n";
	
	print "-------------------- Begin SQL DB Structure --------------------\n";
	#---------- DB ----------
	print "CREATE TABLE `" . $_POST["db"] . "` (\n";
	print "\t`" . $fields_array[0] . "` int(10) unsigned NOT NULL auto_increment,\n";
	for($i = 1; $i < count($fields_array); $i++)
	{
		print "\t`" . $fields_array[$i] . "` tinytext NOT NULL,\n";
	}
	print "\tPRIMARY KEY  (`" . $fields_array[0] . "`)\n";
	print ") TYPE=InnoDB;\n";
	print "-------------------- End SQL DB Structure --------------------";
	
	print "</pre>\n";
	print "<script>alert('View source and copy/paste into object file')</script>\n";	
} else {
?>
<form name="main" method="post" action="">
  <p align="center">Object Name:<br>
    <input name="object" type="text" id="object">
</p>
  <p align="center">Table Name:<br> 
    <input name="db" type="text" id="db">
  </p>
  <p align="center">
    Table Columns/Class Members:<br>
    <textarea name="fields" cols="30" rows="25" id="fields"></textarea> <br>
One per line
    </p>
  <p align="center">
    <input name="generate" type="submit" id="generate" value="Generate">
    <input name="submitted" type="hidden" id="submitted" value="1">
  </p>
</form>
<?	
}
?>