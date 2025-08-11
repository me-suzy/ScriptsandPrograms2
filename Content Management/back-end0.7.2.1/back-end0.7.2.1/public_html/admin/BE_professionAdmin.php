<?php
   // $Id: BE_professionAdmin.php,v 1.12 2005/04/13 15:05:14 mgifford Exp $
   /**
    * @package     Back-End
    * @copyright   Copyright (C) 2003 OpenConcept Consulting
    * @version     $Id: BE_professionAdmin.php,v 1.12 2005/04/13 15:05:14 mgifford Exp $
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
   $pagetitle = pslgetText('Profession Administration'); // The name to be displayed in the header
   $xsiteobject = pslgetText('Administration');          // Defines The META TAG Page Type

   /*****************************
    START OF PAGE
    *****************************/

   $auth->login_if(!$perm->have_perm('bibliography'));

   $bib = pslNew('BE_Profiles');

   $submit = pslgetText(getRequestVar('submit', 'PG'), '', true);

   $content = null;
   $ary = array();

   switch ($submit) {

      case 'new':
      case 'edit':
      if (isset($professionID)) {
         $_POST[professionID] = $professionID;
         $content = $bib->newProfession(clean($_POST), 'database');
      } else {
         $content = $bib->newProfession(clean($_POST), 'new');
      }

      break;

      case 'save':
      $success = $bib->saveProfession(clean($_POST));
      if ($success == false) {
         $content = '<p><big>Error Saving Changes..</big></p>';
         $content = $bib->newProfession(clean($_POST), 'array');
      } else {
         $content = '<p><big>Changes Saved...</big></p>';
         $content .= $bib->listProfession(clean($_POST));
      }
      break;

      case 'delete':
      $_POST['professionID'] = $professionID;
      $_POST['confirmed'] = $confirmed;
      $success = $bib->deleteProfession(clean($_POST), 'database');
      if ($success == true) {
         $content = $bib->listProfession(clean($_POST));
      }
      break;

      case 'abort':
      $content = '<p><big>Changes Aborted..</big></p>';
      default:
      // listCat
      $content = $bib->listProfession();
      break;
   }

   $ary['section'] = 'admin';
   $chosenTemplate = getUserTemplates('',$ary['section']);

   $breadcrumb = '<a href="' . $_PSL['rooturl'] . '/">' . pslgetText('Home') . '</a> &#187; ' . $pagetitle;

   generatePage($ary, $pagetitle, $breadcrumb, $content);
   page_close();

?>