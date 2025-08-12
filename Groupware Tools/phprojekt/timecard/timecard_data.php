<?php

// timecard_data.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: timecard_data.php,v 1.28.2.3 2005/08/26 06:14:57 fgraf Exp $

$module = 'timecard';
$path_pre = '../';
$include_path = $path_pre.'lib/lib.inc.php';
include_once $include_path;
include_once('./timecard_date.inc.php');

//echo set_page_header();

// check role
if (check_role("timecard") < 2) die("You are not allowed to do this!");

if (isset($bID) && is_array($bID)) {
    foreach ($bID as $i) {
        $openc  = "open".$i."_x";
        $closec = "close".$i."_x";
        if ($$openc != "") {
            $open = $i;
            break;
        }
        else if ($$closec) {
            $close = $i;
            break;
        }
    }
}

if ($open) {
    $element2_mode = "open";
    $ID = $open;
}
else if ($close) {
    $element2_mode = "close";
    $ID = $close;
}

if ($action=="do") {
    switch (true) {
        case ($deli):
            $loeschen = "";
            $action   = "delete";
            $modes    = "forms";
            //include_once("timecard_data.php");
            break;
        case ($save):
            $sure   = "1";
            $action = "12";
            $save   = "";
            $datum  = $date;
            $modes  = "forms";
            //include_once("timecard_data.php");
            break;
        case ($save_close):
            $sure       = "1";
            $action     = "12";
            $save_close = "";
            $datum      = $date;
            $modes      = "forms";
            //$canc       = "lösch halt";
            //include_once("timecard_data.php");
            break;
        case ($fills2):
            $loeschen = "";
            $action   = "fillsende";
            $modes    = "forms";
            break;
        case ($canc):
            $result = db_query("DELETE FROM ".DB_PREFIX."tc_temp
                                      WHERE user='$user_ID'") or db_die();
            echo '
<html>
<head>
<script type="text/javascript">
<!--
function cl() {
    window.opener.location.href = "timecard.php";
    self.close();
}
//-->
</script>
</head>
<body onload="javascript:cl()">
</body>
</html>
';
            die();
            break;
    }
}
else if ($action == "add") {
    //$result = db_query("INSERT INTO ".DB_PREFIX."tc_temp
    //                         VALUES ('',0,'$user_ID','$date','$timestart','$timestop',$nettoh,$nettom)") or db_die();
    $timestart = substr('0000'.$timestart, -4);
    $tsp_start = mktime(substr($timestart, 0, 2), substr($timestart, 2, 2), 0, 1, 1, 1970);
    if ($timestop <> '') {
        $timestop  = substr('0000'.$timestop, -4);
        if ($timestop <= $timestart) die('check your time data');
        $tsp_stop  = mktime(substr($timestop, 0, 2),  substr($timestop, 2, 2), 0, 1, 1, 1970);
        $tsp = $tsp_stop - $tsp_start;
        if (!($nettoh or $nettom)) {
            $nettoh = floor($tsp/3600);
            $nettom = $tsp%3600 / 60;
        }
        else if (!$nettoh) $nettoh=0;
        else if (!$nettom) $nettom=0;
        $result = db_query("INSERT INTO ".DB_PREFIX."timecard
                                        (   ID,      users,     datum, anfang, ende, nettoh, nettom, ip_address )
                                 VALUES ($dbIDnull,'$user_ID','$date','$timestart','$timestop','$nettoh','$nettom', '".$_SERVER["REMOTE_ADDR"]."')") or db_die();
    }
    else {
        $result = db_query("INSERT INTO ".DB_PREFIX."timecard
                                        (   ID,      users,     datum, anfang, ip_address )
                                 VALUES ($dbIDnull,'$user_ID','$date','$timestart','".$_SERVER["REMOTE_ADDR"]."')") or db_die();
    }
}

$die_ip = $_SERVER["REMOTE_ADDR"];
$now    = strtotime("now");

// filter all speacial characters ou of the time string
if (!$time) $time  = date("Hi", mktime(date("H") + PHPR_TIMEZONE, date("i"), date("s"), date("m"), date("d"), date("Y")));
$time = ereg_replace("[.:,;/]", "", $time);
// check whether $day and $time have a valid format
if (!ereg("(^[0-9]*$)", $day) or !ereg("(^[0-9]*$)", $time) or substr($time, 0, 2) > 24 or substr($time, 2, 2) > 59) {
    message_stack_in(__('Please check your date and time format! '),"timecard","error");
    $error = 1;
}

// validate date
if (!checkdate($month, $day, $year)) {
    message_stack_in(__('Please check the date!')." <br />".__('back')." ...", "timecard", "error");
    $error = 1;
}

// start or end timecard
if ($sure == 1) {
    // check whether there is still one entry open
    $result = db_query("SELECT ID, anfang
                          FROM ".DB_PREFIX."timecard
                         WHERE datum = '$datum'
                           AND users = '$user_ID'
                           AND (anfang <> '' OR anfang IS NOT NULL)
                           AND (ende = '' OR ende IS NULL)") or db_die();
    $row    = db_fetch_row($result);
    $row[1] = check_4d($row[1]);

    if ($row[0] > 0) $entry_open = 1;

    if ($action == "1") {
        if ($entry_open == "1") die("<br /><h5>".__('Theres an error in your time sheet: ')."</h5>");
        $result = db_query("INSERT INTO ".DB_PREFIX."timecard
                                        (   ID,      users,     datum,  anfang,  ip_address)
                                 VALUES ($dbIDnull,'$user_ID','$datum','$time','$die_ip')") or db_die();

    }
    // insert start of timecard
    else if ($action == "12") {
        if ($entry_open == "1") die("<br /><h5>".__('Theres an error in your time sheet: ')."</h5>");

        $resulkt = db_query("SELECT user, datum, anfang, ende, nettoh, nettom, ID
                               FROM ".DB_PREFIX."tc_temp AS tc
                              WHERE user  = '$user_ID'
                                AND datum = '$date'
                                AND tID < 1") or db_die();
        while ($rowk = db_fetch_row($resulkt)) {
            $result = db_query("INSERT INTO ".DB_PREFIX."timecard
                                            (users,datum, anfang, ende, nettoh, nettom, ip_address)
                                     VALUES ('$rowk[0]','$rowk[1]','$rowk[2]','$rowk[3]','$rowk[4]','$rowk[5]','$die_ip')") or db_die();
        }
        $result = db_query("DELETE FROM ".DB_PREFIX."tc_temp
                                  WHERE user  = '$user_ID'
                                    AND datum = '$date'") or db_die();
        /*
        $result = db_query("insert into ".DB_PREFIX."timecard
                                    (   ID,      users,     datum,  anfang,  ip_address)
                            values ($dbIDnull,'$user_ID','$datum','$time','$die_ip')") or db_die();
        */
        if ($canc) {
            $result = db_query("DELETE FROM ".DB_PREFIX."tc_temp
                                      WHERE user='$user_ID'") or db_die();
            echo '
<html>
<head>
<script type="text/javascript">
<!--
function cl() {
    window.opener.location.href = "timecard.php";
    self.close();
}
//-->
</script>
</head>
<body onload="javascript:cl()">
</body>
</html>
';
            die();
        }
    }
    // update timecard, clock end
    else {
        if ($entry_open <> "1") die("<br /><h5>".__('Theres an error in your time sheet: ')."</h5>");
        // close quicktimer
        clock_out();
        $nsum =(substr($time,0,2) - substr($row[1],0,2))*60 + substr($time,2,4) - substr($row[1],2,4);
        $nettoh = floor($nsum/60);
        $nettom = $nsum - $nettoh * 60;
        $result = db_query(xss("update ".DB_PREFIX."timecard
                            set ende = '$time',
                                nettoh='$nettoh',
                                nettom='$nettom'
                            where datum = '$datum' and
                                users = '$user_ID' and
                                (anfang <> '' or anfang is not NULL) and
                                (ende = '' or ende is NULL)")) or db_die();
    }
}

// delete entry for a whole day
if ($action == "delete") {
    // check permission
    foreach($del as $k =>$v) {
        if ($k < $maxa) {
            $result = db_query("DELETE FROM ".DB_PREFIX."tc_temp
                                      WHERE ID='$v'") or db_die();
        }
        else {
            $del1[] = $v;
        }
    }
    foreach ($del1 as $k =>$ID) {
        $result = db_query("SELECT users, datum
                              FROM ".DB_PREFIX."timecard
                             WHERE ID = '$ID'") or db_die();
        $row = db_fetch_row($result);
        if ($row[0] == "") die("no entry found.");
        if ($user_ID <> $row[0]) die("you are not privileged for this!");

        $datc = strtotime($row[1]);
        $diff = $now-$datc;
        $diff = (floor($diff/86400));
        if (PHPR_TIMECARD_DELETE != 1) {
            message_stack_in(__('You are not allowed to delete entries from timecard. Please contact your administrator'), "timecard", "error");
            $error = 1;
        }
        else if (($diff > PHPR_TIMECARD_CHANGE)) {
            message_stack_in(sprintf (__('You cannot delete entries at this date. Since there have been %s days. You just can edit entries not older than %s days.'), $diff, PHPR_TIMECARD_CHANGE), "timecard", "error");
            $error = 1;
        }

        if (!$error) {
            // db actions - delete record in timecard ...
            $result2 = db_query("DELETE FROM ".DB_PREFIX."timecard
                                       WHERE ID = '$ID'") or db_die();

            // ... and in timeproj as well, but only if there isn't any further entry
            // find out if there is another entry for this day in the timecard
            $result2 = db_query("SELECT ID
                                   FROM ".DB_PREFIX."timecard
                                  WHERE datum = '$row[1]'
                                    AND users = '$user_ID'") or db_die();
            $row2 = db_fetch_row($result2);
            // if there isn't any further entry in the timecard, delete all relations to projects
            if (!$row2[0]) {
                $result = db_query("DELETE FROM ".DB_PREFIX."timeproj
                                          WHERE users = '$user_ID'
                                            AND datum LIKE '$row[1]'") or db_die();
            }
        }
    }
}

// delete separate entry
if ($delsep) {
    $result = db_query("SELECT users, datum
                          FROM ".DB_PREFIX."timeproj
                         WHERE ID = '$del[0]'") or db_die();
    $row = db_fetch_row($result);
    if ($row[0] <> $user_ID) die("You are not allowed to do this!");

    $datc = strtotime($row[1]);
    $diff = $now-$datc;
    $diff = (floor($diff/86400));
    if ($diff > PHPR_TIMECARD_CHANGE) {
        $error = 1;
        message_stack_in(sprintf (__('You cannot delete bookings at this date. Since there have been %s days. You just can edit bookings of entries not older than %s days.'), $diff, PHPR_TIMECARD_CHANGE), "timecard", "error");
    }
    // delete entry
    if (!$error) {
        foreach($del as $k =>$v) {
            $result = db_query("DELETE FROM ".DB_PREFIX."timeproj
                                      WHERE ID = '$v'") or db_die();
        }
    }
}

if ($action == "fillsende") {
    foreach ($ende as $k =>$v) {
        if ($v) {
            $res = db_query("SELECT anfang
                               FROM ".DB_PREFIX."timecard
                              WHERE ID = '$k'")or db_die();
            $er    = db_fetch_row($res);
            $er[0] = check_4d($er[0]);
            $ende  = check_4d($v);
            $neh = $neth[$k];
            $nem = $netm[$k];
            if (!($neh or $nem)) {
                $bsum = (substr($v,0,2) - substr($er[0],0,2))*60 + substr($v,2,4) - substr($er[0],2,4);
                $neh  = floor($bsum/60);
                $nem  = $bsum - $neh * 60;
            }
            $result = db_query("UPDATE ".DB_PREFIX."timecard
                                   SET ende   = '$v',
                                       nettoh = '$neh',
                                       nettom = '$nem'
                                 WHERE ID = '$k'")or db_die();
        }
    }
}


// Nachtragen - insert afterwards
if ($action == "fills") {
    $datc = strtotime($datum);
    $diff = $now-$datc;
    $diff = (floor($diff/86400));
    // first option: it is a new record - no ID
    if (!$ID) {
        // check wether the time is in the given range
        if ($diff > PHPR_TIMECARD_CHANGE) {
            $error = 1;
            message_stack_in(sprintf (__('You cannot add entries at this date. Since there have been %s days. You just can edit entries not older than %s days.'), $diff, PHPR_TIMECARD_CHANGE), "timecard", "error");
        }
        // check whether this start time is not between start and end time of another record on this day
        $result = db_query("SELECT anfang, ende
                              FROM ".DB_PREFIX."timecard
                             WHERE datum = '$datum'
                               AND users = '$user_ID'") or db_die();
        while ($row = db_fetch_row($result)) {
            // oh - start time is already in another record -> error
            if ($time >= $row[0] and $time <= $row[1]) {
                message_stack_in(__('This field is not empty. Please ask the administrator'), "timecard", "error");
                $error = 1;
            }
        }
        // insert record into database
        if (!$error) {
            $result = db_query("INSERT INTO ".DB_PREFIX."timecard
                                            (   ID,      users,     datum, anfang, ip_address )
                                     VALUES ($dbIDnull,'$user_ID','$datum','$time','$die_ip')") or db_die();
        }
    }
    // otherwise this user wants to enter the end time
    else {
        // check permission
        $result = db_query("SELECT users, anfang, datum
                              FROM ".DB_PREFIX."timecard
                             WHERE ID = '$ID'") or db_die();
        $row = db_fetch_row($result);
        if ($row[0] <> $user_ID) die("You are not allowed to do this");

        $datc = strtotime($row[2]);
        $diff = $now-$datc;
        $diff = (floor($diff/86400));
        if ($diff > PHPR_TIMECARD_CHANGE) {
            $error = 1;
            message_stack_in(sprintf (__('You cannot add entries at this date. Since there have been %s days. You just can edit entries not older than %s days.'), $diff, PHPR_TIMECARD_CHANGE), "timecard", "error");
        }

        // check whether
        // 1. end time is bigger than start time
        if ($row[1] >= $time) {
            message_stack_in(__('Please check the given time'), "timecard", "error");
            $error = 1;
        }
        // 2. end time is not in conflict with another record on this day
        $result = db_query("SELECT anfang, ende
                              FROM ".DB_PREFIX."timecard
                             WHERE datum = '".$row[2]."'
                               AND users = '$user_ID'") or db_die();
        while ($row = db_fetch_row($result)) {
            if ($time >= $row[0] and $row[1] > 0 and $time <= $row[1]) {
                message_stack_in(__('This field is not empty. Please ask the administrator'), "timecard", "error");
                $error = 1;
            }
        }
        // update the record in the db
        if (!$error) {
            // write end of work time in the db
            $result = db_query("UPDATE ".DB_PREFIX."timecard
                                   SET ende = '$time'
                                 WHERE ID = '$ID'") or db_die();
        }
    }

    // mail note to the chief
    if (PHPR_TIMECARD == '2') {
        // group system?
        if ($user_group > 0) {
            // search for chef of the group - if ther is one!
            $result2 = db_query("SELECT email
                                   FROM ".DB_PREFIX."gruppen, ".DB_PREFIX."users
                                  WHERE ".DB_PREFIX."gruppen.ID = '$user_group'
                                    AND ".DB_PREFIX."gruppen.chef = ".DB_PREFIX."users.ID
                                    AND ".DB_PREFIX."users.status = 0
                                    AND ".DB_PREFIX."users.usertype = 0") or db_die();
            $row2 = db_fetch_row($result2);
        }
        // no chief given or no group system available? -> then take the first available chief
        else if ($row2[0] == '') {
            $result2 = db_query("SELECT email
                                   FROM ".DB_PREFIX."users
                                  WHERE acc LIKE '%c%'
                                    AND status = 0
                                    AND usertype = 0") or db_die();
            $row2 = db_fetch_row($result2);
        }
        // only send mail if a mail adress exists
        if ($row2[0] <> '') {
            if ($type == 'anfang') $type2 = __('Begin');
            if ($type == 'ende') $type2 = __('End');
            use_mail('1');
            $success = $mail->go($row2[0], __('Change in the timecard'),
                                 "$user_name  - $datum - $type2: $time", $user_email);
        }
    } // endif for mail note to the chief
}


// Projektbezug - assign to project
if (($action == "proj") && ($save || $save_close)) {
    $datc = strtotime($datum);
    $diff = $now-$datc;
    $diff = (floor($diff/86400));
    if ($diff > PHPR_TIMECARD_CHANGE) {
        $error = 1;
        message_stack_in(sprintf (__('You cannot add  bookings at this date. Since there have been %s days. You just can add bookings for entries not older than %s days.'), $diff, PHPR_TIMECARD_CHANGE), "timecard", "error");
    }
    // check dates
    if (!ereg("(^[0-9]*$)", $day)) die("<tr><td>".__('Please check the date!')."<br />".__('back')." ...");
    if (!checkdate($month, $day, $year)) die("<tr><td>".__('Please check the date!')."<br />".__('back')." ...");
    if (!$error) {
        // loop over all entries
        for ($i = 0; $i < count($nr); $i++) {
            if ($timeproj_ID[$i]) {
                $result = db_query(xss("UPDATE ".DB_PREFIX."timeproj
                                           SET h = '$h[$i]',
                                               m = '$m[$i]',
                                               note = '$note[$i]'
                                         WHERE ID = '".$timeproj_ID[$i]."'")) or db_die();
            }
            else if ($h[$i] > 0 or $m[$i] > 0) {
                $result = db_query("INSERT INTO ".DB_PREFIX."timeproj
                                                (   ID,      users,   projekt,    datum,   h,       m,        note     )
                                         VALUES ($dbIDnull,'$user_ID','$nr[$i]','$datum','$h[$i]','$m[$i]','$note[$i]')") or db_die();
            }
        }
    }
}

// **********************************
// enter a record into the time sheet
// first the form, the database insert will be executed in the index.php

// begin of work time
if ($action == "1" and !$sure) {
    echo "<b><h3>".__('Begin')."</h3></b>\n";
    echo "<form action='../index.php' method='post' target='_top'>\n";
    echo "<input type='hidden' name='module' value='timecard' />\n";
    echo "<input type='hidden' name='mode' value='data' />\n";
    if (SID) echo "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
    echo "<input type='hidden' name='action' value='1' />\n";
    echo "<input type='hidden' name='sure' value='1' />\n";
    echo "<input type='submit' value='".__('submit')."' />\n</form>\n<br /><br />\n";
}
// stop a running work time entry
else if ($action == "2" and !$sure) {
    echo "<b><h3>".__('End')."</h3></b>\n";
    echo "<form action='../index.php' method='post' target='_top'>\n";
    echo "<input type='hidden' name='module' value='timecard' />\n";
    echo "<input type='hidden' name='mode' value='data' />\n";
    if (SID) echo "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
    echo "<input type='hidden' name='action' value='2' />\n";
    echo "<input type='hidden' name='sure' value='1' />\n";
    echo "<input type='submit' value='".__('submit')."' />\n</form>\n<br /><br />\n";
}
// clock the time for a project  - aka quicktimer
else if ($action == "clock_in") {
    clock_in($projekt,$note);
    header('Location: timecard.php?'.$sid);
    exit;
}
else if ($action == "clock_out") {
    clock_out();
}


function clock_in($projekt,$note) {
    global $today1, $dbTSnull, $user_ID, $dbIDnull, $modes, $canc;
	
    if (!$canc) {
    	//only if module "projects" is activated!
        if (PHPR_PROJECTS and check_role('projects') > 0) {
        	$result = db_query("INSERT INTO ".DB_PREFIX."timeproj
                                        (   ID,       users,    projekt,   datum,    div1,        note    )
                                 VALUES ($dbIDnull,'$user_ID','$projekt','$today1','$dbTSnull', '$note')") or db_die();
        }
    }
    $canc  = "ja";
    $modes = "books";
}


function clock_out() {
    global $today1, $user_ID, $dbTSnull;
    //only if module "projects" is activated!
    if (PHPR_PROJECTS and check_role('projects') > 0) {
    	//calculate difference between clock_in and clock_out
    	$result = db_query("SELECT ID, div1, h, m
                          FROM ".DB_PREFIX."timeproj
                         WHERE users='$user_ID'
                           AND (div1 LIKE '".date("Ym")."%')") or db_die();
    	$row = db_fetch_row($result);
    	if ($row[0] > 0) {
    		// calculate the sconds of the quicktimer span
    		// calculate the sconds of the quicktimer span
    		$seconds = $row[2]*3600 + $row[3]*60 +
    		((substr($dbTSnull,8,2) - substr($row[1],8,2))*3600 +
    		(substr($dbTSnull,10,2) - substr($row[1],10,2))*60 +
    		(substr($dbTSnull,12,2) - substr($row[1],12,2)));
    		// calculate the seconds of the existing value
    		$h = floor($seconds/3600);
    		$m = floor(($seconds - $h*3600)/60);
    		$result = db_query(xss("UPDATE ".DB_PREFIX."timeproj
                                   SET h    = '$h',
                                       m    = '$m',
                                       div1 = ''
                                 WHERE ID='$row[0]'")) or db_die();
    	}
    }
}

$action = "";
if (!$modes) $modes = "view";
include_once("./timecard_view.php");

?>
