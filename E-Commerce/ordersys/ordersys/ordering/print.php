<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style type="text/css" media="all">
/*<![CDATA[*/
 <!--
  @media all {
 .style1 {
 font-family: Arial, Helvetica, sans-serif;
 font-size: 13pt;
 line-height:18pt;
 }
 .style2 {
 font-family: Arial, Helvetica, sans-serif;
 font-size: 12pt;
 font-weight: bold;
 }
 .style3 {
 font-family: Arial, Helvetica, sans-serif;
 font-size: 14pt;
 text-decoration: underline;
 font-weight: bold;
 }
 body, td 
 {
 font-family:'Courier new', Courier, monospace;
 font-size: 13pt;
 line-height:18pt;
 }
 }
 -->
/*]]>*/
</style>
<title></title>
</head>
<body>
<center>
<?php
include ('config.php');
echo ('
<table summary="none" width="640pt" cellspacing="1" border="0" cellpadding="5" style="background-color:#FFFFFF; border:0; width:640pt;">

<tr valign="top">
<td colspan="2" valign="top" style=" align:center; text-align: center; background-color:#FFFFFF;"><span class="style3">'.$form_title.'</span><br /><br /><br /></td>
</tr>

<tr valign="top">
<td valign="top" style="align:left; width:320pt; background-color:#FFFFFF;">');

// mysql parameters
$connection = mysql_connect($host, $user, $pass) or
die("ERROR: Could not connect to the MySQL server! Either it is down or the username/password used for connecting to it as specified in the config.php file are incorrect. If the MySQL server is running, it will respond with an error message (below)<br /><br />".mysql_error()."</p>"); 
$selected = mysql_select_db($db_name, $connection) or die("ERROR: Could not select the MySQL database! The MySQL account being used (as specified in the config.php file) may not have access privileges.<br /><br />".mysql_error()."</p>");
// top left part
$date = date("n-j-y"); 
echo ('<b class="style1">DATE OF ORDER:</b> '.$date); 
echo ('<br /><b class="style1">REQUESTED BY:</b> '.$_POST['by']); 
echo ('
<br /><b class="style1">INVESTIGATOR:</b> '.$chief.'<br />
<b class="style1">ROOM# BLDG:</b> '.$room_bldg.' 
<b class="style1">EXT:</b> '.$extn.'<br />
<b class="style1">VENDOR:</b> '.$_POST['vendor'].'<br />
<b class="style1">PHONE#:</b> '.$_POST['phone'].'<br />
<b class="style1">FAX#:</b> '.$_POST['fax'].'<br />
<b class="style1">ADDRESS:</b> '.$_POST['address'].'
</td>
<td valign="top" style="align:left; width:320pt; background-color:#FFFFFF;">
<b class="style1">DATE ORDERED:</b><br />
<b class="style1">REQ#:</b><br />
<b class="style1">P.O.#:</b><br />
<b class="style1">GRANT:</b> '.$_POST['grant'].'<br />
<b class="style1">CONTACT PERSON:</b><br />
<b class="style1">DELIVERY DATE:</b><br />
<b class="style1">CUST.#:</b><br />
<b class="style1">REF.#:</b><br />
</td>
</tr>
<tr valign="top">
<td colspan="2" valign="top" style="text-align:center; align:center; background-color:#FFFFFF;"><span class="style3">
FOR RUSH ORDERS:</span><br /><span class="style2">DATE NEEDED:</span> '.$_POST['rushdate'].'<br /><br /><br />
</td>
</tr>
</table>
<table summary="none" width="640pt" cellspacing="1" border="0" cellpadding="5" style="background-color:#000000; border:0; width:640pt;">
<tr valign="top">
<td valign="top" style="align:left; width:55pt; background-color:#FFFFFF;" class="style2"><u>QUANT.</u></td>
<td valign="top" style="align:left; width:67pt; background-color:#FFFFFF;" class="style2"><u>UNIT OF<br />MEASURE</u></td>
<td valign="top" style="align:left; width:333pt; background-color:#FFFFFF;" class="style2"><u>CAT.#</u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u><u>DESCRIPTION</u></td>
<td valign="top" style="align:left; width:40pt; background-color:#FFFFFF;" class="style2"><u>UNIT<br />PRICE ('.$currency.')</u></td>
<td valign="top" style="align:left; width:50pt; background-color:#FFFFFF;" class="style2"><u>TOTAL<br />PRICE ('.$currency.')</u></td>
</tr>'
);
$summary = ''; // end order summary
$total_total = ''; // total order cost
foreach ($_POST as $key => $value) // item ID => item quantity
{
if (preg_match('/^[0-9]+$/',$key)) // only if item ID
 {
 $sql = mysql_query("SELECT `Name`,`Size`,`Price`,`Vendor_cat_no` FROM `item` WHERE `ID`=".$key); 
 $row = mysql_fetch_array($sql);
 if($row)
  {
  $item_total = $value*$row['Price']; // each item cost as per amount
  $total_total = $item_total + $total_total;
  $summary .= $row['Vendor_cat_no'].': '.$row['Name'].' ('.$value.') @'.$row['Price'].' per '.$row['Size'].'; ';
  echo('
  <tr valign="top">
  <td valign="top" style="align:left; width:55pt; background-color:#FFFFFF;">'.$value.'</td>
  <td valign="top" style="align:left; width:67pt; background-color:#FFFFFF;">'.$row['Size'].'</td>
  <td valign="top" style="align:left; width:313pt; background-color:#FFFFFF;"><b>'.$row['Vendor_cat_no'].'</b> '.$row['Name'].'</td>
  <td valign="top" style="align:left; width:60pt; background-color:#FFFFFF;">'.$row['Price'].'</td>
  <td valign="top" style="align:left; width:60pt; background-color:#FFFFFF;">'.$item_total.'</td>
  </tr>
  ');
  }
 }
} // end foreach
if ($_POST['comment'] !== '')
{echo('
<tr valign="top">
<td colspan="5" valign="top" style="align:left; background-color:#FFFFFF;">'.$_POST['comment'].'</td>
</tr>');
} 
echo('</table></center>');
// update order history table depending on config.php parameters
// Get client's IP address
if ($all_order_history == "no")
{
if (empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
 {$IP = $_SERVER["REMOTE_ADDR"];}
  else {$IP = $_SERVER["HTTP_X_FORWARDED_FOR"];} 
$lh = gethostbyaddr($_SERVER['REMOTE_ADDR']);
// Test that the address is allowed; then update history table
$test=$IP.".".$lh;
if(in_array($test, $allowed1) || in_array($IP, $allowed1))
 {
 $update = "yes";
 }
else {$update = "no";} 
}
else
{
$update = "yes";
}
if ($update == "yes")
{
 function add_slashes($value) {
    if (is_array($value)) {
        foreach ($value as $index => $val) {
            $value[$index] = add_slashes($val);
        }
        return $value;
    } else {
        return addslashes($value);
    }
 }
 // update
 $summary .= 'VENDOR - '.$_POST['vendor'].'; GRANT - '.$_POST['grant'].'; COMMENT - '.$_POST['comment'];
 $ordered_date = date("Y-m-d");
 $total_amount = $total_total;
 $status = "Ordered";
 $query = "INSERT INTO `order` (`description`,`ordered_date`,`status`,`total_amount`,`ordered_by`) VALUES ('".add_slashes($summary)."','".$ordered_date."','Ordered','".$total_total."','".add_slashes($_POST['by'])."')";
 mysql_query ($query) or die ('');
 // update item table for 'last ordered' field
 foreach ($_POST as $key => $value) // item ID => item quantity
 {
 if (preg_match('/^[0-9]+$/',$key)) // only if item ID
  {
  $query_lo = "UPDATE `item` SET `order_date` = '".date("Y-m-d")."' WHERE `ID`=".$key; 
  mysql_query($query_lo) or die ('');
  }
 } 
}
// Unset the session variables
session_unregister('checked');
session_unregister('unchecked');
unset($_SESSION['checked']);
unset($_SESSION['unchecked']);
unset($checked);
unset($unchecked);
?>
</body>
</html>
