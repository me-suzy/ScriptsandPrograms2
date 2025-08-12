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


function cleanPrivateTable($table) {
	$sql = "delete from $table where objectid not in (select objectid from object)";
	$db =& getDbConn();
	$db->query($sql);
	echo $table." : ".$db->Affected_Rows()."<br>";
}

function cleanTableByCol($table,$colname) {
	$sql = "delete from $table where $colname not in (select objectid from object)";
	$db =& getDbConn();
	$db->query($sql);
	echo $table." : ".$db->Affected_Rows()."<br>";
}

function cleanObjectByCol($colname) {
	echo "Deleting from object by $colname <br>";
	$sql = "select objectid from object where $colname != 0 AND $colname not in (select objectid from object)";
	$db =& getDbConn();
	$a = $db->getCol($sql);
	$res = false;
	foreach ($a as $cur) {
		$db->query("delete from object where objectid = $cur");
		echo "Deleting object by $colname: $cur <br>";
		$res = true;
	}
	return $res;
}

function setSystemUserByCol($colname) {
	$userhandler =& getUserHandler();
	$systemid = $userhandler->getSystemAccountId();
	echo "Setting to system user by $colname <br>";
	$sql = "select objectid from object where $colname not in (select objectid from object)";
	$db =& getDbConn();
	$a = $db->getCol($sql);
	$res = false;
	foreach ($a as $cur) {
		$db->query("update object set $colname = $systemid where objectid = $cur");
		echo "Setting system user on objectid: $cur <br>";
		$res = true;
	}
	return $res;
}

function cleanPrivateTables() {
	echo "<h1>Oprydning i klassetabeller</h1>";
	$db =& getDbConn();
	$a = $db->getCol("select datatype from class where type <> 5 order by datatype");
	foreach ($a as $cur) {
		if (owTry($cur)) {
			$obj = owNew($cur);
			cleanPrivateTable($obj->objecttable);
		}
	}
}

function cleanSpecialTables() {
	echo "<h1>Oprydning i specialtabeller</h1>";
	cleanTableByCol('object_access','objectid');
	cleanTableByCol('object_category','objectid');
	cleanTableByCol('object_category','categoryid');
	cleanTableByCol('object_dependency','objectid');
	cleanTableByCol('object_dependency','dependson');
	cleanTableByCol('object_extradata','objectid');
	cleanTableByCol('object_multiple','objectid');
	cleanTableByCol('object_search','objectid');
	cleanTableByCol('object_variantfield','objectid');

	cleanTableByCol('document_count','objectid');
	cleanTableByCol('documentsection_modules','documentsectionid');

	cleanTableByCol('extradata_data','objectid');
	cleanTableByCol('listcol_data','objectid');
	cleanTableByCol('profile_data','objectid');
	cleanTableByCol('sortcol_data','objectid');

	cleanTableByCol('template_include','templateid');
	cleanTableByCol('template_include','includeid');
	cleanTableByCol('template_modules','template');
	cleanTableByCol('usergroupmember','userid');
	cleanTableByCol('usergroupmember','groupid');
}

function cleanObjectTable() {
	echo "<h1>Oprydning i OBJECT</h1>";
	$db =& getDbConn();
	$db->query("delete from object where deleted = 1");
	$db->query("delete from object where futurerevisionof <> 0");
	$db->query("delete from object where oldrevisionof <> 0");
	$db->query("delete from object where type not in (select datatype from class)");
	
	$again = true;
	while ($again) {
		$again = false;
		if (cleanObjectByCol('variantof')) $again = true;
		if (cleanObjectByCol('futurerevisionof')) $again = true;
		if (cleanObjectByCol('oldrevisionof')) $again = true;
		if (cleanObjectByCol('parentid')) $again = true;
	}
	setSystemUserByCol('createdby');
	setSystemUserByCol('changedby');
	setSystemUserByCol('checkedby');
}

function cleanSite() {
	$sql = "delete from object where site not in (select site from site)";
	$db =& getDbConn();
	$db->query($sql);
	# slet også eventuelle site-biblioteker
}

function cleanCache() {
}

function checkClasses() {
	echo "<h1>Ukendte klasser</h1>";
	$db =& getDbConn();

	$a = $db->getCol("select datatype from class where type <> 5 order by datatype");
	foreach ($a as $cur) {
		if (!owTry($cur) && !owIsExtension($cur)) {
			echo $cur ."<br>";
		}
	}
}

function checkTables() {
	$db =& getDbConn();
	$arr = $db->getCol("show tables");

	$a = $db->getCol("select datatype from class where type <> 5");
	foreach ($a as $cur) {
		if (owTry($cur)) {
			$obj = owNew($cur);
			unset($arr[array_search($obj->objecttable,$arr)]);
		}
	}
	unset($arr[array_search('class',$arr)]);
	unset($arr[array_search('classname',$arr)]);
	unset($arr[array_search('dbversion',$arr)]);
	unset($arr[array_search('document_count',$arr)]);
	unset($arr[array_search('document_statistics',$arr)]);
	unset($arr[array_search('documentsection_modules',$arr)]);
	unset($arr[array_search('ext_listingdata',$arr)]);
	unset($arr[array_search('extradata_data',$arr)]);
	unset($arr[array_search('listcol_data',$arr)]);
	unset($arr[array_search('object',$arr)]);
	unset($arr[array_search('object_access',$arr)]);
	unset($arr[array_search('object_category',$arr)]);
	unset($arr[array_search('object_dependency',$arr)]);
	unset($arr[array_search('object_extradata',$arr)]);
	unset($arr[array_search('object_multiple',$arr)]);
	unset($arr[array_search('object_search',$arr)]);
	unset($arr[array_search('object_statistics',$arr)]);
	unset($arr[array_search('object_variantfield',$arr)]);
	unset($arr[array_search('profile_data',$arr)]);
	unset($arr[array_search('site',$arr)]);
	unset($arr[array_search('sortcol_data',$arr)]);
	unset($arr[array_search('statistics_login',$arr)]);
	unset($arr[array_search('system_colors',$arr)]);
	unset($arr[array_search('system_country',$arr)]);
	unset($arr[array_search('system_fonts',$arr)]);
	unset($arr[array_search('system_languages',$arr)]);
	unset($arr[array_search('template_include',$arr)]);
	unset($arr[array_search('template_modules',$arr)]);
	unset($arr[array_search('usergroupmember',$arr)]);
	echo "<h1>Ukendte tabeller</h1>";
	foreach ($arr as $cur) {
		echo $cur ."<br>";
	}
}

function optimizeTables() {
	$db =& getDbConn();
	$arr = $db->getCol("show tables");

	foreach ($arr as $cur) {
		$db->query("optimize table $cur");
	}
}

cleanSite();
cleanObjectTable();
cleanPrivateTables();
cleanSpecialTables();
checkClasses();
checkTables();
owReIndexAll();
optimizeTables();
?>