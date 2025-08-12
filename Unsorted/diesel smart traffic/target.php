<?
  	if($uid == "") $uid=-1;
	$htref=$HTTP_REFERER."|".$HTTP_USER_AGENT."|".$REMOTE_PORT."|".$REQUEST_METHOD."|".$QUERY_STRING;
	require "conf/sys.conf";
	require "lib/mysql.lib";
	require "lib/ban.lib";

	require "lib/bann.lib";

$d_url = get_ban($DURL_P);
function go($url="") {
 global $d_url;
 if (!$url) $url=$d_url;
 header("Location: $url");
 exit;
};

	$db = c();
	$r = q("select group_id as group_id, id as id from campaigns where status='1' and id='$uid' ORDER BY RAND()");

	if(e($r))
	{
		d($db);
		go();
		exit;
	}

	$c = f($r);

	$campaign = f(q("SELECT *, cam.id as id, cam.user_id as user_id, cam.title as c_title FROM campaigns cam,  prev pn WHERE pn.cid=cam.id AND pn.prev_number>0 AND cam.id<>'$c[id]' AND cam.status='1' AND cam.id='$cmid'"));

	if (!$campaign[id])
	{
	go();
	exit;
	};
	
	$cid=$campaign[id];

	if (e(q("select cs.rate from credits_set cs, campaigns c where cs.user_id=c.user_id and c.id='$uid'"))){

			$rate = def_rate();
	}
	else
	{
	 	$set = f(q("select cs.rate from credits_set cs, campaigns c where cs.user_id=c.user_id and c.id='$uid'"));
		$rate_str = @split("/", $set[ rate ]);

		$rate = (float)sprintf("%0.2f",$rate_str[ 1 ] / $rate_str[ 0 ]);
	}

	if (!$ipfseconds) $ipfseconds=300;
	$sql_cur_period2 = "and idate>=".(time()-$ipfseconds);
	if(!e(q("select id from previews where ifrom='$REMOTE_ADDR' $sql_cur_period2"))) {go();exit;}

	q("insert into previews values('','-7','$uid','".strtotime(date("d M Y H:i:s"))."','$REMOTE_ADDR','$campaign[id]')");
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

	q("insert into clicks values('','$cid','$uid','-7','".strtotime(date("d M Y H:i:s"))."','$REMOTE_ADDR','$htref')");

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
		go();
		exit;
	}

	$c = f($r);
	$url = $c[url];
	if ($topframe==1) $url=$ROOT_HOST."framer.php?cmid=$campaign[id]&uid=$uid&url=$url";
	go($url);
?>
