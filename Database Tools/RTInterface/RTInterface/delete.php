<?php require_once("./inc/header.inc.php"); ?>
<?php 
if($_SESSION['submit_delete']){
	doDelete();
}
else{
	showDelete();
}

?>
<?php require_once("./inc/footer.inc.php"); ?>