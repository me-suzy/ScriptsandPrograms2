<?php # ZIHS delete_user.php

// Iron Muskie, Inc
// Michael J. Muff
// customer_service@iron-muskie-inc.com
// Version 1.0918
// September 2005

require_once('Access_Check.php');
AuthUser('Admin');

//Page Title
$page_title = ' - Delete User';

//Include Functions
include_once ('./IMI-HTMLcode.php');

require_once ('./Mysqlconnect.php'); // Connect to the db.

// Get a valid user ID, through GET or POST.
	$id = $_GET['id'];

// Get the data from the database for session.
$uid=$_SESSION['user_id'];

//Get  Info From Database
				$check = "SELECT first_name, last_name FROM Users WHERE user_id='$id'";
				$resultc = mysql_query($check);
				while ($row=mysql_fetch_array ($resultc, MYSQL_ASSOC)) {
					$FirstName=$row['first_name'];
					$LastName=$row['last_name'];
				}
				
// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

	if ($_POST['sure'] == 'Yes') { // Delete them.
		
		// Make the query.
		$query = "DELETE FROM Users WHERE user_id='$id'";		
		$result = mysql_query ($query); // Run the query.
		if (mysql_affected_rows() == 1) { // If it ran OK.
		
			// Print a message.
			echo '<h2 id="mainhead">Delete A User</h2>
		<p>The user has been deleted.</p><p><br /><br /></p>';	
		
		} else { // If the query did not run OK.
			echo '<h2 id="mainhead">System Error</h2>
			<p class="error">The user could not be deleted due to a system error.</p>'; // Public message.
			//echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
		}	
	
	} else { // Wasn't sure about cancelling the image.
		echo '<h2 id="mainhead">Delete User Error</h2>
		<p>The user has NOT been deleted.</p><p><br /><br /></p>';	
	}

} else { // Show the form.

		
		// Create the form.
		echo '<h2>Delete User</h2>
	<h3><div class="required">Warning: This Action Will Cause the Image To Be Deleted.</div></h3>
	<form action="delete_user.php?id='.$id.'" method="post">
	<h3>User: ' . $FirstName .' '.$LastName.'</h3>
	<p>Are you sure you want to delete this user?<br />
	<input type="radio" name="sure" value="Yes" /> Yes 
	<input type="radio" name="sure" value="No" checked="checked" /> No</p>
	<p><input type="submit" name="submit" value="Submit" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
	</form><div align="center"><a href="admin_index.php">Main Admin Site</a>
    </div>';
	

} // End of the main Submit conditional.

mysql_close(); // Close the database connection.
?>
