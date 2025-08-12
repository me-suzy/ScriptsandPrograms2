<?php
echo '<table align="right"><tr><td class="noboldtitle"><b>logged on as <i>'.$username.'</i></b></td></tr></table><h1>Statistics</h1>
  <table border="0">
    <tr>
      <td colspan="2"><form method="post" action="login.php?sav=stats&sessionid='.$sessionid.'">Select a site ID:</td>
    </tr>
    <tr>
      <td>
        <select name="siteid" size="7">';
      	for ($i = 1; $i <= $num_siteids; ++$i) {
      		if ($siteid[$i]) {
     		echo "\n         <option"; if ($i==1) { echo " selected"; } echo ">".$siteid[$i]."</option>"; }
	}
echo '
        </select>
     </td>
      <td valign="top">
        <input type="submit" class="button" value="View Stats"><br><br><input type="button" class="button" value="Cancel" onclick="javascript:window.location=\'login.php?act=home&sessionid='.$sessionid.'\'"></td>
    </tr>
  </table>
</form>
<p>[<a href="login.php?act=home&sessionid='.$sessionid.'">Back to Account Control Panel</a>] 
[<a href="index.php?p=help" target="_blank">Help!</a>] 
[<a href="login.php?act=logout&sessionid='.$sessionid.'">Logout</a>]</p>';
?>