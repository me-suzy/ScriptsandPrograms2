<?php
   // $Id: searchProfiles.php,v 1.8 2005/03/27 10:28:35 krabu Exp $
   /**
    * Profiles - Search screen
    *
    * @package     Back-End
    * @copyright   Copyright (C) 2003 OpenConcept Consulting
    * @version     $Id: searchProfiles.php,v 1.8 2005/03/27 10:28:35 krabu Exp $
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

   // Security: Don�t use User Vars
   $pagetitle = pslGetText('Search - Profiles');
   $xsiteobject = pslGetText('Information');
   // Defines The META TAG Page Type

   /*****************************
    START OF PAGE
    *****************************/

   $pro = pslNew('BE_Profiles');

   if (isset($_REQUEST['profileID']) && !empty($_REQUEST['profileID'])) {
      $content = $pro->indexDetail(clean($_REQUEST['profileID']));
   } else {
      $level = (isset($_REQUEST['level']) && !empty($_REQUEST['level'])) ? clean($_REQUEST['level']) : '';
      $next = (isset($_REQUEST['next']) && !empty($_REQUEST['next'])) ? clean($_REQUEST['next']) : '';
      $content = $pro->indexSearch($level, $next);
   }

   $ary = array();
   $ary['section'] = 'profilesSearch';

   // getUserTemplates();
   $chosenTemplate = getUserTemplates('', $ary['section']);

   generatePage($ary, $pagetitle, '', $content);
   page_close();

?>