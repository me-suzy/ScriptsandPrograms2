<?php
/********************************************************************************
* Setupscript for contenido 4.x - http://www.contenido                          *
* (c) 2002 by Björn Brockmann                                                   *
* modified by timo, htw                                                         *
* This script works with PHP >= 4.03 . If you found errors or have make         *
* some cool hacks please contact the author.                                    *
*                                                                               *
* @License: GPL                                                                 *
* @Version: 1.3                                                                 *
*********************************************************************************/


@set_time_limit(120);
$con_setup = &new setup();
$data = $con_setup -> make_setup();
echo $data;


class setup
{
	var $globals;
	var $debug = false;
	
	/**
	* Konstruktor. Catch all globals
	*/
	function setup()
	{
		$this -> catch_globals();

		//Show seperate finish screen if user chose update only
		if($this -> globals["action"] == 'screen_finish' && $this -> globals["mode"] == 'update'){
			$this -> globals["action"] = 'screen_finish_update';
		}

	}

	/**
	* Manage the setup
	*
	* @return string specific HTML- screen
	*/
	function make_setup()
	{
		switch($this -> globals["action"])
		{
			case 'screen_check_server_config':
				$return_this = $this->screen_check_server_config();
				break;
			case 'screen_chose_setup_kind':
				$return_this = $this -> screen_chose_setup_kind();
				break;
			case 'screen_enter_mysql_data':
				$return_this = $this -> screen_enter_mysql_data();
				break;
			case 'screen_ready_to_insert_sql':
				$return_this = $this -> screen_ready_to_insert_sql();
				break;
			case 'screen_thumbnail_config':
				$return_this = $this -> screen_thumbnail_config();
				break;
			case 'screen_download_config':
				$return_this = $this -> screen_download_config();
				break;
			case 'screen_finish':
				$return_this = $this -> screen_finish();
				break;
			case 'screen_finish_update':
				$return_this = $this -> screen_finish_update();
				break;
			case 'make_cfg_general':
				$return_this = $this -> make_cfg_general();
				break;
			case 'make_cfg_save':
				$return_this = $this -> make_cfg_save();
				break;				
			default:
				$return_this = $this -> screen_welcome();
		}
		return $return_this;
	}

	/**
	* Catch POST and GET - Statements
	*/
	function catch_globals()
	{
		global $HTTP_GET_VARS, $HTTP_POST_VARS;

		while (list($key, $val) = @each($HTTP_GET_VARS))
		{
			$this -> globals[$key] = $val;
		}

		while (list($key, $val) = @each($HTTP_POST_VARS))
		{
			$this -> globals[$key] = $val;
		}
	}

	/**
	* Make the Welcome screen. If it supported in future contenido-versions
	* you can chose your language here (at the moment only german)
	*
	* @return string comple HTML welcome screen
	*/
	function screen_welcome()
	{
		$tpl = new gb_template();
		$tpl -> insert('', 'next_step', 'screen_check_server_config');
		return $tpl -> make('templates/welcome.tpl');
	}

	/**
	* Make screen where the server is checked
	*
	* @return string complete HTML chose screen
	*/
	function screen_check_server_config()
	{
		$tpl = new gb_template();
		$tpl -> insert('', 'lang', $this -> globals["lang"]);
		$tpl -> insert('', 'next_step', 'screen_chose_setup_kind');
		
		if ($this->globals["lang"] == "english")
		{
			$deact = "Deactivated functions";
			$maxrun = "Maximum execution time";
			$maxupl = "Maximum upload size";
			$dircheck = "Directory write check";
			$dirok = "Directory is writeable";
			$dirfail = "Directory and/or the files are NOT writeable!";
		} else {
			$deact = "Deaktivierte Funktionen";
			$maxrun = "Maximale Laufzeit";
			$maxupl = "Maximale Uploadgröße";
			$dircheck = "Verzeichnis-Überprüfung";
			$dirok = "Verzeichnis ist schreibbar";
			$dirfail = "Verzeichnis und/oder die enthaltenen Dateien sind NICHT schreibbar!";
		}
		
		$root_path = str_replace ('\\', '/', dirname(__FILE__) . '/*');
		$root_path = str_replace('/setup/*', '', $root_path);
		$icon_ok = '<img src="icon_ok.gif" onclick="javascript:alert(\''.$dirok.'\')">';
		$icon_fail = '<img src="icon_fatalerror.gif" onclick="javascript:alert(\''.$dirfail.'\')">';
		$results = '<table border="0">';
		$results .= "<tr><td valign=bottom>PHP-Version:</td><td>".$this->check_php_version()."</td></tr>";
		$results .= "<tr><td valign=bottom>SAFE_MODE:</td><td>".$this->check_safe_mode()."</td></tr>";
		$results .= "<tr><td valign=bottom>$deact:</td><td>".$this->check_disable_functions()."</td></tr>";
		$results .= "<tr><td valign=bottom>$maxrun:</td><td>".$this->check_max_exec_time()."</td></tr>";
		$results .= "<tr><td valign=bottom>$maxupl:</td><td>".$this->check_max_upload()."</td></tr>";
		$results .= "</table>";
		$results .= "<p><b>$dircheck:</b></p>";
		if (is_writable($root_path."/contenido/cronjobs/"))
		{
			$vdir = $root_path."/contenido/cronjobs/";
			$dh  = opendir($vdir);
			while (false !== ($filename = readdir($dh))) {
    			$files[] = $filename;
			}
			
			$write = true;
			if (is_array($files))
			{
				
				foreach ($files as $value)
				{
					if (substr($value,strlen($value)-3) == "job")
					{
						
						if (!is_writable($vdir.$value))
						{
							$write = false; 	
						}	
					}		
				}
			}
			
			if ($write == true)
			{
				$dir = $root_path."/contenido/cronjobs/".$icon_ok;
			} else {
				$dir = $root_path."/contenido/cronjobs/".$icon_fail;
			}
		} else {
			$dir = $root_path."/contenido/cronjobs/".$icon_fail;
		}
		$results .= $dir."<br>";
		
		if (is_writable($root_path."/contenido/logs/"))
		{
			$files = "";
			$vdir = $root_path."/contenido/logs/";
			$dh  = opendir($vdir);
			while (false !== ($filename = readdir($dh))) {
    			$files[] = $filename;
			}
			
			$write = true;
			if (is_array($files))
			{
				
				foreach ($files as $value)
				{
					if (substr($value,strlen($value)-3) == "txt")
					{
						if (!is_writable($vdir.$value))
						{
							$write = false; 	
						}	
					}		
				}
			}
			
			if ($write == true)
			{
				$dir = $root_path."/contenido/logs/".$icon_ok;
			} else {
				$dir = $root_path."/contenido/logs/".$icon_fail;
			}	
		} else {
			$dir = $root_path."/contenido/logs/".$icon_fail;
		}
		$results .= $dir."<br>";
		
		if (is_writable($root_path."/cms/upload/"))
		{
			$dir = $root_path."/cms/upload/".$icon_ok;
		} else {
			$dir = $root_path."/cms/upload/".$icon_fail;
		}
		$results .= $dir."<br>";
		
		if (is_writable($root_path."/cms/js/"))
		{
			$dir = $root_path."/cms/js/".$icon_ok;
		} else {
			$dir = $root_path."/cms/js/".$icon_fail;
		}
		$results .= $dir."<br>";
		
		if (is_writable($root_path."/cms/cache/"))
		{
			$dir = $root_path."/cms/cache/".$icon_ok;
		} else {
			$dir = $root_path."/cms/cache/".$icon_fail;
		}
		$results .= $dir."<br>";
		
		if (is_writable($root_path."/cms/css/"))
		{
			$dir = $root_path."/cms/css/".$icon_ok;
		} else {
			$dir = $root_path."/cms/css/".$icon_fail;
		}
		$results .= $dir."<br>";
		
		if (is_writable($root_path."/cms/logs/"))
		{
			$dir = $root_path."/cms/logs/".$icon_ok;
		} else {
			$dir = $root_path."/cms/logs/".$icon_fail;
		}
		$results .= $dir."<br>";								
		$tpl->insert('', 'checkresults', $results);
		return $tpl -> make('templates/'. $this -> globals['lang'] . '/check_server_config.tpl');
	}


	/**
	* Checks the PHP-Version
	*
	* @return string 
	*/
	function check_php_version ()
	{
		$checkFatal["german"] = "Ihre PHP-Version ist zu alt, oder definiert nicht die Funktion \'version_compare\'. Die Mindestanforderung ist PHP 4.1.0.";
		$checkFatal["english"] = "Your PHP-Version is too old, or doesn\'t define the function \'version_compare\'. The minimum requirement is PHP 4.1.0.";
		$checkFatal422["german"] = "Die PHP-Version 4.2.2 enthält kritische Fehler mit Multi-Byte-Strings und wird daher mit Contenido nicht richtig funktionieren. Bitte benutzen Sie eine andere Version als 4.2.2";
		$checkFatal422["english"] = "The version 4.2.2 of php contains critical bugs which makes it impossible to run Contenido with it. Please upgrade or downgrade to another version.";		
		$checkOK["german"] = "Ihre PHP-Version erfüllt die Mindestanforderung von PHP 4.1.0.";
		$checkOK["english"] = "Your version of PHP meets the required minimum PHP-Version of PHP 4.1.0.";
		
		$phpversion = phpversion();
		
		if (!function_exists("version_compare"))
		{
			$ret = $checkFatal[$this->globals['lang']];
			return($phpversion.' <img src="icon_fatalerror.gif" onclick="javascript:alert(\''.$ret.'\')">');
		}
		
		if (phpversion() == "4.2.2")
		{
			$ret = $checkFatal422[$this->globals['lang']];
			return($phpversion.' <img src="icon_fatalerror.gif" onclick="javascript:alert(\''.$ret.'\')">');
		}
		
		$ret = $checkOK[$this->globals['lang']];
		return ($phpversion.' <img src="icon_ok.gif" onclick="javascript:alert(\''.$ret.'\')">');
	}
	
	function check_safe_mode ()
	{
		$checkFatal["german"] = "Achtung: Der SAFE_MODE auf Ihrem System ist aktiviert. Dies kann unter Umständen zu Problemen mit Dateioperationen (Upload, Mandantenkopierfunktion) führen, sofern Ihr PHP-Script nicht denselben Besitzer wie Ihr Webserver hat.";
		$checkFatal["english"] = "Warning: The SAFE_MODE functionality is turned on. This may cause a few problems with file uploads and client management if the contenido scripts don't have the same owner than your web server.";
		$checkOK["german"] = "Der SAFE_MODE ist nicht aktiv. Upload- und Mandantenmanagementfunktionen können benutzt werden, sofern die Dateisystemberechtigungen korrekt gesetzt wurden.";
		$checkOK["english"] = "SAFE_MODE is off on your system. Upload and client management functions can be used as long as the permissions are correct on your filesystem.";
		$on["german"] = "Aktiv";
		$on["english"] = "Active";
		$off["german"] = "Deaktiviert";
		$off["english"] = "Disabled";
		
		if (ini_get("safe_mode") == 1)
		{
			$ret = $checkFatal[$this->globals['lang']];
			return($on[$this->globals['lang']].' <img src="icon_warning.gif" onclick="javascript:alert(\''.$ret.'\')">');
		}
		
		$ret = $checkOK[$this->globals['lang']];
		return ($off[$this->globals['lang']].' <img src="icon_ok.gif" onclick="javascript:alert(\''.$ret.'\')">');
	}	
	
	function check_disable_functions ()
	{
		$checkFatal["german"] = "Achtung: Einige Funktionen wurden auf Ihrem System deaktiviert. Dies kann unter Umständen zu Problemen mit Contenido führen. Bei Problemen bitte unbedingt die nachfolgende Liste der deaktivierten Funktionen angeben:";
		$checkFatal["english"] = "Warning: Some functions were disabled on your system. This may cause problems with Contenido. If you experience problems, please attach the following list of disabled functions:";
		$checkOK["german"] = "Es wurden auf Ihrem System keine Funktionen deaktiviert.";
		$checkOK["english"] = "No functions were disabled on your system.";
		$on["german"] = "Ja";
		$on["english"] = "Yes";
		$off["german"] = "Nein";
		$off["english"] = "No";
				
		if (strlen(ini_get("disable_functions")) > 1)
		{
			$ret = $checkFatal[$this->globals['lang']];
			return($on[$this->globals['lang']].' <img src="icon_warning.gif" onclick="javascript:alert(\''.$ret.ini_get("disable_functions").'\')">');
		}
		
		$ret = $checkOK[$this->globals['lang']];
		return ($off[$this->globals['lang']].' <img src="icon_ok.gif" onclick="javascript:alert(\''.$ret.ini_get("disable_functions").'\')">');
	}	
	
	function check_max_exec_time()
	{
		$checkFatal["german"] = "Achtung: Die maximale Laufzeit ist eventuell zu niedrig für ihr System. Eine Mindestlaufzeit von 20 Sekunden wird auf schnelleren, eine Mindestlaufzeit von 60 Sekunden auf langsameren Servern empfohlen.";
		$checkFatal["english"] = "Warning: The maximum execution time for scripts is probably too low for your system. A minimum time of 20 seconds are recommended for faster servers, a minimum time of 60 seconds for slower ones.";
		$checkOK["german"] = "Die maximale Laufzeit beträgt 40 oder mehr Sekunden für Ihr System";
		$checkOK["english"] = "The maximum execution time is 40 or more seconds for your system";
		$on["german"] = "Sekunden";
		$on["english"] = "Seconds";
		$off["german"] = "Sekunden";
		$off["english"] = "Seconds";
				
		if (ini_get("max_execution_time") < 20)
		{
			$ret = $checkFatal[$this->globals['lang']];
			return(ini_get("max_execution_time") . " ".$on[$this->globals['lang']].' <img src="icon_warning.gif" onclick="javascript:alert(\''.$ret.'\')">');
		}
		
		$ret = $checkOK[$this->globals['lang']];
		return (ini_get("max_execution_time") . " " .$off[$this->globals['lang']].' <img src="icon_ok.gif" onclick="javascript:alert(\''.$ret.'\')">');
	}	
	
	function check_max_upload()
	{
		$checkFatal["german"] = "Achtung: Die maximale Größe für Datei-Uploads ist niedriger als 4MB. Dies ist womöglich nicht beabsichtigt.";
		$checkFatal["english"] = "Warning: The maximum size for file uploads is lower than 4MB. This is probably not intentional.";
		$checkOK["german"] = "Die maximale Größe für Datei-Uploads ist 4MB oder höher.";
		$checkOK["english"] = "The maximum size for file uploads is 4MB or higher.";
		$on["german"] = "MB";
		$on["english"] = "MB";
		$off["german"] = "MB";
		$off["english"] = "MB";
				
		$ret = $checkOK[$this->globals['lang']];
		return (ini_get("post_max_size") . " " .' <img src="icon_ok.gif">');
	}		
	
	/**
	* Make screen where user can chose mysql-dump
	*
	* @return string complete HTML chose screen
	*/
	function screen_chose_setup_kind()
	{
		$tpl = new gb_template();
		$tpl -> insert('', 'lang', $this -> globals["lang"]);
		$tpl -> insert('', 'next_step', 'screen_enter_mysql_data');
		return $tpl -> make('templates/'. $this -> globals['lang'] . '/chose_setup_kind.tpl');
	}

	/**
	* Make screen where user enter datbaseinformation (host,
	* database, user, password).
	*
	* @return string complete HTML enter_mysql_data screen
	*/
	function screen_enter_mysql_data()
	{
		$tpl = new gb_template();

		//check if user have chose "update"
		if ($this -> globals["sql_target"] == 'updates.sql'){
			$this -> globals["mode"] = 'update';
		}
		else{
			$this -> globals["mode"] = 'normal';
		}

		$tpl -> insert('', 'mode', $this -> globals["mode"]);
		$tpl -> insert('', 'sql_target', $this -> globals["sql_target"]);
		$tpl -> insert('', 'lang', $this -> globals["lang"]);
		$tpl -> insert('', 'next_step', 'screen_ready_to_insert_sql');

		// Insert template dummies for some erros that can in occur screen_ready_to_insert_sql()
		$tpl -> insert('', 'host', '');
		$tpl -> insert('', 'db', '');
		$tpl -> insert('', 'user', '');
		$tpl -> insert('', 'pass', '');
		$tpl -> insert('', 'connection_error', '');
		$tpl -> insert('', 'prefix', 'con');

		return $tpl -> make('templates/'. $this -> globals['lang'] . '/enter_mysql_data.tpl');
	}

	/**
	* Validate User Data(host,database, user, password) and print out error
	* or the ready_to_insert_mysql Screen.
	*
	* @return string complete HTML ready_to_insert_sql or error  screen
	*/
	function screen_ready_to_insert_sql()
	{
		$error = false;

		$tpl = new gb_template();

		//check host, username and password
		$con_handle = @mysql_connect ($this -> globals["host"],
		$this -> globals["user"],
		$this -> globals["pass"]);

		if(empty($con_handle)){
			$target_tpl = "enter_mysql_data.tpl";
			$tpl -> insert('', 'connection_error', " <b>FEHLER!</b> Bitte überprüfen Sie HOST, USERNAME UND PASSWORT<br><br>");
			$error = true;
		}

		else{
			if(!mysql_select_db ($this -> globals["db"], $con_handle)){
				mysql_query("CREATE DATABASE ".$this->globals["db"], $con_handle);
				
				if (!mysql_select_db ($this -> globals["db"], $con_handle)){
					
					$target_tpl = "enter_mysql_data.tpl";
					if ($this->globals["lang"] == "german")
					{
					$tpl -> insert('', 'connection_error', '<b>FEHLER!</b> Die Datenbank "'. $this -> globals["db"] . '" existiert nicht oder ist nicht erreichbar! Überprüfen Sie bitte, ob diese Datenbank schon angelegt ist.<br><br>');
					} else {
					$tpl -> insert('', 'connection_error', '<b>ERROR!</b> The database "'. $this -> globals["db"] . '" doesn\'t exist or is not reachable. Please verify that the database has been created.<br><br>');
					}
					$error = true;
				}
			}
		}
		$tpl -> insert('', 'host', $this -> globals["host"]);
		$tpl -> insert('', 'db', $this -> globals["db"]);
		$tpl -> insert('', 'user', $this -> globals["user"]);
		$tpl -> insert('', 'pass', $this -> globals["pass"]);
		$tpl -> insert('', 'prefix', $this -> globals['prefix']);

		$tpl -> insert('', 'sql_target', $this -> globals["sql_target"]);
		$tpl -> insert('', 'lang', $this -> globals["lang"]);
		$tpl -> insert('', 'mode', $this -> globals["mode"]);


		if ($error){
			$tpl -> insert('', 'next_step', 'screen_ready_to_insert_sql');
			$target_tpl = "enter_mysql_data.tpl";
		}
		else{
			$tpl -> insert('', 'next_step', 'screen_thumbnail_config');
			$target_tpl = "ready_to_insert_sql.tpl";
		}
		return $tpl -> make('templates/'. $this -> globals['lang'] . '/' . $target_tpl);
	}

	/**
	* Insert mysqldump and ask for the thumbnail config
	*
	* @return string complete HTML thumbnail_config screen
	*/
	function screen_thumbnail_config()
	{
		$this -> insert_sql();
		$tpl = new gb_template();

		$tpl -> insert('', 'host', $this -> globals["host"]);
		$tpl -> insert('', 'db', $this -> globals["db"]);
		$tpl -> insert('', 'user', $this -> globals["user"]);
		$tpl -> insert('', 'pass', $this -> globals["pass"]);
		$tpl -> insert('', 'prefix', $this -> globals['prefix']);

		$tpl -> insert('', 'sql_target', $this -> globals["sql_target"]);
		$tpl -> insert('', 'lang', $this -> globals["lang"]);
		$tpl -> insert('', 'next_step', 'screen_download_config');
		$tpl -> insert('', 'mode', $this -> globals["mode"]);

		return $tpl -> make('templates/'. $this -> globals['lang'] . '/thumbnail_config.tpl');
	}

	/**
	* Give user the opinion to download the config.php
	*
	* @return string complete HTML download_config  screen
	*/
	function screen_download_config()
	{
		$tpl = new gb_template();
		
		if($this -> globals["mode"] == 'update'){
			$tpl -> insert('', 'show_txt_only_by_update', '<div align="center"><b>Auch wenn Sie die Updateoption gewählt haben, müssen Sie die Konfigurationsdatei neu herunterladen!"</b></div>');
		}
		else{
			$tpl -> insert('', 'show_txt_only_by_update', '');
		}
		
		$tpl -> insert('', 'host', $this -> globals["host"]);
		$tpl -> insert('', 'db', $this -> globals["db"]);
		$tpl -> insert('', 'user', $this -> globals["user"]);
		$tpl -> insert('', 'pass', $this -> globals["pass"]);
		$tpl -> insert('', 'prefix', $this -> globals['prefix']);

		$tpl -> insert('', 'thumbnail_width', $this -> globals["thumbnail_width"]);
        $tpl -> insert('', 'thumbnail_height', $this -> globals["thumbnail_height"]);

		$tpl -> insert('', 'sql_target', $this -> globals["sql_target"]);
		$tpl -> insert('', 'lang', $this -> globals["lang"]);
		$tpl -> insert('', 'next_step', 'screen_finish');
		$tpl -> insert('', 'mode', $this -> globals["mode"]);

		return $tpl -> make('templates/'. $this -> globals['lang'] . '/download_config.tpl');
	}

	/**
	* Setup is done. Throw out the finish screen
	*
	* @return string complete HTML finish screen
	*/
	function screen_finish()
	{
		$tpl = new gb_template();
		return $tpl -> make('templates/'. $this -> globals['lang'] . '/finish.tpl');
	}


	/**
	* Update is done. Throw out the finish screen
	*
	* @return string complete HTML finish screen
	*/
	function screen_finish_update()
	{
		$this -> insert_sql();

		$tpl = new gb_template();
		return $tpl -> make('templates/'. $this -> globals['lang'] . '/finish_update.tpl');
	}


	/**
	* Makes the config.php and send it to the user. Make use
	* of header functions.
	*
	* @return string config.php as downloadfile
	*/
	function make_cfg_general()
	{
		global $PHP_SELF, $SERVER_NAME;

		if ($SERVER_NAME == "")
		{
			$SERVER_NAME = $_SERVER["SERVER_NAME"];
		}
		
		if ($PHP_SELF == "")
		{
			$PHP_SELF = $_SERVER["PHP_SELF"];
		}
		
		//make pathvariables for the configfile
        $root_path = str_replace ('\\', '/', dirname(__FILE__) . '/*');
		$root_path = str_replace('/setup/*', '', $root_path);
		$root_http_path = "http://" . $SERVER_NAME . str_replace('setup/index.php', '', $PHP_SELF);

        
		//make headers for download
		header("Content-Type: text/x-delimtext; name=\"config.php\"");
		header("Content-disposition: attachment; filename=config.php");

		//insert data
		$tpl = new gb_template();

		$tpl -> insert('', 'mysql_host', $this -> globals["host"]);
		$tpl -> insert('', 'mysql_db', $this -> globals["db"]);
		$tpl -> insert('', 'mysql_user', $this -> globals["user"]);
		$tpl -> insert('', 'mysql_pass', $this -> globals["pass"]);
		$tpl -> insert('', 'mysql_prefix', $this -> globals["prefix"]);
		$tpl -> insert('', 'prefix', $this -> globals["prefix"]);

		$tpl -> insert('', 'contenido_root', $root_path);
		$tpl -> insert('', 'contenido_web', $root_http_path);

        $tpl -> insert('', 'thumbnail_width', $this -> globals["thumbnail_width"]);
        $tpl -> insert('', 'thumbnail_height', $this -> globals["thumbnail_height"]);
        
		$tpl -> insert('', 'lang', $this -> globals["lang"]);

		return $tpl -> make('templates/config.php.tpl');
	}

	/**
	* Makes the config.php and send it to the user. Make use
	* of header functions.
	*
	* @return string config.php as downloadfile
	*/
	function make_cfg_save()
	{
		global $PHP_SELF, $SERVER_NAME;

		if ($SERVER_NAME == "")
		{
			$SERVER_NAME = $_SERVER["SERVER_NAME"];
		}
		
		if ($PHP_SELF == "")
		{
			$PHP_SELF = $_SERVER["PHP_SELF"];
		}		
		
		//make pathvariables for the configfile
        $root_path = str_replace ('\\', '/', dirname(__FILE__) . '/*');
		$root_path = str_replace('/setup/*', '', $root_path);
		$root_http_path = "http://" . $SERVER_NAME . str_replace('setup/index.php', '', $PHP_SELF);

        
		//insert data
		$tpl = new gb_template();

		$tpl -> insert('', 'mysql_host', $this -> globals["host"]);
		$tpl -> insert('', 'mysql_db', $this -> globals["db"]);
		$tpl -> insert('', 'mysql_user', $this -> globals["user"]);
		$tpl -> insert('', 'mysql_pass', $this -> globals["pass"]);
		$tpl -> insert('', 'mysql_prefix', $this -> globals["prefix"]);
		$tpl -> insert('', 'prefix', $this -> globals["prefix"]);

		$tpl -> insert('', 'contenido_root', $root_path);
		$tpl -> insert('', 'contenido_web', $root_http_path);

        $tpl -> insert('', 'thumbnail_width', $this -> globals["thumbnail_width"]);
        $tpl -> insert('', 'thumbnail_height', $this -> globals["thumbnail_height"]);
        
		$tpl -> insert('', 'lang', $this -> globals["lang"]);

		$handle = fopen($root_path."/contenido/includes/config.php", "w");
		if ($handle === false)
		{
			$tpl2 = new gb_template();
			return ($tpl2->make('templates/'.$this->globals['lang'] . '/config_save_fail.tpl'));
		}
		fwrite($handle, $tpl -> make('templates/config.php.tpl'));
		fclose($handle);
		$tpl2 = new gb_template();
		return ($tpl2->make('templates/'.$this->globals['lang'] . '/config_save_success.tpl'));		
	}

	/**
	* insert the mysqldump into the datbase
	*
	* @return void
	*/
	function insert_sql()
	{
        global $SERVER_NAME, $PHP_SELF, $setup_host, $setup_database, $setup_user, $setup_password;
		global $cfg;
		
		if ($SERVER_NAME == "")
		{
			$SERVER_NAME = $_SERVER["SERVER_NAME"];
		}		

		if ($PHP_SELF == "")
		{
			$PHP_SELF = $_SERVER["PHP_SELF"];
		}
				
		$con_handle =  mysql_connect ($this -> globals["host"],
		$this -> globals["user"],
		$this -> globals["pass"]);

        $root_path = str_replace ('\\', '/', dirname(__FILE__) . '/*');
		$root_path = str_replace('/setup/*', '', $root_path);
		$root_http_path = "http://" . $SERVER_NAME . str_replace('setup/index.php', '', $PHP_SELF);

		mysql_select_db ($this -> globals["db"], $con_handle);
		$prefix = $this -> globals["prefix"];

	
		mysql_query ("ALTER TABLE ".$prefix."_keywords DROP PRIMARY KEY");
		mysql_query ("ALTER TABLE ".$prefix."_lang_value DROP PRIMARY KEY");
		$setup_host = $this -> globals["host"];
		$setup_password = $this -> globals["pass"];
		$setup_user = $this -> globals["user"];
		$setup_database = $this -> globals["db"];
		
		$thisPath = getcwd();
		chdir($thisPath."/../contenido");
		ob_start();
		
		$cfg["sql"]["sqlprefix"] = $prefix;
		
		include($thisPath."/../contenido/"."upgrade.php");
		ob_end_clean();
		chdir($thisPath);

		
		
		
		// Drop old actions, areas, etc
		mysql_query ("DELETE FROM ".$prefix."_area");
		mysql_query ("DELETE FROM ".$prefix."_actions");
		mysql_query ("DELETE FROM ".$prefix."_files");
		mysql_query ("DELETE FROM ".$prefix."_frame_files");
		mysql_query ("DELETE FROM ".$prefix."_nav_sub");
		mysql_query ("DELETE FROM ".$prefix."_nav_main");
		mysql_query ("DELETE FROM ".$prefix."_type");
		
		$result = mysql_query("SELECT idartlang, free_use_01, free_use_02, free_use_03 FROM ". $prefix."_art_lang");
		//echo "SELECT idartlang, free_use_01, free_use_02, free_use_03 FROM ". $prefix."_art_lang";

		$savedTM = "";
		
		if ($result !== false)
		{
			while ($data = mysql_fetch_array($result, MYSQL_ASSOC))
			{
				$savedTM[] = $data;
			}
		}
		//mysql_query ("ALTER TABLE ".$prefix."_keywords CHANGE 'keyword' 'idkeyword' INT( 10 ) DEFAULT '0' NOT NULL");
		 
		$sql_data = implode("",(file('sql/' . 'base.sql')));
		
		if (($this->globals["sql_target"] == "examples") ||
			($this->globals["sql_target"] == "new"))
		{
			$sql_data .= implode("",(file('sql/' . 'standard.sql')));
		}
		
		if ($this->globals["sql_target"] == "examples")
		{
			$sql_data .= implode("",(file('sql/' . 'examples.sql')));
		}
		
		
			
		
		//Load mysql_dump - file, format it and make it handy
		$sql_data = str_replace("!PREFIX!",$this->globals["prefix"],$sql_data);
		$sql_data = str_replace("<!--{contenido_root}-->",$root_path,$sql_data);
        $sql_data = str_replace("<!--{contenido_web}-->", $root_http_path,$sql_data);
				
		$sql_data = $this -> remove_remarks($sql_data);
		$sql_pieces = $this -> split_sql_file($sql_data, ';');

        
		$sql_count = count($sql_pieces);

		//DEBUGGING
		if($this -> debug){
			echo "Auszuführende querys:  $sql_count <br><br>";
		}


		for($i = 0; $i < $sql_count; $i++)
		{

			$sql = trim($sql_pieces[$i]);

			if(!empty($sql))
			{

				mysql_query ($sql, $con_handle);

				//DEBUGGING
				if($this -> debug){
					if(mysql_error() != ''){
						echo  $i+1 . ":  <font color='darkred'><b>FEHLER</b></font>  -->  " . mysql_error() . "<br>" . $sql . '<br><br>';
					}
					else{
						echo  $i+1 . ":   <font color= 'darkgreen'><b>AUSGEFÜHRT</b></font><br>". $sql . '<br><br>';
					}
				}

			}
		}
		
		$thisPath = getcwd();
		chdir($thisPath."/../contenido");
		ob_start();
		include($thisPath."/../contenido/"."upgradeseq.php");
		ob_end_clean();
		chdir($thisPath);
		
		if (is_array($savedTM))
		{
			foreach ($savedTM as $entry)
			{
				mysql_query("UPDATE ".$prefix."_art_lang SET time_move_cat = '".$entry["free_use_01"]."',".
			            	" time_target_cat ='".$entry["free_use_02"]."', time_online_move = '".$entry["free_use_03"]."' WHERE idartlang ='".$entry["idartlang"]."'");
	                        	
			}
		}
		
		$sql ="ALTER TABLE ".$prefix."_keywords DROP PRIMARY KEY, ADD PRIMARY KEY (idkeyword)";
		mysql_query($sql);
		
	}

	/**
	* removes '# blabla...' from the mysql_dump.
	* This function was originally developed for phpbb 2.01
	* (C) 2001 The phpBB Group http://www.phpbb.com
	*
	* @return string input_without_#
	*/
	function remove_remarks($sql)
	{
		$lines = explode("\n", $sql);

		// try to keep mem. use down
		$sql = "";

		$linecount = count($lines);
		$output = "";

		for ($i = 0; $i < $linecount; $i++)
		{
			if (($i != ($linecount - 1)) || (strlen($lines[$i]) > 0))
			{
					if ($lines[$i][0] != "#")
					{
						$output .= $lines[$i] . "\n";
					}
					else
					{
						$output .= "\n";
					}
				// Trading a bit of speed for lower mem. use here.
				$lines[$i] = "";
			}
		}
		return $output;
	}

	/**
	* Splits sql- statements into handy pieces.
	* This function was original developed for the phpbb 2.01
	* (C) 2001 The phpBB Group http://www.phpbb.com
	*
	* @return array sql_pieces
	*/
	function split_sql_file($sql, $delimiter)
	{
		// Split up our string into "possible" SQL statements.
		$tokens = explode($delimiter, $sql);

		// try to save mem.
		$sql = "";
		$output = array();

		// we don't actually care about the matches preg gives us.
		$matches = array();

		// this is faster than calling count($oktens) every time thru the loop.
		$token_count = count($tokens);
		for ($i = 0; $i < $token_count; $i++)
		{
			// Dont wanna add an empty string as the last thing in the array.
			if (($i != ($token_count - 1)) || (strlen($tokens[$i] > 0)))
			{
				// This is the total number of single quotes in the token.
				$total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
				// Counts single quotes that are preceded by an odd number of backslashes,
				// which means they're escaped quotes.
				$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);

				$unescaped_quotes = $total_quotes - $escaped_quotes;

				// If the number of unescaped quotes is even, then the delimiter did NOT occur inside a string literal.
				if (($unescaped_quotes % 2) == 0)
				{
					// It's a complete sql statement.
					$output[] = $tokens[$i];
					// save memory.
					$tokens[$i] = "";
				}
				else
				{
					// incomplete sql statement. keep adding tokens until we have a complete one.
					// $temp will hold what we have so far.
					$temp = $tokens[$i] . $delimiter;
					// save memory..
					$tokens[$i] = "";

					// Do we have a complete statement yet?
					$complete_stmt = false;

					for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); $j++)
					{
						// This is the total number of single quotes in the token.
						$total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
						// Counts single quotes that are preceded by an odd number of backslashes,
						// which means theyre escaped quotes.
						$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);

						$unescaped_quotes = $total_quotes - $escaped_quotes;

						if (($unescaped_quotes % 2) == 1)
						{
							// odd number of unescaped quotes. In combination with the previous incomplete
							// statement(s), we now have a complete statement. (2 odds always make an even)
							$output[] = $temp . $tokens[$j];

							// save memory.
							$tokens[$j] = "";
							$temp = "";

							// exit the loop.
							$complete_stmt = true;
							// make sure the outer loop continues at the right point.
							$i = $j;
						}
						else
						{
							// even number of unescaped quotes. We still dont have a complete statement.
							// (1 odd and 1 even always make an odd)
							$temp .= $tokens[$j] . $delimiter;
							// save memory.
							$tokens[$j] = "";
						}

					} // for..
				} // else
			}
		}
		return $output;
	}
}

/**
* My little templateclass
* 2000 by Bjoern Brockmann
*/
class gb_template
{
	function insert($Loop, $TemplateName, $ToInsert)
	{
	  global $TemplateArray;
	  global $TemplateLoopArray;

	  if($Loop ==""){
	  $TemplateArray[$TemplateName] = $ToInsert;
	  }

	  else{
	  $TemplateLoopArray[$Loop][$TemplateName][] = $ToInsert;
	  }
	}


	function make($File)
	{
	  global $TemplateArray;
	  global $TemplateLoopArray;

	  $Matrix = implode("",(file($File)));

	  if(is_array($TemplateLoopArray)){
	    $KeysLoopname = array_keys($TemplateLoopArray);
	    for($f = 0; $f < count($KeysLoopname); $f++){
              $Start = strpos($Matrix, "<!--{start:".$KeysLoopname[$f]."}-->");
	      $Stop = strpos($Matrix, "<!--{stop:".$KeysLoopname[$f]."}-->");
	      $LoopLength = $Stop - $Start;
	      $Loop = substr( $Matrix, $Start, $LoopLength);
	      $KeysLoopTemplate = array_keys($TemplateLoopArray[$KeysLoopname[$f]]);
	      $KeysLoopValue = array_keys($TemplateLoopArray[$KeysLoopname[$f]][$KeysLoopTemplate[0]]);
	      for($t = 0; $t < count($KeysLoopValue); $t++){
	        $Loopb = $Loop;
	        for($s = 0; $s < count($KeysLoopTemplate); $s++){
		  $Loopb = str_replace("<!--{".$KeysLoopTemplate[$s]."}-->", $TemplateLoopArray[$KeysLoopname[$f]][$KeysLoopTemplate[$s]][$t], $Loopb);
	        }
	        $LoopFinal = $LoopFinal.$Loopb;
	      }
	      $Matrix = str_replace ($Loop, $LoopFinal, $Matrix);
	      $Matrix = str_replace ("<!--{start:".$KeysLoopname[$f]."}-->", "", $Matrix);
	      $Matrix = str_replace ("<!--{stop:".$KeysLoopname[$f]."}-->", "", $Matrix);
	      $LoopFinal ="";
	      $Matrix = str_replace ($Loop, $Start, $Matrix);
	    }
	  }

	  if(is_array($TemplateArray)){
	    $Keys = array_keys($TemplateArray);
	    for($i = 0; $i < count($Keys); $i++){
	      $Matrix = str_replace("<!--{".$Keys[$i]."}-->", $TemplateArray[$Keys[$i]], $Matrix);
	    }
	  }
          return $Matrix;
        }

	 function flush()
	 {
		 global $TemplateArray;
		 global $TemplateLoopArray;

		 unset($TemplateArray);
		 unset($TemplateLoopArray);
	 }
}
?>
