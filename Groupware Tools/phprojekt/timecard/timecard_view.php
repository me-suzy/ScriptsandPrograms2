<?php

// timecard_view.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: nina $
// $Id: timecard_view.php,v 1.38.2.4 2005/08/19 20:56:41 nina Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) { die("Please use index.php!"); }

// check role
if (check_role("timecard") < 1) { die("You are not allowed to do this!"); }

// wird benötigt um Projekte auz/zu zuklappen

 if($tree2_mode=="close"){
        $arrproj1= $empt_arr;
}
else if($tree2_mode=="open"){
  $result2t = db_query("select ID
      from ".DB_PREFIX."projekte") or db_die();
 while ($row2t = db_fetch_row($result2t)) {
        $arrproj1[$row2t[0]] ="1";
    }
  }
$tree2_mode="";
if ($element2_mode == "open") {
  $arrproj1[$ID] = "1";
}
elseif ($element2_mode == "close"){
  $arrproj1[$ID] = "";
}
$_SESSION['arrproj1'] =& $arrproj1;
//Projzusatzfunktion Ende


$y = date("Y", mktime(date("H")+PHPR_TIMEZONE,date("i"),date("s"),date("m"),date("d"),date("Y")));
    if($date){
        $year=substr($date,0,4);
        $month= substr($date,5,2);
    }
    if(!$year)$year=$y;
    if(!$month)$month=date("m", mktime(date("H")+PHPR_TIMEZONE,date("i"),date("s"),date("m"),date("d"),date("Y")));
echo '<script type="text/javascript">
    function auf(url,name,det){
        var ex= window.open(url,name ,
         det);

     if (ex.opener == null) {
         ex.opener = window;
     }
 }';
echo '</script>';

     $result = db_query("select ID, users, datum, projekt, anfang, ende
                        from ".DB_PREFIX."timecard
                       where users = '$user_ID' and
                             datum like '$year-$month-%'
                    order by datum desc") or db_die();
         $result = db_query("SELECT ID,datum,MIN(anfang)as anf, MAX(ende) as max,
        SUM(nettoh),SUM(nettom) from ".DB_PREFIX."timecard where users = '$user_ID' and 
        datum like '$year-$month-%' GROUP by datum, ID order by datum desc")or db_die();
    $liste= make_list($result);

//tabs
$tabs = array();
if($view=="days" ){
    $tabs[] = array('href' => 'timecard.php?view=days&amp;year='.$year.'&amp;date='.$date.'&amp;month='.$month.'', 'active' => true, 'id' => 'tab1', 'target' => '_self', 'text' => __('Working times'), 'position' => 'left');
        //only show projectbookings if project module is activated!
    if (PHPR_PROJECTS and check_role('projects') > 0) {
    	$tabs[] = array('href' => 'timecard.php?view=proj&amp;year='.$year.'&amp;date='.$date.'&amp;month='.$month.'', 'active' => false, 'id' => 'tab2', 'target' => '_self', 'text' => __('Project bookings'), 'position' => 'left');
    }
}
else if($view=="proj" ){
    $tabs[] = array('href' => 'timecard.php?view=days&amp;year='.$year.'&amp;date='.$date.'&amp;month='.$month.'', 'active' => false, 'id' => 'tab1', 'target' => '_self', 'text' => __('Working times'), 'position' => 'left');
    $tabs[] = array('href' => 'timecard.php?view=proj&amp;year='.$year.'&amp;date='.$date.'&amp;month='.$month.'', 'active' => true, 'id' => 'tab2', 'target' => '_self', 'text' => __('Project bookings'), 'position' => 'left');
}
$tmp = get_export_link_data('timecard', false);
$tabs[] = array('href' => $tmp['href'], 'active' => $tmp['active'], 'id' => 'export', 'target' => '_self', 'text' => $tmp['text'].' '.__('Timecard'), 'position' => 'right');
$tmp = get_export_link_data('timeproj', false);
$tabs[] = array('href' => $tmp['href'], 'active' => $tmp['active'], 'id' => 'export1', 'target' => '_self', 'text' => $tmp['text'].' '.__('Show bookings'), 'position' => 'right');
$output .= get_tabs_area($tabs);

// button bar
$buttons = array();
//$buttons[] = array('type' => 'text', 'text' => '<b>'.__('Assign work time').__(':').'</b>');
//Zeit nachtragen wird ab sofort über timecard_forms gesteuert!
//$buttons[] = array('type' => 'button', 'name' => 'nachtragen', 'value' => __('insert additional working time'), 'active' => false, 'onclick' => 'auf(\'timecard_forms.php\',\''.str_replace(' ', '', __('insert additional working time')).'\',\'width=720,height=400,scrollbars=no,resizable=no\');');
//Zeit auf Prohekte buchen auch über forms
//$buttons[] = array('type' => 'button', 'name' => 'projzu', 'value' => __('Project assignment'), 'active' => false, 'onclick' => 'auf(\'timecard_books.php\',\''.str_replace(' ', '', __('insert additional working time')).'\',\'width=720,height=400,scrollbars=yes,resizable=no\');');
$buttons[] = array('type' => 'text', 'text' => '<b>'.__('stop watches').__(':').' </b>');
// stop watch working time
$result = db_query("select ID
                    from ".DB_PREFIX."timecard
                   where datum = '$datum' and
                         (ende = '' or ende is NULL) and
                         users = '$user_ID'") or db_die();
$row = db_fetch_row($result);
// buttons for 'come' and 'leave', alternate display
if ($row[0] > 0) {
    $buttons[] = array('type' => 'link', 'href' => 'timecard.php?mode=data&amp;view='.$view.'&amp;action=&amp;sure=1', 'text' => __('Working times stop'), 'stopwatch' => 'started');
}
else{
    $buttons[] = array('type' => 'link', 'href' => 'timecard.php?mode=data&amp;view='.$view.'&amp;action=1&amp;sure=1', 'text' => __('Working times start'), 'stopwatch' => 'stopped');
}
//only show projectbookings if project module is activated!
if (PHPR_PROJECTS and check_role('projects') > 0) {
	// stop watch project time
	$resultq = db_query("select ID, div1, h, m
                        from ".DB_PREFIX."timeproj
                       where users = '$user_ID' and
                             (div1 like '".date("Ym")."%')") or db_die();
	$rowq = db_fetch_row($resultq);
	// buttons for 'come' and 'leave', alternate display
	if ($rowq[0] > 0) {
		$buttons[] = array('type' => 'link', 'href' => 'timecard.php?mode=data&amp;view='.$view.'&amp;action=clock_out', 'text' => __('Project booking stop'), 'stopwatch' => 'started');
	}  else {
		$buttons[] = array('type' => 'link', 'href' => 'timecard.php?mode=books&amp;view='.$view.'&amp;action=clockin'.$sid, 'text' => str_replace('-', '', __('Project booking start')), 'stopwatch' => 'stopped');
	}
}
$output .= get_buttons_area($buttons);
$output .= get_status_bar();
$output .='<div class="hline"></div><a name="content"></a>';

//$output.= get_top_page_navigation_bar();
//$output .='<div class="hline"></div>';
//$output.= get_status_bar();
$output .= "<div class='tc_right'>\n";
if($view=='days')include_once('timecard_forms.php');
else include_once('timecard_books.php');
$output.="</div>";
$output .="<div class='tc_left'>";
$output.= "<div class='tc_header'>";
$buttons = array();
$buttons[] = array('type' => 'text', 'text' =>__('My working time overview').__(':'));
$output.= get_buttons($buttons);
if(eregi(0,$month)){
    $monthsub = substr($month,0,1);
    if($monthsub==0) $monthna =substr($month,1);
    else$monthna=$month;
}
else$monthna=$month;
$output.=' '.$name_month[$monthna]." ".$year."</div>\n";
$output.= "<div class='admin_fields'>\n";

$output.= '
<div>  <b>'.__('choose month').__(':').'</b>
    <form style="display: inline;" action="timecard.php" name="date" method="post">
    <input type="hidden" name="mode" value="view" />
    <input type="hidden" name="view" value="'.$view.'" />
    <input type="hidden" name="page" value="'.$page.'" />
    <select name="month">';
for ($a=1; $a<13; $a++) {
    $mo = date("n", mktime(0,0,0,$a,1,$year));
    $name_of_month = $name_month[$mo];
    if ($mo == $month) {$output .= "<option value='$a' selected='selected'>$name_of_month</option>\n";}
    else {$output .= "<option value='$a'>$name_of_month</option>\n";}
}
$output .= '</select><select name="year">';
for ($i=$y-2; $i<=$y+5; $i++) {
    if ( $i == $year) {$output .= "<option selected='selected'>$i</option>\n";}
    else {$output .="<option>$i</option>\n";}
}
$output .='
    </select>&nbsp;<input class="button2" value="GO" type="submit" />
    </form>
      </div>
      <div class="hline"></div><br/>';

 $output.="<table class=\"ruler\" summary=\"$tc_sum\">
    <thead>
        <tr>
            <th scope=\"col\" title=\"Wochentag\">".__('Weekday')."</th>
            <th scope=\"col\" title=\"Datum\">".__('Date')."</th>
            <th scope=\"col\" title=\"Beginn\">".__('Start')."</th>
            <th scope=\"col\" title=\"Ende\">".__('End')."</th>
            <th scope=\"col\" title=\"Nettozeit\">".__('Hours')."</th>
        </tr>
    </thead>";
 $outbody="<tbody>";

 //for ($i=($page*$perpage); $i < $max; $i++) {
        $result = db_query("SELECT MAX(ID), datum, MIN(anfang) as anf, MAX(ende) as max,
        SUM(nettoh),SUM(nettom) from ".DB_PREFIX."timecard where users = '$user_ID' and  datum like '$year-$month-%' GROUP by datum order by datum desc")
        or db_die();
        $int=0;
        while($row = db_fetch_row($result)){
            $int++;

        $datum2 = explode("-", $row[1]);
        $wo_tag = date('w', mktime(0,0,0,$datum2[1],$datum2[2],$datum2[0]));
        if (($row[2] or $row[2]==0) and $row[3]) {
            $row[2] = check_4d($row[2]);
             $row[3] = check_4d($row[3]);
             $result2= db_query("SELECT anfang, ende, nettoh, nettom from ".DB_PREFIX."timecard where users = '$user_ID' and  datum like '$row[1]' order by datum desc")
        or db_die();
        $dsum='';
        while($row2 = db_fetch_row($result2)){
            $row2[0] = check_4d($row2[0]);
             $row2[1] = check_4d($row2[1]);
             if(($row2[0]&&$row2[1])and !($row2[2]or $row2[3])){
             $dsum =$dsum+(substr($row2[1],0,2) - substr($row2[0],0,2))*60 + substr($row2[1],2,4) - substr($row2[0],2,4);
             }

            else $dsum= $dsum+$row2[2]*60 + $row2[3];
        }
         $sum1 = $sum1 + $dsum;
        }
    $error_day = '';
      $h1 = floor($dsum/60);
      $m1 = $dsum - $h1 * 60;
            $outbody.= "<tr";
        if($int%2==1){
             $outbody.= " class=\"unev\" ";
        }

        $outbody.="> <td scope='row'>$name_day[$wo_tag]</td>
        <td>$row[1]</td>
        <td>".check_4d($row[2])."</td>
        <td>".check_4d($row[3])."</td>
        <td>";
        if(!$error_day) { $outbody.="$h1 h $m1 m"; }
         $outbody.="&nbsp;
        </td>
        </tr>";
        if($view=="days" ){
            $outbody.= show_bookings($row[1]);
        }
        else{
            $outbody.= show_prbookings($row[1]);
        }
        }
 if($int==0)$outbody.="<tr><td></td><td></td><td></td><td></td></tr>";
$outbody.="</tbody>";
$output.="<tfoot>
    <tr>
      <td colspan='5' align='right' style='padding-right:20px;' >";
 if (!$error_month) {
    $h1 = floor($sum1/60);
    $m1 = $sum1 - $h1 * 60;
    $output.=__('Sum for')." $month/$year: $h1 ".__('Hours').", $m1 ".__('minutes');}
    $output.="
    </td></tr>
  </tfoot>$outbody </table>";


$output.="</div></div>";
//get_bottom_page_navigation_bar();
echo $output;

function list_projects($query, $order,$parent) {
  global $user_kurz, $user_ID, $datum, $indent, $h_sum, $m_sum, $today1, $img_path, $fld_nr, $sid;

   // fetch all projects from this group
  $result2 = db_query("$query and parent = '$parent' $order") or db_die();
  while ($row2 = db_fetch_row($result2)) {

    // BIG check: list the project under certain conditions
    if
      // 1. the user has access to it: be a member or the project leader
      ((ereg("\"$user_kurz\"",$row2[1]) or $row2[2] == $user_kurz) and
      // 2. aditional check: status of the project must be null or active
      ($row2[3] == '3' or !$row2[3]) and
      // 3. check whether the mentioned day is between begin and end of this record
      ($row2[4] <= $datum and $row2[5] >= $datum)) {
      // end of BIG check :-)

      echo "<tr><td>";
      // indent
      for ($i=0; $i < $indent; $i++) { echo "&nbsp;&nbsp;&nbsp;&nbsp;"; }
      // name of the project
      echo html_out($row2[6])."</td>\n";
      // show input fields for hour and minute
      echo "<td><input type='text' name='h[]' size='3' maxlength='2' onblur=\"chkChrs('frm',(int)$fld_nr,'0 - 23!',/[2][0-3]|[0-1]?\d?/,1)\" /></td>\n";
      echo "<td><input type='text' name='m[]' size='3' maxlength='2' onblur=\"chkChrs('frm',".($fld_nr + 1).",'0 - 59!',/[0-5]?\d?/,1)\" /></td>\n";
      echo "<td><input type='text' name='note[]' size='40' maxlength='255 'value='".html_out($row3[3])."' ></td><td>\n";
      echo "<input type='hidden' name='timeproj_ID[]' value='$row3[0]' />\n";
      echo "<input type='hidden' name='nr[]' value='$row2[0]' />\n";
      $fld_nr += 5;

      // start quicktimer
      if($datum == $today1) {
        // check whether there is an active/running booking
        $result3 = db_query("select ID, projekt
                               from ".DB_PREFIX."timeproj
                              where datum = '$today1' and
                                    div1 like '".date("Ym")."%' and
                                    users = '$user_ID'") or db_die();
        $row3 = db_fetch_row($result3);
        if ($row3[0] > 0) {
          // is it this project?
          if ($row3[1] == $row2[0]) {
            // show stop button
            echo "<a href='timecard.php?mode=data&amp;action=clock_out&amp;projekt=$row2[0]".$sid."'><img src='$img_path/stop.gif' alt='".__('End')."' title='".__('End')."' border='0'/></a>";
          }
        }
        // no project running - give all of them a green button :-)
        else {
          echo "<a href='timecard_book.php?mode=books&amp;action=clock_in&amp;projekt=$row2[0]".$sid."'><img src='$img_path/start.gif' alt='".__('Begin')."' title='".__('Begin')."' border='0' /></a>";
        }
      }

      echo "&nbsp;</td></tr>\n";

      // look for entries for project/day combination
      $result3 = db_query("select ID, h, m, note
                             from ".DB_PREFIX."timeproj
                            where datum = '$datum' and
                                  users = '$user_ID' and
                                  projekt = '$row2[0]'") or db_die();
      while ($row3 = db_fetch_row($result3)) {
        echo  "<tr><td colspan=3>&nbsp;</td><td>$row3[1] : $row3[2] $row3[3]</td>\n";
        // red button to delete this entry
        echo "<td><a href='timecard.php?mode=data&amp;action=delete_booking&amp;ID=".$row3[0]."'><img src='$img_path/r.gif' alt='".__('Delete')."' title='".__('Delete')."' border='0' width='7' /></a></tr>\n";

      }

    } // end of bracket for record listing

    // look for subprojects
    $indent++;
    list_projects($query,$order,$row2[0]);
    $indent--;
  }
  // summarize all work time of a day
  $result4 = db_query("select SUM(h), SUM(m)
                         from ".DB_PREFIX."timeproj
                        where datum = '$datum' and
                              users = '$user_ID'") or db_die();
  $row4 = db_fetch_row($result4);
  $h_sum =$row4[0];
  $m_sum =$row4[1];

}

function show_prbookings($day) {
  global $user_ID;
  $result2 = db_query("select name, h, m, ".DB_PREFIX."timeproj.note
                         from ".DB_PREFIX."timeproj, ".DB_PREFIX."projekte
                        where projekt = ".DB_PREFIX."projekte.ID and
                              users = '$user_ID' and
                              datum like '$day'
                     order by name") or db_die();
  while ($row2 = db_fetch_row($result2)) {
    $out.= "<tr class='book2'><td class='book2'></td>
     ";
    $out.="<td class='book2'>$row2[0]</td>";
    $out.="<td class='book2'>$row2[3]</td><td class='book2'></td>";
    (!$row2[1]) ? $h=0 : $h=$row2[1];
    (!$row2[2]) ? $m=0 : $m=$row2[2];
    $out.= "<td class='book2'>$h h $m m</td>\n";
    $out.= "</tr>\n";
  }
  return $out;
}
function show_bookings($day) {
  global $user_ID;
  $result21 = db_query("select anfang, ende, nettoh, nettom
                         from ".DB_PREFIX."timecard
                        where users = '$user_ID' and
                              datum like '$day'
                     order by anfang ASC") or db_die();
  while ($row21 = db_fetch_row($result21)) {
    $row21[0] = check_4d($row21[0]);
    $row21[1] = check_4d($row21[1]);
     if(($row21[0]&&$row21[1])and !($row21[2]or $row21[3])){
        $bsum =(substr($row21[1],0,2) - substr($row21[0],0,2))*60 + substr($row21[1],2,4) - substr($row21[0],2,4);
        $row21[2]= floor($bsum/60);
        $row21[3] = $bsum - $row21[2] * 60;
     }


    $out.= "<tr class='book1'><td class='book1'></td><td class='book1'></td>
     ";
    $out.="<td class='book1'>".check_4d($row21[0])."</td>";
    $out.="<td class='book1'>".check_4d($row21[1])."</td>";
    (!$row21[2]) ? $h=0 : $h=$row21[2];
    (!$row21[3]) ? $m=0 : $m=$row21[3];
    $out.= "<td class='book1'>$h h: $m m</td>\n";
    $out.= "</tr>\n";
  }
  return $out;
}

?>
