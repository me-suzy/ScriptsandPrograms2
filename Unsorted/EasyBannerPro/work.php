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


include("./common.php");
$si = $HTTP_GET_VARS[size];
// work.php?n=cisloclena&size=size

if (!$si) $si = 1;

$z = mysql_query("select number,c0,c1,c2,c3,c4,c5,category,userid from $s[pr]stats$si where number = '$HTTP_GET_VARS[n]'");
$e = mysql_fetch_row($z);

$b = (int)ceil((date('s')+1)/20);	// ad 1-3  // musi se prevest na integer !
$hour = date('G',$s[cas]);

if ($s[way2])
{ $z = mysql_query("select max(number) from $s[pr]members");
  $data = mysql_fetch_row($z);
  list($usec,$sec) = explode(' ',microtime()); srand ((float) ($sec+($usec*100000)));
  $i=rand(0,$data[0]);
}

if ($e[0])  // exists
{ $r = mysql_query("SELECT hits FROM $s[pr]ip WHERE number='$HTTP_GET_VARS[n]' AND ip='$HTTP_SERVER_VARS[REMOTE_ADDR]'");
  $u = mysql_fetch_row($r);
  if ($u[0]) mysql_query("update $s[pr]ip set hits = hits + 1 where number='$HTTP_GET_VARS[n]' AND ip='$HTTP_SERVER_VARS[REMOTE_ADDR]'");
  else mysql_query("INSERT INTO $s[pr]ip VALUES ('$HTTP_GET_VARS[n]','$HTTP_SERVER_VARS[REMOTE_ADDR]','1')");
  if ($u[0]<$s[count_ip])
  { mysql_query("update $s[pr]stats$si set last = '$s[cas]', i_m = i_m+1, earned = (earned+exratio), i_nu = (i_nu+exratio) where number = '$HTTP_GET_VARS[n]'");
    mysql_query("update $s[pr]day set m$hour = m$hour + 1 where number = '$HTTP_GET_VARS[n]' and size = '$si'");
    $c = 1;
  }
  if (($c) OR (!$s[def_only]))	// hledat ad jen kdyz nema mit default $k
  { if ( (!$e[1]) AND ($s["usecats$si"]) ) $k=" AND (category='$e[2]' OR category='$e[3]' OR category='$e[4]' OR category='$e[5]' OR category='$e[6]')";
    if ($s[way2]) $z = mysql_query("select number,linka1,linka2,linka3,linkb1,linkb2,linkb3 from $s[pr]stats$si where number >= $i AND (i_nu >= 1 OR c_nu > 0) $k AND approved=1 AND enable=1 AND NOT(number=$HTTP_GET_VARS[n]) ORDER BY weight desc,number LIMIT 1");
    else $z = mysql_query("select number,linka1,linka2,linka3,linkb1,linkb2,linkb3,MD5(RAND()) AS m from $s[pr]stats$si where (i_nu >= 1 OR c_nu > 0) $k AND approved=1 AND enable=1 AND accept=1 AND NOT(number=$HTTP_GET_VARS[n]) ORDER BY weight desc,rand() LIMIT 1");
    $d = mysql_fetch_row($z);
    if (($s[way2]) AND (!$d[0]))  			// nasel?
    { $z = mysql_query("select number,linka1,linka2,linka3,linkb1,linkb2,linkb3 from $s[pr]stats$si where number<$i $k AND (i_nu >= 1 OR c_nu > 0) AND approved=1 AND enable=1 AND accept=1 AND NOT(number=$HTTP_GET_VARS[n]) ORDER BY weight desc,number LIMIT 1");
      $d = mysql_fetch_row($z); }
    if (!$d[$b]) 
    { $p = array(1=>1,2,3);
      unset ($p[$b]);
      srand ((double) microtime() * 10000000);
      $b = (int)array_rand($p);
      if (!$d[$b]) { unset ($p[$b]); $b = array_rand ($p); }
    }
  }					// pokud v $d[$b] nic neni tak nema ani jeden ad - nemelo by nastat
}
elseif ($s[after]) // clen ktery posila impression neexistuje ale maji se mu zobrazovat ads
{ if ($s[way2]) $z = mysql_query("select number,linka1,linka2,linka3,linkb1,linkb2,linkb3 from $s[pr]stats$si where number >= $i AND (i_nu >= 1 OR c_nu > 0) AND approved=1 AND enable=1 AND accept=1 ORDER BY weight desc,number LIMIT 1");
  else $z = mysql_query("select number,linka1,linka2,linka3,linkb1,linkb2,linkb3,MD5(RAND()) AS m from $s[pr]stats$si where (i_nu >= 1 OR c_nu > 0) AND approved=1 AND enable=1 AND accept=1 ORDER BY weight desc,m LIMIT 1");
  $d = mysql_fetch_row($z);
  if (($s[way2]) AND (!$d[0]))  			// nasel?
  { $z = mysql_query("select number,linka1,linka2,linka3,linkb1,linkb2,linkb3 from $s[pr]stats$si where number < $i AND (i_nu >= 1 OR c_nu > 0) AND approved=1 AND enable=1 AND accept = 1 AND (i_nu >= 1 OR c_nu > 0) ORDER BY weight desc,number LIMIT 1");
    $d = mysql_fetch_row($z); }
  //$d[1] = $d[2] = ''; $b = 2; // test only
  if (!$d[$b])							// nasel?
  { $p = array(1=>1,2,3);
    unset ($p[$b]);
    srand ((double) microtime() * 10000000);
    $b = (int)array_rand($p);
    if (!$d[$b]) { unset ($p[$b]); $b = array_rand ($p); }
  }					// pokud v $d[$b] nic neni tak nema ani jeden ad - nemelo by nastat
}
else $sp = 1;		// co posila neexistuje

if ($d[$b]) // mame ad nejakyho clena
{ mysql_query("update $s[pr]stats$si set i_nu = i_nu-1,i_w = i_w+1 where number = $d[0]");
  mysql_query("update $s[pr]b$si set i$b = i$b+1 where number = $d[0]");
  mysql_query("update $s[pr]day set w$hour = w$hour + 1 where number = '$d[0]' and size = '$si'");
  $b1 = $b + 3;
  if (!$d[$b1]) $ba = str_replace('<_>',$HTTP_GET_VARS[n],$d[$b]); // data tam from pokud je to raw html
  elseif ($c) $ba = $d[$b].$HTTP_GET_VARS[n].'&b='.$b.$d[$b1];	// pokud jeste nema dost IP, tak tam dame jeho cislo
  else $ba = $d[$b].'&b='.$b.$d[$b1];
  //$ba = str_replace('%7EUSER%7E',$e[8],$ba); // pokud je referal primo v adu
}
else
{ if ($c) $n = $HTTP_GET_VARS[n]; else $n = 0; // pokud jeste nema dost IP, tak tam dame jeho cislo
  $ba = str_replace('%7EUSER%7E',$e[8],def_ban($si,$e[7],$sp,$n));
}
$ba = unreplace_ad(str_replace('~USER~',$e[8],$ba));

if ($HTTP_GET_VARS[j])
{ $ba = str_replace("\r","');\ndocument.write('",str_replace("\n",'',str_replace("'","\'",str_replace("\\",'\\\\',$ba))));
  $l = str_replace("\r","');\ndocument.write('",str_replace("\n",'',str_replace("'","\'",$s["htmllogo$si"]))); if (!$l) $l = '';
  $l = str_replace('~USER~',$e[8],$l);
  if ($s["logoleft$si"])
  { $w = $s["w$si"] + $s["logow$si"];
    echo "<!--
    document.write('<table border=0 cellpadding=0 cellspacing=0 width=$w><tr>');
    document.write('<td valign=\"top\" align=\"right\">$l</td>');
    document.write('<td valign=\"top\" align=\"left\">$ba</td></tr></table>');
    //-->"; 
  }
  else
  { $h = $s["h$si"] + $s["logoh$si"];
    echo "<!--
    document.write('<table border=0 cellpadding=0 cellspacing=0 height=$h>');
    document.write('<tr><td valign=\"bottom\" align=\"center\">$ba</td></tr>');
    document.write('<tr><td valign=\"top\" align=\"center\">$l</td></tr></table>');
    //-->";
  }
}
else
{ 
$l = str_replace('~USER~',$e[8],$s["htmllogo$si"]);
  if ($s["logoleft$si"])
  { $w = $s["w$si"] + $s["logow$si"];
    echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"><HTML><HEAD>
    <META http-equiv=Content-Type content="text/html;"></HEAD>
    <BODY leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><table border=0 cellpadding=0 cellspacing=0 width='.$w.'><tr>
    <td valign="top" align="right">'.$l.'</td>
    <td valign="top" align="left">'.$ba.'</td></tr>
    </table></BODY></HTML>'; }
  else
  { $h = $s["h$si"] + $s["logoh$si"];
    echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"><HTML><HEAD>
    <META http-equiv=Content-Type content="text/html;"></HEAD>
    <BODY leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><table border=0 cellpadding=0 cellspacing=0 height='.$h.'>
    <tr><td valign="bottom" align="center">'.$ba.'</td></tr>
    <tr><td valign="top" align="center">'.$l.'</td></tr>
    </table></BODY></HTML>';
  }
}

exit;


###############################################################################

function unreplace_ad($x) {
// podobne jako unreplace_once_html
if (!$x) return $x;
$x = ereg_replace("''","'",ereg_replace("[\]",'',$x));
return ereg_replace('&#92;','\\',ereg_replace('&#039;',"'",$x));
}

###############################################################################

function def_ban($si,$c,$sp,$n) {
global $s;
$b = (int)ceil((date('s')+1)/20);  // ad od 1 do 3
if ($sp)
{ $q = mysql_query("select ad$b from $s[pr]def_ads where c0 = 9 AND size = '$si' AND enable = 1 limit 1");
  $r = mysql_fetch_row($q); return $r[0]; }
else
{ $q = mysql_query("select ad$b from $s[pr]def_ads where (c0 != 9 AND (c0=1 OR c1='$c' OR c2='$c' OR c3='$c' OR c4='$c' OR c5='$c')) AND size = '$si' AND enable = 1");
  $r = mysql_fetch_row($q); return str_replace('<_>',$n,$r[0]); }
}

###############################################################################

?>