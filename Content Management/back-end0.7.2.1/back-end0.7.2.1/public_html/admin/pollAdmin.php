<?php

   /* $Id: pollAdmin.php,v 1.18 2005/04/13 15:05:14 mgifford Exp $ */

   require('./config.php');

   $pagetitle = pslgetText('Poll Administration'); // The name to be displayed in the header
   $xsiteobject = pslgetText('Administration');    // Defines The META TAG Page Type
   $_PSL['metatags']['object'] = $xsiteobject;

   $content = null;
   $ary = array();
   $min = null;

   /*****************************
    START OF PAGE
    *****************************/

   $auth->login_if(!$perm->have_perm('pollList'));

   /* DEBUG STUFF */
   # debug("HTTP_POST_VARS" , $_POST);
   # debug("HTTP_GET_VARS" , $_GET);
   /* END DEBUG STUFF */

   $poll = pslNew('Poll');

   if (!empty($_POST['submit'])) {
      $submit = pslgetText(getRequestVar('submit', 'P'), '', true);
      $submit = (isset($_POST['submit'])) ? clean($_POST['submit']) : NULL;
      $question_id = (isset($_POST['question_id'])) ? clean($_POST['question_id']) : NULL;
   } elseif (!empty($_GET['submit'])) {
      $submit = pslgetText(getRequestVar('submit', 'G'), '', true);
      $question_id = (isset($_GET['question_id'])) ? clean($_GET['question_id']) : NULL;
      $min = (isset($_GET['min'])) ? clean($_GET['min']) : NULL;
   } else {
      $submit = NULL;
   }

   switch ($submit) {
   case 'edit':
      if ($perm->have_perm('pollEdit')) {
         $content .= $poll->editPoll($question_id);
      }
      break;
   case 'new':
      if ($perm->have_perm('pollNew')) {
         $content .= $poll->newPoll();
      }
      break;
   case 'save':
      if ($perm->have_perm('pollPut')) {
         if ($question_id = $poll->savePoll(clean($_POST))) {
            $content .= '<div align="center">';
#            $content .= getFancybox(210, $_PSL['site_name'] ."Poll", $poll->getPollBooth(clean($_POST['question_id'])), "r");
            $content .= getFancybox(210, $_PSL['site_name'] .'Poll', $poll->getPollBooth($question_id), 'r');
            $content .= '</div>';

            // expire cache for this question_id
            //  do this only if caching is currently enabled
            if (function_exists('jpcache_gc')) {
#               jpcache_gc('string', "-question_id-" . $_POST['question_id'], "100");
               jpcache_gc('string', '-question_id-' . $question_id, '100');
            }
         } else {
            $content .= getError($poll->message);
         }
      }
      break;
   case 'delete':
      if ($perm->have_perm('pollDelete')) {
         $content .= getTitlebar ('100%', 'Deleting poll');
         if ($poll->deletePoll($question_id)) {
            $content .= getMessage($poll->message);
         } else {
            $content .= getError($poll->message);
         }
      }
   case 'makecurrent':
      if ($perm->have_perm('pollPut')) {
         if (!$poll->makeCurrent($question_id)) {
            $content .= getError($poll->message);
         }
      }
   default:
      if ($perm->have_perm('pollList')) {
         $content .= $poll->listPolls($min);
      }
   }

   if ($content == '') {
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