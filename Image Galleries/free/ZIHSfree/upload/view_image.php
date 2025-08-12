<?php # ZIHS view_image.php

// Iron Muskie, Inc
// Michael J. Muff
// customer_service@iron-muskie-inc.com
// Version 1.0918
// September 2005

require_once('Access_Check.php');
AuthUser('User');

//Page Title
$page_title = ' - View Image';

//Include Functions
include_once ('./IMI-HTMLcode.php');

require_once ('./Mysqlconnect.php'); // Connect to the db.

// Get a valid user ID, through GET or POST.
	$id = $_GET['id'];

// Get the data from the database for session.
$uid=$_SESSION['user_id'];
$acc=$_SESSION['access_level'];

//Prevent User, but allow Admin From Image Not Associated with their User ID
				$check = "SELECT user_id, file_name FROM Uploads WHERE upload_id='$id'";
				$resultc = mysql_query($check);
				while ($row=mysql_fetch_array ($resultc, MYSQL_ASSOC)) {
					$UserID=$row['user_id'];
					$FileName=$row['file_name'];
				}

				if ($uid!=$UserID && $acc!='Admin') {
					echo '<h3>Error:</h3><p class="required">An error was encountered accessing this image.  Please contact 
					the ZIHS Administrator to review and delete the image.</p>';
					exit();
				}
				
echo '<h2 id="mainhead">View the Image</h2>
		<p align=center> Image:  '.$FileName.'<br><br>
		<img src="uploads/'.$FileName.'"><br><br>
		Link:  <a href="http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']).'/uploads/'.$FileName.'">http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']).'/uploads/'.$FileName.'</a></p><p><br /><br /></p>';

mysql_close(); // Close the database connection.
?>