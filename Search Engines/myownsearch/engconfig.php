<?
include "./config.php";
include "./mysql.php";
if ($pass!=$adminpass){
	echo "<center><form name='form1' method='get'>Enter Administrator's Password: <input type='text' name='pass'><input type=submit value='Submit'><br></form></center>";
	exit;
}
If ($update){
	$sql = "delete from $enginestable";
	$result = mysql_query($sql);
	$sql = "insert into $enginestable values('$orderdmoz', 'dmoz', '$cachedmoz')";
	$result = mysql_query($sql);
	$sql = "insert into $enginestable values('$ordergoogle', 'google', '$cachegoogle')";
	$result = mysql_query($sql);
	$sql = "insert into $enginestable values('$orderaltavista', 'altavista', '$cachealtavista')";
	$result = mysql_query($sql);
	$sql = "insert into $enginestable values('$ordersearchfeed', 'searchfeed', '$cachesearchfeed')";
	$result = mysql_query($sql);
	$sql = "insert into $enginestable values('$ordermsn', 'msn', '$cachemsn')";
	$result = mysql_query($sql);
	$sql = "insert into $enginestable values('$orderaskjeeves', 'askjeeves', '$cacheaskjeeves')";
	$result = mysql_query($sql);
	$sql = "insert into $enginestable values('$orderrevenuepilot', 'revenuepilot', '$cacherevenuepilot')";
	$result = mysql_query($sql);
}

$sql = "select * from $enginestable";
$result = mysql_query($sql) or die("Failed: $sql");
$cnt = mysql_num_rows($result);
for($x=0;$x<$cnt;$x++){
	$resrow = mysql_fetch_row($result);
	$srt = $resrow[0];
	$engine = $resrow[1];
	$cchres = $resrow[2];
	if ($engine=="dmoz"){
		if ($srt=="") $srt = "0";
		$orderdmoz = $srt;
		if ($cchres=="1") $cachedmoz = "checked";
	}
	if ($engine=="google"){
		if ($srt=="") $srt = "0";
		$ordergoogle = $srt;
		if ($cchres=="1") $cachegoogle = "checked";
	}
	if ($engine=="altavista"){
		if ($srt=="") $srt = "0";
		$orderaltavista = $srt;
		if ($cchres=="1") $cachealtavista = "checked";
	}
	if ($engine=="searchfeed"){
		if ($srt=="") $srt = "0";
		$ordersearchfeed = $srt;
		if ($cchres=="1") $cachesearchfeed = "checked";
	}
	if ($engine=="msn"){
		if ($srt=="") $srt = "0";
		$ordermsn = $srt;
		if ($cchres=="1") $cachemsn = "checked";
	}
	if ($engine=="askjeeves"){
		if ($srt=="") $srt = "0";
		$orderaskjeeves = $srt;
		if ($cchres=="1") $cacheaskjeeves = "checked";
	}
	if ($engine=="revenuepilot"){
		if ($srt=="") $srt = "0";
		$orderrevenuepilot = $srt;
		if ($cchres=="1") $cacherevenuepilot = "checked";
	}
	if ($engine=="webger"){
		if ($srt=="") $srt = "0";
		$orderwebger = $srt;
		if ($cchres=="1") $cachewebger = "checked";
	}
}

?>
<html>
<head>
<title>Configure Meta Engines</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<form name="form1" method="post" action="engconfig.php">
<center>[<a href='admin.php?pass=<? print $pass; ?>'><? print $engtitle; ?> Admin Home</a>]<br><br></center>
  <table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr bgcolor="#000000"> 
      <td><b><font size="-1" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF">Engine 
        Name:</font></b></td>
      <td><b><font size="-1" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF">Usage 
        Order*</font></b></td>
      <td><b><font size="-1" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF">Save 
        Meta Results?</font></b></td>
    </tr>
    <tr bgcolor="#DDDDDD"> 
      <td><font size="-1" face="Verdana, Arial, Helvetica, sans-serif">DMOZ</font></td>
      <td><font size="-1" face="Verdana, Arial, Helvetica, sans-serif"> 
        <input type="text" name="orderdmoz" maxlength="2" size="5" value="<? print $orderdmoz; ?>">
        </font></td>
      <td><font size="-1" face="Verdana, Arial, Helvetica, sans-serif"> 
        <input type="checkbox" name="cachedmoz" value="1" <? print $cachedmoz; ?>>
        Yes!</font></td>
    </tr>
    <tr bgcolor="#EEEEEE"> 
      <td><font size="-1" face="Verdana, Arial, Helvetica, sans-serif">Google</font></td>
      <td><font size="-1" face="Verdana, Arial, Helvetica, sans-serif"> 
        <input type="text" name="ordergoogle" maxlength="2" size="5" value="<? print $ordergoogle; ?>">
        </font></td>
      <td bgcolor="#EEEEEE"><font size="-1" face="Verdana, Arial, Helvetica, sans-serif"> 
        <input type="checkbox" name="cachegoogle" value="1" <? print $cachegoogle; ?>>
        Yes!</font></td>
    </tr>
    <tr bgcolor="#DDDDDD"> 
      <td><font size="-1" face="Verdana, Arial, Helvetica, sans-serif">Altavista</font></td>
      <td><font size="-1" face="Verdana, Arial, Helvetica, sans-serif"> 
        <input type="text" name="orderaltavista" maxlength="2" size="5" value="<? print $orderaltavista; ?>">
        </font></td>
      <td bgcolor="#DDDDDD"><font size="-1" face="Verdana, Arial, Helvetica, sans-serif"> 
        <input type="checkbox" name="cachealtavista" value="1" <? print $cachealtavista; ?>>
        Yes!</font></td>
    </tr>
    <tr bgcolor="#EEEEEE"> 
      <td><font size="-1" face="Verdana, Arial, Helvetica, sans-serif"><a href="http://www.searchfeed.com/rd/AffiliateInfo.jsp?p=6410" target="_searchfeed">SearchFeed</a>**</font></td>
      <td><font size="-1" face="Verdana, Arial, Helvetica, sans-serif"> 
        <input type="text" name="ordersearchfeed" maxlength="2" size="5" value="<? print $ordersearchfeed; ?>">
        </font></td>
      <td><font size="-1" face="Verdana, Arial, Helvetica, sans-serif"> 
        <!-- <input type="checkbox" name="cachesearchfeed" value="1" <? print $cachesearchfeed; ?>>
        Yes! //-->
        No</font></td>
    </tr>
    <tr bgcolor="#DDDDDD"> 
      <td><font size="-1" face="Verdana, Arial, Helvetica, sans-serif"><a href="http://www.revenuepilot.com/gopilot/home.jsp?id=2751" target="_revenuepilot">RevenuePilot</a>**</font></td>
      <td><font size="-1" face="Verdana, Arial, Helvetica, sans-serif"> 
        <input type="text" name="orderrevenuepilot" maxlength="2" size="5" value="<? print $orderrevenuepilot; ?>">
        </font></td>
      <td><font size="-1" face="Verdana, Arial, Helvetica, sans-serif"> 
        <!-- <input type="checkbox" name="cacherevenuepilot" value="1" <? print $cacherevenuepilot; ?>>
        Yes! //-->
        No</font></td>
    </tr>
    <tr bgcolor="#EEEEEE"> 
      <td><font size="-1" face="Verdana, Arial, Helvetica, sans-serif">MSN</font></td>
      <td><font size="-1" face="Verdana, Arial, Helvetica, sans-serif"> 
        <input type="text" name="ordermsn" maxlength="2" size="5" value="<? print $ordermsn; ?>">
        </font></td>
      <td><font size="-1" face="Verdana, Arial, Helvetica, sans-serif"> 
        <input type="checkbox" name="cachemsn" value="1" <? print $cachemsn; ?>>
        Yes!</font></td>
    </tr>
    <tr bgcolor="#DDDDDD"> 
      <td><font size="-1" face="Verdana, Arial, Helvetica, sans-serif">AskJeeves</font></td>
      <td><font size="-1" face="Verdana, Arial, Helvetica, sans-serif"> 
        <input type="text" name="orderaskjeeves" maxlength="2" size="5" value="<? print $orderaskjeeves; ?>">
        </font></td>
      <td><font size="-1" face="Verdana, Arial, Helvetica, sans-serif"> 
        <input type="checkbox" name="cacheaskjeeves" value="1" <? print $cacheaskjeeves; ?>>
        Yes! </font></td>
    </tr>
    <tr> 
      <td><font size="-1" face="Verdana, Arial, Helvetica, sans-serif"></font></td>
      <td><font size="-1" face="Verdana, Arial, Helvetica, sans-serif"></font></td>
      <td><font size="-1" face="Verdana, Arial, Helvetica, sans-serif"></font></td>
    </tr>
  </table>
  <div align="center">
    <input type="hidden" name="pass" value="<? print $pass; ?>">
    <input name="update" type="hidden" value="1"><input type="submit" value="Save Changes">
  </div>
</form>
<font size="-1" face="Verdana, Arial, Helvetica, sans-serif">* Enter a number 
starting with 1, to specify the order of the meta engines to be used. <b>Enter 
<font color="#FF0000">0</font> to disable the engine!</b><br>
** If you use SearchFeed or RevenuePilot, be sure to set your SearchFeed ID and 
Revenue Pilot preferences in config.php. </font> 
</body>
</html>