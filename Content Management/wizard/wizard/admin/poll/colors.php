<?php
/*  
    Poll Colors
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
              
<form enctype='multipart/form-data' action='admin.php?id=2&item=3&sub=6' method='post'>

<!--- Interface Settings  -->
    <tr><td colspan="4">
	<table cellpadding="2" border="0" width="100%">
	<tr class="normalText" bgcolor="#f0f0f0">
	   <td style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b>&nbsp;Set Poll Properties</b></a></td>
    </tr>
	</table>
	</td></tr>

<tr><td colspan="4">
	


<?php
    
	
if($_POST['options']){
	$width = $_POST['width'];
	$border = $_POST['border'];
	$header = $_POST['header'];
	$headtext = $_POST['headtext'];
    $background = $_POST['background'];
	$strip = $_POST['strip'];
	$percent = $_POST['percent'];
	$text = $_POST['text'];
	$size = $_POST['size'];
	

	$db = new DB();
	$db->query("UPDATE ". DB_PREPEND . "poll_config SET header='$header',width='$width',border='$border',headtext='$headtext',background='$background',strip='$strip',percent='$percent',text='$text',size='$size'");
	
	
		echo("<span class=\"message\">&nbsp;&nbsp;&nbsp;Update successful.</span><br /><br />");
	
}

	
$db = new DB();
$db->query("select * from ". DB_PREPEND . "poll_config");
$settings = $db->next_record();
$width = $settings['width'];
$border = $settings['border'];
$headtext = $settings['headtext'];
$header = $settings['header'];
$background = $settings['background'];
$strip = $settings['strip'];
$percent = $settings['percent'];
$size = $settings['size'];
$text = $settings['text'];

			
?>
<form action="<? echo "admin.php?id=2&item=3&sub=6"; ?>" method="post">

<table cellspacing="1" cellpadding="4" border="0" bgcolor="#EEEEEE">
<tr><td width="130">Width of Poll: </td><td style="border-right: 10px solid <? echo $width; ?>;"><input type="text" name="width" value="<? echo $width; ?>" size="20" /></td></tr>
<tr><td width="130">Poll Border Color: </td><td style="border-right: 10px solid <? echo $border; ?>;"><input type="text" name="border" value="<? echo $border; ?>" size="20" /></td></tr>
<tr><td width="130">Header BG Color: </td><td style="border-right: 10px solid <? echo $header; ?>;"><input type="text" name="header" value="<? echo $header; ?>" size="20" /></td></tr>
<tr><td width="130">Header Text Color: </td><td style="border-right: 10px solid <? echo $headtext; ?>;"><input type="text" name="headtext" value="<? echo $headtext; ?>" size="20" /></td></tr>
<tr><td width="130">Background Color: </td><td style="border-right: 10px solid <? echo $background; ?>;"><input type="text" name="background" value="<? echo $background; ?>" size="20" /></td></tr>
<tr><td width="130">Result Strip Color: </td><td style="border-right: 10px solid <? echo $strip; ?>;"><input type="text" name="strip" value="<? echo $strip; ?>" size="20" /></td></tr>
<tr><td>Percent Color: </td><td style="border-right: 10px solid <? echo $percent; ?>;"><input type="text" name="percent" value="<? echo $percent; ?>" size="20" /></td></tr>
<tr><td>Text Color: </td><td style="border-right: 10px solid <? echo $text; ?>;"><input type="text" name="text" value="<? echo $text; ?>" size="20" /></td></tr>
<tr><td>Text Size: </td><td><input type="text" name="size" value="<? echo $size; ?>" size="20" /></td></tr>
</table>
<br /><br />

<input type="submit" name="options" value="Submit" />
</form>

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
