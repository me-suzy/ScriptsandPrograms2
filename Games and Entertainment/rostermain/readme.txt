README
###########################

by ryan marshall of irealms.co.uk

################################

Rostermain is a set of php files designed to act as a roster for an everquest 2 guild site i designed. This can easily be altered to work for any type of roster that is needed. For some of the queries the session variable $_SESSION['valid_user'] is used, this can be changed to whatever variable you wish to use for determining the user name.

All you need to do to install is insert the rostermain.sql file into your database and then upload all the files in this script.
After this fill in the database info in config.php and your ready to go.

You can remove the stylesheet standard.css if you wish to use your own.


trialists(new in version 1.1) users can be given start and end dates for a trial or promoted to a member instantly.
combined with authmain and template files(version 1.2)

NOTE:

A test logon will be created after install. To log on as this user enter the following information:

Logon : test
Password : test

This test user has admin access and 2 test characters. Both the test characters and the test user can be removed once you register and approve another user. 

Example:

Register yourself as a user

Log on as test

Approve and grant admin rights for the new user

Log off

Log on as the new user

Delete test user


For any help modifiying this script or for help installing rostermain contact ryanmarshall@irealms.co.uk