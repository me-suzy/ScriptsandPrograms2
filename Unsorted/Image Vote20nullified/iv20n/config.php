<?      // 2.0.0
//////////////////////////////////////////////////////////////////////////////
// Program Name         : Image Vote - Photo Rating System                  //
// Release Version      : 2.0.0                                             //
// Program Author       : Ronald James                                      //
// Supplied by          : Scoons [WTN]                                      //
// Nullified by         : CyKuH [WTN]                                       //
//////////////////////////////////////////////////////////////////////////////
// COPYRIGHT NOTICE                                                         //
// (c) 2002 Ronald James    All Rights Reserved.                            //
// Distributed under the licencing agreement located in wtn_release.nfo     //
//////////////////////////////////////////////////////////////////////////////
// enter your mysql info

$database = "yourdb"; 	// (mysql database name)
$user = "dbusername"; 	// (mysql username)
$pass = "dbpassword"; 	// (mysql password)
$host = "localhost"; 	// (leave as localhost for most servers)

// website info
$sitename="Image Vote Website";
$siteurl="http://www.imagevote.com/demo/";  // url to Imagevote directory (with trailing slash)
$admin="admin@yoursite.com"; 	// your admin e-mail address
$votesneeded = 2;  			// votes needed before showing rating


$categories = array ("women", "men", "couples");	// categories
$pickcat = "list"; // display categories as radio buttons or pull down list - enter "radio" or "list"

// descriptions for ratings, delete the next line if you don't wish to use this feature
$des = array ("Couldn't Be Worse!","Needs Help","Not Great","So-So","About Average","Not Bad","Pretty Good","Wow!","Very Hot","The Perfect 10!");

$order="loop";  // Choose pictures by random or cycle in loop - enter "random" or "loop"
$maxreport = 5;  // picture is made inactive after being reported how many times?
$imgsize = 335; // for big images, resize images to what width? 

// set the following two numbers to 0 if you do not wish to use auto-resizing (saves cpu)
$max_height = 400; // if you have php 4.0.5 or higher, you can also specify a maximum height
$max_width = 400; // maximum width, probably the same as $imgsize above

$reportauto = "no"; // auto detect and report broken images?  yes or no

$validate = "no"; // require e-mail validation?  yes or no
$nopic = "yes"; // allow users to signup without submitting picture?
$allowurl = "1"; // allow users to provide a remote URL for their pictures?  1 = Yes / 0 = No

$notification  = "yes"; // send notification email to user on sign up?

// file uploading options
$allowupload = "0"; // Allow users to upload pictures?  1 = Yes / 0 = No
$uploadurl = "http://www.imagevote.com/demo/pics/";   // URL to Upload Directory (with trailing slash)
$uploadpath = '/path/to/store/uploaded/files/';  // Server Path to Upload Directory (with trailing slash)
$uploadsize = "100"; // Maximum file size for image uploads, in kilobytes (100 = 100k)
// $chmod = "yes"; // Uncomment this line if your server has an issue setting permissions on uploads

// public comments options
$commentson = "2"; // Allow public comments on pictures?  2 = Yes, Unmoderated / 1 = Yes, Moderated / 0 = No
$samplecomments = "2"; // Number of sample comments to display on voting page 0 = None

// To enable bad word filter, add a line in this format:
// $bad_words = array("fowlword","verybadword","badlanguage","replaceme");  

// mysql table names - do not change these variables unless needed

$usertable="usertable";
$imagetable="imagetable";
$mailtable="mailtable";
$admintable="admintable";
$commenttable="commenttable";

$template = "template1.php"; // which template to use for voting?


// location & name of php scripts - do not change these variables unless needed
$votephp = "index.php";
$gophp = "go.php";  // (function template)
$loginphp = "login.php";
$topphp = "top10.php";
$profilephp = "profile.php";
$mailphp = "mail.php";
$reportphp = "report.php";
$modphp = "moderate.php";
$signupphp = "signup.php";
$processphp = "process.php";
$userphp = "user.php";
$newphp = "newest.php";
$faqphp = "faq.php";

include ('lang.php');
include ('extras.php');
?>
