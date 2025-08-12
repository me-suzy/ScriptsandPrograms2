<?php
echo '<table align="right"><tr><td class="noboldtitle"><b>logged on as <i>'.$username.'</i></b></td></tr></table><h1>HTML Code</h1>
Use something like the following SCRIPT entry to reference your new counter.<br><br>

<b>Display Total Hits:</b><br><textarea cols=47 rows=5><!-- Begin '.$title.' Code -->
<script language="JavaScript" src="'.$getcountpath.'?id='.$siteid.'"></script>
<!-- End '.$title.' Code --></textarea><br><br>

<b>Please note:</b> If you are using my free counter service, all I ask is that you credit '.$title.' 
somewhere on your home page by using its name and a HTML link to the following URL:<p>

<textarea cols=47 rows=3><a href="'.$linkpath.'" target="_blank">Get your own free hit counter from '.$title.'!</a></textarea></p>

<p>[<a href="login.php?act=home&sessionid='.$sessionid.'">Back to Account Control Panel</a>] 
[<a href="index.php?p=help" target="_blank">Help!</a>] 
[<a href="login.php?act=logout&sessionid='.$sessionid.'">Logout</a>] 
[<a href="getcount.php?id='.$siteid.'">View Counter in Action</a>]</p>';
?>