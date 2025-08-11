<?php
   // $Id: BE_editTemplateAdmin.php,v 1.19 2005/05/25 20:49:48 mgifford Exp $
   /**
    * Links
    *
    * Currently, link-administration is not aware of subsites, so must
    * be carried out by a superuser
    *
    * @package     Back-End on phpSlash
    * @copyright   2002 - Mike Gifford
    * @version     $Id: BE_editTemplateAdmin.php,v 1.19 2005/05/25 20:49:48 mgifford Exp $
    * @author      Peter Cruickshank
    *
    */

   require('./config.php');

   $pagetitle = pslgetText('Template Administration'); // The name to be displayed in the header
   $xsiteobject = pslgetText('Administration');        // Defines The META TAG Page Type
   $_PSL['metatags']['object'] = $xsiteobject;

   $content = null;
   $ary = array();

   $editObj = pslNew('BE_EditTemplate');

   // error_reporting (E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

   if ($perm->have_perm('template')) {

      $showList = TRUE;

      // $submit = pslgetText(getRequestVar('submit', 'PG'), '', true);
      $submit = decodeAction($_REQUEST);
      $skin = getRequestVar('skin', 'PG');

      switch ($submit) {

         // Process screen actions

         case 'confirm': // after newskin to create a new skin
         # debug('be_et subsite_id',$BE_subsite['subsite_id']);
         $editObj->newSubsiteSkin($skin);
         break;

         case 'revert': // from template list
         if ($editObj->setSkin($skin)) {
            $editObj->revertTemplate(clean($_GET['file']));
         }
         break;

         case 'save':
         if ($editObj->setSkin($skin)) {
            if (!$editObj->saveTemplate(clean($_POST, TRUE))) {
               $content .= $editObj->editScreen(clean($_POST, TRUE));
               $showList = false;
            }
         }
         break;

         case 'cancel':
         $content .= getMessage(pslGetText('Action Cancelled'));
         $editObj->setSkin($skin);
         break;

         // Display screens

         case 'edit':
         if ($editObj->setSkin($skin)) {
            $content .= $editObj->editScreen(clean($_GET['file']));
            $showList = false;
         }
         break;

         case 'newtemplate':
         if ($editObj->setSkin($skin)) {
            $editObj->newTemplate(clean($_GET['file']), $skin, clean(@$_GET['theme']));
            $showList = false;
         }

         break;


         case 'newskin':
         default:
         if (!$editObj->setSkin($skin)) {
            if (isset($BE_subsite['subsite_id'])) {
               // Confirm creation of new skin if in a subsite
               $content .= $editObj->confirmScreen($skin);
               $showList = false;
            }
         }
      }

      if (isset($showList)) {
         $orderBy = (isset($_GET['orderby'])) ? clean($_GET['orderby']) : NULL;
         $content .= $editObj->showList($orderBy);
      }

      $content = $editObj->message . $content;

   } else {
      $content .= getTitlebar('100%', pslgetText('Error! Invalid Privileges'));
      $content .= getError(pslgetText('Sorry. You do not have the necessary privilege to view this page.'));
   }

   $ary['section'] = 'admin';

   $chosenTemplate = getUserTemplates('',$ary['section']);

   $breadcrumb = '<a href="' . $_PSL['rooturl'] . '/">' . pslgetText('Home') . '</a> &#187; ' .$pagetitle;

   // generate the page
   generatePage($ary, $pagetitle, $breadcrumb, $content);

   page_close();

?>
