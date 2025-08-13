<?php
/////////////////////////////////////////////////////////////
// Program Name         : EzUpload Pro                       
// Program Version      : 2.20                                
// Program Author       : ScriptsCenter.com                  
// Supplied by          : CyKuH [WTN]                        
// Nullified and tested : CyKuH [WTN]                        
// Distribution         : via WebForum and Forums File Dumps 
//                    WTN Team `2004
/////////////////////////////////////////////////////////////
  echo( "<br><b>UPGRADING FROM EZUPLOAD 2.0 TO 2.1</b><br><br>" );
  ////////////////////////////////////////////
  // CONVERT SETTINGS FROM EZUPLOAD 2.0.X
  ////////////////////////////////////////////
	
  $old_subdir = $CONF->getval("subdir");
	  
  if( is_numeric($old_subdir) )
  {
	$CONF->setval( "field", "subdir" );
	$CONF->setval( $old_subdir, "subdir_field" );
  }
  else
  {
	$CONF->setval( -1, "subdir_field" );
  }
		
  // delete unused fields
  $CONF->deletevalue( "tablealign" );
	  
  // add all the new variables
  $CONF->setval( "default", "email_method" );
  $CONF->setval( "localhost", "smtp_host" );
  $CONF->setval( 25, "smtp_port" );
  $CONF->setval( "<html>
<head>
<title>Upload Form</title>
</head>
<body bgcolor=\"#FFFFFF\">
<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" valign=\"middle\" align=\"center\" height=\"100%\">
  <tr>
    <td>
      <table border=\"0\" cellspacing=\"1\" cellpadding=\"10\" bgcolor=\"#000000\">
        <tr bgcolor=\"#F2F2F2\"> 
          <td> ", "header" );
  $CONF->setval( "          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>", "footer" );
  $CONF->setval( "", "formpass" );
  $CONF->setval( -1, "namefield" );
  $CONF->setval( 1, "includelinks" );
  $CONF->setval( "", "notifyemails" );
  $CONF->setval( "lang_english.php", "language_pack" );
  $CONF->setval( 0, "display_warning" );
  $CONF->setval( 1, "js_detection" );
  $CONF->setval( 0, "timezone" );
  $CONF->setval( 0, "autodel_files" );
  $CONF->setval( 0, "autodel_info" );
  $CONF->setval( 0, "autodel_dir" );

?>