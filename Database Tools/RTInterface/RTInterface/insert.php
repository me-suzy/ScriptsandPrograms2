<?php require_once("./inc/header.inc.php"); ?>
<?php 
if($_SESSION['submit_insert']){
	doInsert();
}
else{
	showInsert();
}

?>
<?php require_once("./inc/footer.inc.php"); ?>