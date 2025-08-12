<?php

// timecard_forms.php - PHProjekt Version 5.0
// copyright  ©  2004 Nina Schmitt	
// www.phprojekt.com
// Author: Nina Schmitt
//
$css_void_background_image = true;

// check whether the lib has been included - authentication!$path_pre="../";
//$module = "timecard";
$path_pre="../";
//$include_path = $path_pre."lib/lib.inc.php";
//include_once $include_path;
include_once("./timecard_date.inc.php");   
                                                                                                   

// check role
if (check_role("timecard") < 1) { die("You are not allowed to do this!"); }


//echo set_page_header();	
if(!$date)$date= $today1;
if(($year!=substr($date,0,4)) or($month != substr($date,5,2))) $date= $year."-".$month.'-01';
if(!$timestop)$timestop="2000";
if(!$timestart)$timestart="0800";
$datum2 = explode("-", $date);
$wo_tag = date('w', mktime(0,0,0,$datum2[1],$datum2[2],$datum2[0]));
$output .= "<div class='tc_header'>".__('insert additional working time').__(':')."&nbsp;".$name_day[$wo_tag].", ".$date."
</div>\n";
$output .= "<div class='admin_fields'>\n";

echo datepicker();
$output .= '
<div>  <b>'.__('choose day').__(':').'</b>
        <form style="display:inline" name="pickdate" action="timecard.php?mode=data" method="post">
<input type="text" size="10" name="date" value="'.$date.'" style="font-size:11px;" />
<a href="javascript:void(0)" title="'.__('This link opens a popup window').'" onclick="callPick(document.pickdate.date)"><img src="'.$img_path.'/cal.gif" border="0" alt="calendar" /></a>
'.get_go_button().'
        </form>
'.get_buttons(array(array('type' => 'button', 'name' => 'day_back', 'value' =>'&lt;', 'active' => false, 'onclick' => 'lM(document.forms[\'pickdate\'].elements[\'date\']);document.forms[\'pickdate\'].submit();'))).'
'.get_buttons(array(array('type' => 'button', 'name' => 'day_forward', 'value' => '>', 'active' => false, 'onclick' => 'nM(document.forms[\'pickdate\'].elements[\'date\']);document.forms[\'pickdate\'].submit();'))).'
</div>
<div class="hline"></div>
<div><br /><form style="display: inline;" action="timecard.php?mode=data"  name="nachtragen1" method="post">
';


$anf=$timestart;
$end=$timestop;
$netto= ((substr($end,0,2) - substr($anf,0,2))*60 + substr($end,2,4) - substr($anf,2,4));
$nettoh = floor($netto/60);
$nettom = $netto - $nettoh * 60;
//$output.= "<input type='hidden' name='mode' value='forms'/>\n";
$output.= "<b>".__('Assign work time').":</b> <input type='hidden' name='view' value='days'/>\n";
$output.= "<input type='hidden' name='date' value='$date'/>\n";
$output.="<input type='hidden' name='action' value='add'/>\n";
$output.= "<input type='hidden' name='ID' value='$ID'/>\n";  
$output.='<label for="timestart" class="elfpx">'.__('Begin').':</label><input type="text" name="timestart" id="timestart" value="'.$timestart.'"class="elfpx" maxlength="4" size="4"
onblur="getNetto(document.nachtragen1.timestart,document.nachtragen1.timestop,document.nachtragen1.nettom,document.nachtragen1.nettoh)"/> <label for="timestop" class="elfpx">'.__('End').':</label><input type="text" name="timestop" id="timestop" class="elfpx"value="'.$timestop.'" size="4" maxlength="4" onblur="getNetto(document.nachtragen1.timestart,document.nachtragen1.timestop,document.nachtragen1.nettom,document.nachtragen1.nettoh)"/>
';	
//$anf=$timestart;
//$end=$timestop;
if(PHPR_TIMECARD_NETTO){
$output.='<span class="col3b"><span class="elfpx"> <b>'.__('Net time').__(':').'</b></span> <input type="text" name="nettoh" id="nettoh" size="2" class="elfpx" maxlength="2" value="'.$nettoh.'"/>
<label for="nettoh" class="elfpx">h</label> <input type="text" name="nettom" id="nettom" class="elfpx" size="2" maxlength="2"  value="'.$nettom.'"/><label for="nettom" class="elfpx">m</label>';
}
$output.='<input type="submit" style="margin-left:5px;" name="insert" value="'.__('GO').'" class="button2small"/>
</span></form></div><br />';


$output.='<form style="display: inline;" action="timecard.php?mode=data" name="nachtragen" method="post">';
//output.= "<input type='hidden' name='mode' value='forms'/>\n";
$output.= "<input type='hidden' name='view' value='days'/>\n";
$output.="<input type='hidden' name='action' value='do'/>\n";
 $output.="<table class=\"ruler\" width='90%' id=\"contacts\" summary=\"$tc_sum\">
	<thead>
    	<tr>
    	    <th scope=\"col\" title=\"Beginn\">".__('Start')."</th>
			<th scope=\"col\" title=\"Ende\">".__('End')."</th>
 			<th scope=\"col\" title=\"Nettorzeit\">".__('Hours')."</th>
 			<th scope=\"col\" title=\"".__('save+close')."'\"></th>
		</tr>
	</thead>";
 $outbody="<tbody>";

/**$result=db_query("INSERT INTO ".DB_PREFIX."tc_temp(tiD, user, datum, anfang, ende, nettoh, nettom) SELECT t.ID, t.users, t.datum, t.anfang, t.ende,t.nettoh,t.nettom from ".DB_PREFIX."timecard as t  WHERE users='$user_ID' AND t.datum='$date'")or db_die();
*/

$result2=db_query("SELECT t.ID from ".DB_PREFIX."timecard as t  WHERE users='$user_ID' AND t.datum='$date'")or db_die();
$liste= make_list($result);
$maxa= $max;
$lasta= $last;
$liste2 = make_list($result2);
$max= $max+ $maxa;
$last = $last+ $lasta;
$liste= array_merge($liste,$liste2);
 $nachtragen=false;
 $ctable=0;
 for ($i=($page*$perpage); $i < $max; $i++) {
 		if($i<$maxa){
  		$result = db_query("SELECT  anfang, ende, nettoh, nettom,ID from ".DB_PREFIX."tc_temp Where ID = '".$liste[$i]."'")
		or db_die();
 		}
 		else
 		$result = db_query("SELECT  anfang, ende, nettoh, nettom,ID from ".DB_PREFIX."timecard Where ID = '".$liste[$i]."'") or db_die();
		$row = db_fetch_row($result);
	$outbody.= "<tr";
		if($i%2==1){
			 $outbody.= " class=\"unev\" ";
		}
	    $row[0] = check_4d($row[0]);
	    $row[1] = check_4d($row[1]);
  	    if(($row[0]&&$row[1])and !($row[2]or $row[3])){
			$bsum =(substr($row[1],0,2) - substr($row[0],0,2))*60 + substr($row[1],2,4) - substr($row[0],2,4);
			$row[2]= floor($bsum/60);
  	    	$row[3] = $bsum - $row[2] * 60;
  	 	}
		$outbody.="> <td scope='row'>$row[0]</td>";
		if(!$row[1]){
			$nachtragen =true;
			$outbody.="<td><input type='text' name='ende[$row[4]]'  size='4' maxlength='4' value=''/></td>";
			if(PHPR_TIMECARD_NETTO){
				$outbody.="<td><input type='text' size='2' maxlength='2' name='neth[$row[4]]' value=''/> h ";
				$outbody.="<input type='text' size='2' maxlength='2' name='netm[$row[4]]' value=''/> m</td>";
			}
			else $outbody.="<td></td>";
		
 		}
		else $outbody.="<td>$row[1]</td><td>$row[2] h $row[3] m</td>";
	
		 $outbody.="<td><input type='checkbox' name='del[$i]' value='$row[4]'/></td>
		</tr>";
		$gesh= $gesh+$row[2];
		$gesm= $gesm+ $row[3];	
		$ctable++;
 }
 if($ctable==0)$outbody.="<tr><td></td><td></td><td></td><td></td></tr>";
 $hg= $gesh + floor($gesm/60);
 $mg = $gesm-  floor($gesm/60)*60;
 $outbody.="</tbody>";
 $output.="<tfoot>
  
    <tr>
    	<td></td>";
  	if($nachtragen==true) $output.="<td>". get_buttons(array(array('type' => 'submit', 'name' => 'fills2', 'value' => __('Save'), 'active' => false)))."</td>";
	else $output.=" <td></td>";
	$output.="
		<td>$hg h $mg m</td>
 		<td>". get_buttons(array(array('type' => 'submit', 'name' => 'deli', 'value' => __('Delete'), 'active' => false)))."</td></tr>

  </tfoot>$outbody</table>";
 $output.= "
 	<input type='hidden' name='maxa' value='$maxa' />
  	<input type='hidden' name='modes' value='forms' />
 <input type='hidden' name='date' value='$date'/>\n";


$output .= '
</form>
</div>
';

//echo $output;

?>