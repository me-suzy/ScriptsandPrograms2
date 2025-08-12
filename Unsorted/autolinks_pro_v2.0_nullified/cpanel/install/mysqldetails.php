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
  include( "functions.php" );

  $success = false;

  if( $submitted=="submitmysql" )
  { 
    echo( "Testing connection to MySQL... " );
    $result = @mysql_connect( $mysql_host, $mysql_user, $mysql_pass );
	
    if( !$result )
    {
      echo( "<br>Unable to connect to MySQL!<br><br>" );
    }
    else
    {
	  echo( "OK<br>" );
	  
	  echo( "Detecting the '$mysql_db' database... " );
      $result = @mysql_select_db( $mysql_db );
	  
	  if( !$result )
	  {
        echo( "<br>Unable to find the database!<br><br>" );
	  }
	  else
	  {
	    echo( "OK<br>" );
		
	    echo( "Creating variables.php file... " );
        $fp = @fopen( "../variables.php", "w" );
		
		if( !$fp )
		{
          echo( "<br>Unable to create the file, make sure the cpanel directory is write-enabled!<br><br>" );
		}
		else
		{
		  echo( "OK<br>" );
		  
		  $variables .= "<?php\n\n";
		  $variables .= "\$mysql_host = \"$mysql_host\";\n";
		  $variables .= "\$mysql_user = \"$mysql_user\";\n";
		  $variables .= "\$mysql_pass = \"$mysql_pass\";\n";
		  $variables .= "\$mysql_db = \"$mysql_db\";\n";
		  $variables .= "\n?>";
		  
		  fwrite( $fp, $variables );
		  fclose( $fp );
		  
		  $numerrors = test_privileges();
		  
		  if( $numerrors>0 )
		  {
		    echo( "<br>The MySQL configuration has been successful but the tables cannot be installed/upgraded because the user $mysql_user@$mysql_host doesn't have enough privileges. So you'll have to execute this <a href='$type.sql' target='_blank'>$type.sql</a> file either through phpMyAdmin (recommended) or Telnet. See with your host if this still doesn't work. " );
			
			if( $type!="newinstall" )
			  echo( "Once this is done, go to <a href='convertdata.php'>this page</a> to convert various data, including the images, to AutoLinks 2.0. This is very important." );
			else
			  echo( "Once this is done, go <a href='install_finish.php'>here</a> to finish the installation." );
			
			exit;
		  }
		  else
		  {
		    $success = true;
		  }
		}
	  }
    }
  }
  else
  {
	$mysql_host = localhost;
	$mysql_db = autolinks;
  }

 if( !$success ):
 
?>

To start with, you must provide the info to connect to the MySQL database. The database must have been created before. The info will be written on the variables.php file so make sure the /cpanel/ folder is write-enabled (mode 777). To know how to change the mode to 777.
<br>
<form method="post" action="<?=$PHP_SELF?>">
<input type="hidden" name="type" value="<?=$type?>">
<input type="hidden" name="submitted" value="submitmysql">
<table border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="130"><font size="2">MySQL Host</font></td><!--CyKuH-->
    <td>
      <input type="text" name="mysql_host" value="<?=$mysql_host?>">
    </td>
  </tr>
  <tr>
    <td width="130"><font size="2">MySQL User</font></td>
    <td>
      <input type="text" name="mysql_user" value="<?=$mysql_user?>">
    </td>
  </tr>
  <tr>
    <td width="130"><font size="2">MySQL Pass</font></td>
    <td>
      <input type="password" name="mysql_pass" value="<?=$mysql_pass?>">
    </td>
  </tr>
  <tr>
    <td width="130"><font size="2">Database Name</font></td>
    <td>
      <input type="text" name="mysql_db" value="<?=$mysql_db?>">
    </td>
  </tr>
  <tr>
    <td width="130">&nbsp;</td>
    <td align="right">
      <input type="submit" name="Submit" value="Submit">
    </td>
  </tr>
</table>
</form>

<? else: ?>

Database connection correct!
<br>
<form method="post" action="<?=$type?>.php">
<input type="submit" name="Submit" value="Continue to Next Step">
</form>

<? endif; ?>

</body>
</html>