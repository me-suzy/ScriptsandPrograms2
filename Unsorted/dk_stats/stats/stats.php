<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr> 
    <td>Today </td>
    <td align="right">
<?
include("connect.php");
include("counter.php");
$userstoday = number_format($userstoday, 0, '', '.');
echo($userstoday);
?>
    </td>
  </tr>
  <tr> 
    <td>Total </td>
    <td align="right">
<?
$userstotal = number_format($userstotal, 0, '', '.');
echo($userstotal);
?>
    </td>
  </tr>
</table>
<?
mysql_close();
?>
