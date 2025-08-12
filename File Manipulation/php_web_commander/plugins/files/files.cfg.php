<?php

//
// +----------------------------------------------------------------------+
// | Web Manager                                                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 Protung Dragos (www.protung.ro)                   |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// |   filename             : files.cfg.php                               |
// |   begin                : 20 07 2004                                  |
// |   copyright            : (C) 2004 Dragos Protung                     |
// |   email                : dragos@protung.ro                           |
// |   lastedit             : 27/08/2004 01:09                            |
// |                                                                      |
// |                                                                      |
// +----------------------------------------------------------------------+
// | Author: Protung Dragos <dragos@protung.ro>                           |
// +----------------------------------------------------------------------+
//


// start directory
$START_DIR         = $DOCUMENT_ROOT;

// plugin name (as shown in the dropbox)
$PLUGIN_NAME_LONG  = 'File';

// short name
$PLUGIN_NAME_SHORT = 'files';

// show the size for directorys
// leave it as 'false' for many subdirectorys and many files (if you have over 5.000 in total)
define('SHOW_DIR_SIZE', false);

// minimum access levels needed by users
define('VIEW_LEVEL', 1); // to view files
define('EDIT_LEVEL', 1); // to edit files
define('COPY_LEVEL', 1); // to copy files
define('MOVE_LEVEL', 1); // to move files
define('DELETE_LEVEL', 1); // to delete files
define('MKDIR_LEVEL', 1); // to create new folders
define('DOWNLOAD_LEVEL', 1); // to download files
define('CHMOD_LEVEL', 1); // to CHMOD files
define('RENAME_LEVEL', 1); // to rename files
define('UPOAD_LEVEL', 1); // to upload files
define('EXEC_LEVEL', 1); // to execute commands

?>