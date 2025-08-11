<?php

   /* $Id: mailinglist.php,v 1.5 2005/03/11 16:18:17 mgifford Exp $ */

   require('./config.php');

   $pagetitle = pslgetText("Mailing List Admin"); // header title
   $xsiteobject = pslgetText("Administration");
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
   }

   /* DEBUG */

   // debug("HTTP_POST_VARS", $_POST);
   // debug("HTTP_GET_VARS", $_GET);

   /* DEBUG */

   $list = pslNew("MailingList");

   $content = '';

   if (empty($ary['action'])) {
      $ary['action'] = '';
   }

   switch ($ary['action']) {
      case "subscribe":
      if ($list->subscribe(clean($_POST))) {
         $content .= getMessage($list->message);
      } else {
         $content .= getError($list->message);
      }
      break;
      case "unsubscribe":
      if ($list->unsubscribe(clean($_POST['unsubscribe_address']))) {
         $content .= getMessage($list->message);
      } else {
         $content .= getError($list->message);
      }
      break;
      default:
      $content .= $list->newList();
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