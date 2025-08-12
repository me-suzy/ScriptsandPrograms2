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
include_once('../inc/adodb/adodb.inc.php');	   # load code common to ADOdb
include_once('../config.php');
include_once('../inc/UIfunctions.php');
include_once('loginfunction.php');
include_once('../inc/FCKeditor/fckeditor.php');

$page = new pagebuilder('../', 'admin.php');
include_once('../inc/setLang.php');

checkUser('admin');

if (isset($_GET['newsid'])) $newsid = $_GET['newsid']; else $newsid = false;
$action = $_GET['action'];

if ($newsid)
{
	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	$conn = &ADONewConnection('mysql');	# create a connection
	$conn->Connect($dbhost,$dbuser,$dbpass,$dbdatabase);
	
	$getNewsSQL = "SELECT * from ".$tablePrefix."news WHERE newsid=$newsid";
	$newsRecordSet = &$conn->Execute($getNewsSQL);
	$username = $newsRecordSet->fields['username'];
	$newssubject = $newsRecordSet->fields['subject'];
	$body = $newsRecordSet->fields['body'];
	$postdate = date("F j, Y g:i a", $newsRecordSet->fields['postdate']);
	$conn->Close();
}


$page->showHeader($nav05);
?>

<table align="center" cellpadding="10">
	<form action="news.php" method="post" name="myForm">
	<?
		if ($action == 'new')
		{
		?><input type="hidden" name="action" value="add"><?
		}
		if ($action == 'old')
		{
		?><input type="hidden" name="action" value="edit"><input type="hidden" name="newsid" value="<? echo $newsid;?>"><?
		}
	?>
	
	<tr>
		<td colspan="2" class="bold"><? echo "$action02 $news05";?><br><? $page->drawLine();?></td>
	</tr>
	<tr>
		<td><? echo $news06;?>:</td>
		<td><input type="text" name="newssubject" value="<? showVar('newssubject'); ?>" size="80"></td>
	</tr>
	<tr>
		<td colspan="2">
			<?
			$editor = new FCKEditor('body');
			$editor->BasePath	= '../inc/FCKeditor/';
			$editor->Width		= '500';
			$editor->Height		= '200';
			$editor->ToolbarSet	= 'Basic';
			$editor->Value		= $body;
			$editor->Create();
			?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<input type="submit" value="<? echo $action02;?>">
		</td>
	</tr>			
	</form>
</table>		

<?
$page->showFooter();
?>
