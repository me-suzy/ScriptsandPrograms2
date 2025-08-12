<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 16th August 2005                        #||
||#     Filename: theme_info.php                         #||
||#                                                      #||
||########################################################||
/*========================================================*/

// alternate row colours
$themeInfo['alternate_color1'] = ' style="background: #edf3fe;"';
$themeInfo['alternate_color2'] = '';

// no records - this is for things such as news, emoticons etc which do not have any records in the database
$themeInfo['norecords']['news'] = '<tr><td colspan="4" style="text-align: center;font-weight: bold;">There are no News Articles</td></tr>';
$themeInfo['norecords']['emoticons'] = '<tr><td colspan="4" style="text-align: center;font-weight: bold;">There are no Emoticons</td></tr>';
$themeInfo['norecords']['database'] = '<tr style="background: #edf3fe;"><td colspan="3" style="text-align: center;">The health of Database Tables are Ok, you dont need to Optimize any Tables.</td></tr>';
$themeInfo['norecords']['comments'] = 'There are no comments.';
$themeInfo['norecords']['categories'] = '<tr><td colspan="3" style="text-align: center;font-weight: bold;">There are no Categories</td></tr>';

//small templates
$themeInfo['template']['spam_comment_link'] = '[<a href="comment.php?action=notspam&amp;id={id}"><strong>Not SPAM</strong></a>]';
$themeInfo['template']['categorylist_image'] = '<img alt="{name}" title="{name}" src="{image}" />';

// redirection
$themeInfo['redirect']['CATEGORY_ADDED']            = array("title" => 'Category Added', "message" => 'The Category has been added. Please wait while you get redirected');
$themeInfo['redirect']['CATEGORY_ADDED_ERROR']      = array("title" => 'Category Added Error', "message" => 'The Category was not added, please check the Database or System Logs for reason why. Please wait while you get redirected');
$themeInfo['redirect']['CATEGORY_MODIFIED']         = array("title" => 'Category Modified', "message" => 'The Category has been modified. Please wait while you get redirected');
$themeInfo['redirect']['CATEGORY_MODIFIED_ERROR']   = array("title" => 'Category Modified Error', "message" => 'The Category was not modified, please check the Database or System Logs for reason why. Please wait while you get redirected');
$themeInfo['redirect']['CATEGORY_DELETED']          = array("title" => 'Category Deleted', "message" => 'The Category has been deleted. Please wait while you get redirected');
$themeInfo['redirect']['CATEGORY_DELETED_ERROR']    = array("title" => 'Category Deleted Error', "message" => 'The Categorywas not deleted, please check the Database or System Logs for reason why. Please wait while you get redirected');
$themeInfo['redirect']['COMMENT_MODIFIED']          = array("title" => 'Comment Modified', "message" => 'The Comment has been modified. Please wait while you get redirected');
$themeInfo['redirect']['COMMENT_MODIFIED_ERROR']    = array("title" => 'Comment Modified Error', "message" => 'The Comment was not modified, please check the Database or System Logs for reason why. Please wait while you get redirected');
$themeInfo['redirect']['COMMENT_BAN']               = array("title" => 'User Banned', "message" => 'The User has been banned. Please wait while you get redirected');
$themeInfo['redirect']['COMMENT_BAN_ERROR']         = array("title" => 'User Banned Error', "message" => 'The User was not banned, please check the Database or System Logs for reason why. Please wait while you get redirected');
$themeInfo['redirect']['COMMENT_NOTSPAM']           = array("title" => 'Comment', "message" => 'The Comment has been labelled as spam. Please wait while you get redirected');
$themeInfo['redirect']['COMMENT_NOTSPAM_ERROR']     = array("title" => 'Comment Error', "message" => 'The Comment not labelled as spam, please check the Database or System Logs for reason why. Please wait while you get redirected');
$themeInfo['redirect']['DATABASE_OPTIMIZE']         = array("title" => 'Database Optimize', "message" => 'Database Table(s) have been successfully optimized.');
$themeInfo['redirect']['EMOTICON_ADDED']            = array("title" => 'Emoticon Added', "message" => 'The Emoticon has been added. Please wait while you get redirected');
$themeInfo['redirect']['EMOTICON_ADDED_ERROR']      = array("title" => 'Emoticon Added Error', "message" => 'The Emoticon was not added, please check the Database or System Logs for reason why. Please wait while you get redirected');
$themeInfo['redirect']['EMOTICON_MODIFIED']         = array("title" => 'Emoticon Modified', "message" => 'The Emoticon has been modified. Please wait while you get redirected');
$themeInfo['redirect']['EMOTICON_MODIFIED_ERROR']   = array("title" => 'Emoticon Modified Error', "message" => 'The Emoticon was not modified, please check the Database or System Logs for reason why. Please wait while you get redirected');
$themeInfo['redirect']['EMOTICON_DELETED']          = array("title" => 'Emoticon Deleted', "message" => 'The Emoticon has been deleted. Please wait while you get redirected');
$themeInfo['redirect']['EMOTICON_DELETED_ERROR']    = array("title" => 'Emoticon Deleted Error', "message" => 'The Emoticon was not deleted, please check the Database or System Logs for reason why. Please wait while you get redirected');
$themeInfo['redirect']['LOGGED_IN']                 = array("title" => 'Logged In', "message" => 'You have successfully logged in, please wait while you get transfered.');
$themeInfo['redirect']['LOGOUT']                    = array("title" => 'Logging Out', "message" => 'You have successfully logged out, please wait while you are transfered back to the Login page.');
$themeInfo['redirect']['NOT_LOGGED_IN']             = array("title" => 'Not Logged In', "message" => 'Sorry you are currently not logged in, please wait while you get redirected to the Administration Login.');
$themeInfo['redirect']['NEWS_ADDED']                = array("title" => 'News Added', "message" => 'The News article has been added. Please wait while you get redirected');
$themeInfo['redirect']['NEWS_ADDED_ERROR']          = array("title" => 'News Added Error', "message" => 'The News article was not added, please check the Database or System Logs for reason why. Please wait while you get redirected');
$themeInfo['redirect']['NEWS_MODIFIED']             = array("title" => 'News Modified', "message" => 'The News article has been modified. Please wait while you get redirected');
$themeInfo['redirect']['NEWS_MODIFIED_ERROR']       = array("title" => 'News Modified Error', "message" => 'The News article was not modified, please check the Database or System Logs for reason why. Please wait while you get redirected');
$themeInfo['redirect']['NEWS_DELETED']              = array("title" => 'News Article Deleted', "message" => 'The News article has been deleted. Please wait while you get redirected');
$themeInfo['redirect']['NEWS_DELETED_ERROR']        = array("title" => 'News Article Delete Error', "message" => 'The News article was not deleted, please check the Database or System Logs for reason why. Please wait while you get redirected');
$themeInfo['redirect']['NEWSCONFIG']                = array("title" => 'News Configuration Updated', "message" => 'News Configuration has been updated, please wait while you get transferred.');
$themeInfo['redirect']['THEME_ADD']                 = array("title" => 'Theme Added', "message" => 'The Theme has been added. Please wait while you get redirected');
$themeInfo['redirect']['THEME_ADD_ERROR']           = array("title" => 'Theme Added Error', "message" => 'The Theme was not added, please check the Database or System Logs for reason why. Please wait while you get redirected');
$themeInfo['redirect']['THEME_MODIFIED']            = array("title" => 'Theme Modified', "message" => 'The Theme has been modified. Please wait while you get redirected');
$themeInfo['redirect']['THEME_MODIFED_ERROR']       = array("title" => 'Theme Modified Error', "message" => 'The Theme was not modified, please check the Database or System Logs for reason why. Please wait while you get redirected');
$themeInfo['redirect']['THEME_DELETED']             = array("title" => 'Theme Deleted', "message" => 'The Theme has been deleted. Please wait while you get redirected');
$themeInfo['redirect']['THEME_DELETED_ERROR']       = array("title" => 'Theme Deleted Error', "message" => 'The Theme was not deleted, please check the Database or System Logs for reason why. Please wait while you get redirected');
$themeInfo['redirect']['USER_ADDED']                = array("title" => 'User Added', "message" => 'The User has been added. Please wait while you get redirected');
$themeInfo['redirect']['USER_ADDED_ERROR']          = array("title" => 'User Added Error', "message" => 'The User was not added, please check the Database or System Logs for reason why. Please wait while you get redirected');
$themeInfo['redirect']['USER_MODIFIED']             = array("title" => 'User Modified', "message" => 'The User has been modified. Please wait while you get redirected');
$themeInfo['redirect']['USER_MODIFIED_ERROR']       = array("title" => 'User Modified Error', "message" => 'The User was not modified, please check the Database or System Logs for reason why. Please wait while you get redirected');
$themeInfo['redirect']['USER_DELETED']              = array("title" => 'User Deleted', "message" => 'The User has been deleted. Please wait while you get redirected');
$themeInfo['redirect']['USER_DELETED_ERROR']        = array("title" => 'User Deleted Error', "message" => 'The User was not deleted, please check the Database or System Logs for reason why. Please wait while you get redirected');
$themeInfo['redirect']['USER_ACCOUNT']              = array("title" => 'Account Updated', "message" => 'The Account has been updated. Please wait while you get redirected');
$themeInfo['redirect']['USER_ACCOUNT_ERROR']        = array("title" => 'Account Not Updated', "message" => 'The Account was not updated, please check the Database or System Logs for reason why. Please wait while you get redirected');
$themeInfo['redirect']['UGROUP_ADD']                = array("title" => 'Group Added', "message" => 'The Usergroup has been added. Please wait while you get redirected');
$themeInfo['redirect']['UGROUP_ADD_ERROR']          = array("title" => 'Group Added Error', "message" => 'The Usergroup was not added, please check the Database or System Logs for reason why. Please wait while you get redirected');
$themeInfo['redirect']['UGROUP_MODIFIED']           = array("title" => 'Group Modified', "message" => 'The Usergroup has been modified. Please wait while you get redirected');
$themeInfo['redirect']['UGROUP_MODIFED_ERROR']      = array("title" => 'Group Modified Error', "message" => 'The Usergroup was not modified, please check the Database or System Logs for reason why. Please wait while you get redirected');
$themeInfo['redirect']['UGROUP_DELETED']            = array("title" => 'Group Deleted', "message" => 'The Usergroup has been deleted. Please wait while you get redirected');
$themeInfo['redirect']['UGROUP_DELETED_ERROR']      = array("title" => 'Group Deleted Error', "message" => 'The Usergroup was not deleted, please check the Database or System Logs for reason why. Please wait while you get redirected');
$themeInfo['redirect']['INVALID_URL']               = array("title" => 'Invalid URL', "message" => 'You have been sent to an Invalid URL, please wait while you get redirected.');

?>
