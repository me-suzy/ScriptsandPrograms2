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
include_once('../config.php');
include_once('loginfunction.php');
include_once('../inc/UIfunctions.php');
include_once('../inc/dbform.php');
include_once('../inc/pager.php');
$page = new pagebuilder('../', 'admin.php');
include_once('../inc/setLang.php');
checkUser();
$page->showHeader($nav05);

include_once('../inc/adodb/adodb.inc.php');	   # load code common to ADOdb
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$conn = &ADONewConnection('mysql');	# create a connection
$conn->Connect($dbhost,$dbuser,$dbpass,$dbdatabase);

$dbform = new dbform($conn, $tablePrefix.'music');
$dbform->setTitleClass('bold');
$dbform->setCustomAddForm('addmusic.php');
$dbform->setSelectTitle($music01);
$dbform->setAddTitle($music02);
$dbform->setEditTitle($music03);
$dbform->setColumnTitle('mid', $admin08);
$dbform->setColumnTitle('mname', $music04);
$dbform->setColumnTitle('mpath', $music05);
$dbform->unEditable('mpath');
?>
<table cellspacing="2" cellpadding="2">
	<tr>
		<td>
		<?
		$dbform->processForms();
		$dbform->showForms();
		$dbform->viewTable();
		?>
		</td>
	</tr>
</table>

<?
$page->showFooter();
?>
