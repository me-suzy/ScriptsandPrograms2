                       Somery 0.4.4

		  readme date: 07-11-2005

What is Somery?

  Welcome to Somery, also known as the Somery weblogging 
  system. This is just a simple readme.txt meant to help you 
  underway with installing the script.

  NOTE: If you get errors concerning the extract() functions,
      	you probably have an outdated PHP version. Ask your host
      	to update his PHP version.

How to install Somery

  - Open up config.php and edit the data to fit your host's 
    info. Make sure the database exists before running the 
    install script.
  - Upload all the files in the /upload/ folder, including 
    all the subdirs to your server into whatever directory 
    you want. You do not need to upload the files from the
    /tools/ or /docs/ folders, unless you're upgrading from
    a pre-106 version of Somery - then you do need the 
    106-upgrade.php file.
  - CHMOD feed.xml to 666, so it is writeable when you start 
    posting.
  - Open up the location where you uploaded Somery in your 
    browser and run install.php.
  - After install.php is finished, remove it.
  - Log in with your chosen account name and password.
  - Start weblogging!

Upgrading your Somery

  Normally this is just a question of opening the Changelog 
  file (CHANGELOG.txt) and looking at what versions worth of 
  updates have been made. Every update shows the files 
  affected, making it easy to update your script. If you 
  currently use a pre-106 version of somery, you will be 
  required to run 106-update.php before going on, as 106 
  brought a couple of changes to the database.

  Other than that it's a simple case of uploading and done.

BB code

  Instead of allowing HTML, somery uses BB codes to allow
  for easy markup of your posts. The standard BB codes are:

  [b]bold[/b]
  [i]italic[/i]
  [u]underline[/u]
  [center]center this[/center]
  [img]
  [url]url[/url]
  [url=url]url here[/url]
  [email]email@email.com[/email]
  [quote]quote this[/quote]

  Commenters can use all of these, except for the [img] tag.

Skinning/Templating/Designing the blog

  See index.php in the root of the somery files. This is the 
  basic template which will be used for the look of your blog. 
  Just tinker around with it all. I'll list some important 
  things here:

  CODES USED IN THE TEMPLATE:
  	<?php archive("x","y","z"); ?>
		Used to display links to posts. The number of 
		posts displayed can be set in the settings of 
		the somery admin system. Mind that as of yet, 
		the archive simply starts with the latest post 
		and works its way down to either the last post 
		(archive set to 0) or the post you specified 
		to be last (ie, the 30th).

	EXAMPLE: <?php archive("<h3>- %</h3>","date","d/m/Y"); ?>
		X in the example, is:
			"<h3>- %</h3>", which is the additional 
			layout to be sent to the archive 
			function. The % will be replaced with the
			actual 
			link.
		Y in the example, is:
			"date", "title" or "td", which displays
			the archive posts as just the date they
			were posted on, their titles, and lastly
			a combination of both.
		Z in the example, is:
			"d/m/Y". This is the layout of the date 
			if you have chosen the links to be 
			displayed as the date or td. Consult the 
			PHP manual for specific information.
	<?php getauthor("x"); ?> 
		Used to display any and all information simply 
		as it is stored in the profile.
		
		X can be the following:
			nickname, firstname, lastname, gender, 
			dob, country, city, email, url, icq, msn, 
			aim, yim, avatar, signature
	<?php permalink(); ?>
		Used to display a link to the complete article, 
		plus comments. Useful to send a specific article 
		to somebody.
	<?php getadate(); ?>
		Displays the article's posted date 
		(output: dd/mm/yyyy)
	<?php getatime(); ?>
		Displays the article's posted time 
		(output: hh:mm)
	<?php body(); ?>
		Used to display the body of an article. This goes 
		for both the body and more sections. You don't 
		need to call anything for the extra content 
		anywhere. Just call this and the whole content 
		will be rendered exactly how you entered it in 
		the admin section (plus any settings that apply).
	<?php commentlink("X","Y","Z"); ?>
		Used to display a link to the comments. When 
		comments are off, this link wimply won't appear. 
		Is the same as the permalink one, but is 
		specifically for the comments.

		X: the link when there are no comments 
		   (default: no comments)
		Y: when there is 1 comment 
		   (default: 1 comment)
		Z: when there are multiple comments 
		   (default: % comments)

		% is a wildcard, which is replaced by the actual 
		number of comments, so doing "% splurts" would 
		display 45 splurts should there be 45 comments.
	<?php getarticle("X"); ?>
		Similar to the getauthor function, but this 
		applies to the article.

		X can be:
			username, title, body, more, category
	<?php category(); ?>
		Outputs the category the current article is in. 
		Just use this as this:

		<a href="index.php?cat=<?php category(); ?>">link</a>
	<?php getcomment("X"); ?>
		Similar to the getarticle and getauthor functions, 
		but this one does it all.

		X can be:
			date, time, author, email, url
	<?php comment(); ?>
		Outputs the comment's content. Similar to the 
		body(); function.
	<?php prevnext("X","Y","Z"); ?>
		Outputs the previous/next links. These are based 
		on the Posts Per Page setting, so the offset will 
		be (with a ppp setting of 5) 1, 6, 11, 16, 21 and 
		so on.

		X: the text for the next link 
		   (default: "newer posts")
		Y: link divider 
	  	   (default: " - ")
		Z: the text for the previous link 
		   (default: "older posts")

Skinning the Admin Section

  Check the default skin in skins/default and use those files as
  a basis for your own template.

Credits

  Somery core code by: Robin de Graaf (voh - voh@hostvoh.net)
  Many thanks to: AnTi, Plix and Knyght for testing and trying, 
  and helping me when I couldn't figure something out as soon 
  as I wanted to.

  Original archive function hack by Jase.
  Original RSS feed functions hack by Mark Kooijman.

Copyright & License

  Somery, a weblogging script by Robin de Graaf, copyright 2001-2005
  Somery is distributed under the Artistic License (see LICENSE.txt)

  The name Somery comes from a Descendents album, which contains
  some of their best and rarest songs. (C)1991 SST Records.

Contact information

  Robin de Graaf - voh@hostvoh.net
  Somery website - http://somery.danwa.net