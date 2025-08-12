<?
if(isset($id)) {
if($id == 'home') {
$id = "list";
}
include($id.".php");
}
else {
include("list.php");
}
?>