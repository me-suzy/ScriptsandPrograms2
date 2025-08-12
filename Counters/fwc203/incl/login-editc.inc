<?php
echo '<h1>Edit Counter Details</h1><form method="post" action="login.php?sav=ecntr&sessionid='.$sessionid.'">
  <table border="0" width="297" height="16"><input type="hidden" name="btn" value="Edit"><input type="hidden" name="siteid" value="'.$siteid.'">
    <tr>
      <td width="102" height="1" align="right" class="boldtitle">Site ID:</td>
      <td width="179" height="1" class="boldtitle">'.$siteid.'</td>
    </tr>
    <tr>
      <td width="102" height="1" align="right" class="noboldtitle">Web Site URL:</td>
      <td width="179" height="1"><input type="text" name="newwebsite" size="24" value="'.$url.'"></td>
    </tr>
    <tr>
      <td width="102" height="1" align="right" class="noboldtitle">Count:</td>
      <td width="179" height="1"><input type="text" name="newstartcount" size="14"  value="'.$count.'"></td>
    </tr>
    <tr>
      <td width="102" height="1" align="right" class="noboldtitle">Style:</td>
      <td width="179" height="1"><select name="newstyle">
       <option name="text"'; if ($style=="text") { echo ' selected'; } echo'>text</option>
       <option name="hidden"'; if ($style=="hidden") { echo ' selected'; } echo'>hidden</option>
       <option name="text">----------------------------</option>
';
$path = "digits";
$dh = opendir($path);
while ($file = readdir($dh)) {
	if (is_dir($path."/".$file)) {
		if (!is_file($path."/".$file)) {
			if (($file!=".") && ($file!="..")) {
				echo "       <option name=\"".$file."\"";
				if ($style==$file) { echo " selected"; }
				echo ">".$file."</option>\n";
			}
		}
	}
}
closedir($dh);
echo '      </td><td><a href="index.php?p=styles" target="_blank" class="ss-2">View Styles</a></td>
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