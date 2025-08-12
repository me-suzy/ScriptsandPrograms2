<?php

// forum_view.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: forum_view.php,v 1.27.2.2 2005/08/22 08:39:11 fgraf Exp $

// check whether lib.inc.php has been included
if (!defined("lib_included")) { die("Please use index.php!"); }

// check role
if (check_role("forum") < 1) { die("You are not allowed to do this!"); }
$acc= "(von = '$user_ID' or acc like 'system' or
                             ((acc like 'group'  or acc like '%$user_kurz%') and
                             ".DB_PREFIX."forum.gruppe = '$user_group')) ";
$where = " where "; // we need a default when there is no filter
// open and close main contact
if ($element_mode == "open") {
  $arrproj[$ID] = "1";
  $ID = 0;
}
elseif ($element_mode == "close"){
  $arrproj[$ID] = "";
  $ID = 0;
}

include_once('./forum.inc.php');

//tabs
$tabs = array();
$output .= get_tabs_area($tabs);

if($do=="niente");
// Liste Foren
elseif(empty($fID)){
    $result = db_query("SELECT * from ".DB_PREFIX."forum $where $acc
    AND (parent=0 OR parent IS NULL OR parent='') ")or db_die();
    $liste= make_list($result);



    // button bar
    $buttons = array();
    // form start
    $hidden = array('mode' => 'forms', 'page' => $page);
    if(SID) $hidden[session_name()] = session_id();
    $buttons[] = array('type' => 'form_start', 'hidden' => $hidden);
    // create new forum button
    $buttons[] = array('type' => 'submit', 'name' => 'newfor', 'value' => __('Create'), 'active' => false);
    // form end
    $buttons[] = array('type' => 'form_end');
    // delete forum
    $buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=options&amp;tree_mode='.$tree_mode.'&amp;sort='.$sort.'&amp;up='.$up.'&amp;page='.$page.'&amp;perpage='.$perpage.'&amp;keyword='.$keyword.'&amp;filter='.$filter.'&amp;fID='.$fID.$sid, 'text' => __('Delete forum'), 'active' => false);
    $output .= get_buttons_area($buttons);
    $output .='<div class="hline"></div>';
    $output .= get_top_page_navigation_bar();
    $output .='<div class="hline"></div>';
    $output .= get_status_bar();
    $output .= '<a name="content"></a><br/>';

    $output.="<table class=\"ruler\" id=\"contacts\" summary=\"__('In this table you can find all forums listed')\">
    <thead>
    <tr>
        <th class=\"column-1\" scope=\"col\" title=\"".__('Forum')."\">".__('Forum')."</th>
        <th scope=\"col\" title=\"".__('Topics')."\">".__('Topics')."</th>
        <th scope=\"col\" title=\"".__('Threads')."\">".__('Threads')."</th>
        <th scope=\"col\" title=\"".__('Latest Thread')."\">".__('Latest Thread')."</th>
    </tr>
</thead><tbody>";
    $int=0;
    for ($i=($page*$perpage); $i < $max; $i++) {
        $cons ="AND topic='0'";
        $cons1 = "AND antwort='0'";
        $result = db_query("SELECT * from ".DB_PREFIX."forum where ID = '".$liste[$i]."'")
            or db_die();
        $row = db_fetch_row($result);
        /*
        $output.= "<tr";
        if($int%2==1){
             $output.= " class=\"unev\" ";
        }*/
        $output1='';
        tr_tag("forum.php?mode=forms&amp;ID=$row[0]&amp;fID=$fID&amp;page=$page",'',$row[0]);
        $output.=$output1;
        $output.=" <td scope='row' class=\"column-1\">
        <a href='forum.php?fID=$row[0]'> $row[3]</a></td>
        <td>".get_articles($row[0],"parent","$cons1")."</td>
        <td>".get_articles($row[0], "parent","$cons2")."</td>
        <td>".get_lastarticle($row[0], "parent","$cons1")."</td>
        </tr>\n";
        $int++;
    }

if($int==0)$output.="<tr><td></td><td></td><td></td><td></td></tr>";
$output.="</tbody></table>";


$output.= '<br/>';
$output .= get_bottom_page_navigation_bar();
echo "$output<br /><br />";

}

// show list of threads if no posting is selected
elseif($fID){
    threads($fID);
}

/**
* function which returns array with results of an sql query
* @author Nina Schmitt
* @param  result: $query result
* @return array rows of sql query in array
*/



$_SESSION['arrproj'] =& $arrproj;

?>