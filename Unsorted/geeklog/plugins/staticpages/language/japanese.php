<?php

###############################################################################
# lang.php
# This is the english language page for the Geeklog Static Page Plug-in!
#
# Copyright (C) 2001 Tony Bibbs
# tony@tonybibbs.com
# Tranlated by SaY
# sakata@ecofirm.com
#
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
#
###############################################################################

###############################################################################
# Array Format: 
# $LANGXX[YY]:	$LANG - variable name
#		  	XX - file id number
#			YY - phrase id number
###############################################################################


$LANG_STATIC= array(
    newpage => '¿·µ¬¥Ú¡¼¥¸',
    adminhome => '´ÉÍý¥Ú¡¼¥¸',
    staticpages => 'ÀÅÅª¡Ê¸ÇÄê¡Ë¥Ú¡¼¥¸',
    staticpageeditor => 'ÀÅÅª¥Ú¡¼¥¸¤ÎÊÔ½¸',
    writtenby => 'Ãø¼Ô¡§',
    date => 'ºÇ½ª¹¹¿·Æü¡§',
    title => '¥¿¥¤¥È¥ë',
    content => 'ÆâÍÆ',
    hits => '·ï¤Î±ÜÍ÷',
    staticpagelist => 'ÀÅÅª¥Ú¡¼¥¸°ìÍ÷',
    url => 'URL',
    edit => 'ÊÔ½¸',
    lastupdated => 'ºÇ½ª¹¹¿·Æü¡§',
    pageformat => '¥Ú¡¼¥¸¥Õ¥©¡¼¥Þ¥Ã¥È',
    leftrightblocks => 'º¸±¦¤Î¥Ö¥í¥Ã¥¯ÉÕ¤­',
    blankpage => '¶õÇò¥Ú¡¼¥¸',
    noblocks => '¥Ö¥í¥Ã¥¯¤Ê¤·',
    leftblocks => 'º¸Â¦¥Ö¥í¥Ã¥¯¤Î¤ß',
    addtomenu => '¥á¥Ë¥å¡¼¤ËÄÉ²Ã¤¹¤ë',
    label => '¥é¥Ù¥ë',
    nopages => 'ÀÅÅª¥Ú¡¼¥¸¤¬¥·¥¹¥Æ¥à¤Ë¤¢¤ê¤Þ¤»¤ó',
    save => 'ÊÝÂ¸',
    preview => '¥×¥ì¥Ó¥å¡¼',
    delete => 'ºï½ü',
    cancel => '¥­¥ã¥ó¥»¥ë',
    access_denied => '¥¢¥¯¥»¥¹¤¬µñÈÝ¤µ¤ì¤Þ¤·¤¿',
    access_denied_msg => 'ÀÅÅª¥Ú¡¼¥¸¤Î´ÉÍý¥Ú¡¼¥¸¤Ë¸¢¸Â¤Ê¤·¤Ç¥¢¥¯¥»¥¹¤·¤è¤¦¤È¤·¤Þ¤·¤¿¡£¸¢¸Â¤Î¤Ê¤¤¥¢¥¯¥»¥¹¤Ï¤¹¤Ù¤Æµ­Ï¿¤µ¤ì¤Þ¤¹¤Î¤Ç¤´Ãí°Õ¤¯¤À¤µ¤¤¡£',
    all_html_allowed => '¤¹¤Ù¤Æ¤ÎHTML¤¬ÍøÍÑ¤Ç¤­¤Þ¤¹¡£',
    results => 'Static Pages Results',
    author => 'Ãø¼Ô¡§',
    no_title_or_content => '¾¯¤Ê¤¯¤È¤â¡¢<b>¥¿¥¤¥È¥ë</b>¤È<b>ÆâÍÆ</b>¤Ïµ­Æþ¤·¤Æ¤¯¤À¤µ¤¤¡£',
    no_such_page_logged_in => $_USER['username'].'¤µ¤ó¡¢¤´¤á¤ó¤Ê¤µ¤¤',
    no_such_page_anon => '¤Þ¤º¥í¥°¥¤¥ó¤·¤Æ¤¯¤À¤µ¤¤¡£',
    no_page_access_msg => "¤³¤ÎÌäÂê¤Ï¡¢¤Þ¤À¥í¥°¥¤¥ó¤·¤Æ¤¤¤Ê¤¤¤«¡¢¤½¤â¤½¤â¤³¤Î¥µ¥¤¥È¡Ê{$_CONF["site_name"]}¡Ë¤Î¥á¥ó¥Ð¡¼¤Ç¤Ï¤Ê¤¤¤¿¤á¤À¤È¹Í¤¨¤é¤ì¤Þ¤¹¡£{$_CONF["site_name"]}¤Ë<a href=\"{$_CONF['site_url']}/users.php?mode=new\"> ¥á¥ó¥Ð¡¼ÅÐÏ¿</a>¤¹¤ë¤«¡¢Å¬ÀÚ¤Ê¥¢¥¯¥»¥¹¸¢¤ò´ÉÍý¼Ô¤«¤é¼èÆÀ¤·¤Æ¤¯¤À¤µ¤¤¡£",
    php_msg => 'PHP: ',
    php_warn => 'Ãí°Õ¡§¤³¤Î¥ª¥×¥·¥ç¥ó¤òÍ­¸ú¤Ë¤¹¤ë¤È¡¢¤¢¤Ê¤¿¤Î¥Ú¡¼¥¸¤Ë´Þ¤Þ¤ì¤ëPHP¥³¡¼¥É¤¬¼Â¹Ô¤µ¤ì¤Þ¤¹¡£ÍøÍÑ¤Ë¤ÏºÇ¿·¤ÎÃí°Õ¤òÊ§¤Ã¤Æ¤¯¤À¤µ¤¤¡£',
    exit_msg => 'Exit Type: ',
    exit_info => '¡ÖÍ×¥í¥°¥¤¥ó¡×¡§¥Á¥§¥Ã¥¯¤ò¤·¤Ê¤¤¾ì¹ç¤Ë¤ÏÄÌ¾ï¤Î¥»¥­¥å¥ê¥Æ¥£¥Á¥§¥Ã¥¯¤¬Å¬ÍÑ¤µ¤ì¡¢¥á¥Ã¥»¡¼¥¸¤¬É½¼¨¤µ¤ì¤Þ¤¹¡£',
    deny_msg => '¥Ú¡¼¥¸¤Ø¤Î¥¢¥¯¥»¥¹¤ÏµñÈÝ¤µ¤ì¤Þ¤·¤¿¡£¥Ú¡¼¥¸¤¬°ÜÆ°¤Þ¤¿¤Ïºï½ü¤µ¤ì¤¿¤«¡¢¸¢¸Â¤¬¤Ê¤¤¤«¤Î¤¤¤º¤ì¤«¤Ç¤¹¡£',
    stats_headline => 'ÀÅÅª¥Ú¡¼¥¸¡Ê¾å°Ì£±£°¡Ë',
    stats_page_title => '¥¿¥¤¥È¥ë',
    stats_hits => '·ï¤Î±ÜÍ÷',
    stats_no_hits => 'ÀÅÅª¥Ú¡¼¥¸¤¬¤Ê¤¤¤«¡¢±ÜÍ÷¼Ô¤¬¤¤¤Ê¤¤¤«¤Î¤É¤Á¤é¤«¤Ç¤¹¡£',
    id => 'ID¡Ê¥Ú¡¼¥¸Ì¾¡Ë',
    duplicate_id => '»ØÄê¤·¤¿ID¤Ï¤¹¤Ç¤Ë»È¤ï¤ì¤Æ¤¤¤Þ¤¹¡£ÊÌ¤ÎID¤ò¤´»ÈÍÑ¤¯¤À¤µ¤¤¡£',
    instructions => '¥Ú¡¼¥¸¤òÊÔ½¸¡¦ºï½ü¤¹¤ë¤¿¤á¤Ë¤Ï¡¢°Ê²¼¤Î¥Ú¡¼¥¸ÈÖ¹æ¤ò¥¯¥ê¥Ã¥¯¤·¤Æ¤¯¤À¤µ¤¤¡£¥Ú¡¼¥¸¤ò±ÜÍ÷¤¹¤ë¾ì¹ç¤Ï¡¢¥¿¥¤¥È¥ë¤ò¥¯¥ê¥Ã¥¯¤·¤Æ¤¯¤À¤µ¤¤¡£¿·¤·¤¤¥Ú¡¼¥¸¤òºîÀ®¤¹¤ë¾ì¹ç¤Ë¤Ï¡¢¡Ö¿·µ¬¡×¥Ü¥¿¥ó¤ò²¡¤·¤Æ¤¯¤À¤µ¤¤¡£[C]¤ò²¡¤¹¤È¡¢´ûÂ¸¤Î¥Ú¡¼¥¸¤Î¥³¥Ô¡¼¤òºîÀ®¤Ç¤­¤Þ¤¹¡£',
    centerblock => '¥»¥ó¥¿¡¼¥Ö¥í¥Ã¥¯: ',
    centerblock_msg => '¥Á¥§¥Ã¥¯¤¹¤ë¤È¡¢¥È¥Ã¥×¥Ú¡¼¥¸¤ÎÃæ¿´ÉôÊ¬¤ËÉ½¼¨¤µ¤ì¤Þ¤¹¡£',
    topic => 'ÏÃÂê: ',
    position => '»Ô: ',
    all_topics => '¤¹¤Ù¤Æ',
    no_topic => '¥Û¡¼¥à¥Ú¡¼¥¸¤Î¤ß',
    position_top => '¥Ú¡¼¥¸¤ÎºÇ¾åÉô',
    position_feat => 'ÃíÌÜµ­»ö¤Î²¼',
    position_bottom => '¥Ú¡¼¥¸¤Î²¼',
    position_entire => '¥Ú¡¼¥¸Á´ÂÎ',
    head_centerblock => '¥»¥ó¥¿¡¼¥Ö¥í¥Ã¥¯',
    centerblock_no => '¤¤¤¤¤¨',
    centerblock_top => '¾åÉô',
    centerblock_feat => 'ÃíÌÜµ­»ö',
    centerblock_bottom => '²¼Éô',
    centerblock_entire => '¥Ú¡¼¥¸Á´ÂÎ'
);

?>
