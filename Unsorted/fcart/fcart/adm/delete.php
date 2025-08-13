<?
include "../config.php";
include "../mod.php";
include "auth.php";
include "mod.php";

if ($safe_admin) safe_mode_msg(true);
$sortby = $default_sortby;
if (strlen($HTTP_GET_VARS["category"]) > 0)
        $sortby = r_secure($HTTP_GET_VARS["category"]);
if (strlen($HTTP_GET_VARS["sortby"]) > 0)
        $sortby = r_secure($HTTP_GET_VARS["sortby"]);
if (strlen($HTTP_GET_VARS["productid"]) > 0)
		$productid = d_secure($HTTP_GET_VARS["productid"]);
if (strlen($HTTP_GET_VARS["first"]) > 0)
        $first = r_secure($HTTP_GET_VARS["first"]);
else
        $first = 1;

$result = mysql_query("select image from products where productid='$productid'");
mysql_query("delete from products where productid='$productid'") or die ("$mysql_error_msg");

$row = mysql_fetch_row($result);
if ($row[0] != $default_image) 
	exec("rm ../$images_url/$row[0]");

header("Location: $HTTP_REFERER");
?>
