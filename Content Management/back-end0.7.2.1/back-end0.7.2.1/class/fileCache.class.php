<?php
   /**
    * Used for caching page elements.
    *
    * @access   private
    * @package  Common
    * @author   Peter Cruickshank
    * @version  $Id: fileCache.class.php,v 1.20 2005/05/24 11:11:40 krabu Exp $
    *
    * Modified to write/read commented out (//) headers -mg
    *
    * As the file cache is strictly a server-side entity, all date/time
    * manipulations stay in the server timezone or GMT (php conversion
    * functions are used instead of corresponding psl_* and tz_* functions).
    *
    */

   class fileCache extends file_cache_class {

      var $automatic_headers = 0;

      var $cacheLive = true;
      var $id;
      var $key;
      var $expires;
      var $cacheDir;


      /**
       * CONSTRUCTOR
       *
       * @param string $id panel (typically, its name)
       * @param string $key Extra info for identifying which version of the panel to retrieve (eg userid, or serialize($_GET))
       * @param int    $expires When to expire the cache - YYYY-MM-DD HH:MM:SS
       */
      function fileCache($id, $key = '', $expires = '') {
         global $_BE;

         umask(011);
         $this->cacheDir = $_BE['uploaddir'] . '/fileCache';

         // This code belongs as part of the install crutch in config.php
         // Make upload directory if required
         if(!is_dir($_BE['uploaddir'])) {
            mkdir($_BE['uploaddir'], 0777);
         }

         // Make cache directory if required
         if(!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0777);
         }

         $this->id = $id;
         $this->expires = $expires;
         if (!empty($key)) {
            $keyCode = '.'.$key;
         } else {
            $keyCode = '';
         }
         // $this->path = $_PSL['basedir'].$this->cacheDir.$id.$keyCode;
         $this->path = $this->cacheDir . '/' . $id.$keyCode;
      }


      /**
       * Fetches cache if possible
       *
       * Returns an empty string if either the cache needs refreshed or some other
       * problem was encountered.
       *
       * @return   string cache content
       * @access   public
       */
      function retrieve() {
         global $_PSL;
         if (!@$_PSL['filecache.enable']) {
            return '';
         }
         $out = '';
         $cacheOk = $this->verifycache($this->cacheLive);

         if ($cacheOk && $this->cacheLive) {
            $eoc = 0; // pass by ref - not used here tho.
            do {
               $cacheOk = $this->retrievefromcache($v, $eoc);
               $out .= $v;
            } while (!$eoc);
         }
#         if (!empty($this->error)) {
#            debug('Cache.retrieve error for:'.$this->id, $this->error);
#         }

         return $out;
      }


      function store($data) {
         global $_PSL;
         if (!@$_PSL['filecache.enable']) {
            return true;
         }
         # debug('fileCache store'.$this->id,strlen($data));
         // Use either the expiry time or expiry date
         if (!empty($this->expires)) {
            $this->setexpirydate($this->expires);
         } else {
            $this->setexpirytime(300); // Cache for 5 minutes
         }

         if (!$this->storedata($data, 1)) {
            if (!empty($this->error)) {
               # debug('Cache.store error for:' . $this->id, $this->error . ' ('.$this->path . ')=' . strlen($data));
               # debug('data', $data);
            }
         }
         return true;
      }


      /**
       * Clears family of related cache files when sections etc are added or updated
       *
       * Related files are identified by the initial part of their name
       *
       * @parma string base1, base2, base3...   Base names of file chaches to remove
       * @return NULL
       * @access public
       */
      function clearAllCaches() {
         $argCount   = func_num_args();
         $argList    = func_get_args();

         // Define Cache Directory
         if (isset($this->cacheDir)) {
            $dir = $this->cacheDir;
         } else {
            // This is used when this has been called as a static method (ie the class has not been instatiated)
            global $_BE;
            $dir = $_BE['uploaddir'] . '/fileCache';
         }

         // List and clear files in fileCache directory
         $open = opendir($dir);
         while ($file = readdir($open)) {
            $filePath = "$dir/$file";
            if (is_file($filePath)) {
               if ($argCount < 1) {
                  unlink($filePath);
               } else {
                  // If in the list of caches, delete it
                  foreach ($argList as $baseName) {
                     if (strpos($file,$baseName) === 0) {
                        @unlink($filePath);
                     }
                  }
               }
            }
         }
         closedir($open);
      }
   }

   // The real thing ------------------------------------------------------------------------------------------
   /**
    *
    * Copyright © (C) Manuel Lemos 2001
    *
    */

   class file_cache_class {
      /*
       * Protected variables
       *
       */
      var $read_cache_file;
      var $read_cache_file_opened = 0;
      var $write_cache_file;
      var $write_cache_file_opened = 0;
      var $writtencachelength = 0;

      /*
       * Public variables
       *
       */
      var $path = '';
      var $flush_cache_file = 0;
      var $error = '';
      var $reopenupdatedcache = 1;
      var $headers = array();
      var $cache_buffer_length = 100000;
      var $automatic_headers = 1;


      /*
       * Protected functions
       *
       */
      function writecachedata(&$data) {
         if (((fwrite($this->write_cache_file, $data, strlen($data)) != 0)))
         return 1;
         $this->error = 'could not write to the cache file';
         return 0;
      }


      function readcachedata(&$data, $length) {
         if (($length <= 0)) {
            $this->error = 'it was specified an invalid cache data read length';
            return 0;
         }
         if ((GetType($data = fread($this->read_cache_file, $length)) == 'string'))
         return 1;
         $this->error = 'could not read from the cache file';
         return 0;
      }


      function readcacheline(&$line, $length) {
         if (($length <= 0)) {
            $this->error = 'it was specified an invalid cache line read length';
            return 0;
         }
         if ((GetType($line = fgets($this->read_cache_file, $length)) == 'string')) {
            if ((GetType($endofline = strpos($line, "\r")) == 'integer'))
            $line = substr($line, 0, $endofline);
            return 1;
         }
         $this->error = 'could not read from the cache file';
         return 0;
      }


      function endofcache(&$endofcache) {
         if (!($this->read_cache_file_opened)) {
            $this->error = 'endofcache function is not implemented';
            return 0;
         }
         $endofcache = feof($this->read_cache_file);
         return 1;
      }


      function cachelength(&$length) {
         if (((GetType($length = @filesize($this->path)) == 'integer')))
         return 1;
         $this->error = 'could not determing cache file length';
         return 0;
      }


      function cachebufferlength(&$length) {
         $length = $this->cache_buffer_length;
         if (($length != 0 || $this->cachelength($length)))
         return 1;
         $this->error = ('could not get cache buffer length: ' . $this->error);
         return 0;
      }


      function readcacheheaders(&$read) {
         $this->headers = array();
         if (!($this->cachebufferlength($length)))
         return 0;
         for(; ; ) {
            if (!($this->readcacheline($line, $length)))
            return 0;
            if (!(strlen($line) != 0))
            break;
            if (!($this->endofcache($endofcache)))
            return 0;
            if ((GetType($endofline = strpos($line, "\r")) == 'integer'))
            $line = substr($line, 0, $endofline);
            if (($endofcache || !GetType($space = strpos($line, ' ')) == 'integer')) {
               $read = 0;
               return 1;
            }
            // commented out for touch modification
            $this->headers[substr($line, 0, $space)] = substr($line, ($space+1), (strlen($line)-$space-1));
         }
         $read = 1;
         return 1;
      }


      function updatedcache(&$updated) {
         if (!($this->readcacheheaders($updated)))
         return 0;
         if (($updated && IsSet($this->headers["//x-expires:"])))
         $updated = (strcmp($this->headers["//x-expires:"], strftime("%Y-%m-%d %H:%M:%S")) > 0);
         if (!($updated))
         $this->headers = array();
         return 1;
      }


      function writecache(&$data, $endofcache) {
         if (($this->writtencachelength == 0)) {
            $rfc822now = gmstrftime("%a, %d %b %Y %H:%M:%S GMT");
            srand(time());
            if (($this->automatic_headers && (!$this->setheader('date:', $rfc822now) || !$this->setheader('etag:', '"' . md5(strval(rand(0, 9999)).$rfc822now).'"') || ($endofcache && !$this->setheader('content-length:', strval(strlen($data)))))))
            return 0;
            $header = 0;
            Reset($this->headers);
            for(; $header < Count($this->headers); ) {
               $headername = Key($this->headers);
               $headerdata = ($headername.' '.$this->headers[$headername]."\r\n");
               if (!($this->writecachedata($headerdata))) {
                  $this->error = ('could not write to the cache headers: '.$this->error);
                  return 0;
               }
               Next($this->headers);
               ++$header;
            }
            $headerdata = "\r\n";
            if (!($this->writecachedata($headerdata))) {
               $this->error = ('could not write to the cache header separator: ' . $this->error);
               return 0;
            }
         }
         if (!($this->writecachedata($data))) {
            $this->error = ('could not write to the cache data: ' . $this->error);
            return 0;
         }
         $this->writtencachelength = ($this->writtencachelength+strlen($data));
         return true;
      }


      /*
       * Public functions
       *
       */
      function verifycache(&$updated) {
         if (($this->read_cache_file_opened)) {
            $this->error = 'the cache file is already opened for reading';
            return 0;
         }
         if ((!strcmp($this->path, ''))) {
            $this->error = 'it was not specified the cache file path';
            return 0;
         }
         $updated = 0;
         if ((file_exists($this->path))) {
            if (!((($this->read_cache_file = @fopen($this->path, 'rb')) != 0)))
            {
               $this->error = 'could not open cache file for reading';
               return 0;
            }
            if (!(@flock($this->read_cache_file, 1))) {
               fclose($this->read_cache_file);
               $this->error = 'could not lock shared cache file';
               return 0;
            }
            $this->read_cache_file_opened = 1;
            $success = $this->cachelength($length);
            if (($success)) {
               if (($length > 0))
               $success = $this->updatedcache($updated);
            }
            if (!($success)) {
               @flock($this->read_cache_file, 3);
               fclose($this->read_cache_file);
               $this->read_cache_file_opened = 0;
               return 0;
            }
            if (($updated))
            return 1;
            $success = @flock($this->read_cache_file, 3);
            fclose($this->read_cache_file);
            $this->read_cache_file_opened = 0;
            if (!($success)) {
               $this->error = 'could not unlock the cache file';
               return 0;
            }
         }
         if (!((($this->read_cache_file = @fopen($this->path, 'ab')) != 0)))
         {
            $this->error = 'could not open cache file for appending';
            return 0;
         }
         $this->read_cache_file_opened = 1;
         if (!(@flock($this->read_cache_file, 2))) {
            $this->error = 'could not lock exclusive cache file';
            @flock($this->read_cache_file, 3);
            fclose($this->read_cache_file);
            $this->read_cache_file_opened = 0;
            return 0;
         }
         if (!((($this->write_cache_file = @fopen($this->path, 'wb')) != 0))) {
            @flock($this->read_cache_file, 3);
            fclose($this->read_cache_file);
            $this->read_cache_file_opened = 0;
            $this->error = 'could not open cache file for writing';
            return 0;
         }
         $this->write_cache_file_opened = 1;
         return 1;
      }


      function storedata(&$data, $endofcache) {
         if (!($this->write_cache_file_opened)) {
            $this->error = 'cache file is not set for storing data';
            return 0;
         }
         $success = $this->writecache($data, $endofcache);
         if (($success)) {
            $success = (!$endofcache || !$this->flush_cache_file || fflush($this->write_cache_file));
            if (!($success))
            $this->error = 'could not flush the cache file';
         }
         if (!($success)) {
            fclose($this->write_cache_file);
            $this->write_cache_file_opened = 0;
            @flock($this->read_cache_file, 3);
            fclose($this->read_cache_file);
            unlink($this->path);
            $this->read_cache_file_opened = 0;
            return 0;
         }
         if (!($endofcache))
         return 1;
         fclose($this->write_cache_file);
         $this->write_cache_file_opened = 0;
         @flock($this->read_cache_file, 3);
         fclose($this->read_cache_file);
         $this->read_cache_file_opened = 0;
         if (!($this->reopenupdatedcache))
         return 1;
         if (!((($this->read_cache_file = @fopen($this->path, 'rb')) != 0))) {
            $this->error = 'could not reopen cache file for reading';
            return 0;
         }
         if (!(@flock($this->read_cache_file, 1))) {
            fclose($this->read_cache_file);
            $this->error = 'could not lock shared cache file';
            return 0;
         }
         $this->read_cache_file_opened = 1;
         if (!($this->readcacheheaders($read)))
         return 0;
         if (!($read)) {
            @flock($this->read_cache_file, 3);
            fclose($this->read_cache_file);
            $this->read_cache_file_opened = 0;
            $this->error = 'could not read the cache file headers';
            return 0;
         }
         return 1;
      }


      function retrievefromcache(&$data, &$endofcache) {
         if (!($this->read_cache_file_opened)) {
            $this->error = 'cache file is not set for retrieving cached data';
            return 0;
         }
         if (($this->write_cache_file_opened)) {
            $this->error = 'cache file is still opened for writing';
            return 0;
         }
         $success = $this->cachebufferlength($length);
         if (($success))
         $success = ($length == 0 || $this->readcachedata($data, $length));
         if (!($success)) {
            @flock($this->read_cache_file, 3);
            fclose($this->read_cache_file);
            $this->read_cache_file_opened = 0;
            return 0;
         }
         if (($length == 0))
         $endofcache = 1;
         else
            {
            if (!($this->endofcache($endofcache))) {
               @flock($this->read_cache_file, 3);
               fclose($this->read_cache_file);
               $this->read_cache_file_opened = 0;
               return 0;
            }
         }
         if (!($endofcache))
         return 1;
         @flock($this->read_cache_file, 3);
         fclose($this->read_cache_file);
         $this->read_cache_file_opened = 0;
         return 1;
      }


      function closecache() {
         if (($this->read_cache_file_opened || $this->write_cache_file_opened)) {
            @flock($this->read_cache_file, 3);
            $this->read_cache_file_opened = 0;
         }
         if (($this->read_cache_file_opened)) {
            fclose($this->read_cache_file);
            $this->read_cache_file_opened = 0;
         }
         if (($this->write_cache_file_opened)) {
            fclose($this->write_cache_file);
            $this->write_cache_file_opened = 0;
         }
      }


      function voidcache() {
         if (($this->read_cache_file_opened)) {
            $this->error = 'can not void cache with the file open for reading';
            return 0;
         }
         if ((!strcmp($this->path, ''))) {
            $this->error = 'it was not specified the cache file path';
            return 0;
         }
         if (!(file_exists($this->path)))
         return 1;
         if (!((($this->read_cache_file = @fopen($this->path, 'ab')) != 0))) {
            $this->error = 'could not open the cache file for reading';
            return 0;
         }
         if (!(@flock($this->read_cache_file, 2))) {
            fclose($this->read_cache_file);
            $this->error = 'could not lock exclusive cache file';
            return 0;
         }
         $success = (($this->write_cache_file = @fopen($this->path, 'wb')) != 0);
         if (($success))
         fclose($this->write_cache_file);
         @flock($this->read_cache_file, 3);
         fclose($this->read_cache_file);
         $this->error = 'could not open cache file for writing';
         unlink($this->path);
         return $success;
      }


      function setheader($header, $value) {
         if ((GetType(strpos($header, ' ')) == 'integer')) {
            $this->error = 'it was specified a header name with a space in it';
            return 0;
         }
         $lowerheader = strtolower($header);
         if ((IsSet($this->headers[$lowerheader]))) {
            $this->error = 'it was specified a header already defined';
            return 0;
         }
         $this->headers[$lowerheader] = $value;
         return 1;
      }


      function setexpirydate($date) {
         if (!($this->setheader('//x-expires:', $date)))
         return 0;
         if (($this->automatic_headers))
         return $this->setheader('expires:', gmstrftime("%a, %d %b %Y %H:%M:%S GMT", mktime(substr($date, 11, 2), substr($date, 14, 2), substr($date, 17, 2), substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4))));
         return 1;
      }


      function setexpirytime($time) {
         if (($time <= 0)) {
            $this->error = 'it was not specified a valid expiry time period';
            return 0;
         }
         return $this->setexpirydate(strftime("%Y-%m-%d %H:%M:%S", time()+$time));
      }
   }

?>
