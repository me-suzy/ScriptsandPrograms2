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
 
?>