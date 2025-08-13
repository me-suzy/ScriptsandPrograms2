<?php

$linkAttributes = array('title','url','linkURL', 'description', 'bid');

$maxAltavistaCount=10;

$_sf_count=0;
$_sf_startcount=0;


$items = array();        
$item = null;    

$index = null;     
                        
function parseLinks_altavista($str, $from ,&$error){
	global $items, $index;
	global $_count, $_startcount;
	global $optionsSystem;
	
	//dprint("avf: str=$str, from=$from");
	//return false;
	
	$filename = "http://altavista.com/sites/search/web?q=".urlencode($str)."&pg=q&avkw=qtrp&kl=XX&stq=$from";
	//$filename = "http://altavista.com/sites/search/web?q=business&pg=q&avkw=qtrp&kl=XX&stq=10";
	//$filename = "libs/search/av3.html";
	
	$ff = @fopen ($filename, "r");
	if (!$ff) {    /*	dprint("error opening av-file"); */ return 0;	}

	$text = join('',file($filename));
	
	$items = array();
	
	$text = preg_replace("/\\\n|\\\t|\\\r/im","",$text);
	$text = preg_replace("/<b>|<\/b>/im","",$text);	
	//$text = preg_replace("/<\/span>|<span [^<>]*>/im","",$text);
	$text = preg_replace("/<\/font>|<font [^<>]*>/ims","",$text);
	$text = preg_replace("/<br>/ims","",$text);

	//echo $text;		
	$founds = array();	
//	$t = preg_match_all("/<span +class *= *i *> *\<a +(onmouseover *= *\"[^\<>\"]*\")? *(onclick *= *\"[^\"]*\")? +(href *= *\"([^\<>\"]*)\")?[^<>]*>(<b>|<\/b>|[a-z0-9]*)+<\/a>/im",  $text, $founds);
//echo $text;

/*
	   $pattern = "/span *?class *?= *?i.*?> *\<a +(onmouseover *?= *?\"[^\<>\"]*?\")? *?(onclick *?= *?\"[^\"]*?\")? *?(href *?= *?\"([^\<>\"]*?)\")[^<>]*>(.*?)<\/a>";
*/	   
	   //$pattern = "/span.*?class *?= *?i.*>.*?\<a(.*?)(href *?= *?\"([^\<>\"]*?)\")[^\<>]*>(.*?)<\/a>";
//	   $pattern = "/span.*?class *?= *?i.*>.*?\<a(.*?)(href *?= *?\"([^\<>\"]*?)\")[^\<>]*>(.*?)<\/a>";
	   $pattern = "/span( *?)class( *?)=( *?)i(.*?)>(.*?)\<a(.*?)(href( *?)=( *?)\"([^\<>\"]*?)\")[^\<>]*>(.*?)<\/a>";
	   
	   $pattern.="([^\<>]*?)(\<span +class=y *[a-z0-9=\"]*>([^\<>]*?)\<\/span>)?(.*?)\<\/span>";
	   $pattern.="([^\<>]*?)\<span";
	   $pattern.="/ims"; 
	$t = preg_match_all($pattern,  $text, $founds);
 	//$t = preg_match_all("/<A +[^a<>]*href *=\"(.*)\" [^>]*>/im",  $text, $founds);
	
//	$text = "<a >hi </a>";
//	$t = preg_match_all("/<A(<a){0}>(.*)<\/a>/im",  $text, $founds);
	if (!$t){
		//dprint("no matches");
	}
//dprint("<br>AV:results<hr>");
//	print_r($founds);	
	//print_r($founds[4]);
	//print_r($founds[5]);
	//print_r($founds[7]);			


  $refs = $founds[10];
  $titles = $founds[11];
  $descrs = $founds[16];
  
  //print_r($refs);

//$linkAttributes = array('title','url','linkURL', 'description', 'bid');
  
  for ($i=0;$i<($_count=sizeof($refs)); $i++){
  	  // url
	  $ref = $refs[$i];
	  $matches = array();
	  preg_match_all("/&r=(.*)/ims", $ref, $matches);
	  $url = $matches[1][0];
	  $url=preg_replace("/%3A/ims", ":",$url);
	  $url=preg_replace("/%2F/ims", "/",$url);
	  
	  //
  	  $item=array("linkID"=>0, "url"=>$url, "linkURL"=>$url, 
	      "title"=>$titles[$i], "description"=>$descrs[$i], "bid"=>0);

	//dprint("item="); print_r($item);
//	  array("linkID"=>0, "linkURL"=>$site['URL'], "url"=>$site['URL'], "title"=>$site['title'], "description"=>$site['snippet'], "bid"=>0);
	  $items[] = $item;
 	  
  } 	

	preg_match("/AltaVista found +([0-9,]+) +results/im",$text, $res);	
	//$result["count"] = $_count;
	$cs = $res[1];
	$_count = preg_replace("/[\s,]+/im","",$cs);
	$result["count"] = $_count;
	
	//dprint("AV: count=".$res[1]);
	$result["links"] = $items;
	$error = "";
	return $result;

}


function find_altavista($str, $from ,$count, &$error){
	global $optionsSystem;
	
	$maxCount = getOption("maxAltavistaCount");	
	if ($from>$maxCount) return 0;
	//dprint("find from=$from, count=$count");

	$page1 = floor($from/10);
	$page2 = ceil(($from+$count-1)/10);
	$start = $page1*10;
	
	$_fCount = 0;
	$links=array();
	
	$index = 0;
	for ($p=$page1; $p<=$page2; $p++){
		//dprint("find page=$p");
		$res = parseLinks_altavista($str, $p*10, $error);
		if (empty($res)) break;
		
		$fCount = $res["count"];
		foreach($res["links"] as $link){
			//dprint("index=$index");
			if ($index>=($from-$start) && $index<($from-$start+$count)){
				$links[]=$link;
				//dprint("add");
			}
				
			$index++;
		}
	}
	
	
	
	$result["links"]=$links;
	$result["count"]=$fCount>$maxCount?$maxCount:$fCount;
	//print_r($result);
	
	return $result;
	
}

/*
$str = "business";
$from = 0;
$page=1;

$error = "";
$links = parseLinks_altavista($str, $from, $error);	
echo "$error<br>";

print_r($links);
*/
?>