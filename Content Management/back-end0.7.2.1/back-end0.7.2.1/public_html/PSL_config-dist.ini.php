; <?php die(); DO NOT REMOVE THIS LINE
;;;;;;;;;;
;; config.ini.php - Configuration File for PHPSlash
;; $Id: PSL_config-dist.ini.php,v 1.4 2005/03/22 16:06:55 krabu Exp $
;;;;;;;;;;

;;;
;; Welcome to PHPSlash!
;;
;; This file controls many aspects of PHPSlash's behavior.  To avoid
;; having your settings clobbered on future updates, rename it from
;; $RCSfile: PSL_config-dist.ini.php,v $ to something else, such as "config.ini.php".  Specify
;; that filename in config.php or config-dist.php, the script which
;; parses this one and fills in derived values.
;;
;; This file contains information which you obviously do not want
;; world-visible.  To protect it, do one of the following:
;;
;; - Place it outside the web filesystem (UNIX standards would probably
;;   suggest that it go in an "etc" directory).  Make sure the parser
;;   script knows the full path.
;;
;; - Keep it in the main web directory of your site, but KEEP THE PHP
;;   FILE EXTENSION AND TAGS.  That way, if someone types the filename
;;   in the browser URL bar, all that will be displayed is a parse
;;   error.  Giving this file an unparsed extension such as ".ini" will
;;   probably cause the entire file to be displayed as plain text.
;;
;; Users familiar with INI files can skip down to the first directive,
;; which is 'basedir' below.  Otherwise, please read these first
;; comments carefully.
;;
;; The syntax of the file is extremely simple.  Whitespace and lines
;; beginning with a semicolon are silently ignored (as you probably guessed).
;; Section headers mean that configuration will be placed in a subarray.
;;
;; Directives are specified using the following syntax:
; directive = value
;; Directive names are *case sensitive* - foo=bar is different from FOO=bar.
;;
;; The value can be a string, a number, a PHP constant (e.g. E_ALL or
;; M_PI), one of the INI constants (On, Off, True, False, Yes, No and
;; None) or an expression (e.g. E_ALL & ~E_NOTICE), or a quoted string
;; ("foo").  Use double quotes for strings.
;;
;; This file format is built for speed.  Expressions in the INI file are
;; limited to bitwise operators (|,&,~,!) and parentheses.  If you need
;; to make more complex configurations, put them in a copy of
;; config-dist.php
;;
;; Boolean flags can be turned on using the values 1, On, True or Yes.
;; They can be turned off using the values 0, Off, False or No.
;;
;; An empty string can be denoted by simply not writing anything after
;; the equals sign, or by using the None keyword:
;;
;  foo =         ; sets foo to an empty string
;  foo = none    ; sets foo to an empty string
;  foo = "none"  ; sets foo to the string 'none'
;;;

;;;
;; To Developers or extenders of PHPSlash code:
;;
;; These values all go into the $_PSL array.  For information on how
;; this file is parsed see http://www.php.net/parse_ini_file
;;
;; We are trying to migrate to a dotted-keyword form for configuration
;; directives e.g., "config.ini_file".  Section headers ARE currently
;; being utilized, but keep keywords globally scoped all the same.
;;
;; GOOD:
;[mymodule] <-- Norms for use of section headers have not been determined.
;;              For now, call them optional.
;mymodule.enabled = true
;mymodule.file = "foo.php"
;;
;; BAD:
;[mymodule]
; enabled = true
; file = "foo.php"
;;
;; This allows for maximum clarity to the configurer of a PHPSlash site,
;; who may or may not be a hacker.
;;
;; Comment Customs:
;; Two semicolons mark comments which are interesting to humans.
;; One semicolon marks comments which are disabling actual code.
;;;

;;;
;; Here is where the configuration actually begins!
;;;

;;;;
;; Site determinant variables
;;
;; 'dir' means this path is a directory in the complete filesystem
;; 'url' means this path is interpreted by the web server
;; Thus 'basedir' and 'rooturl' are front and back doors to the same place.
;; No trailing slashes on either!
;;
;; The complete filesystem path to the pages
basedir = "/path/to/phpslash/public_html"
;;
;; The complete filesystem path to the class directory
classdir = "/path/to/class"
;;
;; The base URL for the PHPSlash site.
rooturl = "http://localhost/phpslash/public_html"
;;
;; The directories and URLs below have default values based on the above
;; three.
;;
;; Complete filesystem path to the template files
;; Default: ${basedir}/templates
; templatedir = ""
;;
;; URL for the adminstrative pages
;; Default: ${baseurl}/admin
; adminurl = ""
;;
;; URL for PHPSlash images
;; Default: ${rooturl}/images
; imageurl = ""
;;
;; URL for PHPSlash topic images
;; Default: ${imageurl}/topics
; topicimageurl = ""
;;
;; Complete filesystem path to the topic images.
;; Default: ${basedir}/images/topics
; topicimagedir = ""
;;
;; Complete filesystem path to the language and localization files
;; Default: ${classdir}/locale
; localedir = ""
;;;


;;;
;; Database variables
;;
;; Make sure you initialize the database with one of the sql scripts in
;; the PHPSlash distribution and create the user below.
DB_Host = "localhost"
DB_Database = "phpslash"
DB_User = "phpslash"
DB_Password = "phpslash"
;; end of database variables
;;;

;;;
;; PHPLIB configuration
;;
;; Source directory for PHPLIB classes and functions.
;; Default: classdir/phplib/php/
;; Note: Make sure you have a trailing slash (unlike the other directory
;;       variables!)
; phplibdir = "/classdir/phplib/php/"
;;
;; Loader script for PHPLIB classes and functions
;; DEPRECATED: The prepend actions now built into config.php
;prependfile = "/path/to/phplib/php/prepend.php"
;; end of PHPLIB config
;;;

;;;
;; RSS Exporter Values:
;;
;; These values are used in your RSS Feeds generated by backend.php as
;; well as by PHPSlash for the pages your views see.  Please take a
;; moment and adjust these to the correct value
;;
;; Name of the site.  This appears for example on the titlebar of
;; browser windows.
site_name = "PHPSlash"
;;
;; Email address to reach a human.
site_owner = "info@phpslash.org"
;;
;; A catch phrase for your site.
site_slogan = "Slash for you, Slash for me"
;;
;; A Longer version of the title.
site_title = "PHPSlash: The Web Stops Here"
;;
;; end RSS exporter values
;;;

;;;
;; Mailing list configuration
;;
;; Use this for the subject of periodic e-mails with headlines sent to
;; users of your site.
mailinglist_subject = "PHPSlash Times"
;; end of mailing list configuration
;;;

;;;
;; randomization
;;
;; Use this secret string when generating site unique variables.  For
;; security's sake, change this to something, anything.
magic = "ChangeThisToSomethingUnique"
;;;

;; End of Site determinant variables
;;;;;

;;;;;
;; Optional Configuration

;; The "skin" is the basic look for your site.  PHPSlash ships with two,
;; both HTML 4.0 Transitional.  The "basic" skin does all formatting
;; with CSS, whereas the "default" skin uses <html> tags and such.
defaultskin = "basiccurves"

;;;
;; Registration mode:
;; 'reg' - allow users to register themselves and create accounts on your site
;; 'log' - restrict the creation of new accounts to administrators
authmode = "reg"
;;;

;;;
;; NavBar settings
;; The Navigation bar gives links to various areas of the site.
;;
;; Turn the Admin link on/off in the navbar
;; Joe Stewart writes:
;;
;; "The login/logout links are available in the menuarray now.  This was
;; the last of the navbar links that were in NavBar.class instead of the
;; data array.  Now you can just comment out the login link and still
;; have the logout link available."
show_admin_on_navbar = false
;; end of NavBar settings
;;;

;;;
;; TopicBar Options
;;
;; These following two variables control how the topic icons get
;; organized after submitting a story:
;;
;; Automatically reorganize the topic icons after publishing a new article
auto_renorm = false
;;
;; Put this number of icons in the topic bar
bar_limit = 5
;; end of TopicBar settings
;;;

;;;
;; Comment Options
;; The comment_defaultmode variable allows you to define how the comments
;; will look the first time a visitor views a story.  It can be one of the
;; following options:
;; thread:  the first comment is displayed, the replies are then listed in
;;          index style
;; index:   the subject/author/date is listed.  you have to click on the
;;          link to get the full subject
;; nested:  all the comments are displayed and are indented to show the
;;          parent/child relationship between the comments.
;; flat:    all the comments are displayed like the nested view but without
;;          any indentation.
;; none:    no comments are displayed.
comment_defaultmode = "nested"
;;
;; Mark new comments pending (basically allows you to moderate the posts).
default_pending = false
;;; end of Comment Options

;;;
;; Poll Options
;;
;; When creating a poll, you have a question and at least this number of
;; blanks.
poll_min_answers = 8
;; end of Poll Options
;;;

;;;
;; Search Page Options
;;
;; Return this many results when searching
search_maxresults = 20
;; Allow search of the comments as well
allow_comment_search = true
;;; end of Search Options

;;; Submission Options
;;
;; Delete submission when "edit as story" is clicked on the submissions
;; page
submission_autodelete = false
;;; end of Submission Options

;;; Article(Story) Options
;; Put Next and Previous story links on each article page
article_nextprevlinks = true
;; Keep track of hits on a story (the drawback is an extra DB query).
article_updatehits = true
;;; end of Article Options

;;;
;; Block Options
;; Use this number as the minimum number of options available for a block.
block_optioncount = 4
;;; end of Block Options

;;;
;; Homepage options
;;
;; Name of the default section (what you want browsers to see when they
;; first enter the site
site_homesection = "Home"
;; ID in the database of the above section
home_section_id = 3
;; use this script as the default for the site (relative to rooturl)
mainpage = "index.php"
;;; end of Homepage options

;;;
;; Notification Options
;;
;; Notify the site owner when a submission is made (default is false)
; submitnotify = true
;;
;; Notify the site owner when a comment is submitted (default is false)
; commentnotify = true
;;;

;;;
;; Story Cache options
;;
;; Cache the formatted output of the story in a serialized variable for
;; this many seconds.  Zero seconds => never cache the story.
;; FYI: 3600 seconds == 1 hour; 86400 seconds == 1 day.
expirestory = 0

;; Cache the "related links" of the story in a serialized variable for
;; this many seconds.
;; WARNING: always set expirerelated >= expirestory
expirerelated = 14400
;; end of Story Cache options
;;;

;;;
;; Debug options
;;
;; Debug variables and messages (i.e., do not suppress output from calls
;; to the debug function)
debug = false
;; Send debugging information here.  Use:
;;  'now' for output to the browser
;;  'log' for output to the admin log
;;  'mail' to e-mail output
debug_type = "now"
;; Mail debug output here (e.g., "webmaster@phpslash.org");
;; CAVEAT: Generates one message per debug call, not per page.
debug.mailto =
;; Recurse through data structures down this far.
;; Use -1 if you are not afraid of circular references, or
;; 1 for pre-M7 behavior
debug.max_recursion_level = 10
;; Escape html in debug output
debug.escape_html = true
;; end of Debug Options
;;;

;;;
;; Informational Logging
;; Log messages from PHPSlash to administrators in the database
use_infolog = true
;;;


;;;
;; Locale Options
;;
;; Default language ( ISO format)
language = "en"
;;
[locale]
;; Use this locale for formatting date and time strings
LC_TIME = "en_US"
;; Use this locale for the translation of messages
;; (NOT USED -- use 'language' directive instead)
LC_MESSAGES = "en"
;; end of locale definitions
;;;

;;;
;; Timezone Options
[timezone]
;; Set this to something true if you want times to be calculated in a
;; different time zone.
engine = true
;;
;; Now you have to choose the implementation of time zones.  If you are
;; on a UNIX platform and can set environment variables, native is probably
;; the implemention for you.
native = true
;;
;; Otherwise, you can use the time zone classes which interface the
;; time zone database, if you have one.  Specify the directory here (good
;; to do even if you are using native time zones):
dir = "/usr/share/zoneinfo"
;; For MacOSX Users: some files are missing in the time zone database
;; installation.  Uncomment these lines (and correct the directory) to use
;; the PHPSlash-distributed copies.
; zone_file = "/home/username/phpslash-0.7/contrib/zone.tab"
; country_file = "/home/username/phpslash-0.7/contrib/iso3166.tab"
;;
;; If you can't do that either, you are stuck specifying time zones as
;; POSIX-compatible strings; see below.  In any case, you need to
;; specify the server's default time zone.  If you are using native
;; timezones and want the default to be the actual server's setting,
;; enter it this way (find the right path):
; default = ":/etc/localtime";
;;
;; If you are not using native timezones, but are using the time zone
;; database, specify a meaningful time zone name here.  You can also use
;; this to fake the server's time zone.
name = "America/Chicago";
;;
;; Finally, if all you are able to do is specify a POSIX string, do it
;; like this:
; name = "GMT+0BST,M4.1.0/1,M10.5.0/2";
;; end of timezone definitions
;;;

;;;
;; Metatag Definitions
;;
;; Metatags will be inserted into your site's pages as ... <meta> tags.
;; They are useful for explaining to robots like search engines just
;; what it is your site is concerned with.
;;
;; Metatags that may need to be overwritten later (article description).
;; Other metatags should probably just reside in slashHead.tpl
[metatags]
keywords = "weblog, content management, php"
;;
;; Short summary of your site.
;; Note that this too is used by the backend module in your RSS feed.
;; Please change it!
description = "PHPSlash is a port of the slash-0.2 code from Perl to PHP. Since it was first started, PHPSlash has become a different beast of its own. It currently boasts full HTML templates, an OO design, the ability to operate in a hosted environment, and a bunch of other goodies."
;;; end of metatags definitions

;;;
;; Module registration
;;
;; Modules add functionality to PHPSlash.
[module]
;; Turn any of these off, and the link will not appear on the navbar,
;; nor will the excess code be compiled.  Make sure you're really not
;; using it, though!
;;
;; Set up a glossary page where administrators can insert
;; term definitions
Glossary = true
;; Allow users to subscribe to a mailing list to get
;; headlines (the actual mailing is handled elsewhere; see the file
;; cronmail.php
MailingList = true
;; Provide a container for small bits of HTML to be incorporated into
;; side columns of pages.
Block = true
;; Allow users of your site to leave and peruse comments.
Comment = true
;; Show a row of icons relating to topics for which stories have been
;; posted recently.
TopicBar = true
;; Show a row of links relating to various modules within the site.
NavBar = true
;; Allow administrators to create polls on your site.
Poll = true
;; Allow administrators to create and modify site sections
Section = true
;; Allow administrators to create, modify, and post stories
Story = true
;; Allow users to submit new stories (they won't appear until posted by
;; trusted author, however)
Submission = true
;; Allow administrators to view the informational log
Infolog = true
;; Allow administrators to create/modify/delete authors
Author = true
;; Allow administrators to modify site variables
;; (Not sure how much this is used)
Variable = false
;; Allow administrators to create and modify user groups.
Group = true
;;; end of module definitions


;;;
;; Approved HTML tags for user comments
;;
;; 2 means accept all qualifiers: <foo bar>
;; 1 means accept the tag only: <foo>
[approvedtags]
p = 1
b = 1
i = 1
a = 2
em = 1
br = 1
strong = 1
blockquote = 1
tt = 1
hr = 1
li = 1
ol = 1
ul = 1
;;; end of approvedtags definitions

; DO NOT REMOVE THIS LINE ?>
