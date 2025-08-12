 _    _ ______  _____ 
| |  | ||  _  \/  __ \
| |  | || | | || /  \/
| |/\| || | | || |    
\  /\  /| |/ / | \__/\
 \/  \/ |___/   \____/
Weekly Drawing Contest Sourcecode readme

1) Installation
2) Creating the Admin account
3) Adding contests
4) Information
5) Changelog

-------------

1) Installation

i...) Edit mysql.php to include your MySQL details
ii..) Edit config.php with desired settings
iii.) IMPORTANT - Add your mysql username to the secret word list in config.php
iv..) Upload all files to webspace
v...) Make sure the entries folder has create file and write file permissions
vi..) Run install.php
vii.) Delete install.php

-------------

2) Creating the Admin account

i...) Register a new account
ii..) Log into your mysql admin panel (PHPMyAdmin recomended)
iii.) Edit your newly created user data in "users" table, and change position to Admin

-------------

3) Adding contests

i...) Log into your mysql admin panel (PHPMyAdmin recomended)
ii..) Add an insert to the table "contest_contests" and give it a name and description (leave other fields at default)

-------------

4) Information

The WDC was originally created by Akira Hasegawa of Tsunami Channel fame (http://www.tsunamichannel.com) work and other time takers ripped him from this project and others took over, its seen a few leaders and now its my turn, i run the Tsunami Channel fan site http://www.tcgames.net

if anything goes wrong you can contact me at drakahn@tcgames.com you can also ask on the WDC forums at http://www.ponju.net or http://www.tcgames.net

-------------

5) Changelog

8/22/2005
---
first release
---
