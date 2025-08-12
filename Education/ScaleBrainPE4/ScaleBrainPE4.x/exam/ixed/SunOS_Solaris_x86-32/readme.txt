SourceGuardian 4.1 for PHP ixed loaders.

For SunOS Solaris Generic Intel 32-bit (IA32)
-----------------------------------------------------------------------
- This package is for SunOS Solaris Generic Intel 32-bit platform  (IA32)
and for other IA32 compatible platforms (IA64, AMD64) running SunOS Solaris
with PHP compiled as 32-bit executable (default PHP build).
- Please check that you are running 32-bit PHP and/or webserver by
executing the following command from the shell: 

> file /path/to/php
> file /path/to/httpd (where /path/to a real full path to executable)

This command will display information about executable file format. And you
will find either "32-bit" or "64-bit" in the output.
- If you are running CGI or CLI version of PHP it should be 32-bit
executable. Webserver may be any 32/64-bit
- If you are running PHP as shared object both PHP library and webserver
must be 32-bit executable.
- Unpack this package and follow SourceGuardian Loader installation
instructions.
-----------------------------------------------------------------------
