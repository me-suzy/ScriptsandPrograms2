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

function browse_email()
{
        global $gbl_env,$gbl_mnv,$gbl_microtime,$_cfig;
	global $gbl_sid,$gbl_id,$gbl_type, $gbl_name,$cperf;

        $tpl_template_url=$_cfig[url_tpl];
	
	$url_skins=$_cfig[url_skins];
	$url_upload=$_cfig[url_upload];
	$url_php=$_cfig[url_php];
	$url_tpl=$_cfig[url_tpl];
	$in_date=_date(time());

	$id=$gbl_env["id"];
	if($id =="")
	{
		_err("Please specify article ID");
	}
	if($gbl_env["opt"] =="")
	{
		print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/browse_header.html",0));
        	print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/browse_sendmail.html",0));
        	print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/browse_footer.html",0));
	}
	else
	{
		$in_name   =$gbl_mnv["name"];
		$in_email  =$gbl_mnv["email"];
		$in_rname  =$gbl_mnv["rname"];
		$in_remail =$gbl_mnv["remail"];

		if($in_name == "" || $in_email == "" || $in_rname =="" || $in_remail=="")
		{
			_err("Required form field(s) is empty.");
		}
		$msgtxt=preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/txt_umail.txt",0));
		_email($in_remail,$in_name,$in_email,"An article forward from $in_name",$msgtxt);
                print "An email have been dispatch to $in_rname { <b>$in_remail</b> }. <a href=browse.php?mod=view&id=$id>Click here</a> to continue.";
	}

}