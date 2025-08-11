<?php

   /* $Id: mailinglistAdmin.php,v 1.8 2005/03/11 16:18:23 mgifford Exp $ */

   require('./config.php');

   $pagetitle = pslgetText('Mailing List Admin'); // header title
   $xsiteobject = pslgetText('Administration');   // Defines The META TAG Page Type

   $content = '';
   $ary = array();

   if (!is_object($sess)) {
      page_open($_PSL['page_features']);
   }

   /*****************************
    START OF PAGE
    *****************************/

   $auth->login_if(!$perm->have_perm('mailinglist'));

   /* DEBUG */

   # debug("HTTP_POST_VARS", $_POST);
   # debug("HTTP_GET_VARS", $_GET);

   /* DEBUG */

   $list = pslNew('MailingList');

   $action = pslgetText(getRequestVar('action', 'PG'), '', true);

   if ($perm->have_perm('mailinglist')) {

      switch ($action) {
         case "newlist":
         /* this is how we can see the user page without
          logging out */
         $content .= $list->newlist();
         break;
         case "newsletter":
         $content .= $list->newsletterForm();
         break;
         case "send_newsletter":
         if ($list->newsletterSend(clean($_POST, true))) {
            $content .= getMessage($list->getMessage());
         } else {
            $content .= getError($list->getMessage());
         }
         break;
         case "subscribe":
         if ($list->subscribe(clean($_POST, true))) {
            $content .= getMessage($list->getMessage());
         } else {
            $content .= getError($list->getMessage());
         }
         break;
         case "unsubscribe":
         if ($list->subscribe(clean($_POST, true))) {
            $content .= getMessage($list->getMessage());
         } else {
            $content .= getError($list->getMessage());
         }
         break;
         case "mass_delete":
         # debug("mass_del", $_POST["mass_del"]);
         $list->mass_delete(clean($_POST["mass_del"]));
         $content .= $list->getMessage();
         break;
         default:
         if ($perm->have_perm("mailinglistList")) {
            $content .= $list->AdminMenu();
         }
      }

   } else {
      $content = getTitlebar("100%", "Error! Invalid Privileges");
      $content .= "Sorry. You do not have the necessary privilege to view this page.";
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