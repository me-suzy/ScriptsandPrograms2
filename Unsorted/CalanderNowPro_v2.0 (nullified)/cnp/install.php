<?
//////////////////////////////////////////////////////////////////////////////                      
//                                                                          //
//  Program Name         : Calander Now Pro                                 //
//  Program version      : 2.0                                              //
//  Program Author       : Jason VandeBoom                                  //
//  Supplied by          : drew010                                          //
//  Nullified by         : CyKuH [WTN]                                      //
//  Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                          //
//////////////////////////////////////////////////////////////////////////////                      
if ($step == ""){
$step = 1;
}
?>
<html>
<head>
<title>CalendarNow</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript">
<!--
function MM_findObj(n, d) { //v4.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && document.getElementById) x=document.getElementById(n); return x;
}

function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='R') {
        if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (val<min || max<val) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
  } if (errors) alert('The following error(s) occurred:\n'+errors);
  document.MM_returnValue = (errors == '');
}
//-->
</script>
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div align="center"> 
  <p><img src="media/top.gif" width="750" height="73"></p>
  <p><font size="5" face="Arial, Helvetica, sans-serif"><b><font color="#000066" size="4">CalendarNow 
    Installer<br><font size=2>Nullified by WTN Team</font></b></font></p>
<?
if ($step == 1){
?>
  <form name="step1" method="post" action="install.php">
    <div align="left">
      <table width="400" border="0" cellspacing="0" cellpadding="3" align="center">
        <tr>
          <td bgcolor="#ECECFF"> 
            <div align="center"><b><font size="4" face="Arial, Helvetica, sans-serif" color="#000066">Step 
              1 of 2</font></b> </div>
          </td>
        </tr>
      </table>
      
    </div>
    <table width="400" border="0" cellspacing="0" cellpadding="1" bgcolor="#000066">
      <tr> 
        <td> 
          <div align="center"></div>
          <table width="100%" border="0" cellspacing="0" cellpadding="8" bgcolor="#FFFFFF">
            <tr> 
              <td> 
                <div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><br>
                  The information below may usually be available from your web 
                  host upon request.</font></div>
              </td>
            </tr>
            <tr> 
              <td bgcolor="#F4F4FF"> 
                <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><b>Database 
                  Name</b><br>
                  <input type="text" name="db">
                  </font></div>
              </td>
            </tr>
            <tr> 
              <td bgcolor="#F0F0F0"> 
                <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><b>Database 
                  Username</b><br>
                  <input type="text" name="user">
                  </font></div>
              </td>
            </tr>
            <tr> 
              <td bgcolor="#F4F4FF"> 
                <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><b>Database 
                  Password</b><br>
                  <input type="password" name="pass">
                  </font></div>
              </td>
            </tr>
            <tr> 
              <td bgcolor="#F0F0F0"> 
                <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><b>Database 
                  Host</b><br>
                  <font size="1">This can usually be set to &quot;localhost&quot; 
                  by default.</font><br>
                  <input type="text" name="host" value="localhost">
                  </font></div>
              </td>
            </tr>
            <tr> 
              <td> 
                <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
                  <input type="submit" value="Continue to Step 2" name="s1" onClick="MM_validateForm('db','','R','user','','R','host','','R');return document.MM_returnValue">
                  <input type="hidden" name="step" value="2">
                  </font></div>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </form>
  
  <?
}
if ($step == 2){
?>
  <font size="2" face="Arial, Helvetica, sans-serif"> 
  <?
@MYSQL_CONNECT($host,$user,$pass) OR DIE ("<b>Failed to connect to server.</b><br>Information entered is incorrect.<BR>Please check your username, password, and hostname that you entered.<P><a href=\"javascript:window.history.go(-1);\">Back</a>"); 
@MYSQL_SELECT_DB ("$db") OR DIE ("<b>Database Does not exist.</b><BR>Please check/verify your database name and re-enter it.<P><a href=\"javascript:window.history.go(-1);\">Back</a>"); 

$table1 = "CREATE TABLE cnpAdmin (
  id int(50) NOT NULL auto_increment,
  user varchar(50) NOT NULL default '',
  pass varchar(50) NOT NULL default '',
  name varchar(250) NOT NULL default '',
  email varchar(250) NOT NULL default '',
  lists text NOT NULL,
  PRIMARY KEY  (id)
)";
$result = mysql_query($table1); 
if ($result) {} 
else { 
echo("<P>Error: " . 
mysql_error() . "</P>"); 
print "To fix this problem you must first completly erase all previous 12all settings.  Click <a href=\"install.php?step=clear&db=$db&user=$user&pass=$pass&host=$host\">here</a> to do so.  You will lose all content, e-mails, addresses, etc..";
exit(); } 

$table1 = "CREATE TABLE cnpBackend (
  valid int(5) NOT NULL default '0',
  serial varchar(250) NULL default '',
  version varchar(250) NOT NULL default ''
)";

$result = mysql_query($table1); 
if ($result) {} 
else { 
echo("<P>Error: " . 
mysql_error() . "</P>"); 
exit(); } 

$table1 = "INSERT INTO cnpBackend VALUES ( '1', 'WTN Team', '2.0')";
$result = mysql_query($table1); 
if ($result) {} 
else { 
echo("<P>Error: " . 
mysql_error() . "</P>"); 
exit(); } 

$table1 = "CREATE TABLE cnpCalendar (
  id int(20) NOT NULL auto_increment,
  nl int(10) NOT NULL default '0',
  date date NOT NULL default '0000-00-00',
  time varchar(250) NOT NULL default '',
  header varchar(250) NOT NULL default '',
  info text NOT NULL,
  PRIMARY KEY  (id)
)";
$result = mysql_query($table1); 
if ($result) {} 
else { 
echo("<P>Error: " . 
mysql_error() . "</P>"); 
exit(); } 


$table1 = "CREATE TABLE cnpLists (
  id int(20) NOT NULL auto_increment,
  name varchar(250) NOT NULL default '',
  date date NOT NULL default '0000-00-00',
  PRIMARY KEY  (id)
)";
$result = mysql_query($table1); 
if ($result) {} 
else { 
echo("<P>Error: " . 
mysql_error() . "</P>"); 
exit(); } 
$file = fopen ("engine.inc.php", "w");
$fp = fwrite($file,"<?");
$fp = fwrite($file,"\n");
$fp = fwrite($file,"mysql_connect ");
$fp = fwrite($file,"(\"$host\",\"$user\",\"$pass\"");
$fp = fwrite($file,");");
$fp = fwrite($file,"\n");
$fp = fwrite($file,"mysql_select_db ");
$fp = fwrite($file,"(\"$db\");");
$fp = fwrite($file,"?>"); 
fclose($file);  ?> </font> 
  <form name="step2" method="post" action="install.php">
    <table width="400" border="0" cellspacing="0" cellpadding="3" align="center">
      <tr> 
        <td bgcolor="#ECECFF"> 
          <div align="center"><b><font size="4" face="Arial, Helvetica, sans-serif" color="#000066">Step 
            2 of 2</font></b> </div>
        </td>
      </tr>
    </table>
    <table width="400" border="0" cellspacing="0" cellpadding="1" bgcolor="#000066">
      <tr> 
        <td> 
          <div align="center"></div>
          <table width="100%" border="0" cellspacing="0" cellpadding="8" bgcolor="#FFFFFF">
            <tr> 
              <td bgcolor="#F0F0F0"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><b>Your 
                  Name</b><br>
                  <input name="name" type="text" id="name" size="30">
                  </font></div></td>
            </tr>
            <tr> 
              <td bgcolor="#F4F4FF"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><b>Your 
                  E-mail Address</b><br>
                  <input type="text" name="email" size="30">
                  </font></div></td>
            </tr>
            <tr> 
              <td bgcolor="#F0F0F0"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><b>Administrative 
                  Username for your Mailing List</b><br>
                  admin </font></div></td>
            </tr>
            <tr> 
              <td bgcolor="#F4F4FF"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><b>Administrative 
                  Password for your Mailing List</b><br>
                  <input name="password" type="password" id="password">
                  </font></div></td>
            </tr>
            <tr> 
              <td> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
                  <input type="submit" value="Finish" name="s12" onClick="MM_validateForm('murl','','R','surl','','R','sname','','R','username','','R','password','','R');return document.MM_returnValue">
                  <input type="hidden" name="step" value="3">
                  <input type="hidden" name="host" value="<? print $host; ?>">
                  <input type="hidden" name="user" value="<? print $user; ?>">
                  <input type="hidden" name="pass" value="<? print $pass; ?>">
                  <input type="hidden" name="db" value="<? print $db; ?>">
                  <input type="hidden" name="serial" value="<? print $serial; ?>">
                  </font></div></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </form>
  <?
}
if ($step == 3){
?>
  <font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> 
  <?php
  @MYSQL_CONNECT($host,$user,$pass) OR DIE ("<b>Failed to connect to server.</b><br>Information entered is incorrect.<BR>Please check your username, password, and hostname that you entered.<P><a href=\"javascript:window.history.go(-1);\">Back</a>"); 
@MYSQL_SELECT_DB ("$db") OR DIE ("<b>Database Does not exist.</b><BR>Please check/verify your database name and re-enter it.<P><a href=\"javascript:window.history.go(-1);\">Back</a>"); 
$password=base64_encode($password);
mysql_query ("INSERT INTO cnpAdmin (name, email, user, pass) VALUES ('$name' ,'$email' ,'admin' ,'$password')");  

?>
  </font> 
  <table width="400" border="0" cellspacing="0" cellpadding="3" align="center">
    <tr> 
      <td bgcolor="#ECECFF"> 
        <div align="center"><b><font size="4" face="Arial, Helvetica, sans-serif" color="#000066">Setup 
          Completed </font></b></div>
      </td>
    </tr>
  </table>
  <table width="400" border="0" cellspacing="0" cellpadding="1" bgcolor="#000066">
    <tr> 
      <td> 
        <div align="center"><b></b></div>
        <table width="100%" border="0" cellspacing="0" cellpadding="8" bgcolor="#FFFFFF">
          <tr> 
            <td bgcolor="#F4F4FF">
<div align="center">
                <p><font face="Arial, Helvetica, sans-serif" size="2"><b><font color="#990000">IMPORTANT</font></b>:<br>
                  Before logging into your control panel or using this software, 
                  delete the file: &quot;install.php&quot;</font></p>
                <p><font size="2" face="Arial, Helvetica, sans-serif">Your username 
                  is admin and your password is the password you specified in 
                  this setup.</font></p>
                <p><font face="Arial, Helvetica, sans-serif" size="2">Thank you 
                  for using CalendarNow Software.</font></p>
                <p><font size="2" face="Arial, Helvetica, sans-serif"><a href="index.php">Click 
                  here to login</a></font></p>
              </div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <?
}
if ($step == clear){

@MYSQL_CONNECT($host,$user,$pass) OR DIE ("<b>Failed to connect to server.</b><br>Information entered is incorrect.<BR>Please check your username, password, and hostname that you entered.<P><a href=\"javascript:window.history.go(-1);\">Back</a>"); 
@MYSQL_SELECT_DB ("$db") OR DIE ("<b>Database Does not exist.</b><BR>Please check/verify your database name and re-enter it.<P><a href=\"javascript:window.history.go(-1);\">Back</a>"); 

$table1 = "DROP TABLE cnpBackend";
$result = mysql_query($table1); 
if ($result) {} 
else { 
echo("<P>Error: " . 
mysql_error() . "</P>"); 
 } 

$table1 = "DROP TABLE cnpLists";
$result = mysql_query($table1); 
if ($result) {} 
else { 
echo("<P>Error: " . 
mysql_error() . "</P>"); 
 } 

$table1 = "DROP TABLE cnpAdmin";
$result = mysql_query($table1); 
if ($result) {} 
else { 
echo("<P>Error: " . 
mysql_error() . "</P>"); 
 } 

$table1 = "DROP TABLE cnpCalendar";
$result = mysql_query($table1); 
if ($result) {} 
else { 
echo("<P>Error: " . 
mysql_error() . "</P>"); 
} 
?>
  <font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> </font> 
  <table width="400" border="0" cellspacing="0" cellpadding="3" align="center">
    <tr> 
      <td bgcolor="#ECECFF"> 
        <div align="center"><b><font size="4" face="Arial, Helvetica, sans-serif" color="#000066">Previous 
          Database Settings Cleared</font></b></div>
      </td>
    </tr>
  </table>
  <table width="400" border="0" cellspacing="0" cellpadding="1" bgcolor="#000066">
    <tr> 
      <td> 
        <div align="center"><b></b></div>
        <table width="100%" border="0" cellspacing="0" cellpadding="8" bgcolor="#FFFFFF">
          <tr> 
            <td bgcolor="#F4F4FF"> 
              <div align="center"> 
                <p><font size="2" face="Arial, Helvetica, sans-serif">All database 
                  tables, and content have been removed.</font></p>
                <p><font face="Arial, Helvetica, sans-serif" size="2">Click here 
                  to return to the <a href="install.php">install main screen</a>.</font></p>
                </div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <?
}
?>
  <font size="2" face="Arial, Helvetica, sans-serif"> </font></div>
</body>
</html>
