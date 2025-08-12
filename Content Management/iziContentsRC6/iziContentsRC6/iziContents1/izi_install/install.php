<?php

/***************************************************************************
 The iziContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding iziContents and ezContents must remain intact on the scripts and in the HTML for the scripts.

 For more info on iziContents,
 visit http://www.izicontents.com*/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the License which can be found within the
 *   zipped package. Under the licence of GPL/GNU.
 *
 ***************************************************************************/

function install_iziContents()
{
	global $_POST;
	
	include_once("./dboperat.php");
	// connection to DB
	if ($_POST["CreateDatabase"] == 'yes') {
		$GLOBALS["dbConn"]->Connect($_POST["DBServer"],$_POST["DBLogin"],$_POST["DBPassword"])
			or die('<table border=0 cellpadding=8 cellspacing=8 width="100%"><tr><td align="center"><font color="yellow">Unable to connect to MySQL server.</font></td></tr></table>');
	} else {
		$GLOBALS["dbConn"]->Connect($_POST["DBServer"],$_POST["DBLogin"],$_POST["DBPassword"],$_POST["DBName"])
			or die('<table border=0 cellpadding=8 cellspacing=8 width="100%"><tr><td align="center"><font color="yellow">Unable to connect to database '.$_POST["DBName"].'.</font></td></tr></table>');
	}
	?>
	<form name="install" action="index.php" method="post" enctype="multipart/form-data">
	<table BORDER="0" height="100%" width="100%" cellpadding="4" cellspacing="2">
		<tr><td align="center" valign="middle">
			<?php
			$Status = True;
			blocktitle("NewInstall");
				if ($_POST["CreateDatabase"] == 'yes') {
				create_database($_POST["DBName"],$Status);
				}
			// installing core
			if ($Status) { populate_database($_POST["DBPrefix"],'core_tables',$Status); }
			if ($Status) { install_message('green','Core-Installation of database completed.'); }
			// installing contents
			if ($Status) { populate_database($_POST["DBPrefix"],'new_install',$Status); }
			//if ($Status) { specialcontents($_POST["DBPrefix"],$_POST["DBPrefix"],$Status); }
			if ($Status) { install_message('green','Installation of SQL-Insertsto database completed.'); }
			// updatin version
			if($Status){$Status = setVersion($_POST["DBPrefix"]);}
			?>
			</td>
		</tr>
		<tr><td align="center" valign="middle">
				<?php
				if ($Status) {
					blocktext('Basic-Installation has completed successfully.'); 
				} else {
					blocktext('Basic-Installation has failed.','red'); 
				}
				?>
			</td>
		</tr>

		<?php
		//	If the install/upgrade ran successfully, then we rename the installation script so that it can't be run again by accident
		//if ($Status) { $Status = rename_file("install.php","install.php.complete"); }
		?>

		<tr><td align="center" valign="middle">
				<input type="hidden" name="DBServer" value="<?= $_POST["DBServer"];?>">
				<input type="hidden" name="DBLogin" value="<?= $_POST["DBLogin"];?>">
				<input type="hidden" name="DBPassword" value="<?= $_POST["DBPassword"];?>">
				<input type="hidden" name="DBPrefix" value="<?= $_POST["DBPrefix"];?>">
				<input type="hidden" name="DBType" value="<?= $_POST["DBType"];?>">
				<input type="hidden" name="DBName" value="<?= $_POST["DBName"];?>">
				<input type="hidden" name="DBPersistent" value="<?= $_POST["DBPersistent"];?>">
				<input type="hidden" name="mode" value="languages">
			<input type="submit" class="ip_text" value="Configure Languages">
			</td>
		</tr>
	</table>
	</form>
	<?php
} // function install_iziContents()

function update_iziContents(){
global $_POST;

include_once("./dboperat.php");

?>
<table BORDER="0" height="100%" width="100%" cellpadding="4" cellspacing="2">
		<tr><td align="center" valign="middle">
<?php

$Status = true;
// connection to DB
$GLOBALS["dbConn"]->Connect($_POST["DBServer"],$_POST["DBLogin"],$_POST["DBPassword"],$_POST["DBName"])
or die('<table border=0 cellpadding=8 cellspacing=8 width="100%"><tr><td align="center"><font color="yellow">Unable to connect to database '.$_POST["DBName"].'.</font></td></tr></table>');

// checking if CreateDatabase is set to no
if ($_POST["CreateDatabase"] == 'yes') {
	if (!$GLOBALS["dbConn"]->SelectDB($_POST["DBName"])) {
	install_message('red','Cannot connect to database '.$_POST["DBName"]);
	$Status = False;
	}
	install_message('red','The database '.$_POST["DBName"].' already exists - setting "Create Database" to NO');
	$_POST["CreateDatabase"] = 'no';
}

//checking DB-version
if($Status){
	$version = getVersion($_POST["DBPrefix"]);
	if($version == '1.0 RC5'){ $version = '1RC5';}
		list($old_version, $old_release) = explode("RC",$version);
		list($new_version, $new_release) = explode("RC",$GLOBALS["version"]);
		
	if($old_version.$old_release < $new_version.$new_release){
		if($old_version == $new_version){
			while($old_release < $new_release){
			$old_release = $old_release + 1;
			populate_database($_POST["DBPrefix"],'updateto_'.$old_version.'RC'.$old_release,$Status); 
			install_message('green','Updating database to '.$old_version.'RC'.$old_release);
			}
		}
	} else{ install_message('red','Your database is already an '.$GLOBALS[$version] );
	$Status = false;
	}
	
	// updatin version
	if($Status){$Status = updateVersion($_POST["DBPrefix"]);
	install_message('green','Updated settingvalue to '.$GLOBALS["version"] );
	}
	//	If the install/upgrade ran successfully, then we rename the installation script so that it can't be run again by accident
	//	if ($Status) { $Status = rename_file("install.php","install.php.complete");}
}?>
	</td></tr>
	<tr><td align="center" valign="middle">
				<?php
				if ($Status) {
					blocktext('Update completed successfully.'); 
				} else {
					blocktext('Update has failed.','red'); 
				}
				?>
	</td></tr>
	<tr><td align="center" valign="middle">
				<input type="button" class="ip_text" value="Goto iziAdmin" onClick="location.href = '../admin/index.php'">
			</td>
		</tr>
	</table>
	<?php

}// end update_izicontents
?>
