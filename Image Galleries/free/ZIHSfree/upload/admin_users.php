<?php # ZIHS admin_images.php

// Iron Muskie, Inc
// Michael J. Muff
// customer_service@iron-muskie-inc.com
// Version 1.0918
// September 2005

require_once('Access_Check.php');
AuthUser('Admin');

//Page Title
$page_title = ' - Admin Panel List Users';

//Include Functions
include_once ('./IMI-HTMLcode.php');

//Get the user id!
$userid=$_SESSION['user_id'];

require_once ('./Mysqlconnect.php'); // Connect to the db.

// Number of records to show per page:
$display = 10;

// Determine how many pages there are. 
if (isset($_GET['np'])) { // Already been determined.
	$num_pages = $_GET['np'];
} else { // Need to determine.

 	// Count the number of records
	$query = "SELECT COUNT(*) FROM Users ORDER BY last_name ASC";
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
$link1 = "{$_SERVER['PHP_SELF']}?sort=lna";
$link2 = "{$_SERVER['PHP_SELF']}?sort=fna";
$link3 = "{$_SERVER['PHP_SELF']}?sort=lla";

// Determine the sorting order.
if (isset($_GET['sort'])) {

	// Use existing sorting order.
	switch ($_GET['sort']) {
		case 'lna':
			$order_by = 'last_name ASC';
			$link1 = "{$_SERVER['PHP_SELF']}?sort=lnd";
			break;
		case 'lnd':
			$order_by = 'last_name DESC';
			$link1 = "{$_SERVER['PHP_SELF']}?sort=lna";
			break;
		case 'fna':
			$order_by = 'first_name ASC';
			$link2 = "{$_SERVER['PHP_SELF']}?sort=fnd";
			break;
		case 'fnd':
			$order_by = 'first_name DESC';
			$link2 = "{$_SERVER['PHP_SELF']}?sort=fna";
			break;
		case 'lla':
			$order_by = 'last_login ASC';
			$link3 = "{$_SERVER['PHP_SELF']}?sort=lld";
			break;
		case 'lld':
			$order_by = 'last_login DESC';
			$link3 = "{$_SERVER['PHP_SELF']}?sort=lla";
			break;
		default:
			$order_by = 'last_name DESC';
			break;
	}
	
	// $sort will be appended to the pagination links.
	$sort = $_GET['sort'];
	
} else { // Use the default sorting order.
	$order_by = 'last_name ASC';
	$sort = 'lnd';
}
		
// Make the query.
$query = "SELECT last_name, first_name, last_login, access_level, user_id FROM Users ORDER BY $order_by LIMIT $start, $display";		
$result = @mysql_query ($query); // Run the query.

// Table header.
echo '<table align="center" cellspacing="0" cellpadding="5">
<tr>
	<td align="center"><b>Edit</b></td>
	<td align="center"><b>Delete</b></td>
	<td align="center"><b><a href="' . $link1 . '">Last Name</a></b></td>
	<td align="center"><b><a href="' . $link2 . '">First Name</a></b></td>
	<td align="center"><b><a href="' . $link3 . '">Last Login</a></b></td>
	<td align="center"><b>Access Level</b></td>
</tr>
';

// Fetch and print all the records.
$bg = '#eeeeee'; // Set the background color.
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // Switch the background color.
	echo '<tr bgcolor="' . $bg . '">
		<td align="center"><a href="admin_uduser.php?id=' . $row['user_id'] . '">Edit</a></td>
		<td align="center"><a href="delete_user.php?id=' . $row['user_id'] . '">Delete</a></td>
		<td align="center">' . $row['last_name'] . '</td>
		<td align="center">' . $row['first_name'] . '</td>
		<td align="center">' . $row['last_login'] . '</td>
		<td align="center">' . $row['access_level'] . '</td>
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
		echo '<a href="admin_users.php?s=' . ($start - $display) . '&np=' . $num_pages . '&sort=' . $sort .'">Previous</a> ';
	}
	
	// Make all the numbered pages.
	for ($i = 1; $i <= $num_pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="admin_users.php?s=' . (($display * ($i - 1))) . '&np=' . $num_pages . '&sort=' . $sort .'">' . $i . '</a> ';
		} else {
			echo $i . ' ';
		}
	}
	
	// If it's not the last page, make a Next button.
	if ($current_page != $num_pages) {
		echo '<a href="admin_users.php?s=' . ($start + $display) . '&np=' . $num_pages . '&sort=' . $sort .'">Next</a>';
	}
	
	echo '</p>';
	
} // End of links section.
	
echo '<div align="center"><a href="admin_index.php">Main Admin Site</a>
    </div>';


?>