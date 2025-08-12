<?
include("checksession.php"); 
?>
<html>
<head>
<title>Configure your Help Desk Accounts</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="Designed by Chad Edwards" content="QuickIntranet.com">
<script language="JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
// -->
</script>
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000" link="#0000FF" alink="#FF0000" vlink="#0000FF">
<table width="99%" border="0">
  <tr> 
    <td height="127" align="center" valign="top" bgcolor="#FFFFFF"> 
      <div align="center"> <a href="index.php"></a></div>
      <table width="99%" border="0" cellpadding="0">
        <tr> 
          <td bordercolor="#FFFFFF" bgcolor="#FFFFFF" height="2" valign="top"> 
            <img src="images/help-desk-account-managment.jpg" alt="Help Desk Account Managment" width="594" height="176" border="0" usemap="#Map2"><br>
            <map name="Map"><area shape="rect" coords="543,151,611,195" href="DataAccess.php">
              <area shape="rect" coords="480,146,542,198" href="search.php">
               
              <area shape="rect" coords="280,146,362,194" href="actmgt.php">
              <area shape="rect" coords="189,146,277,196" href="ocm-first.htm">
              <area shape="rect" coords="127,148,182,198" href="DataAccessSearch.php">
              <area shape="rect" coords="76,147,122,196" href="helpDeskAccessAllCalls.php">
              <area shape="rect" coords="163,2,248,14" href="DataAccess.php">
              <area shape="rect" coords="2,148,74,200" href="reportproblem.htm">
            </map> </td>
        </tr>
        <tr> 
          <td bordercolor="#FFFFFF" bgcolor="#FFFFFF" height="2" valign="top"><form name="form1" method="post" action="actupdate.php">
              <p><font color="#FF0000"><em><strong>Warming</strong></em>: This 
                page is for authorized Help Desk Administrators only. All use 
                is logged and maybe subject to auditing!</font><br>
                <font color="#FF0000">Notice</font>: You must complete all fields 
                in order to update your network name. Simply re-enter your current 
                password as your new password.</p>
              <table width="75%" border="0">
                <tr> 
                  <td>User Name:</td>
                  <td><input name="userName" type="text" id="userName"></td>
                </tr>
                <tr> 
                  <td height="25"><em><font color="#FF0000">Current</font></em> 
                    Password:</td>
                  <td><p> 
                      <input name="password" type="password" id="password3">
                    </p></td>
                </tr>
                <tr> 
                  <td><em><font color="#FF0000">New</font></em> Password:</td>
                  <td> <p> 
                      <input name="password2" type="password" id="password2">
                      <br>
                    </p></td>
                </tr>
                <tr>
                	<td>Email From Name</td>
                	<td><input name="emailName" typ"text" id="emailName"/>
                <tr> 
                  <td>Computer Network Name (Example: CAL-RD-1010)</td>
                  <td><input name="compName" type="text" id="compName"></td>
                </tr>
              </table>
              <p> 
                <input type="submit" name="Submit" value="Submit" class="button">
              </p>
            </form>
            <p align="center"><a href="http://www.helpdeskreloaded.com"><img src="http://www.helpdeskreloaded.com/reload/help-desk-copyright.jpg" alt="http://www.helpdeskreloaded.com Help Desk Software By  HelpDeskReloaded &quot;Help Desk Reloaded&quot;" border="0"></a></p>
            <p>&nbsp;</p>
            <p>&nbsp;</p></td>
        </tr>
      </table>

      <table width="100%" border="0" height="104">
        <tr> 
          <td height="60" valign="top" bordercolor="#CCCCCC" bgcolor="#FFFFFF">&nbsp; 
            
            </td>
        </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
<p> 
  <script Language="JavaScript">
<!-- hide// Navigation - Stop
var timerID = null;
var timerRunning = false;
function stopclock (){
        if(timerRunning)
                clearTimeout(timerID);
        timerRunning = false;
}
function showtime () {
        var now = new Date();
        var hours = now.getHours();
        var minutes = now.getMinutes();
        var seconds = now.getSeconds()
        var timeValue = "" + ((hours >12) ? hours -12 :hours)
        timeValue += ((minutes < 10) ? ":0" : ":") + minutes
        timeValue += ((seconds < 10) ? ":0" : ":") + seconds
        timeValue += (hours >= 12) ? " P.M." : " A.M."
        //document.clock.face.value = timeValue;
        // you could replace the above with this
        // and have a clock on the status bar:
        window.status = timeValue;
        timerID = setTimeout("showtime()",1000);
        timerRunning = true;
}
function startclock () {
        // Make sure the clock is stopped
        stopclock();
        showtime();
}
// un hide --->
</script>
</p>
<p>&nbsp;</p>
<p align="center"><font size="5" face="Arial, Helvetica, sans-serif">



<SCRIPT LANGUAGE="JavaScript">
<!--
startclock()
//-->
</SCRIPT>


</font></p>
<p>&nbsp;</p>
<p>&nbsp;</p>

<map name="Map2">
  <area shape="rect" coords="4,130,70,176" href="reportproblem.php">
  <area shape="rect" coords="80,128,159,174" href="helpDeskAccessAllCalls.php">
  <area shape="rect" coords="173,129,274,173" href="DataAccessSearch.php">
  <area shape="rect" coords="292,126,375,177" href="ocm-first.php">
  <area shape="rect" coords="384,128,447,174" href="search.php">
  <area shape="rect" coords="454,128,544,169" href="DataAccess.php">
</map>
</body>
</html>
