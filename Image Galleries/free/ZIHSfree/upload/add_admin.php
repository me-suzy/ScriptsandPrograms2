<?php # ZIHS register.php

// Iron Muskie, Inc
// Michael J. Muff
// customer_service@iron-muskie-inc.com
// Version 1.0918
// September 2005

// Use this script to add the first Administrator.
require_once ('./Mysqlconnect.php'); // Connect to the db.

$email="admin@zihs.com";
$password="adminpassword";

// Check for previous registration.
		$query = "SELECT user_id FROM Users WHERE email='$e'";
		$result = mysql_query($query);
		if (mysql_num_rows($result) == 0) {
		
			$query = "INSERT INTO Users (email, password, access_level, confirmed) VALUES ('$email', SHA('$password'), 'Admin', 'Yes')";
			$result = mysql_query ($query); // Run the query.
			if ($result) { // If it ran OK.

				// Send an email.
				$body = "You have been registered as an Admin at the ZIHS web site.
				
Your Username is your email address:  ".$email."

Your Password is:  ".$password."

Please delete add_admin.php from the server to avoid problems.

Thank You,

ZIHS Administrator.";

				mail ($email, 'Your ZIHS Registration Confirmation.', $body, 'From: admin@zihs.com');				

				// Print a message.
				echo '<div id="mainhead">Thank you!</div>
				<p>You are now registered as Admin. You should receive an email in the next 24 hours to confirm your registration! Please delete this script from the server to avoid further problems.</p><p><br /></p>';	
			
				// Quit the script (to not show the form). 
				exit(); 
				
			} else { // If it did not run OK.
				echo '<div id="mainhead">System Error</div>
				<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				//echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>'; // Debugging message. 
				exit();
			}
				
		} else { // Already registered.
			echo '<div id="mainhead">Error!</div>
			<p class="error">The email address has already been registered.</p>';
		}



?>