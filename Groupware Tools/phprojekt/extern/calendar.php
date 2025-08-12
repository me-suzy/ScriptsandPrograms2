<?php
// demo "public event list"
// based on parts of:
// m1.php - PHProjekt Version 5.0
// copyright  ©  2000-2004 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Authors: Albrecht Guenther, Dieter Fiebelkorn, Norbert Ku:ck
#--------------------------------------------------------------
# Some text above and/or below the table?
$prologue = "<b>PHProjekt ".__('Event List')."</b>";
$epilogue = "Enjoy!";

# uncomment the following lines if needed
#$all="1";      //show past too
#$make="ress"; // show resource list instead of event list
#--------------------------------------------------------------

// set contstant avoid_auth in order to bypass authentication in lib
define ("avoid_auth","1");
$path_pre="../";
require_once($path_pre."lib/lib.inc.php");
echo set_page_header();
// show list of events
  // all events or only coming events?
  if ($all == 1) {$y = date("Y")-1; $datum2 = "$y-01-01"; }
  else { $datum2 = date("Y")."-".date("m")."-".date("d"); }
// headline
  if ($make == "ress") { echo "<h3>".__('Resource List')."</h3>";}
  else { echo "<h3>".__('Event List')."</h3>"; }
  if($prologue) echo "$prologue<br><br>\n";

  echo "<table border=1 cellspacing=0 cellpadding=3>";
//db action
  if ($make == "ress") {
     $querystr = "select datum, anfang, ende, ressource, an, event, remark 
                    from ".DB_PREFIX."termine 
                   where ressource > 0 and 
                         datum >= '$datum2' 
                order by datum, anfang desc";
  } else {
     $querystr = "select datum, anfang, ende, ressource, an, event, remark  
                    from ".DB_PREFIX."termine 
                   where visi = '2' and 
                         datum >= '$datum2' 
                order by datum, anfang desc";
  }
  $result = db_query($querystr) or db_die();
// table head
  echo "<tr><td width=70 id=ri>".__('Day')."</td><td width=40 id=ri>".__('Begin')."</td><td width=40 id=ri>".__('End')."</td>";
  if ($make == "ress") { echo "<td>".__('Resource')."</td><td>".__('User')."</td></tr>"; }
  else { echo "<td>".__('Text')."</td></tr>"; }
// table body
  while ($row = db_fetch_row($result)) {
    $day = substr($row[0],8,2);
    $month = substr($row[0],5,2);
    $year = substr($row[0],0,4);
    $begin =substr($row[1],0,2).":".substr($row[1],2,2);
    $end =substr($row[2],0,2).":".substr($row[2],2,2);
    echo "<tr>";
    if ($make == "ress") {
      echo "<td id=ri>$day.$month.$year</td><td id=ri>$begin</td><td id=ri>$end</td>\n";
      $result2 = db_query("select name 
                             from ".DB_PREFIX."ressourcen 
                            where ID = '$row[3]'") or db_die();
      while ($row2 = db_fetch_row($result2)) { echo "<td>&nbsp;$row2[0]</td>\n"; }
      $result3 = db_query("select nachname, vorname 
                             from ".DB_PREFIX."users 
                            where ID = '$row[4]'") or db_die();
      while ($row3 = db_fetch_row($result3)) { echo "<td>&nbsp;$row3[0]"." ".substr($row3[1],0,1).".</td></tr>\n"; }
    }
    else {
      $text = "<b>".html_out($row[5])."</b>";
      if($row[6]) $text .= "<br>".nl2br(html_out($row[6]));
      echo "<td id=ri>$day.$month.$year</td><td id=ri>$begin</td><td id=ri>$end</td>\n";
      // output text
      echo "<td>&nbsp;$text</td></tr>\n";
    }
  }
  echo "</table>";

  echo "$epilogue<br><br>\n";
  echo "&nbsp;<a href='javascript:self.print()'>".__('print')."</a>\n";
?>
</body>
</html>
