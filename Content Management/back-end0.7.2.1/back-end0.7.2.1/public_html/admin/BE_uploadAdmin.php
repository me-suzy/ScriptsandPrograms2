<?php
   // $Id: BE_uploadAdmin.php,v 1.20 2005/04/18 17:17:05 mgifford Exp $
   /**
    * Administration of uploaded files
    *
    * @author      Peter Cruickshank (based on MGs code)
    * @package     Back-End
    * @copyright   Copyright (C) 2003 OpenConcept Consulting
    * @version     $Id: BE_uploadAdmin.php,v 1.20 2005/04/18 17:17:05 mgifford Exp $
    *
    * This file is part of Back-End.
    *
    * Back-End is free software; you can redistribute it and/or modify
    * it under the terms of the GNU General Public License as published by
    * the Free Software Foundation; either version 2 of the License, or
    * (at your option) any later version.
    *
    * Back-End is distributed in the hope that it will be useful,
    * but WITHOUT ANY WARRANTY; without even the implied warranty of
    * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    * GNU General Public License for more details.
    *
    * You should have received a copy of the GNU General Public License
    * along with Back-End; if not, write to the Free Software
    * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
    */
   require('./config.php');

   // The name to be displayed in the header
   $pagetitle = pslgetText('Upload Administration');
   // Defines The META TAG Page Type
   $xsiteobject = pslgetText('Administration');
   $_PSL['metatags']['object'] = $xsiteobject;

   $content = null;
   $ary = array();

   if ($perm->have_perm('upload')|| $perm->have_perm('root')) {

      // Set no timelimit for large directories
      @set_time_limit(0);

      // Load proper class based on use of profileID variable
      $profileID = getRequestVar('profileID', 'PG');

      if (!empty($profileID)) {
         $uploadObj = pslNew('BE_ProfileUpload');
      } else {
         $uploadObj = pslNew('BE_Upload');
      }

      $orderby = (isset($_GET['orderby']) && !empty($_GET['orderby'])) ? clean($_GET['orderby']) : NULL;
      $count = (isset($_GET['orderby']) && !empty($_GET['orderby'])) ? clean($_GET['orderby']) : NULL;
      $first = (isset($_GET['file_i']) && !empty($_GET['file_i'])) ? clean($_GET['file_i']) : NULL;
      $count = (isset($_GET['file_n']) && !empty($_GET['file_n'])) ? clean($_GET['file_n']) : NULL;
      $orderbyLogic = (isset($_GET['logic']) && !empty($_GET['logic'])) ? clean($_GET['logic']) : 'desc';

      // Manipulate sort logic so that if you click twice on the title that the sort order changes
      $refererURL = (isset($_SERVER['HTTP_REFERER'])) ? parse_url($_SERVER['HTTP_REFERER']) : NULL;
      if (!empty($refererURL) && ereg('orderby='.@$_GET['orderby'], @$refererURL['query']) &&
         ereg('file_i='.@$_GET['file_i'], @$refererURL['query']) &&
         ereg('file_n='.@$_GET['file_n'], @$refererURL['query'])) {
         if (isset($_GET['logic']) && $_GET['logic'] == 'desc') {
            $orderbyLogic = 'asc';
         } elseif (isset($_GET['logic']) && $_GET['logic'] == 'asc') {
            $orderbyLogic = 'desc';
         }
      }

      // Work around for legacy templates
      if (isset($_GET['delete']) && !empty($_GET['delete'])) {
         $file = clean($_GET['delete']);
         $content .= $uploadObj->deleteFile($file);
      } else {
         $file = isset($_GET['file']) ? clean($_GET['file']) : NULL;
      }

      $submit = decodeAction($_POST);
      if (empty($submit)) {
         $submit = (isset($_GET['submit'])) ? clean($_GET['submit']) : '';
      }

      $directory = (isset($_POST['directory'])) ? '/' . clean($_POST['directory']) : '';
      if ($directory == '/updir') {
         $directory = '';
      }

      $search = getRequestVar('search','PG');

      // ===================
      // Process actions
      // ===================

      switch ($submit) {
      case 'delete':
         if (!empty($file)) {
            $uploadObj->deleteFile($file, $directory);
         }
         break;

      case 'upload':
      case 'Upload':
         if(!empty($_FILES['file1']['name'])) {
            $uploadObj->saveUploads($directory);
         }
         break;

      case 'upgradeDB':
      case 'updateDB':
         $uploadObj->updateDatabase();
         break;

      case 'create':
         $uploadObj->createFolder('', clean($_POST['newDirectory']));
         break;

      case 'change':
         $uploadObj->changeFolder($directory);
         break;

      }


      // ===================
      // Display results, Uploaded file list and associated form
      // ===================
      $content .= $uploadObj->showList($orderby, $count, $first, $orderbyLogic, $directory);

   } else {

      $content .= getTitlebar('100%', 'Error! Invalid Privileges');
      $content .= pslgettext('Sorry. You do not have the necessary privilege to view this page.');

   }

   $ary['section'] = 'admin';

   $chosenTemplate = getUserTemplates('',$ary['section']);

   $breadcrumb = '<a href="' . $_PSL['rooturl'] . '/">' . pslgetText('Home') . '</a> &#187; ' . $pagetitle;

   // generate the page
   generatePage($ary, $pagetitle, $breadcrumb, $content);

   // Close page and save session variables
   page_close();

?>