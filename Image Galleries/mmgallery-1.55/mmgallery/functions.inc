<?

/*
 * mmgallery v1.55                                                 
 * ===============                                                          
 *                                                                      
 * Copyright (c) 2004 by madmaz <madmaz@netfriends.it>                  
 *                                                                      
 * This program is free stware; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 */

// OPTIONS

// If you want to skip some directory rename them with the prefix specified in 
// $filterprefix (for example use "__");
$filterprefix = "__";

// Set to true if you want to show filename below thumbs.
$showfilename = false;

// Set to true if you want to hide extension in filename.
$hidefilenameext = false;

/*********************************************************/
/* Counts the number of file in the specified directory. */
/*********************************************************/

function count_files($basedir)
{
  $handle = opendir($basedir);
  $i = 0;
  
  while ($file = readdir($handle))
  {
    if ((strstr(strtolower($file), '.bmp') || 
         strstr(strtolower($file), '.gif') || 
         strstr(strtolower($file), '.jpg') || 
         strstr(strtolower($file), '.png')) && 
         $file != "thumb.jpg")
    {
      $i++;
    }
  }

  closedir($handle);

  return $i;
}


/*********************************************************/
/* Gets the name of the file number '$index' in the      */
/* '$basedir' directory.                                 */
/*********************************************************/

function search_file($basedir, $index)
{
  $handle = opendir($basedir);

  while ($file = readdir($handle)) 
  {
    if ((strstr(strtolower($file), '.bmp') || 
         strstr(strtolower($file), '.gif') || 
         strstr(strtolower($file), '.jpg') || 
         strstr(strtolower($file), '.png')) &&
         $file != "thumb.jpg")
    {
      $filelist[] = "$file";
    }
  }

  sort($filelist);
  closedir($handle);
  
  return $filelist[$index - 1];
}


/*********************************************************/
/* Gets the names of the files in the '$basedir'         */
/* directory.                                            */
/*********************************************************/

function list_files($basedir)
{
  $handle = opendir($basedir);

  while ($file = readdir($handle)) 
  {
    if ((strstr(strtolower($file), '.bmp') || 
         strstr(strtolower($file), '.gif') || 
         strstr(strtolower($file), '.jpg') || 
         strstr(strtolower($file), '.png')))
    {
      $filelist[] = "$file";
    }
  }

  sort($filelist);
  closedir($handle);
  
  return $filelist;
}


/*********************************************************/
/* Gets the directory list in the '$basedir'             */
/* directory.                                            */
/*********************************************************/

function search_dir($basedir)
{  
  global $filterprefix;
  $handle = opendir($basedir);
  
  while ($file = readdir($handle)) 
  {
    if (!($file == "." || $file == ".." ) && is_dir($file) && 
       (strstr($file, $filterprefix) != $file))
    {
      $filelisting[] = "$file";
    }
  }

  closedir($handle);

  return $filelisting;
}


/**************************************************************/
/* Shows one thumb of each gallery in a table with            */
/* with '$cols' columns.                                      */
/* '$th_width' and '$th_height' are the dimensions            */
/* of the thumbnails.                                         */      
/* The thumbnails are picked from each directory and must     */
/* be named 'thumb.jpg' 												  */
/*	'$cellpadding', '$cellspacing' refer to the table          */  
/* properties.       													  */
/**************************************************************/

function show_galleries($cols, $th_width, $th_height, 
                        $cellpadding, $cellspacing)
{
  echo "<table border='0' cellpadding='$cellpadding' 
        cellspacing='$cellspacing' 
        align='center'>\n";
  echo "<tr>\n";
         
  $dirlist = search_dir("./"); 
  sort($dirlist);
  $n = 1;

  for ($i = 0;  $i < sizeof($dirlist); $i++) 
  {
    $dir = $dirlist[$i];
    $tot = count_files($dir);
    $gallery_name = str_replace("_", " ", $dirlist[$i]);

    echo "<td align='center'>\n";
    echo "<table border='0' cellspacing='1' cellpadding='0' 
          bgcolor='#000000'>\n";
    echo "<tr><td>\n";
    echo "<a href='thumbs.php?dir=$dir&page=1'>\n";
    echo "<img src='$dir/thumb.jpg' width=$th_width 
          height=$th_height border=0><br>\n";
    echo "</a></td></tr>\n";
    echo "</table>\n";
    echo "<a href='thumbs.php?dir=$dir&page=1'>";
    echo "<b>$gallery_name</b>&nbsp;<small>($tot)</small>\n";
    echo "</a>";
    echo "</td>\n";

    if (($n % $cols) == 0 && ($n != sizeof($dirlist))) 
    {
      echo "</tr>\n";
      echo "<tr align=center>";
    }

    $n++;
  }
    
  echo "</tr>\n";
  echo "</table>\n";
  
  // Powered by mmgallery
  echo "<br>";
  echo "<div align=\"center\"><small>Powered by <a href=\"http://www.mmgallery.net\"><u>mmgallery</u></a></small></div>";
}


/**************************************************************/
/* Shows all thumbs of a gallery in a table with              */    
/* '$cols' columns.                                           */
/* '$th_width' and '$th_height' are the dimensions            */
/* of the thumbnails.                                         */      
/* The thumbnails are picked from each directory and must     */
/* be named 'thumb.jpg'                                       */  
/*	'$cellpadding', '$cellspacing' refer to the table          */  
/* properties.       													  */
/**************************************************************/


function show_thumbs($cols, $th_width, $th_height, $cellpadding, 
                     $cellspacing, $perpage)
{
  $dir = $_GET["dir"];
  $tot = count_files($dir);
  if (isset($_GET["page"])) {
    $page = $_GET["page"];
  }  
  if (isset($_GET["img"])) {
    $img = $_GET["img"];
  }
  global $showfilename;
  global $hidefilenameext;
  
  if ($page == NULL) {
    $page = ceil($img  / $perpage);
  }
  
  echo "<div style='text-align: center'>";
  echo "<table border='0' cellpadding='$cellpadding' 
        cellspacing='$cellspacing' align='center'>\n";
  echo "<tr>\n";
         
  $filelist = list_files("./$dir/thumbs");
  $n = 1;
  
  $end = min(($page * $perpage), sizeof($filelist));

  for ($i = (($page - 1) * $perpage);  $i < $end; $i++) 
  {
    echo "<td align='center'>\n";    
    echo "<table border='0' cellspacing='1' cellpadding='0' 
          bgcolor='#000000'>\n";
    echo "<tr><td>\n";
    echo "<a href='show.php?dir=$dir&tot=$tot&img=".($i + 1).
         "&page=$page'>\n";
    echo "<img src='$dir/thumbs/$filelist[$i]' width='$th_width' 
               height='$th_height' border='0'><br>\n";
    echo "</a>\n";
    echo "</td></tr>\n";
    echo "</table>\n";
    
    if ($showfilename) 
    {
      if ($hidefilenameext) {
        echo "<small>".substr($filelist[$i], 0, strrpos($filelist[$i], '.'))."</small>";
      }
      else
      {
        echo "<small>$filelist[$i]</small>";
      }     
    }
    
    echo "</td>\n"; 

    if (($n % $cols) == 0 && ($n != $perpage)) 
    {
      echo "</tr>";
      echo "<tr align='center'>";
    }
    
    $n++;
  }
  echo "</tr>\n";    
  echo "</table>";
 
  // We need to show thumbs in more than one page
  if (sizeof($filelist) > $perpage) {
    echo ("Page:&nbsp;");
    
    for ($j = 1; $j <= ceil(sizeof($filelist) / $perpage); $j++) {
     echo ("<a href='thumbs.php?dir=$dir&page=".$j."'>");
     
     // Current page   
     if ($page == $j) {
       echo "<b>".$j."</b>";
     }
     else 
     {
       echo $j;
     } 
      
     echo "</a>";
     echo ("&nbsp;&nbsp;");
    }
  
    echo "<br><br>";  
  }

  echo "<a href='show.php?dir=$dir&tot=$tot&img=".
        ((($page - 1) * $perpage) + 1)."'>start</a>&nbsp;::&nbsp;"; 
  echo "<a href='index.php'>main</a>&nbsp;"; 
  
  // Powered by mmgallery
  echo "<br><br><br>";
  echo "<small>Powered by <a href=\"http://www.mmgallery.net\"><u>mmgallery</u></a></small>";
  
  echo "</div>";
 }


/***********************************************************/
/* Shows the picture and the links related to the position */
/* in the slideshow.                                       */
/***********************************************************/

function show_picture()
{
  $dir = $_GET["dir"];
  $tot = $_GET["tot"];
  $img = $_GET["img"];

  echo "<div style='text-align: center'>";
  echo "<big>\n";
  
  if ($img  > 1)
  {
    echo "<a href='show.php?dir=$dir&tot=$tot&img=".($img - 1)."'>&lt;&lt;</a>"; 
  }

  echo "&nbsp;&nbsp;&nbsp\n";
  echo "</big>\n";
  echo "<b>".$img."</b> of ".$tot; 
  echo "&nbsp;&nbsp;&nbsp\n";
  echo "<big>\n";

  if ($img < $tot)
  {
    echo "<a href='show.php?dir=$dir&tot=$tot&img=".($img + 1)."'>&gt;&gt;</a>"; 
  }

  echo "</big>\n";
  echo "<br><br>\n"; 
  
  echo "<table border='0' cellspacing='1' cellpadding='0' 
               bgcolor='#000000' align='center'>\n";
  echo "<tr><td>\n";
  echo "<a href='thumbs.php?dir=$dir&img=$img'>"; 
  echo "<img src='$dir/".search_file("./$dir", $img)."' border=0 
        onLoad='resize(this);' name=foto 
        alt='Click to go to thumbnails page.'>";
  echo "</a>";        
  echo "</td></tr>\n";
  echo "</table>\n";

  
  echo "<br><br>\n"; 
  echo "<big>\n";
  
  if ($img > 1)
  {
    echo "<a href='show.php?dir=$dir&tot=$tot&img=".($img - 1)."'>&lt;&lt;</a>"; 
  }
  
  echo "&nbsp;&nbsp;&nbsp\n";
  
  if ($img  < $tot)
  {
    echo "<a href='show.php?dir=$dir&tot=$tot&img=".($img + 1)."'>&gt;&gt;</a>"; 
  }

  echo "</big>\n";
  echo "<br><br>\n"; 

  echo "<a href='show.php?dir=$dir&tot=$tot&img=1'>first</a>&nbsp;::&nbsp;"; 
  echo "<a href='index.php'>main</a>&nbsp;::&nbsp;"; 
  echo "<a href='thumbs.php?dir=$dir&img=$img'>thumbs</a>&nbsp;::&nbsp;"; 
  echo "<a href='show.php?dir=$dir&tot=$tot&img=$tot'>last</a>"; 
    
  echo "<br><br>\n"; 
  
  echo "<form name='jump'>\n";
  echo "Go to picture <select name=go onChange=\"document.location = 
        'show.php?dir=$dir&tot=$tot&img=' + document.jump.go.value\">";
  echo "<option value='' selected>";

  for ($i = 1; $i <= $tot; $i++)
  {
    echo "<option value='$i'>$i";
  }

  echo "</select>\n";
  echo "</form>\n";

  // Powered by mmgallery
  echo "<br>";
  echo "<small>Powered by <a href=\"http://www.mmgallery.net\"><u>mmgallery</u></a></small>";
  
  echo "</div>"; 
}

?>
