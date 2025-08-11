<?php
   // $Id: result.php,v 1.10 2005/03/27 10:28:35 krabu Exp $
   /**
    * Profiles - Display results of profiles search
    *
    * @package     Back-End
    * @copyright   Copyright (C) 2003 OpenConcept Consulting
    * @version     $Id: result.php,v 1.10 2005/03/27 10:28:35 krabu Exp $
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

   unset($seclev);
   require_once('./config.php');

   $pagetitle = pslGetText('Profiles'); // The name to be displayed in the header
   $xsiteobject = pslGetText('Information');
   // Defines The META TAG Page Type

   /*****************************
    START OF PAGE
    *****************************/

   $pro = pslNew('BE_Profiles');
   $submit = pslgetText(getRequestVar('submit', 'PG'), '', true);

   if (!empty($profileID) AND empty($submit)) {
      $content = $pro->indexDetail($profileID);
   } elseif ($submit == 'photo' AND !empty($profileID)) {
      $content = $pro->photoPro($profileID);
   } elseif ($submit == 'save') {
      if ($pro->saveNom($_POST)) {
         if (empty($profileID))
            $content = '<p><b>Nomination Received.</b></p>';
         else
            $content = "<p><b>Photo Uploaded.</b></p>";
         $content .= $pro->photoPro($profileID, 'result.php');
         // $pro->indexList($level, $next);
      } else {
         $content = $pro->newNom(clean($_POST), 'array');
      }
   } else {
      $content = $pro->indexList($level, $next);
      $content .= $pro->indexSearch($level, $next);
   }

   $ary = array();
   $ary['section'] = "Home";
   $chosenTemplate = getUserTemplates();
   generatePage($ary, $pagetitle, '', $content);
   page_close();

?>