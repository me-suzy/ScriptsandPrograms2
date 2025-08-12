<?php # ZIHS admin_images.php

// Iron Muskie, Inc
// Michael J. Muff
// customer_service@iron-muskie-inc.com
// Version 1.0918
// September 2005

require_once('Access_Check.php');
AuthUser('Admin');

//Page Title
$page_title = ' - Admin Panel List Images';

//Include Functions
include_once ('./IMI-HTMLcode.php');

//Get the user id!
$userid=$_SESSION['user_id'];

require_once ('./Mysqlconnect.php'); // Connect to the db.

// Get Valid Start
	if (isset($_GET['s'])){
		$shere = $_GET['s'];
	} else {
		$shere=0;
	}

if (isset($_GET['vchid'])) {

	// Get a valid  ID, through GET or POST.
	$id = $_GET['vchid'];
	
// Get the data from the database for session.
$acc=$_SESSION['access_level'];

//Prevent User From Image Not Associated with their User ID
				$check = "SELECT user_id, viewable, file_name, hidden FROM Uploads WHERE upload_id='$id'";
				$resultc = mysql_query($check);
				while ($row=mysql_fetch_array ($resultc, MYSQL_ASSOC)) {
					$UserID=$row['user_id'];
					$Viewable=$row['viewable'];
					$FileName=$row['file_name'];
					$Hidden=$row['hidden'];
				}

				if ($acc!='Admin') {
					echo '<h3>Error:</h3><p class="required">An error was encountered accessing this image.  Please contact 
					the ZIHS Administrator to review and delete the image.</p>';
					exit();
				}
				
//Check Image Status
if ($Viewable=='Yes') {

	// Hide File from user.
	$fn="uploads/".$FileName;
	$new_fn="uploads/hidden".substr ( md5(uniqid(rand(),1)), 3, 8).get_ext($FileName);
	rename($fn, $new_fn);

	$query = "UPDATE Uploads SET viewable='No', hidden='$new_fn' WHERE upload_id='$id'";
	$result = mysql_query($query);
	if ($result) {
					echo '<h2>Status Change</h2><p>Link:  <a href="http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']).'/uploads/'.$FileName.'">http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']).'/uploads/'.$FileName.'</a>
					is no longer viewable.</p>';
					//exit();
	}

} else {

	rename($Hidden, "uploads/".$FileName);
	
	$query = "UPDATE Uploads SET viewable='Yes' WHERE upload_id='$id'";
	$result = mysql_query($query);
	if ($result) {
					echo '<h2>Status Change</h2><p>Link:  <a href="http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']).'/uploads/'.$FileName.'">http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']).'/uploads/'.$FileName.'</a>
					is now viewable.</p>';
					//exit();
	}

}

	
}


// Number of records to show per page:
$display = 10;

// Determine how many pages there are. 
if (isset($_GET['np'])) { // Already been determined.
	$num_pages = $_GET['np'];
} else { // Need to determine.

 	// Count the number of records
	$query = "SELECT COUNT(*) FROM Uploads ORDER BY date_entered ASC";
	$result = @mysql_query ($query);
	$row = mysql_fetch_array ($result, MYSQL_NUM);
	$num_records = $row[0];
	
	// Calculate the number of pages.
	if ($num_records > $display) { // More than 1 page.
		$num_pages = ceil ($num_records/$display);
	} else {
		$num_pages = 1;
	}
	
} // End of np IF.

// Determine where in the database to start returning results.
if (isset($_GET['s'])) {
	$start = $_GET['s'];
} else {
	$start = 0;
}

// Default column links.
$link1 = "{$_SERVER['PHP_SELF']}?sort=fna";
$link2 = "{$_SERVER['PHP_SELF']}?sort=uia";
$link3 = "{$_SERVER['PHP_SELF']}?sort=dra";

// Determine the sorting order.
if (isset($_GET['sort'])) {

	// Use existing sorting order.
	switch ($_GET['sort']) {
		case 'fna':
			$order_by = 'file_name ASC';
			$link1 = "{$_SERVER['PHP_SELF']}?sort=fnd";
			break;
		case 'fnd':
			$order_by = 'file_name DESC';
			$link1 = "{$_SERVER['PHP_SELF']}?sort=fna";
			break;
		case 'uia':
			$order_by = 'user_id ASC';
			$link2 = "{$_SERVER['PHP_SELF']}?sort=uid";
			break;
		case 'uid':
			$order_by = 'user_id DESC';
			$link2 = "{$_SERVER['PHP_SELF']}?sort=uia";
			break;
		case 'dra':
			$order_by = 'date_entered ASC';
			$link3 = "{$_SERVER['PHP_SELF']}?sort=drd";
			break;
		case 'drd':
			$order_by = 'date_entered DESC';
			$link3 = "{$_SERVER['PHP_SELF']}?sort=dra";
			break;
		default:
			$order_by = 'date_entered DESC';
			break;
	}
	
	// $sort will be appended to the pagination links.
	$sort = $_GET['sort'];
	
} else { // Use the default sorting order.
	$order_by = 'date_entered ASC';
	$sort = 'dra';
}
		
// Make the query.
$query = "SELECT file_name, file_size, viewable, upload_id, date_entered, user_id, logged_ip FROM Uploads ORDER BY $order_by LIMIT $start, $display";		
$result = @mysql_query ($query); // Run the query.

// Table header.
echo '<table align="center" cellspacing="0" cellpadding="5">
<tr>
	<td align="center"><b>View</b></td>
	<td align="center"><b>Delete</b></td>
	<td align="center"><b><a href="' . $link1 . '">File Name</a></b></td>
	<td align="center"><b><a href="' . $link2 . '">User ID</a></b></td>
	<td align="center"><b><a href="' . $link3 . '">Date Entered</a></b></td>
	<td align="center"><b>Viewable</b></td>
</tr>
';

// Fetch and print all the records.
$bg = '#eeeeee'; // Set the background color.
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // Switch the background color.
	echo '<tr bgcolor="' . $bg . '">
		<td align="center"><a href="view_image.php?id=' . $row['upload_id'] . '">View</a></td>
		<td align="center"><a href="delete_image.php?id=' . $row['upload_id'] . '">Delete</a></td>
		<td align="center">' . $row['file_name'] . '</td>
		<td align="center"><a href="admin_uduser.php?id=' . $row['user_id'] . '">'.$row['user_id'].'</a></td>
		<td align="center">' . $row['date_entered'] . '</td>
		<td align="center"><a href="admin_images.php?s='. $shere . '&np=' . $num_pages . '&sort=' . $sort .'&vchid=' . $row['upload_id'] . '">' . $row['viewable'] . '</a></td>
	</tr>
	';
}

echo '</table>';

mysql_free_result ($result); // Free up the resources.	

mysql_close(); // Close the database connection.

// Make the links to other pages, if necessary.
if ($num_pages > 1) {
	
	echo '<br /><p>';
	// Determine what page the script is on.	
	$current_page = ($start/$display) + 1;
	
	// If it's not the first page, make a Previous button.
	if ($current_page != 1) {
		echo '<a href="admin_images.php?s=' . ($start - $display) . '&np=' . $num_pages . '&sort=' . $sort .'">Previous</a> ';
	}
	
	// Make all the numbered pages.
	for ($i = 1; $i <= $num_pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="admin_images.php?s=' . (($display * ($i - 1))) . '&np=' . $num_pages . '&sort=' . $sort .'">' . $i . '</a> ';
		} else {
			echo $i . ' ';
		}
	}
	
	// If it's not the last page, make a Next button.
	if ($current_page != $num_pages) {
		echo '<a href="admin_images.php?s=' . ($start + $display) . '&np=' . $num_pages . '&sort=' . $sort .'">Next</a>';
	}
	
	echo '</p><div align="center"><a href="admin_index.php">Main Admin Site</a>
    </div>';
	
} // End of links section.
	
?>