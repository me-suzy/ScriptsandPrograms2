<?
include "config.php";
include "mod.php";
include "params.php";

$id = $HTTP_GET_VARS["id"];
$referer = $HTTP_GET_VARS["referer"];

setcookie("ID", "$id", time() + $cookie_timeout);
$location="$referer?".(ereg("^https",$referer) ? "id=$id&" : "")."first=$first&sortby=$sortby&category=".urlencode($category);
header("Location: $location");
?>
