<?php require_once("./inc/header.inc.php"); ?>
<?php 
if($_SESSION['submit_update']){
	doUpdate();
}
else{
	showUpdate();
}

?>
<?php require_once("./inc/footer.inc.php"); ?>