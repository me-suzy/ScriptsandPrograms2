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
  
  switch( $type )
  {
    case "hitsin": $hittable = "al_hitin";  $typename = "hits"; break;
	case "clicks": $hittable = "al_hitclk"; $typename = "referred clicks"; break;
  }
  
  $res_hit = mysql_query( "SELECT * FROM $hittable WHERE ref='$reflogin' ORDER BY sent" );
  if( mysql_num_rows($res_hit)==0 ) fatalerr( "No $typename found for this referrer in the past 24 hours" );

  $res_ref = mysql_query( "SELECT * FROM al_ref WHERE login='$reflogin' LIMIT 1" );
  $ref = mysql_fetch_array( $res_ref );
  
  $info = "Showing IPs for the $typename sent by {$ref[name]} in the past 24 hours.";
  
?>

<html>
<head>
<link rel="stylesheet" href="main.css">
</head>
<body>
<? showmessage(); ?>
<table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td>
      <table cellpadding='4' cellspacing='1' border='0' width='100%'>
        <tr>
          <td><font color="#FFFFFF" size="1">HIT TIME</font></td>
		  <? if($type=="clicks") echo( "<td align='center'><font color='#FFFFFF' size='1'>TO REFERRER</font></td>" ); ?>
          <td align="center"><font color="#FFFFFF" size="1">IP ADDRESS</font></td>
		  <? if($CONF[find_host]) echo( "<td align='center'><font color='#FFFFFF' size='1'>HOST NAME</font></td>" ); ?>
        </tr>
<?
  while( $hit = mysql_fetch_array($res_hit) ):

    if( $type=="clicks" )
	{
	  $res_ref = mysql_query( "SELECT * FROM al_ref WHERE login='{$hit[toref]}' LIMIT 1" );
      $ref = mysql_fetch_array( $res_ref );
	}
?>
        <tr bgcolor="#F5F5F5">
          <td><?=$hit[sent]?></td>
          <? if($type=="clicks") echo( "<td align='center'>{$ref[name]}</td>" ); ?>
          <td align="center"><?=$hit[ip]?></td>
          <? if($CONF[find_host]) echo( "<td align='center'>{$hit[host]}</td>" ); ?>
        </tr>
		
<? endwhile; ?>

      </table>
    </td>
  </tr>
</table>
</body>
</html>