   <?php

  $contest = mysql_fetch_array(mysql_query("select * from contest_contest where active=1 limit 1"));
  if(!$contest[id]){
  $nextcontest = mysql_fetch_array(mysql_query("select * from contest_contest where active=0 order by id asc limit 1"));
  mysql_query("update contest_contest set active=1 where id=$nextcontest[id]");
  }
  $contest = mysql_fetch_array(mysql_query("select * from contest_contest where active=1 limit 1"));
  if(!$contest[id]){
  $contest[name]="ERROR";
  $contest[des]="There is an error.... most likely there is no contest set";
  }

  print"Current contest theme <h1>$contest[name]</h1>$contest[des]";




   if($user[position]=="Guest"){
   print"Guests cannot enter.";
   return;
   }

    print"<form action=\"$GAME_SELF?p=upload\" method=\"POST\" enctype=\"multipart/form-data\">
    <input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"200000\">

    <p>Image: <input type=\"file\" name=\"imgfile\"><br>
    <br>
    <input type=\"submit\" value=\"Upload Image\">
    </form>";