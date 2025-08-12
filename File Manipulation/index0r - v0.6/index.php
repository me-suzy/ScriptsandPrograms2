<?php
################################################################################
## Project Name: index0r
## Author: Phillip Metzger (phillip.metzger@gmail.com) (http://0zz.org/)
## 
## Version: 0.7;
## Modified: 11/9/2005 12:20:14 PM
################################################################################
/*******************************************************************************
++ Changes ++
Case insensitive file type compare


++ Install ++

1. Extract everything in to the root directory you want to list.
2. Modify configurations
3. Call with:
     $directory_list = new index0r;
     $directory_list->main();
     
     CSS class names for alternating row colors: .alt_row1, .alt_row2.
      - if html="on" style is manually created. Other wise it must be
        specified manually.

*******************************************************************************/
class index0r {
  var $html;
  var $debug;
	var $directory_root;
	var $homepage;
	var $img_src;
	var $blacklist;
	var $failed_msg;
	var $max_length;
	var $date_format;
	var $file_types;
	var $alt_color;
	var $alt_color_one;
	var $alt_color_two;
	var $order;
	var $table_width;
	
	var $root_dir;
	var $current_dir;
	var $path;
	var $clean_path;
	 
 function index0r () {

  ############################## CONFIGURATIONS #################################
  
   # html page on/off toggle writing of html header and footer
   $this->html = "on";
   
   # width of <table>
   $this->table_width = "100%";
   
   # on/off
   $this->debug = "off"; /* turn off/on display of working path. not recommened to leave
                     on during public use. */
   # full root path to directory (ie: /www/data/docs/user.com/public)                  
   $this->directory_root = ".";
   
   # http path to directory (ie: http://user.com/public) (no trailing slash)
   # Note: this must be a directory path you cannot use http://user.com/public/index0r.php
   $this->homepage = ".";
   
   # http path to images
   $this->img_src = "./index0r_img";
   
   # alternating row color;
   $this->alt_color = "off"; /* on/off */
   $this->alt_color_one = "#f6f6f6";
   $this->alt_color_two = "#c0c0c0";
   
   # show directories first on/off
   $this->order = "on";
   
   # blacklist file names and directories so they will not show
   $this->blacklist = Array(".",
                      "..",
                      "index.php",
                      "index0r_img",
                      "Thumbs.db",
                      ".htaccess",
                      ".htpasswd",
                      );
   
   # error message when directory has failed to open or directory is empty
   $this->failed_msg = "Error 666: Directory not found or directory is empty.";
   
   # cut name at XX length
   $this->max_length = 40;
   
   # format modified date is displayed uses date() function
    # http://php.net/manual/en/function.date.php
   $this->date_format = "F d Y h:i:s";
   
   # file type icon list key by file extension (IE: file.exe image is found by key exe)
   $this->file_types = Array(
	               'dir' => 'directory.png',
                 'jpg' => 'image.png',
                 'gif' => 'image.png',
                 'png' => 'image.png',
                 'exe' => 'binary.png',
                 'txt' => 'text.png',
                 'zip' => 'archive.png',
                 'rar' => 'archive.png',
                 'gz' => 'archive.png',
                 'tar' => 'archive.png',
                 'htm' => 'html.png',
                 'html' => 'html.png',
                 'php' => 'php.png',
                 'css' => 'text.png',
                 'iso' => 'iso.png',
                 'rpm' => 'rpm.png',
                 'pdf' => 'adobe_pdf.png',
                 'xls' => 'excel.png',
                 'wmv' => 'video.png',
                 'asf' => 'video.png',
                 'mpeg' => 'video.png',
                 'mov' => 'video.png',
                 'ini' => 'text.png',
                 'psd' => 'psd.png',
                 'plan' => 'plan.png'
                );
  ###############################################################################

 }
 function main () {
	$this->root_dir = $this->find_root_dir();

  # calculate path
  if(isset($_GET['dir'])) {
   $this->path = $this->directory_root . $_GET['dir'];
  } else {
   $this->path = $this->directory_root;
  }
	$this->clean_path = $this->validate_path($this->path);
  
  # current path excluding directory_root 
  if(isset($_GET['dir']) && (isset($_GET['dir'])) != "") {
   $this->current_dir = $this->root_dir.$_GET['dir'];
  } else {
   $this->current_dir = $this->root_dir;
  } 
  $contents = Array();

  
  # all the stuff in the directory is thrown into the array
  $contents = $this->directory_array();
   
   # print array
   $i = 1;
   
   if($this->html == "on") {
    print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"
        \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
    print "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n";
    print "<head>\n";
    print "<title>\n". $this->path . "</title>\n";
    print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=ISO-8859-1\" />\n";
    print "<style type=\"text/css\">\n";
    print ".alt_row1 { background: ". $this->alt_color_one ."; } \n";
    print ".alt_row2 { background: ". $this->alt_color_two ."; } \n";
    print "</style>\n";
    print "</head>\n";
    print "<body>\n";
   }    

   # debug mode - print full path to attempted directory
   if($this->debug == "on") {
    print "<p>Attempting to open: " . $this->clean_path ."<br /></p>\n\n";
   }
   print "<h2>Directory: " . $this->slice_and_dice() . "</h2>\n";
   if($contents != FALSE) {
    print "<h4><img src=\"" . $this->img_src . "/parent.png\" alt=\"^\" /> <a href=\"" . $this->homepage ."/?dir=" . $this->parent_directory() . "\" title=\"Parent Directory\">[Parent Directory]</a></h4>\n\n";
    print "<table cellpadding=\"3\" cellspacing=\"0\" border=\"0\" width=\"" . $this->table_width . "\">\n";
    print "<tr><td><a href=\"".$this->order_parse("name")."\" tile=\"Name\">Name</a></td><td><a href=\"".$this->order_parse("size")."\" tile=\"Size\">Size</a></td><td><a href=\"".$this->order_parse("lm")."\" tile=\"Last Modified\">Last Modified</a></td></tr>\n";
    foreach($contents as $key => $value) {
     print $this->print_file($value[name], $value[details], $i);
     $i++;
    }
    print "</table>\n";
   } else {
    print "<h4>" . $this->failed_msg . "</h4>\n";
   }
   if($this->html == "on") {
    print "</body>\n";
    print "</html>\n";
   }
 }
 
 function order_parse($name) {

 }
 
 function human_file_size($size)
 {
  if(!($size <= 0)){
    $filesizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
    return round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $filesizename[$i];
  }
 }
 
 function file_image($file) {
  $image = "/";
  $file = strtolower($file);
  
  if(is_dir($file)) {
   $image .= $this->file_types['dir'];
  } else {
   $position = strrpos($file, ".");
   $extension = substr($file, ($position+1));  
   
   if(array_key_exists($extension, $this->file_types))
   {
    $image .= $this->file_types[$extension];
   } else {
    $image .= "unknown.png";
   }
  }
  
  return $image;
 }
 
 function find_root_dir () {
  $position = strrpos($this->directory_root, "/");
  $this->root_dir = substr($this->directory_root, $position);
  
  return $this->root_dir; 
 }
 
 function print_file ($file, $details, $i) {
  $last_modified_date = date($this->date_format, filemtime($this->clean_path . "/" . $file));
  
  if($i % 2)
  $alt_class = "alt_row1";
  else
  $alt_class = "alt_row2";
  
  if($this->alt_color == "on")
  $row = "<tr class=\"" . $alt_class . "\">\n";
  else
  $row = "<tr>\n";
  
  if(is_dir($this->path . "/" . $file)) {
   $row .= "<td><img src=\"" . $this->img_src . $this->file_image(($this->path . "/" . $file), $this->file_types) . "\" alt=\"\" /> <a href=\"" . $this->homepage . "/?dir=" . $_GET['dir'] . "/" . $file . "\">" . $this->validate_name($file, $this->max_length) . "</a></td><td>" . $details . "</td><td>" . $last_modified_date . "</td>";
  } else {
   $row .= "<td><img src=\"" . $this->img_src . $this->file_image(($this->path . "/" . $file), $this->file_types) . "\" alt=\"\" /> <a href=\"" . $this->homepage . $_GET['dir'] . "/" . $file . "\">" . $this->validate_name($file, $this->max_length) . "</a></td><td>" . $details . "</td><td>" . $last_modified_date . "</td>";
  }
  $row .= "</tr>\n";
  
  return $row;
 }
 
 function parent_directory() {
 
  # remove root_dir from current_dir
  $root_length = strlen($this->root_dir);
  $parent = substr($this->current_dir, $root_length);
  
  # remove the last dir in path (ie: /dir/dir1 to /dir)
  $position = strrpos($parent, "/");
  $parent = substr($parent, 0, $position);
  return  $parent;
 }
 
 function slice_and_dice () {
  $str = $this->current_dir;
  # slice up the directory path and add links
  $cheese = "<a href=\"" . $this->homepage . "/?dir=\">" . $this->root_dir . "</a>";
  $directory_stack = "";
  
  # remove root dir
  $length = strlen($this->root_dir);
  $str = substr($str, $length);
  $str = explode("/", $str);
  
  foreach($str as $key => $value) {
   if($value != "") {
    $value = "/" . $value;
    $cheese .= "<a href=\"" . $this->homepage . "/?dir=" . $directory_stack.$value . "\">" . $value . "</a>";
    $directory_stack .= $value;
   }
  }
  
  return $cheese;
 }
 
 function validate_name ($str) {
  if(strlen($str) > $this->max_length) {
   $str = substr($str, 0, $this->max_length);
   $str .= "...";
  }
  
  return $str;
 }
 
 function validate_path($str) {
  # security function, remove any "/.." to prevent recursive browsing of
  # directories.
  $random_tack = uniqid(rand(), true);
  
  $pattern[] = '%[\.]{2,}%is';
  $replacement = $random_tack;
  
  $clean_path = preg_replace($pattern, $replacement, $str);
 
  return $clean_path;
 }
 
 function directory_array() {
  $file_array = Array(); // holds files
  $dir_array = Array();  // holds directories
  
  // open directory
  if($open_dir = @opendir($this->clean_path)) {
   while(FALSE != ($spiff = readdir($open_dir))) {
    
    if(!in_array($spiff, $this->blacklist)) {
     if(is_dir($this->path . "/" . $spiff)) {
      if($this->order == "on") {
       $dir_array[$spiff][name] = $spiff;
       $dir_array[$spiff][details] = "-";
      } else {
       $contents[$spiff][name] = $spiff;
       $contents[$spiff][details] = "-";
      }
     } else {
      if($this->order == "on") {
       $file_array[$spiff][name] = $spiff;
       $file_array[$spiff][details] = $this->human_file_size(filesize($this->clean_path . "/" . $spiff));
      } else {
       $contents[$spiff][name] = $spiff;
       $contents[$spiff][details] = $this->human_file_size(filesize($this->clean_path . "/" . $spiff));
      }
     }
    }
    
   } // end while
   
   // merge arrays
   if($this->order == "on") {
    $contents = array_merge($dir_array, $file_array);
   }
   
   return $contents;
  } else {
   return FALSE;
  }
 }
 
 // support function(s)
 function set($var, $val) {
  $this->$var=$val;
 }
 
 function lower_case_array($array) {
  foreach($array as $key => $value) {
   print $array[$key][name] = strtolower($value[name]) ."<br />";
  }
  
  return $array;
 }
}

     $directory_list = new index0r;
     $directory_list->main();
?>
