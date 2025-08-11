<?php
   // $Id: sectionAdmin.php,v 1.10 2005/04/13 15:05:15 mgifford Exp $

   require('./config.php');

   # header title
   $pagetitle = pslgetText('Administration');

   #Defines The META TAG Page Type
   $xsiteobject = pslgetText('Administration');

   $content = null;
   $ary = array();

   if (!is_object($sess)) {
      page_open($_PSL['page_features']);
   }

   /*****************************
    START OF PAGE
    *****************************/

   $auth->login_if(!$perm->have_perm('sectionList'));

   # debug("HTTP_POST_VARS", $_POST);
   # debug("HTTP_GET_VARS", $_GET);
   # debug("section_del", $section_del );
   # debug("section_ary", $section_ary );
   # debug("description", $description );

   $section = pslNew('Section');

   if (!empty($_POST['submit'])) {
      $submit = pslgetText(getRequestVar('submit', 'P'), '', true);
   } elseif (!empty($_GET['submit'])) {
      $submit = pslgetText(getRequestVar('submit', 'G'), '', true);
      $section_id = clean($_GET['section_id']);
   } else {
      $submit = '';
   }

   if ($perm->have_perm('sectionList')) {
      switch ($submit) {

         case 'delete':
         if ($perm->have_perm("sectionDelete")) {
            $success = $section->deleteSection($section_id);
            if ($success) {
               $content .= getMessage($section->getMessage());
            } else {
               $content .= getError($section->getMessage());
            }
         }
         break;
         case 'edit':
         if ($perm->have_perm('sectionEdit')) {
            $ary["section_id"] = $section_id;
         }
         break;
         case 'update':
         if ($perm->have_perm('sectionSave')) {
            $success = $section->saveSection(clean($_POST, true));
            debug('message', $section->getMessage());
            if ($success) {
               $content .= getMessage($section->getMessage());
            } else {
               $content .= getError($section->getMessage());
            }
         }
         break;
         default:
      }
      if ($perm->have_perm('sectionNew')) {
         $content .= $section->newSection($ary);
      }

      if ($perm->have_perm('sectionList')) {
         $content .= $section->listSection();
      }
   } else {
      $content = getTitlebar('100%', 'Error! Invalid Privileges');
      $content .= 'Sorry. You do not have the necessary privilege to view this page.';
   }

   $ary['section'] = 'admin';

   $chosenTemplate = getUserTemplates('',$ary['section']);

   $breadcrumb = '<a href="' . $_PSL['rooturl'] . '/">' . pslgetText('Home') . '</a> &#187; ' . $pagetitle;

   // generate the page
   generatePage($ary, $pagetitle, $breadcrumb, $content);

   page_close();

?>