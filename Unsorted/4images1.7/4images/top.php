<?php
/**************************************************************************
 *                                                                        *
 *    4images - A Web Based Image Gallery Management System               *
 *    ----------------------------------------------------------------    *
 *                                                                        *
 *             File: top.php                                              *
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

$main_template = 'top';

define('GET_CACHES', 1);
define('ROOT_PATH', './');
include(ROOT_PATH.'global.php');
require(ROOT_PATH.'includes/sessions.php');
$user_access = get_permission();
include(ROOT_PATH.'includes/page_header.php');

$cat_match_sql = ($cat_id && check_permission("auth_viewcat", $cat_id)) ? "AND i.cat_id = '$cat_id' " : "";
$register_array = array();

$cat_id_sql = get_auth_cat_sql("auth_viewcat", "NOTIN");

// Rating
$sql = "SELECT i.image_id, i.user_id, i.cat_id, i.image_name, i.image_rating, i.image_votes, c.cat_name".get_user_table_field(", u.", "user_name")." 
        FROM ".IMAGES_TABLE." i, ".CATEGORIES_TABLE." c 
        LEFT JOIN ".USERS_TABLE." u ON (".get_user_table_field("u.", "user_id")." = i.user_id) 
        WHERE i.image_active = 1 AND i.cat_id NOT IN ($cat_id_sql) AND i.cat_id = c.cat_id 
        $cat_match_sql 
        ORDER BY i.image_rating DESC, i.image_name ASC
        LIMIT 10";
$result = $site_db->query($sql);
$top_list = array();
$i = 1;
while ($row = $site_db->fetch_array($result)) {
  $top_list[$i] = $row;
  $i++;
}
$site_db->free_result();

for ($i = 1; $i <= 10; $i++) {
  if (isset($top_list[$i])) {
    $register_array['image_rating_'.$i] = (check_permission("auth_viewimage", $top_list[$i]['cat_id'])) ? "<a href=\"".$site_sess->url(ROOT_PATH."details.php?".URL_IMAGE_ID."=".$top_list[$i]['image_id'])."\">".htmlspecialchars($top_list[$i]['image_name'])."</a>" : htmlspecialchars($top_list[$i]['image_name']);
    $register_array['image_rating_openwindow_'.$i] = (check_permission("auth_viewimage", $top_list[$i]['cat_id'])) ? "<a href=\"".$site_sess->url(ROOT_PATH."details.php?".URL_IMAGE_ID."=".$top_list[$i]['image_id'])."\" onclick=\"opendetailwindow()\" target=\"detailwindow\">".htmlspecialchars($top_list[$i]['image_name'])."</a>" : htmlspecialchars($top_list[$i]['image_name']);
    if (isset($top_list[$i][$user_table_fields['user_name']]) && $top_list[$i]['user_id'] != GUEST) {
      $user_profile_link = (!empty($url_show_profile)) ? preg_replace("/{user_id}/", $top_list[$i]['user_id'], $url_show_profile) : ROOT_PATH."member.php?action=showprofile&amp;".URL_USER_ID."=".$top_list[$i]['user_id'];
      $register_array['image_rating_user_'.$i] = "<a href=\"".$site_sess->url($user_profile_link)."\">".htmlspecialchars($top_list[$i][$user_table_fields['user_name']])."</a>";
    }
    else {
      $register_array['image_rating_user_'.$i] = $lang['userlevel_guest'];
    }
    $register_array['image_rating_cat_'.$i] = "<a href=\"".$site_sess->url(ROOT_PATH."categories.php?".URL_CAT_ID."=".$top_list[$i]['cat_id'])."\">".htmlspecialchars($top_list[$i]['cat_name'])."</a>";
    $register_array['image_rating_number_'.$i] = "<b>".$top_list[$i]['image_rating']."</b> (".$top_list[$i]['image_votes']." ".$lang['votes'].")";
  }
  else {
    $register_array['image_rating_'.$i] = "--";
    $register_array['image_rating_user_'.$i] = "--";
    $register_array['image_rating_cat_'.$i] = "--";
    $register_array['image_rating_number_'.$i] = "--";
  }
}

// Votes
$sql = "SELECT i.image_id, i.user_id, i.cat_id, i.image_name, i.image_rating, i.image_votes, c.cat_name".get_user_table_field(", u.", "user_name")." 
        FROM ".IMAGES_TABLE." i, ".CATEGORIES_TABLE." c 
        LEFT JOIN ".USERS_TABLE." u ON (".get_user_table_field("u.", "user_id")." = i.user_id) 
        WHERE i.image_active = 1 AND i.cat_id NOT IN ($cat_id_sql) AND i.cat_id = c.cat_id 
        $cat_match_sql 
        ORDER BY i.image_votes DESC, i.image_name ASC 
        LIMIT 10";
$result = $site_db->query($sql);
$top_list = array();
$i = 1;
while ($row = $site_db->fetch_array($result)) {
  $top_list[$i] = $row;
  $i++;
}
$site_db->free_result();

for ($i = 1; $i <= 10; $i++) {
  if (isset($top_list[$i])) {
    $register_array['image_votes_'.$i] = (check_permission("auth_viewimage", $top_list[$i]['cat_id'])) ? "<a href=\"".$site_sess->url(ROOT_PATH."details.php?".URL_IMAGE_ID."=".$top_list[$i]['image_id'])."\">".htmlspecialchars($top_list[$i]['image_name'])."</a>" : htmlspecialchars($top_list[$i]['image_name']);
    $register_array['image_votes_openwindow_'.$i] = (check_permission("auth_viewimage", $top_list[$i]['cat_id'])) ? "<a href=\"".$site_sess->url(ROOT_PATH."details.php?".URL_IMAGE_ID."=".$top_list[$i]['image_id'])."\" onclick=\"opendetailwindow()\" target=\"detailwindow\">".htmlspecialchars($top_list[$i]['image_name'])."</a>" : htmlspecialchars($top_list[$i]['image_name']);
    if (isset($top_list[$i][$user_table_fields['user_name']]) && $top_list[$i]['user_id'] != GUEST) {
      $user_profile_link = (!empty($url_show_profile)) ? preg_replace("/{user_id}/", $top_list[$i]['user_id'], $url_show_profile) : ROOT_PATH."member.php?action=showprofile&amp;".URL_USER_ID."=".$top_list[$i]['user_id'];
      $register_array['image_votes_user_'.$i] = "<a href=\"".$site_sess->url($user_profile_link)."\">".htmlspecialchars($top_list[$i][$user_table_fields['user_name']])."</a>";
    }
    else {
      $register_array['image_votes_user_'.$i] = $lang['userlevel_guest'];
    }
    $register_array['image_votes_cat_'.$i] = "<a href=\"".$site_sess->url(ROOT_PATH."categories.php?".URL_CAT_ID."=".$top_list[$i]['cat_id'])."\">".htmlspecialchars($top_list[$i]['cat_name'])."</a>";
    $register_array['image_votes_number_'.$i] = "<b>".$top_list[$i]['image_rating']."</b> (".$top_list[$i]['image_votes']." ".$lang['votes'].")";
  }
  else {
    $register_array['image_votes_'.$i] = "--";
    $register_array['image_votes_user_'.$i] = "--";
    $register_array['image_votes_cat_'.$i] = "--";
    $register_array['image_votes_number_'.$i] = "--";
  }
}

// Hits
$sql = "SELECT i.image_id, i.user_id, i.cat_id, i.image_name, i.image_hits, c.cat_name".get_user_table_field(", u.", "user_name")." 
        FROM ".IMAGES_TABLE." i, ".CATEGORIES_TABLE." c 
        LEFT JOIN ".USERS_TABLE." u ON (".get_user_table_field("u.", "user_id")." = i.user_id) 
        WHERE i.image_active = 1 AND i.cat_id NOT IN ($cat_id_sql) AND i.cat_id = c.cat_id 
        $cat_match_sql 
        ORDER BY i.image_hits DESC, i.image_name ASC 
        LIMIT 10";
$result = $site_db->query($sql);
$top_list = array();
$i = 1;
while ($row = $site_db->fetch_array($result)) {
  $top_list[$i] = $row;
  $i++;
}
$site_db->free_result();

for ($i = 1; $i <= 10; $i++) {
  if (isset($top_list[$i])) {
    $register_array['image_hits_'.$i] = (check_permission("auth_viewimage", $top_list[$i]['cat_id'])) ? "<a href=\"".$site_sess->url(ROOT_PATH."details.php?".URL_IMAGE_ID."=".$top_list[$i]['image_id'])."\">".htmlspecialchars($top_list[$i]['image_name'])."</a>" : htmlspecialchars($top_list[$i]['image_name']);
    $register_array['image_hits_openwindow_'.$i] = (check_permission("auth_viewimage", $top_list[$i]['cat_id'])) ? "<a href=\"".$site_sess->url(ROOT_PATH."details.php?".URL_IMAGE_ID."=".$top_list[$i]['image_id'])."\" onclick=\"opendetailwindow()\" target=\"detailwindow\">".htmlspecialchars($top_list[$i]['image_name'])."</a>" : htmlspecialchars($top_list[$i]['image_name']);
    if (isset($top_list[$i][$user_table_fields['user_name']]) && $top_list[$i]['user_id'] != GUEST) {
      $user_profile_link = (!empty($url_show_profile)) ? preg_replace("/{user_id}/", $top_list[$i]['user_id'], $url_show_profile) : ROOT_PATH."member.php?action=showprofile&amp;".URL_USER_ID."=".$top_list[$i]['user_id'];
      $register_array['image_hits_user_'.$i] = "<a href=\"".$site_sess->url($user_profile_link)."\">".htmlspecialchars($top_list[$i][$user_table_fields['user_name']])."</a>";
    }
    else {
      $register_array['image_hits_user_'.$i] = $lang['userlevel_guest'];
    }
    $register_array['image_hits_cat_'.$i] = "<a href=\"".$site_sess->url(ROOT_PATH."categories.php?".URL_CAT_ID."=".$top_list[$i]['cat_id'])."\">".htmlspecialchars($top_list[$i]['cat_name'])."</a>";
    $register_array['image_hits_number_'.$i] = "<b>".$top_list[$i]['image_hits']."</b>";
  }
  else {
    $register_array['image_hits_'.$i] = "--";
    $register_array['image_hits_user_'.$i] = "--";
    $register_array['image_hits_cat_'.$i] = "--";
    $register_array['image_hits_number_'.$i] = "--";
  }
}

// Downloads
$sql = "SELECT i.image_id, i.user_id, i.cat_id, i.image_name, i.image_downloads, c.cat_name".get_user_table_field(", u.", "user_name")." 
        FROM ".IMAGES_TABLE." i, ".CATEGORIES_TABLE." c 
        LEFT JOIN ".USERS_TABLE." u ON (".get_user_table_field("u.", "user_id")." = i.user_id) 
        WHERE i.image_active = 1 AND i.cat_id NOT IN ($cat_id_sql) AND i.cat_id = c.cat_id 
        $cat_match_sql 
        ORDER BY i.image_downloads DESC, i.image_name ASC 
        LIMIT 10";
$result = $site_db->query($sql);
$top_list = array();
$i = 1;
while ($row = $site_db->fetch_array($result)) {
  $top_list[$i] = $row;
  $i++;
}
$site_db->free_result();

for ($i = 1; $i <= 10; $i++) {
  if (isset($top_list[$i])) {
    $register_array['image_downloads_'.$i] = (check_permission("auth_viewimage", $top_list[$i]['cat_id'])) ? "<a href=\"".$site_sess->url(ROOT_PATH."details.php?".URL_IMAGE_ID."=".$top_list[$i]['image_id'])."\">".htmlspecialchars($top_list[$i]['image_name'])."</a>" : htmlspecialchars($top_list[$i]['image_name']);
    $register_array['image_downloads_openwindow_'.$i] = (check_permission("auth_viewimage", $top_list[$i]['cat_id'])) ? "<a href=\"".$site_sess->url(ROOT_PATH."details.php?".URL_IMAGE_ID."=".$top_list[$i]['image_id'])."\" onclick=\"opendetailwindow()\" target=\"detailwindow\">".htmlspecialchars($top_list[$i]['image_name'])."</a>" : htmlspecialchars($top_list[$i]['image_name']);
    if (isset($top_list[$i][$user_table_fields['user_name']]) && $top_list[$i]['user_id'] != GUEST) {
      $user_profile_link = (!empty($url_show_profile)) ? preg_replace("/{user_id}/", $top_list[$i]['user_id'], $url_show_profile) : ROOT_PATH."member.php?action=showprofile&amp;".URL_USER_ID."=".$top_list[$i]['user_id'];
      $register_array['image_downloads_user_'.$i] = "<a href=\"".$site_sess->url($user_profile_link)."\">".htmlspecialchars($top_list[$i][$user_table_fields['user_name']])."</a>";
    }
    else {
      $register_array['image_downloads_user_'.$i] = $lang['userlevel_guest'];
    }
    $register_array['image_downloads_cat_'.$i] = "<a href=\"".$site_sess->url(ROOT_PATH."categories.php?".URL_CAT_ID."=".$top_list[$i]['cat_id'])."\">".htmlspecialchars($top_list[$i]['cat_name'])."</a>";
    $register_array['image_downloads_number_'.$i] = "<b>".$top_list[$i]['image_downloads']."</b>";
  }
  else {
    $register_array['image_downloads_'.$i] = "--";
    $register_array['image_downloads_user_'.$i] = "--";
    $register_array['image_downloads_cat_'.$i] = "--";
    $register_array['image_downloads_number_'.$i] = "--";
  }
}

$site_template->register_vars($register_array);

//-----------------------------------------------------
//--- Clickstream -------------------------------------
//-----------------------------------------------------
$clickstream = "<span class=\"clickstream\"><a href=\"".$site_sess->url(ROOT_PATH."index.php")."\" class=\"clickstream\">".$lang['home']."</a>".$config['category_separator'];
if ($cat_id && isset($cat_cache[$cat_id])) {
  $clickstream .= get_category_path($cat_id, 1).$config['category_separator'];
}
$clickstream .= $lang['top_images']."</span>";

//-----------------------------------------------------
//--- Print Out ---------------------------------------
//-----------------------------------------------------
$site_template->register_vars(array(
  "msg" => $msg,
  "clickstream" => $clickstream,
  "lang_top_image_hits" => $lang['top_image_hits'],
  "lang_top_image_downloads" => $lang['top_image_downloads'],
  "lang_top_image_rating" => $lang['top_image_rating'],
  "lang_top_image_votes" => $lang['top_image_votes']
));
$site_template->print_template($site_template->parse_template($main_template));
include(ROOT_PATH.'includes/page_footer.php');
?>