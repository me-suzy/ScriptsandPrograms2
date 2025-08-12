------------------------------
SSphp - Simple Site PHP
------------------------------

                                                                                                    
          ..;;;;LLjj::        ..;;;;LLff..                    ..,,jj                                
        ..iittjj####EEjj..  ..ttiiff####EEtt..                ..fftt::                              
        ,,EELL  ,,ffGG;;::..::KKff  ,,ffGG,,..                ..fftt..                ..            
        iiWW##KKjj..  ..    ;;####EEtt..  ..    ::jjii;;LLGG....ffDDiijjEEtt  ..;;ff;;ttEE;;        
        ..GG######KKLL..      DD######WWff..    iiDD,,iiDD##tt..ff;;;;ff##DD..::KKiiiiff##LL::      
            ;;GGWW####WW,,      iiDD######KK::  ,,DD::  ..DDtt::ff;;    jjDD::..DDii..  jjDD::      
        ......  ttKK####tt    ..  ..jjEE####;;..::DD::  ..jjii::ff;;    ::ff....GGii..  ::LL::      
        ::GGKKGG::  jjKK,,..::EEKKLL..  ffKK::..::DD::  ..ii::..ff;;    ::ii::..LLii..  ::ii::      
        ttDDWW##WWtttttt::  iiDDWW##KKttttii::  ::DD::  ::ii::..ffii    tt;;  ..LLii....tt,,        
            ,,LLtt,,,,          ;;LLii,,::      ::DD,,iiff::  ::ff;;..,,jj..  ..LLLL::ff;;          
                ....                ....        ::DD,,;;..      ....  ii::    ..GGtt;;,,            
                                                ;;LL::                        ::DD;;..              
                                                ::::..                        ..::..                
                                                

[Product Info]
version 1.0.5
Release date: 10/18/05
author: Anthony Pircio
website: http://www.lan4all.net/page.php?id=9

[overview]
SSphp is a content management system designed to make managing a professional looking website easy 
and affordable... well free at least.  

[disclaimer]
SSphp offers no technical support or warranties of any kinds.  Problems or general questions can be forwarded to pircio@yahoo.com.  Use this product at your own risk.

[features]
- easy to install
- comes with 2 premade themes, and making your own is easy
- lets you add/remove users, boxes, and pages
- admin protect specific sections or pages, so only you or your friends can see them

[requirements]
Before you can start using SSphp you will need the following...
1. Your own webserver
2. Access to a MySQL server (any database)
3. an FTP client (you should have this already)

[installation]
Installation is really easy, and I hope you don't mess it up.
1. Upload the contents of this archive to any web folder, leaving the directory structure intact.
2. Open "config.php" (with your favorite text editor) and edit the values there for your MySQL server, the values are explained there.  DOUBLE CHECK THESE!
3. In your browser open "install.php" and fill the information out.  After you enter that information 
it will list a bunch of things and say "done"... there should be 8 of them.
4. DELETE INSTALL.PHP! Failure to do so will leave SSphp open for anyone else that wants to create an administrative login account to your site. 
5. That's it!  You will be able to log into your page at "yoursite.com/admin.php" or what ever directory you put these files in.  

[Themes]
You can use themes with this program.  There is no official theme program builder but you can get an idea of what needs to be done if you go to the themes folder, and look at style.css.  There aren't any comments in there (yet) but it's faily straightforward.  Basically what is required is a folder with a unique name inside of the "themes" directory.  That folder will contain all theme-spefic files and a styles.css file.  

[Other notes/how to use]
Once you install it successfully and log in you will see the site options there.  This is where you control your site.  Here it will give you the option to add "pages" and "boxes".  Boxes run along the left and right side of the site, as navigation objects, and pages are differnet sections that can be put in said boxes.  By default, you get a "home" page.  This cannot be deleted (unless you delete it out of the MySQL server, which is STRONGLY not recommended).  The home page is your default page.  If you delete a box that a page was in, the page still exists, but must be reassigned to a new box.  Remember, this is SIMPLE SITE php, this is not an advanced content management system.  I will not be adding things like RSS feeds, forums, modules, etc.  I will be adding smaller other things, but the idea of this utility is to easily create and use a website.  

Lastly... the source code is messy, but secure, and it works.  Please don't go messing with it right now.  There will be many future updates to make this code more legible and easier to mess with.  But for now I'd rather you didn't.