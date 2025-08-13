Netbingo Multiplay Platinum Edition Install Instructions

1. Unzip files and install on web server. The directory structure used on 
	netbingo multiplay is:

	home/www/netbingo -  web server root - index.html to start game
	> home/www/netbingo/PHP - PHP scripts for game
	> home/www/netbingo/cgi-bin/multiplay - location of bingochecker.cgi
	>> home/www/netbingo/cgi-bin/multiplay/game - game files written by schedule_game.php3	
	>> home/www/netbingo/PHP/response - html files to respond to bingo claims
	>> home/www/netbingo/PHP/images - graphics files used in the game. These are further 
		subdivided as follows
	>>> home/www/netbingo/PHP/images/balls - images for the display of the chosen ball number
	>>> home/www/netbingo/PHP/images/cards - images used to build the cards

	You can change this directory structure as you wish. Just make sure 
	you pay careful attention to setting up your paths in the next section.
	The game files are stored in the cgi-bin directory for security reasons.

2. In the PHP directory, make the config.inc file writable from the server. chmod 666.
   In the cgi-bin/multiplay directory, make the multiconfig.pl file writeable from the server also. chmod 666.

3. Start the Administration Console by opening the admin.html file in the PHP/admin/ directory
   in your browser. Choose the "General Config." option to set the paths and general game 
   parameters to correspond to the directories where you installed the scripts, etc. 
   If you want the user to be able to select the game of their choice, set the 
   "user can choose game" option to "yes". If you want to determine the game sequence yourself, 
   set this option to "no".Click the "save" button to register your choices.
   
4. Now choose the "Game Config." option. Depending on whether you set "user can choose game" option 
   to yes or no, you will now see a form which allows you to set the options for each game type.
   The parameters you can set are explained on the page.
	
5. Set permissions on bingochecker.cgi and cards.cgi to allow execution. On Unix use chmod 755.
	
6. Set the game directory to be writable from the web server. chmod 777.	

7. Create a mysql database to hold your netbingo tables and data. Use the
   script netbingodb.sql in the mysql directory to create your tables. Set the 
   database name, username and password in the PHP/config.inc file.
   
8. Modify the index.html page which contains the licence to suite your site requirements. 
   Please note the terms of the licence and respect them.
	
9. Use the admin.html file to set up your awards. You can also use the 
   Database Admin. option to administer your database.

10. That's it!

11. Email me if anything goes wrong: mike@proactech.com. Although the game comes 
    without a warranty, unless you bought the installation, I'll do my best to solve 
    your problems by email.