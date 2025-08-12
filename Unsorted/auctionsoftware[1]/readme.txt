
                   _____________________________
          
                      E v e r y A u c t i o n
                   _____________________________
                   The Freeware Auction Solution

#########################################################
#                                                       #
# This script was provided by:                          #
#                                                       #
# PHPSelect Web Development Division.                   #
# http://www.phpselect.com/                             #
#                                                       #
# This script and all included modules, lists or        #
# images, documentation are © 2004                      #
# PHPSelect (http://www.phpselect.com/) unless          #
# otherwise stated in the script.                       #
#                                                       #
# Purchasers are granted rights to use this script      #
# on any site they own. There is no individual site     #
# license needed per site.                              #
#                                                       #
# Any copying, distribution, modification with          #
# intent to distribute as new code will result          #
# in immediate loss of your rights to use this          #
# program as well as possible legal action.             #
#                                                       #
# This and many other fine scripts are available at     #
# the above website or by emailing the authors at       #
# admin@phpselect.com or info@phpselect.com             #
#                                                       #
#########################################################

-----------------------------------------------------------------
-->READ THIS CAREFULLY BEFORE INSTALLING OR USING EVERYAUCTION<--
-----------------------------------------------------------------


This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA
02111-1307, USA.

-----------------------------------------------------------------

===QUICK START (UNIX)===

1. Make a directory that is given write permissions.
2. Open the script with a plain text editor and edit the
first line of the configuration section to point to the
directory you just made.  Also, edit the first line of the script
to point to the perl executable on your system.
3. Put auction.pl in your cgi-bin dir and give it executable
permissions.

===QUICK START (Windows, Mac, Other)===

1. Make a new folder.
2. Open the script with a plain text editor (notepad, etc.)
and edit the first line of the configuration section to
point to the directory you just made.  Use forward slashes like
in the example.  (ie: $basepath = '/auction/'; if it is in C:\auction\)
3. Put auction.pl in your cgi-bin or cgi-shl dir.  Read your server
docs about how to use perl scripts.  You may have to rename it to
auction.pl.


===CONTENTS===

I. First Things First
II. Installation
III. Configuration
IV. FAQ/Troubleshooting
V. Support
VI. Version History

===I. First Things First===

A legal copy of this script will always be available free of charge
at http://www.everysoft.com/ or the author's current web site.

Please read and understand the GNU General Public License included
in this archive (license.txt).

===II. Installation===

1. Make a base directory somewhere on your server.  It should not be
web-accessible, but the script should be able to read and write data
to and from the directory.

UNIX:
mkdir auction
chmod a+rwx auction (OR chmod 777 auction)

WINDOWS95/NT:
File->New->Folder

2. Install Perl if it is not already installed.  Most ISPs/IPPs already
have Perl installed.  Perl is also included with most flavors of UNIX.
You can also download Perl free of charge.

UNIX:
http://www.perl.com/

WINDOWS95/NT:
http://www.activestate.com/ (get Perl for Win32)

3. Put auction.pl in your CGI-BIN directory.  Be sure it is executable.

UNIX ONLY:
chmod a+rx auction.pl (OR chmod 755 auction.cgi)

4. Configure the script to run like you want it to!  (see next section)

===III. Configuration===

Line 1: This should point to your Perl executable.

UNIX EXAMPLE:
#!/usr/bin/perl

WINDOWS95/NT EXAMPLE:
#!c:/perl/bin/perl

Line 41: This should point to the base directory (the one you just
made).  It must be an absolute or relative path.  Be sure to include
the trailing slash.

UNIX EXAMPLE:
$config{'basepath'} = '/home/hahnfld/auctiondata/';

WINDOWS95/NT EXAMPLE:
$config{'basepath'} = 'c:/auctiondata/';

Line 63: List your categories here.  Follow the examples.  Be sure
it is in the correct format.

All other lines in the configuration section are heavily commented.

===IV. FAQ/Troubleshooting===

Q. My users keep getting the error message:

	Error:
	We were unable to write to the user directory.

A. This is usually caused when a user is trying to bid on an
item or post an item and the server permissions are not set
correctly.  Be sure the folder you made has write permissions
and the path is correct in the script.  You also should not
put any files or directories in the folder you created.  The
script will create them automatically.

Q. Mail is not sent to the user when the auction closes. Why?

A. Check the mail configuration.  You may want to use a mail
program instead of a mail host.  It is up to you.  If you see
an error about IO::Socket you will need to use a mail program
instead of a mail host or you will need to re-install the perl
modules on your system.
 
Q. How do I make new/different categories?

A. Edit line 63 of the script to point to new categories.
The directories listed will be automatically created by the
script.  Each category should be listed like:

	[dir] => [category name],

Q. How do I delete entries from the auction before the auction
closes?

A. The system administrator may remove items from the auction
at any time.  Just use the URL:

	auction.pl?action=admin

The administrator password is defined on line 72 of the script.

Q. Do users need accounts to post a product or place a bid?

A. Maybe... If the $regdir variable is defined in the configuration
section then users will be required to register.  Random passwords
will be sent via e-mail and user data will be stored in the regdir
you specify.  If $regdir is undefined, then users will be presented
with the "classic" interface where registration is not required.

Q. Are closed items retained on the server?

A. Maybe... When an item closes, e-mail is sent to both the winning
bidder and the seller explaining the results.  If a closed item
directory is specified, the item will be copied to that directory
upon close, otherwise it will just be deleted.  Users can view closed
items using the closed item viewer utility included.

Q. When is e-mail sent out?

A. E-Mail is sent out when auctions close and when a user registers.
E-Mail will also be sent out when a user is outbid.

Q. I clicked on an item and it displayed the list of items or
I clicked on a category and it displayed a list of categories.

A. When the script is executed, it automatically determines
whether the item/category exists.  If it does not, it displays
the list.  Maybe the item you tried to access was closed or
the item was deleted?  Maybe you didn't give your base
directory write permissions so the script could create its
directories?

Q. How does the command line for the script work?

A. When called from a web browser, the script can be called
with many different options (action=whatever).  I'll let
you figure the options out, but for a hint, see the "if"
block near the top of the script. :)

Q. I do not want users to be able to post new items, but I
want to be able to.  Is this possible?

A. Yes, set $config{'newokay'} = 0 (or leave it undefined) and the
link to post a new item will not be visible.  Users who are supposed
to post an item may post items using the command line:

http://www.your.host.com/cgi-bin/auction.pl?action=new

Q. What is file locking, and how can I enable/disable it?

In version 1.01 and above, file locking is included to eliminate the
risk of data corruption on high-traffic sites.  If two users try
to post a bid at the EXACT SAME TIME, file locking will delay
the second poster until the first poster is finished posting.  File
locking should be enabled unless your server does not support it.
If you get weird flock errors or your script crashes, try setting
$flock = 0.


===VI. Version History===

Version 1.51 - 1.5 bugfix release.  Fixed an e-mail bug (replaced
\n with \r\n for all e-mails for RFC compatibility) and a closed
item viewer bug.  Also fixed error message for auctions not found. 

Version 1.5 - Partial rewrite.  A lot of code cleaned up and
a few minor new features.  This is also the first GPL release.
Now works under Apache mod_perl!

Version 1.01 - Fixed reposting (quotes bug), fixed e-mail
and closed item viewing for items that did not meet the reserve
price, minor cosmetic changes (corrected link to EveryAuction
home page, added site name to config section).

Version 1.0 - First non-beta release.  Added numerous new
features and a new look.  EveryAuction is now professional, reliable
auction software being used by over 50 Internet auction sites.

