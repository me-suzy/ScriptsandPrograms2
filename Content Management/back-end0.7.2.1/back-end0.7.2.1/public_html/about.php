<?php
   /* $Id: about.php,v 1.8 2005/03/17 16:36:14 mgifford Exp $ */
   /**
    * About project page
    *
    * Displays about.tpl in context
    *
    * @package     Back-End on phpSlash
    * @copyright   2002 - Mike Gifford
    * @version     $Id: about.php,v 1.8 2005/03/17 16:36:14 mgifford Exp $
    *
    */

   require('./config.php');

   $pagetitle = pslgetText('About'); // The name to be displayed in the header
   $xsiteobject = pslgetText('About Page'); // This Defines The META Tag Object Type
   $_PSL['metatags']['object'] = $xsiteobject; // render the standard header

   if (!empty($_GET['login'])) {
      $auth->login_if($_GET['login']);
   }

   $storyText = $content = '';

   // Objects
   $sectionObj = pslNew('BE_Section');

   // Start of Page
   $_PSL['metatags']['object'] = $xsiteobject;


   // setup the template for the index page
   $template = pslNew('slashTemplate', $_PSL['templatedir']);
   $template->set_file('main', 'about.tpl');
   // $template->debug = 1;
   $storyText = $template->parse('OUT', 'main');

   $ary['section'] = 'About';

   $breadcrumb = $sectionObj->breadcrumb($ary['section'], 'About', 'about');

   // generate the page
   generatePage($ary, $pagetitle, $breadcrumb, $storyText);

   // close the page
   page_close();

?>