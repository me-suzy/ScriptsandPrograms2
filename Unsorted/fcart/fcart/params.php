<?
$category=$default_category;
if (strlen($HTTP_GET_VARS["category"]) > 0)
	$category = r_secure($HTTP_GET_VARS["category"]);
if ($category == "All")
	$category=$default_category;
$sortby = $default_sortby;
if (strlen($HTTP_GET_VARS["sortby"]) > 0)
	$sortby = r_secure($HTTP_GET_VARS["sortby"]);
if (strlen($HTTP_GET_VARS["first"]) > 0)
	$first = r_secure($HTTP_GET_VARS["first"]);
else
	$first = 1;
?>
