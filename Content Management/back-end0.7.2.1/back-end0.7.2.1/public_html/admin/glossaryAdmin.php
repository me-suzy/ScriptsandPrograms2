<?php

   /* $Id: glossaryAdmin.php,v 1.12 2005/04/13 15:05:14 mgifford Exp $ */

   require('./config.php');

   $pagetitle = pslgetText('Glossary Administration'); // header title
   $xsiteobject = pslgetText('Administration'); // Defines The META TAG Page Type
   $_PSL['metatags']['object'] = $xsiteobject;

   $content = null;
   $ary = array();

   /*****************************
    START OF PAGE
    *****************************/

   $auth->login_if(!$perm->have_perm('glossaryList'));

   /* DEBUG */

   # debug("HTTP_POST_VARS", $_POST);
   # debug("HTTP_GET_VARS", $_GET);
   # debug("glossary_ary", $glossary_ary);
   # debug("glossary_id", $glossary_id);
   # debug("glossary_term", $glossary_term);
   # debug("glossary_def", $glossary_def);

   /* DEBUG */

   $glossary = pslNew('Glossary');

   if (!empty($_POST['submit'])) {
      $submit = pslgetText(getRequestVar('submit', 'P'), '', true);
      $glossary_ary = clean($_POST['glossary_ary'], true);
      $glossary_id = clean($_POST['glossary_id']);
      $glossary_term = clean($_POST['glossary_term']);
      $glossary_def = clean($_POST['glossary_def'], true);
   } elseif (!empty($_GET['submit'])) {
      $submit = pslgetText(getRequestVar('submit', 'G'), '', true);
   } else {
      $submit = '';
   }

   if ($perm->have_perm('glossaryList')) {
      switch ($submit) {

         case "delete":
         if ($perm->have_perm('glossaryDelete')) {
            while (list($key, $val) = each($glossary_ary)) {
               $glossary->deleteGlossary($glossary_ary[$key]);
            }
         }
         break;
         case "new":
         if ($perm->have_perm('glossarySave')) {
            $glossary->saveGlossary(clean($_POST));
         }
         break;
         case "update":
         reset ($glossary_id);
         if ($perm->have_perm("glossarySave")) {
            while (list($key, $val) = each($glossary_id)) {
               $ary["id"] = $glossary_id[$key];
               $ary["term"] = $glossary_term[$key];
               $ary["def"] = $glossary_def[$key];

               if ($glossary->saveGlossary($ary)) {
                  $content .= "<em>$glossary_term[$key]</em> ".pslgetText("has been updated")."<br />\n";
               } else {
                  $content .= "<em>$glossary_term[$key]</em> ".pslgetText("has not been updated")."<br />\n";
               }

            }
         }
         break;
         default:
         break;
      }
      if ($perm->have_perm("glossaryNew")) {
         $content .= $glossary->newGlossary();
      }
      if ($perm->have_perm("glossaryList")) {
         $content .= $glossary->listGlossary();
      }

   } else {

      $content .= $glossary->searchGlossary(clean($_GET['search']));

   }

   $ary['section'] = 'admin';

   $chosenTemplate = getUserTemplates('',$ary['section']);

   $breadcrumb = '<a href="' . $_PSL['rooturl'] . '/">' . pslgetText('Home') . '</a> &#187; ' . $pagetitle;

   // generate the page
   generatePage($ary, $pagetitle, $breadcrumb, $content);

   page_close();

?>