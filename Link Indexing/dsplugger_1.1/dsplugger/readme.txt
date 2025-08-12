DS Plugger version 1.1
Author: Chris Warren
http://www.dawgiestyle.com

##### Installation instructions: #####

1) Open "config.php", enter your MySQL info in the spaces provided, as well as your desired username and password for the admin area.

2) Upload the entire dsplugger directory to your webserver.

3) Open your browser and go to www.yourdomain.com/dsplugger/install.php and choose "(Re)Install" to install the tables, using the actual URL to install.php.
   *Note: you must delete install.php from your server once installed.

4) Follow the link to the admin page after installation.  DS Plugger is now installed.

#####

##### Upgrade instructions: #####

1) Upload all files except config.php to your dsplugger directory.  Overwrite the existing files.

2) Open your browser and go to www.yourdomain.com/dsplugger/install.php and choose "Upgrade", using the actual URL to install.php.
	*Note: the "Upgrade" option is only available when there is a previous version installed on the same database.
	*Note: you must delete install.php from your server once installed.
	
3) That's it!  All previous settings and plugs will remain on your new version.

##### Notes: ####

DS Plugger is displayed within an iframe on your site.  The iframe code is found in the admin panel.

Settings:
    Button Width and Button Height - Submitted plug buttons will be displayed in this size.

    Columns of Buttons - Number of buttons to be displayed horizontally.

    Rows of Buttons - Number of buttons to be displayed vertically.
    *Example: 3 columns and 4 rows will display a total of 12 buttons.

    Link Target - _blank will open links in a new window.
    
Style:
	Border Color - If left blank, no border will appear.


Email me with any questions or suggestions.
chris@dawgiestyle.com
