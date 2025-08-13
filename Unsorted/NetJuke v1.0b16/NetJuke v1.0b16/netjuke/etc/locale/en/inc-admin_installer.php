<?php

##################################################

# UNUSED SINCE 1.0b8, NOW /installer/installer.php

##################################################

define( "ADMINST_ERR_VERSION", "This installer cannot be used with the targetted\\ncopy of the Netjuke. Versions do not match.\\nPlease get the latest copy and try again." );

define( "ADMINST_ERR_FSPATH_1", "Please enter a Filesystem Path." );
define( "ADMINST_ERR_FSPATH_2", "A Filesystem Path cannot contain ://" );
define( "ADMINST_ERR_WEBPATH_1", "Please enter a Web Path." );
define( "ADMINST_ERR_WEBPATH_2", "A Web Path must contain http:// or https://" );

define( "ADMINST_ERR_DBTYPE", "Please enter a DB Type." );
define( "ADMINST_ERR_DBHOST", "Please enter a DB Host." );
define( "ADMINST_ERR_DBNAME", "Please enter a DB Name." );
define( "ADMINST_ERR_DBCONN", "Cannot connect to the database using the provided information." );

define( "ADMINST_ERR_EMAIL", "Please enter a default Sys. Admin email address." );
define( "ADMINST_ERR_PASS", "Please choose a default Sys. Admin password." );

define( "ADMINST_ERR_STREAM", "Please enter a Streaming Server." );

define( "ADMINST_ERR_NOSQL", "Your installer is missing some critical components." );

define( "ADMINST_ERR_DENIED_1", "- Sorry, but you cannot use this installer with the targetted copy of the Netjuke because it already is already live.\\n- Please use the upgrade process instead." );
define( "ADMINST_ERR_DENIED_2", "- This upgrade tool cannot be used with the targetted copy of the netjuke.\\n- The database was never generated (need to install)." );
define( "ADMINST_ERR_DENIED_3", "The given filesystem path does not appear to be valid." );
define( "ADMINST_ERR_DENIED_4", "Access denied: The provided admin user and password do not match any existing administrator account." );

define( "ADMINST_ERR_EXEC", "Problem executing part of the generated sql.\\nPlease try again." );

define( "ADMINST_FILEINFO_1", "Created" );
define( "ADMINST_FILEINFO_2", "From" );
define( "ADMINST_FILEINFO_3", "Save As" );
define( "ADMINST_FILEINFO_4", "after renaming the temporary install directory which is still" );
define( "ADMINST_FILEINFO_5", "Saved successfully. You can now access the new version at" );

##################################################

?>