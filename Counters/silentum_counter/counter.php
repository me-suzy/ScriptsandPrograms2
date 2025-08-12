<?
	/*
	Silentum Counter v1.0.3
	counter.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	$counter = fopen("counter/counter.txt", "r");
	$total = fread($counter, 1024);
	fclose($counter);
	$total = $total + 1;
	$counter = fopen("counter/counter.txt", "w");
	fwrite($counter, $total);
	fclose($counter);
?>