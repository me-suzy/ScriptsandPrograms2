<?php

   /* $Id: PSL_authorAdmin.php,v 1.8 2005/03/11 16:18:22 mgifford Exp $ */

   require('./config.php');

   $pagetitle = pslgetText('Author Administration'); // header title
   $xsiteobject = pslgetText('Administration');      // Defines The META TAG Page Type

   if (!is_object($sess)) {
      page_open($_PSL['page_features']);
   }

   /*****************************
    START OF PAGE
    *****************************/

   $auth->login_if(!$perm->have_perm('authorList'));

   $content = null;

   $author = pslNew('Author');

   $submit = pslgetText(getRequestVar('submit', 'PG'), '', true);

   switch ($submit) {

      case 'delete':
      if ($perm->have_perm('authorDelete')) {
         $success = $author->deleteAuthor(clean($_GET['id']), $auth->auth['uid']);
         if ($success) {
            $content .= getMessage($author->message);
         } else {
            $content .= getError($author->message);
         }
      }
      if ($perm->have_perm('authorNew')) {
         $content .= $author->newAuthor();
      }
      if ($perm->have_perm('authorList')) {
         $content .= $author->listAuthor();
      }
      break;
      case "edit":
      if ($perm->have_perm("authorEdit")) {
         $content .= $author->editAuthor(clean($_GET['id']));
      }
      if ($perm->have_perm("authorList")) {
         $content .= $author->listAuthor();
      }
      break;
      case "lostpw":
      // if ($perm->have_perm("authorLostPW")) {
      if ($perm->have_perm("authorList")) {
         $success = $author->lostpw(clean($_GET), $auth->auth['uid']);
         if ($success) {
            $content .= getMessage($author->message);
         } else {
            $content .= getError($author->message);
         }
      }
      if ($perm->have_perm("authorNew")) {
         $content .= $author->newAuthor();
      }
      if ($perm->have_perm("authorList")) {
         $content .= $author->listAuthor();
      }
      break;
      case "update":
      case "new":
      if ($perm->have_perm("authorSave")) {
         $success = $author->saveAuthor(clean($_POST));
         if ($success) {
            $content .= getMessage($author->message);
         } else {
            $content .= getError($author->message);
         }
      }
      default:
      if ($perm->have_perm("authorNew")) {
         $content .= $author->newAuthor();
      }
      if ($perm->have_perm("authorList")) {
         $content .= $author->listAuthor();
      }
   }

   if ($content == '') {
      $content = getTitlebar("100%", "Error! Invalid Privileges");
      $content .= pslgetText("Sorry. You do not have the necessary privilege to view this page.");
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