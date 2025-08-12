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

check_mini_job($s[cas]);

if ($HTTP_GET_VARS[size]) $size = $HTTP_GET_VARS[size]; else $size = 1;
if (!$s[userlist]) $s[userlist] = 25;
if ($HTTP_GET_VARS[f]) $from = $HTTP_GET_VARS[f]; else $from = 0;
$b = (int)ceil((date('s')+1)/20);
$q = dq("select $s[pr]link$size.url1,$s[pr]link$size.url2,$s[pr]link$size.url3,
$s[pr]stats$size.linka1,$s[pr]stats$size.linka2,$s[pr]stats$size.linka3,
$s[pr]stats$size.linkb1,$s[pr]stats$size.linkb2,$s[pr]stats$size.linkb3, 
$s[pr]stats$size.userid, $s[pr]stats$size.i_m 
from $s[pr]stats$size,$s[pr]link$size 
where $s[pr]link$size.userid=$s[pr]stats$size.userid 
AND (NOT($s[pr]stats$size.linka1='') OR NOT($s[pr]stats$size.linka2='') OR NOT($s[pr]stats$size.linka3='')) 
order by $s[pr]stats$size.i_m desc limit $from,$s[userlist]",1);

while ($n = mysql_fetch_assoc($q))
{ if (!$n["linka$b"])
  { $p = array(1=>1,2,3);
    unset ($p[$b]);
    srand ((double) microtime() * 10000000);
    $b = (int)array_rand($p);
    if (!$n["linka$b"]) { unset ($p[$b]); $b = array_rand ($p); }
  }					// pokud v $d[$b] nic neni tak nema ani jeden ad - nemelo by nastat
  $n[banner] = str_replace('link.php?','link.php?nc=1&',$n["linka$b"]).$n["linkb$b"]; $n[url] = $n["url$b"];
  $a[links] .= unreplace_once_html(parse_part('list.txt',$n));
}

$q = dq("select count(*) from $s[pr]stats$size where NOT($s[pr]stats$size.linka1='') OR NOT($s[pr]stats$size.linka2='') OR NOT($s[pr]stats$size.linka3='')",1);
$pocet = mysql_fetch_row($q);
if ($pocet[0]>($from+$s[userlist]))
{ $f = $from+$s[userlist];
  $next = '<a href="list.php?size='.$size.'&f='.$f.'">Next page</a>'; }
if ($from)
{ $f = $from-$s[userlist];
  $previous = '<a href="list.php?size='.$size.'&f='.$f.'">Previous page</a>'; }
if (($next) AND ($previous)) $a[prev_next] = "$previous - - $next";
else $a[prev_next] = "$previous $next";


$a[size] = $size;
page_from_template('list.html',$a);

?>