<?php
   // $Id: search.php,v 1.19 2005/06/17 13:53:15 mgifford Exp $
   /**
    * Search functionality business logic
    *
    * @package     Back-End on phpSlash
    * @copyright   2002 - Mike Gifford
    * @version     $Id: search.php,v 1.19 2005/06/17 13:53:15 mgifford Exp $
    *
    */

   // don't cache this page
   $cachetimeout = -1;

   require('./config.php');

   $pageTitle = pslGetText('Search');
   $xsiteobject = 'Search Page'; //Defines The META TAG Page Type

   $vars = array();
   if (!empty($_GET)) {
      $vars = clean($_GET);
   }

   if (!empty($vars['login'])) {
      $auth->login_if(!$perm->have_perm('user'));
   }

   /****************
    * INITIALISATION
    *****************/

   # debug('GET', $vars);
   # debug('POST', $_POST);

   // Objects
   // Use either BE_Search or BE_GoogleSearch based on config.ini.php
   $searchObj = pslNew($_BE['search_class']);

   // Calls function that pulls info from the url if it isn't included before hand

   // store the section and article in an array to pass to the Block later on
   // $ary = $vars;

   $ary['query'] = (isset($vars['query'])) ? $vars['query'] : '';
   $ary['min'] = (isset($vars['min'])) ? $vars['min'] : '';

   #debug('ary now',$ary);
   #debug('ary CATDIVN',$_ary['CATDIVN']);
   #print_r($ary); echo "<hr />\n";
   #print_r($vars); echo "<hr />\n";

   /****************
    * CONSTRUCT PAGE
    *****************/

   $content = '';

   if (!empty($vars['error'])) {
      // If redirected to search page with an error message
      // NB: pslgettext not used because the error message is already translated
      $content .= getError($vars['error']);
   }

   if ($perm->have_perm('root')) {

      if ($vars['submit'] == 'clearCache') {
         $content .= $searchObj->clearSearchlog();
      } elseif ($vars['submit'] == 'viewLogs') {
         $content .=  $searchObj->viewSearchlog($vars);
         $content .=  $errors .= '<p><a href="' . $_PSL['rooturl'] . '/search.php?submit=clearCache">' . pslgetText('Clear Log') . '</a></p>';
      } else {
         $content .= $searchObj->getResults($vars);
         $content .=  $errors .= '<p><a href="' . $_PSL['rooturl'] . '/search.php?submit=viewLogs">' . pslgetText('View Search Logs') . '</a></p>';
      }

   } else {
      $content .= $searchObj->getResults($vars);
   }

   $ary['section'] = $_BE['search_section'];

   // If templates are defined, checks if they exist and formats them correctly
   $chosenTemplate = getUserTemplates('', $ary['section']);

   // render the standard header
   $_PSL['metatags']['object'] = $xsiteobject;

   $sectionObj = pslNew('BE_Section');
   $breadcrumb = $sectionObj->breadcrumb($ary['section'], 'Search', 'search');

   // generate the page
   generatePage($ary, $pageTitle, $breadcrumb, $content, '');

   page_close();

?>
