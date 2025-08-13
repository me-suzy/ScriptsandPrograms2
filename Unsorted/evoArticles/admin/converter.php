<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+


require("./admin_common.php");
$usr->checkperm('',"isadmin");
if($usr->checkperm('',"isadmin",1) == true ) 
$site[title] = "Converter";

// conf
$_cfg['path'] = "converter/";
$_cfg['prefix'] = "conv_";
if ( trim($_GET['t']) != '' && file_exists($_cfg['path'].$_cfg['prefix'].$_GET['t'].".php") )
{
	// start crap
	include $_cfg['path'].$_cfg['prefix'].$_GET['t'].".php";
	$conv = new Converter;

	switch ($_GET['act'])
	{
		/* ---------------------------------------------- */
		default;
			$content = $conv->main();

	}
}
else
{
	$handle = opendir($_cfg['path']);
	while( $lfile = readdir($handle) )
	{
		if($lfile!="." && $lfile!="..")
		{
			$lfile2 = explode(".",$lfile);
			$lfile3 = str_replace("conv_","",$lfile2[0]); // filename
			if( preg_match("/conv/",$lfile) ) 
			{
				$out .= "<li>".$admin->makelink(str_replace("_"," ",$lfile3),$_SERVER['PHP_SELF']."?t=".$lfile3)."</li>";
			}
		}
	}

	closedir($handle);

	$content .= "<h4>".$evoLANG['conv_selectone']."</h4><br />";
	$content .= ($out == '' ? $evoLANG['noconv'] : $out);
}

/* + ------------------------------------------------------------------------- + */
//    SPIT IT OUT . Generate the file i mean.. 
/* + ------------------------------------------------------------------------- + */
eval("echo(\"".$tpl->gettemplate("main",1)."\");");
/* + ------------------------------------------------------------------------- + */
?>