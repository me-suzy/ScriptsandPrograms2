<?php
   // $Id: nominations.php,v 1.11 2005/03/27 10:28:35 krabu Exp $
   /**
    * Profiles - allow visitors to nominate a new individual for the profiles databank
    *
    * @package     Back-End
    * @copyright   Copyright (C) 2003 OpenConcept Consulting
    * @version     $Id: nominations.php,v 1.11 2005/03/27 10:28:35 krabu Exp $
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

   require_once('./config.php');

   $pagetitle = pslGetText('Nominate someone for the profiles databank'); // The name to be displayed in the header
   $xsiteobject = pslGetText('Information');
   // Defines The META TAG Page Type

   /*****************************
    START OF PAGE
    *****************************/

   $pro = pslNew ('BE_Profiles');

   // echo "submit: $submit $bibID<br />";
   $submit = pslgetText(getRequestVar('submit', 'P'), '', true);
   switch ($submit) {
      case 'save':
      $success = $pro->saveNom(clean($_POST));
      if ($success == false) {
         $content = '<big>Error Saving Changes..</big>';
         $content .= $pro->newNom(clean($_POST), 'array', $errorMSG);
      } else {
         $content = '<big>Changes Saved...</big>';
         // $pro->newNom($author_id,$next);
      }
      break;

      default:
      $content = $pro->newNom(clean($_POST), 'new');
      break;
   }

   $ary = array();
   $ary['section'] = 'profilesNominations';

   // getUserTemplates();
   $chosenTemplate = getUserTemplates('', $ary['section']);

   generatePage($ary, $pagetitle, '', $content);
   page_close();

?>