<?php

   require('./config.php');

   // Uploaded files use $_GET['ID']
   if (isset($_GET['ID']) && !empty($_GET['ID'])) {

      $imageObj = pslNew('BE_Image');
      $image = $imageObj->generateImage(clean($_GET['ID']));

      if (!empty($image)) {
         Header('Content-type: image/jpeg');
         echo $image;
      } else {
         echo pslgetText('No image available.') . ' ';
      }

   }

   // Gallery images use $_GET['imageID']
   elseif (isset($_GET['imageID']) && !empty($_GET['imageID'])) {

      $imageFile = $_BE['uploaddir'] . '/images/' . clean($_GET['imageID']) . '.jpg';

      // Is the image cached on the hard drive or stored in the database
      if (is_readable ($imageFile)) {
         header('Location: ' . $imageFile);
      } else {
         $galleryObj = pslNew('BE_Gallery');
         Header('Content-type: image/jpeg');
         echo $galleryObj->displayImage(clean($_GET['imageID']));
      }

   } else {
      echo pslgetText('Requires ID or imageID parameter.') . ' ';
   }

?>