<?php

   /* $Id: glossary.php,v 1.4 2005/03/11 16:18:16 mgifford Exp $ */

   require('./config.php');

   $pagetitle = "Glossary"; // header title
   $xsiteobject = "Glossary";
   // Defines The META TAG Page Type

   // page_open(array("sess"=>"slashSess","auth"=>"slashAuth","perm"=>"slashPerm"));

   /*****************************
    START OF PAGE
    *****************************/
   $ary = array();
   if (!empty($_GET)) {
      $ary = clean($_GET);
   }

   if ((empty($ary['section'])) AND (empty($ary['section_id']))) {
      $section = $_PSL['site_homesection'];
      if (!$section) {
         $section = "Home";
      }
      $ary['section'] = $section;
   } else {
      $pagetitle .= " - $section";
   }

   /* DEBUG */

   // debug("HTTP_POST_VARS", $_POST);
   // debug("HTTP_GET_VARS", $_GET);
   // debug("glossary_ary", $glossary_ary);
   // debug("glossary_id", $glossary_id);
   // debug("glossary_term", $glossary_term);
   // debug("glossary_def", $glossary_def);

   /* DEBUG */

   $glossary = pslNew("Glossary");

   if (empty($ary['search'])) {
      $ary['search'] = '';
   }
   $content = $glossary->searchGlossary($ary['search']);
   $block = pslNew("Block_i");

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