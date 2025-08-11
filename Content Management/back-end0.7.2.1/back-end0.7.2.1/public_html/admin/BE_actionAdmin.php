<?php
   // $Id: BE_actionAdmin.php,v 1.18 2005/05/25 20:49:48 mgifford Exp $
   /**
    * BE_actionAdmin.php
    *
    * Administration UI page for Actions
    *
    * @package     Back-End on phpSlash
    * @author      Peter Bojanic
    * @copyright   Copyright (C) 2003 OpenConcept Consulting
    * @version     $Id: BE_actionAdmin.php,v 1.18 2005/05/25 20:49:48 mgifford Exp $
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

   $pagetitle = pslgetText('Action Administration'); // name that appears in the header
   $xsiteobject = pslgetText('Administration'); // meta tag object type

   // set page variables
   $ary = array();
   $adminContent = '';

   $section = 'Admin';
   $_BE['currentSection']        = $section;
   $_PSL['metatags']['object'] = $xsiteobject;

   // page logic and rendering
   $standardAdmin = pslNew('BE_Action_admin');
   if (!$standardAdmin) {
      pslError(pslGetText('Fatal Error! The class BE_Action_admin or an
         associated class could not be started'));
      $vars['templates'] = array('slashTemplate');
   } else {
      if (!$perm->have_perm('action')) {

         $adminContent .= getTitlebar("100%", "Error! Invalid Privileges");
         $adminContent .= getError(pslgetText("Sorry. You do not have the necessary privilege to view this page."));
      $vars['templates'] = array('slashTemplate');

      } else {

        $sectionObj  = DB_DataObject::factory('be_sections');
        $sectionObj->getByName($section);
         $vars = clean($_REQUEST, true);

         if (@$vars['count'] < 1 || @$vars['count'] > 200) // needs to be paginated
            $vars['count'] = '25';
         $vars['submit'] = decodeAction($vars);

         $adminContent = $standardAdmin->renderPage($vars, $sectionObj);
      }
   }

   // generate the page
   $standardAdmin->generatePage($vars, $pagetitle, $adminContent, $sectionObj);

   page_close();

?>
