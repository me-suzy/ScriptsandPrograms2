<?php

   /* $Id: storyAdmin.php,v 1.6 2005/03/11 16:18:23 mgifford Exp $ */

   require('./config.php');


   $pagetitle = pslgetText('Story Administration'); // The name to be displayed in the header
   $xsiteobject = pslgetText('Administration');     // Defines The META TAG Page Type

   if (!is_object($sess)) {
      page_open($_PSL['page_features']);
   }

   /*****************************
    START OF PAGE
    *****************************/


   if (!empty($_POST['submit'])) {
      $submit = clean($_POST['submit']);
      $ary = clean($_POST, true);
   } elseif (!empty($_GET['submit'])) {
      $submit = clean($_GET['submit']);
      $story_id = clean($_GET['story_id']);
      $next = clean($_GET['next']);
      $ary = clean($_GET);
   } else {
      $submit = '';
   }

   $auth->login_if(!$perm->have_perm('storyList'));

   $content = '';

   $story = pslNew("Story_admin");

   if ($perm->have_perm("storyList")) {

      switch ($submit) {

         case "delete":
         if ($perm->have_perm("storyDelete")) {
            if (!$story->deleteStory($story_id)) {
               $content .= getError(pslgetText("Sorry. You do not have the necessary privilege to view this page."));
            }
         }
         if ($perm->have_perm("storyList")) {
            $content .= $story->listStory($ary, $next);
         }
         break;

         case "save":
         if ($perm->have_perm("storySave")) {
            $success = $story->saveStory(clean($_POST));
            if ($success == false) {
               $content .= getError($story->message);
               $content .= $story->newStory(clean($_POST), 'array');
            }

            // expire cache for this story_id
            jpcache_gc('string', "-story_id-" . clean($_POST['story_id']), "100");
            // expire cache for these section_id's
            $section_id_ary = clean($_POST['section_id_ary']);
            foreach($section_id_ary as $key => $value) {
               jpcache_gc('string', "-section_id-" . $value, "100");
            }
         }
         if ($perm->have_perm("storyList")) {
            $content .= $story->listStory($ary, $next);
         }
         break;

         case "modify":
         if ($perm->have_perm("storyList")) {
            $content .= $story->listStory($ary, $next);
         }
         break;

         case "preview":
         $_POST['name'] = clean($_POST['author_id']);
         $content .= $story->showStory(clean($_POST));
         $content .= getTitlebar("100%", "Edit Story");
         $_POST['title'] = stripslashes(clean($_POST['title']));
         $_POST['intro_text'] = stripslashes(clean($_POST['intro_text']));
         $_POST['body_text'] = stripslashes(clean($_POST['body_text']));
         $content .= $story->newStory(clean($_POST), 'array');
         break;

         case "edit":
         if ($perm->have_perm("storyEdit")) {
            $_POST['story_id'] = $story_id;
            $returned = $story->newStory(clean($_POST), "database");
            if (isset($returned)) {
               $content .= $returned;
            } else {
               $content .= getError(pslgetText("Sorry. You do not have the necessary privilege to view this page."));
            }
         }
         break;

         case "new":
         if ($perm->have_perm("storyNew")) {
            $content .= $story->newStory(clean($_POST), "array");
         }
         break;

         default:
         if ($perm->have_perm("storyList")) {
            $content .= $story->listStory($ary, $next);
         }
      }
   } else {
      $content = getTitlebar("100%", "Error! Invalid Privileges");
      $content .= getError(pslgetText("Sorry. You do not have the necessary privilege to view this page."));
   }

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