SourceGuardian 4.1 for PHP ixed loaders.


For SunOS Solaris x86-64-bit
-----------------------------------------------------------------------
- This package is for SunOS Solaris x86-64-bit platforms
if PHP is compiled and running as 64-bit executable or 64-bit shared
object. Please note that default PHP build is 32-bit and if you didn't
compile it manually for 64-bit you possibly don't need this package.
- Please check that you are running 64-bit PHP and/or webserver by
executing the following command from the shell: 

> file /path/to/php
> file /path/to/httpd (where /path/to a real full path to executable)

This command will display information about executable file format. And you
will find either "32-bit" or "64-bit" in the output. Use this package only
if there is "64-bit" in the output.
- If you are running CGI or CLI version of PHP it should be 64-bit
executable. Webserver may be any 32/64-bit
- If you are running PHP as shared object both PHP library and webserver
must be 64-bit executable.
- Unpack this package and follow SourceGuardian Loader installation
instructions.
-----------------------------------------------------------------------