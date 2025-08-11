<?php

   /* $Id: topicAdmin.php,v 1.6 2005/03/11 16:18:23 mgifford Exp $ */

   require('./config.php');

   $pagetitle = pslgetText('Topic Administration'); // header title
   $xsiteobject = pslgetText('Administration');     // Defines The META TAG Page Type

   if (!is_object($sess)) {
      page_open($_PSL['page_features']);
   }

   /*****************************
    START OF PAGE
    *****************************/

   /* DEBUG */

   debug("HTTP_POST_VARS", $_POST);
   debug("HTTP_GET_VARS", $_GET);
   debug("topic_name", $topic_name);
   debug("topic_image", $topic_image);
   debug("alt_text", $alt_text);
   debug("topic_width", $topic_width);
   debug("topic_height", $topic_height);
   debug("onlinkbar", $onlinkbar);

   /* DEBUG */

   $auth->login_if(!$perm->have_perm('topicList'));

   $topic = pslNew("Topic");

   $content = '';

   if (!empty($_POST['submit'])) {
      $submit = clean($_POST['submit']);
   } elseif (!empty($_GET['submit'])) {
      $submit = clean($_GET['submit']);
      $topic_id = clean($_GET['topic_id']);
      $option = clean($_GET['option']);
   } else {
      $submit = '';
   }

   if ($perm->have_perm("topicList")) {
      switch ($submit) {

         case "delete":
         if ($perm->have_perm("topicDelete")) {
            if ($topic->deleteTopic($topic_id)) {
               $content .= getMessage($topic->getMessage());
            } else {
               $content .= getError($topic->getMessage());
            }
         }
         if ($perm->have_perm("topicNew")) {
            $content .= $topic->newTopic();
         }
         break;
         case "submit":
         if ($perm->have_perm("topicSave")) {
            if ($topic->saveTopic(clean($_POST, true))) {
               $content .= getMessage($topic->getMessage());
            } else {
               $content .= getError($topic->getMessage());
            }
         }
         if ($perm->have_perm("topicNew")) {
            $content .= $topic->newTopic();
         }
         break;

         case "edit":
         if ($perm->have_perm("topicEdit")) {
            $ary["topic_id"] = $topic_id;
            $content .= $topic->newTopic($ary);
         }
         break;

         default:
         if ($perm->have_perm("topicNew")) {
            $content .= $topic->newTopic();
         }
         break;
      }
      if ($perm->have_perm("topicList")) {
         $content .= $topic->listTopic();
         $content .= $topic->displayTopics($option);
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

   $template = pslNew('slashTemplate', $_PSL['templatedir']);
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