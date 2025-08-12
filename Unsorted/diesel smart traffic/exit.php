<?
  	if($uid == "") exit;
	$htref=$HTTP_REFERER."|".$HTTP_USER_AGENT."|".$REMOTE_PORT."|".$REQUEST_METHOD."|".$QUERY_STRING;
	require "conf/sys.conf";
	require "lib/mysql.lib";
	require "lib/ban.lib";
	require "lib/bann.lib";
$d_url = get_ban($DURL_P);
function go($url="") {
 global $d_url;
 if (!$url) $url=$d_url;
{
?>
//  Exit Exchange Client Code <br>
//  (C) 2002 NTM3K <br>

// Declarations
var exitURL="<?php echo $url;?>";

var nextime = 0;
var fixlinks = 0;

var docVal = 0 ;
var Cdelay = 1000;
var exiting = 1;

var isNav, isIE;
if (parseInt(navigator.appVersion) >= 4) {
    if (navigator.appName == "Netscape") {
isNav = true;
    } else {
isIE = true;
    }
}

function stopError(e) { return true; }
window.onerror = stopError;

function fixNoise() {
    if (document.all &&
document.all.tags("bgsound") &&
document.all.tags("bgsound")[0]
)
document.all.tags("bgsound")[0].volume = -10000;
}

function exitHandler() {
    if (exiting) {
var ExitWindow = window.open(exitURL,'NTM_ExitWindow');
self.focus();
    }
    return true;
}

var newload;
var oldload;

function installLoad(func) {
    newload = func;
    oldload = window.onload;
    window.onload = new Function ("newload(); if (oldload != null) oldload();");
}

if (isNav) {
    document.captureEvents(Event.CLICK | Event.UNLOAD | Event.LOAD | Event.ERROR);
}

try {

if (
    (window.name != null && window.name == "EEmF1")
    || (window.parent != null && window.parent.name != null && window.parent.name == "EEmF1")
    )
{
    docVal = 1;
}

}
catch (e) { }

if (docVal == 1) {
// EXIT WINDOW
    window.open = null;
    installLoad(fixNoise);
} else {  
// CLIENT WINDOW
    window.onunload = exitHandler;
}
<?
}

};

	$db = c();

	if (!$ipfseconds) $ipfseconds=300;
	$sql_cur_period2 = "and idate>=".(time()-$ipfseconds);
	if(!e(q("select id from previews where ifrom='$REMOTE_ADDR' $sql_cur_period2"))) {go();exit;}

	include("src/exchange.php");

	q("insert into previews values('0','-2','$uid','".strtotime(date("d M Y H:i:s"))."','$REMOTE_ADDR','$campaign[id]')");
	q("update prev set prev_number=prev_number-1 where cid='$campaign[id]'");
	q("update prev set prev_number=prev_number+$rate where cid='$uid'");
	d($db);

	if(e(q("select id from logs where user_id='$campaign[user_id]'")))
	{
		q("insert into logs values('0','$campaign[user_id]','".strtotime(date("d M Y H:i:s"))."')");
	}
	else
	{
		q("update logs set idate='".strtotime(date("d M Y H:i:s"))."' where user_id='$campaign[user_id]'");
	}


/////
$sql_cur_period = "and idate>='".(strtotime(date("d M Y")." 00:00:00"))."' and idate<='".(strtotime(date("d M Y 23:59:59")))."'";
if(e(q("select id from clicks where ifrom='$REMOTE_ADDR' and cid='$cid' $sql_cur_period")))
{

	q("insert into clicks values('0','$cid','$uid','-2','".strtotime(date("d M Y H:i:s"))."','$REMOTE_ADDR','$htref')");
	
	$cred = def_credits();
	$credits = $cred[0];
	if($credits == "")
	{
		$credits = 3;
	}

	$credits_received=$credits*$rate;

	q("update prev set prev_number=prev_number-$credits where cid='$ruid'");
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
	if (!$url) $url=$d_url;
	if ($topframe==1) $url=$ROOT_HOST."framer.php?cmid=$campaign[id]&uid=$uid&url=$url";
	go($url);
?>
