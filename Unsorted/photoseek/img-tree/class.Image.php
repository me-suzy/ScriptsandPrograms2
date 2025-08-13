<?php
 // file: class.Image.php
 // desc: class Image (extend for all image classes)
 // code: jeff b (jeff@univrel.pr.uconn.edu)
 // lic : GPL, v2

if (!defined(__CLASS_IMAGE_PHP__)) {

define(__CLASS_IMAGE_PHP__, true);

class Image {
 var $buffer_length = 8192;   // maximum buffer length (8k) 

 var $name;                   // file name
 var $buffer;                 // image description buffer
 var $timestamp;              // timestamp last modified

 var $type = "IMAGE";         // image type

 var $entire_file_read = false; // is the entire image read?
 var $entire_file;              // a place to keep the entire file

 // DESCRIPTION VARIABLES
 var $caption;
 var $caption_writer;
 var $headline;
 var $special_instructions;
 var $byline;
 var $byline_title;
 var $credit;
 var $source;
 var $object_name;
 var $date_created;
 var $city;
 var $state;
 var $country;
 var $original_transmission_reference;
 var $categories;
 var $keywords;
 var $copyright_notice;
 var $thumbnail;
 var $large_thumbnail;

 function Image ($file_name, $type = "IMAGE") {
   global $VB;                    // global verbose setting

   $this->type = $type;
   $this->new_Image ($file_name); // actual constructor call
 } // end constructor Image

 // abstracted init function to handle non-call of derived constructor
 function new_Image ($file_name) {
   global $VB,                       // master verbose variable
          $convert_exec,             // convert executable location
          $EXCEPTION_IMAGE_TYPES;    // exceptions for std IPTC pull

   // check to see if file is there...
   if (strlen($file_name)<5) die("Image->Constructor :: no file!");
   if (!is_file($file_name)) die("Image->Constructor :: file doesn't exist!");

   // pull file modification information
   $this->name       = $file_name;
   $file_information = stat ($file_name);
   $this->timestamp  = $file_information [10];
   clearstatcache();

   // open file
   $this_file = fopen($file_name, "r");

   // *** get buffer of information from the file ***
   //         (passed to all daughter classes)

   // clear local buffer varibles
   $this_read = ""; $buffer = "";
   if ($VB) echo "<BR><B>$file_name</B><BR>Reading buffer... ";
   for ($i=0;$i<$this->buffer_length;$i++) {
    $this_read = fgetc ($this_file);
    $buffer   .= $this_read;   // pull in current character
   }
   fclose ($this_file);        // close the file handle

   $this->buffer = $buffer;    // pass to holding spot in class

   // zero keywords and categories (/w counts)
   $this->keywords    = $this->categories    = "";
   $this->keywords[0] = $this->categories[0] = "";
   $num_keywords      = $num_categories      = 0 ;
   $this->caption     = "";

   // if that type is not set as an exception
   if (!isset($EXCEPTION_IMAGE_TYPES[$this->type])) {

    // generate thumbnail
    $this->thumbnail       = generate_thumbnail ($file_name, false);
    $this->large_thumbnail = generate_thumbnail ($file_name, true);

    // process IPTC fields
    $size = GetImageSize ($this->name, &$info);
    if (isset($info["APP13"])) {
      $iptc = iptcparse($info["APP13"]);
      if (is_array($iptc)) {
        while(list($key, $val) = each($iptc)) {
          while(list($k, $v) = each($val)) {
            if ($VB) echo "$key, $k => \"$v\" <br>\n";
            switch ($key) {
             case "2#120": // caption
              $type = "caption";
              $this->caption = $v;
              break;
             case "2#122": // caption writer
              $type = "caption writer";
              $this->caption_writer = $v;
              break;
             case "2#105": // headline
              $type = "headline";
              $this->headline = $v;
              break;
             case "2#040": // special instructions
              $type = "special instructions";
              $this->special_instructions = $v;
              break;
             case "2#080": // byline
              $type = "byline";
              $this->byline = $v;
              break;
             case "2#085": // byline title
              $type = "byline title";
              $this->byline_title = $v;
              break;
             case "2#110": // credit
              $type = "credit";
              $this->credit = $v;
              break;
             case "2#115": // source
              $type = "source";
              $this->source = $v;
              break;
             case "2#005": // object name
              $type = "object name";
              $this->object_name = $v;
              break;
             case "2#055": // date created
              $type = "date created";
              $this->date_created = $v;
              break;
             case "2#090": // city
              $type = "city";
              $this->city = $v;
              break;
             case "2#095": // state
              $type = "state";
              $this->state = $v;
              break;
             case "2#101": // country
              $type = "country";
              $this->country = $v;
              break;
             case "2#103": // original transmission reference
              $type = "original transmission reference";
              $this->original_transmission_reference = $v;
              break;
             case "2#015": // category
             case "2#020": // supplemental category
              $type = "category";
              $this->categories[$num_categories] = $v;
              $num_categories++;
              break;
             case "2#025": // keyword
              $type = "keyword";
              $this->keywords[$num_keywords] = $v;
              $num_keywords++;
              break;
             case "2#116": // copyright notice
              $type = "copyright notice";
              $this->copyright_notice = $v;
              break;
            } // end switch for key
            if ($VB) echo "\n<BR>i = $i, contents = <B>$v</B>,
               int type = <B>$key</B>, type = <I>$type</I>";
          } // end inner value loop 
        } // end outer value loop
      } // if there *is* information
    } // if the APP13 array exists

    // get caption if not included in ICTP fields
    if (empty ($this->caption)) {
      $temporary_name = tempnam ("/tmp", "cg-").".jpg";
      $this_convert   =
        exec ("$convert_exec \"$file_name\" \"$temporary_name\"");
      $this->caption  = exec ("rdjpgcom \"$temporary_name\"");
      if (file_exists($temporary_name)) unlink($temporary_name);
    } // end of checking for caption

   } // end checking for EXCEPTION_IMAGE_TYPES[type]

 } // end Image->new_Image

} // end class Image

} // end if not defined

?>
