<?php

// navigation.inc.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Alexander Haslberger, $Author: nina $
// $Id: navigation.inc.php,v 1.34.2.1 2005/08/05 08:14:57 nina Exp $


class PHProjekt_Navigation {

    var $output = '';
    var $module_entries = array();
    var $addons_entries = array();
    var $control_entries = array();
    var $controls = array();
    var $all_modules = array();
    var $config = array();
    var $skin = 'default';
    var $numeration = array();

    /**
    * @param 
    * @return
    */
    function PHProjekt_Navigation(){
        // some application paras
        $this->actor_id                  = $GLOBALS['user_ID'];
        $this->actor_user_group          = $GLOBALS['user_group'];
        $this->actor_access              = $GLOBALS['user_access'];
        $this->application_mode          = $GLOBALS['mode'];
        $this->application_mode2         = $GLOBALS['mode2'];
        $this->application_action        = $GLOBALS['action'];
        $this->application_sure          = $GLOBALS['sure'];
        $this->application_view          = $GLOBALS['view'];
        $this->application_module        = $GLOBALS['module'];
        $this->application_language      = $GLOBALS['langua'];
        $this->application_nav_searchbox = $GLOBALS['nav_searchbox'];
        $this->application_addon         = $_REQUEST['addon'];
    }
    /**
    * @param 
    * @return
    */
    function render(){
        // import layout config data
        $this->set_config_data();
        // add global
        uasort($this->controls, array('PHProjekt_Navigation', 'sort_entries'));
        $this->add_controls();
        $this->render_controls();
        // add modules
        $this->add_modules();
        uasort($this->module_entries, array('PHProjekt_Navigation', 'sort_entries'));
        $this->render_modules();
        // add addons
        $this->add_addons();
        $this->render_addons();
    }
    /**
    * @param 
    * @return
    */
    function add_modules(){
        foreach($this->all_modules as $module){
            $const_name = 'PHPR_'.strtoupper($module[1]);
            if((defined($const_name) && constant($const_name) && (check_role($module[2]) > 0 || in_array($module[1], array('links'))))
                || in_array($module[1], array('summary'))){
                $this->module_entries[$module[2]] = array($module[0], __($module[3]), $module[4]);
            }
        }
    }
    /**
    * @param 
    * @return
    */
    function render_modules(){
        $this->output .= $this->render_headline('Modules');
        $this->numeration['modules'] = 0;
        foreach($this->module_entries as $k => $v){
            $numeration = '2.'.(++ $this->numeration['modules']) . ': ';
            if($this->is_active($k)){
                // module is selected
                $selected = 'Selected';
            }
            else{
                // module is not selected
                $selected = '';
            }
            // add text
            if($v[2] == 1){
                $str  = '<li><a class="navLink" href="../%s/%s.php?%s" title="%s">';
                $str .= '<span class="navLink%s"><dfn>%s</dfn>%s</span>';
                $str .= '</a><br class="navbr" /></li>%s';
                $this->output .= sprintf($str, $k, $k, SID, strip_tags($v[1]), $selected, $numeration, $v[1], "\n");
            }
            // add image
            elseif($v[2] == 2){
                $str  = '<li><a class="navLink" href="../%s/%s.php?%s" title="%s">';
                $str .= '<dfn>%s</dfn><img src="../layout/%s/img/%s.gif" alt="%s" />';
                $str .= '</a><br class="navbr" /></li>%s';
                $this->output .= sprintf($str, $k, $k, SID, strip_tags($v[1]), $numeration, $this->skin, $k, $v[1], "\n");
            }

            // add text and image
            elseif($v[2] == 3){
                $str  = '<li><a class="navLink" href="../%s/%s.php?%s" title="%s">';
                $str .= '<dfn>%s</dfn><img src="../layout/%s/img/%s.gif" alt="%s" /> ';
                $str .= '<span class="navLink%s">%s</span>';
                $str .= '</a><br class="navbr" /></li>%s';
                $this->output .= sprintf($str, $k, $k, SID, strip_tags($v[1]), $numeration, $this->skin, $k, $v[1], $selected, $v[1], "\n");
            }
        }
    }
    /**
    * @param 
    * @return
    */
    function add_addons(){
        // check whether the addon directory exists at all
        $addons_dir = dirname(__FILE__).'/../addons/';
        if(file_exists($addons_dir)){
            // open the addon directory
            $fp = opendir($addons_dir);
            // read all objects in this dir, set the count of found addons to zero ...
            while($file = readdir($fp)){
                // but exclude links, index files, system files etc.
                if(is_dir($addons_dir.$file) and $file != 'CVS' and
                    $file != '.' and $file != '..' and !ereg('^_', $file) ){
                    $this->addons_entries[] = $file;
                }
            }
            closedir($fp);
        }
    }
    /**
    * @param 
    * @return
    */
    function render_addons(){
        if(!$this->addons_entries){
            return '';
        }
        $this->output .= $this->render_headline('Addons');
        $this->numeration['addons'] = 0;
        foreach($this->addons_entries as $addon){
            $numeration = '3.'.(++ $this->numeration['addons']) . ': ';
            if($this->is_active($addon)){
                // module is selected
                $selected = 'Selected';
            }
            else{
                // module is not selected
                $selected = '';
            }
            $sid="&amp;".SID;
            $str  = '<li><a class="navLink" href="../addons/addon.php?addon=%s%s" title="%s" target="_top">';
            $str .= '<span class="navLink%s"><dfn>%s</dfn>%s</span>';
            $str .= '</a><br class="navbr" /></li>%s';
            $this->output .= sprintf($str, $addon, $sid, ucfirst($addon), $selected, $numeration, ucfirst($addon), "\n");
        }
    }
    /**
    * @param 
    * @return
    */
    function add_controls(){
        foreach($this->controls as $control){
            // activated ?
            if(!$control[2]){
                continue;
            }
            switch($control[1]){
                // logged as
                case 'logged_as':
                    $this->control_entries[] = __('logged in as').': <br class="navbr" /><b>'.str_replace(',', ' ', slookup('users', 'vorname,nachname', 'ID', $this->actor_id))."</b>";
                    break;
                // search field
                case 'search_field':
                    $this->control_entries[] = '
                        <form action="../search/search.php" target="_top" style="display:inline">
                        <input class="navsearchbox" type="text" name="searchterm" id="searchterm" value="'.__('Search').'" onfocus="this.value=\'\'"/>
                        <input type="submit" class="navSearchButton" value="" />
                        <input type="hidden" name="searchformcount" value="" />
                        <input type="hidden" name="module" value="search" />
                        <input type="hidden" name="gebiet" value="all" />
                        </form>
                    ';
                    break;
                // group box
                case 'group_box':
                    $group_box = $this->get_group_box();
                    if(strlen($group_box)){
                        $this->control_entries[] = $group_box;
                    }
                    break;
                // settings
                case 'settings':
                    if($this->is_active('settings')){
                        // module is selected
                        $selected = 'Selected';
                    }
                    else{
                        // module is not selected
                        $selected = '';
                    }
                    $this->control_entries[] = '<a class="navLink" href="../settings/settings.php?'.SID.'" title="'.strip_tags(__('Settings')).'" target="_top">
                                                <span class="navLink'.$selected.'">'.__('Settings').'</span></a>';
                    break;
                // help
                case 'help':
                    // now the help routine
                    if(ereg($this->application_language, "de|en|es|nl|fr|tr|zh|fi")){
                        $help = __('Help');
                    }
                    else{
                        $help = __('?');
                    }
                    $this->control_entries[] = '<a class="navLink" href="'.get_helplink().'" title="'.strip_tags($help).'" target="_blank">
                                                <span class="navLink">'.$help.'</span></a>';
                    break;
                // admin
                case 'admin':
                    if (eregi("a", $this->actor_access)) {
                        $this->control_entries[] = '<a class="navLink" href="../index.php?module=admin&amp;'.SID.'" title="Admin" target="_top">
                                                    <span class="navLink">Admin</span></a>';
                    }
                    break;
                // logout
                case 'logout':
                    $this->control_entries[] = '<a class="navLink" href="../index.php?module=logout'.SID.'" title="'.strip_tags(__('Logout')).'" target="_top">
                                                <span class="navLink">'.__('Logout').'</span></a>';
                    break;
                // timecard buttons
                case 'timecard_buttons':
                    if (PHPR_TIMECARD and check_role('timecard') > 1) {
                        $mode    = $this->application_mode;
                        $action  = $this->application_action;
                        $sure    = $this->application_sure;
                        $view    = $this->application_view;
                        $today1 = date('Y-m-d', mktime(date('H') + PHPR_TIMEZONE, date('i'), date('s'), date('m'), date('d'), date('Y')));
                        $result1 = db_query("SELECT ID
                                               FROM ".DB_PREFIX."timecard
                                              WHERE datum = '$today1'
                                                AND (ende = '' OR ende IS NULL)
                                                AND users = '".$this->actor_id."'") or db_die();
                        $row1 = db_fetch_row($result1);
                        // buttons for 'come' and 'leave', alternate display
                        $just_timed_in  = ($mode == 'data' && $action == '1' && $sure == '1');
                        $just_timed_out = ($mode == 'data' && $action != '1' && $sure == '1');
                    
                        if (($row1[0] > 0 && !$just_timed_out) || $just_timed_in) {
                            $this->control_entries[] = get_buttons(array(array('type' => 'link', 'href' => '../timecard/timecard.php?mode=data&amp;view='.$view.'&amp;action=&amp;sure=1&amp;'.SID, 'text' => __('End'), 'stopwatch' => 'started'))).'<br/>';
                        }
                        else {
                            $this->control_entries[] = get_buttons(array(array('type' => 'link', 'href' => '../timecard/timecard.php?mode=data&amp;view='.$view.'&amp;action=1&amp;sure=1&amp;'.SID, 'text' => __('Begin'), 'stopwatch' => 'stopped'))).'<br/>'; 
                        }
                        // Projektzuweisung
                        $resultq = db_query("SELECT ID, div1, h, m
                                               FROM ".DB_PREFIX."timeproj
                                              WHERE users = '".$this->actor_id."'
                                                AND (div1 LIKE '".date("Ym")."%')") or db_die();
                        $rowq = db_fetch_row($resultq);
                        // buttons for 'come' and 'leave', alternate display
                        $just_clocked_out = ($mode == 'data' && $action == 'clock_out');
                        if ($rowq[0] > 0 && !$just_clocked_out && !$just_timed_out) {
                            $this->control_entries[] = get_buttons(array(array('type' => 'link', 'href' => '../timecard/timecard.php?mode=data&amp;view='.$view.'&amp;action=clock_out&amp;'.SID, 'text' => __('stop stop watch'), 'stopwatch' => 'started'))).'<br/>';
                        }
                        else {
                            $this->control_entries[] = get_buttons(array(array('type' => 'link', 'href' => '../timecard/timecard.php?mode=books&amp;view='.$view.'&amp;action=clockin&amp;'.SID, 'text' => str_replace('-', '', __('Project stop watch')), 'stopwatch' => 'stopped'))).'<br/>';
                        }
                    }
                    break;
            }
        }
    }
    /**
    * @param 
    * @return
    */
    function render_controls(){
        if(!$this->control_entries){
            return '';
        }
        $this->output .= $this->render_headline('Controls');
        $this->numeration['controls'] = 0;
        foreach($this->control_entries as $control){
            $numeration = '1.'.(++ $this->numeration['controls']) . ': ';
            $control = '<dfn>'.$numeration.'</dfn>'.$control;
            $str  = '<li>%s<br class="navbr" /></li>%s';
            $this->output .= sprintf($str, $control, "\n");
        }
    }
    /**
    * @param 
    * @return
    */
    function render_headline($headline){
        if(isset($this->config['show_headlines']) && $this->config['show_headlines']){
            return '<li><div style="font-weight:bold;background-color:#D6D6D6;width:92%;height:20px;line-height:20px;padding-left:0.5em;padding-top:2px;">'.__($headline).'</div></li>';
        }
        return '';
    }
    /**
    * @param 
    * @return
    */
    function sort_entries($a, $b){
        if($a[0] == $b[0]){
            return 0;
        }
        return ($a[0] < $b[0]) ? -1 : 1;
    }
    /**
    * @param 
    * @return
    */
    function draw(){
        echo '<div class="navi">';
        echo '<img id="logo" src="/'.PHPR_INSTALL_DIR.'layout/'.$this->skin.'/img/logo.png" alt=""  title=" PHProjekt '.PHPR_VERSION.' - '.str_replace(',', ' ', slookup('users', 'vorname,nachname', 'ID', $this->actor_id)).' "/><br class="navbr" /><br class="navbr" />';
        echo '<ul>';
        echo $this->output;
        echo '</ul>';
        echo '</div>';
    }
    /**
    * @param 
    * @return
    */
    function get_group_box() {    
        // determine whether this is the first or second from onthis page
        // -> must know this to get the onchange-js properly working
        if ($this->application_nav_searchbox) $form_nr = 1;
        else                $form_nr = '0';
    
        $out = '';
        $groups = array();
        // does a group with this user exists?
        $query = "SELECT grup_ID
                    FROM ".DB_PREFIX."grup_user
                   WHERE user_ID = '".$this->actor_id."'";
        $res = db_query($query) or db_die();
        while ($row = db_fetch_row($res)) {
            $groups[] = $row[0];
        }
        if (count($groups) > 0) {
            $query = "SELECT ID, kurz
                        FROM ".DB_PREFIX."gruppen
                       WHERE ID IN ('".implode("','", $groups)."')
                    ORDER BY kurz";
            $res = db_query($query) or db_die();
            $groups = array();
            while ($row = db_fetch_row($res)) {
                $groups[] = array( 'ID' => $row[0], 'kurz' => $row[1] );
            }
            $out .= '
                <label for="change_group" class="nav">'.__('Usergroup').':</label>
                <br class="navbr" />'."\n";

            if (count($groups) == 1) {
                $out .= '    <span id="change_group"><b>'.$groups[0]['kurz'].'</b></span>'."\n";
            }
            else {
                $out .= '
            <form name="grsel" action="../index.php" method="post" style="display:inline;">
            <input type="hidden" name="'.$this->application_module.'" value="'.$this->application_module.'" />
            <input type="hidden" name="'.$this->application_mode2.'"  value="'.$this->application_mode2.'" />
            '.(SID ? '<input type="hidden" name="'.session_name().'" value="'.session_id().'" />' : '').'
            <select class="groupselect" name="change_group" id="change_group" onchange="document.grsel.submit();">'."\n";

                foreach ($groups as $item) {
                    $out .= '        <option value="'.$item['ID'].'"'.
                            ($this->actor_user_group == $item['ID'] ? ' selected="selected"' : '').
                            '>'.$item['kurz']."</option>\n";
                }
                $out .= '
            </select>
            &nbsp;'.get_go_button('arrow_search', 'image').'
        </form>'."\n";
            }
        }
        return $out;
    }
    /**
    * @param 
    * @return
    */
    function is_active($k){
        return $this->application_module == $k or (isset($this->application_addon) && $this->application_addon == $k);
    }
    /**
    * @param 
    * @return
    */
    function set_skin($skin = 'default'){
        $skin = preg_replace('°\W+°', '', $skin);
        $file = dirname(__FILE__).'/../layout/'.$skin.'/'.$skin.'.inc.php';
        if(file_exists($file)){
            $this->skin = $skin;
        }
        else{
            $this->skin = 'default';
        }
    }
    /**
    * @param 
    * @return
    */
    function set_config_data(){
        $file = dirname(__FILE__).'/../layout/'.$this->skin.'/'.$this->skin.'.inc.php';
        include($file);
        $this->all_modules = $modules;
        $this->controls = $controls;
        $this->config = $config;
    }

}

$nav = new PHProjekt_Navigation();
$nav->set_skin($skin);
$nav->render();
$nav->draw();

?>