<?php

function truncateConfig() {
	
	$sql = "TRUNCATE TABLE `website_config`";
	$res = mysql_query($sql, Config::getDbLink());
}

function setConfigValue($key, $value) {
	
	$sql = "INSERT `website_config` ";
	$sql .= "(`config_key`,`config_value`) VALUES ";
	$sql .= "('$key','$value')";
	$res = mysql_query($sql, Config::getDbLink());
}

if(isset($_POST['sent_data'])) {
	
	truncateConfig();
	setConfigValue("websiteName", $_POST['websiteName']);
	setConfigValue("websiteUrl", $_POST['websiteUrl']);
	setConfigValue("websitePath", $_POST['websitePath']);
	setConfigValue("websiteEmail", $_POST['websiteEmail']);
	setConfigValue("ftpHost", $_POST['ftpHost']);
	setConfigValue("ftpDataPath", $_POST['ftpDataPath']);
	setConfigValue("ftpUsername", $_POST['ftpUsername']);
	setConfigValue("ftpPassword", $_POST['ftpPassword']);
	setConfigValue("executionTime", $_POST['executionTime']);
	
	Utilities::redirect("admin.php?action=settings.display");
}

?>