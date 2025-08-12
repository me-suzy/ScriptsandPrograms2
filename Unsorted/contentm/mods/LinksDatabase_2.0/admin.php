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
	define("CREATE_CATEGORY",3);	
	define("CREATE_LINK",4);
	define("ADD_CATEGORY",5);
	define("ADD_LINK",6);
	define("REMOVE_CATEGORY",7);
	define("REMOVE_LINK",8);

	// Module Class
	class Module {

		function ModExists ()
			{
			global $IW;
			if(!$IW->tableExists ("mod_linksportal")){return false;}
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
			$IW->Query("CREATE TABLE mod_linksportal (  id varchar(50) default NULL,  category varchar(50) default NULL,  title varchar(50) default NULL,  description text,  url varchar(50) default NULL)");
			$IW->Query("CREATE TABLE mod_linksportal_category (  id varchar(50) default NULL,  category_title varchar(50) default NULL,  category_desc text)");
			$GUI->Message("Module Installed.");
			$GUI->Navigate("admin.php?");
			}

		function ConfigForm ()
			{
			global $IW,$GUI;
			}

		function ConfigUpdate ()
			{
			global $IW,$GUI;
			}

		function CreateCategoryForm ()
			{
			global $IW,$GUI;
			$GUI->OpenForm("","admin.php?cmd=".ADD_CATEGORY,"");
			$GUI->OpenWidget("Create Link Category");
			echo "<table border=0>";
			echo "<tr>";
			echo "<td>".$GUI->Label("Category Title")."</td>";
			echo "<td>".$GUI->TextBox("category_title","",50)."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td colspan=2>".$GUI->Label("Category Description")."<br>".$GUI->TextArea("category_desc","",3,50)."</td>";
			echo "</tr>";
			echo "</table>";
			echo $GUI->Button("Add Category");
			$GUI->CloseWidget("");
			$GUI->CloseForm ();
			}

		function CreateLinkForm ()
			{
			global $IW,$GUI;
			$GUI->OpenForm("","admin.php?cmd=".ADD_LINK,"");
			$GUI->OpenWidget("Create Link");
			echo "<table border=0>";
			echo "<tr>";
			echo "<td>".$GUI->Label("Link Title")."</td>";
			echo "<td>".$GUI->TextBox("title","",50)."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td>".$GUI->Label("Link Category")."</td>";
			echo "<td>";
			$GUI->OpenListBox("category",1);
			$categories=$IW->Query("select * from mod_linksportal_category order by category_title");
			for($i=0;$i<$IW->CountResult($categories);$i++)
			{$GUI->ListOption($IW->Result($categories,$i,"id"),$IW->Result($categories,$i,"category_title"),0);}
			$IW->FreeResult($categories);
			$GUI->CloseListBox ();
			echo "</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td>".$GUI->Label("Link URL")."</td>";
			echo "<td>".$GUI->TextBox("url","http://",50)."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td colspan=2>".$GUI->Label("Link Description")."<br>".$GUI->TextArea("description","",3,50)."</td>";
			echo "</tr>";
			echo "</table>";
			echo $GUI->Button("Add Link");
			$GUI->CloseWidget("");
			$GUI->CloseForm ();
			}

		function ManageLinks ()
			{
			global $IW,$GUI;
			$GUI->OpenWidget("Links Manager");
			$GUI->OpenForm("","admin.php?cmd=".CREATE_CATEGORY,"");
			echo $GUI->Button("New Category");
			$GUI->CloseForm ();	
			$GUI->OpenForm("","admin.php?cmd=".CREATE_LINK,"");
			echo $GUI->Button("New Link");
			$GUI->CloseForm ();	
			echo "<table border=1 bordercolor=#c0c0c0 cellpadding=3 cellspacing=0>";
			$categories=$IW->Query("select * from mod_linksportal_category order by category_title");
			for($i=0;$i<$IW->CountResult($categories);$i++)
				{
				echo "<tr>";
				echo "<td bgcolor=#c0c0c0 colspan=2>".$GUI->Label($IW->Result($categories,$i,"category_title"))."</td>";
				$GUI->OpenForm("","admin.php?cmd=".REMOVE_CATEGORY."&id=".$IW->Result($categories,$i,"id"),"");
				echo "<td bgcolor=#c0c0c0>".$GUI->Button("Delete Category")."</td>";
				$GUI->CloseForm ();	
				echo "</tr>";
				$cat=$IW->Result($categories,$i,"id");
				$links=$IW->Query("select * from mod_linksportal where category='$cat' order by title");
				$row=0;
				for($j=0;$j<$IW->CountResult($links);$j++)
					{
					if($row==0){$color="#f5f5f5";}
					elseif($row==1){$color="#e4e4e4";}
					echo "<tr>";
					echo "<td bgcolor=$color>".$IW->Result($links,$j,"title")."</td>";
					echo "<td bgcolor=$color><a href=\"".$IW->Result($links,$j,"url")."\" target=_blank>".$IW->Result($links,$j,"url")."</a></td>";
					$GUI->OpenForm("","admin.php?cmd=".REMOVE_LINK."&id=".$IW->Result($links,$j,"id"),"");		
					echo "<td bgcolor=$color>".$GUI->Button("Delete Link")."</td>";
					$GUI->CloseForm ();	
					echo "</tr>";
					if($row==0){$row=1;}
					elseif($row==1){$row=0;}
					}
				$IW->FreeResult($links);	
				}
			$IW->FreeResult($categories);
			echo "</table></p>";
			$GUI->CloseWidget("");
			}

		function AddLinkCategory ()
			{
			global $IW,$GUI;
			global $category_title,$category_desc;
			$id=md5(uniqid(rand(),1));
			$IW->Query("insert into mod_linksportal_category (id,category_title,category_desc) values ('$id','$category_title','$category_desc')");
			$GUI->Message("Link Category Added");
			$GUI->Navigate("admin.php?");
			}

		function AddLink ()
			{
			global $IW,$GUI;
			global $category,$title,$description,$url;
			$id=md5(uniqid(rand(),1));
			$IW->Query("insert into mod_linksportal (id,category,title,description,url) values ('$id','$category','$title','$description','$url')");
			$GUI->Message("Link Added");
			$GUI->Navigate("admin.php?");
			}

		function DeleteLinkCategory ()
			{
			global $IW,$GUI;
			global $id;
			$IW->Query("delete from mod_linksportal_category where id='$id' ");
			$IW->Query("delete from mod_linksportal where category='$id' ");
			$GUI->Message("Link Category Deleted");
			$GUI->Navigate("admin.php?");
			}

		function DeleteLink ()
			{
			global $IW,$GUI;
			global $id;
			$IW->Query("delete from mod_linksportal where id='$id' ");
			$GUI->Message("Link Deleted");
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
			$MOD->ManageLinks ();
		break;
		case UPDATE:
			$MOD->ConfigUpdate ();
		break;
		case CREATE_CATEGORY:
			$MOD->CreateCategoryForm ();
		break;
		case CREATE_LINK:
			$MOD->CreateLinkForm ();
		break;
		case ADD_CATEGORY:
			$MOD->AddLinkCategory ();
		break;
		case ADD_LINK:
			$MOD->AddLink ();
		break;
		case REMOVE_CATEGORY:
			$MOD->DeleteLinkCategory ();
		break;
		case REMOVE_LINK:
			$MOD->DeleteLink ();
		break;
		}
?>
<?php include "../../admin/author.php"; ?>
</body>
</html>