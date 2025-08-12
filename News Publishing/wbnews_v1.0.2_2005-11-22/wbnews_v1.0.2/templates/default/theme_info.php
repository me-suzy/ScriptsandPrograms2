<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 13th September 2005                     #||
||#     Filename: theme_info.php                         #||
||#                                                      #||
||########################################################||
/*========================================================*/

/* Errors */
$themeInfo['ERROR_SYSTEM_OFFLINE'] = $tpl->getTemplate('news_system_off');
$themeInfo['ERROR_NONEWS'] = $tpl->getTemplate('nonews');
$themeInfo['ERROR_INVALIDURL'] = $tpl->getTemplate('error_invalidurl');
$themeInfo['ERROR_NOCOMMENTS'] = "No Comments";
$themeInfo['ERROR_BANNED'] = "You are banned, you cannot post comments.";
$themeInfo['ERROR_FLOODFILTER'] = "The Administrator has set a flood filter.";
$themeInfo['ERROR_COMMENTS_DISALLOWED'] = "Comments are disallowed for this article.";

/* Small Templates */
$themeInfo['TPL_SENDFRIEND_LINK'] = '<a href="?action=sendto&amp;newsid={id}">Send to Friend</a>';
$themeInfo['TPL_AVATARIMAGE'] = '<img src="{image}" title="{title}" alt="{title}" style="float: left;" />';
$themeInfo['TPL_EMOTICON'] = '<img src="{image}" title="{name}" alt="{name}" />';
$themeInfo['TPL_PAGINATION'] = '<div style="border: 1px solid #999;background: #eee;padding: 5px;margin: 0 10% 0 10%;"><table style="width: 100%;" cellpadding="0"><tr><td>{prev}</td><td>{next}</td></tr></table></div>';
$themeInfo['TPL_PAGINATION_NEXT'] = '<div style="text-align: right;"><a href="?p={p}">Next Page</a></div>';
$themeInfo['TPL_PAGINATION_PREV'] = '<div style="text-align: left;"><a href="?p={p}">Previous Page</a></div';

?>
