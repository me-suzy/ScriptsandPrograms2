<?php
// Cjultra v2.1

if (ini_get('register_globals') != 1) {
    $supers = array('_REQUEST','_ENV','_SERVER','_COOKIE','_GET','_POST');
    foreach ($supers as $__s) {
        if (is_array($$__s) == true) extract($$__s, EXTR_OVERWRITE);
    }
    unset($supers);
}

if ($action == "setup") {
   setup();
   exit;
}
else {
?>
<html>

<head>
<title>CJULTRA SETUP</title>
<style>
<!--
.icq:hover { text-decoration: none; color: "orange";}
A { text-decoration: none }
A:hover {COLOR: yellow }
TH { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#D098FF; background-color: #222244}
TD { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#FFFFC0; background-color: #333355}
BODY { font-family:Arial ; font-size:10pt; color:#EFFFFF}
input { font-family: Verdana ; font-size:10pt;}
img { border-width: 0}
table {border-color: #003366;  border-width: 1}
-->
</style>
</head>

<body bgcolor="#555555" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">

<?php

/// cjstats
if (!file_exists('cjstats'))
{
    echo "Warning: Folder 'cjstats' does not exist. Please create it and chmod it to 777<br>";
    $setup_err = 1;
}
else
{
    if (!is_writeable('cjstats'))
    {
        echo "Warning: Folder 'cjstats' does not have writing permissions. Please chmod it to 777<br>";
    $setup_err = 1;
    }
}
/// iplog.txt
if (!file_exists('iplog.txt'))
{
    echo "Warning: File 'iplog.txt' does not exist. Please upload it and chmod it to 777<br>";
    $setup_err = 1;
}
else
{
    if (!is_writeable('iplog.txt'))
    {
        echo "Warning: File 'iplog.txt' does not have writing permissions. Please chmod it to 777<br>";
    $setup_err = 1;
    }
}
/// common.php
if (!file_exists('common.php'))
{
    echo "Warning: File 'common.php' does not exist. Please upload it and chmod it to 777<br>";
    $setup_err = 1;
}
else
{
    if (!is_writeable('common.php'))
    {
        echo "Warning: File 'common.php' does not have writing permissions. Please chmod it to 777<br>";
    $setup_err = 1;
    }
}
/// topheader.txt
if (!file_exists('topheader.txt'))
{
    echo "Warning: File 'topheader.txt' does not exist. Please upload it and chmod it to 777<br>";
    $setup_err = 1;
}
else
{
    if (!is_writeable('topheader.txt'))
    {
        echo "Warning: File 'topheader.txt' does not have writing permissions. Please chmod it to 777<br>";
    $setup_err = 1;
    }
}
/// toplines.txt
if (!file_exists('toplines.txt'))
{
    echo "Warning: File 'toplines.txt' does not exist. Please upload it and chmod it to 777<br>";
    $setup_err = 1;
}
else
{
    if (!is_writeable('toplines.txt'))
    {
        echo "Warning: File 'toplines.txt' does not have writing permissions. Please chmod it to 777<br>";
    $setup_err = 1;
    }
}
/// topfooter.txt
if (!file_exists('topfooter.txt'))
{
    echo "Warning: File 'topfooter.txt' does not exist. Please upload it and chmod it to 777<br>";
    $setup_err = 1;
}
else
{
    if (!is_writeable('topfooter.txt'))
    {
        echo "Warning: File 'topfooter.txt' does not have writing permissions. Please chmod it to 777<br>";
    $setup_err = 1;
    }
}

// in.php
if (!file_exists('in.php'))
{
    echo "Warning: File 'in.php' does not exist. Please upload it<br>";
    $setup_err = 1;
}
// out.php
if (!file_exists('out.php'))
{
    echo "Warning: File 'out.php' does not exist. Please upload it<br>";
    $setup_err = 1;
}

if ($setup_err)
{
    echo "<br>1 or more errors occured. Script is not ready for setup, please fix the errors and run setup again. Read the readme file for assistance.<br>";
    exit;
}
?>

<form method="POST">
<input type="hidden" name="action" value="setup">


<p>&nbsp;</p>
<div align="center">
  <center>
  <table border="1" width="600" cellspacing="0" bgcolor="#000066" cellpadding="0">
    <tr>
      <td colspan="2" valign="top">
        <p align="center"><font face="Verdana" size="3"><b>CJULTRA v.2.1 SETUP</b></font></td>
    </tr>
    <tr>
      <td valign="top"><b><font size="3" face="Verdana">Your Mainpage Url:</font></b>&nbsp; <font face="Verdana" size="3"> <br>
        </font><font face="Verdana" size="1">
        for example: http://www.<i>somecjsite.com</i></font></td>
      <td valign="top"> <font face="Verdana" size="3"> <!--webbot
        bot="Validation" B-Value-Required="TRUE" I-Minimum-Length="10"
        I-Maximum-Length="100" --> <input type="text" size="40" maxlength="100"
            name="b1" value="http://"></font></td>
    </tr>
    <tr>
      <td valign="top"><b><font face="Verdana" size="3">Your E-mail:</font></b><font face="Verdana" size="3">&nbsp;</font></td>
      <td valign="top"><font face="Verdana" size="3"><!--webbot bot="Validation"
        B-Value-Required="TRUE" I-Maximum-Length="100" --><input type="text" size="40" name="b2"
            value="@" maxlength="100"></font></td>
    </tr>
    <tr>
      <td valign="top"><b><font face="Verdana" size="3">Your ICQ#:</font></b><font face="Verdana" size="3"> &nbsp;</font></td>
      <td valign="top"><font face="Verdana" size="3"> <!--webbot
        bot="Validation" B-Value-Required="TRUE" I-Maximum-Length="20" --> <input type="text" size="20" maxlength="20"
            name="b3"></font></td>
    </tr>
    <tr>
      <td valign="top"><b><font face="Verdana" size="3">Your Nickname:</font></b><font face="Verdana" size="3">
        </font></td>
      <td valign="top"><font face="Verdana" size="3"> <!--webbot
        bot="Validation" B-Value-Required="TRUE" I-Maximum-Length="20" --> <input type="text" size="20" maxlength="20"
            name="b4"></font></td>
    </tr>
    <tr>
      <td valign="top"><font face="Verdana" size="3"><b>Password:<br>
        </b><font size="1">This is gonna be your admin password for the script.</font></font></td>
      <td valign="top"><font face="Verdana" size="3"><!--webbot bot="Validation"
        B-Value-Required="TRUE" I-Maximum-Length="20" --><input type="text" size="20" maxlength="20"
            name="b12"></font></td>
    </tr>
    <tr>
      <td valign="top"><b><font face="Verdana" size="3">MySql Username:<br>
        </font></b><font face="Verdana" size="1">Usually same as your ftp
        username, ask your host if you are not sure.</font></td>
      <td valign="top"><font face="Verdana" size="3"><!--webbot bot="Validation"
        B-Value-Required="TRUE" --><input type="text" size="20"
            name="dbusername"></font></td>
    </tr>
    <tr>
      <td valign="top"><b><font face="Verdana" size="3">MySql Password:<br>
        </font></b><font face="Verdana" size="1">Usually same as your ftp
        password, ask your host if you are not sure.</font></td>
      <td valign="top"><font face="Verdana" size="3"><!--webbot bot="Validation"
        B-Value-Required="TRUE" --><input type="text" size="20"
            name="dbuserpassword"></font></td>
    </tr>
    <tr>
      <td valign="top"><b><font face="Verdana" size="3">MySql Database name:<br>
        </font></b><font face="Verdana" size="1">Usually same as your ftp
        username, ask your host if you are not sure.</font></td>
      <td valign="top"><font face="Verdana" size="3"><!--webbot bot="Validation"
        B-Value-Required="TRUE" --><input type="text" size="20"
            name="dbname"></font></td>
    </tr>
        <tr>
      <td valign="top"><b><font face="Verdana" size="3">MySql Database Host:<br>
        </font></b><font face="Verdana" size="1">Mostly localhost</font></td>
      <td valign="top"><font face="Verdana" size="3"><!--webbot bot="Validation"
        B-Value-Required="TRUE" --><input type="text" size="20"
            name="dbhost" value="localhost"></font></td>
    </tr>
    <tr>
      <td valign="top" colspan="2">
        <p align="center"><font face="Verdana" size="3"><input
            type="submit" name="butt1" value="SETUP"></font></p>
      </td>
    </tr>
  </table>
  </center>
</div>
</form>
</body>
</html>
<?php
}

#######################
function setup()
{
?>
<html>

<head>
<title>CJULTRA SETUP</title>
</head>

<body bgcolor="#555555" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">
<p><font face="Verdana" size="4"><strong>SETTING UP...<br><br>
<?php
global $b1, $b2, $b3, $b4, $b12, $dbusername, $dbuserpassword, $dbname,$dbhost;
//$dbhost = 'localhost';
$MYSQL_ERRNO = '';
$MYSQL_ERROR = '';
$linkid = db_connect($dbname);
if (!$linkid) {
    $con_err = true;
    error_message(sql_error());
}

// create trade

$query = "create table trade (a1 varchar(100) not null, a2 varchar(100), a3 varchar(100),
a4 varchar(15), a5 varchar(30), a6 int, a7 int, a8 int, a9 int, a10 int, a11 int, a12 int,
a13 int, a14 int, a15 int, a16 int, a17 int, a18 int, a19 int, a20 int, a21 varchar(50), primary key (a1))";
$result = mysql_query($query);
if(!$result) error_message(sql_error());

// create settings

$query2 = "create table settings (b1 varchar(100) not null, b2 varchar(100), b3 varchar(15),
b4 varchar(30), b5 varchar(200), b6 int, b7 int, b8 int, b9 int, b10 int, b11 int, b12 varchar(20),
b13 int, b14 int, primary key (b1))";
$result2 = mysql_query($query2);
if(!$result2) error_message(sql_error());

// create links

$query3 = "create table links (c1 varchar(100) not null, c2 int, primary key (c1))";
$result3 = mysql_query($query3);
if(!$result3) error_message(sql_error());

// set settings

$query5 = "delete from settings";
$result5 = mysql_query($query5);
if(!$result5) error_message(sql_error());
$query5 = "insert into settings values('$b1','$b2','$b3','$b4','ENTER A URL HERE','110','0','0','0','0','10','$b12','0','0')";
$result5 = mysql_query($query5);
if(!$result5) error_message(sql_error());

// create and set lastupdate

$query6 = "create table lastupdate (i1 int not null, i2 int, primary key (i1))";
$result6 = mysql_query($query6);
if(!$result6) error_message(sql_error());
$day = date("j");
$hour = date("G");
$time = time();
$query6 = "delete from lastupdate";
$result6 = mysql_query($query6);
if(!$result6) error_message(sql_error());
$query6 = "insert into lastupdate values('$hour','$time')";
$result6 = mysql_query($query6);
if(!$result6) error_message(sql_error());


// create blacklist

$query8 = "create table blacklist (e1 varchar(100))";
$result8 = mysql_query($query8);
if(!$result8) error_message(sql_error());

// create day

$query9 = "create table day (z varchar(100) not null, zr0 int, zr1 int, zr2 int, zr3 int, zr4 int, zr5 int, zr6 int, zr7 int, zr8 int, zr9 int, zr10 int, zr11 int,
zr12 int, zr13 int, zr14 int, zr15 int, zr16 int, zr17 int, zr18 int, zr19 int, zr20 int, zr21 int, zr22 int, zr23 int,
zu0 int, zu1 int, zu2 int, zu3 int, zu4 int, zu5 int, zu6 int, zu7 int, zu8 int, zu9 int, zu10 int, zu11 int,
zu12 int, zu13 int, zu14 int, zu15 int, zu16 int, zu17 int, zu18 int, zu19 int, zu20 int, zu21 int, zu22 int, zu23 int,
zo0 int, zo1 int, zo2 int, zo3 int, zo4 int, zo5 int, zo6 int, zo7 int, zo8 int, zo9 int, zo10 int, zo11 int,
zo12 int, zo13 int, zo14 int, zo15 int, zo16 int, zo17 int, zo18 int, zo19 int, zo20 int, zo21 int, zo22 int, zo23 int,
zc0 int, zc1 int, zc2 int, zc3 int, zc4 int, zc5 int, zc6 int, zc7 int, zc8 int, zc9 int, zc10 int, zc11 int,
zc12 int, zc13 int, zc14 int, zc15 int, zc16 int, zc17 int, zc18 int, zc19 int, zc20 int, zc21 int, zc22 int, zc23 int, primary key (z))";
$result9 = mysql_query($query9);
if(!$result9) error_message(sql_error());


if (!$con_err) {
$common_array = file("./common.txt");
$common_array[2] = "\$dbhost = '$dbhost';\n";
$common_array[3] = "\$dbusername = '$dbusername';\n";
$common_array[4] = "\$dbuserpassword = '$dbuserpassword';\n";
$common_array[5] = "\$default_dbname = '$dbname';\n";
if ($fp = fopen("./common.php" , "w")) {
            $common = implode("", $common_array);
            fwrite($fp, $common);
            fclose($fp);
}
else die("SETUP COULD NOT BE COMPLETED");


?>
DONE</strong></font>
<?php
}
}

function db_connect($dbname='') {
   global $dbhost, $dbusername, $dbuserpassword, $default_dbname;
   global $MYSQL_ERRNO, $MYSQL_ERROR;

   $link_id = mysql_connect($dbhost, $dbusername, $dbuserpassword);
   if(!$link_id) {
      $MYSQL_ERRNO = 0;
      $MYSQL_ERROR = "Connection failed to the host $dbhost.";
      return 0;
   }
   else if(empty($dbname) && !mysql_select_db($default_dbname)) {
      $MYSQL_ERRNO = mysql_errno();
      $MYSQL_ERROR = mysql_error();
      return 0;
   }
   else if(!empty($dbname) && !mysql_select_db($dbname)) {
      $MYSQL_ERRNO = mysql_errno();
      $MYSQL_ERROR = mysql_error();
      return 0;
   }
   else return $link_id;
}

function sql_error() {
   global $MYSQL_ERRNO, $MYSQL_ERROR;

   if(empty($MYSQL_ERROR)) {
      $MYSQL_ERRNO = mysql_errno();
      $MYSQL_ERROR = mysql_error();
   }
   return "$MYSQL_ERRNO: $MYSQL_ERROR";
}

function error_message($msg) {
   global $con_err;
   echo "Error: $msg<br>";
   if ($con_err) die("SETUP COULD NOT BE COMPLETED");
}
