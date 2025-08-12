#############################################################
#############################################################
##        Aaron's All Engine Expired Domain Finder v1.0    ##
##                This is a Commercial Script              ##
##        Modification, Distribution or Resale without     ##
##        Prior written permission is in Violation of      ##
##        the copyrights and International Intellectual    ##
##        Property Laws.  Violators will be prosecuted!    ##
##        http://www.aaronscgi.com - aaron@aaronscgi.com   ##
#############################################################
#############################################################
#                                                           #
#             INSTALL AND OPERATION INSTRUCTIONS            #
#                                                           #
#############################################################

##################
## INSTALLATION ##
##################

   PREPARING THE FILES
	
	1. Open all .cgi, .pm .pl files and assure the path to perl is correct.  
	   If you do not know for sure check with your server administrator.
	2. Create an executable directory on your server where you will place
	   this script. 
	3. Open ".htaccess" and change Line 3 ( Action text/html /expired/i.cgi )
	   and change "/expired/i.cgi" to a path from your web root to the location
	   of the directory that you created where you will be placing the "i.cgi"
	   
	   *EXAMPLE: If you own and are placing this script at 
	   http://www.aaroncgi.com/domains and that is where you will be uploading 
	   the script to, then line 3 in the .htacess file should read:
	   
	   ( Action text/html /domains/i.cgi )
	   
	   DO NOT remove the "/" at the beginning of the path.
	       
	         
   UPLOADING AND SETTING PERMISSIONS FOR THE SCRIPT
        
        1. Upload all files in "ASCII" Mode to the directory you created
           and ASSURE YOU MAINTAIN DIRECTORY PATHS. 

    PERMISSIONS

Chmod <data> Directory to 777 as this is where all search Data will be saved.     
Do Not Chmod -->  .htaccess
Chmod to 755 -->  Browse.html  
Do Not Chmod  -->  expireddomain.css
Chmod to 755 -->  i.cgi
Chmod to 755 -->  index.html
Do Not Chmod  --> read_me_install.txt
Chmod to 755 -->  Search.html
Chmod to 755 -->  Search.pl
Chmod to 755 -->  data/debug
Chmod to 755 -->  .lib/CheckDNS.pl
Chmod to 755 -->  .lib/CheckWHOIS.pl
Chmod to 755 -->  .lib/POWER/iCGI.pm
Chmod to 755 -->  .lib/POWER/lib.pm
Chmod to 755 -->  .lib/POWER/LOG.pm
Chmod to 755 -->  .lib/POWER/HTML/TAG.pm
Chmod to 755 -->  .lib/POWER/MetaSearch.pm
Chmod to 755 -->  .lib/POWER/MetaSearch/AllTheWeb
Chmod to 755 -->  .lib/POWER/MetaSearch/AOL
Chmod to 755 -->  .lib/POWER/MetaSearch/DirectHit
Chmod to 755 -->  .lib/POWER/MetaSearch/Dmoz
Chmod to 755 -->  .lib/POWER/MetaSearch/Excite
Chmod to 755 -->  .lib/POWER/MetaSearch/Google
Chmod to 755 -->  .lib/POWER/MetaSearch/LycosPro
Chmod to 755 -->  .lib/POWER/MetaSearch/MSN
Chmod to 755 -->  .lib/POWER/MetaSearch/Netscape
Chmod to 755 -->  .lib/POWER/MetaSearch/NorthernLight
Chmod to 755 -->  .lib/POWER/MetaSearch/Yahoo
Chmod to 755 -->  .lib/POWER/MetaSearch/YahooDirectories
Chmod to 755 -->  .lib/POWER/Multi/DNS.pm
Chmod to 755 -->  .lib/POWER/Multi/GET.pm
Chmod to 755 -->  .lib/POWER/NB/IO.pm
Chmod to 755 -->  .lib/POWER/NB/Resolver.pm


       
   RUNNING THE SCRIPT
 
 	In the example above, we placed the script at:
        http://www.aaronscgi.com/domains
        
        All you have to do is open the directory you placed the script at in your 
        we browser.
        
        So if you were going to start the script from the example above, you would  
 	point your browser to:
	
	http://www.aaronscgi.com/domains
	
	
 
