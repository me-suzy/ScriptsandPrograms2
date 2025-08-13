!! Please check back regularly for updates !!
--------------------------------------
Newslead 2
Added features include...
 - Editing / Deleting of articles.
 - Deleting of specific comments.
 - Easier setup.
 - Modular coding; easier to install updates, and...
 - HACKS & Plugins!

Installing the included version of Newslead.

1. Preparing.
=============
FTP to your hosting account.

Create a subdirectory called 'newslead' or similar in your cgi-bin directory.

In your site root, create a new directory called 'news'. CHMOD it to 777.

2. Editting.
============
[The variables file comes setup so that the script will work if you install all of the files in the same directory and CHMOD that directory to 777.]

Note: all urls are relative to your 'news' directory or in absolute form
E.g. http://www.youserver.com/news

Load vars.pl in your favourite text editor.

$latest: This variable controls how many articles are on you main news page.
E.g. 5;
$newshtml: This is the file that the headlines and summaries are written to. 
E.g. "/homes/sites/username/web/news/news.html";

$template: Main template for formatted output (HTML)
E.g. "./template.html";

$newsdir: Directory for *.xml and *.htm files, exclude trailing '/'. Please note, this is NOT he URL, but the system path to the directory!
E.g. ".";

$newsurl: The URL to this news directory (so the articles can be linked to the main page)
E.g. "";

$newspl: URL to the script 'showcomments.pl';
E.g. "showcomments.pl";

$delpl: URL to the script 'delete.pl';
E.g. "delete.pl";

$editpl: URL to the script 'edit.pl';
E.g. "edit.pl";

$commentpl: URL to the script 'edit.pl';
E.g. "comment.pl";

$delcurl: URL to the script 'delcomment.pl';
E.g. 'delcomment.pl';

$heading: Heading for the output of the headlines.. (remember, " requires a \ before it)
E.g. "<b>Main news</b><br><a href=\"post.html\">Post a news article</a><br>
<a href=\"index.html\">View the News archive</a><br><br>";

$cheading: Heading for the output of the article with comments..
E.g. "<b>Comments</b><br><a href=\"news.html\">Back to news index</a><br><br>";

$aheading: Heading for the output of the article with comments..
E.g. "<b>Archives</b><br><a href=\"news.html\">Back to news index</a><br><br>";

$users{'username'}: Adding users...
Users who can post here. Items should be in the format of $users{'username'} = "email&password";
Remember '@' signs need a '\' preceeding them.
Note: these are case sensitive and the password cannot be nothing.
E.g.
$users{'Admin'} = "admin\@site.com&easy";

$pwd: Password to delete articles and comments #
E.g. "easy";

## template.html
If you want anything from the script to be displayed, you must have the <!--insert--> included in the file, ON A SEPARATE LINE!

## headlines.pl and headlinessi.pl
Locate the part in the file that reads..
## Edit the section between 'print newshtml <<NewsEntryHTML;' 
## and 'NewsEntryHTML' to influence the appearance of each 
## individual news item on your news page.
All the HTML between these two points allow you to edit the HTML for each article.

## archives.pl and archivessi.pl
Locate the sections in the file that read..
#############################################################
## Edit the section between 'print thec <<NewsEntryHTML;'   # 
## and 'NewsEntryHTML' to change the appearance of each     #
## individual article on your comments pages.               #
#############################################################
,
#############################################################
## Edit the section between 'print thec <<CommentsForm;'    #
## and 'CommentsForm' to change the appearance of each      #
## individual article on your comments pages.               #
#############################################################
and
#############################################################
## Edit the section between 'print thec <<HTMLforComments;' #
## and 'HTMLforComments' to change the appearance of each   #
## individual comment on your comments pages.               #
#############################################################

You can edit template.html as well.

3. Finalisation.
================
Save vars.pl.
Upload
archives.pl
archivessi.pl
comment.pl
delcomment.pl
delete.pl
edit.pl
headlines.pl
headlinessi.pl
post.pl
showcomments.pl
template.html
vars.pl
in ASCII mode to your cgi-bin/newslead directory.

CHMOD *.pl to 755 and template.html to 666.
Upload (and edit, if you want) post.html. Most the form action to point to the url of post.pl. Call post.html from your browser, and you should be able to post news articles!


the *ssi.pl are included so that you can use them from server side includes.
E.g. <!--#exec cgi="archivessi.pl"-->

- Peter
http://www.incomplete.co.uk/


Disclaimer..
Usual text about if it goes wrong, dont blame me, not guaranteed to be fit for purpose or of merchantable quality, I can't be held responsible for ANY damages. I explicity disclaim all myself from liabilities that may arise.
By using the script, you agree to this.
