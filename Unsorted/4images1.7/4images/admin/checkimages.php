<?php
/**************************************************************************
 *                                                                        *
 *    4images - A Web Based Image Gallery Management System               *
 *    ----------------------------------------------------------------    *
 *                                                                        *
 *             File: checkimages.php                                      *
 *        Copyright: (C) 2002 Jan Sorgalla                                *
 *            Email: jan@4homepages.de                                    *
 *              Web: http://www.4homepages.de                             *
 *    Scriptversion: 1.7                                                  *
 *                                                                        *
 *    Never released without support from: Nicky (http://www.nicky.net)   *
 *                                                                        *
 **************************************************************************
 *                                                                        *
 *    Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-       *
 *    bedingungen (Lizenz.txt) für weitere Informationen.                 *
 *    ---------------------------------------------------------------     *
 *    This script is NOT freeware! Please read the Copyright Notice       *
 *    (Licence.txt) for further information.                              *
 *                                                                        *
 *************************************************************************/

define('IN_CP', 1);
define('ROOT_PATH', './../');
require('admin_global.php');

include(ROOT_PATH.'includes/search_utils.php');

if ($action == "") {
  $action = "checkimages";
}

show_admin_header();

if ($action == "savenewimages") {
  @include(ROOT_PATH."includes/db_field_definitions.php");

  $date = time();
  $error = array();
  $num_newimages = $HTTP_POST_VARS['num_newimages'];
  $detailed = $HTTP_POST_VARS['detailed'];

  for ($i = 1; $i <= $num_newimages; $i++) {
    $addimage = (isset($HTTP_POST_VARS['addimage_'.$i]) && $HTTP_POST_VARS['addimage_'.$i] == 1) ? 1 : 0;
    if ($addimage) {
      $image_name = trim($HTTP_POST_VARS['image_name_'.$i]);
      $cat_id = intval($HTTP_POST_VARS['cat_id_'.$i]);
      $image_download_url = (isset($HTTP_POST_VARS['image_download_url_'.$i])) ? trim($HTTP_POST_VARS['image_download_url_'.$i]) : "";

      if ($image_name == "") {
        $error['image_name_'.$i] = 1;
      }
      if ($cat_id == 0) {
        $error['cat_id_'.$i] = 1;
      }
      if ($image_download_url != "" && !is_remote($image_download_url) && !is_local_file($image_download_url)) {
        $error['image_download_url_'.$i] = 1;
      }

      if (!empty($additional_image_fields)) {
        foreach ($additional_image_fields as $key => $val) {
          if (isset($HTTP_POST_VARS[$key.'_'.$i]) && intval($val[2]) == 1 && trim($HTTP_POST_VARS[$key.'_'.$i]) == "") {
            $error[$key.'_'.$i] = 1;
          }
        }
      }
    }
  }
  if (empty($error)) {
    $log = array();
    for ($i = 1; $i <= $num_newimages; $i++) {
      $addimage = (isset($HTTP_POST_VARS['addimage_'.$i]) && $HTTP_POST_VARS['addimage_'.$i] == 1) ? 1 : 0;
      if ($addimage) {
        $image_media_file = trim($HTTP_POST_VARS['image_media_file_'.$i]);
        $image_thumb_file = trim($HTTP_POST_VARS['image_thumb_file_'.$i]);
        $image_name = trim($HTTP_POST_VARS['image_name_'.$i]);

        $cat_id = intval($HTTP_POST_VARS['cat_id_'.$i]);
        $old_cat_id = intval($HTTP_POST_VARS['old_cat_id_'.$i]);
        
        $user_id = (isset($HTTP_POST_VARS['user_id_'.$i]) && intval($HTTP_POST_VARS['user_id_'.$i]) != 0) ? intval($HTTP_POST_VARS['user_id_'.$i]) : $user_info['user_id'];

        $image_description = (isset($HTTP_POST_VARS['image_description_'.$i])) ? trim($HTTP_POST_VARS['image_description_'.$i]) : "";
        $image_date = (!isset($HTTP_POST_VARS['image_date_'.$i])) ? time() : ((trim($HTTP_POST_VARS['image_date_'.$i] != "")) ? "UNIX_TIMESTAMP('".trim($HTTP_POST_VARS['image_date_'.$i])."')" : time());
        $image_download_url = (isset($HTTP_POST_VARS['image_download_url_'.$i])) ? trim($HTTP_POST_VARS['image_download_url_'.$i]) : "";

        if (isset($HTTP_POST_VARS['image_keywords_'.$i])) {
          $image_keywords = trim($HTTP_POST_VARS['image_keywords_'.$i]);
          $image_keywords = preg_replace("/[\n\r]/is", " ", $image_keywords);
          $image_keywords = str_replace(","," ",$image_keywords);
          $image_keywords = ereg_replace("( ){2,}", " ", $image_keywords);
        }
        else {
          $image_keywords = "";
        }
        $image_active = intval($HTTP_POST_VARS['image_active_'.$i]);
        $image_allow_comments = intval($HTTP_POST_VARS['image_allow_comments_'.$i]);

        $additional_field_sql = "";
        $additional_value_sql = "";
        if (!empty($additional_image_fields)) {
          $table_fields = $site_db->get_table_fields(IMAGES_TABLE);
          foreach ($additional_image_fields as $key => $val) {
            if (isset($HTTP_POST_VARS[$key.'_'.$i]) && isset($table_fields[$key])) {
              $additional_field_sql .= ", $key";
              $additional_value_sql .= ", '".un_htmlspecialchars(trim($HTTP_POST_VARS[$key.'_'.$i]))."'";
            }
          }
        }

        if ($cat_id != $old_cat_id) {
          $image_media_file = copy_media($image_media_file, $old_cat_id, $cat_id);
          $image_thumb_file = copy_thumbnail($image_media_file, $image_thumb_file, $old_cat_id, $cat_id);
        }

        $sql = "INSERT INTO ".IMAGES_TABLE." 
                (cat_id, user_id, image_name, image_description, image_keywords, image_date, image_active, image_media_file, image_thumb_file, image_download_url, image_allow_comments".$additional_field_sql.") 
                VALUES 
                ($cat_id, $user_id, '$image_name', '$image_description', '$image_keywords', $image_date, $image_active, '$image_media_file', '$image_thumb_file', '$image_download_url', $image_allow_comments".$additional_value_sql.")";
        $result = $site_db->query($sql);
        $image_id = $site_db->get_insert_id();

        if ($result) {
          $search_words = array();
          foreach ($search_match_fields as $image_column => $match_column) {
            if (isset($HTTP_POST_VARS[$image_column.'_'.$i])) {
              $search_words[$image_column] = stripslashes($HTTP_POST_VARS[$image_column.'_'.$i]);
            }
          }
          add_searchwords($image_id, $search_words);
          $log[] = $lang['image_add_success'].": <b>".stripslashes($image_name)."</b> ($image_media_file)";
        }
        else {
          $log[] = $lang['image_add_error'].": <b>$image_media_file</b>";
        }
      }
    }
    show_table_header($lang['nav_images_check'], 1);
    echo "<tr>\n<td class=\"tablerow\">\n";
    echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\"><tr><td>&nbsp;</td><td>\n";
    if (!empty($log)) {
      foreach ($log as $val) {
        echo $val."<br />";
      }
    }
    else {
      echo $lang['no_newimages_added'];
    }
    echo "</td>\n</tr>\n</table>\n";
    echo "</td>\n</tr>\n";
    show_table_footer();
  }
  else {
    $msg = sprintf("<span class=\"marktext\">%s</span>", $lang['lostfield_error']);
    $action = "checkimages";
  }
}

if ($action == "checkimages") {
  $cat_id = (isset($HTTP_POST_VARS['cat_id'])) ? intval($HTTP_POST_VARS['cat_id']) : 0;
  $detailed_checked = (isset($HTTP_POST_VARS['detailed']) && $HTTP_POST_VARS['detailed'] == 1) ? " checked=\"checked\"" : "";
  $num_newimages = (isset($HTTP_POST_VARS['num_newimages'])) ? intval($HTTP_POST_VARS['num_newimages']) : 30;
  if ($num_newimages == "" || !$num_newimages) {
    $num_newimages = 30;
  }
  show_form_header("checkimages.php", "checkimages");
  show_table_header($lang['nav_images_check'], 2);
  $desc = get_category_dropdown($cat_id, 0, 4);
  $desc .= "&nbsp;&nbsp;&nbsp;&nbsp;".$lang['num_newimages_desc']."<input type=\"text\" name=\"num_newimages\" value=\"".$num_newimages."\" size=\"5\">";
  $desc .= "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"detailed\" value=\"1\"".$detailed_checked.">".$lang['detailed_version'];
  show_custom_row($desc, "<input type=\"submit\" value=\"".$lang['nav_images_check']."\" class=\"button\">");
  show_table_footer();
  echo "</form>";
}

if (isset($HTTP_POST_VARS['action']) && $action == "checkimages") {
  if (isset($HTTP_POST_VARS['detailed']) && $HTTP_POST_VARS['detailed'] == 1) {
    $detailed = 1;
    $colspan = 2;
  }
  else {
    $detailed = 0;
    $colspan = 6;
  }

  $cat_id = (isset($HTTP_POST_VARS['cat_id'])) ? intval($HTTP_POST_VARS['cat_id']) : 0;
  $cat_path = ($cat_id == 0) ? "" : "/".$cat_id;

  $handle = opendir(MEDIA_PATH.$cat_path);
  $image_list_all = array();
  while ($file = @readdir($handle)) {
    if ($file != "." && $file != "..") {
      if (check_media_type($file)) {
        $image_list_all[] = $file;
      }
    }
  }
  sort($image_list_all);

  $where_sql = ($cat_id != 0) ? "WHERE cat_id = $cat_id" : "";
  $sql = "SELECT image_media_file 
          FROM ".IMAGES_TABLE." 
          $where_sql 
          ORDER BY image_media_file";
  $result = $site_db->query($sql);

  $image_list_sql = array();
  while ($row = $site_db->fetch_array($result)) {
    $image_list_sql[] = $row['image_media_file'];
  }
  sort($image_list_sql);

  $image_list = array();
  $image_counter = 0;
  for ($i = 0; $i < count($image_list_all); $i++) {
    if (!in_array($image_list_all[$i], $image_list_sql)) {
      $image_list[] = $image_list_all[$i];
      $image_counter++;
    }
    if ($image_counter == $num_newimages) {
      break;
    }
  }
  sort($image_list);

  $num_all_newimages = sizeof($image_list);

  if ($num_newimages > $num_all_newimages) {
    $num_newimages = $num_all_newimages;
  }

  if ($msg != "") {
    printf("<b>%s</b>\n", $msg);
  }

  printf ("<p>%s</p>\n", preg_replace("/".$site_template->start."num_all_newimages".$site_template->end."/siU", $num_all_newimages, $lang['checkimages_note']));

  show_form_header("checkimages.php", "savenewimages", "form");
  show_hidden_input("cat_id", $cat_id);
  show_table_header($lang['nav_images_check'], $colspan);

  if ($num_all_newimages) {
    show_description_row("<input name=\"allbox\" type=\"checkbox\" onClick=\"CheckAll();\" checked=\"checked\" /> <b>".$lang['check_all']."</b>", $colspan);
  }
  else {
    show_description_row($lang['no_newimages'], $colspan);
  }

  for ($i = 1; $i <= $num_newimages; $i++) {
    //Check Image
    $file_type = get_file_extension($image_list[$i-1]);
    $image_name = get_file_name($image_list[$i-1]);

    $thumb_file = 0;
    if (file_exists(THUMB_PATH.$cat_path."/".$image_name.".jpg")) {
      $thumb_file = $image_name.".jpg";
    }
    elseif (file_exists(THUMB_PATH.$cat_path."/".$image_name.".JPG")) {
      $thumb_file = $image_name.".JPG";
    }
    elseif (file_exists(THUMB_PATH.$cat_path."/".$image_name.".jpeg")) {
      $thumb_file = $image_name.".jpeg";
    }
    elseif (file_exists(THUMB_PATH.$cat_path."/".$image_name.".JPEG")) {
      $thumb_file = $image_name.".JPEG";
    }
    elseif (file_exists(THUMB_PATH.$cat_path."/".$image_name.".gif")) {
      $thumb_file = $image_name.".gif";
    }
    elseif (file_exists(THUMB_PATH.$cat_path."/".$image_name.".GIF")) {
      $thumb_file = $image_name.".GIF";
    }
    elseif (file_exists(THUMB_PATH.$cat_path."/".$image_name.".png")) {
      $thumb_file = $image_name.".png";
    }
    elseif (file_exists(THUMB_PATH.$cat_path."/".$image_name.".PNG")) {
      $thumb_file = $image_name.".PNG";
    }

    $checked = (isset($HTTP_POST_VARS['image_name_'.$i]) && (!isset($HTTP_POST_VARS['addimage_'.$i]) || $HTTP_POST_VARS['addimage_'.$i] != 1)) ? "" : " checked=\"checked\"";

    if ($detailed) {
      show_table_separator("<input type=\"checkbox\" name=\"addimage_".$i."\" value=\"1\"".$checked." /> ".$image_list[$i-1], 2);
      if ($file_type == "gif" || $file_type == "jpg"  || $file_type == "png") {
        $file_src = MEDIA_PATH.$cat_path."/".$image_list[$i-1];
        show_image_row($lang['image'], $file_src, 1);
      }
      else {
        show_image_row($lang['image'], ICON_PATH."/".$file_type.".gif", 1);
      }
      show_hidden_input("image_media_file_".$i, $image_list[$i-1]);

      if ($thumb_file) {
        show_image_row($lang['thumb'], THUMB_PATH.$cat_path."/".$thumb_file, 1, "", 50);
        show_hidden_input("image_thumb_file_".$i, $thumb_file);
      }
      else {
        show_custom_row($lang['thumb'], $lang['no_thumb_newimages_ext']);
        show_hidden_input("image_thumb_file_".$i, "");
      }
      show_input_row($lang['field_download_url'].$lang['download_url_desc'], "image_download_url_".$i, "", $textinput_size);

      $image_name = (isset($error['image_name_'.$i])) ? $HTTP_POST_VARS['image_name_'.$i] : str_replace("_"," ", $image_name);
      $title = $lang['field_image_name'].((isset($file_src)) ? get_iptc_insert_link($file_src, "object_name", "image_name_".$i, 0) : "");
      show_input_row($title, "image_name_".$i, $image_name, $textinput_size);

      $title = $lang['field_description_ext'].((isset($file_src)) ? get_iptc_insert_link($file_src, "caption", "image_description_".$i) : "");
      show_textarea_row($title, "image_description_".$i, "", $textarea_size);

      $title = $lang['field_keywords_ext'].((isset($file_src)) ? get_iptc_insert_link($file_src, "keyword", "image_keywords_".$i) : "");
      show_textarea_row($title, "image_keywords_".$i, "", $textarea_size);

      show_cat_select_row($lang['field_category'], $cat_id, 3, $i);
      show_user_select_row($lang['user'], $user_info['user_id'], $i);

      $date = date("Y-m-d H:i:s", time());
      $title = $lang['field_date'].$lang['date_desc'].$lang['date_format'].((isset($file_src)) ? get_iptc_insert_link($file_src, "date_created", "image_date_".$i, 0) : "");
      show_input_row($title, "image_date_".$i, $date, $textinput_size);

      show_hidden_input("old_cat_id_".$i, $cat_id);
      show_radio_row($lang['field_free'], "image_active_".$i, 1);
      show_radio_row($lang['field_allow_comments'], "image_allow_comments_".$i, 1);
      show_additional_fields("image", array(), IMAGES_TABLE, $i);
    }
    else {
      echo "<tr class=".get_row_bg().">\n";
      echo "<td><input type=\"checkbox\" name=\"addimage_".$i."\" value=\"1\"$checked></td>\n";
      $link = "<a href=\"".MEDIA_PATH.$cat_path."/".$image_list[$i-1]."\" target=\"_blank\">".$image_list[$i-1]."</a>";
      show_hidden_input("image_media_file_".$i, $image_list[$i-1]);
      if ($thumb_file) {
        $thumb_status = $lang['thumb_newimages_exists'];
        show_hidden_input("image_thumb_file_".$i, $thumb_file);
      }
      else {
        $thumb_status = $lang['no_thumb_newimages'];
        show_hidden_input("image_thumb_file_".$i, "");
      }
      echo "<td><b>".$link."</b><br />-&raquo; ".$thumb_status."</td>\n";


      if (isset($error['image_name_'.$i])) {
        $lang['field_image_name'] = sprintf("<span class=\"marktext\">%s</span>", $lang['field_image_name']);
        $image_name = $HTTP_POST_VARS['image_name_'.$i];
      }
      else {
        $image_name = str_replace("_"," ", $image_name);
      }
      echo "<td>".$lang['field_image_name'].":<br /><input type=\"text\" name=\"image_name_".$i."\" value=\"".$image_name."\">\n";
      if (isset($error['cat_id_'.$i])) {
        $lang['field_category'] = sprintf("<span class=\"marktext\">%s</span>", $lang['field_category']);
      }
      $cat_id = (isset($HTTP_POST_VARS['cat_id_'.$i])) ? intval($HTTP_POST_VARS['cat_id_'.$i]) : $cat_id;
      echo "<td>".$lang['field_category'].":<br />".get_category_dropdown($cat_id, 0, 3, $i)."</td>\n";
      show_hidden_input("old_cat_id_".$i, $cat_id);

      echo "<td>".$lang['field_free'].":<br />";
      if (isset($HTTP_POST_VARS['image_active_'.$i]) && $HTTP_POST_VARS['image_active_'.$i] == 0) {
        $c1 = "";
        $c2 = " checked=\"checked\"";
      }
      else {
        $c1 = " checked=\"checked\"";
        $c2 = "";
      }
      echo "<input type=\"radio\" name=\"image_active_".$i."\" value=\"1\"".$c1."> ".$lang['yes']."&nbsp;&nbsp;&nbsp;\n";
      echo "<input type=\"radio\" name=\"image_active_".$i."\" value=\"0\"".$c2."> ".$lang['no']." ";
      echo "</td>\n";

      echo "<td>".$lang['field_allow_comments'].":<br />";
      if (isset($HTTP_POST_VARS['image_allow_comments_'.$i]) && $HTTP_POST_VARS['image_allow_comments_'.$i] == 0) {
        $c1 = "";
        $c2 = " checked=\"checked\"";
      }
      else {
        $c1 = " checked=\"checked\"";
        $c2 = "";
      }
      echo "<input type=\"radio\" name=\"image_allow_comments_".$i."\" value=\"1\"".$c1."> ".$lang['yes']."&nbsp;&nbsp;&nbsp;\n";
      echo "<input type=\"radio\" name=\"image_allow_comments_".$i."\" value=\"0\"".$c2."> ".$lang['no']." ";
      echo "</td>\n";
      echo "</tr>\n";
    }
  }
  if ($num_all_newimages) {
    show_hidden_input("num_newimages", $num_newimages);
    show_hidden_input("detailed", $detailed);
    show_form_footer($lang['add'], $lang['reset'], $colspan);
  }
  else {
    show_table_footer();
  }
}
show_admin_footer();
?>