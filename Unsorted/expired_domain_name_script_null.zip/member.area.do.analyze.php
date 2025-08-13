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
	FFileRead("template.member.area.do.analyze.htm",$cmember);
	FFileRead("template.member.area.do.analyze.row.htm",$row);
	if (!isset($page)) $page=1;
	
	$qd=new CdbD;


		$list="";
		$list=HTTP_Post($cat,"");
		while ($list!="")
		{
			$list=strstr($list,"http:");
			$list=substr($list,7);
			$pos=strpos($list,">");
			$slist=substr($list,0,$pos);
			$pos=strpos($slist,".com");
			if ($pos===false) 
			{
				$pos=strpos($slist,".net");
				if ($pos===false) 
				{			
					$pos=strpos($slist,".org");
				}
			}
			if (!($pos===false))
			{
				$tocheck=substr($list,0,$pos+4);
				$n_ar=array('"',"'", ' ', '@', '>', '<','/','\\','*','(',')','`','!','#','$','%','^','&','+','|',',', ':', ';' ,'?', '[', ']', '{','}');	
				for ($i=strlen($tocheck)-1; $i>=0; $i--)
				{
					if (in_array ($tocheck[$i],$n_ar )) 
					{
						//echo $tocheck."  aaaaaaa\n";
						$tocheck=substr($tocheck,$i+1);
						break;
					}
				}
				$n=substr_count($tocheck,".");
						if ($n>1)
						{
							for ($i=1; $i<$n; $i++)
							{

								$pos=strpos($tocheck,".");
								$tocheck=substr($tocheck,$pos+1);
							}
						}
					$b=0;
					//echo $tocheck."\n";
					$b=1;
					if ($tocheck=="yahoo.com" || 
						$tocheck=="google.com" || 
						$tocheck=="yimg.com" || 
						$tocheck=="dmoz.org" ) $b=0;
					if (($b==1))
					{

								check_domain($tocheck, $status, $ed, $ud);
								//echo "       ".$tocheck."|".$status."|".$ud."|".$ed."\n";
								$rows.=str_replace("{domain}",$tocheck,$row);
								
								if ($ed==0)
								{
									$expdate="N/A";
								}
								else
								{
									$expdate=date("m-d-Y",strtotime($ed));
								}
								
								switch($status)
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
		
					}//end if $b
				
			}//end if $pos 
	}//end while



	

			
	$last=$start+$k;
	$time=substr($time,0,6);
	$cmember=str_replace("{rows}",$rows,$cmember);
	FFileRead("template.member.area.main.htm",$content);
	$content=str_replace("{content}",$cmember,$content);

?>