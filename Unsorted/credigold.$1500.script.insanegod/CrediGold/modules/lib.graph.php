<?php

include("prepend.php3");

//$dc->query("SELEC * FROM");

$prices = array(

            50, 19, 20, 30, 34, 69,

            20, 19, 40, 30, 34, 29,

            30, 34, 60, 30, 37, 39,

            40, 49, 10, 30, 30, 49,

            60, 96, 43, 30, 24, 49,

            10, 40, 23, 23, 14, 19,

            32, 70, 23, 70, 54, 59,

            ); // Resource Data





$im     = ImageCreateFromJPEG("test.jpg");

$black  = ImageColorAllocate($im, 0,0,0);

$orange = ImageColorAllocate($im, 250,105,0);

$green  = ImageColorAllocate($im, 100,155,0);

$red    = ImageColorAllocate($im, 250,25,100);



$color = $red; // Define the color

$max   = 100; // max($prices)



$x = 39;

$y = 157;



$x0   = 39;

$y0   = 157;

$x100 = 360;

$y100 = 15;



$xDi  = $x100 - $x0;

$yDi  = $y0   - $y100;



// Currencty Graph

for ($i=0;$i<count($prices);$i++)

   {

      $indexPoint = ($prices[$i] > $max)?$max:$prices[$i];

      $newX = $x0 + $i*($xDi/count($prices));

      $newY = $y0 - ($yDi*($indexPoint/$max));

      $newColor = ($i == 0)?$black:$color;

      ImageLine($im, $x, $y, $newX, $newY, $newColor);

      ImageRectangle($im, $newX-2, $newY-2, $newX+1, $newY+1, $black);

      $x = $newX;

      $y = $newY;

   }



// Week's Images

for ($i=0;$i<7;$i++)

   {

      $week   = "days/week/".strtolower(date("D",time()-86400*$i)).".jpg";

      $day    = "days/nums/".date("d",time()-86400*$i).".jpg";

      $wk     = ImageCreateFromJpeg($week);

      $dys    = ImageCreateFromJpeg($day);

      ImageCopy($im, $wk, ($x100 - 45) - 45*$i, $y0+3, 0, 0, imagesx($wk), imagesy($wk));

      ImageCopy($im, $dys, ($x100 - 45) - 45*$i, $y0+13, 0, 0, imagesx($dys), imagesy($dys));

   }



// Balance Building

for ($i=0;$i<12;$i++)

   {

      if ($i <= 6)

      $today     += $prices[count($prices)-$i];

      else

      $yesterday += $prices[count($prices)-$i];

   }

$balance     = $today - $yesterday;

$balanceSRC  = (($balance >= 0)?"days/up.jpg":"days/down.jpg");

$balanceIMG  = ImageCreateFromJpeg($balanceSRC);

$balance    = ($balance > 0)?("+".$balance):$balance;

ImageString($im, 3, 350, 16, $balance, $black);

ImageCopy($im, $balanceIMG, 340, 17, 0, 0, imagesx($balanceIMG), imagesy($balanceIMG));



// Create the Chart

Header("Content-type: image/jpeg");

ImageJPEG($im);

ImageDestroy($im);

?>