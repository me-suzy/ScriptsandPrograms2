<?php #admin_banip.php

// Iron Muskie, Inc
// Michael J. Muff
// customer_service@iron-muskie-inc.com
// Version 1.0918
// September 2005
require_once('Access_Check.php');
AuthUser('Admin');

if ($_POST['submit']=='Cancel') {

					// Start defining the URL.
					$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
					// Check for a trailing slash.
					if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
							$url = substr ($url, 0, -1); // Chop off the slash.
					}
					$url .= '/index.php'; // Add the page.
					header("Location: $url");
					exit(); // Quit the script.
}

//Page Title
$page_title = ' - Admin Ban IP';

//Include Functions
include_once ('./IMI-HTMLcode.php');

require_once ('./Mysqlconnect.php'); // Connect to the db.

// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

	$errors = array(); // Initialize error array.
	
	// Check for the first box.
	if (empty($_POST['box1'])) {
		$errors[] = 'You forgot to enter the first box.';
	} else {
		$box1 = $_POST['box1'];
	}
	
	// Check for the second box.
	if (empty($_POST['box2'])) {
		$errors[] = 'You forgot to enter the second box.';
	} else {
		$box2 = $_POST['box2'];
	}
	
	// Check for the third box.
	if (empty($_POST['box3'])) {
		$errors[] = 'You forgot to enter the third box.';
	} else {
		$box3 = $_POST['box3'];
	}
	
	// Check for the fourth box.
	if (empty($_POST['box4'])) {
		$errors[] = 'You forgot to enter the fourth box.';
	} else {
		$box4 = $_POST['box4'];
	}
	
	$ip=$box1.'.'.$box2.'.'.$box3.'.'.$box4;
	
	if (empty($errors)) { // If everything's okay.
	
		$query = "SELECT * FROM Banned WHERE logged_ip='$ip'";
		$result = mysql_query($query);
		if (mysql_num_rows($result) == 0) {
		
			$query2 = "INSERT INTO Banned (logged_ip) VALUES ('$ip')";
			$result2 = mysql_query($query2);
			if ($result2) { // If it ran OK.
			
				// Print a message.
				echo '<h1 id="mainhead">Thank you!</h1>
				<p>The ip is now banned!</p><p><br /></p>';	
			
				// Quit the script (to not show the form). 
				exit();
			
			} else { // If it did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The IP could not be banneded due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				//echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>'; // Debugging message. 
				exit();
			}
		
		} else { // Already banneded.
			echo '<h1 id="mainhead">Error!</h1>
			<p class="error">The ip has already been banneded.</p>';
		}	
		
	
	} else { // Report the errors.
	
		echo '<h1 id="mainhead">Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';
		
	} // End of if (empty($errors)) IF.

} // End of the main Submit conditional.

?>
<form name="banip" action="admin_banip.php" method="post">
	<table align="center">
	<tr>
	<td colspan="2"><h2>Ban IP Address:</h2></td></tr>
	<tr>
	<td><div align="right">IP:</div></td>
	<td><input name="box1" type="text" value="<?php if (isset($_POST['box1'])) echo $_POST['box1']; ?>" size="4" />
	.<input name="box2" type="text" value="<?php if (isset($_POST['box2'])) echo $_POST['box2']; ?>" size="4"  />
	.<input name="box3" type="text" value="<?php if (isset($_POST['box3'])) echo $_POST['box3']; ?>" size="4" />
	.<input name="box4" type="text" value="<?php if (isset($_POST['box4'])) echo $_POST['box4']; ?>" size="4" />
	</td>
	</tr>
	<tr>
	<td colspan="2" align="center">
	  <div align="center">
	    <p>
	      <input type="submit" name="submit" value="Ban IP" />
	      </p>
	    <p>
	      <input type="hidden" name="submitted" value="TRUE" />
          </p>
	  </div></td>
	</tr>
  </table>
    <div align="center"><a href="admin_index.php">Main Admin Site</a>
    </div>
</form>