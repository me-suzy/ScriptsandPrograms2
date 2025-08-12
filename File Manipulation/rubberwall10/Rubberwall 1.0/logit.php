<?php

/***************************************************************************
 *                                RubberwaLL 1.0a
 *                            -------------------
 *   created:                : Friday, 9th May 2005
 *   copyright               : (C) 2005 ExplodingPanda.com, Neil Ord
 *   email                   : neil@explodingpanda.com
 *   web                     : http://www.explodingpanda.com/
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
$serverdate = date("d M Y h:i:s a");

$fw=fopen("referlog.html","a"); //open file in append mode for data addition.

$fdata=sprintf("%s%s%s%s%s%s%s%s%s%s%s%s","<b>Referer:</B> ",$_SERVER['HTTP_REFERER'],"<b> Wanted:</B> ",$serve,"<b> At:</B> ",$serverdate," <B>Remote Address:</B> ",$_SERVER['REMOTE_ADDR']," <B>Status:</B> ",$status,"<BR>","\n"); 

$logadd=fputs($fw,$fdata);

fclose($fw); 

?>