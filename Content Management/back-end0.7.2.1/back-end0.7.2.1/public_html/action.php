<?php
   //
   /**
    * action.php
    *
    * Action UI page
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
   $pagetitle = pslgetText('Actions'); // name that appears in the header
   $xsiteobject = pslgetText('Actions'); // meta tag object type

   $section =  (isset($section))  ? $section  : null;
   $type =     (isset($type))     ? $type     : null;
   $page =     (isset($page))     ? $page     : null;
   $language = (isset($language)) ? $language : null;

   // store the section and article in an array to pass to the Block later on
   $ary = getRequestValue(@$section, @$article, @$type);

   if (!empty($_GET['login'])) {
      $auth->login_if(!$perm->have_perm('user'));
   }

   //  In subsites, we can't count on the action section existing,
   // so we'll just set it to the home section.
   if(be_inSubsite()) {
      $section = $BE_subsite['URLname'];
   }

   // Objects
   $templateObj = & pslNew('slashTemplate');

   $_PSL['metatags']['object'] = $xsiteobject;
   $_BE['currentSection'] = $section;

   // initialize page
   initializePage($xsiteobject);
   // page logic and rendering
   $actionObj = pslNew('BE_Action_participate');
   // $actionObj will return FALSE if it can't init
   if (!$actionObj) {
      pslError(pslGetText('Fatal Error! The class BE_Action or an
         associated class could not be started'));
      slashhead($pagetitle, $_PSL['metatags'], $section, $chosenTemplate['header']); // PSB: not sure why we're passing in PSL metatags


   } else {

      // DB_DataObject::debugLevel(10);

      // generate the page
      $vars = clean($_REQUEST);
      $sectionObj  = DB_DataObject::factory('be_sections');
      $sectionObj->getByName($section);
      $pageContent = $actionObj->renderPage($vars, $sectionObj);
      $actionObj->generatePage($vars, $pagetitle, $pageContent, $sectionObj);
   }

   page_close();

?>
