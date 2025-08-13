Account Manager 1.030 - Security Update
VERSION 1.0
============================================

## Version 1.029 update information ##

Now compatible with Authorizenet/ECX 3.0

############

Hello Patient Account Manager users!

Well, we've finally completed our Beta of our Authorize.net /
ECX interface for Account Manager.

Although we have every confidence that you will experience
very few problems, please do submit your questions and "bug"
reports to us.  You will find our Support contact information at:

http://support.cgiscriptcenter.com

Please be sure to include the following information:

Name
Username
Program (Account Manager)
and version of Authorizenet interface (VERSION 1.1)

Setup instructions are found below.  Please do let us know if
this is working properly for you.

Diran Alemshah
CGI Script Center
=====================================

Installation Instructions for Authorizenet Update:
==================================================

1) Rename your existing files to:
 
acctman.pl --> acctman.bak.
config.pl --> config.back (optional, see below)


We'll need these as backup if you ever decide to reuse
them.

2) Open the files included in this update:

config.pl
acctman.pl
authorizenet.pl

You *can* just copy the new portion of the config.pl file to
your existing config.pl file, if you so choose.  Please make
sure to copy the entire section, so everything works properly.

The section to copy over is located at the bottom of the new
config.pl, and marked with:

#################################################################
################## Authorize.net Users Only #####################
#################################################################

Everything below this line (in the new config.pl file), please copy
and paste to your existing config.pl file.  Otherwise, just replace
your existing config.pl file with the new one included in this
upgrade and make the necessary changes throughout.

Make sure to set the variables requested in the Authorizenet section.

Be sure to change the "require" lines in both the acctman.pl and
the authorizenet.pl files to tell the programs where to find
your config.pl file.

3) Upload all three files.  Make sure to set CGI permissions on
these files.  Normally 755 is sufficient.  Contact your host for
server-specific permission settings.

4) Log into your Authorizenet or ECX Control Panel and be sure
to do the following:

===

a) Set NO required fields. Uncheck all of the form fields in the control panel: 

Payment Form/Receipt Settings -to- 
Payment Form / Weblink Field Settings 

Here you should UNcheck all the boxes in the "Required" field. Account Manager
and Commission Cart both have their own Required fields already set. 

b) Go back to your main screen on your control panel, then go to the: 

Manage URLs 

section. Here you'll need to enter two things: 

1) The path to your amform.htm file, as a 

Valid Browser Referrer URL 

and last.. enter your authorizenet.pl file path as 

ADC Relay Response Default URL

=======


Once completed, simply submit a test purchase.  Make sure to set your
config.pl variable $test_request = "TEST";
while testing.

Authorizenet has provided test credit card numbers to use for
testing of their system at:

http://www.nsi-corp.com/testdrivecust.htm

=======================


KNOWN PROBLEMS:
=================

1) We have not yet created a way for users of Authorizenet to accept
Checks as a form of payment, as with the original version.  This is
something we intend to do.


## Version 1.030 update information ##


We have addressed a security concern that we were made aware of in the
administratin portion of the Account Manager Professional program.  We
recommend upgrading to version 1.030 as soon as possible.

To upgrade, first backup your original script files (acctman.pl,
amadmin.pl, config.pl, nightlyU.pl, and remote.pl).  Next, simply
configure the new Account Manager script files with the information
you used on your previous installation of Account Manager,
and upload the new script files over the existing script files.

If you find any documentation for this upgrade lacking or inaccurate,
please contact us.  You will find our Support contact information at:

http://support.cgiscriptcenter.com

and let us know the details.

Diran Alemshah
CGI Script Center
http://cgiscriptcenter.com

