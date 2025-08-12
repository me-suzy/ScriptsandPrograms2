phpBackupMySQLdiff

Copyright (C) 2004 Thomas Pequet

License: GNU General Public License (GPL)

Homepage: http://phpbackupmysql.sourceforge.net
Download: http://sourceforge.net/project/showfiles.php?group_id=104823
Demo:     http://tpequet.free.fr/phpBackupMySQLdiff

----------------------------------------------------------------------------------------------------------------------------

phpBackupMySQLdiff is a Web Application to do MySQL differentials backup into XML files compressed ("zip" or or "tar" "tar.gz"): 
 - Just save the structure" and the "rows" changed
 - Restore the data into SQL queries
 - Select two date to restore the data
 - Add a filter (ex: "ID=123" OR "NAME LIKE toto") to filter the data restored

----------------------------------------------------------------------------------------------------------------------------
 
Hierarchy of folders     : /base/year+month/table/ 
Name of files compressed : day+hour+minutes+secondes+("data" or "structure")+"."+("zip" or "tar.gz" or "tar")

----------------------------------------------------------------------------------------------------------------------------

Requirements:
 - PHP4 (need module Zlib and module XML)
 - MySQL

----------------------------------------------------------------------------------------------------------------------------

Version 1.0 - March 2004
