<?php
require("./admin_common.php");

/* ----------------------------------------------------------------------------- */
$site[title] = "Settings Management";
/* ----------------------------------------------------------------------------- */
//   DECLARE ALL CRAPPY STUFF
/* ----------------------------------------------------------------------------- */
require ("../source/class_settings.php");
$set = new settings;
$set->db['sg'] = $database['sgroup'];
$set->db['s'] = $database['settings'];
$set->db_file = "config_site.php";
$set->db_file_loc = "";
$set->generate_file = 0;
$set->array_name = "settings";
$set->add = array();

/* ----------------------------------------------------------------------------- */


// cam ni, aku ade idea best punya. barang best aa ;) buat SELECT|||arrayname ,
// pastu process kan array tu .. tak ke senang tu? muahahaha

/* ----------------------------------------------------------------------------- */
//								Lang Fetching
/* ----------------------------------------------------------------------------- */
$handle = opendir(LANG_FOLDER);
while( $lfile = readdir($handle) )
{
	if($lfile!="." && $lfile!="..")
	{
		$lfile2=explode(".",$lfile);
		$lfile3=str_replace("lang_","",$lfile2[0]);
		if( preg_match("/lang/",$lfile) ) 
		{
			$evoLANG_opt .= $lfile2[0]."|".$admin->intercap($lfile3).",";
		}
	}
}
closedir($handle);
/* ----------------------------------------------------------------------------- */
$set->add['lang'] = $evoLANG_opt;
$set->add['mode'] = 'gb|Guestbook Mode,sb|Shoutbox Mode';

$content = $set -> init();
if (!$_GET['do']) $content = $set->gen_table();


/* + ------------------------------------------------------------------------- + */
//    SPIT IT OUT . Generate the file i mean.. 
/* + ------------------------------------------------------------------------- + */
eval("echo(\"".$tpl->gettemplate("main",1)."\");");
/* + ------------------------------------------------------------------------- + */
?>