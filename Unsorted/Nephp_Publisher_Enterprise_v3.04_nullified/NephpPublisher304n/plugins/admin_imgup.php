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

function admin_imgup()
{

	global $gbl_env,$gbl_mnv,$gbl_microtime,$_cfig,$HTTP_POST_FILES;
	$tpl_template_url=$_cfig[url_tpl];

	print "<br>Upload Status: <br>--------------------------------------<br><br>";
	
	for($i=0;$i<$gbl_env['file_totals'];$i++)
	{
		if(getFileSize($i."_file") > 10)
		{
			if(getFileSize($i."_file") > $_cfig{"upload_limit"})
			{
				_err("File size is too big.");
			}
			if(getFileMimeType($i."_file") == "image/gif")
			{
				$file_icon=$gbl_env[$i."_name"].".gif";
				move_uploaded_file($HTTP_POST_FILES[$i."_file"]['tmp_name'],"$_cfig[dir_upload]/$file_icon");
				print "(<a href='{$_cfig[url_upload]}/$file_icon' target='_blank'>view</a>)uploaded- Filename: $file_icon<br>";
                        }
                        elseif(getFileMimeType($i."_file") =="image/pjpeg")
                        {
                                $file_icon=$gbl_env[$i."_name"].".jpg";
				move_uploaded_file($HTTP_POST_FILES[$i."_file"]['tmp_name'],"$_cfig[dir_upload]/$file_icon");
				print "(<a href='{$_cfig[url_upload]}/$file_icon' target='_blank'>view</a>)uploaded- Filename: $file_icon<br>";
                        }
                        else
                        {
                                _err("Unknown file type.");
                        }
			
		}
	}
	print "<br>The file(s) are uploaded successfully.&nbsp;&nbsp; <a href=\"admin.php?mod=articles&opt=view\" target=\"_self\">Click here</a> to continue.";
}
?>