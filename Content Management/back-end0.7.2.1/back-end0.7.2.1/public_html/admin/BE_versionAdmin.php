<?php
   /* $Id: BE_versionAdmin.php,v 1.7 2005/04/13 15:05:14 mgifford Exp $ */
   /**
    * Back-End History Administration
    *
    * Equivalent phpSlash file: storyAdmin.php - the two files should be kept compatible
    *
    * Permissions are shared with phpSlash stories
    *
    * @package     Back-End on phpSlash
    * @copyright   2002 - Mike Gifford
    * @version     $Id: BE_versionAdmin.php,v 1.7 2005/04/13 15:05:14 mgifford Exp $
    */

   require('./config.php');

   $pagetitle = pslgetText('Article Administration'); // The name to be displayed in the header
   $xsiteobject = pslgetText('Administration');       // Defines The META TAG Page Type
   $_PSL['metatags']['object'] = $xsiteobject;

   # error_reporting (E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

   $auth->login_if(!$perm->have_perm('section'));

   $content = pslgetText('Coming soon');


// STANDARD STUFF BELOW

   if ($content == '') {
      $content = getTitlebar('100%', 'Error! Invalid Privileges');
      $content .= getError(pslGetText('Sorry. You do not have the necessary privilege to view this page.'));
   }

   $ary['section'] = 'admin';

   $_PSL['metatags']['object'] = $xsiteobject;

   $chosenTemplate = getUserTemplates('',$ary['section']);
   // Check for use of htmlarea header
   if (isset($_BE['HTMLAREA']) && $_BE['HTMLAREA']) {
      if ($_BE['HTMLAREA'] == 2) {
         $chosenTemplate['header'] = 'slashHead-htmlarea2';
      } else {
         $chosenTemplate['header'] = 'slashHead-htmlarea3';
      }
   } else {
      $chosenTemplate['header'] = 'slashHead';
   }

   $breadcrumb = '<a href="' . $_PSL['rooturl'] . '/">' . pslgetText('Home') . '</a> &#187; ' . $pagetitle;

   // generate the page
   generatePage($ary, $pagetitle, $breadcrumb, $content);

   page_close();

?>
