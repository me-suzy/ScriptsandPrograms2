<?php
/*************************
  Copyright (c) 2004-2005 TinyWebGallery
  written by Michael Dempfle

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.
  ********************************************
  TWG version: 1.3c
  $Date: 2005/11/15 09:02 $
**********************************************/
echo '<center><div class="twg_rating"><table class="twg" summary=""><tr><td class=twg_rating_text>' . $lang_rating . ': ';
echo '</td><td class=twg>';
echo '<img alt="" width=5 height=5 src="' . $install_dir . 'buttons/1x1.gif" />';

$ratelink = "<a onclick='twg_showSec(" . $lang_height_rating . ")' target='details' id='i_rate' href='" . $install_dir . "i_frames/i_rate.php?twg_album=" . $album_enc . "&amp;twg_show=" . $image_enc . $twg_standalone;
echo $ratelink . "'>";

$rating = substr(getVotesCount($twg_album, urldecode($image)), 0, 4);
if (round($rating) == floor($rating)) {
    $rateimage = floor($rating) . "0";
} else {
    $rateimage = floor($rating) . "5";
} 
echo '<span id="img_rating"><img alt="' . $rating . '" title="' . $rating . '"  src="' . $install_dir . 'buttons/s' . $rateimage . '.gif" /></span></a>';
echo '</td></tr></table>';
echo '</div></center>';
?>