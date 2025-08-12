<?  ##############################################
   ### MySource ------------------------------###
  ##- Include Files ------ PHP4 --------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This file is subject to version 1.0 of the
## MySource License, that is bundled with
## this package in the file LICENSE, and is
## available at through the world-wide-web at
## http://mysource.squiz.net/
## If you did not receive a copy of the MySource
## license and are unable to obtain it through
## the world-wide-web, please contact us at
## mysource@squiz.net so we can mail you a copy
## immediately.
##
## File: include/html_general.inc
## Desc: General functions for outputting common chunks of MySource HTML
## $Source: /home/cvsroot/mysource/include/html_general.inc,v $
## $Revision: 2.4 $
## $Author: csmith $
## $Date: 2003/05/16 05:17:58 $
#######################################################################

#---------------------------------------------------------------------#

  ###########################################################
 # Prints a little skerrick of javascript code (with tags) # 
###########################################################

function js($code='') {
	if (empty($code)) return;
	return '<script language="javascript" type="text/javascript">'.$code.'</script>';
}

  ###########################################################
 # Prints a little skerrick of javascript code (with tags) # 
###########################################################

function js_include($file='') {
	if (!$file) return false;
	return '<script language="javascript" type="text/javascript" src="'.$file.'"></script>';
}

  ################################################
 # Prints a javascript alert box with a message #
################################################

function js_alert($message='') {
	if (!$message) return false;
	$message = str_replace("\\n","\n",$message);
	$message = str_replace("''","\'\'",$message);
	$message = ereg_replace("([^\\])'","\\1\\'",$message);
	$message = ereg_replace("^'","\'",$message);
	$message = str_replace("\r\n","\n",$message);
	$exploded = explode("\n",$message);
	$js = "alert('";
	$i = 1;
	foreach($exploded as $line) {
		$js .= trim($line)."\\n";
		if($i++ % 20 == 0) $js .= "...');alert('";
	}
	$js .= "');";
	return js($js);
}

  ##########################################
 # Prints the javascript to relocation us #
##########################################

function js_location($new_loc='') {
	if (!$new_loc) return false;
	return js("location = '$new_loc';");
}

?>