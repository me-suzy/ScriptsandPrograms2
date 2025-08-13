#######################################################
#		Home Free v3.2
#     
#    		Created by Solution Scripts
# 		Email: solutions@solutionscripts.com
#		Web: http://solutionscripts.com
#
#######################################################
#
#
# COPYRIGHT NOTICE:
#
# Copyright 1999 Solution Scripts  All Rights Reserved.
#
# Selling the code for this program without prior written consent is
# expressly forbidden. In all cases
# copyright and header must remain intact.
#
#######################################################

Home Free is a copyrighted commercial piece of software. Please respect this
copyright and our hard work. Dirstibuting the source code to Home Free in any manner
to anyone is strictly prohibited. We will take legal actions against those who do.


Initial installation instructions can be found at:
http://hf-online.com/manual/install.shtml

If you are upgrading from a previous version, 
please see the upgrading instructions at:
http://hf-online.com/manual/upgrade.shtml

The Home Free online manual can be found at
http://hf-online.com/manual

## OBTAINING SUPPORT ##

For a fast and informative response to your support questions, please use
one of the following 2 methods of contacting us. (or both)

1. Use our support forums. The Home Free support/help forum can be found at:
   http://solutionscripts.com/forum/homefree.cgi
   Many other Home Free owners frequent this forum, thus may be able to provide
   an answer or helpful advice faster then we can. Of course Solution Scripts
   also answers questions posted on the forums. 
   
2. Use our ticket based support system, where each support request you make will
   be assigned a ticket number. This greatly helps us speed up and track all support
   requests. To use this ticket based system, either use the support center at:
   http://solutionscripts.com/lounge/homefree/support.cgi 
   or send an email to support@solutionscripts.com. If you send an email please include
   which prorgam (Home Free in this case) you are running and your Solution Scripts 
   username, without these we will first have to ask you for them to verfiy you are a
   true customer.   
   
If either case when asking a support question, please include all information about
the problem you can. Also include which version you are currently running, 
and the database type you are using.

Please do not send support questions to any Solution Scripts address besides
support@solutionscripts.com

On the otherhand, all non support related questions can be sent to: 
solutions@solutionscripts.com

##

A small community has built up around users of Home Free and thus we have
opened 2 additional forums for users of Home Free to come and share their 
ideas about anything they like. The urls for these forums, the "Home Free
User Enhancmenets" forum and the "Home Free marketing and promotion" can be
found from the members lounge page at:
http://solutionscripts.com/lounge/homefree

As of version 3.2 we have created a Resource Center for Home Free
http://solutionscripts.com/lounge/homefree/resource_center
This includes some tips and tricks for Home Free, plus other language files

The below is being reworked into a web page shortly......

##########################################

About Home Free features and how they work.......

To allow people to sign up for a free account, send them to the new.cgi file.
When they sign up, a random password is chosen for them and sent to their email address
thus you know the email address is valid, and that only one account per email address
is given (although now you may let emails have more than one account)......

For members to login into their file manager, send them to manager.cgi 
Once logged in they have access to all the file manager features. 
Your headers and footers are always placed on the tops and bottoms of
every html file uploaded or created by them.

To add more space, above and beyond the amount you selected in
the variables.pl file, log into the admin.cgi script, and enter
the account name you want to add more space to into the 
"view account" box, then press the "view account" button..
You will then see the space to add or remove space to a file...
To remove space, set the text back back to zero or the lesser amount you want

As of version 1.1 you may make all new users "Agree" to terms and conditions
for gaining an account and free web pages. To turn this option on, set
the term variable in variables.pl to 1. Then in the admin.cgi script,
you can add and edit your terms and conditions by selecting the rules.txt file from 
the list of those to edit. You can enter any html in the rules file, but
remember that the "Terms and Conditions" is hard coded into the script, as is the
checkbox for clicking to accept the terms.


#######################

Any questions please visit the Home Free members lounge at
http://solutionscripts.com/lounge/homefree
or email us at solutions@solutionscripts.com