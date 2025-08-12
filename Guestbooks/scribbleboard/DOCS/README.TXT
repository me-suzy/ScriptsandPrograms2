                               .+"+.+"+.+"+.+"+.                               
                              (                 )                              
                               ) ScribbleBoard (                               
                              (                 )                              
                               "+.+"+.+"+.+"+.+"                               

What is this?
-------------
Thank  You  for  downloading  ScribbleBoard.  This  script  is  a  twist  to the
traditional guest book most people use.  Instead of just  writing  a  plain-text
message, people can write you a message in a variety of fonts, sizes, colors and
directions.  It offers people the freedom to express themselves how  they  want,
not how the maker of traditional guest book scripts wants them to. To summarize,
ScribbleBoard simulates a virtual piece of paper on which people can scribble  a
message.  It  is open to the public and everyone can write a message or greeting
on it.

For your convenience,  and to track abuse, a "messages.log" file includes the IP
address of every person who added a message. It also shows the message contents,
along with its coordinates. To prevent further abuse there is a word filter that
you  can use to stop people from adding insults or spam.  A default word list is
included, which filters out around 80% of the spam. You can also limit people to
only posting one message in a specified interval, which discourages flooding.

I have  also seen various communities  using it as a  collaboration software and
apparently some teachers use it for their classes. *Wow!* :)

Change Log
----------
09.07.2005 - Version 1.0:
Initial Release.

09.07.2005 - Version 1.1:
Fixed  a few things to make this script work with the free web host "free.fr"  -
Thanks to \Kasumi\ for pointing out it did not work there.

09.07.2005 - Version 1.2:
Commented out flock stuff since that is apparently forbidden at free.fr  ~_~  --
Uncomment  it  please  if  you  have  a  real web host since it can lead to data
corruption.

11.07.2005 - Version 1.3:
Final version. It is now configurable so check out config.php :D

23.07.2005 - Version 1.3a:
Added captcha and time-outs to prevent flooding.

04.08.2005 - Version 1.4:
Extended checks in "init.php"  to see if FreeType2 is available and added a  fix
for "postform.php" to fix corruption of "messages.txt" and "messages.log."

05.08.2005 - Version 1.4a:
Left in debug stuff by accident.

03.12.2005 - Version 1.49:
Major  upgrade!  Now  supporting  word filtering (with notification),  automatic
detection of  available fonts,  and notification of new messages.  Also supports
Japanese/Korean/Chinese characters correctly with the right fonts installed! See
the FAQ for more information on setting this up.

Installation Guide
------------------
1. Extract the files to a new folder. You have probably already done this.

2. Upload everything to the desired directory on your web server.

3. Edit "config.php" and make sure that you have configured everything properly.
The file includes many comments to help you understand it.

4. Optional: Create 2 empty files: "messages.txt" and "messages.log", and upload
them to the desired directory on your web  server.  (If  you  do  not  do  this,
ScribbleBoard will try to create the files if it can.  If it cannot you will get
errors,  however if it can,  you might not be able to delete them later  because
they  will  be  owned  by a user called "nobody"  -  This is especially true for
Unix(R) operating systems such as Linux(R).)

5.  If your server runs a Unix(R)  operating system such as  Linux(R),  set  the
permissions for "messages.txt" and "messages.log" to 777 (Owner, Group and World
Read/Write/Execute permissions).

6. Point your browser to the directory you installed ScribbleBoard to and enjoy.

7. Optional: If you want to enable  support for eastern languages,  read through
the question in the FAQ and follow the instructions.
   
Frequently Asked Questions
--------------------------
Q: The page is full and I cannot read anything anymore; what do I do?
A:  Delete "messages.txt." You could also archive old text files and the picture
ScribbleBoard made and put them onto a separate site.
   
Q: I just get a broken image, and nothing works; what's the problem?
A:  Well  you  obviously  need  PHP  for this to run,  but you also need the GD2
extension with FreeType2 compiled in/enabled.  Most major web hosts have  these.
Ask your web host if you are not sure.

Q:  How can I check if I have working PHP and GD support in case I cannot ask my
webhost?
A:  Create an empty file called "test.php"  and put "<?phpinfo();?>"  inside it.
Upload it to your web server and access it.  If you see nothing, then you do not
have PHP installed.  If you cannot find anything about GD mentioned on the page,
you do not have GD installed. Similarly, if you do not find any mentioning about
FreeType, you do not have it installed either.

Q:  I checked everything,  I seem to have GD and FreeType2,  but it *still* does
not work!
A:  Right  click  the  broken  image,  select "Properties",  copy the URL in the
properties window, and paste it into the address bar and press enter. You should
now  see the image file directly.  It will probably be a PHP error message and a
bunch of garbage characters.  If you cannot conclude the reason  for  the  error
yourself,  send  me an E-Mail with the exact error message,  and the filename of
the script that is causing it. You can find my E-Mail address at the end of this
file.

Q: What happened to the fonts from previous distributions?  Where can I download
more fonts?
A: I got in trouble with the makers of these fonts because I did not credit them
in the way they wanted me to. As an alternative I added the Bitstream Vera fonts,
which are free of charge. (See FONT_COPYRIGHT.txt for more information.) You can
visit http://www.1001freefonts.com/ if you want more fonts. The site offers many
fonts and all of them are free.

Q: Are eastern languages supported in ScribbleBoard?
A: Yes! As of version 1.49,  eastern language characters and ideograms are fully
supported in ScribbleBoard! To enable eastern language support in ScribbleBoard,
open  your  Control  Panel and  select  the font  that contains  your  langauges
characters or idiograms.  Copy this font  to your Desktop and  then upload it to
your ScribbleBoard installations "fonts/" folder. When posting a message, people
need to select the right font in order for the  characters or idiograms to show.
Optionally,  you can  delete  all other fonts since  eastern fonts  usually also
support the western character sets.

Q: I want to modify this script, can I do that?
A: This script is licensed under the creative commons license. See the following
URL for information on what you can and cannot do:

This work is licensed under the  Creative  Commons  Attribution-  NonCommercial-
ShareAlike    License.   To    view    a    copy   of   this   license,    visit
http://creativecommons.org/licenses/by-nc-sa/2.5/  or send a letter to  Creative
Commons, 559 Nathan Abbott Way, Stanford, California 94305, USA.

Basically, do not make money off it and give me credit where it is due.

Contact Information
-------------------
Website: http://www.zabadab.org
Article: http://www.zabadab.org/projects.php?subaction=showfull&id=1130181064&ucat=3
E-Mail: zabadab@zabadab.org