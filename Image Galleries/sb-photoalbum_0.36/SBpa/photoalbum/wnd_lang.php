<?php
/*
******************************************************************************************
** SB|photoAlbum                                                                        **
** Copyright (C)2005 Ladislav Soukup                                                    **
**                                                                                      **
** URL: http://php.soundboss.cz                                                         **
** URL: http://www.soundboss.cz                                                         **
******************************************************************************************
*/
chdir("..");
include_once "./pa_config.php";
include_once "./photoalbum/core.php";
$pa_core = new pa_core();
$dir = "./photoalbum/lang/";
$files = scandir($dir);
if (is_array($files)) {
	foreach($files as $file) {
		$full_path = $dir . $file;
		if (is_file($full_path)) {
			$lang_file = explode(".", $file);
			if ($lang_file[1] == "php") {
				$lang_codes[] = $lang_file[0];
			}
		}
	}
}
define("pa_header_include_subdir", false);
include_once "./photoalbum/html_header.php";
?>
<body>
<h1 id="img_name"><?php echo pa_txt_select_lang; ?></h1>
<div class="hline">&nbsp;</div>
<div style="text-align: center;">
<?php if (!empty($lang_codes)) { foreach($lang_codes as $lang_code) { ?>
<img src="img/flags/<?php echo $lang_code; ?>.gif" alt="<?php echo $lang_code; ?>" class="lang_sel" onclick="pa_switch_lang('<?php echo $lang_code; ?>');" />
<?php } } ?>
</div>
<div style="padding-top: 10px; text-align: center;"><div class="toolbar_btn" onclick="self.close()"><?php echo pa_txt_close_window; ?></div></div>
<script language="JavaScript" type="text/javascript">
function pa_switch_lang(lang_code) {
	opener.location.href = "../pa_index.php?lang="+lang_code;
	self.close();
}
</script>
</body>
</html>