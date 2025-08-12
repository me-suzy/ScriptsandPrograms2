<?php

	/*////////////////////////////////////////////////////////////
	
	iWare Professional 4.0.0
	Copyright (C) 2002,2003 David N. Simmons 
	http://www.dsiware.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

	A COPY OF THE GPL LICENSE FOR THIS PROGRAM CAN BE FOUND WITHIN THE
	docs/ DIRECTORY OF THE INSTALLATION PACKAGE.

	/////////////////////////////////////////////////////////////*/	
	
// Load Configuration
	$config=$IW->Query("select * from mod_imagegallery_config limit 1");
	define("IMG_PER_PAGE",$IW->Result($config,0,"img_per_page"));
	define("IMG_PER_ROW",$IW->Result($config,0,"img_per_row"));	
	define("SHOW_SIZE",$IW->Result($config,0,"show_size"));
	define("SHOW_TYPE",$IW->Result($config,0,"show_type"));
	define("SHOW_CAPTION",$IW->Result($config,0,"show_caption"));
	define("USE_THUMBS",$IW->Result($config,0,"use_thumbs"));
	define("THUMBS_SCALE",$IW->Result($config,0,"thumbs_scale"));
	define("USE_ZOOM",$IW->Result($config,0,"use_zoom"));
	$IW->FreeResult($config);	

	// get total num of images
	$posts=$IW->Query("select * from mod_imagegallery");
	$numrows=$IW->CountResult($posts);
	$IW->FreeResult($posts);

	// Show Previous Posts Ordered By Date Posted
	if (empty($offset)) {$offset=0;}
	$result=$IW->Query("select * from mod_imagegallery limit $offset,".IMG_PER_PAGE);
	$count=$IW->CountResult($result);
	
	?>
	<script language=JavaScript>
		function zoomImage(file,width,height)
			{
			var file='files/'+file;
			var win=window.open(file,'win','width='+width+',height='+height+'');
			win.focus ();
			}
	</script>
	<center>
	<table border=0>
	<?php
	// show images
	$row=1;
	for($i=0;$i<$count;$i++)
		{
		$filename=$IW->Result($result,$i,"filename");
		$caption=$IW->Result($result,$i,"caption");
		$size=getImageSize("files/$filename");
		$width=$size[0];
		$height=$size[1];
		if(USE_THUMBS==1)
			{
			$width=($size[0] / THUMBS_SCALE);
			$height=($size[1] / THUMBS_SCALE);
			}
		switch($size[2])
			{
			case 1:$type="GIF";break;
			case 2:$type="JPG";break;
			case 3:$type="PNG";break;
			case 4:$type="SWF";break;
			case 5:$type="PSD";break;
			case 6:$type="BMP";break;
			case 7:case 8:$type="TIFF";break;
			default:$type="Unknown Image Type";break;
			}
		echo "<td align=center>";
		if(USE_ZOOM==1){echo "<a href=\"javascript:zoomImage('$filename','".$size[0]."','".$size[1]."')\">";}
		echo "<img src=\"files/$filename\" width=\"$width\" height=\"$height\">\n";
		if(USE_ZOOM==1){echo "</a><br /><i>click to enlarge</i>";}
		if(SHOW_SIZE==1){echo "<br />".$size[0]." X ".$size[1]."\n";}
		if(SHOW_TYPE==1){echo "&nbsp;&nbsp;&nbsp;".$type."\n";}
		if(SHOW_CAPTION==1){echo "<br />".$caption."\n";}
		echo "</td>";
		$row++;
		if($row>IMG_PER_ROW){echo "</tr><tr>";$row=1;}
		}
	?>
	</table>
	</center>
	<center>
	<?php
	$IW->FreeResult($result);

	// Paging Links
	echo "<center>\n";

		// next we need to do the links to other results
		if ($offset!=0) { // bypass PREV link if offset is 0
			$prevoffset=$offset-3;
				echo "<a href=\"index.php?D=$D&V=offset&offset=$prevoffset\"><b>««</b></a> prev ".IMG_PER_PAGE."&nbsp;&nbsp;&nbsp; \n";
		}

		// calculate number of pages needing links
		$pages=intval($numrows/IMG_PER_PAGE);

		// $pages now contains int of pages needed unless there is a remainder from division
		if ($numrows % IMG_PER_PAGE) {
			// has remainder so add one page
			$pages++;
		}

		for ($i=1;$i<=$pages;$i++) { // loop thru
			$newoffset=IMG_PER_PAGE*($i-1);
			print "<a href=\"index.php?D=$D&V=offset&offset=$newoffset\">[<b>$i</b>]</a> &nbsp; \n";
		}

		// check to see if last page
		if (!(($offset/IMG_PER_PAGE)==$pages) && $pages!=1) {
			// not last page so give NEXT link
			$newoffset=$offset + IMG_PER_PAGE;
			print "&nbsp;&nbsp;&nbsp;next ".IMG_PER_PAGE." <a href=\"index.php?D=$D&V=offset&offset=$newoffset\"><b>»»</b></a><p>\n";
		}

	echo "</center>\n";
	?>
	</center>