<?php
$lang['nav']['cpanel'][0]['main'] = "General";
$lang['nav']['cpanel'][0]['sub'][] = "Profile";
$lang['nav']['cpanel'][0]['sub'][] = "Logout";
$lang['nav']['cpanel'][1]['main'] = "Fiction";
$lang['nav']['cpanel'][1]['sub'][] = "Add Story";
$lang['nav']['cpanel'][1]['sub'][] = "Edit Story";
$lang['nav']['cpanel'][1]['sub'][] = "Delete Story";
$lang['nav']['cpanel'][1]['sub'][] = "Add Chapter";
$lang['nav']['cpanel'][1]['sub'][] = "Edit Chapter";
$lang['nav']['cpanel'][1]['sub'][] = "Delete Chapter";
$lang['nav']['cpanel'][1]['sub'][] = "Order Chapters";
$lang['nav']['cpanel'][2]['main'] = "Manage Favorites";
$lang['nav']['cpanel'][2]['sub'][] = "Delete Favorites";
$lang['nav']['cpanel'][3]['main'] = "Manage Reviews";
$lang['nav']['cpanel'][3]['sub'][] = "Delete Reviews";

define( "LANG_CP_YES" , "Yes" );
define( "LANG_CP_NO" , "No" );

define( "LANG_CP_ERR1" , "Email or password incorrect, please try again. Both email and password are case-sensitive" );
define( "LANG_CP_ERR2" , "The email or pen name you have chosen already exists in the database, please choose another" );
define( "LANG_CP_ERR3" , "Your pen name must be between 3 and ".$conf['penname_length']." letters long and contain letters, numbers and spaces only" );
define( "LANG_CP_ERR4" , "You must agree to the terms and conditions before registering" );
define( "LANG_CP_ERR5" , "You must provide an email address" );
define( "LANG_CP_ERR6" , "Cannot find email address" );

define( "LANG_CP_TAC" , "TERMS AND CONDITIONS
You agree that you will not post any material which is false, defamatory, inaccurate, abusive, vulgar, hateful, harassing, obscene, profane, sexually oriented, threatening, invasive of a person's privacy, adult material, or otherwise in violation of any International law. You also agree not to post any copyrighted material unless you own the copyright or you have written consent from the owner of the copyrighted material. Spam, flooding, advertisements, chain letters, pyramid schemes, and solicitations are also forbidden.

Note that it is impossible for the staff or the owners to confirm the validity of posts. Please remember that we do not actively monitor the posts, and as such, are not responsible for the content contained within. We do not warrant the accuracy, completeness, or usefulness of any information presented. The posts express the views of the author and no one else. Anyone who feels that a post is objectionable is encouraged to notify an administrator or moderator. The staff and the owner reserve the right to remove objectionable content, within a reasonable time frame, if they determine that removal is necessary. This is a manual process, however, please realize that they may not be able to remove particular posts immediately. This policy applies to author profile information as well.

You remain solely responsible for the content of your posts. Furthermore, you agree to indemnify and hold harmless the owners.

You have the ability, as you register, to choose your username. We advise that you keep the name appropriate. With this user account you are about to register, you agree to never give your password out to another member, for your protection and for validity reasons. You also agree to NEVER use another member's account for any reason. We also HIGHLY recommend you use a complex and unique password for your account, to prevent account theft.

After you register and login, you will be able to fill out a profile. It is your responsibility to present clean and accurate information. Any information the owner or staff determines to be inaccurate or vulgar in nature will be removed, with or without prior notice. Appropriate sanctions may be applicable.

Please note that your IP address is recorded. Also note that the software places a cookie, a text file containing bits of information (such as your username and password), in your browser's cache. This is ONLY used to keep you logged in/out. The software does not collect or send any other form of information to your computer." );

define( "LANG_CP_PENNAME" , "Pen Name" );
define( "LANG_CP_EMAIL" , "Email" );
define( "LANG_CP_PASSWORD" , "Password" );
define( "LANG_CP_FORGOTTEN" , "Forgotten Password" );
define( "LANG_CP_FORGOTTEN2" , "If you have forgotten you password, fill out your email and a new one will be sent to you." );
define( "LANG_CP_FORGOTTEN_SUBJECT" , $conf['title'] . " Forgotten Password" );
define( "LANG_CP_FORGOTTEN_SUCCESS" , "Your password has been updated and sent to the email specified" );
define( "LANG_CP_LOGIN" , "Login" );
define( "LANG_CP_REGISTER" , "Register" );
define( "LANG_CP_REGISTER_LINK" , "<a href='".$conf['url']."?go=".$_GET['go']."&set=".LANG_CP_REGISTER."'>Click here to register</a>" );
define( "LANG_CP_REGISTER_SUCCESS" , "Thank you for registering. You should shortly receive your password details at the email address you provided" );

define( "LANG_CP_AGREE1" , "I agree to the terms and conditions" );
define( "LANG_CP_AGREE2" , LANG_CP_AGREE1 . " and am ".$conf['mage']." or over" );

define( "LANG_CP_U_ACCOUNTNO" , "Account N&ordm;" );
define( "LANG_CP_U_PENNAME" , "PenName" );
define( "LANG_CP_U_PASSWORD" , "Password" );
define( "LANG_CP_U_EMAIL" , "Email" );
define( "LANG_CP_U_LANGUAGE" , "Language" );
define( "LANG_CP_U_SKIN" , "Skin" );
define( "LANG_CP_U_BIO" , "About" );
define( "LANG_CP_U_GROUP" , "Group" );
define( "LANG_CP_U_IP" , "IP" );
define( "LANG_CP_U_REGISTERED" , "Registered" );

define( "LANG_CP_U_SUCC1" , "Your profile has been altered successfully" );
define( "LANG_CP_U_ERR1" , "The pen name you've chosen is already in use" );
define( "LANG_CP_U_ERR2" , "Your password must be between 3 and ".$conf['password_length']." characters long and alphanumeric only" );
define( "LANG_CP_U_ERR3" , "You must provide a valid email address" );


define( "LANG_CP_ADDSTORY_ERR1" , "You must submit a chapter with the story" );
define( "LANG_CP_ADDSTORY_ERR2" , "Your story must have a title" );
define( "LANG_CP_ADDSTORY_ERR3" , "Your story must have a description" );
define( "LANG_CP_ADDSTORY_ERR4" , "You can only upload the following types of file: " . $conf['fiction_types'] );
define( "LANG_CP_ADDSTORY_ERR5" , "Your chapter cannot be longer than " . $conf['fiction_words'] );

define( "LANG_CP_ADDSTORY_SUCC1" , "Your story has been successfully added but may not be listed yet if the category requires approval." );

define( "LANG_CP_ADDSTORY_TITLE" , "Title" );
define( "LANG_CP_ADDSTORY_DESC" , "Description" );
define( "LANG_CP_ADDSTORY_CATEGORY" , "Category" );
define( "LANG_CP_ADDSTORY_RATING" , "Rating" );
define( "LANG_CP_ADDSTORY_WIP" , "Finished" );
define( "LANG_CP_ADDSTORY_PGENRE" , "Primary Genre" );
define( "LANG_CP_ADDSTORY_SGENRE" , "Secondary Genre" );
define( "LANG_CP_ADDSTORY_MCHAR" , "Main Character" );
define( "LANG_CP_ADDSTORY_MCHARGEN" , "General" );
define( "LANG_CP_ADDSTORY_CHAPTERTITLE" , "Chapter Title" );
define( "LANG_CP_ADDSTORY_CHAPTERTEXT" , "Chapter Text" );
define( "LANG_CP_ADDSTORY_CHAPTERUPLOAD" , "or Chapter Upload" );

define( "LANG_CP_EDITSTORY_SUCC1" , "Your story has been successfully edited" );

define( "LANG_CP_DELSTORY_WARN1" , "Deleting a story will also delete all chapters and reviews associated with it" );
define( "LANG_CP_DELSTORY_SUCC1" , "Your story has been successfully deleted" );

define( "LANG_CP_ADDCHAPTER_ERR1" , "Your chapter must have text" );
define( "LANG_CP_ADDCHAPTER_SUCC1" , "Your chapter has been successfully added" );

define( "LANG_CP_EDITCHAPTER_SUCC1" , "Your chapter has been edited" );

define( "LANG_CP_CHAPTER" , "Chapter " );

define( "LANG_CP_DELCHAPTER_ERR1" , "You cannot delete the only chapter of a story" );
define( "LANG_CP_DELCHAPTER_SUCC1" , "The chapter has been successfully deleted" );

define( "LANG_CP_ODERCHAPTER_ERR1" , "Each chapter must have an individual order slot" );
define( "LANG_CP_ODERCHAPTER_SUCC1" , "The chapters have been successfully reordered" );

define( "LANG_CP_DELFAVORITES_ERR1" , "You have no favorites to delete");
define( "LANG_CP_DELFAVORITES_SUCC1" , "Favorites deleted successfully");

define( "LANG_CP_DELREVIEW_REVIEWNUMBER" , "Review N&ordm;");
define( "LANG_CP_DELREVIEW_SUCC1" , "The review has been deleted successfully");
define( "LANG_CP_DELREVIEW_ERR1" , "The review cannot be deleted - please check the ID number");
?>