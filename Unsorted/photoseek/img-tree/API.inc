<?php
  // file: API.inc
  // desc: PhotoSeek API
  // code: jeff b (jeff@univrel.pr.uconn.edu)
  // lic : GPL, v2

if (!defined(__API_INC__)) {

define(__API_INC__, true);

// load defines and functions
include "config.inc";
include "class.Image.php";
include "class.Eps.php";
include "generate_thumbnail.php";

class psRepository {
  var $rPath,     // path to directory (i.e., /var/images)
      $rName,     // plain text name   (i.e., Austin Photos)
      $rDescrip;  // description       (i.e., put stuff here)

  function psRepository ($newPath, $newName, $newDescrip = "") {
    $this->rPath    = $newPath;    // assign path
    $this->rName    = $newName;    // assign given name
    $this->rDescrip = $newDescrip; // assign description
    if (!$this->isPathGood()) 
      DIE("psRepository->constructor :: ".
          "Path \"".$this->rPath."\" is not good.\n");
  } // end constructor psRepository

  function isPathGood ($nullval = "") {
    return ( (is_dir($this->rPath) or
             ((is_link($this->rPath)) and (is_dir(readlink($this->rPath))) )));
  } // end function psRepository->isPathGood
} // end class psRepository

class psImage {
  var $iRepository,    // link to the repository
      $iName,          // name of the image
      $iDescription,   // description of the image (pulled from image)
      $iMagic,         // magic info (GIF image file, 160x20, etc)
      $iType,          // JPEG, TIFF, etc
      $iClass,         // class is carried along...
      $iModified,      // date of last modification (timestamp)
      $iFullPath;      // full path to image

  function psImage ($newRepository, $newName) { //, $newRelPath) {
    $this->iRepository = $newRepository;
    $this->iName       = $newName;
    $this->iFullPath   = $this->iRepository->rPath . "/" .
                         $this->iName;
    while (strpos ($this->iFullPath, "//"))
      $this->iFullPath   = eregi_replace ("//", "/", $this->iFullPath);
    if (VERBOSE) echo "<BR> &nbsp; ( [psImage-constructor]
                 this fullpath = $this->iFullPath ) \n";
    if (!$this->isFileGood())
      DIE ("psImage->constructor :: ".
           "File $this->iFullPath does not exist!\n");
    $this_fullpath     = $this->iFullPath;
    $this->iMagic      = `file "$this_fullpath"`;
    $this->processDescription (); // process the image description 

    // temporarily extract stat modification data
    $stat_data         = stat ($this->iFullPath);
    $this->iModified   = $stat_data[10];
  } // end constructor psImage

  function isFileGood ($nullval = "") {
    return ( (is_file($this->iFullPath) or
             (is_link($this->iFullPath) and
              is_file(readlink($this->iFullPath))) ));
  } // end function psImage->isFileGood

  function processDescription ($nullval = "") {
    // split the magic info into parts and parse
    $this_path          = $this->iFullPath;
    $f_magic_full       = `file "$this_path"`;
    $f_magic_full_array = explode (":", $f_magic_full);
    $f_magic            = $f_magic_full_array[1];

    // just in case we need it, a temporary file name
    $tempfilename = tempnam ("/tmp", "ps-") . ".jpg";
    if (VERBOSE) echo "( [processDescription]
          thispath = $this_path, fmg_full=$f_magic_full,
          f_magic = $f_magic )\n";
    if (empty($tempfilename))
      DIE ("psImage->processDescription :: Error creating temp file\n");
    if (strpos ($f_magic, "EPS")) {
      // handle Encapulated Postscript with class.Eps.php
      // (before all else due to enclosed JPEGs)
      $this_eps      = new Eps ($this->iFullPath);
      $this->iType   = "EPS";
      $this->iClass  = $this_eps;
      return $this_eps->caption;
    } elseif (strpos ($f_magic, "JPEG") or
              // dealing with deficiencies in the magic file
              strpos ($f_magic_full_array[0], ".jpg" ) or
              strpos ($f_magic_full_array[0], ".jpeg") or
              strpos ($f_magic_full_array[0], ".JPG" ) or
              strpos ($f_magic_full_array[0], ".JPEG")  ) {
      // handle JPEGs with class.Jpeg.php
      $this_jpeg     = new Image ($this->iFullPath, "JPEG");
      $this->iType   = "JPEG";
      $this->iClass  = $this_jpeg;
      return $this_jpeg->caption;
    } elseif (strpos ($f_magic, "TIFF")) {
      // handle TIFFs with class.Tiff.php
      $this_tiff     = new Image ($this->iFullPath, "TIFF");
      $this->iType   = "TIFF";
      $this->iClass  = $this_tiff;
      return $this_tiff->caption;
    } elseif (strpos ($f_magic, "GIF")) {
      // handle GIFs with class.Gif.php
      $this_gif      = new Image ($this->iFullPath, "GIF");
      $this->iType   = "GIF";
      $this->iClass  = $this_gif;
    } elseif (strpos ($f_magic, "PNG")) {
      // handle PNGs with class.Png.php
      $this_png      = new Image ($this->iFullPath, "PNG");
      $this->iType   = "PNG";
      $this->iClass  = $this_png;
    } else { // don't know what to do!
      return $nullval;  // return the presented value
    } // end file type case loop  
  } // end function psImage->readDescription
} // end class psImage

// database functions .. for lookups, etc
function addImageToDatabase ($this_image) {
  global $sql;
  if (!is_object($sql))
    DIE ("addImageToDatabase :: could not connect to SQL server");
  echo " <B>addImageToDatabase :: NOT IMPLEMENTED!!</B>\n ";
} // end function addImageToDatabase

// database functions .. for lookups, etc
function update_image_in_database ($id) {
  global $sql;
  if (!is_object($sql))
    DIE ("update_image_in_database :: could not connect to SQL server");

  // pull current record
  $result = $sql->db_query (DB_NAME, "SELECT * FROM images
                                      WHERE id='".addslashes($id)."'");
  if (!$result or ($sql->num_rows($result) != 1))
    DIE ("update_image_in_database :: incorrect response from SQL server");
  $r_r = $sql->fetch_array ($result);

  // make new image
  $this_image = new Image ($r_r[fullfilename]);

  // put together the categories, etc
  if (is_array ($this_image->categories)) {
    $categories = join (",", $this_image->categories);
  } else {
    $categories = $this_image->categories;
  }
  if (is_array ($this_image->keywords)) {
    $keywords   = join (",", $this_image->keywords);
  } else {
    $keywords   = $this_image->keywords;
  }

  //$result = $sql->query ( $sql->
  $query = "UPDATE images SET
            repository=    '".addslashes($r_r[repository])."',
            lastmodified=  '".addslashes($this_image->timestamp)."',
            thumbnail=     '".addslashes($this_image->thumbnail)."',
            largethumbnail='".addslashes($this_image->large_thumbnail)."',
            caption=       '".addslashes($this_image->caption)."',
            caption_writer='".addslashes($this_image->caption_writer)."',
            headline=      '".addslashes($this_image->headline)."',
            special_instructions='".addslashes(
                              $this_image->special_instructions)."',
            byline=        '".addslashes($this_image->byline)."',
            byline_title=  '".addslashes($this_image->byline_title)."',
            credit=        '".addslashes($this_image->credit)."',
            image_source=  '".addslashes($this_image->source)."',
            object_name=   '".addslashes($this_image->object_name)."',
            date_created=  '".addslashes($this_image->date_created)."',
            city=          '".addslashes($this_image->city)."',
            state=         '".addslashes($this_image->state)."',
            country=       '".addslashes($this_image->country)."',
            original_transmission_reference='".addslashes(
               $this_image->original_transmission_reference)."',
            categories=    '".addslashes($categories)."',
            keywords=      '".addslashes($keywords)."',
            copyright_notice='".addslashes($this_image->copyright_notice)."'
            WHERE id='$id'";
  $result = $sql->db_query (DB_NAME, $query);
  if ($result) { echo "[updated]";   }
   else        { echo "[db error \"$query\"]"; }
} // end function update_image_in_database

// you can explicitly close connections here (php will do it at the end of
// the script)
function closeDatabase ()
{
  global $sql;
  if (!is_object($sql))
    DIE ("closeDatabase :: could not connect to SQL server");
  //$sql->close ();
} // end function closeDatabase

function existsInDatabase ($this_filename) {
  global $sql;
  if (!is_object($sql))
    DIE ("existsInDatabase :: could not connect to SQL server");
  $query = "SELECT fullfilename,id FROM images WHERE ".
           "fullfilename='".addslashes($this_filename)."'";
  $result = $sql->db_query (DB_NAME, $query);
  if ($sql->num_rows ($result)) { return true;  }
   else                         { return false; }
} // end function existsInDatabase

function openDatabase ()
{
  global $sql;
  $sql = new Sql (DB_ENGINE, DB_HOST, DB_USER, DB_PW, DB_NAME);
}

function db_rec_count ($table) {
  global $sql;
  static $db_rec_counts; // keep static counts

  if (empty($db_rec_counts["$table"])) {
    // if there is nothing, poll and return
    $query = "SELECT COUNT(*) FROM $table";
    if (!is_object($sql)) openDatabase ();
    $result = $sql->fetch_array (
      $sql->db_query (DB_NAME, $query) );
    // cache the value
    $db_rec_counts["$table"] = $result[0];
  }
  // return the cached value
  return $db_rec_counts["$table"];
} // end function db_rec_count

function hilite ($_haystack, $needle="") {
  $haystack = $_haystack;
  //if (count($haystack)>1) $haystack = $_haystack[0];
  return ( ((strlen($needle) <= strlen($haystack)) and
             (!empty($needle))) ?
             (eregi_replace (htmlentities($needle),
              "<FONT COLOR=\"#ff0000\">\\0</FONT>",
              $haystack))                         :
           ($haystack));
} // end function hilite

function photoseek_authenticate ($nullval="") {
  global $admin_users, $PHP_AUTH_USER, $PHP_AUTH_PW, $sql, $style;
  if (!valid_user()) {
    Header("WWW-Authenticate: Basic realm=\"".PACKAGE_NAME."\"");
    Header("HTTP/1.0 401 Unauthorized");
    if ($style) { include "header.php"; }
     else { echo "
      <HTML>
      <TITLE>".PACKAGE_NAME."</TITLE>
      <BODY BGCOLOR=\"".BGCOLOR."\" LINK=\"".LINKCOLOR."\" ".
      "VLINK=\"".VLINKCOLOR."\">
     "; }
    echo "
     <CENTER>
     <B>ACCESS DENIED!</B>
     <P>
     <A HREF=\"index.php\"
     >Return to ".PACKAGE_NAME."</A>
     <P>
     </CENTER>
    ";
    if ($style) { include "footer.php"; }
     else { echo "
      </BODY></HTML>
     "; }
    die("");
  }
} // end function photoseek_authenticate

function photoseek_authenticate_admin ($nullval="") {
  global $admin_users, $PHP_AUTH_USER, $PHP_AUTH_PW, $style;
  if ((!isset($PHP_AUTH_USER)) or
      ($admin_users[$PHP_AUTH_USER] != $PHP_AUTH_PW) or
      (empty($admin_users[$PHP_AUTH_USER]))) {
    Header("WWW-Authenticate: Basic realm=\"".PACKAGE_NAME."\"");
    Header("HTTP/1.0 401 Unauthorized");
    if ($style) { include "header.php"; }
     else { echo "
      <HTML>
      <TITLE>".PACKAGE_NAME."</TITLE>
      <BODY BGCOLOR=\"".BGCOLOR."\" LINK=\"".LINKCOLOR."\" ".
      "VLINK=\"".VLINKCOLOR."\">
     "; }
    echo "
     <B>ACCESS DENIED!</B>
    ";
    if ($style) { include "footer.php"; }
     else { echo "
      </BODY></HTML>
     "; }
    die("");
  }
} // end function photoseek_authenticate_admin

function valid_user ($null_var = "") {
  global $PHP_AUTH_USER, $PHP_AUTH_PW, $sql;
  if (!isset($PHP_AUTH_USER) or (empty($PHP_AUTH_USER)))
    return false;
  if (!is_object($sql)) openDatabase();  
  $result = $sql->db_query (DB_NAME,
           "SELECT * FROM users
            WHERE username='".addslashes($PHP_AUTH_USER)."'");
  if (($result) and ($sql->num_rows ($result) > 0)) {
    $r = $sql->fetch_array ($result);
    if ($r[userpass] == $PHP_AUTH_PW) return $r[userlevel];
  } // end checking for result
  // if all else fails, return false
  return false;
} // end function valid_user

// function build_iptc_block
function build_iptc_block ($iptc_data) {

  // define the IPTC_FIELD array
  $IPTC_FIELD = array (
    "object_name"                     => chr(2).chr(  5),
    "category"                        => chr(2).chr( 15),
    "supplemental_category"           => chr(2).chr( 20),
    "keyword"                         => chr(2).chr( 25),
    "special_instructions"            => chr(2).chr( 40),
    "date_created"                    => chr(2).chr( 55),
    "byline"                          => chr(2).chr( 80),
    "byline_title"                    => chr(2).chr( 85),
    "city"                            => chr(2).chr( 90),
    "state"                           => chr(2).chr( 95),
    "country"                         => chr(2).chr(101),
    "original_transmission_reference" => chr(2).chr(103),
    "headline"                        => chr(2).chr(105),
    "credit"                          => chr(2).chr(110),
    "source"                          => chr(2).chr(115),
    "copyright_notice"                => chr(2).chr(116),
    "caption"                         => chr(2).chr(120),
    "caption_writer"                  => chr(2).chr(122)
  );

  // clear the buffer
  $buffer = "";

  // check to make sure it's an array
  if (!count ($iptc_data))
    die ("build_iptc_block :: an associative array must be passed");

  // create the IPTC header
  $buffer = chr(0x1C).chr(0x02).chr(0x00).chr(0x00).chr(0x02).
            chr(0x00).chr(0x02);

  // reset the counter position for the array data
  reset ($iptc_data);

  // make sure no categories to begin with
  $supplemental_category = false;

  while (list ($key, $value) = each ($iptc_data)) {
    // check to make sure it isn't a null value
    if (!empty ($value)) {
     if (VERBOSE) echo "key = $key, value = $value <BR>\n";
     if (($key=="keyword") or ($key=="category")) {
      // split into different components
      $value_array = split (",", $value);

      // loop through array of values
      for ($i=0;$i<count($value_array);$i++) {
        // debug information
        if (VERBOSE) echo "loop iteration # $i<BR>\n";
	
        // add 0x1C to the previous buffer
	$buffer .= chr(0x1C);
	// special case for supplemental categories
	if (($i>0) and ($key=="category")) {
	  $iptc_id = $IPTC_FIELD[supplemental_category];
        } else {
	  $iptc_id = $IPTC_FIELD[$key];
	} // end of assigning key

        // add the id
        $buffer .= $iptc_id;

        // calculate the two bytes of length
        $length  = strlen ($value_array[$i]);
        $byte[1] = ( ($length > 0) ? (floor($length / 255)) : 0);
        $byte[2] = ( ($length > 0) ? (floor($length % 255)) : 0);

        // add the length bytes
        $buffer .= chr($byte[1]).chr($byte[2]);

        // now add the actual value
        $buffer .= $value_array[$i];
      } // end of looping through array of values
     } else { // categories and keywords
      // add 0x1C to the previous buffer (end character)
      $buffer .= chr(0x1C);
      if (VERBOSE) echo "<BR>key = $key, value = $value <BR>\n";

      $iptc_id = $IPTC_FIELD[$key];

      // check for proper id length, if not, error
      if (!$iptc_id or (strlen($iptc_id)!=2) or (ord($iptc_id[1])==0))
        die ("build_iptc_block :: invalid key \"$key\" provided");

      // add the IPTC id to the buffer
      $buffer .= $IPTC_FIELD[$key];

      // calculate the two bytes of length
      $length  = strlen ($value);
      $byte[1] = ( ($length > 0) ? (floor($length / 255)) : 0);
      $byte[2] = ( ($length > 0) ? (floor($length % 255)) : 0);

      // add the length bytes
      $buffer .= chr($byte[1]).chr($byte[2]);

      // some debug code
      if (VERBOSE) echo "length = $length, byte[1] = $byte[1],
                                  byte[2] = $byte[2], type = $key,
				  value = \"$value\"\n<BR>";

      // now add the actual value
      $buffer .= $value;
     } // end checking for categories or keywords 
    } // end of checking for not empty value
  } // end of while

  // return the buffer
  return $buffer;

} // end function build_iptc_block

function nonjpeg_iptcembed ($iptc_block, $file_name) {
  global $VB;

  // if there is no block or no file, kick out
  if (empty ($iptc_block) or empty ($file_name)) return false;

  if (VERBOSE) echo "IPTC block length = ".strlen($iptc_block)." <BR>\n";

  // check to see if the original file exists
  if (!file_exists ($file_name)) return false;

  // build temporary file name
  $temp_file = tempnam ("/tmp", "psiptc") . ".iptc";

  // remove if it exists already
  #if (file_exists ($temp_file)) unlink ($temp_file);

  // open the new file
  $fp = fopen ($temp_file, "w");

  // write to the file with the IPTC profile
  if (!fputs($fp, $iptc_block)) return false;

  // close the file
  fclose ($fp);

  // embed using convert
  $returned = exec ("$convert_exec \"$file_name\" \"$file_name\" ".
    "-profile iptc:$temp_file");
  if (VERBOSE) echo "<BR>returned: $returned<BR>\n";

  // exit okay
  #if (file_exists ($temp_file)) unlink ($temp_file);
  return true;
  
} // end function nonjpeg_iptcembed

function image_at_resolution ($height, $width, $resolution) {
  return image_dots_to_inches ($height, $resolution). " x ".
         image_dots_to_inches ($width,  $resolution).
	 " inches at $resolution dpi";
} // end function image_at_resolution

function image_dots_to_inches ($dots, $resolution) {
  return number_format ($dots / $resolution, 1);
} // end function image_dots_to_inches

function commadelim_list ($type) {
  global $sql;
  static $__commadelim_$type;

  if ($type=="") DIE ("commadelim_list :: must specify type");

  // if the database is not open, fix the problem
  if (!$sql) openDatabase();

  if (!is_array(${"__commadelim_".$type})) {
    ${"__commadelim_".$type} = array();
    // if there is nothing yet, form the list
    $form_query = "SELECT DISTINCT ".addslashes($type)." FROM images ".
      "WHERE LENGTH(".addslashes($type).")>1";
    $query = $sql->query($form_query);
    while ($this_one = $sql->fetch_array($query)) {
      $this_val = stripslashes($this_one["$type"]);
      if (strpos($this_val, ",")) { // check for smooshed array
        ${"__commadelim_".$type}[] = explode (",", $this_val);
      } else {
        ${"__commadelim_".$type}[] = $this_val;
      } // end checking for smooshed array
    } // end of while there are results

    if (!is_array(${"__commadelim_".$type})) ${"__commadelim_".$type}[] = "";

    // now, we flatten and pull out duplicates
    ${"__commadelim_".$type} = flatten_array (${"__commadelim_".$type});
    asort (${"__commadelim_".$type});
    ${"__commadelim_".$type} = unique_array (${"__commadelim_".$type});
  } // end if not array categories

  // return either cached or built categories list
  return ${"__commadelim_".$type};
} // end function commadelim_list


define (CRITERIA_CONTAINS, 1);
define (CRITERIA_IS,       2);
function sql_search_for ($field, $criteria, $arg) {

  switch ($criteria) {
    case CRITERIA_IS:
      switch ($field) {
        case "keywords": case "categories":
	  $buffer = " (
	    (".addslashes($field)." LIKE '".addslashes($arg)."'    ) OR
	    (".addslashes($field)." LIKE '".addslashes($arg).",%'  ) OR
	    (".addslashes($field)." LIKE '%,".addslashes($arg)."'  ) OR
	    (".addslashes($field)." LIKE '%,".addslashes($arg).",%') OR
	  ";
	  break; // end case keywords/categories

	default:
	  $buffer = "
	    (".addslashes($field)." LIKE '".addslashes($arg)."')
	  ";
	  break; // default

      } // end switch field
      break; // end CRITERIA_IS

      case CRITERIA_CONTAINS: default:
        $buffer = "
	  (".addslashes($field)." LIKE '%".addslashes($arg)."%')
	";
        break; // end CRITERIA_CONTAINS

  } // end switch criteria

  return $buffer;
} // end function sql_search_for

// passed variables
$passed_vars = array (
	"search",
	"criteria",
	"last_action",
	"field",
	"start",
	"style",
	"repository",
	"type",
	"criteria_categories",
	"search_categories",
	"criteria_keywords",
	"search_keywords",
	"action",
	"id",

	// advanced search
	"use_1",
	"field_1",
	"criteria_1",
	"search_1",
	"use_2",
	"field_2",
	"criteria_2",
	"search_2",
	"use_3",
	"field_3",
	"criteria_3",
	"search_3"
);

function pass_url ($override = "") {
  global $passed_vars;
  $buffer = "";
  reset ($passed_vars);
  while (list ($key, $val) = each ($passed_vars)) {
    if (empty($override["$val"])) 	{ $$val = $GLOBALS["$val"]; }
     else 				{ $$val = $override["$val"]; }
    if (!empty($$val))
      $buffer .= "&".urlencode($val)."=".urlencode($$val);
  } // end while loop
  return $buffer;
} // end function pass_url

function pass_form ($override = "") {
  global $passed_vars;
  $buffer = "";
  reset ($passed_vars);
  while (list ($key, $val) = each ($passed_vars)) {
    if (empty($override["$val"])) 	{ $$val = $GLOBALS["$val"]; }
     else 				{ $$val = $override["$val"]; }
    if (!empty($$val))
      $buffer .= "<INPUT TYPE=HIDDEN NAME=\"".prepare($val)."\" ".
        "VALUE=\"".prepare($$val)."\">\n";;
  } // end while loop
  return $buffer;
} // end function pass_form

///////////////////////////////////////////////////////////////////////////
//                        LANGUAGE STUFF HERE                            //
///////////////////////////////////////////////////////////////////////////

$lang_months = array (
	"",          // null for 0, to make index start at 1
	"Jan",
	"Feb",
	"Mar",
	"Apr",
	"May",
	"Jun",
	"Jul",
	"Aug",
	"Sep",
	"Oct",
	"Nov",
	"Dec"
);

} // end if not defined

?>
