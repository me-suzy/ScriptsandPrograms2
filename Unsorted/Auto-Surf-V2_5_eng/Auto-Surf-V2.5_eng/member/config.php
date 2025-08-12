<?php
include("header.inc.php");

$result3 = mysql_query("SELECT name, prename, password, email FROM `demo_a_accounts` WHERE id='$userid'");
$row3 = mysql_fetch_row($result3);

$newsid=mt_srand((double)microtime()*1000000);
$newsid=md5(str_replace('.', '', getenv('REMOTE_ADDR') + mt_rand(100000, 999999)));

require('../prepend.inc.php');
$userid=s_verify();

if($email){
        modifyemail($email);
        logout();
}
if($url)
        modifyurl($url);
if($savepoints)
        savepoints(1);
elseif($dontsavepoints)
        savepoints(0);
if($points && $target)
{
        transferpoints($userid, $target, $points);
}

$config=getconfig();

?>
<?
include("../templates/member-header.txt");
?>
<br><font size="3"><table border="0" cellspacing="0" cellpadding="0" width="95%">
  <tr>
    <td align="center" width="20%"><a href="./?sid=<?php echo $sid; ?>">Stats</a></td>
    <td align="center" width="20%"><a href="../frame.php?userid=<?php echo $userid; ?>" target="blank">Surfbar</a></td>
    <td align="center" width="20%"> <a href="./banner.php<? echo "?userid=".$userid."&sid=".$sid; ?>">Bannerviews</a></td>
    <td align="center" width="20%"><a href="./config.php<? echo "?userid=".$userid."&sid=".$sid; ?>">Edit your account</a></td>
  </tr>
</table>
<br><br><br>
<form method="post" action="config2.php<? echo "?sid=".$sid."&userid=".$userid; ?>">
  <input type="hidden" name="sid2" value="<?php echo $sid; ?>">
  <table width="400" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
      <td width="100" bgcolor="#E6E6E6">Name</td>
      <td width="200" bgcolor="#E6E6E6">
        <input type="text" size="30" name="vorneu" value="<?php echo $row3[1]; ?>">
      </td></TR>
      <tr>
      <td width="100">First name</td>
      <td width="200">
        <input type="text" size="30" name="nameneu" value="<?php echo $row3[0]; ?>">
      </td></TR>
      <tr>
      <td width="100" bgcolor="#E6E6E6">Password</td>
      <td width="200" bgcolor="#E6E6E6">
        <input type="text" size="30" name="passneu" value="<?php echo $row3[2]; ?>">
      </td></TR>
      <tr>
      <td width="100">E-mail</td>
      <td width="200">
        <input type="text" size="30" name="emailneu" value="<?php echo $row3[3]; ?>">
      </td> </TR>
       </table><br>
        <center><input type="submit" value="Save"></center></form>
<form method="post" action="config.php">
  <input type="hidden" name="sid" value="<?php echo $sid; ?>">
  <table width="400" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
      <td width="100" bgcolor="#E6E6E6">URL</td>
      <td width="200" bgcolor="#E6E6E6">
        <input type="text" size="30" name="url" value="<?php echo $config[url] ?>">
      </td></tr>
  </table> <br>

        <center><input type="submit" value="Save"></center>
</form>
<form method="post" action="config.php">
  <input type="hidden" name="sid" value="<?php echo $sid; ?>">
<?php
if($config[savepoints]==1){
?>
  <center><input type="submit" name="dontsavepoints" value="Earned points ARE SAVED at the moment">
<?php
}else{
?>
  <center><input type="submit" name="savepoints" value="Earned points are NOT saved at the moment">
<?php } ?>
</form>
<?
include("../templates/member-footer.txt");
?>