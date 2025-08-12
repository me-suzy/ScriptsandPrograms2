<?php

require('../prepend.inc.php');
$noshowups=getnoshowups();

banner_cleandb();

?>
<?
include("../templates/admin-header.txt");
?>
<TABLE bgcolor="#FFFFFF" bordercolor="#000000" border="1" width="99%" align="center">
  <tr align="center">
    <td width="20%"><b>Name</b></td>
    <td width="8%"><b>ID</b></td>
    <td width="52%"><b>URL</b></td>
    <td width="10%">&nbsp;</td>
    <td width="10%">&nbsp;</td>
  </tr>
<?php

for($i=0; $i<count($noshowups); $i++)
        echo '
  <tr align="center">
    <td width="20%"><a href="mailto:'.$noshowups[$i][email].'">'.$noshowups[$i][prename].' '.$noshowups[$i][name].'</a></td>
    <td width="8%">'.$noshowups[$i][id].'</td>
    <td width="52%"><a href="./frame.php?url='.$noshowups[$i][url].'" target="_blank">'.$noshowups[$i][url].'</a></td>
    <td width="10%"><form method="post" action="./showup.php"><input type="hidden" name="id" value="'.$noshowups[$i][id].'"><input type="submit" value="Validate"></form></td>
    <td width="10%"><form method="post" action="./delete.php"><input type="hidden" name="id" value="'.$noshowups[$i][id].'"><input type="submit" value="Delete"></form></td>
  </tr>
        ';

?></TD>
</TR>
</TABLE>
<?
include("../templates/admin-footer.txt");
?>