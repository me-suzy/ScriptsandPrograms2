<?
session_start();
require ('_.php');
require ('functions.php');

$page_id=$_GET[page_id];
$dir=$_GET['dir'];
$del=$_GET['del'];
$pg_data_query="SELECT pg_title FROM ".$dir." WHERE page_id=".$page_id;
$pg_data_result=mysql_query($pg_data_query);
$pgdata=mysql_fetch_array($pg_data_result);
$pg_title=$pgdata[pg_title];
if (!$page_id){
	echo "No page to delete, how did you get here?<br>";
	echo "return to: <a href='admin_funtions.php'>admin_funtions.php</a><br>";
	}
if ($del!=1){
	echo "Command: <br>Delete <b>".$pg_title."</b> from <b>".$dir."</b>?<br><br><br>";	
	echo "<a href='?del=1&dir=".$dir."&page_id=".$page_id."'>heck ya!</a> <br>On second though, <a href='admin_funtions.php'>No</a><br><b>THERE IS NO UNDO!</b><br>" ;
	}
if ($del==1){

	$del_query= "DELETE FROM ".$dir." WHERE page_id=".$page_id." LIMIT 1";
	mysql_query($del_query) or die (mysql_error());
	echo"<script>window.location.replace('admin_funtions.php?dir=".$dir."')</script>";
}
?>

</body>
</html>
