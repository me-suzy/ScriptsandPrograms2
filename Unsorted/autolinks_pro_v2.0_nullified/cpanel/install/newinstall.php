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
echo ("<p>Nullified Version &copy WTN Team `2002</p>");
  include( "initialize.php" );

  $numerrors = 0;

  $numerrors += loadsqlfile( "new_cat.sql", "Creating new al_cat table" );
  $numerrors += loadsqlfile( "new_conf.sql", "Creating new al_conf table" );
  $numerrors += loadsqlfile( "new_email.sql", "Creating new al_email table" );
  $numerrors += loadsqlfile( "new_hitclk.sql", "Creating new al_hitclk table" );
  $numerrors += loadsqlfile( "new_hitin.sql", "Creating new al_hitin table" );
  $numerrors += loadsqlfile( "new_hitout.sql", "Creating new al_hitout table" );
  $numerrors += loadsqlfile( "new_refarea.sql", "Creating new al_refarea table" );
  $numerrors += loadsqlfile( "new_tag.sql", "Creating new al_tag table" );
	
  $numerrors += loadsqlfile( "new_ref.sql", "Creating new al_hitclk table" );
  $numerrors += loadsqlfile( "new_site.sql", "Creating new al_hitin table" );
  $numerrors += loadsqlfile( "new_redir.sql", "Creating new al_hitout table" );
  $numerrors += loadsqlfile( "new_stats.sql", "Creating new al_refarea table" );
  $numerrors += loadsqlfile( "new_img.sql", "Creating new al_tag table" );
	
  if( $numerrors>0 ):
  
?>

<br><?=$numerrors?> SQL queries could not be executed properly. Please try to execute them yourself (save this page on your hard drive not to lose it) using phpMyAdmin or Telnet<!--CyKuH--> or ask your site administrator.
<form method="post" action="install_finish.php">
<input type="submit" name="continue" value="Continue to Next Step">
</form>

<? else: ?>

<form method="post" action="install_finish.php">
<input type="submit" name="continue" value="Continue to Next Step">
</form>

<? endif; ?>

</body>
</html>