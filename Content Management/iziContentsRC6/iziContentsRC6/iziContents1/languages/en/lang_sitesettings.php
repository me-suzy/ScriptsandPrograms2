<?php

//  Form Title
$GLOBALS["tFormTitle"] = 'maintain site settings';

//  Form Block Titles
$GLOBALS["thTitles"] = 'Site Titles';
$GLOBALS["thSecurity"] = 'Security';
$GLOBALS["thTopFrame"] = 'Top Frame Settings';
$GLOBALS["thUserdataFrame"] = 'Userdata Frame Settings';
$GLOBALS["thMenus"] = 'Menu Settings';
$GLOBALS["thContents"] = 'Content Settings';
$GLOBALS["thBanners"] = 'Banner Settings';
$GLOBALS["thFooter"] = 'Footer Settings';

//  Form Field Headings
$GLOBALS["tSiteTitle"] = 'Site title';
$GLOBALS["tSiteDescr"] = 'Site description';
$GLOBALS["tSiteKeywords"] = 'Site keywords';
$GLOBALS["tSiteWidth"] = 'Site width';
$GLOBALS["tSiteHeight"] = 'Site height';
$GLOBALS["tSectionSecurity"] = 'Section-level security';
$GLOBALS["tTopFrameHeight"] = 'Top frame height';
$GLOBALS["tTopHTML"] = 'Top HTML';
$GLOBALS["tTopMenu"] = 'Include top-menu level in header';
$GLOBALS["tTopMenuHeight"] = 'Top-menu frame height';
$GLOBALS["tUserdata"] = 'Include userdata frame';
$GLOBALS["tUserdataWidth"] = 'Userdata frame width';
$GLOBALS["tMenuWidth"] = 'Menu frame width';
$GLOBALS["tMenuFrameAlign"] = 'Menu frame alignment';
$GLOBALS["tMenuExpand"] = 'Expand menus on click';
$GLOBALS["tMenuCollapse"] = 'Collapse menus on click';
$GLOBALS["tMenuHover"] = 'Display menu text on hover';
$GLOBALS["tLRContent"] = 'Enable left/right content frames';
$GLOBALS["tRightWidth"] = 'Right column width';
$GLOBALS["tColBreak"] = 'left/right content column break image';
$GLOBALS["tBreadcrumb"] = 'Enable \'breadcrumb\' display';
$GLOBALS["tBreadcrumbSeparator"] = '\'breadcrumb\' separator';
$GLOBALS["tBookmark"] = 'Enable bookmarking';
$GLOBALS["tBanners"] = 'Show banners';
$GLOBALS["tFooter"] = 'Include footer frame';
$GLOBALS["tFooterHeight"] = 'Footer frame height';
$GLOBALS["tFooterText"] = 'Footer text';

//  Form Field Options
$GLOBALS["tHeaderPanel"] = 'In Header Frame';
$GLOBALS["tMenuPanel"] = 'In Menu Frame';
$GLOBALS["tNoUserdata"] = 'None';

$GLOBALS["tNoBanners"] = 'Don\'t show banners';
$GLOBALS["tBannersT"] = 'Show banners on top';
$GLOBALS["tBannersB"] = 'Show banners on bottom';
$GLOBALS["tBannersTB"] = 'Show banners on top and bottom';

//  Form Text Description
$GLOBALS["tDetails"] = 'This form allows you to set some global values for the way your site appears.';
$GLOBALS["hSiteTitle"] = 'A site title will show up in the title bar.<br />';
$GLOBALS["hSiteDescr"] = 'Specifies the HTML meta tag of type description.<br />';
$GLOBALS["hSiteDescr"] = 'Useful for indexing in searching engines.';
$GLOBALS["hSiteKeywords"] = 'Specifies the HTML meta tag of type keywords.<br />Useful for indexing in searching engines. Put here all the keywords of your site separated by commas.';
$GLOBALS["hSiteWidth"] = 'If you want the your site to open in central window in the browser, this is the width of the window.';
$GLOBALS["hSiteHeight"] = 'If you want the your site to open in central window in the browser, this is the height of the window.';
$GLOBALS["hSectionSecurity"] = 'If section-level security is enabled, you can restrict article authors and contributors to specific sections of your site.';
$GLOBALS["hTopFrameHeight"] = 'Set the height of the top header frame.';
$GLOBALS["hTopHTML"] = 'Include any additional HTML for the top frame here.';
$GLOBALS["hTopMenu"] = 'Indicate whether a top-menu should be displayed in the header bar.<br />If you enable this feature, you must also specify the Top Menu Height.<br /><br />If you change this setting, refresh the admin menu in your browser once you\'ve saved the change to refresh the admin options available to you.';
$GLOBALS["hTopMenuHeight"] = 'Set the height of the header top-menu bar if one is being displayed.<br />This is in addition to the Top Frame Height.';
$GLOBALS["hUserdata"] = 'Indicate whether the user login (\"userdata\") frame will appear in right of header bar.<br />If you enable this feature, you must also specify the Userdata Frame Width.';
$GLOBALS["hUserdataWidth"] = 'Specify the width of the Userdata frame if you\'ve enabled that feature.';
$GLOBALS["hMenuWidth"] = 'Adjust the width of the left sidebar menu frame.';
$GLOBALS["hMenuFrameAlign"] = 'Should the menu be displayed to the left or right of the content frame?<br />The \'Automatic\' option will display the menu on the left if the site language is displayed left-to-right; and on the right if the site language is right-to-left.';
$GLOBALS["hMenuExpand"] = 'If you choose to expand the menus only one menu will ever be expanded with its submenus.<br />If it is set to \'No\', <B><I>all</I></B> menus will always be expanded.';
$GLOBALS["hMenuCollapse"] = 'If you choose to collapse the menus, clicking on an expanded menu will collapse it again.<br />Note that you must have Expand menus set to \'Yes\' to enable this feature, and that only one menu will ever be expanded at a time.';
$GLOBALS["hMenuHover"] = 'Display the menu text in a title box if the cursor is hovered over the menu entry.';
$GLOBALS["hLRContent"] = 'This determines whether content pages can be split across the left and right of the content frame.<br />If you enable this feature, you must also specify a Right Frame Width.';
$GLOBALS["hRightWidth"] = 'This is the width for the right column in the content frame if you have enabled this feature.';
$GLOBALS["hColBreak"] = 'If you have enabled the split content frame, you can also specify a column break image here.';
$GLOBALS["hBreadcrumb"] = 'If enabled, a \'breadcrumb\' link showing the current menu selections is displayed at the top of the page.';
$GLOBALS["hBreadcrumbSeparator"] = 'If the \'breadcrumb\' display is enabled, this is the separator used between the different menu levels.';
$GLOBALS["hBookmark"] = 'Determines whether the \'bookmark\' this page function is enabled.';
$GLOBALS["hBanners"] = 'Indicate here if you want to show banners on your site.<br />This setting also allows you to show whether the banners appear at the top of the page, on the bottom or on both.';
$GLOBALS["hFooter"] = 'Include a footer frame at the base of your page. If you enable this, you must specify a Footer Height and (optionally) Footer Text.';
$GLOBALS["hFooterHeight"] = 'Specify the height of the footer frame if you have enabled this feature.';
$GLOBALS["hFooterText"] = 'If you have enabled the footer frame, specify the content of the frame here as text or HTML.';

//  Error Messages
$GLOBALS["eTopFrame"] = 'Top frame height is not a number.';
$GLOBALS["eTopmenuFrame"] = 'Top menu frame height is not a number.';
$GLOBALS["eUserdataFrame"] = 'Userdata frame width is not a number.';
$GLOBALS["eMenuFrame"] = 'Menu frame width is not a number.';
$GLOBALS["eRightFrame"] = 'Right frame width is not a number.';
$GLOBALS["eBottomFrame"] = 'Bottom frame height is not a number.';

?>
