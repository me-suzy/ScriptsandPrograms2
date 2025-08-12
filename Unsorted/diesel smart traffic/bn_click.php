<?
  	if($uid == "" || $ruid == "") exit;
	$htref=$HTTP_REFERER."|".$HTTP_USER_AGENT."|".$REMOTE_PORT."|".$REQUEST_METHOD."|".$QUERY_STRING;	require "conf/sys.conf";
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

	$banner = f(q("select * from banners where status='1' and id='$ruid'"));

	if($banner[ id ] == "")
	{
		d($db);
		exit;
	}

	$cid=$banner[cid];
	$r = q("select url from campaigns where id='$banner[cid]'");

	if(e($r))
	{
		d($db);
		exit;
	}

	$c = f($r);
	$url = $c[url];

	if(e(q("select cs.rate from credits_set cs, campaigns c where cs.user_id=c.user_id and c.id='$uid'")))
	{
			$rate = def_rate();
	}
	else
	{
	 	$set = f(q("select cs.rate from credits_set cs, campaigns c where cs.user_id=c.user_id and c.id='$uid'"));
		$rate_str = @split("/", $set[ rate ]);

		$rate = (float)sprintf("%0.2f",$rate_str[ 1 ] / $rate_str[ 0 ]);
	}

$sql_cur_period = "and idate>='".(strtotime(date("d M Y")." 00:00:00"))."' and idate<='".(strtotime(date("d M Y 23:59:59")))."'";
if(e(q("select id from clicks where ifrom='$REMOTE_ADDR' and cid='$ruid' $sql_cur_period")))
{
	q("insert into clicks values('0','$cid','$uid','$ruid','".strtotime(date("d M Y H:i:s"))."','$REMOTE_ADDR','$htref')");

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
	d($db);
	if ($topframe==1) $url=$ROOT_HOST."framer.php?cmid=$campaign[id]&uid=$uid&url=$url";
	header("Location: $url");
?>
