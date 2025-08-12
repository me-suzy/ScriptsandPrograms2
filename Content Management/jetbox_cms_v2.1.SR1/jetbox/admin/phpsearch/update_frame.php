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
$thisfile ="/".array_pop(explode("/", $_SERVER["SCRIPT_NAME"]));
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
		"2.1"		=>  array($jetstream_url . "/../phpsearch/index.php" => "Status"),		//Compose
		"2.2"		=>  array($jetstream_url . "/internalmail.php" => "Update"),		//Add/delete subscriber
		"2.3"		=>  array($jetstream_url . "/../postlister/generate.php" => "Generate text"),		//import
		"2.4"		=>  array($jetstream_url . "/../postlister/edit.php" => $GLOBALS[s4]),		//list properties
		"2.5"		=>  array($jetstream_url . "/../postlister/lists.php" => $GLOBALS[s5]),		//create/ delete list
);


$relative_script_path = '../../includes/phpdig';
include "$relative_script_path/includes/config.php";
//include "$relative_script_path/libs/auth.php";
include "robot_functions.php";

// extract vars
extract( phpdigHttpVars(
     array('delete'=>'string',
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
else if (isset($site_ids[0]) && (int)$site_ids[0]) {
      $site_id = $site_ids[0];
}

if (!(int)$site_id) {
   header ("location:index.php");
   exit();
}

print '<?xml version="1.0" encoding="'.PHPDIG_ENCODING.'"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include $relative_script_path.'/libs/htmlmetas.php' ?>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<frameset cols="50%,50%">
<frame src="update.php?site_id=<?php print $site_id ?>" name="tree" frameborder="0" noresize="noresize" />
<frame src="files.php" frameborder="0" name="files" />
<noframes><body></body></noframes>
</frameset>
</html>