<?php
set_time_limit(600);
$p=array_merge($GLOBALS['HTTP_GET_VARS'],$GLOBALS['HTTP_POST_VARS']);
include('./client.php');
include('./html.php');
$out_path=date("Y.m.d-H.i.s");

if ($p['url']) {
	$orghtml=url_content($url);
	if (!$orghtml) {
		print 'can\'t open url';
		die();
	}else {
		$parser = new htmlparser_class;
		$parser->LoadHTML($orghtml);
		$parser->Parse();
		$result=$parser->GetElements(&$htmlcode);
		if ($result) {
			$_c=1;
			mkdir('./out/'.$out_path,0755);
			$f=fopen('./out/'.$out_path.'/index.html','wb');
			fwrite($f,$orghtml);
			fclose($f);
			while (list($key, $code) = each ($htmlcode)){
				$tag = strtolower(substr($code,0,7));
				if($tag == '<a href') {
					$raz = $code;
					$dwa = current($htmlcode);
					$trzy = next($htmlcode);
					$picnum = preg_match('/href=\'?"?(\S+\.jpe?g)\'?"?/i',$raz, $matches);
					$pic = $matches[1];

					$thbnum = preg_match('/src=\'?"?(\S+\.jpe?g)\'?"?/i',$dwa, $matches);
					$thu = $matches[1];
					if (($picnum) AND ($thbnum)) {
						print 'reading '.$pic; flush();
						$imgc=url_content(makefull($pic),$p['url']);
						if ($imgc) {
							$name=sprintf("%02d", $_c);
							$fp=fopen('./out/'.$out_path.'/'.$name.'.jpg','wb');
							fwrite($fp,$imgc); fclose($fp);
							print ' done <br>';
							flush();
						}else {print ' error.'; die();}
						print 'reading '.$thu; flush();
						$imgc=url_content(makefull($thu),$p['url']);
						if ($imgc) {
							$name=sprintf("%02d", $_c);
							$fp=fopen('./out/'.$out_path.'/th_'.$name.'.jpg','wb');
							fwrite($fp,$imgc); fclose($fp);
							print ' done <br>';
							flush();
						}else {print ' error.'; die();}
						$_c++;
					}
				}
			}
		}else {
			print 'can\'t read pages';
		}
	}
}else {
	?>
	<form action="" method="post">
	<input type="text" name="url" size="80"><input type="submit" value="leech">
	</form>
	<?
}

function url_content($url, $ref=false) {
	$http = new Net_HTTP_Client();
	$urlp=parse_url($url);
	if ($http->Connect( $urlp['host'], 80 )) {
		$http->addHeader( "Referer", trim($ref) );
		$http->addHeader( "User-Agent", "Mozilla/4.0 (compatible; MSIE 5.5; Windows 98)" );
		$http->addHeader( "Host", $urlp['host'] );
		$http->addHeader( "Connection", "Keep-Alive" );

		$status = $http->get($urlp['path']);
		if( $status != 200 and $status != 302) {
			return false;
		}else {
			$content=$http->getBody();
			$http->Disconnect();
			unset($http);
			return $content;
		}
	}else {
		return false;
	}
}

function makefull($pic) {
	global $url;
	$domain=parse_url($url);
	
	$rest = substr($pic,0,1);
	if($rest == '/') {
		$r = $domain["scheme"] ."://". $domain["host"] . $pic;
		return $strona;
	}
	$rest = substr($pic,0,7);
	if($rest == 'http://') {
		return $pic;
	}
	preg_match('/^(\S+)\//',$domain["path"], $pathh);
	$r = $domain["scheme"] ."://". $domain["host"] . $pathh[0] . $pic;
	return $r;
}

?>