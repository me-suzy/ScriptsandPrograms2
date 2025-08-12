<?
include("./templates/main-header.txt");
?>
<br><br><center><b>Sponsor-Login</b></center><br><br>

<form method="post" action="./sponsorlogin2.php">
  <table width="170" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
      <td><font size="2" color="#000080"><b>UserName:</b>&nbsp;</td>
      <td>
        <input type="text" name="username" size="11">
      </td>
    </tr>
    <tr>
      <td colspan="2" align="center">
        <input type="submit" value="Login"><br><br><a href="sppasswort.php"><font size="2" color="red">Lost password??</a></font><br><br>
      </td>
    </tr>
  </table></from>
<?
include("./templates/main-footer.txt");
?>