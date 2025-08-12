<?php

// forum_forms.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: forum_forms.php,v 1.26 2005/06/20 14:34:14 paolo Exp $

// check whether lib.inc.php has been included
if (!defined("lib_included")) { die("Please use index.php!"); }

// check role
if (check_role("forum") < 1) { die("You are not allowed to do this!"); }

/**
* writetext()- gibt das Antwortformular auf einen Forumsbeitrag aus
* @author Nina Schmitt
* @param
* @return
*/
function writetext() {
global $ID,$fID, $antwort, $sid, $page, $perpage, $keyword,$page,$mode, $mode2, $user_group, $sql_user_group, $filter, $tree_mode,$user_kurz, $user_ID;
global $dbTSnull;


    //tabs
    $tabs = array();
    $output .= get_tabs_area($tabs);

    // button bar
    $buttons = array();
    $buttons[] = array('type' => 'text', 'text' => '<b>'.slookup("forum","titel","ID",$fID).'</b>');
    $buttons[] = array('type' => 'separator');
    // form start
    $hidden = array('mode' => 'forms', 'page' => $page, 'fID' => $fID);
    if(SID) $hidden[session_name()] = session_id();
    $buttons[] = array('type' => 'form_start', 'hidden' => $hidden);
    // new posting
    $buttons[] = array('type' => 'submit', 'name' => 'newbei', 'value' => __('New posting'), 'active' => false);
    // form end
    $buttons[] = array('type' => 'form_end');
    // delete posting
    $buttons[] = array('type' => 'link', 'href' => 'forum.php?mode=options&tree_mode='.$tree_mode.'&sort='.$sort.'&up='.$up.'&page='.$page.'&perpage='.$perpage.'&keyword='.$keyword.'&filter='.$filter.'&fID='.$fID.$sid, 'text' => __('Delete thread'), 'active' => false);
    $output .= get_buttons_area($buttons);
    $output .='<div class="hline"></div>';
    $output .= get_forum_path_bar();
    $output .='<div class="hline"></div>';



  // check permission
  $result = db_query("select ID, von, acc, acc_write
                        from ".DB_PREFIX."forum
                       where ID = '$ID' and
                             $sql_user_group") or db_die();
    $row = db_fetch_row($result);
    if (!$row[0] or check_role("forum") < 1 or ($row[1] <> $user_ID and !eregi("system|group|$user_kurz",$row[2])) or (1<2)) {
      //die("You are not privileged to do this!");
    }

    $result = db_query("select ID,antwort,von,titel,remark,kat,datum,gruppe,lastchange,notify
                          from ".DB_PREFIX."forum
                         where ID = '$ID'") or db_die();
    $row = db_fetch_row($result);







     /**
      B  <tr><td width=450><i>".slookup('users','vorname,nachname','ID',$row[2])."<i>".show_iso_date1($row[6])."</i><h3>".html_out($row[3])."</h3></td></tr>";*/
    // find out how many comments are already there and adjustthe wordwrap to it
    $linelength = 80;
    $linelength = $linelength + findlinelength($row[4]);
    // add linebreaks, convert web and mail links to clickable links
    $posting = wordwrap(html_out($row[4]),$linelength);
    // begin regexp - turn text links into clickable links
    $posting = @eregi_replace("(((f|ht){1}tp://)[a-zA-Z0-9@:%_.~#-\?&]+[a-zA-Z0-9@:%_~#\?&])", "<a href=\"\\1\" target=\"_blank\">\\1</a>", $posting); //http
    // in case of problems change the above line with the next one
    $posting = @eregi_replace("([[:space:]()[{}])(www.[a-zA-Z0-9@:%_.~#-\?&]+[a-zA-Z0-9@:%_~#\?&])", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $posting); // www.
    // in case of problems change the above line with the next one
    $posting = @eregi_replace("([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})","<a href=\"mailto:\\1\">\\1</a>", $posting); // @
    // end regexp for clickable links
    // add linebreaks, convert web and mail links to clickable links
    $posting = '<br/>'.nl2br($posting);
    $posting .= '<br/><br/>';
    $posting .= get_buttons(array(array('type' => 'link', 'href' => 'forum.php?fID='.$fID.'&sort='.$sort.'&mode=view', 'text' => __('back'), 'active' => false)));
    $posting .= '<br/><br/>';
    $do="niente";
    include_once("./forum.inc.php");
    $posting .= "<br/>".show_ans($fID,$ID).'<br/>';






    // comment
    $ti= "AW: ".html_out($row[3]);
    $comment = '
<br/>
        <form style="display: inline;" action="forum.php" name="forumneu" method="post">
<label for="titel" class="center">'.__('Title').'</label><input class="center" type="text" tabindex="1" size="50" name="titel" id="titel" value="'.htmlspecialchars($ti).'"
title="insert new name for forum"/>

<label for="remark" class="center">'.__('Text').'</label><textarea class=center tabindex="2" rows="9" cols="60" name="remark" title="a comment about this record"></textarea>
<input type="hidden" name="mode" value="data">
<input type="hidden" name="fID" value="'.$fID.'">
<input type="hidden" name="ID" value="'.$ID.'">
</fieldset>

<div class="clear">
   <span id="right">
        <input type="hidden" name="page" value="'.$page.'">
'.get_buttons(array(array('type' => 'submit', 'name' => 'answer', 'value' => __('Send'), 'active' => false)))
.get_buttons(array(array('type' => 'link', 'href' => 'forum.php?fID='.$fID.'&sort='.$sort.'&mode=view', 'text' => __('back'), 'active' => false))).'
    </span>
   <fieldset>
<legend>'.__('Notification').'</legend>
        <span id="center">';
        if (PHPR_FORUM_NOTIFY) {
        $comment .= "<input type=checkbox name=notify_others>".__('Notify all group members')."<br>\n";
        }
        // checkbox for notification for myself on comments
        $comment .= "<input type=checkbox name=notify_me> ".__('Notify me on comments')."
    </span>
";
// access form
//$output.= "<b>$datei_text11</b>";
 include_once("../lib/access_form.inc.php");
  // acc_read, exclude the user itself, acc_write, no parent possible, write access=yes
 $comment .= access_form2($row[11], 0, $row[12], 0, 0).'</div>';
 $comment .= "</form>";




    $output .= '
    <br/>
    <div class="inner_content">
        <a name="content"></a>
        <div class="boxHeaderLeft">'.__('Thread title').__(':').' '.html_out($row[3]).'</div>
        <div class="boxHeaderRight">'.slookup('users', 'vorname', 'ID', $row[2]).', '.show_iso_date1($row[6]).'</div>
        <div class="boxContent">'.$posting.'</div>
        <br style="clear:both"/><br/>

        <div class="boxHeaderLeft">'.__('Comment').'</div>
        <div class="boxHeaderRight"></div>
        <div class="boxContent">'.$comment.'</div>
        <br style="clear:both"/><br/>
    </div>
    ';
    unset($posting);
    unset($comment);

  echo $output;
}


/**
*determines how often a string was commented (and thus the extended the length)
* @author Nina Schmitt
* @param string: the concerned text
* @return int   the length of the text
*/
function findlinelength($text) {

  while (!$i) {
    $commentprefix .= ">";
    if (ereg($commentprefix,$text)) { $addlength += 6; }
    else { $i = 1; }
  }
  return $addlength;
}

/**
* form to create a new forum or topic
* @author Nina Schmitt
* @param int fID: forum ID
* @param int ID: topic ID
* @return
*/
function create_forum($fID="",$ID=""){
    global $dbTSnull;

    if(empty($fID)){

    //tabs
    $tabs = array();
    $output .= get_tabs_area($tabs);

    // button bar
    $buttons = array();
    // form start
    $hidden = array('mode' => 'forms', 'page' => $page);
    if(SID) $hidden[session_name()] = session_id();
    $buttons[] = array('type' => 'form_start', 'hidden' => $hidden);
    // create new forum
    $buttons[] = array('type' => 'submit', 'name' => 'newfor', 'value' => __('Create'), 'active' => false);
    // form end
    $buttons[] = array('type' => 'form_end');
    // delete forum
    $buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=options&tree_mode='.$tree_mode.'&sort='.$sort.'&up='.$up.'&page='.$page.'&perpage='.$perpage.'&keyword='.$keyword.'&filter='.$filter.'&fID='.$fID.$sid, 'text' => __('Delete forum'), 'active' => false);
    $output .= get_buttons_area($buttons);
    $output .='<div class="hline"></div>';


    $action= "createfor";
    $head="Neues Forum";
    }
    else{
        $head= __('New Thread');
        $action= "createbei";
        $head1=__('New posting');
    }   if(!empty($fID)){

        //tabs
        $tabs = array();
        $output .= get_tabs_area($tabs);

        // button bar
        $buttons = array();
        $buttons[] = array('type' => 'text', 'text' => '<b>'.slookup("forum","titel","ID",$fID).'</b>');
        // form start
        $hidden = array('mode' => 'forms', 'fID' => $fID);
        if(SID) $hidden[session_name()] = session_id();
        $buttons[] = array('type' => 'form_start', 'hidden' => $hidden);
        // new posting
        $buttons[] = array('type' => 'submit', 'name' => 'newbei', 'value' => __('New posting'), 'active' => false);
        // form end
        $buttons[] = array('type' => 'form_end');
        // delete posting
        $buttons[] = array('type' => 'link', 'href' => 'forum.php?mode=options&tree_mode='.$tree_mode.'&sort='.$sort.'&up='.$up.'&page='.$page.'&perpage='.$perpage.'&keyword='.$keyword.'&filter='.$filter.'&fID='.$fID.$sid, 'text' => __('Delete thread'), 'active' => false);
        $output .= get_buttons_area($buttons);
        $output .='<div class="hline"></div>';
        $output .= get_forum_path_bar();
        $output .='<div class="hline"></div>';

    }

    /*******************************
    *       basic fields
    *******************************/
    $form_fields = array();
    $form_fields[] = array('type' => 'text', 'name' => 'titel', 'label' => __('Title').__(':'), 'value' => '', 'label_class' => 'small', 'width' => '500px');
    $form_fields[] = array('type' => 'textarea', 'name' => 'remark', 'label' => __('Text').__(':'), 'value' => '', 'label_class' => 'small', 'width' => '500px', 'height' => '200px');
    $form_fields[] = array('type' => 'hidden', 'name' => 'mode', 'value' => 'data');
    $form_fields[] = array('type' => 'hidden', 'name' => 'fID', 'value' => $fID);
    $form_fields[] = array('type' => 'hidden', 'name' => 'page', 'value' => $page);
    if (PHPR_FORUM_NOTIFY) {
        $form_fields[] = array('type' => 'checkbox', 'name' => 'notify_others', 'label_right' => __('Notify all group members').__(':'));
    }
    // checkbox for notification for myself on comments
    $form_fields[] = array('type' => 'checkbox', 'name' => 'notify_me', 'label_right' => __('Notify me on comments').__(':'));
    // access
    include_once("../lib/access_form.inc.php");
    // acc_read, exclude the user itself, acc_write, no parent possible, write access=yes
    $form_fields[] = array('type' => 'parsed_html', 'html' => access_form2($row[11], 0, $row[12], 0, 0));
    // buttons
    $html  = get_buttons(array(array('type' => 'submit', 'name' => $action, 'value' => __('Accept'), 'active' => false)));
    $html .= get_buttons(array(array('type' => 'link', 'href' => 'forum.php?fID='.$fID.'&sort='.$sort.'&mode=view', 'text' => __('Cancel'), 'active' => false)));
    $form_fields[] = array('type' => 'parsed_html', 'html' => $html);
    $basic_fields = get_form_content($form_fields);

    $output .= '
    <br/>
    <form style="display: inline;" action="forum.php" name="forumneu" method="post">
    <div class="inner_content">
        <a name="content"></a>
        <a name="oben" id="oben"></a>
        <div class="boxHeader">'.$head.'</div>
        <div class="boxContent">'.$basic_fields.'</div>
        <br style="clear:both"/><br/>
    </div>
    </form>
    <br style="clear:both"/><br/>
    </form>
    ';

    echo $output;
}
if($newfor) create_forum();
elseif($newbei)create_forum($fID);
else{ if ($ID > 0) writetext();
//if (check_role("forum") > 1) { forms(); }
}

?>