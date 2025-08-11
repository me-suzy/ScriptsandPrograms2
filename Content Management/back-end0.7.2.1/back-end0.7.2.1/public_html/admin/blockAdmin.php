<?php
   /* $Id: blockAdmin.php,v 1.24 2005/05/18 22:09:15 krabu Exp $ */
   /**
    * Back-End/phpSlash Block Administration
    *
    * Adapted version of phpSlash code - the two files should be kept compatible
    *
    * @package     Back-End on phpSlash
    * @copyright   2002 - Mike Gifford
    * @version     $Id: blockAdmin.php,v 1.24 2005/05/18 22:09:15 krabu Exp $
    */

   /* Require the config */
   require('./config.php');

   /* header title */
   $pagetitle = pslgetText('Block Administration');

   /* Defines The META TAG Page Type */
   $xsiteobject = pslgetText('Administration');
   $_PSL['metatags']['object'] = $xsiteobject;

   $content = $blockTypes = null;
   $ary = array();


   /* Start Block Object */
   $block = pslNew('Block_admin');

   /*****************************
    START OF PAGE
    *****************************/

   $auth->login_if(!$perm->have_perm('blockList'));

   $showList = true;

   $submit = pslgetText(getRequestVar('submit_var', 'PG'), '', true);
   if (empty($submit)) {
      $submit = pslgetText(getRequestVar('submit', 'PG'), '', true);
   }

   //Set up pagination and ordering
   $first = (isset($_GET['blk_i']) && !empty($_GET['blk_i'])) ? clean($_GET['blk_i']) : NULL;
   $count = (isset($_GET['blk_n']) && !empty($_GET['blk_n'])) ? clean($_GET['blk_n']) : NULL;
   $orderby = (isset($_GET['orderby']) && !empty($_GET['orderby'])) ? clean($_GET['orderby']) : 'title';
   $orderbyLogic = (isset($_GET['logic']) && $_GET['logic'] == 'asc') ? 'asc' : 'desc';

   // Manipulate sort logic so that if you click twice on the title that the sort order changes
   $refererURL = parse_url(@$_SERVER['HTTP_REFERER']);
   if (ereg('orderby='.@$_GET['orderby'], @$refererURL['query']) &&
      ereg('blk_i='.@$_GET['blk_i'], @$refererURL['query']) &&
      ereg('blk_n='.@$_GET['blk_n'], @$refererURL['query'])) {
      if (isset($_GET['logic']) && $_GET['logic'] == 'desc') {
         $orderbyLogic = 'asc';
      } elseif (isset($_GET['logic']) && $_GET['logic'] == 'asc') {
         $orderbyLogic = 'desc';
      }
   }

   /* First do stuff that we have to */
   switch ($submit) {
      case 'delete':
      if ($perm->have_perm('blockDelete')) {
         if ($block->delBlock(clean($_GET['id']))) {
            $content .= pslgetText('The Block has been Deleted');
         }
      }
      if ($perm->have_perm('blockNew')) {
         $content .= $block->newBlock();
         /* prints the new block form */
      }
      break;

      case 'view':
      $block->getBlock(clean($_GET['id']));
      $block->doParse(); /* refreshes block cache in db */
      $content .= $block->doBlock(clean($_GET['id']));
      break;

      case 'new':
      if ($perm->have_perm('blockPut')) {
         $content .= $block->putBlock(clean($_POST));
      }
//      $showList = false;
      break;

      case 'edit':
      if ($perm->have_perm('blockEdit')) {
         $content .= $block->editBlock(clean($_GET['id']));
      }
//      $showList = false;
      break;

      case 'update': // old default not sufficient
      case 'create': // should specify update & create (where appropriate) or
      case 'save':

      if ($perm->have_perm('blockPut')) {
         $content .= $block->putBlock(clean($_POST));

         // expire cache for these section_id's
         $section_id_ary = (isset($_POST['section_id_ary'])) ? clean($_POST['section_id_ary']) : null;
         if (is_array($section_id_ary) && function_exists('jpcache_gc')) {
            foreach($section_id_ary as $key => $value) {
               jpcache_gc('string', '-section_id-' . $value, '100');
            }
         }

         $content .= $block->editBlock(clean($_POST['block_id']));
         break;

      }

      case 'refresh':
      if($perm->have_perm('blockNew')) {
         $content .= $block->editBlock(clean($_POST['block_id']),clean($_POST));
         break;
      }

      case 'reorder':
      if($perm->have_perm('blockEdit')) {
         $block->reorderBlock(clean($_GET['id']),clean($_GET['ordernum']));
         $redirect = (isset($_SERVER["HTTP_REFERER"])) ? $_SERVER["HTTP_REFERER"] : $_PSL['rooturl'];
         Header('Location: ' . $redirect);
         break;
      }

      # break;
      default:
      $option = getRequestVar('option', 'G'); //(isset($_GET['option']) && !empty($_GET['option'])) ? clean($_GET['option']) : '';
      $name = getRequestVar('name', 'G'); //(isset($_GET['name']) && !empty($_GET['name'])) ? clean($_GET['name']) : '';

      if (!empty($option)) {
			// TODO: Add permission check for blocktype management
         $blockTypes = $block->listBlockTypes($option, $name); /* optional block type management */
      } elseif ($perm->have_perm('blockNew')) {
         $content .= $block->newBlock();
         /* prints the new block form */
      }
   }

   if ($showList) {
      if ($submit == 'view' || $submit == 'edit' || $submit == 'new') {
         $content .= $block->listBlock($count, $first, $orderby, $orderbyLogic);
      } else {
        $content = $block->listBlock($count, $first, $orderby, $orderbyLogic) . $content;
      }
      // prints a list of current blocks
      $content .= $blockTypes;
   }

   if ($content == '') {
      $content = getTitlebar('100%', 'Error! Invalid Privileges');
      $content .= 'Sorry. You do not have the necessary privilege to view this page.';
   }

   $ary['section'] = 'admin';

   $chosenTemplate = getUserTemplates('',$ary['section']);

   $breadcrumb = '<a href="' . $_PSL['rooturl'] . '/">' . pslgetText('Home') . '</a> &#187; ' . $pagetitle;

   // generate the page
   generatePage($ary, $pagetitle, $breadcrumb, $content);

   page_close();

?>
