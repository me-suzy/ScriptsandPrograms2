<?php
echo '<table align="right"><tr><td class="noboldtitle"><b>logged on as <i>'.$username.'</i></b></td></tr></table><h1>Create New Counter</h1>
To create a counter, you need to do the following things: <ol>
<li>Fill out the following form describing the page to be counted and press the Create Counter button below. Make sure you read the resulting page which is returned. 
<li>Insert somewhere in your web-page (on top, in the middle, or at the bottom) the HTML code which is returned by the creation form. 
<li>Now go ahead and load your home page on your web browser. Your counter should be visible.
<li>I ask you to credit '.$title.' somewhere on your home-page by using its name and/or logo and with a HTML link to the URL on the following page.
</ol>
<form method="post" action="login.php?sav=newc&sessionid='.$sessionid.'">
  <table border="0" width="297" height="16"><input type="hidden" name="newname" value="'.$fname.'"><input type="hidden" name="newemail" value="'.$femail.'">
    <tr>
      <td width="102" height="1" align="right" class="noboldtitle">Web Site Title:</td>
      <td width="179" height="1"><input type="text" name="newwebsitetitle" size="24" value=""></td>
    </tr>
    <tr>
      <td width="102" height="1" align="right" class="noboldtitle">Web Site URL:</td>
      <td width="179" height="1"><input type="text" name="newwebsite" size="24" value="http://"></td>
    </tr>
    <tr>
      <td width="102" height="1" align="right" class="noboldtitle">Starting Count:</td>
      <td width="179" height="1"><input type="text" name="newstartcount" size="10" value="0"></td>
    </tr>
    <tr>
      <td width="102" height="1" align="right" class="noboldtitle">Style:</td>
      <td width="179" height="1"><select name="newstyle">
       <option name="text">text</option>
       <option name="hidden">hidden</option>
       <option name="text">----------------------------</option>
';
$path = "digits";
$dh = opendir($path);
while ($file = readdir($dh)) {
	if (is_dir($path."/".$file)) {
		if (!is_file($path."/".$file)) {
			if (($file!=".") && ($file!="..")) {
				print "       <option name=\"".$file."\">".$file."</option>\n";
			}
		}
	}
}
closedir($dh);
echo '      </td><td><a href="index.php?p=styles" target="_blank" class="noboldtitle">View Styles</a></td>
    </tr>
    <tr>
      <td width="100%" height="1" colspan="2" align="center" class="noboldtitle"><input type="checkbox" name="newaddtowslst" checked>Add to Web Site Listings</td>
    </tr>
    <tr>
      <td width="100%" height="1" align="right" colspan="2">
        <p align="center"><input type="submit" class="button" value="Create Counter">&nbsp;<input type="reset" class="button" value="Reset"></td>
    </tr>
  </table>
</form>
<p>[<a href="login.php?act=home&sessionid='.$sessionid.'">Back to Account Control Panel</a>] 
[<a href="index.php?p=help" target="_blank">Help!</a>] 
[<a href="login.php?act=logout&sessionid='.$sessionid.'">Logout</a>]</p>';
?>