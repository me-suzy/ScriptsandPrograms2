<?php
define("pa_title", "PhotoAlbum"); //page title
define("pa_description", " !!! EDIT !!! "); //page description
define("pa_default_lang", "en"); //default language if not recorgnized using HTTP_ACCEPT_LANGUAGE
define("pa_logo", "photoalbum/img/logo.gif"); //page logo
define("pa_image_dir", "./images"); //image dir ("./images" by default)
define("pa_image_save_thumb", true); // FALSE is VERY SLOW - thumbs will not be saved.
define("pa_thumb_prefix", "_ThumB_"); // thumb file prefix
define("pa_delete_old_thumbs", mktime(00,00,00,01,01,2005)); // DELETE all thumbs older than this date(in UNIX time) - so use mktime to generate it
// int mktime ( int hour, int minute, int second, int month, int day, int year [, int is_dst])
define("pa_thumb_background", "ffffff"); // thumb background-color in RGB
define("pa_thumb_quality", 65); // JPEG compresion for thumbs (100 is best, 0 is worst)
define("pa_thumb_resample", true); // RESAMPLED pictures are better, but generating is slower!
define("pa_image_show_thumb_size", 80); //Size of thumbs
define("pa_leftframe_min_width", 200); // minimal width of left frame
define("pa_sort_by", "name"); // sort image by ("name" OR "time")
define("pa_sort_order", "asc"); // sort image order ("asc" OR "desc")
define("pa_image_add_logo", false); //true = add logo over image (slower), false = or show image directly
define("pa_slideshow_enabled", true); // Enable or disable slideshow feature... [true/false]
define("pa_slideshow_timer", 10); // Default slideshow timer... [time in secs]
define("pa_slideshow_times", serialize(array(5,10,15,20,30))); // Slideshow times... [in seconds]
define("pa_home_url", pa_auto_home_url()); // pa_auto_home_url() - autodetect, OR absolute URI (ex. http://devil/webpub/phpFotoAlbum2/)
define("pa_dir_tree_cache_file", "./photoalbum_cache/dir_tree.cache"); // path to cache file, where DIR TREE will be cached (optional)
/////////////////
@ini_set("arg_separator.output","&amp;");
@ini_set("zend.ze1_compatibility_mode", "off"); // FIX zend.ze1_compatibility_mode
/////////////////
// DO NOT EDIT //
/////////////////
/* returns full absolute http url needed i.e. for Location header redirects */
function pa_auto_home_url() {
	$host = "http".($_SERVER["HTTPS"] == "on" ? "s" : "")."://".
		// www.server.cz(:port)
		$_SERVER["HTTP_HOST"].($_SERVER["SERVER_PORT"] != 80 ? ":".$_SERVER["SERVER_PORT"] : "");
	// go to root?
	$script_dir = ($relurl{0} != "/" ? dirname($_SERVER["SCRIPT_NAME"])."/" : "").dirname($relurl);
	// $arr_dir now holds every dir name down to script
	$arr_dir = explode("/", $script_dir);
	// put canonicalized path parts here
	$arr_realpath = array();
	// now go throught the dirs
	for ($i = $j = 0; $i < count($arr_dir); $i++)
		// if we have empty dir or . (i.e. from exploding // or /./) ignore it
		if ($arr_dir[$i] != "" && $arr_dir[$i] != ".") {
			// if we have ".." roll-back, but not beyond root
			if ($arr_dir[$i] == "..")
				$j = ($j > 0 ? $j - 1 : 0);
			// store part
			else
				$arr_realpath[$j++] = $arr_dir[$i];
		}
	// add filename
	$arr_realpath[$j] = basename($relurl);
	// remove rolled-back parts
	array_splice($arr_realpath, $j + 1);
	return("$host/".implode("/", $arr_realpath));
}
?>