<?php
/*****************************************
* File      :   $RCSfile: functions.api.general.php,v $
* Project   :   Contenido
* Descr     :   Contenido General API functions
*
* Author    :   $Author: timo.hummel $
*               
* Created   :   01.09.2003
* Modified  :   $Date: 2003/09/05 10:39:37 $
*
* © four for business AG, www.4fb.de
*
* $Id: functions.api.general.php,v 1.5 2003/09/05 10:39:37 timo.hummel Exp $
******************************************/

/* Info:
 * This file contains Contenido General API functions.
 *
 * If you are planning to add a function, please make sure that:
 * 1.) The function is in the correct place
 * 2.) The function is documented
 * 3.) The function makes sense and is generically usable
 *
 */
 
/**
 * contenido_include: Includes a file
 * and takes care of all path transformations.
 *
 * Example:
 * contenido_include("classes", "class.backend.php");
 *
 * Currently defined areas:
 *
 * frontend		Path to the *current* frontend
 * conlib		Path to conlib
 * pear			Path to the bundled pear copy
 * classes		Path to the contenido classes
 * cronjobs		Path to the cronjobs
 * external		Path to the external tools
 * includes		Path to the contenido includes 
 * scripts		Path to the contenido scripts
 *
 * @param $where string The area which should be included
 * @param $what string The filename of the include
 * @param $force boolean If true, force the file to be included  
 *
 * @return none
 *
 */
function contenido_include ($where, $what, $force = false)
{
	global $client, $cfg, $cfgClient;
	
	if ($where == "frontend")
	{
		$include = $cfgClient[$db->f("idclient")]["path"]["frontend"] . $what;
	} else {
		$include = $cfg['path']['contenido'] . $cfg['path'][$where] . $what;
	}

	if ($force == true)
	{
		include($include);
	} else {
		include_once($include);
	}  
	
}


/**
 * cInclude: Shortcut to contenido_include.
 *
 * @see contenido_include
 *
 * @param $where string The area which should be included
 * @param $what string The filename of the include 
 * @param $force boolean If true, force the file to be included   
 *
 * @return none
 *
 */
function cInclude ($where, $what, $force = false)
{
	contenido_include($where, $what, $force);
}

/**
 * plugin_include: Includes a file
 * from a plugin and takes care of all
 * path transformations.
 *
 * Example:
 * plugin_include("formedit", "classes/class.formedit.php");
 *
 * @param $which string The name of the plugin
 * @param $what string The filename of the include 
 *
 * @return none
 *
 */
function plugin_include ($where, $what)
{
	global $cfg;
	
	$include = $cfg['path']['contenido'] . $cfg['path']['plugins'] . $where. "/" . $what;

	include_once($include);  
}



?>