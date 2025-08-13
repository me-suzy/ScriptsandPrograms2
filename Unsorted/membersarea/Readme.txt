Thanks for using the clanpage members area cgi script version 2.1!
What's new?
I have added to folowing:
-Password encryption.
-Time login limit.
-User list.

Fixed the following bugs:
-possible to plan two wars on the same date and time what crashes the database file!
-NT file open problem
-Path location error
-switching from .cgi to .pl

And What's old:
-New section script.
-Clanwars section script based on clanwars section 1.3!
-Message system between the users
-3 level user authorization
-Admin options to configure the entire script
-and probably much more, just watch it! ;-)

Okay, your ready to start? I will explain how to install the shit!

1. First determine where you are goint to install the script, if your server doesn't recognizes .cgi files
you need to switch all the files from .cgi into .pl and you need to open all files saying .cgi and change the extention
in their into .pl (as explained in the files)

The files you need to edit are:
register.cgi
index.cgi
news.cgi
clanwars.cgi
search.cgi

2. Determine the path to perl on your server (only needs to be done on linux servers)
The script uses: #!/usr/local/bin/perl
and it's located on the first line of each .cgi file.
If you server uses an other path, change that line!

3. Determine if your server can use flock.
Flock is something that checks if files are open or not so other people can't open them.
in index.cgi there is a option. If you server can use flock put it to 1 (1=on)
default is 0 (0=off)

Most Linux servers use flock.

=====That's for configuring befor uploading.===

Now upload the following files to the server in this map structure:
<main Dir> index.cgi
	lay.out
	news.cgi
	protected.dat
	register.cgi
	<clanwars> cani.play
	<clanwars> clanwars.cgi
	<clanwars> footer.dat
	<clanwars> header.dat
	<clanwars> wars.dat
	<clanwars> leagues.dat
	<clanwars> scheduled.dat
	<clanwars> search.cgi
	<clanwars> statics.dat
	<links> extralinks.dat
	<msges> msges.dat
	<msges> Admin.msg
	<news> news.dat
	<news> news.set

That's the structure.
If you are on a linux server, you need to chmod some files.
all .cgi files must be chmod "755"
all the other files must be: "660"
The directories should be: "777"

for NT servers just be sure you are authorized on the server to read and write files.

if that's done. It should work
go to the location index.cgi is located (If a white screen pops up, you probably need to adjust the path to perl or the
script extensions!)
if a login screen is showed:
Register yourself as a level 1 user.
login as the admin:
username = Admin
password = Setup

and you're in as a level 3 user!
Then go to the admin options and give your just registerd real name level 3
authorization. After that log-out, and log-in with your real name, and delete the Admin account
(or keep it, but change the password)

Well, if you need any help.
I can be found at quakenet:
IRC: #dudra
Or mail me on: manie@superweb.nl
I'm going to run www.clanscripts.com with a friend of mine,
so have a look there now and then for changes or new funky things ;)
(if www.clanscripts.com doesn't work, try www.draftsman.nl/clanscripts)

Greatz,
Manie
