<?php

// mail_forms.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: mail_forms.php,v 1.18.2.3 2005/09/12 10:38:26 fgraf Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) die("Please use index.php!");

// check role
if (check_role("mail") < 1) die("You are not allowed to do this!");

// fetch original data
if ($ID) {
    $result = db_query("select ID, von, subject, body, sender, recipient, cc, kat, remark, date_received,
                               touched, date_sent, body_html, parent, contact, projekt,typ
                          from ".DB_PREFIX."mail_client
                         where ID = '$ID'") or db_die();
    $row = db_fetch_row($result);
    // check permission
    if ($row[0] == 0) { die("no entry found."); }
    if ($row[1] <> $user_ID) { die("You are not allowed to do this!"); }
    $form = $row[16];
    $parent= $row[13];

}


// form for mails
if ($form <> "d") {
    // mark mail as red.
    if(!$row[10]) {
        $result = db_query("update ".DB_PREFIX."mail_client
                             set touched=1
                          where ID = '$ID'") or db_die();
    }
    $head=__('Mail');
    /**
    echo "<form method=post action='mail.php'>\n";
    echo "<input type='hidden' name='mode' value='view'>\n";
    echo "<input type=hidden name='typ' value='m'>\n";
    */
    // format 'sender' string from db
    if (ereg("&lt;",$row[4])) {$sender=explode("&lt;",$row[4]);$sender1=substr($sender[1],0,-4);$sender2=$sender[0];}
    elseif (ereg("<",$row[4])) {$sender=explode("<",$row[4]);$sender1=substr($sender[1],0,-1);$sender2=$sender[0];}
    else { $sender2 = $row[4]; }

    // create receiver string
    // if there are several receivers, split them
    $receiver_string = "";
    if (ereg(", ",$row[5])) { $receivers = explode(", ",$row[5]); }
    else { $receivers[0] = $row[5]; }
    for ($i=0; $i < count($receivers); $i++) {
        $rec = explode("<",$receivers[$i]);
        // try to find the email adress
        // 1. option: test<test@test.de> -> split was successful
        if ($rec[1] <> "") { $receiver1 = html_out(substr($rec[1],0,-1)); }
        // 2. option text@test.de -> take the email adress as it is
        else { $receiver1 = html_out($rec[0]); }
        $receiver2 = html_out($rec[0]);
        if ($i > 0) { $receiver_string .= "; "; }
        $receiver_string .= "<a class='pa'href='mail.php?mode=send_form&amp;form=email&amp;recipient=$receiver1$sid'>$receiver2</a>";
    }
    // echo sender and receiver string
    $outmail='<br style="clear:left" /><br />';
    $box_right_data = array();
    $box_right_data['type']         = 'anker';
    $box_right_data['anker_target'] = 'oben';
    $box_right_data['link_text']    = __('Basis data');
    $outmail.="<div class='boxHeaderSmallLeft'>".__('Message from:') ." <a class='mail_pa' href='mail.php?mode=send_form&amp;form=email&amp;recipient=$sender1$sid'>$sender2</a></div>";
    $outmail.='<div class="boxHeaderSmallRight">'.__('Recipients').'</div><div class="boxContentSmallLeft">';

    $outmail.="<br />
    <span class='formbody'>".__('Title').":</span> $row[2]<br class='clear' /><br />\n";
    // body: add linebreaks
    $body = $row[3];
    // try to avoid scripting which can be poisoned
    //$body = eregi_replace("<script","&lt;script",$body);
    $body = html_out($body);
    // convert web and mail links to clickable links ... but only for a text mail!
    // if (!eregi("<href=",$body) and !eregi("mailto:",$body)) {
    $body = @eregi_replace("(((f|ht){1}tp://)[a-zA-Z0-9@:%_.~#-\?&]+[a-zA-Z0-9@:%_~#\?&])", "<a href=\"\\1\" target=\"_blank\">\\1</a>", $body); //http
    $body = @eregi_replace("([[:space:]()[{}])(www.[a-zA-Z0-9@:%_.~#-\?&]+[a-zA-Z0-9@:%_~#\?&])", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $body); // www.
    $body = @eregi_replace("([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})","<a href=\"mailto:\\1\">\\1</a>", $body); // @
    //}
    $body = nl2br($body);

    // show link to html output and display body in plain text
    $outmail.="$body<br class='clear' />";
    if (!empty($row[12])) {
        $outmail .= "<a href='./mail.php?mode=data&amp;action=showhtml&amp;ID=$ID&perpage=$perpage&page=$page'>HTML Body</a><br />\n";
    }
    $outmail.="<br />";
    
    // reply, reply all and forward
    $outmail.="<input type='button' onclick=\"self.location.href='mail.php?mode=send_form&amp;form=email&amp;action2=reply&amp;ID=$row[0]$sid'\" value='".__('Reply')."' name='".__('Reply')."' class='button2' />&nbsp;\n";
    $outmail.="<input type='button' onclick=\"self.location.href='mail.php?mode=send_form&amp;form=email&amp;action2=replyall&amp;ID=$row[0]$sid'\" value='".__('answer all')."' name='".__('answer all')."' class='button2' />&nbsp;\n";
    $outmail.="<input type='button' onclick=\"self.location.href='mail.php?mode=send_form&amp;form=email&amp;action2=forward&amp;ID=$row[0]$sid'\" value='".__('Forward')."' name='".__('Forward')."' class='button2' />&nbsp;\n";
    $outmail.="<br style='clear: both;' /></div>";
    // links zu rechts auf
    $outmail.='<div class="boxContentSmallRight">';

    $outmail.= "<br style='clear:both;' /><span class='formbody'>".__('to').": &nbsp;</span> $receiver_string";
    // cc string
    if ($row[6]) {
        $cc_string = "";
        if (ereg(", ",$row[6])) { $ccs = explode(", ",$row[6]); }
        else { $ccs[0] = $row[6]; }
        for ($i=0; $i < count($ccs); $i++) {
            $ccstrings = explode("<",$ccs[$i]);
            $cc1 = html_out(substr($ccstrings[1],0,-1));
            $cc2 = html_out($ccstrings[0]);
            if ($i > 0) { $cc_string .= "; "; }
            $cc_string .= "<a href='mail.php?mode=send_form&amp;form=email&amp;recipient=$cc1$sid'>$cc2</a>";
        }
    }
    $outmail.="<br class='clear' /><span class='formbody'>CC: &nbsp;</span>$cc_string
    <br class='clear' />";
    $outmail.="<hr class='mail' />";
    $date_received = show_iso_date1($row[9]);
    $date_sent = show_iso_date1($row[11]);

    $outmail.="<span class='formbody'>".__('Send date').":</span> $date_sent<br class='clear' />
    <span class='formbody'>".__('Received').":</span> $date_received <br class='clear' />
    <hr class='mail' />\n";
    $outmail.="<span class='formbody'>".__('Attachment').":</span><br class='clear' />";

    $result3 = db_query("select ID, parent, filename, tempname, filesize
                           from ".DB_PREFIX."mail_attach
                          where parent = '$ID'") or db_die();
    while ($row3 = db_fetch_row($result3)) {
        // determine filesize
        if ($row3[4] > 1000000)  {$fsize = floor($row3[4]/1000000)." M";}
        elseif ($row3[4] > 1000) {$fsize = floor($row3[4]/1000)." k";}
        else {$fsize = $row3[4];}
        // write data to the array for downloading
        $rnd = rnd_string();
        $file_ID[$rnd] = "$row3[2]|$row3[3]|$row3[0]";
        $outmail.="<a href='mail_down.php?rnd=".$rnd.$sid."' target=_blank>$row3[2] ($fsize)</a><br />\n";
    }
    $outmail.= "<br /><br />";
    //$outmail.= "<br /><br /></div>";

    $_SESSION['file_ID'] =& $file_ID;
    $box_right_data = array();
    $box_right_data['type']         = 'anker';
    $box_right_data['anker_target'] = 'unten';
    $box_right_data['link_text']    = __('Links');
    $outdir ='
    <br style="clear: both;" />
    <div class="inner_content"><a name="content"></a>';
    $outdir.=get_box_header(__('Basis data'), 'oben', $box_right_data).'<div class="boxContent">';
    $outmail1.='<br style="clear: both;" />
    <div class="inner_content"><a name="content"></a>'.get_box_header(__('file away message'), 'oben', $box_right_data).'<div class="boxContent">';

}

// form for directories
elseif ($form == "d") {
    $head=__('Directory');
    $box_right_data = array();
    $box_right_data['type']         = 'anker';
    $box_right_data['anker_target'] = 'unten';
    $box_right_data['link_text']    = __('Links');
    $outdir ='
    <br style="clear: both;" />
    <div class="inner_content"><a name="content"></a>';
    $outdir.=get_box_header(__('Basis data'), 'oben', $box_right_data).'<div class="boxContent">';
    $outdir.="<div class='formBodyRow'><label class='formsmall' for='dirname'>".__('Name').":</label><input type='text' name='dirname' id='dirname' value='$row[2]' /><br style='clear:both;' /></div>";
}
//tabs
$tabs = array();
if ($ID){
    $hidden=array('mode'=>'data','ID'=>$ID,'aendern'=>'anlegen','make'=>'aendern','action'=>$action,'typ'=>$form,);
}
else{
    $hidden=array('mode'=>'data','anlegen'=>'neu_anlegen','action'=>$action,'typ'=>$form);
}
$hidden = array_merge($hidden, $view_param);
$buttons = array();
$buttons[] = array('type' => 'form_start', 'enctype'=>'multipart/form-data','hidden' => $hidden, 'onsubmit'=>"return chkForm('frm','dirname','".__('Please specify a description!'));
$output= get_buttons($buttons);
$output .= get_tabs_area($tabs);
// button bar
$buttons = array();
if ($ID){
    $result2 = db_query("select ID
                             from ".DB_PREFIX."projekte
                            where parent = '$ID'") or db_die();
    $row2 = db_fetch_row($result2);
    $buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=view&amp;set_read_flag=1&amp;ID_s='.$ID.$sid, 'text' => __('Mark as read'), 'active' => false);

    $buttons[]=array('type' => 'submit', 'name' => 'modify_b', 'value' => __('Modify'), 'active' => false);
    if ($row2[0] == '') {
        $buttons[]=array('type'=>'submit', 'name'=>'delete_b','value'=>__('Delete'), 'onclick'=>"return confirm('".__('Are you sure?'));
    }
}
else{
    $buttons[]=array('type' => 'submit', 'name' => 'create_b', 'value' => __('Create'), 'active' => false);
}

$buttons[]=(array('type' => 'link', 'href' => 'mail.php?mode=view', 'text' => __('back'), 'active' => false));
$output .= get_buttons_area($buttons);


//form data
if($form == "d")$output.= $outdir;
else $output.=$outmail1;

// now the module_manager output
// continue with rest of the form which applies to all three types of records


$output.="<div class='formBodyRow'><label class='formsmall' for='parent'>".__('Directory').":</label>";
if ($ID) {
    // copying of dirs at the moment not possible
    if ($form <> "d") $output.= " <input type='radio' name='c_m' value='c' />".__('Copy')."\n";
    $output.= " <input type='radio' name='c_m' value='m' />".__('Move')." &nbsp; ".__('with new values')." &nbsp;&nbsp;&nbsp;\n";
}
$output.= "<select name='parent' id='parent'><option value='0'></option>\n";
$output.=show_elements_of_tree("mail_client",
"subject",
"where typ like 'd' and  von = '$user_ID'",
'von',"",$parent,"parent",$ID);
$output.= "</select><br style='clear:both;' /></div>\n";


$output.=build_form($fields);
if ($ID){
    $output.=get_buttons(array(array('type' => 'submit', 'name' => 'modify_b', 'value' => __('Modify'),  'active' => false)));
    if ($row2[0]== '')$output.=get_buttons(array(array('type'=>'submit', 'name'=>'delete_b','value'=>__('Delete'), 'onclick' => "return confirm('".__('Are you sure?'))));
}
else{
    $output.=get_buttons(array(array('type' => 'submit', 'name' => 'create_b', 'value' => __('Create'),'active' => false)));
}

$output.=get_buttons(array(array('type' => 'link', 'href' => 'mail.php?mode=view', 'text' => __('back'), 'active' => false)));

$output.='</div></form><div class="inner_content">';
if($outmail<>'') $output.=$outmail.'</div></div>';
else $output.='</div>';
// general part over
echo $output;


?>
