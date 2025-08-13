<?
/*
 * gCards - a web-based eCard application
 * Copyright (C) 2003 Greg Neustaetter
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
session_start();
include_once('inc/UIfunctions.php');
include_once('config.php');

$page = new pagebuilder;
include_once('inc/setLang.php');

if (isset($_GET['imageid'])) $_SESSION['imageid'] = (int)$_GET['imageid'];
if (isset($_SESSION['reply'])) $_SESSION['to_email'] = $_SESSION['reply'];
createLocalFromSession('imageid');
createLocalFromSession('from_name');
createLocalFromSession('from_email');
createLocalFromSession('to_email');
createLocalFromSession('cardtext');
createLocalFromSession('sendOnPickup');

if (!$imageid)
	{
		$page->showHeader();
		echo $compose02;
		$page->showFooter();
		exit;
	}
else
	{
		include_once('inc/smileyClass.php');
		include_once('inc/htmlarea_js.php');
		$page->headervalues = getHTMLareaJStop('myForm', 'cardtext');
		$page->showHeader();
		
		$smileyClass = new smileyClass('images/siteImages/smilies/');
		
		include('inc/adodb/adodb.inc.php');	   # load code common to ADOdb
		include('config.php');
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$conn = &ADONewConnection('mysql');	# create a connection
		$conn->Connect($dbhost,$dbuser,$dbpass,$dbdatabase);
		$sqlstmt = "select imageid, cardname, imagepath from ".$tablePrefix."cardinfo where imageid='$imageid'";
		
		
		$recordSet = &$conn->Execute($sqlstmt);
		if (!$recordSet) 
			print $conn->ErrorMsg();
		else
			{
				$imageid = $recordSet->fields['imageid'];
				$cardname = $recordSet->fields['cardname'];
				$imagepath = $recordSet->fields['imagepath'];
				echo $compose02;
?>
<br><br>

<table align="center" cellpadding="10">
	<form action="processCompose.php" method="post" name="myForm">
	<tr>
		<td align="center">
			<table cellpadding="10">
				<tr>
					<?
					if ($dropShadow == 'yes')
						{
						?>
							<td>
								<span class="bold"><? echo $cardname; ?></span><br>
								<table cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td><img src="images/<? echo rawurlencode($imagepath); ?>" border="0"></td>
										<td valign="top" background="images/siteImages/dropshadow/ds_right.gif"><img src="images/siteImages/dropshadow/ds_topright.gif" alt="" width="7" height="10" border="0"></td>
									</tr>
									<tr>
										<td background="images/siteImages/dropshadow/ds_bottom.gif"><img src="images/siteImages/dropshadow/ds_bottomleft.gif" alt="" width="7" height="7" border="0"></td>
										<td><img src="images/siteImages/dropshadow/ds_corner.gif" alt="" width="7" height="7" border="0"></td>
									</tr>
								</table>
							</td>
						<?
						}	
					else
						{
						?>
							<td bgcolor="white">
								<span class="bold"><? echo $cardname; ?></span><br>
								<img src="images/<? echo rawurlencode($imagepath); ?>" border="0">
							</td>
						<?
						}
					?>

				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table>
				<tr class="smalltext">
					<td class="smalltext">&nbsp;</td>
					<td class="smalltext"><? echo $compose03;?></td>
					<td class="smalltext"><? echo $compose04;?></td>
				</tr>
				<tr>
					<td class="bold"><? echo $compose05;?></td>
					<td><input type="text" name="from_name" value="<? showVar('from_name'); ?>"></td>
					<td><input type="text" name="from_email" size="30" value="<? showVar('from_email'); ?>"></td>
				</tr>
				<tr class="smalltext">
					<td class="smalltext">&nbsp;</td>
					<td class="smalltext" colspan="2"><? echo $compose08;?></td>
				</tr>
				<tr>
					<td class="bold"><? echo $compose06;?></td>
					<td colspan="2">
						<textarea name="to_email" cols="45" rows="3"><? showVar('to_email'); ?></textarea>
					</td>
				</tr>
				<?
				$musicFiles = getMusic($conn);
				if (count($musicFiles) > 0)
				{
					if (isset($_SESSION['music'])) $musicValue = $_SESSION['music']; else $musicValue = 'none';
				?>
				<tr>
					<td class="bold"><? echo $compose09;?></td>
					<td>
					<select name="music">
						<option value="none">None</option>
						<?
						foreach($musicFiles as $musicFile)
						{
						?><option value="<? echo $musicFile['mpath'];?>" <? if ($musicFile['mpath'] == $musicValue) echo 'selected';?>><? echo $musicFile['mname'];?></option><?
						}
						?>
					</select>&nbsp;&nbsp;
					<a href="javascript:preview()">[ <? echo $action08;?> ]</a>
					</td>
				</tr>
				<?
				}
				?>			
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<textarea cols="60" rows="12" name="cardtext" wrap="virtual"><? if (isset($cardtext)) echo stripslashes($cardtext); ?></textarea>
		</td>
	</tr>
	<tr>
		<td>
			<? $smileyClass->showSmileys('cardtext'); ?>
		</td>
	</tr>
	<tr>
		<td>
			<input type="checkbox" name="sendOnPickup" value="send" <? if (isset($sendOnPickup)) if ($sendOnPickup == 'send') echo 'checked';?>> <? echo $compose07;?>
		</td>
	</tr>
	<tr>
		<td>
			<input type="submit" name="action" value="<? echo $action08;?>">
			<input type="submit" name="action" value="<? echo $preview04;?>">
		</td>
	</tr>			
	</form>
</table>				

<script language="JavaScript">
<!--	
function preview(){
var selectedMusic=document.myForm.music.options[document.myForm.music.selectedIndex].value
if (selectedMusic=="none"){alert("Please choose a song to preview"); return ;}	

var url = "previewMusic.php?music=" + selectedMusic ;

window.open(url, null,'height=150,width=300,status=no,toolbar=no,menubar=no,location=no')
}
// -->
</script>
				
<?
			}
$recordSet->Close(); 
$conn->Close(); 
print getHTMLareaJSbot('cardtext');
		
$page->showFooter();
	}

function getMusic(&$conn)
{
	global $tablePrefix;
	$getMusicSQL = "SELECT * from ".$tablePrefix."music order by mname";
	$recordSet = $conn->Execute($getMusicSQL);
	return $recordSet->getArray();
}
?>
