<?php

// $Id: contextmenu.inc.php,v 1.17.2.1 2005/08/17 13:31:27 fgraf Exp $

/**
 * Class contextmenu
 * @abstract provides contextmenus (mouse right click) for several occasions: list view, column header etc.
 * @package PHProjekt
 */
class contextmenu
{

    var $menucolID;
    var $menulistID;
    var $menusysID;

    /**
     * contextmenu for an entry in a list - mostly used if the whole line is referenced to the element
     * @access public
     * @return the whole html div
     */
    function menu_table($module, $listentries_single, $listentries_selected) {
        global $path_pre;
        // operations in all modules: modify, copy and delete
        $listmenu_start = array(
            // '0'=>array('doLink',$module.".php?mode=forms&amp;ID=",'','',$modify_it),
            //'1'=>array('doLink',$module.".php?mode=forms&amp;cop_b=1&amp;ID=",'','',__('Copy')),
        );
        // closing the table entries with 'select all' - 'deselect all'
        $listmenu_end = array(
            '0'=>array('selectAll()','&nbsp;'.__('Select all')),
            '1'=>array('deselectAll()','&nbsp;'.__('Deselect all'))
        );

        $this->menulistID = $this->create_menuID();
        $str .= $this->menu_start($this->menulistID,'-1450px','-2000px','100px','200px','200px',"<i><b id='recname'>xxx</b></i>");
        $str .= $this->menu_entries($listmenu_start);
        if ($this->menu_entries($listentries_single)) {
            $str .= $this->menu_line('<hr />');
            $str .= $this->menu_entries($listentries_single);
        }
        $str .= $this->menu_line('<hr />');
        $str .= $this->menu_line('<i><b>&nbsp;&nbsp;'.__('selected elements').'</b></i>');
        $str .= $this->menu_entries($listentries_selected);
        $str .= $this->menu_line('<hr />');
        $str .= $this->menu_script($listmenu_end);
        $str .= $this->menu_close();
        return $str;
    }


    /**
     * contextmenu for a column header of a table
     * @access public
     * @param string module name
     * @param string module to link
     * @param bool   related Object
     * @param bool  $is_addon true if called from a addon
     * @return the whole html div
     */

    function menu_columnheader($module, $link=null, $is_related_obj=false, $is_addon=false) {
        if (!$link) $link = $module;

        $width = array(
            '0'=>array("nop();' onmousedown='resizeImage(20,\"relative\")",' '.__('wider')),
            '1'=>array("nop();' onmousedown='resizeImage(-20,\"relative\")",' '.__('narrower')),
        );
        if($is_related_obj){
            global $ID;
            $direction = array(
                '0'=>array('doLink',basename($_SERVER['PHP_SELF'])."?mode=forms&amp;ID=".$ID."&amp;sort_module=".$module."&amp;direction_rel=ASC&amp;sort_col=",'','','&nbsp;'.__('ascending')),
                '1'=>array('doLink',basename($_SERVER['PHP_SELF'])."?mode=forms&amp;ID=".$ID."&amp;sort_module=".$module."&amp;direction_rel=DESC&amp;sort_col=",'','','&nbsp;'.__('descending'))
            );
        }
        else{
            $addon = $is_addon ? '&amp;addon='.$module : '';
            $direction = array(
                '0'=>array('doLink',$link.".php?mode=view&amp;sort_module=".$module.$addon."&amp;direction=ASC&amp;sort=",'','','&nbsp;'.__('ascending')),
                '1'=>array('doLink',$link.".php?mode=view&amp;sort_module=".$module.$addon."&amp;direction=DESC&amp;sort=",'','','&nbsp;'.__('descending'))
            );
            unset($addon);
        }
        $this->menucolID = $this->create_menuID();
        $str .= $this->menu_start($this->menucolID,'-350px','-2000px','100px','150px','80px',__('Column'));
        $str .= $this->menu_script($width);
        // doesn't work at the moment :-(
        // $str .= $this->set_width();
        $str .= $this->save_width();
        $str .= $this->menu_line('&nbsp;&nbsp;<b>'.__('Sorting').'</b>');
        $str .= $this->menu_entries($direction);
        $str .= $this->menu_close();
        return $str;
    }

    /**
     * contextmenu for actions concerning the list view of a module
     * @access public
     * @return the whole html div
     */
    function menu_page($module) {
        $page_actions = array(
            '1' => array('doLink',$module.".php?mode=view&amp;toggle_archive_flag=1",'','',show_archive_flag($module)),
            '2' => array('doLink',$module.".php?mode=view&amp;toggle_read_flag=1",'','',show_read_flag($module)),
            '3' => array('doLink',$module.".php?mode=view&amp;mode=view&amp;tree_mode=open&amp;",'','',"&nbsp;".__('Tree view').": ".__('open')),
            '4' => array('doLink',$module.".php?mode=view&amp;mode=view&amp;tree_mode=close&amp;",'','',"&nbsp;".__('Tree view').": ".__('closed'))
            );
        if (PHPR_SUPPORT_HTML) $page_actions[] = array('doLink',$module.".php?mode=view&amp;toggle_html_editor_flag=1",'','','&nbsp;'.show_html_editor_flag($module));
        $this->menusysID = $this->create_menuID();
        $str  = $this->menu_start($this->menusysID,'-450px','-2000px','100px','200px','80px',$module);
        $str .= $this->fulltextsearch();
        $str .= $this->menu_entries($page_actions);
        $str .= $this->menu_close();
        return $str;
    }

    //nur für den chat!;
    function menu_page_chat() {
        $module = 'chat';
        if (PHPR_SUPPORT_HTML) $page_actions[] = array('doLink',$module.".php?toggle_html_editor_flag=1",'','',show_html_editor_flag($module));
        $this->menusysID = $this->create_menuID();
        $str .= $this->menu_start($this->menusysID, '-450px', '-2000px', '2', '200px', '80px', $module);
        //$str .= $this->fulltextsearch();
        $str .= $this->menu_entries($page_actions);
        $str .= $this->menu_close();
        return $str;
    }

    /** creates a name for this menu
     * @access private
     * @return a uniques string for the menu name like menu1, menu2 etc.
     */
    function create_menuID() {
        static $name;
        if (!isset($name)) $name = 0;
        $name++;
        return 'menu'.$name;
    }

    /**
     * contextmenu for actions concerning the list view of a module
     * @access private
     * @return html part of the div
     */
    function menu_start($id, $top, $left, $z, $width, $height, $title) {
        $z = 99; // context menu should always overlay other layers
        $str = "
            <div id='".$id."' style='position:absolute;top:".$top.";left:".$left.";z-index:".$z.";width:".$width.";height:".$height.";'>
            <table class='contextmenu' cellpadding='0' cellspacing='0' width='".$width."'>
            <tr><td>&nbsp;&nbsp;<b>".$title."</b></td></tr>";
        return $str;
    }

    function menu_entries($entries) {
        $str = '';
        if ($entries) {
            foreach ($entries as $menuentry) {
                $str .= "<tr><td><a href='javascript:".$menuentry[0]."(\"".$menuentry[1]."\",\"".$menuentry[2]."\",\"".$menuentry[3]."\")'>&nbsp;&nbsp;".$menuentry[4]."</a>&nbsp;</td></tr>\n";
            }
        }
        return $str;
    }

    function menu_close() {
        $str = $this->menu_line('<hr />');
        $str .= "<tr><td><a class='menu' href='javascript:nop()' onmousedown='document.onmouseup=hideMenu'>&nbsp;&nbsp;".__('Close')."</a>&nbsp;</td></tr></table></div>\n";
        return $str;
    }

    function menu_line($string) {
        return   "<tr><td>".$string."</td></tr>";
    }

    function menu_script($actions) {
        foreach ($actions as $action) {
            $str .= "<tr><td><a class='menu' href='javascript:".$action[0]."'>&nbsp;".$action[1]."</a>&nbsp;</td></tr>\n";
        }
        return $str;
    }

    function save_width() {
        global $fields, $tdw, $module;
        $str .= "<tr><td><form method='post' action='".$module.".php' name='tdwfrm' onsubmit='return showsize()'>\n";
        $hidden = array('mode'=>'view','filter'=>$field_name,'rule'=>'like','perpage'=>$perpage,'page'=>$page,'save_tdwidth'=>1);
        if (SID) $hidden[session_name()] = session_id();
        $str .= hidden_fields($hidden);
        if (is_array($fields)) {
            foreach ($fields as $field_name => $field) {
                if ($field['list_pos'] > 0) $n_fields++;
            }
            foreach ($fields as $field_name => $field) {
                if (!$tdw[$module][$field_name] or $tdw[$module][$field_name] <> 94) $tdw[$field_name] = floor(100/$n_fields)*14;
                else $tdw[$field_name] = $tdw[$module][$field_name];
                $str .= "<input type='hidden' name='ii$field_name' value='".$tdw[$field_name]."' />\n";
            }
        }
        $str .= "&nbsp;&nbsp;<input type='submit' id='tr' value='".__('Save width')."' /></form></td></tr>\n";
        return $str;
    }

    function set_width() {
        global $fields, $tdw, $module;
        $str .= "<tr><td><form name='setwidth1' onsubmit=\"resizeImage(document.setwidth1.size.value,'absolut')\">\n";
        $str .= " &nbsp;". __('Width').": <input type='text' name='size' size='3' onfocus='document.onmouseup=nop;' /></form></td></tr>\n";
        return $str;

    }
    function fulltextsearch() {
        global $module;
        $str = "<tr><td><form action='../index.php' target='_top'><input type='hidden' name='module' value='search' />&nbsp;\n";
        $str .= "<label for='searchterm'>".__('Keyword Search')."</label> <input type='text' name='searchterm' size='14' value='".__('Search')."' onfocus='document.onmouseup=nop;' /><input type='hidden' name='gebiet' value='all' /></form></td></tr>\n";
        return $str;
    }
}


function show_read_flag($module) {

    //(isset($_SESSION['show_archive_elements'][$module]) and $_SESSION['show_read_elements']["$module"] > 0) ? $str = __('&nbsp;Show read elements') : $str = __('&nbsp;Hide read elements') ;
    ($_SESSION['show_read_elements']["$module"] > 0) ? $str = __('&nbsp;Show read elements') : $str = __('&nbsp;Hide read elements') ;
    return $str;
}


function show_archive_flag($module) {
    (isset($_SESSION['show_archive_elements'][$module]) and $_SESSION['show_archive_elements']["$module"] > 0) ? $str = __('&nbsp;Show archive elements') : $str = __('&nbsp;Hide archive elements');
    return $str;
}


function show_html_editor_flag($module) {
    (isset($_SESSION['show_html_editor'][$module]) and $_SESSION['show_html_editor']["$module"] > 0) ? $str = __('switch off html editor') : $str = __('switch on html editor');
    return $str;
}

function add_links2pagemenu($module,$path_pre) {
  global $listentries_selected;
  if (PHPR_LINKS) {
    $listentries_selected[] = array('proc_marked',$path_pre.'lib/set_links.inc.php?module='.$tablename[$module].'&amp;ID_s=','_blank','',__('Add to link list'));
  }
}

?>
