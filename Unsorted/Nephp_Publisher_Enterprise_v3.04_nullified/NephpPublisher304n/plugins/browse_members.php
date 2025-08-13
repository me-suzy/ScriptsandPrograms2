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
function browse_members()
{
        global $gbl_env,$gbl_mnv,$gbl_microtime,$_cfig;
        $tpl_template_url=$_cfig[url_tpl];


        $url_skins=$_cfig[url_skins];
        $url_upload=$_cfig[url_upload];
        $url_php=$_cfig[url_php];
        $url_tpl=$_cfig[url_tpl];
        $in_date=_date(time());

        ///////////////////////////////////////////////////////////////////////////
        // POST-SPAN                                                             //
        ///////////////////////////////////////////////////////////////////////////
        if($gbl_env["page"] == '')
        {
                $gbl_env["page"]=1;
        }
        $gbl_env["page"]--;
        $startpoint=$_cfig[span_members]*$gbl_env["page"];
        //////////////////////////////////////////////////////////////////////////
        if($gbl_env["opt"] == "")
        {
                $id=$gbl_env["id"];
                if($_cfig{"isvb"}==1)
                {
                        if($id=='') { Header("Location: ".$_cfig{"vb_url"}."/memberlist.php"); }
                        else        { Header("Location: ".$_cfig{"vb_url"}."/member.php?action=getinfo&userid=$id");}
                }
                else
                {
                        if($id == "")  { $extra="1";           }
                        else           { $extra="`nnet_uid`='$id'"; }

                        $line = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `$_cfig[sql_db]`.`nnet_users` WHERE $extra"));
                        $totalsize=$line[0];

                        $result = mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_users` WHERE $extra LIMIT $startpoint,$_cfig[span_members]")
                                  or die("Error #".mysql_errno().": ".mysql_error());
                        $html_code=_html("$_cfig[dir_skins]/$_cfig[template]/html/txt_umemlist.txt",0);
                        while ($line = mysql_fetch_array($result))
                        {
                                extract($line);$nnet_date=_date($nnet_date);
                                $memlist_output.=preg_replace("/{%(\w+)%}/ee", "$\\1",$html_code)."\n";
                        }
                        mysql_free_result($result);

                        //print $memlist_output;
                        $spandisplay=_span($_cfig[span_members],$gbl_env["page"]+1,$totalsize,"browse.php?mod=members");
                        print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_header.html",0));
                        print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_memlist.html",0));
                        print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_footer.html",0));
                }
        }
	elseif($gbl_env["opt"]=='info')
	{
		
	
	}
        else
        {
                print "Unknown - Action";
        }
}
?>