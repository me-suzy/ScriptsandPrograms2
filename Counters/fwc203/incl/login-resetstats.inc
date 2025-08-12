<?php
echo '<table align="right"><tr><td class="noboldtitle"><b>logged on as <i>'.$username.'</i></b></td></tr></table><h1>Statistics - Reset Stats</h1><form method="post" action="login.php?sav=resetstats&sessionid='.$sessionid.'">
  <table border="0" width="100%" height="16"><input type="hidden" name="siteid" value="'.$siteid.'">
    <tr>
      <td width="100%" height="1" class="ss-1">All daily reports and graphs for <i>'.$siteid.'</i> will be wiped completly. Are you sure you want to continue?<br><br></td>
    </tr>
    <tr>
      <td width="100%" height="1" align="right">
        <p align="center"><input type="submit" class="button" value="Yes">&nbsp;&nbsp;<input type="button" class="button" value="No" onclick="javascript:history.go(-1)"></td>
    </tr>
  </table>
</form>
<p>[<a href="login.php?act=home&sessionid='.$sessionid.'">Back to Account Control Panel</a>] 
[<a href="index.php?p=help" target="_blank">Help!</a>] 
[<a href="login.php?act=logout&sessionid='.$sessionid.'">Logout</a>]</p>';
?>