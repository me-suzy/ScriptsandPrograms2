<?PHP 
require("engine_admin.inc.php");
	@ini_set('max_execution_time', '950*60');
	@set_time_limit (950*60);
	$msgCounter == 0;
$result = mysql_query ("SELECT email,name,field1,field2,field3,field4,field5,field6,field7,field8,field9,field10,sdate FROM ListMembers
						 WHERE email != ''
						 AND nl LIKE '$nl'
                       	ORDER BY email
");
$count = mysql_num_fields($result); 
for ($i = 0; $i < $count; $i++){ 
$header .= "\"".mysql_field_name($result, $i)."\","; 
} 
while($row = mysql_fetch_row($result)){ 
$line = "\n"; 
foreach($row as $value){ 
if(!isset($value) || $value == ","){ 
$value = ""; 
}else{ 
$value = str_replace('"', '""', $value); 
$value = '"' . $value . '",'; 
} 
$line .= $value; 
} 
$data .= trim($line)."\n"; 
} 
$data = str_replace("\r", "", $data); 
if ($data == "") { 
$data = "\no matching records found\n"; 
} 
header("Content-type: application/octet-stream"); 
header("Content-Disposition: attachment; filename=excelfile.csv");
header("Pragma: no-cache");
header("Expires: 0");
echo $header."\n".$data; 
?> 