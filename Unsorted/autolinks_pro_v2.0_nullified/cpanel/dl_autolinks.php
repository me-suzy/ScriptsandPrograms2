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

  // find the autolinks dir
  $res_site = mysql_query( "SELECT * FROM al_site WHERE login='$login' LIMIT 1" );
  $site = mysql_fetch_array( $res_site );
  $aldir = getaldir( $site[alurl] );
  
  // force to download as .php file
  header( "Content-type: application/octet-stream" );   
  header( "Content-disposition: attachment; filename=autolinks.php" );   
  
  echo( "<?php\n\nif( isset(\$i) )\n  header( \"Location: $aldir?i=\$i\" );\nelseif( isset(\$o) )\n  header( \"Location: $aldir?o=\$o\" );\n\n?>" );
  
?>