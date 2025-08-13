<?php
	set_time_limit(21600);
	
	include ("vars.inc");
	$q=new Cdb;
	$q2=new Cdb;
	$today=date("Ymd");
	$k=0;
	$query="select id, domain from yahoo_dom where exp_date<$today order by exp_date asc";
	echo $query."\n";
	$q->query($query);
	while ($q->next_record())
	{
			$k++;
			echo $k;
			$tocheck=$q->f("domain");
			check_domain($tocheck, $status, $ed, $ud);
			echo "       ".$tocheck."|".$status."|".$ud."|".$ed."\n";
			
			$query="select status from yahoo_dom where id='".$q->f("id")."'";
			$q2->query($query);
			$q2->next_record();
			if ($q2->f("status")!=$status)
			if ($status == 1)
			{
				$query="update yahoo_dom set exp_date='".date("Ymd")."', status='$status' where id='".$q->f("id")."'";
			}
			else
				$query="update yahoo_dom set exp_date='$ed', status='$status' where id='".$q->f("id")."'";

			$q2->query($query);

	}
?>