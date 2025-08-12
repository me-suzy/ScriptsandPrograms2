<?php

class Application {
	
	private static $websiteName;
	private static $websiteUrl;
	private static $websitePath;
	private static $websiteEmail;
	private static $ftpHost;
	private static $ftpDataPath;
	private static $ftpUsername;
	private static $ftpPassword;
	private static $executionTime;
	
	/*
	   +----------------------------------------------------------------
	   | Sucht das uebergebene Array (configArray) nach einem Wert und	
	   | gibt den zurueck. Falls der Wert nicht vorhanden ist wird er	
	   | als leere Zeichenkette initialisiert und zurueckgegeben.		
	   +----------------------------------------------------------------
	*/
	private static function initConfigValue(&$configArray, $key, $default="") {
		if(!isset($configArray[$key])) {
			$configArray[$key] = $default;
		}
		return $configArray[$key];
	}
	
	/*
	   +----------------------------------------------------------------
	   | Initalisiert alle Applikationseinstellungen und prueft ob 		
	   | alle Einstellungen gesetzt sind.								
	   +----------------------------------------------------------------
	*/
	public static function initWithConfigArray($configArray) {
		Application::$websiteName	= Application::initConfigValue($configArray, 'websiteName');
		Application::$websiteUrl	= Application::initConfigValue($configArray, 'websiteUrl');
		Application::$websitePath	= Application::initConfigValue($configArray, 'websitePath');
		Application::$websiteEmail	= Application::initConfigValue($configArray, 'websiteEmail');
		Application::$ftpHost		= Application::initConfigValue($configArray, 'ftpHost');
		Application::$ftpDataPath	= Application::initConfigValue($configArray, 'ftpDataPath');
		Application::$ftpUsername	= Application::initConfigValue($configArray, 'ftpUsername');
		Application::$ftpPassword	= Application::initConfigValue($configArray, 'ftpPassword');
		Application::$executionTime	= Application::initConfigValue($configArray, 'executionTime');
	}
	
	// -------------------------------------------------------------
	public static function getWebsiteName() {
		return Application::$websiteName;
	}
	
	// -------------------------------------------------------------
	public static function getWebsiteUrl() {
		return Application::$websiteUrl;
	}
	
	// -------------------------------------------------------------
	public static function getWebsitePath() {
		return Application::$websitePath;
	}
	
	// -------------------------------------------------------------
	public static function getWebsiteEmail() {
		return Application::$websiteEmail;
	}
	
	// -------------------------------------------------------------
	public static function getFtpHost() {
		return Application::$ftpHost;
	}
	// -------------------------------------------------------------
	public static function getFtpDataPath() {
		return Application::$ftpDataPath;
	}
	
	// -------------------------------------------------------------
	public static function getFtpUsername() {
		return Application::$ftpUsername;
	}
	
	// -------------------------------------------------------------
	public static function getFtpPassword() {
		return Application::$ftpPassword;
	}
	
	// -------------------------------------------------------------
	public static function getExecutionTime() {
		return Application::$executionTime;
	}
}

?>