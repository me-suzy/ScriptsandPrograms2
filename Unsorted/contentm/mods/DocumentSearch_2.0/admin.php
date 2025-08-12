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

	// Module Class
	class Module {

		function ModExists ()
			{
			global $IW;
			if(!$IW->tableExists ("mod_docsearch_config")){return false;}
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
			$IW->Query("create table mod_docsearch_config (per_page int (4),search_links int (2), search_meta int (2), search_content int (2))");
			$IW->Query("insert into mod_docsearch_config (per_page,search_links,search_meta,search_content)values ('10','0','1','1')");
			$GUI->Message("Module Installed.");
			$GUI->Navigate("admin.php?");
			}

		function ConfigForm ()
			{
			global $IW,$GUI;
			$GUI->OpenWidget("DocSearch Configuration");
			$GUI->OpenForm ("","admin.php?cmd=".UPDATE,"");
			$result=$IW->Query("select * from mod_docsearch_config limit 1");
			echo "<table border=0><tr><td>";
			echo $GUI->Label("Displayed search results per page")." ".$GUI->TextBox("per_page",$IW->Result($result,0,"per_page"),5) . "<br />";
			echo $GUI->CheckBox("search_links",1,($IW->Result($result,0,"search_links")==1)?1:0);
			echo $GUI->Label("Include link text of documents in searches") . "<br />";
			echo $GUI->CheckBox("search_meta",1,($IW->Result($result,0,"search_meta")==1)?1:0);
			echo $GUI->Label("Include META information from documents in searches") . "<br />";
			echo $GUI->CheckBox("search_content",1,($IW->Result($result,0,"search_content")==1)?1:0);
			echo $GUI->Label("Include content of documents in searches") . "<br />";
			echo "</td></tr></table><br />";
			echo $GUI->Button("Save Changes");
			$GUI->CloseForm ();
			$GUI->CloseWidget();
			}

		function ConfigUpdate ()
			{
			global $IW,$GUI;
			global $per_page,$search_links,$search_meta,$search_content;
			$IW->Query("update mod_docsearch_config set per_page='$per_page',search_links='$search_links', search_meta='$search_meta', search_content='$search_content'");
			$GUI->Message("Configuration Saved");
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
<script language="JavaScript">
	// Module JavaScript Code

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
		break;
		case UPDATE:
			$MOD->ConfigUpdate ();
		break;
		}
?>
<?php include "../../admin/author.php"; ?>
</body>
</html>