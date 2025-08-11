<?php
   /**
    * petition.php
    *** This is a pretty simple copy/search/replace from action.php
    * Petition UI page
    *
    * @package     Back-End on phpSlash
    * @author      Peter Bojanic
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


   // disable caching
   $cachetimeout = -1;

   // initialize Back-End by including this file
   require_once('PET_config.php');

   // set page variables
   $pagetitle = pslgetText('Petition - Verify Signature');
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
      pslError(pslGetText('Fatal Error! The class PET_Petition or an
         associated class could not be started'));

   } else {


      // generate the page
      $vars = clean($_REQUEST);
      $vars['section'] = 'Petitions';
      $language = getCurrentLanguage();

      $viewAlertForm = true;
      if ($viewAlertForm) {
         $alertObj = pslNew('PET_Petition_alert');
         $alertForm = $alertObj->renderAlertPage($vars, $language);
      }

      if (empty($vars['pet']))
         $vars['pet'] = 1;

      // If an id is provided, check password
      if (!empty($vars['id'])) {
         $success = $petitionObj->verifySignature($vars['pet'], $vars['id'], $vars['pw']);

         if ($success)
            $pageContent = "<p>Email Verified, Thanks</p>";
         else
            $pageContent = "<p>Inaccurate id/password</p>";
      } elseif($vars['email']) {

         $success = $petitionObj->verifyEmail($vars['pet'], $vars['email']);

         if ($success)
            $pageContent = "<p>Email Verified, Thanks</p>";
         else
            $pageContent = "<p>Inaccurate id/password</p>";

      } else {
         $pageContent = "<p>Verify Email<br /></p><form action=\"\"><input type=\"text\" name=\"email\" size=\"20\" maxlength=\"200\"><input name=\"submit\" value=\"submit\" type=\"submit\"></form>";
      }

      $pageContent .= $alertForm;
      $petitionObj->renderPageContent($vars, $language, $pageContent);
   }

   slashfoot();
   page_close();

?>