<html>
<head>
<title>System Managers Help Desk Login</title>
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

<body bgcolor="#FFFFFF" text="#000000"  link="#0000FF" alink="#FF0000" vlink="#0000FF">
<table width="99%" border="0">
  <tr> 
    <td height="12" bgcolor="#000000" width="16%" valign="top" align="center"> 
      <div align="center"> <a href="../index.php"></a><br>
      </div>
      <p align="center">&nbsp;</p>
      <p align="center">&nbsp;</p>
      <p align="center"><a href="../Personnel/rootdocs/offcadd.html"><br>
        </a> </p>
      <p align="center">&nbsp;</p>
      <p>&nbsp;</p>
    </td>
    <td height="127" width="84%" valign="top"> 
      <table width="99%" border="0" cellpadding="0">
        <tr> 
          <td bordercolor="#FFFFFF" bgcolor="#FFFFFF" height="2" valign="top"> 
            <p align="left"><img src="images/support-staff-login.jpg" alt="Customer Support Help Desk Managment" width="713" height="98"><br>
              Support Professional Help Desk Log-in</p></td>
        </tr>
        <tr> 
          <td bordercolor="#FFFFFF" bgcolor="#FFFFFF" height="2" valign="top">
		  <form method="post" action="007.php">
              <p><font color="#FF0000"><em><strong>Information</strong></em>: 
                This page is for authorized Help Desk Administrators only. All 
                use is logged and maybe subject to auditing!</font></p>
              <table width="75%" border="0">
                <tr>
                  <td>User Name:</td>
                  <td><select name="userName" >
				  <option value="">Select User</option>
				  <?
				  include("config.php");
	   				$cnx = mysql_connect($server,$database,$databasePassword); mysql_select_db($databaseName)		//This statement is required to select the database from the mysql server
       	           or die("Invalid : " . mysql_error());
		  
				  $sql="SELECT *  FROM ".$databasePrefix."accounts where securityLevel between 1 and 2";
				  $res=mysql_query($sql) or die(mysql_error());
				  if(mysql_num_rows($res))
				  {
				     while($row=mysql_fetch_object($res))
				     {
				  	     $uname=$row->User;
				  		 echo "<option value=$uname>$uname</option>";
				     }
				  }
				  ?>
				  </select>
				  </td>
                </tr>
                <tr>
                  <td>Password:</td>
                  <td><input name="password" type="password" id="password"></td>
                </tr>
              </table>
              <p> 
                <input type="submit" name="Submit" value="Enter The Help Desk" class="button">
              </p>
              </form>
            <p align="center">&nbsp;</p>
            <p align="center">&nbsp;</p>
            <p align="center"><font size="2" face="Times New Roman, Times, serif">CopyRight 
              2005 Help Desk Reloaded<br>
              <a href="http://www.helpdeskreloaded.com">Today's Help Desk Software 
              for Tomorrows Problem.</a></font></p>
            <p>&nbsp;</p>
            <p>&nbsp;</p></td>
        </tr>
      </table>
      
      </td>
  </tr>
</table>
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

<SCRIPT LANGUAGE="JavaScript">
<!--
startclock()
//-->
</SCRIPT>
</body>
</html>
