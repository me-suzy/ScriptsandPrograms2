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
?>

<br><br><table width="90%" align="center" border="0">
	<tr>
<?
$per_row = 3;
$all_cats = cats_get();
if(is_array($all_cats)) {
	sort($all_cats);
}

foreach($all_cats as $cat) { 
	if(!isset($cur_row)) { $cur_row = 0; }
	if(!isset($cur_cell)) { $cur_cell = 0; }
	$ar_2 = "";
	$out="";
	$cat_2="";
	$cat_2 = ereg_replace("_", "&nbsp;", $cat);
	if(strlen($cat) >1) {
			// count sub menu
			$ob = new SNIPS;
			$ob->snips_list_set($cat);
			$array = $ob->snips_list_get();
			
			if(is_array($array)) {
			sort($array);
			$c=0;
			 foreach($array as $ar) {
			 	$c++;
			 }
			}

		if(!isset($c)) { $c=0;}
		
		// check cell
		if ($cur_cell < $per_row) { 
			echo '<td width="33%">';    
		} else {
			echo '</tr><tr><td width="33%">';
			$cur_cell = 0;
		}

		echo '<table border="0"><tr><td><img src='.$snippet_theme.'/img/cats.gif alt=category border=0>&nbsp;</td><td><a href=index.php?cat_select='.$cat.'>'.$cat_2.'</a> ('.$c.')</td></tr></table></td>';
		$cur_cell++;
		$c=0;
	}
}

while($cur_cur < $per_row) { 
	echo '<td width="33%">&nbsp;</td>';
	$cur_cur++;
} // while	
?>
</tr></table>