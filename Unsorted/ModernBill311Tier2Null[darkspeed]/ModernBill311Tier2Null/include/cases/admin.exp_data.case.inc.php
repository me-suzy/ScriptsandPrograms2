<?php
// +----------------------------------------------------------------------+
// | ModernBill [TM] .:. Client Billing System                            |
// +----------------------------------------------------------------------+
// | Copyright (c) 2001-2002 ModernGigabyte, LLC                          |
// +----------------------------------------------------------------------+
// | This source file is subject to the ModernBill End User License       |
// | Agreement (EULA), that is bundled with this package in the file      |
// | LICENSE, and is available at through the world-wide-web at           |
// | http://www.modernbill.com/extranet/LICENSE.txt                       |
// | If you did not receive a copy of the ModernBill license and are      |
// | unable to obtain it through the world-wide-web, please send a note   |
// | to license@modernbill.com so we can email you a copy immediately.    |
// +----------------------------------------------------------------------+
// | Authors: ModernGigabyte, LLC <info@moderngigabyte.com>               |
// | Support: http://www.modernsupport.com/modernbill/                    |
// +----------------------------------------------------------------------+
// | ModernGigabyte and ModernBill are trademarks of ModernGigabyte, LLC. |
// +----------------------------------------------------------------------+


## Must be included ONLY once!
include_once("include/functions.inc.php");

## Validate that the user is an ADMIN or log them out
if (!testlogin()||!$this_admin||$this_user)  { if ($op!="exp_batch") { Header("Location: http://$standard_url?op=logout"); exit; } }
$ap = ($this_admin["admin_level"]==9) ? $this_admin["ap"] : NULL ;

$pw = ($password1) ? $password1 : md5(strip_tags($password)) ;
$result = mysql_query("SELECT * FROM admin WHERE admin_username='".$this_admin["admin_username"]."' AND admin_password='$pw'",$dbh) or die (mysql_error());

if ($result && mysql_num_rows($result) != 1)
{
  Header("Location: $page?op=menu&tile=dbexport&pw=1");
  exit;
}

if($continue)
{

if(!$dbh)dbconnect();

$client = getenv("HTTP_USER_AGENT");
if(ereg('[^(]*\((.*)\)[^)]*',$client,$regs))
{
   $os = $regs[1];
   $crlf = (eregi("Win",$os)) ? "\r\n" : "\n" ;
}

switch ($output) {
   case excel:
        $file_type   = "vnd.ms-excel";
        $file_ending = "xls";
   break;

   case word:
        $file_type   = "msword";
        $file_ending = "doc";
   break;

   case csv:
        $file_type   = "x-gzip";
        $file_ending = "txt";
   break;
}

header("Content-Type: application/$file_type");
header("Content-Disposition: attachment; filename=$db_table.$file_ending");
header("Pragma: no-cache");
header("Expires: 0");

$now_date = date("Y/m/d: h:i:s");
$title    = "Dump For Table $db_table from Database $db_name on $now_date";
$sql      = "Select * from $db_table";

$result = @mysql_query($sql,$dbh) or die(mysql_error());

switch ($output) {
   case excel:
        echo("$title\n");
        $sep = "\t";
        for ($i = 0; $i < mysql_num_fields($result); $i++) {
             echo mysql_field_name($result,$i) . "\t";
        }
        print("\n");
        $i = 0;
        while($row = mysql_fetch_row($result))
        {
            set_time_limit(60);
            $schema_insert = "";

            for($j=0; $j<mysql_num_fields($result);$j++)
            {
                if ($db_table=="client_info"&&$j==14) {
                    list($client_stamp,$billing_cc_num)=mysql_fetch_row(mysql_query("SELECT client_stamp,billing_cc_num FROM client_info WHERE client_id=".$row[0],$dbh));
                    $schema_insert .= encrpyt($client_stamp.$decrypt_key,$billing_cc_num,1).$sep;
                } else {
                    if(!isset($row[$j]))
                        $schema_insert .= "NULL".$sep;
                    elseif ($row[$j] != "")
                        $schema_insert .= "$row[$j]".$sep;
                    else
                        $schema_insert .= "".$sep;
                }
            }
            $schema_insert = str_replace($sep."$", "", $schema_insert);
            $schema_insert .= "\t";
            print(trim($schema_insert));
            print "\n";
            $i++;
        }
   break;

   case word:
        echo("$title\n\n");
        $sep = "\n";
        $i = 0;
        while($row = mysql_fetch_row($result))
        {
            set_time_limit(60);
            $schema_insert = "";

            for($j=0; $j<mysql_num_fields($result);$j++)
            {
                $field_name = mysql_field_name($result,$j);
                $schema_insert .= "$field_name:\t";

                if ($db_table=="client_info"&&$j==14) {
                    list($client_stamp,$billing_cc_num)=mysql_fetch_row(mysql_query("SELECT client_stamp,billing_cc_num FROM client_info WHERE client_id=".$row[0],$dbh));
                    $schema_insert .= encrpyt($client_stamp.$decrypt_key,$billing_cc_num,1).$sep;
                } else {
                    if(!isset($row[$j]))
                        $schema_insert .= "NULL".$sep;
                    elseif ($row[$j] != "")
                        $schema_insert .= "$row[$j]".$sep;
                    else
                        $schema_insert .= "".$sep;
                }
            }
            $schema_insert = str_replace($sep."$", "", $schema_insert);
            $schema_insert .= "\t";
            print(trim($schema_insert));
            print "\n----------------------------------------------------\n";
            $i++;
        }
   break;

   case csv:
        $sep = $delim;
        $i = 0;
        while($row = mysql_fetch_row($result))
        {
            set_time_limit(60);
            $schema_insert = "";

            for($j=0; $j<mysql_num_fields($result);$j++)
            {
                if ($db_table=="client_info"&&$j==14) {
                    list($client_stamp,$billing_cc_num)=mysql_fetch_row(mysql_query("SELECT client_stamp,billing_cc_num FROM client_info WHERE client_id=".$row[0],$dbh));
                    $schema_insert .= encrpyt($client_stamp.$decrypt_key,$billing_cc_num,1).$sep;
                } else {
                    if(!isset($row[$j]))
                        $schema_insert .= "NULL".$sep;
                    elseif ($row[$j] != "")
                        $schema_insert .= "$row[$j]".$sep;
                    else
                        $schema_insert .= "".$sep;
                }
            }
            $schema_insert  = str_replace($sep."$", "", $schema_insert);
            $schema_insert .= "\t";
            print(trim($schema_insert));
            print "\n";
            $i++;
        }
   break;
}
           } else {
             start_html();
             echo "<form method=post action=http://".$standard_url."$admin_page?".session_id().">";
             echo "<input type=hidden name=op value=exp_data>";
             echo "<input type=hidden name=db_table value=$db_table>";
             echo "<input type=hidden name=continue value=1>";
             echo "<input type=hidden name=output value=$output>";
             echo "<input type=hidden name=delim value=$delim>";
             admin_heading($tile);
             start_table(NULL,$a_tile_width);
                  echo "<tr><td colspan=2>
                          <center><input type=submit name=submit value=\"".DOWNLOADNOW."!\"></center>
                          <input type=hidden name=decrypt_key value=\"".md5(strip_tags($decrypt_key))."\" size=15 maxlength=15>
                          <input type=hidden name=password1 value=\"$pw\" size=15 maxlength=15>
                          </td>
                     </tr>";
             stop_table();
             stop_form();
             stop_html();
           }
?>