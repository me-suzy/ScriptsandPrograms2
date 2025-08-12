Before starting the installation, MySource Classic has been tested on:


Apache 1.3.x (x > 23) and 2.0.x 
PHP 4.3.x 
MySQL 3.23.x (x > 50) and 4.0.x

Versions above and below these specified may or may not work, if you have any success with versions other than those listed above please let us know.

Additionally, while there has been some success with some people running it on Windows, it is only officially supported on linux, bsd or solaris.

You will need permission to edit your apache config since some parts of MySource Classic requires mod_alias loaded and you will have to add AliasMatch directives in order to be able to get MySource Classic to work. 

If you want to use the spell checking features you will need php compiled with pspell support.

If you want to use the squiz_server features you will need php compiled with sockets support.

If you want to use the stalks menu item you will need php compiled with gd support for the type of image you want to use (png or gif or both).

If you want to authenticate off an ldap directory you will need ldap support compiled into php. 
Installation - A Simple Guide
Assuming your PHP, Apache and MySQL have been compiled and setup correctly and are the right versions you should be able to follow this procedure to install your MySource Classic.

Extract the files from the tarball to where you want them 

$ tar -xzvf mysource_2-10-4.tar.gz -C /var/www
$ mv /var/www/mysource_2-10-4 /var/www/mysource

Set the configure script to be executable 

$ cd /var/www/mysource
$ chmod 755 configure

Run the configure script 

$ ./configure

Answer the questions as it prompts you. If you run the script as root it will set the permissions up correctly for you. If you run it as another user it will display some commands that you will be required to run in order to setup the permissions correclty. The out-put with the comands will look like this: 

I can't reset the file ownerships. You are currently not logged in as root.
Please log in as root, then run the command:
chown -R apache.apache /var/www/mysource

or

chown -R apache:apache /var/www/mysource
Thanks.

The configure script will ouput an example apache virtual host that you can choose to add to your exisiting apache configuration. If you aren't using virtual hosts, the important lines in the output are the: 

AliasMatch "^(/.*)?/__lib(.*)$" "/var/www/mysource/web/__lib$2"
AliasMatch "^(/.*)?/__squizlib(.*)$" "/var/www/mysource/squizlib$2"
AliasMatch "^(/.*)?/__data(.*)$" "/var/www/mysource/data/unrestricted$2"
AliasMatch "^(/.*)?/_edit(.*)$" "/var/www/mysource/web/edit$2"
AliasMatch "^(/.*)?$" "/var/www/mysource/web/index.php"

<Directory /var/www/mysource/web>
AllowOverride All
Order allow,deny
Allow from all
</Directory>

<Directory /var/www/mysource/squizlib>
AllowOverride All
Order allow,deny
Allow from all
</Directory>

<Directory /var/www/mysource/data/unrestricted>
AllowOverride All
Order allow,deny
Allow from all
</Directory>
If you only want MySource Classic to be www.example.com/mysource and everything underneath that, for example, you would have to adjust the AliasMatch lines above to be: 



AliasMatch "^/mysource(/.*)?/__lib(.*)$" "/var/www/mysource/web/__lib$2"
AliasMatch "^/mysource(/.*)?/__squizlib(.*)$" "/var/www/mysource/squizlib$2"
AliasMatch "^/mysource(/.*)?/__data(.*)$" "/var/www/mysource/data/unrestricted$2"
AliasMatch "^/mysource(/.*)?/_edit(.*)$" "/var/www/mysource/web/edit$2"
AliasMatch "^/mysource(/.*)?$" "/var/www/mysource/web/index.php"

You will still need the directory sections regardless of what part of your domain you configure MySource Classic to use.

These changes to your apache config will require apache to be restarted, however before we do that we need to double check some settings in your php.ini. If you don't know where your php.ini is, see the section on finding your php.ini.

your php.ini will need the following settings: 

file_uploads = On
short_open_tag = On 

The next settings are system dependant but some sensible starting points are shown. 

memory_limit = 32M (8M is typically the default setting)
post_max_size = 20M (8M is typically the default setting)
upload_max_filesize = 20M (2M is typically the default setting)

Once these changes are done, you should be able to restart apache and visit www.example.com or www.example.com/mysource depending on how you setup your system and get a MySource Page not found page. 

From here you can goto www.example.com/_edit and login with the username root and password you specified to the configure script and then load up a design (examples are are in the doc directory in the MySource tarball), then create a site that uses that design then add some pages to that site add a site url to the site and make some pages live and view it on the front end. 
Congratulations!
