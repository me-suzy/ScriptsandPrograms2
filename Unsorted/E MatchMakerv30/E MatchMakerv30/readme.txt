#################################################
# e_Match 3.0beta Readme.txt
#
# e_Scripts Software will install this script for you
# on your site for $39.95.  E-mail us at
# support@e-scripts.com for more information.
#################################################

Contents
	1. Features
	2. Setup
	3. Free vs. Subscription-based operation.
	4. Data files
	5. Banning
	6. Customizing the Language
	7. Customizing the Layout
	8. Restoring your users log.

New Features - 3.0

	1.) Quick Search Builtin.

	2.) Session ID's enhance Security

	3.) Easily adaptable to subscription setup.

	4.) Private Boards can be Cleared by Users.

	5.) Profile Form broken up into separate pages.

	6.) Faster Match Process.

	7.) Separate path for user files allow storage away for web pages.

	8.) Expired and banned users are removed from users.log.

	9.) Banning bug fixed.

	10.) A bunch of stuff I can't think of right now. ;-)

New Features - 2.0

	1.) Profiles form items can be radio buttons or select boxes (menus).

	2.) An email filter to block users from registering using free
		e-mail accounts like hotmail.

	3.) Added script to restore a corrupted users log. (restore.cgi)

	4.) Forget-your-password? script built-in.

New Features - 1.5

	1.) picture uploading.

	2.) unlimited users.

	3.) e-mail works with NT servers.

	4.) Users are notified by e-mail if anything is posted to one of
	their private boards.

	5.) Old, unused files are automatically deleted.  No
	administration is needed.

Features - 1.1

	1.) Registration is automatic, and via e-mail, so users have
	immediate access, and you have a valid e-mail address for each
	user.

	2.) Editable profile form includes three main sections -
	        Information about user,
	        Information about who they're looking for,
	        Common Interests.

	3.) Profile items can be rated according to importance or
	desirability.

	4.) The profile form is very intuitive, and users need only
	answer the questions that they want to answer.

	5.) A users profile is compared with all profiles, a numeric
	"score" is computed for every other user, and a "Match List" is
	generated, sorted according to score.

	6.) When a new match is found, the data is added to both users
	lists, so they both see the new match listed immediately.

	7.) The match list is displayed on a menu page, along with links
	to each match's profile, private message board, and [Nuke'em]
	function.

	8.) Admin can determine how many matches can be listed at once.
	This helps keep the file count under control.

	9.) Users can view profiles of their matches, and interact with
	each match via a private message board.

	10.) Each private message board is accessible only by the user
	and the match.

	11.) Users can remove, or "Nuke", matches that they don't want.
	The nuked match is permanently removed from their list.

	12.) Users can modify their profile and change their password
	whenever they choose.

	13.) Help files are included for the main menu and the profile
	form page.

2. Setup

NOTE: If you're upgrading from 2.0, please read upgrade.txt FIRST.
NOTE: If you're setting this up in an NT server, please read nt.txt.

DIRECTORY STRUCTURE

	Your unzipped ematch30 master directory should include three subdirectories:

	-cgi-bin -     For the scripts (you may have a cgi-local, or a cgi
									directory.  Same thing.)
	-ematch_data - For sensitive user information.
	-public_html - For Browser accessible things.

	cgi-bin subdirectory (your site's main script dir)
	----ematch30 subdirectory
	--------index.cgi - The main script.
	--------setup.cgi - The configuration file.
	--------upload.cgi - The picture uploader.
	--------email-lib.pl - handles e-mail functions.
	--------restore.cgi - Rebuilds to corrupted or missing users log.
	--------pchecker.cgi - emails a user their password on request
	--------free_filter.cgi - Blocks addresses from certain	domains
	--------helphtml.cgi - The script generated help files.
	--------search.cgi - Handles the quick search.
	--------renew.cgi - Manually renews a user's subscription.

	ematch_data subdirectory (will hold user profile and match files.)
	----log subdirectory
	--------user.log - the current user list
	--------xuser.log - banned and expired(subscription) user list
	--------id.txt - Online users' ID list.
	----form subdirectory
	--------subject.txt - Data for page 1 of the profile form.
	--------object.txt - Data for page 2 of the profile form.
	--------interests-X.txt - Data for the remaining pages of the
			profile form.
	--------free.txt - A list of domains that provide free email
			accounts.  These domains will not be allowed in an email
			address.  Empty this file if you don't want to restrict
			addresses.
	--------searchform.txt - This is a template used by search.cgi to
			determine which items are scanned during a quick search.
			The item codes you see in this form correspond to the items
			and selections stored in subject.txt and object.txt.

	public_html subdirectory (may be html, or htdocs, etc.)
	----ematch30 subdirectory
	--------pics subdirectory - holds user's uploaded pics.
	--------tmp subdirectory - temporary storage of uploaded files.
	--------html subdirectory - web pages and related files.
	------------style.css - Format information for your pages.
	------------disclaimer.htm - The disclaimer stuff.
	------------banner.gif - Be sure to replace this with your banner.
	------------renew.html - sample form to manually renew a user's
				subscription.

CONFIGURATION

	1.) Set path to Perl in .cgi files.

		    The first line of each .cgi script must contain the path to
			Perl.  The default usually works on Unix.  Your
			provider should	have this information for you.  Paths to
			Perl are found in:

			index.cgi
			search.cgi
			upload.cgi
			pchecker.cgi
			restore.cgi
			renew.cgi

	2.) Set variables in setup.cgi

        $datapath - The complete path to your ematch_data subdirectory.

		$htmlpath - The complete path to your html subdirectory.

		$main_url - The URL of your public_html/ematch30 subdirectory

        $exiturl - The URL you want users sent to when they click
			{Exit e_Match].

		$log - The name of your log file.  If it is in you web space, you should rename this file.

		$xlog - The name of your expired log file.

		$logpath and $form_path - The defaults here should be okay.

		$nt - Set to yes if you server is an NT.

		$smtp - Your SMTP email server. (The one you do e-mail through).
				This is only needed if your on an NT server.

        $admin -  This should probably be your e-mail address.  It is
				used in the "From:" field for the sendmail routine. If
				$nt = yes, make sure this address has the same domain
				name as $smtp, that it's valid address on that server.

        $lockon - After initial testing, try setting this to
			'yes'.  If it works, you're less apt to encounter file
			errors.  However, the script will probably still work
			satisfactorily if you need to set this to 'no'.

OPTIONS SECTION

		$free - Set to yes if you want e_MAtch to be free for users.
				Set to no if you want it to be subscription-based.

		$trial - Used in subscription-based	operation. Number of days of
				free trial period.

		$timeout - Used in Free operation. Number of days before a users
					data is deleted due to inactivity.

		$notify - If set to yes, will send reminder email message to
					user 3 days	before trial expires.

		$reminder - Text of the reminder email message.

		$header - HTML that will be added to each page.  It includes
				all HTML starting just after the </title> tag, through
				the body tag, and including the banner tag.  This can be
				edited as raw HTML.  Just remember to precede any quote
				with a backslash.

		$footer  - HTML that will be added to the bottom of each page.

		$max_size - Maximum size in bytes of uploaded graphics.

		$max_matches - Maximum number of matches appearing on a user's
					match list.

        $force_domain - If 'yes', your users will be required to
			register using their ISP's POP3 e-mail address.  (There
			is probably only one of these that they can use, so if
			they're banned, they can't re-register.  See Banning.)
			If set to 'no',	they can use any valid address.

		%ranks - You can change the language of these form items here.

		%color - These are the color values used in the profiles.

		$subject, $object, $interests - the actual manes of these files.
										You can leave them as-is.

	3.)  In your cgi directory, create a subdirectory called ematch30,
		and	upload index.cgi, upload.cgi, setup.cgi and email-lib.pl to
		this subdirectory. BE SURE TO UPLOAD ALL OF THESE FILES AS ASCII
		FILES. Chmod all .cgi files 755 (rwxr-xr-x).  NOTE: The chmod
		(permissions) commands only pertain to setup on a
		Unix server.  NT users can ignore them.

	4.) Create an 'ematch_data' directory (chmod 777) at the location
		you specified in $datapath.  Load the form and log
		subdirectories and their contents here. Chmod all files in the
		log directory to 666.

	5.)  Create a subdirectory called "ematch30" at the location
		specified by $htmlpath.  Upload the html, pic, and tmp
		subdirectories here. Chmod the pic and tmp subdirectories 777.

	6.)  Upload your banner.gif to replace the default in the html
		subdirectory.

	7.)  That's it!  e_Match is accessed via the URL to index.cgi.


3. Free vs. Subscription-based operation.

	If you choose to make e_Match to be a free resource, users will not be required to subscribe.  They must register, but after that they can use the script for as long as they please.  They can be banned (see below, but their data will only be removed after $timeout days of inactivity.

	If you choose subscription-based, users will be able to register for free, will have $trial days of free use.  Three days before their trial period ends they will receive an email reminder.  Then they must subscribe, using whatever method you choose.  A script called renew.cgi is included with e_Match 3.0 which allows you to manually renew a user's subscription.  If you know some Perl, you can adapt this script to suit your payment setup, and automate the renewal process.


4. Data file Formats

NOTE: THE ELEMENTS IN EACH LINE ARE SEPARATED BY TABS.

SUBJECT.TXT AND OBJECT.TXT - These files contain the items and
	selections for the first two sections for the profile form.  NOTE
	THAT EACH ITEM IN SUBJECT.TXT HAS A CORRESPONDING ITEM IN
	OBJECT.TXT, (except a99, which I'll get to in a minute).  The
	format is:

	        lnn		Category	r/s	selection list

	    l - 'a' for subject items, and 'b' for object items.
	    nn - a two digit number that determines the order that
		the items will appear in the form.  These numbers are also used
		by the match routine when it compares files.  Note that the item
		numbers in subject.txt are the same as the corresponding item in
		object.txt. (For example, Marital status is item number 03 in
		both lists.)  If you rework the order of the items, or add/remove
		items, be sure to make the same changes in BOTH FILES.  This is
		necessary so that the match computation routine is comparing the
		appropriate characteristics.

        Category - This is how the text of the item will appear
		in the form.

	    r/s - This sets the type of form element. select=select box,
				anything else=radio buttons

        selection list - the text of the options, separated by tabs.

	NOTE: a99 is a special entry.  You can remove it or edit the
	options, but you can't renumber it.  It needs to stay a99.

INTERESTS.TXT - This file contains all the interest items.

	The	format is:

        innn	subcategory/item

        i - used by the match routine to identify interest items.

        nnn - three digit number determining the order of the
		items.  Any line beginning with in00 denotes a subcategory
		(Ex. Favorite Movies) and will appear as a heading in the form.

        subcategory/item - the text as it will appear in the form.

	You can edit this one as you please.  However, once you
	have some user profiles on file, then edit this as little as
	possible.  Adding or removing items will be okay, but don't
	renumber them.  The match routine uses the numbers to compute the
	matches, and renumbering the items will mess up the match system.

USERS.LOG - User registration data.

    The data is in the format:

    	nickname	password	e-mail address	time	status

	The 'time field records the time of the last log on for this user,
	and uses Perl's internal time value (which is in seconds)

	The last field, status, hold the expire time of a subscription user.
	(also in seconds)

    This file is currently not encrypted, so you should
	rename it or hide it.

STYLE.CSS - Format information for all script generated pages.

    This is a Cascading Style Sheet(CSS) file, and contains
	data determining the appearance of various pages in the script.
	You can edit this file according to the CSS standard.  Do a web
	search on any directory for "CSS" and you'll find lots of
	information.

DISCLAIMER.HTM - The disclaimer stuff.

    This is a simple HTML page which includes the disclaimer.
	You can edit it however you please.


5. Banning

	You can ban a Nickname from e_Match by accessing the users.log
	via FTP, replace their status (last) field with this string:

	banned

	They will not be able to
	log on, and they won't be able to re-register under the same
	nickname, or by using the same e-mail address.


6. Customizing the Language

	If you need to change the language elements of e_Match, here are
	some tips.

	a.) All the profile stuff is in the various text files (subject.txt,
	object.txt, interests.txt.)  These can be edited with a simple text
	editor, following the formats describe above.  Be sure to put tabs
	between the	items.

	b.) The disclaimer is a html file, and can be edited freely.

	c.) The two help files are in helphtml.cgi, but are mostly HTML and
		can be edited with a text editor.

	d.) Some form elements are defined in the setup.cgi, and can be edited
	with a simple text editor.

	e.) Numerous small pieces of text are interspersed through the code of
	index.cgi.  These can be found by searching for the desired words using
	a simple text editor.  Be careful to only modify the text.  don't change
	the code surrounding it.  If you are having problems with this, let me
	know, and I'll help.


7. Customizing the layout

	Since e_Match generates almost all of its pages on the fly, changing the
	look of the various pages is not as simple as editing HTML.  My
	suggestion is that you stick to the following:

		1.) You can do a lot just by editing these variables in setup.cgi:

			$header - contains the link to the style sheet.  Delete this
					tag if you don't know about, or want to mess with
					style sheets.  Next is the body tag, which controls
					the default background color, and text
					colors, unless over ridden by the style sheet (see
					below). Last is the banner tag. If you have a
					banner, edit this code (or just call your banner
					banner.gif and replace mine with yours.

			$footer - determines the content of the bottom of most
					pages.

		2.) You can embed e_Match in frames if you want.  Just call
			index.cgi from the frameset.

		3.) e_Match includes a style sheet (styles.css) which includes
			format information for specific areas of e_Match pages.
			Here you'll find background colors and fonts types for
			various areas of the page's generated by the script.

		4.) If you know some HTML and have some experience editing Perl
			scripts, you can edit both help pages in helphtml.cgi.

		5.) The disclaimer page is just HTML, and can be edited as you please.

	If you decide to go further than this, you're on your own.

8.) Restoring your users log.

	If your server fails and your users log is corrupted or missing,
	you can run restore.cgi to restore it.  It requires no configuration.


Good luck, and thanks!

Mb