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
define("pa_header_include_subdir", false);
include_once "./photoalbum/html_header.php";
?>
<body>
<h1>SB|photoAlbum <?php echo $pa_core->version; ?></h1>
<div style="padding-top: 10px; font-size: 10px;"><strong><?php echo pa_txt_author; ?>:</strong><br />Ladislav Soukup</div>
<div style="padding-top: 10px; font-size: 10px;"><strong><?php echo pa_txt_homepage; ?>:</strong><br /><span style="cursor: pointer;" onclick="OpenWindowExt('http://php.soundboss.cz',screen.width, screen.height)">php.SOUNDBOSS.cz</span></div>

<div style="padding-top: 10px; font-size: 10px;"><strong><?php echo pa_txt_poweredby; ?>:</strong>
<span style="cursor: pointer;" onclick="OpenWindowExt('http://www.php.net/', screen.width, screen.height)">PHP</span>,
<span style="cursor: pointer;" onclick="OpenWindowExt('http://www.destroydrop.com/javascripts/tree/', screen.width, screen.height)">dTree</span>
</div>

<div style="padding-top: 10px; text-align: center;">
<div class="toolbar_btn" onclick="self.close()"><?php echo pa_txt_close_window; ?></div>
</div>
</body>
</html>
