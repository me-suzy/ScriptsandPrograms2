
Some time at the begining of '96, we moved our web server
off of Apache (running under Unix) to IIS 1.x.  Under unix,
user directories were set up prefixed with a tilde (~).
Also, all of our documents used the '.html' extension.

After setting up IIS, the user directories no longer
contain a tilde, and all of our pages end with '.htm'.  But the
overall directory structure for our web still remains the same.

Now, several months later, after looking at invalid urls in
our log files,  I saw that a lot of our old pages were still
being accessed:
	http://www.mycrowynn.com/~asolberg/
instead of:
	http://www.mycrowynn.com/asolberg/

The same goes for
	http://www.mycrowynn.com/~asolberg/Default.html
instead of:
	http://www.mycrowynn.com/~asolberg/Default.htm

So I wrote a very simple ISAPI filter to correct this.
All this filter does is to look for request containing
'\~' and/or '.html' and remove the '~' or change
the '.html' to '.htm'.

It also logs the invalid and corrected request and the
url's referer (if available) to a log file.  The log
file is c:\OldUnix.log.  This was, other pages can be
notified of the change.

Setting up
----------
1. Copy OldUnix.dll to an appropriate subdirectory, such as 
C:\InetPub\Filters subdirectory.
2. Update the Filter DLLs parameter of IIS. Run REGEDT32.EXE.
3. Add the full path of OldUnix.dll to  
HKEY_LOCAL_MACHINE\System\CurrentControlSet\Services\W3SVC\Parameters\Filter DLLs
(the DLLs are separated by commas)
4. Stop and Restart IIS.

To Do
-----
1. Add ability to control logging in the registry.
2. Include a message in HTML that the link has changed.

If you make any cool modifications or put some better features
in let me know at asolberg@mycrowynn.com.

Andrew Solberg
12/18/1996
