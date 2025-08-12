<?php
// timecard_forms.php - PHProjekt Version 5.0
// copyright  ©  2004 Nina Schmitt
// www.phprojekt.com
// Author: Nina Schmitt
//

$css_void_background_image = true;

// check whether the lib has been included - authentication!
$module = 'timecard';
$path_pre = '../';
$include_path = $path_pre."lib/lib.inc.php";
include_once $include_path;
include_once("./timecard_date.inc.php");


// check role
if (check_role("timecard") < 1) { die("You are not allowed to do this!"); }
if($action=="clockin"){
//echo set_page_header();

$tabs = array();
$output = get_tabs_area($tabs);

$form_fields = array();
$form_fields[] = array('type' => 'hidden', 'name' => 'modes', 'value' => 'books');
$form_fields[] = array('type' => 'hidden', 'name' => 'mode', 'value' => 'data');
$form_fields[] = array('type' => 'hidden', 'name' => 'view', 'value' => 'view');
$form_fields[] = array('type' => 'hidden', 'name' => 'action', 'value' => 'clock_in');
$options = array();
$datum = date("Y-m-d", mktime(date("H")+PHPR_TIMEZONE,date("i"),date("s"),date("m"),date("d"),date("Y")));
$time  = date("H:i", mktime(date("H")+PHPR_TIMEZONE,date("i"),date("s"),date("m"),date("d"),date("Y")));
$query="select ID,personen,chef,kategorie,anfang,ende,name   from ".DB_PREFIX."projekte where $sql_user_group";
$result= db_query($query);
while ($row2 = db_fetch_row($result)) {
     // BIG check: list the project under certain conditions
     if
      // 1. the user has access to it: be a member or the project leader
      ((ereg("\"$user_kurz\"",$row2[1]) or $row2[2] == $user) and
      // 2. aditional check: status of the project must be null or active
      ($row2[3] == '3' or !$row2[3]) and
      // 3. check whether the mentioned day is between begin and end of this record
      ($row2[4] <= $datum and $row2[5] >= $datum)) {
        $options[] = array('value' => $row2[0], 'text' => $row2[6]);
      }
}
$form_fields[] = array('type' => 'select', 'name' => 'projekt', 'label' => __('project choice').__(':'), 'options' => $options);
$form_fields[] = array('type' => 'text', 'name' => 'note', 'label' => __('Comment').__(':'), 'value' => '');
$buttons  = get_buttons(array(array('type' => 'submit', 'name' => 'akt', 'value' => __('activate'), 'active' => false)));
$buttons .= get_buttons(array(array('type' => 'submit', 'name' => 'canc', 'value' => __('cancel'), 'active' => false)));
$form_fields[] = array('type' => 'parsed_html', 'html' => $buttons);
$html = '<form style="display: inline;" action="timecard_data.php" name="book" method="post">'.get_form_content($form_fields).'</form><br/>';


$output .= '
<br/>
<div class="inner_content">
    <a name="content"></a>
    <div class="boxHeader">'.__('activate project stop watch').'</div>
    <div class="boxContent">'.$html.'</div>
    <br style="clear:both"/><br/>
</div>
';

 echo $output;

}
else{
if(!$date)$date= $today1;
if(($year!=substr($date,0,4)) or($month != substr($date,5,2))) $date= $year."-".$month.'-01';
$datum=$date;
$result=db_query("SELECT nettoh, nettom,anfang,ende from ".DB_PREFIX."timecard WHERE users='$user_ID' AND datum='$date'")or db_die();
while($row = db_fetch_row($result)){
    $row[2] = check_4d($row[2]);
    $row[3] = check_4d($row[3]);
    if(($row[2]&&$row[3])and !($row[0]or $row[1])){
            $bsum =(substr($row[3],0,2) - substr($row[2],0,2))*60 + substr($row[3],2,4) - substr($row[2],2,4);
            $row[0]= floor($bsum/60);
            $row[1] = $bsum - $row[0] * 60;
    }
    $hges= $hges+ $row[0];
    $mges=$mges+$row[1];
}

$ho= $hges + floor($mges/60);
$mo = $mges- floor($mges/60)*60;

$output.= "<div class='tc_header'>";
$buttons = array();
$buttons[] = array('type' => 'text', 'text' => __('Assigning projects').__(':'));
$output.= get_buttons($buttons);
$datum2 = explode("-", $date);
$wo_tag = date('w', mktime(0,0,0,$datum2[1],$datum2[2],$datum2[0]));
$output.= "&nbsp;". $name_day[$wo_tag].", ".$date."</div>\n";
$output.= "<div class='admin_fields'>\n";

echo datepicker();
$output.= '
<div>  <b>'.__('choose day').__(':').'</b>
        <form style="display:inline" name="pickdate" action="timecard.php?mode=data" method="post">
        <input type="text" size="10" name="date" value="'.$date.'" style="font-size:11px;" />
        <input type="hidden" name="view" value="proj" />
<a href="javascript:void(0)" title="'.__('This link opens a popup window').'" onclick="callPick(document.pickdate.date)"><img src="'.$img_path.'/cal.gif" border="0" alt="calendar" /></a>
'.get_go_button().'
        </form>
'.get_buttons(array(array('type' => 'button', 'name' => 'day_back', 'value' =>'&lt;', 'active' => false, 'onclick' => 'lM(document.forms[\'pickdate\'].elements[\'date\']);document.forms[\'pickdate\'].submit();'))).'
'.get_buttons(array(array('type' => 'button', 'name' => 'day_forward', 'value' => '>', 'active' => false, 'onclick' => 'nM(document.forms[\'pickdate\'].elements[\'date\']);document.forms[\'pickdate\'].submit();'))).'
 </div>
<div class="hline"></div>
<div><br /><b>'.__('Sum working time').':</b> '.$ho.' h '.$mo.' m &nbsp;';
if($timecard_view==1){
$output.=" <a href='timecard.php?mode=view&amp;tree2_mode=open&amp;ID=$row2[0]&amp;PHPSESSID=$PHPSESSID&amp;filter=$filter&amp;keyword=$keyword&amp;sort=$sort&amp;up=$up&amp;page=$page&amp;datum=$datum&amp;perpage=$perpage&amp;day=$day&amp;view=proj&amp;month=$month&amp;year=$year'>
    <font color='#000000' size='3'><b>+</b></font></a>
    &nbsp;<a href='timecard.php?mode=view&amp;tree2_mode=close&amp;ID=$row2[0]&amp;PHPSESSID=$PHPSESSID&amp;filter=$filter&amp;keyword=$keyword&amp;sort=$sort&amp;up=$up&amp;page=$page&amp;datum=$datum&amp;perpage=$perpage&amp;day=$day&amp;view=proj&amp;month=$month&amp;year=$year'>
    <font color='#000000' size='3'><b>-</b></font></a>";
}
$output.="</div><br/>";

$output.='<form style="display: inline;" action="timecard.php" name="book" method="post">';
$output.= "<input type='hidden' name='modes' value='books'/>\n";
$output.= "<input type='hidden' name='mode' value='data'/>\n";
$output.="<input type='hidden' name='action' value='proj'/>\n";
$output.="<input type='hidden' name='view' value='proj'/>\n";
$output.="<input type='hidden' name='datum' value='$date'/>\n";
 $output.="<table class=\"ruler\" width='90%' id=\"contacts\" summary=\"$tc_sum\">
    <thead>
        <tr>
            <th scope=\"col\" title=\"Projekt\">".__('Project')." </th>
            <th scope=\"col\" title=\"Kommentar\">".__('Comment')."</th>
            <th scope=\"col\" title=\"Zeit\">".__('Hours')."</th>
            <th scope=\"col\" title='".__('save+close')."'></th>
        </tr>
    </thead>";
 $outbody="<tbody>";
$query="select ID,personen,chef,kategorie,anfang,ende,name   from ".DB_PREFIX."projekte where $sql_user_group";
if($timecard_view==1) $outbody.= list_projects_tree($query);
else $outbody.=list_projects1($query);
 $result4 = db_query("select SUM(h), SUM(m)
                      from ".DB_PREFIX."timeproj
                      where datum = '$datum' and
                          users = '$user_ID'") or db_die();
  $row4 = db_fetch_row($result4);
  $gesh =$row4[0];
  $gesm =$row4[1];
  $hg= $gesh + floor($gesm/60);
  $mg = $gesm-  floor($gesm/60)*60;
  $ges_h_m= $gesh*60+$gesm;
  $netto_h_m= $ho*60+ $mo;
  $res_h_m= $netto_h_m-$ges_h_m;
  $mult=1;
  if($res_h_m<0){
    	$res_h_m= -1*$res_h_m;
    	$mult= -1;
    }
  $rh = $mult*floor($res_h_m/60);
  $rm = $res_h_m - floor($res_h_m/60)*60;  
 $outbody.="</tbody>";
 $output.="<tfoot>
    <tr>
        <td></td>
        <td>".__('still to allocate:')." $rh h  $rm m </td>
        <td>$hg h $mg m</td>
        <td></td>";
 $output.="
    </tr>
  </tfoot>$outbody</table>";
 $output.= "<input type='hidden' name='date' value='$date'/>\n";




$output .=
get_buttons(array(array('type' => 'submit', 'name' => 'save', 'value' => __('save'), 'active' => false))).
get_buttons(array(array('type' => 'submit', 'name' => 'delsep', 'value' => __('Delete'), 'active' => false))).'
</form>
</div>
';


}
function list_projects_tree($query, $order,$parent) {
  global $user_kurz, $user_ID,$arrproj1, $tree2_mode, $datum, $indent, $h_sum, $m_sum, $today1, $img_path, $fld_nr, $sid, $h, $m, $nr, $note, $timeproj_ID ;

  // fetch all projects from this group

    $result2 = db_query("$query and parent = '$parent' $order") or db_die();
   while ($row2 = db_fetch_row($result2)) {
    $result = db_query("SELECT ID,personen,chef,kategorie,anfang,ende,name
                  from ".DB_PREFIX."projekte WHERE parent = '$row2[0]' ") or db_die();


   if
      // 1. the user has access to it: be a member or the project leader
      ((ereg("\"$user_kurz\"",$row2[1]) or $row2[2] == $user_ID) and
      // 2. aditional check: status of the project must be null or active
      ($row2[3] == '3' or !$row2[3]) and
      // 3. check whether the mentioned day is between begin and end of this record
      ($row2[4] <= $datum and $row2[5] >= $datum)) {
      //suche nach subprojekt:
       // find out whether there is at at least 1 subproject
       $row = db_fetch_row($result);
    $in=0;
        if ($row[0] > 0) {
        $outputlat .= "<tr";
        if($i%2==1){
             $output1 .= " class=\"unev\" ";
        }

        $outputlat .= "> <td scope='row'>";
      // indent
      for ($i=0; $i < $indent; $i++) { $outputlat.= "&nbsp;&nbsp;&nbsp;&nbsp;"; }
        $in=1;
        // show button 'open'
        if (!$arrproj1[$row2[0]]) {
             $no = "open".$row2[0];
         $outputlat.="
         <input type='hidden' name='bID[]' value='$row2[0]' />
         <input type='image' src='$img_path/close.gif' name='$no'  style='border-style:none;' />&nbsp; ";
         }
         // show button 'close'
        else {
            $nc= "close".$row2[0];
            $outputlat.= "
          <input type='hidden' name='bID[]' value='$row2[0]' />
          <input type='image' src='$img_path/open.gif' style='border-style:none;' name='$nc' />&nbsp;"; }
       $outputlat.= html_out($row2[6])."</td>\n";
     }

  // BIG check: list the project under certain conditions

      // end of BIG check :-)
      if($in!=1){
       $outputlat .= "<tr";
        if($i%2==1){
             $output1 .= " class=\"unev\" ";
        }

        $outputlat .= "> <td scope='row'>";
      // indent
      for ($i=0; $i < $indent; $i++) { $outputlat.= "&nbsp;&nbsp;&nbsp;&nbsp;"; }
       $outputlat.= "<image src='$img_path/t.gif' width='9' height='9' />&nbsp;".html_out($row2[6])."</td>\n";
       }
      // name of the project
      // show input fields for hour and minute
      $id = $row2[0];
       $outputlat.= "<td><input type='text' name='note[]'  size='30' /></td>
        <td style='white-space:nowrap;'><input type='text' name='h[]' size='3' maxlength='2' onblur=\"chkChrs('frm',$fld_nr,'0 - 23!',/[2][0-3]|[0-1]?\d?/,1)\" />
        <input type='text' name='m[]' size='3' maxlength='2' onblur=\"chkChrs('frm',".($fld_nr + 1).",'0 - 59!',/[0-5]?\d?/,1)\" />  </td>\n";
      $outputlat.="<td>";
      $outputlat.= "<input type='hidden' name='timeproj_ID[$id]' value='$row3[0]' />\n";
      $outputlat.= "<input type='hidden' name='nr[]' value='$id' /></td></tr>\n";
      $outputlat.= show_bookings1($datum, $row2[0]);
      $fld_nr += 5;

    } // end of bracket for record listing
    else{
        $subpro="false";
        while($row = db_fetch_row($result)){;
              if
            // 1. the user has access to it: be a member or the project leader
            ((ereg("\"$user_kurz\"",$row[1]) or $row[2] == $user_ID) and
            // 2. aditional check: status of the project must be null or active
              ($row[3] == '3' or !$row[3]) and
             // 3. check whether the mentioned day is between begin and end of this record
            ($row[4] <= $datum and $row[5] >= $datum)) {
                $subpro="true";
                break;
            }
        }
        if($subpro=="true"){
         $outputlat .= "<tr";
        if($i%2==1){
             $output1 .= " class=\"unev\" ";
        }

        $outputlat .= "> <td scope='row'>";
      // indent
      for ($i=0; $i < $indent; $i++) { $outputlat.= "&nbsp;&nbsp;&nbsp;&nbsp;"; }
        $in=1;
        // show button 'open'
        if (!$arrproj1[$row2[0]]) {
             $no = "open".$row2[0];
         $outputlat.="
         <input type='hidden' name='bID[]' value='$row2[0]'>
         <input type='image' src='$img_path/close.gif' border='0' name='$no' border=0 style='border-style:none;' />&nbsp; ";
         }
         // show button 'close'
        else {
            $nc= "close".$row2[0];
            $outputlat.= "
          <input type='hidden' name='bID[]' value='$row2[0]' />
          <input type='image' src='$img_path/open.gif' border=0 style='border-style:none;' border=0 name='$nc' />&nbsp;"; }
       $outputlat.= html_out($row2[6])."</td><td></td><td></td><td></td></tr>
       \n";
     }

    }


    // look for subprojects
    $indent++;
    if($arrproj1[$row2[0]]){
        $outputlat.= list_projects_tree($query,$order,$row2[0]);
    }
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
  if (empty($outputlat))$outputlat="<tr><td></td><td></td><td></td><td></td></tr>";
  return $outputlat;

}
function list_projects1($query) {
  global $user_kurz, $user_ID, $datum, $indent, $h_sum, $m_sum, $today1, $img_path, $fld_nr, $sid;
  // fetch all projects from this group
  $result2 = db_query("$query") or db_die();
  while ($row2 = db_fetch_row($result2)) {
     // BIG check: list the project under certain conditions
    if
      // 1. the user has access to it: be a member or the project leader
      ((ereg("\"$user_kurz\"",$row2[1]) or $row2[2] == $user_ID) and
      // 2. aditional check: status of the project must be null or active
      ($row2[3] == 3 or !$row2[3]) and
      // 3. check whether the mentioned day is between begin and end of this record
      ($row2[4] <= $datum and $row2[5] >= $datum)) {
     // end of BIG check :-)
        $output1 .= "<tr";
        if($i%2==1){
             $output1 .= " class=\"unev\" ";
        }

        $output1 .= "> <td scope='row'>$row2[6]</td>
        <td><input type='text' name='note[]'  size='30' /></td>
        <td style='white-space:nowrap;'><input type='text' name='h[]' size='3' maxlength='2' onblur=\"chkChrs('frm',$fld_nr,'0 - 23!',/[2][0-3]|[0-1]?\d?/,1)\" />
        <input type='text' name='m[]' size='3' maxlength='2' onblur=\"chkChrs('frm',".($fld_nr + 1).",'0 - 59!',/[0-5]?\d?/,1)\" /> </td>
        <td><input type='hidden' name='nr[]' value='$row2[0]' />\n</td>
        </tr>";
        $output1 .= show_bookings1($datum, $row2[0]);

    }
    // summarize all work time of a day
  }
  if(empty($output1))$output1="<tr><td></td><td></td><td></td><td></td></tr>";
  return $output1;
}

function show_bookings1($day,$proj) {
  global $user_ID;
  $result3 = db_query("select name, h, m,".DB_PREFIX."timeproj.note,".DB_PREFIX."timeproj.ID
                         from ".DB_PREFIX."timeproj, ".DB_PREFIX."projekte
                        where projekt = ".DB_PREFIX."projekte.ID and
                              users = '$user_ID' and
                              projekt= '$proj' and
                              datum like '$day'
                     order by name") or db_die();
  while ($row3 = db_fetch_row($result3)) {
    $output1 .= "<tr class='book2'><td class='book2'></td>";
     $output1 .= "<td class='book2'>".html_out($row3[3])."</td>";
    (!$row3[1]) ? $h=0 : $h=$row3[1];
    (!$row3[2]) ? $m=0 : $m=$row3[2];
    $output1 .= "<td class='book2'>$h h $m m</td>\n <td class='book2'><input type='checkbox' name='del[]' value='$row3[4]'/></td>";

    $output1 .= "</tr>\n";
  }
  return $output1;
}

?>