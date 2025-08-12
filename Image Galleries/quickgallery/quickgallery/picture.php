<?
    // spradzamy czy mamy konfig
    if(is_file('config.cfg.php'))
        require 'config.cfg.php';
    else
        die('Error, no config file');
			    
    if($_GET[img] == "")
	exit;

    if (!ereg('^[^./][^/]*$', $_GET[img]))
        exit;

    $_image_ = $_images_dir_.$_GET[img];
    $new_w = $_width_min_;
    $imagedata = getimagesize($_image_);

//    echo $_image_;
    if(!$imagedata[0])
	exit();


    $new_h = (int)($imagedata[1]*($new_w/$imagedata[0]));

    if(($_height_min_) AND ($new_h > $_height_min_))
    {
	$new_h = $_height_min_;
	$new_w = (int)($imagedata[0]*($new_h/$imagedata[1]));
    }

    if($_GET["show"] == "full")
    {
	if($_width_max_)
	{
	    if($imagedata[0]<$_width_max_)
	    {
		$new_w = $imagedata[0];
		$new_h = $imagedata[1];
	    }
	    else
	    {
		$new_w = $_width_max_;
		$new_h = (int)($imagedata[1]*($new_w/$imagedata[0]));
	    }
	}
	else
	{
	    $new_w = $imagedata[0];
	    $new_h = $imagedata[1];
	}
    }


  if(strtolower(substr($_GET[img],-3)) == "jpg")
  {
    header("Content-type: image/jpg");
    $dst_img=ImageCreate($new_w,$new_h);
    $src_img=ImageCreateFromJpeg($_image_);
    $dst_img = imagecreatetruecolor($new_w, $new_h);
    imagecopyresampled($dst_img,$src_img,0,0,0,0,$new_w,$new_h,ImageSX($src_img),ImageSY($src_img));
    $img = Imagejpeg($dst_img,'', $_quality_);
  }

  if(substr($_GET[img],-3) == "gif")
  {
    header("Content-type: image/gif");
    $dst_img=ImageCreate($new_w,$new_h);
    $src_img=ImageCreateFromGif($_image_);  
    ImagePaletteCopy($dst_img,$src_img);
    ImageCopyResized($dst_img,$src_img,0,0,0,0,$new_w,$new_h,ImageSX($src_img),ImageSY($src_img));
    $img = Imagegif($dst_img,'', $_quality_);
  }
  

?>