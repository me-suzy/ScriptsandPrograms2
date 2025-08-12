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
	$sel_tab="2.5";
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
echo "\n<tr>\n<td colspan=1  background='../cms/images/stab-bg.gif'><b>Cleanup common words </b></td></tr>";
echo $ruler;
echo "<tr><td>";

$locks = phpdigMySelect($id_connect,'SELECT locked FROM '.PHPDIG_DB_PREFIX.'sites WHERE locked = 1');
if (is_array($locks)) {
    phpdigPrnMsg('onelock');
}
else {
	mysql_query('UPDATE '.PHPDIG_DB_PREFIX.'sites SET locked=1',$id_connect);
	//set the max time to an hour
	set_time_limit(3600);
	$numtot = 0;
	$common_words = phpdigComWords("$relative_script_path/includes/common_words.txt");
	while (list($common) = each($common_words)){
		//list of common words in the keywords table
		$query = "select key_id from ".PHPDIG_DB_PREFIX."keywords where keyword like '$common'";
		$res = mysql_query($query,$id_connect);
		if ($res){
			while (list($key_id) = mysql_fetch_row($res)){
				//delete references to this keyword in the engine table
				$query = "DELETE FROM ".PHPDIG_DB_PREFIX."engine WHERE key_id=$key_id";
				mysql_query($query,$id_connect);
				$numdel = mysql_affected_rows($id_connect);
				print "$numdel".phpdigMsg('deletedfor')."$common ($key_id)<br />";
				$numtot += $numdel;
			}
			//delete this common word from the keywords table
			$query = "DELETE from ".PHPDIG_DB_PREFIX."keywords where keyword like '$common'";
		}
		mysql_query($query,$id_connect);
	}
	print phpdigMsg('cleanuptotal')."$numtot".phpdigMsg('cleaned');
	mysql_query('UPDATE '.PHPDIG_DB_PREFIX.'sites SET locked=0',$id_connect);
}
echo "<br />Done".$ruler."</td></tr></table>";
jetstream_footer();