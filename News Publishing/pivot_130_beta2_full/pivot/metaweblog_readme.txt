MetaWeblog API for Pivot.
Connor Carney (a.k.a mngeoff) <connor@rocksandpaper.com>

	This is an experimental script to add XMLRPC metaweblog support to Pivot. It is based on the MetaWeblog implementation from myPhpBlog (a.k.a. Simplog), and is licensed under the GPL.
	XMLRPC clients include desktop blogging clients such as WBloggar, as well as online services such a flickr.
	Currently, it is only capable of creating, modifying and deleting posts. Administrative and media functions must be performed from Pivot's web-based control panel.
I've tested it with Ecto and with Flickr's blogging feature.  Others have reported success with WBloggar, Archipelago and MarsEdit.


To install:

1) Place metaweblog.php in your Pivot folder (usually http://your.site/pivot/).  You do not need to make any modifications to metaweblog.php or to any Pivot files.

2) In your XMLRPC Client, enter the URL to metaweblog.php (i.e. http://your.site/pivot/metaweblog.php) as the "endpoint".  If your client asks for a "Blog ID" (or "Site ID"), enter a case-sensitive *category name* (i.e. "default" or "linkdump").


Notes:

--I've used the "Blog ID" field to identify categories rather than the category field, because it is the only way to support clients that don't send category information.  A side effect of this is that you cannot assign a post more than one category.  If anybody can think of a way to support the category field without breaking client software, please let me know.

--Pivot's "Ping update trackers" function doesn't work when posting via MetaWeblog, because the clients can't open Pivot's popup window.  However, most clients can be configured to ping update trackers directly.  Look through your client's preferences for such an option.

--Posts that are sent through MetaWeblog are published directly as raw HTML (no processing).  Most clients send raw HTML anyway, so this isn't usually a problem.  If you find your posts showing up without their formatting, you should be able to change this by searching for the line "$conversion_method=0" and changing the "0" to "1" for line breaks or "2" for textile.


Known Issues:
--Dates are not reported properly to XMLRPC clients.  They WILL show up properly on your posts.
--Administrative "superusers" can only edit their own posts.  You have to use the web interface if you need to edit others' posts.