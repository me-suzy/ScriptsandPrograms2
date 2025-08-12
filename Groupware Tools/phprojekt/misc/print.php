<?php

// print.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: print.php,v 1.12 2005/06/20 14:50:04 paolo Exp $

// FIXME: for what is this good for?!
error_reporting(0);

$path_pre = "../";
$include_path = $path_pre."lib/lib.inc.php";
include_once $include_path;
echo set_page_header();

if ($module == "mail") {
  $result = db_query("select ID,sender,recipient,cc,date_received,remark,subject,body,von
                        from ".DB_PREFIX."mail_client
                       where ID = '$ID'") or db_die();
  $row = db_fetch_row($result);
  // check permission
  if ($row[8] <> $user_ID) die("You are not allowed to do this!");

  echo "<table><tr><td width=30>&nbsp;</td>\n";
  echo "<td><br /><br /><b>".__('Sender').":</b> $row[1]&nbsp;<br />\n"; // sender
  echo "<b>".__('Receiver').":</b> $row[2]&nbsp;<br />\n"; // recipient
  echo "<b>Cc:</b> $row[3]&nbsp;<br />\n"; // cc
  echo "<b>".__('Date').":</b> ".show_iso_date1($row[4])."&nbsp;<br />\n"; // date
  echo "<b>".__('Comments')."</b> ".nl2br(htmlspecialchars($row[5]))." &nbsp;<br />\n"; // remark
  echo "<b>".__('Subject').":</b> $row[6]&nbsp;<br /><br />\n";  // subject
  echo nl2br($row[7]); // body
  echo "</td></tr></table>\n";
}

if ($module == "notes") {
    $result = db_query("select ID,von,name,remark,contact,ext,div1,div2,projekt,sync1,sync2,acc,acc_write
                        from ".DB_PREFIX."notes
                         where (acc like 'system' or ((von = '$user_ID' or acc like 'group' or acc like '%\"$user_kurz\"%') and $sql_user_group)) and
                                ".DB_PREFIX."notes.ID = '$ID'") or db_die();
  $row = db_fetch_row($result);
    if (!$row[0]) { die("You are not privileged to do this!"); }

  $body = nl2br(htmlspecialchars($row[3]));
  $title = htmlspecialchars($row[2]);
  echo "<table><tr><td width=30>&nbsp;</td>\n";
  echo "<td>".__('Notes').": <b>$title</b><br /><br />\n";
  echo "<img src='$img_path/s.gif' width='300' height='1' alt='' /><br /><br />\n";
  echo "$body </td></tr></table>\n";
}
if ($module == "todo") {
    $result = db_query("select ID,von,remark,note,deadline,datum,contact,project,ext,div1,div2,sync1,sync2,acc,acc_write
                        from ".DB_PREFIX."todo
                         where (acc like 'system' or ((von = '$user_ID' or acc like 'group' or acc like '%\"$user_kurz\"%') and $sql_user_group)) and
                                ".DB_PREFIX."todo.ID = '$ID'") or db_die();
  $row = db_fetch_row($result);
    if (!$row[0]) { die("You are not privileged to do this!"); }

  $body = nl2br(htmlspecialchars($row[3]));
  $title = htmlspecialchars($row[2]);
  echo "<table><tr><td width=30>&nbsp;</td>\n";
  echo "<td colspan='2'>".__('Title').": <b>$title</b> </td></tr>\n";
  echo "<tr><td></td><td>".__('Describe your request').":</td><td>$body </td></tr>\n";
  echo "<tr><td></td><td>".__('From')." :</td><td>$row[1] </td></tr>\n";
  echo "<tr><td></td><td>".__('Assigned').":</td><td>$row[8] </td></tr>\n";
  echo "<tr><td></td><td>".__('Deadline').":</td><td>$row[4] </td></tr>\n";
  echo "<tr><td></td><td>".__('Date').":</td><td>$row[5] </td></tr>\n";
  echo "<tr><td></td><td>".__('Contact').":</td><td>$row[6] </td></tr>\n";
  echo "<tr><td></td><td>".__('Project').":</td><td>$row[7] </td></tr>\n";
  echo"</table>\n";
}

if ($module == "forum") {
  $result = db_query("select ID,titel,remark,von,gruppe
                        from ".DB_PREFIX."forum
                             where ID = '$ID'") or db_die();
  $row = db_fetch_row($result);
  // check permission
  if ($row[4] <> $user_group) { die("You are not allowed to do this!"); }
  echo "<table><tr><td width=30>&nbsp;</td>\n";
  echo "<td>".__('Forum').": <b>".htmlspecialchars($row[1])."</b><br /><br />\n";
  // fetch name of author
  $result2 = db_query("select vorname, nachname
                         from ".DB_PREFIX."users
                        where ID = '$row[3]'") or db_die();
  $row2 = db_fetch_row($result2);
  echo __('From').": <b>$row2[1]</b>, $row2[0]<br /><br />\n";
  echo "<img src='$img_path/s.gif' width='300' height='1' alt='' /><br /><br />\n";
  echo nl2br(htmlspecialchars($row[2]))." </td></tr></table>\n";
}

if (($module == "proj")||($module=="projects")) {
  $result = db_query("select ID,name,ende,personen,wichtung,status,statuseintrag,anfang,
                             gruppe,chef,typ,parent,ziel,note,kategorie,contact,stundensatz,budget
                        from ".DB_PREFIX."projekte
                             where ID = '$ID' and $sql_user_group") or db_die();
  $row = db_fetch_row($result);
  // check permission
  if (!$row[0]) { die("You are not allowed to do this!"); }
  // output
  echo "<table cellpadding=3 cellspacing=3 border=0>\n";
  echo "<tr><td>".__('Project').":</td><td><b>$row[1]&nbsp;</b><br /></td></tr>\n";
  echo "<tr><td>".__('Begin').":</td><td>$row[7] / ".__('End').": $row[2]</td></tr>\n";
  echo "<tr><td>".__('Priority').":</td><td>$row[4]&nbsp;</td></tr>\n";
  echo "<tr><td>".__('Status').":$row[5]%</td><td>".__('Last status change').": $row[6]&nbsp;</td></tr>\n";
  echo "<tr><td>".__('Leader').":</td><td>".slookup('users','nachname,vorname','ID',$row[9])."&nbsp;</td></tr>\n";
  // fetch name from parent project
  echo "<tr><td>".__('Sub-Project of').":</td><td>".slookup('projekte','name','ID',$row[11])."&nbsp;</td></tr>\n";
  // fetch name of contact
  echo "<tr><td>".__('Contact').":</td><td>".slookup('contacts','nachname,vorname','ID',$row[15])."&nbsp;</td></tr>\n";
  echo "<tr><td>".__('Hourly rate').":</td><td>".PHPR_CUR_SYMBOL." $row[16] </td></tr>\n";
  echo "<tr><td>".__('Calculated budget')."</td><td>".PHPR_CUR_SYMBOL." $row[17]&nbsp;</td></tr>\n";
  echo "<tr><td>".__('Aim').":</td><td>$row[12]&nbsp;</td></tr>\n"; // aim

  // show participants
  echo "<tr><td>".__('Participants').":</td><td>";
  $pers = unserialize($row[3]);
  for ($i=0;$i < count($pers); $i++) {
    echo slookup('users','nachname,vorname','kurz',$pers[$i]).'<br />';
  }
  echo "&nbsp;</td></tr>\n";
  echo "<tr><td>".__('Remark').":</td><td>".nl2br($row[13])."&nbsp;</td></tr>\n";
  echo "</table>\n";
}

// rts
if ($module == "rts") {
  $status_arr = array( "1" => __('pending'), "2" => __('stalled'), "3" => __('moved'), "4" => __('solved'));

  // fetch record
  $result = db_query("select ID,contact,email,submit,recorded,name,note,due_date,status,assigned,priority,remark,solution
                        from ".DB_PREFIX."rts
                       where ID = '$ID'") or db_die();
  $row = db_fetch_row($result);

  // check permission
  if (!$row[0]) { die("You are not allowed to do this!"); }
  echo "<table cellpadding=3 cellspacing=3 border=0>\n";
  echo "<tr><td valign=top>".__('Request').":</td><td>$row[5]</td></tr>\n";    // title
  echo "<tr><td valign=top>".__('Remark').":</td><td>".nl2br($row[6])."</td></tr>\n";  // note

  // fetch name of customer
  if ($row[1] > 0 and PHPR_CONTACTS) {
    $result2 = db_query("select nachname, vorname
                           from ".DB_PREFIX."contacts
                          where ID = '$row[1]'") or db_die();
    $row2 = db_fetch_row($result2);
    $customer = $row2[0].",".$row2[1];
  } // else take email
  else { $customer = $row[2]; }
  echo "<tr><td>".__('Customer').":</td><td>$customer</td></tr>\n"; // from
  echo "<tr><td>".__('Date').":</td><td>$row[3]</td></tr>\n"; // request date
  if ($row[7]) { echo "<tr><td>".__('Due date').":</td><td>$row[7]</td></tr>\n"; } // due date
  echo "<tr><td>".__('Status')."</td><td>".$status_arr[$row[8]]."</td></tr>\n"; // status

  // fetch assigned user
  if ($row[9]) { echo "<tr><td>".__('Assigned')."</td><td>".slookup('users','nachname,vorname','kurz',$row[9])."</td></tr>\n";}
  echo "<tr><td>".__('Priority').":</td><td>$row[10]</td></tr>\n"; // priority
  echo "<tr><td>".__('Remark').":</td><td>".nl2br($row[11])."</td></tr>\n"; // remark
  echo "<tr><td>".__('solve').":</td><td>".nl2br($row[12])."</td></tr>\n"; // solution
  if ($row[18] > 0 and PHPR_PROJECTS) {
    echo "<tr><td>".__('Project').":</td><td>".".slookup('projekte','name','ID',$row[18])."."</td></tr>\n"; // solution
  }
  echo "</table>\n";
}

// calendar - weekly view
if ($module == "calendar") {
  // print week view
  if ($mode == 2) {
    echo "<table cellpadding=0 cellspacing=0 border=1><tr><td valign=top>\n";
    for ($i=0; $i < 7; $i++) {
      $treffer = 0;
      $thisdate = date("Y-m-d", mktime(0,0,0,$month,($day+$i),$year));
      $thisdate2 = date("d.m.Y", mktime(0,0,0,$month,($day+$i),$year));
      // add weekday
      $thisdate2 = $name_day2[$i]." ".$thisdate2;
      // print table for this day
      echo "<table border=0 cellspacing=1 cellpadding=1>\n";
      echo "<tr><td colspan=3 align=left valign=top width=320><b>$thisdate2</b></td></tr>\n";
      $result = db_query("select anfang, ende, event, datum
                            from ".DB_PREFIX."termine
                           where datum = '$thisdate' and
                                 an = '$u_ID'
                        order by datum, anfang") or db_die();
      while ($row = db_fetch_row($result)) {
        $anfang = substr($row[0],0,2).":".substr($row[0],2,2);
        $ende = substr($row[1],0,2).":".substr($row[1],2,2);
        $event = html_out($row[2]);
        echo "<tr><td width=50>$anfang - </td><td width=35>$ende</td><td width=231>$event</td></tr>\n";
        $treffer++;
      }
      // fill in the table with blank lines
      for($e = $treffer; $e < 10; $e++) { echo "<tr><td colspan=3 width=320>&nbsp;</td></tr>\n"; }
      // close table
      echo "</table>\n";

      // change the column after wednesday
      if ($i == 3) {echo "</td><td valign=top>\n"; }
      // draw a separator until the last weekday
      elseif ($i <> 7) {echo "<img src='$img_path/s.gif' width='316' height='1' vspace='2' alt='' />\n"; }
    }
    echo "</td></tr></table>\n";
  }


  // print year view
  elseif ($mode == "year") {
    echo "<table cellpadding=0 cellspacing=0 border=1><tr><td valign=top>\n";
    for ($i = 1; $i <= 12; $i++) {
      $treffer = 0;
      if ($i < 10) { $e = "0".$i; } else { $e = $i; }
      $thismonth = $year."-".$e;
      // print table for this day
      echo "<table border=0 cellspacing=1 cellpadding=1>\n";
      echo "<tr><td colspan=4 align=left valign=top><b>".$name_month[$i]."</b></td></tr>\n";
      $result = db_query("select anfang, ende, event, datum, visi
                            from ".DB_PREFIX."termine
                           where datum like '$thismonth%' and
                                 an = '$u_ID'
                        order by datum, anfang") or db_die();
      $cal_read = stristr(slookup('users', 'acc','ID',$u_ID),'y');
      while ($row = db_fetch_row($result)) {
        $datum = substr($row[3],8,2).".".substr($row[3],5,2);
        $anfang = substr($row[0],0,2).":".substr($row[0],2,2);
        $ende = substr($row[1],0,2).":".substr($row[1],2,2);
        if($u_ID == $user_ID or ($row[4] <> 1 and $cal_read)){$event = html_out($row[2]);}
        echo "<tr id=small><td width=40 valign=top>$datum</td>\n";
        echo "<td width=35 valign=top>$anfang - </td>\n";
        echo "<td width=30 valign=top>$ende </td>\n";
        echo "<td width=211 valign=top>$event</td></tr>\n";
        $treffer++;
      }
      // fill in the table with blank lines
      for($e = $treffer; $e < 10; $e++) { echo "<tr><td colspan=4 width=320>&nbsp;</td></tr>\n"; }
      // close table
      echo "</table>\n";

      if ($i == 6) {echo "</td><td valign=top>\n"; }
      elseif ($i <> 12) {echo "<img src='$img_path/s.gif' width='316' height='1' vspace='2' alt='' />\n"; }
    }
    echo "</td></tr></table>\n";

  } // end year view
} // end calendar

if ($module=="contacts") {
    // fetch values from db
    $tablename['contacts'] = 'contacts';
    require_once($path_pre.'lib/dbman_lib.inc.php');
    $fields = build_array('contacts', $ID, 'forms');
    foreach($fields as $field_name => $field) { $sql_fields[] = $field_name; }
    $result = db_query("select ".implode(',',$sql_fields)."
                          from ".DB_PREFIX."contacts
                         where ID = '$ID'") or db_die();
    $row = db_fetch_row($result);

    echo "<table border=0 rules=none><tr><td><table border=0>\n";
    reset($fields);
    $i = 0;
    foreach($fields as $field_name=>$field) {
        echo "<tr><td><b>".enable_vars($field['form_name'])."</b></td><td>".$row[$i]."</td></tr>\n";
        $i++;
    }
}

echo "\n</body>\n</html>\n";

?>
