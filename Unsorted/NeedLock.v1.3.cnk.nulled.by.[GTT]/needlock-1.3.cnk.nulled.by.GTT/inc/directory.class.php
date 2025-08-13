<?php


class dir {

 var $top_dir = "";
 var $workfiles = array();
 var $dirs = array();

 function get_dirs( $dir ) {
   global $needsecure, $std, $INFO;

    $dir = preg_replace( "#/$#", "", $dir );

    if ( file_exists($dir) ) {
  	   if ( is_dir($dir) ) {

          $handle = opendir($dir);
             while (($subdir = readdir($handle)) !== false) {
		           if ( ($subdir != ".") && ($subdir != "..") ) {
		                if ( is_dir($dir.'/'.$subdir) ) {
		                   $this->dirs[] = $dir.'/'.$subdir;
		                } elseif ( is_file($dir.'/'.$subdir) ) {
                           next;
		                }
	               } else {
		                next;
		           }
	         }
          closedir($handle);

       } else {
          echo "$dir is not a directory";
          return FALSE;
       }

    } else {
       echo "Could not locate $dir";
       return;
    }
 }

 function get_dir_contents( $dir ) {

  $dir = preg_replace( "#/$#", "", $dir );

  if ( file_exists($dir) ) {
    if ( is_dir($dir) ) {

       $handle = opendir($dir);
       while (($filename = readdir($handle)) !== false) {
       	     if (($filename != ".") && ($filename != "..")) {
	            if (is_dir($dir."/".$filename)) {
		           $this->get_dir_contents($dir."/".$filename);
	            } else {
		           $this->workfiles[] = $dir."/".$filename;
		        }
	         }
       }
       closedir($handle);

    } else {
       $this->error = "{$dir} is not a directory";
       return FALSE;
    }

  } else {
    $this->error = "Could not locate $dir";
    return;
  }

 }

 function createDirectory( $dir ) {
   global $needsecure,$std,$INFO;

   $dir = preg_replace( "#/$#", "", $dir );

   if ( !file_exists( $dir ) && !is_dir( $dir ) ) {
      @mkdir( $dir, 0777 );
   } else {
      $std->Error("Can't create directory {$dir}. Target exists.");
   }

 }

 function renameDirectory( $dir ) {

    $dir = preg_replace( "#/$#", "", $dir );


 }

 function removeDirectory( $dir ) {
   global $needsecure,$std,$INFO;

   $dir = preg_replace( "#/$#", "", $dir );

   if ( file_exists( $dir ) && is_dir( $dir ) ) {

      $this->get_dir_contents( $dir );

       for ( $i = 0; $i < count($this->workfiles); $i++ ) {

           if ( is_file( $this->workfiles[$i] ) ) {
              @unlink( $this->workfiles[$i] );
           } elseif ( is_dir( $this->workfiles[$i] ) ) {
              $this->get_dir_contents( $this->workfiles[$i] );
           }

       }

       @rmdir( $dir );

   } else {
      $std->Error("Can't create directory {$dir}. Target exists.");
   }

 }

} // End of class

?>