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
include("../../includes/includes.inc.php");
$result = mysql_query("SELECT id, uid FROM container WHERE cfile='/../phpsearch/index.php'");
$container_id= @mysql_result($result,0,'id');

$jetstream_nav = array (
		"2.1"		=>  array($jetstream_url . "/../phpsearch/index.php" => "Overview"),		//Compose
		"2.2"		=>  array($jetstream_url . "/../phpsearch/index.php" => "Statistics"),		//Add/delete subscriber
		"2.3"		=>  array($jetstream_url . "/../phpsearch/cleanup_engine.php" => "Clean index"),		//Add/delete subscriber
		"2.4"		=>  array($jetstream_url . "/../phpsearch/cleanup_keywords.php" => "Clean keywords"),		//Add/delete subscriber
		"2.5"		=>  array($jetstream_url . "/../phpsearch/cleanup_common.php" => "Clean common words"),		//Add/delete subscriber
);

session_start();
ob_start();
if (isset($_SESSION["uid"])){
	jetstream_header($pagetitle);
	$sel_tab="2.2";
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
//include "$relative_script_path/libs/auth.php";
include "robot_functions.php";
$query = "SET OPTION SQL_BIG_SELECTS=1";
mysql_query($query,$id_connect);

$start_table_template = "";
$end_table_template = "";
$line_template = "<tr>%s</tr>\n";
$title_cell_template = "\t<td class='tab-g' bgcolor=\"#F6F6F6\">%s</td>\n";
$cell_template[0] = "\t<td>%s</td>\n";
//echo "\n<tr bgcolor=\"#F6F6F6\">";

$mod_template = count($cell_template);
flush();

$span="6";
$ruler = '<tr><td colspan="'.$span.'" class="ruler"></td></tr>';
$ruler2 = '<tr><td colspan="'.$span.'" class="ruler2"></td></tr>';

// extract http vars
extract(phpdigHttpVars(array('type' => 'string')));
set_time_limit(300);
echo "\n<br><table border=\"0\" width=100% cellspacing=\"0\" cellpadding=\"4\">";
echo $ruler2;
echo "\n<tr>\n<td colspan=6  background='../cms/images/stab-bg.gif'><b>Most keywords</b></td></tr>";
echo $ruler;
print_stat_rows($id_connect,'mostkeys');
echo "<tr><td>&nbsp;</td></tr>";
echo $ruler2;
echo "\n<tr>\n<td colspan=6  background='../cms/images/stab-bg.gif'><b>Richest pages</b></td></tr>";
echo $ruler;
print_stat_rows($id_connect,'mostpages');
echo "</table>";
echo "\n<table border=\"0\" width=100% cellspacing=\"0\" cellpadding=\"4\">";

echo "<tr><td>&nbsp;</td></tr>";
echo $ruler2;
echo "\n<tr>\n<td colspan=6  background='../cms/images/stab-bg.gif'><b>Most search terms</b></td></tr>";
echo $ruler;
print_stat_rows($id_connect,'mostterms');

echo "<tr><td>&nbsp;</td></tr>";
echo $ruler2;
echo "\n<tr>\n<td colspan=6  background='../cms/images/stab-bg.gif'><b>Search terms with most results</b></td></tr>";
echo $ruler;
print_stat_rows($id_connect,'largestresults');

echo "<tr><td>&nbsp;</td></tr>";
echo $ruler2;
echo "\n<tr>\n<td colspan=6  background='../cms/images/stab-bg.gif'><b>Most searches with no results</b></td></tr>";
echo $ruler;
print_stat_rows($id_connect,'mostempty');

echo "<tr><td>&nbsp;</td></tr>";
echo $ruler2;
echo "\n<tr>\n<td colspan=6  background='../cms/images/stab-bg.gif'><b>Last queries</b></td></tr>";
echo $ruler;
print_stat_rows($id_connect,'lastqueries');

echo "<tr><td>&nbsp;</td></tr>";
echo $ruler2;
echo "\n<tr>\n<td colspan=6  background='../cms/images/stab-bg.gif'><b>Responce time by hour</b></td></tr>";
echo $ruler;
print_stat_rows($id_connect,'responsebyhour');

echo "</table>";
/*
?>
<table>
<tr><td>
<p class='grey'>
<a href="statistics.php?type=mostkeys"><?php phpdigPrnMsg('mostkeywords') ?></a>
<br /><a href="statistics.php?type=mostpages"><?php phpdigPrnMsg('richestpages') ?></a>
<br /><a href="statistics.php?type=mostterms"><?php phpdigPrnMsg('mostterms') ?></a>
<br /><a href="statistics.php?type=largestresults"><?php phpdigPrnMsg('largestresults') ?></a>
<br /><a href="statistics.php?type=mostempty"><?php phpdigPrnMsg('mostempty') ?></a>
<br /><a href="statistics.php?type=lastqueries"><?php phpdigPrnMsg('lastqueries') ?></a>
<br /><a href="statistics.php?type=responsebyhour"><?php phpdigPrnMsg('responsebyhour') ?></a>
</p>
</td><td valign="top">
<?php
if ($type){
	$result = phpdigGetLogs($id_connect,$type);

	if (is_array($result)) {
		echo $start_table_template;
		// title line
		$title_line = '';
		list($i,$titles) = each($result);
		foreach($titles as $field => $useless) {
			$title_line .= sprintf($title_cell_template,ucwords(str_replace('_',' ',$field)));
		}
		printf($line_template,$title_line);
		foreach($result as $id => $row) {
			$this_line = '';
			$id_row_style = $id % $mod_template;
			foreach ($row as $value) {
				$this_line .= sprintf($cell_template[$id_row_style],$value);
			}
			printf($line_template,$this_line);
		}
		echo $end_table_template;
	}
}
?>
</td></tr></table>
<?
*/
jetstream_footer();



//
function print_stat_rows($id_connect,$type){
	global $start_table_template, $end_table_template, $line_template, $title_cell_template, $cell_template, $mod_template;
 	global $ruler, $ruler2;
	$result = phpdigGetLogs($id_connect,$type);
	if (is_array($result)) {
		echo $start_table_template;
		// title line
		$title_line = '';
		list($i,$titles) = each($result);
		foreach($titles as $field => $useless) {
			$counter++;
			$title_line .= sprintf($title_cell_template,ucwords(str_replace('_',' ',$field)));
		}
		for ($aa=1;$aa<=(6-$counter);$aa++) {
			$title_line .= sprintf($title_cell_template,'');
		}

		printf($line_template,$title_line);
		echo $ruler;
		foreach($result as $id => $row) {
			$this_line = '';
			$id_row_style = $id % $mod_template;
			foreach ($row as $value) {
				$this_line .= sprintf($cell_template[$id_row_style],$value);
			}
			printf($line_template,$this_line);
			echo $ruler;
		}
		echo $end_table_template;
	}
} // end func