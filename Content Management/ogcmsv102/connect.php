<?php
/*-----------------------------------------------------------------------------+
|                            OG CMS v1.02                                      |
+------------------------------------------------------------------------------+
| This file is component of OG CMS :: web management system                    |
|                                                                              |
|                    Please, send any comments, suggestions and bug reports to |
|                                                      olegu@soemme.no         |
| Original Author: Vidar Løvbrekke Sømme                                       |
| Author email   : olegu@soemme.no                                             |
| Project website: http://www.soemme.no/                                       |
| Licence Type   : FREE                                                        |
+------------------------------------------------------------------------------+ */
// Create database connection and select database 
mysql_select_db('database_name', mysql_connect('host','username','password'))
or die (mysql_error()); 
?>