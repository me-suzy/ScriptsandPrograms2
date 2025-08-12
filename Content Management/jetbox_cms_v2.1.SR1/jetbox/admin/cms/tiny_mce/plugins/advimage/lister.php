<?
require("../../../../../includes/includes.inc.php");
session_start();
?>
<html>
<head>
<?php
require("config.inc.php");
?>
<style type="text/css">
TD { <?php echo LISTER_STYLE; ?> }
TD.delete { <?php echo LISTER_DELETE; ?> }
</style>
<script language="javascript">

function actionComplete(action, path, error, info) {
   //var manager = findAncestor(window.frameElement, '<?php echo MANAGER_NAME; ?>', '<?php echo MANAGER_TAG; ?>');
   manager = null;
	 var wrapper = findAncestor(window.frameElement, '<?php echo WRAPPER_NAME; ?>', '<?php echo WRAPPER_TAG; ?>');
		
   if(manager) {
      if(error.length < 1) {
         manager.all.actions.reset();
      }
      manager.all.actions.DPI.value = <?php echo AGENT_DPI; ?>;
      manager.all.actions.path.value = path;
   }

   if(wrapper)
      wrapper.all.viewer.contentWindow.navigate('<?php echo scriptURL("viewer.php?DPI=") . AGENT_DPI; ?>');
   if(error.length > 0)
      alert(error);
   else if(info.length > 0)
      alert(info);
}
</script>
</head>

<?php
/*
** Emits the appropriate HTML for the specified Directory.
**
** Params:  $value - Directory name
**          $key - Array key (undefined)
**          $depth - Indent depth
**          $icon - Icon filename
**          $link - TRUE if Directory should be linked; FALSE otherwise
*/
function dirTag($value, $key, $depth, $icon = ICON_CLOSED, $link = TRUE) {
   global $base;
   // initialize context
   $path = basePath($value, ($depth - 1));
   // emit the HTML
   echo "<tr><td align=\"left\" valign=\"bottom\" width=\"100%\">\n";
   // indent as required
   indentTag($depth);
   // emit the HTML
   echo "<img align=\"bottom\" src=\"" . scriptURL($icon) . "\" alt=\"$value\">";
   if($link) {
      echo "<a href=\"" . scriptURL(basename($_SERVER["PHP_SELF"])) . "?DPI=" . AGENT_DPI;
      if(strcmp(TEXT_ROOT, $value))
         echo "&path=" . urlencode($path);
      echo "\">";
   }
   echo "<b>$value</b>";
   echo "</a>";
   echo "</td><td class=\"delete\" align=\"right\" valign=\"bottom\">\n";
   if(SUPPORT_DELETE) {
      if(!(strcmp($icon, ICON_CLOSED)) && isEmpty($path))
         echo "<a href=\"javascript:deletePath('" . $path . "')\">" . TEXT_DELETE . "</a>";
   }
   echo "</td></tr>\n";
}


/*
** Lists a Path.
*/
function doList() {
   global $dirs;
   $exts = array("gif", "jpg", "jpeg", "png", "GIF", "JPG", "JPEG", "PNG");
   // initialize context
   $nodes = 0;
   $current = (count($dirs) + 1);
   // emit the HTML
   echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">\n";
   dirTag(TEXT_ROOT, "", $nodes++, ICON_OPENED);
   // for ALL Directories in the Path ...
   foreach($dirs as $dir) {
      // ... if Directory is exists ...
      if(strlen($dir) > 0)
         // ... emit the HTML
         dirTag($dir, "", $nodes++, ICON_OPENED, ($nodes != $current));
   }
   // list Directories and emit the HTML
   $list = listDirs();
   @array_walk($list, dirTag, $nodes);
   // if BITMAP support is desired ...
   if(SUPPORT_BITMAP)
      // ... include BMP extensions
      $exts[] = "bmp";
   // if METAFILE support is desired ...
   if(SUPPORT_METAFILE)
      // ... include WMF extensions
      $exts[] = "wmf";
   // list Image files and emit the HTML
   $list = listFiles($exts);
   @array_walk($list, fileTag, $nodes);
   // emit the HTML
   echo "</table>\n";
}


/*
** Emits the appropriate HTML for the specified File.
**
** Params:  $value - File name
**          $key - Array key (undefined)
**          $depth - Indent depth
*/
function fileTag($value, $key, $depth) {
   // initialize context
   $file = basePath($value);
   $size = @imageInfo(IMAGE_DIR . $file);
   // if Image size and type are available ...
   if($size) {
      // ... emit the HTML
      echo "<tr><td align=\"left\" valign=\"bottom\" width=\"100%\">\n";
      // indent as required
      indentTag($depth);
      // emit the HTML
      echo "<img align=\"bottom\" src=\"";
      switch($size[2]) {
      case IMAGE_GIF:
         echo scriptURL("gif.gif");
         break;
      case IMAGE_JPG:
         echo scriptURL("jpg.gif");
         break;
      case IMAGE_PNG:
         echo scriptURL("png.gif");
         break;
      case IMAGE_BMP:
         echo scriptURL("bmp.gif");
         break;
      case IMAGE_WMF:
         echo scriptURL("wmf.gif");
         break;
      }
      echo "\" alt=\"" . $value . "\">";
      echo "<a href=\"" . "viewer.php?DPI=" . AGENT_DPI . "&file=" . urlencode($file) . "\" target=\"" . VIEWER_NAME . "\">";
      echo $value;
      echo "</a>\n";
      echo "</td><td class=\"delete\" align=\"right\" valign=\"bottom\">\n";
      if(SUPPORT_DELETE) {
         echo "<a href=\"javascript:deletePath('" . $file . "')\">" . TEXT_DELETE . "</a>";
      }
      echo "</td></tr>\n";
   }
}

/*
** Emits the appropriate HTML for an Indent.
**
** Params:  $depth - Indent depth
*/
function indentTag($depth) {
   // if Indent is desired ...
   if($depth > 0) {
      $size = @getImageSize(SCRIPT_DIR . ICON_INDENT);
      // ... emit the HTML
      echo "<img src=\"" . scriptURL(ICON_INDENT) . "\" width=\"" . ($size[0] * $depth) . "\" height=\"" . $size[1] . "\">";
   }
}

/*
** Returns the empty status of the specified Directory.
**
** Params:  $path    - Path to check
**
** Return:  TRUE if Directory is empty; FALSE otherwise
*/
function isEmpty($path) {
   // initialize context
   $empty = TRUE;
   // if the Directory opens ...
   if(($dir = @opendir(IMAGE_DIR . $path))) {
      // ... while Files remain ...
      while($empty && FALSE !== ($file = readdir($dir))) {
         // ... if NOT hierarchy entries ...
         if($file != "." && $file != "..")
            // ... indicate NOT empty
            $empty = FALSE;
      }
      // close the Directory
      closedir($dir);
   }
   // return the status
   return $empty;
}

/*
** Returns an array of the Directories within the specified path.
**
** Return:  Array of Directory names
*/
function listDirs() {
   global $base;
   $result = array();
   // if the Directory opens ...
   if(($dir = @opendir(IMAGE_DIR . $base))) {
      // ... while Files remain ...
      while(FALSE !== ($file = readdir($dir))) {
         // ... if NOT hierarchy entries ...
         if($file != "." && $file != "..") {
            // ... if File is a Directory ...
            if(is_dir(IMAGE_DIR . basePath($file)))
               // ... return the Directory
               $result[] = $file;
         }
      }
      // close the Directory
      closedir($dir);
   }
   // return the Directories
   return $result;
}

/*
** Returns an array of the Files within the specified path.
**
** Params:  $filter - Extension filter(s)
**
** Return:  Array of File names
*/
function listFiles($filter = Array()) {
   global $base;
   $result = Array();
   // if the Directory opens ...
   if(($dir = @opendir(IMAGE_DIR . $base))) {
      $filters = count($filter);
      // ... while Files remain ...
      while(FALSE !== ($file = readdir($dir))) {
         // ... if File is NOT a Directory ...
         if(!(is_dir(IMAGE_DIR . basePath($file)))) {
            // ... if Filters were specified ...
            if($filters > 0) {
               $compare = (isWindows() ? "strcasecmp" : "strcmp");
               // ... isolate the Extension
               $parts = pathinfo(IMAGE_DIR . basePath($file));
               $ext = $parts["extension"];
               // for ALL specified Filters ...
               for($index = 0; $index < $filters; $index++) {
                  // ... if this a Filtered extension ...
                  if(!($compare($ext, $filter[$index])))
                     // ... get out now!
                     break;
               }
               // if File is NOT to be included ...
               if($index >= $filters)
                  // ... continue listing Files
                  continue;
            }
            // return the File
            $result[] = $file;
         }
      }
      // close the Directory
      closedir($dir);
   }
   // return the Files
   return $result;
}
// process GET/POST parameters
$action = "";
if(isset($_GET["action"]))
   $action = urldecode($_GET["action"]);
else if(isset($_POST["action"]))
   $action = urldecode($_POST["action"]);
$aspect = false;
$file = "";
$folder = "";
$height = 0;
$name = "";
$path = "";
if(isset($_GET["path"]))
   $path = urldecode($_GET["path"]);
else if(isset($_POST["path"]))
   $path = urldecode($_POST["path"]);
$width = 0;

// parse and clean the Path
cleanPath($path);

?>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
// list the Path
doList();
// emit the HTML
echo "<script language=\"javascript\">\n";
?>
actionComplete("<?php echo $action; ?>", "<?php echo $path; ?>", "<?php echo $error; ?>", "<?php echo $info; ?>");
</script>
</body>
</html>
