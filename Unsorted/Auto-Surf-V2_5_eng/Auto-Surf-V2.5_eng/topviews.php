<?php

include('./prepend.inc.php');

$data=gettopviews();
?>
<?
include("./templates/main-header.txt");
?>
<center>
<TABLE bgcolor="#FFFFFF" bordercolor="#000000" border="1" width="99%" align="center">
  <tr>
    <td><center>Rank</td>
    <td><center>Fist name</td>
    <td><center>Name</td>
    <td><center>URL</td>
    <td><center>Views</td>
  </tr>
<?php

        for($i=0; $i<count($data); $i++)
        {
                echo "
  <tr>
    <td><center><b>".($i+1)."</b></td>
    <td><center>".$data[$i][prename]."</td>
    <td><center>".$data[$i][name]."</td>
    <td><center><a href=\"".$data[$i][url]."\" target=\"_blank\">".$data[$i][url]."</a></td>
    <td><center>".$data[$i][views]."</td>
  </tr>
";
        }

?>
</TD>
</TR>
</TABLE>
<?
include("./templates/main-footer.txt");
?>