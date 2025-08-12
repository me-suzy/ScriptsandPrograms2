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

//Show left menu and top tabs.
$nomenu=true;

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
		"2.1"		=>  array($jetstream_url . "/../phpsearch/index.php" => "Pages"),		//Compose
);

session_start();
ob_start();
if (isset($_SESSION["uid"])){
	jetstream_header($pagetitle);
	$sel_tab="2.1";
	$tabs[]="2.1";
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

$span="3";
$ruler = '<tr><td colspan="'.$span.'" class="ruler"></td></tr>';
$ruler2 = '<tr><td colspan="'.$span.'" class="ruler2"></td></tr>';

// extract http vars
extract( phpdigHttpVars(
     array('spider_id' => 'integer',
           'spider' => 'integer',
           'sup' => 'integer',
           'site_id' => 'integer'
          )
     ));

$verify = phpdigMySelect($id_connect,'SELECT locked FROM '.PHPDIG_DB_PREFIX.'sites WHERE site_id='.(int)$site_id);

if (is_array($verify) && !$verify[0]['locked'] && $spider_id) {
	mysql_query('UPDATE '.PHPDIG_DB_PREFIX.'sites SET locked=1 WHERE site_id='.$site_id,$id_connect);
	$query = "SELECT site_id,path,file FROM ".PHPDIG_DB_PREFIX."spider where spider_id=$spider_id";
	$result_id = mysql_query($query,$id_connect);
	if (mysql_num_rows($result_id)) {
		 list($site_id,$path,$file) = mysql_fetch_row($result_id);
	}
	if ($spider)  {
		 $query = "DELETE FROM ".PHPDIG_DB_PREFIX."tempspider WHERE site_id=$site_id";
		 $result_id = mysql_query($query,$id_connect);
		 $query = "INSERT INTO ".PHPDIG_DB_PREFIX."tempspider SET site_id=$site_id,path='$path',file='$file'";
		 $result_id = mysql_query($query,$id_connect);
		 mysql_query('UPDATE '.PHPDIG_DB_PREFIX.'sites SET locked=0 WHERE site_id='.$site_id,$id_connect);
		 header ("location:spider.php?site_id=$site_id&mode=small&spider_root_id=$spider_id");
		 exit();
	}
	if ($sup) {
		 $ftp_id = phpdigFtpConnect();
		 phpdigDelSpiderRow($id_connect,$spider_id,$ftp_id);
		 phpdigFtpClose($ftp_id);
	}
	mysql_query('UPDATE '.PHPDIG_DB_PREFIX.'sites SET locked=0 WHERE site_id='.$site_id,$id_connect);
}

if ($site_id) {
  $query = "SELECT site_url,port,locked FROM ".PHPDIG_DB_PREFIX."sites WHERE site_id=$site_id";
  $result_id = mysql_query($query,$id_connect);
  list ($url,$port,$locked) = @mysql_fetch_row($result_id);
  if ($port) {
      $url = ereg_replace('/$',":$port/",$url);
  }

  $query = "SELECT file,spider_id FROM ".PHPDIG_DB_PREFIX."spider WHERE site_id=$site_id AND path like '$path' ORDER by file";
  $result_id = mysql_query($query,$id_connect);
  $num = mysql_num_rows($result_id);
  if ($num < 1) {
      mysql_free_result($result_id);
  }
}


if (!$site_id) {
	
	?>
	<p class="grey">
	<?php phpdigPrnMsg('branch_start') ?>
	</p>
	<?php
}
else {
	echo "\n<br><table border=\"0\" width=100% cellspacing=\"0\" cellpadding=\"4\">";
	echo $ruler2;
	echo "\n<tr>\n<td colspan=3  background='../cms/images/stab-bg.gif'><b>Search engine status:</b></td></tr>";
	echo $ruler;
		if (!$locked) {
		print "<tr bgcolor=\"#F6F6F6\">\n<td width=\"10%\" nowrap>Select there documents to update individually</td>\n</tr>\n";
		echo $ruler;
	}
	echo "<tr><td>";
	for ($n = 0; $n<$num; $n++) {
			$aname2 = $spider_id;
			list($file_name,$spider_id)=mysql_fetch_row($result_id);
			$href=$url.$path.$file_name;
			if (!$locked) {
					print "<a href='files.php?site_id=$site_id&amp;spider_id=$spider_id&amp;sup=1#$aname2'><img src='no.gif' width='10' height='10' border='0' align='middle' alt='' /></a>&nbsp;\n";
					print "<a href='files.php?site_id=$site_id&amp;spider_id=$spider_id&amp;spider=1' target='_top' ><img src='yes.gif' width='10' height='10' border='0' align='middle' alt='' /></a>&nbsp;\n";
			}
			print "<a href='$href' target='_blank'>-".rawurldecode($file_name)."&nbsp;</a><br />\n";
	}
	echo "</td></tr>";
	echo $ruler;

	if (!$locked) {
	print "<tr bgcolor=\"#F6F6F6\">\n<td width=\"10%\" nowrap><img src='yes.gif' width='10' height='10' border='0' align='middle' alt='' /> to reindex it</td></tr>
		<tr bgcolor=\"#F6F6F6\"><td><img src='no.gif' width='10' height='10' border='0' align='middle' alt='' /> to <b>permanent</b> delete a document</td></tr>";
	}
	echo $ruler;
	echo "<tr><td>&nbsp;</td></tr>";
	print "</table>\n";

}
jetstream_footer();