<?php
session_start();
require_once('config.php');
require_once('adodb.php');
require_once('core/util/func.php');
require_once('core/systemclass.php');
require_once('ow.php');
require_once('basic_context.php');
require_once('basic_control.php');
require_once('basic_error.php');
require_once('basic_event.php');
require_once('basic_user.php');

$userhandler =& getUserHandler();
$userhandler->setWebuser(false);

if (!$userhandler->LoggedIn() && !$userhandler->getLevel() == ACCESS_ADMINISTRATOR) {
	header('Location: index.php?expired=1&load='.urlencode($_SERVER['REQUEST_URI']));
}
header("Cache-Control: private");

function checkByLang($langfile,$lang) {
	$LANG = array();
	if (file_exists($langfile.'.'.$lang.'.php')) {
		require($langfile.'.'.$lang.'.php');
		if (empty($LANG['name']) || !isset($LANG['name'])) echo "Missing name in $langfile<br>";
		if (empty($LANG['label_name']) || !isset($LANG['label_name'])) echo "Missing label name in $langfile<br>";
	} else {
		echo "Missing file $langfile.$lang.php<br>";
	}
	return sizeof($LANG);
}

function checkLangFile($entry) {
	if ($entry['app'] != '') {
		$langfile = 'app/'.$entry['app'].'/lang/'.$entry['app'].'_'.$entry['datatype'];
	} elseif ($entry['type'] == 1) {
		$langfile = 'extension/'.$entry['datatype'].'/lang/'.$entry['datatype'];
	} else {
		$langfile = 'lang/'.$entry['datatype'];
	}
	$res1 = checkByLang($langfile,'da');
	if ($entry['app'] == '') {
		$res2 = checkByLang($langfile,'en');
		if ($res1 != $res2) echo "Language elements don't match in $langfile <br>";
	}
}

function checkLang() {
	$db =& getDbConn();

	$a = $db->getAll("select * from class where type <> 5 order by datatype");
	foreach ($a as $cur) {
		if (owTry($cur['datatype'])) {
			checkLangFile($cur);
		}
	}
}

checkLang();
?>