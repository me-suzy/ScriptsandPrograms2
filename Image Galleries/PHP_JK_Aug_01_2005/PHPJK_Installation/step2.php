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
		Global $sOS;
		Global $sUseDB;
		Global $sDatabaseServer;
		Global $sDatabaseName;
		Global $sDatabaseLogin;
		Global $sDatabasePassword;
		Global $sTemplates;
		
		if ( Trim(Request("sAction")) == "step2" )
		{
			$DBConnection = DB_DBConnect($sDatabaseServer, $sDatabaseLogin, $sDatabasePassword);

			// Create all the databases, users and set the rights
			CreateTables($DBConnection);
	
			// Create the new entry in the DomainInfo table
			DB_Insert ("INSERT INTO DomainInfo (Description,Domain,Type) VALUES ('The first domain.', '" . $_SERVER["SERVER_NAME"] . "','')", $DBConnection);
			
			// Create the new entries in the Configurations table
			Defaults($DBConnection);
			
			header( 'location:step3.php' );
		}

		?>
		<form action="step2.php" method="post">
		<input type='hidden' name='sAction' value='step2'>
			We will now start to add data to the database...<br><br>
			<input type='submit' value=' Continue '>
		</form>
		<?php
	}
	//************************************************************************************
?>