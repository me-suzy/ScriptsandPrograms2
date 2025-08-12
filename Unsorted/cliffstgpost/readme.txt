##############################################################################
# Cliff's Thumbnail Gallery Post Script Version 1.05                         # 
# Copyright 1998 Shaven Ferret Productions                                   #
# Created: 5/25/99             Last Modified: 12/05/01                       #
# Available at http://www.shavenferret.com/scripts                           #
##############################################################################
# COPYRIGHT NOTICE                                                           #
# Copyright 1998 Shaven Ferret Productions All Rights Reserved.              #
#                                                                            #
# This script can be used\modified free of charge as long as you don't       #
# change this header, or the parts of this script that generate the return   #
# link\form.  If you really need to remove these, go to                      #
# http://www.shavenferret.com/scripts/register.shtml .  By using this script #
# you agree to indemnify me from any liability that might arise from its use.#
#                                                                            #
# Redistributing\selling the code for this program without prior written     #
# consent is expressly forbidden.                                            #
##############################################################################

Hello.  These are the instructions for how to install and use the thumbnail
gallery post script.  You should have the following files...

        * tgpadmin.cgi ---- the script that handles administrative features        
        * tgpost.cgi ------ the script that handles publically available
                            features
        * tgp-lib.cgi ----- the perl library
        * tgpcount.cgi ---- the script that handles the counting
        * tgpsetup.cgi ---- the setup file
        * readme.txt ------ the file you're reading now

If you're missing any of these files, you can download them from
http://www.shavenferret.com/scripts/tgpost/ .  If you're new to the wonderful
world of perl, this probably seems like a lot to handle.  Just relax, and take
it one step at a time.

You will need to know the following things to install the script:
        * Your server path
        * Your URL
        * Where perl is on your system
        * Where your mail program is
        * How to set a file's permissions

If you don't know any of these things, see the bottom of this file.  You may
also want to plan out where you're going to put everything.  The script will
create one directory for data, which will hold text files that the script
needs to create the HTML.  It will also create one directory for the HTML,
which will contain all the HTML for the listings, posts, and an index.html
file.  For the sake of organization, you might want to create a directory for
the five scripts, though you don't need to, as long as they're all in the same
directory.

******************************************************************************
* Step 1 - Telling the script where it can find perl                         *
* You can skip this step if the path to perl on your system is /usr/bin/perl *
******************************************************************************

Open tgpadmin.cgi, tgpost.cgi, and tgpcount.cgi in a text editor.  The
absolute first line of these files should read #!/usr/bin/perl .  Change this
to #! and then the path to perl.  For example, if the path to perl is
/blah/directory/perl, you'd make the first line #!/blah/directory/perl .  If
your server has multiple versions of perl, use perl 5.

******************************************************************************
* Step 2 - Configuring everything on your computer                           *
******************************************************************************
* A few quick notes about Windows NT.
* This script *might* run under NT.  The keyword there is might, as is not
* definately, don't be surprised if it doesn't work out.  If you are on an NT
* server, double the backslashes when entering server paths.  For example, if
* the server path to the data directory is C:\bleh\somedir\data, enter is as
* C:\\bleh\\somedir\\data.  Note that you need to use backslashes (\), not the
* forward slashes (/) that are already there.  If that doesn't work, then I'm
* sorry, but I can't help you.  Heh, serves you right for patronizing
* Microsoft.  Bad webmaster..
******************************************************************************

This is probably the longest part of installing the script.  Just bear with
me...

First of all, open tgpsetup.cgi in a text editor.  Scroll down to line 25, and
change shavenferret.com to the password you want to use.  Whatever you do, do
NOT leave it as shavenferret.com, as that would make it just a little too easy
to hack your site.

Now scroll down to line 29.  Change /server/path/to/tgp to the server path
(not the URL) of the HTML directory.  The script will create all the post
files, listings, and an index.html file in this directory.

Scroll down to line 33 and change http://www.yourdomain.com/tgp to
the *full* URL (including the http:// and your domain) of the HTML directory.

By default, the script will create a directory named "tgpdata" in the same
directory that it's running in.  This directory will contain only data files
that the script uses to generate the HTML.  It won't contain any actual HTML,
or sensitive information, such as passwords.  In short, you can really put it
anywhere.  However, if you want to put it somewhere else, you can do so by
changing tgpdata in line 38 to the server path of where you want the
directory.

Scroll down to line 41 and change
http://www.yourdomain.com/cgi-bin/tgpost/tgpost.cgi to the full URL of your
uploaded tgpost.cgi file.

Scroll down to line 44 and change
http://www.yourdomain.com/cgi-bin/tgpost/tgpadmin.cgi to the full URL of your
uploaded tgpadmin.cgi file.

Scroll down to line 47 and change
http://www.yourdomain.com/cgi-bin/tgpost/tgpcount.cgi to the full URL of your
uploaded tgpcount.cgi file.

Scroll down to line 50.  If the path to your mail program is not
/usr/lib/sendmail (it almost always is), change this to the path to your mail
program.  

Now you'll need to tell the script if you have libwww-perl installed.  This
is required for the link checker, but you can use the script without it.  If
you have libwww-perl on your system (you probably do), delete the # in front
of line 53, so that it just reads:
use LWP::Simple; $lwp = -1;
If you don't have libwww-perl on your system, leave this as it is.  If you're
not sure if you have it, I recommend deleting the # and seeing if it works.
If it doesn't, you can always put the # back and try again.

Congratulations, you're done the long part!

******************************************************************************
* Step 2 - Setting everything up on your server                              *
******************************************************************************

First of all, whenever uploading any perl script, use ASCII.  This is because
when you transfer text files as binary it screws up the line breaks.

Upload all the files that end in .cgi to a directory where you can run CGI.
All of these files *must* be in the same directory.

Set the permissions of every file that ends in .cgi to 755.  If whatever you
use to set permissions makes you check things off, for 755 you'll check off
everything under owner, read and execute under group and others.

******************************************************************************
* Step 3 - Finishing everything up.                                          *
******************************************************************************

Point your web browser at the uploaded tgpadmin.cgi file.  This is where
you'll go to approve\delete posts, add banners, and pretty much do everything
you'll need to do to run your thumbnail gallery post.  You should see a form
asking you to click a button that will let me know that you're using the
script, so that I'll be able to keep track of how many sites are using it.
Click it (duh), and click the button that you'll see next.  This will take you
back to your admin page, which will now be ready for you to use.  You'll
probably want to add some banners, screw around with the settings, etc.;
everything will be explained in plain English inside.

******************************************************************************
* Troubleshooting                                                            
* If you're having problems with the script, please read the following before
* asking me for help.                                                        
******************************************************************************

* One of the .cgi files returns 500 Internal Server Error
        * Make sure you've changed the first line of the file to #! and 
          then the path to perl on your system
        * Make sure you've uploaded the file as ascii, not binary.
        * Make sure you've set the permissions for the file to 755.
        * You may not have libwww-perl installed on your system.  Try putting
          a # in front of line 53 of tgpsetup.cgi.  If you don't have
          libwww-perl on your system, and you want it, you'll have to ask your
          system administrator to install it.

* Counter doesn't count hits to pages
        * Try changing the pages' extensions to .shtml.  You can do this
          through the admin page.
        * There's a slim chance that you don't have SSI, which the counter
          requires.  Yell at your system administrator, or deal with it.

* The text "An error occurred", or something like that, appears at the top
  of the pages.
        * Make sure that you've entered the correct URL to the tgpcount.cgi
          file in tgpsetup.cgi
        * Make sure that you changed the first line of tgpcount.cgi to #! and
          then the path to perl on your system.
        * Make sure that you set tgpcount.cgi's permissions to 755.

* Script doesn't send e-mail, or crashes whenever it should.
        * Make sure that you've entered your e-mail address correctly.
        * Make sure that you've entered the correct path to your mail program.
          Do not add a -t to the end of this.  I know you had to in earlier
          version of this script, and several other scripts, but you don't
          have to here.

* I'm not getting nearly enough posts!
        * Okay, this is a pathetic attempt to plug my Get More Posts system.
          Seriously, though, the picpost version of the system is now sending
          in the neighborhood of 30-40 posts a day.  This may sound
          impossible, but keep in mind that we're dealing with copies of data
          (an image can be posted to billions of pic-post pages).  While I
          don't expect the TGP system to send 30-40 days immediately, it is
          basically free posts for you.

* My life feels empty.  How can I be happy again?
        * Send me money.  I *really* need it, I was stupid enough to release
          this script, which is probably worth about $400, for free; and I
          haven't stopped kicking myself in the ass since.  To help me out a
          little, go to http://www.shavenferret.com/scripts/register.shtml

******************************************************************************
* Getting the information you need to set everything up                      *
******************************************************************************

You can get any of the information you need by asking your system
administrator, though you probably shouldn't do that unless you really can't
figure it out yourself.  System administrators don't like having their time
wasted, and they do have the power to screw you over and all.  

If your server provides a FAQ, like most do, you'll almost definately be able
to find your server path there.  This ends the same way as your URL, but
begins differently.  

Don't know your URL?  It's the thing that begins with http://.  Not to sound
too elitist, but if don't know your URL, you might not be just ready to play
with CGI.

If you don't know the path to perl, I suggest trying it as /usr/bin/perl.
That will be correct on about 99.99% of all servers.  If it doesn't work,
check your FAQ, or if you have telnet access, type "whereis perl" (without
quotes) at the command prompt.  If your server has multiple versions of perl,
use perl 5.

If you don't know the path to your mail program, first try it as
/usr/lib/sendmail .  Like the path to perl, this is almost always correct.
If it doesn't work, though, consult your FAQ, or telnet in and type
"whereis sendmail" at the command prompt.

Setting a file's permissions (chmod) - This probably is the one thing that
confuses webmasters new to CGI more than anything else.  It's not really as
confusing as it first seems, though, and if you plan on using CGI you are
going to need to know how to do this.  What it basically means is that you
need to tell your server who can do what with a file.  If you have telnet
access, cd to the directory which holds the file whose permissions you want to
set, and type
chmod <permissions> <filename or directory>
For example,
chmod 755 tgpost.cgi
for the tgpost.cgi file.

If you update your page with an FTP client, you can probably set permissions
this way, too.  To find out how, consult the help file for your FTP client.
In my experiance, it's usually done by highlighting the file, right-clicking,
and selecting chmod for the list.

Many servers also provide a way to do this through the web.  To find out if
your's does, and how to use it, check your FAQ or ask your system
administrator.

******************************************************************************

                        If you still need help, go to
                http://www.shavenferret.com/scripts/help.shtml
