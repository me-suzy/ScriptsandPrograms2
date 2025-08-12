<?php

//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------

session_start();

include ("config.php");
include ("settings.inc.php");
include_once ("header.inc.php");
echo "<h2>".$lng[352]."</h2>";

//
// if session is not registered go to login page

if( !isset( $_SESSION["admin"] ) && !isset( $_SESSION["demo"] ) )
	abcPageExit("<Script language=\"javascript\">window.location=\"login.php\"</script>");

extract( $_GET );
extract( $_POST );

$sql_count = "select * from ".$prefix."store_customer";
$result_count = mysql_query ($sql_count);
$total = mysql_num_rows($result_count);

// see if customers are in database
if( $total == 0 )
	abcPageExit( "<p>".$lng[353]."</p>" );

if( empty( $ddlimit ) )
	$ddlimit = 25;

// so continue
echo "<p><b>".$lng[354].": $total</b></p>";

// form data to search for customers and number to view per page
echo "
<form action=\"customers.php\" method=\"post\" name=\"filter\">
<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\" width=\"99%\">
<tr>
<input type=\"hidden\" value=\"$searchStr\" name=\"searchStr\">
<td>".$lng[355]." 
<select name=\"ddlimit\" onchange=\"document.filter.submit();\">
<option value=\"5\" ";if($ddlimit==5){echo "selected";}echo">5</option>
<option value=\"10\" ";if($ddlimit==10){echo "selected";}echo">10</option>
<option value=\"25\" ";if($ddlimit==25){echo "selected";}echo">25</option>
<option value=\"50\" ";if($ddlimit==50){echo "selected";}echo">50</option>
<option value=\"100\" ";if($ddlimit==100){echo "selected";}echo">100</option>
</select>
 ".$lng[356]." 
<input class=\"submit\" type=\"submit\" value=\"".$lng[357]."\"></form>
</td>
<td align=\"right\" valign=\"top\">
<form action=\"customers.php?search=yes&ddlimit=$ddlimit\" method=\"post\">
".$lng[358].": <input class=\"textbox\" type=\"text\"  name=\"searchStr\">&nbsp;<input class=\"submit\" type=\"submit\" value=\"".$lng[359]."\">
</td></tr>
</form>
</table>";

// set limit value for number of records to be shown per page from drop down menu
// query database to find total number of records to display

$limit = $ddlimit;

// get info
$query_count = "SELECT * FROM ".$prefix."store_customer
				where (email like '%$searchStr%' or name like '%$searchStr%' or
					   add_1 like '%$searchStr%' or add_2 like '%$searchStr%' or
					   town like '%$searchStr%' or county like '%$searchStr%' or
					   country like '%$searchStr%' or phone like '%$searchStr%' or
					   customer_id like '%$searchStr%')";
$result_count = mysql_query( $query_count );
$totalrows = mysql_num_rows( $result_count );

if( empty($page) )
	$page = 1;
$limitvalue = $page * $limit - ($limit); 

// if no search was made
if( $search !== "yes" )
	$query = "SELECT *
			  FROM ".$prefix."store_customer
			  order by name LIMIT $limitvalue, $limit ";
else
	$query = "SELECT * FROM ".$prefix."store_customer
				where (email like '%$searchStr%' or name like '%$searchStr%' or
					   add_1 like '%$searchStr%' or add_2 like '%$searchStr%' or
					   town like '%$searchStr%' or county like '%$searchStr%' or
					   country like '%$searchStr%' or phone like '%$searchStr%' or
					   customer_id like '%$searchStr%')
				order by name LIMIT $limitvalue, $limit ";

$result = mysql_query($query) or die("Error: " . mysql_error()); 
$count_result = mysql_num_rows( $result );

// check results
if( $count_result == 0 )
{ 
	if( empty($search) )
		abcPageExit( "<p><br>".$lng[360]."</p><br>" );
	else
		abcPageExit( "<p><br>".$lng[361]." \"<b>$searchStr</b>\"!<br><br><a href=\"customers.php?ddlimit=$ddlimit\">".$lng[362]."</a></p>" );
}

// search info with reset
if( $count_result > 0 && $search )
		echo "<p><br>".$lng[363]." \"<b>$searchStr</b>\"<br><br>
			  <a href=\"customers.php?ddlimit=$ddlimit\">".$lng[362]."</a></p>";

abcPrintNavigationBar( $totalrows, $count_result, $limit, $page, $searchStr );

// build table of data
echo "
<table border=\"1\" bordercolor=\"#e0e0e0\" cellpadding=\"4\" cellspacing=\"0\" width=\"99%\">
<form action=\"\" method=\"post\">
<tr bgcolor=\"#e6e6e6\">
<td align=\"center\" height=\"25\"><b>".$lng[364]."</b></td>
<td align=\"center\"><b>".$lng[365]."</b></td>
<td align=\"center\"><b>".$lng[366]."</b></td>
<td align=\"center\"><b>".$lng[367]."</b></td>
<td align=\"center\"><b>".$lng[368]."</b></td>
<td align=\"center\"><b>".$lng[369]."</b></td>
<td align=\"center\"><b>".$lng[370]."</b></td>
<td align=\"center\"><b>".$lng[371]."</b></td>
<td align=\"center\"><b>".$lng[372]."</b></td>
</tr>";

if($action=='delete_dis'&&is_array($to_delete)&&count($to_delete)>0&&!$_SESSION['demo'])
{
	mysql_query("delete from ".$prefix."store_customer_discounts where id in(".join(',', $to_delete).")");
	mysql_query("delete from ".$prefix."store_customer_discount_categories where discount_id in(".join(',', $to_delete).")");
}

if($edit != '')
	$dis_item=mysql_fetch_assoc(mysql_query("select * from ".$prefix."store_customer_discounts where id='".$edit."'"));

if($action == 'add_update_dis'&&!$_SESSION['demo'])
{
	if($item['id']!='')
		mysql_query("update ".$prefix."store_customer_discounts set name='".$item['name']."', discount='".$item['discount']."' where id='".$item['id']."'") or die(mysql_error());
	else
		mysql_query("insert into ".$prefix."store_customer_discounts set name='".$item['name']."', discount='".$item['discount']."', id='".$item['id']."'") or die(mysql_error());
}

if($action == 'set_level'&&is_array($user_category)&&count($user_category)>0&&!$_SESSION['demo'])
{
	mysql_query("delete from ".$prefix."store_customer_discount_categories where customer_id in (".join(',', array_keys($user_category)).")") or die(mysql_error());
	foreach($user_category as $key=>$value)
		if($value['discount_id']!=0)
			mysql_query("insert into ".$prefix."store_customer_discount_categories set customer_id='".$key."', discount_id='".$value."'") or die(mysql_error());
}		

$user_categories=array();
$user_categories_res=mysql_query("select * from ".$prefix."store_customer_discounts order by discount asc");
while($row=mysql_fetch_assoc($user_categories_res))
	$user_categories[$row[id]]=$row;

while( $row = mysql_fetch_array($result) )
{ 
	$email = $row["email"]; 
	$name = $row["name"];
	$add_1 = $row["add_1"]; 
	$add_2 = $row["add_2"];
	$town = $row["town"];
	$county = $row["county"];
	$postcode = $row["postcode"];
	$country = $row["country"];
	$phone = $row["phone"];
	$customer_id = $row["customer_id"];
	$day = substr($row["date"],6,2);
	$month = substr($row["date"],4,2);
	$year = substr($row["date"],0,4);
	$time = $row["time"];
	$ip = $row["ip"];
	
	$parent_id = GetNameById ( 'parent_id', 'country_id', 'store_countries', $country );
	$country = GetNameById ( 'country', 'country_id', 'store_countries', $country );
	$parent = GetNameById ( 'country', 'country_id', 'store_countries', $parent_id );
	if ( !empty ( $parent ) )
		$country = $country . ", " . $parent;
	
	$user_category=mysql_fetch_assoc(mysql_query("select * from ".$prefix."store_customer_discount_categories where customer_id='".$customer_id."'"));
	
	switch( $date_style )
	{
	case "0":	// US date format
		$date="$month/$day/$year";
		break;
	
	case "1":	// EU date format
		$date = "$day/$month/$year";
		break;
	}
	
	if( $bgcolour == $colour_3 )
		$bgcolour = $colour_4;
	elseif( $bgcolour == $colour_4 )
		$bgcolour =$colour_3;
				
	echo "<tr>
		<td align=\"center\">$customer_id</td>
		<td align=\"center\">$name</td>
		<td align=\"center\"><a href=\"mailto:$email\">$email</a></td>
		<td align=\"center\">$add_1<br>";
		
	if( !empty($add_2) ) 
		echo"$add_2<br>";
	
	echo "$town<br>$county<br>$postcode<br>$country</td>
			<td align=\"center\">$phone</td>
			<td align=\"center\">$date</td>
			<td align=\"center\">$time</td>
			<td align=\"center\">$ip</td>
			<td align=\"center\"><select name=\"user_category[$customer_id]\"><option value=\"0\">Basic";
			foreach($user_categories as $key=>$field)
			{
				echo '<option value="'.$key.'"';
				if($key==$user_category['discount_id'])
					echo " selected";
				echo '>'.$field['name'];
			}
	
	echo "</select></td>
		</tr>";
} 

echo '<tr><td colspan="9" align="center" align="center">
<input type="Hidden" name="action" value="set_level">
<input type="Submit" value="'.$lng[373].'"></td></tr></form></table><br>';

if($_SESSION['demo']&&($action=='add_update_dis'||$action=='delete_dis'||$action=='set_level'))
	include('_guest_access.php');

?>

<b><?=$lng[374];?>:</b>
<table border="1" bordercolor="#e6e6e6" cellpadding="4" cellspacing="0" width="99%">
<form action="?" method="post">	
<tr bgcolor="#e6e6e6">
	<td align="center"><b><?=$lng[377];?></td>
	<td align="center"><b><?=$lng[378];?></td>
	<td width="5%" align="center"><b><?=$lng[376];?></td>
	<td width="5%" align="center"><b><?=$lng[375];?></td>
</tr>
<? foreach($user_categories as $key=>$value) 
{
?>
<tr bgcolor="#ffffff">
	<td><?echo $value['name'];?></td>
	<td><?echo ($value['discount']);?></td>
	<td align="center"><a href="?edit=<?echo $key;?>"><?=$lng[376];?></a></td>
	<td align="center"><input type="Checkbox" name="to_delete[]" value="<?echo $key;?>"></td>
</tr>
<?
}
?>
<input type="Hidden" name="action" value="delete_dis">
<tr bgcolor="#ffffff">
	<td colspan="4" align="center"><input type="Submit" value="<?=$lng[379];?>"></td>
</tr>
</form>
</table>
<br>

<table border="1" bordercolor="#e6e6e6" cellpadding="4" cellspacing="0" width="99%">
<form action="?" method="post">
	<tr bgcolor="#e6e6e6">
		<td colspan="4"><b><?if($edit==''){?><?=$lng[380];?>:<?}else{?><?=$lng[376];?>:<?}?></b></td>
	</tr>
	<tr bgcolor="#ffffff">
		<td><?=$lng[377];?></td>
		<td><input type="Text" name="item[name]" value="<?=$dis_item['name'];?>"></td>
		<td><?=$lng[378];?></td>
		<td><input type="Text" name="item[discount]" value="<?=$dis_item['discount'];?>"></td>
	</tr>
<input type="Hidden" name="action" value="add_update_dis">
<input type="Hidden" name="item[id]" value="<?=$dis_item['id'];?>">
<tr bgcolor="#ffffff">
	<td colspan="4" align="center"><input type="Submit" value="<?echo $edit==''?$lng[381]:$lng[382];?>"</td>
</tr>
</form>
</table>
</td>
	</tr>
</table>
<?

abcPrintNavigationBar( $totalrows, $count_result, $limit, $page, $searchStr );

include_once ("footer.inc.php");

?>