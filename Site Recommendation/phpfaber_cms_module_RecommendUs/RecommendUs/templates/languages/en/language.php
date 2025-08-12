<?php

// -----------------------------------------------------------------------------
//
// phpFaber CMS v.1.0
// Copyright(C), phpFaber LLC, 2004-2005, All Rights Reserved.
// E-mail: products@phpfaber.com
//
// All forms of reproduction, including, but not limited to, internet posting,
// printing, e-mailing, faxing and recording are strictly prohibited.
// One license required per site running phpFaber CMS.
// To obtain a license for using phpFaber CMS, please register at
// http://www.phpfaber.com/i/products/cms/
//
// 02:09 AM 08/23/2005
//
// -----------------------------------------------------------------------------

if (!defined('INDEX_INCLUDED')) die('<code>You cannot access this file directly..</code>');

$_LNG['_HEADER_RU_FORM'] = 'Recommend Us';

$_LNG['_SETT_EN_M_TF'] = 'Notify admin each time "Tell a Friend" is used ?';
$_LNG['_SETT_FR_NUM'] = "Friens amount on the form";
$_LNG['_SETT_SYMBOL_LIMIT'] = "The symbols maximum amount in the message";

$_LNG['_FLD_RU_NAME'] = 'Name';
$_LNG['_FLD_RU_EMAIL'] = 'E-mail';
$_LNG['_FLD_RU_YOU'] = 'You';
$_LNG['_FLD_RU_FRIEND'] = 'Friend';
$_LNG['_FLD_RU_MSG'] = 'Message';

$_LNG['_MSG_ER_EMAIL_REQ'] = 'Friend\'s email address is required for %s (Friend %s)';
$_LNG['_MSG_ER_EMAIL'] = 'Invalid friend\'s email address pattern';
$_LNG['_MSG_ER_YOUR_NAME_REQ'] = 'Your name is required';
$_LNG['_MSG_ER_YOUR_EMAIL_REQ'] = 'Your email is required';
$_LNG['_MSG_ER_FRIENDS'] = 'At least one friend should be specified';

$_LNG['_MSG_ADM_EMAIL_SUBJ'] = 'Tell a Friend';
$_LNG['_MSG_THANK_YOU'] = 'Thank you. The email has been sent to';


/////////////////////////////
$_LNG['_HEADER_BANNERSLIST_PAGE'] = 'Banners list';
$_LNG['_HEADER_BANNERADD_PAGE'] = 'Add banner';
$_LNG['_HEADER_BANNEREDIT_PAGE'] = 'Edit banner';

$_LNG['_SETT_EN_DEFF_BAN'] = 'Enable default banner';
$_LNG['_SETT_EN_DEFF_BAN_NOTE'] = 'If there is no active banners in the zone, show default banner.';
$_LNG['_SETT_REFR_FREQ'] = 'Banners\' refreshing frequency, seconds';
$_LNG['_SETT_REFR_FREQ_NOTE'] = 'Set 0 to disable banners\' refreshing';

$_LNG['_TBL_TOTALBANNERS'] = 'Banners';

$_LNG['_TBL_LIST_NAME'] = 'Name / Descr.';
$_LNG['_TBL_LIST_ZONE'] = 'Zone';
$_LNG['_TBL_LIST_ACTIVE'] = 'Active';
$_LNG['_TBL_LIST_PEND'] = 'Pending';
$_LNG['_TBL_LIST_DATEEXP'] = 'Expired';
$_LNG['_TBL_LIST_DATEACT'] = 'Activ. date';
$_LNG['_TBL_LIST_IMPRS'] = 'Impressions / Max';
$_LNG['_TBL_LIST_CLICKS'] = 'Clicks / Max';
$_LNG['_TBL_LIST_RATIO'] = 'Ratio';
$_LNG['_TBL_LIST_ACTIONS'] = 'Actions';

$_LNG['_NOTE_LIST_DEF'] = 'Note: <b>default</b> - set default banner for certain zone.';

$_LNG['_FLD_BADD_NAME'] = 'Name';
$_LNG['_FLD_BADD_DESCR'] = 'Description';
$_LNG['_FLD_BADD_DESCR_NOTE'] = 'Brief description of banner to help identify it uses';
$_LNG['_FLD_BADD_HTML'] = 'HTML Code';
$_LNG['_FLD_BADD_HTML_NOTE'] = 'If HTML code is entered it is used instead of the banner url and the link url below.  This allows to use an external banner management system with its own linking system and text banners. Statistics for text banners is not tracked';
$_LNG['_FLD_BADD_IMAGE'] = 'Banner Image URL';
$_LNG['_FLD_BADD_LINK'] = 'Banner Link URL';
$_LNG['_FLD_BADD_STARTD'] = 'Start Date';
$_LNG['_FLD_BADD_EXPD'] = 'Expire Date';
$_LNG['_FLD_BADD_ENABLED'] = 'Enabled?';
$_LNG['_FLD_BADD_ZONE'] = 'Zone the banner ad will be placed in';
$_LNG['_FLD_BADD_ZONE_NOTE'] = 'You can define new zones <a href="'.$_CFG->PG_ADM.'?page=templates&amp;mod=BannerAds&amp;action=select&amp;file_name=zones.txt">here</a>';
$_LNG['_FLD_BADD_ICR'] = 'Impressions / Clicks / Ratio';
$_LNG['_FLD_BADD_MAXI'] = 'Maximum number of impressions';
$_LNG['_FLD_BADD_MAXC'] = 'Maximum number of clicks';
$_LNG['_FLD_BADD_ADDEDD'] = 'Date Added';

$_LNG['_MSG_NO_BANNERS'] = 'There are no banners on the site.';
$_LNG['_MSG_DELETE'] = 'Delete this banner?';

$_LNG['_MSG_A_ALL'] = 'All banners';
$_LNG['_MSG_A_ACTIVE'] = 'Active banners';
$_LNG['_MSG_A_PENDING'] = 'Pending banners';
$_LNG['_MSG_A_EXPIRED'] = 'Inactive/expired banners';
$_LNG['_MSG_UPDATED_SUCCESS'] = 'Banner updated successfully';
$_LNG['_MSG_UNLIMITED'] = 'Unlimited';

$_LNG['_CMD_DEF'] = 'default';
$_LNG['_CMD_EDIT'] = 'edit';
$_LNG['_CMD_DELETE'] = 'delete';

$_LNG['_MSG_ER_REQNAME'] = 'Name is required';
$_LNG['_MSG_ER_REQBAN'] = 'Banner URL or HTML Code is required';
$_LNG['_MSG_ER_REQZONE'] = 'Zone is required';
$_LNG['_MSG_ER_NUMIMP'] = 'Maximum number of impreccions must be numeric';
$_LNG['_MSG_ER_NUMCLK'] = 'Maximum number of clicks must be numeric';
$_LNG['_MSG_ER_REFR_FREQ'] = 'Banners\' refreshing frequency value is wrong';

?>