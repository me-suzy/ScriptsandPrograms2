<? 
	include_once("checksession.php");
?>

<?
$deleteID=$_GET['key'];

$cur= mysql_query( "delete from ".$databasePrefix."excess WHERE ID= '" . $_GET['key'] . "' " )
		or die("Invalid query: " . mysql_error());
header("location:ocm-first.php");
exit;

exit;

?>

