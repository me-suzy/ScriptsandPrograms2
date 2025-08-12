<?php
// Cjultra v2.1

if (ini_get('register_globals') != 1) {
    $supers = array('_REQUEST','_ENV','_SERVER','_COOKIE','_GET','_POST');
    foreach ($supers as $__s) {
        if (is_array($$__s) == true) extract($$__s, EXTR_OVERWRITE);
    }
    unset($supers);
}

include("./common.php");

$linkid = db_connect();
if (!$linkid) error_message(sql_error());

if ( $action == 'signup' ) {
signup();
}
else { form(); }

function form() {

$query = "select * from settings";
$result = mysql_query($query);
$data = mysql_fetch_array($result);
?>

<html>
<head>
<title></title>
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

<body bgcolor="#000000" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">
<?php include("rules.txt"); ?>
<form method="POST">
    <input type="hidden" name="action" value="signup">


<p>&nbsp;</p>
<p>&nbsp;</p>
<div align="center">
  <center>
  <table border="1" width="600" cellspacing="0" bgcolor="#000080" cellpadding="0">
    <tr>
    <td align="center"><font size="2" face="Verdana">please send all hits to:<br>
<?php echo $data["b1"]; ?>
      <br>Webmaster ICQ# =
      <?php echo $data["b3"]; ?>
      </font>
      </td>
    <tr>
      <td><b><font size="3" face="Verdana">Domain Name:</font></b> <font face="Verdana" size="3"> <input type="text" size="20" maxlength="70"
            name="a1"><br>
        for example: <i>yoursite.com</i> (no
        http://www.)</font></td>
    </tr>
    <tr>
      <td><b><font face="Verdana" size="3">Site Name: </font> </b><font face="Verdana" size="3"><input type="text" size="20" maxlength="50"
            name="a21"><br>
        For Toplists.</font></td>
    </tr>
    <tr>
      <td><b><font face="Verdana" size="3">Url to Send Hits To:</font></b><font face="Verdana" size="3"> <input type="text" size="40" name="a2"
            value="http://"><br>
        This is where you want us to send hits to.
        for example:<i><br>
        http://www.yoursite.com/</i></font></td>
    </tr>
    <tr>
      <td><b><font face="Verdana" size="3">Your ICQ#:</font></b><font face="Verdana" size="3"> <input type="text" size="10" maxlength="50"
            name="a4"><br>
        Optional.</font></td>
    </tr>
    <tr>
      <td><b><font face="Verdana" size="3">Your Nick:</font></b><font face="Verdana" size="3"> <input type="text" size="10" maxlength="50"
            name="a5"><br>
        Optional.</font></td>
    </tr>
    <tr>
      <td><b><font face="Verdana" size="3">Your E-mail: </font> </b><font face="Verdana" size="3"><input type="text" size="20" maxlength="50"
            name="a3"><br>
        Optional.</font></td>
    </tr>
    <tr>
      <td><font face="Verdana" size="3"><input
            type="submit" name="B1" value="Add Your Site" style="border-style: double"></font></td>
    </tr>
  </table>
  <p align="center"><a href="http://www.cjultra.com"><font size=3 face="Verdana">Powered
By CjUltra v2.1<br><br>
Click here to get CJULTRA</a>
  </center>
</div>
</form>
</body>
<?php
}

function signup() {

global $a1, $a2, $a3, $a4, $a5, $a21;
// possible errors
if ($a1 == '' OR $a2 == '') die ('Please go back and fill all required fields') ;
elseif ( strlen($a1) > 200 || strlen($a2) > 200 || strlen($a3) > 50 || strlen($a4) > 15 ) die ('entries too long, go back and fix') ;


else {

$a1 = addslashes($a1);
$a2 = addslashes($a2);
$a3 = addslashes($a3);
$a4 = addslashes($a4);
$a5 = addslashes($a5);
$a21 = addslashes($a21);
// member exists
$query = "select count(*) from trade where a1 = '$a1'";
$result = mysql_query($query);
if(!$result) error_message(sql_error());
$data = mysql_fetch_row($result);
if ($data[0] > 0) die("domain already exists in the database");
if($a1=="noref" || $a1=="nocookie" || $a1=="exout") die("username already exists in the database");
$dom = parse_url($a2);
$dom = eregi_replace("www\.","",$dom["host"]);
if ($a1 != $dom) die("domain doesnt match the url");

$query = "select * from blacklist where e1 = '$a1'";
$result = mysql_query($query);
if(!$result) error_message(sql_error());
if (mysql_num_rows($result) > 0) {
    echo "Domain $a1 is in the blacklist, you can't join";
    exit;
}

$query = "select * from settings";
$result = mysql_query($query);
$data = mysql_fetch_array($result);

$query = "insert into trade values('$a1','$a2','$a3','$a4','$a5','0','-1','0','0','0','0','0','0','" . $data["b6"] . "','0','0','0','" . $data["b7"] . "','0','1','$a21')";
$result = mysql_query($query);
if(!$result) error_message(sql_error());


// signup complete
?>
<html>
<head>
<title></title>
</head>

<body bgcolor="#666699" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">
<?php
$query = "select b3, b1 from settings";
$result = mysql_query($query);
if(!$result) error_message(sql_error());
$icq = mysql_fetch_row($result);
if ($icq[0]) mail("$icq[0]@pager.icq.com","Auto Message From Your CjUltra Script","The domain $a1 signed up at your site $icq[1] for a trade");
echo "<center>signup complete<br><br>please send all hits to:<br>" . $data["b1"] ;

}
}
?>
