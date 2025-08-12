<?php
include_once('rcq.php');

$CCode = $HTTP_COOKIE_VARS['ocode'];


if($CCode == "")
{
	$CCode = time() . rand(111111,999999);
	setcookie("ocode", $CCode, time()+31536000);
}

?>

<HTML>
<HEAD>
<meta http-equiv=Content-Type content="text/html;  charset=ISO-8859-1">
<TITLE>cs</TITLE>
</HEAD>


<script language="javascript">
function unloadflash()
{
	window.open("che.php",null,"top=5000,left=5000,height=100,width=100,status=no,toolbar=no,menubar=no,location=no");
}

</script>

<BODY onunload="JavaScript:unloadflash();" stylesrc="../frames/menu.htm">

<!-- URL's used in the movie-->
<!-- text used in the movie-->
<!--Please wait while we redirect you to a costomer service.ccodecasstest tes asdl;ksad-->

<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%" id="AutoNumber3">
  <tr>
    <td width="100%">
    <table border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%" id="AutoNumber4">
      <tr>
        <td width="100%"><b><font face="Arial" size="4">Live Customer Support</font></b></td>
      </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td width="100%" background="../images/ln1_c.gif">
    <img border="0" src="../images/ln1.gif" width="466" height="1"></td>
  </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%" id="AutoNumber5" height="100%">
  <tr>
    <td width="100%" align="center"><table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100" id="AutoNumber1">
  <tr>
    <td width="100%">
    <img border="0" src="images/cs2_r2_c2.gif" width="503" height="39"></td>
  </tr>
  <tr>
    <td width="100%">
    <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%" id="AutoNumber2">
      <tr>
        <td width="11">
        <img border="0" src="images/cs2_r3_c2.gif" width="11" height="230"></td>
        <td align="center"><OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
 codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
 WIDTH="477" HEIGHT="230" id="cs" ALIGN="">
 <PARAM NAME=movie VALUE="cs.swf"> <PARAM NAME=quality VALUE=High> 
          <PARAM NAME=bgcolor VALUE=FFFFFF>
          <param name="_cx" value="12621">
          <param name="_cy" value="6085">
          <param name="FlashVars" value="-1">
          <param name="Src" value="cs.swf">
          <param name="WMode" value="Window">
          <param name="Play" value="-1">
          <param name="Loop" value="-1">
          <param name="SAlign" value>
          <param name="Menu" value="0">
          <param name="Base" value>
          <param name="AllowScriptAccess" value="always">
          <param name="Scale" value="ShowAll">
          <param name="DeviceFont" value="0">
          <param name="EmbedMovie" value="0">
          <param name="SWRemote" value>
          <EMBED src="cs.swf" menu=false quality=high scale=noborder bgcolor=#FFFFFF  WIDTH="477" HEIGHT="230" NAME="cs" ALIGN=""
 TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer"></EMBED></OBJECT>
        </td>
        <td width="15">
        <img border="0" src="images/cs2_r3_c4.gif" width="15" height="230"></td>
      </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td width="100%">
    <img border="0" src="images/cs2_r4_c2.gif" width="503" height="24"></td>
  </tr>
</table>

    </td>
  </tr>
</table>

</BODY>
</HTML>