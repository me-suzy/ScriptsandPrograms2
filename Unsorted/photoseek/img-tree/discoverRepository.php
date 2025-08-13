<?php
 // file: discoverRepository.php
 // desc: routine to "discover" images in a repository
 // code: jeff b (jeff@univrel.pr.uconn.edu)
 // lic : GPL, v2

include "config.inc"; // include the configuration

openDatabase();

photoseek_authenticate_admin();  // authenticate user

set_time_limit(0);               // don't let it time out

if ($repository < 1)
  DIE ("$page_name :: invalid repository\n");

$r = $sql->fetch_array (
     $sql->db_query (DB_NAME, "SELECT * FROM repositories WHERE
                                id='$repository'") );

$R = new psRepository ($r[rpath], $r[rname], $r[rdesc]);

function recurseDirectory ($directory_name) {
  global $R, $this_insert, $this_update, $sql;
  if (substr ($directory_name, -1) != "/")
    $directory_name .= "/";       // trailing slash
  while (strpos ($directory_name, "//"))
    $directory_name = eregi_replace ("//", "/", $directory_name);
  $D = dir ($directory_name);     // get current directory
  echo "<UL>
    <FONT SIZE=+1><B>Processing</B> $directory_name</FONT>
    "; // show status
  flush();

  while ($entry=$D->read()) {     // read entries
    if (VERBOSE) echo "<BR><B>entry = $directory_name$entry
                     </B> [recurseDirectory]";
    $relative_dir = eregi_replace ($R->rPath, "", $directory_name)."/";
    while (strpos ($relative_dir, "//"))
      $relative_dir = eregi_replace ("//", "/", $relative_dir);
    if ( (is_file($directory_name.$entry)) or
         (is_link($entry) and is_file(readlink($entry))) )  {
      // now determine if it is an image
      $full_path = $directory_name.$entry;
      $file_magic = explode (":", `file "$full_path"`);
      if (ereg ("image", $file_magic[1]) or
          // hack for bad magic file
          eregi(".EPS", $file_magic[0]) or
          eregi(".JPG", $file_magic[0]) or
          eregi(".TIF", $file_magic[0]) or
          eregi(".GIF", $file_magic[0]) ) {

       // check here to see if in repository or not
       // first, check to see if any records in the database have the same
       // full path, and then check the timestamp to see if it has to be
       // updated...

       $file_information = stat ($full_path);
       $this_timestamp  = $file_information [10];
       clearstatcache();

       $this_update = $this_insert = false;
       $query  = "SELECT fullfilename,lastmodified,id FROM images WHERE
                  fullfilename='".addslashes($full_path)."'";
       $result = $sql->db_query (DB_NAME, $query);
       if ((!$result) or ($sql->num_rows($result)<1)) $this_insert = true;
       if (($result) and (!$this_insert)) {
         $r  = $sql->fetch_array ($result);
         $id = $r[id];
         if ($r[lastmodified] != $this_timestamp) $this_update = true;
       } // end checking for update

       if ($this_update or $this_insert) {
        $img = new psImage ($R, $relative_dir.$entry);
        if (VERBOSE) echo "( [main?] img readdescrip = ".$img->iClass->caption.") ";
        // ---------- BEGIN PROCESS IMAGE -------------
        echo "<LI><B>Processing image</B> $entry \n";
        processImage ($img, $id);
        flush();
        // ----------  END  PROCESS IMAGE --->---------- 
       } else { // if there is no insert or update
        echo "<LI><B>Skipping image</B> $entry\n";
        flush();
       } // end checking for insert or update
      } // end process image
    } elseif ( ($entry != ".") and ($entry != "..") and
               ($entry[0] != ".") and        // all purpose dot-hiding fix
               ($entry != ".xvpics") and
               ($entry != ".AppleDouble") and 
              ((is_dir($directory_name.$entry)) or
               (is_link($directory_name.$entry) and
                is_dir(readlink($directory_name.entry)))) ) {
      recurseDirectory ($directory_name."/".$entry);
             // call itself and recurse that dir
    } // end checking for file or directory 
  } // end master while loop
  echo "</UL>\n"; // exit directory listing
  $D->close();
} // end function recurseDirectory

function processImage ($this_image, $id) {
  global $repository, $this_update, $this_insert, $sql;
  if (is_object ($this_image->iClass)) {
    if (is_array ($this_image->iClass->categories)) {
      $categories = join (",", $this_image->iClass->categories);
    } else {
      $categories = $this_image->iClass->categories;
    }
    if (is_array ($this_image->iClass->keywords)) {
      $keywords   = join (",", $this_image->iClass->keywords);
    } else {
      $keywords   = $this_image->iClass->keywords;
    }
  } // end if is_object $this_image->iClass

  if (!empty ($this_image->iClass)) {
   echo " <BR>description: <I>".$this_image->iClass->caption."</I>\n";
  } // end checking for class

  // form the insert query for addition
  if ($this_insert) {
   $query = "INSERT INTO images VALUES (
             '".addslashes($repository)."',
             '".addslashes($this_image->iClass->name)."',
             '".addslashes($this_image->iClass->type)."',
             '".addslashes($this_image->iClass->timestamp)."',
             '".addslashes($this_image->iClass->thumbnail)."',
             '".addslashes($this_image->iClass->large_thumbnail)."',
             '".addslashes($this_image->iClass->caption)."',
             '".addslashes($this_image->iClass->caption_writer)."',
             '".addslashes($this_image->iClass->headline)."',
             '".addslashes($this_image->iClass->special_instructions)."',
             '".addslashes($this_image->iClass->byline)."',
             '".addslashes($this_image->iClass->byline_title)."',
             '".addslashes($this_image->iClass->credit)."',
             '".addslashes($this_image->iClass->source)."',
             '".addslashes($this_image->iClass->object_name)."',
             '".addslashes($this_image->iClass->date_created)."',
             '".addslashes($this_image->iClass->city)."',
             '".addslashes($this_image->iClass->state)."',
             '".addslashes($this_image->iClass->country)."',
             '".addslashes(
                $this_image->iClass->original_transmission_reference)."',
             '".addslashes($categories)."',
             '".addslashes($keywords)."',
             '".addslashes($this_image->iClass->copyright_notice)."',
             NULL )";
   $result = $sql->db_query (DB_NAME, $query);
   if ($result) { echo "[stored]";   }
    else        { echo "[db error]"; }
  } // end checking for insert

  if ($this_update) {
   $query = "UPDATE images SET
             repository=    '".addslashes($repository)."',
             lastmodified=  '".addslashes($this_image->iClass->timestamp)."',
             thumbnail=     '".addslashes($this_image->iClass->thumbnail)."',
             largethumbnail='".addslashes($this_image->iClass->large_thumbnail)."',
             caption=       '".addslashes($this_image->iClass->caption)."',
             caption_writer='".addslashes($this_image->iClass->caption_writer)."',
             headline=      '".addslashes($this_image->iClass->headline)."',
             special_instructions='".addslashes(
                               $this_image->iClass->special_instructions)."',
             byline=        '".addslashes($this_image->iClass->byline)."',
             byline_title=  '".addslashes($this_image->iClass->byline_title)."',
             credit=        '".addslashes($this_image->iClass->credit)."',
             image_source=  '".addslashes($this_image->iClass->source)."',
             object_name=   '".addslashes($this_image->iClass->object_name)."',
             date_created=  '".addslashes($this_image->iClass->date_created)."',
             city=          '".addslashes($this_image->iClass->city)."',
             state=         '".addslashes($this_image->iClass->state)."',
             country=       '".addslashes($this_image->iClass->country)."',
             original_transmission_reference='".addslashes(
                $this_image->iClass->original_transmission_reference)."',
             categories=    '".addslashes($categories)."',
             keywords=      '".addslashes($keywords)."',
             copyright_notice='".addslashes($this_image->iClass->copyright_notice)."'
             WHERE id='$id'";
   $result = $sql->db_query (DB_NAME, $query);
   if ($result) { echo "[updated]";   }
    else        { echo "[db error \"$query\"]"; }
  } // end checking for update

  if (!$this_insert and !$this_update) echo "[skipped]";

} // end function processImage

//        ||                            ||
//        || ACTUAL RUNTIME OCCURS HERE ||
//        \/                            \/

$page_name = "Discover Repository";
include "header.php";
flush();
echo "<BR><B>Recursing through \"$R->rName\" ($R->rPath)</B><BR> \n";
recursedirectory ($R->rPath);
echo "<BR><B>Finished recursing through \"$R->rName\" ($R->rPath)</B><BR>\n";
echo "<BR><B>Searching for removed files ... </B><BR> \n";
$result = $sql->db_query (DB_NAME,
  "SELECT fullfilename,id FROM images WHERE repository='$repository'");
echo "\n<UL><B>Processing ...</B> \n";
while ($r = $sql->fetch_array ($result)) {
  if (!file_exists ($r[fullfilename]) and !empty($r[fullfilename])) {
    echo "<LI>\"$r[fullfilename]\" has been removed\n";
    $d_result = $sql->db_query (DB_NAME, "DELETE FROM images
                                           WHERE id='$r[id]'");
    echo ( $d_result ? "[successful]\n" : "[failed]\n");
    flush();
  } // end if file doesn't exist
} // end while loop for images
echo "
 </UL>

 <BR>
 Done.
 <P>

 <A HREF=\"index.php?action=admin\"
 >Return to the Administration menu</A>
";
include "footer.php";

closeDatabase ();
?>
