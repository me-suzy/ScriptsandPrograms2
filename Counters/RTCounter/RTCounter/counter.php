<?php
//User counter
$file = fopen("counter.txt","r+");
$EXPIRE_DATE = 600;
$count = fread($file, filesize("counter.txt"));
fclose($file);
	if ($tivisited == "") {
		$count += 1;
		$file = fopen("counter.txt","w+");
		fputs($file, $count);
		fclose($file);
		setcookie("tivisited", "RTcounter cookie", time()+$EXPIRE_DATE , "/", $SERVER_NAME);
		}
?>