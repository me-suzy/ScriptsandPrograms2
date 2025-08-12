<?php
$table='others'; // mysql table name
$head_extra = ''; // extra to include in HTML before </head>; style, javascript
$heading_color = '#82CAFA'; // table column heading and page number list row
$row_color_1 = '#bdedff'; // alternate row colors
$row_color_2 = '#ffffff'; // alternate row colors
$extra_bottom = ''; // extra, will appear at bottom; message
$column_nos = '6'; // number of columns - for colspan table rows (page number row, etc.)
// header
include ("../header.php");
echo ('<p><span style="color:#dcdcdc;">'.$header_to_show.' || <a href="../help/help.htm" onclick="return popitup(\'../help/help.htm\')">Help</a> || <a>'.$date.'</a></span></p></div><div style="padding-left: 5px;">');
// array of options and values for use with search and sort forms below
$option_value = array(
'name'=>'Name/description',
'category'=>'Category',
'usage'=>'Usage',
'location'=>'Location',
'condition'=>'Condition',
'quantity'=>'Quantity',
'requirement'=>'Requirement',
'modified_on'=>'Update date',
'added_on'=>'Add date',
'modified_by'=>'Updater',
'added_by'=>'Adder'
);
///////////////////////////////////////////////////////////////////////////////
// build upper table
echo ('<table width="750" summary="top" cellpadding="5" cellspacing="1" style="background-color:#dcdcdc; border:0;  background-image:url(\'../images/others_small.gif\'); background-position: top right;background-repeat: no-repeat;"><tr><td>');
include ("../top_part.php");
if ($num_sat !==0) // IF START ################################################
{
// column headings
echo ('<colgroup><col width="40" valign="top" align="left" /><col valign="top" align="left" /><col valign="top" align="left" /><col valign="top" align="left" /><col valign="top" align="left" /><col valign="top" width="170" align="left" /></colgroup><tr style="background-color:'.$heading_color.';" valign="top"><td></td><td>Name/description</td><td >Category</td><td>Condition</td><td>Location</td><td></td></tr>');  
// start - alternate colors of table rows
for ($i = 0; $i < $numofrows; $i++) // FOR START ==============================
{
$row = mysql_fetch_array($result);
if ($i % 2){echo ("<tr style=\"background-color:".$row_color_1.";\" valign=\"top\">");} 
else {echo ("<tr style=\"background-color:".$row_color_2.";\" valign=\"top\">");}
  // end - alternate colors of table rows
  // serial number of item (not record ID)
  $sn = (($page-1)*$max_results)+$i+1;
  // start - build table rows  
echo (
  //---------------------------------------------------
'<td>'
.$sn
.'</td><td>'
  //---------------------------------------------------
.$row["name"]);
if ($row["usage"] != '')
{echo ('<br />Usage - '.$row["usage"]);}
  //---------------------------------------------------
echo ('</td><td>'
.$row["category"]);
  //---------------------------------------------------
echo ('</td><td>'
.$row["condition"]);
  //---------------------------------------------------
echo ('</td><td>'
.$row["location"]);
  //---------------------------------------------------
echo ('</td><td><span style="color:#736F6E;">');
if ($row["added_on"] !='' & $row["added_on"] !='0000-00-00')
{
echo('Added '.$row["added_on"].'<br />');
}
if ($row["modified_on"] !='' & $row["modified_on"] !='0000-00-00')
{
echo ('Updated '.$row["modified_on"].'<br />');
}
   // start - edit, detail, update links
    // detail link
echo ('<a href="../interface_creator/index_short.php?table_name=others&amp;function=details&amp;where_field=id&amp;where_value='.$row["id"].'" onclick="return popitup(\'../interface_creator/index_short.php?table_name=others&amp;function=details&amp;where_field=id&amp;where_value='.$row["id"].'\')">Details</a>'
);
    // edit and delete link
if (!($all_affect_items == "no" and $client == "not_allowed"))
{echo (' | <a href="../interface_creator/index_short.php?table_name=others&amp;function=edit&amp;where_field=id&amp;where_value='.$row["id"].'" onclick="return popitup(\'../interface_creator/index_short.php?table_name=others&amp;function=edit&amp;where_field=id&amp;where_value='.$row["id"].'\')"> Edit</a> | <a href="../interface_creator/index_short.php?table_name=others&amp;function=delete&amp;where_field=id&amp;where_value='.$row["id"].'" onclick="return confipop(\'../interface_creator/index_short.php?table_name=others&amp;function=delete&amp;where_field=id&amp;where_value='.$row["id"].'\')"> Delete</a>');}
   // end - edit, detail, update links
  //---------------------------------------------------
echo ('</span></td></tr>');
  // end - build table rows  
} // FOR END ==================================================================
///////////////////////////////////////////////////////////////////////////
include ("../bottom_part.php");
} // IF END ##############################
include ("../footer.php");
?>