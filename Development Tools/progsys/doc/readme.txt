Copy config.dist.php to config.php and edit it to fit your needs.
Note: You can have multiple instances of ProgSys in the same database.
Just set tableprefix in config.php to different values for each installation.
Do this bevore calling install.php.

Upload all files (.php, inc. ...) to your webhoster.
Please maintain directory structure.

Important installation note: the database you define in config.php must still exist. The
install script does not create a database for security reasons.

1st time installation:
Now you can call http://your.host.com/installdir/admin/install.php to create the tables in database
and defining 1 adminuser.

Upgrade:
THIS ARCHIVE ONLY CONTAINS DB UPDATE SCRIPTS FOR V0.130+.
TO UPDATE OLDER VERSION PLEASE DONWLOAD UPDATE PACKAGE TO 0.130 FROM WEBSITE !!!
Please replace all .php-Files on your server
So you determine which script you need: Take the actually installed versionnumber (e.g. 0.20).
Remove the point in the versionnumber (in our example this results in 020).
Now you have the upgrade version.
Now you need to sequentially use all upgrade scripts from this version to the actual version.
For our example the actual version should be 0.22 (short 023). Use upgrade_020_to_021.php
than upgrade_021_to_022.php and so on till you reach upgrade_022_to_023.php
After installation remove install.php or anyone can mess up your database.
If one script in the sequence does not exists, this only means for this upgrade step
no database changes were necessary. In this case just continue with the next one.
After installation remove install.php or anyone can mess up your database.

After installation remove install.php or anyone can mess up your database.

Now you can enter the admininterface by calling http://your.host.com/installdir/admin/index.php
and login using the adminuser created during the installprogress.

To display the changelogs for a program call:
http://your.host.com/installdir/changelog.php?prog=<progid>&lang=<language>&list=all

To display the ToDo list for a program call:
http://your.host.com/installdir/todo.php?prog=<progid>&lang=<language>

To display the reference sites for a program call:
http://your.host.com/installdir/references.php?prog=<progid>&lang=<language>&display=1

To display buglist for a program call:
http://your.host.com/installdir/bugtraq.php?prog=<progid>&lang=<language>

To track a download:
http://your.host.com/installdir/download.php??url=<url to download file>
or
http://your.host.com/installdir/download.php??filenr=<Nr of entry for download file>

If you don't define a language in URL, the default language defined in config.php will be used.

Example for using downutil.php to generate "switchable" downloadlinks in your own pages:
1st include the script in your PHP file:
<?php
require(getenv("DOCUMENT_ROOT")."/progsys/downutil.php");
?>
2nd use showdownloadlink(<filenr>,<linktext>,<languagecode>) to display the downloadlink, which
will be turned off, if you disable download for this file in admin interface:
<?php showdownloadlink(10,"main server",en)?>


This program is Open Source (see copying).
You can change all to fit your needs, but you have to leave the copyright footer
(genereated by ...) alone.

If You are using this program on Your website, please enter Your site as a
reference on our homepage.

If You are using this program in a commercial environment, it would be very kind to
provide our work with a little donation.

newest version can be found at
http://www.boesch-it.de

Authentitication of admin users
-------------------------------
There are 2 ways to get authentication for admin users.
1) Sessionhandling using cookie.
2) Sessionid sent by get and post requests.

Method #1
---------
You have to set $sessid_url in config.php to false.
In this case please ensure the following settings in config.php are right:
$url_progsys
$cookiedomain
$cookiename
$cookiepath
$cookiesecure
$sesscookiename
$sesscookietime

Method #2
---------
You have to set $sessid_url to true in config.php.
This method uses no cookie for storing the sessionid, but instead sends the sessionid in every
get and post request.
Because of this, everybody who can look on your screen also can see your sessionid. We think
this is not really secure and recommend to use method 1. But you decide yourself.
Please ensure $sesscookiename is set to an value not used in an other way by ProgSys (best
would be to let the default name, because this ensures avoiding conflicts with other
HTTP-variables ProgSys uses).

Newsletter
==========

Example code for using external form to subscribe to newsletter:
<!-- --------- Start of subscription code --------- -->
<table width="80%" align="center">
<form method="post" action="/progsys/subscription.php">
<input type="hidden" name="lang" value="en">
<input type="hidden" name="mode" value="subscribe">
<input type="hidden" name="prog" value="<progid>">
<tr><td align="right" width="30%">Email:</td>
<td><input type="text" name="email" size="40" maxlength="240"></td></tr>
<tr><td align="right" valign="top">Email type:</td><td>
<input type="radio" name="emailtype" value="0" checked> HTML<br>
<input type="radio" name="emailtype" value="1"> plain text</td></tr>
<td align="center" colspan="2"><input type="submit" value="subscribe"></td></tr></form></table>
<!-- --------- End of subscription code --------- -->

Example code for using external form to unsubscribe:
<!-- --------- Start of unsubscription code --------- -->
<table width="80%" align="center">
<form method="post" action="/progsys/subscription.php">
<input type="hidden" name="lang" value="en">
<input type="hidden" name="mode" value="unsubscribe">
<input type="hidden" name="prog" value="<progid>">
<tr><td align="right" width="30%">Email:</td>
<td><input type="text" name="email" size="40" maxlength="240"></td></tr>
<td align="center" colspan="2"><input type="submit" value="unsubscribe"></td></tr></form></table>
<!-- --------- End of unsubscription code --------- -->

Note on field "remark put in emails" at programs:
Please include "{unsubscribeurl}" somewhere in your text (without the quotes). This will
be replaced by the appropriate URL to unsubscribe for the recipient on sending mails.
"{progname}" will be replaced by the program the newsletter is associated with.
"{homepageurl}" will be replaced by a link to the homepage defined in admin interface.

(Uses HTML Mime Mail class by Richard Heyes <richard@phpguru.org>)
