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
			if(!$IW->tableExists ("mod_guestbook_config")){return false;}
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
			$IW->Query("CREATE TABLE mod_guestbook (  id varchar(50) default NULL,  msg_date int(20) default NULL,  msg_subject varchar(50) default NULL,  msg_author varchar(50) default NULL,  msg_body text)");
			$IW->Query("CREATE TABLE mod_guestbook_config (  allow_posts int(3) default NULL,  max_author int(3) default NULL,  max_subject int(3) default NULL,  max_body int(3) default NULL,  author_req int(3) default NULL,  subject_req int(3) default NULL,  body_req int(3) default NULL,  msg_per_page int(3) default NULL)");
			$IW->Query("INSERT INTO mod_guestbook_config VALUES('1', '30', '50', '255', '1', '1', '1', '10')");
			$GUI->Message("Module Installed.");
			$GUI->Navigate("admin.php?");
			}

		function ConfigForm ()
			{
			global $IW,$GUI;
			$result=$IW->Query("select * from mod_guestbook_config limit 1");
			$GUI->OpenForm("gbSettings","admin.php?cmd=".UPDATE,"return vGBSettings ()");
			$GUI->OpenWidget("Guestbook Settings");
			echo "<table border=0>";	
			echo "<tr><td>".$GUI->CheckBox("allow_posts",1,$IW->Result($result,0,"allow_posts"));
			echo "</td><td>".$GUI->Label("Allow users to post new messages")."</td></tr>";
			echo "<tr><td>".$GUI->CheckBox("author_req",1,$IW->Result($result,0,"author_req"));
			echo "</td><td>".$GUI->Label("User must fill in author information")."</td></tr>";
			echo "<tr><td>".$GUI->CheckBox("subject_req",1,$IW->Result($result,0,"subject_req"));
			echo "</td><td>".$GUI->Label("User must fill in subject information")."</td></tr>";
			echo "<tr><td>".$GUI->CheckBox("body_req",1,$IW->Result($result,0,"body_req"));
			echo "</td><td>".$GUI->Label("User must fill in message body")."</td></tr>";
			echo "<tr><td>".$GUI->TextBox("max_author",$IW->Result($result,0,"max_author"),3);
			echo "</td><td>".$GUI->Label("Max chars allowed in author field")."</td></tr>";
			echo "<tr><td>".$GUI->TextBox("max_subject",$IW->Result($result,0,"max_subject"),3);
			echo "</td><td>".$GUI->Label("Max chars allowed in subject field")."</td></tr>";
			echo "<tr><td>".$GUI->TextBox("max_body",$IW->Result($result,0,"max_body"),3);
			echo "</td><td>".$GUI->Label("Max chars allowed in message body")."</td></tr>";
			echo "<tr><td>".$GUI->TextBox("msg_per_page",$IW->Result($result,0,"msg_per_page"),3);
			echo "</td><td>".$GUI->Label("Number of posted messages to display per page")."</td></tr>";
			echo "</table>";
			echo $GUI->Button("Save Settings");
			$GUI->CloseWidget("");
			$GUI->CloseForm();
			$IW->FreeResult($result);
			}

		function ConfigUpdate ()
			{
			global $IW,$GUI;
			global $allow_posts,$author_req,$subject_req,$body_req;
			global $max_author,$max_subject,$max_body,$msg_per_page;
			if(!isset($allow_posts)){$allow_posts=0;}
			if(!isset($author_req)){$author_req=0;}
			if(!isset($subject_req)){$subject_req=0;}
			if(!isset($body_req)){$body_req=0;}
			$IW->Query("update mod_guestbook_config set allow_posts='$allow_posts',author_req='$author_req',subject_req='$subject_req',body_req='$body_req',max_author='$max_author',max_subject='$max_subject',max_body='$max_body',msg_per_page='$msg_per_page' ");
			$GUI->Message("Configuration Saved");
			$GUI->Navigate("admin.php?");			
			}

		function ManageMessages ()
			{
			global $IW,$GUI;
			$result=$IW->Query("select * from mod_guestbook order by msg_date desc");
			$GUI->OpenWidget("Manage Guestbook Posts");
			$count=$IW->CountResult($result);
			echo "<center><i>There are currently $count messages posted.</i></center>";
			echo "<table border=0 cellpadding=3 cellspacing=0>";
			$row=0;
			for($i=0;$i<$count;$i++)
				{
				if($row==0){$color="#f5f5f5";}
				elseif($row==1){$color="#e4e4e4";}
				echo "<tr>";
				echo "<td bgcolor=$color><i>".date("m/d/Y",$IW->Result($result,$i,"msg_date"))."</i></td>";
				echo "<td bgcolor=$color><b>".$IW->Result($result,$i,"msg_subject")."</b></td>";
				$GUI->OpenForm ("dForm","admin.php?cmd=".REMOVE."&id=".$IW->Result($result,$i,"id"),"return cDelPost ()");
				echo "<td bgcolor=$color>".$GUI->Button("Delete Post")."</td>";
				$GUI->CloseForm ();
				echo "</tr>";
				if($row==0){$row=1;}
				elseif($row==1){$row=0;}
				}
			echo "</table>";
			$GUI->CloseWidget("");
			$IW->FreeResult($result);
			}

		function DeleteMessage ()
			{
			global $IW,$GUI;
			global $id;
			$IW->Query("delete from mod_guestbook where id='$id' limit 1");
			$GUI->Message("Message Deleted");
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
	function vGBSettings ()
		{
		if(!isNumber(document.gbSettings.max_author.value))
			{alert('Max author length must be a number');return false;}
		if(!isNumber(document.gbSettings.max_subject.value))
			{alert('Max subject length must be a number');return false;}
		if(!isNumber(document.gbSettings.max_body.value))
			{alert('Max body length must be a number');return false;}
		if(!isNumber(document.gbSettings.msg_per_page.value))
			{alert('No messages per page must be a number');return false;}
		return true;
		}
	function cDelPost ()
		{
		if(window.confirm('Delete this message ?')){return true;}
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
			$MOD->ManageMessages ();
		break;
		case UPDATE:
			$MOD->ConfigUpdate ();
		break;
		case REMOVE:
			$MOD->DeleteMessage ();
		break;
		}
?>
<?php include "../../admin/author.php"; ?>
</body>
</html>