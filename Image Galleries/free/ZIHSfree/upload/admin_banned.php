<?php # ZIHS admin_images.php

// Iron Muskie, Inc
// Michael J. Muff
// customer_service@iron-muskie-inc.com
// Version 1.0918
// September 2005

require_once('Access_Check.php');
AuthUser('Admin');

//Page Title
$page_title = ' - Admin Panel Banned IPs';

//Include Functions
include_once ('./IMI-HTMLcode.php');

//Get the user id!
$userid=$_SESSION['user_id'];

require_once ('./Mysqlconnect.php'); // Connect to the db.

if (isset($_GET['ipid'])) {

	// Get a valid  ID, through GET or POST.
	$id = $_GET['ipid'];

			$query = "DELETE FROM Banned WHERE ip_id='$id'";
			$result = mysql_query($query);
	
}

// Number of records to show per page:
$display = 10;

// Determine how many pages there are. 
if (isset($_GET['np'])) { // Already been determined.
	$num_pages = $_GET['np'];
} else { // Need to determine.

 	// Count the number of records
	$query = "SELECT COUNT(*) FROM Banned ORDER BY logged_ip ASC";
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
$link1 = "{$_SERVER['PHP_SELF']}?sort=ida";
$link2 = "{$_SERVER['PHP_SELF']}?sort=ipa";

// Determine the sorting order.
if (isset($_GET['sort'])) {

	// Use existing sorting order.
	switch ($_GET['sort']) {
		case 'ida':
			$order_by = 'ip_id ASC';
			$link1 = "{$_SERVER['PHP_SELF']}?sort=idd";
			break;
		case 'idd':
			$order_by = 'ip_id DESC';
			$link1 = "{$_SERVER['PHP_SELF']}?sort=ida";
			break;
		case 'ipa':
			$order_by = 'logged_ip ASC';
			$link2 = "{$_SERVER['PHP_SELF']}?sort=ipd";
			break;
		case 'ipd':
			$order_by = 'logged_ip DESC';
			$link2 = "{$_SERVER['PHP_SELF']}?sort=ipa";
			break;
		default:
			$order_by = 'logged_ip DESC';
			break;
	}
	
	// $sort will be appended to the pagination links.
	$sort = $_GET['sort'];
	
} else { // Use the default sorting order.
	$order_by = 'logged_ip ASC';
	$sort = 'ipa';
}
		
// Make the query.
$query = "SELECT ip_id, logged_ip FROM Banned ORDER BY $order_by LIMIT $start, $display";		
$result = @mysql_query ($query); // Run the query.

// Table header.
echo '<table align="center" cellspacing="0" cellpadding="5">
<tr>
	<td align="center"><b><a href="' . $link1 . '">IP ID</a></b></td>
	<td align="center"><b><a href="' . $link2 . '">Logged IP</a></b></td>
	<td align="center"><b>Update</b></td>
</tr>
';

// Fetch and print all the records.
$bg = '#eeeeee'; // Set the background color.
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // Switch the background color.
	echo '<tr bgcolor="' . $bg . '">
		<td align="center">' . $row['ip_id'] . '</td>
		<td align="center">' . $row['logged_ip'] .'</td>
		<td align="center"><a href="admin_banned.php?ipid='.$row['ip_id'].'">Remove</a></td>
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
		echo '<a href="admin_banned.php?s=' . ($start - $display) . '&np=' . $num_pages . '&sort=' . $sort .'">Previous</a> ';
	}
	
	// Make all the numbered pages.
	for ($i = 1; $i <= $num_pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="admin_banned.php?s=' . (($display * ($i - 1))) . '&np=' . $num_pages . '&sort=' . $sort .'">' . $i . '</a> ';
		} else {
			echo $i . ' ';
		}
	}
	
	// If it's not the last page, make a Next button.
	if ($current_page != $num_pages) {
		echo '<a href="admin_banned.php?s=' . ($start + $display) . '&np=' . $num_pages . '&sort=' . $sort .'">Next</a>';
	}
	
	echo '</p>';
	 
	
} // End of links section.
	
	echo '<div align="center"><a href="admin_index.php">Main Admin Site</a>
    </div>';
?>