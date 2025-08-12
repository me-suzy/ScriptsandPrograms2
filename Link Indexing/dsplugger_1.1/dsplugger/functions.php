<?php
function get_params($table) {
	require_once('mysql.class.php');
	require('config.php');
	
	$sql = &new database;
	$sql->connect($db['host'],$db['database'],$db['user'],$db['password']);
	
	$result = $sql->dbQuery("SELECT * FROM $table");
	while ($row = $sql->dbFetchArray($result)) {
		$param[$row['object']] = $row['setting'];
	}
	return $param;
}

function set_params($table,$params) {
	require_once('mysql.class.php');
	require('config.php');
	
	$sql = &new database;
	$sql->connect($db['host'],$db['database'],$db['user'],$db['password']);
	
	$sql->dbDelete($table,"","");
	$fields = "";
	foreach ($params as $key=>$value) {
		if ($key != "submit") {
			$result = $sql->dbSave($table,"object,setting","'$key','$value'");
		}
	}
	return $result;
}

function get_style() {
	$body = get_params("dsp_style_body");
	echo "body,a {\n";
	foreach ($body as $key=>$value) {
		if ($key != "border-color") {
			echo "\t$key" . ": $value;\n";
		}
	}
	echo "}\n";
	$form = get_params("dsp_style_form");
	echo "input {\n";
	foreach ($form as $key=>$value) {
		echo "\t$key" . ": $value;\n";
	}
	echo "}\n";
}

function get_plugs($limit="no") {
	require_once('mysql.class.php');
	require('config.php');
	$sql = &new database;
	$sql->connect($db['host'],$db['database'],$db['user'],$db['password']);
	$setting = get_params("dsp_settings");
	if ($limit == "limit") {
		$num = $setting['columns'] * $setting['rows'];
		$result = $sql->dbQuery("SELECT * FROM dsp_plugs ORDER BY id DESC LIMIT 0,$num");
	} else {
		$result = $sql->dbQuery("SELECT * FROM dsp_plugs ORDER BY id DESC");
	}
	while ($row = $sql->dbFetchArray($result)) {
		$plug[$row['id']] = array(id=>$row['id'],url=>$row['url'],image=>$row['image'],ip=>$row['ip']);
	}
	return $plug;
}

function add_plug($url,$image,$ip) {
	require_once('mysql.class.php');
	require('config.php');
	$sql = &new database;
	$sql->connect($db['host'],$db['database'],$db['user'],$db['password']);
	$result = $sql->dbQuery("SELECT * FROM dsp_banned WHERE ip = '$ip'");
	if ($sql->dbNumRows($result) > 0) {
		$return = "Error: IP is banned.";
	} else {
		$urlcheck = preg_match("/^[a-zA-Z0-9(\.)(\/)(\:)(\-)(\_)(\?)]+$/i", $url);
		$imagecheck = preg_match("/^[a-zA-Z0-9(\.)(\/)(\:)(\-)(\_)(\?)]+$/i", $image);
		if (!$urlcheck || !$imagecheck) {
			$return = "Error: disallowed characters in url or image url.";
		} else {
			if (!preg_match("/^(http:\/\/)/i", $url)) {
				$url = "http://{$url}";
			}
			if (!preg_match("/^(http:\/\/)/i", $image)) {
				$image = "http://{$image}";
			}
			$domain = str_replace("http://","",$url);
			$domain = str_replace("www.","",$domain);
			$path = strstr($domain,"/");
			$domain = str_replace($path,"",$domain);
			$setting = get_params("dsp_settings");
			$num = $setting['columns'] * $setting['rows'];
			$result = $sql->dbQuery("SELECT url FROM dsp_plugs WHERE url LIKE '%$domain%' LIMIT 0,$num");
			if ($sql->dbNumRows($result) > 0) {
				$return = "Error: Plug from same domain is still visable.";
			} else {
				$sql->dbSave("dsp_plugs","url,image,ip","'$url','$image','$ip'");
				$return = 1;
			}
		}
	}
	return $return;
}
function delete_plug($var) {
	require_once('mysql.class.php');
	require('config.php');
	$sql = &new database;
	$sql->connect($db['host'],$db['database'],$db['user'],$db['password']);
	
	$setting = get_params("dsp_settings");
	$num = $setting['columns'] * $setting['rows'];
	if (isset($var['delete_all'])) {
		$result = $sql->dbDelete("dsp_plugs","","");
	} elseif (isset($var['delete_all_but'])) {
		$result1 = $sql->dbQuery("SELECT * FROM dsp_plugs ORDER BY id DESC");
		$n = 1;
		while ($row = $sql->dbFetchArray($result1)) {
			if ($n > $num) {
				$result = $sql->dbDelete("dsp_plugs","id",$row['id']);
			}
			$n++;
		}
	} elseif (isset($var['delete'])) {
		$result = $sql->dbDelete("dsp_plugs","id",$var['id']);
	}
	return $result;
}
function ban_ip($var) {
	require_once('mysql.class.php');
	require('config.php');
	$sql = &new database;
	$sql->connect($db['host'],$db['database'],$db['user'],$db['password']);
	
	if (isset($var['ban_delete'])) {
		$result = $sql->dbSave("dsp_banned","ip,comment","'{$var['ip']}','{$var['comment']}'");
		$result1 = $sql->dbDelete("dsp_plugs","ip",$var['ip']);
	} elseif (isset($var['ban'])) {
		$result = $sql->dbSave("dsp_banned","ip,comment","'{$var['ip']}','{$var['comment']}'");
	} elseif (isset($var['unban_all'])) {
		$result = $sql->dbDelete("dsp_banned","","");
	} elseif (isset($var['unban'])) {
		$result = $sql->dbDelete("dsp_banned","ip",$var['ip']);
	}
	return $result;
}
function get_banned() {
	require_once('mysql.class.php');
	require('config.php');
	$sql = &new database;
	$sql->connect($db['host'],$db['database'],$db['user'],$db['password']);
	
	$result = $sql->dbQuery("SELECT * FROM dsp_banned");
	while ($row = $sql->dbFetchArray($result)) {
		$banned[$row['ip']] = array(ip=>$row['ip'],comment=>$row['comment']);
	}
	return $banned;
}
function image_create() {
	require_once('mysql.class.php');
	require('config.php');
	$setting = get_params('dsp_settings');
	$x = $setting['width'];
	$y = $setting['height'];
	$sql = &new database;
	$sql->connect($db['host'],$db['database'],$db['user'],$db['password']);
	$result = $sql->dbQuery("select size from dsp_image where size = '{$setting['width']}x{$setting['height']}'");
	if ($sql->dbNumRows($result) > 0) {
		return 1;
	} else {
	   $image = imagecreate($x,$y);
		if (!$image) {
			return 0;
		} else {
			$white = imagecolorallocate($image,255,255,255);
			$text_color = imagecolorallocate($image,0,0,0);
			imagestring($image,3,2,2,"No Image",$text_color);
			ob_start();
			imagepng($image);
			$imagevar = ob_get_contents();
			ob_end_clean();
			$sql = &new database;
			$sql->connect($db['host'],$db['database'],$db['user'],$db['password']);
			$result = $sql->dbSave("dsp_image","size,image","'{$setting['width']}x{$setting['height']}','$imagevar'");
			return 1;
		}
	}
}
// Copyright 2005 Chris Warren
?>