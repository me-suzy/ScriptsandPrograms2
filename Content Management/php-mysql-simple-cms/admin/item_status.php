<? 
require "../config.php";
	if(!isset($_GET['id']) || !isset($_GET['status'])) {
		echo "verkeerde parameters";
		exit;
	}
	else
	{
		database_connect();
	    $id = $_GET['id'];
		$status = $_GET['status'];
		$update = "UPDATE content
					SET status='$status'
					WHERE id='$id'"; 
		$query = mysql_query($update)or die("Their was a problem updating the status: ". mysql_error()); 	
		if($query){ header("Location: $ref");}		
	}?>	