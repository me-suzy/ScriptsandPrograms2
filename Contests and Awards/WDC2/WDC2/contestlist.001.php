<?php

$ctest = mysql_query("select * from contest_contest where active=2 order by id asc");
print"<br><center><table border=1 cellpadding=5 cellspacing=5>";
print"<tr><td>Contest theme</td><td>Entries</td><td>enter/vote</td></tr>";
while($contests = mysql_fetch_array($ctest)){
$entries = mysql_num_rows(mysql_query("select * from contest_entries where contest=$contests[id]"));
print"<tr><td><a href=\"$GAME_SELF?p=contest&amp;contest=$contests[id]\">$contests[name]</a></td><td>$entries</td>";
if($contests[vote]==1){
print"<td><a href=\"$GAME_SELF?p=vote\">VOTE</a></td>";
}else{
print"<td><a href=\"$GAME_SELF?p=winners&amp;contest=$contests[id]\">WINNERS</a></td>";
}

print"</tr>";
}

  $bnowcontest = mysql_fetch_array(mysql_query("select * from contest_contest where active=1 limit 1"));
  if(!$bnowcontest[id]){
  $nextcontest = mysql_fetch_array(mysql_query("select * from contest_contest where active=0 order by id asc limit 1"));
  mysql_query("update contest_contest set active=1 where id=$nextcontest[id]");
  }
  $bnowcontest = mysql_fetch_array(mysql_query("select * from contest_contest where active=1 limit 1"));
  if(!$bnowcontest[id]){
  $bnowcontest[name]="ERROR";
  $bnowcontest[des]="There is an error.... most likely there is no contest set";
  }

$entries = mysql_num_rows(mysql_query("select * from contest_entries where contest=$bnowcontest[id]"));
print"<tr><td><a href=\"$GAME_SELF?p=contest\">$bnowcontest[name]</a></td><td>$entries</td><td><a href=\"$GAME_SELF?p=enter\">ENTER</a></td></tr>";



$ctest = mysql_query("select * from contest_contest where active=0 order by id asc");
while($contests = mysql_fetch_array($ctest)){
print"<tr><td><a href=\"$GAME_SELF?p=contest&amp;contest=$contests[id]\">$contests[name]</a></td><td>-</td><td>-</td></tr>";
}


print"</table>";