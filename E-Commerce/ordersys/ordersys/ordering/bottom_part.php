<?php
// IF START---------------- $total_pages >1---new row after the data rows
if($num_sat >0 and $total_pages >1)
{
 echo ('<tr valign="top" style="background-color:#ffffcc;"><td colspan="5" style="align:middle;"><span style="color:grey;">Page ' . $page. ' of ' . $total_pages . '<br />'); 
 // for 'previous'
 if($page > 1)
 {$prev = ($page - 1); echo ("<a href=\"" . $_SERVER['PHP_SELF'] . "?where_condition=".rawurlencode($where_condition)."&amp;order_condition=".rawurlencode($order_condition)."&amp;page=".$prev."\">&laquo;Previous</a>&nbsp;");
 } 
 // for rest of pages
 for($i = 1; $i <= $total_pages; $i++)
 {
  if($i == $page)
  {
  echo ($i."&nbsp;");
  }
  else
  {
  if(abs($i-$page) < 10)
     {
     echo ("<a href=\"" . $_SERVER['PHP_SELF'] . "?where_condition=".rawurlencode($where_condition)."&amp;order_condition=".rawurlencode($order_condition)."&amp;page=".$i."\">".$i."</a>&nbsp;");
     }
  }
 }
 // for 'next'
 if($page < $total_pages)
 {
 $next = ($page + 1);
 echo ("<a href=\"" . $_SERVER['PHP_SELF'] . "?where_condition=".rawurlencode($where_condition)."&amp;order_condition=".rawurlencode($order_condition)."&amp;page=".$next."\">Next&raquo;</a></span>");
 }
 echo ('</td></tr>');
}
// IF END ---------------- $total_pages >1 and $ num_sat >0
echo ('</table>');
///////////////////////////////////////////////////////////// TABLE for the cart
if (isset ($since_order))
{echo $since_order;}
echo ('<table width="750" summary="none" style="background-color:#efefef; border:0;" cellpadding="5" cellspacing="1"><colgroup>
<col valign="top" align="left" style="width:75px; background-color:#ffccff;"></col>
<col valign="top" align="left"></col>
</colgroup>');
$no_items = count($_SESSION['checked']);
if ($no_items>0)
{
// end MAIN form - cart not empty
 echo ('<tr><td valign="top" style="width:75px; background-color:#ffccff;"><input type="submit" value="Update" /></td><td style="background-color:#f8d777;" valign="top"><a name="order"></a>'.$no_items . ' item(s) in your order:<br />');
 $vendor_array = array();
 foreach ($_SESSION['checked'] as $key => $value)
 {
  $sql3 = mysql_query("SELECT `ID`, `Name`, `Vendor` FROM `item` WHERE `ID`=".$value);
  $row3 = mysql_fetch_array($sql3);
  echo('<input type = "radio" name="unchecked['.$row3["ID"].']" id="unchecked['.$row3["ID"].']" value="'.$row3["ID"].'" />'.$row3["Name"] . '<span style="color:grey;"> from '.$row3["Vendor"].'</span><br />');
  $vendor_array[] = $row3["Vendor"];
  }
 echo ('<br /><input type="submit" name="empty" id="empty" value="Clear all" /></form><span style="color:grey;"> Select and click \'Update\' to remove or \'Clear all\'</span><form method="post" action="finalize.php"><input type="submit" value="Finalize" ');
 $vendor_array = array_unique($vendor_array);
 $vendor_array_no = count($vendor_array);
 if ($vendor_array_no>1)
 {echo ('disabled="disabled" /><span style="color:grey;"> The items are from different vendors! You should do separate ordering. Select and click \'Update\' to remove or \'Clear all\'</span></form>');}
 else
 {echo (' /></form>');}
}
else
{
// end MAIN form - empty cart
echo ('<tr><td valign="top" style="width:75px; background-color:#ffccff;"><input type="submit" value="Update" /></form></td><td valign="top" style="background-color:#f8d777;"><a name="order"></a><span style="color: grey;">Browse by searching, etc., to add/remove items, clicking the \'Update\' button on left each time.</span>');
}
echo ('</td></tr></table>');
/////////////// end TABLE for the cart

/////////////// build LOWER table - export + browse options

echo ('<table width="750" summary="export" style="background-color:#efefef; border:0;" cellpadding="5" cellspacing="1"><tr><td>');
if ($num_tot != 0)
{
 // Excel export options - ordering maintained
 echo ('<form action="export.php" method="post"><p>
 <select single="single" name="parameter" id="parameter">
 <option value="Excel llll  ORDER BY '.$order_condition.'">Export all '.$num_tot.' entries in Excel format, or...</option>
 <option value="CSV llll  ORDER BY '.$order_condition.'">All '.$num_tot.' entries in CVS format</option>');
   // selective export only if looking at table subset - therefore too the where_condition
 if ($num_sat !== $num_tot and $num_sat !== 0)
  {
  echo ('
  <option value="Excel llll '.$where_condition.' ORDER BY '.$order_condition.'">These '.$num_sat.' entries in Excel format</option>
  <option value="CSV llll '.$where_condition.' ORDER BY '.$order_condition.'">These '.$num_sat.' entries in CVS format</option>');
  }
   // end selective export
 echo ('</select>');
   // hidden values to pass the mysql query and table name
 echo ('
<input type="hidden" name="table" id="table" value="`'.$table.'`" />
<input type="submit" name="export" id="export" value="Export" /><a href="help/help.htm#export" onclick="return popitup(\'help/help.htm#export\')">?</a>
 </p></form>');
  // end export options
 }
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
////////////// end lower table
 echo ('</td></tr></table>');
 ?>