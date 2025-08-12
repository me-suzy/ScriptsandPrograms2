<?php
/*  
   Search
   (c) 2005 Philip Shaddock, www.wizardinteractive.com
	This file is part of the Wizard Site Framework.

    This file is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    It is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with the Wizard Site Framework; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

include("inc/functions/array2bar.php");

?>
<div id="pagelinks" >
<table bgcolor="#f8f8ff" style="border-collapse: collapse" cellpadding="2" border="1" border-color="c0c0c0" width="100%">
<tr><td>
<table bgcolor="#f8f8ff" style="border-collapse: collapse" cellpadding="0" border="0" border-color="c0c0c0" width="100%">
		<tr><td>&nbsp;</td></tr>
	
	<tr><td>
		<table style="border-collapse: collapse" cellpadding="2" border="1" border-color="c0c0c0" width="100%">
			<tr class="normalText" bgcolor="#f0f0f0">
	   			<td style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b>Search Engine Keywords</b></a></td>
			</tr>
    	</table>
	</td></tr>
	
	
	<tr><td>
	
	
	<br />
<?
echo "<div align=\"left\"><left>";
echo "<table width=\"98%\" align=\"center\"><tr><td>";

echo "<table><tr><td class=\"message\">Search words used to find this site (limited to 500 results):<br/></td></tr></table>";



//variables used by this script
$keywords=""; 
$month=date("m"); 
$year=date("Y"); 



//search browser field for words in the top thirteen search engines
$db->query("SELECT Referer FROM ". DB_PREPEND . "hits WHERE Referer LIKE '%google%'
OR Referer LIKE '%alltheweb%'or Referer LIKE '%search%' or Referer LIKE '%ifind%' 
OR Referer LIKE '%altavista%'or Referer LIKE '%lycos%' 
OR Referer LIKE '%hotbot%'or Referer LIKE '%yahoo%' or Referer LIKE '%looksmart%' 
or Referer LIKE '%dmoz%' or Referer LIKE '%ask%'");  
while ($row = $db->next_record()) { 
$topengines[] = $row['Referer']; }  

if (!$topengines) {
    echo "<p align=\"center\">No results to display!</p>";
	echo "</td></tr></table><br /></td></tr></table></div>";
	exit;
}

	//now process the script to extract the keywords we need

	foreach ($topengines as $url){

	// Google, AllTheWeb, MSN, Freeserve, Altavista 
      if ((eregi("www\.google",$url)) or (eregi("www\.alltheweb",$url)) or (eregi("search\.msn",$url)) or (eregi("ifind\.freeserve",$url)) or (eregi("altavista\.com",$url))) { 
         preg_match("'q=(.*?)(&| )'si", " $url ", $keywords); 
      } 
      // HotBot, Lycos, Netscape, AOL 
      elseif ((eregi("www\.hotbot",$url)) or (eregi("search\.lycos",$url)) or (eregi("search\.netscape",$url)) or (eregi("aolsearch\.aol",$url))) { 
         preg_match("'query=(.*?)(&| )'si", " $url ", $keywords); 
      } 
      // Yahoo 
      elseif ((eregi("yahoo\.com",$url)) or (eregi("search\.yahoo",$url))) { 
         preg_match("'p=(.*?)(&| )'si", " $url ", $keywords); 
      } 
      // Looksmart 
      elseif (eregi("looksmart\.com",$url)) { 
         preg_match("'key=(.*?)(&| )'si", " $url ", $keywords); 
      } 
      // DMOZ 
      elseif (eregi("search\.dmoz",$url)) { 
         preg_match("'search=(.*?)(&| )'si", " $url ", $keywords); 
      } 
      // Ask 
      elseif (eregi("ask\.co",$url)) { 
         preg_match("'ask=(.*?)(&| )'si", " $url ", $keywords); 
      } 
      else{ 
	    unset($keywords);
	  }
	
	if (($keywords[1]!="") and ($keywords[1]!=" ")) { 
         $keywords=preg_replace("/\+/"," ",$keywords[1]);    
         $keywords=eregi_replace("%2B"," ",$keywords); 
         $keywords=eregi_replace("%2E","\.",$keywords); 
         $keywords=trim(eregi_replace("%22","\"",$keywords)); 
		 }
     
    if (is_array($keywords)) {
		//do nothing
    }
	elseif (isset($keywords)) { 
	//builds a new array of search terms for columnar display	
	$keycolumns[] = $keywords; 
	
	}
    else {

    //do nothing	

	}
	

   }//foreach

//set the number of columns
$columns = 4;

//we add this line because we need to know the number of rows
$num_rows = count($keycolumns);
echo "<table width=\"100%\" align=\"center\" border=\"0\">\n";
$count = 0;
//changed this to a for loop so we can use the number of rows
for($i = 0; $i < $num_rows; $i++) {
    
    if($i % $columns == 0) {
        //if there is no remainder, we want to start a new row
        echo "<TR>\n";
    }
    echo "<TD width=\"25%\" class=\"smallText\">" . $keycolumns[$count] . "</TD>\n";
    if(($i % $columns) == ($columns - 1) || ($i + 1) == $num_rows) {
        //if there is a remainder of 1, end the row
        //or if there is nothing left in our result set, end the row
        echo "</TR>\n";
    }
	$count++;
	//limit results shown 
	if ($count > 500) {
	    echo "</TABLE>\n";
		echo "</td></tr></table><br /></td></tr></table></div>";
		exit;
	}
} //for
echo "</TABLE>\n";



echo "</table></left></div>";

?>


</td></tr>
</table>
<br />
</td></tr>
</table>

</div>