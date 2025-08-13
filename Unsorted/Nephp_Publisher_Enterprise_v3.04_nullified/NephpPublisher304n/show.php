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

/////////////////////////////////////////////////////////
include("gbl_utilities.php");
include("global.php");

$tpl_template_url=$_cfig[url_tpl];
///////////////////////////////////////////////////////////////////////////
$ad=localtime();$ad[4]++;
$in_date=date ("M d Y H:i:s", mktime ($ad[2],$ad[1],$ad[0],$ad[4],$ad[3],$ad[5]));

$id=$gbl_env["id"];
// PUBLISH MYSQL CONNECTION
$m_connect = mysql_connect($_cfig[sql_serverip], $_cfig[sql_username],$_cfig[sql_password])
             or die ("Unabled to make the sql connection");

if($gbl_env["id"]=='')
{
	_err("- Please specify document id");
}

if(file_exists("$_cfig[dir_upload]/full_".$gbl_env["id"].".jpg"))
{
	$filename="$_cfig[url_upload]/full_".$gbl_env["id"].".jpg";
}
elseif(file_exists("$_cfig[dir_upload]/full_".$gbl_env["id"].".gif"))
{
	$filename="$_cfig[url_upload]/full_".$gbl_env["id"].".gif";
}

if($filename=='')
{
	$var_enlarge='No image to enlarge';
}
else
{
	$var_enlarge="<img src=\"$filename\" border=0>";
}
$url_article="browse.php?mod=article&opt=view&id=".$gbl_env["id"];
extract(mysql_fetch_array(mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_aid`='".$gbl_env["id"]."';"),MYSQL_ASSOC));
$nnet_time=_date($nnet_time);
$rate_bar="<img border=0 src=\"$tpl_template_url/gfx/stars/$nnet_rrate.gif\">";
$gbl_microtime=microtime()-$gbl_microtime;
print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_articles_enlarge.html",0));
?>



