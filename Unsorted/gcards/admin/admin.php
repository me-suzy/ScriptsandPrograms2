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
include_once('../config.php');
include_once('../inc/UIfunctions.php');

$page = new pagebuilder('../');
include_once('../inc/setLang.php');

if (!isset($_SESSION['auth_user'])) loginuser();

$page->showHeader($nav05);
?>

<table>
	<tr>
		<td class="bold"><? echo $admin01;?></td>
	</tr>
	<tr>
		<td><a href="cards.php"><? echo $admin03;?></a> - <? echo $admin07;?></td>
	</tr>
	<tr>
		<td><a href="music.php"><? echo $admin19;?></a> - <? echo $admin07;?></td>
	</tr>
	<tr>
		<td><a href="categories.php"><? echo $admin04;?></a> - <? echo $admin07;?></td>
	</tr>
<?
	if ($_SESSION['auth_role'] == 'admin')
{
?>
	<tr>
		<td><a href="news.php"><? echo $admin05;?></a> - <? echo $admin07;?></td>
	</tr>
	<tr>
		<td><a href="users.php"><? echo $admin06;?></a> - <? echo $admin07;?></td>
	</tr>

<?
}
?>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><a href="stats.php"><? echo $admin17; ?></a> - <? echo $admin18; ?></td>
	</tr>
</table>
<?
$page->showFooter();
?>
