<?
  	if($uid == "") $uid=-1;
	$htref=$HTTP_REFERER."|".$HTTP_USER_AGENT."|".$REMOTE_PORT."|".$REQUEST_METHOD."|".$QUERY_STRING;
	require "conf/sys.conf";
	require "lib/mysql.lib";
	require "lib/ban.lib";

	require "lib/bann.lib";

$d_url = get_ban($DURL_P);
function go($url="") {
 global $d_url, $errorcode;
 if (!$url) $url=$d_url."?errorcode=$errorcode";
 header("Location: $url");
 exit;
};

$errorcode="0";

	$db = c();
	
	if (!$ipsfseconds) $ipsfseconds=10;
	$sql_cur_period2 = "and idate>=".(time()-$ipsfseconds);
	if(!e(q("select id from previews where ifrom='$REMOTE_ADDR' $sql_cur_period2"))) {$errorcode="startpage_skiptoofast"; go();exit;}

	include("src/exchange.php");

	q("insert into previews values('','-1','$uid','".strtotime(date("d M Y H:i:s"))."','$REMOTE_ADDR','$campaign[id]')");
	q("update prev set prev_number=prev_number-1 where cid='$campaign[id]'");
	q("update prev set prev_number=prev_number+$rate where cid='$uid'");

	if(e(q("select id from logs where user_id='$campaign[user_id]'")))
	{
		q("insert into logs values('','$campaign[user_id]','".strtotime(date("d M Y H:i:s"))."')");
	}
	else
	{
		q("update logs set idate='".strtotime(date("d M Y H:i:s"))."' where user_id='$campaign[user_id]'");
	}


/////
$sql_cur_period = "and idate>='".(strtotime(date("d M Y")." 00:00:00"))."' and idate<='".(strtotime(date("d M Y 23:59:59")))."'";

if(e(q("select id from clicks where ifrom='$REMOTE_ADDR' and cid='$cid' $sql_cur_period")))
{

	q("insert into clicks values('0','$cid','$uid','-1','".strtotime(date("d M Y H:i:s"))."','$REMOTE_ADDR','$htref')");

	$cred = def_credits();
	$credits = $cred[0];
	if($credits == "")
	{
		$credits = 3;
	}

	$credits_received=$credits*$rate;
	q("update prev set prev_number=prev_number-$credits where cid='$cid'");
	q("update prev set prev_number=prev_number+$credits_received where cid='$uid'");
};


$r = q("select url from campaigns where id='$campaign[id]'");

	if(e($r))
	{
		d($db);
		$errorcode="nourl";
		go();
		exit;
	}

	$c = f($r);
	$url = $c[url];
	if ($topframe==1) $url=$ROOT_HOST."framer.php?cmid=$campaign[id]&uid=$uid&url=$url";
	$errorcode="ready_$url";
	go($url);
?>
