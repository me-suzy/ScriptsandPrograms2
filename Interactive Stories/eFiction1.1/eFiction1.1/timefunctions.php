<?php

	if($dateformat == "1")
		$datum = "%m/%d/%y";
	else if($dateformat == "2")
		$datum = "%m/%d/%Y";
	else if($dateformat == "3")
		$datum = "%d/%m/%Y";
	else if($dateformat == "4")
		$datum = "%d %M %Y";
	else if($dateformat == "5")
		$datum = "%d.%m.%y";
	else if($dateformat == "6")
		$datum = "%Y.%m.%d";
	else if($dateformat == "7")
		$datum = "%m.%d.%Y";					
	else if($dateformat == "8")
		$datum = "%d-%m-%y";
	else if($dateformat == "9")
		$datum = "%m-%d-%y";
	else if($dateformat == "10")
		$datum = "%M %d %Y";
		
	if($newsdate == "1")
		$datim = "%m/%d/%y";
	else if($newsdate == "2")
		$datim = "%m/%d/%Y";
	else if($newsdate == "3")
		$datim = "%d/%m/%Y";
	else if($newsdate == "4")
		$datim = "%d %M %Y";
	else if($newsdate == "5")
		$datim = "%d.%m.%y";
	else if($newsdate == "6")
		$datim = "%Y.%m.%d";
	else if($newsdate == "7")
		$datim = "%m.%d.%Y";					
	else if($newsdate == "8")
		$datum = "%d-%m-%y";
	else if($newsdate == "9")
		$datim = "%m-%d-%y";
	else if($newsdate == "10")
		$datim = "%M %d %Y";	
		
?>