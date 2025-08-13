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

  // return a name which can be compatible for a directory name
  // lower case, no special characters and limited to 30 chars
  function makedirname( $string )
  {
    $dirname = strtolower( $string );
	$dirname = preg_replace( "%[^[:alnum:]]%","_", $string );
	$dirname = substr( $dirname, 0, 30 );
	
	return $dirname;
  }

  function canaccessform()
  {
    global $CONF, $USER, $HTTP_SESSION_VARS;
	
	if( $CONF->getval("formprotect")=="user" )
	{
	  if( isset($HTTP_SESSION_VARS['userpass']) && isset($HTTP_SESSION_VARS['userid']) )
	  {
        if( $USER->getval("password", $HTTP_SESSION_VARS['userid']) == $HTTP_SESSION_VARS['userpass'] )
		{
		  return true;
		}
	  }
	}
	elseif( $CONF->getval("formprotect")=="pass" )
	{
	  if( isset($HTTP_SESSION_VARS['formpass']) )
	  {
	    if( $HTTP_SESSION_VARS['formpass']==$CONF->getval("formpass") )
	    {
	      return true;
	    }
	  }
	}
	else
	{
	  return true;
	}
	
	return false;
  }

  function adduploadinfo( $uploadid, $name, $value )
  {
    global $UPLOADINFO;
	
	$infoid = $UPLOADINFO->addrow();
	$UPLOADINFO->setval( $uploadid, "upload", $infoid );
	$UPLOADINFO->setval( $name, "name", $infoid );
	$UPLOADINFO->setval( $value, "value", $infoid );
  }

  function getipaddress()
  {
    if( getenv('HTTP_X_FORWARDED_FOR') )
    {
      $iparray = explode( ",", getenv('HTTP_X_FORWARDED_FOR') );
      return $iparray[0];
    }
    else
    {
      return getenv('REMOTE_ADDR');
    }
  }
  
  function sendemail( $to_email, $from_email, $from_name, $subject, $body, $header="" )
  {
    global $CONF;
	
	if( $CONF->getval("email_method")=="smtp" )
	{
	  $SMTP = new smtp( $CONF->getval("smtp_host"), $CONF->getval("smtp_port") );
	  
	  if( !$SMTP )
	  {
	    echo( "Unable to connect to SMTP server (".$CONF->getval("smtp_host").":".$CONF->getval("smtp_port").")" );
		exit;
	  }
	  
	  $result = $SMTP->mail( $to_email, $from_email, $from_name, $subject, $body, $header ); 

	  if( !$result )
	  {
	    echo( "Unable to send email through SMTP ({$SMTP->result_txt})<br>Please check your email settings on the control panel" );
		exit;
	  }
	  
	  $SMTP->close();
	   
	  $SMTP = null; 
	}
	else
	{
	  $buffer = "From: $from_name <$from_email>\r\n";
	  $buffer .= "Reply-To: $from_email\r\n";
	
	  $result = @mail( $to_email, $subject, $body, ($buffer.$header) );
	  
	  if( !$result )
	  {
	    echo( "Unable to send email through mail()<br>Please check your email settings on the control panel" );
		exit;
	  }
	}
  }
  
  function showdirlist( $path, $basedir="", $level=0 )
  {
    $handle = opendir( $path . $basedir );
	
	while( false !== ($file = readdir($handle)) )
    { 
	  // check the file is a valid directory
      if( is_dir($path.$basedir.$file) && $file!="." && $file!=".." )
	  {
	    $plus = "";
		for( $i=0; $i<$level; $i++ ) $plus .= "--";
		if( $level>0 ) $plus .= " ";
	  
	    echo( "<option value='{$basedir}$file/'>" );
		echo( "$plus{$file}</option>" );
		
		showdirlist( $path, $basedir.$file."/", ($level+1) );
	  }
    }
	
	closedir( $handle );
	
	return $numdirs;
  }
  
  function getnumdirs( $basedir )
  {
    $handle = opendir( $path . $basedir );
	
	while( false !== ($file = readdir($handle)) )
    { 
	  // check the file is a valid directory
      if( is_dir($basedir.$file) && $file!="." && $file!=".." )
	  {
	    $numdirs++;
	  }
    }
	
	closedir( $handle );
	
	return $numdirs;
  }
  
  function checklogged()
  {
    global $HTTP_SESSION_VARS, $HTTP_COOKIE_VARS, $CONF, $SID;
	
	if( $CONF->getval("adminpass")!="" )
	{
	  // try to get session data
	  if( $CONF->getval("adminpass")!=$HTTP_SESSION_VARS['adminpass'] )
	  {
	    // if failed, try to get cookie data
	    if( $CONF->getval("adminpass")!=$HTTP_COOKIE_VARS['adminpass'] )
		{
	      header( "Location: sign.php?$SID" );
	    }
	  }
	}
  }
  

  function wordwrapnew( $string, $cols = 80, $prefix = "", $splitwith = "\n" )
  {
	$t_lines = split( "\n", $string);
    $outlines = "";

	while(list(, $thisline) = each($t_lines))
	{
	  if(strlen($thisline) > $cols)
	  {
	    $newline = "";
	    $t_l_lines = split(" ", $thisline);

	    while(list(, $thisword) = each($t_l_lines))
	    {
		  while((strlen($thisword) + strlen($prefix)) > $cols)
		  {
		    $cur_pos = 0;
		    $outlines .= $prefix;

		    for($num=0; $num < $cols-1; $num++)
		    {
			  $outlines .= $thisword[$num];
			  $cur_pos++;
		    }

		    $outlines .= $splitwith;
		    $thisword = substr($thisword, $cur_pos, (strlen($thisword)-$cur_pos));
		  }

		  if((strlen($newline) + strlen($thisword)) > $cols)
		  {
		    $outlines .= $prefix.$newline.$splitwith;
		    $newline = $thisword." ";
		  }
		  else
		  {
	        $newline .= $thisword." ";
		  }
	    }

	    $outlines .= $prefix.$newline.$splitwith;
      }
	  else
	  {
	    $outlines .= $prefix.$thisline.$splitwith;
	  }
    }
  
    return trim( $outlines );
  }


  function stripslashes_array($arr = array())
  {
    $rs = array();
    
	if( !empty($arr) )
	{
	  while (list($key,$val) = each($arr))
	  {
        if (is_array($val))
		{
          $rs[$key] = stripslashes_array($val);
        }
		else
		{
          $rs[$key] = stripslashes($val);
        }
      }
	}
	
    return $rs;
  }
  
  function rand_string( $length )
  { 
    $allchar = "abcdefghijklnmopqrstuvwxyzABCDEFGHIJKLNMOPQRSTUVWXYZ"; $str = ""; 
    mt_srand ((double) microtime() * 1000000); 
    for ( $i=0; $i<$length; $i++ ) $str .= substr( $allchar, mt_rand(0,52), 1 ); 
    return $str; 
  }
  
  function confirm( $msg, $url="" )
  {
    global $SID;
  
    $msg = urlencode( $msg );
  
    if( $url!="" )
	{
      if( !strstr( $url, "?" ) )
	    $url .= "?$SID";
	  else
	    $url .= "&$SID";
		
	  $url = urlencode( $url );
	  
	  header( "Location: confirm.php?msg=$msg&url=$url&$SID" );
	}
	else
	{
	  header( "Location: confirm.php?msg=$msg&$SID" );
	}
    
	exit;
  }
  
  function showsession()
  {
    echo( "<input type='hidden' name='" . session_name() . "' value='" . session_id() . "'>" );
  }

  function isemail( $email )
  {
    return ereg( '^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.  '@'. '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.' . '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email );
  }
  
  function showheader( $section )
  {
    global $SID, $CONF;
  
	include( "cp_header.php" );
  }
  
  function showfooter( $section )
  {
    global $SID;
  
    include( "cp_footer.php" );
  }
  
  function showspace( $height=20 )
  {
    echo( "<table width='100%' border='0' cellspacing='0' cellpadding='0' height='" . $height . "'>" );
    echo( "<tr><td><img src='images/dot.gif' width='1' height='1'></td></tr>" );
    echo( "</table>" );
  }
  
  function showmessage( $message="" )
  {
    if( $message!="" )
	{
	  echo( "<table width='100%' border='0' cellspacing='0' cellpadding='3' class='formtbl'>" );
      echo( "<tr class='altfirst'><td>$message</td></tr>" );
	  echo( "</table>" );

	  showspace( 15 );
	}
  }
  
  function getaltclass()
  {
    static $row = 0;
  
    $row++;
  
	if( $row % 2 ) 
	  return "class='altfirst'";
	else
	  return "class='altsecond'";
  }
  
  function showmenu( $selected, $name, $url, $description="" )
  {
    if( $selected )
	{
      echo( "<td class='menusel' this.style.cursor='hand';\" onClick=\"window.location.href='$url'\" align=\"center\">" );
      echo( "<a href='$url' class='menu' title='$description'>$name</a>" );
      echo( "</td>" );
	}
	else // not selected
	{
      echo( "<td class='menunosel' onMouseOver=\"this.className='menuover'; this.style.cursor='hand';\" onMouseOut=\"this.className='menunosel'\" onClick=\"window.location.href='$url'\" align=\"center\">" );
      echo( "<a href='$url' class='menu' title='$description'>$name</a>" );
      echo( "</td>" );
	}
  }
  
  function gettypename( $type )
  {
    switch( $type )
	{
	  case "file": $typename = "File Field"; break;
	  case "text": $typename = "Simple Text"; break;
	  case "password": $typename = "Password"; break;
	  case "textarea": $typename = "Text Area"; break;
	  case "dropbox": $typename = "Drop Box"; break;
	  case "checkbox": $typename = "Check Box"; break;
	  case "radio": $typename = "Radio Buttons"; break;
	}
	
	return $typename;
  }
  
  function getyesno( $bool )
  {
    return $bool ? "Yes" : "No";
  }
  
  function checkversion( $version )
  {
    $testver = intval( str_replace( ".", "", $version ) );
    $curver = intval( str_replace( ".", "", phpversion() ) );

    return ( $testver <= $curver );
  }
  
  function userdate( $date )
  {
    global $CONF;
  
    $shiftval = ( $CONF->getval("timezone") * 3600 );
  
    return gmdate( "m/d/y @ H:i", $date + $shiftval );
  }
  
  function clearfiles()
  {
    global $CONF, $FILE, $UPLOAD, $filesdir;
  
    if( $CONF->getval("autodel_files") )
    {
      $files = $FILE->get();
  
      foreach( $files AS $file )
      {
	    $filename = $filesdir . $UPLOAD->getval("subdir",$file['upload']) . $file['name'];
	
	    if( !@file_exists($filename) )
	    {
	      $FILE->deleterow( $file['id'] );
	    }
      }
	
	  $FILE->savedata();
    }
  }
  
  function clearinfos()
  {
    global $CONF, $UPLOAD, $FILE;
  
    if( $CONF->getval("autodel_info") )
    {
      $uploads = $UPLOAD->get();
  
      foreach( $uploads AS $upload )
      {
        // no file under this upload
	    if( $FILE->getnumrows($upload['id'],"upload") == 0 )
	    {
	      $UPLOAD->deleterow( $upload['id'] );
	    }
	  }
    
	  $UPLOAD->savedata();
    }
  }
  
  function cleardirs()
  {
    global $CONF, $safemode, $filesdir;
  
    if( $CONF->getval("autodel_dir") && !$safemode )
    {
      if( $handle = @opendir($filesdir) )
	  {
        while( ($file = @readdir($handle)) !== false )
	    {
          if( @is_dir( $filesdir . $file ) && $file!="." && $file!=".." )
		  {
		    // remove directory (works only if empty)
            @rmdir( $filesdir . $file );
          }
        }
	   
        closedir( $handle );
      }
    }
  }
  
?>