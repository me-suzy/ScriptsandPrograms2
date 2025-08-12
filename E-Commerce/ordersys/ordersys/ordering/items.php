<?php
session_start();
header("Cache-control: private");
$table='item'; // mysql table name
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
// array of options and values for use with search and sort forms below
$option_value = array(
'Name'=>'Name/description',
'Category'=>'Category',
'Vendor'=>'Vendor',
'Manufacturer'=>'Manufacturer',
'Price'=>'Price',
'Size'=>'Size',
'Vendor_cat_no'=>'Vendor catalog no.',
'Manufacturer_cat_no'=>'Manufacturer cat. no.',
'order_date'=>'Date last ordered',
'update_date'=>'Date modified',
'insert_date'=>'Date added'
);
///////////////////////  build DATA table
echo ('<table width="750" summary="top" cellpadding="5" cellspacing="1" style="background-color:#efefef; border:0;">');
include ("top_part.php");
// MAIN form start
echo ('<form method="post" action="items.php?page='.$page.'&amp;where_condition='.rawurlencode($where_condition).'&amp;order_condition='.rawurlencode($order_condition).'#order">');
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
<tr style="background-color:#ffffcc;" valign="top">
<td style="width:75px;" valign="top"></td>
<td style="width:180px;" valign="top">Name/description</td>
<td valign="top">Vendor</td>
<td valign="top">Category</td>
<td valign="top"></td>
</tr>');
////////////////////// ROWS for data 
// start - alternate colors of table rows
for ($i = 0; $i < $numofrows; $i++)
{
$row = mysql_fetch_array($result);
if ($i % 2){echo ("<tr style=\"background-color:#f0f8ff;\" valign=\"top\">");} 
else {echo ("<tr style=\"background-color:#f0ffff;\" valign=\"top\">");}
  // end - alternate colors of table rows
  // start - build table rows  
echo ('<td valign="top">');
if (in_array($row["ID"], $_SESSION['checked'])){echo ('<input type="checkbox" name="unchecked['.$row["ID"].']" id="unchecked['.$row["ID"].']" value="'.$row["ID"].'" />Remove');}
else {echo ('<input type="checkbox" name="checked['.$row["ID"].']" value="'.$row["ID"].'" />Add');}
echo ('</td>');
echo (
  //---------------------------------------------------
'<td valign="top">'
  //---------------------------------------------------
.$row["Name"]);

if ($row["Size"] != '')
{echo ('<br /><span style="color:#736F6E;">'.$row["Size"].'  ');}
else {echo ('<br /><span style="color:#736F6E;">');}
if ($row["Price"] != '')
{echo ($currency.$row["Price"].'  ');}
if ($row["Vendor_cat_no"] != '')
{echo ('#'.$row["Vendor_cat_no"]);}
  //---------------------------------------------------
echo ('</span></td><td valign="top"><a href="items.php?smenu_1=Vendor&amp;sterm_1='.$row["Vendor"].'">'
.$row["Vendor"].'</a>');
  //---------------------------------------------------
echo ('</td><td valign="top"><span style="color:#736F6E;">'
.$row["Category"]);
  //---------------------------------------------------
echo ('</span></td><td valign="top"><span style="color:#736F6E;">');
if ($row["update_date"] !='' & $row["update_date"] !='0000-00-00')
{
echo ('Updated '.$row["update_date"].'<br />');
}
elseif ($row["add_date"] !='' & $row["add_date"] !='0000-00-00')
{
echo ('Added '.$row["update_date"].'<br />');
}

if ($row["order_date"] !='' & $row["order_date"] !='0000-00-00')
{
echo('Last ordered '.$row["order_date"].'<br />');
}
   // start - edit, detail, update links
    // detail link
echo ('<a href="interface_creator/index_short.php?table_name=item&amp;function=details&amp;where_field=id&amp;where_value='.$row["ID"].'" onclick="return popitup(\'interface_creator/index_short.php?table_name=item&amp;function=details&amp;where_field=id&amp;where_value='.$row["ID"].'\')">Details</a>'
);
    // edit and delete link
if (!($all_affect_items == "no" and $client == "not_allowed")){echo (' | <a href="interface_creator/index_short.php?table_name=item&amp;function=edit&amp;where_field=id&amp;where_value='.$row["ID"].'" onclick="return popitup(\'interface_creator/index_short.php?table_name=item&amp;function=edit&amp;where_field=id&amp;where_value='.$row["ID"].'\')"> Edit</a> | <a href="interface_creator/index_short.php?table_name=item&amp;function=delete&amp;where_field=id&amp;where_value='.$row["ID"].'" onclick="return confipop(\'interface_creator/index_short.php?table_name=item&amp;function=delete&amp;where_field=id&amp;where_value='.$row["ID"].'\')"> Delete</a>');}
   // end - edit, detail, update links
  //---------------------------------------------------
echo ('</span></td></tr>');
  // end - build table's data rows  
} // FOR END ==================================================================
} // END if num_sat>0
///////////////////////////////////////////////////////////////////////////



include ("bottom_part.php");
include ("footer.php");
?>