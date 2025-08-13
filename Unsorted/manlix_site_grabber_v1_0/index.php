<?

#	.................................................................................
#
#		Ñêðèïò:	Manlix Site Grabber, âåðñèÿ: 1.0
#		Àâòîð:	Manlix (http://manlix.ru)
#	.................................................................................

if(phpversion()<4.1)
exit("<font face='verdana' size='1' color='#de0000'><b>Âåðñèÿ PHP èíòåðïðåòàòîðà äîëæíà áûòü 4.1.0 èëè âûøå, íî íèêàê íå íèæå (âàøà âåðñèÿ èíòåðïðåòàòîðà: ".phpversion().")</b></font>");

function error($error,$file){exit('<font face="verdana" size="1" color="#de0000"><b>'.$error.'<br>['.htmlspecialchars($file).']</b></font>');}

function CheckConf($conf)
{
	while(list($section,$array)=each($conf))
		while(list($key,$value)=each($array))
			if(!strlen($value))
			error("Â ôàéëå ïàðàìåòðîâ ñêðèïòà, à èìåííî â ñåêöèè <font color=green>".$section."</font>, ïóñò êëþ÷ <font color=green>".$key."</font>",$conf['dir']['path']."/".$conf['dir']['inc']."/config.inc.dat");
}

if(!set_time_limit(0)) error("Îòêðîéòå ôàéë <font color=green>".__FILE__."</font> è óäàëèòå â í¸ì <font color=green>".__LINE__."</font> ñòðî÷êó",date("Äàòà: d.m.Y. Âðåìÿ: H:i:s",time()));

$manlix=parse_ini_file("./inc/config.inc.dat",1) or error("íå ìîãó çàãðóçèòü îñíîâíîé ôàéë êîíôèãóðàöèè","./inc/config.inc.dat");

include("./inc/functions.inc.php");

CheckConf($manlix);

$HostInfo=manlix_read_file($manlix['file']['host']);

$CaseFile=manlix_read_file($manlix['file']['case']);

$request=$result=null;

$OpenRemoteHost=fsockopen($HostInfo[0]=manlix_strip_new_line($HostInfo[0]),$HostInfo[1]=manlix_strip_new_line($HostInfo[1]),$ErrorNum,$ErrorString,$HostInfo[2]=manlix_strip_new_line($HostInfo[2]));


$OpenRequestFile=fopen($manlix['file']['request'],'r');
flock($OpenRequestFile,1);
flock($OpenRequestFile,2);
	while(!feof($OpenRequestFile))
	$request.=fgets($OpenRequestFile,1024);
fclose($OpenRequestFile);

	if($OpenRemoteHost)
	{
	fputs($OpenRemoteHost,$request);

		while(!feof($OpenRemoteHost))
		$result.=fgets($OpenRemoteHost,1024);

	fclose($OpenRemoteHost);
	}

	else
	echo "<i>Grabber:</i> íå óäà¸òñÿ óñòàíîâèòü ñîåäèíåíèå ñ õîñòîì <b>".$HostInfo[0]."</b>, ÷åðåç <b>".$HostInfo[1]."</b> ïîðò.";
preg_match("/".$CaseFile[0]."/is",$result,$array);


if(isset($_GET['first']))
echo array_shift($array);

else
echo array_pop($array);
?>