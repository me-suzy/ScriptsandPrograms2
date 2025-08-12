<?php
// GENERAL INFORMATION
// {
	// Forum name
	//
	//  This is the name of your bulletin board; it is displayed several times
	//  throughout the forum.
	//
	$CFG['general']['name'] = 'Name Of Your Forums Goes Here';

	// Copyright notice
	//
	//  Displayed at the bottom of each forum page, this text
	//  notifies your users of your copyright ownership.
	//  (The forum content does not have to be a registered copyright
	//  work; under US Federal law, you are generally given copyright
	//  ownership automatically for any work that you create.)
	//
	$CFG['general']['copyright'] = 'Copyright &copy; Your Name/Organization';

	// Administrator's e-mail adddress
	//
	//  This is the e-mail address of the bulletin board's administrator.
	//  It is displayed in various e-mails sent by the forum as well as
	//  at the bottom of the forum pages as a 'Contact Us' link.
	//
	$CFG['general']['admin']['email'] = 'you@yourdomain.com';
// }


// SETTINGS
// {
	// GZip aggression level
	//
	//  This is the level of aggression the forum uses when
	//  determining whether or not to compress the forum pages
	//  before sending them to the user. The levels of aggression are
	//  as follows:
	//
	//  0 - Compression is disabled; pages will not be GZipped before
	//      being sent, even if the user's browser specifies that it
	//      is supported.
	//  1 - Compression is used only when the user's browser explicitly
	//      specifies its support.
	//  2 - Compression is enabled when either the browser explicitly
	//      specifies it's supported or -- based on the browser's User
	//      Agent header -- it is probable that the browser supports
	//      GZipped Web pages.
	//  3 - Compression is always enabled; pages will always be GZipped
	//      before being sent, even if the user's browser does not
	//      specify it is supported.
	//
	$CFG['general']['gzip']['aggression_lvl'] = 1;

	// GZip compression level
	//
	//  This is the level of compression the forums will use when
	//  compressing pages (if enabled). Acceptable values are 0-9
	//  inclusive; 0 is no compression while 9 is full compression.
	//
	//  4 is the recommended compression level.
	//
	$CFG['general']['gzip']['compression_lvl'] = 4;

	// Enable Quick Reply?
	//
	//  This determines whether or not the Quick Reply form is displayed
	//  at the bottom of each thread.
	//
	//  TRUE = Enabled, FALSE = Disabled
	//
	$CFG['general']['quickreply'] = TRUE;

  // Time zone offsets
	//
	// Display time zone offset (in seconds)
	//
	//  This is the UTC/GMT time zone offset used when displaying
	//  times on the forum for users who are not logged into their
	//  account or are not registered. For example, if your visitors
	//  are mostly from Central Time (US & Canada), then you might
	//  set this value to '-21600' (GMT-6).
	//
	$CFG['time']['display_offset'] = 0;
	//
	// Display Daylight Saving Time/Summer Time offset (in seconds)
	//
	//  This is the Daylight Saving Time/Summer Time offset used when
	//  displaying times on the forum for users who are not logged into
	//  their account or are not registered. For example, if your visitors
	//  are mostly residing at a location observing Daylight Saving Time
	//  or Summer Time by one hour, then you might set this value to
	//  '3600'.
	//
	$CFG['time']['dst'] = TRUE;
	$CFG['time']['dst_offset'] = 3600;
// }


// REGISTRATION
// {
	// Enable Image Verification?
	//
	//  This determines whether or not users must enter text from
	//  a dynamically-generated image when registering a new
	//  account. It can prevent automated processes from creating
	//  accounts, but it can also be annoying for users.
	//
	//  The GD graphics library is required for this feature.
	//
	$CFG['reg']['verify_img'] = TRUE;
// }


// STYLE
// {
	// Table and page styles
	$CFG['style']['page']['bgcolor'] = '#395A84';
	$CFG['style']['forum']['bgcolor'] = '#FFFFFF';
	$CFG['style']['forum']['txtcolor'] = '#000000';

	$CFG['style']['table']['bgcolor'] = '#395A84';
	$CFG['style']['table']['cella'] = '#F1F1F1';
	$CFG['style']['table']['cellb'] = '#DFDFDF';

	$CFG['style']['table']['width'] = '100%';
	$CFG['style']['content_table']['width'] = '100%';

	$CFG['style']['table']['heading']['bgcolor'] = '#395A84';
	$CFG['style']['table']['heading']['txtcolor'] = '#EEEEFF';
	$CFG['style']['table']['section']['bgcolor'] = '#395A84';
	$CFG['style']['table']['section']['txtcolor'] = '#EEEEFF';

	$CFG['style']['table']['timecolor'] = '#2B4362';
	$CFG['style']['errors'] = '#FF0000';

	$CFG['style']['credits'] = '#EEEEFF';
	$CFG['style']['stats'] = '#EEEEFF';
	$CFG['style']['stats_bold'] = '#FFFFFF';

	// Calendar colors
	$CFG['style']['calcolor']['datea']['bgcolor'] = '#FFFFFF';
	$CFG['style']['calcolor']['datea']['txtcolor'] = '#999999';
	$CFG['style']['calcolor']['dateb']['bgcolor'] = '#DFDFDF';
	$CFG['style']['calcolor']['dateb']['txtcolor'] = '#000000';
	$CFG['style']['calcolor']['today']['bgcolor'] = '#F1F1F1';
	$CFG['style']['calcolor']['today']['txtcolor'] = '#000000';
//FIXME	$CFG['style']['calcolor']['birthday'];
//	$CFG['style']['calcolor']['pubevent'];
//	$CFG['style']['calcolor']['privevent'];

	// Link styles
	$CFG['style']['l_normal']['l'] = '#FF0000';
	$CFG['style']['l_normal']['v'] = '#FF0000';
	$CFG['style']['l_normal']['a'] = '#FF0000';
	$CFG['style']['l_normal']['h'] = '#FF0000';
	//$CFG['style']['l_cat']['l'] = '#EEEEFF';
	//$CFG['style']['l_cat']['v'] = '#EEEEFF';
	//$CFG['style']['l_cat']['a'] = '#EEEEFF';
	//$CFG['style']['l_cat']['h'] = '#EEEEFF';
// }


// MISCELLANEOUS
// {
	$CFG['uploads']['oktypes'] = array('bmp', 'gif', 'jpg', 'jpeg', 'png', 'txt', 'zip', 'rar', 'gz', '7z');
	$CFG['uploads']['maxsize'] = 102400;
	$CFG['avatars']['maxsize'] = 204800;
	$CFG['avatars']['maxdems'] = 50;
	$CFG['paths']['smilies'] = 'images/smilies/';
	$CFG['paths']['avatars'] = 'images/avatars/';
	$CFG['default']['postsperpage'] = 10;
	$CFG['default']['threadsperpage'] = 40;
	$CFG['default']['weekstart'] = 0;
	$CFG['parsing']['showimages'] = TRUE;
// }
?>