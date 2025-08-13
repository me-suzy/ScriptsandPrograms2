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
	FFileRead("template.member.area.do.dig.htm",$cmember);
	
	$a=HTTP_Post($url,"");

	$pos=strpos($a,"<a");
	while (!$pos===false)
	{
		$pos=strpos($a,"<a href=");
		if (!$pos===false)
		{
			$a=substr($a,$pos+8);
			$pos=strpos($a,">");
			if (!$pos===false)
			{
				$urll=substr($a,0,$pos);
				$urll=trim($urll);
				$urll=str_replace("\"","",$urll);
				$all.=$urll."<br>";
				$urll='';
			}
		}
		
	}
	
	$cmember=str_replace("{url}",$url,$cmember);
	$cmember=str_replace("{links}",$all,$cmember);
	FFileRead("template.member.area.main.htm",$content);
	$content=str_replace("{content}",$cmember,$content);

?>