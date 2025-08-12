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
include_once('loginfunction.php');
include_once('../inc/UIfunctions.php');
include_once('../config.php');

$page = new pagebuilder('../', 'admin.php');
include_once('../inc/setLang.php');
checkUser();
$page->showHeader($nav05);

if (isset($_GET['catid']))		$catid = (int)$_GET['catid'];
if (isset($_GET['category']))	$category = $_GET['category'];

?>
<table>
	<form action="categories.php" method="POST">
	<input type="hidden" name="catid" value="<? echo $catid; ?>">
	<input type="hidden" name="action" value="edit">
	<tr>
		<th><? echo "$cat01 $admin08";?></th><th><? echo $cat02;?></th>
	</tr>
	<tr>
		<td><? echo $catid;?></td><td><input type="text" name="category" value="<? echo $category;?>"></td>
	</tr>
	<tr>
		<td colspan="2" align="right"><input type="submit" value="<? echo $action06;?>"></td>
	</tr>
	</form>
</table>

<?
$page->showFooter();
?>