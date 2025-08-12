<?php

// Hardened referrers version 0.4-modified
// By Marco van Hylckama Vlieg, 
// marco@i-marco.nl
// 
// Some changes by Bob den Otter, bob@pivotlog.net

include_once("hr_conf.php");

$sKeyName = MD5(__SECRET__.time());
if(strstr($_SERVER["HTTP_REFERER"], __MYDOMAIN__) != false)  {
	touch("$refkeydir/$sKeyName");
}
echo "var refkey='$sKeyName';";


// delete keys older than 10 seconds
$nNow = time();
$handle=opendir("$refkeydir");
while (false!==($file = readdir($handle))) {
	if ($file != "." && $file != ".." && $file != "salt.php") {
		$Diff = ($nNow - filectime("$refkeydir/$file"));
		if ($Diff > 10)
			unlink("$refkeydir/$file");

	}
}
closedir($handle);

?>
