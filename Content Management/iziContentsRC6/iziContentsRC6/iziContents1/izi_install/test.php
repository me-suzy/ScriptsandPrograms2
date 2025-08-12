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
 function test_screen()
{
	global $_SERVER;

	$safe_mode = (bool) ini_get("safe_mode");
	$open_basedir = ini_get("open_basedir");
	$file_uploads = (bool) ini_get("file_uploads");
	$register_globals = (bool) ini_get("register_globals");

	$Status = True;
	?>
	<table border="0" height="100%" width="100%" cellpadding="1" cellspacing="1">
		<tr><td align="center" valign="middle">
				<?php blocktitle("Main"); ?>
			</td>
		</tr>
		<tr><td align="center" valign="middle">
				<?php blocktext('This screen tests your configuration against the requirements to run iziContents.'); ?>
			</td>
		</tr>
		<tr><td align="center" valign="middle">
				<table border="1" height="100%" width="95%" cellpadding="1" cellspacing="1">
					<tr><td align="center" valign="middle">
							<table border="0" height="100%" width="100%" cellpadding="1" cellspacing="2">
								<tr><td align="right" width="45%" valign="top">
										<?php basetext(mouseover('PHP Version 4.0.4 or greater :','PHPVersion','Test')); ?>
									</td>
									<td valign="top" width="55%">
										<?php
										if (phpversion() >= '4.0.4') { $text = $GLOBALS["Checks"]["green"];
										} else {
											$text = $GLOBALS["Checks"]["red"];
											$Status = False;
										}
										$text .= 'You are running version '.phpversion(). ' of PHP';
										basetext($text);
										if ($GLOBALS["DebugMode"]) { debug_msg($text,$GLOBALS["Titles"]["InstallLog"]); }
										?>
									</td>
								</tr>
								<tr><td align="right" valign="top">
										<?php basetext(mouseover('Register Globals :<br />(strongly advised \'Off\')','RegisterGlobals','Test')); ?>
									</td>
									<td valign="top">
										<?php
										if (!$safe_mode) { $text = $GLOBALS["Checks"]["green"].'Register Globals is \'Off\'';
										} else {
											$text = $GLOBALS["Checks"]["red"].'Register Globals is \'On\'<br />This is a serious security flaw in your PHP configuration';
										}
										basetext($text);
										if ($GLOBALS["DebugMode"]) { debug_msg($text,$GLOBALS["Titles"]["InstallLog"]); }
										?>
									</td>
								</tr>
								<tr><td align="right" valign="top">
										<?php basetext(mouseover('GD graphics extension for PHP :<br />(recommended)','GDGraphics','Test')); ?>
									</td>
									<td valign="top">
										<?php
										if (extension_loaded('gd')) {
											if (ImageTypes() & IMG_GIF) { $text = $GLOBALS["Checks"]["green"].'GD is available';
											} else { $text = $GLOBALS["Checks"]["green"].'GD is available<br />but does not support .gif images'; }
										} else { $text = $GLOBALS["Checks"]["orange"].'GD is NOT available'; }
										basetext($text);
										if ($GLOBALS["DebugMode"]) { debug_msg($text,$GLOBALS["Titles"]["InstallLog"]); }
										?>
									</td>
								</tr>
								<tr><td align="right" valign="top">
										<?php basetext(mouseover('Multi-byte (mbstring) extension for PHP :','mbstring','Test')); ?>
									</td>
									<td valign="top">
										<?php
										if (extension_loaded('mbstring')) {
											if (substr(phpversion(),0,5) >= '4.2.3') { 
												$text = $GLOBALS["Checks"]["orange"].'mbstring is available, but there may be problems with using multi-byte strings under version 4.2.3 of PHP.<br />We don\'t recommend that you use the multi-language features of iziContents with languages using different charsets.';
											} else { $text = $GLOBALS["Checks"]["green"].'mbstring is available'; }
										}
										else { $text = $GLOBALS["Checks"]["orange"].'mbstring is NOT available.<br />We don\'t recommend that you use the multi-language features of iziContents with languages using different charsets.'; }
										basetext($text);
										if ($GLOBALS["DebugMode"]) { debug_msg($text,$GLOBALS["Titles"]["InstallLog"]); }
										?>
									</td>
								</tr>
								<tr><td align="right" valign="top">
										<?php basetext(mouseover('Safe Mode should be \'Off\' :','SafeMode','Test')); ?>
									</td>
									<td valign="top">
										<?php
										if (!$safe_mode) { $text = $GLOBALS["Checks"]["green"].'Safe mode is \'Off\'';
										} else {
											$text = $GLOBALS["Checks"]["orange"].'Safe mode is \'On\'<br />This can restrict much of the functionality of iziContents';
										}
										basetext($text);
										if ($GLOBALS["DebugMode"]) { debug_msg($text,$GLOBALS["Titles"]["InstallLog"]); }
										?>
									</td>
								</tr>
								<tr><td align="right" valign="top">
										<?php basetext(mouseover('Open Basedir should allow access to the ezContents home directory :','OpenBasedir','Test')); ?>
									</td>
									<td valign="top">
										<?php
										if ($open_basedir <> '') { $text = $GLOBALS["Checks"]["orange"].'Open Basedir restriction is in effect';
										} else { $text = $GLOBALS["Checks"]["green"].'Open Basedir restriction is not set'; }
										basetext($text);
										if ($GLOBALS["DebugMode"]) { debug_msg($text,$GLOBALS["Titles"]["InstallLog"]); }
										?>
									</td>
								</tr>
								<tr><td align="right" valign="top">
										<?php basetext(mouseover('File Uploads should be permitted :','FileUploads','Test')); ?>
									</td>
									<td valign="top">
										<?php
										if ($file_uploads) { $text = $GLOBALS["Checks"]["green"].'File uploads are permitted';
										} else { $text = $GLOBALS["Checks"]["orange"].'File uploads are NOT permitted. You will need to use ftp for all your image, scripts and downloads maintenance.'; }
										basetext($text);
										if ($GLOBALS["DebugMode"]) { debug_msg($text,$GLOBALS["Titles"]["InstallLog"]); }
										?>
									</td>
								</tr>
								<tr><td align="right" valign="top">
										<?php basetext(mouseover('MySQL version 3.23 or above :<br />(recommended)','MySQLVersion','Test')); ?>
									</td>
									<td valign="top">
										<?php
										if (mysql_get_client_info() >= '3.23') { $text = $GLOBALS["Checks"]["green"];
										} else {
											if (mysql_get_client_info() >= '3.20.32') { $text = $GLOBALS["Checks"]["orange"];
											} else {
												$text = $GLOBALS["Checks"]["red"];
												$Status = False;
											}
										}
										$text .= 'You are running the MySQL client version '.mysql_get_client_info();
										basetext($text);
										if ($GLOBALS["DebugMode"]) { debug_msg($text,$GLOBALS["Titles"]["InstallLog"]); }
										?>
									</td>
								</tr>
								<tr><td align="right" valign="top">
										<?php basetext(mouseover('Apache webserver :','Apache','Test')); ?>
									</td>
									<td valign="top">
										<?php
										if (strpos($_SERVER["SERVER_SOFTWARE"], 'Apache') !== FALSE) { $text = $GLOBALS["Checks"]["green"];
										} else { $text = $GLOBALS["Checks"]["orange"]; }
										$text .= 'You are running '.$_SERVER["SERVER_SOFTWARE"];
										basetext($text);
										if ($GLOBALS["DebugMode"]) { debug_msg($text,$GLOBALS["Titles"]["InstallLog"]); }
										?>
									</td>
								</tr>
								<tr><td align="right" valign="top">
										<?php basetext(mouseover('Read/Write privileges :','WriteableFiles','Test')); ?>
									</td>
									<td valign="top">
										<?php
										$myuserid = getmyuid();
										if (phpversion() >= '4.1.0') { $mygroupid = getmygid(); } else { $mygroupid = getmyuid(); }

										if (function_exists('posix_getuid')) {
											$posixmyuserid = posix_getuid();
											$posixuserinfo = posix_getpwuid ($posixmyuserid);
											$posixname = $posixuserinfo["name"];
											$posixmygroupid = $posixuserinfo["gid"];
											$posixgroupinfo = posix_getgrgid ($posixmygroupid);
											$posixgroup = $posixgroupinfo["name"];

											if ($myuserid != $posixmyuserid) {
												$text = $GLOBALS["Checks"]["orange"]."Script running as user '".$posixname."', but PHP reports it as user ".$myuserid.".<br />You may not have the necessary level of file access to run iziContents without problems if you use both ftp and the iziContents maintenance functions to manage files.<br />";
												basetext($text);
												if ($GLOBALS["DebugMode"]) { debug_msg($text,$GLOBALS["Titles"]["InstallLog"]); }
											}
										} else {
											$posixmyuserid = $myuserid;
											$posixname = get_current_user();
											$posixmygroupid = $mygroupid;
											$posixgroup = '';
										}

										if ($GLOBALS["DebugMode"]) {
											debug_msg('<font color="LIGHTGREEN" SIZE="-1">',$GLOBALS["Titles"]["InstallLog"]);
											debug_msg('Script User ID = ['.$myuserid.'] ('.get_current_user().') ',$GLOBALS["Titles"]["InstallLog"]);
											debug_msg('Script Group ID = ['.$mygroupid.']<br />',$GLOBALS["Titles"]["InstallLog"]);
											debug_msg('Posix Script User ID = ['.$posixmyuserid.'] ('.$posixname.')<br />',$GLOBALS["Titles"]["InstallLog"]);
											debug_msg('Posix Script Group ID = ['.$posixmygroupid.'] ('.$posixgroup.')<br /><br /></font>',$GLOBALS["Titles"]["InstallLog"]);
										}

										if ($myuserid != $posixmyuserid) { $myuserid = $posixmyuserid; }
										if ($mygroupid != $posixmygroupid) { $mygroupid = $posixmygroupid; }

										$text  = chk_config($myuserid, $mygroupid);
										$ftext = '';
										
										if (!(fileReadWrite($myuserid,$mygroupid,'../'))) { $ftext = '<br />'.$GLOBALS["Checks"]["orange"].'iziContents root directory file is read only'; }
										if (!(fileReadWrite($myuserid,$mygroupid,'../contentimage'))) { $ftext .= '<br />'.$GLOBALS["Checks"]["orange"].'/contentimage directory is read only'; }
										//if (!(fileReadWrite($myuserid,$mygroupid,'../contentimage/gallery'))) { $ftext .= '<br />'.$GLOBALS["Checks"]["orange"].'/contentimage/gallery directory is read only'; }
										//if (!(fileReadWrite($myuserid,$mygroupid,'../contentimage/gallery/normal'))) { $ftext .= '<br />'.$GLOBALS["Checks"]["orange"].'/contentimage/gallery/normal directory is read only'; }
										//if (!(fileReadWrite($myuserid,$mygroupid,'../contentimage/gallery/original'))) { $ftext .= '<br />'.$GLOBALS["Checks"]["orange"].'/contentimage/gallery/original directory is read only'; }
										//if (!(fileReadWrite($myuserid,$mygroupid,'../contentimage/gallery/thumb'))) { $ftext .= '<br />'.$GLOBALS["Checks"]["orange"].'/contentimage/gallery/thumb directory is read only'; }
										if (!(fileReadWrite($myuserid,$mygroupid,'../downloads'))) { $ftext .= '<br />'.$GLOBALS["Checks"]["orange"].'/downloads directory is read only'; }
										if (!(fileReadWrite($myuserid,$mygroupid,'../izi_install'))) { $ftext .= '<br />'.$GLOBALS["Checks"]["red"].'/izi_install has to be chmod 0777'; }
										if (!(fileReadWrite($myuserid,$mygroupid,'../scripts'))) { $ftext .= '<br />'.$GLOBALS["Checks"]["orange"].'/scripts directory is read only'; }
										if (!(fileReadWrite($myuserid,$mygroupid,'../backup'))) { $ftext .= '<br />'.$GLOBALS["Checks"]["orange"].'/backup directory is read only'; }
										if (!(fileReadWrite($myuserid,$mygroupid,'../sites'))) { $ftext .= '<br />'.$GLOBALS["Checks"]["orange"].'/sites directory is read only'; }
										if (!(fileReadWrite($myuserid,$mygroupid,'../themes'))) { $ftext .= '<br />'.$GLOBALS["Checks"]["orange"].'/themes directory is read only'; }
										if (!(fileReadWrite($myuserid,$mygroupid,'../admin/styles/icache'))) { $ftext .= '<br />'.$GLOBALS["Checks"]["orange"].'/admin/styles/icache directory is read only'; }
										if (!(fileReadWrite($myuserid,$mygroupid,'../admin/excelexport/excelexporter/temp'))) { $ftext .= '<br />'.$GLOBALS["Checks"]["orange"].'/admin/excelexport/excelexporter/temp directory is read only'; }
										if ($text == '') { $text = $GLOBALS["Checks"]["green"].'Configuration file is read/write'; }
										basetext($text);
										if ($GLOBALS["DebugMode"]) { debug_msg($text,$GLOBALS["Titles"]["InstallLog"]); }
										if ($ftext == '') {
											$ftext = '<br />'.$GLOBALS["Checks"]["green"].'Directories are all read/write';
										//	basetext($ftext);
											if ($GLOBALS["DebugMode"]) { debug_msg($ftext,$GLOBALS["Titles"]["InstallLog"]); }
										 }
										basetext($ftext);
										?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td align="center" valign="middle">
				<table border="0" cellpadding="1" cellspacing="1">
					<tr><td align="left" valign="middle">
							<?php
							$text  = $GLOBALS["Checks"]["green"].'A green dot indicates that this is acceptable.<br />';
							$text .= $GLOBALS["Checks"]["orange"].'An orange dot indicates that some functionality may be restricted, or that you may encounter some errors during the installation or while running iziContents.<br />';
							$text .= $GLOBALS["Checks"]["red"].'A red dot indicates that iziContents should not be run without changes to your PHP/Server configuration.<br />Don\'t continue with the installation if you see a red dot.';
							basetext($text);
							?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td align="center" valign="middle">
				<?php blocktext($_SERVER["HTTP_USER_AGENT"]); ?>
			</td>
		</tr>
		<?php
		if ($Status) {
			?>
			<tr><td align="center" valign="middle">
					<input type="button" class="ip_text" value="Continue with Installation" onClick="location.href='index.php?mode=database'">
				</td>
			</tr>
			<?php
		}
		?>
	</table>
	<?php
} // function test_screen()

function chk_config($myuserid, $mygroupid){

$text = '';
//chk if file exist else create it
if (!file_exists("../include/config.php")){
	touch("../include/config.php");
	chmod("../include/config.php", 0777);
	$text = $GLOBALS["Checks"]["green"].'Configuration file created sucessfully';
}
elseif (!(fileReadWrite($myuserid,$mygroupid,'../include/config.php'))) { 	
	$text = $GLOBALS["Checks"]["orange"].'Configuration file is read only'; 
}
elseif (!(fileReadWrite($myuserid,$mygroupid,'../include'))){
	$text = $GLOBALS["Checks"]["orange"].'./include Directory does not allow creation of config.php';	
}
return $text;

} //function chk_config
 
 ?>