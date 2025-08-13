<?php
	include ("vars.inc.php");

	class CDbc extends DB_Sql 
	{
		var $classname = "CDbc";
		var $Host="localhost";
		var $Database="cybercan_dm";
		var $User="cybercan_dm";
		var $Password="trinity";


		function haltmsg($msg) 
		{
			printf("</td></table><b>Database error:</b> %s<br>\n", $msg); printf("<b>MySQL Error</b>: %s (%s)<br>\n",$this->Errno, $this->Error);
			printf("Please contact ionut@yourdomain.com and report the ");
			printf("exact error message.<br>\n");
		}
	}
	set_time_limit(0);
	$q=new Cdb;
	$q2=new Cdbc;
/*	$query="select * from google_cat";
	$q2->query($query);
	echo "***************<br>";
	$k=0;
	while ($q2->next_record())
	{
		$k++;
		if ($k % 100 == 0) echo $k;
		$query="insert into google_cat (id, name) values (NULL, '".$q2->f("name")."')";
		$q->query($query);
	}
	$query="select * from yahoo_cat";
	$q2->query($query);
	echo "***************<br>";
	$k=0;
	while ($q2->next_record())
	{
		$k++;
		if ($k % 100 == 0) echo $k;
		$query="insert into yahoo_cat (id, name) values (NULL, '".$q2->f("name")."')";
		$q->query($query);
	}
	$query="select * from dmoz_cat";
	$q2->query($query);
	echo "***************<br>";
	$k=0;
	while ($q2->next_record())
	{
		$k++;
		if ($k % 100 == 0) echo $k;
		$query="insert into dmoz_cat (id, name) values (NULL, '".$q2->f("name")."')";
		$q->query($query);
	}*/
	$query="select * from yahoo_dom";
	$q2->query($query);
	$k=0;
	while ($q2->next_record())
	{
		$k++;
		if ($k % 1000 == 0) echo $k;
		$query="insert into yahoo_dom (id, domain, exp_date, updt_date, status) values (NULL, '".$q2->f("domain")."', '".$q2->f("exp_date")."', '".$q2->f("updt_date")."', '".$q2->f("status")."')";
		$q->query($query);
	}

?>