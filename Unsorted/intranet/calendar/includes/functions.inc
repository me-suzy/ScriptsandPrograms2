<?php



// determine which browser
// currently supported return values:
//	Mozilla (open source Mozilla 5.0) = "Mozilla/5"
//	Netscape (3.X, 4.X) = "Mozilla/[3,4]"
//	MSIE (4.X) = "MSIE 4"
function get_web_browser () {
  if ( ereg ( "MSIE [0-9]", getenv ( "HTTP_USER_AGENT" ) ) )
    return "MSIE";
  if ( ereg ( "Mozilla/[234]", getenv ( "HTTP_USER_AGENT" ) ) )
    return "Netscape";
  if ( ereg ( "Mozilla/[5678]", getenv ( "HTTP_USER_AGENT" ) ) )
    return "Mozilla";
  return "Uknown";
}


// log a debug message
function do_debug ( $msg ) {
  // log to /tmp/webcal-debug.log
  //error_log ( date ( "Y-m-d H:i:s" ) .  "> $msg\n",
  //  3, "/tmp/webcal-debug.log" );
  //error_log ( date ( "Y-m-d H:i:s" ) .  "> $msg\n",
  //  2, "sockieman:2000" );
}


// send a redirect to the specified page
function do_redirect ( $url ) {
  Header ( "Location: $url" );
  echo "<HTML><HEAD><TITLE>Redirect</TITLE></HEAD><BODY>" . 
    "Please go <A HREF=\"" . $url . "\">here</A>.</BODY></HTML>.\n";
  exit;
}


// send an HTTP login request
function send_http_login () {
  Header ( "WWW-Authenticate: Basic realm=\"" . translate("Title") . "\"");
  Header ( "HTTP/1.0 401 Unauthorized" );
  echo "<HTML><HEAD><TITLE>Unauthorized</TITLE></HEAD><BODY>\n" .
    "<H2>" . translate("Title") . "</H2>" . translate("You are not authorized") .
    "\n</BODY></HTML>\n";
  exit;
}



// Load the current user's preferences as global variables.
function load_user_preferences () {
  global $login, $browser;

  $browser = get_web_browser ();

  // Note: default values are set in config.inc
  $res = dbi_query (
    "SELECT cal_setting, cal_value FROM webcal_user_pref " .
    "WHERE cal_login = '$login'" );
  if ( $res ) {
    while ( $row = dbi_fetch_row ( $res ) ) {
      $cmd = "\$GLOBALS[" . $row[0] . "] = \"" . $row[1] . "\";";
      eval ( $cmd );
    }
    dbi_free_result ( $res );
  }
}



// Load current user's layer info and stuff it into layer global variable.
function load_user_layers () {
  global $login;
  global $layers;


  $res = dbi_query (
    "SELECT cal_layerid, cal_layeruser, cal_color, cal_dups FROM webcal_user_layers " .
    "WHERE cal_login = '$login' ORDER BY cal_layerid");
  if ( $res ) {

    $count = 0;

    while ( $row = dbi_fetch_row ( $res ) ) {
      $layers[$count] = array("cal_layerid" => $row[0], "cal_layeruser" => $row[1], "cal_color" => $row[2], "cal_dups" => $row[3]);
      $count++;
    }

    dbi_free_result ( $res );
  }
}




// Build the HTML for the event popup (but don't print it yet since we
// don't want this HTML to go inside the table for the month).
function build_event_popup ( $divname, $description, $time ) {
  $ret = "<DIV ID=\"" . $divname .
    "\" STYLE=\"position: absolute; z-index: 20; visibility: hidden; top: 0px; left: 0px;\">\n" .
    "<TABLE BORDER=\"0\" WIDTH=\"30%\"><TR><TD BGCOLOR=\"" .
    $GLOBALS["POPUP_FG"] . "\">\n" .
    "<TABLE BORDER=\"0\" WIDTH=\"100%\"><TR><TD BGCOLOR=\"" .
    $GLOBALS["POPUP_BG"] . "\">\n" .
    "<FONT COLOR=\"" . $GLOBALS["POPUP_FG"] . "\">";
  
  if ( strlen ( $time ) )
    $ret .= "<B>" . translate ("Time") . ":</B> $time<BR>";
  $ret .= "<B>" . translate ("Description") . ":</B>\n";
  $ret .= htmlspecialchars ( $description );
  $ret .= "</FONT></TD></TR></TABLE>\n" .
    "</TD></TR></TABLE>\n" .
    "</DIV>\n";
  return $ret;
}




// Print the HTML for one day's events in the month view.
// params:
//   $id - event id
//   $date - date (not used)
//   $time - time (in HHMMSS format)
//   $name - event name
//   $description - long description of event
//   $status - event status
//   $pri - event priority
//   $access - event access
//   $event_owner - user associated with this event
//   $hide_icons - hide icons to make printer-friendly
function print_entry ( $id, $date, $time, $duration,
  $name, $description, $status,
  $pri, $access, $event_owner, $hide_icons ) {
  global $eventinfo, $login, $user;
  static $key = 0;
  
  global $layers;


  echo "<FONT SIZE=\"-1\">";

  if ( $login != $event_owner && strlen ( $event_owner ) )
  {
    $class = "layerentry";
  }
  else
  {
    $class = "entry";
    if ( $status == "W" ) $class = "unapprovedentry";
  }

  if ( $pri == 3 ) echo "<B>";
  if ( ! $hide_icons ) {
    $divname = "eventinfo-$id-$key";
    $key++;
    echo "<A CLASS=\"$class\" HREF=\"view_entry.php?id=$id";
    if ( strlen ( $user ) > 0 )
      echo "&user=" . $user;
    echo "\" onMouseOver=\"window.status='" . translate("View this entry") .
      "'; show('$divname'); return true;\" onMouseOut=\"hide('$divname'); return true;\">";
    echo "<IMG SRC=\"circle.gif\" WIDTH=\"5\" HEIGHT=\"7\" ALT=\"view icon\" BORDER=\"0\">";
  }


  if ( $login != $event_owner && strlen ( $event_owner ) )
  {
    for($index = 0; $index < sizeof($layers); $index++)
    {
        if($layers[$index]['cal_layeruser'] == $event_owner)
        {
            echo("<FONT COLOR=\"" . $layers[$index]['cal_color'] . "\">");
        }
    }
  }


  $timestr = "";
  if ( $time >= 0 ) {
    if ( $GLOBALS["TIME_FORMAT"] == "24" ) {
      printf ( "%02d:%02d", $time / 10000, ( $time / 100 ) % 100 );
    } else {
      $h = ( (int) ( $time / 10000 ) ) % 12;
      if ( $h == 0 ) $h = 12;
      echo $h;
      $m = ( $time / 100 ) % 100;
      if ( $m > 0 )
        printf ( ":%02d", $m );
      echo ( (int) ( $time / 10000 ) ) < 12 ? translate("am") : translate("pm");
    }
    echo "&gt;";
    $timestr = display_time ( $time );
    if ( $duration > 0 ) {
      // calc end time
      $h = (int) ( $time / 10000 );
      $m = ( $time / 100 ) % 100;
      $m += $duration;
      $d = $duration;
      while ( $m >= 60 ) {
        $h++;
        $m -= 60;
      }
      $end_time = sprintf ( "%02d%02d00", $h, $m );
      $timestr .= " - " . display_time ( $end_time );
    }
  }
  if ( $login != $user && $access == 'R' && strlen ( $user ) )
    echo "(" . translate("Private") . ")";

  else
  if ( $login != $event_owner && $access == 'R' && strlen ( $event_owner ) )
    echo "(" . translate("Private") . ")";
  else
  if ( $login != $event_owner && strlen ( $event_owner ) )
  {
    echo htmlspecialchars ( $name );
    echo ("</FONT>");
  }

  else
    echo htmlspecialchars ( $name );

  echo "</A>";
  if ( $pri == 3 ) echo "</B>";
  echo "</FONT><BR>";
  if ( ! $hide_icons ) {
    if ( $login != $user && $access == 'R' && strlen ( $user ) )
      $eventinfo .= build_event_popup ( $divname,
        translate("This event is confidential"), "" );

    else
    if ( $login != $event_owner && $access == 'R' && strlen ( $event_owner ) )
      $eventinfo .= build_event_popup ( $divname,
        translate("This event is confidential"), "" );

    else
      $eventinfo .= build_event_popup ( $divname, $description, $timestr );
  }
}



// Read all the events for a user for the specified range of dates.
// This is only called once per page request to improve performance.
// All the events get loaded into the array $events sorted by
// time of day (not date).
// params:
//   $user - username
//   $startdate - start date range, inclusive (in YYYYMMDD format)
//   $enddate - end date range, inclusive (in YYYYMMDD format)
function read_events ( $user, $startdate, $enddate ) {
  global $login;
  global $layers;

  $sql = "SELECT webcal_entry.cal_id, webcal_entry.cal_name, " .
    "webcal_entry.cal_priority, webcal_entry.cal_time, " .
    "webcal_entry.cal_duration, " .
    "webcal_entry_user.cal_status, webcal_entry.cal_create_by, " .
    "webcal_entry.cal_access, webcal_entry.cal_description, " .

    "webcal_entry.cal_date, webcal_entry_user.cal_login, " .
    "if(webcal_entry_user.cal_login = '" . $user . "', 1, 2) as sortval " .

    "FROM webcal_entry, webcal_entry_user " .
    "WHERE (webcal_entry.cal_id = webcal_entry_user.cal_id AND " .
    "webcal_entry_user.cal_login = '" . $user . "') ";

  if($user == $login)
  {
    for($index = 0; $index < sizeof($layers); $index++)
    {
      $sql = $sql . "OR (webcal_entry.cal_id = webcal_entry_user.cal_id AND webcal_entry_user.cal_login = '" . $layers[$index]['cal_layeruser'] . "') ";

      // while we are parsing the whole layers array, build ourselves a new array that will help when we have to check for dups
      $layeruser = $layers[$index]['cal_layeruser'];
      $layers_byuser["$layeruser"] = $layers[$index]['cal_dups'];
    }
  }

  if ( $startdate == $enddate )
    $sql .= " AND webcal_entry.cal_date = $startdate";
  else
    $sql .= " AND webcal_entry.cal_date >= $startdate " .
      "AND webcal_entry.cal_date <= $enddate";

  // now order the results by time, by entry id, and then by sortval to help with checking for dups
  $sql .= " ORDER BY webcal_entry.cal_time, webcal_entry.cal_id, sortval";

  $res = dbi_query ( $sql );
  if ( $res ) {
    $i = 0;
    $checkdup_id = -1;

    while ( $row = dbi_fetch_row ( $res ) ) {

      // set entry id to check for dups (user's copy of entry always comes first)
      if($row[10] == $user)
      {
        $checkdup_id = $row[0];
      }
      else
      {
        // if this is a dup entry id and this layer isn't suppose to dup, skip
        $showdups = $layers_byuser["$row[10]"];

        if( ($row[0] == $checkdup_id) && ($showdups == 'N') )
        {
          continue;
        }
      }

      if ( $row[5] != 'R' ) { // rejected
        $events[$i] = array (
          "cal_id" => $row[0],
          "cal_name" => $row[1],
          "cal_priority" => $row[2],
          "cal_time" => $row[3],
          "cal_duration" => $row[4],
          "cal_status" => $row[5],
          "cal_create_by" => $row[6],
          "cal_access" => $row[7],
          "cal_description" => $row[8],

          "cal_date" => $row[9],
	  "cal_login" => $row[10]
          );
        $i++;
      }
    }
  }
  return $events;
}



// Get all the events for a specific date from the array of pre-loaded
// events (which was loaded all at once to improve performance).
// The returned events will be sorted by time of day.
// params:
//   $user - username
//   $date - date to get events for in YYYYMMDD format
function get_entries ( $user, $date ) {
  global $events;
  $n = 0;

  for ( $i = 0; $i < count ( $events ); $i++ ) {
    if ( $events[$i][cal_date] == $date )
      $ret[$n++] = $events[$i];
  }

  return $ret;
}


// Read all the repeated events for a user.  This is only called once
// per page request to improve performance.  All the events get loaded
// into the array $repeated_events sorted by time of day (not date).
// params:
//   $user - username
function read_repeated_events ( $user ) {
  global $login;
  global $layers;

  $sql = "SELECT webcal_entry.cal_name, webcal_entry.cal_description, "
    . "webcal_entry.cal_date, webcal_entry.cal_time, "
    . "webcal_entry.cal_id, webcal_entry.cal_priority, "
    . "webcal_entry.cal_access, webcal_entry.cal_duration, "
    . "webcal_entry_user.cal_status, "
    . "webcal_entry_repeats.cal_type, webcal_entry_repeats.cal_end, "
    . "webcal_entry_repeats.cal_frequency, webcal_entry_repeats.cal_days "

    . ", webcal_entry_user.cal_login, "
    . "if(webcal_entry_user.cal_login = '" . $user . "', 1, 2) as sortval " 

    . "FROM webcal_entry, webcal_entry_repeats, webcal_entry_user "
    . "WHERE (webcal_entry.cal_id = webcal_entry_repeats.cal_id "
    . "AND webcal_entry.cal_id = webcal_entry_user.cal_id "
    . "AND webcal_entry_user.cal_login = '" . $user . "') ";

  if($user == $login)
  {
    for($index = 0; $index < sizeof($layers); $index++)
    {
      $sql = $sql . "OR (webcal_entry.cal_id = webcal_entry_repeats.cal_id "
     . "AND webcal_entry.cal_id = webcal_entry_user.cal_id "
     . "AND webcal_entry_user.cal_login = '" . $layers[$index]['cal_layeruser'] . "') ";

      // while we are parsing the whole layers array, build ourselves a new array that will help when we have to check for dups
      $layeruser = $layers[$index]['cal_layeruser'];
      $layers_byuser["$layeruser"] = $layers[$index]['cal_dups'];

    }
  }

  // now order the results by time, by entry id, and then by sortval to help with checking for dups
  $sql = $sql . "ORDER BY webcal_entry.cal_time, webcal_entry.cal_id, sortval";

  $res = dbi_query ( $sql );
  if ( $res ) {
    $i = 0;
    $checkdup_id = -1;

    while ( $row = dbi_fetch_row ( $res ) ) {

      // set entry id to check for dups (user's copy of entry always comes first)
      if( $row[13] == $user )
      {
        $checkdup_id = $row[4];
      }
      else
      {
        // if this is a dup entry id and this layer isn't suppose to dup, skip
        $showdups = $layers_byuser["$row[13]"];

        if( ($row[4] == $checkdup_id) && ($showdups == 'N') )
        {
          continue;
        }
      }

      if ( $row[8] != 'R' ) { // rejected
        $repeated_events[$i] = array (
          "cal_name" => $row[0],
          "cal_description" => $row[1],
          "cal_date" => $row[2],
          "cal_time" => $row[3],
          "cal_id"   => $row[4],
          "cal_priority" => $row[5],
          "cal_access" => $row[6],
          "cal_duration" => $row[7],
          "cal_status" => $row[8],
          "cal_type" => $row[9],
          "cal_end"  => $row[10],
          "cal_frequency" => $row[11],

          "cal_days" => $row[12],
	  "cal_login" => $row[13]
          );
        $i++;
      }
    }
  }
  return $repeated_events;
}


// Get all the repeating events for the specified data and return them
// in an array (which is sorted by time of day).
// params:
//   $user - username
//   $date - date to get events for in YYYYMMDD format
function get_repeating_entries ( $user, $dateYmd ) {
  global $repeated_events;
  $n = 0;

  $date = date_to_epoch ( $dateYmd );
  $thisyear = substr($dateYmd, 0, 4);
  $thismonth = substr($dateYmd, 4, 2);
  for ($i=0; $i < count($repeated_events); $i++ ) {

    $start = date_to_epoch ( $repeated_events[$i][cal_date] );
    $end   = date_to_epoch ( $repeated_events[$i][cal_end] );
    $freq = $repeated_events[$i][cal_frequency];

    // only repeat after the beginning, and if there is an end
    // before the end
    if ($repeated_events[$i][cal_end] && date("Ymd",$date) > date("Ymd",$end) )
      continue;
    if ( date("Ymd",$date) <= date("Ymd",$start) )
      continue;
    $id = $repeated_events[$i][cal_id];

    if ($repeated_events[$i][cal_type] == 'daily') {
      if ( (floor(($date - $start)/86400)%$freq) )
        continue;
      $ret[$n++] = $repeated_events[$i];
    } else if ($repeated_events[$i][cal_type] == 'weekly') {
      $dow = date("w", $date);
      $isDay = substr($repeated_events[$i][cal_days], $dow, 1);
      if (floor(($date - $start)/604800)%$freq)
        continue;
      if (strcmp($isDay,"y") == 0) {
        $ret[$n++] = $repeated_events[$i];
      }
    } else if ($repeated_events[$i][cal_type] == 'monthlyByDay') {
      $dowS = date("w", $start);
      $dayS = floor(date("d", $start)/7);
      $mthS = date("m", $start);
      $yrS  = date("Y", $start);

      $dow  = date("w", $date);
      $day  = floor(date("d", $date)/7);
      $mth  = date("m", $date);
      $yr   = date("Y", $date);

      if ((($yr - $yrS)*12 + $mth - $mthS) % $freq)
        continue;

      if (($dowS == $dow) && ($day == $dayS)) {
        $ret[$n++] = $repeated_events[$i];
      }

    } else if ($repeated_events[$i][cal_type] == 'monthlyByDate') {
      $mthS = date("m", $start);
      $yrS  = date("Y", $start);

      $mth  = date("m", $date);
      $yr   = date("Y", $date);

      if ((($yr - $yrS)*12 + $mth - $mthS) % $freq)
        continue;

      if (date("d", $date) == date("d", $start)) {
        $ret[$n++] = $repeated_events[$i];
      }
    }
    else if ($repeated_events[$i][cal_type] == 'yearly') {
      $yrS = date("Y", $start);
      $yr  = date("Y", $date);

      if (($yr - $yrS)%$freq)
        continue;

      if (date("dm", $date) == date("dm", $start)) {
        $ret[$n++] = $repeated_events[$i];
      }
    } else {
      // unknown repeat type
    }
  }
  return $ret;
}


function date_to_epoch ( $d ) {
  return mktime ( 2, 0, 0, substr ( $d, 4, 2 ), substr ( $d, 6, 2 ),
    substr ( $d, 0, 4 ) );
}



// Get the Sunday of the week that the specified date is in.
// (If the date specified is a Sunday, then that date is returned.)
function get_sunday_before ( $year, $month, $day ) {
  $weekday = date ( "w", mktime ( 2, 0, 0, $month, $day, $year ) );
  $newdate = mktime ( 2, 0, 0, $month, $day - $weekday, $year );
  return $newdate;
}

// Get the Monday of the week that the specified date is in.
// (If the date specified is a Monday, then that date is returned.)
function get_monday_before ( $year, $month, $day ) {
  $weekday = date ( "w", mktime ( 2, 0, 0, $month, $day, $year ) );
  if ( $weekday == 0 )
    return mktime ( 2, 0, 0, $month, $day - 6, $year );
  if ( $weekday == 1 )
    return mktime ( 2, 0, 0, $month, $day, $year );
  return mktime ( 2, 0, 0, $month, $day - ( $weekday - 1 ), $year );
}


// Returns week number for specified date
// depending from week numbering settings.
// params:
//   $date - date in UNIX time format
function week_number ( $date ) {
  if ( $GLOBALS["WEEK_START"] == "1" )
    return strftime ( "%V", $date ); // ISO Weeks -- which start on Mondays
  else 
    return strftime ( "%W", $date );
}



// This function is not yet used.  Some of the places that will call it
// have to be updated to also get the event owner so we know if the current
// user has access to edit and delete.
function icon_text ( $id, $can_edit, $can_delete ) {
  $ret = "<A HREF=\"view_entry.php?id=$id\">" .
    "<IMG SRC=\"view.gif\" ALT=\"View Entry\" BORDER=\"0\" " .
    "WIDTH=\"10\" HEIGHT=\"10\">" .
    "</A>";
  if ( $can_edit )
    $ret .= "<A HREF=\"edit_entry.php?id=$id\">" .
      "<IMG SRC=\"edit.gif\" ALT=\"Edit Entry\" BORDER=\"0\" " .
      "WIDTH=\"10\" HEIGHT=\"10\">" .
      "</A>";
  if ( $can_delete )
    $ret .= "<A HREF=\"del_entry.php?id=$id\" " .
      "onClick=\"return confirm('" .
      translate("Are you sure you want to delete this entry?") .
      "\\n\\n" . translate("This will delete this entry for all users.") .
      "');\">" .
      "<IMG SRC=\"delete.gif\" ALT=\"Delete Entry\" BORDER=\"0\" " .
      "WIDTH=\"10\" HEIGHT=\"10\">" .
      "</A>";
  return $ret;
}


//
// Print all the calendar entries for the specified user for the
// specified date.  If we are display data from someone other than
// the logged in user, then check the access permission of the entry.
// params:
//   $date - date in YYYYMMDD format
//   $user - username
//   $hide_icons - hide icons to make printer-friendly
//   $is_ssi - is this being called from week_ssi.php?
function print_date_entries ( $date, $user, $hide_icons, $ssi ) {
  global $events;

  $year = substr ( $date, 0, 4 );
  $month = substr ( $date, 4, 2 );
  $day = substr ( $date, 6, 2 );
 
  $dateu = mktime ( 2, 0, 0, $month, $day, $year );

  if ( ! $hide_icons && ! $ssi ) {
    print "<A HREF=\"edit_entry.php?";
    if ( strcmp ( $user, $GLOBALS["login"] ) )
      print "user=$user&";
    print "date=$date\">" .
      "<IMG SRC=\"new.gif\" WIDTH=\"10\" HEIGHT=\"10\" ALT=\"" .
      translate("New Entry") . "\" BORDER=\"0\" ALIGN=\"right\">" .
      "</A>";
  }
  if ( ! $ssi ) {
    echo "<FONT SIZE=\"-1\"><A CLASS=\"dayofmonth\" HREF=\"day.php?";
    if ( strcmp ( $user, $GLOBALS["login"] ) )
      echo "user=$user&";
    echo "date=$date\">$day</A></FONT>";
    if ( $GLOBALS["DISPLAY_WEEKNUMBER"] == "Y" &&
      date ( "w", $dateu ) == $GLOBALS["WEEK_START"] ) {
      echo "<A HREF=\"week.php?date=$date";
      if ( strcmp ( $user, $GLOBALS["login"] ) )
        echo "&user=$user";
       echo "\" CLASS=\"weeknumber\">";
      echo "<FONT SIZE=\"-2\">(" .
        translate("Week") . " " . week_number ( $dateu ) . ")</FONT></A>";
    }
    print "<BR>\n";
  }

  // get all the repeating events for this date and store in array $rep
  $rep = get_repeating_entries ( $user, $date );
  $cur_rep = 0;

  // get all the non-repeating events for this date and store in $ev
  $ev = get_entries ( $user, $date );

  for ( $i = 0; $i < count ( $ev ); $i++ ) {
    if ( $GLOBALS["DISPLAY_UNAPPROVED"] == "N" &&
      $ev[$i]['cal_status'] == 'A' )
      continue; // ignore this unapproved event
    // print out any repeating events that are before this one...
    while ( $cur_rep < count ( $rep ) &&
      $rep[$cur_rep]['cal_time'] < $ev[$i]['cal_time'] ) {
      print_entry ( $rep[$cur_rep]['cal_id'],
        $date, $rep[$cur_rep]['cal_time'], $rep[$cur_rep]['cal_duration'],
        $rep[$cur_rep]['cal_name'], $rep[$cur_rep]['cal_description'],
        $rep[$cur_rep]['cal_status'], $rep[$cur_rep]['cal_priority'],
        $rep[$cur_rep]['cal_access'], $rep[$cur_rep]['cal_login'], $hide_icons );
      $cur_rep++;
    }
    print_entry ( $ev[$i]['cal_id'],
      $date, $ev[$i]['cal_time'], $ev[$i]['cal_duration'],
      $ev[$i]['cal_name'], $ev[$i]['cal_description'],
      $ev[$i]['cal_status'], $ev[$i]['cal_priority'],
      $ev[$i]['cal_access'], $ev[$i]['cal_login'], $hide_icons );
  }
  // print out any remaining repeating events
  while ( $cur_rep < count ( $rep ) ) {
    print_entry ( $rep[$cur_rep]['cal_id'],
      $date, $rep[$cur_rep]['cal_time'], $rep[$cur_rep]['cal_duration'],
      $rep[$cur_rep]['cal_name'], $rep[$cur_rep]['cal_description'],
      $rep[$cur_rep]['cal_status'], $rep[$cur_rep]['cal_priority'],
      $rep[$cur_rep]['cal_access'], $rep[$cur_rep]['cal_login'], $hide_icons );
    $cur_rep++;
  }
}



// Generate the HTML for an event to be viewed in the day-at-glance.
// The HTML will be stored in an array ($hour_arr) indexed on the event's
// starting hour.
function html_for_event_week_at_a_glance ( $id, $date, $time,
  $name, $description, $status, $pri, $access, $duration, $event_owner, $hide_icons ) {
  global $first_hour, $last_hour, $hour_arr, $rowspan_arr, $rowspan,
    $eventinfo, $login, $user;
  static $key = 0;
  global $DISPLAY_ICONS;

  global $layers;

  $divname = "eventinfo-day-$id-$key";
  $key++;
  if ( $time >= 0 ) {
    $ind = (int) ( $time / 10000 );
    if ( $ind < $first_hour )
      $first_hour = $ind;
    if ( $ind > $last_hour )
      $last_hour = $ind;
  } else
    $ind = 99;


  if ( $login != $event_owner && strlen ( $event_owner ) )
  {
    $class = "layerentry";
  }
  else
  {
    $class = "entry";
    if ( $status == "W" ) $class = "unapprovedentry";
  }


  if ( ! $hide_icons ) {
    $hour_arr[$ind] .= "<A CLASS=\"$class\" HREF=\"view_entry.php?id=$id";
    if ( strlen ( $GLOBALS["user"] ) > 0 )
      $hour_arr[$ind] .= "&user=" . $GLOBALS["user"];
    $hour_arr[$ind] .= "\" onMouseOver=\"window.status='" .
      translate("View this entry") .
      "'; show('$divname'); return true;\" onMouseOut=\"hide('$divname'); return true;\">";
  }
  if ( $pri == 3 ) $hour_arr[$ind] .= "<B>";


  if ( $login != $event_owner && strlen ( $event_owner ) )
  {
    for($index = 0; $index < sizeof($layers); $index++)
    {
        if($layers[$index]['cal_layeruser'] == $event_owner)
        {
            $hour_arr[$ind] .= "<FONT COLOR=\"" . $layers[$index]['cal_color'] . "\">";
        }
    }
  }


  if ( $time >= 0 ) {
    $hour_arr[$ind] .= display_time ( $time ) . "&gt; ";
    $timestr = display_time ( $time );
    if ( $duration > 0 ) {
      // calc end time
      $h = (int) ( $time / 10000 );
      $m = ( $time / 100 ) % 100;
      $m += $duration;
      $d = $duration;
      while ( $m >= 60 ) {
        $h++;
        $m -= 60;
      }
      $end_time = sprintf ( "%02d%02d00", $h, $m );
      $timestr .= "-" . display_time ( $end_time );
      if ( $m == 0 )
        $rowspan = $h - $ind;
      else
        $rowspan = $h - $ind + 1;
      if ( $rowspan > $rowspan_arr[$ind] && $rowspan > 1 )
        $rowspan_arr[$ind] = $rowspan;
    }
  }
  if ( $login != $user && $access == 'R' && strlen ( $user ) )
    $hour_arr[$ind] .= "(Private)";

  else
  if ( $login != $event_owner && $access == 'R' && strlen ( $event_owner ) )
    $hour_arr[$ind] .= "(Private)";
  else
  if ( $login != $event_owner && strlen ( $event_owner ) )
  {
    $hour_arr[$ind] .= htmlspecialchars ( $name );
    $hour_arr[$ind] .= "</FONT>";
  }

  else
    $hour_arr[$ind] .= htmlspecialchars ( $name );
  if ( $pri == 3 ) $hour_arr[$ind] .= "</B>";
  $hour_arr[$ind] .= "</A>";
  //if ( $DISPLAY_ICONS == "Y" ) {
  //  $hour_arr[$ind] .= icon_text ( $id, true, true );
  //}
  $hour_arr[$ind] .= "<BR>";
  if ( $login != $user && $access == 'R' && strlen ( $user ) )
    $eventinfo .= build_event_popup ( $divname,
      translate("This event is confidential"), "" );

  else
  if ( $login != $event_owner && $access == 'R' && strlen ( $event_owner ) )
    $eventinfo .= build_event_popup ( $divname,
      translate("This event is confidential"), "" );

  else
    $eventinfo .= build_event_popup ( $divname,
      $description, $timestr );
}



// Generate the HTML for an event to be viewed in the day-at-glance.
// The HTML will be stored in an array ($hour_arr) indexed on the event's
// starting hour.
function html_for_event_day_at_a_glance ( $id, $date, $time,
  $name, $description, $status, $pri, $access, $duration, $event_owner, $hide_icons ) {
  global $first_hour, $last_hour, $hour_arr, $rowspan_arr, $rowspan,
    $eventinfo, $login, $user;
  static $key = 0;

  global $layers;

  $divname = "eventinfo-day-$id-$key";
  $key++;
  if ( $login != $user && $access == 'R' && strlen ( $user ) )
    $eventinfo .= build_event_popup ( $divname,
      translate("This event is confidential"), "" );

  else
  if ( $login != $event_owner && $access == 'R' && strlen ( $event_owner ) )
    $eventinfo .= build_event_popup ( $divname,
      translate("This event is confidential"), "" );

  else
    $eventinfo .= build_event_popup ( $divname, $description, "" );
  if ( $time >= 0 ) {
    $ind = (int) ( $time / 10000 );
    if ( $ind < $first_hour )
      $first_hour = $ind;
    if ( $ind > $last_hour )
      $last_hour = $ind;
  } else
    $ind = 99;


  if ( $login != $event_owner && strlen ( $event_owner ) )
  {
    $class = "layerentry";
  }
  else
  {
    $class = "entry";
    if ( $status == "W" ) $class = "unapprovedentry";
  }


  if ( ! $hide_icons ) {
    $hour_arr[$ind] .= "<A CLASS=\"$class\" HREF=\"view_entry.php?id=$id";
    if ( strlen ( $GLOBALS["user"] ) > 0 )
      $hour_arr[$ind] .= "&user=" . $GLOBALS["user"];
    $hour_arr[$ind] .= "\" onMouseOver=\"window.status='" .
      translate("View this entry") .
      "'; show('$divname'); return true;\" onMouseOut=\"hide('$divname'); return true;\">";
  }
  if ( $pri == 3 ) $hour_arr[$ind] .= "<B>";


  if ( $login != $event_owner && strlen ( $event_owner ) )
  {
    for($index = 0; $index < sizeof($layers); $index++)
    {
        if($layers[$index]['cal_layeruser'] == $event_owner)
        {
            $hour_arr[$ind] .= "<FONT COLOR=\"" . $layers[$index]['cal_color'] . "\">";
        }
    }
  }


  if ( $time >= 0 ) {
    $hour_arr[$ind] .= "[" . display_time ( $time );
    if ( $duration > 0 ) {
      // calc end time
      $h = (int) ( $time / 10000 );
      $m = ( $time / 100 ) % 100;
      $m += $duration;
      $d = $duration;
      while ( $m >= 60 ) {
        $h++;
        $m -= 60;
      }
      $end_time = sprintf ( "%02d%02d00", $h, $m );
      $hour_arr[$ind] .= "-" . display_time ( $end_time );
      if ( $m == 0 )
        $rowspan = $h - $ind;
      else
        $rowspan = $h - $ind + 1;
      if ( $rowspan > $rowspan_arr[$ind] && $rowspan > 1 )
        $rowspan_arr[$ind] = $rowspan;
    }
    $hour_arr[$ind] .= "] ";
  }
  if ( $login != $user && $access == 'R' && strlen ( $user ) )
    $hour_arr[$ind] .= "(Private)";

  else
  if ( $login != $event_owner && $access == 'R' && strlen ( $event_owner ) )
    $hour_arr[$ind] .= "(Private)";
  else
  if ( $login != $event_owner && strlen ( $event_owner ) )
  {
    $hour_arr[$ind] .= htmlspecialchars ( $name );
    $hour_arr[$ind] .= "</FONT>";
  }


  else
    $hour_arr[$ind] .= htmlspecialchars ( $name );
  if ( $pri == 3 ) $hour_arr[$ind] .= "</B>";
  $hour_arr[$ind] .= "</A><BR>";
}

//
// Print all the calendar entries for the specified user for the
// specified date in day-at-a-glance format.
// If we are displaying data from someone other than
// the logged in user, then check the access permission of the entry.
// We output a two column format like:   time: event
// params:
//   $date - date in YYYYMMDD format
//   $user - username
//   $hide_icons - should we hide the icons to make it printer-friendly
function print_day_at_a_glance ( $date, $user, $hide_icons ) {
  global $first_hour, $last_hour, $hour_arr, $rowspan_arr, $rowspan;
  global $CELLBG, $TODAYCELLBG, $THFG, $THBG;
  global $repeated_events;

  // get all the repeating events for this date and store in array $rep
  $rep = get_repeating_entries ( $user, $date );
  $cur_rep = 0;

  // Get static non-repeating events
  $ev = get_entries ( $user, $date );
  $hour_arr = Array ();
  $first_hour = $GLOBALS["WORK_DAY_START_HOUR"];
  $last_hour = $GLOBALS["WORK_DAY_END_HOUR"];
  $rowspan_arr = Array ();
  for ( $i = 0; $i < count ( $ev ); $i++ ) {
    // print out any repeating events that are before this one...
    while ( $cur_rep < count ( $rep ) &&
      $rep[$cur_rep]['cal_time'] < $ev[$i]['cal_time'] ) {
      html_for_event_day_at_a_glance ( $rep[$cur_rep]['cal_id'],
        $date, $rep[$cur_rep]['cal_time'],
        $rep[$cur_rep]['cal_name'], $rep[$cur_rep]['cal_description'],
        $rep[$cur_rep]['cal_status'], $rep[$cur_rep]['cal_priority'],
        $rep[$cur_rep]['cal_access'], $rep[$cur_rep]['cal_duration'],
        $rep[$cur_rep]['cal_login'], $hide_icons );
      $cur_rep++;
    }
    html_for_event_day_at_a_glance ( $ev[$i]['cal_id'],
      $date, $ev[$i]['cal_time'],
      $ev[$i]['cal_name'], $ev[$i]['cal_description'],
      $ev[$i]['cal_status'], $ev[$i]['cal_priority'],
      $ev[$i]['cal_access'], $ev[$i]['cal_duration'],
      $ev[$i]['cal_login'], $hide_icons );
  }
  // print out any remaining repeating events
  while ( $cur_rep < count ( $rep ) ) {
    html_for_event_day_at_a_glance ( $rep[$cur_rep]['cal_id'],
      $date, $rep[$cur_rep]['cal_time'],
      $rep[$cur_rep]['cal_name'], $rep[$cur_rep]['cal_description'],
      $rep[$cur_rep]['cal_status'], $rep[$cur_rep]['cal_priority'],
      $rep[$cur_rep]['cal_access'], $rep[$cur_rep]['cal_duration'],
      $rep[$cur_rep]['cal_login'], $hide_icons );
    $cur_rep++;
  }

  // squish events that use the same cell into the same cell.
  // For example, an event from 8:00-9:15 and another from 9:30-9:45 both
  // want to show up in the 8:00-9:59 cell.
  $rowspan = 0;
  $last_row = -1;
  for ( $i = 0; $i < 24; $i++ ) {
    if ( $rowspan > 1 ) {
      if ( strlen ( $hour_arr[$i] ) ) {
        if ( $rowspan_arr[$i] == 0 )
          $rowspan_arr[$last_row] += $rowspan_arr[$i];
        else
          $rowspan_arr[$last_row] += ( $rowspan_arr[$i] - 1);
        // this will move entries apart that appear in one field, yet start on different hours
        $start_time = $i;
        $diff_start_time = $start_time - $last_row;
        for ( $u = $diff_start_time ; $u > 0 ; $u-- )
          $hour_arr[$last_row] .= "<BR>";
        $hour_arr[$last_row] .= $hour_arr[$i];
        $hour_arr[$i] = "";
        $rowspan_arr[$i] = 0;
      }
      $rowspan--;
    } else if ( $rowspan_arr[$i] > 1 ) {
      $rowspan = $rowspan_arr[$i];
      $last_row = $i;
    }
  }
  if ( strlen ( $hour_arr[99] ) ) {
    echo "<TR><TD HEIGHT=\"40\" BGCOLOR=\"$TODAYCELLBG\">&nbsp;</TD><TD VALIGN=\"top\" HEIGHT=\"40\" BGCOLOR=\"$TODAYCELLBG\">$hour_arr[99]</TD></TR>\n";
  }
  $rowspan = 0;
  for ( $i = $first_hour; $i <= $last_hour; $i++ ) {
    $time = display_time ( $i * 10000 );
    echo "<TR><TH VALIGN=\"top\" HEIGHT=\"40\" WIDTH=\"14%\" BGCOLOR=\"$THBG\">" .
      "<FONT COLOR=\"$THFG\">" .
      $time . "</FONT></TH>\n";
    if ( $rowspan > 1 ) {
      // this might mean there's an overlap, or it could mean one event
      // ends at 11:15 and another starts at 11:30.
      if ( strlen ( $hour_arr[$i] ) )
        echo "<TD VALIGN=\"top\" HEIGHT=\"40\" BGCOLOR=\"$TODAYCELLBG\">$hour_arr[$i]</TD>";
      $rowspan--;
    } else {
      if ( ! strlen ( $hour_arr[$i] ) )
        echo "<TD HEIGHT=\"40\" BGCOLOR=\"$CELLBG\">&nbsp;</TD></TR>\n";
      else {
        $rowspan = $rowspan_arr[$i];
        if ( $rowspan > 1 )
          echo "<TD VALIGN=\"top\" BGCOLOR=\"$TODAYCELLBG\" ROWSPAN=\"$rowspan\">$hour_arr[$i]</TD></TR>\n";
        else
          echo "<TD VALIGN=\"top\" HEIGHT=\"40\" BGCOLOR=\"$TODAYCELLBG\">$hour_arr[$i]</TD></TR>\n";
      }
    }
  }
}


// display a link to any unapproved events
function display_unapproved_events ( $user ) {
  $sql = "SELECT COUNT(cal_id) FROM webcal_entry_user " .
    "WHERE cal_status = 'W' AND cal_login = '$user'";
  //print "SQL: $sql<BR>";
  $res = dbi_query ( $sql );
  if ( $res ) {
    if ( $row = dbi_fetch_row ( $res ) ) {
      if ( $row[0] > 0 )
        echo translate("You have") . " <A HREF=\"list_unapproved.php\">" .
          $row[0] . " " . translate("unapproved") . " " .
          ( $row[0] > 1 ? translate("events") : translate("event") ) .
          "</A><BR>\n";
    }
    dbi_free_result ( $res );
  }
}



// Display a time in either 12 or 24 hour format
// params:
//   $time - an interger like 235900
function display_time ( $time ) {
  $hour = (int) ( $time / 10000 );
  $min = ( $time / 100 ) % 100;
  if ( $GLOBALS["TIME_FORMAT"] == "12" ) {
    $ampm = $hour >= 12 ? translate("pm") : translate("am");
    $hour %= 12;
    if ( $hour == 0 )
      $hour = 12;
    $ret = sprintf ( "%d:%02d%s", $hour, $min, $ampm );
  } else {
    $ret = sprintf ( "%d:%02d", $hour, $min );
  }
  return $ret;
}


// Return the full month name
// params:
//   $m - month (0-11)
function month_name ( $m ) {
  switch ( $m ) {
    case 0: return translate("January");
    case 1: return translate("February");
    case 2: return translate("March");
    case 3: return translate("April");
    case 4: return translate("May");
    case 5: return translate("June");
    case 6: return translate("July");
    case 7: return translate("August");
    case 8: return translate("September");
    case 9: return translate("October");
    case 10: return translate("November");
    case 11: return translate("December");
  }
  return "unknown-month($m)";
}


// Return the abbreviated month name
// params:
//   $m - month (0-11)
function month_short_name ( $m ) {
  switch ( $m ) {
    case 0: return translate("Jan");
    case 1: return translate("Feb");
    case 2: return translate("Mar");
    case 3: return translate("Apr");
    case 4: return translate("May");
    case 5: return translate("Jun");
    case 6: return translate("Jul");
    case 7: return translate("Aug");
    case 8: return translate("Sep");
    case 9: return translate("Oct");
    case 10: return translate("Nov");
    case 11: return translate("Dec");
  }
  return "unknown-month($m)";
}


// Return the full weekday name
// params:
//   $w - weekday (0=Sunday,...,6=Saturday)
function weekday_name ( $w ) {
  switch ( $w ) {
    case 0: return translate("Sunday");
    case 1: return translate("Monday");
    case 2: return translate("Tuesday");
    case 3: return translate("Wednesday");
    case 4: return translate("Thursday");
    case 5: return translate("Friday");
    case 6: return translate("Saturday");
    case 7: return translate("July");
  }
  return "unknown-weekday($w)";
}

// Return the abbreviated weekday name
// params:
//   $w - weekday (0=Sun,...,6=Sat)
function weekday_short_name ( $w ) {
  switch ( $w ) {
    case 0: return translate("Sun");
    case 1: return translate("Mon");
    case 2: return translate("Tue");
    case 3: return translate("Wed");
    case 4: return translate("Thu");
    case 5: return translate("Fri");
    case 6: return translate("Sat");
    case 7: return translate("Jul");
  }
  return "unknown-weekday($w)";
}

// convert a date from an int format "19991231" into "Friday, December 31, 1999"
function date_to_str ( $indate ) {
  if ( strlen ( $indate ) == 0 ) {
    $indate = date ( "Ymd" );
  }
  $y = (int) ( $indate / 10000 );
  $m = (int) ( $indate / 100 ) % 100;
  $d = $indate % 100;
  $date = mktime ( 0, 0, 0, $m, $d, $y );
  $wday = strftime ( "%w", $date );
  return sprintf ( "%s, %s %d, %04d",
    weekday_name ( $wday ), month_name ( $m - 1 ), $d, $y );
}



// Define an array to use to jumble up the key
$offsets = array ( 31, 41, 59, 26, 54 );


function hextoint ( $val ) {
  if ( ! strlen ( $val ) )
    return 0;
  switch ( strtoupper ( $val ) ) {
    case "0": return 0;
    case "1": return 1;
    case "2": return 2;
    case "3": return 3;
    case "4": return 4;
    case "5": return 5;
    case "6": return 6;
    case "7": return 7;
    case "8": return 8;
    case "9": return 9;
    case "A": return 10;
    case "B": return 11;
    case "C": return 12;
    case "D": return 13;
    case "E": return 14;
    case "F": return 15;
  }
  return 0;
}

// Extract a user's name from a session id
// This is a lame attempt at security.  Otherwise, users would be
// able to edit their cookies.txt file and set the username in plain
// text.
// $instr is a hex-encoded string. "Hello" would be "48656c6c6f".
function decode_string ( $instr ) {
  $orig = "";
  for ( $i = 0; $i < strlen ( $instr ); $i += 2 ) {
    $ch1 = substr ( $instr, $i, 1 );
    $ch2 = substr ( $instr, $i + 1, 1 );
    $val = hextoint ( $ch1 ) * 16 + hextoint ( $ch2 );
    $j = $i % count ( $offsets );
    $newval = $val + $offsets[$j];
    $newval %= 256;
    $orig .= chr ( $val );
  }
  return $orig;
}

function encode_string ( $instr ) {
  $ret = "";
  for ( $i = 0; $i < strlen ( $instr ); $i++ ) {
    $ch1 = substr ( $instr, $i, 1 );
    $val = ord ( $ch1 );
    $j = $i % count ( $offsets );
    $newval = $val + $offsets[$j];
    $newval %= 256;
    $ret .= bin2hex ( chr ( $newval ) );
  }
  return $ret;
}


?>
