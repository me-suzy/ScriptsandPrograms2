<?php
echo '<h1>Delete Counter</h1><form method="post" action="login.php?sav=ecntr&sessionid='.$sessionid.'">
  <table border="0" width="100%" height="16"><input type="hidden" name="btn" value="Delete"><input type="hidden" name="siteid" value="'.$siteid.'">
    <tr>
      <td width="100%" height="1" class="ss-1">Are you sure you want to delete counter <i>'.$siteid.'</i>?<br><br></td>
    </tr>
    <tr>
      <td width="100%" height="1" align="right">
        <p align="center"><input type="submit" class="button" value="Yes">&nbsp;&nbsp;<input type="button" class="button" value="No" onclick="javascript:window.location=(\'login.php?act=home&sessionid='.$sessionid.'\')"></td>
    </tr>
  </table>
</form>
<p>[<a href="login.php?act=home&sessionid='.$sessionid.'">Back to Account Control Panel</a>] 
[<a href="index.php?p=help" target="_blank">Help!</a>] 
[<a href="login.php?act=logout&sessionid='.$sessionid.'">Logout</a>]</p>';
?>