<?php
   /**
    * contact.php
    *
    * Action contact UI page
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

   /**
   * Initialize Back-End by including this file
   * @ignore
   */
   require_once('config.php');

   // set page variables
   $pagetitle = pslgetText('Catalog');
   // name that appears in the header
   $xsiteobject = pslgetText('Catalog Administration');
   // meta tag object type

   // initialize page
   initializePage($xsiteobject);
   slashhead($pagetitle, $_PSL['metatags']); // PSB: not sure why we're passing in PSL metatags

   // page logic and rendering
   $catalogObj = pslNew('BE_Catalog');
   // $contactObj will return FALSE if it can't init
   if (!$catalogObj) {
      pslError(pslGetText('Fatal Error! The class BE_Catalog or an associated class could not be started'));

   } else {
      // generate the page
      $vars = $_REQUEST;
      $vars['section'] = 'history';

#      // Check to see if user is logged in with actiOn priviledges
#      $permValue = $perm->have_perm('action');
#      if ($permValue) {
#         // They shouldn't be able to see catalog info
#         // $vars['submit']='';

         $language = getCurrentLanguage();
         $pageContent = $catalogObj->renderPage($vars, $language);
         $catalogObj->renderPageContent($vars, $language, $pageContent);
#      } else {
#         echo "You do not have permission to view this page";
#      }
   }

   slashfoot();
   page_close();

?>
