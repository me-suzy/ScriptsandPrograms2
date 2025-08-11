<?php
   // $Id: sitemap.php,v 1.13 2005/06/09 17:34:47 mgifford Exp $
   /**
   * Public Sitemap
   *
   * @package     Back-End
   * @copyright   2002-5 - OpenConcept.ca
   * @version     $Id: sitemap.php,v 1.13 2005/06/09 17:34:47 mgifford Exp $
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
   *
   *
   * @global string $BE_currentLanguage
   * @global array $ary
   * @global string $articleURLname
   * @global string $sectionURLname
   * @global string $toplevelSections
   */

   require('./config.php');


   $pageTitle = pslgetText('Site Map'); // The name to be displayed in the header
   $xsiteobject = 'Site Map'; // This Defines The META Tag Object Type

   if (!empty($_GET['login'])) {
      $auth->login_if(!$perm->have_perm('user'));
   }

   // Objects
   $sectionObj = pslNew('BE_Section');

   $storyInfo = $sectionObj->getSectionMap('html');
   $ary['section'] = 'Sitemap';
   $breadcrumb = $sectionObj->breadcrumb($ary['section'], 'Sitemap', 'sitemap');

   $template = pslNew('slashTemplate');
   $chosenTemplate = getUserTemplates('', $ary['section']);

   $todaysDate = psl_dateLong(time());

   // place the output for the primary ('story') content section into an array and pass it to index*tpl
   $storyArray = array(
      'STORY_COLUMN' => $storyInfo, //smartText($storyInfo),
      'ROOTDIR' => $_PSL['rooturl'],
      'TODAYS_DATE' => $todaysDate
   );

   # debug("generating page",$sectionID);

   // generate the page
   generatePage($ary, $pageTitle, $breadcrumb, $storyArray, $storyInfo);

   // close the page
   page_close();

?>