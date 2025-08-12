<?php
/* 
	CascadianFAQ v4.1 - Last Updated: November 2003
	Summer S. Wilson, Eclectic Designs, http://eclectic-designs.com
	cfaq@eclectic-designs.com
*/

// Include configuration and database files
$dbtype = "mySQL";

include ("config.php");
include ("functions.php");
include ("header.php");
ConnectToDatabase();

// If there is no version in the config file, we know they are upgrading from version 3.2 or earlier
if (!isset($version) and !isset($mostrecent)) {
	// If they haven't done so yet, get the defaults for the new "most recent" and "most popular" features
	if (!isset($_POST['mostrecent'])) {
	?>
		<h3 align="center">Welcome to the CascadianFAQ Upgrader</h3>
		
		<p>Version 4.0 of CascadianFAQ incorporates several new features that require some changes to your database and to your configuration file.  This upgrade script will take care of those changes for you!</p>
		<p>Please fill in this form to let me know how you want the new features set up, then click update. Make sure your config.php file is writeable, otherwise I'll have to give you an error message. :-)</p>
		
		<form name="form1" method="post" action="<?php print $PHP_SELF ?>">
		<table width="600" border="1" align="center" cellpadding="1" cellspacing="0" bordercolor="#000000" bgcolor="#FFFFFF">
		<tr bordercolor="#FFFFFF">
			<th colspan="2">Most Recent Questions Feature</th>
		</tr>
		<tr bordercolor="#FFFFFF">
			<td>Do you want this feature turned on?</td>
			<td><input type="radio" name="mostrecent" value="0"> No | <input name="mostrecent" type="radio" value="1" checked> Yes</td>
		</tr>
		<tr bordercolor="#FFFFFF">
			<td>How many recent questions should be displayed?</td>
			<td><input name="nummostrecent" type="text" id="nummostrecent" value="5" size="3" maxlength="2">&nbsp;&nbsp;(ignore if feature is turned off)</td>
		</tr>
		<tr bordercolor="#FFFFFF">
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr bordercolor="#FFFFFF">
			<th colspan="2">Most Popular Questions Feature</th>
		</tr>
		<tr bordercolor="#FFFFFF">
			<td>Do you want this feature turned on?</td>
			<td><input type="radio" name="mostpopular" value="0"> No | <input name="mostpopular" type="radio" value="1" checked> Yes</td>
		</tr>
		<tr bordercolor="#FFFFFF">
			<td>How many popular questions should be displayed?</td>
			<td><input name="nummostpopular" type="text" id="nummostpopular" value="5" size="3" maxlength="2">&nbsp;&nbsp;(ignore if feature is turned off)</td>
		</tr>
		<tr bordercolor="#FFFFFF">
			<th colspan="2">User Submitted Questions</th>
		</tr>
		<tr bordercolor="#FFFFFF">
			<td>Do you want to allow users to submit new questions to the FAQ?</td>
			<td><input type="radio" name="usersubmit" value="0"> No | <input name="usersubmit" type="radio" value="1" checked> Yes</td>
		</tr>
		<tr bordercolor="#FFFFFF">
			<td>Do you want to be emailed when a user submits a new question?</td>
			<td><input type="radio" name="emailonusersubmit" value="0"> No | <input name="emailonusersubmit" type="radio" value="1" checked> Yes</td>
		</tr>
		<tr bordercolor="#FFFFFF">
			<th colspan="2">Other Options</th>
		</tr>
		<tr bordercolor="#FFFFFF">
			<td>Do you want to show view counts on each question?</td>
			<td><input type="radio" name="showviewcounts" value="0"> No | <input name="showviewcounts" type="radio" value="1" checked> Yes</td>
		</tr>
		<tr bordercolor="#FFFFFF">
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr bordercolor="#FFFFFF">
			<td colspan="2" align="center" valign="middle"><input type="submit" name="Submit" value="Update my CascadianFAQ!"></td>
		</tr>
		</table>
		</form>
	<?php
	}
	//Otherwise perform the upgrade and let the user know.
	else {
		// Move post variables to general ones for those with globals off
		$mostrecent = $_POST['mostrecent'];
		$nummostrecent = $_POST['nummostrecent'];
		$mostpopular = $_POST['mostpopular'];
		$nummostpopular = $_POST['nummostpopular'];
		$usersubmit = $_POST['usersubmit'];
		$emailonusersubmit = $_POST['emailonusersubmit'];
		$showviewcounts = $_POST['showviewcounts'];

		// Check config.php permissions.  If can't write to it, throw error.
		if (!is_writable("config.php"))
			die("Your configuration file is not writable.  Please fix the permissions and try again.");
			
		echo("Configuration file is writable.  Updating Configuration file...<BR>");
		
		// Open the config file
		$configfile = fopen("config.php", "w");
		
		// Store new lines in one nice variable
		$newoptions = "<?php\n";
		$newoptions .= "/*\n";
		$newoptions .= "	CascadianFAQ v4.1 - Last Updated: November 2003\n";
		$newoptions .= "	Summer S. Wilson, Eclectic Designs, http://eclectic-designs.com\n";
		$newoptions .= "	cfaq@eclectic-designs.com\n";
		$newoptions .= "\n";
		$newoptions .= "	This file is where you customize CascadianFAQ to run on your server and to\n";
		$newoptions .= "	your specifications.  By using the configuration file, you won't have to\n";
		$newoptions .= "	edit the actual PHP code (unless you want to totally change some aspect\n";
		$newoptions .= "	of the system).\n";
		$newoptions .= "*/\n";
		$newoptions .= "\n";
		$newoptions .= "\$dbhost = \"$dbhost\"; // Host of your database server, usually localhost\n";
		$newoptions .= "\$dbusername = \"$dbusername\"; // A valid username for logging into the database\n";
		$newoptions .= "\$dbpassword = \"$dbpassword\"; // The password that goes with the username above\n";
		$newoptions .= "\$dbname = \"$dbname\"; //The name of the database to use\n";
		$newoptions .= "\$dbtype = \"mySQL\"; // Type of database (available choices: PostgreSQL or mySQL)\n";
		$newoptions .= "\n";
		$newoptions .= "\$faqname = \"$faqname\"; // Name of the FAQ (usually Some Sitename's FAQ)\n";
		$newoptions .= "\$adminname = \"$adminname\"; // Name of the FAQ administrator\n";
		$newoptions .= "\$adminemail = \"$adminemail\"; //Email address of FAQ administrator\n";
		$newoptions .= "\$background = \"$background\"; // Background color of site, used for tables in admin area\n";
		$newoptions .= "\n";
		$newoptions .= "// Set to 1 to display the number of questions in a category, 0 to not show it\n";
		$newoptions .= "\$showcounts = $showcounts; \n";
		$newoptions .= "\n";
		$newoptions .= "// Set to 1 to display the times a question has been viewed, 0 to not show it\n";
		$newoptions .= "\$showviewcounts = $showviewcounts; \n";
		$newoptions .= "\n";
		$newoptions .= "// Set to 1 to display category descriptions, 0 to turn them off\n";
		$newoptions .= "\$usedescripts = $usedescripts; \n";
		$newoptions .= "\n";
		$newoptions .= "// Set to 1 to have level navigation appear at the top of each page, 0 to turn it off; Goes 3 levels deep\n";
		$newoptions .= "\$topmenu = $topmenu; \n";
		$newoptions .= "\n";
		$newoptions .= "// Set to 1 to show a credit line for my work, 0 to hide the credit in the comments\n";
		$newoptions .= "\$givecredit = $givecredit;\n";
		$newoptions .= "\n";
		$newoptions .= "// An intro message to appear on the first page of the FAQ.  HTML is allowed, but if you\n";
		$newoptions .= "// use quotes, be sure to escape them with a / first.\n";
		$newoptions .= "\$intro = \"$intro\";\n";
		$newoptions .= "\n";
		$newoptions .= "// You should only change this if you renamed the index.php file\n";
		$newoptions .= "\$cfaqindex = \"$cfaqindex\";\n";
		$newoptions .= "\n";
		$newoptions .= "// More options\n";
		$newoptions .= "\$mostrecent = $mostrecent; // Set to 1 to turn the most recent list on, 0 to turn it off.\n";
		$newoptions .= "\$nummostrecent = $nummostrecent; // How many questions should appear in the most recent list.\n";
		$newoptions .= "\n";
		$newoptions .= "\$mostpopular = $mostpopular; // Set to 1 to turn the most popular list on, 0 to turn it off.\n";
		$newoptions .= "\$nummostpopular = $nummostpopular; // How many questions should appear in the most popular list\n";
		$newoptions .= "\n";
		$newoptions .= "\$usersubmit = $usersubmit; // Set to 1 to allow users to submit new questions, 0 to not allow it.\n";
		$newoptions .= "\$emailonusersubmit = $emailonusersubmit; // Set to 1 to be emailed when new questions are submitted, 0 to turn it off\n";
		$newoptions .= "\n";
		$newoptions .= "// Globalizing variables so functions can access them\n";
		$newoptions .= "global \$dbhost, \$dbusername, \$dbpassword, \$dbname\n";
		$newoptions .= "?>";
		
		// Overwrite config file with new one
		fwrite($configfile, $newoptions);
		
		// Close the file and let the user know it's done
		fclose ($configfile);
		
		// Let the user know what's going on, then start database updates
		echo("Configuration file updated...<BR>");
		echo("Updating database...<BR>");
		
		// Alter admin table to add access level rights
		$query = "ALTER TABLE cfaq_admin ADD COLUMN accesslevel INT";
		$update  = mysql_query( $query, $link ) or die("Error adding the accesslevel field to the cfaq_admin table: ".mysql_error());

		// Alter admin table to add access level rights
		$query = "UPDATE cfaq_admin SET accesslevel = 1";
		$update  = mysql_query( $query, $link ) or die("Error setting accesslevels for current admins: ".mysql_error());
		
		echo("cfaq_admin table updated in the database.<BR>");
		
		// Create admin to cat table
		$query = "CREATE TABLE cfaq_admintocats (connectionid int4 PRIMARY KEY AUTO_INCREMENT, username varchar(15), catid INT);";
		$update  = mysql_query( $query, $link ) or die("Error creating cfaq_admintocats table: ".mysql_error());
		
		echo("cfaq_admintocats table added to the database.<BR>");
		
		// Alter table in database to add two new columns
		$query = "ALTER TABLE cfaq_qandas ADD COLUMN dateadded DATE";
		$update = mysql_query( $query, $link ) or die("Error adding the dateadded field to the cfaq_qandas table: ".mysql_error());
		$query = "ALTER TABLE cfaq_qandas ADD COLUMN viewed int4 DEFAULT 0";
		$update = mysql_query( $query, $link ) or die("Error adding the viewed field to the cfaq_qandas table: ".mysql_error());
	
		// Put some data in the current questions
		$today = date("Y-m-d");
		$query = "UPDATE cfaq_qandas SET dateadded = '$today', viewed = 0";
		$update = mysql_query( $query, $link ) or die("Error populating new fields in cfaq_qandas table: ".mysql_error());
		
		echo("cfaq_qandas table updated in the database.<BR>");
	
		// Add new table for user submissions
		$query = "CREATE TABLE cfaq_submissions (
				submissionid int4 PRIMARY KEY AUTO_INCREMENT,
				question varchar(255),
				datesubmitted date,
				suggestedcat int4 DEFAULT 0, 
				submittername varchar(100), 
				submitteremail varchar(255), 
				submitterip varchar(20) 
			) COMMENT='Questions submitted to the FAQ';";
		$update = mysql_query( $query, $link ) or die("Error creating the cfaq_submissions table: ".mysql_error());
	
		echo("cfaq_submissions table succesfully added to the database.<BR>");
	
		echo("<h4 align=center>CascadianFAQ upgrade to 4.0 now completed!</h4>");
		echo("<h4 align=center>Make sure you delete this file from your server and reset the config.php permissions to read only!</h4>");
	}
}
else {
	if (!is_writable("config.php"))
		die("Your configuration file is not writable.  Please fix the permissions and try again.");
		
	echo("Configuration file is writable.  Updating Configuration file...<BR>");
	// Open the config file
	$configfile = fopen("config.php", "w");
	
	// Store new lines in one nice variable
	$newoptions = "<?php\n";
	$newoptions .= "/*\n";
	$newoptions .= "	CascadianFAQ v4.1 - Last Updated: November 2003\n";
	$newoptions .= "	Summer S. Wilson, Eclectic Designs, http://eclectic-designs.com\n";
	$newoptions .= "	cfaq@eclectic-designs.com\n";
	$newoptions .= "\n";
	$newoptions .= "	This file is where you customize CascadianFAQ to run on your server and to\n";
	$newoptions .= "	your specifications.  By using the configuration file, you won't have to\n";
	$newoptions .= "	edit the actual PHP code (unless you want to totally change some aspect\n";
	$newoptions .= "	of the system).\n";
	$newoptions .= "*/\n";
	$newoptions .= "\n";
	$newoptions .= "\$dbhost = \"$dbhost\"; // Host of your database server, usually localhost\n";
	$newoptions .= "\$dbusername = \"$dbusername\"; // A valid username for logging into the database\n";
	$newoptions .= "\$dbpassword = \"$dbpassword\"; // The password that goes with the username above\n";
	$newoptions .= "\$dbname = \"$dbname\"; //The name of the database to use\n";
	$newoptions .= "\$dbtype = \"mySQL\"; // Type of database (available choices: PostgreSQL or mySQL)\n";
	$newoptions .= "\n";
	$newoptions .= "\$faqname = \"$faqname\"; // Name of the FAQ (usually Some Sitename's FAQ)\n";
	$newoptions .= "\$adminname = \"$adminname\"; // Name of the FAQ administrator\n";
	$newoptions .= "\$adminemail = \"$adminemail\"; //Email address of FAQ administrator\n";
	$newoptions .= "\$background = \"$background\"; // Background color of site, used for tables in admin area\n";
	$newoptions .= "\n";
	$newoptions .= "// Set to 1 to display the number of questions in a category, 0 to not show it\n";
	$newoptions .= "\$showcounts = $showcounts; \n";
	$newoptions .= "\n";
	$newoptions .= "// Set to 1 to display the times a question has been viewed, 0 to not show it\n";
	$newoptions .= "\$showviewcounts = $showviewcounts; \n";
	$newoptions .= "\n";
	$newoptions .= "// Set to 1 to display category descriptions, 0 to turn them off\n";
	$newoptions .= "\$usedescripts = $usedescripts; \n";
	$newoptions .= "\n";
	$newoptions .= "// Set to 1 to have level navigation appear at the top of each page, 0 to turn it off; Goes 3 levels deep\n";
	$newoptions .= "\$topmenu = $topmenu; \n";
	$newoptions .= "\n";
	$newoptions .= "// Set to 1 to show a credit line for my work, 0 to hide the credit in the comments\n";
	$newoptions .= "\$givecredit = $givecredit;\n";
	$newoptions .= "\n";
	$newoptions .= "// An intro message to appear on the first page of the FAQ.  HTML is allowed, but if you\n";
	$newoptions .= "// use quotes, be sure to escape them with a / first.\n";
	$newoptions .= "\$intro = \"$intro\";\n";
	$newoptions .= "\n";
	$newoptions .= "// You should only change this if you renamed the index.php file\n";
	$newoptions .= "\$cfaqindex = \"$cfaqindex\";\n";
	$newoptions .= "\n";
	$newoptions .= "// More options\n";
	$newoptions .= "\$mostrecent = $mostrecent; // Set to 1 to turn the most recent list on, 0 to turn it off.\n";
	$newoptions .= "\$nummostrecent = $nummostrecent; // How many questions should appear in the most recent list.\n";
	$newoptions .= "\n";
	$newoptions .= "\$mostpopular = $mostpopular; // Set to 1 to turn the most popular list on, 0 to turn it off.\n";
	$newoptions .= "\$nummostpopular = $nummostpopular; // How many questions should appear in the most popular list\n";
	$newoptions .= "\n";
	$newoptions .= "\$usersubmit = $usersubmit; // Set to 1 to allow users to submit new questions, 0 to not allow it.\n";
	$newoptions .= "\$emailonusersubmit = $emailonusersubmit; // Set to 1 to be emailed when new questions are submitted, 0 to turn it off\n";
	$newoptions .= "\n";
	$newoptions .= "\$currentversion = \"4.0 Beta\"; // Do not edit this line!\n";
	$newoptions .= "\n";
	$newoptions .= "// Globalizing variables so functions can access them\n";
	$newoptions .= "global \$dbhost, \$dbusername, \$dbpassword, \$dbname\n";
	$newoptions .= "?>";
	
	// Overwrite old config file with new one
	fwrite($configfile, $newoptions);
	
	// Close the file and let the user know it's done
	fclose ($configfile);
	
	// Let the user know what's going on, then start database updates
	echo("Configuration file updated...<BR>");
	echo("Updating database...<BR>");
	
	// Alter admin table to add access level rights
	$query = "ALTER TABLE cfaq_admin ADD COLUMN accesslevel INT";
	$update  = mysql_query( $query, $link ) or die("Error adding the accesslevel field to the cfaq_admin table: ".mysql_error());

	// Alter admin table to add access level rights
	$query = "UPDATE cfaq_admin SET accesslevel = 1";
	$update  = mysql_query( $query, $link ) or die("Error setting accesslevels for current admins: ".mysql_error());
	
	echo("cfaq_admin table updated in the database.<BR>");
	
	// Create admin to cat table
	$query = "CREATE TABLE cfaq_admintocats (connectionid int4 PRIMARY KEY AUTO_INCREMENT, username varchar(15), catid INT);";
	$update  = mysql_query( $query, $link ) or die("Error creating cfaq_admintocats table: ".mysql_error());
	
	echo("cfaq_admintocats table added to the database.<BR>");

	echo("<h4 align=center>CascadianFAQ upgrade to 4.0 now completed!</h4>");
	echo("<h4 align=center>Make sure you delete this file from your server and reset the config.php permissions to read only!</h4>");
}
	include ("footer.php");

?>
