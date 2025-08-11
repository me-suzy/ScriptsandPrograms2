<?php
   // $Id: submissionAdmin.php,v 1.7 2005/03/11 16:18:23 mgifford Exp $

   require('./config.php');

   $pagetitle = pslgetText('Submission Administration');
   # The name to be displayed in the header
   $xsiteobject = pslgetText('Administration');
   #Defines The META TAG Page Type

   if (!is_object($sess)) {
      page_open($_PSL['page_features']);
   }

   if (isset($cookie)) {
      $submission_name = $name;
      $submission_email = $email;
      $sess->register("submission_name");
      $sess->register("submission_email");
   }

   /*****************************
    START OF PAGE
    *****************************/

   $auth->login_if(!$perm->have_perm('submissionEdit'));

   $content = '';

   // debug("HTTP_POST_VARS" , $_POST);
   // debug("HTTP_GET_VARS" , $_GET);
   // debug("topic_id_ary" , $topic_id_ary);
   // debug("submission_id_ary" , $submission_id_ary);

   $submission = pslNew("Submission");

   if (!empty($_POST['submit'])) {
      $submit = clean($_POST['submit']);
      $submission_id_ary = clean($_POST['submission_id_ary']);

   } elseif (!empty($_GET['submit'])) {
      $submit = clean($_GET['submit']);
      $submission_id = clean($_GET['submission_id']);
      $next = clean($_GET['next']);
   } else {
      $submit = '';
   }

   if ($perm->have_perm("submissionEdit")) {

      switch ($submit) {
         case "delete":
         if ($perm->have_perm("submissionDelete")) {
            $count = count($submission_id_ary);
            if ($count > 0 ) {
               for ($i = 0 ; $i < $count ; $i++) {
                  $submission->deleteSubmission($submission_id_ary[$i]);
               }
               $content .= $count.pslgetText(" record deleted")."<br />\n";
            } else {
               $content .= "<br /><br />".pslgetText("This would work SO much better if you actually selected something to delete!")."<br />\n";
            }
         }
         break;

         case "save":
         if ($perm->have_perm("submissionSave")) {
            if ($submission->saveSubmission(clean($_POST))) {
               logwrite("Story Submission", "$REMOTE_ADDR submitted a story as an admin");
               $content .= pslgetText('Submission Saved');
            } else {
               $content .= getError($submission->getMessage());
            }
         }
         break;

         case "preview":
         if ($perm->have_perm("submissionEdit")) {
            $content .= getTitlebar("100%", "Submission Preview");
            $content .= $submission->showSubmission(clean($_POST));
            $content .= getTitlebar("100%", "Edit Submission");
            $content .= $submission->newSubmission(clean($_POST), "array");
         }
         break;

         case "editasstory":
         if ($perm->have_perm("submissioneditasstory")) {
            if ($submission->editasStory($submission_id)) {
               $content .= $submission->getMessage();
               if ($_PSL['submission_autodelete']) {
                  $submission->deleteSubmission($submission_id);
               }
            }
         }
         break;

         case "edit":
         if ($perm->have_perm("submissionEdit")) {
            if ($submission->displaySubmission($submission_id)) {
               $content .= getTitlebar("100%", "Submission Preview");
               $content .= $submission->getMessage();
            }
            $content .= getTitlebar("100%", "Edit Submission");
            $ary['submission_id'] = $submission_id;
            $content .= $submission->newSubmission($ary, "database");
         }
         break;

         case "new":
         if ($perm->have_perm("submissionNew")) {
            $content .= $submission->newSubmission(clean($_POST), "array");
         }
         break;

         default:
         /*
          $returned = $submission->listSubmission($next);
          if ($returned) {
          $content .= $returned;
          } else {
          $content .= pslgetText("When you don't see the submission, it means there aren't any.");
          }
          */
         break;

      }
      /* end of switch */

      $returned = $submission->listSubmission($next);
      if ($returned) {
         $content .= getTitlebar("100%", "Current Submissions");
         $content .= $returned;
      } else {
         $content .= pslgetText("When you don't see the submission, it means there aren't any.");
      }

   } else {
      $content = getTitlebar("100%", "Error! Invalid Privileges");
      $content .= "Sorry. You do not have the necessary privilege to view this page.";
   }

   $ary = '';

   $block = pslNew("Block_i");
   $ary['section'] = "Admin";

   // $breadcrumb = breadcrumb($ary);

   $_PSL['metatags']['object'] = $xsiteobject;

   slashhead($pagetitle, $_PSL['metatags']);

   $leftblocks = $block->getBlocks($ary, "left");
   $centerblocks = $block->getBlocks($ary, "center");
   $rightblocks = $block->getBlocks($ary, "right");

   if (empty($leftblocks)) {
      if (empty($rightblocks)) {
         // $centerblocks  = $block->getBlocks($ary);
         $tplfile = "index1col.tpl";
      } else {
         $tplfile = 'index2colright.tpl';
      }
   } elseif (empty($rightblocks)) {
      $tplfile = 'index2colleft.tpl';
   } else {
      $tplfile = 'index3col.tpl';
   }

   $template = pslNew("slashTemplate", $_PSL['templatedir']);
   $template->debug = 0;
   $template->set_file(array(
   'index' => $tplfile //"index3col.tpl"
   ));

   if (!empty($GLOBALS['QUERY_STRING'])) {
      $QUERY_STRING = '?'.$GLOBALS['QUERY_STRING'];
   } else {
      $QUERY_STRING = '';
   }

   $template->set_var(array(
      'QUERYSTRING' => $QUERY_STRING,
      'ROOTDIR' => $_PSL['rooturl'],
      'IMAGEDIR' => $_PSL['imageurl'],
      'BREADCRUMB' => $breadcrumb,
      'STORY_COLUMN' => $content,
      'LEFT_BLOCK_COLUMN' => $leftblocks,
      'CENTER_BLOCK_COLUMN' => $centerblocks,
      'RIGHT_BLOCK_COLUMN' => $rightblocks
   ));

   $template->parse('OUT', 'index');
   $template->p('OUT');

   slashfoot();
   page_close();

?>