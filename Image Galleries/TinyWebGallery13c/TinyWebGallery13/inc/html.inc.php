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
/* not tips for the topx yet - if this page has more stuff like rating ... this will come */
if ($enable_external_html_include) {
    /* image view */
    if ($image && $twg_album && !$top10) {
        $imagehtml = dirname(__FILE__) . "/../image.htm";
        if (file_exists($imagehtml)) {
            echo "<tr><td class='twg_imagehtml'>";
            include ($imagehtml);
            echo "</td></tr>";
        } 
    } 

    /* thumb view */
    if (!$image && $twg_album && !$top10) {
        $thumbhtml = dirname(__FILE__) . "/../thumb.htm";
        if (file_exists($thumbhtml)) {
            echo "<tr><td class='twg_thumbhtml'>";
            include ($thumbhtml);
            echo "</td></tr>";
        } 
    } 

    /* overview */
    if (!$image && !$twg_album && !$top10) {
        $overviewhtml = dirname(__FILE__) . "/../overview.htm";
        if (file_exists($overviewhtml)) {
            echo "<tr><td class='twg_overviewhtml'>"; 
            // echo "test";
            include ($overviewhtml);
            echo "</td></tr>";
        } 
    } 
} 

?>