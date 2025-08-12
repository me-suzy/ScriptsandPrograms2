Free Downlaod Site 
------------------
Copyright (C) 2005 Adullam Limited., All Rights Reserved.

Unless explicitly acquired and licensed from Licensor under the Technical Pursuit License ("TPL") Version 1.0 or greater, the contents of this file are subject to the Reciprocal Public License ("RPL") Version 1.1, or subsequent versions as allowed by the RPL, and You may not copy or use this file in either source code or executable form, except in compliance with the terms and conditions of the RPL.

You may obtain a copy of both the TPL and the RPL (the "Licenses") from Technical Pursuit Inc. at http://www.technicalpursuit.com.

All software distributed under the Licenses is provided strictly on an "AS IS" basis, WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESS OR IMPLIED, AND Adullam Limited. HEREBY DISCLAIMS ALL SUCH WARRANTIES, INCLUDING WITHOUT LIMITATION, ANY WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, QUIET ENJOYMENT, OR NON-INFRINGEMENT. See the Licenses for specific language governing rights and limitations under the Licenses. 

README FILE (v1.0)
------------------

Version 1.0
-----------
ABOUT: This script is the mimimum you need to run a succefull download site offering free downloads.
MONEY: The site has been designed in such a way that it is easy to add adverts to the site.  Indeed, in the standard setup has been designed to carry three (the maximum per page) goodle adverts without distroying the page for your users.  All money you make from these adverts is, of course, yours.

Requirements
------------
- PHP 4+
- FTP Access to your site (or some other upload method)

Hosting
-------
For a small yearly fee Adullam Limited can arrange for this script to be hosted on a server that supports everything you need but also offers
- Unlimmited POP3 Email Accounts
- Unlimited FTP Accounts
- Technical Support
- Generouse Bandwidth
- Plenty of Disk Space
- Use your own domain name
- Use subdomains instead of directories

HOW TO USE
----------
It is very simple to use this script you simply upload it (as is) to your website and it's ready to run.

When you add downloads to the my_downloads folder they are automatically listed for you.

How to customize
----------------
You can make aditional categories by creating new folder (directories) within the my_downloads folder and placing a copy of the my_downloads version of index.php inside the folder.  The system will automatically list the new category and any new files within it.

ADD CATEGORY SPECIFIC CONTENT: To add specific content to a (folder) category simply place your contentinto a file and give the file the php extension (for example (bonusinfo.php, extrastuff.php, crosslinks.php etc).

ADD FILE TO A SECOND CATEGORY: you could just add the file to a second folder or you can use the *.php file method to add a link (or list of links) to the file.  This method has the advantage that you can add text (Have you considered the merits of myfile.zip?) and you need only update one instance of the file (as well as taking up less room).

GETTING FREE FILES: wouldn't it be great if you could get a whole load of files to give away for free?  Well now you can - introducing the GNU GPL: some files and downlaods are released under this license (or it's brother LGPL) which grants you the right to give away these files yourself  A good source of GPL files are scourceforge.net, hotscriptscom or freshmeat.com - be sure to pick the smaller popualr files that fit with you theme/content type as larger less popular or off-topic files will simply use up your bandwidth and space for no real gain.


FAQ
---
In any case where there is a slight difference between the FAQ and the License the License shall be held to be true.

Q - Will this work with MP3 files
A - sure simply add the files and if yours click the file will probably begin streaming 
    (playing in the browser) or they can right click and save as.

Q - can I remove the copyright and link at the page footer
A - No, doing so would be in breach of the license

Q - Can I remove the copyright messages in the files then?
A - No, doing so would be in breach of the license

Q - Can I pay to remove the message?
A - At time of writting this service was not offered, however a reasonable offer might cause a change of mind.

Q - Can I give away alered versions of the script?
A - Basically yes, but there are cetain restrictions in the license that you will need to follow.

Q - Can I offer files from the lordmatt.co.uk or other sites using this script.
A - If they are GNU GPL or LGPL then yes you may (check the General Public License for more details), 
    however if they are offered under other terms then you will need to double check for yourself.

Q - Are there any extentions or options to edit?
A - Nope just drop n go.

Q - How can I get more functions?
A - Ask some one to write them, drop by the development page and ask, 
    or check http://lordmatt.co.uk/freestuff for later versions.

Q - Can I offer any extentions for download?
A - Basically yes, but there are cetain restrictions in the license that you will need to follow.

Q - Do I have to have the root folder called my_downloads?
A - No call it whatever you want, just remember to make sure you link 
    to the folder correctly or your site will not work.

Q - Do I have to offer the files this comes with?
A - no they are a bonus item and you can leave them out if you wish.

Q - I really love all those NucleusCMS plugins being on my site and want to offer more...
A - http://wakka.xiffy.nl/plugin - is the wiki listing most plugins