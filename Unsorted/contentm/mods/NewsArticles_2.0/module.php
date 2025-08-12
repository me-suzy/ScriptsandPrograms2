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
	
	if(isset($view)&&$view==1)
		{

		$result=$IW->Query("select * from mod_newspost where id='$id' limit 1 ");
		$headline=$IW->Result($result,0,"news_headline");
		$date=date("m/d/Y",$IW->Result($result,0,"news_date"));
		$author=$IW->Result($result,0,"news_author");
		$body=$IW->Result($result,0,"news_body");
		$IW->FreeResult($result);

		?>
		<center><table border=0 cellpadding=3 cellspacing=0 width=525><tr>
		<td><b><?php echo $headline; ?></b></td>
		<td align=center width=150><a href="index.php?D=<?php echo $D; ?>">Back</a></td>
		</tr></table></center>
		<p>
		<center>
		<table border=0 cellpadding=3 cellspacing=0 width=525>
		<tr><td height=300 valign=top><p><?php echo $body; ?></p></td></tr>		
		<tr><td align=center><font size=1><?php echo $date; ?> ~ <?php echo $author; ?></font></td></tr>
		</table>
		</center>
		</p>
		<?php

		}
	else
		{

		// Load Configuration
		$config=$IW->Query("select * from mod_newspost_config limit 1");
		define("NEWS_PER_PAGE",$IW->Result($config,0,"news_per_page"));		
		define("PREVIEW_LENGTH",$IW->Result($config,0,"preview_length"));
		$IW->FreeResult($config);

		// get total num of posts
		$posts=$IW->Query("select * from mod_newspost");
		$numrows=$IW->CountResult($posts);
		$IW->FreeResult($posts);

		// Show Previous Posts Ordered By Date Posted
		if (empty($offset)) {$offset=0;}
		$result=$IW->Query("select * from mod_newspost order by news_date desc limit $offset,".NEWS_PER_PAGE);
		$count=$IW->CountResult($result);
		?>
		<center>
		<table border=0 cellpadding=3 cellspacing=0 width=525>
		<?php
		// show news titles
		for($i=0;$i<$count;$i++)
			{
			echo "<tr>\n<td>\n<b><a href=\"index.php?D=$D&V=view|id&view=1&id=".$IW->Result($result,$i,"id")."\">".$IW->Result($result,$i,"news_headline")."</a></b><br />\n";
			echo "<font size=1>".date("m/d/Y",$IW->Result($result,$i,"news_date"))." ~ ".$IW->Result($result,$i,"news_author")."<font size=1><br />\n";
			echo "<font size=1>".strip_tags(substr($IW->Result($result,$i,"news_body"),0,PREVIEW_LENGTH))."</font>\n</td>\n</tr>\n";
			}
		?>
		</table>
		</center>
		<center>
		<table border=0 cellpadding=3 cellspacing=0 width=525>
		<tr><td>
		<?php
		$IW->FreeResult($result);

		// Paging Links
		echo "<br /><br /><center>\n";

			// next we need to do the links to other results
			if ($offset!=0) { // bypass PREV link if offset is 0
				$prevoffset=$offset-3;
					echo "<a href=\"index.php?D=$D&V=offset&offset=$prevoffset\"><b>««</b></a> prev ".NEWS_PER_PAGE."&nbsp;&nbsp;&nbsp; \n";
			}

			// calculate number of pages needing links
			$pages=intval($numrows/NEWS_PER_PAGE);

			// $pages now contains int of pages needed unless there is a remainder from division
			if ($numrows % NEWS_PER_PAGE) {
				// has remainder so add one page
				$pages++;
			}

			for ($i=1;$i<=$pages;$i++) { // loop thru
				$newoffset=NEWS_PER_PAGE*($i-1);
				print "<a href=\"index.php?D=$D&V=offset&offset=$newoffset\">[<b>$i</b>]</a> &nbsp; \n";
			}

			// check to see if last page
			if (!(($offset/NEWS_PER_PAGE)==$pages) && $pages!=1) {
				// not last page so give NEXT link
				$newoffset=$offset + NEWS_PER_PAGE;
				print "&nbsp;&nbsp;&nbsp;next ".NEWS_PER_PAGE." <a href=\"index.php?D=$D&V=offset&offset=$newoffset\"><b>»»</b></a><p>\n";
			}

		echo "</center>\n";
		?>
		</td></tr>
		</table></center>
	<?php } ?>