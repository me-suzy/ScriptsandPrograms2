<?php php_track_vars?>
<?php

include "includes/config.inc";
include "includes/php-dbi.inc";
include "includes/functions.inc";
include "includes/validate.inc";
include "includes/connect.inc";

load_user_preferences ();
load_user_layers ();

include "includes/translate.inc";


// Input time format "235900", duration is minutes
function add_duration ( $time, $duration ) {
  $hour = (int) ( $time / 10000 );
  $min = ( $time / 100 ) % 100;
  $minutes = $hour * 60 + $min + $duration;
  $h = $minutes / 60;
  $m = $minutes % 60;
  $ret = sprintf ( "%d%02d00", $h, $m );
  //echo "add_duration ( $time, $duration ) = $ret <BR>";
  return $ret;
}

// check to see if two events overlap
// time1 and time2 should be an integer like 235900
// duration1 and duration2 are integers in minutes
function times_overlap ( $time1, $duration1, $time2, $duration2 ) {
  //echo "times_overlap ( $time1, $duration1, $time2, $duration2 )<BR>";
  $hour1 = (int) ( $time1 / 10000 );
  $min1 = ( $time1 / 100 ) % 100;
  $hour2 = (int) ( $time2 / 10000 );
  $min2 = ( $time2 / 100 ) % 100;
  // convert to minutes since midnight
  // remove 1 minute from duration so 9AM-10AM will not conflict with 10AM-11AM
  if ( $duration1 > 0 )
    $duration1 -= 1;
  if ( $duration2 > 0 )
    $duration2 -= 1;
  $tmins1start = $hour1 * 60 + $min1;
  $tmins1end = $tmins1start + $duration1;
  $tmins2start = $hour2 * 60 + $min2;
  $tmins2end = $tmins2start + $duration2;
  //echo "tmins1start=$tmins1start, tmins1end=$tmins1end, tmins2start=$tmins2start, tmins2end=$tmins2end<BR>";
  if ( $tmins1start >= $tmins2start && $tmins1start <= $tmins2end )
    return true;
  if ( $tmins1end >= $tmins2start && $tmins1end <= $tmins2end )
    return true;
  if ( $tmins2start >= $tmins1start && $tmins2start <= $tmins1end )
    return true;
  if ( $tmins2end >= $tmins1start && $tmins2end <= $tmins1end )
    return true;
  return false;
}


// first check for any schedule conflicts
if ( strlen ( $hour ) > 0 ) {
  $date = mktime ( 0, 0, 0, $month, $day, $year );
  if ( $TIME_FORMAT == "12" ) {
    $hour %= 12;
    if ( $ampm == "pm" )
      $hour += 12;
  }
  $sql = "SELECT webcal_entry_user.cal_login, webcal_entry.cal_time," .
    "webcal_entry.cal_duration, webcal_entry.cal_name, " .
    "webcal_entry.cal_id, webcal_entry.cal_access, " .
    "webcal_entry_user.cal_status " .
    "FROM webcal_entry, webcal_entry_user " .
    "WHERE webcal_entry.cal_id = webcal_entry_user.cal_id " .
    "AND webcal_entry.cal_date = " . date ( "Ymd", $date ) .
    " AND webcal_entry.cal_time >= 0 " .
    "AND ( webcal_entry_user.cal_status = 'A' OR " .
    "webcal_entry_user.cal_status = 'W' ) " .
    "AND ( ";
  if ( strlen ( $single_user_login ) ) {
    $participants[0] = $single_user_login;
  }
  for ( $i = 0; $i < count ( $participants ); $i++ ) {
    if ( $i ) $sql .= " OR ";
    $sql .= " webcal_entry_user.cal_login = '" . $participants[$i] . "'";
  }
  $sql .= " )";
  //echo "SQL: $sql<P>";
  $res = dbi_query ( $sql );
  if ( $res ) {
    $time1 = sprintf ( "%d%02d00", $hour, $minute );
    $duration1 = sprintf ( "%d", $duration );
    while ( $row = dbi_fetch_row ( $res ) ) {
      // see if either event overlaps one another
      if ( $row[4] != $id ) {
        $time2 = $row[1];
        $duration2 = $row[2];
        if ( times_overlap ( $time1, $duration1, $time2, $duration2 ) ) {
          $overlap .= "<LI>";
          if ( ! strlen ( $single_user_login ) )
            $overlap .= "$row[0]: ";
          if ( $row[5] == 'R' && $row[0] != $login )
            $overlap .=  "(PRIVATE)";
          else {
            $overlap .=  "<A HREF=\"view_entry.php?id=$row[4]";
            if ( $user != $login )
              $overlap .= "&user=$user";
            $overlap .= "\">$row[3]</A>";
          }
          $overlap .= " (" . display_time ( $time2 );
          if ( $duration2 > 0 )
            $overlap .= "-" .
              display_time ( add_duration ( $time2, $duration2 ) );
          $overlap .= ")";
        }
      }
    }
    dbi_free_result ( $res );
  }
}

if ( strlen ( $overlap ) ) {
  $error = translate("The following conflicts with the suggested time") .
    ":<UL>$overlap</UL>";
}


if ( strlen ( $error ) == 0 ) {

  // now add the entries
  if ( $id == 0 ) {
    $res = dbi_query ( "SELECT MAX(cal_id) FROM webcal_entry" );
    if ( $res ) {
      $row = dbi_fetch_row ( $res );
      $id = $row[0] + 1;
    } else {
      $id = 1;
    }
  } else {
    dbi_query ( "DELETE FROM webcal_entry WHERE cal_id = $id" );
    dbi_query ( "DELETE FROM webcal_entry_user WHERE cal_id = $id" );
    dbi_query ( "DELETE FROM webcal_entry_repeats WHERE cal_id = $id" );
  }

  $sql = "INSERT INTO webcal_entry ( cal_id, cal_create_by, cal_date, " .
    "cal_time, cal_mod_date, cal_mod_time, cal_duration, cal_priority, " .
    "cal_access, cal_type, cal_name, cal_description ) " .
    "VALUES ( $id, '$login', ";

  $date = mktime ( 0, 0, 0, $month, $day, $year );
  $sql .= date ( "Ymd", $date ) . ", ";
  if ( strlen ( $hour ) > 0 ) {
    if ( $TIME_FORMAT == "12" ) {
      $hour %= 12;
      if ( $ampm == "pm" )
        $hour += 12;
    }
    $sql .= sprintf ( "%02d%02d00, ", $hour, $minute );
  } else
    $sql .= "-1, ";
  $sql .= date ( "Ymd" ) . ", " . date ( "Gis" ) . ", ";
  $sql .= sprintf ( "%d, ", $duration );
  $sql .= sprintf ( "%d, ", $priority );
  $sql .= "'$access', ";
  if ( $rpt_type != 'none' )
    $sql .= "'M', ";
  else
    $sql .= "'E', ";

  if ( strlen ( $name ) == 0 )
    $name = translate("Unnamed Event");
  $sql .= "'" . $name .  "', ";
  if ( strlen ( $description ) == 0 )
    $description = $name;
  $sql .= "'" . $description . "' )";
  
  $error = "";
  if ( ! dbi_query ( $sql ) )
    $error = "Unable to add entry: " . dbi_error () . "<P><B>SQL:</B> $sql";
  $msg .= "<B>SQL:</B> $sql<P>";
  
  if ( strlen ( $single_user_login ) ) {
    $participants[0] = $single_user_login;
  }

  // build list of users for sending out an email list
  $sql = "SELECT cal_login, cal_lastname, cal_firstname, cal_email " .
    "FROM webcal_user WHERE ( ";
  for ( $i = 0; $i < count ( $participants ); $i++ ) {
    if ( $i ) $sql .= " OR ";
      $sql .= "cal_login = '" . $participants[$i] . "'";
  }
  $sql .= " ) ORDER BY cal_lastname, cal_firstname, cal_login, cal_email";
  $i = 0;
  $res = dbi_query ( $sql );
  if ( $res ) {
    while ( $row = dbi_fetch_row ( $res ) ) {
      $participants_email[$i] = $row[3];
      $participants_name[$i] = $row[2];
      $i++;
    }
  }

  // now add participants
  for ( $i = 0; $i < count ( $participants ); $i++ ) {
    $status = ( $participants[$i] != $login && $require_approvals ) ? "W" : "A";
    $sql = "INSERT INTO webcal_entry_user " .
      "( cal_id, cal_login, cal_status ) VALUES ( $id, '" .
      $participants[$i] . "', '$status' )";
    if ( ! dbi_query ( $sql ) ) {
      $error = "Unable to add to webcal_entry_user: " . dbi_error () .
        "<P><B>SQL:</B> $sql";
      break;
    } else {
      $from = $user_email;
      if ( strlen ( $from ) == 0 && strlen ( $GLOBALS["email_fallback_from"] ) )
        $from = $GLOBALS["email_fallback_from"];
      // only send mail if their email address is filled in
      if ( $participants[$i] != $login && strlen ( $participants_email[$i] ) ) {
        $msg = translate("Hello") . ", " . $participants_name[$i] . ".\n\n" .
          translate("A new appointment has been made for you by") .
          " " . $login .  ". " .
          translate("The subject is") . " \"" . $name . "\"\n\n" .
          translate("Please look on") . " " . translate("Title") . " " .
          ( $GLOBALS["require_approvals"] ?
          translate("to accept or reject this appointment") :
          translate("to view this appointment") ) . ".";
        if ( strlen ( $from ) )
          $extra_hdrs = "From: $from\nX-Mailer: " . translate("Title");
        else
          $extra_hdrs = "X-Mailer: " . translate("Title");
        mail ( $participants_email[$i],
          translate("Title") . " " . translate("Notification") . ": " . $name,
          $msg, $extra_hdrs );
      }
    }
    $msg .= "<B>SQL:</B> $sql<P>";
  }

  // clearly, we want to delete the old repeats, before inserting new...
  dbi_query ( "DELETE FROM webcal_entry_repeats WHERE cal_id = $id");
  // add repeating info
  if ( strlen ( $rpt_type ) && $rpt_type != 'none' ) {
    $freq = ( $rpt_freq ? $rpt_freq : 1 );
    if ( $rpt_end_use )
      $end = sprintf ( "%04d%02d%02d", $rpt_year, $rpt_month, $rpt_day );
    else
      $end = 'NULL';
    if ($rpt_type == 'weekly') {
      $days = ( $rpt_sun ? 'y' : 'n' )
        . ( $rpt_mon ? 'y' : 'n' )
        . ( $rpt_tue ? 'y' : 'n' )
        . ( $rpt_wed ? 'y' : 'n' )
        . ( $rpt_thu ? 'y' : 'n' )
        . ( $rpt_fri ? 'y' : 'n' )
        . ( $rpt_sat ? 'y' : 'n' );
    } else {
      $days = "nnnnnnn";
    }

    $sql = "INSERT INTO webcal_entry_repeats ( cal_id, " .
      "cal_type, cal_end, cal_days, cal_frequency ) VALUES " .
      "( $id, '$rpt_type', $end, '$days', $freq )";
    dbi_query ( $sql );
    $msg .= "<B>SQL:</B> $sql<P>";
  }
}

#print $msg; exit;

if ( strlen ( $error ) == 0 ) {
  $date = sprintf ( "%04d%02d%02d", $year, $month, $day );
  do_redirect ( "$STARTVIEW.php?date=$date" );
}

?>
<HTML>
<HEAD><TITLE><?php etranslate("Title")?></TITLE>
<?php include "includes/styles.inc"; ?>
</HEAD>
<BODY BGCOLOR="<?php echo $BGCOLOR; ?>">

<?php if ( strlen ( $overlap ) ) { ?>
<H2><FONT COLOR="<?php echo $H2COLOR;?>"><?php etranslate("Scheduling Conflict")?></H2></FONT>

<?php etranslate("Your suggested time of")?> <B>
<?php
  $time = sprintf ( "%d%02d00", $hour, $minute );
  echo display_time ( $time );
  if ( $duration > 0 )
    echo "-" . display_time ( add_duration ( $time, $duration ) );
?>
</B> <?php etranslate("conflicts with the following existing calendar entries")?>:
<UL>
<?php echo $overlap; ?>
</UL>

<?php } else { ?>
<H2><FONT COLOR="<?php echo $H2COLOR;?>"><?php etranslate("Error")?></H2></FONT>
<BLOCKQUOTE>
<?php echo $error; ?>
</BLOCKQUOTE>

<?php } ?>


<?php include "includes/trailer.inc"; ?>

</BODY>
</HTML>
