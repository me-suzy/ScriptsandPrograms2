<html>
<head>
<title>ezUPLOAD Pro - Install/Upgrade Utility by WTN Team</title>
<link rel="stylesheet" href="../cpanel.css" type="text/css">
</head>
<body>
<?
/////////////////////////////////////////////////////////////
// Program Name         : EzUpload Pro                       
// Program Version      : 2.20                                
// Program Author       : ScriptsCenter.com                  
// Supplied by          : CyKuH [WTN]                        
// Nullified and tested : CyKuH [WTN]                        
// Distribution         : via WebForum and Forums File Dumps 
//                    WTN Team `2004
/////////////////////////////////////////////////////////////
  if( $_POST['action']=="install" ):

    $path = "../";
	$filesdir = "../files";
    $numerrors = 0;

	include( "functions.php" );
	include( $path."classes.php" );
	

	//////////////////////////////////////////////////////
	// CHECK THE FILES PERMISSION / MYSQL CONNECTION
	//////////////////////////////////////////////////////

    include( "checkperms.php" );
	if( $numerrors>0 ) exit();
	
	if( $_POST['storage_method']=="mysql" )
	{
	  include( "checkmysql.php" );
	  if( $numerrors>0 ) exit();
    }
	
	
	//////////////////////////////////////////////////////
	// CREATE THE TABLEFILE INSTANCES
	//////////////////////////////////////////////////////
	
	$CONF = new tablefile( $path."var_settings.php" );
	
	if( $_POST['from']=="20" || $_POST['from']=="21" || $_POST['storage_method']=="file" )
	{
	  $FIELD = new tablefile( $path."var_fields.php" );
	  $UPLOAD = new tablefile( $path."var_uploads.php" );
	  $FILE = new tablefile( $path."var_files.php" );
	  $OPTION = new tablefile( $path."var_options.php" );
	  $UPLOADINFO = new tablefile( $path."var_uploadinfos.php" );
	  $USER = new tablefile( $path."var_users.php" );
	}
	else
	{
	  $FIELD = new tablefile();
	  $UPLOAD = new tablefile();
	  $UPLOADINFO = new tablefile();
	  $FILE = new tablefile();
	  $OPTION = new tablefile();
	  $USER = new tablefile();
	}
	
	
	//////////////////////////////////////////////////////
	// CONVERT DATA AND SETTINGS FROM PREVIOUS UPGRADES
	//////////////////////////////////////////////////////
	
	if( $_POST['from']=="21" || $_POST['from']=="20" || $_POST['from']=="10" || $_POST['from']=="11" )
	{
	  if( $_POST['from']=="20" || $_POST['from']=="10" || $_POST['from']=="11" )
	  {
	    if( $_POST['from']=="10" || $_POST['from']=="11" )
	    {
	      include( "upgrade1xto20.php" );
	    }
	  
	    include( "upgrade20to21.php" );
	  }
	  
	  include( "upgrade21to22.php" );
    }
	
	
	//////////////////////////////////////////////////////
	// IF NEW INSTALLATION, ENTER DEFAULT FORM FIELDS
	//////////////////////////////////////////////////////
	
	echo( "<br><b>CONFIGURING THE EZUPLOAD SETTINGS</b><br><br>" );
	
	if( $_POST['from']=="new" )
	{
	  echo( "Creating the default form fields... " );

	  $fields[1]['type'] = "text";
	  $fields[1]['name'] = "Full Name";
	  $fields[1]['minchars'] = 0;
	  $fields[1]['maxchars'] = 50;
	  $fields[1]['required'] = 1;

	  $fields[0]['type'] = "text";
	  $fields[0]['name'] = "Email Address";
	  $fields[0]['minchars'] = 0;
	  $fields[0]['maxchars'] = 50;
	  $fields[0]['required'] = 1;

	  $fields[2]['type'] = "file";  
	  $fields[2]['name'] = "File #1";
	  $fields[2]['required'] = 1;
  
	  $fields[3]['type'] = "file";
	  $fields[3]['name'] = "File #2";
	  $fields[3]['required'] = 0;

	  $fields[4]['type'] = "file";  
	  $fields[4]['name'] = "File #3";
	  $fields[4]['required'] = 0;

	  $fields[5]['type'] = "file";
	  $fields[5]['name'] = "File #4";
	  $fields[5]['required'] = 0;

	  $fields[6]['type'] = "file";
	  $fields[6]['name'] = "File #5";
	  $fields[6]['required'] = 0;

	  $fields[7]['type'] = "textarea";
	  $fields[7]['name'] = "Description";
	  $fields[7]['minchars'] = 0;
	  $fields[7]['maxchars'] = 200;
	  $fields[7]['required'] = 0;

	  foreach( $fields AS $fieldrow )
	  {
		$fieldid = $FIELD->addrow();
		$FIELD->setrow( $fieldrow, $fieldid );
	      
		// enter other standard fields
		$FIELD->setval( "", "description", $fieldid );
		$FIELD->setval( "", "default", $fieldid );
		$FIELD->setval( $fieldid, "order", $fieldid );
	  }
	  
	  test( true );
	}
	
	
	//////////////////////////////////////////////////////
	// SAVE THE DATA ENTERED BY THE USER
	//////////////////////////////////////////////////////
	
	echo( "Configuring the storage method... " ); 
	
    $CONF->setval( $_POST['storage_method'], "storage_method" );
	$CONF->setval( "2.2.0", "version" );
	
	if( $_POST['storage_method']=="mysql" )
	{
      $CONF->setval( $_POST['dbhost'], "dbhost" );
      $CONF->setval( $_POST['dbuser'], "dbuser" );
      $CONF->setval( $_POST['dbpass'], "dbpass" );
      $CONF->setval( $_POST['dbname'], "dbname" );
	}
	
	test( true );
	
	
	//////////////////////////////////////////////////////
	// IF MYSQL, CREATE TABLES AND CONVERT INSTANCES
	//////////////////////////////////////////////////////
	
	if( $_POST['storage_method']=="mysql" )
	{
	  // insert the mysql tables
	  echo( "Inserting the MySQL tables... " );
	  $result = test( loadsqlfile( "tables.sql" ) );
	  
	  if( $result )
	  {
	    echo( "Converting the data to MySQL... " );
	    // convert tablefile instances to tablesql
	    $FIELD = convertfiletosql( $FIELD, "ezu_fields" );
	    $UPLOAD = convertfiletosql( $UPLOAD, "ezu_uploads" );
	    $UPLOADINFO = convertfiletosql( $UPLOADINFO, "ezu_uploadinfos" );
	    $FILE = convertfiletosql( $FILE, "ezu_files" );
	    $OPTION = convertfiletosql( $OPTION, "ezu_options" );
	    $USER = convertfiletosql( $USER, "ezu_users" );
	    test( true );
	  }
	}
	
	//////////////////////////////////////////////////////
	// SAVE ALL THE DATA TO FILES OR MYSQL
	//////////////////////////////////////////////////////
	
	echo( "Saving all the settings and informations... " );
	
	$FIELD->savedata();
	$UPLOAD->savedata();
	$UPLOADINFO->savedata();
	$FILE->savedata();
	$OPTION->savedata();
	$USER->savedata();
	$CONF->savedata();
	
	test( true );
?>

<br>
The installation/upgrade of ezUpload has been successful. For your security,<br>
please delete the /install directory. You can then go to the <a href="../cpanel.php">control panel</a>.
	
<? else: ?>

This utility will help you to install or upgrade ezUpload Pro 2.2.<br>
For your security, please make a backup of the var_ files before upgrading.
<br><br>
<table width="350" border="0" cellspacing="0" cellpadding="5">
  <form method="post" action="index.php">
    <input type="hidden" name="action" value="install">
    <tr> 
      <td width="120">Installation Type</td>
      <td align="right"> <select name="from">
          <option value="new">Fresh installation</option>
          <option value="10">Upgrade from ezUpload 1.0</option>
          <option value="11">Upgrade from ezUpload 1.1</option>
          <option value="20">Upgrade from ezUpload 2.0.x</option>
          <option value="21">Upgrade from ezUpload 2.1</option>
        </select> </td>
    </tr>
    <tr> 
      <td width="120">Storage Method</td>
      <td align="right"> <input name="storage_method" type="radio" value="file" checked>
        PHP files 
        <input name="storage_method" type="radio" value="mysql">
        MySQL database </td>
    </tr>
    <tr> 
      <td width="120">MySQL Host</td>
      <td align="right"><input name="dbhost" type="text" value="localhost" size="30"></td>
    </tr>
    <tr> 
      <td width="120">MySQL User/Pass</td>
      <td align="right"><input name="dbuser" type="text" size="13">&nbsp; <input name="dbpass" type="text" size="13"></td>
    </tr>
    <tr> 
      <td width="120">Database Name</td>
      <td align="right"><input name="dbname" type="text" size="30"></td>
    </tr>
    <tr> 
      <td width="120">&nbsp;</td>
      <td align="right" valign="right"><input type="submit" name="upgrade" value="Continue &gt;&gt;"></td>
    </tr>
  </form>
</table>

<? endif; ?>

</body>
</html>