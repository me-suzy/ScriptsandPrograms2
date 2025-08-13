<?php
/*
 ####   ###        ###                  ##						  
  ####   #          ##                  ##						  
  #####  #          ##									  
  # ###  #   ####   ##    ###    #########    ###					  
  #  ### #   #  ##  ##   #   #  ##  ##  ##   #  ##					  
  #  #####  ######  ##  ##   ## ##  ##  ##  ##  ##					  
  #   ####  ##      ##  ##   ## ##  ##  ##  ##						  
  #    ###  ##   #  ##  ##   ##   ###   ##  ##						  
  #     ##  ###  #  ##   #   #  ##      ##  ##	 #
 ###     #   ####  ####   ###   ###### ####  ####
                                 ######		
                                ##   ##						  
                                 #####							  
  ########              ###                      ###                  ##		  
  #  ##  #               ##                       ##                  ##		  
     ##                  ##                       ##					  
     ##    ####    ###   ## ### ### ###    ###    ##    ###    #########   ####    ####  
     ##    #  ##  #  ##  ###  ## ###  ##  #   #   ##   #   #  ##  ##  ##   #  ##  ##  #  
     ##   ###### ##  ##  ##   ## ##   ## ##   ##  ##  ##   ## ##  ##  ##  ######  ####	 
     ##   ##     ##      ##   ## ##   ## ##   ##  ##  ##   ## ##  ##  ##  ##       ####  
     ##   ##   # ##      ##   ## ##   ## ##   ##  ##  ##   ##   ###   ##  ##   #  #  ##  
     ##   ###  # ###     ##   ## ##   ##  #   #   ##   #   #  ##      ##  ###  #  #  ##  
    ####   ####   ##### ###   #####   ###  ###   ####   ###   ###### ####  ####   ####	 
                                                               ######			 
                                                              ##   ##			 
                                                               ######			 
 Program name: NePublisher Server ( PHP EDITION )
 Version: v3.0
 April 26th, 2002
 Coded by: Kenny Ngo
 =======================================================================================
 Contact Information								
 -------------------									
 =======================================================================================
                                                                                        
 DECLAIMER										  
 =========										  
 This program is protected by the US pattern services. Any illegal possession will	  
 persecuted under the law. The owner of this program may modify the coding to fit their  
 needs. Please keep in mind that if we find out that you share this program to others    
 without our permission, your license might be terminated. Thank you. */
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

function browse_lostpwd()
{
        global $gbl_env,$gbl_mnv,$gbl_microtime,$_cfig;
	global $gbl_sid,$gbl_id,$gbl_type, $gbl_name,$cperf;

        $tpl_template_url=$_cfig[url_tpl];
	
	$url_skins=$_cfig[url_skins];
	$url_upload=$_cfig[url_upload];
	$url_php=$_cfig[url_php];
	$url_tpl=$_cfig[url_tpl];
	$in_date=_date(time());
	
	if($gbl_env['opt']=='')
	{
		print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/browse_header.html",0));
                print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/browse_lostpwd.html",0));
                print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/browse_footer.html",0));
	}
	else
	{
		$get_info=mysql_fetch_row(mysql_query("SELECT `nnet_pass`,`nnet_uname` FROM `$_cfig[sql_db]`.`nnet_users` WHERE `nnet_user`='{$gbl_env['username']}' AND `nnet_email` LIKE '{$gbl_env['email']}' LIMIT 1"));
		if($get_info[0]=='') {_err("Unabled to locate your username with that email address. Request is terminated.");}
		
               	$var_username=$gbl_env['username'];
		$var_name=$get_info[1];
		$var_email   =$gbl_env['email'];
		$var_password=$get_info[0];

                _email($gbl_env['email'],$_cfig[admin_email],$_cfig[admin_name],"Automate Password Recovery Message", preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/browse_lostpwd.txt")));

		print "+ An email have been dispatch to \"{$gbl_env['email']}\". &nbsp;&nbsp; <a href=\"browse.php\" target=\"_self\">Click here</a> to continue.";
	}
}
?>