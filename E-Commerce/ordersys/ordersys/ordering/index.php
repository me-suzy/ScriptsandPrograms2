<?php 
session_start();
header("Cache-control: private");
include ("header.php");
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
$querytotal = "SELECT COUNT(*) FROM `item`"; 
$resulttotal1 = mysql_fetch_row(mysql_query($querytotal));
$resulttotal = $resulttotal1[0];
$date = date("l, F j, Y");
?>
<span style="color:#dcdcdc;"><?php echo($log_status.'<a>'.$date);?></a> || <a href="help/help.htm#what" onclick="return popitup('help/help.htm#what')">What is this</a> || <a href="help/help.htm#how" onclick="return popitup('help/help.htm#how')">How do I use it</a> || <a href="help/help.htm" onclick="return popitup('help/help.htm#who')">Get software</a> || Separate ordering for separate vendors.</span></p>
<p><?php echo($resulttotal);?>&nbsp;items
<?php
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
 $querytotal = "SELECT COUNT(*) FROM `order` WHERE DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= `ordered_date`"; 
 $resulttotal1 = mysql_fetch_row(mysql_query($querytotal));
 $num_orders = $resulttotal1[0];
 $query = "SELECT SUM(`total_amount`) - SUM(`cost_reduce`) + SUM(`cost_add`)  FROM `order` WHERE DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= `ordered_date`";
 $sql = mysql_query($query);
 $result = mysql_fetch_row($sql);
 if (isset($result[0]) and $result[0]>0){
 echo ('- '.$currency.$result[0].' spent in the last 30 days on '.$num_orders.' order(s)... <a href="orders.php">more</a>');}
}
// end show expenditure
?>
</p>

<table summary="none" border="0" cellpadding="10"><tr valign="middle"><td valign="middle">

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

</td><td valign="middle">
<img src="images/boxes.jpg" style="border:0;" alt="boxes" />
</td></tr></table>
<?php
include ('footer.php');
?>