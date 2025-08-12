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
 
 
 function read_modules()
{
	$modulecount = 0;
	$savedir = getcwd();
	chdir('../modules');
	if ($handle = @opendir('.')) {
		while ($filename = readdir($handle)) {
			if ((is_dir($filename)) && (!($filename == '..') && !($filename == '.') && !($filename == 'CVS'))) {
				$GLOBALS["modules"][] = $filename;
				$modulecount++;
			}
		}
		closedir($handle);
	}
	chdir($savedir);
	return $modulecount;
} // function read_modules()


function module_screen()
{
	global $_POST;

	$modulecount = read_modules();

	?>
	<form name="module_data" action="index.php" method="post" enctype="multipart/form-data">
	<table border=0>
		<tr><td align="center" valign="middle">
				<?php blocktitle("Modules"); ?>
			</td>
		</tr>
		<tr><td align="center" valign="middle">
				<?php blocktext('Please select the modules that you wish to install.'); ?>
			</td>
		</tr>
		<tr><td align="center" valign="middle">
				<table border="1" height="100%" width="45%" cellpadding="1" cellspacing="1">
					<tr><td align="center" valign="middle">
							<table border="0" height="100%" width="100%" cellpadding="0" cellspacing="2">
								<tr><td align="left"><b><u><?php basetext("Module"); ?><u></b></td>
									<td align="center"><b><u><?php basetext("Install"); ?><u></b></td>
								</tr>
								<?php
								for ($i=0; $i < $modulecount; $i++) {
									echo '<tr><td>';
									basetext(mouseover($GLOBALS["modules"][$i],$GLOBALS["modules"][$i],"Modules"));
									echo '</td><td align="center">';
									echo '<input type="checkbox" name="'.$GLOBALS["modules"][$i].'" value="Y" checked>';
									echo '</td></tr>';
								}
								?>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td align="center" valign="middle">
				<?php
				$checkref = '<a href="#" class="help_text" onClick="';
				for ($i=0; $i < $modulecount; $i++) {
					$checkref .= 'window.document.module_data.'.$GLOBALS["modules"][$i].'.checked=true;';
				}
				$checkref .= '" ';
				$checkref .= 'onMouseOver="window.document.helpform.helptext.value=\''.str_replace('\'','\\\'',str_replace(chr(10),'\n\r',$GLOBALS["Help"]["ModuleSelectAll"])).'\';"';
				$checkref .= 'onMouseOut="window.document.helpform.helptext.value=\''.str_replace('\'','\\\'',str_replace(chr(10),'\n\r',$GLOBALS["Help"]["Modules"])).'\'">';
				$checkref .= 'Check All</a>';
				$uncheckref = '<a href="#" class="help_text" onClick="';
				for ($i=0; $i < $modulecount; $i++) {
					$uncheckref .= 'window.document.module_data.'.$GLOBALS["modules"][$i].'.checked=false;';
				}
				$uncheckref .= '" ';
				$uncheckref .= 'onMouseOver="window.document.helpform.helptext.value=\''.str_replace('\'','\\\'',str_replace(chr(10),'\n\r',$GLOBALS["Help"]["ModuleSelectNone"])).'\';"';
				$uncheckref .= 'onMouseOut="window.document.helpform.helptext.value=\''.str_replace('\'','\\\'',str_replace(chr(10),'\n\r',$GLOBALS["Help"]["Modules"])).'\'">';
				$uncheckref .= 'Uncheck All</a>';
				basetext($checkref.'&nbsp;&nbsp;/&nbsp;&nbsp;'.$uncheckref);
				?>
		</tr>
		<tr><td align="center" valign="middle">
				<?php
				
				$savedir = getcwd();
				/*
				chdir('../languages');
				if ($handle = @opendir('.')) {
					while ($filename = readdir($handle)) {
						if ((is_dir($filename)) && (!($filename == '..') && !($filename == '.'))) {
							if ($_POST[$filename] == 'Y') {
								echo '<input type="hidden" name="'.$filename.'" value="'.$_POST[$filename].'">';
								$debugmsg = '<li>'.$filename;
								if ($filename == $_POST["defaultlanguage"]) { $debugmsg .= ' as default'; }
								if ($GLOBALS["DebugMode"]) { debug_msg($debugmsg,$GLOBALS["Titles"]["Languages"]); }
							}
						}
					}
					closedir($handle);
				}
				*/
				chdir($savedir);
				if ($GLOBALS["DebugMode"]) { debug_msg('</ul>',$GLOBALS["Titles"]["Languages"]); }
				?>
				<input type="hidden" name="DBServer" value="<?= $_POST["DBServer"];?>">
				<input type="hidden" name="DBLogin" value="<?= $_POST["DBLogin"];?>">
				<input type="hidden" name="DBPassword" value="<?= $_POST["DBPassword"];?>">
				<input type="hidden" name="DBPrefix" value="<?= $_POST["DBPrefix"];?>">
				<input type="hidden" name="DBType" value="<?= $_POST["DBType"];?>">
				<input type="hidden" name="DBName" value="<?= $_POST["DBName"];?>">
				<input type="hidden" name="DBPersistent" value="<?= $_POST["DBPersistent"];?>">
				<input type="hidden" name="defaultlanguage" value="<?php echo $_POST["defaultlanguage"]; ?>">
				<input type="hidden" name="mode" value="modules2">
				<input type="submit" class="ip_text" name="submit" value="Submit Module List">
			</td>
		</tr>
	</table>
	</form>
	<?php
} // function module_screen()


 function create_module($Module,$DBPrefix,&$Status)
{
	if ($GLOBALS["Log"] == 'Y') { debug_msg('****		Creating module'.$Module,$GLOBALS["Titles"]["InstallLog"]); }

	$Status = True;
	$fp = fopen('./'.$Module.'/install.sql', "r");
	if (!$fp) {
		install_message('orange','Unable to open '.$Module.' install.sql file');
		if ($GLOBALS["Log"] == 'Y') { debug_msg('****		Unable to create module',$GLOBALS["Titles"]["InstallLog"]); }
	} else {
		$file = fread($fp, $GLOBALS["MaxFileSize"]);
		$file = str_replace("\r", "", $file);
		$query = explode(";\n",$file);
		for ($i=0; $i < count($query) - 1; $i++) {
			if ($Status) {
				$sqlQuery = trim($query[$i]);
				$workquery = explode("\n",$sqlQuery);
				for ($j=0; $j < count($workquery) - 1; $j++) {
					$test_workquery = trim($workquery[$j]);
					if (substr($test_workquery,0,1) == '#') { $workquery[$j] = ''; }
				}
				$sqlQuery = implode("",$workquery);
				if ((substr($sqlQuery,0,4) == 'DROP') || (substr($sqlQuery,0,6) == 'CREATE') || (substr($sqlQuery,0,6) == 'UPDATE') || (substr($sqlQuery,0,6) == 'INSERT')) {
					if (substr($sqlQuery,0,4) == 'DROP') {
						$sqlQuery = str_replace('DROP TABLE IF EXISTS ','DROP TABLE IF EXISTS '.$DBPrefix, $sqlQuery);
					}
					if (substr($sqlQuery,0,6) == 'CREATE') {
						$sqlQuery = str_replace('CREATE TABLE ','CREATE TABLE '.$DBPrefix, $sqlQuery);
					}
					if (substr($sqlQuery,0,6) == 'UPDATE') {
						$sqlQuery = str_replace('UPDATE ','UPDATE '.$DBPrefix, $sqlQuery);
					}
					if (substr($sqlQuery,0,6) == 'INSERT') {
						$sqlQuery = str_replace('INSERT INTO ','INSERT INTO '.$DBPrefix, $sqlQuery);
					}
					sqlLog($sqlQuery);
					$result = dbExecute($sqlQuery,true);
					if (!$result) { $Status = False; }
				}
			}
		}
		$fp = fclose($fp);
	}
	if (!($Status)) { install_message('red','Error installing module '.$Module); }
} // function create_module()


function create_modules($DBPrefix,&$Status)
{
	global $_POST;
	
	include("dboperat.php");
	// connection to DB
	$GLOBALS["dbConn"]->Connect($_POST["DBServer"],$_POST["DBLogin"],$_POST["DBPassword"],$_POST["DBName"])
	or die('<table border=0 cellpadding=8 cellspacing=8 width="100%"><tr><td align="center"><font color="yellow">Unable to connect to database '.$_POST["DBName"].'.</font></td></tr></table>');
		?>
	<form name="mod2" action="index.php" method="post" enctype="multipart/form-data">
	<table border=0 align="center">
		<tr><td align="center" valign="middle">
				<?php blocktitle("Languages"); ?>
			</td>
		</tr>
		<tr><td align="center" valign="middle"><hr></td>
		</tr>
		<tr><td align="center" valign="middle">
	<?php

	if ($GLOBALS["Log"] == 'Y') { debug_msg('****	Creating Modules',$GLOBALS["Titles"]["InstallLog"]); }

	$Status = True;
	$savedir = getcwd();
	chdir('../modules');
	if ($handle = @opendir('.')) {
		while (($Status) && ($filename = readdir($handle))) {
			if ((is_dir($filename)) && (!($filename == '..') && !($filename == '.'))) {
				if ($_POST[$filename] == 'Y') {
					create_module($filename,$DBPrefix,$Status);
					specialcontents($_POST["DBPrefix"]);
					install_message('green','Installing Module - '.$filename);
				}
			}
		}
		closedir($handle);
	}
	install_message('green','Module installation completed successfully');
	chdir($savedir);
	//if ($Status) { $Status = rename_file("modules.php","modules.php.complete"); }	
	?>
		</td></tr>
		<tr><td align="center" valign="middle">
			<input type="hidden" name="DBServer" value="<?= $_POST["DBServer"];?>">
			<input type="hidden" name="DBLogin" value="<?= $_POST["DBLogin"];?>">
			<input type="hidden" name="DBPassword" value="<?= $_POST["DBPassword"];?>">
			<input type="hidden" name="DBPrefix" value="<?= $_POST["DBPrefix"];?>">
			<input type="hidden" name="DBType" value="<?= $_POST["DBType"];?>">
			<input type="hidden" name="DBName" value="<?= $_POST["DBName"];?>">
			<input type="hidden" name="DBPersistent" value="<?= $_POST["DBPersistent"];?>">
			<input type="hidden" name="defaultlanguage" value="<?php echo $_POST["defaultlanguage"]; ?>">
			<input type="hidden" name="mode" value="writeconfig">
			<input type="submit" class="ip_text" name="submit" value="Write Configuration File" >
			</td>
		</tr>
	</table>
	</form>
	<?php
	return $Status;
} // function create_modules()

function specialcontents($DBPrefix)
{
	if ($DBPrefix != '') {
		//  Upgrade the specialcontents table (use prefix if appropriate)
		$sqlString = "UPDATE ".$DBPrefix."specialcontents SET scuseprefix='Y';";
		sqlLog($sqlString);
		$uresult = dbExecute($sqlString,true);
		if (!$uresult) { $Status = False; }
	}
} // function specialcontents()

 ?>