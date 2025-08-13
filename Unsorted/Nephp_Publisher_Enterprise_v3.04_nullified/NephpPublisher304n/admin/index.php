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
include("../global.php");
include("../gbl_utilities.php");

$m_connect = mysql_connect($_cfig[sql_serverip], $_cfig[sql_username],$_cfig[sql_password])
             or die ("Unabled to make the sql connection");
////////////////////////////////////////////////////////////////////////////////////////////////
// Session Cron-Job                                                                           //
////////////////////////////////////////////////////////////////////////////////////////////////
$vb_db=$_cfig{"vb_db"};
$vb_isvb =$_cfig{"isvb"};
$vb_url  =$_cfig{"vb_url"};
$vb_pass =$HTTP_COOKIE_VARS["bbpassword"];
$vb_id   =$HTTP_COOKIE_VARS["bbuserid"];
$sid=_sid(32); $tpl_template_url=$_cfig[url_tpl];$url_php=$_cfig[url_php];

///////////////////////////////////////////
$in_date=_date(time());
///////////////////////////////////////////
if ($gbl_env["action"] == "" && strlen($HTTP_COOKIE_VARS["nelogicphpcookset01"])==32)
{
                header("Location: admin.php?sid=".$HTTP_COOKIE_VARS["nelogicphpcookset01"]);

}
	elseif ($gbl_env["action"] == "login" || ($gbl_env["action"] == "" && strlen($HTTP_COOKIE_VARS["nelogicphpcookset01"]) <1))
        {
                if($gbl_env["opt"] == "")
                {
                        print preg_replace("/{%(\w+)%}/ee","$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_login.html",0));
                }
                else
                {
		      	if($gbl_env["username"]==$_cfig[admin_user] && $gbl_env["password"]==$_cfig[admin_pass])
			{
				mysql_query("INSERT INTO `$_cfig[sql_db]`.`nnet_session` (`session`,`uid`,`status`,`usr`,`time`) VALUES ('$sid','0','admin','$_cfig[admin_name]','".time()."');")
                		or die("Error #".mysql_errno().": ".mysql_error());
                        	setcookie("nelogicphpcookset01", $sid);
				$data[1]=$_cfig[admin_name];
			}
			else
			{
				$data =mysql_fetch_row(mysql_query("SELECT `nnet_uid`,`nnet_uname`,`nnet_type`,`nnet_able` FROM `$_cfig[sql_db]`.`nnet_users` WHERE `nnet_user`='".$gbl_env["username"]."' AND `nnet_pass`='".$gbl_env["password"]."'"));
			
				if($data[2]=='') { _err("Invalid Login Combination. Initialization failed."); }
				if($data[3]==0)  { _err("Your account has been lock. Please contact the administrator.");}
			
				if($data[2]==1)     { $input_mod="mod"; }
				elseif($data[2]==2) { $input_mod="admin"; }
				else                { $input_mod="usr"; }
				mysql_query("INSERT INTO `$_cfig[sql_db]`.`nnet_session` (`session`,`uid`,`status`,`usr`,`time`) VALUES ('$sid','$data[0]','$input_mod','$data[1]','".time()."');")
                		or die("Error #".mysql_errno().": ".mysql_error());
                        	setcookie("nelogicphpcookset01", $sid);
			}
			print "Welcome, <b>$data[1]</b>. You have logged in control panel sucessfully.&nbsp;&nbsp; <a href=\"admin.php?sid=$sid\">Click here</a> for `post-accesss`.";
                }
	}
        elseif ($gbl_env["action"] == "clear")
        {
		mysql_query("DELETE FROM `$_cfig[sql_db]`.`nnet_session` WHERE `session`='{$HTTP_COOKIE_VARS["nelogicphpcookset01"]}';")
		or die("Error #".mysql_errno().": ".mysql_error());
                setcookie ("nelogicphpcookset01",'',time()-3600);

                header("Location: index.php?action=login");
        }
mysql_close($m_connect);
?>