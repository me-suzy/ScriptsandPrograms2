<?php
function findStr($search, $target) {
   $matches = 0;
   $search = strtolower($search);
   $target = strtolower($target);
   $output = "";
   // Create the "search" array, which holds all our search terms
   $search = explode("*",$search); // You could change this to: '$search = explode(" ",$search);' if you wanted your search terms to be split by a space.
   $pos = 0;
   for ($i=0; $i<count($search); $i++) {
       // Check if the current search term is in our target
       if (strpos($target, $search[$i], $pos) != '' && strlen($search[$i])>0) {
           $pos = strpos($target, $search[$i], $pos);
           $matches++;
       }
       if (strlen($search[$i])<1) {
           $matches++;
       }
   }
   if ($matches == count($search)) {
       return true;
   } else {
       return false;
   }
}

////////////////////////////////////
//findStr("search","in this");
////////////////////////////////////







function autolink( &$text, $target='_blank', $nofollow=true )
{
  $urls  =  _autolink_find_URLS( $text );
  if( !empty($urls) ) // i.e. there were some URLS found in the text
  {
    array_walk( $urls, '_autolink_create_html_tags', array('target'=>$target, 'nofollow'=>$nofollow) );
    $text  =  strtr( $text, $urls );
  }
}

function _autolink_find_URLS( $text )
{
  $scheme         =       '(http:\/\/|https:\/\/)';
  $www            =       'www\.';
  $ip             =       '\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}';
  $subdomain      =       '[-a-z0-9_]+\.';
  $name           =       '[a-z][-a-z0-9]+\.';
  $tld            =       '[a-z]+(\.[a-z]{2,2})?';
  $the_rest       =       '\/?[a-z0-9._\/~#&=;%+?-]+[a-z0-9\/#=?]{1,1}';
  $pattern        =       "$scheme?(?(1)($ip|($subdomain)?$name$tld)|($www$name$tld))$the_rest";

  $pattern        =       '/'.$pattern.'/is';
  $c              =       preg_match_all( $pattern, $text, $m );
  unset( $text, $scheme, $www, $ip, $subdomain, $name, $tld, $the_rest, $pattern );
  if( $c )
  {
    return( array_flip($m[0]) );
  }
  return( array() );
}

function _autolink_create_html_tags( &$value, $key, $other=null )
{
  $target = $nofollow = null;
  if( is_array($other) )
  {
    $target      =  ( $other['target']   ? " target=\"$other[target]\"" : null );
    $nofollow    =  ( $other['nofollow'] ? ' rel="nofollow"'            : null );
  }
  $value = "<a href=\"$key\"$target$nofollow>$key</a>";
}





function rand_pass() {
  $array = array(
                 "ap","dus","tin","rog","sti","rev","pik","sty","lev","qot","rel","vid",
                 "kro","xo","pro","wia","axi","jer","foh","mu","ya","zol","gu","pli","cra",
                 "den","bi","sat","ry","qui","wip","fla","gro","tav","peh","gil","lot",
                 "kal","zan","noc","bat","tev","lun","pal","hom","cun","wos","vox"
                 );
  $num_letters = 8;
  $uppercased = 3;
  mt_srand ((double)microtime()*1000000);
  for($i=0; $i<$num_letters; $i++)
    $pass .= $array[mt_rand(0, (count($array) - 1))];
  for($i=1; $i<strlen($pass); $i++) {
    if(substr($pass, $i, 1) == substr($pass, $i-1, 1))
      $pass = substr($pass, 0, $i) . substr($pass, $i+1);
  }
  for($i=0; $i<strlen($pass); $i++) {
    if(mt_rand(0, $uppercased) == 0)
      $pass = substr($pass,0,$i) . strtoupper(substr($pass, $i,1)) . substr($pass, $i+1);
  }
  $pass = substr($pass, 0, $num_letters);
  return $pass;
}







function usersonline($minutes,$checkwhen){
if(!$minutes){
$minutes=10;
}
$seconds = $minutes * 60;

$tpsel = mysql_query("select * from users order by lastseen desc");

$ctime = time();

while ($pl = mysql_fetch_array($tpsel)) {
        $span = ($ctime - $pl[lastseen]);
        if ($span <= $seconds&&$checkwhen=="on") {
                if ($pl[position] == Admin) {
                        $ton = "$ton <font class=admin>[@<A href=\"$GAME_SELF?p=view&amp;view=$pl[id]\">$pl[username]</a>]&nbsp;&nbsp;</font>";
                } elseif ($pl[position] == Moderator) {
                        $ton = "$ton <font class=mod>[%<A href=\"$GAME_SELF?p=view&amp;view=$pl[id]\">$pl[username]</a>]&nbsp;&nbsp;</font>";
                } else {
                        $ton = "$ton [<A href=\"$GAME_SELF?p=view&amp;view=$pl[id]\">$pl[username]</a>]&nbsp;&nbsp; ";
                }
        }elseif($span >= $seconds&&$checkwhen=="off"){
        $charpsel = mysql_fetch_array(mysql_query("select * from characters where id=$pl[activechar] and owner=$pl[id]"));
        $ton = "$ton [<A href=\"$GAME_SELF?p=view&amp;view=$pl[id]\">$pl[username]</a>]&nbsp;&nbsp; ";
        }
}
return $ton;

}



    function getFileExtension($str) {

        $i = strrpos($str,".");
        if (!$i) { return ""; }

        $l = strlen($str) - $i;
        $ext = substr($str,$i+1,$l);

        return $ext;

    }

function getcontestnumber() {
$contest = mysql_fetch_array(mysql_query("select * from contest_contest where active=1 limit 1"));
if(!$contest[id]){ $contest[id]="none"; }
return $contest[id];
}



function getvotenumber() {
$contest = mysql_fetch_array(mysql_query("select * from contest_contest where vote=1 limit 1"));
if(!$contest[id]){ $contest[id]="none"; }
return $contest[id];
}




function getwinnumber() {
$contest = mysql_fetch_array(mysql_query("select * from contest_contest where end=1 limit 1"));
if(!$contest[id]){ $contest[id]="none"; }
return $contest[id];
}