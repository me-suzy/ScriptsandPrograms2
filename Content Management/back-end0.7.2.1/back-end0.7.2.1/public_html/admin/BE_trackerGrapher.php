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

   if(true) {

      @include_once('phpOpenTracker.php');

      // Gather Variables
      $variableArray1 = array(
         'client_id'     => $client_id_default,
         'api_call'      => 'all_paths',  // Options: access_statistics,
                                         // top, page_impressions, visits
         'result_format' => 'graphviz_object'
      );

      /*
      $variableArray2 = array(
         'range'         => 'today',      // total,  current_year,
                                          // current_month, today, yesterday
         'range_length'  => '',
         'range_start'   => '',
         'start'         => '',
         'end'           => '',
         'interval'      => 'hour',          // hour, day, month, year
         'mode'          => 'access_statistics',
         'what'          => '',              // document
         'limit'         => '',              // 25
         'width'         => 640,
         'height'        => 480
      );
      */

      $variableArray2 = array();

      $variableArray = array_merge($variableArray1, $variableArray2);

      foreach($variableArray AS $variable => $default) {
         $$variable = getRequestVar($variable,'G');
         $$variable = (!empty($$variable)) ? $$variable : $default;
      }


      // Define Array
      $graphArray = array();

      $graphArray['client_id'] = $client_id;

      if (!empty($api_call)) {
         $graphArray['api_call'] = $api_call;
      }
      if (!empty($result_format)) {
         $graphArray['result_format'] = $result_format;
      }

      $graph = phpOpenTracker::get($graphArray);

      $graph->image('png');

   } else {
      echo pslgetText('phpOpenTracker.php not properly installed');
   }

?>
