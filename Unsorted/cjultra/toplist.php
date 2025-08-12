<?php
// Cjultra v2.1

if (ini_get('register_globals') != 1) {
    $supers = array('_REQUEST','_ENV','_SERVER','_COOKIE','_GET','_POST');
    foreach ($supers as $__s) {
        if (is_array($$__s) == true) extract($$__s, EXTR_OVERWRITE);
    }
    unset($supers);
}

include_once("./common.php");
$linkid = db_connect();
if (!$linkid) error_message(sql_error());
$header = implode('',file("topheader.txt"));
$lines = implode('',file("toplines.txt"));
$footer = implode('',file("topfooter.txt"));
echo "$header\n";

$query = "select b11 from settings";
$result = mysql_query($query);
$data = mysql_fetch_row($result);
$cols = floor($data[0] / 100);
$rows = $data[0] - (100 * $cols);
if ($cols < 1) $cols = 1;
if ($rows < 1) $rows = 1;

$day = date("w");
$yday = date("w", time() - 86400);
$hour = date("G");

$query2 = "select * from day";
$result2 = mysql_query($query2);
if(!$result2) error_message(sql_error());


while ($data2 = mysql_fetch_array($result2)) {
      for ($i = 0; $i <= $hour; $i ++) {
              $rtoday[$data2["z"]] += $data2["zr$i"];
      }
}

arsort($rtoday);
for ($r = 0; $r < $rows; $r++) {
    echo "<tr>";
    $i = 0;
    while ($list = each($rtoday)) {
          $d = $list[0];
          $j = ($cols * $r) + $i + 1;
          if (!$data[a21]) $data[a21] = $d;
          $query4 = "select * from trade where a1 = '$d'";
          $result4 = mysql_query($query4);
          if(mysql_num_rows($result4) != 0) {
          $data4 = mysql_fetch_array($result4);
          if (!$data4["a21"]) $data4["a21"] = $d;
          $lines2 = eregi_replace("\*domain\*",$data4["a1"],$lines);
          $lines2 = eregi_replace("\*number\*","$j",$lines2);
          $lines2 = eregi_replace("\*url\*",$data4["a2"],$lines2);
          $lines2 = eregi_replace("\*name\*",$data4["a21"],$lines2);
          $lines2 = eregi_replace("\*hits\*","$list[1]",$lines2);
          $lines2 = eregi_replace("<tr>","",$lines2);
          $lines2 = eregi_replace("</tr>","",$lines2);
          echo $lines2;
          $i++;
          if ($i == $cols) break;
          }
    }
    echo "</tr>";
    if (!($list = each($rtoday))) break;
    prev($rtoday);
}

echo "$footer\n";

?>
