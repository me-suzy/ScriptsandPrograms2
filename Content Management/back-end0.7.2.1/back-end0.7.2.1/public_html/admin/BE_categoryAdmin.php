<?php
   // $Id: BE_categoryAdmin.php,v 1.14 2005/06/16 19:36:27 mgifford Exp $
   /**
    * @package     Back-End
    * @copyright   Copyright (C) 2003 OpenConcept Consulting
    * @version     $Id: BE_categoryAdmin.php,v 1.14 2005/06/16 19:36:27 mgifford Exp $
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
   require_once('config.php');

   // Security: Don't use User Vars
   $pagetitle = pslgetText('Categories Administration'); // The name to be displayed in the header
   $xsiteobject = pslgetText('Administration'); // Defines The META TAG Page Type


   /*****************************
    START OF PAGE
    *****************************/

   $auth->login_if(!$perm->have_perm('bibliography'));

   $bib = pslNew('BE_Bibliography');

   $submit = pslgetText(getRequestVar('submit', 'PG'), '', true);

   switch ($submit) {

      case 'new':
      case 'edit':
      if (isset($categoryID)) {
         $_POST['categoryID'] = $categoryID;
         $content = $bib->newCat(clean($_POST), 'database');
      } else {
         $content = $bib->newCat(clean($_POST), 'new');
      }

      break;

      case 'save':
      $success = $bib->saveCat(clean($_POST));
      if ($success == false) {
         $content = '<p><big>Error Saving Changes..</big></p>';
         $content .= $bib->newCat(clean($_POST), 'array');
      } else {
         $content = '<p><big>Changes Saved...</big></p>';
         $content .= $bib->listCat(clean($_POST));
      }
      break;

      case 'delete':
      $_POST['categoryID'] = $categoryID;
      $_POST['confirmed'] = $confirmed;
      $content = $bib->deleteCat(clean($_REQUEST), 'database');
      if ($confirmed == true) {
         $content = $bib->listCat(clean($_POST));
      }
      break;

      case 'abort':
      $content = '<p><big>Changes Aborted..</big></p>';
      default:
      // listCat
      $content = $bib->listCat(clean($_POST));
      break;
   }

   $ary = array();
   $ary['section'] = 'admin';
   $chosenTemplate = getUserTemplates('',$ary['section']);

   $breadcrumb = '<a href="' . $_PSL['rooturl'] . '/">' . pslgetText('Home') . '</a> &#187; ' .$pagetitle;

   generatePage($ary, $pagetitle, $breadcrumb, $content);
   page_close();

?>