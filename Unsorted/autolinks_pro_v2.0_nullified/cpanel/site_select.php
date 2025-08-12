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
  
  $res_site = mysql_query( "SELECT * FROM al_site" );
  if( !mysql_num_rows($res_site) ) fatalerr( "You don't have any site in the database. <a href='site_add.php'>Click here</a> to add a site" );
  
?>

<html>
<head>
<link rel="stylesheet" href="main.css">
</head>
<body>
<form method="get" action="site_install.php">
  <p>Select on which site you want to install AutoLinks:</p>
  <blockquote>
  <p>
      <select name="login">

<?

  while( $site = mysql_fetch_array($res_site) )
  {
    echo( "<option value='{$site[login]}'>{$site[name]}</option>" );
  }

?>
        
      </select>
      <input type="submit" name="Submit" value="Go">
  </p>
  </blockquote>
</form>
<p>&nbsp;
</p>
</body>
</html>
