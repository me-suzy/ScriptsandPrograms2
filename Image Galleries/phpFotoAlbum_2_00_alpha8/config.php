<?php
$default_skin="default"; //default skin
$default_language="select"; //default language - enter "select" to force user to select language on enter
$image_save_thumb=true; // FALSE is VERY SLOW - thumbs will not be saved.
$delete_old_thumbs=mktime(00,00,00,05,01,2004); // DELETE all thumbs older than this date(in UNIX time) - so use mktime to generate it
// int mktime ( int hour, int minute, int second, int month, int day, int year [, int is_dst])
$thumb_quality=65; // JPEG compresion for thumbs (100 is best, 0 is worst)
$thumb_resample=true; // RESAMPLED pictures are better, but generating is slower!
$thumb_show_info=0; // Show information over thumb. 0 - NO INFO, 1 - ONLY NAME, 2 - ALL.
$allow_direct_original=true; // direct original show
$image_resample=true; // use FALSE only when your GD and PHP doesn't support RESAMPLING!!!
$default_quality=70; // image_show defualt quality (100 is best, 0 is worst)
$default_res="800x600"; // image_show default resultion [res. or "orig"]
$default_show="thumb"; // Default show of files ["list" / "thumb"]
$image_show_thumb_size=50; //Size of thumbs for "menu2" (while showing the image)
$slideshow_enabled=true; // Enable or disable slideshow feature... [true/false]
$slideshow_timer=5; // Default slideshow timer... [time in secs]
$zip_download=true; // Enable ZIP download function [true / false]
$home_url="auto"; // auto - autodetect, OR absolute URI (ex. http://devil/webpub/phpFotoAlbum2/)
@ini_set("arg_separator.output","&amp;");
/////////////////
// DO NOT EDIT //
/////////////////
/* returns full absolute http url needed i.e. for Location header redirects */
if (strtolower($home_url)=="auto"){
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
	$home_url="$host/".implode("/", $arr_realpath);
}
?>