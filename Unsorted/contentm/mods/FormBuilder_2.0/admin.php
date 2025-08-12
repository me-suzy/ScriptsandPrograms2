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
			if(!$IW->tableExists ("mod_formbuilder")){return false;}
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
			$IW->Query("CREATE TABLE mod_formbuilder (  mail_mode int(3) default '0',  mail_smtp varchar(50) default NULL,  mail_to varchar(50) default NULL,  mail_from varchar(50) default NULL,  mail_subject varchar(50) default NULL,  submit varchar(50) default NULL,  reply text,  f_0 varchar(100) default NULL,  f_1 varchar(100) default NULL,  f_2 varchar(100) default NULL,  f_3 varchar(100) default NULL,  f_4 varchar(100) default NULL,  f_5 varchar(100) default NULL,  f_6 varchar(100) default NULL,  f_7 varchar(100) default NULL,  f_8 varchar(100) default NULL,  f_9 varchar(100) default NULL)");
			$IW->Query("INSERT INTO mod_formbuilder VALUES('0', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '')");
			$GUI->Message("Module Installed.");
			$GUI->Navigate("admin.php?");
			}

		function ConfigForm ()
			{
			global $IW,$GUI;
			$result=$IW->Query("select * from mod_formbuilder limit 1");
			$GUI->OpenForm("","admin.php?cmd=".UPDATE,"");
			$GUI->OpenWidget("FormBuilder Configuration");
			echo "<table border=0 cellpadding=3 cellspacing=0>";
			echo "<tr><td>".$GUI->Label("Mail Mode")."</td>\n";
			echo "<td>".$GUI->RadioOption("mail_mode","0",($IW->Result($result,0,"mail_mode")==0)?1:0)." ";
			echo $GUI->Label("UNIX / Linux");
			echo " ".$GUI->RadioOption("mail_mode","1",($IW->Result($result,0,"mail_mode")==1)?1:0)." ";
			echo $GUI->Label("Win 32");	
			echo "</td></tr>";
			echo "<tr><td>".$GUI->Label("Mail SMTP")."</td>\n";
			echo "<td>".$GUI->TextBox("mail_smtp",$IW->Result($result,0,"mail_smtp"),30)."</td></tr>";	
			echo "<tr><td>".$GUI->Label("Mail To")."</td>\n";
			echo "<td>".$GUI->TextBox("mail_to",$IW->Result($result,0,"mail_to"),30)."</td></tr>";	
			echo "<tr><td>".$GUI->Label("Subject Line")."</td>\n";
			echo "<td>".$GUI->TextBox("mail_subject",$IW->Result($result,0,"mail_subject"),30)."</td></tr>";	
			echo "<tr><td>".$GUI->Label("From")."</td>\n";
			echo "<td>".$GUI->TextBox("mail_from",$IW->Result($result,0,"mail_from"),30)."</td></tr>";
			echo "<tr><td>".$GUI->Label("Submit Label")."</td>\n";
			echo "<td>".$GUI->TextBox("submit",$IW->Result($result,0,"submit"),30)."</td></tr>";	
			echo "<tr><td colspan=2>".$GUI->Label("Text Response")."<br />";
			echo $GUI->TextArea("reply",$IW->Result($result,0,"reply"),5,40)."</td></tr>";	
			echo "</table><br /><br />";
			echo "<table border=0 cellpadding=3 cellspacing=0>";
			echo "<tr>";
			echo "<td bgcolor=#e4e4e4>".$GUI->Label("Field Name")."</td>";
			echo "<td bgcolor=#e4e4e4>".$GUI->Label("Field Type")."</td>";
			echo "<td bgcolor=#e4e4e4>".$GUI->Label("Default Value")."</td>";
			echo "</tr>";	
			for($i=0;$i<10;$i++)
				{
				$string=$IW->Result($result,0,"f_".$i);
				if(empty($string))
					{
					$name="";
					$type=0;
					$value="";
					}
				else
					{
					$data = explode("|",$string);
					$name=str_replace("_"," ",$data[0]);
					$type=$data[1];
					$value=$data[2];
					}
				echo "<tr>";
				echo "<td>".$GUI->TextBox("f_".$i."_name",$name,30)."</td>";
				echo "<td>";
				$GUI->OpenListBox("f_".$i."_type",1);
				echo $GUI->ListOption("0","-- None --",($type==0)?1:0);
				echo $GUI->ListOption("1","Text Box",($type==1)?1:0);
				echo $GUI->ListOption("2","Text Area",($type==2)?1:0);
				echo $GUI->ListOption("3","Check Box",($type==3)?1:0);
				echo $GUI->ListOption("4","Yes No",($type==4)?1:0);
				$GUI->CloseListBox ();
				echo "</td>";
				echo "<td>".$GUI->TextBox("f_".$i."_value",$value,10)."</td>";
				echo "</tr>";
				}
			echo "</table><br /><br />";
			echo $GUI->Button ("Save Configuration");
			$GUI->CloseWidget();
			$GUI->CloseForm ();
			$IW->FreeResult($result);
			}

		function ConfigUpdate ()
			{
			global $IW,$GUI;
			global $mail_mode,$mail_smtp,$mail_to,$mail_subject,$mail_from,$submit,$reply;
			$sql="update mod_formbuilder set mail_mode='$mail_mode',mail_smtp='$mail_smtp',mail_to='$mail_to',mail_subject='$mail_subject',mail_from='$mail_from',submit='$submit',reply='$reply',";
			for($i=0;$i<10;$i++)
				{
				global ${"f_".$i."_name"},${"f_".$i."_type"},${"f_".$i."_value"};
				$name=${"f_".$i."_name"};
				$type=${"f_".$i."_type"};
				$value=${"f_".$i."_value"};
				$string=$name."|".$type."|".$value."|";
				$sql.="f_".$i."='$string'";
				if($i!=9){$sql.=",";}
				}
			$IW->Query($sql);
			$GUI->Message("Form Configuration Saved");
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