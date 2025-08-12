<?php
// export data - Excel format etc.
// mysql db connection
include 'config.php';
$connection = @ mysql_connect($host, $user, $pass) or
die("Could not connect to MySQL database"); 
$selected = mysql_select_db($db_name, $connection) or die("Could not select
MySQL database");
// only if export button pressed and mysql query and table name and type of export known
if (isset($_POST['export']) and isset($_POST['parameter']) and isset($_POST['table']))
{
///////////////////////////////////////////////////////////////////////////////////
$date = date("Ymd");
$table = $_POST['table'];
$parameter = stripslashes($_POST['parameter']);
 // in the export pull down menu ' llll ' separates type and query in option value
list($type, $sqlquery) = explode(" llll ", $parameter);
$query = "SELECT * FROM ".$table." ".$sqlquery;
$result = mysql_query($query);
 // no. of rows
$count = mysql_num_rows($result);
 // no. of columns
$count_cols = mysql_num_fields($result);
 // set filename - tablename_date format
$filename = $table."_".$date;
// if excel
  // header is column headings
  // data is rest
  // made of many lines each made of many values
///////////////////////////////////////////////////////////////////////////////////
if ($type  == "Excel")
{
 $header = "Downloaded ".$date." from MySQL database. More up-to-date data may be available on the website. MySQL table headings are shown as column headings.\n";
 // the column headings -------------------------------------
 for ($i = 0; $i < $count_cols; $i++) 
 {
 $header .= mysql_field_name($result, $i)."\t";
 }
 // the rows ------------------------------------------------
 while($row = mysql_fetch_row($result)) 
 { 
 $line = ''; 
 foreach($row as $value) 
  { 
   if (!isset($value) OR $value == "") 
   { 
   $value = "\t"; 
   } 
   else 
   { 
   $value = str_replace('"', '""', $value); 
   $value = '"' . $value . '"' . "\t"; 
   } 
  $line .= $value; 
  } 
 $data .= trim($line)."\n"; 
 } 
 // clean ---------------------------------------------------
 $data = str_replace("\r"," ",$data);
 if ($data == "") 
 {
 $data = "\nno matching records found\n";
 }
 // start sending -------------------------------------------
 ob_end_clean();
 header("Content-type: application/vnd.ms-excel");
 header("Content-Disposition: attachment; filename=".$filename.".xls");
 header("Expires: 0");
 header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
 header("Pragma: public");
 echo($header."\n".$data);
}
///////////////////////////////////////////////////////////////////////////////////
if ($type  == "CSV")
{
 $csv = "Downloaded ".$date." from MySQL database. More up-to-date data may be available on the website. MySQL table headings are shown as column headings.\n";
 // heading -------------------------------------------------
 for ($i = 0; $i < $count_cols; $i++) 
 {
 $csv .= mysql_field_name($result, $i).",";
 }
 $csv = substr($csv, 0, -1); // delete the last ","
 $csv .= "\n"; // make it a line
 // rest ----------------------------------------------------
 while($row = mysql_fetch_row($result)) 
 { 
 $line = ''; 
 
 foreach($row as $value) 
  { 
   // value - empty - put comma separator
   if (!isset($value) OR $value == "") 
   { 
   $value = ","; 
   } 
   else 
   // value - not empty - clean and put comma separator
   { 
   $value = str_replace('"', '""', $value);
   $value = str_replace('\r', '"\r"', $value);
   $value = str_replace('\n', '"\n"', $value);
   $value = '"'.$value.'"';
   $value = $value . ","; 
   } 
  // string values to make line
  $line .= $value;
  } 
 $line = substr($line, 0, -1); // delete the last ","
 $csv .= $line."\n"; // make it a line
 }  
 // start sending -------------------------------------------
 ob_end_clean();
 header("Content-Type: text/x-csv");
 header("Content-Disposition: attachment; filename=".$filename.".csv");
 header("Expires: 0");
 header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
 header("Pragma: public");
 echo($csv);
}

///////////////////////////////////////////////////////////////////////////////////
}
?>