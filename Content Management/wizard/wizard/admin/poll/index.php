<?php
/*  
   Existing Poll
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


//authentication check
    include_once ("admin/admin_auth.php");


?>


 

<!-- Inner table -->
<table border="1"  bgcolor="#f8f8ff" cellpadding="5" style="border-collapse: collapse"   bordercolor="#E5E5E5" width="100%">
<tr><td>
<table border="0"  bgcolor="#f8f8ff" cellpadding="5" style="border-collapse: collapse"   bordercolor="#E5E5E5" width="100%">
              

<!--- Existing Poll  -->
    <tr><td colspan="4">
	<table cellpadding="2" border="0" width="100%">
	<tr class="normalText" bgcolor="#f0f0f0">
	   <td style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b>&nbsp;Current Poll Results</b></a></td>
    </tr>
	</table>
	</td></tr>

<tr><td colspan="4">
	<table cellpadding="2" border="0" align="center" width="80%">


<?php
    
	$db = new DB();
	$db->query("SELECT * FROM ". DB_PREPEND . "poll_question");
		$result = $db->next_record();	
		$totalvotes = $result['totalvotes'];
		$question = $result['question'];
		
		echo("<div class=poll><strong>Question:&nbsp;&nbsp;&nbsp;</strong> $question<br /><br />");
	
	
	$get_questions = mysql_query("select * from ". DB_PREPEND . "poll_answers");
	while($r=mysql_fetch_array($get_questions)){
	
	
		extract($r);
		if($votes=="0"){
			
			$per = '0';
			
		}else{
			
			$per = $votes * 100 / $totalvotes;
			
		}
		$per = floor($per);
		
		echo($field); 
?> 
		<strong><?php echo("$votes"); ?></strong><br />
		<div style="background-color:#D7D7D7;"><div style="color:#000000; text-align: right; background-color:#4795C3; width:<? echo($per); ?>%;"><? echo("$per%"); ?></div></div>
		<?
			
	}
	
	echo("<br />Total votes: <strong>$totalvotes</strong></div>"); 
	
?>
</td></tr></table></td></tr>	
	
	
	<tr>
	  <td width="31%">&nbsp;</td>
      <td align="left" width="32%" >&nbsp;</td>
      <td align="left" width="9%" >&nbsp;</td>
      <td width="28%" class="normalText">&nbsp;</td>
    </tr>

  </table>
  </td></tr>
  </table>
