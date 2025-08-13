<?
$pFile = "px.txt";
$tFile = "temp/".$_GET["tmp"];
$break=false;

set_time_limit (0);
ob_implicit_flush();
clearstatcache();

print ("<html>\n<head>\n<style type=\"text/css\">\n body,td{font-size: 10px;font-family : Verdana, Arial, Helvetica;}\n</style>\n</head>\n<body background=\"img/background.gif\" leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>\n");
print ("<p align=center><img src=\"img/top.jpg\" width=600 height=32 border=0>\n<br>\n");
print ("<script>\nfunction scr() {\n scroll(0, 100000000000);\n}\n</script>\n");
function is($value){return (strlen(trim($value))!=0)?true:false;}
function zero($value){return strcmp(trim($value),"0")?true:false;}

function Get($path,$proxy,$ua,$ref,$maxTime,$wt,$proto="1.0"){
  list($pHost,$pPort)=split(":",trim($proxy));
  $s="";$err=0;$errno="";

  $fp = @fsockopen($pHost, $pPort,&$err,&$errno,$maxTime);
  $x=strlen("http://");
  $y=strpos($path,"/",$x);
  if($y===FALSE)
    $y=strlen($path);
  $host=substr($path,$x,$y-$x);
  if($fp){
    printf("<font color=#ff0000><b>Ok</b></font>; Sending request: ");
	fputs($fp, "GET ".$path." HTTP/1.0\n");
	fputs($fp, "Accept: image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, application/vnd.ms-excel, application/msword, application/vnd.ms-powerpoint, */* \n");
	fputs($fp, "Accept-Language: en-us\n");
	fputs($fp, "User-Agent: ".$ua." \n");
        fputs($fp, "Pragma: no-cache\n");
	fputs($fp, "Host: ".$host." \n");
    	fputs($fp, "Referer: ".$ref." \n");
	fputs($fp, "Connection: Close\n\n");
	if(!zero($wt)){
	  fclose($fp);
	  printf("<font color=#ff0000><b>Skipped</b></font>");
	  return true;
	}
	    $s=fgets($fp,2000);
		if(!is($s)){
		  fclose($fp);
		  return false;
		}
		$pos=strpos($s,"200",$x);
     if($pos===FALSE){
	    echo "<font color=#ff0000><b>Error</b></font>";
		fclose($fp);
		return false;
	 }
	 else{
	 	echo "<font color=#ff0000><b>Ok</b></font>";
		fclose($fp);
		return true;
	 }
  }else{
    return false;
  }
}

function GetTask(){
  global $tFile;
  if(!file_exists($tFile))return "";
  if(($sz=filesize($tFile)) == 0) return "";
  $tmp=file($tFile);
  unlink($tFile);  
  clearstatcache();
  return trim($tmp[0]);
}

srand((double)microtime()*1000000);
$arr=file("useragent.txt");
$max=count($arr);

if(!is($res=GetTask())){
  print("Error: Can't find file with data");exit(0);
}
//print ($res."<script>scr()</script><br>\n");
list($url,$ref,$proxy,$wait,$perh,$max)=split("@@",trim($res));
$max=trim($max);$perh=trim($perh);

if(!is($url)){
  print("Error: Can't get url");exit(0);
}
printf("Url: ".$url."");
printf(zero($ref)?("<br>\nRef:".$ref):"");
printf("<br>\n<script>scr()</script>");

$pl=file("proxy/".trim($proxy));
$npl=count($pl);
$sTime=time();
$users=0;
if(zero($perh)){
  $cf=3600/$perh;
  $cTime=time();
  $lTime=$cTime;
  $sTime=$cTime;
}
printf("<br>\n<b>Start Generation:</b>");
printf("<script>scr()</script><br>\n");
$ncp=0;
while (!$break){

  if(zero($perh) && $ncp>0){
    $cTime=time();
    if(($cTime-$lTime)<$cf){
	  sleep(1);
	  continue;
	}else{
	  $lTime=$lTime+$cf;
	}
  }
  do{
       printf(date("[H:i:s] ")."Conecting to ".trim($pl[$ncp]).": ");
     $rs=Get($url,$pl[$ncp],"Mozilla/5.0 (compatible; MSIE 6.0; Windows NT 5.1)",$ref,2,$wait);
	 printf("<script>scr()</script><br>\n");
	 $ncp++;
	 if($ncp<$npl){
	 }else{
	   break;
	 }
	 
  }while(!$rs);  
  $users++;

  if($ncp<$npl){
  }else{
    $break=!$break;
  }
  if($max>0)
   if($users==$max)
   break;
}
printf("<br><b>End Generation!</b><script>scr()</script><br>\n");
printf("Time of work: ".(time()-$sTime)." seconds <script>scr()</script><br>\n");
printf("Users: ".$users."<script>scr()</script><br>\n");
?>