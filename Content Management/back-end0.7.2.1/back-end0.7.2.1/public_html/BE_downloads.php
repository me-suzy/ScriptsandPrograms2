<?php
   /**
    * Displays documents available for download
    *
    * @package     Back-End on phpSlash
    * @copyright   2003 - Ian Clysdale, Canadian Union of Public Employees
    *
    */

   require('./config.php');

   global $_BE, $_PSL;

   $pageTitle = pslGetText('Downloads');
   $xsiteobject = 'Downloads';
   #Defines The META TAG Page Type

   /* ****
    * MERGE WITH PSL 0.7 - 12Feb03
    * - page_open is now dealt with by config.inc
    * - setCurrentLangauge and be_setCurrentLocal are now dealt with by config.inc
    ** ***/

   $getRequestValue = getRequestValue(); //$section, $article);

   if (!empty($_GET['login'])) {
      $auth->login_if(!$perm->have_perm('user'));
   }

   /****************
    * INITIALISATION
    *****************/
   $ary['section'] = "Downloads";

   // Required to clean the QUERY_STRING field in the template
   $ary['query'] = clean($_GET['query']);
   $ary['min'] = clean($_GET['min']);

   /****************
    * CONSTRUCT PAGE
    *****************/

   $downloadObj = pslNew('BE_Downloads');
   $offset = 0;
   if ($_GET['list'] == 'downloads' AND isset($_GET['next'])) $offset = clean($_GET['next']);
   $content = $downloadObj->getDownloadList($offset);

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