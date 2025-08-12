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
	File: index.php
	Description: -
	Last update: 27-09-2002
	Created by: Harald Meyer
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
require("config.inc.php");
require("functions.inc.php");

?>
<html>
<head>
<title>PHP FXP 3.0 BETA 1</title>
</head>
<body>
<script language="JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
//-->
</script>
<?
if ($action==NULL) {
?>
<p><font size="4"><b><font face="Arial, Helvetica, sans-serif" color="#003366">Transfer 
  files</font></b></font></p>
<form name="form1" method="post" action="<? echo("$PHP_SELF?action=transfer&next=0");?>">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr bgcolor="#99CC33"> 
      <td width="150"><font size="2" face="Arial, Helvetica, sans-serif"><b>Destination 
        server:</b></font></td>
      <td> <font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="tdestserver" size="40">
        <input type="checkbox" name="tupasive" value="true" checked>
        pasive mode 
        <select name="tutransfermode">
          <option value="ftp">FTP</option>
          <option value="file">File</option>
        </select>
        transfer mode </font></td>
    </tr>
    <tr> 
      <td colspan="2" bgcolor="#CCCC66">&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="2" bgcolor="#CCCC66"> 
        <input type="button" name="Submit2" value="Select source files" onClick="MM_openBrWindow('ftp.php','ftp','scrollbars=yes,resizable=yes,width=640,height=500')">
      </td>
    </tr>
    <tr bgcolor="#996600">
      <td width="150">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#996600"> 
      <td width="150">&nbsp;</td>
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="checkbox" name="tdelcached" value="true" checked>
        delete cached files</font></td>
    </tr>
    <tr bgcolor="#996600"> 
      <td colspan="2"> 
        <div align="center"> 
          <input type="submit" name="Submit" value="Start transfer">
        </div>
      </td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
<?
}//NULL


if ($action=="transfer") {
	/*** ONLY TEST*/
	$phpfxp_fcontent=file($datapath."source.txt"); 
	if (($next>count($phpfxp_fcontent))OR($phpfxp_fcontent[$next]==NULL)) {
	    echo("<b>All files transfered!</b>");
	}
	else{
		$msg="";
		parse_str($phpfxp_fcontent[$next]);
		if ($next==0) {
		    $tdestserver=$tdestserver."/a.zip";
		}
		$desturl=trim(urldecode($tdestserver));
		$upasive=trim($tupasive);
		$utransfertype=trim($tutransfermode);
		$delcached=trim($tdelcached);
		$sourceurl=urldecode($go);
		$transfertype=trim($transfermode);
		$mode=$mode;
		$buffer=trim($buffer);
		$pasive=trim($pasive);

		echo($desturl);
		echo($utransfertype);
		echo($sourceurl);
		//transfer
		transfer_data($sourceurl,$desturl,$transfertype,$pasive,$mode,$buffer,$utransfertype,$upasive);
		$next++;
		echo($msg);
		//reload page with countdown
		$desturl=urlencode($desturl);
		?>
		<script language="JavaScript">
		<!--
		setTimeout("MM_goToURL('parent','<? echo("$PHP_SELF?action=transfer&next=$next&tdestserver=$desturl&tupasive=$upasive&tutransfermode=$utransfertype&tdelcached=$delcached");?>');return document.MM_returnValue", 1000);
		//-->
		</script>
		
		<?
	}//next>count...
}//transfer

?>
</body>
</html>
<?

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	END
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
?>