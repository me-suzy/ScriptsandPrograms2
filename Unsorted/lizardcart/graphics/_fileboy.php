<?php
/* FileBoy v 0.1 - plugin for HTMLeditbox v 1.0
*  released 02/28/2002
*  Copyright (c) 2002 Josef Zirnsak <josef@labs4.com>
*  Website: http://www.labs4.com
*
*  script based on QTOFileManagerLite 1.0
*  Copyright (C) 2001 Quentin O'Sullivan <quentin@qto.com>
*  Web Site: http://www.qto.com/fm
*
*  This program is free software; you can redistribute it and/or
*  modify it under the terms of the GNU General Public License
*  as published by the Free Software Foundation; either version 2
*  of the License, or (at your option) any later version.
*
*  This program is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  You should have received a copy of the GNU General Public License
*  along with this program; if not, write to the Free Software
*  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
************************************************************************
*/
include ("../admin/config.inc.php");
  
// set these configuration variables
$user = "";  // change this to the username you would like to use. leave it empty if you dont want to use authentication
$pass = "";
$MaxFileSize = "1000000"; // max file size in bytes
$HDDSpace = "100000000"; // max total size of all files in directory
$HiddenFiles = array(".htaccess","_fileboy.php"); // add any file names to this array which should remain invisible
$EditOn = 1; // make this = 0 if you dont want the to use the edit function at all
$EditExtensions = array("htm","html","txt","php"); // add the extensions of file types that you would like to be able to edit

$odd_row = "#FFFFFF";	// color of od row in filelist
$even_row = "FFF3BD";	// color of even row in filelist

/********************************************************************/

$copyrite = "<span style='font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9.9px; letter-spacing: 1px; background: #FFFFFF; color: #0059D3;'>File Editor 0.1 - add-on for WYSIWYG Editor &nbsp&nbsp&nbsp&copy; 2002 <a href='http://www.2tonewebdesign.com' target='_blank'>2tone Web Design</a> - All Rights Reserved</span>";
$cc=0;

$ThisFileName = basename(__FILE__); // get the file name
$path = str_replace($ThisFileName,"",__FILE__);   // get the directory path

if($login)
{
	if(!($u == $user && $password == $pass))
	{
		$msg = "<font face='Verdana, Arial, Hevetica' size='4' color='#CE0000'>FAILED!</font>";
		$loginfailed = 1;
	}
}


if(($user == $u || $user == "") && $edit) // if an edit link was clicked
{
	$fp = fopen($path.$edit, "r");
	$oldcontent = fread($fp, filesize($path.$edit));
	fclose($fp);
	
	// code butcher
		// split head from body --- aaah ya killaa
		$headtag = strstr($oldcontent,"<head>");	// removed stuff before head
		$headend = strpos($headtag,"</head>");		// locate end of the head
		$headtag = substr($headtag,6,$headend-6);		// cut out body
		
	// create body tag
		$bodytag = strstr($oldcontent,"<body");	// removed stuff before head
		$bodyend = strpos($bodytag,"</body>");		// locate end of the head
		$bodytag = substr($bodytag,0,$bodyend+7);		// cut out body
		


$filemanager = <<<content
<center>
	<table border='0' cellspacing='0' cellpadding='1' bgcolor='#F5F5F5' style="border: 1px solid black; width: 600px;">
		<tr style="background: #0059D3; color: #F5F5F5;">
			<td align=center style="border-bottom: 1px solid black;"><span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10.9px; letter-spacing: 2px;"><font color="#FFD200"><b>LABS4.COM</b></font> - FILE MANAGEMENT (FLAT STRUCTURE)</span>
			</td>
		</tr>
		<tr>
			<td style="padding: 5px;">
				<font face="Verdana, Arial, Helvetica" size="4" color="#333333"><b>$edit</b></font>
				<br>
				<form name="fileman" method="post" action="$PHP_SELF" style="margin: 0;">
					<span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10.9px; letter-spacing: 2px;">head:</span>
					<br>    
					<textarea name="newhead" cols="70" rows="8">$headtag</textarea><br>
					<span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10.9px; letter-spacing: 2px;">body:</span>
					<br>    
					<textarea name="newbody" cols="70" rows="15">$bodytag</textarea><br>
					<a href="#" onClick="window.open('_editor.php?formname=fileman&inputname=newbody','editor_popup','width=750,height=570,scrollbars=yes,resizable=yes,status=yes'); return false"><FONT style="font: 11.9px verdana; color: #CE0000;">open body in external WYSIWYG editor</font></a>
					<br>
  					<br>
					<input type="submit" name="save" value="save file" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11.9px; letter-spacing: 1px; background: #ffffff; color: #003782;">&nbsp&nbsp;
					<input type="submit" name="cancel" value="cancel" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11.9px; letter-spacing: 1px; background: #FFFFFF; color: #CE0000;\"><br><br>
					<span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9.9px;"><b>Limitations:</b> do not edit &lt;body&gt; tag, if you want setup background color or something do it by CSS style in head tag!<br>WYSIWYG editor cuts body tag out ...</span>
					<input type="hidden" name="u" value="$u">
					<input type="hidden" name="savefile" value="$edit">
				</form>
			</td>
		</tr>
	</table>
$copyrite
</center>
content;
	
}
elseif(($user == $u || $user == "") && !$loginfailed)
{
		
	if($save) // if the save button was pressed on the edit screen
	{
		// construct new file
		$newcontent = "<html>\n <head>".stripslashes($newhead)."</head>";
			if(strpos($newbody,"<body")=== false) {
				$newcontent .= "<body>\n";
			}
		$newcontent .= "".stripslashes($newbody)."\n";
			if(!strpos($newbody,"</body>")) {
				$newcontent .= "</body>\n";
			}		
		$newcontent .= "</html>\n";
		$fp = fopen($path.$savefile, "w");
		fwrite($fp, $newcontent);
		fclose($fp);
	}

	$HDDTotal = dirsize($path); // get the total size of all files in the directory including any sub directorys

	if ($upload) // if the upload button was pressed
	{
		if($HTTP_POST_FILES['uploadedfile']['name']) // if a file was actually uploaded 
		{
			$HTTP_POST_FILES['uploadedfile']['name'] = str_replace("%","",$HTTP_POST_FILES['uploadedfile']['name']);  // remove any % signs from the file name
			// if the file size is within allowed limits
			if($HTTP_POST_FILES['uploadedfile']['size'] > 0 && $HTTP_POST_FILES['uploadedfile']['size'] < $MaxFileSize)
			{
				// if adding the file will not exceed the maximum allowed total
				if(($HDDTotal + $HTTP_POST_FILES['uploadedfile']['size']) < $HDDSpace)
				{
					// put the file in the directory
					move_uploaded_file($HTTP_POST_FILES['uploadedfile']['tmp_name'], $path.$HTTP_POST_FILES['uploadedfile']['name']);	
				}
				else
				{
			 		$msg = "<font face='Verdana, Arial, Hevetica' size='2' color='#ff0000'>There is not enough free space and the file could<br>not be uploaded.</font><br>";
				}
			}
			else
			{
				$MaxKB = $MaxFileSize/1000; // show the max file size in Kb
				$msg =  "<font face='Verdana, Arial, Hevetica' size='2' color='#ff0000'>The file was greater than the maximum allowed<br>file size of $MaxKB Kb and could not be uploaded.</font><br>";
			}
		}
		else
		{
			$msg =  "<font face='Verdana, Arial, Hevetica' size='2' color='#ff0000'>Please press the browse button and select a file<br>to upload before you press the upload button.</font><br>";
		}
	}
	elseif($delete) // if the delete button was pressed
	{
		// delete the file
		unlink($path.$delete);
	}

	$HDDTotal = dirsize($path); // get the total size of all files in the directory including any sub directorys
	$freespace = ($HDDSpace - $HDDTotal)/1000; // work out how much free space is left
	$HDDTotal = (int) ($HDDTotal/1000); // convert to Kb instead of bytes and type cast it as an int
	$freespace = (int) $freespace; // type cast as an int
	$HDDSpace = (int) ($HDDSpace/1000); // convert to Kb instead of bytes and type cast it as an int
	$MaxFileSizeKb = (int) ($MaxFileSize/1000); // convert to Kb instead of bytes and type cast it as an int

	// build the html that makes up the file manager
	// the $filemanager variable holds the first part of the html
	// including the form tags and the top 2 heading rows of the table which
	// dont display files
	$filemanager = <<<content
	<center>
	<table border='0' cellspacing='0' cellpadding='1' bgcolor='#F5F5F5' style="border: 1px solid black; width: 600px;">
	<tr style="background: #0059D3; color: #F5F5F5;">
		<td align=center style="border-bottom: 1px solid black;"><span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10.9px; letter-spacing: 2px;"><font color="#FFD200"><b>2tone Web Design</b></font> - FILE MANAGEMENT (FLAT STRUCTURE)</span></td>
	</tr>
	<tr>
		<td style="padding: 5px;">
	<span style="font-family: Verdana, Helvetica, sans-serif; font-size: 13.9px; letter-spacing: 1px; color: #CE0000;">$msg</span>
	<span style="font-family: Arial, Verdana, Helvetica, sans-serif; font-size: 10.9px; letter-spacing: 1px;"><b>> webhosting stats:</b><br>
	<font color="#CE0000"><b>Total Space:</b></font> $HDDSpace Kb&nbsp; | &nbsp;<font color="#CE0000"><b>Free Space:</b></font> $freespace Kb&nbsp; | &nbsp;<font color="#CE0000"><b>Used Space:</b></font> $HDDTotal Kb</span><br>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFF3BD">
	<tr> 
	<td height="20" bgcolor="#333333"></td>
	<td bgcolor="#333333" height="20"><font face="Verdana, Arial, Helvetica" size="2" color="#FFFFFF"><b>&nbsp;FILENAME&nbsp;</b></font></td>
	<td height="20" bgcolor="#333333"><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica"><b>&nbsp;SIZE (bytes)&nbsp;</b></font></td>
	<td height="20" bgcolor="#333333"></td>
	<td height="20" bgcolor="#333333"></td>
	</tr>
	<tr> 
	<td height="2" bgcolor="#999999" colspan=5></td>
	</tr>
content;

	// build the table rows which contain the file information
	$newpath = substr($path, 0, -1);   // remove the forward or backwards slash from the path
	$dir = @opendir($newpath); // open the directory
	while($file = readdir($dir)) // loop once for each name in the directory
	{
		// if the name is not a directory and the name is not the name of this program file
		if(is_file($path.$file) && $file != "$ThisFileName")
		{
			$match = 0;
			foreach($HiddenFiles as $name) // for each value in the hidden files array
			{
				if($file == $name) // check the name is not the same as the hidden file name
				{	
					$match = 1;	 // set a flag if this name is supposed to be hidden
				}
			}	
			if(!$match) // if there were no matches the file should not be hidden
			{	
					$filedata = stat($file); // get some info about the file
					
					// find out if the file is one that can be edited
					$editlink = "";
					if($EditOn)  // if the edit function is turned on
					{
						$dotpos = strrpos($file, ".");
						foreach($EditExtensions as $editext)
						{
							$ext = substr($file, ($dotpos+1));
							if(strcmp($ext, $editext) == 0)
							{
								$editlink = 	"&nbsp;<a href='$PHP_SELF?edit=$file&u=$u'><font face='Verdana, Arial, Helvetica' size='2' color='#CE0000'>EDIT</font></a>&nbsp;";
							}
						}
					}
					
					$cc++;
					$cell_color = $odd_row;
					$cc % 2  ? 0 : $cell_color = $even_row;
					
					// append 2 table rows to the $content variable, the first row has the file
					// informtation, the 2nd row makes a black line 1 pixel high
					$content .= <<<content
					<tr bgcolor=$cell_color>
					<td></td>
					<td>&nbsp;<font face="Verdana, Arial, Helvetica" size="2"><img src="$file" border="1" alt="">&nbsp;&nbsp;&nbsp;<b>$file</b></font>&nbsp;</td>
					<td align=center>&nbsp;<font face="Verdana, Arial, Helvetica" size="2">$filedata[7]</font>&nbsp;</td>
					<td align=center><a href='$PHP_SELF?delete=$file&u=$u' onClick='event.returnValue=conf();'><font face="Verdana, Arial, Helvetica" size="2" color="#CE0000">DELETE</font></a>&nbsp;</td>
					<td align=center>$editlink</td>
					</tr>
					<tr> 
					<td height="1" bgcolor="#000000" colspan=5></td>
					</tr>
content;
			}
		}
	}
	closedir($dir); // now that all the rows have been built close the directory
	
	$content .= "</td></tr>";
	
	$content .= "<tr><td height=\"1\" bgcolor=\"#FFFFFF\" colspan=5></td></tr>";
	$content .= "<form name=\"form\" method=\"post\" action=\"".$PHP_SELF."\" enctype=\"multipart/form-data\" style=\"margin: 0;\">";
	$content .= "<tr bgcolor=#F5F5F5><td colspan=5 style=\"padding: 5px;\"><span style=\"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11.9px; letter-spacing: 1px;\">Upload file: </span>";
	$content .= "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"".$MaxFileSize."\">";
	$content .= "<input type=\"file\" name=\"uploadedfile\" style=\"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10.9px; letter-spacing: 1px; background: #FFFFFF; color: #003379; width: 220px;\">&nbsp;";
	$content .= "<input type=\"submit\" name=\"upload\" value=\"upload\" style=\"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10.9px; letter-spacing: 1px; background: #0059D3; color: #FFFFFF;\">&nbsp&nbsp&nbsp;";
	$content .= "<span style=\"font-family: Arial, Verdana, Helvetica, sans-serif; font-size: 10.9px; letter-spacing: 1px;\">(<font color=\"#CE0000\"><b>Max File Size:</b></font> ".$MaxFileSize." Kb)</span>";
	$content .= "<input type=\"hidden\" name=\"u\" value=\"".$u."\">";
	$content .= "</form>";
	$content .= "</td></tr>";

	$content .= "</table></table>";
	$content .= $copyrite;
	$content .= "</center>";
	$filemanager  .= $content; // append the html to the $filemanager variable

}
else 
{
	$filemanager = <<<content
	<center>
	<table border='0' cellspacing='0' cellpadding='1' bgcolor='#F5F5F5' style="border: 1px solid black; width: 500px;">
	<tr style="background: #0059D3; color: #F5F5F5;">
		<td align=center style="border-bottom: 1px solid black;"><span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10.9px; letter-spacing: 2px;"><font color="#FFD200"><b>2tone Web Design</b></font> - FILE MANAGEMENT</span></td>
	</tr>
	<tr>
		<td style="padding: 5px;">
		<form name="login" method="post" action="$PHP_SELF" style="margin: 0;">
		<font face="Verdana, Arial, Hevetica" size="4" color="#333333"><b>AUTHORIZATION</b></font>&nbsp;$msg
	<center>
	<table border='0' cellspacing='0' cellpadding='1'>
		<tr>
			<td>
				<span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11.9px; letter-spacing: 1px;">username:</span>
			</td>
			<td>
				<input type="text" name="u" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11.9px;">
			</td>
		</tr>
		<tr>
			<td>
			  	<span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11.9px; letter-spacing: 1px;">password:</span>
			</td>
			<td>
				<input type="password" name="password" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11.9px;">
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input type="submit" name="login" value="login &gt;&gt;" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11.9px; letter-spacing: 1px; background: #FFFFFF; color: #0059D3;">
			</td>
		</tr>
	</table>
  	</center>
	</form>
	</td>
	</tr>
	</table>
	$copyrite
	</center>
content;
}

function dirsize($dir) 
// calculate the size of files in $dir
{
	$dh = opendir($dir);
	$size = 0;
	while (($file = readdir($dh)) !== false)
	{
		if ($file != "." and $file != "..") 
		{
			$path = $dir.$file;
			if (is_file($path))
			{
				$size += filesize($path);
			}
		}
	}
	closedir($dh);
	return $size;
}

?>


<html>
	<head>
		<title>File Editor 0.1</title>
		
		<script language="JavaScript">
		<!--
			function conf()
			{
				return confirm("Do you really want delete this file?"); 
			}
		-->
		</script>
	</head>
	<body bgcolor="#FFFFFF">
	<?php echo $filemanager ?>
	</body>
</html>


