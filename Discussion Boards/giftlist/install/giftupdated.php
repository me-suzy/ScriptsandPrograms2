<?
include "header.php";
// require('db_connect.php');	// database connect script.
$ud_gift_id = $_POST['ud_gift_id'];
$ud_gift_name = $_POST['ud_gift_name'];
$ud_gift_price = $_POST['ud_gift_price'];
$ud_gift_url_store = $_POST['ud_gift_url_store'];
$ud_gift_description = $_POST['ud_gift_description'];

$query="UPDATE gifts SET gift_name='$ud_gift_name', buyable='$buyable', gift_price='$ud_gift_price', gift_url_store='$ud_gift_url_store', gift_description='$ud_gift_description' WHERE gift_id='$ud_gift_id'";
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query);

echo "Record Updated";
mysql_close();
?>



