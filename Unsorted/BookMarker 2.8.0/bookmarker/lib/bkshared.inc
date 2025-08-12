<?php
# ---------------------------------------------------------------
# bookmarker
# A WWW based bookmark management, retrieval, and search tool.
# Copyright (C) 1998  Padraic Renaghan
# Licensed under terms of GNU General Public License
# (see http://www.renaghan.com/bookmarker/source/LICENSE)
# ---------------------------------------------------------------
# $Id: bkshared.inc,v 1.25 2000/10/08 22:47:05 prenagha Exp $
# ---------------------------------------------------------------
function  set_standard($title, &$p_tpl)  {
  global $sess, $auth, $bookmarker, $SERVER_NAME;

  if (isset($auth) 
  && $auth->is_authenticated()
  && $auth->auth["uid"] != "nobody" ) {
    $name_html = sprintf("as %s", $auth->auth["name"]);
    $logout_html = sprintf("<a class=hdr href=\"%s\"><img width=24 height=24 src=\"%slogout.%s\" border=0 alt=\"Logout\"></a>"
      ,$sess->url("logout.php3")
      ,$bookmarker->image_url_prefix
      ,$bookmarker->image_ext);
  } else {
    $name_html = "as Guest";
    $logout_html = sprintf("<a class=hdr href=\"%s\"><img width=24 height=24 src=\"%slogin.%s\" border=0 alt=\"Login\"></a>"
      ,$sess->url("index.php3?login=YES")
      ,$bookmarker->image_url_prefix
      ,$bookmarker->image_ext);
  }

  $p_tpl->set_var(array(
    TITLE            => $title,
    START_URL        => $sess->url("index.php3"),
    TREE_URL         => $sess->url("tree.php3"),
    LIST_URL         => $sess->url("list.php3"),
    CREATE_URL       => $sess->url("create.php3"),
    MAINTAIN_URL     => $sess->url("maintain.php3"),
    MAILLINK_URL     => $sess->url("maillink.php3"),
    SEARCH_URL       => $sess->url("search.php3"),
    FAQ_URL          => $sess->url("faq.php3"),
    CATEGORY_URL     => $sess->url("codes.php3?codetable=category"),
    SUBCATEGORY_URL  => $sess->url("codes.php3?codetable=subcategory"),
    RATINGS_URL      => $sess->url("codes.php3?codetable=rating"),
    USER_URL         => $sess->url("useropt.php3"),
    USER_SETTINGS_URL=> $sess->url("user.php3"),
    IMPORT_URL       => $sess->url("import.php3"),
    LOGOUT_HTML      => $logout_html,
    DOWNLOAD_URL     => $sess->url("download.php3"),
    BUGS_URL         => $sess->url("bugs.php3"),
    MAILLIST_URL     => $sess->url("maillist.php3"),
    VERSION          => $bookmarker->version,
    IMAGE_URL_PREFIX => $bookmarker->image_url_prefix,
    IMAGE_EXT        => $bookmarker->image_ext,
    NAME_HTML        => $name_html,
    SERVER_NAME      => $SERVER_NAME
  ));
}

# function to load a drop down list box from one
# of the standard id-name formatted tables. this
# routine will insert the <option> tags, it does
# not insert the <select> tags.
function load_ddlb($table, $selected_id, &$content, $blank) {
  global $bookmarker, $auth;

  if ($auth->is_nobody()) return FALSE;

  $db_ddlb = new bk_db;

  $option_tpl = new bktemplate;
  $option_tpl->set_root(TEMPLATEDIR);

  $option_tpl->set_file(array(
    option_list => "common.option.tpl",
    select      => "common.select.tpl"
  ));

# if the user wants a blank option, add that first
# the blank can be selected.
  if ($blank) {
    $opt_value =  "NONE";
    $opt_name  =  "----";
    if ($selected_id == $opt_value) {
      $opt_selected = "selected";
    } else {
      $opt_selected = "";
    }
    $option_tpl->set_var(array(
      OPTION_SELECTED => $opt_selected,
      OPTION_VALUE    => $opt_value,
      OPTION_NAME     => $opt_name
    ));
    $option_tpl->parse(OPTION_LIST, "option_list", TRUE);
  }


  $query = sprintf("select id, name from %s where username='%s' order by name", $table, $auth->auth["uname"]);
  $db_ddlb->query($query);
  while ($db_ddlb->next_record()) {
    $cnt = $cnt + 1;
    $opt_value =  $db_ddlb->f("id");
    $opt_name  =  htmlspecialchars(stripslashes($db_ddlb->f("name")));

    if ($selected_id == $opt_value) {
      $opt_selected = "selected";
    } else {
      $opt_selected = "";
    }

    $option_tpl->set_var(array(
      OPTION_SELECTED => $opt_selected,
      OPTION_VALUE    => $opt_value,
      OPTION_NAME     => $opt_name
    ));
    $option_tpl->parse(OPTION_LIST, "option_list", TRUE);
  }

  if ( $cnt > 0 ) {
    $option_tpl->set_var(FIELD_NAME, $table);
    $option_tpl->parse(MAIN, "select");
    $content = $option_tpl->get("MAIN");
  } else {
    $content = " ";
  }
}

# function to determine what type of browser the user has.
# code idea from http://www.php.net/
function check_browser() {
  global $HTTP_USER_AGENT;

  $browser= "UNKNOWN";

  if (ereg( "MSIE",$HTTP_USER_AGENT)) {
    $browser = "MSIE";
  } elseif (ereg( "Mozilla",$HTTP_USER_AGENT)) {
    $browser = "NETSCAPE";
  } else {
    $browser = "UNKNOWN";
  }

  return $browser;
}
?>
