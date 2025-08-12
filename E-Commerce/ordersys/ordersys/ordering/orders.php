<?php
session_start();
header("Cache-control: private");
$table='order'; // mysql table name
include ("header.php");
// check_login; will go to login if authentication enabled in config.php
include ('interface_creator/check_login.php');
$date = date("l, F j, Y");
echo ('<span style="color:#dcdcdc;">'.$log_status);
if (!($all_affect_items == "no") or ($all_affect_items == "no" and $client == "allowed")){
echo ('
<a>Add </a><a href="'.$site_url.'index_short.php?function=show_insert_form&amp;table_name=item" onclick="return popitup(\''.$site_url.'index_short.php?function=show_insert_form&amp;table_name=item\')">item</a> / <a href="'.$site_url.'index_short.php?function=show_insert_form&amp;table_name=vendor" onclick="return popitup(\''.$site_url.'index_short.php?function=show_insert_form&amp;table_name=vendor\')">vendor</a> || <a href="orders.php">View/adjust past orders</a> / <a href="vendors.php">vendors</a> || ');}
echo ('<a href="help/help.htm" onclick="return popitup(\'help/help.htm\')">Help</a> || <a>'.$date.'</a></span></p></div>
<div style="padding-left: 5px;">');

// show expenditure
// Get client's IP address
if ($all_see_expenditure == "no")
{
if (empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
 {$IP = $_SERVER["REMOTE_ADDR"];}
  else {$IP = $_SERVER["HTTP_X_FORWARDED_FOR"];} 
$lh = gethostbyaddr($_SERVER['REMOTE_ADDR']);
// Test that the address is allowed; then update history table
$test=$IP.".".$lh;
if(in_array($test, $allowed2) || in_array($IP, $allowed2))
 {
 $show = "yes";
 }
else {$show = "no";} 
}
else
{
$show = "yes";
}

if ($show == "yes")
{
 echo ('
<table width="750" summary="top" cellpadding="5" cellspacing="1" style="background-color:#efefef; border:0;">
<tr style="background-color:#ffffcc;">
<td style="background-color:#ffffcc;">
<form method="post" action="orders.php">');
 if 
 (
 !(isset($_POST['val'])) 
 or 
 !(preg_match('/^[0-9]+$/',$_POST['val']))
 )
 {$_POST['val'] = '30';}
 $querytotal = "SELECT COUNT(*) FROM `order` WHERE DATE_SUB(CURDATE(),INTERVAL ".$_POST['val']." DAY) <= `ordered_date`"; 
 $resulttotal1 = mysql_fetch_row(mysql_query($querytotal));
 $num_orders = $resulttotal1[0];
 $query = "SELECT SUM(`total_amount`) - SUM(`cost_reduce`) + SUM(`cost_add`)  FROM `order` WHERE DATE_SUB(CURDATE(),INTERVAL ".$_POST['val']." DAY) <= `ordered_date`";
 $sql = mysql_query($query);
 $result = mysql_fetch_row($sql);
 if (isset($result[0]) and $result[0]>0){echo ($currency.$result[0].' spent in the last <input type="text" name="val" id="val" maxlength"4" size="3" value="'.$_POST['val'].'" /> day(s) on '.$num_orders.' order(s)');
 echo ('&nbsp;<input type="submit" value="Re-calculate" /><a href="help/help.htm#calc" onclick="return popitup(\'help/help.htm#calc\')">?</a></form>');}
 echo ('</td></tr></table>');
}
// end show expenditure

// array of options and values for use with search and sort forms below
$option_value = array(
'ordered_date'=>'Order date',
'total_amount'=>'Order cost',
'status'=>'Order status',
'reception_status'=>'Reception status',
'cost_reduce'=>'-ve adjustment',
'cost_add'=>'+ve adjustment',
'description'=>'Order summary',
'ordered_by'=>'Ordered by',
'comment'=>'Comment',
'order_id'=>'Order ID',
'modified_date'=>'Date modified'
);
///////////////////////  build DATA table
echo ('<table width="750" summary="top" cellpadding="5" cellspacing="1" style="background-color:#efefef; border:0;">');
include ("top_part.php");
// MAIN form start
echo ('<form method="post" action="items.php#order">');
if ($num_sat > 0) // IF START ################################################
{
//////////////////// ROW for column headings
echo ('
<colgroup>
<col valign="top" align="left" style="width:75px;"></col>
<col valign="top" align="left" style="width:180px;"></col>
<col valign="top" align="left"></col>
<col valign="top" align="left"></col>
<col valign="top" align="left"></col>
</colgroup>
<tr style="background-color:#ffffff;" valign="top"><td valign="top" colspan="5"><b>Past orders</b>: Adjust cost and status, and delete cancelled orders</td></tr>
<tr style="background-color:#ffffcc;" valign="top">
<td style="width:75px;" valign="top">Order ID</td>
<td style="width:180px;" valign="top">Summary</td>
<td valign="top">Cost</td>
<td valign="top">Status</td>
<td style="width:180px;" valign="top"></td>
</tr>');
////////////////////// ROWS for data 
// start - alternate colors of table rows
for ($i = 0; $i < $numofrows; $i++)
{
$row = mysql_fetch_array($result);
if ($i % 2){echo ("<tr style=\"background-color:#ccffcc;\" valign=\"top\">");} 
else {echo ("<tr style=\"background-color:#ccff99;\" valign=\"top\">");}
  // end - alternate colors of table rows
  // start - build table rows  
echo ('<td valign="top">');
if ($row['order_id'] != '')
{echo ($row['order_id']);}
echo ('</td>');
  //---------------------------------------------------
echo ('<td valign="top">');
if ($row['description'] != '')
{echo ($row['description']);}
echo ('</td>');
  //---------------------------------------------------
echo ('<td valign="top">');
if ($row['total_amount'] != '')
{echo ($currency.$row['total_amount'].'<br />');}
if ($row['cost_add'] != '' and $row['cost_add']>0)
{echo ('+ '.$currency.$row['cost_add'].'<br />');}
if ($row['cost_reduce'] != '' and $row['cost_reduce']>0)
{echo ('- '.$currency.$row['cost_reduce']);}
echo ('</td>');
  //---------------------------------------------------
echo ('<td valign="top">');
if ($row['status'] != '')
{echo ($row['status']).'<br />';}
if ($row['reception_status'] != '')
{echo ($row['reception_status']);}
echo ('</td>');
  //---------------------------------------------------
echo ('<td valign="top"><span style="color:#736F6E;">');
if ($row["ordered_date"] !='' & $row["ordered_date"] !='0000-00-00')
{
echo ('Ordered '.$row["ordered_date"].'<br />');
}
elseif ($row["modified_date"] !='' & $row["modified_date"] !='0000-00-00')
{
echo ('Modified '.$row["modified_date"].'<br />');
}

   // start - edit, detail, update links
    // detail link
echo ('<a href="interface_creator/index_short.php?table_name=order&amp;function=details&amp;where_field=order_id&amp;where_value='.$row['order_id'].'" onclick="return popitup(\'interface_creator/index_short.php?table_name=order&amp;function=details&amp;where_field=order_id&amp;where_value='.$row['order_id'].'\')">Details</a>'
);
    // edit and delete link
if (!($all_affect_items == "no" and $client == "not_allowed")){echo (' | <a href="interface_creator/index_short.php?table_name=order&amp;function=edit&amp;where_field=order_id&amp;where_value='.$row['order_id'].'" onclick="return popitup(\'interface_creator/index_short.php?table_name=order&amp;function=edit&amp;where_field=order_id&amp;where_value='.$row['order_id'].'\')"> Modify</a> | <a href="interface_creator/index_short.php?table_name=order&amp;function=delete&amp;where_field=order_id&amp;where_value='.$row['order_id'].'" onclick="return confipop(\'interface_creator/index_short.php?table_name=order&amp;function=delete&amp;where_field=order_id&amp;where_value='.$row['order_id'].'\')"> Delete</a>');}
   // end - edit, detail, update links
  //---------------------------------------------------
echo ('</span></td></tr>');
  // end - build table's data rows  
} // FOR END ==================================================================
} // END if num_sat>0
///////////////////////////////////////////////////////////////////////////
$since_order = '<table width="750" summary="top" cellpadding="5" cellspacing="1" style="background-color:#efefef; border:0;"><tr style="background-color:#ffffff;" valign="top"><td valign="top" colspan="5"><b>Items set for ordering</b>:</td></tr></table>';
include ("bottom_part.php");
include ("footer.php");
?>