<?php
//=============================================================================
//
//     CMS Master :: Content management system
//     File: /cms-modules/common.php
//     Version: 1.0
//     Created date: 28 September 2003
//     Modification date: 28 September 2003
//
//============================================================================

function build_url($page_url, $page_parent)
{
    global $Db;
    $pages_url[0] = $page_url; $n=1; $cur_parent = $page_parent;
    while($qpage = $Db->query("SELECT `id`, `name_url`, `parent` FROM `cms_pages` WHERE `id`=$cur_parent")) {
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

?>