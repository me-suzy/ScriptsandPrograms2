<?
/*
* Project:      PHP-CSL (PHP Code Snippet Library)
* Version:      0.9
* Date:         2005/02/09 (y/m/d)
* Author:       Stuart Cochrane
* URL:          http://www.php-csl.com
* Sourceforge:  http://www.sourceforge.net/projects/php-csl/
*
* Read: reame.txt, install.txt, license.txt
*
*
*
*
*
*/


if($hide_options == 0 || isset($_SESSION['phpcsl'])) {
?>	
<br><br>
<table width="100%" border="0" align="center" class="menu">
	<tr>
		<td><img src="<?=$snippet_theme?>/img/link.gif" alt="+">
<a href="index.php?op=snips&act=edit&snip=<?=$_GET['show']?>&cats=<?=$_GET['cat_select']?>">&nbsp;Edit this snippet</a>
		</td>
		<td align="center"><img src="<?=$snippet_theme?>/img/link.gif" alt="+">
<a href="index.php?op=snips&act=delete&snips_delete=<?=$_GET['show']?>&cats=<?=$_GET['cat_select']?>">&nbsp;Delete this snippet</a>

		</td>
		<td align="center"><img src="<?=$snippet_theme?>/img/link.gif" alt="+">
<a href="index.php?op=cats&act=edit&cats=<?=$_GET['cat_select']?>">&nbsp;Rename this category</a>
		</td>
		<td align="center"><img src="<?=$snippet_theme?>/img/link.gif" alt="+">
<a href="index.php?op=cats&act=delete&cats_delete=<?=$_GET['cat_select']?>">&nbsp;Delete this category</a>
		</td>
		<td align="right"><img src="<?=$snippet_theme?>/img/link.gif" alt="+">
			<a target="_blank" href="printer_friendly.php?cat_select=<?=$_GET['cat_select']?>&show=<?=$_GET['show']?>">Printer Friendly</a>
		</td>
	</tr>
</table>
<?
} else {
?>
<br><br>
<table width="100%" border="0" align="center" class="menu">
	<tr>
		<td align="center"><img src="<?=$snippet_theme?>/img/link.gif" alt="+">
			<a target="_blank" href="printer_friendly.php?cat_select=<?=$_GET['cat_select']?>&show=<?=$_GET['show']?>">Printer Friendly</a>
		</td>
	</tr>
</table>

<?
}
?>
