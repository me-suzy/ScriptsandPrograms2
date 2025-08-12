<?php

// forum_options.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: forum_options.php,v 1.21.2.3 2005/08/22 08:39:11 fgraf Exp $

// check lib
if (!defined("lib_included")) { die("Please use index.php!"); }

// check role
if (check_role("forum") < 2) { die("You are not allowed to do this!"); }

/**
* show_postings() Zeigt Foren und Forenbeiträge an und bietet die Option diese zu löschen
* @author Nina Schmitt
*/
function show_postings() {
  global $user_ID, $fID, $dbTSnull;
  if (empty($fID)) {

    //tabs
    $tabs = array();
    $output .= get_tabs_area($tabs);

    // button bar
    $buttons = array();
    // form start
    $hidden = array('mode' => 'forms', 'page' => $page);
    if(SID) $hidden[session_name()] = session_id();
    $buttons[] = array('type' => 'form_start', 'hidden' => $hidden);
    // create new forum button
    $buttons[] = array('type' => 'submit', 'name' => 'newfor', 'value' => __('Create new forum'), 'active' => false);
    // form end
    $buttons[] = array('type' => 'form_end');
    // delete forum
    $buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=options&tree_mode='.$tree_mode.'&sort='.$sort.'&up='.$up.'&page='.$page.'&perpage='.$perpage.'&keyword='.$keyword.'&filter='.$filter.'&fID='.$fID.$sid, 'text' => __('Delete forum'), 'active' => false);
    $output .= get_buttons_area($buttons);
    $output .='<div class="hline"></div><a name="content"></a>';


    $head=__('Delete forum');
    }
   if($fID){
    //tabs
    $tabs = array();
    $output .= get_tabs_area($tabs);

    // button bar
    $buttons = array();
    $buttons[] = array('type' => 'text', 'text' => '<b>'.slookup("forum","titel","ID",$fID).'</b>');
    $buttons[] = array('type' => 'separator');
    // form start
    $hidden = array('mode' => 'forms', 'fID' => $fID);
    if(SID) $hidden[session_name()] = session_id();
    $buttons[] = array('type' => 'form_start', 'hidden' => $hidden);
    // new posting
    $buttons[] = array('type' => 'submit', 'name' => 'newbei', 'value' => __('New posting'), 'active' => false);
    // form end
    $buttons[] = array('type' => 'form_end');
    // delete posting
    $buttons[] = array('type' => 'link', 'href' => 'forum.php?mode=options&tree_mode='.$tree_mode.'&sort='.$sort.'&up='.$up.'&page='.$page.'&perpage='.$perpage.'&keyword='.$keyword.'&filter='.$filter.'&fID='.$fID.$sid, 'text' => __('Delete posting'), 'active' => false);
    $output .= get_buttons_area($buttons);
    $output .='<div class="hline"></div>';
    $output .= get_forum_path_bar();
    $output .='<div class="hline"></div><a name="content"></a>';

    $head =__('Delete posting');



    }



    if($fID){
        $result = db_query("select ID, titel, datum
                              from ".DB_PREFIX."forum
                         where von ='$user_ID' AND parent='$fID'
                          order by datum desc") or db_die();
    }
    else{
        $result = db_query("select ID, titel, datum
                              from ".DB_PREFIX."forum
                             where von ='$user_ID' AND (parent=0 OR parent IS NULL OR parent='')
                          order by datum desc") or db_die();
    }
    $options = '';
    while ($row = db_fetch_row($result)) {
        $result2 = db_query("select ID
                               from ".DB_PREFIX."forum
                              where antwort ='$row[0]'") or db_die();
        $row2 = db_fetch_row($result2);
        if (!$row2[0]) {
            $options .= "<option value='$row[0]'>".html_out(substr($row[1],0,40))." (".show_iso_date1($row[2]).")\n";
            $op = true;
        }
    }
    if ($op) {
        $options = '<option value=""></option>'.$options;
    }

    $output .= '
    <br/>
    <form action="forum.php" method="post">
    <input type="hidden" name="mode" value="options">
    <input type="hidden" name="'.session_name().'" value="'.session_id().'">
    <div class="inner_content">
        <a name="oben" id="oben"></a>
        <div class="boxHeader">'.$head.'</div>
        <div class="boxContent">
        <br/>
        <select name="ID">'.$options.'</select>
        '.get_buttons(array(array('type' => 'submit', 'active' => false, 'name' => 'loeschen', 'value' => __('Delete'), 'onclick' => 'return confirm(\''.__('Are you sure?').'\')'))).
        get_buttons(array(array('type' => 'link', 'active' => false, 'href' => 'forum.php?fID='.$fID.'&sort='.$sort.'&mode=view', 'text' => __('back')))).'
        <br/><br/>
        </div>
        <br style="clear:both"/><br/>
    </div>
    </form>
    <br style="clear:both"/><br/>
    </form>
    ';

   echo $output;
}

// this function is called via the options menu:
// delete a posting but only if there is no comment for it available
function delete_posting($ID) {
  global $lib_path, $fID, $user_ID;
  // check permission
  include_once("$lib_path/permission.inc.php");
  check_permission("forum","von",$ID);
   $result = db_query("DELETE
                                  FROM ".DB_PREFIX."forum
                                 WHERE parent = '$ID'") or db_die();
  // db action
  $result = db_query("delete from ".DB_PREFIX."forum
                       where ID = '$ID'") or db_die();

  // ... and call the list
  $ID = "";
  //$fID="";
  $mode="view";


}

// show own records ...
if (!$ID) show_postings();
// ... and delete them
else{ delete_posting($ID);
  include_once("./forum_view.php");
}

?>
