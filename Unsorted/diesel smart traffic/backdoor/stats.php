<?php

  require("../conf/sys.conf");
  require("../lib/ban.lib");
  require("../lib/mysql.lib");
  require("bots/errbot");

$db = c();

$membercredits=f(q("SELECT sum(credits_num) as t  FROM members_credits"));
$campaigncredits=f(q("SELECT sum(prev_number) as t  FROM prev"));
echo "<blockquote>";

echo "<br><table border=0 cellspacing=1 cellpadding=2 width=60% bgcolor=AAAAAA><tr bgcolor=#ccDcFc><td>Total Credits</td></tr><tr bgcolor=#ffffff><td>";
echo "<br>Credits in member profiles :". ($membercredits[t]+$campaigncredits[t]);
echo "<br>Stored credits : $membercredits[t]";
echo "<br>Credits in campaigns : $campaigncredits[t] <br>";
echo "</td></tr></table>";
echo "<br><table cellspacing=0 cellpadding=0 width=60%><tr><td>";
include("../src/sysinfo");
echo "</td></tr></table>";

$res=q("select me.id as id, me.login as login, pr.prev_number as credits, cm.title as title, cm.url as url FROM prev pr, campaigns cm, members me WHERE me.id=cm.user_id and pr.cid=cm.id group by cm.id ORDER by pr.prev_number desc");
echo "<br><table border=0 cellspacing=1 cellpadding=2 width=60% bgcolor=AAAAAA><tr bgcolor=#FFFFFF><td><center> Websites </center><br>";
while ($web=f($res)) echo "<br> $web[credits] <a href='$web[url]' target='_ntm3kwebsite'>$web[title]</a> (<a href='login.php?id=$web[id]' target='_ntm3kmemarea'>$web[login]</a>)";
echo "</td></tr></table>";
echo "</blockquote>";
d($db);
require("footer.html");
?>