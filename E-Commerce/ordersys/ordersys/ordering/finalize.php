<?php
session_start();
header("Cache-control: private");
if (!isset($_POST['by'])) {$_POST['by'] = '';}
if (!isset($_POST['grant'])) {$_POST['grant'] = '';}
if (!isset($_POST['rushdate'])) {$_POST['rushdate'] = '';}
if (!isset($_POST['comment'])) {$_POST['comment'] = '';}
if (!isset($_POST['vendor'])) {$_POST['vendor'] = '';}
if (!isset($_POST['phone'])) {$_POST['phone'] = '';}
if (!isset($_POST['fax'])) {$_POST['fax'] = '';}
if (!isset($_POST['address'])) {$_POST['address'] = '';}
//////////////// print not pressed /////////////////////
if (!(isset($_POST['print']))){
////////////////////////////////////////////////////////
// header part
include ("header.php");
// check_login; will go to login if authentication enabled in config.php
include ('interface_creator/check_login.php');
$date = date("l, F j, Y");
echo ('<span style="color:#dcdcdc;">'.$log_status);
if (!($all_affect_items == "no") or ($all_affect_items == "no" and $client == "allowed")){
echo ('
<a>Add an </a><a href="'.$site_url.'index_short.php?function=show_insert_form&amp;table_name=item" onclick="return popitup(\''.$site_url.'index_short.php?function=show_insert_form&amp;table_name=item\')">item</a> || <a href="'.$site_url.'index_short.php?function=show_insert_form&amp;table_name=vendor" onclick="return popitup(\''.$site_url.'index_short.php?function=show_insert_form&amp;table_name=vendor\')">Add a vendor</a> || <a href="orders.php">View/adjust past orders</a> || ');}
echo ('<a href="help/help.htm" onclick="return popitup(\'help/help.htm\')">Help</a> || <a>'.$date.'</a></span></p></div>
<div style="padding-left: 5px;">');
//get checked values
if (isset($_POST['checked'])){foreach ($_POST['checked'] as $key => $value){$POSTchecked[] = $key;}}
//if no checked value, still set postchecked
else {$POSTchecked = array();}
//set session checked if not set
if (!isset($_SESSION['checked'])) {$_SESSION['checked'] = array();}
//add checked values to session checked
$_SESSION['checked'] = array_unique(array_merge($_SESSION['checked'], $POSTchecked));
//get unchecked values
if (isset($_POST['unchecked'])){foreach ($_POST['unchecked'] as $key => $value){$POSTunchecked[] = $key;}}
//if no unchecked value, still set postunchecked
else {$POSTunchecked = array();}
//set session unchecked if not set
if (!isset($_SESSION['unchecked'])) {$_SESSION['unchecked'] = array();}
//subtract postunchecked from session checked
$_SESSION['checked'] = array_diff($_SESSION['checked'], $POSTunchecked);
// reset to 0 if 'empty cart'
if (isset($_POST['empty']) and $_POST['empty'] == 'Clear all'){$_SESSION['checked'] = array();}
// make table
echo ('<table width="750" summary="top" cellpadding="5" cellspacing="1" style="background-color:#efefef; border:0;"><colgroup>
<col valign="top" align="left" style="width:120px;"></col>
<col valign="top" align="left"></col>
<col valign="top" align="left" style="width:120px;"></col>
</colgroup>');
echo ('
<tr style="background-color:#ffffff;">
<td valign="top" style="background-color:#ffffff;" colspan="3">
<form method="post" action="finalize.php#finalize">');
//total in order
if (isset($_SESSION['checked']))
{$no_items = count($_SESSION['checked']);}
else 
{$no_items = 0;}
if ($no_items<1)
{echo ('Please browse/search to add an item to order.</td></tr>'); $print_button = "no";}
else
{ // some items to order
// get vendor name and info; assuming all items from same vendor
$query = "SELECT `Vendor` FROM `item` WHERE `ID`=".$_SESSION['checked'][0]." LIMIT 1";
$sql = mysql_query($query); 
$row = @mysql_fetch_array($sql);
$vendor = $row['Vendor'];
$query2 = "SELECT `Vendor_ID`,`Name`,`Fax`,`Phone`,`Address` FROM `vendor` WHERE `Name` ='".$vendor."' LIMIT 1";
$sql2 = mysql_query($query2); 
$row2 = @mysql_fetch_array($sql2);
if ($row2['Name'] != ''){
 echo ("<b>You have " . $no_items . " item(s) for ordering</b> from ".$row2['Name']);
 $print_button = "yes";
 if (!($all_affect_items == "no" and $client == "not_allowed"))
  {echo (' (<a href="interface_creator/index_short.php?table_name=vendor&amp;function=details&amp;where_field=Vendor_ID&amp;where_value='.$row2["Vendor_ID"].'" onclick="return popitup(\'interface_creator/index_short.php?table_name=vendor&amp;function=details&amp;where_field=Vendor_ID&amp;where_value='.$row2["Vendor_ID"].'\')">Details</a> | <a href="interface_creator/index_short.php?table_name=vendor&amp;function=edit&amp;where_field=Vendor_ID&amp;where_value='.$row2["Vendor_ID"].'" onclick="return popitup(\'interface_creator/index_short.php?table_name=vendor&amp;function=edit&amp;where_field=Vendor_ID&amp;where_value='.$row2["Vendor_ID"].'\')">Edit</a>)');}}
else {echo ('<b>Details for the vendor could not be found!</b> Please edit the first item below and re-select the vendor in the pull-down menu');  $print_button = "no";}  
echo (".</td></tr>");
echo ('<tr style="background-color:#ffffcc;">
<td style="background-color:#ffffcc;" valign="top">Option</td>
<td style="background-color:#ffffcc;" valign="top">Item</td>
<td style="background-color:#ffffcc;" valign="top">Edit</td>
</tr>');
//show items in order
foreach ($_SESSION['checked'] as $key => $value)
{
   $query = "SELECT `ID`,`Vendor`,`Vendor_cat_no`,`Name`,`Price`,`Size` FROM `item` WHERE `ID`=".$value;
   $sql = mysql_query($query); 
   $row = @mysql_fetch_array($sql);
   echo ('<tr style="background-color:#ffffff;" valign="top"><td style="background-color:#ffffff;" valign="top">');
   $vendor = $row['Vendor'];
   //---first cell - item number and remove option
   $x = $row["ID"];
   echo("<input type=\"text\" name=\"".$x."\" id=\"".$x."\" maxlength=\"3\" size=\"2\" value=\"");
  
   if(isset($_POST[$x])) 
     { echo ($_POST[$x]);} 
     else 
     {echo ("1");}
   echo ("\" />");
   if (in_array($row["ID"], $_SESSION['checked']))
     {echo ('<input type="checkbox" name="unchecked['.$row["ID"].']" id="unchecked['.$row["ID"].']" value="'.$row["ID"].'" />Remove');}
     else {echo ('<input type="checkbox" name="checked['.$row["ID"].']" id="checked['.$row["ID"].']" value="'.$row["ID"].'" />Add');}

   echo ("</td>");
  // second cell - item details
   echo ("<td valign=\"top\" style=\"background-color:#ffffff;\">" . $row["Name"] . " - from " .$vendor . "<br />". $row["Size"] . "&nbsp;&nbsp;&nbsp;" .$currency . $row["Price"] . "&nbsp;&nbsp;&nbsp;Catalog no. " .    $row["Vendor_cat_no"] . "</td>");
  // third cell - edit options
  echo ("<td valign=\"top\" style=\"background-color:#ffffff;\">");
  if (!($all_affect_items == "no" and $client == "not_allowed"))
  {echo ('<a href="interface_creator/index_short.php?table_name=item&amp;function=edit&amp;where_field=id&amp;where_value='.$row["ID"].'" onclick="return popitup(\'interface_creator/index_short.php?table_name=item&amp;function=edit&amp;where_field=id&amp;where_value='.$row["ID"].'\')">Edit</a>');}
  echo ("</td></tr>");
} // end showing items
echo('
<tr style="background-color:#ffffff;">
<td style="background-color:#ffffff;" valign="top"><input type="submit"  name="change" value="Change" /></td>
<td style="background-color:#ffccff;" valign="top" colspan="2">');
echo('<table summary="none" style="border:0;" cellspacing="2">
<tr>
<td>
Your name
</td>
<td>
<input type="text" name="by" id="by" size="25" value="'.$_POST['by'].'" />
</td>
</tr>
<tr>
<td>
Rush by date (optional)
</td>
<td>
<input type="text" name="rushdate" id="rushdate" size="25" value="'.$_POST['rushdate'].'" />&nbsp;Today - ' . date("l, n-j-y"). '
</td>
</tr>
<tr>
<td>
Grant no. (optional)
</td>
<td>
<input type="text" name="grant" id="grant" size="25" value="'.$_POST['grant'].'" />
</td>
</tr>
<tr>
<td>
Comment if any
</td>
<td> 
<textarea cols="25" rows="4" name="comment" id="comment">'.$_POST['comment'].'</textarea>
</td>
</tr>
</table>
<input type="hidden" name="vendor" id="vendor" value="'.$row2['Name'].'" />
<input type="hidden" name="fax" id="fax" value="'.$row2['Fax'].'" />
<input type="hidden" name="phone" id="phone" value="'.$row2['Phone'].'" />
<input type="hidden" name="address" id="address" value="'.$row2['Address'].'" />
<input type="submit" name="print" id="print" value="Print order"');
if ( $print_button == "no"){echo (' disabled = "disabled"');}
echo ('></form>
<br /><br /><a name="finalize"></a><span style="color:grey;">Enter name, etc., above. Then \'Print order.\' Before doing so you may change quantities of items. Remember to enable background printing for your browser - <a href="help/help.htm#req" onclick="return popitup(\'help/help.htm#req\')">?</a></span>
</td>
</tr>');
} // end - some items to order
echo ('</table>');
// show browse search options for continued shopping
// get options for vendor and category menu
$queryvendor = "SELECT DISTINCT `Name` FROM `vendor` ORDER BY `Name`"; 
$resultvendor = mysql_query($queryvendor);
$Vendor_options=""; 
while ($row=mysql_fetch_array($resultvendor)) {  
$Vendor_name=$row["Name"]; 
$Vendor_options.="<option value=\"".$Vendor_name."\">".$Vendor_name.'</option>'; }
$querycategory = "SELECT DISTINCT `Category` FROM `item` ORDER BY `Category`"; 
$resultcategory = mysql_query($querycategory);
$Category_options=""; 
while ($row=mysql_fetch_array($resultcategory)) { 
$Category_options.="<option value=\"".$row['Category']."\">".$row['Category'].'</option>';}
?>
<form action="items.php" method="get"><p>
<select name="sterm_1" id="sterm_1"> 
<?php
if (isset($Vendor_options)){echo $Vendor_options;} 
?> 
</select>
<input type="submit" value="Browse by vendor" />
<input type="hidden" name="smenu_1" id="smenu_1" value="Vendor" />
</p></form>

<form action="items.php" method="get"><p>
<select name="sterm_1" id="sterm_1">
<?php
if(isset($Category_options)){echo $Category_options;}
?> 
</select>
<input type="submit" value="Browse by category" />
<input type="hidden" name="smenu_1" id="smenu_1" value="Category" />
</p></form>

<form action="items.php" method="get"><p>
<input type="text" name="sterm_1" id="sterm_1" maxlength="20" value="" />
<input type="submit" value="Search by item name" />
<input type="hidden" name="smenu_1" id="smenu_1" value="Name" />
</p></form>
<?php
include ('footer.php');
//////////////////////////////////////////////////////////
}
/////////////////////end print not pressed////////////////
else
{
//////////////////// if print
include ('print.php');
}
//////////////////// end if print
?>