<?php require_once("./inc/header.inc.php"); ?>
<?php 
if($_SESSION['submit_search']){
	doSearch();
}
else{
	showSearch();
}

?>
<?php require_once("./inc/footer.inc.php"); ?>