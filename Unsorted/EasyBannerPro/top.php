<?PHP

#################################################
##                                             ##
##              Easy Banner Pro                ##
##       http://www.phpwebscripts.com/         ##
##       e-mail: info@phpwebscripts.com        ##
##                                             ##
##                 Version 2.8                 ##
##             copyright (c) 2003              ##
##                                             ##
##  This script is not freeware nor shareware  ##
##    Please do no distribute it by any way    ##
##                                             ##
#################################################

include('./functions.php');
include_once("$s[phppath]/data/messages.php");
if (!$s[nocron]) include_once("$s[phppath]/rebuild_f.php");
include('./data/time.php');
$month = month_number(0); $year = year_number(0);

check_mini_job($s[cas]);

if ($HTTP_GET_VARS[size]) $size=$HTTP_GET_VARS[size]; else $size = 1;
if (!$s[top]) $s[top] = 20;
$b = (int)ceil((date('s')+1)/20);

$q = dq("select $s[pr]months$size.userid,$s[pr]months$size.i_m,
$s[pr]link$size.url1,$s[pr]link$size.url2,$s[pr]link$size.url3,
$s[pr]stats$size.linka1,$s[pr]stats$size.linka2,$s[pr]stats$size.linka3,
$s[pr]stats$size.linkb1,$s[pr]stats$size.linkb2,$s[pr]stats$size.linkb3 
from $s[pr]months$size,$s[pr]stats$size,$s[pr]link$size
where $s[pr]months$size.i_m >0 
AND $s[pr]months$size.m='$month' AND $s[pr]months$size.y='$year' 
AND $s[pr]months$size.userid=$s[pr]stats$size.userid 
AND $s[pr]link$size.userid=$s[pr]stats$size.userid 
order by $s[pr]months$size.i_m desc limit $s[top]",1);

while ($n = mysql_fetch_assoc($q))
{ if (!$n["linka$b"])							// nasel?
  { $p = array(1=>1,2,3);
    unset ($p[$b]);
    srand ((double) microtime() * 10000000);
    $b = (int)array_rand($p);
    if (!$n["linka$b"]) { unset ($p[$b]); $b = array_rand ($p); }
  }					// pokud v $d[$b] nic neni tak nema ani jeden ad - nemelo by nastat
  $n[banner] = str_replace('link.php?','link.php?nc=1&',$n["linka$b"]).$n["linkb$b"]; $n[url] = $n["url$b"];
  $a[links] .= unreplace_once_html(parse_part('top.txt',$n));
}

$a[size] = $size;
page_from_template('top.html',$a);

?>