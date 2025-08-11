<?php
   // $Id: BE_editLocaleAdmin.php,v 1.5 2005/04/13 15:05:13 mgifford Exp $
   /**
    * Locale-specific translations
    *
    * @package     Back-End on phpSlash
    * @copyright   2002 - Mike Gifford
    * @version     $Id: BE_editLocaleAdmin.php,v 1.5 2005/04/13 15:05:13 mgifford Exp $
    * @author      Peter Cruickshank
    *
    */

   require('./config.php');

   $pagetitle = pslgetText('Locale Administration'); // The name to be displayed in the header
   $xsiteobject = pslgetText('Administration');        // Defines The META TAG Page Type
   $_PSL['metatags']['object'] = $xsiteobject;

   $content = null;
   $ary = array();

   $editObj = pslNew('BE_EditLocale');

   // error_reporting (E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

   if ($perm->have_perm('locale') || $perm->have_perm('root') ) {

      $submit = pslgetText(getRequestVar('submit', 'PG'), '', true);
      $locale = getRequestVar('locale', 'PG');
      $locale = (isset($locale) && !empty($locale)) ? $locale : $BE_currentLanguage;

      switch ($submit) {

         // Process screen actions
         case 'save':
            if (!$editObj->saveLocale(clean($_POST, TRUE))) {
               $content .= $editObj->editScreen($locale, TRUE);
            }

         // }
         break;

         case 'cancel':
         $content .= getMessage(pslGetText('Action Cancelled'));
         break;
      }

      $content = $editObj->message . $content . $editObj->localePicker($locale);

      $content .= $editObj->editScreen($locale);

   } else {
      $content .= getTitlebar('100%', 'Error! Invalid Privileges');
      $content .= getError(pslgetText('Sorry. You do not have the necessary privilege to view this page.'));
   }

   $ary['section'] = 'admin';

   $chosenTemplate = getUserTemplates('',$ary['section']);

   $breadcrumb = '<a href="' . $_PSL['rooturl'] . '/">' . pslgetText('Home') . '</a> &#187; ' .$pagetitle;

   // generate the page
   generatePage($ary, $pagetitle, $breadcrumb, $content);

   page_close();

?>
