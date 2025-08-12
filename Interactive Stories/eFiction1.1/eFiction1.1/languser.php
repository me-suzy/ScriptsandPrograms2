<?php

//header.php

define ("_HOME", "Home");
define ("_CATEGORIES", "Categories");
define ("_RECENTLYADDED", "Recently Added");
define ("_AUTHORS", "Authors");
define ("_HELP", "Help");
define ("_SEARCH", "Search");
define ("_YOURACCOUNT", "Your Account");
define ("_ADMIN", "Admin");
define ("_LOGIN", "Log In");
define ("_TITLES", "Titles");

//Used in several pages

define ("_CHARACTERS", "Characters");
define ("_CATEGORY", "Category");
define ("_GENRE", "Genre");
define ("_RATING", "Rating");
define ("_WARNING", "Warning");
define ("_PENNAME", "Penname");
define ("_REALNAME", "Real name");
define ("_EMAIL", "Email");
define ("_WEBSITE", "Website");
define ("_SUMMARY", "Summary");
define ("_TITLE", "Title");
define ("_CHAPTERTITLE", "Chapter Title");
define ("_GENRES", "Genres");
define ("_WARNINGS", "Warnings");
define ("_AUTHOR", "Author");
define ("_ROUNDROBIN", "Roundrobin");
define ("_YES", "Yes");
define ("_NO", "No");
define ("_EDIT", "Edit");
define ("_DELETE", "Delete");
define ("_ADDCHAPTER", "Add Chapter");
define ("_REVIEWS", "Reviews");
define ("_VIEWTITLES", "Titles");
define ("_SUBMIT", "Submit");


//authors.php

define ("_VIEWAUTHORS", "View Authors");
define ("_OTHER", "Other");

//categories.php

define ("_PREVIOUS", "Previous");
define ("_NEXT", "Next");
define ("_NOSTORIES", "No stories found.");
define ("_ALLCHAPTERS", "All Chapters");
define ("_FIRSTCHAPTERS", "First Chapters Only");
define ("_SUBCHAPTERS", "Sub Chapters Only");
define ("_ALLCHARACTERS", "All Characters");
define ("_ALLGENRES", "All Genres");
define ("_ALLRATINGS", "All Ratings");
define ("_ALLWARNINGS", "All Warnings");
define ("_GO", "Go");
define ("", "");
define ("", "");
define ("", "");
define ("", "");

//index.php

define ("_LESS", "Less");
define ("_MORE", "More");
define ("_MUSTBELOGGEDIN", "You must be logged in to submit a comment.");

//reviews.php

define ("_SUBMITREVIEW", "Would you like to submit a review?");
define ("_SIGNED", "Signed");
define ("_ANONYMOUS", "Anonymous");
define ("_REVIEWSTORY", "Review Story");
define ("_VIEWALLREVIEWS", "View All Reviews");
define ("_REVIEWSFOR", "Reviews For");
define ("_REVTHANKYOU", "Thank you for your review!");
define ("_MUSTBEMEMBER", "Sorry, you must be a member of the site to review stories.");
define ("_REVIEW", "Review");
define ("_OPINION", "Opinion of story?");
define ("_LIKED", "Liked it");
define ("_DISLIKED", "Disliked it");
define ("_NOTALLOWED", "You are not allowed to be in this area.");
define ("_DELETEREVIEW", "Delete Review");
define ("_REVIEWDELETED", "The review has been deleted.");
define ("_REVIEWNOTDELETED", "The review has not been deleted.");
define ("_SUREDELETE", "Are you sure you want to delete this review?");
define ("_BACKTOSTORY", "Back to story.");

//search.php

define ("_NORESULTS", "No results found.");
define ("_TOOMANYRESULTS", "More than 50 results found. Please try searching again, with more limiters chosen.");
define ("_ADVANCED", "Advanced Search");
define ("_CHOOSECAT", "Choose Category First");
define ("_ALLCATS", "All Categories");
define ("_SELECTCAT", "Select something ^^^");
define ("_SEARCHTERM", "Searchterm");
define ("_NOCHARACTERS", "No characters");
define ("_ALL", "All");
define ("_SIMPLE", "Simple Search");
define ("_STORYTITLE", "Story Title");
define ("_FULLTEXT", "Full Text");
define ("_RECENTSTORIES", "Recent Stories");

//stories.php

define ("_CHOOSECATEGORY", "Choose Category");
define ("_ADDNEWSTORY", "Add New Story");
define ("_MISSINGFIELDS", "Required fields in red.");
define ("_NOSTORYTEXT", "You must have an actual story included, either as an upload or input into the textarea. Please <a href=\"stories.php?action=newstory\">try again</a>  and make sure these fields are filled out.");
define ("_INVALIDUPLOAD", "Invalid Upload");
define ("_POSTTOLIST", "Post to Mailing-List");
define ("_STORYTEXTTEXT", "Story Text (text)");
define ("_STORYTEXTFILE", "Story Text (file)");
define ("_STORYTEXTNEW", "You may copy the text of your story here, or upload a text or html file. Once you choose one, the other form option will gray out, and not be choosable.<br><br>
						The following html codes are allowed: &lt;b&gt; &lt;i&gt; &lt;u&gt; &lt;center&gt; &lt;img&gt; &lt;a&gt; &lt;hr&gt; <br><br>
						The script understands carriage returns so, &lt;br&gt; tags are not required. Some html tags such as &lt;p&gt; tags may cause your story to space oddly.");
define ("_STORYTEXTINFO", "This is the text of your story. Go ahead and make any changes here.<br><br>
						The following html codes are allowed: &lt;b&gt; &lt;i&gt; &lt;u&gt; &lt;center&gt; &lt;img&gt; &lt;a&gt; &lt;hr&gt; <br><br>
						The script understands carriage returns so, &lt;br&gt; tags are not required. Some html tags such as &lt;p&gt; tags may cause your story to space oddly.");
define ("_NEWSTORYAT", "New Story at ");
define ("_NEWSTORYAT2", "A new story has been submitted to the validation queue at");
define ("_STORYADDED", "Your story has been added. If the admin is reviewing submissions, then it will appear to the public after they have okayed it. In the meantime, you can always edit the story in your account area.");
define ("_STORYTEXTCLEANUP", "This is the text of your story. Go ahead and make any changes here.<br><br>The following html codes are allowed: &lt;b&gt; &lt;i&gt; &lt;u&gt; &lt;center&gt; &lt;img&gt; &lt;a&gt; &lt;hr&gt;");
define ("_MANAGESTORIES", "Manage Stories");
define ("_MOVE", "Move");
define ("_CHAPTERINFO", "(first chapter) Completed");
define ("_OPTIONS", "Options");
define ("_READS", "Reads");
define ("_FEATURED", "Featured");
define ("_STORYUPDATED", "Your story has been updated. Back to <a href=\"user.php\">your account</a> area.");
define ("_NOTAUTHOR1", "You are not the author of this story, and are not allowed to edit it!");
define ("_STORYDELETED", "The story has been deleted. <a href=\"user.php\">Back to Account</a>.");
define ("_NOTAUTHOR2", "You are not the author of this story and have no right to delete it!");
define ("_STORYNOTDELETED", "The story has not been deleted. <a href=\"user.php\">Back to Account</a>.");
define ("_DELETESTORY1", "Are you sure you want to delete this story? All chapters beneath it will be deleted as well!");
define ("_DELETESTORY2", "Are you sure you want to delete this chapter?");
define ("_DELETESTORY", "Delete Story");
define ("_PREVIEW", "Preview");
define ("_ADDSTORY", "Add Story");
define ("_SELECT", "Select");
define ("_TRYAGAIN", "You must have an actual story included, either as an upload or input into the textarea. Please try again and make sure these fields are filled out.");


//user.php

define ("_WRONGPASSWORD", "That password doesn't match the one in our database. Please <a href=\"user.php?action=login\">try again</a> or retrieve a <a href=\"user.php?action=lostpassword\">new password</a> if you can't remember yours.");
define ("_MEMBERLOGIN", "Member Login");
define ("_CARRYOVER", "Carry Over Chapter Info");
define ("_PASSWORD", "Password");
define ("_REGISTER", "Register");
define ("_LOSTPASSWORD", "Lost password");
define ("_USERACCOUNT", "User Account Page");
define ("_ADDNEWCHAPTER", "Add New Chapter");
define ("_EDITDELSTORIES", "Edit/Delete Stories");
define ("_EDITPERSONAL", "Edit Personal Information");
define ("_MANAGEIMAGES", "Manage Images");
define ("_LOGOUT", "Logout");
define ("_EMAILREQUIRED", "You must fill out the e-mail field. Press back to try again.");
define ("_PERSONALUPDATED", "Your personal info has been updated. <a href=\"user.php\">Back to your account</a>");
define ("_PASSWORDTWICE", "You must enter your new password twice. Please <a href=\"user.php?action=editbio\">try again</a>.");
define ("_BIO", "Bio");
define ("_IMAGE", "Image");
define ("_ENTEREMAIL", "Enter Your E-mail");
define ("_CONTACTREVIEWS", "Contact for new reviews");
define ("_REQUIREDFIELDS1", "Indicates required field. Emails are not accessible or viewable anywhere on the site, although people can contact you through a feedback form - your actual e-mail address isn't viewable at any point, though.");
define ("_PLEASELOGIN", "Please <a href=\"user.php\">log in</a>.");
define ("_NOFILE", "No file Uploaded. Please <a href=\"user.php?action=manageimages&upload=upload\">try again</a>.");
define ("_BADIMAGE", "Only .jpgs and .gifs are allowed. Please <a href=\"user.php?action=manageimages&upload=upload\">try again</a>.");
define ("_IMAGETOOBIG", "This image is too big. Images may only be $imagewidth wide by $imageheight high.Please <a href=\"user.php?action=manageimages&upload=upload\">try again</a>.");
define ("_FILEEXISTS", "A file with that name already exists on server. Please <a href=\"user.php?action=manageimages&upload=upload\">try again</a>.");
define ("_FILEUPLOADED", "File Uploaded Successfully. Back to <a href=\"user.php?action=manageimages\">manage images</a>.");
define ("_IMAGEDELETED", "The image has been deleted. <a href=\"user.php?action=manageimages\">Back to manage images</a>.");
define ("_IMAGECODE", "HTML code to use image in story");
define ("_PENEMAILREQUIRED", "You must fill out the penname and email fields. Please <a href=\"user.php?action=newaccount\">try again</a>.");
define ("_SIGNUPTHANKS", "Thank you for signing up! You will receive your temporary password at the e-mail address you just filled out.");
define ("_PENNAMEINUSE", "This penname is already in use. Please <a href=\"user.php?action=newaccount\">choose a different one</a>.");
define ("_EMAILINUSE", "This email address has already been used to sign up for an account. If you've lost your password, please generate a new one by using the <a href=\"user.php?action=lostpassword\">lost password</a> feature.");
define ("_REQUIREDFIELD2", "Indicates required fields.");
define ("_BADEMAIL", "That address doesn't appear to be in our database. Please <a href=\"user.php?action=lostpassword\">try again.</a>");
define ("_PASSWORDSENT", "A new password has been sent to your e-mail address.");
define ("_NEWPASSWORD", "Type in the email address that is registered under your account and a NEW password will be sent to you.");
define ("_UPLOADIMAGE", "Upload New Image");
define ("_SKIN", "Skin");
define ("_VIEWREVIEWS", "View Reviews");
define ("_MANAGEFAVORITES", "Manage Favorites");
define ("_MANAGEREVIEWS", "Manage Reviews");
define ("_RESPOND", "Respond");
define ("_AUTHORSRESPONSE", "Author\'s Response");
define ("_RESPONSE", "Response");
define ("_BACKTOREVIEWS", "Back to Reviews");
define ("_RESPONSEAPPENDED", "Your response has been appended.");
define ("_NOREVIEWS", "No Reviews");
define ("_FAVORITEAUTHORS", "Favorite Authors");
define ("_FAVORITESTORIES", "Favorite Stories");
define ("_REMOVE", "Remove");
define ("_ADDTOFAVES", "Add to Favorites");
define ("_GENREINFO", "Select more than one genre by holding down the Control key.");
define ("_CHARINFO", "Select more than one character by holding down the Control key.");
define ("_WARNINGINFO", "Select more than one warning by holding down the Control key.");
define ("_MAILINFO", "Separate multiple lists with commas.");
define ("_NEWACCOUNT", "New Account");
define ("_BADUSERNAME", "Sorry! Usernames can only contain letters, numbers, underscores, hyphens, or spaces, and must be between 3 and 20 characters long.");
define ("_INDICATES", "Indicates required fields.");
define ("_BY", "by");


//viewstory.php

define ("_NONE", "None");
define ("_CHAPTERS", "Chapters");
define ("_ADDROUNDROBIN1", "This is a roundrobin story. Would you like to ");
define ("_ADDROUNDROBIN2", "contribute");
define ("_EDITSTORY", "Edit Story");
define ("_ARCHIVEDAT", "This story archived at");
define ("_COMPLETEDPHRASE", "This story is completed.");
define ("_NOTCOMPLETEDPHRASE", "This story is not yet completed.");
define ("_INDEX", "Story Index");

//viewuser.php

define ("_MEMBERSINCE", "Member Since");
define ("_CONTACT", "Contact");
define ("_CONTACTAUTHOR", "Contact Author");
define ("_COMMENTSSENT", "Your comments have been sent to the author.");
define ("_EMAILNOTE", "Admin note: you are receiving this e-mail because you have the contact form turned on at $sitename. Should you prefer not to receive such e-mails, please login and turn off that setting.");
define ("_YOUREMAIL", "Your E-mail");
define ("_SUBJECT", "Subject");
define ("_COMMENTS", "Comments");
define ("_REQUIREDFIELDS3", "All fields are required. Please be respectful and polite when contacting an author, and don't spam them!");
define ("_STORIESBY", "Stories by");


// added for 1.1d
define ("_CATMUSTBEADDED", "No categories have been added by the admin yet. The archive must have at least one category before any stories can be added.");
define ("", "");
define ("", "");
define ("", "");
define ("", "");
define ("", "");
define ("", "");
define ("", "");

?>