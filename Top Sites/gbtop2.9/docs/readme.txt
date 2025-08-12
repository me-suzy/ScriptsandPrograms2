GB Top-Directory Installation Guide

Requirements:

- PHP 4.0.5, Zend Optimizer 2.0.0

Installation:

1. Upload the files config.php, countries.php, skin.php and .htaccess in ASCII mode into main directory of your toplist
2. Upload all other files in main directory in Binary mode.
Attention! Some FTP-clients like CuteFTP will try automatically upload php-files in ASCII-mode.
You need manually set Binary mode.
3. Create subdirectories backupfiles, datafiles, memberfiles, templates and chmod these subdirectories
and root directory to 777
4. Upload all html-files in templates directory in ASCII mode and chmod to 666
5. Chmod config.php to 666
6. Run setup.php
7. Delete setup.php

Updating of old versions:
- Upload all php files, exclude config.php
- Correct new fields in settings

Deinstallation:
- Upload and run uninstall.php
- Delete all stayed files

Docs and FAQ:
http://www.gbscript.com

Support Board
http://forum.mavpa.com
