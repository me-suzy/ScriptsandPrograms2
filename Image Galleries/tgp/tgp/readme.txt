FUCKYMONKEY TGP SCRIPT END USER LICENSE AGREEMENT (EULA) 

BY DOWNLOADING ANY OF THE FUCKYMONKEY TGP SCRIPT FILES OR INSTALLING OR USING THE FUCKYMONKEY TGP SCRIPT SOFTWARE,
 YOU CONSENT ON BEHALF OF YOURSELF AND/OR THE ENTITY YOU REPRESENT TO BE BOUND BY, AND BECOME A PARTY TO,
 THIS AGREEMENT AS THE "LICENSEE." IF YOU DO NOT AGREE TO ALL OF THE TERMS OF THIS AGREEMENT, YOU MUST NOT
 DOWNLOAD FUCKYMONKEY TGP SCRIPT , INSTALL FUCKYMONKEY TGP SCRIPT OR USE THE FUCKYMONKEY TGP SCRIPT SOFTWARE,
 AND YOU DO NOT BECOME A LICENSEE UNDER THIS AGREEMENT. 
This version of the FuckyMonkeyTgp Script EULA supersedes any prior versions. 
Licensee of the "product" (all versions, including any beta versions) must accept this license agreement 
in full and the disclaimer. 
Licensee is strictly prohibited from redistributing the source code of the "product". 
Licensee may not attempt to reproduce or alter the source code of the "product". 
Licensee may not change the "product" in any way, shape, or form. The "product" is NOT open source software. 
Licensee may terminate this license agreement at any time provided that licensee destroy all copies of the Product.
 This license will automatically terminate if licensee fails to comply with any part of the agreement, at which time,
 licensee must destroy all copies of the "product". The Author, at any time, may terminate this license agreement. 
THIS PRODUCT IS PROVIDED BY THE AUTHOR "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO,
 THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL
 THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE
 OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE. 


Thanks to Try and Use the FuckyMonkey Tgp Script.

###################################################################################
ATENTION:
For the Windows you will need the APACHE or some .htaccess emulator for windows IIS. 
###################################################################################

To install read itens bellow:

1) Upload your script at path your choice; e.g. "tgp";
2) After upload, go to the url of installation dir; e.g. "http://www.yourdomain.com/tgp/installation/";
3) Follow the install sequence;
4) The installation is self-explanatory;
5) If the server not give you a permission to create a bd from a script, create a bd in your server control panel or use 
a existent db;
6) Fill the name of your domain and its installed;

Make sure CHMOD to the 777 the arquive dir and picture dir, other attention is to the  main page... some servers
not permit a script write to html root if not the dir seted to the 766, but it is need some attention here. If it is
your case...create the file (same as your choice in the install) and upload to your html root and after CHMOD to 
766 it is sufficient.


Usage:

CATEGORIES:
 The first thing you will do to is add some categories or delete some categories pre existent, fill free to put
how many categories you want.

TEMPLATES TAGS:
After this you will add and set the templates tags for your categories. You can choose add the name of your choice;
e.g. if you want add a tag for amateurs cat you choose amateurs and add "AMA30" or "AMATEUR30" and add the follow fiels
with start "0", howmany "30", Randon Limit "30". It's set the AMATEURS30 to show the 30 latest link add in your bd or 
(if choice the type of template, i will explain furter, desc(##AMATEUR30##) or random(-#AMATEURS30#-).
Add this templates how many you want.

HTML TEMPLATE:
Look your admin area to modify your template for main page and categories pages.(it's self-explanatory).

MAIN GALLERY TEMPLATE:
This is the principal template of your page. Is the front page links or thumbs! Look the help file in the section for
details.

REGULAR LINK LINE TEMPLATE:
This is how the link will be displayed.  Look the help file in the section for
details.

BLIND LINK LINE TEMPLATE:
This is the link used for ads...or to use with CJ or other things.  Look the help file in the section for
details.

ARQUIVES:
This is the cat or arquives pages stored in db, it will show the categories links.

TARGERED ARQUIVES ADS:
It is the ads for your categories pages, each category page have your own ADS. Write what you want in this template e.g.
banners, HTML code...

BANNING USERS
IP BANNING
Some times you need ban some users, so go to the banned domain and ips and fill the field your choice.

DOMAIN
You can also ban domain names, Enter the domainname.com of domain what you want ban and all post from this domain
will recused.

TEXT BAN
You can prevent some text to your posts, simply enter the words that you want ban and the text to the ban. Look the section
of "ban text".

CHEAT REPORT
The FuckyMonkey Tgp inlcudes a utility that user can interage of admin to say if the galleries is cheat, to do this look
the template help at "regular links line" and follow the examples.

REBUILDING THE GALLERIES

Rebuild the galleries can be handle with different ways. You can make clicking in "Rebuil Page" or set cron utility from
your server:

Some examples of cron:
15 7 * * * /home/public_html/tgp/main/generate.php
* 16 * * * /home/public_html/tgp/main/genearch.php

The explanation:
MINUTE(0-59) HOUR(0-23) DAYOFMONTH(1-31) MONTHOFYEAR(1-12) DAYOFYEAR(0-6) /path/to/the/script/tobe/executed

The firs example mean that the cron have to execute the generate.php all days at 7:15AM;
The second example mean that the cron have to execute the genearch.php all days at 16:00h or 4:00PM;


SCAN GALLERIES FROM DB
The FuckyMonkey Tgp Script includes a feature that you can verify if the galleries exist yet, if was be changed, so to
do this, go to the "link bot settings" and set the days if you want verify and what make with the galleries. If it will be
deleted or post in queue. After this go to the "check bot reports" and verify how many galleries was changed or are 404.
You can delete or update the galleries.

AUTOPOST
The autopost is used to make the maximum number of posts per gallery per period of your choice (cron dependent), set the
number of posts per gallery in "autopost options" and put the "autopost enable" so if the number of posts os reached for
category, the script will generate a warning to the submitter to submit other category or submit tomorrow! To set the 
autopost update the dabase is necessary to set a cron to the "/tgp/main/autopost.php" to period of your choice! Set the 
cron to the "50 23 * * * /home/public_html/tgp/main/autopost.php" and "* 0 * * * /home/public_html/tgp/main/generate.php" to
the generate the main page, also you can set the "* 0 * * * /home/public_html/tgp/main/genearch.php" to generate the arquives.


Go to the www.fuckymonkey.com/scripts/ to make sure find the forum if you have some doubt yet.

FuckYmonkey Tgp Script
www.fuckymonkey.com



