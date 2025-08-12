<?
  	if($kid == "") exit;
	$htref=$HTTP_REFERER."|".$HTTP_USER_AGENT."|".$REMOTE_PORT."|".$REQUEST_METHOD."|".$QUERY_STRING;			require "conf/sys.conf";
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

	if (!$ipfseconds) $ipfseconds=300;
	$sql_cur_period2 = "and idate>=".(time()-$ipfseconds);
	if(!e(q("select id from previews where ifrom='$REMOTE_ADDR' $sql_cur_period2"))) {go();exit;}

if ($uid)
{
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

	q("update prev set prev_number=prev_number+$rate where cid='$uid'");
} else $uid=-1;

	$kw=f(q("select ppc, cid from keywords where id='$kid'"));
	$cm=f(q("select cmp.url as url from campaigns cmp, prev prv where cmp.id='$kw[cid]' and  prv.cid=cmp.id and prv.prev_number>0"));
	
	if (!$cm[url])  {go();exit;};

	q("insert into previews values('','-5','$uid','".strtotime(date("d M Y H:i:s"))."','$REMOTE_ADDR','$kw[cid]')");
	d($db);



$sql_cur_period = "and idate>='".(strtotime(date("d M Y")." 00:00:00"))."' and idate<='".(strtotime(date("d M Y 23:59:59")))."'";
if(e(q("select id from clicks where ifrom='$REMOTE_ADDR' and cid='$kw[cid]' $sql_cur_period")))
{
	q("insert into clicks values('','$kw[cid]','$uid','-5','".strtotime(date("d M Y H:i:s"))."','$REMOTE_ADDR','$htref')");
	q("update prev set prev_number=prev_number-$kw[ppc] where cid='$kw[cid]'");
};



	$url = $cm[url];
	go($url);
?>
