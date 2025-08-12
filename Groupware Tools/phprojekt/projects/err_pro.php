<?php

// err_pro.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: nina $
// $Id: err_pro.php,v 1.13 2005/06/29 21:41:09 nina Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) die("Please use index.php!");


if ($cancel) {
    include_once("./projects_view.php");
}
else if ($upanf) {
    $olddate = explode('-',$anfang);
    $newdate = date("Y-m-d", mktime(0, 0, 0, $olddate[1], $olddate[2]+$diffa, $olddate[0]));
    $_POST['anfang'] = $newdate;
    $anfang=$newdate;
    $_POST['upanf'] = "";
    include_once("./projects_data.php");
}
else if ($upboth) {
    $olddate = explode('-',$anfang);
    $newdate = date("Y-m-d", mktime(0, 0, 0, $olddate[1], $olddate[2]+$diffa, $olddate[0]));
    $anfang = $newdate;
    $_POST['anfang'] = $newdate;
    $olddate = explode('-',$ende);
    $newdate = date("Y-m-d", mktime(0, 0, 0, $olddate[1], $olddate[2]-$diffe, $olddate[0]));
    $ende = $newdate;
    $_POST['ende'] = $newdate;
    $_POST['upboth'] = "";
    include_once("./projects_data.php");
}
else if ($upende) {
    $olddate = explode('-',$ende);
    $newdate = date("Y-m-d", mktime(0, 0, 0, $olddate[1], $olddate[2]-$diffe, $olddate[0]));
    $ende = $newdate;
    $_POST['ende'] = $newdate;
    $_POST['upende'] = "";
    include_once("./projects_data.php");
}
else if ($downboth) {
    move_for($parent,'ende',$diffe);
    move_for_down($ID,'ende',$diffe);
    move_back($parent,'anfang',$diffa);
    move_back_down1($ID,'anfang',$anfang);
    $_POST['donwboth'] = "";
    include_once("./projects_data.php");
}
else if ($downanf) {
    move_back($parent,'anfang',$diffa);
    #move_back_down1($ID,'anfang',$anfang);
    $_POST['downanf'] = "";
    include_once("./projects_data.php");
}
else if ($downende) {
    move_for($parent,'ende',$diffe);
    #move_for_down($ID,'ende',$diffe);
    $_POST['downende'] = "";
    include_once("./projects_data.php");
}
else if ($upanf1) {
    $olddate = explode('-',$anfang);
    $newdate = date("Y-m-d", mktime(0, 0, 0, $olddate[1], $olddate[2]-$diffa, $olddate[0]));
    $_POST['anfang'] = $newdate;
    $anfang=$newdate;
    $_POST['upanf1'] = "";
    $uproj= $arr_empt;
    include_once("./projects_data.php");
}
else if ($upboth1) {
    $olddate = explode('-',$anfang);
    $newdate =  date("Y-m-d", mktime(0, 0, 0, $olddate[1], $olddate[2]-$diffa, $olddate[0]));
    $anfang = $newdate;
    $_POST['anfang'] = $newdate;
    $olddate = explode('-',$ende);
    $newdate = date("Y-m-d", mktime(0, 0, 0, $olddate[1], $olddate[2]+$diffe, $olddate[0]));
    $ende = $newdate;
    $_POST['ende'] = $newdate;
    $_POST['upboth1'] = "";
    $uproj= $arr_empt;
    include_once("./projects_data.php");
}
else if ($upende1) {
    $olddate = explode('-',$ende);
    $newdate = date("Y-m-d", mktime(0, 0, 0, $olddate[1], $olddate[2]+$diffe, $olddate[0]));
    $ende = $newdate;
    $uproj= $arr_empt;
    $_POST['upende1'] = "";
    $_POST['ende'] = $newdate;
    include_once("./projects_data.php");
}
else if ($downboth1) {
    //move_back($parent,'ende',$diffe);
    move_back_down($ID,'ende',$diffe);
    //move_for($parent,'anfang',$diffa);
    move_for_down($ID,'anfang',$diffa);
    $uproj= $arr_empt;
    $_POST['downboth1'] = "";
    include_once("./projects_data.php");
}
else if ($downanf1) {
    //move_back($parent,'anfang',$diffa);
    move_for_down($ID,'anfang',$diffa);
    $uproj= $arr_empt;
    $_POST['downanf1'] = "";
    include_once("./projects_data.php");
}
else if ($downende1) {
    //move_for($parent,'ende',$diffe);
    move_back_down1($ID,'ende',$ende);
    $uproj= $arr_empt;
    $_POST['downende1'] = "";
    include_once("./projects_data.php");
}
else {
    include_once("./projects_data.php");
}


function oerror($parent) {
    global $anfang, $ende;

    $ana = $anfang;
    $ena = $ende;

    // the project is subproject? check whether the start and end time is within the limits of the parent project
    $result2 = db_query("select anfang, ende,ID
                           from ".DB_PREFIX."projekte
                          where ID = '$parent'") or db_die();
    $row2 = db_fetch_row($result2);
    // timespan exceeds timespan of parent -> die ...
    $anfang    = makeTime($anfang);
    $anfangalt = makeTime($row2[0]);
    $ende      = makeTime($ende);
    $endealt   = makeTime($row2[1]);
    if ($anfang < $anfangalt or $ende > $endealt) {
        $diffa = $anfangalt-$anfang;
        $diffa = floor($diffa/60/60/24);
        $diffe = $ende -$endealt;
        $diffe = floor($diffe/60/60/24);
        echo set_page_header();
        //tabs
		$tabs = array();
		echo get_tabs_area($tabs);
        echo' <div class="status_bar">
        <span class="status_bar">
            '.__('Status').':&nbsp;'.__('A conflict exists with the following parent project:').'  &nbsp; '.
        slookup('projekte','name','ID', $parent)." - ".__('Begin').": ".slookup('projekte','anfang','ID', $parent)."
           ".__('End').": ".slookup('projekte','ende','ID', $parent).'
        </span></div>';
        echo "<form name='form' action='projects.php' method='POST'>";
        foreach ($_POST as $pk => $pval) {
            echo "<input type='hidden' name='$pk' value='$pval' />\n";
        }
        echo "
            <input type='hidden' name='diffa' value='$diffa' />
            <input type='hidden' name='diffe' value='$diffe' />
            <input type='hidden' name='inclu' value='err_pro.php' />";
		echo"<div class='header'>".__('You can choose between the following options:')."</div>";
		echo"<div class='formbody_mailops'>";
		
	
	     
        echo "<ul><li>".__('Discard changes')." ".get_go_button_with_name("cancel")."</li>";
        if ($anfang < $anfangalt && $ende > $endealt) {
            echo "
            <li> ".__('Delay project start for ')." $diffa ".__('days ').__('and').
            __('move up the End ')."
               ".$diffe.__(' days')." ".get_go_button_with_name("upboth")."
            </li>
            <li> ".__('Delay the end of the parent project ')." $diffe ".__('days ').__('and')." ".
            __('move up the Beginning ')." ".$diffa .__(' days')." ".get_go_button_with_name("downboth")."
             </li>";

        }
        else if ($anfang < $anfangalt) {
            echo "
        <li>".__('Delay project start for ')." $diffa ".__('days ')."
        
        	".get_go_button_with_name("upanf")."
        </li>
        <li>".__('move up the Beginning of all affected parent projects ')."$diffa".__(' days')." 
        ".get_go_button_with_name("downanf")."
        </li>";

        }
        else if ($ende > $endealt) {
            echo "
        <li> ".__('move up the End ')."$diffe".__(' days')." ".get_go_button_with_name("upende")."
        </li>
         <li> ".__('Delay the end of all affected parent projects ')."$diffe ".__('days ')." 
         ".get_go_button_with_name("downende")."
          </li>  ";
        }
        echo "</ul><br /></div></form>\n";
        die();
    }
    $anfang = $ana;
    $ende   = $ena;
}

function uerror($parent) {
    global $anfang, $ende;

    $ana = $anfang;
    $ena = $ende;
    $anfanga = explode('-',$anfang);
    $anfang = mktime(0, 0, 0, $anfanga[1], $anfanga[2], $anfanga[0]);
    $endea = explode('-',$ende);
    $ende = mktime(0, 0, 0, $endea[1], $endea[2], $endea[0]);
    foreach ($parent as $kidproj) {
        // the project is subproject? check whether the start and end time is within the limits of the parent project
        $result2 = db_query("select anfang, ende,ID
                               from ".DB_PREFIX."projekte
                              where ID = '$kidproj'") or db_die();
        $row2 = db_fetch_row($result2);
        // timespan exceeds timespan of parent -> die ...
        $anfangb   = explode('-',$row2[0]);
        $anfangalt = mktime(0, 0, 0, $anfangb[1], $anfangb[2], $anfangb[0]);
        $endeb     = explode('-',$row2[1]);
        $endealt   = mktime(0, 0, 0, $endeb[1], $endeb[2], $endeb[0]);
        if (($anfang > $anfangalt or $ende < $endealt)&&$kidproj>0) {
            $diffa = $anfang-$anfangalt;
            $diffa = floor($diffa/60/60/24);
            $diffe = $endealt -$ende;
            $diffe = floor($diffe/60/60/24);
            //tabs
		$tabs = array();
		echo get_tabs_area($tabs);
        echo' <div class="status_bar">
        <span class="status_bar">
            '.__('Status').':&nbsp;'.__('A conflict exists with the following subproject:').'  &nbsp; '.
        slookup('projekte','name','ID', $kidproj)." - ".__('Begin').": ".slookup('projekte','anfang','ID', $kidproj)."
           ".__('End').": ".slookup('projekte','ende','ID', $kidproj).'
        </span></div>';
                echo"<form name='form' action='projects.php' method='post'>";
            foreach($_POST as $pk => $pval){
                echo "<input type='hidden' name='$pk' value='$pval' />\n";
            }
            echo "
                <input type='hidden' name='diffa' value='$diffa' />
                <input type='hidden' name='diffe' value='$diffe' />
                <input type='hidden' name='inclu' value='err_pro.php' />";
               
        echo"<div class='header'>".__('You can choose between the following options:')."</div>";
		echo"<div class='formbody_mailops'>";
		echo "<ul><li>".__('Discard changes')." ".get_go_button_with_name("cancel")."</li>";       
		if ($anfang > $anfangalt && $ende < $endealt) {
			  echo "
            <li> ".__('Delay project start for ')." $diffa ".__('days ').__('and').
            __('move up the End ')."
               ".$diffe.__(' days')." ".get_go_button_with_name("upboth1")."
            </li>
            <li> ".__('Delay the end of the subproject ')." $diffe ".__('days ').__('and')." ".
            __('move up the Beginning ')." ".$diffa .__(' days')." ".get_go_button_with_name("downboth1")."
             </li>";
          }
          else if ($anfang > $anfangalt) {
               echo "
        	<li>".__('Delay project start for ')." $diffa ".__('days ')." 
        	".get_go_button_with_name("upanf1")."
        	</li>
        	<li>".__('move up the Beginning of all affected subprojects ')."$diffa".__(' days')."
        	 ".get_go_button_with_name("downanf1")."
        	</li>";    
              }
          else if ($ende < $endealt) {
          	echo "
     	   	<li> ".__('move up the End ')."$diffe".__(' days')." ".get_go_button_with_name("upende1")."
        	</li>
         	<li> ".__('Delay the end of all affected subprojects ')."$diffe ".__('days ')." 
         	".get_go_button_with_name("downende1")."
          	</li>  ";
        }
        echo "</ul><br /></div></form>\n";
            die();
        }
    }
    $anfang = $ana;
    $ende   = $ena;
}

function move_for($ID, $field, $days, $calculated_newdate = '') {
    // get the value
    $result = db_query("select ".qss($field).", parent
                          from ".DB_PREFIX."projekte
                         where ID = '$ID'") or db_die();
    $row = db_fetch_row($result);

    if($calculated_newdate != ''){
        $newdate = $calculated_newdate;
    }
    else{
        $olddate = explode('-',$row[0]);
        $newdate = date("Y-m-d", mktime(0, 0, 0, $olddate[1], $olddate[2]+$days, $olddate[0]));
    }

    // leave here if $newdate is younger than $calculated_newdate
    if(!(strlen($calculated_newdate) == 10 && strcmp($calculated_newdate, $row[0]) < 0)){
        $result = db_query("update ".DB_PREFIX."projekte
                               set ".qss($field)." = '$newdate'
                             where ID = '$ID'") or db_die();
    }

    // loop over parents
    $result = db_query("select ID
                          from ".DB_PREFIX."projekte
                         where ID = '$row[1]'") or db_die();
    while ($row = db_fetch_row($result)) {
        move_for($row[0], $field, $days, $newdate);
    }
}

function move_for_down($ID, $field, $days) {
    $result = db_query("select ".qss($field).", parent
                          from ".DB_PREFIX."projekte
                         where ID = '$ID'") or db_die();
    $row = db_fetch_row($result);
    $olddate = explode('-',$row[0]);
    $newdate = date("Y-m-d", mktime(0, 0, 0, $olddate[1], $olddate[2]+$days, $olddate[0]));
    $result = db_query("update ".DB_PREFIX."projekte
                           set ".qss($field)." = '$newdate'
                         where ID = '$ID'") or db_die();
    $result = db_query("select ID
                          from ".DB_PREFIX."projekte
                         where parent = '$ID'") or db_die();
    while ($row = db_fetch_row($result)) {
        move_for_down($row[0], $field, $days);
    }
}

function move_back($ID, $field, $days, $calculated_newdate = '') {
    // get the value
    $result = db_query("select ".qss($field).", parent
                          from ".DB_PREFIX."projekte
                         where ID = '$ID'") or db_die();
    $row = db_fetch_row($result);
    if($calculated_newdate != ''){
        $newdate = $calculated_newdate;
    }
    else{
        $olddate = explode('-',$row[0]);
        $newdate = date("Y-m-d", mktime(0, 0, 0, $olddate[1], $olddate[2]-$days, $olddate[0]));
    }

    // leave here if $newdate is older than $calculated_newdate
    if(!(strlen($calculated_newdate) == 10 && strcmp($calculated_newdate, $row[0]) > -1)){
        $result = db_query("update ".DB_PREFIX."projekte
                               set ".qss($field)." = '$newdate'
                             where ID = '$ID'") or db_die();
    }

    // loop over parents
    $result = db_query("select ID
                          from ".DB_PREFIX."projekte
                         where ID = '$row[1]'") or db_die();
    while ($row = db_fetch_row($result)) {
        move_back($row[0], $field, $days, $newdate);
    }
}

function move_back_down($ID, $field, $days, $fneu="") {
    // get the value
    $result = db_query("select ".qss($field).",parent
                          from ".DB_PREFIX."projekte
                         where ID = '$ID'") or db_die();
    $row = db_fetch_row($result);
    if ($fneu) $olddate = explode('-',$fneu);
    else       $olddate = explode('-',$row[0]);
    $newdate = date("Y-m-d", mktime(0, 0, 0, $olddate[1], $olddate[2]-$days, $olddate[0]));
    $result = db_query("update ".DB_PREFIX."projekte
                           set ".qss($field)." = '$newdate'
                         where ID = '$ID'") or db_die();
    $result = db_query("select ID
                          from ".DB_PREFIX."projekte
                         where parent= '$ID'") or db_die();
    while ($row = db_fetch_row($result)) {
        move_back_down($row[0], $field, $days);
    }
}

function move_back_down1($ID, $field, $fval, $do_update = true) {
    if($do_update){
        $result = db_query(xss("update ".DB_PREFIX."projekte
                                   set ".qss($field)." = '$fval'
                                 where ID = '$ID'")) or db_die();
    }
    $result = db_query("select ID, ende
                          from ".DB_PREFIX."projekte
                         where parent= '$ID'") or db_die();
    while ($row = db_fetch_row($result)) {
        if(strcmp($row[1], $fval) < 1){
            move_back_down1($row[0], $field, $fval, false);
        }
        else{
            move_back_down1($row[0], $field, $fval, true);
        }
    }
}

function makeTime($time) {
    $temp  = explode('-', $time);
    $mtime = mktime(0, 0, 0, $temp[1], $temp[2], $temp[0]);
    return $mtime;
}

?>
