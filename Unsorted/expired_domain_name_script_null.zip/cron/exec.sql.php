<?php
	include ("vars.inc");
	set_time_limit(0);
	$q=new Cdb;
	FFileRead("exp-cat.sql",$c);
	$a=explode(";",$c);
	$i=0;
	while ($a[$i]!="")
	{
		
		$q->query($a[$i]);
		$i++;

	}
?>