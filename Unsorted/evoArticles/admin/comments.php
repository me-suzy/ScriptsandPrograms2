<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+

require("./admin_common.php");
$usr->checkperm('',"isadmin");
if($usr->checkperm('',"isadmin",1) == true ) 
$site[title] = "Comments System";
/* ----------------------------------------------------------------------------- */
//if ($settings['commentsystem'] != "internal")
//{
//	$content = $evoLANG['cantuse'];
//	eval("echo(\"".$tpl->gettemplate("main",1)."\");");
//	exit();
//}
/* ----------------------------------------------------------------------------- */
//include ( "comments/".$settings['commentsystem'].".php");
/* ----------------------------------------------------------------------------- */
//$cmt = new Comments;

switch($_GET['do'])
{
	/* + ----------------------------------- + */
	case "delete":
		$cmt->deletecomment($_GET['id']);
	break;
	/* + ----------------------------------- + */
	case "edit":
		$content = $cmt->editcomment($_GET['id']);
	break;
	/* + ----------------------------------- + */
	case "approve":
		$content = $cmt->approvecomment($_GET['id']);
	break;
	/* + ----------------------------------- + */
	case "approveall":
		$content = $cmt->approveall();
	break;
	/* + ----------------------------------- + */
	case "viewall":
		$content = $cmt->viewall();
	break;
	/* + ----------------------------------- + */
	default;
		$content = $cmt->manage();
}
/* + ------------------------------------------------------------------------- + */
//    SPIT IT OUT . Generate the file i mean.. 
/* + ------------------------------------------------------------------------- + */
eval("echo(\"".$tpl->gettemplate("main",1)."\");");
/* + ------------------------------------------------------------------------- + */
?>