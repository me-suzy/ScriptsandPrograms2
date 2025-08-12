<?php
	/** Copyright (c) 2003, 2004 EMEDIA OFFICE GmbH. All rights reserved.
	*
	* Redistribution and use in source and binary forms, with or without 
	* modification, are permitted provided that the following conditions are met:
	* 
	*  o Redistributions of source code must retain the above copyright notice, 
	*    this list of conditions and the following disclaimer. 
	*     
	*  o Redistributions in binary form must reproduce the above copyright notice, 
	*    this list of conditions and the following disclaimer in the documentation 
	*    and/or other materials provided with the distribution. 
	*     
	*  o Neither the name of EMEDIA OFFICE GmbH nor the names of 
	*    its contributors may be used to endorse or promote products derived 
	*    from this software without specific prior written permission. 
	*     
	* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" 
	* AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, 
	* THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR 
	* PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR 
	* CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, 
	* EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, 
	* PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; 
	* OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, 
	* WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR 
	* OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, 
	* EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
	*/
	
	session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title></title>	
	<link rel="stylesheet" href="format.css" type="text/css">
</head>
<?php
	include ("../language_files/".$_REQUEST["language"]."/gui.inc.php");
	include ("version.inc.php");
?>
<body style="margin-left:11px;">
<br>
<table width="165px" class="BorderRed" style="background-color: #F0F0F0;" cellspacing="0">
	<tr style="background-color: Red; color: White;">
		<td width="16px" align="center"><img src="../images/dot.gif" height="12" width="12"></td>
		<td style="margin: 0 0 0 0; font-weight:bold;"><?php echo $GROUP_CIRCULATION;?></td>
	</tr>
	<tr>
		<td width="16px" style="padding: 3px;"><img src="../images/circulate.png" height="16" width="16" alt=""></td>
		<td><a href="showcirculation.php?language=<?php echo $_REQUEST["language"]?>&start=1&archivemode=0" target="frame_details"><?php echo $MENU_CIRCULATION;?></a></td>
	</tr>
	<tr>
		<td width="16px" style="padding:3px;"><img src="../images/archive.gif" height="16" width="16"></td>
		<td style="padding: 3px;"><a href="showcirculation.php?language=<?php echo $_REQUEST["language"]?>&archivemode=1&start=1" target="frame_details"><?php echo $MENU_ARCHIVE;?></a></td>
	</tr>	
	<tr>
		<td width="16px" style="padding: 3px; border-top: 1px solid Gray;"><img src="../images/maillist.png" height="16" width="16"></td>
		<td style="padding: 3px; border-top: 1px solid Gray; color: Gray;">
			<?php 
			if ($_SESSION["SESSION_CUTEFLOW_ACCESSLEVEL"] == 2)
			{
			?>
				<a href="showmaillist.php?language=<?php echo $_REQUEST["language"]?>&start=1&sortby=name" target="frame_details"><?php echo $MENU_MAILINGLIST;?></a>
			<?php
			}
			else
			{
				echo $MENU_MAILINGLIST;
			}
			?>
		</td>
	</tr>
</table>
<br>
<table width="165px" class="BorderRed" style="background-color: #F0F0F0;" cellspacing="0">
	<tr style="background-color: Red; color: White;">
		<td width="16px" align="center"><img src="../images/dot.gif" height="12" width="12"></td>
		<td style="margin: 0 0 0 0; font-weight:bold;"><?php echo $GROUP_ADMINISTRATION;?></td>
	</tr>
	<tr>
		<td width="16px" style="padding:3px;"><img src="../images/singleuser2.png" height="19" width="16" alt=""></td>
		<td style="color: Gray;">
			<?php 
			if ($_SESSION["SESSION_CUTEFLOW_ACCESSLEVEL"] == 2)
			{
			?>
				<a href="showuser.php?language=<?php echo $_REQUEST["language"]?>&start=1&sortby=name" target="frame_details"><?php echo $MENU_USERMNG;?></a>
			<?php
			}
			else
			{
				echo $MENU_USERMNG;
			}
			?>
		</td>
	</tr>
	<tr>
		<td width="16px" style="padding: 3px; border-top: 1px solid Gray;"><img src="../images/metharg_obj.gif" height="16" width="16"></td>
		<td style="padding: 3px; border-top: 1px solid Gray; color: Gray;">
			<?php 
			if ($_SESSION["SESSION_CUTEFLOW_ACCESSLEVEL"] == 2)
			{
			?>
				<a href="showfields.php?language=<?php echo $_REQUEST["language"];?>&start=1&sortby=name" target="frame_details"><?php echo $MENU_FIELDS;?></a>
			<?php
			}
			else
			{
				echo $MENU_FIELDS;
			}
			?>
		</td>
	</tr>
	<tr>
		<td width="16px" style="padding:3px;"><img src="../images/template_type.png" height="16" width="16"></td>
		<td style="padding: 3px; color: Gray;">
			<?php 
			if ($_SESSION["SESSION_CUTEFLOW_ACCESSLEVEL"] == 2)
			{
			?>
				<a href="showtemplates.php?language=<?php echo $_REQUEST["language"];?>&start=1&sortby=name" target="frame_details"><?php echo $MENU_TEMPLATE;?></a>
			<?php
			}
			else
			{
				echo $MENU_TEMPLATE;
			}
			?>
		</td>
	</tr>	
</table>
<br>
<table width="165px" class="BorderRed" style="background-color: #F0F0F0;" cellspacing="0">
	<tr style="background-color: Red; color: White;">
		<td width="16px" align="center"><img src="../images/dot.gif" height="12" width="12"></td>
		<td style="margin: 0 0 0 0; font-weight:bold;"><?php echo $GROUP_LOGOUT;?></td>
	</tr>
	<tr>
		<td width="16px" style="padding:3px;"><img src="../images/exit.gif" height="16" width="16"></td>
		<td style="padding: 3px; color: Gray;">
				<a href="logout.php?language=<?php echo $_REQUEST["language"];?>" target="_top"><?php echo $MENU_LOGOUT;?></a>
		</td>
	</tr>		
</table>
<br>
<br>
<br>
<div align="center">
	<strong style="font-size:8pt;font-weight:normal">powered by</strong><br>
	<a href="http://cuteflow.fantastic-bits.de" target="_blank"><img src="../images/cuteflow_logo_small.png" border="0" /></a><br>
	<strong style="font-size:8pt;font-weight:normal">Version <?php echo $CUTEFLOW_VERSION;?></strong><br> 
</div>

</body>
</html>