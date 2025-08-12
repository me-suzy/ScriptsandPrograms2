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
$show_help = true;
/* image view */
if ($image && $twg_album && !$top10) {
    if ($show_tips_image && (!($enable_dir_description_on_image && $default_is_fullscreen))) {
        if ($show_tips_image_once) { // we check if we have shown it already in this session
            if (isset($_SESSION["twg_show_tips_image"])) {
                $show_help = false;
            } else {
                $_SESSION["twg_show_tips_image"] = "TRUE";
            } 
        } 
        if ($show_help) {
            echo '<tr>
						<td class="twg_user_help_td">
						' . $lang_tips_image[array_rand ($lang_tips_image)] . '
						</td>
						</tr>
						';
        } 
    } 
} 

/* thumb view */
if (!$image && $twg_album && !$top10) {
    if ($show_tips_thumb) {
        if ($show_tips_thumb_once) { // we check if we have shown it already in this session
            if (isset($_SESSION["twg_show_tips_thumb"])) {
                $show_help = false;
            } else {
                $_SESSION["twg_show_tips_thumb"] = "TRUE";
            } 
        } 
        if ($show_help) {
            echo '<tr>
						<td class="twg_user_help_td">
						' . $lang_tips_thumb[array_rand ($lang_tips_thumb)] . '
						</td>
						</tr>
						';
        } 
    } 
} 

/* overview */
if (!$image && !$twg_album && !$top10) {
    if ($show_tips_overview) {
        if ($show_tips_overview_once) { // we check if we have shown it already in this session
            if (isset($_SESSION["twg_show_tips_overview"])) {
                $show_help = false;
            } else {
                $_SESSION["twg_show_tips_overview"] = "TRUE";
            } 
        } 
        if ($show_help) {
            echo '<tr>
						<td class="twg_user_help_td">
						' . $lang_tips_overview[array_rand ($lang_tips_overview)] . '
						</td>
						</tr>
						';
        } 
    } 
} 

?>