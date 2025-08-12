<?php

/***************************************************************************

 about.php
 ----------
 copyright : (C) 2005 - The iziContents Development Team

 iziContents version : 1.0
 fileversion : 1.0.1
 change date : 23 - 04 - 2005
 ***************************************************************************/

/***************************************************************************
 The iziContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding iziContents and ezContents must remain intact on the scripts and in the HTML for the scripts.

 For more info on iziContents,
 visit http://www.izicontents.com/

/***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the License which can be found within the
 *   zipped package. Under the licence of GPL/GNU.
 *
 ***************************************************************************/ 

include_once ("rootdatapath.php");
includeLanguageFiles('admin');

function os($agent)
{
	if (ereg("Win", $agent)) { $os = "Windows"; }
	elseif ((ereg("Mac", $agent)) || (ereg("PPC", $agent))) { $os = "Mac"; }
	elseif (ereg("Linux", $agent)) { $os = "Linux"; }
	elseif (ereg("FreeBSD", $agent)) { $os = "FreeBSD"; }
	elseif (ereg("SunOS", $agent) || ereg("Solaris", $agent)) { $os = "SunOS"; }
	elseif (ereg("IRIX", $agent)) { $os = "IRIX"; }
	elseif (ereg("BeOS", $agent)) { $os = "BeOS"; }
	elseif (ereg("OS/2", $agent)) { $os = "OS2"; }
	elseif (ereg("AIX", $agent)) { $os = "AIX"; }
	else { $os = "Other"; }
	return $os;
} // function os()
$osystem	= os(php_uname());

if($_GET["delete_izi"]=='yes'){
	deleteFolder("../izi_install");
}	


HTMLHeader('about');
StyleSheet();
?>
</head>
<body leftmargin=0 topmargin=10 marginwidth="0" marginheight="10" class="mainback">

<table border="0" width="100%" cellspacing="0">
	<tr><td align="center">
			<table border="0" width="100%" cellspacing="1" cellpadding="3">
				<tr class="headercontent">
					<td align="center" class="header">
						<b><?= $GLOBALS["tAbout"]; ?></b>
					</td>
				</tr>
				<tr class="tablecontent">
					<td>
						<br /><b> <?= $GLOBALS["tAboutez"]; ?></b>
						<br />
						<?php
						if(is_dir("../izi_install")){
							echo "<br><b><font color=FF0000>".$GLOBALS["tDeleteInstalDir"]."</font></b>";
							?>  <br><br><input type="button" class="ip_text" name="delete_izi" value="<?php echo $GLOBALS["tDeleteInstalDirButton"]; ?>" onClick="location.href = 'about.php?delete_izi=yes'"/><br>
							<?php
   						}
						?>
						
						<center><?php
						if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
							?><a href="http://www.izicontents.com" target="_blank" <?php echo BuildLinkMouseOver('www.izicontents.com'); ?>><?php
						} else {
							?><a href="http://www.izicontents.com" <?php echo BuildLinkMouseOver('www.izicontents.com'); ?>><?php
						}
						echo imagehtmltag($GLOBALS["icon_home"],'ezcnt_banner.gif','www.izicontents.com',0,''); ?></a>
						</center><br /><br />
						<b><?= $GLOBALS["tWrittenBy"];?>:</b> Maarten Schraven (Netherlands), Lenny (Austia), Sascha Tulodetzki (Belgium), David Jay Jackson (Ohio, USA)<br /><br />
						<b>Style and DHTML:</b> Maarten, Lenny<br />
						<b>Core Development:</b> SaschaT, David Jay<br />
						<b><?= $GLOBALS["tBetaTesters"]; ?>:</b> Bert Deelman, Mark Right <?= $GLOBALS["tBetaTesterThanks"]; ?><br /><br />
					</td>
				</tr>

				<?php
				if ($GLOBALS["gsAdminStyle"] != '') {
					include_once ($GLOBALS["rootdp"].$GLOBALS["style_home"].$GLOBALS["gsAdminStyle"]."/settings.php");
					/*
					?>
					<tr class="tablecontent">
						<td><br />
							<b><?php echo $GLOBALS["tAdminStyle"]; ?>:</b> <?php if ($EzAdmin_Style["styleName"] != '') { echo $EzAdmin_Style["styleName"]; } else { echo $GLOBALS["gsAdminStyle"]; } ?><br />
							<b><?php echo $GLOBALS["tWrittenBy"]; ?>:</b> <?php echo $EzAdmin_Style["settingAuthor"]; ?><br /><br />
						</td>
					</tr>
					<?php
					*/
				}
				?>

				<tr class="tablecontent">
					<td>
						<?php echo '<br /><B><U>',$GLOBALS["tModules"],'</U></B>'; ?>
						<table border="0" width="60%">
							<?php
							$strQuery = "SELECT moduledirectory,modulename FROM ".$GLOBALS["eztbModules"].' GROUP BY moduledirectory';
							$result = dbRetrieve($strQuery,true,0,0);
							while ($rs = dbFetch($result)) {
								$strQuery = "SELECT settingvalue FROM ".$GLOBALS["eztbModuleSettings"]." WHERE modulename='".$rs["moduledirectory"]."' AND settingname='version'";
								$mresult = dbRetrieve($strQuery,true,0,0);
								$mrs = dbFetch($mresult);
								$moduleversion = $mrs["settingvalue"];
								$strQuery = "SELECT settingvalue FROM ".$GLOBALS["eztbModuleSettings"]." WHERE modulename='".$rs["moduledirectory"]."' AND settingname='author'";
								$mresult = dbRetrieve($strQuery,true,0,0);
								$mrs = dbFetch($mresult);
								$moduleauthor = $mrs["settingvalue"];
								echo '<tr><td>',$rs["modulename"],'</td><td>';
								if ($moduleversion != "") { echo $GLOBALS["tVersion"].' '.$moduleversion; }
								echo '</td><td>',$moduleauthor,'</td></tr>';
							}
							?>
						</table><br />
					</td>
				</tr>

				<?php
				if (($EZ_SESSION_VARS["PasswordCookie"] != '') && ($EZ_SESSION_VARS["UserGroup"] == $GLOBALS["gsAdminPrivGroup"])) {
					?>
					<tr class="tablecontent">
						<td align="center">
							<br /><b><?php echo $GLOBALS["Browser"]; ?>:</b> <?php echo $_SERVER["HTTP_USER_AGENT"]; ?><br />
							<table width="80%" border="0" cellspacing="3" cellpadding="3">
								<tr>
									<td align="center" valign="bottom">
										<?php echo imagehtmltag($GLOBALS["icon_home"],'stats/'.$osystem.'_large.gif','Powered by '.$osystem,0,'C'); ?>
									</td>
									<td align="center" valign="bottom">
										<a href="http://www.php.net" target="_blank"><?php echo imagehtmltag($GLOBALS["icon_home"],'platforms/php.gif','Powered by PHP',0,'C'); ?></a>
									</td>
									<td align="center" valign="bottom">
										<?php include ('../webservers.php'); ?>
									</td>
									<td align="center" valign="bottom">
										<a href="http://www.mysql.com" target="_blank"><?php echo imagehtmltag($GLOBALS["icon_home"],'platforms/mysql.gif','Powered by MySQL',0,'C'); ?></a>
									</td>
									<td align="center" valign="bottom">
										<a href="http://php.weblogs.com/ADODB" target="_blank"><?php echo imagehtmltag($GLOBALS["icon_home"],'platforms/adodb.gif','Powered by AdoDB',0,'C'); ?></a>
									</td>
								</tr>
								<tr>
									<td align="center" valign="top">
										<?php echo php_uname(); ?>
									</td>
									<td align="center" valign="top">
										<?php echo phpversion(); ?>
									</td>
									<td align="center" valign="top">
										<?php echo $_SERVER["SERVER_SOFTWARE"]; ?>
									</td>
									<td align="center" valign="top">
										<?php 
										echo $GLOBALS["tDBClient"].' : ';
										echo mysql_get_client_info();
										echo '<br />'.$GLOBALS["tDBServer"].' : ';
										echo mysql_get_server_info();
										?>
									</td>
									<td align="center" valign="top">
										<?php echo $ADODB_vers; ?>
									</td>
								</tr>
							</table>
						</td>
					</tr><?php
				}
				?>
			</table>
		</td>
	</tr>
</table>
</body>
</html>

<?php

function LanguageList ()
{
	$count		= 0;
	$langstring	= '';
	$strQuery = "SELECT * FROM ".$GLOBALS["eztbLanguages"]." WHERE enabled='Y' ORDER BY languagecode";
	$result = dbRetrieve($strQuery,true,0,0);
	while ($rs = dbFetch($result)) {
		$languagename	= trim($rs["languagename"]);
		$translationby	= trim($rs["translationby"]);
		if ($count > 0) { $langstring .= '; '; }
		$count++;
		$langstring .= $languagename;
		if ($translationby > '') { $langstring .= ' - '.$translationby; }
	} // if ($rs = dbFetch($result))
	dbFreeResult($result);
	return $langstring;
} // function LanguageList ()


function deleteFolder($folder){

$dirname = $folder;

    // Sanity check
    if (!file_exists($dirname)) {
        return false;
    }
 
    // Simple delete for a file
    if (is_file($dirname)) {
        return unlink($dirname);
    }
 
    // Loop through the folder
    $dir = dir($dirname);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }
 
        // Recurse
        deleteFolder("$dirname/$entry");
    }
 
    // Clean up
    $dir->close();
    return rmdir($dirname);
}
?>
