<?php
session_start();
if(!session_is_registered("passcode"))
{
include("relogin.html");
exit();
}?>
<link href="stylesheets/admin-panel.css" rel="stylesheet" type="text/css">
                    
<?php if (!$var){
include("connect.php");
$result2 = mysql_query (" SELECT * FROM stylesheet where active='y'");
$row2=mysql_fetch_array($result2);
?>
<span class="heading">YOUR CURRENT STYLE SHEET IS :<?php echo $row2[0];?> </span> <span class="help2"><a href="stylehelp.htm" target="_blank">HELP</a></span>
<table width="86%" border="0" align="center">
  <tr>
    <td class="menu">CLICK ON ANY STYLE TO SELECT IT&nbsp;</td>
   
  </tr>
  <?php 
  include ("connect.php");
  $result = mysql_query (" SELECT * FROM stylesheet ");
  while($row=mysql_fetch_array($result)){
  ?>
  <tr>
  <td class="text-design1"><a href="stylesettings.php?var=<?php echo $row[0];?>"><?php echo $row[0];?></a>
  </td>
  <?php } ?>
</table>

<form method="POST" action="options.php">
  <p align="center"><input name="B2" type="submit" class="back-button" value="BACK TO OPTIONS"></p>
</form>

<?php } else{?>
<?php
include("connect.php");
$result3=mysql_query("UPDATE stylesheet set active='y' where sname='$var'") or die(mysql_error());
$result4=mysql_query("UPDATE stylesheet set active='n' where sname !='$var'") or die(mysql_error());
?>
<span class="heading">Successfully Changed Stylesheet</span>
<?php
$var='';
?>
<form method="POST" action="stylesettings.php">
  <p align="center"><input name="B2" type="submit" class="back-button" value="BACK"></p>
</form>
<?php } ?>