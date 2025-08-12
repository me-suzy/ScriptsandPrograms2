<?php
/**************************************************************************
 *                                                                        *
 *    4images - A Web Based Image Gallery Management System               *
 *    ----------------------------------------------------------------    *
 *                                                                        *
 *             File: download.php                                         *
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

$main_template = 0;

$nozip = 1;
define('GET_CACHES', 1);
define('ROOT_PATH', './');
include(ROOT_PATH.'global.php');
require(ROOT_PATH.'includes/sessions.php');
$user_access = get_permission();

function get_remote_file($url) {
  $file_data = "";
  $url = @parse_url($url);
  if (isset($url['path']) && isset($url['scheme']) && eregi("http", $url['scheme'])) {
    $url['port'] = (!isset($url['port'])) ? 80 : $url['port'];
    if ($fsock = @fsockopen($url['host'], $url['port'], $errno, $errstr)) {
      @fputs($fsock, "GET ".$url['path']." HTTP/1.1\r\n");
      @fputs($fsock, "HOST: ".$url['host']."\r\n");
      @fputs($fsock, "Connection: close\r\n\r\n");
      $file_data = "";
      while (!@feof($fsock)) {
        $file_data .= @fread($fsock, 1000);
      }
      @fclose($fsock);
      if (preg_match("/Content-Length\: ([0-9]+)[^\/ \n]/i", $file_data, $regs)) {
        $file_data = substr($file_data, strlen($file_data) - $regs[1], $regs[1]);
      }
    }
  }
  return (!empty($file_data)) ? $file_data : 0;
}

function get_file_data($file_path) {
  global $script_url;
  ob_start();
  @ob_implicit_flush(0);
  @readfile($file_path);
  $file_data = ob_get_contents();
  ob_end_clean();
  if (!empty($file_data)) {
    return $file_data;
  }
  elseif (is_remote_file($file_path)) {
    $file_data = get_remote_file($file_path);
  }
  else {
    if (!file_exists($file_path)) {
      $file_path = preg_replace("/\/{2,}/", "/", get_document_root()."/".$file_path);
    }
    if (file_exists($file_path)) {
      $file_size = @filesize($file_path);
      $fp = @fopen($file_path, "rb");
      if ($fp) {
        $file_data = @fread($fp, $file_size);
        @fclose($fp);
      }
    }
  }
  if (empty($file_data)) {
    if (ereg("^\/", $file_path)) {
      preg_match("/^(http:\/\/[^\/]+)/i", $script_url, $regs);
      $script_url = $regs[1];
    }
    $file_data = get_remote_file($script_url."/".$file_path);
  }
  return (!empty($file_data)) ? $file_data : 0;
}

$file = array();

if ($action == "lightbox") {
  if (empty($user_info['lightbox_image_ids']) || !function_exists("gzcompress") || !function_exists("crc32")) {
    header("Location: ".$site_sess->url($url, "&"));
    exit;
  }

  $image_id_sql = str_replace(" ", ", ", trim($user_info['lightbox_image_ids']));
  $sql = "SELECT cat_id, image_media_file, image_download_url 
          FROM ".IMAGES_TABLE." 
          WHERE image_active = 1 AND image_id IN ($image_id_sql) AND cat_id IN (".get_auth_cat_sql("auth_download").")";
  $result = $site_db->query($sql);

  if ($result) {
    include(ROOT_PATH."includes/zip.php");
    $zipfile = new zipfile();
    $file_added = 0;
    while ($image_row = $site_db->fetch_array($result)) {
      if (!empty($image_row['image_download_url'])) {
        if (is_remote_file($image_row['image_download_url']) || is_local_file($image_row['image_download_url'])) {
          $file_path = $image_row['image_download_url'];
          $file_name = basename($image_row['image_download_url']);
        }
      }
      elseif (is_remote($image_row['image_media_file'])) {
        $file_path = $image_row['image_media_file'];
        $file_name = basename($image_row['image_media_file']);
      }
      else {
        $file_path = MEDIA_PATH."/".$image_row['cat_id']."/".$image_row['image_media_file'];
        $file_name = $image_row['image_media_file'];
      }

      if (!empty($file_path)) {
        @set_time_limit(120);
        if (!$file_data = get_file_data($file_path)) {
          continue;
        }
        $zipfile->add_file($file_data, $file_name);
        $file_added = 1;
        unset($file_data);
      }
    }

    if ($file_added) {
      @set_time_limit(120);
      $file['file_name'] = time().".zip";
      $file['file_data'] = $zipfile->file();
      $file['file_size'] = strlen($file['file_data']);
    }
    else {
      header("Location: ".$site_sess->url($url, "&"));
      exit;
    }
  }
}
elseif ($image_id) {
  if (isset($HTTP_GET_VARS['size']) || isset($HTTP_POST_VARS['size'])) {
    $size = (isset($HTTP_GET_VARS['size'])) ? intval($HTTP_GET_VARS['size']) : intval($HTTP_POST_VARS['size']);
  }
  else {
    $size = 0;
  }

  $sql = "SELECT cat_id, user_id, image_media_file, image_download_url, image_downloads 
          FROM ".IMAGES_TABLE." 
          WHERE image_id = $image_id AND image_active = 1";
  $image_row = $site_db->query_firstrow($sql);

  if (!check_permission("auth_download", $image_row['cat_id']) || !$image_row) {
    header("Location: ".$site_sess->url($url, "&"));
    exit;
  }

  $remote_url = 0;
  if (!empty($image_row['image_download_url'])) {
    if (is_remote_file($image_row['image_download_url']) || is_local_file($image_row['image_download_url'])) {
      ereg("(.+)\.(.+)", basename($image_row['image_download_url']), $regs);
      $file_name = $regs[1];
      $file_extension = $regs[2];

      $file['file_name'] = $file_name.(($size) ? "_".$size : "").".".$file_extension;
      $file['file_path'] = dirname($image_row['image_download_url'])."/".$file['file_name'];
    }
    else {
      $file['file_path'] = $image_row['image_download_url'];
      $remote_url = 1;
    }
  }
  elseif (is_remote_file($image_row['image_media_file'])) {
    ereg("(.+)\.(.+)", basename($image_row['image_media_file']), $regs);
    $file_name = $regs[1];
    $file_extension = $regs[2];

    $file['file_name'] = $file_name.(($size) ? "_".$size : "").".".$file_extension;
    $file['file_path'] = dirname($image_row['image_media_file'])."/".$file['file_name'];
  }
  else {
    ereg("(.+)\.(.+)", basename($image_row['image_media_file']), $regs);
    $file_name = $regs[1];
    $file_extension = $regs[2];

    $file['file_name'] = $file_name.(($size) ? "_".$size : "").".".$file_extension;
    $file['file_path'] = (is_local_file($image_row['image_media_file'])) ? dirname($image_row['image_media_file'])."/".$file['file_name'] : MEDIA_PATH."/".$image_row['cat_id']."/".$file['file_name'];
  }

  if ($user_info['user_level'] != ADMIN) {
    $sql = "UPDATE ".IMAGES_TABLE." 
            SET image_downloads = image_downloads + 1 
            WHERE image_id = $image_id";
    $site_db->query($sql);
  }

  if (!empty($file['file_path'])) {
    @set_time_limit(120);
    if ($remote_url) {
      header("Location: ".$file['file_path']);
      exit;
    }
    elseif (!$file['file_data'] = get_file_data($file['file_path'])) {
      ?>
      <script language="javascript" type="text/javascript">
      <!--
      window.open('<?php echo $file['file_path']; ?>','imagewindow','toolbar=yes,location=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes');
      // -->
      </script>
      <meta http-equiv="Refresh" content="0; URL=<?php echo $site_sess->url($url, "&"); ?>">
      <?php
      echo $lang['download_error']."\n<!-- NO FILE DATA / FILE NOT FOUND //-->";
      exit;
    }

    if ($action == "zip" && !eregi("\.zip$", $file['file_name']) && function_exists("gzcompress") && function_exists("crc32")) {
      include(ROOT_PATH."includes/zip.php");
      $zipfile = new zipfile();
      $zipfile->add_file($file['file_data'], $file['file_name']);

      $file['file_data'] = $zipfile->file();
      $file['file_name'] = get_file_name($file['file_name']).".zip";
    }
    $file['file_size'] = strlen($file['file_data']);
  }
  else {
    echo $lang['download_error']."\n<!-- EMPTY FILE PATH //-->";
    exit;
  }
}
else {
  echo $lang['download_error']."\n<!-- NO ACTION SPECIFIED //-->";
  exit;
}

if (!empty($file['file_data'])) {
  if (get_user_os() == "MAC") {
    header("Content-Type: application/x-unknown\n");
		header("Content-Disposition: attachment; filename=\"".$file['file_name']."\"\n");
  }
  elseif (get_browser_info() == "MSIE") {
    $disposition = (!eregi("\.zip$", $file['file_name']) && $action != "zip" && $action != "lightbox") ? 'attachment' : 'inline';
    header("Content-Disposition: $disposition; filename=\"".$file['file_name']."\"\n");
    header("Content-Type: application/x-ms-download\n");
  }
  elseif (get_browser_info() == "OPERA") {
    header("Content-Disposition: attachment; filename=\"".$file['file_name']."\"\n");
    header("Content-Type: application/octetstream\n");
  }
  else {
    header("Content-Disposition: attachment; filename=\"".$file['file_name']."\"\n");
    header("Content-Type: application/octet-stream\n");
  }
  header("Content-Length: ".$file['file_size']."\n\n");
  echo $file['file_data'];
}
exit;
?>