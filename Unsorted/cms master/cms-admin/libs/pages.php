<?php
//=============================================================================
//
//     CMS Master :: Content management system
//     File: /cms-admin/libs/pages.php
//     Version: 1.0
//     Created date: 28 September 2003
//     Modification date: 28 September 2003
//
//============================================================================
                                                                                     
class Pages {

    var $num_page=0;

    function delete_page($id) {
      global $CFG, $Db, $Base, $Lang_pages;
      if ($child_pages = $Db->fetch_array($Db->query("SELECT * FROM `cms_pages` WHERE `parent`=$id"))) {
          $Base->msg_js_show($Lang_pages->error_delete_page);
      } else {
          $del_page = $Db->fetch_array($Db->query("SELECT * FROM `cms_pages` WHERE `id`=$id"));
          $q_pages = $Db->query("SELECT * FROM `cms_pages` WHERE `id`<>0 AND `id`<>$id AND `parent`=$del_page[parent] ORDER BY `number`");
          $count_pages = $Db->num_rows($q_pages);
          if ($del_page["is_url_external"]==0) {
            $url = $this->build_url($del_page["name_url"], $del_page["parent"]);
            $this->delete_htaccess($url);
          }
          for($n=1; $n<=$count_pages; $n++) {
            $page = $Db->fetch_array($q_pages);
            $Db->query("UPDATE `cms_pages` SET `number`=$n WHERE `id`=$page[id]");
          }
          $Db->query("DELETE FROM `cms_pages` WHERE `id`=$del_page[id]");
          unlink("$CFG->dir_root/cms-pages/$del_page[id]");
          $Base->msg_js_show($Lang_pages->msg_delete_page_ok);
      }
    }

    function update_page($frm) {
      global $CFG, $Base, $Lang_pages;
      if (get_magic_quotes_runtime() || get_magic_quotes_gpc()) {
          $frm["content"] = stripslashes($frm["content"]);
      }
      $f = fopen("$CFG->dir_root/cms-pages/$frm[id]", "w+");
      fwrite($f, $frm["content"]);
      fclose($f);
      $Base->msg_js_show($Lang_pages->msg_update_page_ok);
    }

    function update_page_options($frm) {
      global $Lang_pages, $Db, $Base, $CFG;
      $q_page = $Db->query("SELECT * FROM `cms_pages` WHERE `id`=$frm[id]");
      $page = $Db->fetch_array($q_page);
      if ($frm["is_url_external"]==0 && $frm["id"]!=0) {
          $url_new = $this->build_url($frm["name_url"],$frm["parent"]);
          $url_old = $this->build_url($page["name_url"],$page["parent"]);
          if ($this->verify_url($url_new) && $url_new!=$url_old) {
            $Base->msg_js_show($Lang_pages->error_dublicated_url);
            $Base->go_back();
          } else {
            $this->delete_htaccess($url_old);
            $this->insert_htaccess($url_new);
          }
      }
      $sql = "UPDATE `cms_pages` SET 
          `name_menu`='$frm[name_menu]',
          `name_title`='$frm[name_title]',
          `name_page`='$frm[name_page]',
          `name_url`='$frm[name_url]',
          `name_url_external`='$frm[name_url_external]',
          `website_title`='$frm[website_title]',
          `website_keywords`='$frm[website_keywords]',
          `website_description`='$frm[website_description]',
          `is_visible`=$frm[is_visible],
          `is_url_external`=$frm[is_url_external],
          `redirect`=$frm[redirect] 
          WHERE `id`=$frm[id]
          ";
      $Db->query($sql);
      $Base->msg_js_show($Lang_pages->msg_update_page_options_ok);
    }

    function delete_htaccess($url) {
      $htaccess = $this->get_htaccess();
      $htaccess_url = $this->build_htaccess_url($url);
      $htaccess_url = preg_replace("/\//", "\/", $htaccess_url);
      $htaccess_url = preg_replace("/['^']/", "\^", $htaccess_url);
      $htaccess_url = preg_replace("/['$']/", "\\\\$", $htaccess_url);
      $htaccess_url = "/".$htaccess_url."/";
      $htaccess = preg_replace($htaccess_url,"",$htaccess);
      $this->write_htaccess($htaccess);
    }
        
    function edit_page_options($id) {
      global $CFG, $Lang_pages, $Lang, $Db;
      $sql = "SELECT * FROM `cms_pages` WHERE `id`=$id";
      $q_page = $Db->query($sql);
      $frm = $Db->fetch_array($q_page);
      $frm["mode"] = "update_page_options";
      $frm["header"] = $Lang_pages->header_edit_page_option . ": " . $frm["name_menu"];
      $frm["button"] = $Lang_pages->button_update;
      $frm["name_menu"] = htmlspecialchars($frm["name_menu"], ENT_QUOTES);
      $frm["name_title"] = htmlspecialchars($frm["name_title"], ENT_QUOTES);
      $frm["name_page"] = htmlspecialchars($frm["name_page"], ENT_QUOTES);
      $frm["website_title"] = htmlspecialchars($frm["website_title"], ENT_QUOTES);
      $frm["website_keywords"] = htmlspecialchars($frm["website_keywords"], ENT_QUOTES);
      include("$CFG->dir_admin_templates/pages-form.php");
    }

    function move_page_down($id) {
      global $Db, $Lang_pages, $Base;
      $page_move = $Db->fetch_array($Db->query("SELECT * FROM `cms_pages` WHERE id=$id"));
      $page_next = $Db->fetch_array($Db->query("SELECT * FROM `cms_pages` WHERE `parent`=$page_move[parent] AND `number`=$page_move[number]+1"));
      if (!empty($page_next["number"])) {
          $Db->query("UPDATE `cms_pages` SET `number`=$page_move[number]+1 WHERE id=$page_move[id]");
          $Db->query("UPDATE `cms_pages` SET `number`=$page_next[number]-1 WHERE id=$page_next[id]");
          $Base->msg_js_show($Lang_pages->msg_move_page_down_ok);
      }
    }

    function move_page_up($id) {
      global $Db, $Lang_pages, $Base;
      $page_move = $Db->fetch_array($Db->query("SELECT * FROM `cms_pages` WHERE id=$id"));
      if ($page_move["number"]!=1) {
          $page_prev = $Db->fetch_array($Db->query("SELECT * FROM `cms_pages` WHERE `parent`=$page_move[parent] AND `number`=$page_move[number]-1"));
          $Db->query("UPDATE `cms_pages` SET `number`=$page_move[number]-1 WHERE id=$page_move[id]");
          $Db->query("UPDATE `cms_pages` SET `number`=$page_prev[number]+1 WHERE id=$page_prev[id]");
          $Base->msg_js_show($Lang_pages->msg_move_page_up_ok);
      }
    }

    function insert_new_page($frm) {
      global $Db, $Base, $Lang_pages, $CFG;
      if ($frm["is_url_external"]==0) {
          $url = $this->build_url($frm["name_url"],$frm["parent"]);
          if ($this->verify_url($url)) {
            $Base->msg_js_show($Lang_pages->error_dublicated_url);
            $Base->go_back();
          } else {
            $this->insert_htaccess($url);
          }
      }
      $prev_page = $Db->fetch_array($Db->query("SELECT number FROM `cms_pages` WHERE `parent`=$frm[parent] ORDER BY `number` DESC"));
      if (empty($prev_page["number"])) { $frm["number"] = 1; } else { $frm["number"] = $prev_page["number"]+1; }
      $parent_page = $Db->fetch_array($Db->query("SELECT * FROM `cms_pages` WHERE id=$frm[parent]"));
      if ($parent_page["id"]==0) { $frm["level"] = 1; } else { $frm["level"] = $parent_page["level"] + 1; }
      $sql = "INSERT INTO `cms_pages`(
          `name_menu`,
          `name_title`,
          `name_page`,
          `name_url`,
          `name_url_external`,
          `website_title`,
          `website_keywords`,
          `website_description`,
          `is_visible`,
          `is_url_external`,
          `redirect`,
          `parent`,
          `number`,
          `level`
          ) VALUES(
          '$frm[name_menu]',
          '$frm[name_title]',
          '$frm[name_page]',
          '$frm[name_url]',
          '$frm[name_url_external]',
          '$frm[website_title]',
          '$frm[website_keywords]',
          '$frm[website_description]',
          $frm[is_visible],
          $frm[is_url_external],
          $frm[redirect],
          $frm[parent],
          $frm[number],
          $frm[level]
          )";
      $Db->query($sql);
      $page_file_name = $Db->insert_id();
      $f = fopen("$CFG->dir_root/cms-pages/$page_file_name", "w+");
      fwrite($f, "");
      fclose($f);
      $Base->msg_js_show($Lang_pages->msg_add_new_page_ok);
    }

    function get_htaccess() {
      global $CFG;
      $f = fopen("$CFG->dir_root/.htaccess", "r");
      $htaccess = fread($f, filesize("$CFG->dir_root/.htaccess"));
      fclose($f);
      return $htaccess;
    }

    function insert_htaccess($url) {
      $htaccess = $this->get_htaccess();
      $htaccess_url = $this->build_htaccess_url($url);
      $htaccess .= $htaccess_url;
      $this->write_htaccess($htaccess);
    }

    function write_htaccess($htaccess) {
      global $CFG;
      $f = fopen("$CFG->dir_root/.htaccess", "w+");
      fwrite($f, $htaccess);
      fclose($f);
    }

    function build_htaccess_url($url) {
      $htaccess_url = "\n";
      $htaccess_url .= "RewriteRule ^$url$ cms-content.php\n";
      $htaccess_url .= "RewriteRule ^$url/$ cms-content.php\n";
      return $htaccess_url;
    }

    function verify_url($url) {
      $htaccess = $this->get_htaccess();
      $htaccess_url = $this->build_htaccess_url($url);
      if (strstr($htaccess,$htaccess_url)) {
          return true;
      } else {
          return false;
      }
    }

    function build_url($page_url, $page_parent) {
      global $Db;
      $pages_url[0] = $page_url; $n=1; $cur_parent = $page_parent;
      while($qpage = $Db->query("SELECT * FROM `cms_pages` WHERE `id`=$cur_parent")) {
          $page = $Db->fetch_array($qpage);
          if ($page["id"]==0) { break; }
          $pages_url[$n] = $page["name_url"];
          $n++;
          $cur_parent = $page["parent"];
      }
      $url = "";
      for ($n=$n-1; $n>=0; $n--) { $url .= $pages_url[$n]."/"; }
      $url = preg_replace("/\/$/","",$url);
      return $url;
    }

    function get_page_list() {
      global $Db;
      $this->build_page_tree($page);
      $sql = "SELECT * FROM `cms_pages` WHERE `id`=0";
      $q_homepage = $Db->query($sql);
      $all_pages[0]["page"] = $Db->fetch_array($q_homepage);
      for($n=0; $n<count($page); $n++) {
          $all_pages[$n+1]["page"] = $page[$n]["page"];
      }
      return $all_pages;
    }

    function build_page_tree(&$output, $parent=0, $indent="") {
      global $Db;
      $sql = "SELECT * FROM `cms_pages` WHERE `parent`=$parent AND `id`<>0 ORDER BY `parent`,`number`";
      $q_page = $Db->query($sql);
      while($page=$Db->fetch_array($q_page)) {
          $output[$this->num_page]["page"] = $page;
          $this->num_page++;
          if ($page["id"]!=$parent) {
            $this->build_page_tree($output, $page["id"], $indent."&nbsp;&nbsp;");
          }
      }
    }
    
    function add_new_page() {
      global $CFG, $Lang_pages, $Lang, $Db;
      $frm["id"] = "-1";
      $frm["name_menu"] = "";
      $frm["name_title"] = "";
      $frm["name_page"] = "";
      $frm["name_url"] = "";
      $frm["name_url_external"] = "";
      $frm["website_title"] = "";
      $frm["website_keywords"] = "";
      $frm["website_description"] = "";
      $frm["is_visible"] = 1;
      $frm["is_url_external"] = 0;
      $frm["redirect"] = -1;
      $frm["parent"] = 0;
      $frm["mode"] = "insert_new_page";
      $frm["header"] = $Lang_pages->header_add_new_page;
      $frm["button"] = $Lang_pages->button_add;
      include("$CFG->dir_admin_templates/pages-form.php");
    }

    function print_page_list($page_tree) {
      global $Lang_pages, $CFG;
      $count_pages = count($page_tree)-1;
      include("$CFG->dir_admin_templates/pages-list.php");
    }    
}

?>