<html>
<head>
<link rel="stylesheet" href="main.css">
</head>
<body>

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

  include( "initialize.php" );

  $numerrors = 0;

  $numerrors += loadsqlfile( "12_to_13.sql", "Upgrading AutoLinks 1.2 to 1.3" );
  
  $numerrors += loadsqlfile( "new_cat.sql", "Creating new al_cat table" );
  $numerrors += loadsqlfile( "new_conf.sql", "Creating new al_conf table" );
  $numerrors += loadsqlfile( "new_email.sql", "Creating new al_email table" );
  $numerrors += loadsqlfile( "new_hitclk.sql", "Creating new al_hitclk table" );
  $numerrors += loadsqlfile( "new_hitin.sql", "Creating new al_hitin table" );
  $numerrors += loadsqlfile( "new_hitout.sql", "Creating new al_hitout table" );
  $numerrors += loadsqlfile( "new_refarea.sql", "Creating new al_refarea table" );
  $numerrors += loadsqlfile( "new_tag.sql", "Creating new al_tag table" );
	
  $numerrors += loadsqlfile( "upg_aff.sql", "Upgrading the al_aff table" );
  $numerrors += loadsqlfile( "upg_site.sql", "Upgrading the al_site table" );
  $numerrors += loadsqlfile( "upg_redir.sql", "Upgrading the al_redir table" );
  $numerrors += loadsqlfile( "upg_hit.sql", "Upgrading the al_hit table" );
  $numerrors += loadsqlfile( "upg_img.sql", "Upgrading the al_img table" );
   
  if( $numerrors>0 ):
  
?>

<br><?=$numerrors?> SQL queries could not be executed properly. Please try to execute them yourself (save this page on your hard drive not to lose it) using <!--CyKuH-->MyAdmin or Telnet or ask your site administrator. Do not continue to the next step until all tables have been changed!
<form method="post" action="convertdata.php">
<input type="submit" name="continue" value="Continue to Next Step">
</form>

<? else: ?>

<form method="post" action="convertdata.php">
<input type="submit" name="continue" value="Continue to Next Step">
</form>

<? endif; ?>

</body>
</html>