<?php

   // Profiles - default index display page

   require_once('./config.php');

   $pagetitle = pslgetText('View Profiles'); // The name to be displayed in the header
   $xsiteobject = 'Information';
   // Defines The META TAG Page Type


   /*****************************
    START OF PAGE
    *****************************/

   $pro = pslNew('BE_Profiles');

   if (isset($_GET['profileID']) && !empty($_GET['profileID'])) {
      $content = $pro->indexDetail(clean($_GET['profileID']));
   } else {
      $level = (isset($_GET['level'])) ? clean($_GET['level']) : '';
      $next = (isset($_GET['next'])) ? clean($_GET['next']) : '';
      $content = $pro->indexList($level, $next);
   }

   $ary = array();
   $ary['section'] = 'profilesView';

   // getUserTemplates();
   $chosenTemplate = getUserTemplates('', $ary['section']);

   generatePage($ary, $pagetitle, '', $content);
   page_close();

?>
