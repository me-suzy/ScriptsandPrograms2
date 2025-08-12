<?php

//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------

session_start();

include ("config.php");
include ("settings.inc.php");
include_once ("header.inc.php");

// link back
$goback="<br><a href=\"javascript:history.back()\">$lng[342]</a>";

//
// if session is not registered go to login page

if( !isset( $_SESSION["admin"] ) && !isset( $_SESSION["demo"] ) )
	abcPageExit("<Script language=\"javascript\">window.location=\"login.php\"</script>");

echo "<h2>".$lng[872]."</h2>";

extract( $_GET );
extract( $_POST );

// if submit has not been clicked
if( !$submit )
{

if ( !isset ( $country_id ) )
	abcPageExit("<br><h4>".$lng[305]."</h4>$goback</p>");


$sql_cntr = "select * from ".$prefix."store_countries where country_id='$country_id'";
$result_cntr = mysql_query($sql_cntr);
$country = mysql_fetch_assoc ($result_cntr);
	

echo "
<form action=\"edit_country.php\" method=\"post\">

<table border=\"1\" bordercolor=\"#e6e6e6\" cellspacing=\"0\" cellpadding=\"4\" width=\"95%\">
<tr>
<td valign=\"top\"><b>".$lng[866].":</b></td>
<td valign=\"top\"><input class=\"textbox\" type=\"textbox\" name=\"country\" value=\"$country[country]\"></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[868].":</b></td>
<td valign=\"top\">\n";

	$countries = abcFetchCountryList();
			
	// Drop down menu to build full category!
	print "\t\t\t\t\t<select name=\"parent_id\">
						<option value=\"0\"></option>\n";

	foreach( $countries as $cntr )
	{							
		if ( $cntr['country_id'] != $_GET['country_id'] ) {
			echo"\t\t\t\t\t\t<option value=\"".$cntr['country_id']."\"";
			if ( $cntr['parent_id'] = $country['parent_id'] )
				echo "selected";
			echo ">".$cntr['country']."</option>\n";
		}
	}
    echo"\t\t\t\t\t</select>
</td>
</tr>

<tr>
<td valign=\"top\">&nbsp;</td>
<td valign=\"top\">
<input type=\"hidden\" name=\"country_id\" value=\"$_GET[country_id]\">
<INPUT class=\"submit\" TYPE=\"submit\" NAME=\"submit\" VALUE=\"".$lng[872]."\"></td>
</tr>
</table>

</form>";

// Delivery prices
	
?>

<b><?=$lng[934];?></b>
<table border="1" bordercolor="#e6e6e6" cellpadding="4" cellspacing="0" width="95%">
<form action="" method="post">
<tr bgcolor="#e6e6e6">
	<td align="center"><b><?=$lng[935];?>, <?=$currency?></td>
	<td align="center"><b><?=$lng[936];?>, <?=$currency?></td>
	<td width="5%" align="center"><b><?=$lng[471];?></td>
	<td width="5%" align="center"><b><?=$lng[470];?></td>
</tr>
<?

	if($_SESSION['demo']&&(is_array($to_delete)||$action=='add_update_delivery'))
		include('_guest_access.php');
	
	if(is_array($to_delete)&&!$_SESSION['demo'])
		mysql_query('delete from '.$prefix.'store_countries_deliveries where id in('.join(',', $to_delete).')');
	if($delivery_id!='')
	{
		$res=mysql_query("select * from ".$prefix."store_countries_deliveries where id='".$delivery_id."'");
		$disc_item=mysql_fetch_assoc($res);
	}
	if($action=='add_update_delivery'&&!$_SESSION['demo']&&$item['sum']!=""&$item['price']!="")
	{
		if($item['id']=='')
			mysql_query('insert into '.$prefix.'store_countries_deliveries (country_id, sum, price) VALUES ("'.$country_id.'", " '.$item['sum'].'", "'.$item['price'].'")');
		else {
			mysql_query('update '.$prefix.'store_countries_deliveries set sum="'.$item['sum'].'", price="'.$item['price'].'" where id="'.$item['id'].'"');
			$disc_item = array ();
			$delivery_id = "";
		}
	}

	$res=mysql_query("select * from ".$prefix."store_countries_deliveries where country_id='".$country_id."' order by sum asc");
	$_have_items=0;
	while($item=mysql_fetch_assoc($res))
	{
		$_have_items=1;
		echo '<tr>';
		echo '<td>'.$item['sum'].'</td>';
		echo '<td>'.$item['price'].'</td>';
		echo '<td align="center">'.($delivery_id!=$item['id']?'<a href="?country_id='.$country_id.'&delivery_id='.$item['id'].'">'.$lng[471].'</a>':$lng[471]).'</td>';
		echo '<td align="center"><input type="Checkbox" name="to_delete[]" value="'.$item['id'].'"></td></tr>';
	}
	if($_have_items)
	{
?>
<tr>
	<td colspan="4" align="center"><input type="Submit" name="delivery_submit" value="<?=$lng[474];?>"></td>
</tr>
<?}?>
<input type="Hidden" name="country_id" value="<?echo $country_id;?>">
</form>
</table>
<br>

<table border="1" bordercolor="#e6e6e6" cellpadding="4" cellspacing="0" width="95%">
<form action="" method="post">
<tr>
	<td colspan="4" bgcolor="#e6e6e6"><b><?=$lng[475];?>:</b></td>
</tr>
<tr>
	<td><?=$lng[935];?>, <?=$currency?></td>
	<td><input type="Text" name="item[sum]" value="<?echo $disc_item['sum'];?>"></td>
	<td><?=$lng[936];?>, <?=$currency?></td>
	<td><input type="Text" name="item[price]" value="<?echo $disc_item['price'];?>"></td>
</tr>
	<input type="Hidden" name="item[id]" value="<?echo $disc_item['id'];?>">
	<input type="Hidden" name="action" value="add_update_delivery">
	<input type="Hidden" name="country_id" value="<?echo $country_id;?>">
<tr>
	<td colspan="4" align="center"><input type="Submit" name="delivery_submit" value="<?echo $delivery_id==''?$lng[476]:$lng[477];?>"></td>
</tr>
</form>
</table>
<br><br>

<?php

//

} // if !$submit

// if submit has been clicked
if( $submit && !$_SESSION['demo'] )
{
		 
	// if category was not chosen
	if( empty($country) )
		abcPageExit("<h4>".$lng[870]."</h4>$goback");
	
	$sql_cntr = "select country_id from ".$prefix."store_countries where country='$country' and country_id!='$country_id'";
	$result_cntr = mysql_query($sql_cntr);
	if ( $num_cntr = mysql_fetch_assoc ($result_cntr) )
		abcPageExit("<h4>".$lng[871]."</h4>$goback");
	
	// if upload image was clicked
	
	$_country = mysql_escape_string($country);
	
	$sql_insert = 	"update ".$prefix."store_countries set
			country = '$_country',
			parent_id = '$parent_id'
			where country_id='$country_id'
			";
	
	$result = mysql_query($sql_insert)
		or abcPageExit("Invalid query: " . mysql_error() . $goback);
			
echo "
<h3>".$lng[793].": $country ".$lng[873]."!</h3>
<p><a href=\"countries.php\">".$lng[864]."</a></p>";
	
}

if( $submit && $_SESSION['demo'] ) {
	
	include('_guest_access.php');
	
}

include ("footer.inc.php");

?>
