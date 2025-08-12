<?
$langdir = 'ltr';  // Text is viewed left to right - can also be set to rtl for other languages such as Arabic.

// index.php
$index01 = 'All Categories';
$index02 = 'Choose a card below to start!';

// general navigation
$nav01 = 'Back';
$nav02 = 'Next';
$nav03 = 'Home';
$nav04 = 'Show';
$nav05 = 'gCards Administration Console';
$nav06 = 'Change Password';
$nav07 = 'Logout';
$nav08 = 'Log In';
$nav09 = 'Powered by';
$nav10 = 'rows per page';
$nav11 = 'Please go <a href="javascript:history.go(-1);">back</a>';
$nav12 = 'Return to';
$nav13 = 'More';


// UIfunctions.php
$uifunc01 = 'User: ';

// compose.php
$compose01 = 'Card not chosen!  Go <a href="index.php">back</a> to choose a card';
$compose02 = 'Add text below to send your eCard!';
$compose03 = 'Name';
$compose04 = 'Email Address';
$compose05 = 'From:';
$compose06 = 'To:';
$compose07 = 'Notify me when the card is picked up';
$compose08 = 'Separate email addresses with a comma';
$compose09 = 'Music:';

// preview.php
$preview01 = "Please fill in all values of the form! $nav11";
$preview02 = 'is not a valid email address';
$preview03 = "Invalid emails! $nav11";
$preview04 = 'Send';
$preview05 = "Card text may not exceed 10,000 characters! $nav11";
$preview06 = 'Close';

// sendcard.php
$sendcard01 = 'Error!  Form not filled out correctly.  Please go <a href="javascript:history.go(-1);">back</a>';
$sendcard02 = 'error inserting: ';
$sendcard03 = 'eCard sent...';
$sendcard04 = 'eCard could not be sent...';

// getcard.php
$getcard01 = 'Error - no card ID';
$getcard02 = '$to_email picked up your eCard';
$getcard03 = 'Send $from_name an eCard';

// showcard.php
$showcard01 = 'An eCard from';

// news.php
$news01 = 'Archived News';
$news02 = 'Posted';
$news03 = 'by';
$news04 = 'news articles per page';
$news05 = 'News';
$news06 = 'Subject';
$news07 = 'Text';
$news08 = 'Date';
$news09 = 'Author';



// user related text
$user01 = 'Username';
$user02 = 'Password';
$user03 = 'You are not logged in - you cannot logout!';
$user04 = 'Successfully logged out of application...';
$user05 = 'Password Confirmation';
$user06 = 'Email Address';
$user07 = 'Role';
$user08 = 'User';
$user09 = 'Passwords did not match - please try again';
$user10 = '(Leave blank to keep current value)';
$user11 = 'Old password did not match your existing password';
$user12 = 'Password updated successfully!';
$user13 = 'Old Password';
$user14 = 'New Password';

/*
*********************
** ADMIN DIRECTORY **
*********************
*/

// Authentication
$auth01 = 'Authentication failed for user';
$auth02 = 'Please enter both a username and password.';
$auth03 = 'You are not logged in.';
$auth04 = 'You do not have sufficient privileges to view this page!';

// admin.php
$admin01 = 'Administrative Options';
$admin02 = 'Maintenance';
$admin03 = "Card $admin02";
$admin04 = "Category $admin02";
$admin05 = "News $admin02";
$admin06 = "Users $admin02";
$admin07 = 'View, add, update, and delete';
$admin08 = 'ID';
$admin09 = 'deleted from database...';
$admin10 = 'deleted from server...';
$admin11 = 'not';
$admin12 = 'updated';
$admin13 = 'deleted';
$admin14 = 'added';
$admin15 = 'Add a new';
$admin16 = 'Upload';
$admin17 = 'Statistics';
$admin18 = 'View gCards statistics for your site';
$admin19 = "Music $admin02";

// actions
$action01 = 'Action';
$action02 = 'Add';
$action03 = 'Edit';
$action04 = 'Delete';
$action05 = 'View';
$action06 = 'Update';
$action07 = 'Cancel';
$action08 = 'Preview';

// cards.php
$cards01 = 'Image';
$cards02 = 'Thumbnail';
$cards03 = 'Times Sent';
$cards04 = 'Card';
$cards05 = 'Card Name';
$cards06 = 'Leave blank for automatic thumbnail';

// categories
$cat01 = 'Category';
$cat02 = 'Category Name';
$cat03 = 'Cards in Category';

// upload.php
$upload01 = 'Image not uploaded!';
$upload02 = "File copy error with 'move_uploaded_file' function!";
$upload03 = 'Image uploaded successfully...';
$upload04 = 'File copy error!';
$upload05 = 'Thumbnail uploaded successfully...';
$upload06 = 'Uploaded image used as thumbnail...';
$upload07 = 'Thumbnail created successfully...';
$upload08 = 'Please upload a thumbnail if image is not a JPEG.';
$upload09 = 'Form validation error - you must choose a card name, category, and upload an image';
// Error messages required by ImageResizer Class
$upload10 = 'The following required function does not exist: ';
$upload11 = 'The GD2 Graphics Library is installed, function ImageCreateTruecolor() exists, but the image can not be created';
$upload12 = 'Image could not be created with function ImageCreate() using GD1 Graphics Library';
$upload13 = 'gCards is configured to use the GD2 Graphics Library, but not all GD2 functions are present.  Try using GD.';
$upload14 = 'The GD2 Graphics Library is installed, function ImageCopyResampled() exists, but the image can not be resized';
$upload15 = 'The Image could not be resized using the GD1 Graphics Library';
$upload16 = 'This image format cannot be output: ';
$upload17 = 'The image you are tyring to output does not exist';
$upload18 = 'This image type is not supported yet';
$upload19 = 'Unable to output: ';

// Statistics
$stat01 = 'General Statistics';
$stat02 = 'Most Popular Cards';
$stat03 = 'Most Popular Categories by Cards Sent';
$stat04 = 'Total Cards Sent:';
$stat05 = 'Cards Sent this Week:';
$stat06 = 'Unique Hits - Main Page:';
$stat07 = 'Cards Picked Up:';
$stat08 = 'Number of Cards:';
$stat09 = 'Number of Categories:';
$stat10 = 'Disabled in config.php';

// Music
$music01 = 'Music Files';
$music02 = 'Add New Music File';
$music03 = 'Edit Display Name';
$music04 = 'Display Name';
$music05 = 'Path';
$music06 = 'Upload Music';
$music07 = 'Successfully uploaded music file!';
?>