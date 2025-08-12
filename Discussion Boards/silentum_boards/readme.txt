++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
+                                                                              +
+                      Silentum Boards v.1.4.3 Readme File                     +
+                                                                              +
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
0. Table of Contents
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
0. Table of Contents
1. Installation
2. Errors
3. Important Notes
4. Contact Information
5. Copyright Information

++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
1. Installation
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
a. After you've unzipped the files to a location you desire, visit the
   index.php page.
b. If there are no errors, you've installed everything successfully. If there
   ARE errors, skip to the "Errors" section. Otherwise, continue to step d.
c. If you haven't already, rename cronjob.php to something random. Ex:
   fnlah98f2h.php.
d. Login under the user name Host and the password ACCESS (case sensitive)
e. You may change your user name and/or password after you've logged in.
f. The "Administrator CP" is located under your "User CP".

++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
2. Errors
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
"When I uploaded all the files and went to the index.php page, all I saw was
this weird code! What happened?"

Most likely, your host doesn't support PHP. You'll need to find a new host.

"I uploaded the files and the boards appear, but there are a few errors in the
middle or the bottom of the pages. What's going on?"

Your host probably has an outdated version of PHP. Check to find out what
version they're running, and if you can, suggest they update.

"There are no PHP errors, but I can't login. Why not?"

The password is case sensitive, make sure you're entering it in ALL CAPS. If it
still doesn't work, try copying and pasting a different password from the 
password_reset_list.txt file under the members folder.

++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
3. Important Notes
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
a. If your boards ever get more than 999 members, you'll need to open
   administrator_actions.php and change the number 999 on line 61 to a higher
   number, such as 9999. This will ensure all members above User ID 999 will
   receive aura when you distribute it.
b. If you change the status names and Aura requirements, you'll need to manually
   update the FAQs inside navigation.php. It's also a good idea to go over
   everything else in the FAQs and Terms of Service after you get your board
   configured to make sure they match with your board's configuration and
   settings.
c. With Aura requirements, you'll also need to edit the post_poll.php,
   post_reply.php, and post_topic.php files accordingly. Near the beginning of
   each file is where you'll find the Aura requirements and the error messages
   given if a certain amount of Aura isn't met. Pretty basic, really.
d. To change the shortcut icon (the dark blue hs.net icon displayed next to the
   URI in the address bar), make a 16x16 pixel .png file and save it over the
   shortcut_icon.png image under the images folder.
e. When you distribute aura manually, you'll see "password=H72kVmal091jGu43" in
   the URL. You can change this by opening up administrator_actions.php and
   editing the password on line 55. You'll also need to open up
   user_control_panel.php and edit the password on line 144. This is simply an
   extra security measure. It's not a requirement to change the password, since
   you need a user ID of 1 to access the page anyway.
f. If you're going to use a cron job (an automated task to run a script at a
   certain time) to update aura, you MUST rename cronjob.php. Rename it to
   something random that ONLY YOU will know. Otherwise, anyone will be able to
   go to the page and distribute aura. If you AREN'T going to use a cron job,
   don't upload cronjob.php.
g. To add/delete the HTML tags that are usable, open function_list.php. Search
   for "function basic_html". It's pretty self-explanatory from there.

++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
4. Contact Information
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
E-mail: hypersilence@hypersilence.net
AOL Instant Messenger: HyperSilence
Support Boards: http://boards.hypersilence.net/index.php
Web Site: http://www.hypersilence.net

Also, if you want to, please rate Silentum Boards at
http://www.hotscripts.com/rate/49588.html
Feedback is always welcome.

++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
5. Copyright Information
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Silentum Boards are open source, meaning you may edit them as you wish, as long
as you don't remove any of the copyright notices inside the files. I'd also
appreciate it if you'd leave the copyright notice on board_bottom.php so people
know which board system you're running. Other than that, feel free to edit
whatever you like.

Thanks for downloading and enjoy!