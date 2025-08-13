<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+


require("./admin_common.php");
$site[title] = "Usergroups";
$usr->checkperm('',"isadmin");
/* ----------------------------------------------------------------------------- */
//   DECLARE ALL CRAPPY STUFF
/* ----------------------------------------------------------------------------- */

require ("lib/class_usergroups.php");
$group = new usergroups;
$group->db_table = $database['article_usergroup'];

$group->perm_array = array(
							"canapprove"       => "yesno",  
							"canpost"		   => "yesno",	
							"candelete"	       => "yesno",	
							"isadmin"		   => "yesno",
							"reqvalidation"    => "yesno",
							"showbox"		   => "yesno",
							"editown"		   => "yesno",
							"editall"		   => "yesno"
						  );
/* ----------------------------------------------------------------------------- */


$content = $group->init();
if (!$_GET['do']) $content = $group->main();


/* + ------------------------------------------------------------------------- + */
//    SPIT IT OUT . Generate the file i mean.. 
/* + ------------------------------------------------------------------------- + */
eval("echo(\"".$tpl->gettemplate("main",1)."\");");
/* + ------------------------------------------------------------------------- + */
?>