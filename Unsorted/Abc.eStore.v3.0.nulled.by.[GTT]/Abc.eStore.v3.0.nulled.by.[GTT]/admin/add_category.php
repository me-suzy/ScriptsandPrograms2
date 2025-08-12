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

echo "<h2>".$lng[267]."</h2>";

$cats = abcFetchCategoryList();
$total = count( $cats );

echo "<p><b>".$lng[268].": $total</b></p>";

if( $_SERVER["REQUEST_METHOD"] == "POST" )
{
	extract( $_POST );
	
	$userfile = $_FILES["userfile"];
	$userfile_name = $userfile['name'];
	$userfile_type = $userfile['type'];
	$userfile_size = $userfile['size'];
	$userfile_tmp_name = $userfile['tmp_name'];
	
		
	if( isset( $userfile['name'] ) && $userfile['name'] != '' )
		$upload_image = 'yes';
	else
		$upload_image = 'no';
	
	$submit = true;
}

if ( !isset ($priority) )
	$priority = "000";

// if submit has not been clicked
if( !$submit )
{
	echo "
<form enctype=\"multipart/form-data\" action=\"add_category.php\" method=\"post\">

<table border=\"1\" bordercolor=\"#e6e6e6\" cellspacing=\"0\" cellpadding=\"4\" width=\"95%\">
<tr>
<td valign=\"top\"><b>".$lng[269].":</b></td>
<td valign=\"top\"><input class=\"textbox\" type=\"textbox\" name=\"cat_name\"></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[270].":</b></td>
<td valign=\"top\">\n";

	$cats = abcFetchCategoryList();
	
	// Drop down menu to build full category!
	print "\t\t\t\t\t<select name=\"cat_father_id\">
						<option value=\"0\">$lng[457]</option>\n";

	foreach( $cats as $cat )
	{							
		$catname = $cat["path"];
		$cat_id_dd = $cat["cat_id"];
		echo"\t\t\t\t\t\t<option value=\"$cat_id_dd\">$catname</option>\n";
	}
    echo"\t\t\t\t\t</select>
</td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[271]." (000-999):</b></td>
<td valign=\"top\"><INPUT class=\"textbox\" TYPE=\"text\" NAME=\"priority\" MAXLENGTH=\"3\" VALUE=\"$priority\"></td>
</tr>

<tr>
<td valign=\"top\"><b>".$lng[272]." (*.jpg,*.gif,*.png):</b></td>
<td valign=\"top\">
<INPUT class=\"file\" NAME=\"userfile\" TYPE=\"file\"></td>
</tr>

<tr>
<td valign=\"top\">&nbsp;</td>
<td valign=\"top\"><INPUT class=\"submit\" TYPE=\"submit\" NAME=\"submit\" VALUE=\"".$lng[284]."\"></td>
</tr>
</table>

</form>";

} // if !$submit

// if submit has been clicked
if( $submit && !$_SESSION['demo'] )
{
	// link back
	$goback="<br><a href=\"javascript:history.back()\">Try again</a>";
	 
	// if category was not chosen
	if( empty($cat_name) )
		abcPageExit("<h4>".$lng[277]."</h4>$goback");

	if( !is_numeric( $priority ) )
		abcPageExit("<h3>".$lng[278]."</h3>$goback");
	else $priority = (int) $priority;


	// if upload image was clicked
	
		// Upload
		$path = "$site_dir/images/category/";
		$max_size = 200000;

		if( is_uploaded_file($userfile_tmp_name) ) {
		
			if( $userfile_size > $max_size )
				abcPageExit("<h4>".$lng[279]."</h4>$goback");

			if( !abcIsImageContentType( $userfile_type  ) )
				abcPageExit($lng[280]."$goback");
			
			$res = copy($userfile_tmp_name, $path . $userfile_name);
			if( !$res )
				abcPageExit($lng[281]."$goback");
		}
		else 	$userfile_name = "";
	
			$userfile_name = addslashes($userfile_name);
			$_cat_name = mysql_escape_string($cat_name);
			
			$sql_insert = "insert into ".$prefix."store_category (
					category, cat_father_id, cat_image, priority ) values (
					'$cat_name', '$cat_father_id','$userfile_name', '$priority')";
			$result = mysql_query($sql_insert)
				or abcPageExit("Invalid query: " . mysql_error() . $goback);
			
			
			if ( !is_file ( "../images/category/$userfile_name" )  )
				$cat_image_view = "nophoto.gif";
			else	$cat_image_view = $userfile_name;
			
			
			echo "
<h3>".$lng[282].": $cat_name created!</h3>
<p><img src='../images/category/$cat_image_view'></p>
<p><a href=\"add_product.php\">".$lng[283]."</a> | <a href=\"add_category.php\">".$lng[284]."</a></p>
<p><a href=\"categories.php\">".$lng[285]."</a></p>";
		
	
}

if( $submit && $_SESSION['demo'] ) {
	
	include('_guest_access.php');
	
}

include ("footer.inc.php");

?>
