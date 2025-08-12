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

echo '<script type="text/javascript">';
		if ($disable_frame_adjustment_ie && (!stristr($_SERVER["HTTP_USER_AGENT"], "MSIE") || stristr($_SERVER["HTTP_USER_AGENT"], "Opera"))) {
			echo 'enable_adjust_iframe();';
		}
		if (!$disable_frame_adjustment_ie) {
			echo 'enable_adjust_iframe();';
		}
echo '</script>';
?>		