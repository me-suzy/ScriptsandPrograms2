<?php

   /* $Id: PSL_poll.php,v 1.5 2005/03/11 16:18:15 mgifford Exp $ */

   require('./config.php');

   $pagetitle = pslgetText("Polls");
   // The name to be displayed in the header
   $xsiteobject = pslgetText("Poll Booth"); //Defines The META TAG Page Type

   // page_open(array("sess"=>"slashSess","auth"=>"slashAuth","perm"=>"slashPerm"));

   $ary = array();
   if (!empty($_GET)) {
      $ary = clean($_GET);
   }

   if ((empty($ary['section'])) AND (empty($ary['section_id']))) {
      $section = $_PSL['site_homesection'];
      if (!$section) {
         $section = 'Home';
      }
      $ary['section'] = $section;
   }

   /* the comment stuff is using "story_id" so we have to funky
    fix it here. */
   if (empty($ary['question_id']) && !empty($ary['story_id'])) {
      $question_id = $ary['story_id'];
   } elseif(!empty($ary['question_id'])) {
      $question_id = $ary['question_id'];
   } else {
      $question_id = '';
   }

   $as = arg_separator("1");
   # Default: = &amp
   $poll = pslNew("Poll");

   # debug("HTTP_POST_VARS", $_POST);
   # debug("HTTP_GET_VARS", $_GET);


   /* setting up the possible comment variables... */
   if (!empty($ary['mode'])) {
      $cmtary['mode'] = $ary['mode'];
   } else {
      $cmtary['mode'] = '';
   }
   if (!empty($ary['order'])) {
      $cmtary['order'] = $ary['order'];
   } else {
      $cmtary['order'] = '';
   }
   $cmtary['question_id'] = $question_id;

   $content = '';

   if (empty($ary['submit'])) {
      $ary['submit'] = '';
   } else {
      $ary['submit'] = pslgetText($ary['submit'], '', true);
   }

   switch ($ary['submit']) {
      case "vote":
      if ($poll->vote($question_id, $ary['answer_id'], $_SERVER['REMOTE_ADDR'])) {
         $content .= pslgetText('VOTE: ') . $poll->message . "<br />\n";
      } else {
         $content .= getError($poll->getMessage());
      }
      /* NOTE:  there's no "break" here, cause after you vote, we
       roll down and "viewresults" */

      case "viewresults":

      /* we register the "return link" in case they post a comment */
      $return_link = $_SERVER["REQUEST_URI"];
      $sess->register("return_link");

      $content .= $poll->resultPage($cmtary);
      break;

      case "viewbooth":
      $content .= getTitlebar ("100%", pslgetText("View Pollbooth"));
      $content .= "<div align=\"center\">\n";
      $content .= getFancybox (210, sprintf(pslgetText("%s Poll"), $_PSL['site_name']), $poll->getPollBooth($question_id), "nc");
      $content .= "</div>\n";
      break;

      case "list":
      $content .= $poll->listPolls ($ary['min']);
      break;

      default:
      if ($question_id) {
         $content .= $poll->resultPage($cmtary);
      } else {
         if (empty($ary['min'])) {
            $ary['min'] = '';
         }
         $content .= $poll->listPolls ($ary['min']);
      }
   }

   $block = pslNew("Block_i");

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
      // 'BREADCRUMB'   => $breadcrumb,
   'STORY_COLUMN' => $content,
      'LEFT_BLOCK_COLUMN' => $leftblocks,
      'CENTER_BLOCK_COLUMN' => $centerblocks,
      'RIGHT_BLOCK_COLUMN' => $rightblocks ));

   $template->parse('OUT', 'index');
   $template->p('OUT');

   slashfoot();
   page_close();

?>