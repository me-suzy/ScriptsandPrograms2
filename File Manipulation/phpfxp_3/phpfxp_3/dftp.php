<?
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	PHP FXP 3.0
	by Harald Meyer (webmaster@harrym.nu) 
	Feel free to use it, but don't delete these lines. 
	PHP FXP is Freeware but all links to the PHP FXP homepage must not be deleted! 
	If you want to use PHP FXP COMMERCIAL please contact me.
	Please send me modified versions! 
	If you use it on your page, a link back to its homepage (to PHP FXP's homepage) 
	would be highly appreciated.
	Homepage: http://fxp.harrym.nu
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

 /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	File: dftp.php
	Description: -
	Last update: 25-09-2002
	Created by: Harald Meyer
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

include ("config.inc.php");
include ("functions.inc.php");

if ($action=="save") {
	phpfxp_savetotxt($servers,$datapath."source.txt");   
}
?>

<html>
<head>
<title>PHP FXP 3 FTP Client</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript">
<!--
function MM_callJS(jsStr) { //v2.0
  return eval(jsStr)
}
//-->
</script>
</head>

<body bgcolor="#003366" text="#000000">
<form name="form1" method="post" action="<? echo("$PHP_SELF?action=save");?>">
  <table width="640" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td>
        <textarea name="servers" wrap="OFF" width="100%" height="100%" cols="70" rows="8"><? //load servers
				$turl=$datapath."source.txt";
				ob_start(); 
				ob_end_clean();
				readfile($turl);
				$img = ob_get_contents(); 
				ob_end_clean(); 
				echo $img;
				unset($img);			
				?></textarea>
      </td>
      <td valign="top"> 
        <input type="submit" name="Submit" value="Save">
        <br>
        <input type="button" name="Submit2" value="Clear" onClick="MM_callJS('clearserver()')">
		<script language="JavaScript">
		<!--
		 function clearserver()
		 {
		    document.form1.servers.value="";
		 } // end addserver
		//-->
	 </script>
      </td>
    </tr>
  </table>
</form>
</body>
</html>
<?
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	END
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
?>