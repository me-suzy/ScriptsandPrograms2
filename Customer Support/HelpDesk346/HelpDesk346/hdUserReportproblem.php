
<?

		include 'dataaccessheader.php';

?>

<link href="style.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
// -->
function validate()
{
if(document.helpDesk.FirstName.value=="")
     {
             alert("Please Enter FirstName");
             document.helpDesk.FirstName.focus();
             return false;
     }
	
 if(document.helpDesk.LastName.value=="")
	 {
	 	alert("Please Enter LastName");
		document.helpDesk.LastName.focus();
		return false;
	 }
   if(document.helpDesk.eMail.value=="")
	{
		alert("Please Enter E-mail Address");
		document.helpDesk.eMail.focus();
		return false;
	}
  else if(!emailOk())
 	return false;


   if(document.helpDesk.describe.value=="")
	{
		alert("Please Enter Description");
		document.helpDesk.describe.focus();
		return false;
	}

return true;
}

function emailOk()
{
	var str = document.helpDesk.eMail.value;
	var reg1 = /(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/; // not valid
	var reg2 = /^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/; // valid
	if (!reg1.test(str) && reg2.test(str))
	{
		return true;
	}
	alert("\"" + str + "\" is an invalid e-mail!");
	document.helpDesk.eMail.focus();
	return false;
}
</script>


<body bgcolor="#FFFFFF" text="#000000"  link="#0000FF" alink="#FF0000" vlink="#0000FF">
<table width="75%" height="873" border="0" align="left" cellpadding="0" cellspacing="2">
  <tr> 
    <td width="6%" rowspan="3" valign="top" bgcolor="#000000"> <div align="center"></div>
      <blockquote> 
        <p>&nbsp;</p>
      </blockquote>
      <div align="center"></div></td>
    <td height="89" valign="top"> 

    </td>
  </tr>
  <tr> 
    <td height="26" valign="top"> 
      <p><strong>Welcome to the Information Technology Help Desk page.</strong></p>
      </td>
  </tr>
  <tr> 
    <td height="233" valign="top"> <div align="center"> 
        <p align="left">Please provide us with the details necessary so that we 
          can quickly diagnose your' technical problem.<br>
          (<strong><font color="#FF0000">Filling in all fields is required</font></strong>)</p>
        <form action="helpDeskAccess.php" method="post" name="helpDesk" id="helpDesk" onSubmit="return validate ()">
          <p align="left">First Name: 
            <input name="FirstName" type="text" id="FirstName">
            <br>
            Last Name: 
            <input name="LastName" type="text" id="LastName">
            <br>
            E Mail Address: 
            <input name="eMail" type="text" id="eMail" size="55">
            <br>
            Phone Number (<em>Optional</em>):
            <input name="phone" type="text" size="35" /> &nbsp;
            Ext (<em>Optional</em>)
            <input name="ext" type="text" size="7" />
          </p>
          <p align="left">Please select the category<br>
            your problem falls under</p>
          <p align="left"> 
            <select name="PCatagory" id="PCatagory">
			<?
			       include("config.php");require("config.php");
	   $cnx = mysql_connect($server,$database,$databasePassword); mysql_select_db($databaseName)		//This statement is required to select the database from the mysql server
       	           or die("Invalid : " . mysql_error());
				   
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
            </select>
          </p>
          <div align="left">Please describe the problem you are experiencing:<br>
            <textarea name="describe" cols="60" rows="5" id="describe"></textarea>
            <br>
            <input type="submit" name="Submit" value="Submit" class="button">
          </div>
        </form>
        <p><br>
          <a href="http://www.helpdeskreloaded.com"><img src="http://www.helpdeskreloaded.com/reload/help-desk-copyright.jpg" alt="http://www.helpdeskreloaded.com Help Desk Software By  HelpDeskReloaded &quot;Help Desk Reloaded&quot;" border="0"></a></p>
       
      </div></td>
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


