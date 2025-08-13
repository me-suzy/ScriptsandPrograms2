
 Free For All Link Page v1.2


#########################################################
#                                                       #
# PHPSelect Web Development Division                    #
#                                                       #
# http://www.phpselect.com/                             #
#                                                       #
# This script and all included modules, lists or        #
# images, documentation are distributed through         #
# PHPSelect (http://www.phpselect.com/) unless          #
# otherwise stated.                                     #
#                                                       #
# Purchasers are granted rights to use this script      #
# on any site they own. There is no individual site     #
# license needed per site.                              #
#                                                       #
# This and many other fine scripts are available at     #
# the above website or by emailing the distriuters      #
# at admin@phpselect.com                                #
#                                                       #
#########################################################


 This script allows visitors to add links
 to your page in a specified category.
 It is quite easy to install.

 1.Open links.pl with a text editor.
   Change the url in line one, to the Perl program at your server.
   Usually it is: - /usr/bin/perl or /usr/local/bin/perl for Unix.
                  - C:/Perl/Perl.exe for Windows (use slash "/")
   Set the correct paths and required urls.

 2.Upload links.pl in ASCII-mode to your cgi-bin directory
   and change mode it to 755 (-rwxr-xr-x).

 3.Open addlink.html with a text editor.
   Change the line
   <form method="post" action="http://localhost/cgi-bin/links.pl" name="message">
   to the correct location of links.pl

   That's all.

