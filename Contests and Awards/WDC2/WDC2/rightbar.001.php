<?php
  $nowcontest = mysql_fetch_array(mysql_query("select * from contest_contest where active=1 limit 1"));
  if(!$nowcontest[id]){
  $nextcontest = mysql_fetch_array(mysql_query("select * from contest_contest where active=0 order by id asc limit 1"));
  mysql_query("update contest_contest set active=1 where id=$nextcontest[id]");
  }
  $nowcontest = mysql_fetch_array(mysql_query("select * from contest_contest where active=1 limit 1"));
  if(!$nowcontest[id]){
  $nowcontest[name]="ERROR";
  $nowcontest[des]="There is an error.... most likely there is no contest set";
  }



  $nowvote = mysql_fetch_array(mysql_query("select * from contest_contest where vote=1 limit 1"));
  if(!$nowvote[id]){
  $nextcontest = mysql_fetch_array(mysql_query("select * from contest_contest where vote=0 and active=2 order by id asc limit 1"));
  mysql_query("update contest_contest set vote=1 where id=$nextcontest[id]");
  }
  $nowvote = mysql_fetch_array(mysql_query("select * from contest_contest where vote=1 limit 1"));
  if(!$nowvote[id]){
  $nowvote[name]="None";
  $nowvote[des]="No voting round currently? odd";
  }



$entereded = mysql_fetch_array(mysql_query("select * from contest_entries where user=$user[id] and contest=$nowcontest[id]"));
if($entereded[id]){
print"You have entered<br><a href=\"$GAME_SELF?p=entry&amp;view=$entereded[id]\" alt=\"\"><img src=\"$entereded[thumbnail]\" border=\"0\"></a>";
}
print"Current contest = <a href=\"$GAME_SELF?p=contest\">$nowcontest[name]</a><br>
Current voting = <a href=\"$GAME_SELF?p=vote\">$nowvote[name]</a><br>
<a href=\"$GAME_SELF?p=enter\">Enter</a>";



include("clocktower.001.php");
?>
<p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but04.gif" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
</form>
</p>