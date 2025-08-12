eFiction, Version 1.1

This script is intended to be used for the archiving of stories or fanfiction.
All questions, problems, or questions should be taken to the forums at
http://orodruin.sourceforge.net The script is released as is, with no guarantees -- 
it may very well have some security flaws, and you use it at your own risk. Be 
sure to back up your site regularly!


Upgrading from v.1.0:

Replace the files on your server with the 1.1. files EXCEPT for config.php.
Go run install.php, and follow the instructions on the screen.


Brand New Install:

If you know basic web stuff like CHMODing, look below at step #1. If you have
no clue what you're doing, just go to http://www.yoursite.com/install.php
after uploading all the files to your server.

1) CHMOD the stories folder to 777 if you intend to write the story texts
to the server or allow image uploads.

2) CHMOD config.php to 666

3) Modify data/dbconfig.php with the appropriate MySQL database info, and
put outside the web directory.

4) Run install.php to install the tables and set up the admin login.

5) Login with the admin login and password set in step #4, and set your settings.


To create new skins:

Read creating_skins.htm located in the docs folder. You can have as many skins
as you desire. All the layout of the site is defined by the skin(s), except for
the various forms -- these are all hardcoded within the PHP script.


Other info:

1) As written, the script is intended to be used in the following way:

- Categories: you can have only one category, or as many as you want, including sub-categories.
Categories were written with the intention that they would indicate different fandoms, or categories
of story.
- Characters: Intended to mean the characters that fall within that fandom or category
- Genres: Genres include such things as Action, Romance, Poetry, Filk, Angst, or whatever
makes the most sense within your fandom or types of stories.
- Ratings: Basic ratings could be G, PG, PG-13, and so on, but you could also do
ratings as Child, Youth, Adult and so on, or whatever makes the most sense to you.
- Warnings: Warnings are intended to be used to help indicate certain things about
stories that you may want to warn readers of, such as rape, male/male sex, and so on.
The Warnings field doesn't have to be used for warnings -- it could be used for
Languages, or whatever you wanted.

Check out the Admin area for more information on all of these settings and more.

2) You can modify the text that is displayed in certain areas by modifying the languser.php and
langadmin.php files -- just change the text within the quotes for each line to whatever
you want it to say.

3) This script is donationware. If you like it, use it, and want to give me money for
it, I won't stop you ;) You may do so through Paypal: https://www.paypal.com/xclick/business=rivkadr%40hotmail.com&item_name=eFiction%20Donation
Seriously, though, all donations are greatly appreciated, and make it so that I can
continue to make scripts like this.

Rebecca Smallwood (Rivka)
http://orodruin.sourceforge.net
Released under the GPL.


Credits:

Fanfiction Script: Rebecca Smallwood (Rivka)
TemplatePower Templating System: R.P.J. Velzeboer, http://templatepower.codocad.com/

A Big Thank You to my original Beta-testers: Theresa Sanchez, Khuffie, Mona Carol-Kaufman, Michele Bumbarger, Stephanie Smith,
eFanfiction, Amy Cheng, arakune, Peganino, Ceit, brihana25, Annabelle Crane

eFiction skin: Amie