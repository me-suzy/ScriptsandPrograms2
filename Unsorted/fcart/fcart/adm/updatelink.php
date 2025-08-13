<?
include "../config.php";
include "../mod.php";
include "auth.php";
include "mod.php";
if ($safe_admin)
	safe_mode_msg(true);

$sortby = $default_sortby;
if (strlen($HTTP_GET_VARS["sortby"]) > 0)
        $sortby = r_secure($HTTP_GET_VARS["sortby"]);
if (strlen($HTTP_GET_VARS["first"]) > 0)
        $first = r_secure($HTTP_GET_VARS["first"]);
if (strlen($HTTP_GET_VARS["productid1"]) > 0)
		$productid1 = d_secure($HTTP_GET_VARS["productid1"]);
if (strlen($HTTP_GET_VARS["productid2"]) > 0)
		$productid2 = d_secure($HTTP_GET_VARS["productid2"]);

if (strlen($HTTP_GET_VARS["category"]) > 0)
        $category = r_secure($HTTP_GET_VARS["category"]);
else
        $first = 1;

$mode = $HTTP_GET_VARS["mode"];
$twotier = $HTTP_GET_VARS["twotier"];

if ($mode == "delete")
	mysql_query("delete from product_links where productid='$productid1' and link='$productid2'") or die ("$mysql_error_msg");
else {
	mysql_query("insert into product_links (productid,link) values ('$productid1','$productid2')") or die ("$mysql_error_msg");

	if ($twotier == "on")
		mysql_query("insert into product_links (productid,link) values ('$productid2','$productid1')") or die ("$mysql_error_msg");
}

header("Location: ".($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location")."/main.php?first=$first&sortby=$sortby&category=".urlencode($category));
?>
