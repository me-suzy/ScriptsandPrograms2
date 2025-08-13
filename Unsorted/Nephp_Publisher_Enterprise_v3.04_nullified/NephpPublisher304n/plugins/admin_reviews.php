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
function admin_reviews()
{
	global $gbl_env,$gbl_mnv,$gbl_microtime,$_cfig,$HTTP_POST_FILES;
	$tpl_template_url=$_cfig[url_tpl];$in_date=_date(time());

        /////////////////////////////////////////////////////////////////
        // POST-SPAN                                                   //
        /////////////////////////////////////////////////////////////////
        if($gbl_env["page"] == '')
        {
                $gbl_env["page"]=1;
        }
        $gbl_env["page"]--;
        $startpoint=$_cfig[op_span]*$gbl_env["page"];

        $id=$gbl_env["id"];

	if($id=="")
	{
		_err("Please specify the document id. Dependencies failed.");
	}
        ///////////////////////////////////////////////////////////////////////////

        if($gbl_env["opt"] == "")
        {
                $result = mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_reviews` WHERE `nnet_aid`='$id'")
                          or die("Error #".mysql_errno().": ".mysql_error());
                $html_docs=_html("$_cfig[dir_tpl]/html/browse_reviews_details.txt",0);
                while ($line = mysql_fetch_array($result))
                {
			extract($data);
			$nnet_isup= cif("$nnet_isup==1","<img src=\"$tpl_template_url/gfx/good.gif\">","<img src=\"$tpl_template_url/gfx/bad.gif\">");
                        $reviews_output.=preg_replace("/{%(\w+)%}/ee", "$\\1",$html_code)."\n";
                }
                mysql_free_result($result);
		print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/admin_header.html",0));
                print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/admin_reviews.html",0));
		print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/admin_footer.html",0));

        }
        elseif($gbl_env["opt"] == "del")
        {
                $result = mysql_query(preg_replace("/{%(\w+)%}/ee", "$\\1",$sql_statement{"admin_reviews_del"}))
                          or die("Error #".mysql_errno().": ".mysql_error());
                mysql_free_result($result);
		$ref=getenv("HTTP_REFERER");
                header("Location: $ref");
        }
        else
        {
                print "Unknown - Action";
        }
}
?>