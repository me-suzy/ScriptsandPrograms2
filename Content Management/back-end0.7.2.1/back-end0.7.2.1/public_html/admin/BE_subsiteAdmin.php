<?php
   // $Id: BE_subsiteAdmin.php,v 1.15 2005/05/25 20:49:48 mgifford Exp $
   /**
    * Administration of subsites
    *
    * @package     Back-End on phpSlash
    * @author      Peter Cruickshank
    * @copyright   2002 - Mike Gifford
    * @version     $Id: BE_subsiteAdmin.php,v 1.15 2005/05/25 20:49:48 mgifford Exp $
    *
    */

   require('./config.php');

   $pagetitle = pslgetText('Subsite Administration'); // header title

   $xsiteobject = pslgetText('Administration'); // Defines The META TAG Page Type
   $_PSL['metatags']['object'] = $xsiteobject;

   $content = null;
   $ary = array();

   /*****************************
    START OF PAGE
    *****************************/

   //$submit = pslgetText(getRequestVar('submit', 'PG'), '', true);
   $submit = decodeAction($_REQUEST);

   // error_reporting (E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

   if ($perm->have_perm('subsite')) {
      $subsite = pslNew('BE_subsite');

      $showList = true;

      $first = (isset($_GET['file_i']) && !empty($_GET['file_i'])) ? clean($_GET['file_i']) : NULL;
      $count = (isset($_GET['file_n']) && !empty($_GET['file_n'])) ? clean($_GET['file_n']) : NULL;
      $orderbyLogic = (isset($_GET['logic']) && !empty($_GET['logic'])) ? clean($_GET['logic']) : 'desc';
      $search = (isset($_GET['search']) && !empty($_GET['search'])) ? str_replace('*','%', clean($_GET['search'])) : '';

      switch ($submit) {

         case 'delete':
         $content .= $subsite->delete(clean($_GET['id']));
         $content .= $subsite->message;
         $content .= $subsite->edit();
         break;

         case 'edit':
         $ary = array();
         $ary['subsite_id'] = clean($_GET['id']);
         $content .= $subsite->edit($ary, 'database');
         break;

         case 'submit':
         if (! $subsite->save(clean($_POST)) ) {
            $content .= $subsite->message;
            $content .= $subsite->edit(clean($_POST), 'array');
         } else {
            if($subsite->inSubsite()) {
               $content .= $subsite->message;
               $content .= $subsite->edit(clean($_POST), 'database');
            } else {
               $content .= $subsite->message;
               $content .= $subsite->getAdminList($count, $first, $orderbyLogic, $search);
               $content .= $subsite->edit();
            }
         }
         break;

         default:
         $content .= $subsite->getAdminList($count, $first, $orderbyLogic, $search);
         $content .= $subsite->edit(); // show blank form
      }


   } else if ($perm->have_perm('ContentManager')) {
      $content = '';
      switch($submit) {
         case 'submit':
         $ary = clean($_POST);
         $ary['subsite_id'] = $BE_subsite['subsite_id'];
         $ary['sectionID'] = $subsite->_getRootSection($BE_subsite['subsite_id']);
         if(!$subsite->save($ary)) {
            $content .= $subsite->message;
            $content .= $subsite->edit($ary, 'array');
         } else {
            $content = $subsite->edit($ary, 'database');
         }

         break;

         default:
         $ary = array();
         $ary['subsite_id'] = $BE_subsite['subsite_id'];
         $content .= $subsite->edit($ary, 'database');
      }
   } else {
      $content .= getTitlebar('100%', 'Error! Invalid Privileges');
      $content .= getError(pslgetText('Sorry. You do not have the necessary privilege to view this page.'));
   }

   $ary['section'] = 'admin';

   # debug('BE_subsiteAdmin:ary=', $ary);

   // generate the page
   $chosenTemplate = getUserTemplates('',$ary['section']);

   $breadcrumb = '<a href="' . $_PSL['rooturl'] . '/">' . pslgetText('Home') . '</a> &#187; ' . $pagetitle;

   generatePage($ary, $pagetitle, $breadcrumb, $content);

   page_close();

?>
