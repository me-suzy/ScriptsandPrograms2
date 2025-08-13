<?
//////////////////////////// COPYRIGHT NOTICE //////////////////////////////
// Program Name  	 : PhotoPost PHP                                  //
// Program Version 	 : 3.11                                           //
// Contributing Developer: Michael Pierce                                 //
// Supplied By           : Poncho                                         //
// Nullified By          : CyKuH [WTN]                                    //
//  This script is part of PhotoPost PHP, a software application by       //
// All Enthusiast, Inc.  Use of any kind of part or all of this           //
// script or modification of this script requires a license from All      //
// Enthusiast, Inc.  Use or modification of this script without a license //
// constitutes Software Piracy and will result in legal action from All   //
//                                                                        //
//           PhotoPost Copyright 2002, All Enthusiast, Inc.               //
//                       Copyright WTN Team`2002                          //
////////////////////////////////////////////////////////////////////////////
    header("Content-Type: image/jpeg");

    // in order for this script to work properly, you need to set the two variables below
    // $srcfilename needs to have the correct path to your "data" directory (leave $file as it is)
    // $watermark should be an absolute path to the PNG file you want to use for your watermark
    // PNG file should be a png-24 with a transparency layer
    
    $srcfilename = "c:/inetpub/wwwroot/gallery/data/$file";
    $watermark = "c:/inetpub/wwwroot/gallery/proof151.png"; 
    
    $imageInfo = getimagesize($srcfilename); 
    $width = $imageInfo[0]; 
    $height = $imageInfo[1]; 
    
    $logoinfo = getimagesize($watermark);
    $logowidth = $logoinfo[0];
    $logoheight = $logoinfo[1];
    
    $horizextra = $width - $logowidth;
    $vertextra = $height - $logoheight;
    // here's where you can determine the placement of the watermark
    
    // middle - places the watermark in dead center of the image
    //$horizmargin =  round($horizextra / 2);
    //$vertmargin =  round($vertextra / 2);
    
    // lower right corner
    $horizmargin =  $horizextra;
    $vertmargin =  $vertextra;
    
    $photoImage = ImageCreateFromJPEG($srcfilename);
    ImageAlphaBlending($photoImage, true);
    
    $logoImage = ImageCreateFromPNG($watermark);
    $logoW = ImageSX($logoImage);
    $logoH = ImageSY($logoImage);
    
    ImageCopy($photoImage, $logoImage, $horizmargin, $vertmargin, 0, 0, $logoW, $logoH);

    ImageJPEG($photoImage, "", 90);
     
    ImageDestroy($photoImage);
    ImageDestroy($logoImage);
?>
