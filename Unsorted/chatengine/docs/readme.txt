
	PHP Chatengine version 1.9
	==========================
	By Michiel Papenhove (michiel@mipamedia.com)
	
	***********************************************************************************
	
	Introduction:
	
	This code is distributed as is. If it happens to lock your server or generate
	an extreme amount of traffic, I'm very sorry for you, but you cannot hold me 
	responsible. You can use the code to whatever extent you consider necessary
	and I don't want anything from you, except a world-readable note in your version 
	of the chat that kindly informs visitors that your chat was based on my code.
	
	This chat has been running for about one and a half years on my own server now
	and it has very rarely caused any problems, except for generating quite some 
	traffic if you have quite a lot of users. The code has .PHP3 file extensions 
	because of the age of the chat. I'm sorry for you, but if you have a newer version
	of PHP and your server didn't also bind .PHP3 extensions to this newer version (the
	chat will work fine on PHP4), you will have to change the extensions manually. If
	anyone happens to make a .PHP version of this chat, please let me know so I can 
	distribute it through my www.mipamedia.com website.
	
	And do feel free to let me know about custom versions of this chatengine. I'd love
	to hear from you :-)
	
	(And if you have problems with getting the damn thing to work, don't be afraid of 
	e-mailing me)
	
	
	Michiel Papenhove.
	
	michiel@mipamedia.com
	http://www.mipamedia.com
	
	p.s. Sorry about the lack of proper comments; I'm a lazy bastard
	
	***********************************************************************************


	What's this chatengine's about ?:
	
	- interactive chatting WITHOUT crappy Java stuff or moronicly refreshing HTML pages
	- public as well as private chatting (sysops see everything)
	- all you need is PHP (any version) and MySQL support
	- offers user customization: give 'em colors, images and special user levels
	- offers different kind of groups of users with different possibilities. Sysops will
	  be in full control, moderators in some control, special users can kick and normal
	  users are just there to be hurt ;-) Or maybe you can think of a different approach ?
	- software logs users' actions to the database so you can use that information
	  to create nice chat statistics
	- sysops have a special options screen. This screen shows additional information
	  about the users that are currently logged in and sysops can change certain 
	  parameters through this screen
	- stable software; chat has been running for about one and a half year and I never
	  experienced any real problems with it
	- be original: the chat has a standard design, but with a little imagination, you
	  can turn it into anything you like. It's all about frames ;-)
	- easy access: use IE 5(+) and start chatting. No annoying extra installs or whatever
	- use an overview of the currently on-line users directly in your website. Eat your
	  heart out standard Java applets !
	- use curses on your users :-) A lot have been supplied, but you can easily add
	  more curses to fit your satanic needs. BTW, these curses were meant to be funny.
	  If you don't think an imitation of a WWII Nazi soldier is funny, erase it :-)
	- use your own icons; upload them to the icons directory and voila, they will be 
	  accessible in your special options screen. Your users will love this :-)
	- use your own in-chat images. Make an entry in the chat_images table, link
	  a command to it, upload the image to the images directory and there you go. Your
	  users will love this even more :-) :-)
	- kicking and banning. Is a user being a real pain in the ass ? There's always 
	  a kick button :-) Is this user returning to do more damage ? Use the ban option
	  in the special options screen :-)
	- IP-banning. Kick and normal banning wouldn't work ? IP-banning it is then.
	- sysops can use HTML tags because of the fact that this is a HTML chat. Be sure
	  to have hours of fun annoying your users with text printed in H1 tags :-) Just 
	  don't forget the closing </H1> tag, will you ?
	- chat messages. If you want everyone's attention, there's the "chat message". This
	  message will pop up an alert messagebox on all users' screens containing a 
	  message you want them to read. Sysop only of course
	- unlimited possibilities: this is no standard out of the box solution that will not
	  allow you to modify it to your needs. It's a bunch of PHP scripts pretending to be
	  a chatengine. So do with it whatever you like :-)
	  
	***********************************************************************************
	
	What's next ?
	
	I don't know yet. I hope you'll let me know what your ideas on this chat are, and
	if you happen to have any ideas for its future, I'm more than willing to hear them.
	
	Long live Open Source :-)
	
	
	***********************************************************************************	  