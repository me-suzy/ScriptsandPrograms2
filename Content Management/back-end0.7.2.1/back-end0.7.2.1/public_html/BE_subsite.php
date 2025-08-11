<?php
   // $Id: BE_subsite.php,v 1.4 2005/03/11 16:18:14 mgifford Exp $
   /**
    * List of subsites
    *
    * Lists all subsites, grouped by type
    * May have to extend to also allow filtering and searching
    * The same list is presented, no matter what subsite the user is in.
    *
    *
    * @package     Back-End on phpSlash
    * @copyright   2002 - Mike Gifford
    * @author      Peter Cruickshank
    * @version     $Id: BE_subsite.php,v 1.4 2005/03/11 16:18:14 mgifford Exp $
    *
    */
   $pagetitle = "Subsites";
   // The name to be displayed in the header
   $xsiteobject = "Subsites Directorys";
   // This Defines The META Tag Object Type

   require('./config.php');

   /* ****
    * MERGE WITH PSL 0.7 - 12Feb03
    * - page_open is now dealt with by config.inc
    * - setCurrentLangauge and be_setSubsite are now dealt with by config.inc
    ** ***/

   if (!empty($_GET['login'])) {
      $auth->login_if($_GET['login']);
   }

   // Calls function that pulls info from the url if it isn't included before hand
   $ary = getRequestValue($link, $section, $type = 'link');

   // Objects
   $subsiteObj = pslNew('BE_Subsite');

   $pageBody = $subsiteObj->getList();

   // render the standard header
   $_PSL['metatags']['object'] = $xsiteobject;

   /* I have no convenient way to get the current section and current article
    to my Blocks so they know how to generate themselves. The use of
    _BE['currentSection'] is a mere convenience given the time I've got to
    solve this problem.

    We need a better way to let blocks know for what section they're generating
    themselves. We also need a simple, well-defined interface to a general-
    purpose caching mechanism. Blocks could be deciding themselves when they
    really need updating, based on certain session state information that's
    globallay accessible. PSB 2002-08-28
    */
   # debug ('section',$ary['section']);
   $_BE['currentSection'] = $ary['section'];
   #$_BE['currentSectionURLname'] = $sectionURLname;
   $_BE['currentLink'] = $ary['link'];

   // If templates are defined, checks if they exist and formats them correctly
   $chosenTemplate = getUserTemplates($articleTemplate, $sectionTemplate);

   // generate the page
   generatePage($ary, $pagetitle, $breadcrumb, $pageBody);

   // close the page
   page_close();

?>