# ==================================================================
# Links SQL - enhanced directory management system
#
#   Website  : http://gossamer-threads.com/
#   Support  : http://gossamer-threads.com/scripts/support/
#   CVS Info : 087,071,086,089,083 
#   Revision : $Id: README.php,v 1.20 2002/04/14 06:02:02 brewt Exp $
# 
# Copyright (c) 2001 Gossamer Threads Inc.  All Rights Reserved.
# Redistribution in part or in whole strictly prohibited. Please
# see LICENSE file for full details.
# ==================================================================

Links SQL PHP Front End README

System Requirements
--------------------------------------------------------
The Links SQL PHP Front End requirements are:

1. PHP version 4.02 or better.
2. track_vars must be on (with PHP v4.03 and later, it is always turned on).
3. register_globals preferably be turned off.
4. short_open_tag must be on (this should be on by default).
5. Appropriate PHP support for the database you will be using (eg. MySQL,
   Postgres, etc).
6. If this is running on a Windows server, a SMTP mail server must be used as
   PHP only supports the use of a SMTP mail server with Windows.  You can use
   either SMTP or sendmail with *nix systems.

Differences between the PHP version and Perl CGI version
--------------------------------------------------------
There are a few differences between the two versions.  The administration side of
Links SQL still uses the Perl CGI, but if you decide to use the PHP front end,
there are some differences you should know about:

1. Adding to multiple categories in one request is not supported.  It's currently
   available in the CGI version, but isn't in use, so this should not be a
   problem.
2. Editors are not supported.
3. Plugins are not supported. 
4. Searches are only available in non-indexed mode.  See the Links SQL manual
   on how this affects how searches are done.
5. The templates are different from the traditional Gossamer Threads templates.
   a) The PHP templates use PHP syntax instead of Gossamer Threads' templating
      system syntax.
      eg. <%foo%> bar
          in the PHP templates becomes:
          <?print $foo?> bar
   b) To use a particular template set, you call it by the 'regular' name
      (eg. default), but the PHP version of the templates are stored in the
      template directory as default_php.  The '_php' suffix is automatically
      added to the end of the template set.  This is done so that you can switch
      between the CGI and PHP versions without any changes to your configuration.
      The PHP templates will also share the same image directory as the regular
      CGI version (so you will not have to duplicate the images as well).
   c) In most cases, you can convert GT templates into PHP front end compatible
      templates by using the convert.pl script in the admin/Links/PHP directory.
      Note that to run this script, you need shell (telnet/ssh) access.
6. You cannot use Perl in the globals for any *_php templates.  Globals are done
   in PHP.  To create sub { }'s like in Perl, you now use create_function().  See
   the PHP manual (http://www.php.net/create_function) for more help.  To call it
   use $variablename() instead of just $variablename.  If you use just
   $variablename, you'll notice that it will output something like lambda_12
   instead of the proper output.
7. Note that you currently CANNOT build the PHP templates.  If you use the PHP
   front end, you can only do this dynamically (ie. not statically).
8. Modify does not have file support (but Add does).  This means, users can
   upload files when they add a link, but when they modify a link, they won't be
   able to change the file field.

