<?php
/*
+----------------------------------------------+
|                                              |
|      PHP example apache log parser class     |
|                                              |
+----------------------------------------------+
| Filename   : example.php                     |
| Created    : 21-Sep-05 23:28 GMT             |
| Created By : Sam Clarke                      |
| Email      : admin@free-webmaster-help.com   |
| Version    : 1.0                             |
|                                              |
+----------------------------------------------+


LICENSE

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License (GPL)
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

To read the license please visit http://www.gnu.org/copyleft/gpl.html

*/

include 'apache-log-parser.php';

$apache_log_parser = new apache_log_parser(); // Create an apache log parser

if ($apache_log_parser->open_log_file('example.log')) // Make sure it opens the log file
{
  while ($line = $apache_log_parser->get_line()) { // while it can get a line
    $parsed_line = $apache_log_parser->format_line($line); // format the line
    print_r($parsed_line); // print out the array
  }
  $apache_log_parser->close_log_file(); // close the log file
}
else
{
  echo 'Sorry cannot open log file.';
}
?>