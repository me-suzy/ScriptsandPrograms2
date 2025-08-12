<?php
echo '<table align="right"><tr><td class="noboldtitle"><b>logged on as <i>'.$username.'</i></b></td></tr></table><h1>Welcome back '.$fname.'!</h1>This is your Account Control Panel, from here you can edit/delete/view your counters. At anytime you need help, click the <i>[Help!]</i> link at the bottom of every page in your Account Control Panel.
  <table border="0" width="100%" height="16">
    <tr>
      <td align="right" colspan=2 class="ss-2">[<a href="login.php?act=logout&sessionid='.$sessionid.'">Logout</a>]<br>
      </td>
    </tr>
    <tr>
      <td align="left" colspan=2 class="bigtitle">Account Details:<br>
      </td>
    </tr>
    <tr>
      <td width="20%" height="1" align="right" class="boldtitle"><b>Username:</b></td>
      <td width="80%" height="1" class="bluetitle">'.$username.'</td>
    </tr>
    <tr>
      <td width="20%" height="1" align="right" class="boldtitle"><b>Password:</b></td>
      <td width="80%" height="1" class="bluetitle">********</td>
    </tr>
    <tr>
      <td height="20" colspan=2 align="center"><input type="submit" class="button" value="Edit Account Details" name="editaccountd" onclick="javascript:window.location=\'login.php?act=edita&sessionid='.$sessionid.'\'"><br><br></td>
    </tr>
    <tr>
      <td align="left" colspan=2 class="bigtitle">Manage Counters:<br>
        <input type="submit" class="button" value="Create New Counter" name="newcounter" onclick="javascript:window.location=\'login.php?act=newc&sessionid='.$sessionid.'\'">&nbsp;'; if ($username=="demo") { echo '<br><br><i><font color="#828282" size="1">(View Statistics will display the stats<br>for the '.$title.' front page)</font></i><br>'; } echo '<input type="button" class="button" value="View Statistics" onclick="javascript:window.location=\'login.php?act=stats&sessionid='.$sessionid.'\'"><br><br>
      </td>
    </tr>
    <tr>
      <td align="left" colspan=2 class="bigtitle"><form method="post" action="login.php?act=ecntr&sessionid='.$sessionid.'">Edit Counter Settings:<br>'; if ($maxcounters!=0) { echo '<font class="noboldtitle">Counters left: <b>'.$countersleft.'</b></font><br>'; } echo '
        <select name="siteid">';
	if ($num_siteids < 2) { echo "\n         <option>No active counters</option>"; }
      	for ($i = 0; $i < $num_siteids; ++$i) {
      		if ($siteid[$i]) {
     		echo "\n         <option>".$siteid[$i]."</option>"; }
	}
echo '
        </select>
        '; if ($num_siteids > 1) { echo '<input type="submit" class="button" value="Edit" name="btn">&nbsp;<input type="submit" class="button" value="Delete" name="btn">&nbsp;<input type="submit" class="button" value="HTML Code" name="btn">'; } echo '</form>
      </td>
    </tr>
  </table>';
?>