<?php
   // $Id: BE_Followup_send_daemon.php,v 1.9 2005/05/25 20:49:48 mgifford Exp $
   /**
    * BE_Followup_send_daemon.php
    *
    * Stand-alone PHP application to send outgoing Actions on behalf
    *  of action participants
    *
    * @package     Back-End on phpSlash
    * @author      Mike Gifford
    * @copyright   Copyright (C) 2003 OpenConcept Consulting
    * @version     $Id: BE_Followup_send_daemon.php,v 1.9 2005/05/25 20:49:48 mgifford Exp $
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

   // page logic and rendering
   $standardAdmin = pslNew('BE_Standard_admin', 'BE_Followup');

   // $standardAdmin = pslNew('BE_Followup');

   if (!$standardAdmin) {
      pslError(pslGetText('Fatal Error! The class BE_Contact_admin or an
         associated class could not be started'));

   } else {
      $adminContent = '';

      if (!$perm->have_perm('contact')) {
         $adminContent .= getTitlebar('100%', 'Error! Invalid Privileges');
         $adminContent .= getError(pslgetText('Sorry. You do not have the necessary privilege to view this page.'));

      } else {

         $tpl = pslNew('slashTemplate');

         $tpl->set_file('followupDaemon', 'BE_followupDaemon.tpl');

         if (empty($_GET['page']))
            $_GET['page'] = 200;

         $current = clean($_GET['current']) + clean($_GET['page']);
         $request = clean("{$_SERVER['PHP_SELF']}?submit=" . pslgetText($_GET['submit'], '', true) . "&amp;petitionID={$_GET['petitionID']}&amp;followupID={$_GET['followupID']}&amp;current=$current&amp;page={$_GET['page']}");


         $vars = clean($_REQUEST);
         if (empty($vars['count']))
            $vars['count'] = '0';
         $pageContent = $standardAdmin->renderPage($vars, 'EN');

         if ($pageContent) {
            $metatag = "<meta http-equiv=\"Refresh\" content=\"20; url=$request\" />";
         } else {
            $pageContent = pslgetText('No More Contacts to Process!') . "<p><input type=\"button\" value=\"Close Window\" onClick=\"self.close()\"></p>";
            $metatag = '';
         }

         $tpl->set_var(array(
            'PAGE_CONTENT' => $pageContent,
            'REFRESH_METATAG' => $metatag,
            'REQUEST' => $request,
            'REQUEST_URI' => $_SERVER['REQUEST_URI'],
            'ROOTDIR' => $_PSL['rooturl'],
            'IMAGEDIR' => $_PSL['imageurl'],
            'VERSION' => $_PSL['version'],
            'SITE_NAME' => $_PSL['site_name']
         ));

         $tpl->pparse('CONTENT', 'followupDaemon');

         // $standardAdmin->renderPageContent($vars, 'EN', $pageContent);
      }
   }

?>