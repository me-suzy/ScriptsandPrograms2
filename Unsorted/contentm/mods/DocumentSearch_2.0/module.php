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
	
	$result=$IW->query("select * from mod_docsearch_config limit 1");
	define("RESULT_PERPAGE",$IW->result($result,0,"per_page"));
	define("LINKS",$IW->result($result,0,"search_links"));
	define("META",$IW->result($result,0,"search_meta"));
	define("CONTENT",$IW->result($result,0,"search_content"));
	$IW->freeResult($result);	
?>
<form method=post action="<?php echo "?D=$D&search=1"; ?>">
<input type="hidden" name="V" value="search|keywords">
<center><p><input type="text" name="keywords" size=40> <input type="submit" value="Search"></p></center>
</form>
<dir>
<?php
	if(isset($search)&&$search==1)
		{
		$result=$IW->query("select * from ".IWARE_DOCS);
		$numrows=$IW->countResult($result);
		$IW->freeResult($result);
		if (empty($offset)) {$offset=0;}
		if(empty($keywords))
			{echo "<p><i>You must enter a search term in order to perform a search.</i></p>\n";}
		else
			{
			$sql ="select * from ".IWARE_DOCS." where is_hidden !='1' ";
			if(LINKS==1 || META==1 || CONTENT==1){$sql.=" and ";}
			if(LINKS==1){$sql.="link_text like '%$keywords%'";(META==1)?$sql.=" or ":$sql.="";}
			if(META==1){$sql.="meta_keywords like '%$keywords%' or meta_description like '%$keywords%' or meta_title like '%$keywords%' ";(CONTENT==1)?$sql.=" or ":$sql.="";}
			if(CONTENT==1){$sql.="doc_content like '%$keywords%'";}
			$sql.=" limit $offset,".RESULT_PERPAGE;
			//echo $sql;
			$result=$IW->query($sql);
			$matches=$IW->countResult($result);
			echo "<p><i>Your search for $keywords returned $matches matches.</i></p>\n";
			for($i=0;$i<$matches;$i++)
				{
				echo "<p><b><a href=\"?D=".$IW->result($result,$i,"id")."\">".$IW->result($result,$i,"link_text")."</a></b><br />\n";
				echo "<font size=1>";
				$meta=$IW->result($result,$i,"meta_description");
				if(empty($meta)){echo strip_tags(substr($IW->result($result,$i,"doc_content"),0,200));}
				else{echo strip_tags(substr($IW->result($result,$i,"meta_description"),0,200));}
				echo " ...</font></p>";
				}
			$IW->freeResult($result);
			if ($offset!=0) 
				{
				$prevoffset=$offset-3;
				echo "<a href=\"?D=$D&search=1&keywords=$keywords&offset=$prevoffset&V=search|keywords|offset\"><b>««</b></a> prev &nbsp;&nbsp;&nbsp; \n";
				}
			$pages=intval($numrows/RESULT_PERPAGE);
			if ($numrows%RESULT_PERPAGE) {$pages++;}
			for ($i=1;$i<=$pages;$i++) 
				{
				$newoffset=RESULT_PERPAGE*($i-1);
				echo "<a href=\"?D=$D&search=1&keywords=$keywords&offset=$newoffset&V=search|keywords|offset\">[<b>$i</b>]</a> &nbsp; \n";
				}
			if (!(($offset/RESULT_PERPAGE)==$pages) && $pages!=1) 
				{
				$newoffset=$offset+RESULT_PERPAGE;
				echo "&nbsp;&nbsp;&nbsp;next <a href=\"?D=$D&search=1&keywords=$keywords&offset=$newoffset&V=search|keywords|offset\"><b>»»</b></a><p>\n";
				}							
			}
		}
?>
</dir>