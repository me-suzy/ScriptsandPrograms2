<? include("checksession.php"); 
header('location:kb');

 ?>
<html>
<head>
<title>Help Desk Search</title>
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
</head>
<link href="style.css" rel="stylesheet" type="text/css">
<body bgcolor="#FFFFFF" text="#000000" link="#0000FF" alink="#FF0000" vlink="#0000FF">
<?		
if($contents == 'B')
		include 'dataaccessheader.php';
else
//if its not banner it must be text.		
include 'textnavsystem.php';
?>
<table width="99%" border="0">
  <tr> 
    <td height="127" align="center" valign="top" bgcolor="#FFFFFF"> 
      <div align="center"> <a href="index.php"></a></div>
      <table width="99%" border="0" cellpadding="0">
        <tr> 
          <td bordercolor="#FFFFFF" bgcolor="#FFFFFF" height="2" valign="top"> 
            <table width="137%" border="0">
              <tr bgcolor="#CCCCCC"> 
                <td height="59" valign="top" bgcolor="#FFFFFF">	 <br>
                  <map name="Map">
                    <area shape="rect" coords="543,151,611,195" href="DataAccess.php">
                    <area shape="rect" coords="480,146,542,198" href="./kb/">
                     
                    <area shape="rect" coords="280,146,362,194" href="actmgt.php">
                    <area shape="rect" coords="189,146,277,196" href="ocm-first.htm">
                    <area shape="rect" coords="127,148,182,198" href="DataAccessSearch.php">
                    <area shape="rect" coords="76,147,122,196" href="helpDeskAccessAllCalls.php">
                    <area shape="rect" coords="163,2,248,14" href="DataAccess.php">
                    <area shape="rect" coords="2,148,74,200" href="reportproblem.htm">
                  </map>
                </td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td bordercolor="#FFFFFF" bgcolor="#FFFFFF" height="2" valign="top"><form name="form1" method="post" action="find.php">
              <p>You may search by First or First <strong>AND</strong> Last Name 
                of the end user or Help Desk ID Tag.</p>
              <table width="75%" border="0">
                <tr>
                  <td colspan="2">
                    <input type="radio" name="searchType" value="name" checked="checked" /> Search by Name
                  </td>
                </tr>
                <tr> 
                  <td width="27%" height="25"> Fist Name</td>
                  <td width="70%" height="25"><input name="firstName" type="text" id="search6"></td>
                  <td width="3%" rowspan="9">&nbsp; </td>
                </tr>
                <tr> 
                  <td height="25">Last Name</td>
                  <td width="70%" height="25"><p> 
                      <input name="lastName" type="text" id="search22">
                    </p></td>
                </tr>
                <tr>
                  <td colspan="2">
                    <input type="radio" name="searchType" value="email" /> Searcb by Email
                  </td>
                </tr>
                <tr> 
                  <td height="25">Email Address</td>
                  <td width="70%" height="25"><p> 
                      <input name="email" type="text" id="lastName">
                    </p></td>
                </tr>
                <tr> 
                  <td height="25">Help Desk ID Tag #</td>
                  <td width="70%" height="25"><p> 
                      <input name="idNum" type="text" id="lastName">
                    </p></td>
                </tr>
                <tr> 
                  <td height="25">Problem Category</td>
                  <td height="25"><select name="PCatagory" id="PCatagory">
                      <option value="">Any</option>
                      <?
			  
              $sql="SELECT *  FROM ".$databasePrefix."problem";
				  $res=mysql_query($sql) or die(mysql_error());
				  if(mysql_num_rows($res))
				  {
				  while($row=mysql_fetch_object($res))
				  {
				  $pcat=$row->pcategory;
				  echo "<option value='$pcat'>$pcat</option>";
				  }
				  }
				  ?>
                    </select> </tr>
                <tr>
                  <td height="25">Status</td>
                  <td height="25"><select name="status">
                      <option value="">Any</option>
                      <option value="Open">Open</option>
                      <option value="Closed">Closed</option>
                    </select></td>
                </tr>
                <tr> 
                  <td height="25">IP Address</td>
                  <td height="25"><input name="ip" type="text" id="ip"></td>
                </tr>
                <tr>
                	<td colspan="2">
                		<input type="radio" name="searchType" value="content" />Search Content
                	</td>
                </tr>
                <tr>
                	<td colspan="2" style="padding-left:10px">
                		<input type="radio" name="contentType" value="desc" checked="checked" />Search Descriptions<br/>
                		<input type="radio" name="contentType" value="res" />Search Resolutions<br/>
                		<em>Keywords:</em><input type="text" name="keywords" size="35" maxlength="150" /><br/>
                		<i>(Please Provide a Comma Seperated List of Keywords)</i>
                	</td>
                </tr>
                <tr> 
                  <td height="25" colspan="2"><input type="submit" name="Submit" value="Search it!" class="button"></td>
                </tr>
                <tr> 
                  <td height="25" colspan="2">&nbsp; </td>
                </tr>
                <tr> 
                  <td height="25" colspan="2">&nbsp;</td>
                </tr>
              </table>
              <p>&nbsp;</p>
            </form>
            <p align="center"><a href="http://www.helpdeskreloaded.com"><img src="http://www.helpdeskreloaded.com/reload/help-desk-copyright.jpg" alt="http://www.helpdeskreloaded.com Help Desk Software By  HelpDeskReloaded &quot;Help Desk Reloaded&quot;" border="0"></a></p>
            <p>&nbsp;</p>
            <p>&nbsp;</p></td>
        </tr>
      </table>
      <table width="100%" border="0" height="104">
        <tr> 
          <td height="60" valign="top" bordercolor="#CCCCCC" bgcolor="#FFFFFF"> 
            <div align="left"> 
              <p>&nbsp;</p>
              <b> </b></div>
            <ul>
            </ul>
            <p align="center">&nbsp;</p>
            <p align="center"><b><br>
              </b></p>
            <blockquote> 
              <p><b><br>
                </b></p>
            </blockquote>
            <blockquote> 
              <p>&nbsp;</p>
            </blockquote>
            <p>&nbsp;</p>
            <div align="right"> 
              <p align="center"><br>
                <br>
              </p>
            </div>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <blockquote> 
              <p>&nbsp;</p>
              <p><font face="Times New Roman, Times, serif"><br>
                </font></p>
            </blockquote>
            <p>&nbsp;</p>
            <div align="right"> 
              <p align="center">&nbsp;</p>
              <p align="center">&nbsp;</p>
            </div></td>
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
  <area shape="rect" coords="2,132,74,180" href="reportproblem.php">
  <area shape="rect" coords="82,130,167,175" href="helpDeskAccessAllCalls.php">
  <area shape="rect" coords="169,131,283,172" href="DataAccessSearch.php">
  <area shape="rect" coords="297,129,373,177" href="ocm-first.php">
  <area shape="rect" coords="454,132,548,173" href="DataAccess.php">
  <area shape="rect" coords="-7,2,264,17" href="DataAccess.php">
</map>
</body>
</html>
