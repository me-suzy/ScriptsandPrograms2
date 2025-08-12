<?
/////////////////////////////////////////////////////////////
// Program Name         : Autolinks Professional            
// Program Version      : 2.0                               
// Program Author       : ScriptsCenter                     
// Supplied by          : CyKuH [WTN] , Stive [WTN]         
// Nullified by         : CyKuH [WTN]                       
// Distribution         : via WebForum and Forums File Dumps
//                   (c) WTN Team `2002
/////////////////////////////////////////////////////////////
  include( "cp_initialize.php" );

  // force to download as .php file
  header( "Content-type: application/octet-stream" );   
  header( "Content-disposition: attachment; filename=variables.php" );   
  
  echo( "<?php\n\n\$mysql_host = \"$mysql_host\";\n\$mysql_user = \"$mysql_user\";\n\$mysql_pass = \"$mysql_pass\";\n\$mysql_db = \"$mysql_db\";\n\$sitelogin = \"$login\";\n\n?>" );

?>