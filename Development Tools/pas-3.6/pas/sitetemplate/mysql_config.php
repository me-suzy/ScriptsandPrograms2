<?php 
// Copyright 2001 - 2004 SQLFusion LLC           info@sqlfusion.com
  /**
   * MySQL Main configuration page
   *
   * Include that file in all the files that will uses PAS objects.
   *
   * @package PASSiteTemplate
   * @author Philippe Lewicki  <phil@sqlfusion.com>
   * @copyright  SQLFusion LLC 2001-2004
   * @version 3.0
   */
   
  if (!isset($cfg_full_path)) { $cfg_full_path = ""; }
  
  $cfg_local_pasdir = $cfg_full_path."../pas/" ; // path to pas from your app usualy its : "../pas/"
  $cfg_local_db = "mysql" ;
  $cfg_eventcontroler = "eventcontroler.php" ;
  $cfg_lang = "us" ;
  // For compatibility with mydb
  $cfg_local_mydbdir = $cfg_local_pasdir;
  // diseable secure events, will show all the parameters of forms and links.
  //define("MYDB_EVENT_SECURE", false);
  define("PAS_DEFAULT_REPORT_TEMPLATE", "default_report");
  define("PAS_DEFAULT_FORM_TEMPLATE", "default_form");   
  // Change this key. This is the key that authorized event execution coming from not local domain.
  $cfg_notrefererequestkey = "@refererkey" ;
  
  include_once($cfg_local_pasdir."class/BaseObject.class.php") ;
  include_once($cfg_local_pasdir."class/".$cfg_local_db."/sqlConnect.class.php") ;
  include_once($cfg_local_pasdir."class/".$cfg_local_db."/sqlQuery.class.php") ;
  include_once($cfg_local_pasdir."class/Registry.class.php") ;
  include_once($cfg_local_pasdir."class/sqlSavedQuery.class.php") ;
  include_once($cfg_local_pasdir."class/Report.class.php") ;
  include_once($cfg_local_pasdir."class/libReport.php") ;
  include_once($cfg_local_pasdir."class/EventControler.class.php") ;
  include_once($cfg_local_pasdir."class/Display.class.php") ;
  include_once($cfg_local_pasdir."class/Event.class.php") ;
  include_once($cfg_local_pasdir."class/ReportForm.class.php") ;
  include_once($cfg_local_pasdir."class/ReportTable.class.php") ;
  
  // Turn off errors display on production site 
  // or when using the pas pagebuilder
  //error_reporting(0);
  
  if (file_exists("includes/extraconfig.inc.php")) {
        include_once("includes/extraconfig.inc.php") ;
  };
  
  session_start() ;

  include("includes/lang_".$cfg_lang.".inc.php") ;
  session_register("cfg_lang") ;
  // Database connexions :

  $conx = new sqlConnect("@login", "@password") ;
  $conx->setHostname("@hostname") ;
  $conx->setDatabase("@database") ;
  // Directory where pas is located
  $conx->setBaseDirectory($cfg_local_pasdir) ;
  // Directory where the project is located unless your config.php file is outside your project tree is should be "./"
  $conx->setProjectDirectory($cfg_full_path."./") ;
  $conx->start() ;

  include("includes/globalvar.inc.php") ;
?>
