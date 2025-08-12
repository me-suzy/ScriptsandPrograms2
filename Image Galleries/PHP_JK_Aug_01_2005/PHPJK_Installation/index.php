<?php
	Require("includes.php");
	Require("../Includes/i_Includes.php");
	Require("SQL/index.php");

	OpenPage();
	Main();
	ClosePage();

	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		?>
		Please verify that you have performed all the tasks listed below before proceeding.
		<br><br>
		<ol>
			<li><b>File Upload.</b> You have uploaded the unzipped PHP JackKnife folder contents to your Web server.
			<br><br>
			<li><b>Folder Permissions.</b> You have assigned IUSR_computername folder permissions as follows:<br>
				(a) 'Read/Write' permissions for the Configurations folder.<br>
				(b) 'Read/Write/Delete' permissions for the Galleries folder.<br>
			<br>
			<li><b>Database.</b> Please make sure you have created a database and database user for your PHP JackKnife installation.
				You will need to know your database server name, the name of the database you created, and the username and password
				of the user assigned to that database.
		</ol>
		<input type='button' value=' Continue to Step 1 ' onClick='document.location="step1.php";'>
		<?php
	}
	//************************************************************************************
?>