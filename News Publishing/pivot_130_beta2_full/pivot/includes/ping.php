<?php


// error_reporting(E_ALL);
if(file_exists('xmlrpc/xmlrpc.inc')) {
	include_once('xmlrpc/xmlrpc.inc');
} else {
	// fallback for old dirname.
	include_once('xmlrpc-1.0.99.2/xmlrpc.inc');
}
chdir('../');
include_once('pv_core.php');
CheckLogin();
Setpaths();

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CurrentEncoding; ?>\"" />
	<title>Pivot &#187; <?php echo lang('adminbar', 'sendping'); ?></title>
	<link href="../<?php echo $theme['css']; ?>" rel="stylesheet" type="text/css" />
<body style="margin: 6px 6px 6px 6px; background-image: none;" onload="self.focus()";>
<h1>Pivot &raquo; Ping Update Trackers</h1>
<?php 

flush();

@set_time_limit(180);

// pings Weblogs.com
function pingWeblogs($name, $url, $server) {
	global $Paths;


	$server = parse_url("http://".$server);

	if ($server['path'] == "") { $server['path'] = "/"; }
	if ($server['port'] == "") { $server['port'] = "80"; }

	printf("<p><b>%s:%s%s</b>:<br />", $server['host'], $server['port'], $server['path']);

	flush();


	$client = new xmlrpc_client($server['path'], $server['host'], $server['port']);
	$message = new xmlrpcmsg("weblogUpdates.ping", array(new xmlrpcval($name), new xmlrpcval($url)));
	$result = $client->send($message);


	if (!$result || $result->faultCode()) {

		echo "<br />Pivot says: could not send ping. Check if you set the server address correctly, or else the server may be temporarily down. This happens sometimes, and if this error occurs out of the blue, it's likely that it will go away in a few hours or days. <br /></p>";
		echo "<!-- \n";
			print_r($result);
		echo "\n -->\n\n\n";
		return(false);
	}
	$msg = $result->serialize();

	$msg = preg_replace("/.*<\/boolean>/si","",$msg);
	$msg = preg_replace("/.*<value>/si","",$msg);
	$msg = preg_replace("/<\/value>.* /si","",$msg);	

	$msg = escape($msg);

	echo "Server said: <i>'$msg'</i><br /></p>";
	return(true);

}



$title = $Weblogs[urldecode($Pivot_Vars['title'])]['name'];
$Current_weblog = urldecode($Pivot_Vars['title']);
$file = urldecode($Pivot_Vars['file']);

$logpath = $Weblogs[$Current_weblog]['front_path'];


if (siteurl_isset()) {

	$url = gethost();
	
} else {

	$url= $Paths['pivot_url'] . $logpath . $file;
	$url = gethost() . fixpath($url);

}

$url = str_replace("/index.php", "/", $url);
$url = str_replace("/index.html", "/", $url);

$servers = explode("\n", $Cfg['ping_urls']);

echo "<p>Now sending update-pings. This might take a while, so please be patient.<br />";
echo "<p>url: $url<br /></p>";
flush();

foreach ($servers as $server) {

	$server = trim($server);
	if (strlen($server)>3) {
		pingWeblogs($title, $url, $server);
		flush();
	}
	
}

echo "<p><br /><b>done</b><p>";

?></body></html>
