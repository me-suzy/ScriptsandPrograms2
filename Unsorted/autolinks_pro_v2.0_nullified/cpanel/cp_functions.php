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

  function stripslashes_array($arr = array())
  {
    $rs = array();
    while (list($key,$val) = each($arr)) {
      if (is_array($val)) {
        $rs[$key] = stripslashes_array($val);
      } else {
        $rs[$key] = stripslashes($val);
      }
    }
    return $rs;
  }

  function fatalerr( $message )
  {
    echo( "<html><head><link rel='stylesheet' href='main.css'></head><body>" );
	echo( $message );
	echo( "</body></html>" );
	exit;
  }
  
  function showmessage()
  {
    global $notice, $info;
  
    if( isset($notice) ) echo( "<font color='red'>$notice</font><br><br>" );
	if( isset($info) ) echo( "$info<br><br>" );
  }

  function getextension( $mimetype )
  {
    switch( $mimetype )
    {
      case "image/gif": return "gif";
      case "image/pjpeg": 
      case "image/jpeg": return "jpg";
      default: return false;
    }
  }
  
  function getaldir( $alurl )
  {
    return strstr( substr($alurl,8), "/" );
  }

  function multicats( $login="_all_" )
  {
    if( $login=="_all_" )
	{
	  $res_cat = mysql_query( "SELECT * FROM al_cat WHERE name!=''" );
	  
	  if( mysql_num_rows($res_cat) > 1 )
	    return true;
	  else
	    return false;
	}
	else
	{
      $res_site = mysql_query( "SELECT * FROM al_site WHERE login='$login' LIMIT 1" );
	  $site = mysql_fetch_array( $res_site );

	  if( count( explode(",", $site[categories]) ) > 1 )
	    return true;
	  else
	    return false;
	}
  }
  
  function checkurl( $url )
  {
    if( substr($url, -9) == "index.php" ) $url = ereg_replace( "index.php", "", $url );
    if( substr($url, -9) == "index.htm" ) $url = ereg_replace( "index.htm", "", $url );
    if( substr($url, -10) == "index.html" ) $url = ereg_replace( "index.html", "", $url );
    if( substr($url, -1) != "/" ) $url .= "/";

    return $url;
  }

  function rand_string( $length )
  { 
    $allchar = "abcdefghijklnmopqrstuvwxyzABCDEFGHIJKLNMOPQRSTUVWXYZ0123456789"; 
    $str = ""; mt_srand( (double) microtime() * 1000000 ); 
    for ( $i=0; $i<$length; $i++ ) $str .= substr( $allchar, mt_rand(0,62), 1 ); 
    return $str; 
  }

  function fill_ref_vars( $template, $ref )
  {
    global $CONF;
  
    $res_site = mysql_query( "SELECT * FROM al_site WHERE login='{$ref['fromsite']}' LIMIT 1" );
    $site = mysql_fetch_array( $res_site );

    $template = str_replace( "[name]", $ref[name], $template );
    $template = str_replace( "[login]", $ref[login], $template );
    $template = str_replace( "[pass]", $ref[password], $template );
    $template = str_replace( "[url]", $ref[url], $template );
    $template = str_replace( "[email]", $ref[email], $template );
    $template = str_replace( "[code]", $ref[code], $template );
    $template = str_replace( "[refarea]", $site[alurl], $template );

    // get the category name
    $res_cat = mysql_query( "SELECT * FROM al_cat WHERE id='{$ref['category']}' LIMIT 1" );
    $cat = mysql_fetch_array( $res_cat );
    $template = str_replace( "[category]", $cat['name'], $template );

    // get the sites that accept this category
    $res_site = mysql_query( "SELECT * FROM al_site WHERE status=1 AND FIND_IN_SET('{$ref['category']}', categories)>0" );

    while( $site = mysql_fetch_array($res_site) )
    {
      if( isset($sites) ) $sites .= "\n";
      if( isset($links) ) $links .= "\n";

      $sites .= "  {$site[name]}: {$site[url]}";
      $links .= "  {$site[name]}: {$site[alurl]}?i={$ref[login]}";
    }

    $template = str_replace( "[sites]", $sites, $template );
    $template = str_replace( "[links]", $links, $template );

    $template = str_replace( "[admin_name]", $CONF[admin_name], $template );
    $template = str_replace( "[admin_email]", $CONF[admin_email], $template );

    return $template;
  }

  function email_templ( $emaillogin, $reflogin )
  {
    global $CONF;
  
    $res_email = mysql_query( "SELECT * FROM al_email WHERE login='$emaillogin' LIMIT 1" );
    $email = mysql_fetch_array( $res_email );

    $res_ref = mysql_query( "SELECT * FROM al_ref WHERE login='$reflogin' LIMIT 1" );
    $ref = mysql_fetch_array( $res_ref );

    $title = fill_ref_vars( $email[title], $ref );
    $content = fill_ref_vars( $email[content], $ref );

    switch( $email[mailto] )
	{
	  case "referrer": mail( $ref[email], $title, $content, "From: {$CONF[admin_name]} <{$CONF[admin_email]}>\nReply-To: {$CONF[admin_email]}" ); break;
	  case "admin": mail( $CONF[admin_email], $title, $content, "From: {$ref[name]} Webmaster <{$ref[email]}>\nReply-To: {$ref[email]}" ); break;
	}
  }

  // add ou update an image on the database
  function uploadimage( $imagefile, $mime, $type, $login, $format )
  {
    // check it's a valid type
    $result = checkformat( $imagefile, $format );
	if( $result != "success" ) return $result;
	
    // read the image from the file and add slashes
	if( !$fp = fopen( $imagefile, "rb" ) ) return "erroropen";
	$imageraw = addslashes( fread( $fp, filesize($imagefile) ) );
	fclose( $fp );
	
	// get the image extension
	$extension = getextension( $mime );
  
    // check if image already exists on database
    $res_img = mysql_query( "SELECT id, login, extension FROM al_img WHERE type='$type' AND login='$login' AND format='$format' LIMIT 1" );
  
    if( mysql_num_rows($res_img)==0 )
	{
	  mysql_query( "INSERT INTO al_img SET
	  
	  				type='$type',
					login='$login',
					format='$format',
					extension='$extension',
					updated=NOW(),
					rawdata='$imageraw'" );
	}
	else
	{
	  // get the previous image data + id
	  $img = mysql_fetch_array( $res_img );

	  // delete the previous image (extension)
	  @unlink( "images/$type/$format/{$img[login]}.{$img[extension]}" );
	
	  mysql_query( "UPDATE al_img SET 
	  
	  				type='$type',
					login='$login',
					format='$format',
					extension='$extension',
					updated=NOW(),
					rawdata='$imageraw'	WHERE id={$img[id]}" );
	}
  
    $result = updateimage( $type, $login, $format );
    if( $result != "success" ) return $result;

	return true;
  }
  
  function checkformat( $imagefile, $format )
  {
    // get various info from the imagefile
    $image_info = getimagesize( $imagefile );
	$image_width = $image_info[0];
	$image_height = $image_info[1];
	$image_type = $image_info[2];
	
	if( $format=="banner" )
	{
	  if( $image_type!=1 && $image_type!=2 ) return "errortype";
	  if( $image_width!=468 || $image_height!=60 ) return "errorsize";
	}
	elseif( $format=="thumb" )
	{
	  if( $image_type!=1 && $image_type!=2 ) return "errortype";
	  if( $image_width!=66 || $image_height!=100 ) return "errorsize";
	}
	elseif( $format=="button" )
	{
	  if( $image_type!=1 && $image_type!=2 ) return "errortype";
	  if( $image_width!=88 || $image_height!=31 ) return "errorsize";
	}
	
	return "success";
  }

  // fetch an image from the database and put it
  // on the correct directory with the correct filename
  function updateimage( $type, $login, $format  )
  {
    // check the directories exist or create them
    $res_img = mysql_query( "SELECT * FROM al_img WHERE type='$type' AND login='$login' AND format='$format' LIMIT 1" );
    
	if( mysql_num_rows($res_img) )
	{
	  $img = mysql_fetch_array($res_img);

      // check the directory exists
	  if( !is_dir( "images/{$img['type']}/{$img['format']}" ) )
	  {
	    $oldumask = umask(0);
		@mkdir( "images", 0777 );
	    @mkdir( "images/{$img['type']}", 0777 );
	    @mkdir( "images/{$img['type']}/{$img['format']}", 0777 );
	    umask($oldumask);
	  }
  
      $fp = fopen( "images/{$img['type']}/{$img['format']}/{$img['login']}.{$img['extension']}", "wb" );

      if( $fp )
      {
        fwrite( $fp, $img['rawdata'] );
        fclose( $fp );
		chmod ( "images/{$img['type']}/{$img['format']}/{$img['login']}.{$img['extension']}", 0777 );
      }
	  else
	  {
	    return "errorwrite";
	  }
    }
	
	return "success";
  }
  
  // load all the configs in an array
  function loadconf()
  {
    $res_conf = mysql_query( "SELECT * FROM al_conf" );
	
	if( !mysql_num_rows($res_conf) ) return false;
	
	while( $confarray = mysql_fetch_array($res_conf) )
	{
	  $name = $confarray[name];
	  $value = $confarray[value];
	  
	  // convert all numeric strings to integers
	  if( is_numeric($value) ) $value = intval( $value );
	  	  
	  $conf[$confarray[name]] = $value;
	}
	
	return $conf;
  }
  
?>