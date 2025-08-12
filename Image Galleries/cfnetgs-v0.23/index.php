<?php

// fix the album icons to be exactly the same so all the text lines
// up nicely. mabe pass a new variable to Resize to adjust height as
// well.

// include the conf file and functions
include 'functions_inc.php';
include 'gallery_conf.php';

// variables we need 
$total = $rows * $cols;
$table_width = $thmb_width + $thmb_width + $display_width;
$gallery_url = dirname (pathinfo ($_SERVER['PHP_SELF']));
$gallery_dir = getcwd(). '/';
$image = rawurldecode ($_GET['image']);
$id = rawurldecode ($_GET['id']);
$currentpage = rawurldecode ($_GET['page']);
$directory = rawurldecode ($_GET['directory']);
$version = "v0.23";

// set default main gallery dir if no album path s passed through url
if (empty($directory)) {
        $directory = "/albums";
}

// directory we are working on, trailing slash removed
$directory_target = $gallery_dir. substr($directory, 1);

// Define the cache dir for the current album
$cache_dir = str_replace("albums", "cache", $directory_target).'/';
$cache_url = str_replace("albums", "cache", $gallery_url.$directory).'/';

// lets check to see what version of gd is installed
if ($library == 'GD') {
	$gd2 = checkgd();
}

// scan thru directory, put files and dirs into arrays, this is not recursive
// each time a link is clicked it repeats this...building the gallery along the way
chdir($directory_target);
$handle = opendir($directory_target);
$current_dir = getcwd();
$i = 0;
$j = 0;
while (false !== ($file = readdir($handle))) {
	if (is_file($file) == '1' && eregi(".(jpg|png|jpeg)$", $file)) {
		$list[$i] = $file;
		$i++;
		$num = count($list);
		sort($list);
		$numimages = 1;
	}
	elseif (is_dir($file) == '1' && $file <> '.' && $file <> '..' && $file <> 'cache') {
		$list[$j] = $file;
		$j++;
		$num = count($list);
		sort($list);
		$numimages = 0;
	}
}
closedir($handle);

// html stuff
echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"'. "\n";
echo '"http://www.w3.org/TR/html4/loose.dtd">'. "\n";
echo '<html>'. "\n";
echo '<head>'. "\n";
echo '<title>'. $title. ' </title>'. "\n";
echo '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">'. "\n";
echo '<link rel="stylesheet" type="text/css" href="'. $style. '">';
echo '</head>'. "\n";
echo '<body>'. "\n";

// include the header if one was given
if (is_file($header)) {
	include ($header);
}

echo '<table width="100%" border="0">'. "\n";
echo ' <tr>'. "\n";
echo '  <td align="center">'. "\n";

// lets make a dynamic navbar!
echo NavBar ($current_dir, $gallery_url, $gallery_dir);

// whate page are we on?
$currentpage = PageCheck ($currentpage);

// if $image is empty, that means were doing a dir list or making thumbnails
if (empty($image)) {
	
	//split the array into $total and calc number of pages
	$chunk = array_chunk($list, $total, true);
	$pages = count ($chunk);
	if ($currentpage > $pages) {
		echo 'No such Page';
	}
	
	// which images are on current page? '-1' to adjust for array index of '0'		
	$current = $chunk[$currentpage - 1];
	
	// what's the first key in this array?
	$id = ($total * $currentpage) - $total;
				
	// how many rows do we really need if there are less than row*col images
	$newrows = intval($num / $cols);
	if ($newrows < $rows) {
		$rows = $newrows + 1;
	}

	// start making the table to disply thumbnails
	echo '<table border="0" cellspacing="0" cellpadding="0" valign="bottom">'. "\n";

	// lets loop to make table based on $cols and $rows
	// in each <td> we will show a thumbnail from the 
	// $current array
	for ($jj = 1; $jj <= $rows; $jj++) {
		echo ' <tr>'. "\n";
		for ($ii = 1; $ii <= $cols; $ii++) {
			echo '  <td align="center" valign="top">';
				
			// if the array element is empty display nothing, this avoids broken image link
			if (empty($current[$id])) {
				echo '&nbsp;';
			} else {
				
				// if $numimages is not zero, then this would be a good time to make thumbnails
				if ($numimages != 0) {
					//$cache_dir = str_replace("albums", "cache", $directory_target).'/';					
					//echo $cache_dir;
					echo '   <div align="center">'. "\n";
					echo '    <table cellpadding="0" cellspacing="0" border="0" valign="top">'. "\n";
					echo '     <tr>'. "\n";
					echo '      <td width="20" background="images/lside-10x1024.jpg">&nbsp;</td>'. "\n";
					echo '      <td>'. "\n";
					echo '      <a href="'. $gallery_url. '/index.php?directory='. $directory. '&image='. $current[$id]. '&id='. $id. '"><img class="border" src="'.$cache_url. basename(Resize($current[$id], $thmb_width, $library, "thmb")). '"></a>';
					echo '      </td>'. "\n";
					echo '      <td width="20" background="images/vdrop-20x1024.jpg">&nbsp;</td>'. "\n";
					echo '     </tr>'. "\n";
					echo '     <tr>'. "\n";
					echo '      <td width="10">&nbsp;</td>'. "\n";
					echo '      <td height="20" background="images/hdrop-1024x20.jpg">&nbsp;</td>'. "\n";
					echo '      <td height="20" background="images/cdrop-20x20.jpg">&nbsp;</td>'. "\n";
					echo '     </tr>'. "\n";
					echo '    </table>'. "\n";
					echo '   </div>'. "\n";
				}else{
					// list the albums we want to show
					// what directory_target and cache dir are we looking for needs to be reassigned,
					// since we are working in a different directory right now
					$directory_target = $gallery_dir. substr($directory, 1).'/'.$current[$id];
					$cache_dir = str_replace("albums", "cache", $directory_target).'/';
					
					// lets check to see if we already have a album icon
					if (!file_exists($cache_dir.'folder_icon')) {
						//lets scan through it and grap all the images
						// so that we can make a nice thumbnail for the album link
						$image_list = list_directory($directory_target);
						$album_icon = basename ($image_list[0]);
					
						// change to the working directory for Resize
						chdir(dirname ($image_list[0]));
						$icon = basename(Resize($album_icon, $album_icon_width, $library, "folder")); 						
					} else {
						$icon = "folder_icon";
					}
									
					echo '   <div align="center">'. "\n";
					echo '    <table cellpadding="10" cellspacing="10" border="0">'. "\n";
					echo '     <tr>'. "\n";
					echo '      <td class="border" height="150" valign="top" width="130" align="center">'. "\n";
					echo '       <a href="'. $gallery_url. '/index.php?directory='. $directory. '/'. $current[$id]. '"><img class="border" src="'.$cache_url.$current[$id].'/'. $icon. '"></a>'. "\n";
					echo '<br><br><b>'.$current[$id].'</b>';
					echo '      </td>'. "\n";
					echo '     </tr>'. "\n";
					echo '    </table>'. "\n";
					echo '   </div>'. "\n";
					
				}
			}
			echo '</td>'. "\n";
			// increment the couter and start again
			$id = $id + 1;
		}
		echo ' </tr>'. "\n";
	}
	echo ' <tr>'. "\n";
	echo '  <th colspan="'. $cols. '">';
				
	// previous link
	if ($currentpage > 1) {
		echo '<a href="'. $gallery_url. '/index.php?directory='. $directory. '&page='. ($currentpage - 1). '">Previous</a> | ';
	}
		
	// showing xx of xx images		
	$showingnow = ($currentpage * $total);
	if ($showingnow > $num) {
		$showingnow = $num;
	}
	echo ' Showing '. $showingnow. ' of '. $num. ' ';
			
	//next link
	if ($currentpage < $pages) {
		echo ' | <a href="'. $gallery_url. '/index.php?directory='. $directory. '&page='. ($currentpage + 1). '">Next</a>';
	}
	echo '  </th>'. "\n";
	echo ' </tr>'. "\n";
	echo '</table>'. "\n";		
		
// if $image is not empty, that means we are trying to show a big picture
} else {
	
	// start the layout using tables
	// you can change width here to suit your taste
	echo '<table border="0" cellpadding="0">'. "\n";
                
        // show current pic with drop shadow
        echo ' <tr>'. "\n";
        echo '  <td  colspan="3">'. "\n";
        echo '   <div align="center">'. "\n";
	echo '    <table cellpadding="0" cellspacing="0" border="0" valign="top">'. "\n";
	echo '     <tr>'. "\n";
	echo '      <td width="20" background="images/lside-10x1024.jpg">&nbsp;</td>'. "\n";
	echo '      <td>'. "\n";
	echo '       <a href="'. $gallery_url. $directory. '/'. $list[$id]. '" target="_blank"><img class="border" src="'. $cache_url. basename(Resize($list[$id], $display_width, $library, "display")). '"></a>'. "\n";
	echo '      </td>'. "\n";
	echo '      <td width="20" background="images/vdrop-20x1024.jpg">&nbsp;</td>'. "\n";
	echo '     </tr>'. "\n";
	echo '     <tr>'. "\n";
	echo '      <td width="10">&nbsp;</td>'. "\n";
	echo '      <td height="20" background="images/hdrop-1024x20.jpg">&nbsp;</td>'. "\n";
	echo '      <td height="20" background="images/cdrop-20x20.jpg">&nbsp;</td>'. "\n";
	echo '     </tr>'. "\n";
	echo '    </table>'. "\n";
	echo '   </div>'. "\n";
	echo '  </td>'. "\n";
	echo ' </tr>'. "\n";
	
        // links
        echo ' <tr>'. "\n";
        
        // previous
        if ($id > 0) {
		echo '  <td width="'.$thmb_width.'"><div align="left"><a href="'. $gallery_url. '/index.php?directory='. $directory. '&image='. $list[$id - 1]. '&id='. ($id - 1). '"><img border="0" src="images/previous.gif"></a></div></td>'. "\n";
        }else{
		echo '  <td width="'.$thmb_width.'"><div align="left">&nbsp;</div></td>'. "\n";
	}
	
	//thumbnails
        $thmb_page = intval(($id + $total) / $total);
        echo '  <td align="center">';
        echo '<a href="'. $gallery_url. '/index.php?directory='. $directory. $list[$count]. '&page='. $thmb_page. '">Back to Thumbnails</a>';
        echo '  </td>'. "\n";
        
        // next
	if ($id < $num - 1) {
		echo '  <td width="'.$thmb_width.'"><div align="right"><a href="'. $gallery_url. '/index.php?directory='. $directory. '&image='. $list[$id + 1]. '&id='. ($id + 1). '"><img border="0" src="images/next.gif"></a></div></td>'. "\n";
        }else{
		echo '  <td width="'.$thmb_width.'"><div align="right">&nbsp;</div></td>'. "\n";
	}
        echo ' </tr>'. "\n";
        echo '</table>'. "\n";
}

// more html stuff
echo '  </td>'. "\n";
echo ' </tr>'. "\n";
echo '</table>'. "\n";

//Copyright Notice
echo '<div align="center">';
echo 'Indexed by <a href="http://www.cyberfrogs.net/">CyberFrogs.Net Gallery Script '. $version. '</a> © '. date(Y). '<br>';
echo 'Content by Patrick Lincoln © '. date(Y). '<br>';
echo '</div>'. "\n";

// include the footer if one was given
if (is_file($footer)) {
	include ($footer);
}

echo '</html>'. "\n";
echo '</body>'. "\n";
?>


