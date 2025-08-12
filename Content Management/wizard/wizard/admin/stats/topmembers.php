<?php
/*  
   Most Active Members
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

// bar graph function
    include 'inc/functions/array2bar.php';

$db = new DB();


   //most active members
   $db->query("SELECT Member, Host, Count(ID) as Count_ID FROM ". DB_PREPEND . "hits GROUP BY Host ORDER BY Count_ID DESC LIMIT 0, 20");

   while($makeArray = $db->next_record()){
     $key = $makeArray['Member']; 
	 $value = $makeArray['Count_ID']; 
   	 $statArray[] = array($key,$value);
   } // while
   
   


?>
<div id="pagelinks" >
<table bgcolor="#f8f8ff" style="border-collapse: collapse" cellpadding="2" border="1" border-color="c0c0c0" width="100%">
<tr><td>
<table bgcolor="#f8f8ff" style="border-collapse: collapse" cellpadding="0" border="0" border-color="c0c0c0" width="100%">
	
	<tr><td>&nbsp;</td></tr>
	
	<tr><td>
		<table style="border-collapse: collapse" cellpadding="2" border="0" border-color="c0c0c0" width="100%">
			<tr class="normalText" bgcolor="#f0f0f0">
	   			<td style="border: 0px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b>Most Active Members</b></a></td>
			</tr>
    	</table>
	</td></tr>
	
	
	<tr><td>
	
		
	<br />
<?php   
   $bar_width = $config['siteWidth'] - 120;
   echo "<p class=\"message\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Twenty most active members in page views:</p>";
   echo array2bar($statArray, $bar_width);
   echo "<br />";
?>
</td></tr>
</table>
</td></tr>
</table>
<br />
</td></tr>
</table>
</td></tr>
</table>
</div>