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
	FFileRead("template.member.area.do.search.domains.htm",$cmember);
	FFileRead("template.member.area.do.search.domains.row.htm",$row);
	if (!isset($page)) $page=1;
	
	$qd=new CdbD;
	
	$query="select * from yahoo_dom where ";
	$qcount="select count(*) as n from yahoo_dom where ";
	
	$set_date=date("Ymd");	
	
	switch ($domains)
	{
		case 1: $qadd=" domain like '%.com%' and ";
				break;
		case 2: $qadd=" domain like '%.net%' and ";
				break;
		case 3: $qadd=" domain like '%.org%' and ";
				break;
		case 4: $qadd=" ";
				break;
	}
	
	$query.=$qadd;
	$qcount.=$qadd;
	
	switch ($status)
	{
		case 1: $qadd=" status = 2 and ";
				$set_date=date("Ymd",mktime(0,0,0,date("m"),date("d")+$when,date("Y")));	
				break;
		case 2: $qadd=" status = 1 and exp_date <= '".date("Ymd")."' and ";
				if ($dph!=0)
				{	
					$ste=date("Ymd",mktime(0,0,0,date("m"),date("d")-$dph,date("Y")));	
					$qadd.=" exp_date >= $ste and ";
				}
				break;
		case 3: $qadd=" status = 3 and exp_date <= '".date("Ymd")."' and ";
				if ($dph!=0)
				{	
					$ste=date("Ymd",mktime(0,0,0,date("m"),date("d")-$dph,date("Y")));	
					$qadd.=" exp_date >= $ste and ";
				}
				break;
		case 5: $qadd=" ( status = 3 or status = 1) and exp_date <= '".date("Ymd")."' and ";
				if ($dph!=0)
				{	
					$ste=date("Ymd",mktime(0,0,0,date("m"),date("d")-$dph,date("Y")));	
					$qadd.=" exp_date >= $ste and ";
				}
				break;
	}

	$query.=$qadd;
	$qcount.=$qadd;
	
	if ($hyphens=="1") 
		$qadd=" ( domain not like '%-%') and ";

	$query.=$qadd." exp_date <> 19691231 and ";
	$qcount.=$qadd." exp_date <> 19691231 and ";

	if ($numbers=="1")
		$qadd="
		(domain not like '%1%') and
		(domain not like '%2%') and
		(domain not like '%3%') and
		(domain not like '%4%') and
		(domain not like '%5%') and
		(domain not like '%6%') and
		(domain not like '%7%') and
		(domain not like '%8%') and
		(domain not like '%9%') and
		(domain not like '%0%') and
		  ";

	$query.=$qadd;
	$qcount.=$qadd;

	switch ($where)
	{
		case 1: $qadd=" domain like  '".$search."%' ";
				break;
		case 2: $qadd=" domain like  '%".$search."' ";
				break;
		case 3: $qadd=" domain like  '%".$search."%' ";
				break;
	}
	
	$qadd.=' and exp_date <= '.$set_date.' ';
	
	$start=($page-1)*30;

	$query.=$qadd." order by domain limit $start,30";
	$qcount.=$qadd;
	
	//echo $query;
	$qd->query($qcount);
	$qd->next_record();
	$n=$qd->f("n");
	
	$time_start = getmicrotime();

	$qd->query($query);
	echo $query; 
	$time_end = getmicrotime();
	$time = $time_end - $time_start;
	
	$k=0;
	while ($qd->next_record())
	{
		$k++;
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
	
	$addtolink=	"domains=".urlencode($domains).
				"&status=".urlencode($status).
				"&search=".urlencode($search).
				"&when=".urlencode($when).
				"&hyphens=".urlencode($hyphens).
				"&numbers=".urlencode($numbers).
				"&dph=".urlencode($dph).
				"&where=".urlencode($where);
	if ($n/30 - (int) ($n/30) != 0) $nmax=(int)($n/30)+1; 
		else 
	 $nmax=$n/30; 
	 
	if ($page>1) $prev=" [ <a href='index.php?action=do_search_domains&page=".($page-1)."&".$addtolink."'><< Prev</a> ]";
	if ($page<$n/30) $next=" [ <a href='index.php?action=do_search_domains&page=".($page+1)."&".$addtolink."'>Next >></a> ]";
	$first=" [ <a href='index.php?action=do_search_domains&page=1&".$addtolink."'>First</a> ]";
	$lastl=" [ <a href='index.php?action=do_search_domains&page=".((int) $nmax)."&".$addtolink."'>Last</a> ]";
			
	$last=$start+$k;
	$time=substr($time,0,6);
	$cmember=str_replace("{rows}",$rows,$cmember);
	$cmember=str_replace("{time}",$time,$cmember);
	$cmember=str_replace("{first}",$start+1,$cmember);
	$cmember=str_replace("{last}",$last,$cmember);
	$cmember=str_replace("{total}",$n,$cmember);
	$cmember.="<br> <div align=center>".$first.$prev.$next.$lastl." </div><br>";
	FFileRead("template.member.area.main.htm",$content);
	$content=str_replace("{content}",$cmember,$content);

?>