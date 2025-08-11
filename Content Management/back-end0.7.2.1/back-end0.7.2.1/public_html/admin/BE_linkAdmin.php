<?php
   // $Id: BE_linkAdmin.php,v 1.17 2005/04/16 18:15:49 mgifford Exp $
   /**
    * Links
    *
    * Currently, link-administration is not aware of subsites, so must
    * be carried out by a superuser
    *
    * @package     Back-End on phpSlash
    * @copyright   2002 - Mike Gifford
    * @version     $Id: BE_linkAdmin.php,v 1.17 2005/04/16 18:15:49 mgifford Exp $
    *
    */

   require('./config.php');

   $pagetitle = pslgetText('Link Administration'); // The name to be displayed in the header
   $xsiteobject = pslgetText('Administration'); // Defines The META TAG Page Type
   $_PSL['metatags']['object'] = $xsiteobject;

   $content = null;
   $ary = array();

   $linkObj = pslNew('BE_Link_admin');

   if ($perm->have_perm('linkList') || $perm->have_perm('root')) {
      $showList = true;
      $vars = clean($_POST);

      // $vars['submit'] = pslgetText($vars['submit'], '', true);
      $vars['submit'] = decodeAction($_POST);

      // Check for $_GET, but don't overwrite $_POST values
      $varNames = array('submit', 'linkID', 'orderby', 'dir', 'lnk_i', 'lnk_n','search');
      foreach ($varNames AS $key) {
         if (empty($vars[$key])) {
            $vars[$key] = (isset($_GET[$key])) ? clean($_GET[$key]) : '';
         }
      }

      switch ($vars['submit']) {
         case 'delete':
         if (!$linkObj->deleteLink($vars['linkID'])) {
            $content .= getError(sprintf(pslgetText('be_delete_failed'), $vars['linkID']));
         }
         break;

         case 'save':
         // echo "##"; print_r($vars);
         $success = $linkObj->saveLink($vars, false);
         if ($success == false) {
            $content .= $linkObj->message;
            $content .= $linkObj->newLink($vars, 'array');
         }
         break;

         case 'preview':
         $content .= getTitlebar('100%', pslgettext('be_previews'));
         $vars['name'] = clean($_POST['author_id']);
         $content .= $linkObj->showLink($vars);
         $content .= getTitlebar('100%', pslgettext('Edit'));
         $content .= $linkObj->newLink($vars, 'array');
         $showList = false;
         break;

         case 'edit':
         $content .= $linkObj->newLink($vars, 'database');
         $showList = false;
         break;

         case 'new':
         $content .= $linkObj->newLink($vars, 'new');
         $showList = false;
         break;
      }

      if ($showList) {
         if (empty($vars['offset'])) {
            $vars['offset'] = 0;
         }
         if (empty($vars['count'])) {
            $vars['count'] = 25;
         }
         $content .= $linkObj->generateLinkIndex('', $vars['lnk_i'], $vars['lnk_n'], $vars['orderby'], $vars['dir'], $vars['search']);
      }

   } else {
      $content .= getTitlebar('100%', 'Error! Invalid Privileges');
      $content .= getError(pslgetText('Sorry. You do not have the necessary privilege to view this page.'));
   }

   $ary['section'] = 'admin';

   $chosenTemplate = getUserTemplates('',$ary['section']);

   $breadcrumb = '<a href="' . $_PSL['rooturl'] . '/">' . pslgetText('Home') . '</a> &#187; ' . $pagetitle;

   // generate the page
   generatePage($ary, $pagetitle, $breadcrumb, $content);

   page_close();

?>