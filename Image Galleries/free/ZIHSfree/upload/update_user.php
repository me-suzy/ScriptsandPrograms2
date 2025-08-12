<?php # ZIHS update_user.php

// Iron Muskie, Inc
// Michael J. Muff
// customer_service@iron-muskie-inc.com
// Version 1.0918
// September 2005

require_once('Access_Check.php');
AuthUser('User');

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
$page_title = ' - Update Profile';

//Include Functions
include_once ('./IMI-HTMLcode.php');

require_once ('./Mysqlconnect.php'); // Connect to the db.

// Get the data from the database for session.
		$uid=$_SESSION['user_id'];
		$query = "SELECT * FROM Users WHERE user_id=".$uid;
		$result = mysql_query($query);
		if (mysql_num_rows($result) == 0) {
			$errors[]="The user_id is not valid, please log in before updating the data.";
		}
		while ($row=mysql_fetch_array ($result, MYSQL_ASSOC)) {
			$FirstName=stripslashes($row['first_name']);
			$LastName=stripslashes($row['last_name']);
			$Email=stripslashes($row['email']);
			$Address1=stripslashes($row['address1']);
			$Address2=stripslashes($row['address2']);
			$City=stripslashes($row['city']);
			$State=stripslashes($row['state']);
			$Zip=stripslashes($row['zip']);
			$Country=stripslashes($row['country']);
			$Pharea=substr(stripslashes($row['phone']),0,3);
			$Phcity=substr(stripslashes($row['phone']),3,3);
			$Phloc=substr(stripslashes($row['phone']),6,4);
		}

// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

	$errors = array(); // Initialize error array.
	
	// Check for a first name.
	if (empty($_POST['first_name'])) {
		//$errors[] = 'You forgot to enter your first name.';
	} else {
		$fn = $_POST['first_name'];
	}
	
	// Check for a last name.
	if (empty($_POST['last_name'])) {
		//$errors[] = 'You forgot to enter your last name.';
	} else {
		$ln = $_POST['last_name'];
	}

	// Check for a address.
	if (empty($_POST['address1'])) {
		//$errors[] = 'You forgot to enter your address.';
	} else {
		$ada = $_POST['address1'];
		$adb = $_POST['address2'];
	}

	// Check for a city.
	if (empty($_POST['city'])) {
		//$errors[] = 'You forgot to enter your city.';
	} else {
		$cy = $_POST['city'];
	}

	// Check for a state.
	if (empty($_POST['state'])) {
		//$errors[] = 'You forgot to enter your state.';
	} else {
		$st = $_POST['state'];
	}

	// Check for a zip.
	if (empty($_POST['zip'])) {
		$errors[] = 'You forgot to enter your zip.';
	} else {
		/*if (!eregi ('([0-9]{5})', escape_data($_POST['zip']))) {
		$errors[]= 'The zip code is not the correct format (12345)';
		} else { */
		$zp = $_POST['zip'];
		//}
	}

	// Check for a country.
	if (empty($_POST['country'])) {
		$errors[] = 'You forgot to enter your country.';
	} else {
		$ctry = $_POST['country'];
	}

	// Check for a phone.
	if (empty($_POST['pharea'])||empty($_POST['phcity'])||empty($_POST['phnum'])) {
		$errors[] = 'You forgot to enter your complete phone number.';
	} else {
		if (!eregi ('([0-9]{3})', $_POST['pharea'])) {
		$errors[]= 'The phone area code is not the correct format (123)';
		} 
		if (!eregi ('([0-9]{3})', $_POST['phcity'])) {
		$errors[]= 'The phone city code is not the correct format (123)';
		}
		if (!eregi ('([0-9]{4})', $_POST['phnum'])) {
		$errors[]= 'The last 4 of phone number is not the correct format (1234)';
		}

		$ph = $_POST['pharea'].$_POST['phcity'].$_POST['phnum'];
		
	}
	
	if (empty($errors)) { // If everything's okay.
		
		// Register the user in the database.
		
		

			// Make the query.
			$query = "UPDATE Users SET first_name='$fn', last_name='$ln', address1='$ada', address2='$adb', city='$cy',  state='$st', zip='$zp', country='$ctry', phone='$ph' WHERE user_id='$uid' LIMIT 1";		
			$result = mysql_query ($query); // Run the query.
			if ($result) { // If it ran OK.

				// Send an email.
				$body = "Your profile has been updated at the ZIHS web site.
				

Thank You,

ZIHS Administrator.";

				mail ($Email, 'Your ZIHS Profile Update Confirmation.', $body, 'From: admin@zihs.com');				

				// Print a message.
				echo '<h1 id="mainhead">Thank you!</h1>
				<p>Your profile is now updated. You should receive an email in the next 24 hours your update!</p><p><br /></p>';	
			
				// Quit the script (to not show the form). 
				exit(); 
				
			} else { // If it did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">Your profile could not be updated due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>'; // Debugging message. 
				exit();
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
<form name="updateuser" action="update_user.php" method="post">
	<table align="center">
	<tr>
	<td colspan="2"><h2>Registration</h2></td></tr>
	<tr>
	<td class="required"><div align="right">First Name:*</div></td>
	<td><input type="text" name="first_name" size="30" maxlength="30" value="<?php if (isset($_POST['first_name'])) {echo $_POST['first_name'];} else {echo $FirstName;} ?>" /></td>
	</tr>
	<tr>
	<td class="required"><div align="right">Last Name:*</div></td><td> <input type="text" name="last_name" size="30" maxlength="30" value="<?php if (isset($_POST['last_name'])) {echo $_POST['last_name'];} else {echo $LastName;} ?>" /></td>
	</tr>
	<tr>
	<td class="required"><div align="right">Email Address:*</div></td><td> <?php echo $Email; ?></td>
	</tr>
	<tr>
	<td class="required"><div align="right">Address:*</div></td><td><input name="address1" type="text" value="<?php if (isset($_POST['address1'])) {echo $_POST['address1'];} else {echo $Address1;} ?>" size="30" maxlength="30" /></td>
	</tr>
	<tr>
	<td class="required"><div align="right"></div></td><td><input name="address2" type="text" value="<?php if (isset($_POST['address2'])) {echo $_POST['address2'];} else {echo $Address2;} ?>" size="30" maxlength="30" />
	</td>
	</tr>
	<tr>
	<td class="required"><div align="right">City:*</div></td><td><input name="city" type="text" value="<?php if (isset($_POST['city'])) {echo $_POST['city'];} else {echo $City;} ?>" size="30" maxlength="30" />
	</td>
	</tr>
	<tr>
	<td class="required"><div align="right">State/County/Province:*</div></td><td><input name="state" type="text" value="<?php if (isset($_POST['state'])) {echo $_POST['state'];} else {echo $State;} ?>" size="20" maxlength="20" />
	</td>
	</tr>
	<tr>
	<td class="required"><div align="right">Zip/Postal Code:*</div></td><td><input name="zip" type="text" value="<?php if (isset($_POST['zip'])) {echo $_POST['zip'];} else {echo $Zip;} ?>" size="20" maxlength="20" />
	</td>
	</tr>
	<tr>
	<td class="required"><div align="right">Country:*</div></td><td><input name="country" type="text" value="<?php if (isset($_POST['country'])) {echo $_POST['country'];} else {echo $Country;} ?>" size="30" maxlength="30" />
	</td>
	</tr>
	<tr>
	<td class="required"><div align="right">Phone:*</div></td><td><input name="pharea" type="text" value="<?php if (isset($_POST['pharea'])) {echo $_POST['pharea'];} else {echo $Pharea;} ?>" size="4" maxlength="3" />
	<input name="phcity" type="text" value="<?php if (isset($_POST['phcity'])) {echo $_POST['phcity'];} else {echo $Phcity;} ?>" size="4" maxlength="3" />
	<input name="phnum" type="text" value="<?php if (isset($_POST['phnum'])) {echo $_POST['phnum'];} else {echo $Phloc;} ?>" size="5" maxlength="4" />
	</td>
	</tr>
	<tr>
	<td colspan="2" align="center">
	  <div align="center">
	    <p>
	      <input type="submit" name="submit" value="Update User" />
		  <input type="submit" name="submit" value="Cancel">
	      </p>
	    <p>
	      <input type="hidden" name="submitted" value="TRUE" />
          </p>
	  </div></td>
	</tr>
	<tr><td class="required" colspan="2">* Indicates Required Fields</td></tr>
  </table>
</form>
<?php
mysql_close(); // Close the database connection.
?>