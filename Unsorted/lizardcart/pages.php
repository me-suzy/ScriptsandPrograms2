<?php
include ("config.inc.php");
$dbResult = mysql_query("select * from pages where id='$id'");
$prow=mysql_fetch_object($dbResult);
?>

<? include ("header.php");?>

    <table width="700" border="1" cellspacing="5" cellpadding="5" bordercolor="#3366CC">
      <tr> 
        <td width="100%" height="50" bgcolor="#3366CC"> 
         <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#FFFFFF"><b><? echo "$prow->page_title"?></b></div>
          
        </td>
		</tr>
		<tr>
		<td>
		<? echo "$prow->page_content"?>
		</td>
      </tr>
    </table>


<? include ("footer.php");?>
