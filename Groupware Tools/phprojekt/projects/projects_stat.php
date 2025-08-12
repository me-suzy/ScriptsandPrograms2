<?php

// projects_stat.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: nina $
// $Id: projects_stat.php,v 1.25 2005/07/26 12:26:29 nina Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) die("Please use index.php!");


// show error messages
if ($action == "calc") {
    if (!$userlist[0]) {
        $err    = "<b>".__('Please choose at least one person')."</b><br /><br />";
        $action = "";
    }
    if (!$projectlist[0]) {
        $err    = "<b>".__('Please choose at least one project')."</b><br /><br />";
        $action = "";
    }
}

// show input box only if the table shouldn't be shown ...
if ($action <> "calc") {
    //if($projectlistsave[0]=='gesamt') $projectlist=$projectlistsave;
    $projectlist=$projectlistsave;
    //if($userlistsave[0]=='gesamt') $userlist=$userlistsave;
    $userlist=$userlistsave;
    // set default date
    if (!$day)   $day   = date("d");
    if (!$month) $month = date("m");
    if (!$year)  $year  = date("Y");

    //tabs
    $tabs = array();
    /**
    $tmp = get_export_link_data('project_stat', false);
     $tabs[] = array('href' => $tmp['href'], 'active' => $tmp['active'], 'id' => 'tab4', 'target' => '_self', 'text' => $tmp['text'], 'position' => 'right');*/
    $output .= get_tabs_area($tabs);
    // button bar
    $buttons = array();
    $buttons[] = array('type' => 'link', 'href' => 'projects.php?mode=forms&amp;action=new'.$sid, 'text' => __('New'), 'active' => false);
    $buttons[] = array('type' => 'link', 'href' => 'projects.php?mode=options'.$sid, 'text' => __('Options'), 'active' => false);
    $buttons[] = array('type' => 'link', 'href' => 'projects.php?mode=stat'.$sid, 'text' => __('Statistics'), 'active' => ((isset($mode2) && $mode2 == 'mystat')) ? false : true);
    $buttons[] = array('type' => 'link', 'href' => 'projects.php?mode=stat&amp;mode2=mystat'.$sid, 'text' => __('My Statistic'), 'active' => ((isset($mode2) && $mode2 == 'mystat')) ? true : false);
    $buttons[] = array('type' => 'link', 'href' => 'projects.php?mode=gantt'.$sid, 'text' => __('Gantt'), 'active' => false);
    $buttons[] = array('type' => 'link', 'href' => 'projects.php?type='.$type.'&amp;sort='.$sort.'&amp;mode=view&amp;up='.$up.'&amp;filter='.$filter.'&amp;keyword='.$keyword.'&amp;perpage='.$perpage.'&amp;page='.$page, 'text' => __('back'), 'active' => false);
    $output .= get_buttons_area($buttons);
    $output .= '<div class="hline"></div>';


    // start form for input
    $html .= $err;
    $html .= "<form action='projects.php' method='post' name='frm'>\n";
    if (SID) $html .="<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
    $html .= "<input type='hidden' name='mode' value='stat' />\n";
    $html .= "<input type='hidden' name='mode2' value='$mode2' />\n";
    $html .= "<input type='hidden' name='action' value='calc' />\n";
    $html .= "<div><br />".__(' Choose a combination Project/Person')."<br />\n";
    $html .= __('(multiple select with the Ctrl/Cmd-key)')."<br /><br /></div>\n";
    // begin input table
    // show boxes for start and end time
    // first time call: give default values
    if (!$start_day)   $start_day   = "01";
    if (!$start_month) $start_month = "01";
    if (!$start_year)  $start_year  = date("Y");
    if (!$end_day)     $end_day     = date("d");
    if (!$end_month)   $end_month   = date("m");
    if (!$end_year)    $end_year    = date("Y");
    $html .= datepicker();
    // start day value
    $html .= "<span class='lf'><label for='anfang'>".__('Begin:')."</label> <input type='text' name='anfang' id='anfang' value='$start_year-$start_month-$start_day' size='10' onblur=\"chkISODate('frm','anfang','".__('ISO-Format: yyyy-mm-dd')."');\" />";
    $html .= "&nbsp;<a href='javascript://' title='".__('This link opens a popup window')."' onclick='callPick(document.frm.anfang);'><img src='".$img_path."/cal.gif' border='0' alt='calendar' /></a>&nbsp;<br />\n";


    // display project list
    $html .= "<br /><label for='projectlist'>".__('Projects').":</label><br /> <select name='projectlist[]' id='projectlist' multiple='multiple' size='20'>\n";
    $html .= "<option value='gesamt'";
     if ($projectlist[0]=='gesamt')$html.= ' selected="selected"';
    $html.= ">".__('All')."</option>\n";
    show_projects("0");
    $html .= "</select></span>\n";
    // end day value
    $html .= "<span class='lf'><label for='ende'>".__('End:')."</label> <input type='text' name='ende' id='ende' value='$end_year-$end_month-$end_day' size='10' onblur=\"chkISODate('frm','ende','".__('ISO-Format: yyyy-mm-dd')."','".__('Begin > End')."!');\" />";
    $html .= "&nbsp;<a href='javascript://' title='".__('This link opens a popup window')."' onclick='callPick(document.frm.ende)'><img src='".$img_path."/cal.gif' border='0' alt='calendar' /></a><br />\n";
    // display user list
    $html .= "<br /> <label for='userlist'>".__('Persons').":</label><br />\n";
    // first case: show only my statistic
    if ($mode2 == "mystat") {
        $html .= "<input type='hidden' name='userlist[]' id='userlist' value='$user_ID' />\n";
        $html .= "$user_name, $user_firstname\n";
    }
    else {
        $html .=  "<select name='userlist[]' id='userlist' multiple='multiple' size='20'>\n";
        // option 'all users' only available for usrs with chief status
        if (ereg("c", $user_access)){
            $html .= "<option value='gesamt'";
            if ($userlist[0]=='gesamt')$html.= ' selected="selected"';
            $html.= ">".__('All')."</option>\n";
        }

        // fetch all users from this group
        $result2 = db_query("select ".DB_PREFIX."users.ID, nachname, vorname, kurz
                               from ".DB_PREFIX."users, ".DB_PREFIX."grup_user
                              where ".DB_PREFIX."users.ID = user_ID
                                and grup_ID = '$user_group'
                           order by nachname") or db_die();
        while ($row2 = db_fetch_row($result2)) {
            // list them only if 1. the user is yourself, 2. you are an user with chief status or 3. you are leader of at least one project :-)
            if ($user_kurz == $row2[3] or ereg("c", $user_access) or $leader) {
                $html .= "<option value='$row2[0]'";
                if ($userlist[0] > 0 and in_array($row2[0], $userlist)) $html .= " selected='selected'";
                $html .= ">$row2[1], $row2[2]</option>\n";
            }
        }
        $html .= "</select>\n";
    }
    $html .= "</span><br class='clear' /><br />\n";
    // end list users
    // show check boxes for bookings
    // dates ...
     if ($showbookingdates == "on") $showbookingdatesflag = " checked='checked'";
    $html .= "<input type='checkbox' name='showbookingdates' $showbookingdatesflag /> ".__('Show bookings')."\n";
    // ... and additionally the notes
    if ($showbookingnotes == "on") $showbookingnotesflag = " checked='checked'";
    $html .= "<input type='checkbox' name='showbookingnotes' id='showbookingnotes' $showbookingnotesflag /> <label for='showbookingnotes'>".__('remark')."</label>";
    $html.="<br/><br/>";
    if(!$display)$display='normal';
    if ($display == "normal") $normalflag = " checked='checked'";
    elseif ($display == "date") $dateflag = " checked='checked'";
    $html .= "".__('sort by').": <input type='radio' name='display' value='normal' id='normal' $normalflag/> <label for='normal'>".__('Project')."</label>";
      $html .= " <input type='radio' name='display' value='date' id='date' $dateflag/> <label for='date'>".__('Date')."</label> <br />\n";

    $html .= "<br />".get_buttons(array(array('type' => 'submit', 'value' => __('go'), 'active' => false)))."\n";
    $html .= "</form>\n";

    $output .= '
    <br/>
    <div class="inner_content">
        <a name="content"></a>
        <div class="boxHeader">'.__('Project summary').'</div>
        <div class="boxContent">'.$html.'
        <br style="clear:both"/><br/><br/><br/>

    </div>
        <br style="clear:both"/><br/></div>
    ';

    // end of input table
    unreg_sess_var("projectlist");
    unreg_sess_var("userlist");
}

//******************
//statistic list
// *****************

else if ($projectlist[0] and $userlist[0]) {
    $projectlistsave=$projectlist;
    $userlistsave=$userlist;
    $_SESSION['projectlistsave'] =& $projectlistsave;
    $_SESSION['userlistsave'] =& $userlistsave;

    //tabs
    $tabs = array();
    if($display=='normal')$tmp = get_export_link_data('project_stat', false);
    elseif($display=='date')$tmp = get_export_link_data('project_stat_date', false);
    $tabs[] = array('href' => $tmp['href'], 'active' => $tmp['active'], 'id' => 'tab4', 'target' => '_self', 'text' => $tmp['text'], 'position' => 'right');
    $output .= get_tabs_area($tabs);

    // button bar
    $buttons = array();
    $buttons[] = array('type' => 'link', 'href' => 'projects.php?mode=forms&amp;action=new'.$sid, 'text' => __('New'), 'active' => false);
    $buttons[] = array('type' => 'link', 'href' => 'projects.php?mode=options'.$sid, 'text' => __('Options'), 'active' => false);
    $buttons[] = array('type' => 'link', 'href' => 'projects.php?mode=stat'.$sid, 'text' => __('Statistics'), 'active' => false);
    $buttons[] = array('type' => 'link', 'href' => 'projects.php?mode=stat&amp;mode2=mystat'.$sid, 'text' => __('My Statistic'), 'active' => true);
    $buttons[] = array('type' => 'link', 'href' => 'projects.php?mode=gantt'.$sid, 'text' => __('Gantt'), 'active' => false);
    $buttons[] = array('type' => 'link', 'href' => 'projects.php?mode=stat&amp;mode2='.$mode2.'&amp;anfang='.$anfang.'&amp;ende='.$ende.'&amp;showbookingdates='.$showbookingdates.'&amp;showbookingnotes='.$showbookingnotes.$sid, 'text' => __('back'), 'active' => false);

    $output .= get_buttons_area($buttons);
    $output .= '<div class="hline"></div>';



    // title
    $html = $err;
    $html .="<br /> ".__('Begin').": $anfang, ".__('End').": $ende<br /><br />\n";
    // check whether the given values are valid dates
    if (!checkdate(substr($anfang,5,2), substr($anfang,8,2), substr($anfang,0,4))) die(__('Please check the date!')." <br />".__('back')." ...");
    if (!checkdate(substr($ende,5,2), substr($ende,8,2), substr($ende,0,4))) die(__('Please check the date!')." <br />".__('back')." ...");
    if ($ende < $anfang) die(__('Please check start and end time! '));
    // fetch all projects
    if($display=='normal'){
    if ($projectlist[0] == "gesamt") {
         unset($projectlist);
        if ($mode2 == "mystat") {
            $result = db_query("select ID
                                  from ".DB_PREFIX."projekte
                              order by name") or db_die();
        }
        else {
            $result = db_query("select ID
                                  from ".DB_PREFIX."projekte
                                 where $sql_user_group
                             order by name") or db_die();
        }
        while ($row = db_fetch_row($result)) $projectlist[] = $row[0];
    }

    }


    else{
    //where Klausel für Personen!
      $where_person='';
      if ($userlist[0] == "gesamt"); 	
      else{
      	foreach ($userlist as $person) {
      		$where_person.="t.users='$person' or ";
      	}
      	if($where_person<>'')$where_person.="1!=1";
      }
      if ($projectlist[0] == "gesamt") {
        unset($projectlist);
        if ($mode2 == "mystat") {

            $result = db_query("select datum, p.ID
                                  from ".DB_PREFIX."projekte as p , ".DB_PREFIX."timeproj as t
                                  WHERE p.ID=t.projekt AND datum >= '$anfang'
                           AND datum <= '$ende'  AND ($where_person) group by p.name,datum
                              order by datum, p.name") or db_die();
        }
        else {
            $result = db_query("select datum, p.ID
                                  from ".DB_PREFIX."projekte as p,".DB_PREFIX."timeproj as t
                                  WHERE p.ID=t.projekt
                                 AND datum >= '$anfang'
                           AND datum <= '$ende' AND $sql_user_group  AND ($where_person) group by p.name,datum
                             order by datum,  p.name") or db_die();

        }
        $i=0;
        while ($row = db_fetch_row($result)){
            $projectlist[$i][$row[0]] = $row[1];
            $i++;
        }
    }
    else {
        $where='';
        foreach($projectlist as $project) $where.= "projekt='$project' or ";
        if($where<>'')$where.="1!=1";
        unset($projectlist);
        $query= "select datum, p.ID
                           from ".DB_PREFIX."projekte as p,".DB_PREFIX."timeproj as t
                                  WHERE p.ID=t.projekt AND datum >= '$anfang'
                           AND datum <= '$ende'
                                 AND $sql_user_group AND ($where)  AND ($where_person) group by p.name,datum
                        order by datum, p.name";
        $result = db_query($query) or db_die();
        $i=0;
        while ($row = db_fetch_row($result)){
            $projectlist[$i][$row[0]] = $row[1];
            $i++;
        }

    }

  }


    // fetch all users from this group
    if ($userlist[0] == "gesamt") {
        unset($userlist);
        $result = db_query("select user_ID
                              from ".DB_PREFIX."grup_user, ".DB_PREFIX."users
                             where grup_ID = '$user_group' and
                                   ".DB_PREFIX."users.ID = user_ID
                          order by nachname") or db_die();
        while ($row = db_fetch_row($result)) $userlist[] = $row[0];
    }
    // begin output table
    $html .= " <table class='ruler'>";

    if($display=='normal')$html.= "<thead><tr><th><b>[h : m]</b></th>\n";
    else $html.= "<thead><tr><th><b>".__('Date')."</b></th><th><b>".__('Project Name')."</b>\n";

    // first row: the users!
    foreach ($userlist as $person) {
        $result = db_query("select kurz
                              from ".DB_PREFIX."users
                             where ID = '$person'") or db_die();
        $row = db_fetch_row($result);
        $html .= "<th>$row[0]</th>\n";
    }
    // end of the first row - display string 'sum'
    $html .= "<th><b>".__('Sum')."</b>\n";
    $html .= "</tr></thead><tbody>\n";
    $i=0;
    // now loop over project list
    foreach ($projectlist as $project) {

        // alternate tr colour
        if (($cnr/2) == round($cnr/2)) {
            $color = PHPR_BGCOLOR1;
            $cnr++;
        }
        else {
            $color = PHPR_BGCOLOR2;
            $cnr++;
        }

        // print project name

        if($display=='normal'){
            $result = db_query("select name
                              from ".DB_PREFIX."projekte
                             where ID = '$project'") or db_die();
            $row = db_fetch_row($result);
            $html .= "<tr bgcolor=$color><td>$row[0]</td>\n";
            // loop over list of persons and fetch the bookings
            foreach ($userlist as $person) { $html .=fetch_bookings($project, $person); }

         }
        else{

            foreach($project as $datum => $projID){
                $i++;

            $result = db_query("select name
                              from ".DB_PREFIX."projekte
                             where ID = '$projID'") or db_die();
               $row = db_fetch_row($result);
                $html .= "<tr bgcolor=$color><td>$datum</td>\n";
                $html .= "<td>$row[0]</td>\n";
                foreach ($userlist as $person) {
                $html .=fetch_date_bookings($projID, $person,$datum,$i);

                }
                $h = floor($sumdatum[$datum.$projID]/60);
                $m = $sumdatum[$datum.$projID] - $h*60;
                $html .= "<td valign='bottom'><b>$h : $m</b></td>\n";
                $html .= "</tr>\n";

            }
        }
       if($display=='normal'){
            $h = floor($sumproject[$project]/60);
            $m = $sumproject[$project] - $h*60;
            $html .= "<td valign='bottom'><b>$h : $m</b></td>\n";
            $html .= "</tr>\n";
       }
    }

    // last row: show sums for the projects
    $html .= "</tbody><tfoot><tr><td><b>".__('Sum')."</b></td>\n";
    if($display!='normal') $html .='<td></td>';
    foreach ($userlist as $person) {
        $h = floor($sumperson[$person]/60);
        $m = $sumperson[$person] - $h*60;
        // sum up for totalsum
        $totalsum += $sumperson[$person];
        $html .= "<td><b>$h : $m</b></td>";
    }



    // display total sum in the last cell of the table and close the table
    $h = floor($totalsum/60);
    $m = $totalsum - $h*60;
    $html .= "<td><b>$h : $m</td></tr>\n";
    $html .= "</tfoot></table><br /><br />";

    $output .= '
    <br/>
    <div class="inner_content">
        <div class="boxHeader">'.__('Statistics').'</div>
        <div class="boxContent">'.$html.'</div>
        <br style="clear:both"/><br/>
    </div>
    <br style="clear:both"/><br/>
    ';

    // register the pojectlist and the participants so the user won't have to select them again
    $_SESSION['projectlist'] =& $projectlist;
    $_SESSION['userlist'] =& $userlist;

}

echo $output;


function fetch_bookings($project, $person) {
    global $anfang, $ende, $show_bookings, $sumperson, $sumproject, $showbookingdates, $showbookingnotes;

    // start table cell

    // open the table of bookings if flag is set
    $out='<td valign="bottom"';
    // fetch values from time card bookings  the bookings are between start and end time
    $result = db_query("SELECT datum, h, m, note
                          FROM ".DB_PREFIX."timeproj
                         WHERE projekt = '$project'
                           AND users = '$person'
                           AND datum >= '$anfang'
                           AND datum <= '$ende'
                      ORDER BY datum");
    while ($row = db_fetch_row($result)) {
        // detailed booking display?
        if ($showbookingdates) {
            $out.= "<div style='float:left; padding-right:10px;'>$row[0] - $row[1] : $row[2]</div>";
            if ($showbookingnotes)$out.= "<div align='right'>$row[3]&nbsp;</div>";
            $out.= "<br style='clear:both;'/>\n";
        }
        // sum up

        $sum1  = $sum1 + $row[1]*60+$row[2];
        $datum = $row[0];
    }

    // close the table of bookings if flag is set
     // build hours and minutes of the sum and display it
    $h = floor($sum1/60);
    $m = $sum1 - $h*60;
     //if (!$showbookingdates)
     $out .= "<b>$h : $m</b>";
     $out.="</td>\n";

    // add sum to the overall sum of this person
    $sumperson[$person]   += $sum1;
    $sumproject[$project] += $sum1;
    return  $out;
}

function fetch_date_bookings($project, $person, $datum,$i) {
    global $anfang, $ende, $show_bookings, $sumperson, $sumdatum, $showbookingdates, $showbookingnotes;

    // start table cell

    // open the table of bookings if flag is set
    $out='<td valign="bottom"';
    // fetch values from time card bookings  the bookings are between start and end time
    $result = db_query("SELECT datum, h, m, note
                          FROM ".DB_PREFIX."timeproj
                         WHERE projekt = '$project'
                           AND datum='$datum'
                           AND users = '$person'
                           ORDER BY datum");
    while ($row = db_fetch_row($result)) {
        // detailed booking display?
        if ($showbookingdates) {
            $out.= "<div style='float:left; padding-right:10px;'>$row[0] - $row[1] : $row[2]</div>";
            if ($showbookingnotes)$out.= "<div align='right'>$row[3]&nbsp;</div>";
            $out.= "<br style='clear:both;'/>\n";
        }
        // sum up

        $sum1  = $sum1 + $row[1]*60+$row[2];
        $datum = $row[0];
    }

    // close the table of bookings if flag is set
     // build hours and minutes of the sum and display it
    $h = floor($sum1/60);
    $m = $sum1 - $h*60;
     //if (!$showbookingdates)
     $out .= "<b>$h : $m</b>";
     $out.="</td>\n";

    // add sum to the overall sum of this person
    $sumperson[$person]   += $sum1;
    $sumdatum[$datum.$project] += $sum1;
    return  $out;
}

// register the pojectlist and the participants so the user won't have to select them again
#reg_sess_vars(array("projectlist","userlist"));


function show_projects($parent_ID) {
    global $indent, $user_kurz, $leader, $sql_user_group, $projectlist, $mode2, $output;
    global $html;

    // fetch parent project
    // 1. case myprojects - independent from the group
    if ($mode2 == "mystat") {
        $result = db_query("SELECT ID, name, personen, chef
                              FROM ".DB_PREFIX."projekte
                             WHERE parent = '$parent_ID'
                          ORDER BY name") or db_die();
    }
    // 2. case: query as project leader or chef - only for the group
    else {
        $result = db_query("SELECT ID, name, personen, chef
                              FROM ".DB_PREFIX."projekte
                             WHERE parent = '$parent_ID'
                               AND $sql_user_group
                          ORDER BY name") or db_die();
    }
    while ($row = db_fetch_row($result)) {
        // identify user as project leader, flag for later use at users list
        if ($row[3] == $user_kurz) $leader = 1;
        if (ereg("\"$user_kurz\"", $row[2]) or $row[3] == $user_kurz ) {
            $html.= "<option value='$row[0]'";
            if ($projectlist[0] and in_array($row[0], $projectlist)) $html.= ' selected="selected"';
            $html.= ">";
            for ($i = 1; $i <= $indent; $i++) $html.= "&nbsp;&nbsp;";
            $html.=  "$row[1]</option>\n";
        }
        // look for subelements
        $indent++;
        show_projects($row[0]);
        $indent--;
    }
}

?>
