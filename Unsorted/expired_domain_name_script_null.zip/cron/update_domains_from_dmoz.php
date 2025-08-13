<?php
	set_time_limit(0);
	include ("../vars.inc.php");
	$q=new Cdb;
	$q2=new Cdb;
	$query="select * from dmoz_cat";
	$q->query($query);
	while ($q->next_record())
	{
		echo $q->f("id")." ";
		$list="";
		$list=HTTP_Post($q->f("name"),"");
		////echo $list;
		while ($list!="")
		{
			$list=strstr($list,"\"http:");
			$list=substr($list,8);
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
					
					if (($b==1))
					{
						$query="select count(*) as n from yahoo_dom where domain='$tocheck' LIMIT 0,1";
						$q2->query($query);
						$q2->next_record();
						//echo "Is : ".$q2->f("n")."  Dom:: $tocheck "."\n";
							if ($q2->f("n")==0)
							{
								check_domain($tocheck, $status, $ed, $ud);
								//echo "       ".$tocheck."|".$status."|".$ud."|".$ed."\n";
								$query="insert into yahoo_dom (id, domain, exp_date, updt_date, status) values (NULL, '$tocheck', '".$ed."', '".$ud."', '$status')";
								$q2->query($query);
							}//end if $q
					}//end if $b
				
			}//end if $pos 
	}//end while
	}//end big while


?>