<?php
echo '<table align="right"><tr><td class="noboldtitle"><b>logged on as <i>'.$username.'</i></b></td></tr></table><h1>Create New Counter - Finished!</h1>
Congratulations <b>'.$fname.'</b>!<br>You have successfully created the following counter with a starting value of '.$newstartcount.':

<p><pre>        Site ID: '.$newsiteid.'</pre></p>

Use something like the following SCRIPT entry to reference your new counter.<br><br>

<b>Display Total Hits:</b><br><textarea cols=47 rows=5><!-- Begin '.$title.' Code -->
<script language="JavaScript" src="'.$getcountpath.'?id='.$newsiteid.'"></script>
<!-- End '.$title.' Code --></textarea><br><br>

<b>Please note:</b> If you are using my free counter service, all I ask is that you credit '.$title.' 
somewhere on your home page by using its name and a HTML link to the following URL:<p>

<textarea cols=47 rows=3><a href="'.$linkpath.'" target="_blank">Get your own free hit counter from '.$title.'!</a></textarea></p>

<p><b>What next?</b><br>
[<a href="login.php?act=home&sessionid='.$sessionid.'">Back to Account Control Panel</a>]<br>
[<a href="index.php?p=help" target="_blank">Help!</a>]<br>
[<a href="login.php?act=logout&sessionid='.$sessionid.'">Logout</a>]<br>
[<a href="getcount.php?id='.$newsiteid.'">View New Counter in Action</a>]</p>';
?>