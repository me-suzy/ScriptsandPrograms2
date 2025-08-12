<?php

/*
	+----------------------------------------------------------------
	| database connection configuration parameters					 
	+----------------------------------------------------------------
*/

$dbHost = "";		// mysql server host
$dbName = "";		// database name	
$dbUser = "";		// database username
$dbPass = "";		// database password

class Config {
	
	private static $dbLink;
	
	public static function getDbLink() {
		return Config::$dbLink;
	}
	
	/*
	   +----------------------------------------------------------------
	   | Initialisiert die Datenbankverbindung, die im Programmcode		
	   | folgendermassen benutzt werden kann: Config::getDbLink()		
	   +----------------------------------------------------------------
	*/
	
	public static function initDbAccess($dbHost, $dbName , $dbUser, $dbPass) {
		Config::$dbLink = mysql_connect($dbHost, $dbUser, $dbPass);
		mysql_select_db($dbName, Config::$dbLink);
		$errorNumber = mysql_errno(Config::$dbLink);
		
		if(Config::$dbLink === false || $errorNumber != 0) {
			Application::errorPage("MySQL Error $errorNumber", mysql_error(Config::$dbLink));
		}
	}
	
	/*
	   +----------------------------------------------------------------
	   | Initialisiert die Applikationseinstellungen wie Titel, 		
	   | Thumbnailgroesse usw.											
	   +----------------------------------------------------------------
	*/
	public static function initApplication() {
		$configArray = array();
		
		$sql = "SELECT * FROM `website_config`";
		$res = mysql_query($sql, Config::getDbLink());
		while($data = mysql_fetch_assoc($res)) {
			$key = $data['config_key'];
			$configArray[$key] = $data['config_value'];
		}
		
		$ok = Application::initWithConfigArray($configArray);
	}
}

Config::initDbAccess($dbHost, $dbName, $dbUser, $dbPass);
Config::initApplication();

?>