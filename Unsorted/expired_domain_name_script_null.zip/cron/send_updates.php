<?php
	set_time_limit(0);
	include ("../vars.inc.php");
	$q=new Cdb;
	$q2=new Cdb;
	$q3=new Cdb;
	$query="select distinct member_id from monitor";
	$q->query($query);
	while ($q->next_record())
	{
		
		$query="SELECT * FROM yahoo_dom, monitor
				WHERE monitor.member_id = '".$q->f("member_id")."' 
						AND monitor.domain_id = yahoo_dom.id AND yahoo_dom.status = 1 AND monitor.visited=0";
		$q2->query($query);
		while ($q2->next_record())
		{
			$domains.=$q2->f("domain")." --> DELETED\n";
			$query="update monitor set visited=1 where domain_id='".$q2->f("domain_id")."' and member_id = '".$q->f("member_id")."' ";
			echo $query."\n";
			$q3->query($query);
		}
		if ($domains!='')
		{
			$query="select * from members where id='".$q->f("member_id")."'";
			$q2->query($query);
			$q2->next_record();
			$body=get_setting("notification_body");
			$subject=get_setting("notification_subject");
			$body=str_replace("{list}",$domains,$body);
			$body=str_replace("{sitename}",$sitename,$body);
			mail($q2->f("email"),$subject,$body,"From: $sitename <$webmasteremail>");
		}
	}

?>