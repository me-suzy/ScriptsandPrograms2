<iframe src="plug.php" name="ysplug" width="220" height="125" frameborder="0"></iframe>
<br>
<font size="1"><a href="http://www.yoursite.nu/plugboard" target="_blank">Powered by ysPlugboard</a></font>
<form method="post" action="plug.php?action=plug" target="ysplug"> 
<input type="hidden" value="<?=$REMOTE_ADDR?>" name="ip">
<table border="0" cellspacing="0" cellpadding="3">
  <tr> 
    <td>URL</td>
    <td><input type="text" name="url"></td>
    <td rowspan="2"><input type="submit" value="Plug"></td>
  </tr>
  <tr> 
    <td>Button</td>
    <td><input type="text" name="button"></td>
  </tr>
</table>
</form>