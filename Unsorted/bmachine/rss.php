<?php

/*
********************************************

BN Soft bMachine 2.7

Written by Kailash Nadh
http://bnsoft.net, support@bnsoft.net

bMachine - http://boastology.com

********************************************
*/


  header("Content-type: text/xml");
  echo "<?xml version=\"1.0\"?".">";
include "config.php";

// Print the initial XML data
echo <<<EOF

<!-- generator="bMachine/$ver" -->
<rss version="0.92">
  <channel>
    <title>$s_title</title>
    <link>$c_urls</link>
    <description>$s_desc</description>
    <language>$s_lang</language>
    <docs>http://backend.userland.com/rss092</docs>
EOF;

//##########################

// Get the posts list
// prin the title, description and link

$ar=getPostList("hd");
for($n=0;$n<=count($ar[title])-1;$n++) {

$title=clrAll($ar[title][$n]);
$desc=clrAll($ar[summary][$n]);
$id=clrAll($ar[id][$n]);
$url=clrAll("$c_urls/index.php?id=$id");

if(strlen($desc)>100) { $desc=substr($desc,0,100)." .."; } // if the description is too long, truncate it

echo <<<EOF
    <item>
      <title>$title</title>
      <description>$desc</description>
      <link>$url</link>
    </item>
EOF;
}

//##########################

echo <<<EOF
  </channel>
</rss>
EOF;


// Function to strip out UnSafe characters for XML/RSS
function clrAll($str) {
$str=str_replace("&","&amp;",$str);
$str=str_replace("\"","&quot;",$str);
$str=str_replace("'","&apos;",$str);
$str=str_replace(">","&gt;",$str);
$str=str_replace("<","&lt;",$str);
return $str;
}

?>