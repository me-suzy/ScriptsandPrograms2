<?php

if($name && $email && $source && $target && ($views OR $clicks))
{
        require('../prepend.inc.php');
        banner_add();
        header('Location: ./');
}

include("header.inc.php");

$einblendung = 0;
$click = 0;
$anzahl = mysql_num_rows(mysql_query("SELECT id FROM `demo_a_banners`"));
$holen = mysql_query("SELECT views, clicks FROM `demo_a_banners`");

require("header.inc.php");

while ($myrow = mysql_fetch_row($holen)) {

$einblendung = $myrow[0] + $einblendung;
$click = $myrow[1] + $click;
$clickss = $click - $click - $click;
};

?>
<?
include("../templates/admin-header.txt");
?>
<TABLE bgcolor="#FFFFFF" bordercolor="#000000" border="0" align="center">
<TR>
  <TD width="250"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Banner in system</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><? echo "$anzahl"; ?></TD>
</TR>
<TR>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Bannerviews remaining</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><? echo "$einblendung"; ?></TD>
</TR>
<TR>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Bannerclicks</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><? echo "$clickss"; ?></TD>
</TR>
</TABLE><br><br>


<center><font size="4" color="red"><b><u>Add new banner</u></b></font></center><br>
<form method="post" action="./banner_add.php">
  <table width="400" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
      <td>Name</td>
      <td>
        <input type="text" name="name" value="<?php echo stripslashes($name); ?>">
      </td>
    </tr>
    <tr>
      <td height="30">e-mail</td>
      <td height="30">
        <input type="text" name="email" value="<?php echo stripslashes($email); ?>">
      </td>
    </tr>
    <tr>
      <td>Banner URL</td>
      <td>
        <input type="text" name="source" value="<?php echo stripslashes($source); ?>">
      </td>
    </tr>
    <tr>
      <td>Banner Target URL</td>
      <td>
        <input type="text" name="target" value="<?php echo stripslashes($target); ?>">
      </td>
    </tr>
    <tr>
      <td>Alt</td>
      <td>
        <input type="text" name="alt" value="<?php echo stripslashes($alt); ?>">
      </td>
    </tr>
    <tr>
      <td>Views</td>
      <td>
        <input type="text" name="views" value="<?php echo stripslashes($views); ?>">
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <input type="submit" value="Submit">
      </td>
    </tr>
  </table>
</form>
<?
include("../templates/admin-footer.txt");
?>