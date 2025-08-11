<?php
session_start();
if(!$_SESSION["passcode"])
{
include("relogin.html");
exit();
}
include("top-header.php");
?>
<link href="stylesheets/admin-panel.css" rel="stylesheet" type="text/css">

 <span class="heading">PAGE CONTENTS</span>
<?php
include ("connect.php");
$result = mysql_query("select text from pages where name='$var' ");
$row = mysql_fetch_row($result);

$name=$row[0];
?>
<table width="100%" border="1" bordercolor="#000000">
 <tr>
          
    <td width="22%" height="59" align="center" class="text-design1">Text</td>
    <td width="78%" align="center" class="text-design2"> 
      <textarea name="text" cols="72" rows="12" class="text-box1"><?php echo $name;?></textarea> 
    </td>
  </tr>
</table>
<form method="POST" action="view.php">
  <p align="center"><input name="B2" type="submit" class="back-button" value="BACK"></p>
</form>
