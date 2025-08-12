<?php
	header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Pragma: no-cache");	
	
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
	
	@import_request_variables('cgps');
	$PHP_SELF=$_SERVER['PHP_SELF'];
	if(!isset($S)){$S=0;}
	$ModLoader=1;
	include "../../admin/iware.php";
	$IW = new IWARE ();	
	$IW->maybeOpenLogInWindow();

	// Include Configured Language Definition
	$lang = IWARE_LANG;
	if(!file_exists($lang)){$lang="US_ENGLISH";}
	include $lang;

	// Program Command definitions
	define("INSTALL",0);
	define("STARTUP",1);
	define("UPDATE",2);
	define("CREATE",3);	
	define("ADD",4);
	define("REMOVE",5);

	// Module Class
	class Module {

		function ModExists ()
			{
			global $IW;
			if(!$IW->tableExists ("mod_imagegallery_config")){return false;}
			else{return true;}
			}

		function ModInstallForm ()
			{
			global $IW,$GUI;
			$GUI->OpenWidget("Module Load Error :");
			$GUI->OpenForm ("","admin.php?cmd=".INSTALL,"");
			echo "This module has not been installed.<br /><br />";
			echo $GUI->Button("Install Now");
			$GUI->CloseForm ();
			$GUI->CloseWidget();
			echo "</body>\n</html>\n";
			exit;
			}

		function ModInstall ()
			{
			global $IW,$GUI;
			$IW->Query("CREATE TABLE mod_imagegallery (  id varchar(50) default NULL,  filename varchar(50) default NULL,  caption text)");
			$IW->Query("CREATE TABLE mod_imagegallery_config (  img_per_page int(3) default NULL,  img_per_row int(3) default NULL,  show_size int(3) default NULL,  show_type int(3) default NULL,  show_caption int(3) default NULL,  use_thumbs int(3) default NULL,  thumbs_scale int(3) default NULL,  use_zoom int(3) default NULL)");
			$IW->Query("INSERT INTO mod_imagegallery_config VALUES('4', '2', '1', '1', '1', '1', '2', '1')");
			$GUI->Message("Module Installed.");
			$GUI->Navigate("admin.php?");
			}

		function ConfigForm ()
			{
			global $IW,$GUI;
			$result=$IW->Query("select * from mod_imagegallery_config limit 1");
			$GUI->OpenForm("Settings","admin.php?cmd=".UPDATE,"return vSettings ()");
			$GUI->OpenWidget("Image Gallery Settings");
			echo "<table border=0>";	
			echo "<tr><td>".$GUI->CheckBox("show_size",1,$IW->Result($result,0,"show_size"));
			echo "</td><td>".$GUI->Label("Show Image Sizes")."</td></tr>";
			echo "<tr><td>".$GUI->CheckBox("show_type",1,$IW->Result($result,0,"show_type"));
			echo "</td><td>".$GUI->Label("Show Image Types")."</td></tr>";
			echo "<tr><td>".$GUI->CheckBox("show_caption",1,$IW->Result($result,0,"show_caption"));
			echo "</td><td>".$GUI->Label("Show Image Captions")."</td></tr>";
			echo "<tr><td>".$GUI->CheckBox("use_zoom",1,$IW->Result($result,0,"use_zoom"));
			echo "</td><td>".$GUI->Label("Enable Click to Enlarge")."</td></tr>";
			echo "<tr><td>".$GUI->CheckBox("use_thumbs",1,$IW->Result($result,0,"use_thumbs"));
			echo "</td><td>".$GUI->Label("Enable Thumbnails at 1/").$GUI->TextBox("thumbs_scale",$IW->Result($result,0,"thumbs_scale"),3).$GUI->Label("of Original")."</td></tr>";
			echo "<tr><td>".$GUI->TextBox("img_per_page",$IW->Result($result,0,"img_per_page"),3);
			echo "</td><td>".$GUI->Label("Images Displayed Per Page")."</td></tr>";
			echo "<tr><td>".$GUI->TextBox("img_per_row",$IW->Result($result,0,"img_per_row"),3);
			echo "</td><td>".$GUI->Label("Images Displayed Per Row")."</td></tr>";
			echo "</table>";
			echo $GUI->Button("Save Settings");
			$GUI->CloseWidget("");
			$GUI->CloseForm();
			$IW->FreeResult($result);
			}

		function ConfigUpdate ()
			{
			global $IW,$GUI;
			global $show_size,$show_type,$show_caption,$use_zoom,$use_thumbs;
			global $thumbs_scale,$img_per_page,$img_per_row;
			if(!isset($show_size)){$show_size=0;}
			if(!isset($show_type)){$show_type=0;}
			if(!isset($show_caption)){$show_caption=0;}
			if(!isset($use_zoom)){$use_zoom=0;}
			if(!isset($use_thumbs)){$use_thumbs=0;}
			$IW->Query("update mod_imagegallery_config set show_size='$show_size',show_type='$show_type',show_caption='$show_caption',use_zoom='$use_zoom',use_thumbs='$use_thumbs',thumbs_scale='$thumbs_scale',img_per_page='$img_per_page',img_per_row='$img_per_row' ");
			$GUI->Message("Configuration Saved");
			$GUI->Navigate("admin.php?");			
			}

		function ManageImages ()
			{
			global $IW,$GUI;
			$result=$IW->Query("select * from mod_imagegallery");
			$GUI->OpenWidget("Manage Gallery Images");
			$count=$IW->CountResult($result);
			echo "<center><i>There are currently $count images assigned to the gallery.</i></center>";
			$GUI->OpenForm("","admin.php?cmd=".CREATE,"");
			echo $GUI->Button("Add Image");
			$GUI->CloseForm();
			echo "<table border=0 cellpadding=3 cellspacing=0>";
			$row=0;
			for($i=0;$i<$count;$i++)
				{
				if($row==0){$color="#f5f5f5";}
				elseif($row==1){$color="#e4e4e4";}
				echo "<tr>";
				echo "<td bgcolor=$color><b>".$IW->Result($result,$i,"filename")."</b><br /><font size=1>".$IW->Result($result,$i,"caption")."</font></td>";
				$GUI->OpenForm ("dForm","admin.php?cmd=".REMOVE."&id=".$IW->Result($result,$i,"id"),"return cDel ()");
				echo "<td bgcolor=$color>".$GUI->Button("Remove Image")."</td>";
				$GUI->CloseForm ();
				echo "</tr>";
				if($row==0){$row=1;}
				elseif($row==1){$row=0;}
				}
			echo "</table>";
			$GUI->CloseWidget("");
			$IW->FreeResult($result);
			}
		
		function AddImageForm ()
			{
			global $GUI;
			$GUI->OpenForm("Add","admin.php?cmd=".ADD,"");
			$GUI->OpenWidget("Add Image to Gallery");
			echo "<table border=0>";	
			echo "<tr><td>".$GUI->Label("Image Filename");
			echo "</td><td>";
			$GUI->OpenListBox ("filename",1);
			if ($handle = opendir('../../files/')) 
				{
				while (false !== ($file = readdir($handle))){if ($file != "." && $file != ".."){$GUI->ListOption("$file","$file -- ".filesize("../../files/$file")." bytes");}}
				closedir($handle); 
				}
			$GUI->CloseListBox ();
			echo "</td></tr>";
			echo "<tr><td>".$GUI->Label("Caption");
			echo "</td><td>".$GUI->TextArea("caption","",3,40)."</td></tr>";
			echo "</table>";
			echo $GUI->Button("Add To Gallery");
			$GUI->CloseWidget("");
			$GUI->CloseForm();
			}

		function AddImage ()
			{
			global $IW,$GUI;
			global $filename,$caption;
			$caption=str_replace("'","",$caption);
			$id=md5(uniqid(rand(),1)); 
			$IW->Query("insert into mod_imagegallery (id,filename,caption) values ('$id','$filename','$caption')");
			$GUI->Message("Image Added To Gallery");
			$GUI->Navigate("admin.php?");
			}

		function DeleteImage ()
			{
			global $IW,$GUI;
			global $id;
			$IW->Query("delete from mod_imagegallery where id='$id' limit 1");
			$GUI->Message("Image Deleted From Gallery");
			$GUI->Navigate("admin.php?");
			}

	// end class
	}

	// Instantiate Module Class
	$MOD = new Module ();

?>
<html>
<head>
<title>iWare Professional Version <?php echo IWARE_VERSION; ?></title>
<link rel="stylesheet" href="../../admin/iware.css"></link>
<script language=JavaScript>
function isNumber (x)
	{
	var anum=/(^\d+$)|(^\d+\.\d+$)/
	if (anum.test(x))
		return true;
	else 
		return false;
	}	
	function vSettings ()
		{
		if(!isNumber(document.Settings.thumbs_scale.value))
			{alert('Thumbnail scaling must be a number');return false;}
		if(!isNumber(document.Settings.img_per_page.value))
			{alert('Images Per Page must be a number');return false;}
		if(!isNumber(document.Settings.img_per_row.value))
			{alert('Images Per Row must be a number');return false;}
		return true;
		}
	function cDel ()
		{
		if(window.confirm('Delete this image file from the gallery ?')){return true;}
		else{return false;}
		}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
</head>
<?php $GUI->PageBody (1); ?>
<?php


	// Command processing
	if(!isset($cmd)){$cmd=STARTUP;}
	switch($cmd)
		{
		case INSTALL:
			$MOD->ModInstall ();
		break;		
		case STARTUP:
			if(!$MOD->ModExists ()){$MOD->ModInstallForm ();}
			$MOD->ConfigForm ();
			$MOD->ManageImages ();
		break;
		case UPDATE:
			$MOD->ConfigUpdate ();
		break;
		case CREATE:
			$MOD->AddImageForm ();
		break;
		case ADD:
			$MOD->AddImage ();
		break;
		case REMOVE:
			$MOD->DeleteImage ();
		break;
		}
?>
<?php include "../../admin/author.php"; ?>
</body>
</html>