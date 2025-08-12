
	IMG-counter v2.0
    ========================
	   
	by Bert Melis
	www.offsite.be


Introduction
============
This is a graphic counter which is put in a page simply by using the
IMG-tag in html. You can keep the htm or html extension of your page.
Many other counters use an include-statement. This one doesn't so it's
much more easy.
this script also uses cookies to avoid counting a visitor twice when
he just refreshes the page.
There's no database required and the script is REGISTER_GLOBALS-compatible!
Due to the GD-library of PHP that doesn't support gif-files anymore, this
script is designed to work with png-files. These images can be produced
using a regular gaphical tool.
A set of digits is distributed along. You can easily change these images.


Requirements
============
PHP-enabled webserver (tested on PHP 4.3.4)
GD-library, version 2.0 (Included with PHP)


Installation
============

1) unzip the script somewhere in your webspace

2) make sure that you've got the following files:
	-counter.php
	-count.txt
	-digits/0.png ... 9.png
	-this readme
	-GNU-licence
The filenames can be altered, but keep in mind that the corresponding
variables in the script match.

3) edit the variables in the script
	-digit_dir:  the folder where the digits are stored. This can be a
		     full or relative path.
	-file:       the filename of the text-file that contains the number
		     of visitors. This can be a full or relative path.
	-min_width:  the minimum number of digits shown.
	-lifetime:   the number of days in which a visitor won't be counted
		     a second time.
	-domain:     your domain, www.domain.tld or subdomain.domain.tld
	-use_mail:   set to 1 if you want to get a mail if a certain number
		     of visitors has been reached.
	-trigger:    every time this number of new visitors has reached, a
		     mail will be sent.
	-your_email: your e-mailaddress.

4) chmod 'count.txt' to 666 using your ftp-program. (Make count.txt writable
   and readable)

5) You now can check whether everything works fine. Just open your browser
and point it to counter.php You should see the image. If you refresh, the
number should not change. (if you've got cookies enabled!)

6) to put the counter on your page, simple add an image to your html-document
the html-tag for this is:
<img src="path/counter.php" alt="counter" border="0" /> Make sure there are
NO dimensions given! If the number of visitors is very high, the image becomes
greater.


Support
=======
There should be no problems, but you may mail me if you've got a question.
I should answer within 2 days! But please, use the support-center on the
site.


Changelog
=========
v1.0 initial release
v2.0 added cookie support
     added mail-function
     REGISTER_GLOBALS-compatible (for using with PHP 4.3.4)


To do
=====
add support for multiple pages
add SQL? --> should be two scripts: one for use with sql, one for use with
             text-files
Do you have any ideas? Let me know!


License (see LICENSE)
=====================
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.



You don't have to give me credit on your page but I would like
it when you send me a mail.

