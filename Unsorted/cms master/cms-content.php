<?php
//=============================================================================
//
//     CMS Master :: Content management system
//     File: /cms-content.php
//     Version: 1.0
//     Created date: 28 September 2003
//     Modification date: 28 September 2003
//
//============================================================================

require("cms-config.php");

//----- START INIT TEMPLATE VARS ---------------------------------------------
$CMS_BLOCKS = array();
$CMS_BLOCKS["CMS-PAGE-TITLE"] = "";
$CMS_BLOCKS["CMS-PAGE-KEYWORDS"] = "";
$CMS_BLOCKS["CMS-PAGE-DESCRIPTION"] = "";
$CMS_BLOCKS["CMS-PAGE-HEADER"] = "";
$CMS_BLOCKS["CMS-PAGE-CONTENT"] = "";
//----- END INIT TEMPLATE VARS -----------------------------------------------

//----- START INCLUDE COMMON LIBS --------------------------------------------
$dir_name = "$CFG->dir_root/cms-libs";
$dir = dir("$dir_name/");
$dir->read(); $dir->read();
while (false !== ($lib = $dir->read())) {
    require("$dir_name/$lib");
}
$dir->close();
//----- END INCLUDE COMMON LIBS ----------------------------------------------



//----- START INIT CLASSES --------------------------------------------------
$Db = new DB($CFG->db_host, $CFG->db_name, $CFG->db_user, $CFG->db_pass);
$ServerVars = new ServerVars();
//----- END INIT CLASSES ----------------------------------------------------

$Db->connect();



//----- START PARCE TITLE AND URL -------------------------------------------
$full_url = preg_replace("/\?.*$/", "", $ServerVars->REQUEST_URI);
$full_url = preg_replace("/\/$/","",$full_url);
$full_url = preg_replace("/^\//","",$full_url);
$urls = preg_split("/\//",$full_url);
$parent = 0;
for ($n=0; $n<=count($urls)-1; $n++) {
    $sql = "SELECT * FROM `cms_pages` WHERE `parent`=$parent AND `name_url`='$urls[$n]';";
    $qpages = $Db->query($sql);
    if ($pages = $Db->fetch_array($qpages)) {
      $parent = $pages["id"];
      if ($pages["name_title"]=="") {
          $title = $pages["name_menu"];
      } else {
          $title = $pages["name_title"];
      }
      $titles[$n] = $title;
      $page = $pages;
    } else {
    	break;
    }
}
//----- END PARCE TITLE AND URL ---------------------------------------------

//----- START INCLUDE MODULES ------------------------------------------------
$dir_name = "$CFG->dir_root/cms-modules";
require("$CFG->dir_root/cms-modules/common.php");
$dir = dir("$dir_name/");
$dir->read(); $dir->read();
while (false !== ($lib = $dir->read())) {
    if ($lib!="common.php") { require("$dir_name/$lib"); }
}
$dir->close();
//----- END INCLUDE MODULES -------------------------------------------------


//----- START REDIRECT ------------------------------------------------------
if ($page["redirect"] != -1) {
    $page = $Db->fetch_array($Db->query("SELECT * FROM `cms_pages` WHERE `id`=$page[redirect]"));
    if ($page["is_url_external"]==1) {
    	$redirect_url = $page["name_url_external"];
    } else {
    	$redirect_url = "/" . build_url($page["name_url"], $page["parent"]);
    }
    header("Location: $redirect_url");
    die;
} else {
    if ($page["is_url_external"]==1) {
      $redirect_url = $page["name_url_external"];
      header("Location: $redirect_url");
      die;
    } 
}
//----- END REDIRECT ------------------------------------------------------

$website = $Db->fetch_array($Db->query("SELECT * FROM `cms_site`"));
for ($n=count($titles)-1; $n>=0; $n--) {
    $CMS_PAGE_TITLE .= $titles[$n] . " > ";
}
if ($page["website_title"]=="") { $CMS_PAGE_TITLE .= $website["title"]; } else { $CMS_PAGE_TITLE .= $page["website_title"]; }
if ($page["website_keywords"]=="") { $CMS_PAGE_KEYWORDS = $website["keywords"]; } else { $CMS_PAGE_KEYWORDS = $page["website_keywords"]; }
if ($page["website_description"]=="") {$CMS_PAGE_DESCRIPTION = $website["description"]; } else { $CMS_PAGE_DESCRIPTION = $page["website_description"]; }

$f = fopen("$CFG->dir_root/cms-pages/$page[id]","r");
$CMS_PAGE_CONTENT = fread($f,filesize("$CFG->dir_root/cms-pages/$page[id]"));
fclose($f);

switch ($page["id"]) {
    default:
    	$template_file = "base";
    	break;
}

$f = fopen("$CFG->dir_root/cms-templates/$template_file","r");
$CONTENT = fread($f,filesize("$CFG->dir_root/cms-templates/$template_file"));
fclose($f);

$CONTENT = preg_replace("/CMS-PAGE-CONTENT/", $CMS_PAGE_CONTENT, $CONTENT);

$CMS_BLOCKS["CMS-PAGE-TITLE"] = htmlspecialchars($CMS_PAGE_TITLE,ENT_QUOTES);
$CMS_BLOCKS["CMS-PAGE-KEYWORDS"] = htmlspecialchars($CMS_PAGE_KEYWORDS,ENT_QUOTES);
$CMS_BLOCKS["CMS-PAGE-DESCRIPTION"] = htmlspecialchars($CMS_PAGE_DESCRIPTION,ENT_QUOTES);
$CMS_BLOCKS["CMS-PAGE-HEADER"] = $page["name_page"];

reset($CMS_BLOCKS);
while (list ($key, $val) = each ($CMS_BLOCKS)) {
    $CONTENT = preg_replace("/$key/", $val, $CONTENT);
}

$Db->disconnect();

echo $CONTENT;

?>