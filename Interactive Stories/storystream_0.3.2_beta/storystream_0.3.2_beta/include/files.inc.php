<?php

///////////////////////////////////////////////////
// SETUP INCLUDE DIRECTORIES
///////////////////////////////////////////////////


// See if we can figure out paths automatically:

// For servers that have setup an include directory automatically in the php.ini file, this
//	will turn off that include directory so that there are not any conflicts with the included
//	PEAR library
ini_set ('include_path', $baseDir);

///////////////////////////////////////////////////
// Constants
///////////////////////////////////////////////////
require_once ($baseDir.'include/lang/'.$GLOBALS['language'].'.inc');
require_once ($baseDir.'include/const.inc.php');
require_once ($baseDir.'include/tables.inc.php');
require_once ($baseDir.'include/utility.inc.php');

///////////////////////////////////////////////////
// PEAR library files
///////////////////////////////////////////////////
require_once ($baseDir.'include/classes/pear/PEAR_SS.php');
require_once ($baseDir.'include/classes/pear/DB_SS.php');
require_once ($baseDir.'include/classes/pear/Mail/mime.php');
require_once ($baseDir.'include/classes/pear/mail_SS.php');

///////////////////////////////////////////////////
// Smarty Library Files
///////////////////////////////////////////////////
require_once ($baseDir.'include/smarty/libs/Smarty.class.php');

///////////////////////////////////////////////////
// StoryStream Files
///////////////////////////////////////////////////
require_once ($baseDir.'include/classes/SSError.class.php');
require_once ($baseDir.'include/classes/SSObject.class.php');
require_once ($baseDir.'include/classes/SSBrowserCapBase.class.php');
require_once ($baseDir.'include/classes/SSBrowserCap.class.php');
require_once ($baseDir.'include/classes/SSConfig.class.php');
require_once ($baseDir.'include/classes/SSApp.class.php');
require_once ($baseDir.'include/classes/SSNotification.class.php');
require_once ($baseDir.'include/classes/SSEventHandler.class.php');

require_once ($baseDir.'include/classes/SSSmarty.class.php');
require_once ($baseDir.'include/classes/SSTableObject.class.php');
require_once ($baseDir.'include/classes/SSContentObject.class.php');

require_once ($baseDir.'include/classes/SSCollection.class.php');
require_once ($baseDir.'include/classes/SSStoryCollection.class.php');
require_once ($baseDir.'include/classes/SSBookmarkCollection.class.php');

require_once ($baseDir.'include/classes/SSItemLists.class.php');

require_once ($baseDir.'include/classes/SSFork.class.php');
require_once ($baseDir.'include/classes/SSStory.class.php');
require_once ($baseDir.'include/classes/SSScene.class.php');
require_once ($baseDir.'include/classes/SSRating.class.php');
require_once ($baseDir.'include/classes/SSClassification.class.php');
require_once ($baseDir.'include/classes/SSBookmark.class.php');
require_once ($baseDir.'include/classes/SSAnnouncement.class.php');
require_once ($baseDir.'include/classes/SSGroup.class.php');

require_once ($baseDir.'include/classes/SSView.class.php');
require_once ($baseDir.'include/classes/SSMod.class.php');

require_once ($baseDir.'include/classes/SSUser.class.php');
require_once ($baseDir.'include/classes/SSDBase.class.php');

require_once ($baseDir.'include/classes/SSPage.class.php');
require_once ($baseDir.'include/classes/SSAuthorFrontPage.class.php');
require_once ($baseDir.'include/classes/SSMainFrontPage.class.php');
require_once ($baseDir.'include/classes/SSForkPage.class.php');
require_once ($baseDir.'include/classes/SSScenePage.class.php');
require_once ($baseDir.'include/classes/SSStoryPage.class.php');
require_once ($baseDir.'include/classes/SSUserPage.class.php');
require_once ($baseDir.'include/classes/SSReadPage.class.php');
require_once ($baseDir.'include/classes/SSBookmarkPage.class.php');
require_once ($baseDir.'include/classes/SSReadFrontPage.class.php');
require_once ($baseDir.'include/classes/SSPopupPage.class.php');
require_once ($baseDir.'include/classes/SSAnnouncePage.class.php');
require_once ($baseDir.'include/classes/SSGroupPage.class.php');

require_once ($baseDir.'include/classes/SSStoryPath.class.php');
require_once ($baseDir.'include/classes/SSFileUpload.class.php');
require_once ($baseDir.'include/classes/SSDiscussionBoard.class.php');
require_once ($baseDir.'include/classes/SSBBCode.class.php');
require_once ($baseDir.'include/classes/SSVersionControl.class.php');

?>
