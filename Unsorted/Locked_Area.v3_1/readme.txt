Locked Area Lite Version 3.1 Linux Stable
                 http://www.lockedarea.com/
Originally Released:     1st September 1999 (v1.0)
Latest Release:          8th June 2002 (v3.1)

- Copyright and Licensing Information
Locked Area Lite may be used and modified by anyone so long as they have a fully
registered copy and this copyright notice and the comments above remain intact.
By using this code you agree to indemnify Neil Skirrow and LockedArea.com from 
any liability that might arise from its use. Selling the code for this program
without prior written consent is expressly forbidden. In other words, please ask
first before you try and make money off of our program. Obtain permission before 
redistributing this software over the Internet or in any other medium. In all 
cases copyright and header must remain intact.  We cannot be held responsible 
for any harm this may cause. 

- Locked Area Lite System Requirements
	Server running Linux, Unix or a variant of either.
	Perl 5 or above.
	The following Perl modules: CGI.pm, File Locking Library
	
Do I have these modules?  Not sure, contact your system administrator
and check.  They are standard Perl modules and should be already installed
but if not, they are both available from http://search.cpan.org/.

- Installation
	1. Extract the files from the Locked Area Lite zip file and place 
	them somewhere on your computer.  You should have the following files: 
	readme.txt, setup.cgi, top.gif, space.gif, linux.gif
	   
	2. Open setup.cgi in your favourite text editor (NotePad will not do,
	WordPad is suitable).
	
	3. Modify the top line of setup.cgi to represent the path to Perl 5
	on your web server.  If you're not sure what it is, contact your
	system administrator.  Common locations are #!/usr/bin/perl and
	#!/usr/local/bin/perl
	
	4. Upload setup.cgi in ASCII mode to your cgi-bin or a location on
	your web server that supports Perl script execution.  Upload top.gif,
	space.gif and linux.gif in Binary mode to the location of your choice.
	These are images used by Locked Area Lite (mainly the admin panel).
	   
	5. Set the permissions (CHMOD) on setup.cgi to 755 (executable).  
	755 is the standard but it may be something else.
	
	6. Set the permissions (CHMOD) on the directory with setup.cgi within
	to 755 or 777.  755 is preferrable.
	
	7. Create a directory for your member's area, it can be wherever you
	like and called whatever you like.
	
	8. Set the permissions (CHMOD) on your member's area directory to 755
	or 777, 755 is preferred.
	
	9. Create a directory for your Locked Area cgi scripts.  You don't
	have to, they can be in the same directory as setup.cgi but creating 
	a directory is often a good idea.
	
	10. Set the permissions (CHMOD) on your cgi script directory to 777
	or 755.
	
	11. Open your web browser and point it to the location of setup.cgi.
	   (Just type the url to where setup.cgi resides on your server,
	   e.g. www.lockedarea.com/cgi-bin/setup.cgi
	   
	12. Follow the on screen instructions until the installation wizard 
	says the installation process is complete.
	
	13. Now, set the directory with setup.cgi in, your cgi script 
	directory and the member's area directory back to their original
	permissions.  They were most likely 644, if you recieve any errors
	after doing that, set them back to 755.
	
	14. You're finished.  Now, call up admin.cgi in your web browser in 
	the same way as you did for setup.cgi.
	
	15. Enter the administrator password you set at installation and log 
	in.  Once logged in, go to configuration and verify that all the 
	variables are set correctly.
	
Please ALWAYS use full/absolute paths and NOT relative paths.  The script 
generally doesn't function correctly when relative paths are used.


- Coverting from Locked Area Lite v2.7 and below
Locked Area Lite v3.1 Linux Stable uses the same database format as Locked 
Area Lite v2.7 and below.  To transfer your existing users you can simply copy
your members database and .htpasswd file across to your new v3.1 installation,
your new users will be required to request a new password before they will be
able to login as the passwords held in v3.1 db are encrypted unlike v2.7.


- Trouble Shooting
I get a Internal Server Error when loading setup.cgi.
	- Check the path to Perl is correct and pointing to Perl 5 on your 
	server.
	- Check your server has the CGI.pm and File Locking modules installed.
	- Make sure you uploaded setup.cgi in ASCII mode, NOT Binary and make 
	sure the permissions on it are 755.

I've installed the scripts, but no files have been created.
	- Check the cgi script directory set to 777 or 755 (writable). 
	- Otherwise, consult your host for advice, the server may require 
	special 	settings.
	- Make sure all the variables your set at installation are correct 
	and non of them have any speach marks in them. i.e. "
	- Check the variables.pl file has been created, if not, re-install.

The scripts run, but when I register the admin area still says no members.
	- Reinstall and make sure your path to the members database is a full
	path that ends with a filename. e.g. /home/username/dir/dir/members.db

I've registered, but can't login, the popup window doesn't except my username/password.
	- Check with your host that they support .htaccess Auth_Basic.
	- Also check the .htaccess and .htpasswd files have been created and 
	both have data in them.
	- Check the server type you're on, if it's a Cobalt Raq, make sure you
	selected so at installation.
	- Also make sure you entered a full path to the members area at installation, 
	a relative path will not work!
	- If all the above fail, go to the configuration section of the admin panel
	change the login style to version 2 rather than 1.
	
locked.cgi displays a not accepting new registrations message.
	- Go to the configuration section of the admin panel and tick the signup
	status tick box, then click update.
	

- Getting Support
If you're having problems and need some help please visit the web site and post
a question to the help desk.
http:///www.lockedarea.com/
Email and ICQ support are reserved for Locked Area Pro customers ONLY!


- Comments, Complaints and Suggestions
Got a comment, a complaint or maybe even a suggestion?  Please email them to 
comments@lockedarea.com.

- Useful Resources
http://www.lockedarea.com/
http://www.opencrypt.com/
http://www.web-scripts.co.uk/
http://www.cgidevelopers.com/