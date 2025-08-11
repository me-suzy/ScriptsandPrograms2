<?php
   require('./config.php');

   // Images transfered through file Upload use $_GET['ID']
   if (!empty($_GET['ID'])) {
      $tnObj = pslNew('Image');
      $tn = $tnObj->generateImage(clean($_GET['ID']));
      if (!empty($tn)) {
         Header("Content-type: image/jpeg");
         echo $tn;
      } else {
         echo "No Image Available";
      }
   }

   // Gallery images use $_GET['imageID']
   elseif (!empty($_GET['imageID'])) {
      $tnFile = $_BE['uploaddir'] . '/images/tn/' . clean($_GET['imageID']) . '.jpg';

      // Is the image cached on the hard drive or stored in the database
      if (is_readable ($tnFile)) {
         header('Location: ' . $tnFile); /* Redirect browser */
      } else {
         $galleryObj = pslNew('BE_Gallery');
         Header("Content-type: image/jpeg");
         echo $galleryObj->displayThumbnail(clean($_GET['imageID']));
      }
   } else {
      die("Need 'ID' or 'imageID' parameter");
   }
?>