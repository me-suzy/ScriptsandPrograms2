<?php
   // $Id: submission.php,v 1.6 2005/03/11 16:18:18 mgifford Exp $

   require('./config.php');

   $pagetitle = pslgetText('Submission');
   # The name to be displayed in the header
   $xsiteobject = pslgetText('Submission');
   #Defines The META TAG Page Type

   // page_open(array("sess"=>"slashSess","auth"=>"slashAuth","perm"=>"slashPerm"));

   // $auth->login_if(!$perm->have_perm('submissionNew'));

   $ary = array();
   if (!empty($_GET)) {
      $ary = clean($_GET);
   }
   $ary_post = array();
   if (!empty($_POST)) {
      $ary_post = clean($_POST);
   }

   if (!empty($ary_post['cookie'])) {
      $submission_name = $ary_post['name'];
      $submission_email = $ary_post['email'];
      $sess->register("submission_name");
      $sess->register("submission_email");
   }

   /*****************************
    START OF PAGE
    *****************************/

   $_PSL['metatags']['object'] = $xsiteobject;

   $content = '';

   // debug("HTTP_POST_VARS" , $_POST);
   // debug("HTTP_GET_VARS" , $_GET);
   // debug("topic_id_ary" , $topic_id_ary);
   // debug("submission_id_ary" , $submission_id_ary);

   $submission = pslNew("Submission");

   if (empty($ary_post['submit'])) {
      $ary_post['submit'] = '';
   }

   switch ($ary_post['submit']) {

      case "save":
      $returned = $submission->saveSubmission(clean($_POST));
      if ($returned) {
         logwrite("Story Submission", "$REMOTE_ADDR submitted a story");
         $content .= pslgetText('Thanks for this submission.  We have it and will set our fearless editorial staff upon it right this second.') . "<br />\n";
         // send email for new submission.
         if ($_PSL['submitnotify']) {
            $ary['tpl'] = "emailNotifySubmission";
            $ary['vars'] = clean($_POST);
            $success = emailNotify($ary);
         }
      } else {
         $content .= "<div class=\"error\">".pslgetText("Something broke, I'm not sure what though??")."</div>\n";
      }
      break;

      case "preview":
      $content .= getTitlebar('100%', 'Submission Preview');
      $content .= $submission->showSubmission(clean($_POST));
      $content .= getTitlebar('100%', 'Edit Submission');
      $content .= $submission->newSubmission(clean($_POST), 'array');
      break;

      default:
      $content .= getTitlebar("100%", pslgetText("New Submission"));
      $content .= $submission->newSubmission("", "array");
   }
   /* end of switch */

   $block = pslNew("Block_i");

   if ((empty($ary['section'])) AND (empty($ary['section_id']))) {
      $section = $_PSL['site_homesection'];
      if (!$section) {
         $section = "Home";
      }
      $ary['section'] = $section;
   } else {
      $pagetitle .= " - $section";
   }

   $breadcrumb = breadcrumb($ary);

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
      'RIGHT_BLOCK_COLUMN' => $rightblocks ));

   $template->parse('OUT', 'index');
   $template->p('OUT');

   slashfoot();
   page_close();

?>