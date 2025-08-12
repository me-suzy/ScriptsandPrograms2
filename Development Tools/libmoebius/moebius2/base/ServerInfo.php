<?php
/*  
 * ServerInfo.php	
 * Copyright (C) 2003-2004, Alejandro Espinoza Esparza.
 *
 * Description :
 *   This class manages all the server information services.
 *
 * Author(s):
 *   Alejandro Espinoza <aespinoza@structum.com.mx>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation; either version 2.1 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, USA.
 *
 */

import("moebius2.base.Object");

/* --- Constants --- */
// Magic Qoutes 
define("MQ_RUNTIME", 0);
define("MQ_GPC", 1);

// Loaded Extensions
define("LE_TEXT", 0);
define("LE_ARRAY", 1);

// PHP Info
define("PI_GENERAL", 1);
define("PI_CREDITS", 2);
define("PI_CONF", 4);
define("PI_MODS", 8);
define("PI_ENV", 16);
define("PI_VARS", 32);
define("PI_LICENSE", 64);
define("PI_ALL", -1);

/**
  * This class manages all the server information services.
  * @class		ServerInfo
  * @package	moebius2.base
  * @author		Alejandro Espinoza &lt; <a href="mailto:aespinoza@structum.com.mx">aespinoza@structum.com.mx</a> &gt;
  * @version	1.0
  * @extends	Object
  * @requires	Object
  */
class ServerInfo
{
	/* --- Attributes --- */
	
	/* --- Methods --- */
	/**
	  * Constructor, initializes the object.
	  * @method		ServerInfo
	  * @returns	none
	  */	
	function ServerInfo()
	{
		Object::Object("moebius2.base", "ServerInfo");
	}

	/**
	  * Returns the server's operating system name.
	  * @method		GetOSName
	  * @returns	name of the operating system.
	  */	
	function GetOSName()
	{
		return PHP_OS;
	}

	/**
	  * Returns the server's name.
	  * @method		GetServerName
	  * @returns	name of the server.
	  */	
	function GetServerName()
	{
		return $_SERVER['SERVER_NAME'];
	}

	/**
	  * Returns the server's identification string.
	  * @method		GetServerSoftware
	  * @returns	server's identification string.
	  */	
	function GetServerSoftware()
	{
		return $_SERVER['SERVER_SOFTWARE'];
	}

	/**
	  * Returns the server's port.
	  * @method		GetServerPort
	  * @returns	port number the server is listening on.
	  */	
	function GetServerPort()
	{
		return $_SERVER['SERVER_PORT'];
	}

	/**
	  * Returns the server's HTTP Host.
	  * @method		GetHTTPHost
	  * @returns	server's http host.
	  */	
	function GetHTTPHost()
	{
		return $_SERVER['HTTP_HOST'];
	}

	/**
	  * Returns the filesystem- (not document root-) based path to the current script, after the server has done any virtual-to-real mapping.
	  * @method		GetPathTranslated
	  * @returns	string containing the filesystem based path of the current script.
	  */	
	function GetPathTranslated()
	{
		return $_SERVER['PATH_TRANSLATED'];
	}

	/**
	  * Returns the PHP version currently running.
	  * @method		GetPHPVersion
	  * @returns	string containing the version of the currently running PHP parser.
	  */	
	function GetPHPVersion()
	{
		return phpversion();
	}

	/**
	  * Returns the name of the current session.
	  * @method		GetSessionName
	  * @returns	string containing the name of the current session.
	  */	
	function GetSessionName()
	{
		return session_name();
	}

	/**
	  * Returns the path of the current directory used to save session data.
	  * @method		GetSessionSavePath
	  * @returns	string containing the save path of the current session.
	  */	
	function GetSessionSavePath()
	{
		return session_save_path();
	}

	/**
	  * Returns the value of the SMTPconfiguration
	  * @method		GetSMTP
	  * @returns	string containing the SMTP configration.
	  */	 
	function GetSMTPConfig()
	{
		return ini_get(SMTP);
	}

	/**
	  * Returns the max upload size permitted by the server.
	  * @method		GetUploadMaxSize
	  * @returns	number representing the max upload size.
	  */	
	function GetUploadMaxSize()
	{
		return ini_get(upload_max_filesize);
	}

	/**
	  * Returns true if GDLib is installed.
	  * @method		GDLibExists
	  * @returns	true if it is installed, false otherwise.
	  */	
	function GDLibExists()
	{
		return function_exists('imagecreate');
	}

	/**
	  * Returns true if mail is permitted.
	  * @method		IsMailOn
	  * @returns	true if mail is permitted, false otherwise.
	  */	
	function IsMailOn()
	{
		return function_exists('mail');
	}

	/**
	  * Returns true if safe mode is on.
	  * @method		IsSafeModeOn
	  * @returns	true if safe mode  is on, false otherwise.
	  */	
	function IsSafeModeOn()
	{
		$safeModeOn = false;
		
		if(ini_get(safe_mode) == "1") {
			$safeModeOn = true;
		}

		return $safeModeOn;
	}

	/**
	  * Returns true if magic qoutes is on.
	  * Types :
	  * 0 - MQ_RUNTIME : Magic Qoutes runtime. (Default)
	  * 1 - MQ_GPC : Magic Qoutes GPC.
	  * @method		IsMagicQoutesOn
	  * @param		optional int type
	  * @returns	true if magic qoutes is on, false otherwise.
	  */	
	function IsMagicQoutesOn($type = MQ_RUNTIME)
	{
		$MQOn = false;

		if($type == MQ_RUNTIME) {
			$MQTest = ini_get(magic_quotes_runtime);
		} else {
			$MQTest = ini_get(magic_quotes_gpc);
		}
						    
		if($MQTest == "1") {
			$MQOn = true;
		}

		return $MQOn;
	}

	/**
	  * Returns true if register globals is on.
	  * @method		IsRegisterGlobalsOn
	  * @returns	true if register globals is on, false otherwise.
	  */	
	function IsRegisterGlobalsOn()
	{
		$regGlobalOn = false;
		
		if(ini_get(register_globals) == "1") {
			$regGlobalOn = true;
		}

		return $regGlobaOn;
	}

	/**
	  * Returns the loaded extensions dir.
	  * @method		GetLoadedExtensionsDir
	  * @returns	string containing the loaded extensions dir.
	  */	
	function GetLoadedExtensionsDir()
	{
		return ini_get(extension_dir);
	}

	/**
	  * Returns the loaded extensions formated into a string.
	  * Types :
	  * 0 - LE_TEXT : Loaded extensions in text form.
	  * 1 - LE_ARRAY : Loaded extensions in array form.
	  * @method		GetLoadedExtensions
	  * @param		optional int type
	  * @returns	string or array containing the loaded extensions.
	  */	
	function GetLoadedExtensions($type = LE_TEXT)
	{
		$arrExt = get_loaded_extensions();
		$iCount = count($arrExt);

		if($type == LE_TEXT) {
			$ret = "";
			
			for ($i = 0; $i < $count; $i++) {
				$ret .= $exts[$i];
				if($i != $count-1) {
					$ret .= ", ";
				}
			}
		} else {
			$ret = $exts;
		}

		return $ret;
	}

	/**
	  * Returns the include path.
	  * @method		GetIncludePath
	  * @returns	string containing the include path.
	  */	
	function GetIncludePath()
	{
		return  ini_get(include_path) ;
	}

	/**
	  * Returns any PHP information.
	  * Types :
	  * 1 - PI_GENERAL : General information.
	  * 2 - PI_CREDITS : Credits.
	  * 4 - PI_CONF : Configuration.
	  * 8 - PI_MODS : Modules.
	  * 16 - PI_ENV : Enviroment.
	  * 32 - PI_VARS : Variables.
	  * 64 - PI_LICENSE : License.
	  * -1 - PI_ALL : All info. (Default)
	  * @method		GetPHPInfo
	  * @param		optional int type
	  * @returns	string containing the selected php info.
	  */	
	function GetPHPInfo($type = PI_ALL)
	{
		return phpinfo($type);
	} 
}

?>