<?php
   /**
    * functions for working with the tz classes
    *
    * $Id: libtz.inc,v 1.3 2005/03/16 00:58:31 mgifford Exp $
    */
    
   /**
    * select the working time zone
    *
    * sets $_TZ['name'] and $_TZ['obj'].
    *
    * @param string name of the time zone
    * @return boolean success (whether the time zone name made sense)
    */
   function tz_select($tz_name) {
      GLOBAL $_TZ;
       
      $tz = & $_TZ['obj'];
       
      if (is_object($tz) && $tz->name == $tz_name) {
         // do nothing; we've already selected the right one
         return true;
      } elseif ($_TZ['native']) {
         $tz = pslNew("tz_env", $tz_name);
         return $tz->valid;
      } else {
         /* we have non-native time zone objects selected. We need to
          * figure out which object to use based on the name.
          * Luckily each object constructor (though it can't return a
          * value) sets the "valid" member to indicate if the
          * construction succeeded in something useful.  */
          
         /* First try a file. */
         $tz = pslNew("tz_file", $tz_name);
         if ($tz->valid) {
            return true;
         } else {
            /* OK, maybe a string */
            $tz = pslNew("tz_posix", $tz_name);
            if ($tz->valid)
               return true;
            else
            {
               /* We're stumped! We use the dumbest object*/
               $tz = pslNew("tz", $tz_name);
               $tz->error = "Can't understand time zone name: $tz_name";
               return false;
            }
         }
      }
   }
    
   /**
    * generic time zone object wrapper
    *
    * @access private
    */
   function tz_generic() {
      $fn = "tz_generic";
      // echo "$fn: begin\n";
      GLOBAL $_TZ;
      $argv = func_get_args();
      $method = array_shift($argv);
      //echo "method: $method\n";
      //echo "object class: ", get_class($_TZ['obj']), "\n";
      return call_user_func_array(array(&$_TZ['obj'], $method), $argv);
   }
    
   /**
    * get the local time, with timezone adjustment
    *
    * uses the $_TZ['obj'] object
    *
    * @link http://www.php.net/localtime
    * @see tz::localtime
    * @param integer timestamp (defaults to current time)
    * @param boolean whether to return as associative array
    * @global mixed TZ environment
    * @return array  simple or (if $is_assoc==true) associative array
    *                like the C tm structure
    */
   function tz_localtime($t = false, $is_assoc = 0) {
      return tz_generic('localtime', $t, $is_assoc);
   }
    
   /**
    * Get UNIX timestamp for a date
    *
    * uses the $_TZ['obj'] object
    *
    * @link http://www.php.net/mktime
    * @see tz::mktime
    * @param integer hour
    * @param integer minute
    * @param integer second
    * @param integer month
    * @param integer day
    * @param integer year
    * @param integer is_dst
    * @param string  time zone name (effectively defaults to $TZ)
    * @return integer timestamp
    *
    */
   function tz_mktime($hour = false, $minute = false, $second = false,
      $month = false, $day = false, $year = false, $is_dst = -1, $tz_str = '') {
      return tz_generic('mktime', $hour, $minute, $second, $month, $day, $year, $is_dst);
   }
    
   /**
    * format time, with time zone adjustment
    *
    * uses the $_TZ['obj'] object
    *
    * @link http://www.php.net/strftime
    * @see tz::strftime
    * @param string format
    * @param integer timestamp (defaults to current time)
    * @return string
    */
   function tz_strftime($fmt, $t = false) {
      return tz_generic('strftime', $fmt, $t);
   }
    
   /**
    * convert human-readable date/time string to timestamp, minding time zone.
    *
    * uses the $_TZ['obj'] object.
    *
    * @see tz::strtotime
    * @param string date/time string
    * @return integer timestamp
    */
   function tz_strtotime($str) {
      return tz_generic('strtotime', $str);
   }
    
   /**
    * @global mixed $_TZ
    */
   $_TZ['default'] = ':/etc/localtime';
   // $_TZ['name'] = 'America/Chicago';
   $_TZ['dir'] = '/usr/share/zoneinfo';
   // $_TZ['native'] = false;
    
    
    
    
?>
