<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 */

header("Content-type: image/jpeg");
$im = imagecreate(20, 150);
$white = imagecolorallocate($im, 255, 255, 255);
$grey = imagecolorallocate($im, 128, 128, 128);
$black = imagecolorallocate($im, 0, 0, 0);
$text = $_REQUEST['txt'];
$font = 'vera.ttf';
imagettftext($im, 8, 270, 3, 5, $black, $font, $text);
imagejpeg($im);
imagedestroy($im);
?> 