<?php
	if (!eregi("index.php", $PHP_SELF)) 
	{
    	die ("You can't access this file directly...");
	}
	if (!isset($sess_id))
	{
			header("Location: index.php?action=sign_up&error_sign_in=".urlencode("Please login first."));
	}
	$cmember="";
	FFileRead("template.member.area.monitor.domains.htm",$cmember);
	FFileRead("template.member.area.monitor.domains.row.htm",$row);
	if (!isset($page)) $page=1;
	
	$qd=new CdbD;
	
	$query="select * from monitor where member_id='$sess_id' ";


	$q->query($query);
	
	$k=0;
	while ($q->next_record())
	{
		$k++;
		$query="select * from yahoo_dom where id='".$q->f("domain_id")."'";
		$qd->query($query);
		$qd->next_record();
		$rows.=str_replace("{domain}",$qd->f("domain"),$row);
		
		if ($qd->f("exp_date")==0)
		{
			$expdate="N/A";
		}
		else
		{
			$expdate=date("m-d-Y",strtotime($qd->f("exp_date")));
		}
		
		switch($qd->f("status"))
		{
			case 1: $stat="Deleted";
					break;
			case 2: $stat="Registered";
					break;
			case 3: $stat="On Hold";
					break;
		}
		$rows=str_replace("{status}",$stat,$rows);
		$rows=str_replace("{date}",$expdate,$rows);
		$rows=str_replace("{id}",$qd->f("id"),$rows);
		
	}
			
	$last=$start+$k;
	$cmember=str_replace("{rows}",$rows,$cmember);
	FFileRead("template.member.area.main.htm",$content);
	$content=str_replace("{content}",$cmember,$content);

?>