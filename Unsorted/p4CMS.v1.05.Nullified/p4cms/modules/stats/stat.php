<? 
$inc="include";
$cdatum=date("d.m.Y");
$indatum=date("d.m.Y");

include("$abs_pfad/include/config.inc.php");

@mysql_connect("$sql_server","$sql_user","$sql_passwort");
@mysql_select_db("$sql_db");


//--------------------------------------------
// BROWSER
//--------------------------------------------
if( eregi("(opera) ([0-9]{1,2}.[0-9]{1,3}){0,1}",$_SERVER['HTTP_USER_AGENT'],$sysarg) || eregi("(opera/)([0-9]{1,2}.[0-9]{1,3}){0,1}",$_SERVER['HTTP_USER_AGENT'],$sysarg)){$browser_user = "Opera";}
		else if( eregi("(konqueror)/([0-9]{1,2}.[0-9]{1,3})",$_SERVER['HTTP_USER_AGENT'],$sysarg) ){$browser_user = "Konqueror";}
		else if( eregi("(lynx)/([0-9]{1,2}.[0-9]{1,2}.[0-9]{1,2})",$_SERVER['HTTP_USER_AGENT'],$sysarg) ){$browser_user = "Lynx";}
		else if( eregi("(msie) ([0-9]{1,2}.[0-9]{1,3})",$_SERVER['HTTP_USER_AGENT'],$sysarg) ){$browser_user = "MSIE";}
		else if( eregi("(netscape6)/(6.[0-9]{1,3})",$_SERVER['HTTP_USER_AGENT'],$sysarg) ){$browser_user = "Netscape";}
		else if( eregi("mozilla/5",$_SERVER['HTTP_USER_AGENT']) ){$browser_user = "Mozilla";}
		else if( eregi("(mozilla)/([0-9]{1,2}.[0-9]{1,3})",$_SERVER['HTTP_USER_AGENT'],$sysarg) ){$browser_user = "Mozilla";}
else{$browser_user = "?";}
//--------------------------------------------
// BETRIEBSSYTEM
//--------------------------------------------	
if(eregi("linux",$_SERVER['HTTP_USER_AGENT'])){$os_user = "Linux";}
		else if(eregi("unix",$_SERVER['HTTP_USER_AGENT']) || eregi("hp-ux",$_SERVER['HTTP_USER_AGENT']) || eregi("X11",$_SERVER['HTTP_USER_AGENT']) ){$os_user = "Linux";}
		else if(eregi("win32",$_SERVER['HTTP_USER_AGENT'])){$os_user = "Windows";}
		else if((eregi("(win)([0-9]{2})",$_SERVER['HTTP_USER_AGENT'],$sysarg)) || (eregi("(windows) ([0-9]{2})",$_SERVER['HTTP_USER_AGENT'],$sysarg)) ){$os_user = "Windows";}
		else if(eregi("Win 9x 4.90",$_SERVER['HTTP_USER_AGENT'])){$os_user = "Me";}
		else if(eregi("windows 2000",$_SERVER['HTTP_USER_AGENT']) || eregi("(windows nt)( ){0,1}(5.0)",$_SERVER['HTTP_USER_AGENT']) ){$os_user = "2000";}
		else if(eregi("(windows nt)( ){0,1}(5.1)",$_SERVER['HTTP_USER_AGENT']) ){$os_user = "XP";}
		else if(eregi("(winnt)([0-9]{1,2}.[0-9]{1,2}){0,1}",$_SERVER['HTTP_USER_AGENT'],$sysarg) ){$os_user = "NT";}
		else if(eregi("(windows nt)( ){0,1}([0-9]{1,2}.[0-9]{1,2}){0,1}",$_SERVER['HTTP_USER_AGENT'],$sysarg) ){$os_user = "NT";}
		else if(eregi("mac",$_SERVER['HTTP_USER_AGENT'])){$os_user = "Mac";}
		else if(eregi("(sunos) ([0-9]{1,2}.[0-9]{1,2}){0,1}",$_SERVER['HTTP_USER_AGENT'],$sysarg)){$os_user = "SunOS";}
		else if(eregi("(beos) r([0-9]{1,2}.[0-9]{1,2}){0,1}",$_SERVER['HTTP_USER_AGENT'],$sysarg)){$os_user = "BeOS";}
		else if(eregi("freebsd",$_SERVER['HTTP_USER_AGENT'])){$os_user = "FreeBSD";}
		else if(eregi("openbsd",$_SERVER['HTTP_USER_AGENT'])){$os_user = "OpenBSD";}
		else if(eregi("irix",$_SERVER['HTTP_USER_AGENT'])){$os_user = "IRIX";}
		else if(eregi("os/2",$_SERVER['HTTP_USER_AGENT'])){$os_user = "OS2";}
		else{$os_user = "?";}
//--------------------------------------------
// REFERER
//--------------------------------------------
$refer = $_SERVER['HTTP_REFERER'];
if($refer)
	{
		$referer = explode ("/", $refer);
		if($referer[0] == "http:")
		{
			if(strpos ($referer[2], ".") == false || $referer[2]=="127.0.0.1")
			{
				$eintrag = 0;
			}	else {
				$dom1 = strstr ($referer[2], "www.");
				if($dom1==false) $domainname = $referer[2];
				else $domainname = substr ($dom1, 4);
				$eintrag = 1;
			}
		} else if($referer[0] == "file:" || $referer[0] == "news:")
		{
			$eintrag = 0;
		} else {
			$refer1 = "http://$refer";
			$refer = $refer1;
			if(strpos ($referer[0], ".") == false || $referer[0]=="127.0.0.1")
			{
				$eintrag = 0;
			}else
			{
				$dom1 = strstr ($referer[0], 'www.');
				if($dom1==false) $domainname = $referer[0];
				else $domainname = substr ($dom1, 4);
				$eintrag = 1;
			}
		}
		if($eintrag=="1")
		{
			$domainname1 = explode (".", $domainname);
			$anzahl1 = count($domainname1) - 1;
			$domainname = $domainname1[$anzahl1-1].".".$domainname1[$anzahl1];
		}
	} else $refer = "-";
	
//--------------------------------------------
// Heute schon User gezählt?
//--------------------------------------------
$query="select * from " . $sql_prefix ."stats where statid='$_REQUEST[statid]' and datum='$cdatum'";
$result=mysql_query($query);
$anzahl=@mysql_numrows($result);

//--------------------------------------------
// IST REFERER EINGETRAGEN ?
//--------------------------------------------

if($besuch==1 || $anzahl==0){ 
$query="select * from  " . $sql_prefix ."referer where name='".$domainname."' AND statid='$_REQUEST[statid]' AND jahr='".date("Y")."'";
$result=mysql_query($query);
$anzahl=@mysql_numrows($result);


if($anzahl == "0") {
$query="INSERT into  " . $sql_prefix ."referer (statid,id,jahr,name,visits) values ('$_REQUEST[statid]','','".date("Y")."','".$domainname."','1')";
$result=mysql_query($query);

}

if($anzahl != "0") {
$query="UPDATE  " . $sql_prefix ."referer SET visits=visits+1 where name='".$domainname."' AND statid='$_REQUEST[statid]' AND jahr='".date("Y")."'";
$result=mysql_query($query);}
}

//--------------------------------------------
// ENDE REFERER EINTRAGEN
//--------------------------------------------

$query = "SELECT DISTINCT ip,stamp,statid FROM " . $sql_prefix ."stats WHERE ip = '".$_SERVER['REMOTE_ADDR']."'  order by stamp DESC limit 1";
$res = mysql_query($query);
$row = mysql_fetch_array($res);


$loeschzeit  = 60*60*$sperrstunden;

//if($row['stamp']+($loeschzeit)){
if(date("j",time())>date("j",$row['stamp'])){
$query="INSERT INTO " . $sql_prefix ."stats (statid,refdomain,stamp,id,datum,ip,os,browser,ref,tag,monat,jahr) VALUES ('$_REQUEST[statid]','".$domainname."','".time()."','','$cdatum','".$_SERVER['REMOTE_ADDR']."','$os_user','$browser_user','".$_SERVER['HTTP_REFERER']."','".$cdatum=date("d")."','".$cdatum=date("m")."','".$cdatum=date("Y")."')";
$result= mysql_query($query);
}


$num="";
$queryc = "SELECT * FROM " . $sql_prefix ."docstats WHERE datum='".date("d.m.Y")."' AND ref='".$_SERVER['REQUEST_URI'] ."'";
$resultc= mysql_query($queryc);
$rowc = mysql_fetch_array($resultc);
if($rowc['ref']!=$_SERVER['REQUEST_URI'])
	{
	$query2="INSERT INTO " . $sql_prefix ."docstats (ref,id,datum,tag,monat,jahr,stamp,hits) VALUES ('".$_SERVER['REQUEST_URI']."','','".date("d.m.Y")."','".date("d",time())."','".date("m",time())."','".date("Y",time())."','".time()."','1')";
	$result2= mysql_query($query2);
	echo mysql_error();
	
	}
else
	{
	$query3="UPDATE " . $sql_prefix ."docstats set hits=hits+1 WHERE ref='".$_SERVER['REQUEST_URI']."' AND datum='".date("d.m.Y")."'";
	$result3= mysql_query($query3);

	}
?>

