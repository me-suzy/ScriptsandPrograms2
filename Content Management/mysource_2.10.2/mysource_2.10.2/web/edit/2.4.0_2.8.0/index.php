<?
$files_to_ignore = array('blank.php', 'index.php', 'upgrade_functions.php');

require_once('../../init.php');

$GLOBALS['UPGRADE_FROM'] = '2.4.0';
$GLOBALS['UPGRADE_TO']   = '2.8.0';

error_reporting(5);
 ####################################################################
# tell anyone who isn't root to go away
if (!$SESSION->logged_in()) {
	$SESSION->login_screen("Upgrade to $UPGRADE_TO", "You must be logged in.");
	exit();
} else if (!user_root()) {
	$SESSION->login_screen("Upgrade to $UPGRADE_TO", "You must be a <b>root</b> to upgrade the system.",$SESSION->user->login);
	exit();
}

?>
<html>
<head>
<title>Upgrade MySource Version</title>
<body>
<?

if (!$_REQUEST['system_backed_up']) {
	?>
	<div style="font-family: verdana">
	Upgrading MySource <?=$UPGRADE_FROM?> to <?=$UPGRADE_TO?>...<br><br>
	<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
	<b>Are you sure that you have backed up your system? You only need to make copies of the <b>data</b> directory and dump you MySource database(s).</b><p>
	<input type="hidden" name="time" value="<?=microtime()?>">
	<input type="submit" name="system_backed_up" value="&nbsp;&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;">
	<input type="button" value="&nbsp;&nbsp;&nbsp;No&nbsp;&nbsp;&nbsp;" onClick="javascript: alert('Well get on with it :)');">
	</form>
	</div>
	<?
	exit;
}

?>
These are the upgrade scripts that need to run to upgrade MySource from version <?=$GLOBALS['UPGRADE_FROM']?> to version <?=$GLOBALS['UPGRADE_TO']?>.<br />
<?
$upgrade_files = array();
$dir = opendir(dirname(__FILE__));

while(($file = readdir($dir)) !== FALSE) {
	if ($file == '.' || $file == '..') continue;
	if (is_dir($file)) continue;
	$ext = end(explode('.',$file));
	if ($ext == 'php' && !in_array($file, $files_to_ignore)) $upgrade_files[] = $file;
}
closedir($dir);

sort($upgrade_files);

$upgrades_done = array();

foreach($upgrade_files as $file) {
	if (is_file($file . '.success')) {
		$upgrades_done[] = $file;
		?><span class="success">Upgrade <?=$file?> has been run successfully.</span><br /><?
	} elseif (is_file($file . '.failure')) {
		?><span class="failure">Upgrade <a href="<?=$file?>" target="upgrade_bottom"><?=$file?></a> has been run, but failed.</span><br /><?
	} elseif (is_file($file . '.ignore')) {
		$upgrades_done[] = $file;
		?><span class="ignore">Upgrade <?=$file?> doesn't need to run.</span><br /><?
	} else {
		?><a href="<?=$file?>" target="upgrade_bottom"><?=$file?></a><br /><?
	}
}

sort($upgrades_done);

if (equal_arrays($upgrades_done, $upgrade_files)) {
	?>
	All Upgrades Complete!<br />
	<a href="../index.php">Go back to site.</a><br />
	<?
}

?>
</body>
</html>
