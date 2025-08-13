
       
    1. Installation
        2.1 Quick Install


1. Installation
====================================================

1. Installation
----------------------------------------------------
If you are comfortable with Perl, here's some instructions that
should get the script up and running quickly. If you are upgrading 
from Links 1.1, please consult the Upgrade.txt file.

1. Unzip the distribution and you should see two directories cgi-bin
   and webpage. Upload everything in cgi-bin in ASCII mode to a directory
   on your server that can run cgi. For example, I recommend creating a
   directory called ddl off your cgi-bin. You'll end up with a structure 
   like:
                /cgi-bin/ddl        - User cgi like search.cgi, add.cgi, etc.
                /cgi-bin/ddl/admin  - All the admin programs.
                
    Make sure all the files are transferred in ASCII mode!!
    
    Upload the images to the directory where 
    you want the pages created (like http://url.com/ddl/images).

2. Double check that the Path to perl is correct. DDL defaults with
        #!/usr/local/bin/perl
  
   If this is incorrect, you'll need to edit the first line of every .cgi
   program, and change it to where you have Perl version 5 installed.

3. Set permissions:
        chmod 755 (-rwxr-xr-x) on all .cgi files.
        chmod 666 (-rw-rw-rw-) on all files in the data directory.
        chmod 666 (-rw-rw-rw-) on all your template files (if using the online editor).
        chmod 777 (drwxrwxrwx) on the hits directory
        chmod 777 (drwxrwxrwx) on the ratings directory
        chmod 777 (drwxrwxrwx) on the directory where Links pages will be created.      

4. Edit links.cfg

5. ** Password protect your admin directory. Never leave your admin directory unprotected
   in a public site, your whole directory could be erased!!
   
6. Give it a test! Go to: http://yourserver.com/cgi-bin/ddl/admin/admin.cgi or wherever
   you setup admin.cgi, and you should see the admin screen. Try (in this order):
   
        1. Add a category.
        2. Add a link in that category.
        3. Build pages.
        4. Search for the one link, using the new pages created.
        5. Add a link from the new pages created.
        6. Validate a link from admin.
    
    If everything goes ok, you should be all done!