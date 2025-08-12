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
 function read_languages()
{
	$languagecount = 0;
	$savedir = getcwd();
	chdir('../languages');
	if ($handle = @opendir('.')) {
		while ($filename = readdir($handle)) {
			if ((is_dir($filename)) && (!($filename == '..') && !($filename == '.') && !($filename == 'CVS'))) {
				$GLOBALS["languages"][] = $filename;
				$languagecount++;
			}
		}
		closedir($handle);
	}
	chdir($savedir);
	return $languagecount;
} // function read_languages()


function language_screen()
{
	global $_POST;
	$languagecount = read_languages();

	?>
	<form name="language_data" action="index.php" method="post" enctype="multipart/form-data">
	<table border=0>
		<tr><td align="center" valign="middle">
				<?php blocktitle("Languages"); ?>
			</td>
		</tr>
		<tr><td align="center" valign="middle">
				<?php blocktext('Please select the languages that you wish to install.'); ?>
			</td>
		</tr>
		<tr><td align="center" valign="middle">
				<table border="1" height="100%" width="60%" cellpadding="1" cellspacing="1">
					<tr><td align="center" valign="middle">
							<table border="0" height="100%" width="100%" cellpadding="0" cellspacing="2">
								<tr><td align="right"><b><u><?php basetext("Language"); ?><u></b></td>
									<td align="center"><b><u><?php basetext("Install"); ?><u></b></td>
									<td align="left"><b><u><?php basetext("Default"); ?><u></b></td>
								</tr>
								<?php
								for ($i=0; $i < $languagecount; $i++) {
									$language = strtolower($GLOBALS["languages"][$i]);
									echo '<tr><td align="right">';
									basetext(mouseover($language,$language,"Languages"));
									echo '</td><td align="center">';
									if (strtolower($GLOBALS["Available"][$language]) == 'yes') {
										echo '<input type="checkbox" name="'.$language.'" value="Y" checked>';
									}
									echo '</td><td align="left">';
									if (strtolower($GLOBALS["Available"][$language]) == 'yes') {
										echo '<input type="radio" name="defaultlanguage" value="'.$language.'"';
										if ($language == 'en') { echo " checked"; }
										echo '>';
									}
									echo '</td><td align="left">';
									if (strtolower($GLOBALS["Available"][$language]) != 'yes') { basetext(mouseover("not yet available",$language,"Languages")); }
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
				for ($i=0; $i < $languagecount; $i++) {
					$j = $GLOBALS["languages"][$i];
					if (strtolower($GLOBALS["Available"][$j]) == 'yes') { $checkref .= 'window.document.language_data.'.$GLOBALS["languages"][$i].'.checked=true;'; }
				}
				$checkref .= '" ';
				$checkref .= 'onMouseOver="window.document.helpform.helptext.value=\''.str_replace('\'','\\\'',str_replace(chr(10),'\n\r',$GLOBALS["Help"]["LanguageSelectAll"])).'\';"';
				$checkref .= 'onMouseOut="window.document.helpform.helptext.value=\''.str_replace('\'','\\\'',str_replace(chr(10),'\n\r',$GLOBALS["Help"]["Languages"])).'\'">';
				$checkref .= 'Check All</a>';
				$uncheckref = '<a href="#" class="help_text" onClick="';
				for ($i=0; $i < $languagecount; $i++) {
					$j = $GLOBALS["languages"][$i];
					if (strtolower($GLOBALS["Available"][$j]) == 'yes') { $uncheckref .= 'window.document.language_data.'.$GLOBALS["languages"][$i].'.checked=false;'; }
				}
				$uncheckref .= '" ';
				$uncheckref .= 'onMouseOver="window.document.helpform.helptext.value=\''.str_replace('\'','\\\'',str_replace(chr(10),'\n\r',$GLOBALS["Help"]["LanguageSelectNone"])).'\';"';
				$uncheckref .= 'onMouseOut="window.document.helpform.helptext.value=\''.str_replace('\'','\\\'',str_replace(chr(10),'\n\r',$GLOBALS["Help"]["Languages"])).'\'">';
				$uncheckref .= 'Uncheck All</a>';
				basetext($checkref.'&nbsp;&nbsp;/&nbsp;&nbsp;'.$uncheckref);
				?>
		</tr>
		<tr><td align="center" valign="middle">
				<input type="hidden" name="DBServer" value="<?= $_POST["DBServer"];?>">
			<input type="hidden" name="DBLogin" value="<?= $_POST["DBLogin"];?>">
			<input type="hidden" name="DBPassword" value="<?= $_POST["DBPassword"];?>">
			<input type="hidden" name="DBPrefix" value="<?= $_POST["DBPrefix"];?>">
			<input type="hidden" name="DBType" value="<?= $_POST["DBType"];?>">
			<input type="hidden" name="DBName" value="<?= $_POST["DBName"];?>">
			<input type="hidden" name="DBPersistent" value="<?= $_POST["DBPersistent"];?>">
			<input type="hidden" name="mode" value="languages2">
			<input type="submit" class="ip_text" name="submit" value="Submit Language List" >
			</td>
		</tr>
	</table>
	</form>
	<?php
} // function language_screen()

function setusergroup_languages($Language,$DBPrefix,$text,$keyref,&$Status)
{
	$sqlQuery = "INSERT INTO ".$DBPrefix."usergroups (usergroupdesc, usergroupname, language, authorid) VALUES ('".$text."', '".$keyref."', '".$Language."', 1)";
	sqlLog($sqlQuery);
	$result = dbExecute($sqlQuery,true);
	if (!$result) { $Status = False; }
} // function setusergroup_languages()


function settagcategory_languages($Language,$DBPrefix,$text,$keyref,&$Status)
{
	$sqlQuery = "INSERT INTO ".$DBPrefix."tagcategories (catdesc, catname, language, authorid) VALUES ('".$text."', '".$keyref."', '".$Language."', 1)";
	sqlLog($sqlQuery);
	$result = dbExecute($sqlQuery,true);
	if (!$result) { $Status = False; }
} // function settagcategory_languages()


function create_language($Language,$DBPrefix,&$Status)
{
	if ($GLOBALS["Log"] == 'Y') { debug_msg('****		Creating language'.$Language,$GLOBALS["Titles"]["InstallLog"]); }

	$Status = True;
	$fp = fopen('./'.$Language.'/install.sql', "r");
	if (!$fp) {
		install_message('orange','Unable to open '.$Language.' install.sql file');
		if ($GLOBALS["Log"] == 'Y') { debug_msg('****		Unable to create language',$GLOBALS["Titles"]["InstallLog"]); }
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
	if ($Status) { setusergroup_languages($Language,$DBPrefix,'Administrators','administrator',$Status); }
	if ($Status) { setusergroup_languages($Language,$DBPrefix,'Contributors','contributor',$Status); }
	if ($Status) { setusergroup_languages($Language,$DBPrefix,'Translators','translator',$Status); }
	if ($Status) { setusergroup_languages($Language,$DBPrefix,'Members','member',$Status); }
	if ($Status) { setusergroup_languages($Language,$DBPrefix,'Probationary Members','probationer',$Status); }

	if ($Status) { settagcategory_languages($Language,$DBPrefix,'Table','table',$Status); }
	if ($Status) { settagcategory_languages($Language,$DBPrefix,'Titles','title',$Status); }
	if ($Status) { settagcategory_languages($Language,$DBPrefix,'List','list',$Status); }
	if ($Status) { settagcategory_languages($Language,$DBPrefix,'Text Formatting','text',$Status); }
	if ($Status) { settagcategory_languages($Language,$DBPrefix,'Miscellaneous','other',$Status); }

	if (!($Status)) { install_message('red','Error installing language '.$Language); }
} // function create_language()


function create_languages($DBPrefix,&$Status)
{
	$Status = true;
	global $_POST;
	include("dboperat.php");
	// connection to DB
	$GLOBALS["dbConn"]->Connect($_POST["DBServer"],$_POST["DBLogin"],$_POST["DBPassword"],$_POST["DBName"])
	or die('<table border=0 cellpadding=8 cellspacing=8 width="100%"><tr><td align="center"><font color="yellow">Unable to connect to database '.$_POST["DBName"].'.</font></td></tr></table>');
		?>
<form name="lang2" action="index.php" method="post" enctype="multipart/form-data">
	<table border=0 align="center">
		<tr><td align="center" valign="middle">
				<?php blocktitle("Languages"); ?>
			</td>
		</tr>
		<tr><td align="center" valign="middle"><hr></td>
		</tr>
		<tr><td align="center" valign="middle">
	<?php
	if ($GLOBALS["Log"] == 'Y') { debug_msg('****	Creating Languages',$GLOBALS["Titles"]["InstallLog"]); }

	$Status = True;
	$LanguageCount = 0;
	$savedir = getcwd();
	chdir('../languages');
	if ($handle = @opendir('.')) {
		while (($Status) && ($filename = readdir($handle))) {
			if ((is_dir($filename)) && (!($filename == '..') && !($filename == '.'))) {
				if ($_POST[$filename] == 'Y') {
					create_language($filename,$DBPrefix,$Status);
					install_message('green','Installing language - '.$filename);
					$LanguageCount++;
				}
			}
		}
		closedir($handle);
	}
	chdir($savedir);

	$sqlQuery = "UPDATE ".$DBPrefix."settings SET settingvalue='".$_POST["defaultlanguage"]."' WHERE settingname='default_language'";
	sqlLog($sqlQuery);
	$result = dbExecute($sqlQuery,true);
	if (!$result) { $Status = False;
			install_message('red','Could not set default Language to '.$_POST["defaultlanguage"]);
	} else {
			install_message('green','Setting default Language to '.$_POST["defaultlanguage"]);
		if ($LanguageCount > 1) {
			$sqlQuery = "UPDATE ".$DBPrefix."settings SET settingvalue='Y' WHERE settingname='multilanguage'";
			sqlLog($sqlQuery);
			$result = dbExecute($sqlQuery,true);
			if (!$result) { $Status = False; 
			install_message('red','Could not set Multilinguality');}
			else{install_message('green','Enabling Multilinguality');}
		}
			// if ($Status) { $Status = rename_file("languages.php","languages.php.complete"); }	
	}
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
			<input type="hidden" name="mode" value="modules">
			<input type="submit" class="ip_text" name="submit" value="Configure Modules" >
			</td>
		</tr>
	</table>
	</form>
	<?php
	return $Status;
} // function create_languages()

?>