<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+


require("./admin_common.php");
$usr->checkperm('',"isadmin");
/* ----------------------------------------------------------------------------- */
$site[title] = "Settings Management";
/* ----------------------------------------------------------------------------- */
//   DECLARE ALL CRAPPY STUFF
/* ----------------------------------------------------------------------------- */
require ("lib/class_settings.php");
$set = new settings;
$set->db['sg'] = $database['article_sgroup'];
$set->db['s'] = $database['article_settings'];
$set->db_file = "config_site.php";
$set->db_file_loc = "";
$set->generate_file = 0;
$set->array_name = "settings";
$set->add = array();

/* ----------------------------------------------------------------------------- */


// cam ni, aku ade idea best punya. barang best aa ;) buat SELECT|||arrayname ,
// pastu process kan array tu .. tak ke senang tu? muahahaha


/* ----------------------------------------------------------------------------- */
//							Comments Fetching
/* ----------------------------------------------------------------------------- */
$comments_opt =  "internal|Built-In/Internal,vbulletin|vBulletin 2.3.0 >,vbulletin3|vBulletin 3,ibp|Invision Power Board 1.3,wbb2|Woltlab Burning Board 2,phpbb2|phpBB 2.0.6 >";
/* ----------------------------------------------------------------------------- */
//							Styles Fetching
/* ----------------------------------------------------------------------------- */
$sql = $udb->query("SELECT * FROM $database[article_styles]	ORDER BY id	");
while($s_row = $udb->fetch_array($sql))
{
	$style_opt .= $s_row['tplfolder']."|".$s_row['name'].",";
}
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
$set->add['style'] = $style_opt;
$set->add['comments'] = $comments_opt;
/* ----------------------------------------------------------------------------- */

$content = $set -> init();
if (!$_GET['do']) $content = $set->gen_table();


/* + ------------------------------------------------------------------------- + */
//    SPIT IT OUT . Generate the file i mean.. 
/* + ------------------------------------------------------------------------- + */
eval("echo(\"".$tpl->gettemplate("main",1)."\");");
/* + ------------------------------------------------------------------------- + */
?>