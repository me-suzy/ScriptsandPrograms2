Secure multiple user Log-In script by Dave Lauderdale 
Originally published at: www.digi-dl.com

######################################################


I wrote this script in an effort to help avoid the "security by obscurity" issue.

You see...even though log in scripts require you enter a name and password in order to view a particular resource once the location of the resource is known the visitor can bypass the normal log in procedure and just go straight to the "secure" page.

This is not good!

Keep in mind this is just one simple way to help get around this issue and I am sure there are more elegant ways to code this out but this works fine for me...


How the script works:

1)  When the user logs in and his username and password have been verified his username is logged to a file then he is redirected to the "secure" page.

2) As soon as the "secure" page loads it opens the log file to see if a valid user name is found. If so it will erase the log and then display the protected page content. The reason the script wipes the log clear is so noone (including the original visitor) can access the page again without re-logging on.

3) If someone tries to go directly to the "secure" page without signing in the script will read an empty log and display an error to the visitor.


***NOTES: 
You will need to add the usernames and passwords to the "dataProcess.php" page.
You will also need to add the usernames to the "secure.php" page.
The user.log file will have to have read/write permissions or else you will see this error:

"You will have to log on via the form to view this page"

