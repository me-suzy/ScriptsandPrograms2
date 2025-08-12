<?php
/*****************************************
* File      :   $RCSfile: include.system_sysvalues.php,v $
* Project   :   Contenido
* Descr     :   output of important system variables
*
* Author    :   $Author: timo.hummel $
*               
* Created   :   15.08.2003
* Modified  :   $Date: 2003/10/17 12:22:56 $
*
* © four for business AG, www.4fb.de
*
* $Id: include.system_sysvalues.php,v 1.3 2003/10/17 12:22:56 timo.hummel Exp $
******************************************/

$tpl->reset();

/*
 * print out tmp_notifications if any action has been done
*/
if (isset($tmp_notification))
{
	$tpl->set('s', 'TEMPNOTIFICATION', $tmp_notification);
}
else
{
	$tpl->set('s', 'TEMPNOTIFICATION', '');	
}

/* get system variables for output */
writeSystemValuesOutput($usage='output');

// error log
if (file_exists($cfg['path']['contenido']."logs/errorlog.txt"))
{
    $errorLogHandle = fopen ($cfg['path']['contenido']."logs/errorlog.txt", "r");
    $txtAreaHeight = "200";
    while (!feof($errorLogHandle))
    {
        $errorLogBuffer .= fgets($errorLogHandle, 4096);
    }
    fclose ($errorLogHandle);
    if (strlen ($errorLogBuffer) == 0)
    {
    	$errorLogBuffer = i18n("No error log entries found");
    	$txtAreaHeight = "20";	
    }
    
}
else
{
	$errorLogBuffer = i18n("No error log file found");
	$txtAreaHeight = "20";	
}
$tpl->set('s', 'TXTERRORLOGSIZE', $txtAreaHeight);
$tpl->set('s', 'ERRORLOG', $errorLogBuffer);

// upgrade error log
if (file_exists($cfg['path']['contenido']."logs/install.log.txt"))
{
    $upgErrorLogHandle = fopen ($cfg['path']['contenido']."logs/install.log.txt", "r");
    $txtAreaHeight = "200";
    while (!feof($upgErrorLogHandle))
    {
        $upgErrorLogBuffer .= fgets($upgErrorLogHandle, 4096);
    }
    fclose ($upgErrorLogHandle);
    if (strlen ($upgErrorLogBuffer) == 0)
    {
    	$upgErrorLogBuffer = i18n("No install error log entries found");
    	$txtAreaHeight = "20";	
    }
    
}
else
{
	$upgErrorLogBuffer = i18n("No error log entries found");
	$txtAreaHeight = "20";	
}
$tpl->set('s', 'TXTUPGERRORLOGSIZE', $txtAreaHeight);
$tpl->set('s', 'UPGERRORLOG', $upgErrorLogBuffer);

/*
 * parameter which log shoult be cleared
 * log = 1	clear /contenido/logs/errorlog.txt
 * log = 2	clear /contenido/upgrade_errorlog.txt
*/
$tpl->set('s', 'LOGEMPTYURL', $sess->url("main.php?area=$area&frame=$frame&action=emptyLog&log=1"));
$tpl->set('s', 'UPGLOGEMPTYURL', $sess->url("main.php?area=$area&frame=$frame&action=emptyLog&log=2"));

// parse out template
$tpl->generate($cfg['path']['templates'] . $cfg['templates']['systam_variables']);

?>