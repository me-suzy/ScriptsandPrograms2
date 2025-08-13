<?
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

function browse_reviews()
{
        global $gbl_env,$gbl_mnv,$gbl_microtime,$_cfig,$HTTP_COOKIE_VARS;
        global $gbl_sid,$gbl_id,$gbl_type,$gbl_name;
        global $url_upload,$cperf;

        $tpl_template_url=$_cfig[url_tpl];

        $url_skins=$_cfig[url_skins];
        $url_upload=$_cfig[url_upload];
        $url_php=$_cfig[url_php];
        $url_tpl=$_cfig[url_tpl];

        $id =$gbl_env["id"]; $cid=$gbl_env["catid"];
        $in_date=_date (time());
        ///////////////////////////////////////////////////////////////////////////

	if($cperf["review"] != 1)
	{
		if($gbl_type=='guest')
		{
			_err("Please <a href=browse.php?mod=signup>signup</a> or <a href=index.php?action=clear>log-in</a> before submit your article.");
		}
		else
		{
			_err("You don't have the permission to write review.");
		}
	}

        if ($gbl_env["opt"]=="write")
        {
                if($gbl_env["proceed"]!="true")
                {
                        if($id =="" || $gbl_env["catid"]=='') { _err("Please specify Article ID and category ID."); }
                        print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_header.html",0));
                        print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_reviews_write.html",0));
                        print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_footer.html",0));
                }
                else
                {
                        //if($HTTP_COOKIE_VARS[nnet_greview]=='')
                        //{
                                if($gbl_env["nnet_rate"]<0 && $gbl_env["nnet_rate"]>10) { _err("Invalid pollz. ke'k'e'ke' ke'."); }

                                if($gbl_env["nnet_aid"] =="" || $gbl_env["nnet_cid"]=='')   { _err("Please specify Article ID and category ID."); }
                                if($gbl_env["nnet_msg"] =='' || $gbl_env["nnet_title"]=='') { _err("Require fields are empty."); }

                                $result = mysql_query("INSERT INTO `$_cfig[sql_db]`.`nnet_reviews` (`nnet_aid`,`nnet_cid`,`nnet_title`,`nnet_rate`,`nnet_poster`,`nnet_isup`,`nnet_msg`,`nnet_date`) VALUES ('".$gbl_mnv["nnet_aid"]."','".$gbl_mnv["nnet_cid"]."','".$gbl_mnv["nnet_title"]."','".$gbl_mnv["nnet_rate"]."','$gbl_name','".$gbl_mnv["nnet_isup"]."','".$gbl_mnv["nnet_msg"]."','".time()."')")
                                          or die("Error #".mysql_errno().": ".mysql_error());
                                mysql_free_result($result);
                                setcookie("nnet_greview",1,time() + 800);
                                if($_cfig[static_pages]==1)
                                {
                                        // include reviews update
                                        if($_cfig[auto_update]==1)
                                        {
                                                include("$_cfig[dir_library]/admin_built_static.php");
                                                gbl_build_document($gbl_env["nnet_aid"]);
                                        }

                                        Header("Location: $_cfig[url_static]/article".$gbl_env["nnet_aid"].".html");
                                }
                                else
                                {
                                        Header("Location: browse.php?mod=article&opt=view&id=".$gbl_env["nnet_aid"]);
                                }
                        //}
                        //else
                        //{
                        //        _err("Review just has been made. Please wait at least 15 mins before posting another.");
                        //}
                }
        }
        else {}
}
?>