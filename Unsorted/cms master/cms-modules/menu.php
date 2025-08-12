<?php
//=============================================================================
//
//     CMS Master :: Content management system
//     File: /cms-modules/menu.php
//     Version: 1.0
//     Created date: 28 September 2003
//     Modification date: 28 September 2003
//
//============================================================================

$ModuleMenu = new Menu();

$CMS_BLOCKS["CMS-MENU-1"] = $ModuleMenu->menu1($page);
$CMS_BLOCKS["CMS-MENU-2"] = $ModuleMenu->menu2($page);

class Menu {

    function menu1($cur_page) {
      global $Db, $CFG;
        
      $menu_level = 1;
      $f = fopen("$CFG->dir_root/cms-templates/modules/menu/menu1", "r");
      $menu_start = fgets($f);
      $menu_item_notactive = fgets($f);
      $menu_item_active = fgets($f);
      $menu_end = fgets($f);
      $menu_empty = fgets($f);
      fclose($f);
        
      if ($cur_page["id"]==0) { 
          if ($menu_level==1) {
            $qpage = $Db->query("SELECT * FROM `cms_pages` WHERE parent=0 AND is_visible=1 ORDER BY number");
            $active_item = 0;
          } else {
            $qpage = $Db->query("SELECT * FROM `cms_pages` WHERE 1=2");
          }
      } else { 
          $cur_level = $cur_page["level"]+1;
          if ($cur_level == $menu_level) {
            $qpage = $Db->query("SELECT * FROM `cms_pages` WHERE parent=$cur_page[id] AND level=$cur_level AND is_visible=1 ORDER by number");
            $active_item = "";
          } else {
            $active_menu_item = $this->get_active_menu_item($cur_page["id"], $cur_page["parent"], $menu_level);
            $active_menu_item_page = $Db->fetch_array($Db->query("SELECT * FROM `cms_pages` WHERE id=$active_menu_item"));
            $qpage = $Db->query("SELECT * FROM `cms_pages` WHERE parent=$active_menu_item_page[parent] AND is_visible=1 ORDER by number");
            $active_item = $active_menu_item;
          }
      }

      $menu = $menu_start;
      while($page=$Db->fetch_array($qpage)) {
          if ($active_item==$page["id"]) {
            $item = $menu_item_active;
          } else {
            $item = $menu_item_notactive;
          }
          if ($page["is_url_external"]==1) {
            $url = $page["name_url_external"];
          } else {
            $url = "/".build_url($page["name_url"],$page["parent"]);
          }
          $item = preg_replace("/MENU_URL/",$url,$item);
          $item = preg_replace("/MENU_TITLE/",htmlspecialchars($page["name_page"],ENT_QUOTES),$item);
          $item = preg_replace("/MENU_NAME/",htmlspecialchars($page["name_menu"],ENT_QUOTES),$item);
          $menu .= $item;
      }
      $menu .= $menu_end;
      if ($Db->num_rows($qpage)==0) { $menu = ""; }
      return $menu;
    }


    function menu2($cur_page) {
      global $Db, $CFG;
        
      $menu_level = 2;
      $f = fopen("$CFG->dir_root/cms-templates/modules/menu/menu2", "r");
      $menu_start = fgets($f);
      $menu_item_notactive = fgets($f);
      $menu_item_active = fgets($f);
      $menu_end = fgets($f);
      $menu_empty = fgets($f);
      fclose($f);

      if ($cur_page["id"]==0) { 
          if ($menu_level==1) {
            $qpage = $Db->query("SELECT * FROM `cms_pages` WHERE parent=0 AND is_visible=1 ORDER BY number");
            $active_item = 0;
          } else {
            $qpage = $Db->query("SELECT * FROM `cms_pages` WHERE 1=2");
          }
      } else { 
          $cur_level = $cur_page["level"]+1;
          if ($cur_level == $menu_level) {
            $qpage = $Db->query("SELECT * FROM `cms_pages` WHERE parent=$cur_page[id] AND level=$cur_level AND is_visible=1 ORDER by number");
            $active_item = "";
          } else {
            $active_menu_item = $this->get_active_menu_item($cur_page["id"], $cur_page["parent"], $menu_level);
            $active_menu_item_page = $Db->fetch_array($Db->query("SELECT * FROM `cms_pages` WHERE id=$active_menu_item"));
            $qpage = $Db->query("SELECT * FROM `cms_pages` WHERE parent=$active_menu_item_page[parent] AND is_visible=1 ORDER by number");
            $active_item = $active_menu_item;
          }
      }

      $menu = $menu_start;
      while($page=$Db->fetch_array($qpage)) {
          if ($active_item==$page["id"]) {
            $item = $menu_item_active;
          } else {
            $item = $menu_item_notactive;
          }
          if ($page["is_url_external"]==1) {
            $url = $page["name_url_external"];
          } else {
            $url = "/".build_url($page["name_url"],$page["parent"]);
          }
          $item = preg_replace("/MENU_URL/",$url,$item);
          $item = preg_replace("/MENU_TITLE/",htmlspecialchars($page["name_page"],ENT_QUOTES),$item);
          $item = preg_replace("/MENU_NAME/",htmlspecialchars($page["name_menu"],ENT_QUOTES),$item);
          $menu .= $item;
      }
      $menu .= $menu_end;
      if ($Db->num_rows($qpage)==0) { $menu = ""; }
      return $menu;
    }

    function get_active_menu_item($page_id, $page_parent, $level) {
      global $Db;
      $pages[0]["id"] = $page_id; $n=1; $cur_parent = $page_parent;
      while($qpage = $Db->query("SELECT id, name_url, parent FROM `cms_pages` WHERE `id`=$cur_parent")) {
          $page = $Db->fetch_array($qpage);
          if ($page["id"]==0) { $pages[$n]=$page; $n++; break; }
          $cur_parent = $page["parent"];
          $pages[$n] = $page;
          $n++;
      }
      return $pages[$n-$level-1]["id"];
    }

}

?>