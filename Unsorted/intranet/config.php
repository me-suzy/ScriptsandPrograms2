<?php
// BHC INTRANET CONFIGURATION FILE
// Only change words between single quotes. Changing anything else will likely break everything.
$dbusername = 'root';      // Name of the MySQL user that the scripts will use.
$dbuserpasswd = 'password';     // Password of the aforementioned MySQL user.
// If you change either of the following two entries, or your apache server is not installed in the
// same spot, you'll need to edit the cron file appropriately.
$uploaddirectory = "/usr/local/apache/htdocs/bhc/intranet/uploads/";  // Where uploaded files go.
$huploaddirectory = "/usr/local/apache/htdocs/bhc/intranet/hiddenuploads/"; // Where uploaded files that only the sys admin knows
									    // about go.
$serveripaddy1 = '192.168.1.254';  // Server's internal IP address.
$serveripaddy2 = '192.168.1.254'; // Server's external IP address. If you've only got one, set this
                                   // to a non-sensical number like 999.999.999.fug or make it the same
                                   // as the first ip address.
$allowedipaddress='192.168.1';  // People on this network are OK. Leave off the trailing .0
$serverdnsname1 = 'bhcinfo.com'; // The DNS name of your server.
$serverdnsname2 = 'snoopy'; // An alternate DNS name for your server.
$headerstr = 'BHC Intranet:'; 	     // This is the string that appears in the header. You can leave it blank.
$useheader = 'user';          // "yes" includes header on each page, "no" removes the header, "user" leaves it up to users.
$deleteanynews = 'no';	     // Default is "no" -- changing this to "yes" allows people to delete news posted
			     // by other people.
$admindeletenews = 'yes';    // Default is "yes" -- changing this to "no" means that the administrator can't delete
			     // news items of other people from the news page.
$printtimesheet = 'yes';     // "yes" makes the timesheet program give you the option of a printer-friendly version.
                             // This is so that if your company requires employees to submit a hard copy of their
                             // timesheet, they can do so easily.
$printtsadmin = 'yes';	     // "yes" makes the timesheet administrator program give you the option of a printer-friendly
			     // version of the timesheet.
$printtsbw = 'no';           // "yes" removes all color from the printer-friendly version of the timesheet.
$printtssig = 'yes';         // "yes" means that the printer-friendly timesheet will have a place for the employee to sign it.
$printtssigsup = 'yes';      // "yes" means that the printer-friendly timesheet will have a place for the supervisor to sign it.
$printcontactlog = 'yes';    // "yes" means that the contact log gives you the option of a printer-friendly version
$tsharddelete = 'no';        // "yes" means that when an employee deletes a timesheet, it is gone forever. "no" means that
                             // when an employee deletes a timesheet, it is only hidden from the employee and the time sheet
                             // administrator can still see it. Time sheet administrator deletes are always permanent.
$sharepw = 'restrict';	     // When set to "yes" there will be a wrench at the bottom of the news page. If you click on the
			     // wrench, you will be brought to a page that will ask you for a login and password. Any valid
	              	     // combination will return a list including every user's login name and password. It is recommended
	       		     // that you set this to "no" for security reasons. If you do set it to "yes" make sure that the
                             // administrator does not use his/her intranet password for more important things, like the root
                             // password of the server. You can also set this to "restrict" which checks to see if the admin
        		     // has given the user permission to access the passwords.
$menumode = 'user';          // Choices are: user (user decides), norm (icons with labels), text (just labels), list (bulleted list),
                             // icon (just icons).
$showquotes = 'yes';	     // If you want to turn the quotes off for everyone, change this to "no".
$numemployees = '10';         // Default number of employees at the organization (used by survey program)
$defaultcontent = 'news.php'; // Application that comes up in the main window by default.
                               // news="news.php", calendar="calendar/", rolodex="rolodex.php",tasklist="tasklist.php",
                               // timesheet="timesheet.php", timesheet admin="timesheetcp.php", survey="survey.php",
			       // admin="admin.php", setup="setup.php", network="network.php"
			       // Catch: Only news or calendar will actually work. Others give you a blank page.
// For the following, the choices are yes, no, user, or restrict. "yes" means it always shows. "no" means it never
// shows. "user" means that the user has the choice. "restrict" means that only certain users have it, at the
// choice of the administrator, but are not forced to have it.
$shownews = 'yes';            // news
$showcalendar = 'user';       // calendar program
$showrolodex = 'user';        // rolodex program
$showcontact = 'user';        // contact log
$shownetwork = 'user';        // network program
$showtasklist = 'user';       // task list program
$showtimesheet = 'user';      // time sheet program
$showsetup = 'user';          // setup program
$showtsadmin = 'restrict';    // time sheet administration program
$showadmin = 'restrict';      // general administration program (doesn't exist yet!)
$showsurvey = 'restrict';     // survey administration program
// The following entries specify which icon to use for each application. You can add new icons by putting them in
// the icon subdirectory.
$newsicon = 'icons/news2.gif';
$calendaricon = 'icons/calendar3.gif';
$rolodexicon = 'icons/rolodex.gif';
$contacticon = 'icons/phone002.gif';
$networkicon = 'icons/network.gif';
$tasklisticon = 'icons/tasklist.gif';
$timesheeticon = 'icons/timesheet6.gif';
$timesheetcpicon = 'icons/calc0b.gif'; // For the time sheet administration application.
$setupicon = 'icons/panel1b.gif';
$surveyicon ='icons/talkbubble3_query.gif'; // For the survey application
$setupiconalt = 'icons/setup.gif'; // This icon is used if a user has an odd number of icons in the menu and

// ------------------------------ DON'T CHANGE ANYTHING BELOW THIS POINT.

// FUNCTIONS.

function dbconnect($DBUserName,$DBUserPasswd) 
	{
        mysql_connect( "localhost", $DBUserName, $DBUserPasswd);
        mysql_select_db( "intranet") or die( "<p>[config.php] Error opening database"); 
	}
?>
