#               -------------
#                  Links
#               -------------
#               Links Manager
#
#         File: Readme.txt
#  Description: Program Documentation
#       Author: Alex Krohn
#        Email: alex@gossamer-threads.com
#          Web: http://www.gossamer-threads.com/
#      Version: 2.01
#
# Copyright 1998 Gossamer Threads Inc.  All Rights Reserved.
#

TABLE OF CONTENTS
    1. Welcome
        1.1 Copyright Notice
        1.2 About the Script
        1.3 System Requirements        
    2. Installation
        2.1 Quick Install
    3. Problems
    4. Revision History

1. Welcome
====================================================

1.1 COPYRIGHT NOTICE:
----------------------------------------------------
Please read the following as it has changed from version 1.x!

This script is being offered as shareware. It may be used and
modified free of charge for personal, non-profit and academic 
use under the following conditions:

    1. All copyright notices, and headers remain intact and
       unmodified in the source.
    2. The 'Links Engine Powered By Gossamer Threads' text
       remains/is placed on the links home page.
    3. The text is linked to:
            http://gossamer-threads.com/scripts/links/
    4. The URL where the script is being used is registered at:
            http://gossamer-threads.com/scripts/register/

Commercial use must register the script for a one time fee of $150 US. 
With registration you receive 60 days of email tech support, free upgrades
within the version number, and discounts on other products/add ons. This
is just a minimum you will receive, but in all likelihood, you will get more
(for instance, Links 2.0 is a free upgrade to 1.x users)! With paid
registration, you also do not have to display the link back to Gossamer Threads.

A mailing address and an online order form for Visa and American Express can
be found at:
            http://gossamer-threads.com/scripts/register/

Selling the code for this program, or a program derived from Links, without 
prior written consent is expressly forbidden. Similarly, redistributing
this program, or a program derived from Links, over the Internet, CD-Rom 
or any other medium is expressly forbidden without prior written consent. 
By using this program you agree to indemnify Gossamer Threads Inc. 
from any liability.

If you really like the script, feel free to use a powered by Gossamer
logo found at (please copy to your own server):

    http://www.gossamer-threads.com/images/powered.gif
    
Please do not take credit for the script. 

1.2 About the Script
----------------------------------------------------
Links is a program that allows you run and manage your own mini Yahoo. 
These sites are extremely popular because they allow you to apply your
specialized and focused knowledge of a subject area to create a great
directory! Sites like Yahoo provide the visitor with way to much information,
and they often give up in frustration. 

With Links, you can easily run and manage a directory of upward 10,000 links
without the headaches of FTP, coding HTML, checking links, validating, etc. All
of these features and many more are available through a browser interface!

Links is based primarily on static html pages. It does not generate html
pages every time a user wants to view a category of links. Instead the 
administrator "builds" the directory when it should be updated. The 
script then recreates all the necessary html. 

This model has several advantages: 

    * security - visitors are viewing static pages and never interact
                 with the actual database.
    * speed - since the user is primarily requesting regular pages
              the server does not have to work as hard.

1.3 System Requirements
----------------------------------------------------
Links requires the following:

    * A working copy of Perl 5 (might have problems on versions less then
      5.002).
    * A basic understanding of both Perl and CGI.
    * Links 2.0 now has support for link checking without LWP! The script
      will first try and use LWP (which will be much faster), but if it can't
      it will try and use IO::Socket, a standard module. You can get better 
      performance by having your ISP install LWP. It can be found at:
            
            http://www.perl.com/CPAN/modules/by-module/LWP/
            
This program has been tested on the following platforms: Win95 (without 
file locking), WinNT, HP-UX 9.05 and Linux 2.0 and should work on any system 
with a working, fairly current, Perl5. 

2. Installation
====================================================

2. Installation
----------------------------------------------------
If you are comfortable with Perl, here's some instructions that
should get the script up and running quickly. If you are upgrading 
from Links 1.1, please consult the Upgrade.txt file.

1. Unzip the distribution and you should see two directories cgi-bin
   and pages. Upload everything in cgi-bin in ASCII mode to a directory
   on your server that can run cgi. For example, I recommend creating a
   directory called links off your cgi-bin. You'll end up with a structure 
   like:
                /cgi-bin/links        - User cgi like search.cgi, add.cgi, etc.
                /cgi-bin/links/admin  - All the admin programs.
                
    Make sure all the files are transferred in ASCII mode!!
    
    Upload the cascading style sheet: links.css and the background image
    to the directory where you want the pages created.

2. Double check that the Path to perl is correct. Links defaults with
        #!/usr/local/bin/perl
  
   If this is incorrect, you'll need to edit the first line of every .cgi
   program, and change it to where you have Perl version 5 installed.

3. Set permissions:
        chmod 755 (-rwxr-xr-x) on all .cgi files.
        chmod 666 (-rw-rw-rw-) on all files in the data directory.
        chmod 666 (-rw-rw-rw-) on all your template files (if using the online editor).
        chmod 777 (drwxrwxrwx) on the hits directory
        chmod 777 (drwxrwxrwx) on the ratings directory
        chmod 777 (drwxrwxrwx) on the directory where Links pages will be created.      

4. Edit links.cfg and set as a minimum:
    
    - PATH to your admin directory, this is a system path, not a URL! If you
      get an error message about not finding a file, look at the SCRIPT_FILENAME
      variable for clues on the proper path!
        $db_script_path   = "/alex/links/cgi-bin/admin";
    - URL to your admin directory. This should start with 'http://'.      
        $db_dir_url       = "http://localhost/links/cgi-bin/admin";
    - URL of where your user cgi is kept. If you kept the default installation,
      it will just be one level below the admin directory.
        $db_cgi_url       = "http://localhost/links/cgi-bin";
    - PATH to where your pages are built. This should be a system path, not a URL.
        $build_root_path  = "/alex/links/pages";
    - URL of where your pages are.      
        $build_root_url   = "http://localhost/links/pages";  
    
    - Set one of either:
        $db_mail_path     = '';         $db_smtp_server  = '';
      If you are on NT, set $db_smtp_server to your SMTP server. If you are on UNIX,
      set $db_mail_path to your sendmail program.
    - Set the email address for who all emails will come from:
        $db_admin_email   = ''; 

5. ** Password protect your admin directory. Never leave your admin directory unprotected
   in a public site, your whole directory could be erased!!
   
6. Give it a test! Go to: http://yourserver.com/cgi-bin/links/admin/admin.cgi or wherever
   you setup admin.cgi, and you should see the admin screen. Try (in this order):
   
        1. Add a category.
        2. Add a link in that category.
        3. Build pages.
        4. Search for the one link, using the new pages created.
        5. Add a link from the new pages created.
        6. Validate a link from admin.
    
    If everything goes ok, you should be all done!

If you run into problems, take a look at the Problems section
below and check out the Links forum at:
    
    http://gossamer-threads.com/scripts/forum/
    
4. Problems
====================================================
* 500 Server Errors.
I've tried to remove as much chance of 500 server errors as possible. If you still 
get one check the following:

   - Did you upload/ftp the file up in ASCII mode?
   - Is the path to Perl right? (#!/usr/local/bin/perl)
   - Is the path to Perl to a version of perl5?
   - Are there any syntax errors you made when editing links.cfg?
   - Are the file set to the right permissions?
   
500 server errors are 99% of the time caused by: File uploaded in BINARY mode, or
path to perl is wrong or goes to perl version 5.

* Fatal Error: (Maybe you didn't strip carriage returns after a network transfer?)
You've uploaded something in BINARY mode! Make sure you transfer ALL the files
in ASCII mode. Don't trust your FTP programs AUTO-DETECT mode, but instead
transfer everything in ASCII.

* Header/Footer problems.
The script looks at whatever you have entered in the header/footer field. It first
checks to see if what you entered is a file and if it is it will insert the contents
of that file. 

If it can't find/open the file the script will insert the value. This way you can
either specify a file or some text to use as a category header/footer. 

TIP: If you want the same text to appear on all pages, don't use a header. Just put
that text into site_html.pl.

* Fatal error: unrecognized token my( ...
You have the path to perl pointing to perl version 4. Make sure it goes to perl
version 5.

Remember: Please be careful with the script as it does create 
directories and other potentially hazardous stuff. 

Good Luck!

Alex Krohn
Gossamer Threads Inc.

4. Revision History:
====================================================
July 9, 2000
    o SECURITY FIX: Fixed admin security hole.
    o BUGFIX: Removed old lwp::parallel based verifier, replaced with simple check.

Jan 26, 1998
    o BUGFIX: Cleaned up some typos in the templates.
    o ADDED: Can now use code refs in templates.
    o ADDED: Backup database files on a regular basis.
    o ADDED: Link checking without LWP.
    o ADDED: Ratings.

Jan 12, 1998
    o Release: 2.0b4. More bug fixes and new features.
    o BUGFIX: sort routines not defined in db.pl.
    o BUGFIX: multiple modify would wipe data.
    o BUGFIX: refined link validating expression.
    o BUGFIX: added validation check to category name.
    o BUGFIX: email address not verified for form.  
    o BUGFIX: random did not work with 1 link.
    o BUGFIX: search.cgi will now ignore single char searches.
    o ADDED: Duplicate URL check to link validation.
    o ADDED: Unsubscribe option to email newsletter.
    o ADDED: Staggered Build, build your directory in steps for low-mem servers.
    o ADDED: Major overhaul to template support. Much improved!
    o ADDED: Search.cgi will now accept any database field.

Dec 24, 1998
    o Release: 2.0b3. More bug fixes.
    o BUGFIX: nph-email not splitting newsletter addresses.
    o BUGFIX: modify.cgi not validating record properly.
    o BUGFIX: add.cgi incorrectly reporting New/Cool as categories.
    o ADDED: auto choosing of category is now an option.
    o BUGFIX: Top n Cool Links was being displayed with a %.
    o ADDED: using templates is now an option in links.cfg
    o BUGFIX: default 'red' smtp server and @referers removed.
    o BUGFIX: update site_html_templates.pl so it wasn't missing pages.

Dec 22, 1998
    o Release: 2.0b2. Clean up most of the major bugs.
    o BUGFIX: Incorrect require in jump.cgi.
    o BUGFIX: Double declaration of $output in db_utils.pl: 120.
    o BUGFIX: Not a hash ref in load_template in admin_html.pl: 974.
    o BUGFIX: nph-email.cgi not setting To field properly: 115.
    o BUGFIX: missing $nph++ in nph-build.cgi: 151.
    o BUGFIX: what's cool percent generated incorrectly in nph-build.cgi.
    o BUGFIX: add.cgi would report category as index.html.
    o BUGFIX: modify.cgi did not display category list properly.
    o BUGFIX: email_del misnamed, should be email-del. Same for add, mod.
    o BUGFIX: overhauled Template.pm to load from strings as well as files.
    o BUGFIX: couldn't detect sendmail if -t parameter used.
    o BUGFIX: removed -odq option on sendmail due to incompatibility.
    o ADDED: Links to other search engines if search fails.
    o ADDED: Mailing log. All outgoing messages are logged.
    
Dec 17, 1998
    o Release: 2.0b1. First beta of a long overdue update! Lot's 
      of new features.
    o Admin Database engine rewritten -- 40% faster!
    o Public search engine rewritten -- 100% faster, plus
      Altavista style next hits toolbar.
    o Link checker rewritten -- 300% faster, also a quick
      and a detailed check, plus a link summary at the bottom.
    o Fully NT compatibile -- no more IIS patch, and also 
      include SMTP support for emailing.
    o Mass mail link owners -- either targeted, or entire
      directory. Custom tailor each message using the link
      owners information.
    o Built in subscriber list -- users can subscribe to be kept
      informed of what's new. Admin can auto email a newsletter
      outlining all new links added recently.
    o Enhanced category checker -- can now move batches of links
      into a new category, or delete batches of links that no longer
      have a category.
    o Default Rejection letter for links that aren't suitable. 
      can alter before sending.
    o Can modify multiple links at once through admin.
    o Default HTML is HTML 4.0 compliant, and use Cascading Style sheets
      that degrade nicely.
    o New template support -- Home, New, Cool and Category pages can
      be created from templates. Also, add, modify and reject letters
      are as text templates and not in the code. All templates are 
      parsed for tags so they can be customized easily.
    o Rewritten hits counter -- much more stable, and won't corrupt
      the entire url database if one link goes wrong.
    o Detailed View -- you can now build a separate page for every link! This
      is really useful if you have a field called Review that is too long to
      be displayed on the category page, but rather you want the user to click
      on the link and be presented with a full description of the link.
    o Referer check for add.cgi -- stop auto-submit robots from submitting
      junk to your site. This will block all submissions unless they come from
      your site.
    o Add category - when clicking on add from a category page, the user's
      category is automatically assumed to be in that category.
    
Jun 24, 1998
    o Release 1.11 - Minor bug fixes.
    o Jump.cgi is overhauled. Fixed occasional file corrupting
      bug found on heavily trafficed site. Also now works with
      random link.
    o Added admin email check in add.cgi and modify.cgi.
    o Added perl 5 check for admin.cgi.
    o Added sorting modification to db.pl.
    o Added better error checking for header/footer inclusions.
    o Can only validate 10 links at a time.

Mar. 13, 1998
    o Release 1.1 - Lots of new changes. First major revision to 
      the script.
    o Categories are now completely separate from links. 
    o Can add/remove/delete/modify categories exactly as you can links. 
    o More category information like: meta tags, header, footer, etc. Can
      easily, at least for me ;), add new attributes. 
    o No longer need one link per category. 
    o Routine to check to make sure every link has a matching category. 
    o Routine to check for duplicate links (based on domain name). 
    o Admin now allows you to edit multiple links at once. No more 
      search-modify-search-modify-search. 
    o Validate links now auto-emails notices that the link has been 
      updated or added (Sorry, nothing about deletion as each case 
      will be different and you'll
      probably want to send a personal message). 
    o Validate links now lets you edit before adding. 
    o Verify links has been re-vamped. It still uses LWP, but now can 
      run using multiple processes! This should speed things up a lot 
      if you are using a system that supports fork() (not win95, not 
      sure about NT?). 
    o Both nph-verify and nph-build can be run from the command line
      and don't produce html. 
    o Default of two columns in site_html, as well as a lot of html 
      fix up. 
    o nph-build rewritten, to make a lot more sense (to me at least).
       Shouldn't affect site_html though.
    o Can now split up links so they span multiple pages.                
    o What's New Page can now span multiple pages.

Dec. 4, 1997  
    o Release 1.0 - First Official Release
    o Category Descriptions, Related Categories added.
    o Modify listing script added.
    o Date format easier to modify.
    o Various bugs squashed.

Nov. 10, 1997
    o Release 0.9 - First Beta Released.
====================================================
