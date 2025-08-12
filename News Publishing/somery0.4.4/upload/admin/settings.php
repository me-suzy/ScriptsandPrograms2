<?php
// Somery, a weblogging script by Robin de Graaf, copyright 2001-2005
// Somery is distributed under the Artistic License (see LICENSE.txt)
//
// ADMIN/SETTINGS.PHP > 03-11-2005

$start = TRUE; 
include("system/include.php"); 
if ($checkauth) { 

 
if ($userdata['level'] >= 3) { 
 
if (!$action) {; ?> 
 
<?php loadsettings(); ?> 

<strong>System settings</strong><br />

<table><tr><td> 
<form method="post" action="settings.php"> 
<input type="hidden" name="action" value="update"> 
</td></tr></table> 
 
<table> 
<tr><td width=175>Skin</td><td><select name='set_skindir'> 
<?php 
	$dir ="skins/"; 
	$handle=opendir($dir); 
	$i=0; 
	while ($file = readdir($handle)) { 
	      $skins[$i]=$file; 
	      $i=$i+1; 
	} 
	closedir($handle); 
	arsort($skins); 
	 
	$e = "1"; 
	while ($e != ($i-1)) { 
		$setskin = ereg_replace("skins","",$skindir); 
		$setskin = ereg_replace("/","",$setskin); 
		if ($skins[$i-$e] == $setskin) {  
			echo "<option value='".$skins[$i-$e]."' selected>".$skins[$i-$e];  
		} else {  
			echo "<option value='".$skins[$i-$e]."'>".$skins[$i-$e];  
		} 
		$e++; 
	} 
?> 
</select></td></tr> 
<tr><td width=175>Start level</td><td><select name='set_startlevel'> 
<?php 
	$e = "0"; 
	while ($e < 4) { 
		if ($e == $settings['startlevel']) {  
			echo "<option value='$e' selected>level $e";  
		} else {  
			echo "<option value='$e'>level $e";  
		} 
		$e++; 
	} 
?> 
</select></td></tr> 
<tr><td width=175>Posts start status</td><td><select name='set_startstatus'> 
<?php 
	$es[0] = "hidden"; 
	$es[1] = "visible"; 
	$e = "0"; 
	while ($e < 2) { 
		if ($e == $settings['startstatus']) {  
			echo "<option value='$e' selected>$es[$e]";  
		} else {  
			echo "<option value='$e'>$es[$e]";  
		} 
		$e++; 
	} 
?> 
</select></td></tr> 
<tr><td width=175>Allow registration</td><td> 
<?php 
	$e = "0"; 
	echo "<input type='checkbox' name='set_registration' "; 
		if ($settings['registration'] == 1) echo "CHECKED";  
	echo ">"; 
?> 
</td></tr> 
<tr><td width=175>Allow comments</td><td> 
<?php 
	$e = "0"; 
	echo "<input type='checkbox' name='set_comments' "; 
		if ($settings['comments'] == 1) echo "CHECKED";  
	echo ">"; 
?> 
</td></tr> 
<tr><td width=175>Posts per page</td><td><input size=3 name='set_noposts' type='text' value='<?php echo $settings[noposts];?>'></td></tr>
<tr><td width=175>Posts in archive</td><td><input size=3 name='set_archive' type='text' value='<?php echo $settings[archive];?>'> 0 shows all</td></tr>
<tr><td width=175>More text</td><td><input size=50 name='set_more' type='text' value='<?php echo $settings[more];?>'></td></tr>
<tr><td width=175>No comments text</td><td><input size=50 name='set_nocomments' type='text' value='<?php echo $settings[nocomments];?>'></td></tr> 
<tr><td width=175>Time difference</td><td><input size=3 name='set_gmt' type='text' value='<?php echo $settings[gmt];?>'> server time: <?php echo date("H:i:s"); ?></td></tr>
</table><br> 
 
<strong>Confirm</strong><br /><br />
<table> 
<tr><td width=175>Save changes</td><td><input type='submit' value='proceed'></td></tr> 
</table> 
 
<?php } elseif ($action == "update") { 
	if ($set_registration == "on") { $set_registration = "1"; } else { $set_registration = "0"; } 
	if ($set_comments == "on") { $set_comments = "1"; } else { $set_comments = "0"; } 
	$result = mysql_query("UPDATE ".$prefix."settings SET  
		skindir='$set_skindir', 
		startlevel='$set_startlevel', 
		gmt='$set_gmt', 
		startstatus='$set_startstatus', 
		registration='$set_registration', 
		comments='$set_comments', 
		noposts='$set_noposts', 
		archive='$set_archive', 
		more='$set_more', 
		nocomments='$set_nocomments'"); 
	echo "<meta http-equiv=Refresh content=0;URL='settings.php'>"; 
}; 
 
}; ?> 
 
 
 
 
<?php }; $start = FALSE; include("system/include.php"); ?>