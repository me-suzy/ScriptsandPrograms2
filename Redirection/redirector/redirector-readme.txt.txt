Redirector.php

About:
Written by Kevin Ohashi.
Available at http://www.ohashi.us.
This script is designed to help you redirect 
all your traffic to one place efficiently.

This scripts works best on cPanel hosting.

If you like this script please include a link to:
<a href="http://www.ohashi.us">Ohashi.US - The Domain Name and Web Hosting Resource</a>


Usage (for cpanel):
-Open redirector.php in notepad (or any text editor)

-Edit this line:
$redirect = "http://landing.domainsponsor.com/?a_id=778&domainname=".$parts[$total-1].".".$parts[$total];
Changing the http://landing.domainsponsor.com?a_id=778*domainname= to whatever you want.
This will then redirect to http://whateveryouwant.com/?somequery=domain.com
domain.com is whatever domain the traffic comes from.  This is optimized for DomainSponsor
but can be applied to other redirection services or for tracking purposes.


-Create a domain to host all others on (max out subdomains/addons/parked)
Note:  This domain does not have to actually exist if you dont wont it to.
Just ftp with the username and pass to the IP of your server and upload redirector
to the public_html folder and rename it to "index.php"

-From WHM control panel park as many domains as you want on top of the dmoain you
just created.

-Your domains will now redirect properly to wherever you setup.

Multiple redirects:
-Create subdomains on your test account and upload redirector.php to those folders.

-Rename redirector.php to index.php and park domains on top of that subdomain from WHM.

-Make sure you configure each version of redirector for your subdomains.



If you have any questions or need help:
http://talk.ohashi.us is my forum that you can contact me at.


Enjoy,
Kevin Ohashi


