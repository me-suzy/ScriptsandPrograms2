<?php

//  Form Title
$GLOBALS["tFormTitle"] = 'maintain server settings';

//  Form Field Headings
$GLOBALS["tGzipSetting"] = 'Use gzip compression';
$GLOBALS["tSecureServer"] = 'Secure server';
$GLOBALS["tGzipTest"] = 'Test compression';
$GLOBALS["tGzipSupported"] = 'Your server supports GZIP compression.';
$GLOBALS["tGzipUnsupported"] = 'Your server does not support GZIP compression.';
$GLOBALS["tMultiSite"] = 'Multi-Site enabled';
$GLOBALS["tMultiSiteAuthors"] = 'Multi-Site with common users';
$GLOBALS["tMultiLanguage"] = 'Multi-Language enabled';
$GLOBALS["tMultiTheme"] = 'Multi-Theme enabled';
$GLOBALS["tPageTimer"] = 'Display page generation time';
$GLOBALS["tDefaultLanguage"] = 'Default language';
$GLOBALS["tDateFormat"] = 'Date Format Mask';
$GLOBALS["tTimezone"] = 'Server timezone';
$GLOBALS["tFrameSetting"] = 'Frames/No frames';
$GLOBALS["tVisitorStats"] = 'Enable access statistics';

//  Form Block Titles
$GLOBALS["thServerOptions"] = 'Server Options';
$GLOBALS["thezContentsOptions"] = 'ezContents Options';

//  Form Field Options
$GLOBALS["tGzipCompression"] = 'Compress text';
$GLOBALS["tGzipNoCompression"] = 'Don\'t compress';
$GLOBALS["tTimerDisplay"] = 'Display timer';
$GLOBALS["tTimerNoDisplay"] = 'Don\'t display timer';
$GLOBALS["tFrames"] = 'Frames';
$GLOBALS["tNoFrames"] = 'No frames';

//  Form Text Description
$GLOBALS["tDetails"] = 'This form allows you to set some global values for the way your site appears.';
$GLOBALS["hGzipSetting"] = 'Set this flag to enable gzip compression if your server supports it.';
$GLOBALS["hSecureServer"] = 'If your web server supports https, check this for secure login.<br /><br /><B>Note:-</B> Functionality not yet implemented.';
$GLOBALS["hMultiSite"] = 'Set this flag to \'Yes\' if you intend to run more than one ezContents site on this server.';
$GLOBALS["hMultiSiteAuthors"] = 'Set this flag to \'Yes\' if you wish to allow users access to all sites on this server in multi-site mode.';
$GLOBALS["hMultiLanguage"] = 'Set this flag to \'Yes\' if you intend to manage your site in more than one language.';
$GLOBALS["hMultiTheme"] = 'Set this flag to \'Yes\' if you want to enable alternative themes for your site.';
$GLOBALS["hPageTimer"] = 'Enable the timer to display page generation times.<br />This is primarily a diagnostic tool for the developers of ezContents in testing the efficiency of the code.';
$GLOBALS["hDefaultLanguage"] = 'Set the default language for your site.';
$GLOBALS["hDateFormat"] = 'Formatting mask for displaying dates.<br /><br />Codes that can be used are based on the Open Group standard, and include:<br /><ul><li><b>%a</b> = day of week (3 letters)<br />eg. Fri<li><b>%A</b> = day of week (long)<br />eg. Friday<li><b>%d</b> = day of month (2 digits)<br />eg. 01<li><b>%e</b> = day of month (1 or 2 digit)<br />eg. 1<br />(Doesn\'t seem to work even though defined as part of the Open Group standard.)<li><b>%b</b> = month (textual, 3 letters)<br />eg. Mar<li><b>%B</b> = month (textual, long)<br />eg. March<li><b>%m</b> = month (2 digits)<br />eg. 03<li><b>%Y</b> = year (4 digits)<br />eg. 2002<li><b>%y</b> = year (2 digits)<br />eg. 02<br /><br /><li><b>%x</b> Standard format for locale<br />(Depends on the server.)</ul>';
$GLOBALS["hTimezone"] = 'Timezone to show in date/time displays.<br />e.g. \"UST\", \"UST+1\"<br />If your <i>date format mask</i> includes the time, set this to give readers around the world an indication of the time in relation to their own timezone; otherwise leave it blank.';
$GLOBALS["hFrameSetting"] = 'Show the site in frames or use a frameless version.';
$GLOBALS["hVisitorStats"] = 'Do you wish to maintain access statistics for your site?';

?>
