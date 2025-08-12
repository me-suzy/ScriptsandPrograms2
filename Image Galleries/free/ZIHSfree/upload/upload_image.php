<?php #upload_image.php

// Iron Muskie, Inc
// Michael J. Muff
// customer_service@iron-muskie-inc.com
// Version 1.0918
// September 2005

require_once('Access_Check.php');
AuthUser('User');

//Page Title
$page_title = ' - Upload Image';

//Include Functions
include_once ('./IMI-HTMLcode.php');

require_once ('./Mysqlconnect.php'); // Connect to the db.

//Get the user id!
$userid=$_SESSION['user_id'];

// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

	// Check for an uploaded file.
	if (isset($_FILES['upload'])) {
		
		if ($_FILES['upload']['error']==0) {
		
			// Validate the type. Should be jpeg, jpg, or gif.
			$allowed = array ('image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png');
			if (in_array($_FILES['upload']['type'], $allowed)) {
		
				//	Generate Case Number
				$fp = fopen("nextimid.log", "r");
				$imid = fgets($fp);
				fclose($fp);

				$newimid=$imid+1;

				$fp = fopen("nextimid.log", "w");
				fputs($fp, $newimid);
				fclose($fp);
			
				$fn = 'image_'.substr ( md5(uniqid(rand(),1)), 3, 8).'-'.$newimid.get_ext($_FILES['upload']['name']);
				$fs = $_FILES['upload']['size'];
				$ipid = $_SERVER['REMOTE_ADDR'];
				
				if (move_uploaded_file($_FILES['upload']['tmp_name'], "uploads/{$fn}")) {
			
					$query = "INSERT INTO Uploads (file_name, file_size, date_entered, user_id, logged_ip, viewable ) 
						VALUES ('$fn', '$fs', NOW(), '$userid', '$ipid', 'Yes' )";		
					$result = mysql_query ($query); // Run the query.
				
					echo '<p>The file has been uploaded! The link is <a href="http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']).'/uploads/'.$fn.'">http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']).'/uploads/'.$fn.'</a></p>';
			
				} else { // Couldn't move the file over
				
					echo 'There was an error in the file transfer, please contact an administrator!';
				
				}

			} else { // Invalid type.
				echo '<p><font color="red">Please upload either a JPEG, PNG, or GIF image.</font></p>';
				unlink ($_FILES['upload']['tmp_name']); // Delete the file.
			}
		
		} else { // Evaluate the error
			
				echo '<p><font color="red">The file could not be uploaded because: </b>';
		
				// Print a message based upon the error.
				switch ($_FILES['upload']['error']) {
					case 1:
						print 'The file exceeds the upload_max_filesize setting in php.ini.';
						break;
					case 2:
						print 'The file exceeds the MAX_FILE_SIZE setting: '.$_POST['MAX_FILE_SIZE'].' in the HTML form.';
						break;
					case 3:
						print 'The file was only partially uploaded.';
						break;
					case 4:
						print 'No file was uploaded.';
						break;
					case 6:
						print 'No temporary folder was available.';
						break;
					default:
						print 'A system error occurred.';
						break;
				} // End of switch.
				
				print '</b></font></p>';

			} // End of Error If

	} else { // No file uploaded.
		echo '<p><font color="red">Please upload a JPEG, PNG, or GIF image smaller than '.$_POST['MAX_FILE_SIZE'].' KB.</font></p>';
	}

} // End of the submitted conditional.
?>

<form enctype="multipart/form-data" action="upload_image.php" method="post">

	<input type="hidden" name="MAX_FILE_SIZE" value="524288">

<table width="400" align="center"><tr><td align="center" valign="middle">
	
	<fieldset><legend>Upload Now:</legend>
	
	<p align="center"><input type="file" name="upload" /><input type="submit" name="submit" value="Upload" />
	  <br>
	  <br></p>
	
	</fieldset>
	
</td></tr></table>
	<input type="hidden" name="submitted" value="TRUE" />
</form>
