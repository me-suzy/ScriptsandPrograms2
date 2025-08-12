<?php
// Cjultra v2.1

if (ini_get('register_globals') != 1) {
    $supers = array('_REQUEST','_ENV','_SERVER','_COOKIE','_GET','_POST');
    foreach ($supers as $__s) {
        if (is_array($$__s) == true) extract($$__s, EXTR_OVERWRITE);
    }
    unset($supers);
}

ignore_user_abort(true);
srand((double)microtime()*1000000);
include_once("./common.php");

if (!$linkid)
{
	$linkid = db_connect();
	if (!$linkid) error_message(sql_error());
}

$day = date("w");
$hour = date("G");


$query = "select * from day,trade where trade.a1 = day.z";
$result = mysql_query($query);
if(!$result) error_message(sql_error());

$r_trades = Array();
$u_trades = Array();

while($data = mysql_fetch_array($result))
{
	$trade["hr"] = $data["zr$hour"];
	$trade["hu"] = $data["zu$hour"];
	$trade["ho"] = $data["zo$hour"];
	$trade["hc"] = $data["zc$hour"];
	
	for($trade["r"]=$trade["u"]=$trade["o"]=$trade["c"]=$i=0;$i<24;$i++)
	{
		$trade["r"]+= $data["zr$i"];
		$trade["u"]+= $data["zu$i"];
		$trade["o"]+= $data["zo$i"];
		$trade["c"]+= $data["zc$i"];
	}
	
	$trade["url"] = $data["a2"];
	$trade["des"] = $data["a21"];
	$trade["dom"] = $data["a1"];
	
		
	$r_trades[$data["a1"]] = $trade;
	$u_trades[$data["a1"]] = $trade;
}

uasort($r_trades,"rin_sort");
uasort($u_trades,"uin_sort");


$dp = opendir("./");
while ($file = readdir($dp))
{
	if (ereg("^cjutop_",$file) && is_writable(ereg_replace("^cjutop_","",$file)))
	{
		$tophtml = implode("",file($file));
		for($i=1;$list = each($r_trades);$i++)
		{
			$tophtml = eregi_replace("\%\%R_RIN$i\%\%","" . $list[1]["r"],$tophtml);
			$tophtml = eregi_replace("\%\%R_OUT$i\%\%","" .$list[1]["o"],$tophtml);
			$tophtml = eregi_replace("\%\%R_CLK$i\%\%","" .$list[1]["c"],$tophtml);
			$tophtml = eregi_replace("\%\%R_HRIN$i\%\%","" .$list[1]["hr"],$tophtml);
			$tophtml = eregi_replace("\%\%R_HOUT$i\%\%","" .$list[1]["ho"],$tophtml);
			$tophtml = eregi_replace("\%\%R_HCLK$i\%\%","" .$list[1]["hc"],$tophtml);
			$tophtml = eregi_replace("\%\%R_URL$i\%\%",$list[1]["url"],$tophtml);
			$tophtml = eregi_replace("\%\%R_DES$i\%\%",$list[1]["des"],$tophtml);
			$tophtml = eregi_replace("\%\%R_LNK$i\%\%","out.php?perm=" . $list[1]["dom"] . "&link=top" . $i . "_" . $list[1]["dom"],$tophtml);
			$tophtml = eregi_replace("\%\%R_DOM$i\%\%",$list[1]["dom"],$tophtml);			
		}
		for($i=1;$list = each($u_trades);$i++)
		{
			$tophtml = eregi_replace("\%\%U_UIN$i\%\%","" .$list[1]["u"],$tophtml);
			$tophtml = eregi_replace("\%\%U_OUT$i\%\%","" .$list[1]["o"],$tophtml);
			$tophtml = eregi_replace("\%\%U_CLK$i\%\%","" .$list[1]["c"],$tophtml);
			$tophtml = eregi_replace("\%\%U_HUIN$i\%\%","" .$list[1]["hu"],$tophtml);
			$tophtml = eregi_replace("\%\%U_HOUT$i\%\%","" .$list[1]["ho"],$tophtml);
			$tophtml = eregi_replace("\%\%U_HCLK$i\%\%","" .$list[1]["hc"],$tophtml);
			$tophtml = eregi_replace("\%\%U_URL$i\%\%",$list[1]["url"],$tophtml);
			$tophtml = eregi_replace("\%\%U_DES$i\%\%",$list[1]["des"],$tophtml);
			$tophtml = eregi_replace("\%\%U_LNK$i\%\%","out.php?perm=" . $list[1]["dom"] . "&link=top" . $i . "_" . $list[1]["dom"],$tophtml);
			$tophtml = eregi_replace("\%\%U_DOM$i\%\%",$list[1]["dom"],$tophtml);			
		}
		reset($r_trades);
		reset($u_trades);
		$fp = fopen(ereg_replace("^cjutop_","",$file),"w");
		if ($fp) fwrite($fp,$tophtml);
		fclose($fp);
	}
}
closedir($dp);



function rin_sort($a,$b)
{
	if($a["r"] == $b["r"])
		return 0;
	return ($a["r"] > $b["r"]) ? -1 : 1;
}

function uin_sort($a,$b)
{
	if($a["u"] == $b["u"])
		return 0;
	return ($a["u"] > $b["u"]) ? -1 : 1;
}



