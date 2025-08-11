<?php
  // $Id: BE_tracker.php,v 1.1 2005/05/11 19:37:01 mgifford Exp $
   /**
   * Back-End Articles Administration
   *
   * Equivalent phpSlash file: storyAdmin.php - the two files should be kept compatible
   *
   * Permissions are shared with phpSlash stories
   *
   * @package     Back-End on phpSlash
   * @copyright   2002-5 - Open Concept Consulting
   * @version     $Id: BE_tracker.php,v 1.1 2005/05/11 19:37:01 mgifford Exp $
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

   require('./config.php');

   $client_id_default = 2;

   $pagetitle = pslgetText('User Stats Administration'); // The name to be displayed in the header
   $xsiteobject = pslgetText('Administration');   // Defines The META TAG Page Type
   $_PSL['metatags']['object'] = $xsiteobject;

   $content = null;

   // $auth->login_if(!$perm->have_perm('root'));

   /**
    * Check if a file exists in the include path
    *
    * @version      1.2.0
    * @author       Aidan Lister <aidan@php.net>
    * @param        string     $file       Name of the file to look for
    * @return       bool       TRUE if the file exists, FALSE if it does not

   function file_exists_incpath ($file) {
       $paths = explode(PATH_SEPARATOR, ini_get('include_path'));
       foreach ($paths as $path) {
           // Formulate the absolute path
           $fullpath = $path . DIRECTORY_SEPARATOR . $file;

           // Check it
           if (file_exists($fullpath)) {
               return true;
           }
       }
       return false;
   }
    */

   if(true) {

      @include_once('phpOpenTracker.php');

      $client_id = getRequestVar('client_id','G');
      $client_id = (!empty($client_id)) ? $client_id : 1;

      $callArray = array(
         'average_clickpath_length',
         'average_time_between_visits',
         'average_visits',
         'num_one_time_visitors',
         'visitors_online',
         'num_return_visits',
         'num_returning_visitors',
         'num_unique_visitors',
         'num_visitors_online',
         'page_impressions',
      );

      foreach($callArray AS $call) {
         $$call  = phpOpenTracker::get(
            array(
               'client_id' => $client_id,
               'api_call'  => $call
            )
         );
      }

      $content = getTitlebar('100%', 'User Stats via phpOpenTracker');

      $content .= "<p>\n Clickpath Length $average_clickpath_length \n</p>";

      $content .= "<p>\n Time Between Visits $average_time_between_visits  \n</p>";

      $content .= "<p>\n Average Visits $average_visits  \n</p>";

      $content .= "<p>\n One Time Visitors $num_one_time_visitors  \n</p>";

      $content .= "<p>\n Page Impressions $page_impressions  \n</p>";

      $content .= "<p>\n Users Online : " . count($visitors_online) .  "\n</p>";


      $imageArray[] = array(
         'name'        => 'BE_trackerPlotter.php',
         'description' => 'Daily clicks'
      );
      $imageArray[] = array(
         'name'        => 'BE_trackerPlotter.php?api_call=top&amp;limit=20&amp;range=current_month&amp;what=document',
         'description' => 'Top Pages This Month'
      );

      $imageArray[] = array(
         'name'        => 'BE_trackerGrapher.php?api_call=all_paths&amp;result_format=20graphviz_object',
         'description' => 'Clickthrough Paths'
      );

      foreach($imageArray AS $image) {
         $content .= '<h3>' . $image['description'] . '</h3>';
         $content .= '<img src="' . $image['name'] . '" /><br />';
      }

      // echo "<pre>"; print_r($visitors_online); echo "</pre>";;

   } else {
      $content = getTitlebar('100%', 'User Stats via phpOpenTracker');
      $content .= pslgetText('phpOpenTracker.php not properly installed');
   }

   $breadcrumb = '<a href="' . $_PSL['rooturl'] . '/">' . pslgetText('Home') . '</a> &#187; ' .$pagetitle;

   // generate the page
   generatePage($ary, $pagetitle, $breadcrumb, $content);

   page_close();

?>
