Installation:

There are 18 files that are required to install List Site Pro. Make sure that you upload all of the program files in ASCII not Binary.

Directory Structure and CHMOD Permissions:

The structure for List Site Pro is:

Directory: http://www.yoursite.com/topsites
File: index.html - 777

Directory: http://www.yoursite.com/cgi-bin/lspro
File: lspro.cgi - 755

Directory: http://www.yoursite.com/cgi-bin/lspro/protected
File: admin.cgi - 755
File: .htaccess - 666 
File: .htpasswd - 666 
File: admin.pl - 666
File: data.file - 666
File: reset_time - 666
File: update_time - 666

Directory: http://www.yoursite.com/cgi-bin/lspro/html
File: lspro_list_footer.txt
File: lspro_list_header.txt
File: lspro_rules.txt
File: lspro_std_header.txt
File: lspro_std_footer.txt
File: lspro_break_10.txt
File: lspro_break_25.txt
File: lspro_break_50.txt
File: lspro_break_7.txt

To set up the program go to http://www.yoursite.com/cgi-bin/lspro/protected/admin.cgi
