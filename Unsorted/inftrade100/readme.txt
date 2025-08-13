
infTrade v1.00 Manual
=====================

Installation
------------

- Create a new database in MySQL.

- Upload all files to server. *.php must be uploaded as Ascii, and *.gif 
  as Binary.

- Chmod directory 'ittoplist' - 777
  Chmod directory 'itadmin' - 777.
  Chmod file 'it/dbsettings.php' - 777

- Run "itadmin/setup.php". This will setup MySQL and password protect the 
  admin section.

- Make sure your 'itadmin' directory is password protected.

- Edit settings in itadmin.

- Insert this code in the top of your page: <!--#include file="it/in.php"-->
  The page with this code must be named .shtml


Out Links
---------

it/out.php                                         
- Send visitor to a trade (blind link)

it/out.php?site=domain.com
- Permanent link to trade domain.com

it/out.php?url=http://www.xxx.com/gallery.html     
- Send to http://www.xxx.com/gallery.html

it/out.php?p=70&url=http://www.xxx.com/gallery.html    
- Will skim 30% traffic to trades, and send 70% of traffic to URL.

it/out.php?link=abc
- Track links.


Toplists
--------

You can create as many toplists as your like. For every .top file in the 
'ittoplist' directory a .html with the same name will be updated every hour.

$site#       - Site name, where # is the number of which site ($site1, $site2 etc.)
$in#         - Hits in
$domain#     - Site domain
$desc#       - Site description

To include a toplist on your site use this code: 

<!--#include file="ittoplist/yourtoplist.html"-->


Webmaster Signup
----------------

To let webmasters signup link to 'it/webmaster.php'

You can edit the rules text by editing 'it/rules.html'



---

For support and questions visit http://Www.inftrade.com/forum/

Latest version can be downloaded from http://www.inftrade.com/
