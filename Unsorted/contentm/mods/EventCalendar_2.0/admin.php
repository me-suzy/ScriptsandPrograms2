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
	define("CREATE",2);
	define("ADD",3);
	define("EDIT",4);
	define("UPDATE",5);
	define("REMOVE",6);

	// Module Class
	class Module {

		function ModExists ()
			{
			global $IW;
			if(!$IW->tableExists ("mod_eventcalendar")){return false;}
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
			$IW->Query("CREATE TABLE mod_eventcalendar (id varchar (50), date_mm int (2), date_dd int (2), date_yyyy int (4), title varchar (50), description text)");
			$GUI->Message("Module Installed.");
			$GUI->Navigate("admin.php?");
			}

		function ConfigForm ()
			{
			}

		function ConfigUpdate ()
			{
			}

		function EventManager ()
			{
			global $GUI,$IW;
			$GUI->OpenWidget("Event Manager");
			$result=$IW->Query("select * from mod_eventcalendar order by date_yyyy,date_mm, date_dd");
			echo "<p>There are " . $IW->CountResult($result)." events scheduled.</p>\n";
			echo "<table border=0 width=\"90%\" cellpadding=3 cellspacing=0>\n";
			echo "<tr>\n";
			echo "<td bgcolor=#e4e4e4>".$GUI->Label("Date")."</td>\n";
			echo "<td bgcolor=#e4e4e4>".$GUI->Label("Event")."</td>\n";
			$GUI->OpenForm("","admin.php?cmd=".CREATE,"");
			echo "<td colspan=2 bgcolor=#e4e4e4>".$GUI->Button("New")."</td>\n";
			$GUI->CloseForm ();
			echo "</tr>\n";
			for($i=0;$i<$IW->CountResult($result);$i++)
				{
				echo "<tr>\n";
				echo "<td>".$IW->Result($result,$i,"date_mm")."/".$IW->Result($result,$i,"date_dd")."/".$IW->Result($result,$i,"date_yyyy")."</td>\n";
				echo "<td>".$IW->Result($result,$i,"title")."</td>\n";
				$GUI->OpenForm("","admin.php?cmd=".EDIT."&id=".$IW->Result($result,$i,"id"),"");
				echo "<td>".$GUI->Button("Edit")."</td>\n";
				$GUI->CloseForm ();
				$GUI->OpenForm("","admin.php?cmd=".REMOVE."&id=".$IW->Result($result,$i,"id"),"");
				echo "<td>".$GUI->Button("Delete")."</td>\n";
				$GUI->CloseForm ();
				echo "</tr>\n";
				}
			echo "</table>\n";
			$IW->FreeResult($result);
			$GUI->CloseWidget ();
			}

		function NewEvent ()
			{
			global $IW,$GUI;
			$GUI->OpenForm ("","admin.php?cmd=".ADD,"");
			$GUI->OpenWidget("New Event");
			echo "<table border=0>\n";
			echo "<tr><td>".$GUI->Label("Event Date")."</td>\n";
			echo "<td>".$GUI->TextBox("date_mm",date("m"),2)."/".$GUI->TextBox("date_dd",date("d"),2)."/".$GUI->TextBox("date_yyyy",date("Y"),2)."</td></tr>";
			echo "<tr><td>".$GUI->Label("Event Title")."</td>\n";
			echo "<td>".$GUI->TextBox("title","",50)."</td></tr>";
			echo "<tr><td colspan=2>".$GUI->Label("Event Description")."<br />".$GUI->TextArea("description","",5,40)."</td></tr>";
			echo "</table><br /><br />\n";
			echo $GUI->Button("Add Event");
			$GUI->CloseWidget ();
			$GUI->CloseForm ();
			}

		function AddEvent ()
			{
			global $IW,$GUI;
			global $date_mm,$date_dd,$date_yyyy,$title,$description;
			$id=uniqid("event_");
			$IW->Query("insert into mod_eventcalendar (id,date_mm,date_dd,date_yyyy,title,description) values ('$id','$date_mm','$date_dd','$date_yyyy','$title','$description')");
			$GUI->Message("Event Added");
			$GUI->Navigate("admin.php?");
			}	

		function EditEvent ($id)
			{
			global $IW,$GUI;
			$result=$IW->Query("select * from mod_eventcalendar where id='$id' limit 1");
			$GUI->OpenForm ("","admin.php?cmd=".UPDATE."&id=".$id,"");
			$GUI->OpenWidget("Edit Event");
			echo "<table border=0>\n";
			echo "<tr><td>".$GUI->Label("Event Date")."</td>\n";
			echo "<td>".$GUI->TextBox("date_mm",$IW->Result($result,0,"date_mm"),2)."/".$GUI->TextBox("date_dd",$IW->Result($result,0,"date_dd"),2)."/".$GUI->TextBox("date_yyyy",$IW->Result($result,0,"date_yyyy"),2)."</td></tr>";
			echo "<tr><td>".$GUI->Label("Event Title")."</td>\n";
			echo "<td>".$GUI->TextBox("title",$IW->Result($result,0,"title"),50)."</td></tr>";
			echo "<tr><td colspan=2>".$GUI->Label("Event Description")."<br />".$GUI->TextArea("description",$IW->Result($result,0,"description"),5,40)."</td></tr>";
			echo "</table><br /><br />\n";
			echo $GUI->Button("Save Changes");
			$GUI->CloseWidget ();
			$GUI->CloseForm ();		
			$IW->FreeResult($result);
			}

		function UpdateEvent ($id)
			{
			global $IW,$GUI;
			global $date_mm,$date_dd,$date_yyyy,$title,$description;
			$IW->Query("update mod_eventcalendar set date_mm='$date_mm',date_dd='$date_dd',date_yyyy='$date_yyyy',title='$title',description='$description' where id='$id'");
			$GUI->Message("Changes Saved");
			$GUI->Navigate("admin.php?");
			}

		function DeleteEvent ($id)
			{
			global $IW,$GUI;
			global $date_mm,$date_dd,$date_yyyy,$title,$description;
			$IW->Query("delete from mod_eventcalendar  where id='$id'");
			$GUI->Message("Event Deleted");
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
			$MOD->EventManager ();
		break;
		case CREATE:
			$MOD->NewEvent ();
		break;
		case ADD:
			$MOD->AddEvent ();
		break;
		case EDIT:
			$MOD->EditEvent ($id);
		break;
		case UPDATE:
			$MOD->UpdateEvent ($id);
		break;
		case REMOVE:
			$MOD->DeleteEvent ($id);
		break;
		}
?>
<?php include "../../admin/author.php"; ?>
</body>
</html>