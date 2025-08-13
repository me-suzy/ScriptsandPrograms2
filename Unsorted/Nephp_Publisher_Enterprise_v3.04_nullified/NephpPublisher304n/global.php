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
 error_reporting(0);

 // Variable declaration;
 
 // PATH DEFINE


 $_cfig[dir_library]    = "E:/works/www/plugins";
 $_cfig[dir_skins]      = "E:/works/www/skins";
 $_cfig[url_skins]      = "http://localhost/skins";

 $_cfig[dir_static]     = "E:/works/www/static";
 $_cfig[url_static]     = "http://localhost/static";

 $_cfig[dir_upload]     = "E:/works/www/upload";
 $_cfig[url_upload]     = "http://localhost/upload";

 $_cfig[dir_php]        = "E:/works/www";
 $_cfig[url_php]        = "http://localhost";

 // VB INTEGRATION
 $_cfig{"isvb"}       = 0;                // NEED TO CHANGE  0= no integration, 1 = vb integration
 $_cfig{"vb_db"}      ="vb";             // VBULLETIN DATABASE NAME
 $_cfig{"vb_url"}     ="http://localhost/vb";             // VBULLETIN WEB ADDRESS


 // SQL Database information
 $_cfig[sql_username]   = "root";
 $_cfig[sql_password]   = "";
 $_cfig[sql_serverip]   = "localhost";
 $_cfig[sql_db]         = "nn";

 // Join Variables
 if(file_exists("$_cfig[dir_php]/config.php")) {include("$_cfig[dir_php]/config.php");}
 $_cfig=array_merge($_cfig,$_dfig);

 // No need to modify
 $_cfig[dir_tpl]      = $_cfig[dir_skins]."/".$_cfig[template];
 $_cfig[url_tpl]      = $_cfig[url_skins]."/".$_cfig[template];
 
 // CHECK FOR REQUEST METHOD
 if(preg_match("/post/i",getenv("REQUEST_METHOD"))) { $gbl_env=$HTTP_POST_VARS; }
 else                                               { $gbl_env=$HTTP_GET_VARS;  }

 function _detect()
 {
         return 1;
 }
?>