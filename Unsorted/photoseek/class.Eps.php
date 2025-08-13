<?php
 // file: class.Eps.php
 // desc: class Eps
 // code: jeff b (jeff@univrel.pr.uconn.edu)
 //       tom d (trout@uwm.edu)
 // lic : GPL, v2

if (!defined(__CLASS_EPS_PHP__)) {

define(__CLASS_EPS_PHP__, true);

if (!defined(CLASS_IMAGE)) include ("class.Image.php");
if (!defined(THUMB_GEN))   include ("generate_thumbnail.php");

// set this to be an exception
$EXCEPTION_IMAGE_TYPES ["EPS"] = true;

class Eps extends Image {
 var $buffer_length = 8192;   // maximum buffer length (8k)

 var $name;                   // file name
 var $buffer;                 // image description buffer
 var $timestamp;              // timestamp last modified

 var $type = "EPS";         // image type

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

 function Eps ($file_name) {
   global $convert_exec;       // global verbose setting

   $this->new_Image ($file_name);   // call superclass "constructor"

   // check to see if file is there...
   if (strlen($file_name)<5) die("Eps->Constructor :: no file!");
   if (!is_file($file_name)) die("Eps->Constructor :: file doesn't exist!");

   $this->name       = $file_name;
   $file_information = stat ($file_name);
   $this->timestamp  = $file_information [10];
   clearstatcache();

   // open file
   $this_file = fopen($file_name, "r");

   // get buffer of information from the file
   $this_read = ""; $buffer = ""; // clear local buffer varibles
   if (defined(VERBOSE)) echo "<BR><B>$file_name</B><BR>Reading buffer... ";
   for ($i=0;$i<$this->buffer_length;$i++) {
    $this_read = fgetc ($this_file);
    //if (defined(VERBOSE)) echo "<BR>$i = ".htmlentities($this_read);
    $buffer .= $this_read;     // pull in current character
   }
   fclose ($this_file);        // close the file handle

   // pull out the buffer of information
   $temp_split_buffer = explode ("8BIM", $buffer);
   $this->buffer      = $temp_split_buffer[1];
  
   // debug junk 
   if (defined(VERBOSE)) echo "<BR><B>done.</B><BR><PRE>\n";
   $temp_split = explode (chr(28).chr(2), $this->buffer);

   // zero keywords and categories (/w counts)
   $this->keywords = $this->categories = "";
   $this->keywords[0] = $this->categories[0] = "";
   $num_keywords = $num_categories = 0;

   // go through all of the fields
   for ($i=0;$i<count($temp_split);$i++) {
     $type = "";                 // reset current type
     $mine = $temp_split[$i];    // access current part of array
     // convert first 3 chars of current segment to numbers
     $p0   = ord($mine[0]);
     if(strlen($mine)>=2) $p1   = ord($mine[1]);
     if(strlen($mine)>=3) $p2   = ord($mine[2]);
     // now run through all cases and decide what they are...
     if (($p0==2) and ($p1==2)) {
       $type = "NULL";
     } elseif (($p0==4) and ($p1==4)) {
       $type = "NULL";
     } elseif (($p0==120)) {
       $type = "caption";
       if ($p1==1) {
         $this->caption = trim (substr ($mine, 3, strlen($mine)-3));
       } else {
         $this->caption = trim (substr ($mine, 2, strlen($mine)-2));
       } // checking for different offset
     } elseif (($p0==122)) {
       $type = "caption writer";
       $this->caption_writer = substr ($mine, 2, strlen($mine)-2);
     } elseif (($p0==105)) {
       $type = "headline";
       $this->headline = substr ($mine, 2, strlen ($mine)-2);
     } elseif (($p0==40)) {
       $type = "special instructions";
       $this->special_instructions = substr ($mine, 2, strlen($mine)-2);
     } elseif (($p0==80)) {
       $type = "byline";
       $this->byline = substr ($mine, 2, strlen($mine)-2);
     } elseif (($p0==85)) {
       $type = "byline title";
       $this->byline_title = substr ($mine, 2, strlen($mine)-2);
     } elseif (($p0==110)) {
       $type = "credit";
       $this->credit = substr ($mine, 2, strlen($mine)-2);
     } elseif (($p0==115)) {
       $type = "source";
       $this->source = substr ($mine, 2, strlen($mine)-2);
     } elseif (($p0==5)) {
       $type = "object name";
       $this->object_name = substr ($mine, 2, strlen($mine)-2);
     } elseif (($p0==55)) {
       $type = "date created";
       $this->date_created = substr ($mine, 2, strlen($mine)-2);
     } elseif (($p0==90)) {
       $type = "city";
       $this->city = substr ($mine, 2, strlen($mine)-2);
     } elseif (($p0==95)) {
       $type = "providence/state";
       $this->state = substr ($mine, 2, strlen($mine)-2);
     } elseif (($p0==101)) {
       $type = "country name";
       $this->country = substr ($mine, 2, strlen($mine)-2);
     } elseif (($p0==103)) {
       $type = "original transmission ref";
       $this->original_transmission_reference = substr ($mine, 2, strlen($mine)-2);
     } elseif (($p0==15)) {
       $type = "category";
       $this->categories[$num_categories] = substr ($mine, 2, strlen($mine)-2);
       $num_categories++;
     } elseif (($p0==20)) {
       $type = "supplemental category";
       $this->categories[$num_categories] = substr ($mine, 2, strlen($mine)-2);
       $num_categories++;
     } elseif (($p0==25)) {
       $type = "keyword";
       $this->keywords[$num_keywords] = substr ($mine, 2, strlen($mine)-2);
       $num_keywords++;
     } elseif (($p0==116)) {
       $type = "copyright notice";
       $this->copyright_notice = substr ($mine, 2, strlen($mine)-2);
     } // end of huge figuring out thing
     if (defined(VERBOSE)) echo "\n<BR>i = $i, contents = <B>$mine</B>,
              m0 = $p0, m1 = $p1, m2 = $p2, type = <I>$type</I>";
   }

   // generate thumbnail
   $this->thumbnail       = generate_eps_thumbnail ($file_name, false);
   $this->large_thumbnail = generate_eps_thumbnail ($file_name, true);

   if (defined(VERBOSE)) echo "

     caption = $this->caption
     caption_writer = $this->caption_writer
     keywords = ".join(",", $this->keywords)."
     categories = ".join (",", $this->categories)."
     state = $this->state
   ";
   if (defined(VERBOSE)) echo "</PRE>";
 } // end constructor Eps
} // end class Eps

} // end if not defined

?>
