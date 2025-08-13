<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/
include_once("include/functions.inc.php");
GLOBAL $dbh,$batch_export_file;
if(!$dbh)dbconnect();
if (!testlogin()||!$this_admin||$this_user)  { if ($op!="exp_batch") { Header("Location: http://$standard_url?op=logout"); exit; } }set_time_limit(100000);
$result = mysql_query("SELECT * FROM authnet_batch");
$schema_insert = "";
$i = 0;

$client = getenv("HTTP_USER_AGENT");
if(ereg('[^(]*\((.*)\)[^)]*',$client,$regs))
{
   $os = $regs[1];
   $crlf = (eregi("Win",$os)) ? "\r\n" : "\n" ;
}

while($row = mysql_fetch_row($result)) {
  for($j=0; $j<mysql_num_fields($result);$j++) {
      if($j==6)
      {
        list($client_stamp,$billing_cc_num)=mysql_fetch_row(mysql_query("SELECT client_stamp,billing_cc_num FROM client_info WHERE client_id=".$row[9],$dbh));
        $schema_insert.=encrpyt($client_stamp.$decrypt_key,$billing_cc_num,1).$batch_delim;
      }
      elseif($j==3)
      {
        $schema_insert.=str_replace(",","",display_currency($row[$j],1)).$batch_delim;
      } else
      {
        if(!isset($row[$j])) {
            $schema_insert .= "NULL".$batch_delim;
        } elseif ($row[$j] != "") {
            $schema_insert .= $row[$j].$batch_delim;
        } else {
            $schema_insert .= "".$batch_delim;
        }
      }
  }
  $schema_insert  = substr($schema_insert, 0, -1);
  $schema_insert .= $crlf;
  $i++;
}
$batch_export_file = str_replace("%%DATE%%",date("Y_m_d"),$batch_export_file);
header( "Content-type: application/x-gzip" );
header( "Content-Disposition: attachment; filename=$batch_export_file.txt" );
header( "Content-Description: Batch Export" );
print $schema_insert;
?>