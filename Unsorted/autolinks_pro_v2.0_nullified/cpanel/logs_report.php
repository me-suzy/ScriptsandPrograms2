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
  
  if( $submitted=="checklogs" )
  {
	header( "Location: logs_show.php?reflogin=$reflogin&type=$type" );
  }
  
  $info = "On this page you can see the log of the past 24 hours. It will show the IP (and the host domain if you choose it) of each hit for a selected referrer, an effective way to check if a site is cheating.";

?>
<html>
<head>
<link rel="stylesheet" href="main.css">
</head>
<body>
<? showmessage(); ?>
<form method="post" action="<? echo($PHP_SELF); ?>">
<input type="hidden" name="submitted" value="checklogs">
  <table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr class='tblhead'>
            <td colspan='2'><font color="#FFFFFF" size="1">GENERATE REPORT</font></td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Referrer(s)</b><br>
              <font size="1">Referrers who sent the hits to the website(s). </font></p>
            </td>
            <td width="35%">
              <select name="reflogin">
                <?
  $res_ref = mysql_query( "SELECT * FROM al_ref" );

  while( $ref = mysql_fetch_array($res_ref) )
  {
    echo( "<option value='{$ref[login]}'>{$ref[name]}</option>" );
  }
				?>
              </select>
            </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%"><b>Log Type</b><br>
              <font size="1">Choose what kind of hits you want to see.</font></td>
            <td width="35%">
              <select name="type">
                <option value="hitsin">Hits In</option>
                <option value="clicks">Referred Clicks</option>
              </select>
              </td>
          </tr>
        </table>
    </td>
  </tr>
</table>
  <table cellpadding='4' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td align="center">
        <input type="submit" value=" Generate Report " name="submit">
      </td>
  </tr>
</table>
</form>
</body>
</html>
