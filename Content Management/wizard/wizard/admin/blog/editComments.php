<?php
/*  
   	Edit Comments
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


$colname = $_GET['colname'];
$colvalue = $_GET[$colname];

if (!isset($colname) && !isset($colvalue) ) {
    $message = "Please select a comment from the Comments List for editing.";
   $location = CMS_WWW . "/admin.php?id=2&item=90&sub=91";
	header("Location: $location");
	exit;
}


$db = new DB(); 
$db->query("SELECT * FROM ". DB_PREPEND . "comments WHERE id='$colvalue'");
$comment = $db->next_record();

$message = $_GET['message'];
?><!-- Inner table -->
<div id="pagelinks">
<table border="1"  cellpadding="0" style="border-collapse: collapse"   bordercolor="#E5E5E5" width="100%">
<tr><td>
<table border="0"  bgcolor="#f8f8ff" cellpadding="5" style="border-collapse: collapse"   bordercolor="#E5E5E5" width="100%">
           
<form enctype='multipart/form-data' action='admin/blog/editCommentsPro.php' method='post'>

 <?php
 if ($message) {   
	echo "<tr>";
  		echo "<td colspan=\"4\"><span class=\"message\">$message</span></td>";
	echo "</tr>";
 }
 ?>  

 	<?php
				echo "<tr>";
			        echo "<td colspan=\"4\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Delete comment?&nbsp;&nbsp;<a href=\"admin/blog/commentDelete.php?uid=" . $comment['id'] . "\"><img alt=\"Delete Comment\" border=\"0\" src=\"admin/images/del.gif\" height=\"11\" width=\"11\">&nbsp; Delete</a></td>";
			echo "</tr>";
		
	
	
	?>
 
 
<!--- User Identidy  -->
    <tr><td colspan="4">
	<table style="border-collapse: collapse" cellpadding="2" border="0" border-color="c0c0c0" width="100%">
	<tr class="normalText" bgcolor="#f0f0f0">
	   <td colspan="4" style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b>&nbsp;Comment</b></a></td>
    </tr>
	</table>
	</td></tr>
	
	<tr class="normalText"> 
	  <td width="24%">&nbsp;</td>
      <td align="left" width="22%" >Record Id</td>
      <td align="left" width="27%" ><?php echo $comment['id'] ?> 
      </td>
      <td width="32%">&nbsp;</td>
    </tr>
	
	<tr class="normalText"> 
	  <td width="24%">&nbsp;</td>
      <td align="left" width="22%" >Article Id</td>
      <td align="left" width="27%" > <input type="text" name="article_id" value="<?php echo $comment['article_id'] ?>" size="6" > 
      &nbsp;</td>
      <td width="32%">&nbsp;</td>
    </tr>

	<tr class="normalText"> 
	  <td width="24%">&nbsp;</td>
      <td align="left" width="22%" >Article Title</td>
      <td align="left" width="27%" class="colorNormalText" ><?php echo $comment['title'] ?> 
      </td>
      <td width="32%">&nbsp;</td>
    </tr>

	
	<tr class="normalText"> 
	  <td width="24%">&nbsp;</td>
      <td align="left" width="22%" >Server File Name</td>
      <td align="left" width="27%" ><?php echo $comment['page'] ?>
      </td>
      <td width="32%">&nbsp;</td>
    </tr>
	
	<tr class="normalText"> 
	  <td width="24%">&nbsp;</td>
      <td align="left" width="22%" >Subject</td>
      <td align="left" width="27%" > <input type="text" name="subject" value="<?php echo $comment['subject'] ?>" size="30" > 
      </td>
      <td width="32%">&nbsp;</td>
    </tr>
	
	<tr class="normalText"> 
	  <td width="24%">&nbsp;</td>
      <td align="left" width="22%" >Username</td>
      <td align="left" width="27%" ><?php echo $comment['username'] ?> 
      </td>
      <td width="32%">&nbsp;</td>
    </tr>
	
	<tr class="normalText"> 
	  <td width="24%">&nbsp;</td>
      <td align="left" width="22%" >IP</td>
      <td align="left" width="27%" ><?php echo $comment['ip'] ?> 
      </td>
      <td width="32%">&nbsp;</td>
    </tr>
	
	<tr class="normalText"> 
	  <td width="24%">&nbsp;</td>
      <td align="left" width="22%" >Contact</td>
      <td align="left" width="27%" ><?php echo $comment['contact'] ?> 
      </td>
      <td width="32%">&nbsp;</td>
    </tr>
 
	
	<tr class="normalText"> 
	  <td width="24%">&nbsp;</td>
      <td valign="top" align="left" width="22%" >Comment</td>
      <td align="left" width="27%" ><textarea name='comment' rows=10 cols=80><? echo $comment['comment']; ?></textarea> 
      </td>
      <td width="32%">&nbsp;</td>
    </tr>

	<tr class="normalText"> 
	  <td width="24%">&nbsp;</td>
      <td align="left" width="22%" >Date</td>
      <td align="left" width="27%" ><?php echo $comment['date'] ?> 
      </td>
      <td width="32%">&nbsp;</td>
    </tr>
	
	<tr>
	  <td width="24%">&nbsp;</td>
      <td align="left" width="22%" >&nbsp;</td>
      <td align="left" width="27%" >&nbsp;</td>
      <td width="32%">&nbsp;</td>
    </tr>
	
		
    <tr bgcolor="#f0f0f0">
	   <input type="hidden" name="id" value="<?php echo $comment[id]; ?>">
       <td background="admin/images/bluebarBg.gif" class="normalText" colspan="4"><center><input type="submit" name="Submit" value="Save"></center></td>
    </tr>
	</form>
  </table>
  </td></tr>
  </table>
</div>


