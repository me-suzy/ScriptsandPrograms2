<?php

// project_options.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $auth$
// $Id: projects_options.php,v 1.13 2005/06/17 14:34:12 nina Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) die("Please use index.php!");

// check role
if (check_role('projects') < 1) die('You are not allowed to do this!');


if ($action == 'cop_branch') {
    include_once($lib_path.'/branches.inc.php');
    copy_branch($root_ID, $new_parent_ID);
    include_once('./projects_view.php');
}
else if ($action == 'move_branch') {
    include_once($lib_path.'/branches.inc.php');
    move_branch($ID, $field, $days);
    include_once('./projects_view.php');
}
else {
    // tabs
    $tabs = array();
    $output .= get_tabs_area($tabs);

    // button bar
    $buttons = array();
    $buttons[] = array('type' => 'link', 'href' => 'projects.php?mode=forms&amp;action=new'.$sid, 'text' => __('New'), 'active' => false);
    $buttons[] = array('type' => 'link', 'href' => 'projects.php?mode=options'.$sid, 'text' => __('Options'), 'active' => true);
    $buttons[] = array('type' => 'link', 'href' => 'projects.php?mode=stat'.$sid, 'text' => __('Statistics'), 'active' => false);
    $buttons[] = array('type' => 'link', 'href' => 'projects.php?mode=stat&amp;mode2=mystat'.$sid, 'text' => __('My Statistic'), 'active' => false);
    $buttons[] = array('type' => 'link', 'href' => 'projects.php?mode=gantt'.$sid, 'text' => __('Gantt'), 'active' => false);
    $buttons[] = array('type' => 'link', 'href' => 'projects.php?type='.$type.'&amp;sort='.$sort.'&amp;mode=view&amp;up='.$up.'&amp;filter='.$filter.'&amp;keyword='.$keyword.'&amp;perpage='.$perpage.'&amp;page='.$page, 'text' => __('back'), 'active' => false);
    $output .= get_buttons_area($buttons);
    $output .= '<div class="hline"></div>';





    //prepare values for function
    $where = "where (acc like 'system' or ((von = '$user_ID' or acc like 'group' or acc like '%\"$user_kurz\"%') and $sql_user_group))";
    // copy project branches
    $copy_html  = "
    <br />
    <form action='projects.php' method='post'>
    <input type='hidden' name='mode' value='options' />
    <input type='hidden' name='action' value='cop_branch' />
    <label for='root_ID' class='options'>".__('Copy this element<br> (and all elements below)').": </label>
    <select name='root_ID' id='root_ID' class='options'><option value='0'></option>
    ".show_elements_of_tree("projekte","name",$where,"personen"," order by name",'',"parent",0)."
    </select>
    <br class='clear' /><label class='options' for='new_parent_ID' >".__('And put it below this element').": </label>
    <select name='new_parent_ID' id='new_parent_ID' class='options'><option value='0'></option>
    ".show_elements_of_tree("projekte","name",$where,"personen"," order by name",'',"parent",0)."
    </select>";
    if (SID) $copy_html .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
    $copy_html .= "<br />".get_buttons(array(array('type' => 'submit', 'value' => __('go'), 'active' => false)))
    ."&nbsp;</form>\n";


    //prepare values for function
    $where = "where $sql_user_group";
    // move project branches
    $edit_html .= "
    <br />
    <form action='projects.php' method='post'>
    <input type=hidden name='mode' value='options' />
    <input type=hidden name='action' value='move_branch' />
    <label class='options' for='field'>".__('Modify').":</label>
    <select name='field' class='options'>
    <option value='anfang'>".__('Begin')."</option><option value='ende'>".__('End')."</option>
    </select>

    <label class='options' for='ID'>".__('of this element<br> (and all elements below)').": </label>
    <select name='ID' class='options'><option value='0'></option>
    ".show_elements_of_tree("projekte", "name", $where, "personen", " ORDER BY name", '', "parent", 0)."
    </select>


    <label class='options' for='days' >".__('by')." </label><select name='days' class='options'>";
    for ($i=-100; $i<=-1; $i++) $edit_html .= "<option value='$i'>$i ".__('Days')."</option>";
    $edit_html .= "<option value='0' selected>0 ".__('Days')."</option>";
    for ($i=1; $i<101; $i++) $edit_html .= "<option value='$i'>$i ".__('Days')."</option>";
    $edit_html .= "</select>\n";
    if (SID) $edit_html .="<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
    $edit_html .= "<br />".get_buttons(array(array('type' => 'submit', 'value' => __('go'), 'active' => false)))."&nbsp;</form>\n";


    $form_fields = array();
    $form_fields[] = array('type' => 'parsed_html', 'html' => '<br /><form action="projects.php" method="post">');
    $form_fields[] = array('type' => 'hidden', 'name' => 'mode', 'value' => 'options');
    $form_fields[] = array('type' => 'hidden', 'name' => 'action', 'value' => 'move_branch');
    $options = array();
    $options[] = array('value' => 'anfang', 'text' => __('Begin'));
    $options[] = array('value' => 'ende', 'text' => __('End'));
    $form_fields[] = array('type' => 'select', 'name' => 'field', 'label' => __('Modify').__(':'), 'options' => $options);

    $options = array();
    $options[] = array('value' => '0', 'text' => '');


    // missing show_elements_of_tree here ...
    $tmp = get_elements_of_tree("projekte","name",$where,"personen"," order by name",'',"parent",0);
    foreach($tmp as $option_data){
        $options[] = array('value' => $option_data['value'], 'text' => (str_repeat('&nbsp;&nbsp;', $option_data['depth'])).$option_data['text'], 'selected' => $option_data['selected']);
    }
    $form_fields[] = array('type' => 'select', 'name' => 'ID', 'label' => __('of this element<br> (and all elements below)').__(':'), 'options' => $options);

    $options = array();
    $options[] = array('value' => '0', 'text' => '');
    $options[] = array('value' => 'ende', 'text' => __('End'));
    for ($i=-100; $i<=-1; $i++){
        $options[] = array('value' => $i, 'text' => $i.' '.__('Days'));
    }
    $options[] = array('value' => '0', 'text' => '0 '.__('Days'), 'selected' => true);
    for ($i=1; $i<101; $i++){
        $options[] = array('value' => $i, 'text' => $i.' '.__('Days'));
    }
    $form_fields[] = array('type' => 'select', 'name' => 'days', 'label' => __('by').__(':'), 'options' => $options);
    if(SID) $form_fields[] = array('type' => 'hidden', 'name' => session_name(), 'value' => session_id());
    $form_fields[] = array('type' => 'parsed_html', 'html' => "<br />".get_buttons(array(array('type' => 'submit', 'value' => __('go'), 'active' => false)))."&nbsp;</form>\n");
    $edit_html = get_form_content($form_fields);

    $output .= '
    <br />
    <div class="inner_content">
        <a name="content"></a>
        <div class="boxHeader">'.__('Copy project branch').'</div>
        <div class="boxContent">'.$copy_html.'</div>
        <br style="clear:both" /><br />

        <div class="boxHeader">'.__('Edit timeframe of a project branch').'</div>
        <div class="boxContent">'.$edit_html.'</div>
        <br style="clear:both" /><br />
    </div>
    <br style="clear:both" /><br />
    ';

    echo $output;
}

?>
