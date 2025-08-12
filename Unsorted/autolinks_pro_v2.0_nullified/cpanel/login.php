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
  if( $submitted=="login" )
  {
    setcookie( "cookieadmin", $adminpass, time() + 2592000, "/" );

    if( isset($from) && $from!="" )
    {
      header( "Location: $from" );
    }
    else
    {
      header( "Location: home.php" );
    }
  }
?>

<html>
<head>
<title>Admin Area</title>
<link rel="stylesheet" href="main.css">
</head>

<body>
<table width="485" border="0" cellspacing="0" cellpadding="0" align="center" height="100%">
  <tr valign="top"> 
    <td align="center" valign="middle">
      <p>This area is password-protected</p>
      
      
      <table border="0" cellspacing="0" cellpadding="0">
      <form name="form1" method="post" action="login.php">
	  <input type="hidden" name="submitted" value="login">
	  <input type="hidden" name="from" value="<? echo($from); ?>">
        <tr> 
          <td align="right"> 
              <input type="password" name="adminpass" size="15">
          </td>
          <td width="10"></td>
          <td> 
            <input type="submit" name="Submit" value="  Enter  ">
          </td>
        </tr>
	  </form>
      </table>
      <p>&nbsp;</p>
    </td>
  </tr>
</table>
</body>
</html>
