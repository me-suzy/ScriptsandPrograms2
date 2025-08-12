<?php
echo '<table align="right"><tr><td class="noboldtitle"><b>logged on as <i>'.$username.'</i></b></td></tr></table><h1>Edit Account Settings</h1><form method="post" action="login.php?sav=edita&sessionid='.$sessionid.'">
  <table border="0" width="297" height="16">
    <tr>
      <td width="102" height="1" align="right" class="boldtitle">Username:</td>
      <td width="179" height="1" class="ss-1">'.$username.'</td>
    </tr>
    <tr>
      <td width="102" height="1" align="right" class="boldtitle">Passsword:</td>
      <td width="179" height="1"><input type="text" name="newpassword" size="20" value="'.$password.'"></td>
    </tr>
    <tr>
      <td width="102" height="1" align="right" class="noboldtitle">Name:</td>
      <td width="179" height="1"><input type="text" name="name" size="24" value="'.$fname.'"></td>
    </tr>
    <tr>
      <td width="102" height="1" align="right" class="noboldtitle">Email:</td>
      <td width="179" height="1"><input type="text" name="email" size="24" value="'.$femail.'"></td>
    </tr>
    <tr>
      <td width="100%" height="1" align="right" colspan="2">
        <p align="center"><input type="submit" class="button" value="Save Settings">&nbsp;<input type="button" class="button" value="Cancel" onclick="javascript:window.location=\'login.php?act=home&sessionid='.$sessionid.'\'"></td>
    </tr>
  </table>
</form>
<p>[<a href="login.php?act=home&sessionid='.$sessionid.'">Back to Account Control Panel</a>] 
[<a href="index.php?p=help" target="_blank">Help!</a>] 
[<a href="login.php?act=logout&sessionid='.$sessionid.'">Logout</a>]</p>';
?>