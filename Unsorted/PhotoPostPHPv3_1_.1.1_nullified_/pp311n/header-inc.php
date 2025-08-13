<?
//////////////////////////// COPYRIGHT NOTICE //////////////////////////////
// Program Name  	 : PhotoPost PHP                                  //
// Program Version 	 : 3.11                                           //
// Contributing Developer: Michael Pierce                                 //
// Supplied By           : Poncho                                         //
// Nullified By          : CyKuH [WTN]                                    //
//  This script is part of PhotoPost PHP, a software application by       //
// All Enthusiast, Inc.  Use of any kind of part or all of this           //
// script or modification of this script requires a license from All      //
// Enthusiast, Inc.  Use or modification of this script without a license //
// constitutes Software Piracy and will result in legal action from All   //
//                                                                        //
//           PhotoPost Copyright 2002, All Enthusiast, Inc.               //
//                       Copyright WTN Team`2002                          //
////////////////////////////////////////////////////////////////////////////
// vBPortal Integration
// If you want to include the vBPortal header, footer, and left menu, remove
// the "//" slashes from the beginning of the 16 lines of code below, and change
// "/home/public_html/vbportal" to your actual path to vbportal's main directory,
// and change "/home/public_html/photopost" to your actual path to PhotoPost's
// directory. This will override the default header and footer variables set in the
// PhotoPost admin panel.

//$vbportal="/home/public_html/vbportal"; // No ending slash
//$pppath ="/home/public_html/photopost"; // No ending slash
//chdir($vbportal . "/");
//require ("mainfile.php");
//$index = 0;
//global $Pmenu,$Pheader;
//$Pheader="P_themeheader";
//$Pmenu="P_thememenu_photopost";
//require("header.php");
//$vbportal=ob_get_contents();
//ob_end_clean();
//ob_start();
//require("footer.php");
//$vbfooter=ob_get_contents();
//ob_end_clean();
//chdir($pppath . "/");

// vBulletin Integration
// Instead of using the static header/footer file specified in the Admin options
// panel, you can use your existing default vBulletin header/footer.  Just change
// $vbpath and $pppath below to the proper full paths and remove the "//" slashes
// from the beginning of the 17 lines of code below.  If PhotoPost has an odd
// background color or squished width, you will need to edit vbulletin's default
// "header" style input box / template and change "{pagebgcolor}" and "{tablewidth}"
// (near the bottom) to your preferred background color and table width, respectively.

//$vbpath ="/www/forum"; // changeme
//$pppath ="/www/photopost"; // changeme
//chdir($vbpath . "/");
//require($vbpath . "/global.php");
//ob_start();
//eval("dooutput(\"".gettemplate('headinclude')."\",0);");
//$bodytag="<body>";
//echo dovars($bodytag,0);
//eval("dooutput(\"".gettemplate('header')."\",0);");
//$vbheader=ob_get_contents();
//ob_end_clean();
//ob_start();
//eval("dooutput(\"".gettemplate('footer')."\",0);");
//$vbfooter=ob_get_contents();
//ob_end_clean();
//chdir($pppath . "/");

// UBBThreads Headers
// Set $PathToThreads to the path of your threads installation (INCLUDE TRAILING SLASH /)
// or leave blank if this page is located the threads directory
// Provided by JustDave

//$PathToThreads = "/home/sites/site1/web/ebti/forums/";
//require ("{$PathToThreads}main.inc.php");
//$userob = new user;
//$html = new html;
//if ($title == "") {$title = $config['title'];}
//$html -> send_header($title,$Cat,0,$user);

?>
