
#########################################################
#    Copyright © EliteWeaver UK All rights reserved.    #
#########################################################
#                                                       #
#  Program         : IPN Development Handler            #
#  Author          : Marcus Cicero                      #
#  File            : readme.txt                         #
#  Function        : Licence/General Information        #
#  Version         : 2.0                                #
#  Last Modified   : 10/04/2003                         #
#  Copyright ©     : EliteWeaver UK                     #
#                                                       #
#########################################################
#    THIS SCRIPT IS FREEWARE AND IS NOT FOR RE-SALE!    #
#########################################################
#              END USER LICENCE AGREEMENT               #
# Redistribution and  use in source and/or binary forms #
# with or without  modification, are permitted provided #
# that the above copyright notice is  reproduced in the #
# script, documentation and/or any other materials that #
# may  have been provided in the original distribution. #
#########################################################
#    Copyright © EliteWeaver UK All rights reserved.    #
#########################################################


 License
 =======

 Redistribution and  use in source and/or binary forms
 with or without  modification, are permitted provided
 that the above copyright notice is  reproduced in the
 script, documentation and/or any other materials that
 may  have been provided in the original distribution.



 Information
 ===========

 The EliteWeaver UK "IPN Development Handler" is a free
 php resource for developers wishing to integrate their
 projects into the PayPal Instant Payment Notification
 service, more genuinely known as IPN. As PayPal do
 not currently have a testing environment this script
 can operate in two different modes!

 1.) Live - This mode should only be used when you have
 completely finished customizing this handler to suit
 your needs, and have verified that it actually works.

 2.) Test - This mode ties itself into our own IPN
 enviroment where you can simulate the posting of
 a notification using some or all of the variables.

 Our IPN testing environment can be found below!

 URL: http://www.eliteweaver.co.uk/testing/ipntest.php



 Information
 ===========

 You will find that the skeleton of this handler is
 fully documentated throughout and contains plenty of
 useful information, and tips you may wish to consider.

 See: "ipnvars.txt" for a full list of IPN variables.



 Change Log
 ==========

 Changes: 1.X > 2.0

 1.) Script has been completely rewritten from ground up!

 2.) Removed $restrict array as it was causing too much
     hassle as users had to keep it upto date else any
     newer IPN's would fail. There is also a serious flaw
     in some builds of php that renders the filtering of
     these variables useless and can disable the flow.

 3.) Added a new regex to replace the above which works
     perfectly in terms of protecting vulnerable builds
     of php from any variable injection and poisoning!!

 4.) Added support for IPN data that is escaped using
     magic quotes gpc. This means that names containing
     a ' will not result in INVALID IPN's as before.

 5.) The IPN POST array is now passed into a PAYPAL array
     and the original post data is destroyed. There is a
     specific reason for this but I will not comment :-0

 6.) IPN's that fail on a POST request due to a multitude
     of reasons such as collapsed or blocked sockets and/
     or tempermental stream wrapping can now have a second
     chance at validation using GET. This can seriously
     reduce the likelyhood of any unresolved notifications.

 7.) Extremely useful and well presented debugging output
     that will display anything and everything you wanted
     to know about the IPN and validation process. Please
     ensure you have $debugger set to 1 to benefit from it.

 8.) Built-In function that allows you to cross reference
     and match IPN variables against any supplied criteria.
     A very simple way to audit your variables efficiently!

 9.) Finally, due to a "certain" individual who thought it
     would be a great idea to remove our copyright from a
     previous version, rename a few of the named variables
     and then repackaged the distro in order to advertise
     their services this script has been taken out of GPL
     as of this release. You are still granted all the
     rights and privledges as stated in the GPL but this
     now means we are in a better position to prevent such
     people from cashing in on something that is 100% free!



2.0 Summary
===========

 As a solid code foundation for IPN we strongly believe this
 update offers twice the security as previous versions with
 none of the restrictions that used to disable the old handler
 everytime PayPal added new variables to IPN. As it now comes
 with full debugging output capabilities and a fail safe switch
 to GET in the event that an IPN validation via POST should fail
 it could be conceived that it features one of the most stable
 and consistent methods of reliable IPN validation. It has been
 tested on several platforms using a range of different php
 builds without error and works regardless of globals switched
 on or not, though for php security reasons preferably not!



 Support
 =======

 Unfortunately, due to popular demand, we DO NOT offer
 any technical support for this script. It is fairly
 straight forward to use but if you are new to php
 then we reccomend you visit the following web sites:

 http://www.php.net
 http://www.mysql.com
 http://curl.haxx.se/

 Also, if you are looking for other useful ipn help and
 resources then we would ask that you check out these:

 http://www.paypalipn.com
 http://www.paypaldev.org
 http://www.paypal.com/pdn

 You may ask for our support on the above 2 boards.


 If you would like to register your support for this
 product or would like to suggest any improvements
 then please feel free to email: ipn@eliteweaver.co.uk



 Custom Work
 ===========

 If you would like us to integrate ipn into any of your
 existing scripts or projects, we do offer custom coded
 services with free consulting. If you are interested
 then please send us details of your requirements to:
 sales@eliteweaver.co.uk. We will contact you normally
 within 24 hours but please allow upto 72 (we get busy).



 Commercial Products
 ===================
 
 Anti-Fraud Shield: http://www.eliteweaver.co.uk/shield
 Business Portal: http://www.eliteweaver.co.uk/business
 ProfitPal: http://www.profitpal.co.uk



 Kindest regards,

 Marcus Cicero - (Developer)
 EliteWeaver (U)nited (K)ingdom


#########################################################
#    Copyright © EliteWeaver UK All rights reserved.    #
#########################################################
#              END USER LICENCE AGREEMENT               #
# Redistribution and  use in source and/or binary forms #
# with or without  modification, are permitted provided #
# that the above copyright notice is  reproduced in the #
# script, documentation and/or any other materials that #
# may  have been provided in the original distribution. #
#########################################################
#    THIS SCRIPT IS FREEWARE AND IS NOT FOR RE-SALE!    #
#########################################################