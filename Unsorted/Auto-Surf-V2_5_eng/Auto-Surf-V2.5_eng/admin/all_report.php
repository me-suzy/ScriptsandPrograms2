<?php

require('../prepend.inc.php');
$reported=report_all();

?>
<?
include("../templates/admin-header.txt");
?>
<table border="1" cellspacing="1" cellpadding="0" bordercolor="#000000">
  <tr align="center">
    <td width="20%"><b>Points</b></td>
    <td width="8%"><b>ID</b></td>
    <td width="6%"><b>URL</b></td>
    <td width="4%"><b>e-mail</b></td>

  </tr>
<?php

for($i=0; $i<count($reported); $i++)
        echo '
  <tr align="center">
    <td width="8%">'.$reported[$i][points].'</td>
    <td width="6%">'.$reported[$i][id].'</td>
    <td width="4%">'.$reported[$i][url].'</td>
     <td width="4%">'.$reported[$i][email].'</td>
  </tr>
        ';

?></TD>
</TR>
</TABLE>
<?
include("../templates/admin-footer.txt");
?>