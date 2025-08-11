<?php
   /**
    *  Strike Administration module.  Must be done by super-user; no subsite
    * support.
    *
    * @package     Back-End on phpSlash
    * @copyright   2003 - Ian Clysdale, Canadian Union of Public Employees
    * @version     $Id: BE_strikeAdmin.php,v 1.12 2005/04/13 15:05:14 mgifford Exp $
    *
    */

   require('./config.php');

   $pagetitle = pslgetText('Strike Administration'); // The name to be displayed in the header
   $xsiteobject = pslgetText('Administration');      // Defines The META TAG Page Type
   $_PSL['metatags']['object'] = $xsiteobject;

   $content = null;
   $ary = array();

   $strikeObj = pslNew('BE_Strikes_admin');

   if ($perm->have_perm('strikes') || $perm->have_perm('root')) {
      $showList = true;
      $vars = clean($_POST);
      $vars['submit'] = pslgetText($vars['submit'], '', true);
      $varNames = array('submit', 'id');
      foreach ($varNames as $key) {
         if (empty($vars[$key])) {
            $vars[$key] = (isset($_GET[$key])) ? clean($_GET[$key]) : '';
         }
      }

      switch ($vars['submit']) {
         case 'delete':
         if (!$strikeObj->deleteStrike($vars['id'])) {
            $content .= getError(sprintf(pslgetText('be_delete_failed'), $vars['id']));
         }
         break;

         case 'save':
         $success = $strikeObj->saveStrike($vars);
         if ($success == false) {
            $content .= $strikeObj->message;
            $content .= $strikeObj->newStrike($vars, 'array');
         }
         break;

         case 'edit':
         $content .= $strikeObj->newStrike($vars, 'database');
         $showList = false;
         break;

         case 'new':
         $content .= $strikeObj->newStrike($vars, 'array');
         $showList = false;
         break;
      }

      if ($showList) {
         $content .= $strikeObj->generateStrikeIndex();
      }

   } else {
      $content .= getTitlebar('100%', 'Error! Invalid Privileges');
      $content .= getError(pslgetText('Sorry. You do not have the necessary privilege to view this page.'));
   }

   $ary['section'] = 'admin';

   // generate the page
   $chosenTemplate = getUserTemplates('',$ary['section']);

   $breadcrumb = '<a href="' . $_PSL['rooturl'] . '/">' . pslgetText('Home') . '</a> &#187; ' . $pagetitle;

   generatePage($ary, $pagetitle, $breadcrumb, $content);

   page_close();

?>
