Deep Though Productions - DTP Topsites Installation Instructions.

For Information on the other components of this topsite,
please read the other.txt file.

Any thing that will need to be modified to make this topsites script
work will be in the file config.pl included in this archive.

Configuring this is fairly simple as we documented every varible.

The other choice you have is weather you want a STATIC banner
or one that shows the members Position.

The position showing tend to be more unique but can be a pain as you
have to create every individual banner by hand, including the Rank
number.

If you chose to do this then you should name the banners in the
following manner.

First you need the default banner. This should be named default.jpg, or default.gif.
Next you need to create the banners which show the members position.

The banner for Position 1 should be named 1.jpg or 1.gif
The banner for Position 2 should be named 2.jpg or 2.gif
The banner for Position 3 should be named 3.jpg or 3.gif
The banner for Position 4 should be named 4.jpg or 4.gif
and so on, until you reach the desired number.

DO NOT MIX FILE EXTENTIONS!! Choose eather .gif or .jpg.

These will all be placed in a directory seperate from the rest of the scripts
files as it is easyer to check up on them.


You can have a Top 100, with only 20 Position showing banners. The other
positions will be shown the default image.

Static mean there is just one banner and is quicker to setup, and can
be changed easily to the other type if you choose to do so at a later
date.


Once you have chosen which type you want, you need to edit config.pl
and set it the way you want it.

Once you have done this, you should start uploading.
Please make sure that you set the directorys and paths correctly
and that you are sure that is where you want things to go.


### Uploading

UPLOAD ALL FILES IN ASCII MODE! Using a binary mode will cause
this script to not function properly. MAKE SURE YOUR FTP
PROGRAM IS SET TO UPLOAD THESE FILES AS ASCII FILES!

You should also create a directory in the topsites cgi directory called log (lowercase)
this is used to log the IP from votes, and an added feature to help save resources.

You should upload all files which you got with this script exluding
*.txt files to the topsites CGI directory (as set in config.pl)


### CHMODing files.

Please refer to chmod.txt for more information on setting
the permissions for this script.


### layout
You can alter the layout by editing template.html and template2.html
These files are also documented, view the HTML source to read!

Good luck with your new topsites.