<?php
/*
-------------------------------------------------------------
|MD Random Image Generator                                  |
|Version 1.0.0                                              |
|This program is Copyright (c) Matthew Dingley 2003         |
|For more help or assistance go to MD Web at:               |
|www.matthewdingley.co.uk                                   |
|For information on how to install or for basic licence     |
|information, view below                                    |
|                                                           |
|This program is not to be used on a commercial site without|
|a commercial licence. Go to www.matthewdingley.co.uk for   |
|more information.                                          |
|                                                           |
|To install, just enter in the directory name that you store|
|the images in to the variable below named $dir. Upload any |
|images to that folder to have them randomly displayed.     |
|                                                           |
|You can also edit the variable $pattern if you know what   |
|you are doing.                                             |
|                                                           |
|To display the random image on your web page, you can      |
|either copy and paste all of this code into the page where |
|you want it or you can include it by putting in the        |
|following code into the page where you want the image:     |
|<?php include "image.php"; ?>                              |
-------------------------------------------------------------
*/
$dir=opendir("/home/you/public_html/folder/");
//This is the directory route to the folder
$directory="";
//This is a relative link to the directory if it is not in the same directory as the file you are displaying the images on

$pattern="\.(gif|jpg|jpeg|png|bmp|swf)$";
if(!$dir)
{
die("Failed to read directory");
}
$s=readdir($dir);
$count="0";
$image;
while($s)
{
if(ereg($pattern, $s))
{
$image[$count]=$s;
$count++;
}
$s=readdir($dir);
}
closedir($dir);

//Spit it out
$limit=count($image);
$limit--;
$randNum=rand(0,$limit);
$size=getimagesize("$directory$image[$randNum]");
echo "<br><img src=\"$directory$image[$randNum]\" $size[3]>";
?>
