<?php

//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------

session_start();
	
include ("config.php");
include ("settings.inc.php");
include_once ("header.inc.php");

//
// if session is not registered go to login page

if( !isset( $_SESSION["admin"] ) && !isset( $_SESSION["demo"] ) )
	abcPageExit("<Script language=\"javascript\">window.location=\"login.php\"</script>");

echo "<h2>".$lng[454]."</h2>";

if( $_SERVER["REQUEST_METHOD"] == "POST" )
{
	extract( $_POST );

	$userfile = $_FILES["userfile"];
	$userfile_name = $userfile['name'];
	$userfile_type = $userfile['type'];
	$userfile_size = $userfile['size'];
	$userfile_tmp_name = $userfile['tmp_name'];
}

elseif( $_SERVER["REQUEST_METHOD"] == "GET" )
	extract( $_GET );

// if category has not been submit for editing
if( !$submit )
{
	$sql_select = "select * from ".$prefix."store_category where cat_id ='$cat_id'";
	$result = mysql_query($sql_select);

	while( $row = mysql_fetch_array($result) )
	{
		$category = $row["category"];
		$cat_id = $row["cat_id"];
		$cat_image = $row["cat_image"];
		$cat_full_name = $row["cat_full_name"];
		$cat_father_id = $row["cat_father_id"]; 
		$per_ship = $row["per_ship"];
		$item_ship = $row["item_ship"];
		$item_int_ship = $row["item_int_ship"];
		$per_int_ship = $row["per_int_ship"];
		$priority = $row["priority"];
		
	}
	
	$category = str_replace("\"", "&quot;", $category );
	$category = str_replace("\'", "&#39;", $category );

	// build form with current values
	echo "
		<form enctype=\"multipart/form-data\" action=\"edit_category.php\" method=\"post\">

		<input type=\"hidden\" name=\"cat_id\" value=\"$cat_id\">
		
		<table border=\"1\" bordercolor=\"#e6e6e6\" cellpadding=\"4\" cellspacing=\"0\" width=\"95%\">
		<tr>
		<td valign=\"top\"><b>".$lng[455].":</b></td>
		<td valign=\"top\"><input class=\"textbox\" type=\"textbox\"  name=\"category\" value=\"$category\"></td>
		</tr>
		
		<tr>
		<td valign=\"top\"><b>".$lng[456].":</b></td>
		<td valign=\"top\">";
	
	$cats = abcFetchCategoryList();
	
	if ( !is_file ( "../images/category/$cat_image" )  )
		$cat_image_view = "nophoto.gif";
	else	$cat_image_view = $cat_image;
	
	// Drop down menu of full category name!
	echo "<select name=\"cat_father_id\"><option value=\"0\"";if($cat_father_id==0){echo "selected";}echo">".$lng[457]."</option>";

	foreach( $cats as $cat )
	{
		$cat_id_dd = $cat["cat_id"];
		
		// skip itself - cannot be a parent of itself
		if( $cat_id_dd == $cat_id )
			continue;
    
		echo "<option value=\"$cat_id_dd\"";
		if( $cat_father_id == $cat_id_dd )
			echo "selected";	
		echo">";
	
		$category = $cat["path"];
		echo "$category</option>\n";
	}

	echo "</option>";
    echo "</select></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[458]." (000-999):</b></td>
<td valign=\"top\"><INPUT TYPE=\"text\" class=\"textbox\" NAME=\"priority\" VALUE=\"$priority\" MAXLENGTH=\"3\"></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[459].":</b></td>
<td valign=\"top\"><img src='../images/category/$cat_image_view'></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[460]."</b></td>
<td valign=\"top\"><input name=\"image_function\" type=\"radio\" value=\"del\"></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[461]."</b></td>
<td valign=\"top\"><input name=\"image_function\" type=\"radio\" value=\"up\"></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[462]."</b></td>
<td valign=\"top\"><input name=\"image_function\" type=\"radio\" value=\"\" checked></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[463]." (*.jpg,*.gif,*.png):</b></td>
<td valign=\"top\"><INPUT class=\"file\" NAME=\"userfile\" TYPE=\"file\"></td>
</tr>

<tr>
<td valign=\"top\">&nbsp;</td>
<td valign=\"top\">
<input type=\"hidden\" name=\"cat_image\" value=\"$cat_image\">
<INPUT class=\"submit\" TYPE=\"submit\" NAME=\"submit\" VALUE=\"".$lng[468]."\"></td>
</tr>
</table>

</form>";
?>

<b><?=$lng[469];?></b>
<table border="1" bordercolor="#e6e6e6" cellpadding="4" cellspacing="0" width="95%">
<form action="?" method="post">
<tr bgcolor="#e6e6e6">
	<td align="center"><b><?=$lng[472];?></td>
	<td align="center"><b><?=$lng[473];?></td>
	<td width="5%" align="center"><b><?=$lng[471];?></td>
	<td width="5%" align="center"><b><?=$lng[470];?></td>
</tr>
<?

	if($_SESSION['demo']&&(is_array($to_delete)||$action=='add_update_discount'))
		include('_guest_access.php');
	
	if(is_array($to_delete)&&!$_SESSION['demo'])
		mysql_query('delete from '.$prefix.'store_price_discounts where id in('.join(',', $to_delete).')');
	if($discount_id!='')
	{
		$res=mysql_query("select * from ".$prefix."store_price_discounts where id='".$discount_id."'");
		$disc_item=mysql_fetch_assoc($res);
	}
	if($action=='add_update_discount'&&!$_SESSION['demo'])
	{
		if($item['id']=='')
			mysql_query('insert into '.$prefix.'store_price_discounts (category_id, min_amount, discount) VALUES ("'.$cat_id.'", " '.$item['min_amount'].'", "'.$item['discount'].'")');
		else
			mysql_query('update '.$prefix.'store_price_discounts set min_amount="'.$item['min_amount'].'", discount="'.$item['discount'].'" where id="'.$item['id'].'"');
	}

	$res=mysql_query("select * from ".$prefix."store_price_discounts where category_id='".$cat_id."' order by min_amount asc");
	$_have_items=0;
	while($item=mysql_fetch_assoc($res))
	{
		$_have_items=1;
		echo '<tr>';
		echo '<td>'.$item['min_amount'].'</td>';
		echo '<td>'.$item['discount'].'</td>';
		echo '<td align="center">'.($discount_id!=$item['id']?'<a href="?cat_id='.$cat_id.'&discount_id='.$item['id'].'">'.$lng[471].'</a>':$lng[471]).'</td>';
		echo '<td align="center"><input type="Checkbox" name="to_delete[]" value="'.$item['id'].'"></td></tr>';
	}
	if($_have_items)
	{
?>
<tr>
	<td colspan="4" align="center"><input type="Submit" value="<?=$lng[474];?>"></td>
</tr>
<?}?>
<input type="Hidden" name="cat_id" value="<?echo $cat_id;?>">
</form>
</table>
<br>

<table border="1" bordercolor="#e6e6e6" cellpadding="4" cellspacing="0" width="95%">
<form action="?" method="post">
<tr>
	<td colspan="4" bgcolor="#e6e6e6"><b><?=$lng[475];?>:</b></td>
</tr>
<tr>
	<td><?=$lng[472];?></td>
	<td><input type="Text" name="item[min_amount]" value="<?echo $disc_item['min_amount'];?>"></td>
	<td><?=$lng[473];?></td>
	<td><input type="Text" name="item[discount]" value="<?echo $disc_item['discount'];?>"></td>
</tr>
	<input type="Hidden" name="item[id]" value="<?echo $disc_item['id'];?>">
	<input type="Hidden" name="action" value="add_update_discount">
	<input type="Hidden" name="cat_id" value="<?echo $cat_id;?>">
<tr>
	<td colspan="4" align="center"><input type="Submit" value="<?echo $discount_id==''?$lng[476]:$lng[477];?>"></td>
</tr>
</form>
</table>
<br><br>

<?
	abcPageExit();
} // end of if( !$submit )

if ( !$_SESSION['demo'] ) {

//////////////////////////////////////////
// if form has been submit to edit info

// create back link
$goback="<p><a href=\"javascript:history.back()\">".$lng[478]."</a></p>";

// make sure category was selected
if( empty( $category ) )
	abcPageExit("<h3>".$lng[479]."</h3>$goback");
	
if( $cat_id == $cat_father_id )
	abcPageExit("<h3>".$lng[480]."</h3>$goback");

if( !is_numeric( $priority ) )
	abcPageExit("<h3>".$lng[481]."</h3>$goback");
else $priority = (int) $priority;

// delete category image (or delete old image if update function)
if( $image_function == "del" || $image_function == "up" )
{
	$image_dupe = mysql_query("select * from ".$prefix."store_category where cat_image = '$cat_image'"); 
	$image_total = mysql_num_rows($image_dupe);
	if( $image_total == 1 )
		abcDelCategoryImage( $cat_image );
	if( $image_function == "del" )
		$userfile_name = "";
}

// upload category image
if( $image_function == "up" )
{
	$path = "$site_dir/images/category/";
	$max_size = 200000;

	if( is_uploaded_file( $userfile_tmp_name ) )
	{
		if( $userfile_size > $max_size )
			abcPageExit("<h3>".$lng[482]."</h3>$goback\n");

		if( !abcIsImageContentType( $userfile_type  ) )
			abcPageExit($lng[483]."$goback");

		$res = copy( $userfile_tmp_name, $path . $userfile_name );
		if( !$res )
			abcPageExit($lng[484]."$goback");
		
	}
}

// no image update/delete
if( empty( $image_function ) )
	$userfile_name = $cat_image;

$userfile_name = addslashes( $userfile_name );

// Upate category itself
$sql_update = "update " . $prefix . "store_category set
		cat_father_id='$cat_father_id', category='$category',
		cat_image='$userfile_name', priority='$priority' where cat_id='$cat_id'";
$result = mysql_query ($sql_update);

if ( !is_file ( "../images/category/$userfile_name" ) )
	$image_view = "nophoto.gif";
else	$image_view = $userfile_name;

echo "<h3>$category ".$lng[485]."</h3>
	<p><img src='../images/category/$image_view'>
	<br><br>
	<a href='categories.php'>".$lng[486]."</a></p>";

}
else {
	
	echo ("<font color='red'>".$lng[487]."</font><br><br>
	<a href='categories.php'>".$lng[488]."</a>");	
	
}

include("footer.inc.php");

?>