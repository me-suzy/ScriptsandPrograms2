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
 
  function database_screen()
{
	global $_POST;

	?>
	<form name="db_data" action="index.php" method="post" enctype="multipart/form-data">
	<table border=0>
		<tr><td align="center" valign="middle">
				<?php blocktitle("Database"); ?>
			</td>
		</tr>
		<tr><td align="center" valign="middle">
				<?php blocktext('Please enter the following details about your SQL server.'); ?>
			</td>
		</tr>
		<tr><td align="center" valign="middle">
				<table border="1" height="100%" width="95%" cellpadding="1" cellspacing="1">
					<tr><td align="center" valign="middle">
							<table border="0" height="100%" width="100%" cellpadding="4" cellspacing="4">
								<tr><td align="right" valign="top">
									<?php basetext(mouseover('Manual installation :', 'Manualinstall', 'Database')); ?>
								</td>
								<td><input type="checkbox" name="manualinstall" value="true"/></td>
								</tr>
								<tr><td align="right" valign="top">
										<?php basetext(mouseover('Database driver :','DBDriver','Database')); ?>
									</td>
									<td valign="top">
										<select class="ip_text" size="1" name="DBType">
											<option value="mysql">MySQL</option>
											<option value="mysqlt">MySQL Transactional</option>
											<?php /*
											<option value="postgres">PostgresSQL
											<option value="sqlite">Sqlite
											<option value="ibase">Firebird
										    */?>
										</select>
									</td>
								</tr>
								<tr><td align="right" valign="top">
										<?php basetext(mouseover('IP Address of the database server :','DBAddress','Database')); ?>
									</td>
									<td valign="top">
										<input class="ip_text" type="text" size="15" name="DBServer" value="localhost">
									</td>
								</tr>
								<tr><td align="right" valign="top">
										<?php basetext(mouseover('Database name :','DBName','Database')); ?>
									</td>
									<td valign="top">
										<input class="ip_text" type="text" size="20" name="DBName" value="izicontents">
									</td>
								</tr>
								<tr><td align="right" valign="top">
										<?php basetext(mouseover('Database login ID :','DBLogin','Database')); ?>
									</td>
									<td valign="top">
										<input class="ip_text" type="text" size="20" name="DBLogin">
									</td>
								</tr>
								<tr><td align="right" valign="top">
										<?php basetext(mouseover('Database password :','DBPassword','Database')); ?>
									</td>
									<td valign="top">
										<input class="ip_text" type="password" size="20" name="DBPassword">
									</td>
								</tr>
								<tr><td align="right" valign="top">
										<?php basetext(mouseover('Table prefix :','DBPrefix','Database')); ?>
									</td>
									<td valign="top">
										<input class="ip_text" type="text" size="8" name="DBPrefix">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td align="center" valign="middle">
				<input type="hidden" name="mode" value="database2">
				<input type="submit" class="ip_text" name="submit" value="Submit Database Login">
			</td>
		</tr>
	</table>
	</form>
	<?php
} // function database_screen()



function database2_screen(){
global $_POST;

include_once("dboperat.php");

// beginning of form
?>
<form name="db_data2" action="index.php" method="post" enctype="multipart/form-data">
	<table border=0 align="center">
		<tr><td align="center" valign="middle">
				<?php blocktitle("Database"); ?>
			</td>
		</tr>
		<tr><td align="center" valign="middle"><hr></td>
		</tr>
		<tr><td align="center" valign="middle">
		<?php
		// checking version
		//check connection
		if(!$GLOBALS["dbConn"]->Connect($_POST["DBServer"],$_POST["DBLogin"],$_POST["DBPassword"])){
			install_message('red','Cannot connect to database '.$_POST["DBName"].' - check Username and Passwd"');
			exit;
		} 
		elseif(!$GLOBALS["dbConn"]->SelectDB($_POST["DBName"])){
			install_message('red','Cannot connect to database '.$_POST["DBName"].' - check Username and Passwd"');
			$db_create = 'checked';
			$chk_new = 'checked';
		}
		else {
		$db_nocreate = 'checked';
		$chk_new = '';
		$chk_update = '';
	
			$version = getVersion($_POST["DBPrefix"]);
			if($version){
				if($version == '1.0 RC5'){ $version = '1RC5';}
				if($version < $GLOBALS["version"]){
					$chk_update = 'checked';
					install_message('green','Found iziContents '.$version.' - choosing "Update" to update iziContents to Version '.$GLOBALS["version"]);
					
				}
				elseif($version >= $GLOBALS["version"]){
					$chk_new = 'checked';
					install_message('red','iziContents '.$version.' already installed - choosing "New Installation" to overwrite database');
					
				}
			} else {
			$chk_new = "checked";
			install_message('orange','There seems no older version of iziContents to be installed on this database - please use "New Installation"');
			}
		}
		?>
		
	<table border="1" height="100%" width="95%" cellpadding="1" cellspacing="1" align="center">
		<tr><TD>
		<table border="0" align="center">
			
<tr><td align="right" valign="top">
										<?php basetext(mouseover('Type of Install :','InstallType','Database')); ?>
									</td>
									<td valign="top">
										<input type="radio" name="InstallType" value='newinstall' <?= $chk_new;?>><?php basetext(mouseover('New Installation','InstallNew','Database')); ?><br />
										<input type="radio" name="InstallType" value='update' <?= $chk_update;?> ><?php basetext(mouseover('Update','InstallUpdate','Database')); ?>
									</td>
								</tr>
								<tr><td align="right" valign="top">
										<?php basetext(mouseover('Create Database :','CreateDatabase','Database')); ?>
									</td>
									<td valign="top">
										<input type="radio" name="CreateDatabase" value='no' <?= $db_nocreate; ?> ><?php basetext(mouseover('No','OldDatabase','Database')); ?><br />
										<input type="radio" name="CreateDatabase" value='yes' <?= $db_create; ?>><?php basetext(mouseover('Yes','NewDatabase','Database')); ?>
									</td>
								</tr>
								<tr><td align="right" valign="top">
										<?php basetext(mouseover('Use persistent connections :','DBPersistent','Database')); ?>
									</td>
									<td valign="top">
										<select class="ip_text" size="1" name="DBPersistent">
											<option value="N">No</option>
											<option value="Y">Yes</option>
										</select>
									</td>
								</tr>
								<tr><td align="right" valign="top">
										<?php basetext(mouseover('Log database updates during install/upgrade :','InstallLog','Database')); ?>
									</td>
									<td valign="top">
										<input type="radio" name="DBLogUpdates" value='N' checked><?php basetext(mouseover('No','LogNo','Database')); ?>
										<input type="radio" name="DBLogUpdates" value='Y'><?php basetext(mouseover('Yes','LogYes','Database')); ?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><TD align="center" valign="middle">
			<input type="hidden" name="DBServer" value="<?= $_POST["DBServer"];?>">
			<input type="hidden" name="DBLogin" value="<?= $_POST["DBLogin"];?>">
			<input type="hidden" name="DBPassword" value="<?= $_POST["DBPassword"];?>">
			<input type="hidden" name="DBPrefix" value="<?= $_POST["DBPrefix"];?>">
			<input type="hidden" name="DBType" value="<?= $_POST["DBType"];?>">
			<input type="hidden" name="DBName" value="<?= $_POST["DBName"];?>">
			<input type="hidden" name="mode" value="install_update">
			<input type="submit" class="ip_text" name="submit" value="Submit Database Details">
		</TD></tr>
	<tr><td align="center" valign="middle">
				<?php blocktext('This may take a few minutes.'); ?>
			</td>
		</tr>	
	</table>
</form>
<?php
}// function database2_screen
?>