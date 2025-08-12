<?  ##############################################
   ### MySource ------------------------------###
  ##- Frontend Index file -- PHP4 ------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/mysource/web/edit/upgrade_2.8.0_2.10.0.php,v $
## $Revision: 2.1.2.1 $
## $Author: brobertson $
## $Date: 2004/04/19 12:09:13 $
#######################################################################
# Initialise
include_once("../init.php");
#---------------------------------------------------------------------#

$UPGRADE_FROM = '2.8.0';
$UPGRADE_TO   = '2.10.0';

 ######################################
# tell anyone who isn't root .... sorry
if (!$SESSION->logged_in()) {
	$SESSION->login_screen("Upgrade MySource", "You must be logged in.");
	exit();
} else if (!user_root()) {
	$SESSION->login_screen("Upgrade MySource", "You must be <b>root</b> to upgrade the system.",$SESSION->user->login);
	exit();
}

$dir = dirname(__FILE__).'/'.$UPGRADE_FROM.'_'.$UPGRADE_TO;
if (!is_writable($dir) || !is_dir($dir)) {
	report_error(__FILE__, __LINE__, 'Unable to run upgrade the following directory is not writable : '.$dir);
}

?>
<HTML>
	<HEAD>
		<TITLE>Upgrade MySource from <?=$UPGRADE_FROM ?> to <?=$UPGRADE_TO?></TITLE>
	</HEAD>
	<frameset rows="150,*" border="3" frameborder="3">
		<frame src="<?=$UPGRADE_FROM?>_<?=$UPGRADE_TO?>/index.php" name="upgrade_top" marginwidth="0" marginheight="0" frameborder="1" border="0" scrolling="yes">
		<frame src="<?=$UPGRADE_FROM?>_<?=$UPGRADE_TO?>/blank.php" name="upgrade_bottom" marginwidth="0" marginheight="0" frameborder="0" border="10" scrolling="yes">
	</frameset>
</HTML>
