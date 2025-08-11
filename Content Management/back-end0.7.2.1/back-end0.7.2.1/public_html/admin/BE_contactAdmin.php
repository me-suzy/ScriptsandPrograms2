<?php
   // $Id: BE_contactAdmin.php,v 1.12 2005/05/25 20:49:48 mgifford Exp $
   /**
    * BE_contactAdmin.php
    *
    * Administration UI page for Action Contacts
    *
    * @package     Back-End on phpSlash
    * @author      Peter Bojanic
    * @copyright   Copyright (C) 2003 OpenConcept Consulting
    * @version     $Id: BE_contactAdmin.php,v 1.12 2005/05/25 20:49:48 mgifford Exp $
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

   // initialize Back-End by including this file
   require_once('config.php');

   $pagetitle = pslgetText('Contact Administration'); // name that appears in the header
   $xsiteobject = pslgetText('Administration'); // meta tag object type
   $_PSL['metatags']['object'] = $xsiteobject;

   // set page variables
   $ary = array();
   $adminContent = null;

   // page logic and rendering
   $standardAdmin = pslNew('BE_Standard_admin', 'BE_Contact_admin');
   if (!$standardAdmin) {
      pslError(pslGetText('Fatal Error! The class BE_Contact_admin or an
         associated class could not be started'));

   } else {
      if ($perm->have_perm('contact') || $perm->have_perm('root')) {
         $vars = clean($_REQUEST);
         $vars['submit'] = pslgetText($vars['submit'], '', true);
         $vars['section'] = 'Admin';
         if (@$vars['count'] < 1 || @$vars['count'] > 200) // needs to be paginated
         $vars['count'] = '25';

         $adminContent = $standardAdmin->renderPage($vars, 'EN');
         // $adminContent = $standardAdmin->renderPageContent($vars, 'EN', $pageContent);
      } else {
         $adminContent .= getTitlebar('100%', 'Error! Invalid Privileges');
         $adminContent .= getError(pslgetText('Sorry. You do not have the necessary privilege to view this page.'));
      }
   }

   $ary['section'] = 'admin';

   $chosenTemplate = getUserTemplates('', $ary['section']);

   $breadcrumb = '<a href="' . $_PSL['rooturl'] . '/">' . pslgetText('Home') . '</a> &#187; ' .$pagetitle;

   // generate the page
   generatePage($ary, $pagetitle, $breadcrumb, $adminContent);

   page_close();

?>