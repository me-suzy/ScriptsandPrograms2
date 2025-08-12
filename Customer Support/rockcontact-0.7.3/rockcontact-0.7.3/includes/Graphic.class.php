<?
/***************************************************************************
 *                             Graphic.class.php
 *                            -------------------
 *   begin                : Tuesday, Jan 11, 2005
 *   copyright            : (C) 2005 Network Rebusnet
 *   contact              : http://rockcontact.rebusnet.biz/contact/
 *
 *   $Id$
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

require_once("config.inc.php");

class Graphic {

  /**
   * Return the RED part of color in decimal.
   *
   * @param string $color Color in format '#RRGGBB'
   * @return int The RED part of $color
   */
  function getRed($color){
    $rv = hexdec($color[1] . $color[2]);
    return $rv;
  }

  /**
   * Return the GREEN part of color in decimal.
   *
   * @param string $color Color in format '#RRGGBB'
   * @return int The GREEN part of $color
   */
  function getGreen($color){
    $rv = hexdec($color[3] . $color[4]);
    return $rv;
  }

  /**
   * Return the BLUE part of color in decimal.
   *
   * @param string $color Color in format '#RRGGBB'
   * @return int The BLUE part of $color
   */
  function getBlue($color){
    $rv = hexdec($color[5] . $color[6]);
    return $rv;
  }

  /**
   * Create GD image for visual code.
   *
   * @param string $code The rendered code
   * @param string $bgcolor The background color
   * @param string $fgcolor The font color
   * @param integer $mask The random mask intensity
   * @param integer $height The height of font in pixel
   * @return GD image visual code
   * @see VISUAL_FONT_PIXEL_SPACE
   */
  function renderVisualConfim($code, $bgcolor, $fgcolor, $mask, $height){
    $array_digit = array();

    for ( $x = 0; $x < strlen($code); $x++){
      $digit = $this->drawDigit($code[$x],$bgcolor, $fgcolor, $height);
      $array_digit = array_merge ($array_digit, array($x => $digit));
    }

    $array_size = $this->getSizeVisual($array_digit);

    $visual_code = imageCreateTrueColor($array_size["width"], $array_size["height"]);
    $bg = ImageColorAllocate($visual_code, $this->getRed($bgcolor) , $this->getGreen($bgcolor), $this->getBlue($bgcolor));
    ImageFill($visual_code, 0, 0, $bg);

    $next_pos = 1;

    for ( $x = 0; $x < count($array_digit); $x++){
      $w = imagesx($array_digit[$x]);
      $h = imagesy($array_digit[$x]);
      imagecopy($visual_code, $array_digit[$x],$next_pos,0, 0, 0, $w, $h);
      $next_pos += $w + VISUAL_FONT_PIXEL_SPACE;
      imageDestroy($array_digit[$x]);
    }

    $visual_code = $this->addMask($visual_code, $mask);

    return $visual_code;
  }

  /**
   * Merge visual code image and mask.
   *
   * @param GD image $img The image visual code
   * @param GD image $mask The random mask
   * @return GD image visual code with mask
   * see@ VISUAL_RAND_MASK_INTENSITY
   */
  function addMask($img, $mask){
    $w = imagesx($img);
    $h = imagesy($img);
    $m = $this->createMask($w, $h);
    imagecopymerge ( $img, $m, 0, 0, 0, 0, $w, $h, $mask);
    imageDestroy($m);
    return $img;
  }

  /**
   * Create random pixel mask.
   *
   * @param int $width The width of mask
   * @param int $height The height of mask
   * @return GD image mask
   */
  function createMask($width, $height){
    $mask = imageCreateTrueColor($width, $height);
    $color[0] = ImageColorAllocate($mask, 0, 0, 0);
    $color[1] = ImageColorAllocate($mask, 64, 64, 64);
    $color[2] = ImageColorAllocate($mask, 128, 128, 128);
    $color[3] = ImageColorAllocate($mask, 192, 192, 192);
    $color[4] = ImageColorAllocate($mask, 255, 255, 255);

    for($x = 0; $x < $width; $x++){
      for($y = 0; $y < $height; $y++ ){
        $pix_color = $color[rand(0,4)];
        imagesetpixel ( $mask, $x, $y, $pix_color);
      }
    }

    return $mask;
  }

  /**
   * Calculation of WIDTH and HEIGHT of visual code image.
   *
   * @param array $array_digit Array of digit in format GD image
   * @return array The size of new visual code. array('width'=> THE_WIDTH, 'height' => THE_HEIGHT)
   */
  function getSizeVisual($array_digit){
    $rv = array ( 'width' => VISUAL_FONT_PIXEL_SPACE + 1, 'height' => 0 );

    for ( $x = 0; $x < count($array_digit); $x++){
      $w = imagesx($array_digit[$x]);
      $h = imagesy($array_digit[$x]);
      $rv['width'] += $w + VISUAL_FONT_PIXEL_SPACE;
      if ( $h > $rv['height'] )
        $rv['height'] = $h;
    }

    return $rv;
  }

  /**
   * Draw one digit in GD image with random modification.
   *
   * @param string $digit The digit to draw
   * @param string $bgcolor The background color
   * @param string $fgcolor The font color
   * @param integer $height The height of font in pixel
   * @return GD image of digit
   * @see VISUAL_FONT_TYPE
   * @see VISUAL_FONT_HEIGHT
   * @see VISUAL_BACKGROUND_COLOR
   * @see VISUAL_FONT_COLOR
   * @see VISUAL_RAND_MIN_ROTATE
   * @see VISUAL_RAND_MAX_ROTATE
   * @see VISUAL_RAND_TRANSLATE
   */
  function drawDigit($digit, $bgcolor, $fgcolor, $height){
    $fw = imagefontwidth(VISUAL_FONT_TYPE);
    $fh = imagefontheight(VISUAL_FONT_TYPE);

    $img = imageCreateTrueColor($fw, $fh);
    $bg = ImageColorAllocate($img, $this->getRed($bgcolor), $this->getGreen($bgcolor), $this->getBlue($bgcolor));
    ImageFill($img, 0, 0, $bg);

    $fg = imagecolorallocate($img, $this->getRed($fgcolor), $this->getGreen($fgcolor), $this->getBlue($fgcolor));
    imagechar($img, VISUAL_FONT_TYPE, 0, 0, $digit, $fg);

    $rotate_angle = $this->getRandomSignedNumber(VISUAL_RAND_MIN_ROTATE, VISUAL_RAND_MAX_ROTATE);
    if(function_exists("imagerotate")){
      $img = imagerotate($img, $rotate_angle, hexdec($bgcolor));
    }

    $w = imagesx($img);
    $h = imagesy($img);

    $new_w = ($height * $w / $h);

    $rv = imagecreatetruecolor($new_w, $height);
    ImageFill($rv, 0, 0, $bg);
    imagecopyresampled($rv, $img, 0, 0, 0, 0, $new_w, $height, $w, $h);
    imageDestroy($img);

    return $rv;
  }

  /**
   * Return a random signed number between $min and $max.
   *
   * @param int $min The minimun
   * @param int $max The maximun
   * @return int Random positive or negative number
   */
  function getRandomSignedNumber($min, $max){
    $rv = rand($min, $max);

    if ( rand(0,1) == 1 ){
      $rv = 0 - $rv;
    }

    return $rv;
  }

}
?>
