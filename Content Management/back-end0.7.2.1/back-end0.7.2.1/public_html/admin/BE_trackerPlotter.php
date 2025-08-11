<?php

   require('./config.php');

   $client_id_default = 2;

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

   if(isset($_PSL['module']['phpOpenTracker']) && $_PSL['module']['phpOpenTracker']) {

      @include_once('phpOpenTracker.php');


      // Gather Variables
      $variableArray = array(
         'client_id'    => $client_id_default,
         'api_call'     => 'access_statistics', // Options: access_statistics,
                                            // top, page_impressions, visits
         'range'        => 'today',      // total,  current_year, current_month,
                                         // today, yesterday
         'range_length' => '',
         'range_start'  => '',
         'start'        => '',
         'end'          => '',
         'interval'     => 'hour',          // hour, day, month, year
         'mode'         => 'access_statistics',
         'what'         => '',              // document
         'limit'        => '',              // 25
         'width'        => 640,
         'height'       => 480
      );

      foreach($variableArray AS $variable => $default) {
         $$variable = getRequestVar($variable,'G');
         $$variable = (!empty($$variable)) ? $$variable : $default;
      }


      // Define Array
      $plotArray = array();

      $plotArray['client_id'] = $client_id;
      $plotArray['width']     = $width;
      $plotArray['height']    = $height;

      if (!empty($api_call)) {
         $plotArray['api_call'] = $api_call;
      }
      if (!empty($range)) {
         $plotArray['range'] = $range;
      }
      if (!empty($interval)) {
         $plotArray['interval'] = $interval;
      }
      if (!empty($mode)) {
         $plotArray['mode'] = $mode;
      }
      if (!empty($what)) {
         $plotArray['what'] = $what;
      }
      if (!empty($limit)) {
         $plotArray['limit'] = $limit;
      }

      // Plot Graphic
      phpOpenTracker::plot($plotArray);

   } else {
      echo pslgetText('phpOpenTracker.php not installed');
   }

?>
