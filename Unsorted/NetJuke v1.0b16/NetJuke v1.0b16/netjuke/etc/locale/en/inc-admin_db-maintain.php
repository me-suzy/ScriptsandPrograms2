<?php

##################################################

# /admin/db-maintain.php

##################################################

define( "TFBCKP_CONFIRM", "Are you sure you want to proceed?" );

define( "TFBCKP_HEADER", "MUSIC DATABASE MAINTENANCE" );

define( "TFBCKP_BACKUP_START", "Music Database Backup" );
define( "TFBCKP_BACKUP_HELP", "Backs up all the music-related data (tracks, artists, etc.) in a text file. Files can be backups to be restored on-demand using the import utility, or downloaded and used to share data with other tools (Spreadsheet, etc.)." );
define( "TFBCKP_BACKUP_DONE", "View Backup File" );

define( "TFBCKP_MAINTAIN_START", "Maintain Music Database" );
define( "TFBCKP_MAINTAIN_HELP", "Utility to scan the entire music database, and delete the records if their related local file cannot be found. Files with a full URL (http://, rtsp://. etc.) will remain untouched. If an artist, album or genre is subsequently left without tracks, it will be deleted as well." );
define( "TFBCKP_MAINTAIN_DONE", "missing audio files have been found and have been deleted from the database." );

define( "TFBCKP_DELETE_START", "Clear All Music-Related Data" );
define( "TFBCKP_DELETE_HELP", "Deletes all the music-related data (tracks, artists, etc.). The users, their preferences and session data remains untouched. Playlists are deleted." );
define( "TFBCKP_DELETE_DONE", "All the music-related data has been cleared. The user records are still available." );

##################################################

?>