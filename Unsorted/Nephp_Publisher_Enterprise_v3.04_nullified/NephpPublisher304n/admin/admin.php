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

// GET SPEED IN MICRO SECS
$gbl_microtime=microtime();


// PUBLISH MYSQL CONNECTION
$m_connect = mysql_connect($_cfig[sql_serverip], $_cfig[sql_username],$_cfig[sql_password])
             or die ("Unabled to make the sql connection");

////////////////////////////////////////////////////////////////////////////////////////////////
// Cron-Job: Cleaning Session                                                                 //
////////////////////////////////////////////////////////////////////////////////////////////////
mysql_query("DELETE FROM `$_cfig[sql_db]`.`nnet_session` WHERE (".time()." -`time`)>13000;")
or die("Error #".mysql_errno().": ".mysql_error());

if($gbl_env["sid"] !='')
{
	$sid= $gbl_env["sid"];}else{$sid=$HTTP_COOKIE_VARS[nelogicphpcookset01];
}
////////////////////////////////////////////////////////////////////////////////////////////////
// Session Check-Point                                                                        //
////////////////////////////////////////////////////////////////////////////////////////////////
$result = mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_session` WHERE `session`='$sid' LIMIT 1")
          or die("Error #".mysql_errno().": ".mysql_error());
if(mysql_num_rows($result) > 0)
{
        extract(mysql_fetch_array($result, MYSQL_ASSOC));
        // vars: session  uid  status  usr  time
}
mysql_free_result($result);
global $gbl_sid,$gbl_id,$gbl_type, $gbl_name;

$gbl_sid=$session; $gbl_id=$uid;
$gbl_type=$status; $gbl_name=$usr;

/////////////////////////////////////////////////////////////////////
if($gbl_name==''){$gbl_name="Guest";}
if($gbl_type==''){$gbl_type="guest";}
/////////////////////////////////////////////////////////////////////
// GET PERMISSION                                                  //
/////////////////////////////////////////////////////////////////////
$nnet_node=explode('|',$_cfig["permission_$gbl_type"]);
while(list($tmp1,$tmp2)=each($nnet_node))
{
	list($tmp3,$tmp4)=explode("=",$tmp2);
	$cperf[$tmp3]=$tmp4;
}
// PERMISSION CHECK-POINT
if($cperf['cpanel']!=1)
{
	_err("$gbl_name. Your account level does not have control panel access.<Br>Your current account type is ('$gbl_type'). Please contact adminstrator to set appropriate permission. <br><a href='index.php?action=clear' target='_top'>Log-in</a> with a different account.");
}
// MYSQL STRIPPING REQUEST
$gbl_mnv=array();
while(list($tmp1,$tmp2)= each ($gbl_env))
{
	$gbl_mnv[$tmp1]=addslashes(stripslashes($tmp2));
}
// TEMPLATE BASES
if($env["style"]!=''){$_cfig[template]=$env["style"];}
if($gbl_env["mod"]=='')
{
	if(!include("$_cfig[dir_library]/admin_main.php"))
	{
		_err('Invalid <b>plugins</b> can pick up this request.');
	}
	admin_main();
}
else
{
	if(file_exists("$_cfig[dir_library]/admin_".$gbl_env["mod"].".php"))
	{
		if(!include("$_cfig[dir_library]/admin_".$gbl_env["mod"].".php"))
		{
			_err('Invalid <b>plugins</b> can pick up this request.');
		}
		eval("if(!function_exists('admin_$gbl_env[mod]'))
		      {
	                    _err('No appropriate <b>plugins</b> can pick up this request.');
	              }
	              else
	              {
	                    admin_$gbl_env[mod]();
	              }
	        ");
	}
	else
	{
		_err('No appropriate <b>plugins</b> can pick up this request.');
	}
}

// ENDING MYSQL CONNECTION
mysql_close($m_connect);
?>