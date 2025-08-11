<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<?php

   /******************************************************************************
    function   : clean->removes nasty things that hurt databases
    Parameters : $dirty->string or array to clean up
    $allow_html->if true, then we don't convert HTML characters
    like < and > into &gt; and &lt;
    *******************************************************************************/
   function clean($dirty, $allow_html = false) {
      if (empty($dirty)) {
         return NULL;
      }
      if (is_array($dirty)) {
         foreach ($dirty as $key => $val) {
            if (is_scalar($val)) { //PAC Hack for BE_History which passes arrays through GET
               if ($allow_html) {
                  $clean[$key] = str_replace("'", "&#039;", (stripslashes($val)));
               } else {
                  $clean[$key] = str_replace("'", "&#039;", (htmlspecialchars(stripslashes($val))));
               }
            } elseif (is_array($val)) {
               $clean[$key] = clean($val, $allow_html);
            } else { // Ignore $val where null or Object
               $clean[$key] = NULL;
            }
         }
      } else { // assume its a string
         if ($allow_html) {
            $clean = str_replace('\'', '&#039;', (stripslashes($dirty)));
         } else {
            $clean = str_replace('\'', '&#039;', (htmlspecialchars(stripslashes($dirty))));
         }
      }

      return $clean;
   }

   $image = clean($_GET['image']);
   $hiResImage = clean($_GET['hiResImage']);
   $hiResWidth = clean($_GET['hiResWidth']);
   $hiResHeight = clean($_GET['hiResHeight']);
   $title = urldecode(clean($_GET['title']));
   $width = clean($_GET['width']) - 40;
   $height = clean($_GET['height']) - 110;
?>
<html>
<head>
<meta http-equiv="content-style-type" content="text/css" />
<link rel="stylesheet" type="text/css" href="/templates/en/BE_default/v3.css" />
<title>Image: <?php echo $title; ?></title>
</head>
<body>
<div id="article">
<p style="width:100%;padding: 0px 0px 0px 0px;">
<img src="<?php echo $image; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>">
<?php echo $title; ?></p>
<form>
<?php
if($hiResImage!='') {
   $title = urlencode($title);
   echo "<input type=\"button\" value=\"High-resolution Version\" onClick=\"window.open('/showimage.php?image=$hiResImage&width=$hiResWidth&height=$hiResHeight&title=$title');\">\r\n";
}
?>
<input type="button" value="Close" onClick='window.close()'>
</form>
</p>
</div>
</body>
</html>
