<?php
   // $Id: BE_regionAdmin.php,v 1.14 2005/04/16 01:52:01 mgifford Exp $
   /**
    * @package     Back-End
    * @copyright   Copyright (C) 2003 OpenConcept Consulting
    * @version     $Id: BE_regionAdmin.php,v 1.14 2005/04/16 01:52:01 mgifford Exp $
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
   $pagetitle = pslgetText('Region Administration'); // The name to be displayed in the header
   $xsiteobject = pslgetText('Administration');      // Defines The META TAG Page Type


   /*****************************
    START OF PAGE
    *****************************/

   $auth->login_if(!$perm->have_perm('bibliography'));

   $bib = pslNew('BE_bibliography');

   $submit    = pslgetText(getRequestVar('submit', 'PG'), '', true);
   $regionID  = getRequestVar('regionID','PG');
   $confirmed = getRequestVar('confirmed','PG');

   $content = null;
   $ary = array();

   switch ($submit) {
      case 'new':
      case 'edit':
      if (isset($regionID)) {
         $_POST['regionID'] = $regionID;
         $content = $bib->newRegion(clean($_POST), 'database');
      } else {
         $content = $bib->newRegion(clean($_POST), 'new');
      }

      break;

      case 'save':
      $success = $bib->saveRegion(clean($_POST));
      if ($success == false) {
         $content = '<p><big>Error Saving Changes..</big></p>';
         $content .= $bib->newRegion(clean($_POST), 'array');
      } else {
         $content = '<p><big>Changes Saved...</big></p>';
         $content .= $bib->listRegion(clean($_POST));
      }
      break;

      case 'delete':
      $_POST['regionID'] = $regionID;
      $_POST['confirmed'] = $confirmed;
      $content = $bib->deleteRegion(clean($_POST), 'database');
      if ($confirmed == true) {
         $content .= $bib->listRegion(clean($_POST));
      }
      break;

      case 'abort':
      $content = '<p><big>Changes Aborted..</big></p>';
      default:
      // listRegion
      $content = $bib->listRegion(clean($_POST));
      break;
   }

   $ary['section'] = 'admin';
   $chosenTemplate = getUserTemplates('',$ary['section']);

   $breadcrumb = '<a href="' . $_PSL['rooturl'] . '/">' . pslgetText('Home') . '</a> &#187; ' . $pagetitle;

   generatePage($ary, $pagetitle, $breadcrumb, $content);

   page_close();

?>