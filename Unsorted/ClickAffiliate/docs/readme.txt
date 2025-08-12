
ClickAffiliate PRO support file.

(version 3.0)

Summary:

A) Introduction: The way it works
B) Installation
C) How to work with files?
D) Start using ClickAffiliate PRO
E) 2 tier affiliation program
F) International use 

#---- LICENCE ----#

This is a commercial script, registered and protected by International Copyright laws. 

- Usage of this software can only be made under licence purchase. 
- You have to use 1 licence per domain.
- Each licence corresponds to a ClickBank number obtained after each credit card payment.

Copyritgh Digital Signature:

IDDN.FR.010.0096724.000.R.P.2001.018.30000 


A) ######### The way it works ############

This script allows you to start an affiliate program to increase traffic to your site.
It is based on redirection scripts that the affiliate call by a link. 

The real unique features of this script are that it manages PAY-PER-CLICK (PPC), PAY-PER-SALE (PPS) 
and PAY-PER-LEAD (PPL) affiliation programs!. 
No other script can do this for you as easy as ClickAffiliate PRO.

On PPC, the affiliate gets credit to his account if the visitor has never clicked on the link before. 
To ensure this, a 2 level protection checks for the uniqueness of the visitor. If the visitor 
has already clicked on a link on any affiliate no more click will be counted if the same visitor 
clicks on any member link.

On PPS, the affiliate gets commissions after a sale has been processed. The final client, must have 
clicked on your link in the affiliate site. A special commission script can be integrated into your 
final thanks page, after a sale have been processed.
All sales are logged so you can revert any commission on a sale not really completed.

On PPL, you pay to your affiliates a flat commission fee for each signup or sale. For example: you pay 
any referrals to your affiliate program.

UNIQUE: Each affiliate must provide their site URL. Only clicks coming from his URLs will be considered.
You can easily block any URL that does not complies with your rules.


- Affiliate STATS:

Affiliates can log on and visualize their statistics and earnings by entering into member area (stats.cgi).
Affiliate can also change their personal informations and add new URLs to their list.


- ADMIN:

You have access to all members data (adress, e-mail, clicks, payments, etc). 
You display users by click values or by earnings. 
You can search members by keywords. You can list all registered URLs.
You can list all referrals.
You can list all sales and make refunds of commissions on sales not really completed!


- When users reach the payment limit (you define), just click on process payment button to pay them.
You just have to send to the affiliate member his check with the amount earned. 

- You can pay to individual affiliates or make a batch payment of several pending payments by choosing 
it on the admin menu. 

To help you on payments, the admin script then displays you a table 
with affiliates postal addresses that you can print and use to label the envelopes (just cut 
the rectangles with a pair of cissors!).

- You can view raw clicks by members gathered by IP numbers. (clicks log)

- TRACKING SALES:
You can use the image tag method (described below), that you insert into your final thanks page. All customers referred by your affiliates will have a cookie that identifies the affiliate. The commission 
script called in this thanks page will check the cookies and process commissions when needed.


B) ######### Install your files ##############

IMPORTANT: If you have version 2.x installed (ClickAffiliate or ClickAffiliate PLUS) read  
the upgrade instructions in upgrade.txt.


==First time install:==

unZip the clickAffiliate.zip file into a new folder of your computer and follow instructions:

After unzip, you will find 3 folders: 

	docs : txt files with documentation
	scripts: cgi files
	html: HTML files


1 - Edit the config.cgi file according included instructions. Save it as a TXT file.

2 - Edit header.htm, footer.htm and stats_enter.htm files to reflect your site design.

3 - transfer files to your server:

*Files from scripts folder to your cgi-bin folder:
install.cgi      (chmod 755)
click.cgi      (chmod 755)
referral.cgi   (chmod 755)
click2.cgi     (chmod 755)
signup.cgi     (chmod 755)
stats.cgi      (chmod 755)
admin.cgi      (chmod 755)
commission.cgi      (chmod 755)
lead.cgi      (chmod 755)
commission_img.cgi      (chmod 755)
lead_img.cgi      (chmod 755)
config.cgi     (chmod 755)
cookie.lib     (chmod 755)


*files from html folder To a web folder outside cgi-bin:
stats_enter.htm  (chmod 777)
affiliate_ok.htm (chmod 777)
header.htm       (chmod 777)
footer.htm       (chmod 777)


4 - mySQL Database install:
 
 * You should arlready have a mySQL databse created. If not ask your web hosting provider for one.

 * You can simply run install.cgi from the cgi-bin folder. It will do the job automatically!

 * You can also use the affitables.sql file to create the tables if you know how to (with a windows
   mySQL client for example)


All files are now set up on your server and you are ready to use ClickAffiliate PRO!!!



C) ######## How to make links and work with files ##########

AFFILIATE LINKS:
Just tell your members to add the following link to their pages or e-mails. 
You have to edit it according to your site URL and cgi-bin path.

http://www.yourserver.com/cgi-bin/click.cgi?member_ID

Be creative, suggest banners, logos or buttons to your members to add to the link.

AFFILIATE STATS:
Provide on your site, a link to the stats.cgi file that you have edited. Affiliates can enter
to their account manager by entering their member_id and password.

ADMIN:
just invoque the admin.cgi script that you have uploaded to your cgi-bin folder. You should find
the usage of admin quite easy. All commands are centralized into the main menu. You select the command
you want then press the action button.


# Catch cheaters!!!!!!!

1/ TO help you catch cheaters, all clicks are recorded. You have access to login id, IP number
and URL from which the click came. 


2/ OPTIONAL 2 page click system

You can avoid cheaters by making a click page confirmation. 
Use click2.cgi in your click link. This will generate a page asking for confirmating the click.


MAKE SALES AND PAY COMMISSIONS:

//IMAGE TAG METHOD:

# SALES: #
On your final thanks page, you need to add an image tag pointing

<img src="http://www.yourserver.com/cgi-bin/clickaffiliate/commission_img.cgi?subtotal=100&invoice=XXXXX">

where:
subtotal = the sale subtotal you want to use to calculate the commission
invoice = the invoice or order number you will use in the future to identify the sale. It is specially 
          important if for example the sale is canceled and you want to revert the affiliate commission.

# LEADS: #
To pay flat fee commissions like on pay per lead programs, use on your final thanks page:

<img src="http://www.yourserver.com/cgi-bin/clickaffiliate/lead_img.cgi?subtotal=100&invoice=XXXXX">

where:
subtotal = the LEAD amount you want to use to pay your affiliates (the exact amount to pay)
invoice = the invoice or order number you will use in the future to identify the Lead. It is specially 
          important if for example the sale is canceled and you want to revert the affiliate commission.

In config.cgi you can define a sepecial lead for referring new members:
$pay_new_members=1  => Means you want to pay for referring new affiliates

$ppl=1  => The currency amount you want to pay for each new signup.


// Redirection method.
This method is similar to image tag, but instead of including an image on the thanks page, you need to
redirect to : comission.cgi file or lead.cgi file.

Ex:

commission.cgi?subtotal=100&invoice=XXXXX

lead.cgi?subtotal=100&invoice=XXXXX



// SUBROUTINE METHOD
This method is to use if you have PERL experience and know how to change perl scripts
 
In the appropriate place of your shopping cart scripts, insert:

	require "config.cgi";
	require "salecommission.lib";

	&commission($SUBTOTAL, $INVOICE);

replace $SUBTOTAL and $INVOICE by the real variable names you use in your scripts for the subtotal and
 the invoice or order number. Also, make sure to use the correct path in the require lines.



D) ######## Start your affiliate program ##########

1 - First test your scripts by signing-up as a test member
2 - test the affiliate link by creating a sample page for your new test affiliate member.
3 - Click on your sample link to test the redirection.
4 - click a second time on your sample link to test the protection against multiple clicks. 
If it is OK, you should generate only one click-through  

5 - Just invoque your admin.cgi script and check users with more than 0 clicks. You should see your
test member with 1 click.

6 - Edit HTML pages explaining your program, make phrases, banners and images that affiliates could
use to promote your site. The support page should also indicate the member link that members should copy
to their pages. Remember them to change the 'member_id' to their name.

7 - If you want to pay a flat fee to your affiliates for all new affiliate tehy refer, propose them the referral.cgi link:
http://www.yourserver.com/cgi-bin/referral.cgi?member_ID

Therefore, on each signup, ClickAffiliate PRO will search for referring members information and saves it. 
This is usufull also for 2 tier method. 



E) ######## 2 tier affiliate programs ##########

If you want to propose a 2 tier program to your affiliates, you need to set $twotier to 1 in config.cgi.

You need also to define the price of second level clicks and the % of second level sale commissions also
in config.cgi.

Then, the rest is automatic! ClickAffiliate PRO will track master ID's (affiliates that refer new affiliates)
on each click or sale. 

All 2 tier data is available to affiliates and admin.



F) ######## International usage support ##########

First, ClickAffiliate PRO can use any currency. You just define it in config.cgi

But most importantly, your relation with affiliate is done in the language you want. There is a file 
called LANG.CGI, that you can translate to your language! This file will apply on signup script and 
on affiliate account manager (stats.cgi). Your Administration part will remain in english.


If you have questions visit the web page and/or send me a mail.
http://www.affiliate-scripts.com

There is also a support forum where you can find answers to your problems or post new messages. 

Thanks! Make great business with ClickAffiliate PRO!!

Pierre Rodrigues
www.Affiliate-scripts.com
