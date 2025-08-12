<?php
/*
******************************************************************************************
** SB|photoAlbum                                                                        **
** Copyright (C)2005 Ladislav Soukup                                                    **
**                                                                                      **
** Tento program je svobodný software; mùete jej íøit a modifikovat podle             **
** ustanovení GNU General Public License, vydávané Free Software                        **
** Foundation; a to buï verze 2 této licence anebo (podle vaeho uváení)               **
** kterékoli pozdìjí verze.                                                            **
**                                                                                      **
** Tento program je roziøován v nadìji, e bude uiteèný, avak BEZ                    **
** JAKÉKOLI ZÁRUKY; neposkytují se ani odvozené záruky PRODEJNOSTI anebo                **
** VHODNOSTI PRO URÈITÝ ÚÈEL. Dalí podrobnosti hledejte ve GNU General Public License. **
**                                                                                      **
** Kopii GNU General Public License jste mìl obdret spolu s tímto                      **
** programem; pokud se tak nestalo, napite o ni Free Software Foundation,              **
** Inc., 675 Mass Ave, Cambridge, MA 02139, USA.                                        **
**                                                                                      **
** Autor:  Ladislav Soukup                                                              **
** e-mail: root@soundboss.cz                                                            **
** URL: http://php.soundboss.cz                                                         **
** URL: http://www.soundboss.cz                                                         **
******************************************************************************************
*/
if (function_exists("scan_dir")) { define("USE_PHP4", false); } else { define("USE_PHP4", true); } // PHP5 check.
include_once "./pa_config.php";
include_once "./photoalbum/core.php";
$pa_core = new pa_core();

function make_tree($path, $loop = 1) {
	global $pa_dir_tree;
	
	$prev_idx = ($loop-1);
	if (USE_PHP4 == false) {
		$dirs = scandir($path);
	} else {
		$dh  = opendir($path);
		while (false !== ($filename = readdir($dh))) {
			$dirs[] = $filename;
		}
	}
	if (is_array($dirs)) {
		foreach($dirs as $dir) {
			if(!preg_match('/^\./',$dir)) {
				$full_path = $path ."/". $dir;
				$full_path_js = str_replace(pa_image_dir, "", $full_path);
				if(is_dir($full_path)){
					$pa_dir_tree[] = array($loop, $prev_idx, $dir, "javascript:pa_chdir('".$full_path_js."','".$loop."', true)");
					echo $loop . " # \"" . $full_path_js . "\"<br />\n";
					$loop++;
					make_tree($full_path, $loop);
					$loop++;
				}
			}
		}
	}
	return true;
}

echo "ALPHA TEST VERSION ONLY<br />\n";
flush();
make_tree(pa_image_dir);

$cahce_data = serialize($pa_dir_tree);
$f = fopen(pa_dir_tree_cache_file, "w");
fputs($f, $cahce_data);
fclose($f);
?>
