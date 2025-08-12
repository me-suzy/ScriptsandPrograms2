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
	$sel_tab="2.1";
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

$span="3";
$ruler = '<tr><td colspan="'.$span.'" class="ruler"></td></tr>';
$ruler2 = '<tr><td colspan="'.$span.'" class="ruler2"></td></tr>';

// extract vars
extract( phpdigHttpVars(array('message'=>'string')));

// database status
$phpdig_tables = array('sites'=>'Hosts','spider'=>'Pages','engine'=>'Index','keywords'=>'Keywords','tempspider'=>'Temporary table');
echo "\n<br><table border=\"0\" width=100% cellspacing=\"0\" cellpadding=\"4\">";
echo $ruler2;
echo "\n<tr>\n<td colspan=3  background='../cms/images/stab-bg.gif'><b>Search engine status:</b></td></tr>";
echo $ruler;
while (list($table,$name) = each($phpdig_tables)){
	$result = mysql_fetch_array(mysql_query("SELECT count(*) as num FROM ".PHPDIG_DB_PREFIX."$table"),MYSQL_ASSOC);
  print "<tr bgcolor=\"#F6F6F6\">\n<td width=\"10%\" nowrap>\n$name : </td>\n\t<td class=\"greyForm\">\n<b>".$result['num']."</b>".phpdigMsg('entries')."</td>\n</tr>\n";
}
echo $ruler;
echo "<tr><td>&nbsp;</td></tr>";
print "</table>\n";
/*
echo "\n<table border=\"0\" width=100% cellspacing=\"0\" cellpadding=\"4\">";
echo $ruler2;
echo "\n<tr>\n<td colspan=3  background='../cms/images/stab-bg.gif'><b>Actions:</b></td></tr>";
echo $ruler;
echo "<tr bgcolor=\"#F6F6F6\"><td>";
?>
<a href="cleanup_common.php"><?php print phpdigMsg('clean')." ".phpdigMsg('t_stopw'); ?></a> |
<?
echo "</td></tr>";
echo $ruler;
echo "<tr><td>&nbsp;</td></tr>";
print "</table>\n";
*/
echo "\n<table border=\"0\" width=100% cellspacing=\"0\" cellpadding=\"4\">";
echo $ruler2;
echo "\n<tr>\n<td colspan=3  background='../cms/images/stab-bg.gif'><b>Index a new website:</b></td></tr>";
echo $ruler;

?>
<tr bgcolor="#F6F6F6"><td>
<form class="grey" action="spider.php" method="post">
<input class="phpdigSelect" type="text" name="url" value="http://" size="56"/>
<br/>
<?php phpdigPrnMsg('spider_depth') ?> :
<select class="phpdigSelect" name="limit">
<?php
//select list for the depth limit of spidering
for($i = 1; $i <= SPIDER_MAX_LIMIT; $i++) {
    print "\t<option value=\"$i\">$i</option>\n";
} ?>
</select>
<input type="submit" name="spider" value="Dig this !" />
</form>
<p class="blue">
<?php if ($message) { phpdigPrnMsg($message); } ?>
</p>

</td></tr>

<?
echo $ruler;
echo "<tr><td>&nbsp;</td></tr>";
echo "</table>";

//update form
echo "\n<table border=\"0\" width=100% cellspacing=\"0\" cellpadding=\"4\">";
echo $ruler2;
echo "\n<tr>\n<td colspan=3  background='../cms/images/stab-bg.gif'><b>Update a site or one of its branch:</b></td></tr>";
echo $ruler;
echo "<tr bgcolor=\"#F6F6F6\"><td>";
?>
<form action="update.php" >
<select class="phpdigSelect" name="site_ids[]" multiple="multiple" size="10">
<?php
//list of sites in the database
$query = "SELECT site_id,site_url,port,locked FROM ".PHPDIG_DB_PREFIX."sites ORDER BY site_url";
$result_id = mysql_query($query,$id_connect);
while (list($id,$url,$port,$locked) = mysql_fetch_row($result_id))
    {
    if ($port)
        $url .= " (port #$port)";
    if ($locked) {
        $url = '*'.phpdigMsg('locked').'* '.$url;
    }
    print "\t<option value='$id'>$url</option>\n";
    }
?>
</select>
<br/>
<input type="submit" name="update" value="<?php phpdigPrnMsg('updateform'); ?>" />
<input type="submit" name="delete" value="<?php phpdigPrnMsg('deletesite'); ?>" />
</form>
<?
echo $ruler;
?>
</td></tr></table>

<?
/*
</div>
</body>
</html>
*/
jetstream_footer();