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


if(isset($_GET['cat_select'])) {
	$show_sub = $_GET['cat_select'];
}

$all_cats = cats_get();

if(is_array($all_cats)) {
	sort($all_cats);
}

foreach($all_cats as $cat) { 
	$ar_2 = "";
	$out="";
	$cat_2="";
	$cat_2 = ereg_replace("_", "&nbsp;", $cat);
	if(strlen($cat) >1) {
			// show sub menu
			$ob = new SNIPS;
			$ob->snips_list_set($cat);
			$array = $ob->snips_list_get();
			if(is_array($array)) {
			sort($array);
			$c=0;
			 foreach($array as $ar) {
			 	$ar_2 = ereg_replace("_", "&nbsp;", $ar);
				$out .= '<img src='.$snippet_theme.'/img/link.gif alt=+>&nbsp;';
				$out .= '<a class=thin href=index.php?cat_select='.$cat.'&show='.$ar.'>'.$ar_2.'</a><br>';
				$c++;
			 }
			}
		
		if(!isset($c)) { $c=0;}
		echo '<a href=index.php?cat_select='.$cat.'><img src='.$snippet_theme.'/img/cats_icon_list.gif alt=+ border=0>&nbsp;'.$cat_2.'</a> ('.$c.')</br>';
		$c=0;
		if (isset($show_sub) && $show_sub == $cat) {
			if(isset($out)) {
				echo $out;
			}
		}
	
	}
}
?>
