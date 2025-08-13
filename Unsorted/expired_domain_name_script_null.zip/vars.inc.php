<?php
	include("./db_mysql.inc");
	
	$rootpath="./";
	$sitename="swishfiles.com";
	$webmasteremail="webmaster@swishfiles.com";


	class CDb extends DB_Sql 
	{
		var $classname = "CDb";
		var $Host="localhost";
		var $Database="swish_e";
		var $User="swish_e";
		var $Password="trinity";


		function haltmsg($msg) 
		{
			printf("</td></table><b>Database error:</b> %s<br>\n", $msg); printf("<b>MySQL Error</b>: %s (%s)<br>\n",$this->Errno, $this->Error);
			printf("Please contact ionut@yourdomain.com and report the ");
			printf("exact error message.<br>\n");
		}
	}
	class CDbD extends DB_Sql 
	{
		var $classname = "CDbD";
		var $Host="localhost";
		var $Database="swish_e";
		var $User="swish_e";
		var $Password="trinity";


		function haltmsg($msg) 
		{
			printf("</td></table><b>Database error:</b> %s<br>\n", $msg); printf("<b>MySQL Error</b>: %s (%s)<br>\n",$this->Errno, $this->Error);
			printf("Please contact ionut@yourdomain.com and report the ");
			printf("exact error message.<br>\n");
		}
	}
   
    function URLReadw($tocheck)
	{
			$ptr = fsockopen("whois.nsiregistry.net", 43);
			if ($ptr>0) 
			{
				fputs($ptr, "whois =$tocheck\n");
				while(!feof($ptr)) 
				{
					$output.=fgets($ptr, 1024);
				}
			}
			fclose($ptr);
			return $output;
			// passthru("whois -h whois.nsiregistry.net -p 43 =$tocheck",$out);
			// return $out;
	}


	function FFileRead($name/*filename*/, &$contents/*returned contents of file*/)
	{
		$fd = fopen ($name, "r");
		$contents = fread ($fd, filesize ($name));
		fclose ($fd); 
	}
	
	 function FFileRead2($name/*filename*/, &$contents/*returned contents of file*/)
	{
		if (!($fd = fopen ($name, "r"))) return (false);
		while (!feof($fd))
		{
			$contents.= fread ($fd,128);
		}
		fclose ($fd); 
		return true;
	}


       function HTTP_Post($URL,$data, $referrer="") {
         
         // parsing the given URL
         //$URL_Info=parse_url($URL);

         // Building referer
         //if($referer=="") // if not given use this script as referer
         //  $referer=$_SERVER["SCRIPT_URI"];

   
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL,$URL);
         curl_setopt($ch, CURLOPT_HEADER,1);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
         curl_setopt($ch, CURLOPT_REFERER, $referer );
         curl_setopt($ch, CURLOPT_POST,0);
         //curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
         $result=curl_exec ($ch);
         curl_close ($ch);
         return $result;
       }
	   
	
	function get_setting($name)
	{
		$q=new CDb;
		$query="select value from settings where name='$name'";
		$q->query($query);
		if ($q->nf()==0) return -1;
			else
				{
					$q->next_record();
					return $q->f("value");
				}
	}
	function save_setting($name,$value)
	{
		$q=new CDb;
		$query="update settings set value='$value' where name='$name'";
		$q->query($query);
	}

	function getmicrotime()
	{ 
	   list($usec, $sec) = explode(" ",microtime()); 
	   return ((float)$usec + (float)$sec); 
	 } 

	function get_pop_altavista($link)
	{
		FFileRead2("http://altavista.com/web/results?q=link:".urlencode($link)."&kgs=0&kls=1&avkw=qtrp", $content);
		$begin=strpos($content,"AltaVista found ");
		$content=substr($content,$begin+strlen("AltaVista found "));
		$end=strpos($content," results");
		$link_pop=substr($content,0,$end);
		$link_pop=trim($link_pop);
		$link_pop=str_replace(",","",$link_pop);
		return $link_pop;
	}
	
	function get_pop_google($link)
	{
		FFileRead2("http://www.google.com/search?hl=en&ie=UTF-8&oe=UTF-8&q=link:".urlencode($link), $content);
		$begin=strpos($content,"of about <b>");
		$content=substr($content,$begin+strlen("of about <b>"));
		$end=strpos($content,"</b>.");
		$link_pop=substr($content,0,$end);
		$link_pop=trim($link_pop);
		$link_pop=str_replace(",","",$link_pop);
		return $link_pop;
	}

	function get_pop_ms($link)
	{
		FFileRead2("http://search.msn.com/results.asp?RS=CHECKED&FORM=MSNH&v=1&q=linkdomain:".urlencode($link), $content);
		$begin=strpos($content,"Results 1-15 of about ");
		$content=substr($content,$begin+strlen("Results 1-15 of about "));
		$end=strpos($content," containing");
		$link_pop=substr($content,0,$end);
		$link_pop=trim($link_pop);
		$link_pop=str_replace(",","",$link_pop);
		return $link_pop;
	}

	function get_pop_yahoo($link)
	{
		FFileRead2("http://search.yahoo.com/search?p=link%3A".urlencode($link)."&ei=UTF-8&vm=i&n=20&fl=0&x=wrt", $content);
		$begin=strpos($content,"out of about ");
		$content=substr($content,$begin+strlen("out of about "));
		$end=strpos($content,"\n");
		$link_pop=substr($content,0,$end);
		$link_pop=trim($link_pop);
		$link_pop=str_replace(",","",$link_pop);
		return $link_pop;
	}

	function check_domain($tocheck, &$status, &$ed, &$ud)
	{
			if (strpos($tocheck,".org")===false)
			{
								$output=URLReadw($tocheck);
								////echo $output;
								$pos = strpos($output, "No match");
									if ($pos === false) 
									{
										$xpos=strpos($output, "ACTIVE");
										if (!($xpos === false))
										{
											$status = "2";//Registered
											$pos=strpos($output,  "Updated Date:");
											$upddate=substr($output,$pos+14,11);
											$pos=strpos($output,  "Expiration Date:");

											$expdate=substr($output,$pos+17,11);		
										}
										else
										{
											$status = "3"; //On Hold
											$pos=strpos($output,  "Updated Date:");
											$upddate=substr($output,$pos+14,11);
											$pos=strpos($output,  "Expiration Date:");
											$expdate=substr($output,$pos+17,11);																}

									}
									else
									{ 
										$status = "1"; //Available
									}
								$expdate=trim($expdate);
								$upddate=trim($upddate);
								$ed=date("Ymd", strtotime($expdate));
								$ud=date("Ymd", strtotime($upddate));
								if (date("Ymd")<$ed) $status=2;
									if ($status=="1" )
									{
										$expdate="0";
										$upddate="0";
										$ed=0;
										$ud=0;
									}

		}
		else
			{
								exec("whois -h whois.networksolutions.com ".$tocheck, $output);
								$output=implode("",$output);
								//echo $output;
								$pos = strpos($output, "NOT FOUND");
									if ($pos === false) 
									{
										$xpos=strpos($output, "Status:OK");
										if (!($xpos === false))
										{
											$status = "2";//Registered
											$pos=strpos($output,  "Last Updated On:");
											$upddate=substr($output,$pos+16,11);
											$pos=strpos($output,  "Expiration Date:");
											$expdate=substr($output,$pos+16,11);		
										}
										else
										{
											$status = "3"; //On Hold
											$pos=strpos($output,  "Last Updated On:");
											$upddate=substr($output,$pos+16,11);
											$pos=strpos($output,  "Record expires on ");
											$expdate=substr($output,$pos+18,11);		
										}
										
									}
									else
									{ 
										$status = "1"; //Available
									}
								$expdate=trim($expdate);
								$upddate=trim($upddate);
								$ed=date("Ymd", strtotime($expdate));
								$ud=date("Ymd", strtotime($upddate));
								if (date("Ymd")<$ed) $status=2;
									if ($status=="1" )
									{
										$expdate=date("Ymd");
										$upddate=date("Ymd");
										$ed=0;
										$ud=0;
									}

		}
	}
 
?>
