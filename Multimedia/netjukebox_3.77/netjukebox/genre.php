<?php
//  +---------------------------------------------------------------------------+
//  | netjukebox, Copyright © 2001-2005  Willem Bartels                         |
//  |                                                                           |
//  | info@netjukebox.nl                                                        |
//  | http://www.netjukebox.nl                                                  |
//  |                                                                           |
//  | This file is part of netjukebox.                                          |
//  | netjukebox is free software; you can redistribute it and/or modify        |
//  | it under the terms of the GNU General Public License as published by      |
//  | the Free Software Foundation; either version 2 of the License, or         |
//  | (at your option) any later version.                                       |
//  |                                                                           |
//  | netjukebox is distributed in the hope that it will be useful,             |
//  | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
//  | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
//  | GNU General Public License for more details.                              |
//  |                                                                           |
//  | You should have received a copy of the GNU General Public License         |
//  | along with this program; if not, write to the Free Software               |
//  | Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA |
//  +---------------------------------------------------------------------------+



//  +---------------------------------------------------------------------------+
//  | genre.php                                                                 |
//  +---------------------------------------------------------------------------+
require_once('include/initialize.inc.php');

$command		= GetPost('command');
$genre_id		= GetPost('genre_id');
$album_id_array	= GetPost('album_id_array');

if ($command == 'select')		select($album_id_array);
if ($command == 'update')		update($album_id_array, $genre_id);

exit();



//  +---------------------------------------------------------------------------+
//  | Genre Tree                                                                |
//  +---------------------------------------------------------------------------+
function GenreTree($genre_id, &$genre_id_array, &$genre_array)
{
$query = mysql_query('SELECT genre_id, genre FROM genre WHERE genre_id LIKE "' . mysql_real_escape_string($genre_id) . '_" ORDER BY genre');
while ($genre = mysql_fetch_array($query))
    {
	$genre_id_array[] = $genre['genre_id'];
	$genre_array[]    = $genre['genre'];
    GenreTree($genre['genre_id'], $genre_id_array, $genre_array);
    }
}



//  +---------------------------------------------------------------------------+
//  | Select Genre                                                              |
//  +---------------------------------------------------------------------------+
function select($album_id_array)
{
global $cfg;
authenticate('access_config');
if (empty($album_id_array)) message('warning', '<strong>No album selected</strong><br>Select one or more album(s) before editing<ul class="compact"><li><a href="' . $_SERVER['HTTP_REFERER'] . '">Go back</a></li></ul>');
require_once('include/header.inc.php');
?>
<form action="genre.php" method="post" target="main">
	<input type="hidden" name="command" value="update">
<?php
for($i=0; $i < count($album_id_array); $i++)
	echo '<input type="hidden" name="album_id_array[]" value="' . $album_id_array[$i] . '">' . "\n";
//FormattedNavigator
$name	= array('Browse');
$url	= array('browse.php');
$name[] = 'Select genre';
FormattedNavigator($url, $name);
?>
<table class="border" border="0" cellspacing="0" cellpadding="0">
<tr class="header">
	<td class="spacer"></td>
	<td>Genre</td>
	<td class="spacer"></td>
</tr>
<?php
$genre_id_array = array();
$genre_array 	= array();
GenreTree('', $genre_id_array, $genre_array);
$i=0;
foreach($genre_array as $key => $genre)
	{
	$genre_id = $genre_id_array[$key];
	$lenght = strlen($genre_id);
	$tab = str_repeat('&nbsp;', ($lenght - 1) * 4); 
?>
<tr class="<?php echo ($i++ & 1) ? 'even' : 'odd'; ?>">
	<td></td>
	<td><input type="radio" name="genre_id" value="<?php echo $genre_id; ?>" class="space"<?php if ($i == 1) echo ' checked'; ?>><?php echo $tab . htmlentities($genre); ?></td>
	<td></td>
</tr>
<?php
	}
?>
</table>
<br>
<input type="image" src="<?php echo $cfg['img']; ?>/button_save.gif">
<a href="browse.php"><img src="<?php echo $cfg['img']; ?>/button_cancel.gif" alt="" width="106" height="26" border="0"></a>
</form>
<?php
require_once('include/footer.inc.php');
}



//  +---------------------------------------------------------------------------+
//  | Update                                                                    |
//  +---------------------------------------------------------------------------+
function update($album_id_array, $genre_id)
{
authenticate('access_config');
for($i=0; $i < count($album_id_array); $i++)
	{
	mysql_query('UPDATE album SET
				genre_id = "' . mysql_real_escape_string($genre_id) . '"
				WHERE album_id = "' . mysql_real_escape_string($album_id_array[$i]) . '"');
	}
echo '<meta http-equiv="refresh" content="0;URL=browse.php?command=view1&amp;genre_id=' . $genre_id . '">';
flush();
}
?>
