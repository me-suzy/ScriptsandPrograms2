<?php
   //
   /**
    * target.php
    *
    * Action target UI page
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
   require_once('config.php');

   // set page variables
   $pagetitle = pslgetText('Targets for Actions');
   // name that appears in the header
   $xsiteobject = pslgetText('Action Targets');
   // meta tag object type

   // initialize page
   initializePage($xsiteobject);
   slashhead($pagetitle, $_PSL['metatags']); // PSB: not sure why we're passing in PSL metatags

   // page logic and rendering
   $targetObj = pslNew('BE_Target');
   // $targetObj will return FALSE if it can't init
   if (!$targetObj) {
      pslError(pslGetText('Fatal Error! The class BE_Target or an
         associated class could not be started'));

   } else {
      // generate the page
      $vars = clean($_REQUEST);
      $targetObj->renderPage($vars, 'EN');
   }

   slashfoot();
   page_close();

?>