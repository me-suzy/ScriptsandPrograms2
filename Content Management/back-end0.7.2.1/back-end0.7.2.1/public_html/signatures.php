<?php
   /**
    * signatures.php
    *** This is a pretty simple copy/search/replace from action.php
    * Petition UI page
    *
    * @package     Back-End on phpSlash
    * @author      Mike Gifford
    * @copyright   Copyright (C) 2003 OpenConcept Consulting
    * @version
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


   // enable caching
   // $cachetimeout=-1;

   // initialize Back-End by including this file
   require_once('PET_config.php');

   // set page variables
   $pagetitle = pslgetText('Petitions');
   // name that appears in the header
   $xsiteobject = pslgetText('Petitions');
   // meta tag object type

   // initialize page
   initializePage($xsiteobject);
   slashhead($pagetitle, $_PSL['metatags']); // PSB: not sure why we're passing in PSL metatags

   // page logic and rendering
   $petitionObj = pslNew('PET_Petition2Contact');

   // $petitionObj will return FALSE if it can't init
   if (!$petitionObj) {
      pslError(pslGetText('Fatal Error! The class PET_Petition or an associated class could not be started'));

   // Object exists
   } else {

      // generate the page
      $vars = clean($_REQUEST);
      $vars['submit'] = pslgetText($vars['submit'], '', true);

      if (empty($vars['petitionID'])) {
         $vars['petitionID'] = $petitionID;
      }

      $vars['section'] = 'Petitions';
      if (@$vars['count'] < 1 || @$vars['count'] > 200) { // needs to be paginated
         $vars['count'] = '25';
      }

      $language = getCurrentLanguage();

      // Check to see if user is logged in with action priviledges
      $permValue = $perm->have_perm('action');
      if (!$permValue) {
         // They shouldn't be able to see contact info and shouldn't see non public/non-verified
         $vars['submit'] = '';
         $vars['public'] = 1;
         if (!$_PET['defaultApprovedVal'])
            $vars['approved'] = 1;
         if (!$_PET['defaultVerifiedVal'])
            $vars['verified'] = 1;
      }

      // echo "<pre>"; print_r($vars); echo "</pre>";

      $viewAlertForm = true;
      if ($viewAlertForm) {
         $alertObj = pslNew('PET_Petition_alert');
         $alertForm = $alertObj->renderAlertPage($vars, $language);
      }

      $pageContent = $petitionObj->renderPage($vars, $BE_currentLanguage);
      if (empty($pageContent)) {
         $pageContent = pslGetText('Petition') . ' (' . $vars['petitionID'] . ') ' .  pslGetText('Not Active');
      }

      $petitionObj->renderPageContent($vars, $language, $pageContent);

   }

   slashfoot();
   page_close();

?>