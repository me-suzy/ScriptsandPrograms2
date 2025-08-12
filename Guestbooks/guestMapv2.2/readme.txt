guestMap v2

///////////////////////////////////////////////////////
//  Database has been changed.  If you are updating  //
//  to v2 from v1, please look at the sql file for   //
//  for the changes.                                 //
///////////////////////////////////////////////////////


Thank you for downloading guestMap.  As I am sure you already know, this is a PHP project that utilizes the Google Map's API to allow visitors to your website to "mark" their location in the world.  Now on to the setup of guestMap.

1. Run the import.sql file to create the guestmap database.  If you are unfamiliar with doing this, I would recommend checking into phpMyAdmin.

2. Open guestmap.php and search for
	
	<script src="http://maps.google.com/maps?file=api&v=1&key=
   
   You will want to insert the key you received from Google after key=.

3. Open config.inc.php and insert the database information between the ' ' on each line.

4. Now upload all the files.  Make sure that the guestmap.php is at the root level for the website, and the other files are in a folder named guestmap.


I have spent a good amount of time and energy(ask my wife) on this project and it really is my first adventure into the realm of programming.  I only ask that you mark my guestMap at www.thompsonbd.com and that you email me with any changes you make so that I can use them to advance the project.