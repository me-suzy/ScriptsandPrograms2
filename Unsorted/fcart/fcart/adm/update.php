<?
include "../config.php";
include "../mod.php";
include "auth.php";
include "mod.php";
if ($safe_admin)
	safe_mode_msg(true);

$sortby = $default_sortby;
if (strlen($HTTP_POST_VARS["sortby"]) > 0)
        $sortby = r_secure($HTTP_POST_VARS["sortby"]);
if (strlen($HTTP_POST_VARS["first"]) > 0)
        $first = r_secure($HTTP_POST_VARS["first"]);
if (strlen($HTTP_POST_VARS["productid"]) > 0)
		$productid = d_secure($HTTP_POST_VARS["productid"]);
if (strlen($HTTP_POST_VARS["_category"]) > 0)
        $_category = r_secure($HTTP_POST_VARS["_category"]);
else
        $first = 1;

$product = r_secure($HTTP_POST_VARS["product"]);
$descr = r_secure($HTTP_POST_VARS["descr"]);
$category = r_secure($HTTP_POST_VARS["category"]);
$price = r_secure($HTTP_POST_VARS["price"]);
$avail = r_secure($HTTP_POST_VARS["avail"]);
$image = r_secure($HTTP_POST_VARS["image"]);
$date = r_secure($HTTP_POST_VARS["date"]);
$newcategory = $HTTP_POST_VARS["newcategory"];

if (connection_status()) die("Connection was aborted with status:".connection_status());

if ($userfile == "none") {
	$error = "Using old/default image file<br>";
	$image=$oldimage;
} else {

	if($userfile_type == "image/jpeg" || $userfile_type == "image/pjpeg") {
		$ext=".jpg";
		$_fname=preg_replace("/^.*\//","",$userfile);
		$_funame=preg_replace("/\..*$/","",preg_replace("/^.*\//","",$userfile_name));
		$targetfile="../$images_url/".$_funame."_".$_fname.$ext;
		exec("mv $userfile $targetfile");
		$image=$_funame."_".$_fname.$ext;

	} elseif ($userfile_type == "image/gif") {
		$ext=".gif";
		$_fname=preg_replace("/^.*\//","",$userfile);
		$_funame=preg_replace("/\..*$/","",preg_replace("/^.*\//","",$userfile_name));
		$targetfile="../$images_url/".$_funame."_".$_fname.$ext;
		exec("mv $userfile $targetfile");
		$image=$_funame."_".$_fname.$ext;

	} else { 
		$error = "Unsupported image file type ( $userfile_type ). Only JPEG and GIF are supported. Using old image.<br>"; 
	$image = $oldimage;
	}
}

$date=date("Y-m-d",time());
if ($avail == "on") { $avail="Y"; } else { $avail="N"; }

if ($newcategory != "") { $category = $newcategory; }

if ($mode == "update" ) { 
	mysql_query("update products set product='$product', category='$category', price='$price', image='$image', descr='$descr', avail='$avail', a_date='$date' where productid='$productid'") or die ("$mysql_error_msg");
}
else { 
	mysql_query("insert into products (product,category,price,image,descr,avail,a_date) values ('$product','$category','$price','$image','$descr','$avail','$date')") or die ("$mysql_error_msg");
	$productid = mysql_insert_id();
}

$text = "
<font color=\"$cl_header\" size=\"+1\"><b>".($mode == "update" ? "Update" : "Add")." product results:</b></font><hr>
$error<br>
<b>ID:</b> $productid<br>
<b>Product Name:</b> $product<br>
<b>Description:</b><br>$descr<br>
<b>Category:</b> $category<br>
<b>Price:</b> $price<br>
<b>Available:</b> $avail<br>
<b>Image:</b> $image<br>
<b>Date:</b> $date<br>
<br>";

header("Location: ".($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location")."/message.php?text=".urlencode($text));
?>
