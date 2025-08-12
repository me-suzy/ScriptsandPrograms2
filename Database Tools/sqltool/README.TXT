GreenLight SQLTool

Version: 1.0
Created: 28/05/2004

Freeware
(c) 2004 by Marco Vervoort, GreenLight Solutions
URL: http://www.greenlightsolutions.nl/
Email: marco@greenlightsolutions.nl

ABOUT
=====

This tool consists of two files, one to execute queries submitted by
form or uploaded as a file and display the results, and another to
dump the database in MySQL or MSSQL format. The scripts can be used
with either MySQL or MSSQL databases.  Together they can be used to
migrate a database (or at least the table structures and contents)
from MySQL to MSSQL or vice versa.

I wrote these two scripts to assist with the porting of our CMS system
from MySQL to MSSQL. If you find these scripts useful, please drop me
a note.

FEATURES
========
- execute all kind of queries
- shows error messages and number of affected rows
- show resultsets as tables
- execute multiple SQL statements, seperated by ;
- cross-platform database dumps
- dump structure, contents and full-text indices of tables
- add RECONNECT commands in dumps to split dumps into batches that
  MSSQL/WindowsNT can handle.

REQUIRES
========
PHP 4.x
PEAR DB library (a recent version implementing $db->quoteSmart(); I use 1.58)
MySQL or MSSQL Database

INSTALL
=======
Just put the files in a directory accessible to your web-server

USAGE
=====
Open the file 'showquery.php' in your browser.
In the top table, you can enter your connection details.
These will be remembered from query to query.
In the window below, you can type in your query (clear the box using
the 'Clear' button), or select a file containing the queries that you
want to run. Press 'Execute Query'.

In the bottom area you can specify the parameters for creating a
dump of the database:
 - you can specify that the dumped database should be dumped to a file,
   rather than displayed in a window
 - you can specify that the dumped database should be in MySQL or MSSQL
   format, rather than its original format
 - you can specify a regular expression denoting the tables whose contents
   should be dumped (by default, all tables have their contents are dumped).
   For tables not matching the regex, only the structure is dumped.
 - You can specify that a RECONNECT command should be added every N queries.
   This is useful for porting to Windows NT systems, where due to a
   bug in the System Heap handling large batches of queries can crash
   the server
 - You can specify that 'DROP TABLE' commands should be added for each
   table that is created.
 - You can specify that commands should be added to recreate the full-text
   search indices. Note that to create full-text search indices on
   MSSQL servers requires the MSSearch extension to be installed.

To migrate a database, install the scripts on both servers.  On one
server, make a dump-to-file, specifying that the dump should be made
in the SQL dialect of the other server. Then, on the other server,
create the new database and use the showquery.php script to execute
the queries contained in the dumpfile you created.


DISCLAIMER
==========
You may freely use or adapt this script. However, I'm not responsible
for what you do with your databases.
