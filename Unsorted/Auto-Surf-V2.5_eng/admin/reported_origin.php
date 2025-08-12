<?php

require('../prepend.inc.php');
$reported=getreports();

?>
<?
include("../templates/admin-header.txt");
?>
<table width="100%" border="0" cellspacing="1" cellpadding="0" bordercolor="#000000" align="center">
  <tr align="center">
    <td width="20%"><b>Name</b></td>
    <td width="8%"><b>ID</b></td>
    <td width="10%"><b>Reporter ID</b></td>
    <td width="42%"><b>URL</b></td>
    <td width="10%"></td>
    <td width="10%"></td>
  </tr>
<?php

for($i=0; $i<count($reported); $i++)
        echo '
  <tr align="center">
    <td width="20%"><a href="mailto:'.$reported[$i][email].'">'.$reported[$i][prename].' '.$reported[$i][name].'</a></td>
    <td width="8%">'.$reported[$i][id].'</td>
    <td width="10%">'.$reported[$i][reportedby].'</td>
    <td width="42%"><a href="./frame.php?url='.$reported[$i][url].'" target="_blank">'.$reported[$i][url].'</a></td>
    <td width="10%"><form method="post" action="./report_delete.php"><input type="hidden" name="id" value="'.$reported[$i][id].'"><input type="submit" value="Validate"></form></td>
    <td width="10%"><form method="post" action="./report_confirm.php"><input type="hidden" name="id" value="'.$reported[$i][id].'"><input type="submit" value="Lock"></form></td>
  </tr>
        ';

?></TD>
</TR>
</TABLE>
<?
include("../templates/admin-footer.txt");
?>