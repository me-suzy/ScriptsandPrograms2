Last Modified: March 12th, 2002

----mod_l33t v1.0----

A fast, dynamic and limited virtual hosting module for Apache 1.3.x

This module has been tested on RedHat Linux 7.2 and Windows XP.

View the INSTALL file for instructions on how to install and configure mod_l33t.

----------------

Why does this module exist?

Apache's virtual hosting is both slow and memory intensive.  On the server at L33T.ca, with 6000 virtual hosts, each Apache thread used 64 MB of memory, and it took apache about 20 seconds to start up.  *Each virtual host took up about 10.5 kB of memory*. Reloading was just as hellish - if even one virtual host was changed the server would have to be reloaded, taking up to 20 seconds as well.  In addition, Apache often served pages slowly (probably due to the fact that there were 40 of those Apache death-threads running at any given moment).  Our server was dying horribly, crashing every few days because of the ridiculous memory usage.

This module, while castrating the functionality of virtual hosts, makes virtual hosting fast and memory efficient.  The server starts up instantly, pages are served instantly, and *each virtual host now takes up about 0.3 kB of memory*.  With 6000 virtual hosts each apache thread now uses 2.3 MB of memory.

Our hosted users have noticed the massive stability and speed increase, and are benefiting from it greatly.

If you need virtual hosting for just file redirection and user/group setting for suexec, I suggest you give apache virtual hosting the ol' boot and try out mod_l33t.  It's done wonders for L33T.ca.

----------------

Does this module really do virtual hosting?

It's better to think of this module as a file redirection layer that also sets the User + Group for suexec, cuz that's all it really is at the moment.  mod_l33t perfectly suits the needs of L33T.ca web-hosting, so it could be useful elsewhere.

In the future if there's a demand, this module could better replicate the functionality of virtual hosting at a fraction of the memory cost and with increased performance.

----------------

What makes this module so "dynamic"?  

The module configuration file is read in every time it is modified.  So you just edit the config file and the module will reload the configuration file automatically.  No server restart/reload!  The configuration loads in just milliseconds and is unnoticeable.

----------------

How is this module "limited virtual hosting"?

At L33T.ca we needed only the following directives for an effective virtual host:

ServerName
User
Group
DocumentRoot
Alias

The User and Group directives are neccessary for running scripts securely (suexec).  The ServerName, DocumentRoot, and Alias directives are neccessary for pointing Apache to the right file.

---------------

Compatibility-wise, are virtual hosts in this module as good as a true apache virtual host?

Hell no... for one thing, this module doesn't do real virtual hosting.  For an example of poor compatibility, take cgiwrap: we found that mod_l33t doesn't work with cgiwrap; however, in combination with suexec this module has cgiwrap's functionality.  mod_l33t is largely untested, so its compatibility with other modules is unknown.


---------------
---------------

Contact:

Post your bug reports, problems and questions in the L33T.ca support forums

http://forums.l33t.ca

---------------

Credits:

**L33T Kr3w**
- DethPigeon
- Mr.Oreo

http://www.L33T.ca
