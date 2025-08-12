<?php
/*  
   New Poll
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


$message = $_GET['message'];
?>


 

<!-- Inner table -->
<table border="1"  bgcolor="#f8f8ff" cellpadding="5" style="border-collapse: collapse"   bordercolor="#E5E5E5" width="100%">
<tr><td>
<table border="0"  bgcolor="#f8f8ff" cellpadding="5" style="border-collapse: collapse"   bordercolor="#E5E5E5" width="100%">
              
<form enctype='multipart/form-data' action='admin.php?id=2&item=3&sub=5' method='post'>

<!--- Interface Settings  -->
    <tr><td colspan="4">
	<table cellpadding="2" border="0" width="100%">
	<tr class="normalText" bgcolor="#f0f0f0">
	   <td style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b>&nbsp;Set Question and Number of Answers</b></a></td>
    </tr>
	</table>
	</td></tr>

<tr><td colspan="4">
	


<?php
    
	
if($_POST['step1']){
	
	
	?><form action="admin.php?id=2&item=3&sub=5" method="post">
	<table width="80%" align="center" cellspacing="1" cellpadding="4" border="0" bgcolor="#f2f2f2"><tr>
	<td width="130">
	Question: 
	</td>
	<td><input type="text" name="question" size="40" value="<? echo $_POST['question']; ?>" />
	</td></tr>
	<tr><td colspan="2"><hr /></td></tr>
	<?
	
	$i = 1;
	while ($i <= $_POST['options']) {
		
		
			
  		echo(" <tr><td>Question $i</td><td><input type='text' name='field[$i]' size='60' value='' /></td></tr> "); 
  		
  		
   		$i++;
   
	}

	echo('</tr></table><br /><input type="submit" name="step2" value="Submit!" /></form>');
	
	
	
}elseif($_POST['step2']){
	
	$question = addslashes($_POST['question']);
	
	
	//delete all previous poll data 
	$db = new DB();
	$db->query(" TRUNCATE `". DB_PREPEND . "poll_answers`");
	$db->query(" TRUNCATE `". DB_PREPEND . "poll_question`");
	$db->query(" TRUNCATE `". DB_PREPEND . "poll_ip`");
	
	
	//insert new poll info
	$db->query("INSERT INTO ". DB_PREPEND . "poll_question (id, question, totalvotes)" . "VALUES ('NULL', '$question', '0')");
	
	$field = $_POST['field'];
	
	//for each option
	foreach ($field as $value) {
						
		
		//add it to the database
		$db->query("INSERT INTO ". DB_PREPEND . "poll_answers (id, field, votes)" . "VALUES ('NULL', '$value', '0')"); 
		
	
}

	echo("<span class=\"message\"><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The new poll has been created.</span><br />");
	
	
	
}else{


	//our first form
	?>
	
	<form action="admin.php?id=2&item=3&sub=5" method="post">
	<table cellspacing="1" cellpadding="4" border="0" bgcolor="#f2f2f2"><tr>
	<td width="130">
	Question:
	</td>
	<td width="300">
	<input type="text" name="question" size="40" />
	</td></tr><tr>
	<td>
	Number of Options: 
	</td>
	<td>
	<input type="text" name="options" size="1" value="4" />
	</td></tr></table><br /><br />
	<input type="submit" name="step1" value="Continue!" />
	</form>
	<?



}

?>
</td></tr>	
	
	
	<tr>
	  <td width="31%">&nbsp;</td>
      <td align="left" width="32%" >&nbsp;</td>
      <td align="left" width="9%" >&nbsp;</td>
      <td width="28%" class="normalText">&nbsp;</td>
    </tr>

  </table>
  </td></tr>
  </table>
