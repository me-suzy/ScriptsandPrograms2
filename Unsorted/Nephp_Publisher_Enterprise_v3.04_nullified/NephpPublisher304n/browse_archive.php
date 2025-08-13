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
if(!function_exists("_detect"))
{
        print "You can't make direct access to this file";
        exit();
}

function browse_archive()
{
 	global $gbl_env,$gbl_mnv,$gbl_microtime,$_cfig,$HTTP_COOKIE_VARS;
        $tpl_template_url=$_cfig[url_tpl];

        $url_skins=$_cfig[url_skins];
        $url_upload=$_cfig[url_upload];
        $url_php=$_cfig[url_php];
        $url_tpl=$_cfig[url_tpl];

	if($_cfig[static_pages]!=1){ $url_php_home="$url_php/browse.php"; }
	else                       { $url_php_home="$_cfig[url_static]/index.html";}

        $in_date=date (time());

	$result = mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_approval`='1' ORDER BY `nnet_aid` DESC LIMIT 0,12")
                  or die("Error #".mysql_errno().": ".mysql_error());
	$html_docs=_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_listings_default.txt",0);

        while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
        {
		extract($line);
		if(strlen($datdes)>160)
		{
			$datdes=substr($datdes,0,160)."...";
		}			
                if($ftype != "")
                {
                	$isfeatured=" <sup><b><font face=\"Verdana\" size=\"2\" color=\"#FF0000\">featured</font></b></sup>";
                }
                else
                {
                        $isfeatured="";
                }
		if($_cfig[static_pages]!=1){$var_listings_view_url="$url_php/browse.php?mod=article&opt=view&id=$nnet_aid";}
		else                       {$var_listings_view_url="$_cfig[url_static]/article$nnet_aid.html";}
		$var_listings_author_url="$url_php/browse.php?mod=members&id=$nnet_uid";
                $article_listing.=preg_replace("/{%(\w+)%}/ee", "$\\1",$html_docs);
        }
        mysql_free_result($result);
 	print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_header.html",0));
        print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_archive.html",0));
        print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_footer.html",0));
}
?>