<?
    // spradzamy czy mamy konfig
    if(is_file('config.cfg.php'))
	require 'config.cfg.php';
    else
	die('Error, no config file');
    
    //sprawdzamy czy mamy katalog ze zdjeciami
    if(!is_dir($_images_dir_))
	die('Error, no image directory');

?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-2">
<title>QuickGallery</title>
<meta NAME="Author" CONTENT="Marcin Chmielecki">

<link href="<?echo $_style_?>" rel="stylesheet" type="text/css">
</head>
<body>

<?
    $time_start = microtime(true);
    

    //liczymy zdjecia (jpg i gif)
    $noimage = 0;
    
    if ($dh = opendir($_images_dir_)) {
    while (($f = readdir($dh)) !== false) {
	if((substr(strtolower($f),-3) == 'jpg') || (substr(strtolower($f),-3) == 'jpg'))
	{
	    $imageinfo = getimagesize($_images_dir_.$f);
	    $width = $imageinfo[0];
	    $height = $imageinfo[1];
	    $size = round(filesize($_images_dir_.$f)/1024,1).' KB';
	    $noimage++;
    	    $images[] = array('filename' => $f, 'width' => $width, 'height' => $height, 'size' => $size);
	    array_multisort($images, $_sort_, SORT_REGULAR);
	}
    }
    closedir($dh);
    }	


    if($noimage)
    {

	if($_GET["img"] != '')
	{
	    echo '<p class="title">'.$images[$_GET["img"]][filename].'</p>';
	    if($images[$_GET["img"]][filename] != '')
	    {
	    
		if($_GET["img"])
		    $_no_ = ceil(($_GET["img"]+1)/$_no_pics_per_page_);
		else
		    $_no_ = 1;
		
		if($_GET["img"] == 0)
		    $side = '<p class="link"><a href="'.$_SERVER["PHP_SELF"].'?no='.$_no_.'" class="link">up</a>&nbsp;<a href="'.$_SERVER["PHP_SELF"].'?img='.($_GET["img"]+1).'" class="link">next >></a></p>';
		elseif($_GET["img"] == ($noimage-1))
		    $side = '<p class="link"><a href="'.$_SERVER["PHP_SELF"].'?img='.($_GET["img"]-1).'" class="link"><< prev</a>&nbsp;<a href="'.$_SERVER["PHP_SELF"].'?no='.$_no_.'" class="link">up</a></p>';
		else
		    $side = '<p class="link"><a href="'.$_SERVER["PHP_SELF"].'?img='.($_GET["img"]-1).'" class="link"><< prev</a>&nbsp;<a href="'.$_SERVER["PHP_SELF"].'?no='.$_no_.'" class="link">up</a>&nbsp;<a href="'.$_SERVER["PHP_SELF"].'?img='.($_GET["img"]+1).'" class="link">next >></a></p>';
		echo $side.'<p align="center"><img  class= "link" src="picture.php?img='.$images[$_GET["img"]][filename].'&show=full" border="'.$_border_.'" hspace="'.$_hspace_.'" vspace="'.$_vspace_.'" alt="'.$images[$_GET["img"]][filename].'" title="'.$images[$_GET["img"]][filename].', '.$images[$_GET["img"]][width].'x'.$images[$_GET["img"]][height].', '.$images[$_GET["img"]][size].'" ></p>'.$side;
	    }
	}
	else
	{
    	echo '<p class="title">'.$_page_title_.'</p>';

//	echo $noimage.'<br>'.print_r($images);
	if($noimage > $_no_pics_per_page_)
	    $norows = ceil($_no_pics_per_page_/$_no_cols_);
	else
	    $norows = ceil($noimage/$_no_cols_);
	
	$nopos = ceil($noimage/$_no_pics_per_page_);
	
//	echo $nopos;
	echo '<table border="0" align="center" cellpadding="0" cellspacing="0">
	';
	
	if($_GET["no"] == "")
	    $no = 1;
	else
	    $no = $_GET["no"];
	    
	
	$index=abs($no-1)*$_no_pics_per_page_;
	if(($noimage-$index)<$_no_pics_per_page_)
	    $norows= ceil(($noimage-$index)/$_no_cols_);

	for($a = 1;$a<=$norows;$a++)
	{
	    echo '
	    <tr>
	    ';
		for($b = 1;$b<=$_no_cols_;$b++)
		{
		    if($images[$index][filename] != '')
		    {
			echo '<td width="'.(2*$_vspace_+$_width_min_).'" valign="top" align="center"><a href="'.$_SERVER["PHP_SELF"].'?img='.$index.'" ><img  class="link"src="picture.php?img='.$images[$index][filename].'" border="'.$_border_.'" hspace="'.$_hspace_.'" vspace="'.$_vspace_.'" alt="'.$images[$index][filename].'" title="'.$images[$index][filename].', '.$images[$index][width].'x'.$images[$index][height].', '.$images[$index][size].'"></a>
			';
			if($_filename_)
			    echo '<span class="name">'.$images[$index][filename].'</span><br><br>';
	
			echo '</td>';
			$index++;
		    }
		    else
			echo '<td width="'.(2*$_vspace_+$_width_min_).'">&nbsp;</td>';
		}	
	    echo '</tr>
	    ';
	}
	
	echo '
	</table>
	';
	
	if($nopos>1)
	{
	    echo '<p class="stopka">';
    	    for($i=1;$i<=$nopos;$i++)
	    {
	        if($i == $no)
	            echo '<font class="stopka_select">'.$i.'</font>&nbsp;';
	        else
	            echo '<a href="'.$_SERVER["PHP_SELF"].'?no='.$i.'" class="stopka">'.$i.'</a>&nbsp;';
	    }
	    echo '</p>';
	}	

	}



    }
    else
	die('No images in directory');


    $time_end = microtime(true);
    $time = $time_end - $time_start;
    echo '<p align="center"><span class="exe"><br>All images in gallery : '.count($images).'<br></span><span class="copy"><br>Created by <a href="http://www.chiliweb.com.pl/freesoft/quickgallery/" target="_blank" class="copy">QuickGallery</span></p>';
?>
</body>
</html>