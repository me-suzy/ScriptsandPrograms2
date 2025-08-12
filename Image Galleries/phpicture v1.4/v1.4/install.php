<?php
$starttime=gettimeofday();

@$p = $GET_['p'];

$new_height=140;

//Get all of the image names into array "images", numbered from 1-whatever.
$handle = opendir("images");
$key=1;
while (false !== ($file = readdir($handle)))
{
 if ($file <> "." AND $file <> ".." AND $file <> "system" AND $file <> "thumbs")
 {
  $files[$key] = $file;
  $key++;
 }
}
$numfiles=count($files);

if ($p == "" OR $p == 0) {$p=1;}
while ($p<=$numfiles)
{

//Identifying Image type

$len = strlen($files[$p]);
$pos =strpos($files[$p],".");
$type = substr($files[$p],$pos + 1,$len);

if ($type=="jpeg" OR $type=="jpg" OR $type=="JPEG" OR $type=="JPG")
{
thumb_jpeg ($files[$p]); //Call to jpeg function
}
else if($type=="png" OR $type=="PNG")
{
thumb_png ($files[$p]);    //Call to PNG function
}
else if($type=="gif" OR $type=="GIF")
{
thumb_gif ($files[$p]);
}
else
{
echo "$files[$p] is an unknown file format. Only .jpg, .gif and .png files are supported.";
}

//Check that the loop has not been running for >25 secs (and so be in danger of being stopped by the 30 second file execution limit)
$timenow=gettimeofday();
$timetohere = ($timenow['sec']-$starttime['sec']);
if ($timetohere >= 25)
{
$nextpic=$p+1;
die ("$p thumbnails created so far. <a href=\"install.php?p=$nextpic\">Continue...</a><BR>");
}

echo "$files[$p] thumbnail created...<BR>";
$p++;
}  //End of loop.

//JPEG function
function thumb_jpeg($image_name)
{
    global $new_height;
    $size = getimagesize ("images/$image_name");
    $new_width=$size[0]*($new_height/$size[1]);

    $destimg=ImageCreatetruecolor($new_width,$new_height) or die("Problem In Creating image");

    $srcimg=ImageCreateFromJPEG("images/".$image_name) or die("Problem In opening Source Image");

    ImageCopyResized($destimg,$srcimg,0,0,0,0,$new_width,$new_height,ImageSX($srcimg),ImageSY($srcimg)) or die("Problem In resizing");
    
    //Add the border to the thumbnail
    $black = ImageColorAllocate($destimg, 0, 0, 0);
    ImageLine($destimg, 0, 0, $new_width-1, 0, $black);   //Horizontal- top.
    ImageLine($destimg, 0, $new_height-1, $new_width-1, $new_height-1, $black);   //Horizontal- bottom.
    ImageLine($destimg, 0, 0, 0, $new_height-1, $black);   //Vertical- left
    ImageLine($destimg, $new_width-1, 0, $new_width-1, $new_height-1, $black);   //Vertical- right

    ImageJPEG($destimg,"images/thumbs/".$image_name) or die("Problem In saving");
}

//GIF function
function thumb_gif($image_name)
{
    global $new_height;
    $size = getimagesize ("images/$image_name");
    $new_width=$size[0]*($new_height/$size[1]);

    $destimg=ImageCreate($new_width,$new_height) or die("Problem In Creating image");

    $srcimg=ImageCreateFromGIF("images/".$image_name) or die("Problem In opening Source Image");

    ImageCopyResized($destimg,$srcimg,0,0,0,0,$new_width,$new_height,ImageSX($srcimg),ImageSY($srcimg)) or die("Problem In resizing");
    
    //Add the border to the thumbnail
    $black = ImageColorAllocate($destimg, 0, 0, 0);
    ImageLine($destimg, 0, 0, $new_width-1, 0, $black);   //Horizontal- top.
    ImageLine($destimg, 0, $new_height-1, $new_width-1, $new_height-1, $black);   //Horizontal- bottom.
    ImageLine($destimg, 0, 0, 0, $new_height-1, $black);   //Vertical- left
    ImageLine($destimg, $new_width-1, 0, $new_width-1, $new_height-1, $black);   //Vertical- right

    ImageGIF($destimg,"images/thumbs/".$image_name) or die("Problem In saving");
}

//PNG function
function thumb_png($image_name)
{
    global $new_height;
    $size = getimagesize ("images/$image_name");
    $new_width=$size[0]*($new_height/$size[1]);

    $destimg=ImageCreatetruecolor($new_width,$new_height) or die("Problem In Creating image");

    $srcimg=ImageCreateFromPNG("images/".$image_name) or die("Problem In opening Source Image");

    ImageCopyResized($destimg,$srcimg,0,0,0,0,$new_width,$new_height,ImageSX($srcimg),ImageSY($srcimg)) or die("Problem In resizing");
    
    //Add the border to the thumbnail
    $black = ImageColorAllocate($destimg, 0, 0, 0);
    ImageLine($destimg, 0, 0, $new_width-1, 0, $black);   //Horizontal- top.
    ImageLine($destimg, 0, $new_height-1, $new_width-1, $new_height-1, $black);   //Horizontal- bottom.
    ImageLine($destimg, 0, 0, 0, $new_height-1, $black);   //Vertical- left
    ImageLine($destimg, $new_width-1, 0, $new_width-1, $new_height-1, $black);   //Vertical- right

    ImagePNG($destimg,"images/thumbs/".$image_name) or die("Problem In saving");
}

echo "Complete.<a href=\"gallery.php\">Back to the gallery.</a><BR>";
















?>
