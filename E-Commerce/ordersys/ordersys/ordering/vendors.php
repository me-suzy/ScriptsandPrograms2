<?php
session_start();
header("Cache-control: private");
$table='vendor'; // mysql table name
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
'Name'=>'Name',
'Phone'=>'Phone number',
'Fax'=>'Fax number',
'Address'=>'Address',
'website'=>'Website',
'insert_date'=>'Date added',
'update_date'=>'Date modified'
);
///////////////////////  build DATA table
echo ('<table width="750" summary="top" cellpadding="5" cellspacing="1" style="background-color:#efefef; border:0;">');
include ("top_part.php");
// MAIN form start
echo ('<form method="post" action="vendors.php#order">');
// IF START ################################################
if ($num_sat > 0) 
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
<tr style="background-color:#ffffff;" valign="top"><td valign="top" colspan="5"><b>Vendors</b>:</td></tr>
<tr style="background-color:#ffffcc;" valign="top">
<td style="width:100px;" valign="top">Name</td>
<td style="width:180px;" valign="top">Phone and fax</td>
<td valign="top">Address</td>
<td valign="top">Website</td>
<td style="width:180px;" valign="top"></td>
</tr>');
////////////////////// ROWS for data 
// start - alternate colors of table rows
// FOR start ============================================================
for ($i = 0; $i < $numofrows; $i++)
{
$row = mysql_fetch_array($result);
if ($i % 2){echo ("<tr style=\"background-color:#ffcc99;\" valign=\"top\">");} 
else {echo ("<tr style=\"background-color:#ffcccc;\" valign=\"top\">");}
  // end - alternate colors of table rows
  // start - build table rows  
echo ('<td valign="top">');
if ($row['Name'] != '')
{echo ($row['Name']);}
echo ('</td>');
  //---------------------------------------------------
echo ('<td valign="top">');
if ($row['Phone'] != '')
{echo ('Phone - '.$row['Phone']);}
if ($row['Fax'] != '')
{echo ('<br />Fax - '.$row['Fax']);}
echo ('</td>');
  //---------------------------------------------------
echo ('<td valign="top">');
if ($row['Address'] != '')
{echo ($row['Address'].'<br />');}
echo ('</td>');
  //---------------------------------------------------
echo ('<td valign="top">');
if ($row['website'] != '')
{echo ('<a href="'.$row['website']).'">Website</a>';}
echo ('</td>');
  //---------------------------------------------------
echo ('<td valign="top"><span style="color:#736F6E;">');
if ($row["insert_date"] !='' & $row["insert_date"] !='0000-00-00')
{
echo ('Added '.$row["insert_date"].'<br />');
}
elseif ($row["update_date"] !='' & $row["update_date"] !='0000-00-00')
{
echo ('Modified '.$row["update_date"].'<br />');
}

   // start - edit, detail, update links
    // detail link
echo ('<a href="interface_creator/index_short.php?table_name=vendor&amp;function=details&amp;where_field=Vendor_ID&amp;where_value='.$row['Vendor_ID'].'" onclick="return popitup(\'interface_creator/index_short.php?table_name=vendor&amp;function=details&amp;where_field=Vendor_ID&amp;where_value='.$row['Vendor_ID'].'\')">Details</a>'
);
    // edit and delete link
if (!($all_affect_items == "no" and $client == "not_allowed")){echo (' | <a href="interface_creator/index_short.php?table_name=vendor&amp;function=edit&amp;where_field=Vendor_ID&amp;where_value='.$row['Vendor_ID'].'" onclick="return popitup(\'interface_creator/index_short.php?table_name=vendor&amp;function=edit&amp;where_field=Vendor_ID&amp;where_value='.$row['Vendor_ID'].'\')"> Modify</a> | <a href="interface_creator/index_short.php?table_name=vendor&amp;function=delete&amp;where_field=Vendor_ID&amp;where_value='.$row['Vendor_ID'].'" onclick="return confipop(\'interface_creator/index_short.php?table_name=vendor&amp;function=delete&amp;where_field=Vendor_ID&amp;where_value='.$row['Vendor_ID'].'\')"> Delete</a>');}
   // end - edit, detail, update links
  //---------------------------------------------------
echo ('</span></td></tr>');

  // end - build table's data rows  
} 
// FOR END ==================================================================
}
// END if num_sat>0 ################################################
$since_order = '<table width="750" summary="top" cellpadding="5" cellspacing="1" style="background-color:#efefef; border:0;"><tr style="background-color:#ffffff;" valign="top"><td valign="top" colspan="5"><b>Items set for ordering</b>:</td></tr></table>';
include ("bottom_part.php");
include ("footer.php");
?>