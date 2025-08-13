<?php
///////////////////////////////////////////////////////////////////////////////
//      =   =       ====  =   = ====                                         //
//      =   =       =   = =   = =   =                                        //
//      ==  =  ===  =   = =   = =   =                                        //
//      = = = =   = ====  ===== ====                                         //
//      =  == ===== =     =   = =                                            //
//      =   = =     =     =   = =                                            //
//      =   =  ==== =     =   = =                                            //
//      ------------------------------------------------------               //
//      ====        =     ===     =         =                                //
//      =   =       =       =               =                                //
//      =   = =   = ====    =   ===    ==== ====   ===   ===                 //
//      ====  =   = =   =   =     =   =     =   = =   = =   =                //
//      =     =   = =   =   =     =    ===  =   = ===== =                    //
//      =     =   = =   =   =     =       = =   = =     =                    //
//      =      ===  ====  ===== ===== ====  =   =  ==== =                    //
///////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////
// Program Name         : Nephp Publisher Enterprise                         //
// Release Version      : 3.04                                               //
// Program Author       : Kenny Ngo     (CTO of Nelogic Technologies.)       //
// Program Author       : Ewdision Then (CEO of Nelogic Technologies.)       //
// Retail Price         : $499.00 United States Dollars                      //
// WebForum Price       : $000.00 Always 100% Free                           //
// ForumRu Price        : $000.00 Always 100% Free                           //
// xCGI Price           : $000.00 Always 100% Free                           //
// Supplied by          : Scoons [WTN]                                       //
// Nullified by         : CyKuH [WTN]                                        //
// Distribution         : via WebForum, ForumRU and associated file dumps    //
///////////////////////////////////////////////////////////////////////////////
if(!function_exists("_detect"))
{
	print "You can't make direct access to this file";
	exit();
}
function browse_del()
{
        global $gbl_env,$gbl_mnv,$gbl_microtime,$_cfig;
	global $gbl_sid,$gbl_id,$gbl_type, $gbl_name,$cperf;

        $tpl_template_url=$_cfig[url_tpl];
	
	$url_skins=$_cfig[url_skins];
	$url_upload=$_cfig[url_upload];
	$url_php=$_cfig[url_php];
	$url_tpl=$_cfig[url_tpl];

	$id=$gbl_env['id'];

	$info=mysql_fetch_row(mysql_query("SELECT `nnet_uid`,`nnet_cid`,`nnet_feature` FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_aid`='".$gbl_env["id"]."' LIMIT 1"));
	if($cperf["del"]!=1)                       { _err("You don't have the permission to delete your own article.");       }
	if($gbl_id!=$info[0] && $cperf["mdel"]!=1) { _err("You don't have the permission to delete other people's articles"); }
	
	// DELETE SPECIFIED ARTICLE
	mysql_query("DELETE FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_aid`='{$gbl_env["id"]}' LIMIT 1;")
	or die("Error #".mysql_errno().": ".mysql_error());

	// CLEAN POLL BELONG TO THIS ARTICLE
	mysql_query("DELETE FROM `$_cfig[sql_db]`.`nnet_polls` WHERE `nnet_aid`='{$gbl_env["id"]}';")
	or die("Error #".mysql_errno().": ".mysql_error());
	
	// CLEAN REVIEWS THAT BELONG TO THIS ONE
	mysql_query("DELETE FROM `$_cfig[sql_db]`.`nnet_reviews` WHERE `nnet_aid`='{$gbl_env["id"]}';")
	or die("Error #".mysql_errno().": ".mysql_error());
	
	if(file_exists("$_cfig[dir_upload]/thumb_{$gbl_env["id"]}.jpg"))
	{
		unlink(file_exists("$_cfig[dir_upload]/thumb_{$gbl_env["id"]}.jpg"));
	}
	if(file_exists("$_cfig[dir_upload]/thumb_{$gbl_env["id"]}.gif"))
	{
		unlink(file_exists("$_cfig[dir_upload]/thumb_{$gbl_env["id"]}.gif"));
	}
	if(file_exists("$_cfig[dir_upload]/full_{$gbl_env["id"]}.gif"))
	{
		unlink(file_exists("$_cfig[dir_upload]/full_{$gbl_env["id"]}.gif"));
	}
	if(file_exists("$_cfig[dir_upload]/full_{$gbl_env["id"]}.jpg"))
	{
		unlink(file_exists("$_cfig[dir_upload]/full_{$gbl_env["id"]}.jpg"));
	}
	if($_cfig[static_pages]==1)
        {
		if($_cfig[auto_update]==1)
                {
           		include("$_cfig[dir_library]/admin_built_static.php");
			if($info[1]!='' || $info[1]>0)
			{
				gbl_build_cat($info[1]);
			}
			gbl_build_main();
			if($info[2]!=0)
			{
				if($info[2]>0)
				{	
						gbl_build_cat($info[2]);
				}
			}
			
			if(file_exists("$_cfig[dir_static]/article{$gbl_env["id"]}.html"))
			{
				unlink("$_cfig[dir_static]/article{$gbl_env["id"]}.html");
			}
    		}
		print "<script>alert('Article: #`{$gbl_env["id"]}` is deleted successfully.');document.location='$_cfig[url_static]/cat_$info[1].html';</script>";
	}
	else
	{
		print "<script>alert('Article: #`{$gbl_env["id"]}` is deleted successfully.');document.location='browse.php?opt=browse&catid=$info[1]';</script>";
	}
}
?>