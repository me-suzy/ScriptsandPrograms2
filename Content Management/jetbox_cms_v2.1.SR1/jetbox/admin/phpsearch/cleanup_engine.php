<?php
/*
--------------------------------------------------------------------------------
PhpDig 1.6.x
This program is provided under the GNU/GPL license.
See LICENSE file for more informations
All contributors are listed in the CREDITS file provided with this package

PhpDig Website : http://phpdig.toiletoine.net/
Contact email : phpdig@toiletoine.net
Author and main maintainer : Antoine Bajolet (fr) bajolet@toiletoine.net
--------------------------------------------------------------------------------
*/
$auth='yes';
//name of this file, starting with a slash
$thisfile ="/".array_pop(explode("/", $_SERVER["SCRIPT_NAME"]));
//if (!isset($uid) && !isset($mysession)){
//	$uid=$HTTP_COOKIE_VARS[postlister_uid];
//	$mysession=$HTTP_COOKIE_VARS[postlister_mysession];
//}
//$toptab=array("8", "3");
//$seltoptab="8";
//title of the administration page
$pagetitle="Search Engine";
$current_time=time();

function listrecords($error='', $blurbtype='notify'){
	global $titel, $menu;
	//sidehoved($titel = "", $menu = 1);
};
include("../../includes/includes.inc.php");
$result = mysql_query("SELECT id, uid FROM container WHERE cfile='/../phpsearch/index.php'");
$container_id= @mysql_result($result,0,'id');

$jetstream_nav = array (
		"2.1"		=>  array($jetstream_url . "/../phpsearch/index.php" => "Overview"),		//Compose
		"2.2"		=>  array($jetstream_url . "/../phpsearch/statistics.php" => "Statistics"),		//Add/delete subscriber
		"2.3"		=>  array($jetstream_url . "/../phpsearch/cleanup_engine.php" => "Clean index"),		//Add/delete subscriber
		"2.4"		=>  array($jetstream_url . "/../phpsearch/cleanup_keywords.php" => "Clean keywords"),		//Add/delete subscriber
		"2.5"		=>  array($jetstream_url . "/../phpsearch/cleanup_common.php" => "Clean common words"),		//Add/delete subscriber

);

session_start();
ob_start();
if (isset($_SESSION["uid"])){
	jetstream_header($pagetitle);
	$sel_tab="2.3";
	$tabs[]="2.1";
	$tabs[]="2.2";
	$tabs[]="2.3";
	$tabs[]="2.4";
	$tabs[]="2.5";
	jetstream_ShowSections($tabs, $jetstream_nav, $sel_tab);
}
elseif (new_visit()){
	jetstream_header($pagetitle);
	$tabs[]="2.1";
	//$tabs[]="2.2";
	jetstream_ShowSections($tabs, $jetstream_nav, "2.1");
}
else{
	exit;
}

$relative_script_path = '../../includes/phpdig';
include "$relative_script_path/includes/config.php";

$span="3";
$ruler = '<tr><td colspan="'.$span.'" class="ruler"></td></tr>';
$ruler2 = '<tr><td colspan="'.$span.'" class="ruler2"></td></tr>';

echo "\n<br><table border=\"0\" width=100% cellspacing=\"0\" cellpadding=\"4\">";
echo $ruler2;
echo "\n<tr>\n<td colspan=1  background='../cms/images/stab-bg.gif'><b>".phpdigMsg('cleaningindex')."</b></td></tr>";
echo $ruler;

$del = 0;
set_time_limit(3600);
$locks = phpdigMySelect($id_connect,'SELECT locked FROM '.PHPDIG_DB_PREFIX.'sites WHERE locked = 1');
if (is_array($locks)) {
	phpdigPrnMsg('onelock');
}
else {
	mysql_query('UPDATE '.PHPDIG_DB_PREFIX.'sites SET locked=1',$id_connect);
	//print phpdigMsg('pwait')." ...<br /></td></tr>";
	

	$query = "SET OPTION SQL_BIG_SELECTS=1";
	mysql_query($query,$id_connect);
	echo "<tr bgcolor=\"#F6F6F6\"  class='tab-g' ><td>Step One</td></tr>";
	echo $ruler;
	echo "<tr><td>";
	//list of key_id's in engine table
	$query = "SELECT key_id FROM ".PHPDIG_DB_PREFIX."engine GROUP BY key_id";
	$id = mysql_query($query,$id_connect);
	while (list($key_id) = mysql_fetch_row($id)){
		//search this id in the keywords table
		$query = "SELECT key_id FROM ".PHPDIG_DB_PREFIX."keywords WHERE key_id=$key_id";
		$id_key = mysql_query($query,$id_connect);
		if (mysql_num_rows($id_key) < 1){
			//if non-existent, delete this useless id from the engine table
			$del ++;
			print "X ";
			$query_delete = "DELETE FROM ".PHPDIG_DB_PREFIX."engine WHERE key_id=$key_id";
			$id_del = mysql_query($query_delete,$id_connect);
		}
		else{
			print ". ";
			mysql_free_result($id_key);
		}
	}

	echo "<br>Done</td></tr>";
	echo $ruler;
	echo "<tr bgcolor=\"#F6F6F6\"  class='tab-g' ><td>Step Two</td></tr>";
	echo $ruler;
	echo "<tr><td>";
	//explore keywords to find bad values
	$query = "SELECT key_id FROM ".PHPDIG_DB_PREFIX."keywords WHERE twoletters REGEXP \"^[^0-9a-zßðþ]\"";
	$id = mysql_query($query,$id_connect);
	if (mysql_num_rows($id) > 0) {
		while (list($key_id) = mysql_fetch_row($id)) {
			echo '° ';
			$query_delete = "DELETE FROM ".PHPDIG_DB_PREFIX."engine WHERE key_id=$key_id";
			mysql_query($query_delete,$id_connect);
		}
	}
	echo "<br>Done</td></tr>";
	echo $ruler;
	echo "<tr bgcolor=\"#F6F6F6\"  class='tab-g' ><td>Step Three</td></tr>";
	echo $ruler;
	echo "<tr><td>";
	//list of spider_id from engine table
	$query = "SELECT spider_id FROM ".PHPDIG_DB_PREFIX."engine GROUP BY spider_id";
	$id = mysql_query($query,$id_connect);
	while (list($spider_id) = mysql_fetch_row($id)){
		$query = "SELECT spider_id FROM ".PHPDIG_DB_PREFIX."spider WHERE spider_id=$spider_id";
		$id_spider = mysql_query($query,$id_connect);
		if (mysql_num_rows($id_spider) < 1){
			//if no-existent in the spider page, delete from engine
			$del ++;
			print "X ";
			$query_delete = "DELETE FROM ".PHPDIG_DB_PREFIX."engine WHERE spider_id=$spider_id";
			$id_del = mysql_query($query_delete,$id_connect);
		}
		else
			print "- ";
			mysql_free_result($id_spider);
	}
	echo "<br>Done</td></tr>";
	echo "<tr><td>&nbsp;</td></tr>";

	echo $ruler2;
	echo "<tr bgcolor=\"#F6F6F6\" class='tab-g'><td>";
	
	echo ($del ? "0" : $del);
	//if ($del){
	////	print "$del".phpdigMsg('enginenotok');
	//}
	//else{
	//	print "".phpdigMsg('engineok');
	//}
	echo " changes, search engine is up-to-date.</td></tr>$ruler<tr><td><br>Cleaning up the search engine speeds up the search process, this should be done after big website updates.</td></tr>";
	mysql_query('UPDATE '.PHPDIG_DB_PREFIX.'sites SET locked=0',$id_connect);
}
echo "</table>";
jetstream_footer();