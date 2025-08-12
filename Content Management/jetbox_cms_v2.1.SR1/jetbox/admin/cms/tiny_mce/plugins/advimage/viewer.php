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
TD.label { <?php echo VIEWER_STYLE; ?> }
TD.none { <?php echo VIEWER_NONE; ?> }
</style>
</head>
<?php
/*
** Emits the appropriate HTML for the specified Image.
**
** Params:  $file - File name
*/
function imageTag($file) {
	global $size;
   // if Image was specified ...
   if(strlen($file) > 0) {

      // ... initialize context
      if(($bytes = filesize(IMAGE_DIR . $file)) < 1024)
         $bytes = 1024;
      $size = @imageInfo(IMAGE_DIR . $file);

      // if Image size and type are available ...
      if($size) {

         // ... assume actual Image size
         $width = $size[0];
         $height = $size[1];

         // if Image should be scaled ...
         if($width > PANE_WIDTH || $height > PANE_HEIGHT) {

            // ... calculate scaling factor
            $dx = (PANE_WIDTH / $width);
            $dy = (PANE_HEIGHT / $height);
            $ratio = min($dx, $dy);

            // keep aspect ratio
            $width = (int) ($width * $ratio);
            $height = (int) ($height * $ratio);
         }

         // emit the HTML
				 /*
         echo "<table align=\"center\" border=\"0\" cellspacing=\"" . VIEWER_SPACING . "\" cellpadding=\"" . VIEWER_PADDING . "\" width=\"100%\" height=\"100%\">\n";
         echo "  <tr>\n";
         echo "    <td class=\"label\" align=\"center\" valign=\"middle\" width=\"100%\">\n";
         echo "      <b>" . basename($file) . "</b><br>\n";
         echo "      " . number_format((($bytes + 511) / 1024)) . " KB&nbsp;&nbsp;<i>($size[0] &#215; $size[1])</i>\n";
         echo "    </td>\n";
         echo "  </tr>\n";
         echo "  <tr>\n";
         echo "    <td align=\"center\" valign=\"middle\" width=\"100%\" height=\"100%\">\n";
         echo "      <img src=\"" . imageURL($file) . "\" alt=\"" . imageURL($file) . "\" width=\"$width\" height=\"$height\">\n";
         echo "    </td>\n";
         echo "  </tr>\n";
         echo "</table>\n";
*/
         // get out now!
         return;
      }
   }

   // emit the HTML
	 /*
   echo "<table align=\"center\" border=\"0\" cellspacing=\"" . VIEWER_SPACING . "\" cellpadding=\"" . VIEWER_PADDING . "\" width=\"100%\" height=\"100%\">\n";
   echo "  <tr>\n";
   echo "    <td class=\"none\" align=\"center\" valign=\"middle\" width=\"100%\" height=\"100%\">\n";
   echo "      " . TEXT_SELECT . "\n";
   echo "    </td>\n";
   echo "  </tr>\n";
   echo "</table>\n";
	 */
}

// process GET/POST parameters
$file = "";
if(isset($_GET["file"]))
   $file = urldecode($_GET["file"]);

// parse and clean the File
cleanPath($file);
?>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>
<?php
// generate the Preview
imageTag($base);
?>
<script language="javascript">
var src = '<?php echo ((strlen($file) > 0) ? imageURL($file, TRUE) : ""); ?>';

if(src.length > 0) {
   //var manager = findAncestor(window.frameElement, '<?php echo MANAGER_NAME; ?>', '<?php echo MANAGER_TAG; ?>');
	//a=parent.MM_findObj('src');
	//a.value=src;
	//parent.showPreview_tree(\'".$qarray2["img_id"]."\',\'".$qarray2["name"]."\',\'".$qarray["delete_option"]."\',\'".$front_end_url."\');

	parent.document.getElementById('previewimgname').innerHTML='<b><?echo basename($file);?></b><br><? echo $size[0]." &#215; ".$size[0]?>';
	parent.showLayer('preview');
	parent.hideLayer('emptyheader');
	parent.hideLayer('loading');
	if(parent.document.getElementById('upload')){
		parent.hideLayer('upload');
	}
	if(parent.document.getElementById('txtFileName')){
		parent.document.getElementById('txtFileName').value= src;
	}
	if(parent.document.forms[0].src){
		parent.document.forms[0].src.value= src
	}
	parent.document.images['invis'].src= src;
	parent.resize();
   //if(manager)
   //   manager.all.<?php echo MANAGER_SRC; ?>.value = src;
}
</script>
</body>
</html>
