************************************************************************
* Orca Forum v4.3c                                                     *
*  A simple threaded forum for a small community                       *
* Copyright (C) 2004 GreyWyvern                                        *
*                                                                      *
* This program is free software; you can redistribute it and/or modify *
* it under the terms of the GNU General Public License as published by *
* the Free Software Foundation; either version 2 of the License, or    *
* (at your option) any later version.                                  *
*                                                                      *
* This program is distributed in the hope that it will be useful,      *
* but WITHOUT ANY WARRANTY; without even the implied warranty of       *
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        *
* GNU General Public License for more details.                         *
*                                                                      *
* You should have received a copy of the GNU General Public License    *
* along with this program; if not, write to the Free Software          *
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 *
* USA                                                                  *
************************************************************************

- Script Requirements:

PHP 4.1.0+
MySQL 3.23+


- Upgrading Notes:

This script is set to upgrade your MySQL tables automatically.  Make
sure you make the required MySQL changed to the of_head.php file
BEFORE uploading over your old version.


- Changelog
4.3c - Patch for Secunia advisory #17721 - MySQL Injection
       http://secunia.com/advisories/17721/

Full 4.x changelog:
  http://www.greywyvern.com/orca/of_changelog.txt


- Installation Instructions


1. The Orca Forum comes with five (5) files:

forum.php         - Plain forum setup
of_head.php       - Header PHP
of_body.php       - Body PHP
of_style.css      - Stylesheet
of_lang_en.php    - English language file

Make sure you have all of them! If you need a language file other than
English, you can take a peek in the "langpack" which contains all
contributed language files to date.  You can download the file from my
site using this link:
  http://www.greywyvern.com/code/php/of_langpack.zip

You can see what files this zip contains by visiting here:
  http://www.greywyvern.com/orca/of_langpack.txt


2. Open the "of_head.php" file.

At the top of the file you'll see two lists of variables under the
headings "SQL Information" and "Other User Variables".  Most of these
should be self-explanatory, but I'll mention a few which might not.

The SQL variables $dData['hostname'], $dData['username'],
$dData['password'], and $dData['database'] are all values relating to
logging into your database system.  If you don't know these, your host
should.  Send them an email.

$dData['tablename'] is any name you want, without spaces.  If you want
to have more than one forum running on your site, just give them all
different names.

$fData['admin'] and $fData['password'], like previous versions of this
script, allow you to delete messages and threads from the forum using
the Post/Reply form.  Make sure they are difficult to guess.  If you
ever forget them, you can just reopen the "of_head.php" file and find
them again.  If you want the forum to email you whenever someone starts
a new thread, set $fData['notify'] to true.

This forum allows users to request email replies to posts they've made
and applied their email address to.  Because of this, the script needs
a "From:" header in order to send mail properly.  The variables
$fData['emailfrom'] and $fData['emailreply'] make up this header.

$fData['wordwrap'] sets the maximum number of characters in a row the
forum will allow before inserting white space and allowing line breaks.

$fData['threadspp'] sets the number of threads to show per page.  If
more threads exist than can be shown on one page, the forum will start
to paginate automatically.

For the benefit of search engines, the forum now sets a variable within
the head.php file which you can use for the HTML title of your page.
This variable is $fData['pagetitle'] and the value you set for it here
will be its default value, shown on the forum home page.  When a single
message is being viewed, the subject of that message will be placed in
this variable.  The example setup in forum.php has been changed to show
you how to insert this variable in your HTML page title.

A very important variable which tells your blog how to function is the
$fData['threadcollapse'] variable.  By setting this variable to true,
comments won't be displayed on the main page until the "+" character is
clicked. Setting it to false will cause all comments to be displayed
below each top level post.

$fData['maxthreads'] is the total number of threads your forum will
store.  After the limit is reached, the oldest threads will start
getting deleted. Set this value to 0 if you never want threads to
disappear.

The forum can order threads in two ways.  Using the normal method, the
opening post of each thread remained in a static order.  The thread with
the newest top level post was always at the top.  Using the last-post
method, threads change their order depending on the newest post within
the thread.  For example, if a thread currently in position three has a
reply posted to it, the whole thread will move to the top of the list.
To use the normal thread ordering method set $fData['oblastpost'] to
false.  The variable is true (last-post method) by default.

The following four lines are commented out with double forward slashes.
They will not be applied unless you uncomment them (remove the slashes).

These lines control two different types of message filters for your
forum.  The first is a ban-by-IP ability.  If you hover your mouse over
the name of a poster in your forum, their IP will appear as a title.
If someone is being a pain, you can ban these IPs using the "ipban"
array.  Just uncomment the variable and set the value to the offender's
IP and they will no longer be able to post.  You can also ban ranges of
IPs by omitting the last octet as seen in the second commented line.  If
you want to ban more than two IPs, just copy and paste the whole line.
There is no limit to the number of IPs you can ban.

The "textban" array works in a similar fashion except it will search the
message text for terms or phrases you specify and REFUSE THE ENTIRE POST
if there is a match.  Using this type of ban is NOT trivial!  The
examples given show "casino" and "gambling", but keep in mind that if
one of your friends is trying to tell you a story about how they went to
the casino and made $500 last night, it will be refused.  The best use
for this type of ban is for nasty/spam URIs or very specific text
filters.

The final three variables control the replacement of the expand (+) and
collapse (-) characters with images of your choosing.  Enable the images
by setting $iData['showimages'] to true, and giving the appropriate
filenames to the next two variables.  The script comes with two default
images for this purpose.

After changing any of the other variables to suit, you can now close
this file.


3. Place all of the files except "forum.php" in the directory /orca on
your web server.  If you are planning on using images for the expand and
collapse characters, upload the two images to the /orca directory as
well.

Place "forum.php" in the directory just below /orca which will result in
the following setup:

/forum.php
/orca/of_head.php
/orca/of_body.php
/orca/of_style.css
/orca/of_lang_en.php

NOTE: You may actually setup the files any way you wish, you only have
to change the includes as listed in "forum.php" and the image includes
(if necessary) in "of_head.php", but I find it easier to keep them
together in this fashion.


4. Now visit forum.php through your internet browser.  If you get the
welcome message, the forum has successfully created the database to
store the messages and is ready to go!


5. You can snip a thread at any point simply by using the reply/post
form.  Doing this will also delete all replies to that message.

First select a message to delete and note it's post ID.  You can usually
find this value using the URL of the post.  It's the number after
"msg=".

Now enter your admin name ($fData['admin']) in the Name field, your
password ($fData['password']) into the Subject field and the message
number you want to delete in the message textarea.  Hit submit and the
thread will be snipped.  If you don't specify a message number, the
current thread you are viewing will be deleted.


6. "forum.php" is really just a simple setup for the script.  Let's see
how we can embed our forum into an existing website.

Open the "forum.php" file.

The Orca Forum requires the following setup:

a) The files "of_lang_en.php" and "of_head.php" included at the very top
of the page before any whitespace or HTML code.  The order is important.
Always include the language file before the head file.

b) The "of_style.css" file included as a stylesheet using a <link> tag.

c) The "of_body.php" file included wherever page content normally goes
on your website.

Other than that, the rest of the HTML/PHP is all up to you!  The forum
is almost 100% styleable using the CSS in the of_style.css file.  A bit
of editing and you can make it look like you built your site around the
forum, instead of the other way around!


************************************************************************
* Please send all questions/comments/bugs to orcaforum@greywyvern.com  *
*                      -------------------------                       *
* Thanks for using my scripts!  I hope you enjoy them as much as I do  *
************************************************************************