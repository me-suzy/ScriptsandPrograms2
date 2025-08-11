<?php
   // $Id: register.php,v 1.8 2005/03/11 16:18:18 mgifford Exp $
   /**
    * Displays information on strikes and lockouts
    *
    * @package     Back-End on phpSlash
    * @copyright   2002 - Mike Gifford
    * @version     $Id: register.php,v 1.8 2005/03/11 16:18:18 mgifford Exp $
    *
    */

   global $_BE, $_PSL;

   $pageTitle = "Register Online";
   $xsiteobject = "Register Online";
   #Defines The META TAG Page Type

   require('./config.php');

   $getRequestValue = getRequestValue(); //$section, $article);

   if (!empty($_GET['login'])) {
      $auth->login_if(!$perm->have_perm('user'));
   }

   /****************
    * INITIALISATION
    *****************/
   $ary['section'] = 'Services';

   // Required to clean the QUERY_STRING field in the template
   $ary['query'] = clean($_GET['query']);
   $ary['min'] = clean($_GET['min']);

   /****************
    * CONSTRUCT PAGE
    *****************/

   $submit = pslgetText(getRequestVar('submit', 'PG'), '', true);
   $registerObj = pslNew('BE_Register');
   $content = '';

   if ($submit == 'Register') {
      $content = $registerObj->sendRegistrationForm();
   } else {
      $content = $registerObj->getRegistrationForm();
   }

   // If templates are defined, checks if they exist and formats them correctly
   $_BE['currentSection'] = $ary['section'];
   $sectionObj = pslNew('BE_Section');
   $breadcrumb = $sectionObj->breadcrumb($ary['section']);
   $sectionRec = $sectionObj->extractSection($ary['section']);
   $sectionTemplate = $sectionRec['template'];
   $chosenTemplate = getUserTemplates('', $sectionTemplate);

   // render the standard header
   $_PSL['metatags']['object'] = $xsiteobject;


   // generate the page
   generatePage($ary, $pageTitle, $breadcrumb, $content);

   page_close();

?>