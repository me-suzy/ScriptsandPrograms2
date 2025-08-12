<?php
/**************************************************************************
 *                                                                        *
 *    4images - A Web Based Image Gallery Management System               *
 *    ----------------------------------------------------------------    *
 *                                                                        *
 *             File: search.php                                           *
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

if (isset($HTTP_POST_VARS['show_result']) || isset($HTTP_GET_VARS['show_result'])) {
  $show_result = 1;
}
else {
  $show_result = 0;
}

if (isset($HTTP_POST_VARS['search_keywords']) || isset($HTTP_GET_VARS['search_keywords'])) {
  $search_keywords = (isset($HTTP_POST_VARS['search_keywords'])) ? trim($HTTP_POST_VARS['search_keywords']) : urldecode(trim($HTTP_GET_VARS['search_keywords']));
  if ($search_keywords != "") {
    $show_result = 1;
  }
}
else {
  $search_keywords = "";
}
$org_search_keywords = $search_keywords;

if (isset($HTTP_POST_VARS['search_user']) || isset($HTTP_GET_VARS['search_user'])) {
  $search_user = (isset($HTTP_POST_VARS['search_user'])) ? trim($HTTP_POST_VARS['search_user']) : urldecode(trim($HTTP_GET_VARS['search_user']));
  if ($search_user != "") {
    $show_result = 1;
  }
}
else {
  $search_user = "";
}
$org_search_user = $search_user;

if (isset($HTTP_POST_VARS['search_terms'])) {
  $search_terms = (trim($HTTP_POST_VARS['search_terms']) == "all") ? 1 : 0;
}
else {
  $search_terms = 0;
}

if (isset($HTTP_POST_VARS['search_fields'])) {
  $search_fields = trim($HTTP_POST_VARS['search_fields']);
}
else {
  $search_fields = "all";
}

$search_cat = (isset($HTTP_POST_VARS['cat_id']) ) ? intval($HTTP_POST_VARS['cat_id']) : 0;

if (isset($HTTP_POST_VARS['search_new_images']) || isset($HTTP_GET_VARS['search_new_images'])) {
  $search_new_images = 1;
  $show_result = 1;
}
else {
  $search_new_images = 0;
}

$main_template = 'search';

define('GET_CACHES', 1);
define('ROOT_PATH', './');
include(ROOT_PATH.'global.php');
require(ROOT_PATH.'includes/sessions.php');
$user_access = get_permission();
include(ROOT_PATH.'includes/search_utils.php');

$search_id = array();

if ($search_user != "" && $show_result == 1) {
  $search_user = str_replace('*', '%', trim($search_user));
  $sql = "SELECT ".get_user_table_field("", "user_id")." 
          FROM ".USERS_TABLE." 
          WHERE ".get_user_table_field("", "user_name")." LIKE '$search_user'";
  $result = $site_db->query($sql);
  $search_id['user_ids'] = "";
  if ($result) {
    while ($row = $site_db->fetch_array($result)) {
      $search_id['user_ids'] .= (($search_id['user_ids'] != "") ? ", " : "").$row[$user_table_fields['user_id']];
    }
    $site_db->free_result($result);
  }
}

if ($search_keywords != "" && $show_result == 1) {
  $search_keywords = strip_tags(strtolower(stripslashes($search_keywords)));
  $search_array = array(
    "/\s+\+/",
    "/\s+\-/",
    "/[&|#][a-z0-9]*;/siU",
    "#([^]_a-z0-9-=\"'\/])([a-z]+?)://([^, \(\)<>\n\r]+)#si",
    "#([^]_a-z0-9-=\"'\/])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^, \(\)<>\n\r]*)?)#si",
    "#[-_'`´]+#s",
    "#[\n\t\r^\$&\(\)<>\"\|,@\?%~\+\.\[\]{}:\/=\#;!§\\\\]+#s"
  );

  $replace_array = array(
    " and ",
    " not ",
    " ",
    " ",
    " ",
    "",
    " "
  );
  $search_keywords = preg_replace($search_array, $replace_array, $search_keywords);
  $split_words = preg_split("/\s+/", $search_keywords);

  $match_field_sql = ($search_fields != "all" && isset($search_match_fields[$search_fields])) ? "AND m.".$search_match_fields[$search_fields]." = 1" : "";
  $stopword_list = get_stopwords();
  $search_word_cache = array();
  for ($i = 0; $i < sizeof($split_words); $i++) {
    if ($split_words[$i] == "and" || $split_words[$i] == "or" || $split_words[$i] == "not") {
      $search_word_cache[$i] = ($search_terms) ? "and" : $split_words[$i];
    }
    elseif ($split_words[$i] != "" && strlen($split_words[$i]) >= MIN_SEARCH_KEYWORD_LENGTH && strlen($split_words[$i]) <= MAX_SEARCH_KEYWORD_LENGTH && !in_array($split_words[$i], $stopword_list)) {
      $sql = "SELECT m.image_id 
              FROM ".WORDLIST_TABLE." w, ".WORDMATCH_TABLE." m 
              WHERE w.word_text LIKE '".addslashes(str_replace("*", "%", $split_words[$i]))."' 
              AND m.word_id = w.word_id 
              $match_field_sql";
      $result = $site_db->query($sql);
      $search_word_cache[$i] = array();
      while ($row = $site_db->fetch_array($result)) {
        $search_word_cache[$i][$row['image_id']] = 1;
      }
      $site_db->free_result();
    }
  }

  $is_first_word = 1;
  $operator = "or";
  $image_id_list = array();
  for ($i = 0; $i < sizeof($search_word_cache); $i++) {
    if ($search_word_cache[$i] == "and" || $search_word_cache[$i] == "or" || $search_word_cache[$i] == "not") {
      $operator = $search_word_cache[$i];
    }
    elseif (is_array($search_word_cache[$i])) {
      foreach ($search_word_cache[$i] as $key => $val) {
        if ($is_first_word || $operator == "or") {
          $image_id_list[$key] = 1;
        }
        elseif ($operator == "not") {
          unset($image_id_list[$key]);
        }
      }
      if ($operator == "and" && !$is_first_word) {
        foreach ($image_id_list as $key => $val) {
          if (!isset($search_word_cache[$i][$key])) {
            unset($image_id_list[$key]);
          }
        }
      }
    }
    $is_first_word = 0;    
  }

  $search_id['image_ids'] = "";
  foreach ($image_id_list as $key => $val) {
    $search_id['image_ids'] .= (($search_id['image_ids'] != "") ? ", " : "").$key;
  }
  unset($image_id_list);
}

if ($search_new_images && $show_result == 1) {
  $search_id['search_new_images'] = 1;
}

if ($search_cat && $show_result == 1) {
  $search_id['search_cat'] = $search_cat;
}

if (!empty($search_id)) {
  $site_sess->set_session_var("search_id", serialize($search_id));
  $session_info['search_id'] = $search_id;
}

include(ROOT_PATH.'includes/page_header.php');

$num_rows_all = 0;
if ($show_result == 1) {
  if (!isset($session_info['searchid']) || empty($session_info['searchid'])) {
    $session_info['search_id'] = $site_sess->get_session_var("search_id");
  }

  if (!empty($session_info['search_id'])) {
    $search_id = unserialize($session_info['search_id']);
  }

  $sql_where_query = "";

  if (!empty($search_id['image_ids'])) {
    $sql_where_query .= "AND i.image_id IN (".$search_id['image_ids'].") ";
  }

  if (!empty($search_id['user_ids'])) {
    $sql_where_query .= "AND i.user_id IN (".$search_id['user_ids'].") ";
  }

  if (!empty($search_id['search_new_images']) && $search_id['search_new_images'] == 1) {
    $new_cutoff = time() - 60 * 60 * 24 * $config['new_cutoff'];
    $sql_where_query .= "AND i.image_date >= $new_cutoff ";
  }

  if (!empty($search_id['search_cat']) && $search_id['search_cat'] != 0) {
    $cat_id_sql = 0;
    $sub_cat_ids = get_subcat_ids($search_id['search_cat'], $search_id['search_cat'], $cat_parent_cache);
    if (check_permission("auth_viewcat", $search_id['search_cat'])) {
      $cat_id_sql .= ", ".$search_id['search_cat'];
      if (!empty($sub_cat_ids[$search_id['search_cat']])) {
        foreach ($sub_cat_ids[$search_id['search_cat']] as $val) {
          if (check_permission("auth_viewcat", $val)) {
            $cat_id_sql .= ", ".$val;
          }
        }
      }
    }
  }
  else {
    $cat_id_sql = get_auth_cat_sql("auth_viewcat");
  }

  if (!empty($sql_where_query)) {
    $sql = "SELECT COUNT(*) AS num_rows_all 
            FROM ".IMAGES_TABLE." i 
            WHERE i.image_active = 1 $sql_where_query 
            AND i.cat_id IN ($cat_id_sql)";
    $row = $site_db->query_firstrow($sql);
    $num_rows_all = $row['num_rows_all'];
  }
}

if (!$num_rows_all && $show_result == 1)  {
  $msg = preg_replace("/".$site_template->start."search_keywords".$site_template->end."/", $search_keywords, $lang['search_no_results']);
}

//-----------------------------------------------------
//--- Show Search Results -----------------------------
//-----------------------------------------------------
if ($num_rows_all && $show_result == 1)  {
  $link_arg = $site_sess->url(ROOT_PATH."search.php?show_result=1");

  include(ROOT_PATH.'includes/paging.php');
  $getpaging = new Paging($page, $perpage, $num_rows_all, $link_arg);
  $offset = $getpaging->get_offset();
  $site_template->register_vars(array(
    "paging" => $getpaging->get_paging(),
    "paging_stats" => $getpaging->get_paging_stats()
  ));

  $imgtable_width = ceil((intval($config['image_table_width'])) / $config['image_cells']);
  if ((substr($config['image_table_width'], -1)) == "%") {
    $imgtable_width .= "%";
  }

  $additional_sql = "";
  if (!empty($additional_image_fields)) {
    foreach ($additional_image_fields as $key => $val) {
      $additional_sql .= ", i.".$key;
    }
  }

  $sql = "SELECT i.image_id, i.cat_id, i.user_id, i.image_name, i.image_description, i.image_keywords, i.image_date, i.image_active, i.image_media_file, i.image_thumb_file, i.image_download_url, i.image_allow_comments, i.image_comments, i.image_downloads, i.image_votes, i.image_rating, i.image_hits".$additional_sql.", c.cat_name".get_user_table_field(", u.", "user_name")." 
          FROM ".IMAGES_TABLE." i,  ".CATEGORIES_TABLE." c 
          LEFT JOIN ".USERS_TABLE." u ON (".get_user_table_field("u.", "user_id")." = i.user_id) 
          WHERE i.image_active = 1 
          $sql_where_query 
          AND c.cat_id = i.cat_id AND i.cat_id IN ($cat_id_sql) 
          ORDER BY ".$config['image_order']." ".$config['image_sort']." 
          LIMIT $offset, $perpage";
  $result = $site_db->query($sql);

  $thumbnails = "<table width=\"".$config['image_table_width']."\" border=\"0\" cellpadding=\"".$config['image_table_cellpadding']."\" cellspacing=\"".$config['image_table_cellspacing']."\">\n";

  $count = 0;
  $bgcounter = 0;
  while ($image_row = $site_db->fetch_array($result)) {
    if ($count == 0) {
      $row_bg_number = ($bgcounter++ % 2 == 0) ? 1 : 2;
      $thumbnails .= "<tr class=\"imagerow".$row_bg_number."\">\n";
    }
    $thumbnails .= "<td width=\"".$imgtable_width."\" valign=\"top\">\n";
    show_image($image_row, "search");
    $thumbnails .= $site_template->parse_template("thumbnail_bit");
    $thumbnails .= "\n</td>\n";
    $count++;
    if ($count == $config['image_cells']) {
      $thumbnails .= "</tr>\n";
      $count = 0;
    }
  } // end while
  if ($count > 0)  {
    $leftover = ($config['image_cells'] - $count);
    if ($leftover >= 1) {
      for ($i = 0; $i < $leftover; $i++) {
        $thumbnails .= "<td width=\"".$imgtable_width."\">\n&nbsp;\n</td>\n";
      }
      $thumbnails .= "</tr>\n";
    }
  }
  $thumbnails .= "</table>\n";
  $content = $thumbnails;
  unset($thumbnails);
} // end if
else {
  $site_template->register_vars(array(
    "search_keywords" => htmlspecialchars(stripslashes($org_search_keywords)),
    "search_user" => htmlspecialchars(stripslashes($org_search_user)),
    "lang_search_by_keyword" => $lang['search_by_keyword'],
    "lang_search_by_username" => $lang['search_by_username'],
    "lang_new_images_only" => $lang['new_images_only'],
    "lang_search_terms" => $lang['search_terms'],
    "lang_or" => $lang['or'],
    "lang_and" => $lang['and'],
    "lang_category" => $lang['category'],
    "lang_search_fields" => $lang['search_fields'],
    "lang_all_fields" => $lang['all_fields'],
    "lang_name_only" => $lang['name_only'],
    "lang_description_only" => $lang['description_only'],
    "lang_keywords_only" => $lang['keywords_only'],
    "category_dropdown" => get_category_dropdown($cat_id)
  ));

  if (!empty($additional_image_fields)) {
    $additional_field_array = array();
    foreach ($additional_image_fields as $key => $val) {
      if (isset($lang[$key.'_only'])) {
        $additional_field_array['lang_'.$key.'_only'] = $lang[$key.'_only'];
      }
    }
    if (!empty($additional_field_array)) {
      $site_template->register_vars($additional_field_array);
    }
  }
  $content = $site_template->parse_template("search_form");
}

//-----------------------------------------------------
//--- Clickstream -------------------------------------
//-----------------------------------------------------
$clickstream = "<span class=\"clickstream\"><a href=\"".$site_sess->url(ROOT_PATH."index.php")."\" class=\"clickstream\">".$lang['home']."</a>".$config['category_separator'].$lang['search']."</span>";

//-----------------------------------------------------
//--- Print Out ---------------------------------------
//-----------------------------------------------------
$site_template->register_vars(array(
  "content" => $content,
  "msg" => $msg,
  "clickstream" => $clickstream,
  "lang_search" => $lang['search']
));
$site_template->print_template($site_template->parse_template($main_template));
include(ROOT_PATH.'includes/page_footer.php');
?>