<?php
/*
Copyright 2005 VUBB
*/

/*
title = titles of things for example the title of an error 'Missing Data'
text = general language text for example 'Topic has been deleted'
mix = some things can pass off being a title and text and they make a mix :)
submit = the text of a submit button for example 'Edit Post'
*/

// admin
$lang['text']['settingsupdated'] = "Settings (General Config) Updated!";
$lang['title']['no_access'] = "No Access";
$lang['title']['admin_area'] = "Admin Area";
$lang['title']['no_cats'] = "No Categories";
$lang['title']['unlocked'] = "Unlocked";
$lang['title']['deleted_forums_first'] = "Cannot delete category!";
$lang['text']['deleted_forums_first'] = "Cannot delete category as it has forums, please delete the forums or move the forums and try again.";
$lang['mix']['groups'] = "Groups";
$lang['text']['template_change'] = "Change Template/Style";
$lang['text']['back_to_forum'] = "Back to forum";
$lang['text']['admin_home'] = "Admin Home";
$lang['text']['general'] = "General Configuration";
$lang['text']['no_access'] = "Sorry you dont have access to admin area!";
$lang['text']['no_cats'] = "You need to make a category first!";
$lang['mix']['lock_member'] = "Lock Member";
$lang['mix']['unlock_member'] = "Unlock Member";
$lang['text']['delete_member'] = "Delete Member";
$lang['text']['group_changer'] = "Change Member's Group";
$lang['text']['members_list'] = "Members List";
$lang['text']['style_config'] = "Styles Config";
$lang['text']['admin_welcome'] = "Welcome admin ";
$lang['text']['forum_controls'] = "Forum controls";
$lang['text']['member_controls'] = "Member controls";
$lang['text']['group_controls'] = "Group controls";
$lang['text']['cat_deleted'] = "Category deleted.";
$lang['text']['forum_deleted'] = "Forum delete.";
$lang['text']['styles_controls'] = "Styles controls";
$lang['text']['create_group'] = "Create Group";
$lang['text']['delete_group'] = "Delete Group";
$lang['text']['edit_group'] = "Edit Group";
$lang['text']['add_cat'] = "Add Category";
$lang['text']['add_forum'] = "Add Forum";
$lang['text']['manage'] = "Manage";
$lang['text']['delete'] = "Delete";
$lang['text']['cat_name'] = "Category Name";
$lang['text']['cat_names'] = "Category Names, One per line";
$lang['text']['group_names'] = "Group Names, One per line";
$lang['text']['forum_name'] = "Forum Name";
$lang['text']['forum_desc'] = "Forum Description";
$lang['text']['forum_ilink'] = "Forum is link?";
$lang['text']['forum_link'] = "Forum Link";
$lang['text']['permissions'] = "Permissions";
$lang['text']['can_view'] = "Can View";
$lang['text']['can_post'] = "Can Post";
$lang['text']['can_reply'] = "Can Reply";
$lang['text']['guests'] = "Guests";
$lang['mix']['members'] = "Members";
$lang['text']['moderators'] = "Moderators";
$lang['text']['administrators'] = "Administrators";
$lang['mix']['category'] = "Category";
$lang['text']['category_added'] = "Category Added!";
$lang['text']['group_added'] = "Group Added!";
$lang['text']['group_edited'] = "Group Edited!";
$lang['text']['group_deleted'] = "Group Deleted!";
$lang['text']['forum_added'] = "Forum Added!";
$lang['text']['category_edited'] = "Category Edited!";
$lang['text']['forum_edited'] = "Forum Edited!";
$lang['text']['check_user_list'] = "Check user list";
$lang['text']['you_locked'] = "You Locked Out ";
$lang['text']['you_unlocked'] = "You Unlocked ";
$lang['text']['you_deleted'] = "You Deleted ";
$lang['text']['current_template'] = "Current Template";
$lang['text']['more_than_1'] = "There is more than one person with that name.";
$lang['text']['owner_delete'] = "No deleting the owner.";
$lang['text']['forum_url'] = "Forum Url";
$lang['text']['forum_path'] = "Forum Path";
$lang['text']['new_reg'] = "Allow new registrations";
$lang['text']['website_name'] = "Website Name";
$lang['text']['website_url'] = "Website Url";
$lang['text']['group_changed'] = "You changed " . $_POST['user'] . "'s group";
$lang['text']['template_updated'] = "Template/Style Updated! <br /><br /><a href='admin.php?view=styles'>Click here to Continue</a>";
$lang['text']['invalid_template'] = "Template/Style specified does not exist!";
$lang['mix']['edit_group'] = "Edit Group";
$lang['submit']['add_cats'] = "Add Categorys";
$lang['submit']['add_forum'] = "Add Forum";
$lang['submit']['add_groups'] = "Add Groups";
$lang['submit']['change_group'] = "Change Group";
$lang['submit']['update'] = "Update";

// header and login bar
$lang['title']['log_reg'] = "Please Login or <a href='index.php?act=register' class='tlinks'>Register</a>";
$lang['title']['loggin_failed'] = "Login Failed";
$lang['title']['logged'] = "Logged In";
$lang['text']['admin'] = "Admin";
$lang['text']['welcome'] = 'Welcome';
$lang['text']['logout'] = 'Logout';
$lang['text']['not_logged'] = 'Not logged in';
$lang['text']['login'] = "Login";
$lang['text']['home'] = "Home";
$lang['text']['usercp'] = "User CP";
$lang['text']['login_failed'] = 'If you have not already, please signup. Otherwise, check your spelling and login again.';
$lang['text']['logged'] = "Already logged in!";
$lang['text']['logged_in'] = "Thank you for logging in, please <a href='index.php'><strong>click here</strong></a> to continue.";
$lang['submit']['login'] = "Login";

// viewtopic
$lang['title']['topic_tools'] = "Topic Tools";
$lang['title']['move_topic'] = "Move Topic";
$lang['title']['stick_topic'] = "Sticky Topic";
$lang['title']['unstick_topic'] = "Unsticky Topic";
$lang['title']['topic_locked'] = "Topic Locked";
$lang['title']['topic_moved'] = "Topic Moved";
$lang['title']['topic_unlocked'] = "Topic Unlocked";
$lang['title']['topic_added'] = "Topic Added";
$lang['title']['delete_no'] = "Cannot Delete";
$lang['title']['topic_lock_no'] = "Cannot Lock";
$lang['title']['topic_unlock_no'] = "Cannot Unlock";
$lang['title']['topic_move_no'] = "Cannot Move";
$lang['title']['topic_stick_no'] = "Cannot Sticky";
$lang['title']['topic_unstick_no'] = "Cannot Sticky";
$lang['title']['no_id'] = "Cant find topic";
$lang['title']['voted'] = "Voted";
$lang['text']['topic_deleted'] = "Topic has been deleted, <a href='index.php?act=viewforum&f={$_GET['f']}'><strong>click here</strong></a> to continue.";
$lang['text']['reply_deleted'] = "The reply has been deleted, <a href='index.php?act=viewforum&f={$_GET['f']}'><strong>click here</strong></a> to continue.";
$lang['text']['topic_locked'] = "Topic has been locked, <a href='index.php?act=viewtopic&t={$_GET['t']}&f={$_GET['f']}'><strong>click here</strong></a> to continue.";
$lang['text']['topic_unlocked'] = "Topic has been unlocked, <a href='index.php?act=viewtopic&t={$_GET['t']}&f={$_GET['f']}'><strong>click here</strong></a> to continue.";
$lang['text']['topic_moved'] = "Topic has been moved, <a href='index.php?act=viewtopic&t={$_GET['t']}&ft={$_POST['forum']}'><strong>click here</strong></a> to continue.";
$lang['text']['topic_stickied'] = "Topic has been stickied, <a href='index.php?act=viewtopic&t={$_GET['t']}&f={$_GET['f']}'><strong>click here</strong></a> to continue.";
$lang['text']['topic_unstickied'] = "Topic has been unstuck, <a href='index.php?act=viewtopic&t={$_GET['t']}&f={$_GET['f']}'><strong>click here</strong></a> to continue.";
$lang['text']['delete_no'] = "Sorry cannot delete, you may not have proper permissions, please <a href='index.php'><strong>click here</strong></a> to continue.";
$lang['text']['topic_lock_no'] = "Sorry cannot lock the topic, you may not have proper permissions, please <a href='index.php'><strong>click here</strong></a> to continue.";
$lang['text']['topic_lock_no'] = "Sorry cannot unlock the topic, you may not have proper permissions, please <a href='index.php'><strong>click here</strong></a> to continue.";
$lang['text']['topic_move_no'] = "Sorry cannot move the topic, you may not have proper permissions, please <a href='index.php'><strong>click here</strong></a> to continue.";
$lang['text']['topic_stick_no'] = "Sorry cannot stick the topic, you may not have proper permissions, please <a href='index.php'><strong>click here</strong></a> to continue.";
$lang['text']['topic_unstick_no'] = "Sorry cannot unstick the topic, you may not have proper permissions, please <a href='index.php'><strong>click here</strong></a> to continue.";
$lang['text']['new_reply'] = "New Reply";
$lang['text']['move'] = "Move Topic";
$lang['text']['lock'] = "Lock Topic";
$lang['text']['unlock'] = "Unlock Topic";
$lang['text']['stick'] = "Stick Topic";
$lang['text']['unstick'] = "Unstick Topic";
$lang['text']['joined'] = "Joined:";
$lang['text']['posted_on'] = "Posted on:";
$lang['text']['at'] = "at";
$lang['text']['unregistered'] = "Unregistered";
$lang['text']['post_subject'] = "Post Subject:";
$lang['text']['edit'] = "Edit";
$lang['text']['voted'] = "Your vote has been counted. <a href='index.php?act=viewtopic&t={$_GET['t']}&f={$_GET['f']}'><strong>Click here</strong></a> to return.";
$lang['submit']['move_topic'] = "Move Topic";
$lang['submit']['vote'] = "Vote";

// newpoll
$lang['title']['post_new_poll'] = "Post new poll in";
$lang['text']['poll_question'] = "Poll Question";
$lang['text']['poll_options'] = "Poll Options, One per line";
$lang['submit']['add_poll'] = "Add Poll";

// includes/poll
$lang['text']['total_votes'] = "Total Votes";

// functions
$lang['text']['bold'] = "Bold";
$lang['text']['underline'] = "Underline";
$lang['text']['italic'] = "Italic";
$lang['text']['image'] = "Image";
$lang['text']['quote'] = "Quote";
$lang['text']['code'] = "Code";

// index
$lang['text']['topics'] = "Topics";
$lang['text']['topic'] = "Topic";
$lang['text']['hits'] = "Hits";
$lang['text']['redirection'] = "REDIRECTION";

// register
$lang['title']['no_reg'] = "Can't Register";
$lang['title']['welcome'] = "Welcome to";
$lang['text']['email'] = "Email";
$lang['text']['verify_email'] = "Verify Email";
$lang['text']['password'] = "Password";
$lang['text']['verify_password'] = "Verify Password";
$lang['text']['user_taken'] = 'Someone already has that username.';
$lang['text']['email_taken'] = 'Someone already has that email.';
$lang['text']['email_not_match'] = 'The emails you entered did not match.';
$lang['text']['pass_not_match'] = 'The passwords you entered did not match.';
$lang['text']['already_reg'] = "Sorry but you are currently already registered!";
$lang['text']['no_reg'] = "Sorry but the Admin has turned off new registrations!";
$lang['text']['user_too_long'] = "Username too long, <a href='index.php?act=register'>go back.</a>";
$lang['text']['agreement'] = "Please remember that we are not responsible for any messages posted. We do not vouch for or warrant the accuracy, 
completeness or usefulness of any message, and are not responsible for the contents of any message. 
The messages express the views of the author of the message, not necessarily the views of ZitroteBB. 
Any user who feels that a posted message is objectionable is encouraged to contact us immediately by email. 
We have the ability to remove objectionable messages and we will make every effort to do so, within a reasonable time frame, 
if we determine that removal is necessary. You agree, through your use of this service, 
that you will not use ZitroteBB to post any material which is knowingly false and/or defamatory, inaccurate, abusive, vulgar, hateful, 
harassing, obscene, profane, sexually oriented, threatening, invasive of a person's privacy, or otherwise violative of any law. 
You agree not to post any copyrighted material unless the copyright is owned by you or by ZitroteBB.";
$lang['text']['agree'] = "I Agree";
$lang['text']['reg_welcome'] = $user . ", your password is " . $pass . ", you may login now! <a href='index.php?act=login'><strong>Click here</strong></a> login now.";

// things used in more than one place (used more than once)
$lang['title']['missing'] = "Missing Data";
$lang['title']['error'] = "Error";
$lang['title']['no_forum_id'] = "Cant find forum";
$lang['title']['locked'] = "Locked";
$lang['title']['smilies'] = "Smilies";
$lang['title']['bbcode'] = "BB Code";
$lang['title']['no_post'] = "Cannot Post";
$lang['title']['no_view'] = 'Cannot View';
$lang['title']['no_body'] = "Please enter a message";
$lang['title']['no_topic_id'] = "No Topic";
$lang['title']['edited'] = "Edited";
$lang['title']['deleted'] = "Deleted";
$lang['text']['replies'] = "Replies";
$lang['text']['last_action'] = 'Last Action';
$lang['text']['location'] = "Location";
$lang['text']['website'] = "Website";
$lang['text']['aim'] = "AIM";
$lang['text']['msn'] = "MSN";
$lang['text']['yahoo'] = "Yahoo";
$lang['text']['icq'] = "ICQ";
$lang['text']['account_locked'] = 'Sorry your account has been locked.';
$lang['text']['no_id'] = "Sorry cannot find a topic with that id.";
$lang['text']['no_body'] = "You must enter a message to post!";
$lang['text']['locked'] = "Sorry the topic is locked!";
$lang['text']['fill_all_fields'] = 'You must fill out all fields.';
$lang['text']['signature'] = "Signature";
$lang['text']['avatar'] = "Avatar";
$lang['text']['missing'] = "You must fill out all fields.";
$lang['text']['enter_post'] = "Enter your post";
$lang['text']['no_post'] = "Sorry you don't have the right permission to post in this forum. <a href='index.php?act=viewforum&f={$_GET['f']}'><strong>Click here</strong></a> to continue.";
$lang['text']['forum'] = 'Forum';
$lang['text']['username'] = "Username";
$lang['text']['more'] = "More";
$lang['text']['less'] = "Less";
$lang['mix']['delete'] = "Delete";
$lang['mix']['register'] = "Register";
$lang['submit']['edit'] = "Edit";
$lang['text']['attachement'] = "Attachement";
$lang['text']['no_tags'] = "No tags were provided for replacement, you should remove the replace_tags from the file.";
$lang['text']['template_not_found'] = "Template file " . $templateFile . " not found";
$lang['text']['by'] = "By";

// index
$lang['text']['no_module'] = "Module not found!";

// members/groups
$lang['title']['group_click'] = "Please click a group to view its members, then click a member to view the profile.";

// viewprofile
$lang['text']['date_reg'] = "Date Joined";

// viewforum
$lang['text']['views'] = "Views";
$lang['text']['starter'] = 'Starter';
$lang['text']['new_topic'] = 'New Topic';
$lang['text']['new_poll'] = "New Poll";
$lang['text']['no_view'] = "Sorry you don't have the right permission to view this forum. <a href='index.php'><strong>Click here</strong></a> to continue.";
$lang['text']['no_forum_id'] = "Sorry cannot find a forum with that id.";
$lang['text']['sticky'] = "Sticky:";

// editpost
$lang['title']['edit_post'] = "Edit post in";
$lang['title']['post_edited'] = "Post Edited";
$lang['text']['post_edited'] = "Post edited. <a href='index.php?act=viewtopic&t={$_GET['t']}&f={$_GET['f']}'><strong>Click here</strong></a> to continue.";

// reply
$lang['title']['post_reply'] = "Post a new reply in";
$lang['title']['reply_added'] = "Reply Added";
$lang['text']['reply_added'] = "Reply added. <a href='index.php?act=viewtopic&t={$_GET['t']}&f={$_GET['f']}'><strong>Click here</strong></a> to continue.";
$lang['submit']['add_reply'] = "Add Reply";

// newtopic
$lang['title']['post_new_topic'] = "Post a new topic in";
$lang['text']['topic_added'] = "Topic added. <a href='index.php?act=viewforum&f={$_GET['f']}'><strong>Click here</strong></a> to continue.";
$lang['text']['topic_title'] = "Topic Title";
$lang['submit']['add_topic'] = "Add Topic";

// usercp
$lang['title']['usercp'] = "User Control Panel";
$lang['text']['usercp_welcome'] = "Welcome to the UserCP";
$lang['text']['edited'] = "Profile successfully edited, please <a href='index.php'><strong>click here</strong></a> to continue";
$lang['text']['pass_edited'] = "Password successfully changed, please <a href='index.php'><strong>click here</strong></a> to continue";
$lang['text']['edit_profile'] = "Edit Profile";
$lang['text']['edit_pass'] = "Edit Password";
$lang['text']['edit_email'] = "Edit Email";
$lang['text']['current_pass'] = "Current Password";
$lang['text']['current_pass_again'] = "Current Password Verification";
$lang['text']['new_pass'] = "New Password";
$lang['text']['new_pass_again'] = "New Password Verification";
$lang['text']['cpass_no_match'] = "Current password verification failed, current passwords entered where not the same.";
$lang['text']['npass_no_match'] = "New password verification failed, new passwords entered where not the same.";
$lang['text']['cpass_no_match_db'] = "Sorry but the current password does not match the database record for this user.";

// stats
$lang['mix']['stats'] = "Forum Stats";
$lang['text']['who_online'] = "Who's Online?";
$lang['text']['total_topics'] = "Total Topics:";
$lang['text']['total_replies'] = "Total Replies:";
$lang['text']['total_users'] = "Total Users:";
$lang['text']['last_member'] = "Latest Member:";
$lang['text']['users_online'] = "Users Online:";
$lang['text']['guests_online'] = "Guests Online:";
?>