===========================
100janCMS: Articles Control v1.0
INSTALLATION INSTRUCTIONS
===========================

1. Unzip distribution archive to your local computer to a temporary ('temp') folder (preserving archive's folders structure).

2. Create a folder of your choice (application folder) under root folder of your host, where you will install SOFTWARE PRODUCT (by default use '100jancms', e.g. http://www.yourdomain.com/100jancms/).

3. Upload all files from temp/100jancms folder to the application folder.

4. Make sure the following files and folders are writable (CHMOD 666) by application:

[files]:
	- http://www.yourdomain.com/100jancms/config_connection.php
[folders]:
	- http://www.yourdomain.com/100jancms/images/
	- http://www.yourdomain.com/100jancms/images/articles/
	- http://www.yourdomain.com/100jancms/images/articles/depot/

5. Run install.php file from web browser from the application folder location, e.g. http://www.yourdomain.com/100jancms/install.php

6. Follow the onscreen instructions to complete installation of SOFTWARE PRODUCT. You will need information to connect to your database. Contact your hosting administrator for database connection information.

7. After the installation is successful, make sure to delete the following files from application folder for security:

[files]:
	- http://www.yourdomain.com/100jancms/install.php
	- http://www.yourdomain.com/100jancms/install_2.php
	- http://www.yourdomain.com/100jancms/install_3.php
	- http://www.yourdomain.com/100jancms/install_eula.php

Make sure to apply read only attribut (CHMOD 644) to the following files for security:

[files]:
	- http://www.yourdomain.com/100jancms/config_connection.php

8. Now you can login in to application using master administrator username and password, that you specified during installation.