==================================================================
Snippet-title:          Hardened BBClone v0.1 (10th October 2005)
Creator:                Hans Fredrik Nordhaug
E-mail:                 hans@nordhaug.priv.no
Creation date:          10th October 2005
License:                GPL
==================================================================

This is based on hardened referers writen by TheMarco.  Thanks,
TheMarco, for your contributions to the Pivot community.

This snippet protects your BBClone stats from referer spam. It works
by only adding data to BBClone if javascript and image loading is
enabled. In addition the referer is tested against the Pivot Blacklist
(if it's installed). It is a replacement for the bbclone_counter
snippet. Use it in the body (important) of your templates by including

    [[hardened_bbclone]]

or if you want to give it an explicit reference (to appear in your
BBClone stats) use

    [[hardened_bbclone:text]]

In the text you can use "%weblogtitle%" and "%title%" which will be
replaced by the blog's title or the blog entry's title, respectively.

Hardened BBClone should at least be used in the frontpage templates
since most referer spammers attack the frontpage. You can also use it
in the archive page template. If you want to use in the entry page
template, you should remove the hard-coded bbclone code:
----
if (!(defined('_BBC_PAGE_NAME'))) {
        [...]
        define("COUNTER", _BBCLONE_DIR."mark_page.php");
        if (is_readable(COUNTER)) { include_once(COUNTER) ; }
}
----

The snippet is written for Pivot 1.30 beta1 or better. If you 
want to use it with Pivot 1.24, you need to install Pivot Blacklist 
and change "hardened_refs/getkey.php" to "snippets/getkey.php" in
"snippet_hardened_bbclone.php".

That's it - enjoy.

ROUGH CHANGELOG:
10.10.2005 - v0.1: Initial release 
