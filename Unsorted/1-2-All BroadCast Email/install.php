<?PHP
if ($step == ""){
$step = 1;
}
?>
<html>
<head>
<title>Installer -=-= nulled by [GTT] =-=- </title>
<meta http-equiv="Content-Type" content="text/html; charset=<?PHP print $lang_char; ?>">
<script language="JavaScript">
<!--
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

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}
//-->
</script>
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div align="center">
<table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000033">
    <tr>
      <td><table width="100%" border="0" cellpadding="5" cellspacing="1">
          <tr>
            <td width="150" bgcolor="#FFFFFF"><font size="5" face="Arial, Helvetica, sans-serif"><b><font color="#000066" size="4"><img src="media/h_l.gif" width="150" height="40"></font></b></font></td>
            <td bgcolor="#000033"><div align="center"><font size="5" face="Arial, Helvetica, sans-serif"><b><font color="#FFFFFF">1-2-ALL
                Broadcast E-mail Installer <font color="#FF0000"><em>nulled by [GTT] </em></font></font></b></font></div></td>
          </tr>
        </table></td>
    </tr>
  </table>
  <p>
    <?PHP
        $set1 = ini_get('register_globals');
        if ($set1 != "1" AND $set1 != "On" AND $set1 != "ON" AND $set1 != "on" AND $set1 != "yes" AND $set1 != "YES" AND $set1 != "Yes"){
        ?>
  </p>
  <table width="400" border="0" cellpadding="2" cellspacing="0" bgcolor="#FF0000">
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="4">
          <tr>
            <td width="50"><div align="center"><font color="#FFFFFF" size="12" face="Arial, Helvetica, sans-serif">!</font></div></td>
            <td bgcolor="#FFFFFF"><strong><font size="2" face="Arial, Helvetica, sans-serif">Warning....</font></strong><font size="2" face="Arial, Helvetica, sans-serif"><br>
              register_globals appears to be turned off in your php configuration
              (php.ini file). <strong>register_globals should be turned on</strong>
              to continue install and to function script.<br>
              <font size="1">You may contact your host or system admin to make
              this change.</font></font></td>
          </tr>
        </table></td>
    </tr>
  </table>
  <p>
    <?PHP
        }
$dsource = $HTTP_SERVER_VARS['HTTP_REFERER'];
if ($step == 1){
?>
  </p>
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
              <td width="140" bgcolor="#F4F4FF"> <div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><b>Database
                  Name</b> <br>
                  </font></div></td>
              <td bgcolor="#F4F4FF"><font size="2" face="Arial, Helvetica, sans-serif">
                <input name="db" type="text" id="db" size="15">
                &nbsp; &nbsp;
                <input name="cdb" type="checkbox" id="cdb" value="1">
                *Create </font></td>
            </tr>
            <tr>
              <td width="140" bgcolor="#F0F0F0"> <div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><b>Database
                  Username</b> <br>
                  </font></div></td>
              <td bgcolor="#F0F0F0"><font size="2" face="Arial, Helvetica, sans-serif">
                <input name="user" type="text" id="user" size="15">
                </font></td>
            </tr>
            <tr>
              <td width="140" bgcolor="#F4F4FF"> <div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><b>Database
                  Password</b> <br>
                  </font></div></td>
              <td bgcolor="#F4F4FF"><font size="2" face="Arial, Helvetica, sans-serif">
                <input name="pass" type="password" id="pass" size="15">
                </font></td>
            </tr>
            <tr>
              <td width="140" bgcolor="#F0F0F0"> <div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><b>Database
                  Host</b> <br>
                  </font></div></td>
              <td bgcolor="#F0F0F0"><font size="2" face="Arial, Helvetica, sans-serif"><font size="1">This
                can usually be set to &quot;localhost&quot; by default.</font><br>
                <input name="host" type="text" id="host" value="localhost">
                </font></td>
            </tr>
            <tr>
              <td colspan="2"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
                  <input name="step" type="hidden" id="step" value="2">
                  <input type="submit" value="Continue to Step 2" name="s1" onClick="MM_validateForm('db','','R','user','','R','pass','','R','host','','R');return document.MM_returnValue">
                  </font></div></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <p><font size="2" face="Arial, Helvetica, sans-serif"><em>*If checked the
      installer will attempt to create the database for you.<br>
      Should not be checked if the database already exists.</em></font></p>
  </form>

  <?PHP
}
if ($step == "2"){
@MYSQL_CONNECT("$host","$user","$pass") OR DIE ("<b>Failed to connect to server.</b><br>Information entered is incorrect.<BR>Please check your username, password, and hostname that you entered.<P><a href=\"javascript:window.history.go(-1);\">Back</a>");
if ($cdb == "1"){
$table1 = "CREATE DATABASE `$db`";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
exit();
}
}
$serial = ereg_replace (" ", "", $serial);
$icd = $REMOTE_ADDR;
@MYSQL_SELECT_DB ("$db") OR DIE ("<b>Database Does not exist.</b><BR>Please check/verify your database name and re-enter it.<P><a href=\"javascript:window.history.go(-1);\">Back</a>");
?>
<font size="2" face="Arial, Helvetica, sans-serif">
</font>
  <form name="step2" method="post" action="install.php">
    <table width="400" border="0" cellspacing="0" cellpadding="3" align="center">
      <tr>
        <td bgcolor="#ECECFF"> <div align="center"><b><font size="4" face="Arial, Helvetica, sans-serif" color="#000066">Step
            2 of 2</font></b></div></td>
      </tr>
    </table>
    <table width="400" border="0" align="center" cellpadding="1" cellspacing="0" bgcolor="#000066">
      <tr>
        <td> <div align="center"></div>
          <table width="100%" border="0" cellspacing="0" cellpadding="8" bgcolor="#FFFFFF">
            <tr>
              <td><div align="center">
                <p>
                  <strong><font size="2">You login:</font></strong>
                  <input name="loginn" id="loginn" value="">
                </p>
                <p><font size="2" face="Arial, Helvetica, sans-serif"><strong>You pass:</strong>
                    <input name="password" id="password" value="">
                  </font></p>
                <p><font size="2" face="Arial, Helvetica, sans-serif">
                  <input name="s1" type="submit" id="s1" onClick="MM_validateForm('murl','','R','password','','R');return document.MM_returnValue" value="Finish">
                  </font></p>
                <p><font size="2" face="Arial, Helvetica, sans-serif">
                                    <input name="step" type="hidden" id="step" value="3">
                    <input name="host" type="hidden" id="host" value="<?PHP print $host; ?>">
                    <input name="user" type="hidden" id="user" value="<?PHP print $user; ?>">
                    <input name="pass" type="hidden" id="pass" value="<?PHP print $pass; ?>">
                    <input name="db" type="hidden" id="db" value="<?PHP print $db; ?>">
                          </font></p>
              </div></td>
            </tr>
          </table></td>
      </tr>
    </table>
  </form>
  <font size="2" face="Arial, Helvetica, sans-serif"> </font>
  <?PHP

}
if ($step == 3){
?>
  <font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000">
  <?PHP
@MYSQL_CONNECT("$host","$user","$pass") OR DIE ("<b>Failed to connect to server.</b><br>Information entered is incorrect.<BR>Please check your username, password, and hostname that you entered.<P><a href=\"javascript:window.history.go(-1);\">Back</a>");
@MYSQL_SELECT_DB ("$db") OR DIE ("<b>Database Does not exist.</b><BR>Please check/verify your database name and re-enter it.<P><a href=\"javascript:window.history.go(-1);\">Back</a>");

$table1 = "CREATE TABLE Admin (
  id int(50) NOT NULL auto_increment,
  user varchar(50) NOT NULL default '',
  pass varchar(50) NOT NULL default '',
  name varchar(250) NOT NULL default '',
  email varchar(250) NOT NULL default '',
  m_users int(5) NOT NULL default '0',
  m_lists int(5) NOT NULL default '0',
  m_cre_del int(5) NOT NULL default '0',
  send int(5) NOT NULL default '0',
  lists text NOT NULL,
  m_dusers int(5) NOT NULL default '0',
  m_limit int(10) NOT NULL default '0',
  m_impath varchar(250) NOT NULL default '',
  a_ui int(10) NOT NULL default '0',
  a_ua int(10) NOT NULL default '0',
  a_is int(15) NOT NULL default '100000',
  a_as int(15) NOT NULL default '100000',
  a_ff varchar(250) NOT NULL default '',
  a_nm varchar(15) NOT NULL default '',
  a_pt varchar(25) NOT NULL default '',
  a_tp int(10) NOT NULL default '0',
  a_gc int(10) NOT NULL default '0',
  a_ed int(10) NOT NULL default '0',
  a_mx varchar(15) NOT NULL default '',
  a_s1 int(10) NOT NULL default '0',
  a_s2 int(10) NOT NULL default '0',
  a_s3 int(10) NOT NULL default '0',
  a_em varchar(250) NOT NULL default '',
  a_lt int(10) NOT NULL default '0',
  a_pz int(10) NOT NULL default '0',
  a_bn int(10) NOT NULL default '0',
  a_op int(10) NOT NULL default '0',
  a_co int(10) NOT NULL default '0',
  a_li varchar(25) NOT NULL default '0',
  a_li2 varchar(25) NOT NULL default '',
  menu varchar(20) NOT NULL default '0,0,0,0,0,0,0,0,0',
  PRIMARY KEY  (id)
)";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
print "To fix this problem you must first completly erase all previous 12all settings.  Click <a href=\"install.php?step=clear&db=$db&user=$user&pass=$pass&host=$host\">here</a> to do so.  You will lose all content, e-mails, addresses, etc..";
exit(); }

$table1 = "CREATE TABLE Backend (
  valid int(1) NOT NULL default '0',
  serial varchar(250) NOT NULL default '',
  version varchar(250) NOT NULL default '',
  murl text NOT NULL,
  btype varchar(250) NOT NULL default '',
  add1 varchar(250) NOT NULL default '',
  add2 varchar(250) NOT NULL default '',
  add3 varchar(250) NOT NULL default '',
  add4 varchar(250) NOT NULL default '',
  unsub1 varchar(250) NOT NULL default '',
  unsub2 varchar(250) NOT NULL default '',
  unsub3 varchar(250) NOT NULL default '',
  unsub4 varchar(250) NOT NULL default '',
  lang varchar(125) NOT NULL default '',
  pop_ho varchar(100) NOT NULL default '',
  pop_us varchar(100) NOT NULL default '',
  pop_pa varchar(100) NOT NULL default '',
  pop_po varchar(100) NOT NULL default '',
  pop_em varchar(100) NOT NULL default '',
  pop_op int(10) NOT NULL default '0',
  pop_nu int(10) NOT NULL default '3'
)";

$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
exit(); }

$table1 = "INSERT INTO Backend VALUES ( '1', '', '3.8201', '', 'none', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '')";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
exit(); }

$table1 = "CREATE TABLE Links (
  id int(25) NOT NULL auto_increment,
  nl int(25) NOT NULL default '0',
  link text NOT NULL,
  PRIMARY KEY  (id)
)";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
exit(); }


$table1 = "CREATE TABLE Templates (
  id int(20) NOT NULL auto_increment,
  nl varchar(20) NOT NULL default '',
  name text NOT NULL,
  type varchar(250) NOT NULL default '',
  content text NOT NULL,
  uni varchar(20) NOT NULL default '',
  PRIMARY KEY  (id)
)";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
exit(); }


$table1 = "CREATE TABLE MessagesT (
  id int(20) NOT NULL default '0',
  content longtext NOT NULL,
  UNIQUE KEY id (id)
)";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
exit(); }



$table1 = "CREATE TABLE ListMembersU (
  id int(10) NOT NULL auto_increment,
  em varchar(250) NOT NULL default '',
  nl varchar(250) NOT NULL default '',
  PRIMARY KEY  (id)
)";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
exit(); }


$table1 = "CREATE TABLE 12all_SubForms (
  id int(20) NOT NULL auto_increment,
  add1 varchar(250) NOT NULL default '',
  add2 varchar(250) NOT NULL default '',
  add3 varchar(250) NOT NULL default '',
  add4 varchar(250) NOT NULL default '',
  unsub1 varchar(250) NOT NULL default '',
  unsub2 varchar(250) NOT NULL default '',
  unsub3 varchar(250) NOT NULL default '',
  unsub4 varchar(250) NOT NULL default '',
  label varchar(250) NOT NULL default '',
  PRIMARY KEY  (id)
)";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
exit(); }


$table1 = "CREATE TABLE ListMembers (
  id int(20) NOT NULL auto_increment,
  sip text NOT NULL,
  comp text NOT NULL,
  sdate date NOT NULL default '0000-00-00',
  email varchar(250) NOT NULL default '',
  name varchar(250) NOT NULL default '',
  bounced int(10) NOT NULL default '0',
  bounced_d date NOT NULL default '0000-00-00',
  active int(10) NOT NULL default '0',
  nl int(25) NOT NULL default '0',
  field1 varchar(250) NOT NULL default '',
  field2 varchar(250) NOT NULL default '',
  field3 varchar(250) NOT NULL default '',
  field4 varchar(250) NOT NULL default '',
  field5 varchar(250) NOT NULL default '',
  field6 varchar(250) NOT NULL default '',
  field7 varchar(250) NOT NULL default '',
  field8 varchar(250) NOT NULL default '',
  field9 varchar(250) NOT NULL default '',
  field10 varchar(250) NOT NULL default '',
  stime time NOT NULL default '00:00:00',
  respond varchar(250) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY email (email)
)";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
exit(); }

$table1 = "CREATE TABLE Lists (
  id int(20) NOT NULL auto_increment,
  date date NOT NULL default '0000-00-00',
  admin text NOT NULL,
  name text NOT NULL,
  email text NOT NULL,
  confirmopt int(10) NOT NULL default '0',
  confirmopt2 int(10) NOT NULL default '0',
  bk text NOT NULL,
  murl varchar(250) NOT NULL default '',
  field1 varchar(250) NOT NULL default '',
  field2 varchar(250) NOT NULL default '',
  field3 varchar(250) NOT NULL default '',
  field4 varchar(250) NOT NULL default '',
  field5 varchar(250) NOT NULL default '',
  field6 varchar(250) NOT NULL default '',
  field7 varchar(250) NOT NULL default '',
  field8 varchar(250) NOT NULL default '',
  field9 varchar(250) NOT NULL default '',
  field10 varchar(250) NOT NULL default '',
  a_ui int(10) NOT NULL default '0',
  a_ua int(10) NOT NULL default '0',
  a_is int(15) NOT NULL default '100000',
  a_as int(15) NOT NULL default '100000',
  a_ff varchar(250) NOT NULL default '',
  a_nm varchar(15) NOT NULL default '',
  a_pt varchar(25) NOT NULL default '',
  a_tp int(10) NOT NULL default '0',
  a_gc int(10) NOT NULL default '0',
  a_ed int(10) NOT NULL default '0',
  a_mx varchar(15) NOT NULL default '',
  a_s1 int(10) NOT NULL default '0',
  a_s2 int(10) NOT NULL default '0',
  a_s3 int(10) NOT NULL default '0',
  a_em varchar(250) NOT NULL default '',
  a_lt int(10) NOT NULL default '0',
  a_pz int(10) NOT NULL default '0',
  a_bn int(10) NOT NULL default '0',
  a_op int(10) NOT NULL default '0',
  a_co int(10) NOT NULL default '0',
  a_rq int(10) NOT NULL default '0',
  a_ep date NOT NULL default '0000-00-00',
  a_sc int(10) NOT NULL default '0',
  a_priv int(10) NOT NULL default '0',
  a_atch int(3) NOT NULL default '1',
  confirmoptt varchar(250) NOT NULL default 'text',
  isubject varchar(250) NOT NULL default '',
  icontent longtext NOT NULL,
  osubject varchar(250) NOT NULL default '',
  ocontent longtext NOT NULL,
  PRIMARY KEY  (id)
)";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
exit(); }



$table1 = "CREATE TABLE Messages (
  id int(50) NOT NULL auto_increment,
  user varchar(75) NOT NULL default '',
  mdate date NOT NULL default '0000-00-00',
  mtime time NOT NULL default '00:00:00',
  mfrom varchar(250) NOT NULL default '',
  mfromn varchar(250) NOT NULL default '',
  subject text NOT NULL,
  message text NOT NULL,
  textmesg text NOT NULL,
  htmlmesg longtext NOT NULL,
  nl varchar(250) NOT NULL default '',
  type varchar(250) NOT NULL default '',
  tlinks varchar(25) NOT NULL default '',
  amt int(10) NOT NULL default '0',
  sent int(25) NOT NULL default '0',
  completed int(2) NOT NULL default '0',
  link1n text NOT NULL,
  link1t text NOT NULL,
  filter varchar(20) NOT NULL default '',
  d_check int(10) NOT NULL default '0',
  mesg_id int(10) NOT NULL default '0',
  status int(10) NOT NULL default '0',
  s_date date NOT NULL default '0000-00-00',
  s_time time NOT NULL default '00:00:00',
  init int(10) NOT NULL default '0',
  PRIMARY KEY  (id)
)";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
exit(); }

$table1 = "CREATE TABLE 12all_LinksD (
  id int(10) NOT NULL auto_increment,
  lid int(10) NOT NULL default '0',
  nl int(10) NOT NULL default '0',
  stime time NOT NULL default '00:00:00',
  sdate date NOT NULL default '0000-00-00',
  email varchar(250) NOT NULL default '',
  times int(20) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY lid (lid)
)";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
exit(); }



$table1 = "CREATE TABLE 12all_MesgId (
  id int(10) NOT NULL auto_increment,
  numlist int(10) NOT NULL default '0',
  numlist_comp int(10) NOT NULL default '0',
  PRIMARY KEY  (id)
)";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
exit(); }


$table1 = "CREATE TABLE 12all_MesgTemp (
  runid int(10) NOT NULL default '0',
  email varchar(250) NOT NULL default '',
  KEY runid (runid)
)";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
exit(); }


$table1 = "CREATE TABLE 12all_Bounce (
  id int(20) NOT NULL auto_increment,
  email varchar(250) NOT NULL default '',
  nl int(10) NOT NULL default '0',
  mid int(20) NOT NULL default '0',
  tdate date NOT NULL default '0000-00-00',
  ttime time NOT NULL default '00:00:00',
  PRIMARY KEY  (id),
  KEY email (email),
  KEY mid (mid),
  KEY nl (nl)
)";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
exit(); }

$table1 = "CREATE TABLE 12all_Respond (
  id int(10) NOT NULL auto_increment,
  nl int(10) NOT NULL default '0',
  type varchar(25) NOT NULL default '',
  subject varchar(250) NOT NULL default '',
  fromn varchar(250) NOT NULL default '',
  frome varchar(250) NOT NULL default '',
  time int(30) NOT NULL default '0',
  content longtext NOT NULL,
  PRIMARY KEY  (id)
)";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
exit(); }


$table1 = "CREATE TABLE 12all_RespondT (
  fid int(10) NOT NULL default '0',
  sid int(10) NOT NULL default '0',
  sdate date NOT NULL default '0000-00-00'
)";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
exit(); }



$file = fopen ("engine.inc.php", "w");
$fp = fwrite($file,"<?PHP");
$fp = fwrite($file,"\n");
$fp = fwrite($file,"\$db_link = mysql_connect ");
$fp = fwrite($file,"(\"$host\",\"$user\",\"$pass\"");
$fp = fwrite($file,");");
$fp = fwrite($file,"\n");
$fp = fwrite($file,"\$db_linkdb = mysql_select_db ");
$fp = fwrite($file,"(\"$db\");");
$fp = fwrite($file,"?>"); fclose($file);

$password=base64_encode($password);
mysql_query("UPDATE Backend SET murl='$murl', lang='$lang' WHERE (valid='1')");
mysql_query ("INSERT INTO Admin (name, email, user, pass, m_users, m_lists, m_cre_del, send, m_dusers) VALUES ('$name' ,'$email' ,'$loginn' ,'$password' ,'1' ,'1' ,'1' ,'1' ,'1')");

?>
  </font>
  <table width="400" border="0" cellspacing="0" cellpadding="3" align="center">
    <tr>
      <td bgcolor="#ECECFF">
        <div align="center"><b><font size="4" face="Arial, Helvetica, sans-serif" color="#000066">Basic
          Setup Completed </font></b></div>
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
                <table width="100%" border="0" cellpadding="3" cellspacing="0" bgcolor="#FF0000">
                  <tr>
                    <td><table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF">
                        <tr>
                          <td><font size="2" face="Arial, Helvetica, sans-serif"><strong><font color="#FF0000">NOTICE</font></strong>:
                            To enable such features as <strong>Auto-Responders</strong>,
                            <strong>Automatic Bounce Checking</strong>, <strong>Schedule
                            Sending</strong>, and <strong>Sending Monitoring</strong>,
                            you must complete the extended setup details <a href="cron_setup.txt">here</a>.</font></td>
                        </tr>
                      </table></td>
                  </tr>
                </table>
                <p><font size="2" face="Arial, Helvetica, sans-serif">To login
                  to your Mailing List control panel, visit:<br>
                  <a href="<?PHP print $surl; ?>"> <?PHP print $murl; ?> </a></font></p>
                <?PHP if ($lang == "custom"){
                                  print "<p>You have selected the custom language file.  To modify this language file open the /12all/lang/custom.php file";
                                  } ?>
                <p align="left"><font face="Arial, Helvetica, sans-serif" size="2">
                  <font color="#666666">Your username is admin and your password
                  is the password you specified in this setup.</font></font></p>
                <p align="left"><font color="#666666" size="2" face="Arial, Helvetica, sans-serif">Before
                  logging into your control panel or using this software, delete
                  the file: &quot;install.php&quot;</font></p>
              </div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <?PHP
}
if ($step == clear){

@MYSQL_CONNECT("$host","$user","$pass") OR DIE ("<b>Failed to connect to server.</b><br>Information entered is incorrect.<BR>Please check your username, password, and hostname that you entered.<P><a href=\"javascript:window.history.go(-1);\">Back</a>");
@MYSQL_SELECT_DB ("$db") OR DIE ("<b>Database Does not exist.</b><BR>Please check/verify your database name and re-enter it.<P><a href=\"javascript:window.history.go(-1);\">Back</a>");

$table1 = "DROP TABLE Backend";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
 }

$table1 = "DROP TABLE Lists";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
 }
$table1 = "DROP TABLE 12all_Bounce";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
 }
 $table1 = "DROP TABLE 12all_MesgId";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
 }


$table1 = "DROP TABLE 12all_MesgTemp";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
 }


$table1 = "DROP TABLE Messages";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
 }

 $table1 = "DROP TABLE MessagesT";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
 }

  $table1 = "DROP TABLE ListMembersU";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
 }


$table1 = "DROP TABLE ListMembers";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
}

$table1 = "DROP TABLE Links";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
}
$table1 = "DROP TABLE 12all_LinksD";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
}

$table1 = "DROP TABLE Templates";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
}

$table1 = "DROP TABLE Admin";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
}

$table1 = "DROP TABLE 12all_SubForms";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
}

$table1 = "DROP TABLE 12all_Respond";
$result = mysql_query($table1);
if ($result) {}
else {
echo("<P>Error: " .
mysql_error() . "</P>");
}

$table1 = "DROP TABLE 12all_RespondT";
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
  <?PHP
}
?>
  <font size="2" face="Arial, Helvetica, sans-serif"> </font></div>
</body>
</html>