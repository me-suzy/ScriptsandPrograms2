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

	$in_history=$_cfig[history_days]*(3600*24);
	$result = mysql_query("SELECT `nnet_time`,`nnet_title`,`nnet_desc`,`nnet_aid` FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_approval`='1' AND ".time()."-`nnet_time` < $in_history ORDER BY `nnet_aid` DESC;")
                  or die("Error #".mysql_errno().": ".mysql_error());
	$html_docs=_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_archive.txt",0);
	
	$date_arrayz=array();

        while ($line = mysql_fetch_row($result))
        {
		$ad=localtime($line[0]);$ad[4]++;
		$date_arrayz[]="$ad[3]-$ad[4]";
		$doc_time["$ad[3]-$ad[4]"] =$line[0];
		$doc_title["$ad[3]-$ad[4]"][]=$line[1];
		$doc_id["$ad[3]-$ad[4]"][]=$line[3];
		$doc_desc["$ad[3]-$ad[4]"][] =$line[2];
        }
        mysql_free_result($result);
	
	$date_arrayz=array_unique($date_arrayz);

	while(list($key,$value)=each($date_arrayz))
	{
		$docs_listing='';
		$docs_found=count($doc_title[$value]);
		for($i=0;$i<count($doc_title[$value]);$i++)
		{
                	if($i>=$_cfig[archive_sub_limit]){break;}
			if($_cfig[static_pages]!=1){$var_listings_view_url="$url_php/browse.php?mod=article&opt=view&id={$doc_id[$value][$i]}";}
			else                       {$var_listings_view_url="$_cfig[url_static]/article{$doc_id[$value][$i]}.html";}
			$docs_listing.="<b><font color=\"#FF0000\">".($i+1)."</font>.&nbsp;<a href='$var_listings_view_url'>{$doc_title[$value][$i]}</a></b><br>{$doc_desc[$value][$i]}<br><br>";
		}
		if($docs_listing==''){$docs_listing="No articles on this day";}
		$show_date=_cdate($doc_time[$value]);
		$article_listing.=preg_replace("/{%(\w+)%}/ee", "$\\1",$html_docs);			
	}
	
	//print _date(time());
	//print_r($doc_title);
	//$date_arrayz=array_unique($date_arrayz);
	//print_r($date_arrayz);
	
 	print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_header.html",0));
        print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_archive.html",0));
        print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_footer.html",0));
}
?>