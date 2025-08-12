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
include "robot_functions.php";

$span="3";
$ruler = '<tr><td colspan="'.$span.'" class="ruler"></td></tr>';
$ruler2 = '<tr><td colspan="'.$span.'" class="ruler2"></td></tr>';

// extract http vars
extract( phpdigHttpVars(
     array('path'=>'string',
           'sup'=>'integer',
           'path' => 'string',
           'sup' => 'integer',
           'exp' => 'integer',
           'deny' => 'integer',
           'ex_id' => 'integer',
           'new_username' => 'integer',
           'username' => 'string',
           'password' => 'string',
           'site_id' => 'integer',
           'unlock' => 'integer',
					 'delete'=>'string',
           'site_id'=>'integer',
           'site_ids'=>'array'
           )
     ));
if ($delete) {
    $message = '';
    foreach($site_ids as $site_id) {
      $verify = phpdigMySelect($id_connect,'SELECT locked FROM '.PHPDIG_DB_PREFIX.'sites WHERE site_id='.(int)$site_id);
      if (is_array($verify) && !$verify[0]['locked']) {
        // locks site (prevents any operation before erase)
        $query = "UPDATE ".PHPDIG_DB_PREFIX."sites SET locked=1 WHERE site_id=$site_id";
        $result_id = mysql_query($query,$id_connect);

        $query = "SELECT spider_id FROM ".PHPDIG_DB_PREFIX."spider WHERE site_id=$site_id";
        $result_id = mysql_query($query,$id_connect);

        if (mysql_num_rows($result_id) > 0)
            {
            $in = "IN (0";
            $ftp_id = phpdigFtpConnect();
            while (list($spider_id) = mysql_fetch_row($result_id))
                   {
                   phpdigDelText($relative_script_path,$spider_id,$ftp_id);
                   $in .= ",$spider_id";
                   }
            phpdigFtpClose($ftp_id);
            $in .= ")";
            $query = "DELETE FROM ".PHPDIG_DB_PREFIX."engine WHERE spider_id $in";
            $result_id = mysql_query($query,$id_connect);
            }
        $query = "DELETE FROM ".PHPDIG_DB_PREFIX."spider WHERE site_id=$site_id";
        $result_id = mysql_query($query,$id_connect);

        $query = "DELETE FROM ".PHPDIG_DB_PREFIX."tempspider WHERE site_id=$site_id";
        $result_id = mysql_query($query,$id_connect);

        $query = "DELETE FROM ".PHPDIG_DB_PREFIX."excludes WHERE ex_site_id=$site_id";
        $result_id = mysql_query($query,$id_connect);

        $query = "DELETE FROM ".PHPDIG_DB_PREFIX."sites WHERE site_id=$site_id";
        $result_id = mysql_query($query,$id_connect);
      }
      else if (is_array($verify) && $verify[0]['locked'] == 1) {
        $message = '?message=onelock';
      }
      else {
        $message = '?message=no_site';
      }
    }
    header ("location:index.php".$message);
    exit();
}
elseif (isset($site_ids[0]) && (int)$site_ids[0]) {
			$site_id = $site_ids[0];
}
else{
	header ("location:index.php".$message);
}

set_time_limit(3600);
srand(time());
if ($path) {
	$andpath = "AND path like '".str_replace('%','\%',$path)."%'";
}
else {
	$andpath = '';
}

$verify = phpdigMySelect($id_connect,'SELECT locked FROM '.PHPDIG_DB_PREFIX.'sites WHERE site_id='.(int)$site_id);
if (!is_array($verify)) {
	die();
}
elseif ($unlock) {
	mysql_query('UPDATE '.PHPDIG_DB_PREFIX.'sites SET locked=0 WHERE site_id='.$site_id,$id_connect);
}
elseif (!$verify[0]['locked']) {
  mysql_query('UPDATE '.PHPDIG_DB_PREFIX.'sites SET locked=1 WHERE site_id='.$site_id,$id_connect);
  if($sup) {
    $query = "SELECT spider_id FROM ".PHPDIG_DB_PREFIX."spider WHERE site_id=$site_id $andpath";
    $result_id = mysql_query($query,$id_connect);

    if ( mysql_num_rows($result_id) > 0) {
			$ftp_id = phpdigFtpConnect();
			$in = "IN (0";
			while (list($spider_id) = mysql_fetch_row($result_id)) {
				phpdigDelText($relative_script_path,$spider_id,$ftp_id);
				$in .= ",$spider_id";
			}
			$in .= ")";
			phpdigFtpClose($ftp_id);

			$query = "DELETE FROM ".PHPDIG_DB_PREFIX."engine WHERE spider_id $in";
			$result_id = mysql_query($query,$id_connect);

			$query = "DELETE FROM ".PHPDIG_DB_PREFIX."spider WHERE site_id=$site_id $andpath";
			$result_id = mysql_query($query,$id_connect);

			// deny branch
			if ($deny && $path) {
				$query = "DELETE FROM ".PHPDIG_DB_PREFIX."excludes WHERE ex_site_id=$site_id AND ex_path LIKE '".str_replace('%','\%',$path)."%'";
				mysql_query($query,$id_connect);
				$query = "INSERT INTO ".PHPDIG_DB_PREFIX."excludes (ex_site_id,ex_path) VALUES ($site_id,'".str_replace('%','\%',$path)."')";
				mysql_query($query,$id_connect);
			}
    }
  }
  elseif ($exp) {
    $query = "DELETE FROM ".PHPDIG_DB_PREFIX."tempspider WHERE site_id=$site_id and indexed = 1";
    mysql_query($query,$id_connect);
    $query = "INSERT INTO ".PHPDIG_DB_PREFIX."tempspider (site_id,file,path) SELECT site_id,file,path FROM ".PHPDIG_DB_PREFIX."spider WHERE site_id=$site_id $andpath";
    mysql_query($query,$id_connect);

    mysql_query('UPDATE '.PHPDIG_DB_PREFIX.'sites SET locked=0 WHERE site_id='.$site_id,$id_connect);
    header ("location:spider.php?site_id=$site_id&mode=small");
  }
  elseif ($ex_id) {
    $query = "DELETE FROM ".PHPDIG_DB_PREFIX."excludes WHERE ex_site_id=$site_id and ex_id = ".$ex_id;
    mysql_query($query,$id_connect);

  }
  mysql_query('UPDATE '.PHPDIG_DB_PREFIX.'sites SET locked=0 WHERE site_id='.$site_id,$id_connect);
}

$query = "SELECT site_id,site_url,username,password,port,locked FROM ".PHPDIG_DB_PREFIX."sites WHERE site_id=$site_id";
$result_id = mysql_query($query,$id_connect);
$num = mysql_num_rows($result_id);
if ($num < 1) {
    mysql_free_result($result_id);
    phpdigPrnMsg('no_site');
    $num_tot = 0;
}
else {
    $a_result = mysql_fetch_array($result_id,MYSQL_ASSOC);
    extract($a_result);
    mysql_free_result($result_id);
    $query = "SELECT count(spider_id) as num_tot FROM ".PHPDIG_DB_PREFIX."spider WHERE site_id='$site_id'";
    $result_id = mysql_query($query,$id_connect);
    $num_result = mysql_fetch_array($result_id,MYSQL_ASSOC);
    extract($num_result);
    mysql_free_result($result_id);
    // retrieve list of all exclude paths
    $query = "SELECT ex_id, ex_path FROM ".PHPDIG_DB_PREFIX."excludes WHERE ex_site_id='$site_id'";
    $list_exclude = phpdigMySelect($id_connect,$query);
}

$query = "SELECT path,spider_id FROM ".PHPDIG_DB_PREFIX."spider WHERE site_id=$site_id GROUP BY path ORDER by path";
$result_id = mysql_query($query,$id_connect);
$num = mysql_num_rows($result_id);
if ($num < 1) {
    mysql_free_result($result_id);
}

//change the user/pass for an existing site
if (!$locked && $new_username && $new_password && $site_id) {
	$query = "UPDATE ".PHPDIG_DB_PREFIX."sites SET username='$new_username',password='$new_password' WHERE site_id=$site_id";
	mysql_query($query,$id_connect);
	if (mysql_affected_rows($id_connect) > 0) {
			print "<font color='red'><b>".phpdigMsg('userpasschanged')."</b></font><br />\n";
	}
}

if ($port) {
    $site_url = ereg_replace('/$',":$port/",$site_url);
}

if (!$locked) {
	echo "\n<br><table border=\"0\" width=100% cellspacing=\"0\" cellpadding=\"4\">";
	echo $ruler2;
	echo "\n<tr>\n<td colspan=3  background='../cms/images/stab-bg.gif'><b>Username & password for protected sites:</b></td></tr>";
	echo $ruler;
	
	?>
	<form method="post" action="update.php">
	
	<input type='hidden' name='site_id' value='<?php print $site_id ?>' />
	<?
	echo "<tr bgcolor=\"#F6F6F6\"><td>". phpdigMsg('uri')."$site_url"." ("."$num_tot".phpdigMsg('pages').")</td></tr>";
	echo $ruler;
	echo "<tr><td>";
	?>

	<?php phpdigPrnMsg('user'); ?> <input type='text' size='12' name='new_username' value='<?php print $username ?>' /><br>
	<?php phpdigPrnMsg('password'); ?> <input type='password' size='12' name='new_password' />
	<input type='submit' name='change' value='<?php phpdigPrnMsg('change'); ?>' />
	<?echo "</td></tr>";?>
	</form>
	<?php

	echo $ruler;
	echo "</table>";
}
else {
	print '<p class="blue"><b>'
	.phpdigMsg('uri')."$site_url"." ("."$num_tot".phpdigMsg('pages').")</b><br />"
	.'<i>'.phpdigMsg('locked').' :</i> '
	.'<a href="update.php?site_id='.$site_id.'&amp;unlock=1">'.phpdigMsg('unlock').'</a>'
	."</p>\n";
}

if (is_array($list_exclude)) {
	echo "\n<table border=\"0\" width=100% cellspacing=\"0\" cellpadding=\"4\">";
	echo "<tr><td>&nbsp;</td></tr>";
	echo $ruler2;
	echo "\n<tr>\n<td colspan=3  background='../cms/images/stab-bg.gif'><b>".phpdigMsg('excludes')."</b></td></tr>";
	echo $ruler;
	echo "<tr><td>";
	foreach ($list_exclude as $this_exclude) {
		extract($this_exclude);
		print "<a href='update.php?site_id=$site_id&ex_id=$ex_id' target='_self'><img src='no.gif' width='10' height='10' border='0' align='middle' alt='".phpdigMsg('delete')."'></a>&nbsp;<font COLOR='red'>$ex_path</font><br/>\n";
	}

	echo "</td></tr>";
	echo $ruler;
	echo "<tr><td>&nbsp;</td></tr>";
	print "</table>\n";
}
echo "\n<table border=\"0\" width=100% cellspacing=\"0\" cellpadding=\"4\">";
echo $ruler2;
echo "\n<tr>\n<td colspan=3  background='../cms/images/stab-bg.gif'><b>Found Tree</b></td></tr>";
echo $ruler;
echo "<tr><td>";
$aname = "AAA";
$previous_dir = explode('/','///////////////');

for ($n = 0; $n<$num; $n++) {
	$aname2 = $aname;
	list($path_name,$aname)=mysql_fetch_row($result_id);
	print "<a name=\"$aname\"></a>\n";
	$paths = explode('/',rawurldecode($path_name));
	$num_levels = count($paths);
	$path_name_aff = '';
	while(list($id,$dir) = each($paths)) {
		if ($dir != $previous_dir[$id]) {
			$path_name_aff .= substr('/'.$dir,0,20);
			if ($id == 0) {
				$path_name_aff = '<b>'.$path_name_aff.'</b>';
			}
			$previous_dir[$id] = $dir;
		}
		else if ($dir) {
			if (($id+4) > $num_levels) {
				$start_char = '\\';
				$space_char = '-';
				$numc = 5;
			}
			else {
				$start_char = '§';
				$space_char = '§';
				if ($id < $num_levels) {
					$numc = 5;
				}
				else {
					$numc = 20;
				}
			}
			$path_name_aff .= str_replace('§','&nbsp;',substr($start_char.ereg_replace('.{1}',$space_char,$dir),0,$numc));
		}
	}
	if (!$locked) {
		if ($path_name) {
			 print "<a href='update.php?path=".urlencode($path_name)."&amp;site_id=$site_id&amp;deny=1&amp;sup=1#$aname2' target='_self' ><img src='deny.gif' width='10' height='10' border='0' align='middle' alt='".phpdigMsg('exclude')."' /></a>&nbsp;\n";
		}
		else {
			 print "<img src='fill.gif' width='10' height='10' border='0' align='middle' alt='' />&nbsp;\n";
		}
		print "<a href='update.php?site_id=$site_id&amp;path=".urlencode($path_name)."&amp;sup=1#$aname2' target='_self'><img src='no.gif' width='10' height='10' border='0' align='middle' alt='".phpdigMsg('delete')."' /></a>&nbsp;\n";
		print "<a href='update.php?path=".urlencode($path_name)."&amp;site_id=$site_id&amp;exp=1' target='_top'><img src='yes.gif' width='10' height='10' border='0' align='middle' alt='".phpdigMsg('reindex')."' /></a>&nbsp;\n";
	}
	if ($path_name == "") {
		$path_name_aff = "<i><b style='color:red;'>".phpdigMsg('root')."</b></i>";
	}
	print '<code>'.$path_name_aff."</code>&nbsp;<a href='files.php?path=".urlencode($path_name)."&amp;site_id=$site_id' target='files' ><img src='details.gif' width='10' height='10' border='0' align='middle' alt='".phpdigMsg('files')."' /></a><br />\n";
}
echo "</td></tr>";
if (!$locked) {
	echo $ruler;
	echo "<tr bgcolor=\"#F6F6F6\"><td>";
	phpdigPrnMsg('update_help');
	echo "<br>";
	phpdigPrnMsg('warning');
	phpdigPrnMsg('update_warn');

	echo "</td></tr>";

}
echo $ruler;
echo "<tr><td>&nbsp;</td></tr>";
print "</table>\n";

jetstream_footer();