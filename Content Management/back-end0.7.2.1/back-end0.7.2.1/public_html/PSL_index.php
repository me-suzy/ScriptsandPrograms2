<?php
   // $Id: PSL_index.php,v 1.4 2005/03/11 16:18:15 mgifford Exp $

   require('./config.php');

   $pagetitle = pslgetText('Home');
   // The name to be displayed in the header
   $xsiteobject = pslgetText('Home Page');
   // This Defines The META Tag Object Type

   if (!empty($_GET['login'])) {
      $auth->login_if($_GET['login']);
   }

   // Objects

   // $poll  = pslNew("Poll");
   $story = pslNew("Story");
   $block = pslNew("Block_i");
   // $db    = pslNew("slashDB");

   // Start of Page

   $breadcrumb = breadcrumb($ary);

   $_PSL['metatags']['object'] = $xsiteobject;

   slashhead($pagetitle, $_PSL['metatags']);

   $allstories = $story->getStories($ary);
   $leftblocks = $block->getBlocks($ary, "left");
   $centerblocks = $block->getBlocks($ary, "center");
   $rightblocks = $block->getBlocks($ary, "right");

   if (empty($leftblocks)) {
      if (empty($rightblocks)) {
         // $centerblocks  = $block->getBlocks($ary);
         // $tplfile = "index1col.tpl";
         // default to 2 column for transparent upgrade
         $rightblocks = $block->getBlocks($ary);
         $tplfile = 'index2colright.tpl';
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
      'STORY_COLUMN' => $allstories,
      'LEFT_BLOCK_COLUMN' => $leftblocks,
      'CENTER_BLOCK_COLUMN' => $centerblocks,
      'RIGHT_BLOCK_COLUMN' => $rightblocks ));

   $template->parse('OUT', 'index');
   $template->p('OUT');

   slashfoot();
   page_close();

?>