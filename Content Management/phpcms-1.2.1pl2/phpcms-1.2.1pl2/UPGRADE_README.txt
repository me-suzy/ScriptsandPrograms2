/* $Id: UPGRADE_README.txt,v 1.1.2.6 2004/08/27 15:49:09 bjmg Exp $ */

_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/
                 Upgrading to phpCMS 1.2.x from phpCMS 1.1.x
_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/

These instructions assume that you have an older version of phpCMS running on
your server that you do not want to interfere with during upgrade. If you want
to make a fresh installation instead, please read the file "install.txt" in the
directory "/parser/readme/en".

(10 Steps to Success ;-))

   0. Unpack the ZIP file (phpcms-1_2_x.zip or phpcms-1_2_x.tar.gz) on
      your local machine.

   1. Rename the directory "/parser" that you now have on your computer into
      "/parser12x".

   2. Upload the complete directory and its contents to your server. You now
      have these two directories:
        /parser (your old phpCMS)
        /parser12x (new phpCMS 1.2.x)

   3. Your current settings and HTTP-Indexer profiles can be easily transferred
      to the new parser. To do so, _copy_ the two files "default.php" and
      "defaults_indexer.php" from the directory "/parser/include" into the new
      directory "/parser12x/include".

   4. Copy any plug-ins and scripts you are using from your old directory
      "/parser/plugs" into the new directory "/parser12x/plugs".

   5. Make sure that all new directories and files are given appropriate
      permissions. You will find the permissions necessary described in the
      file "install.txt" in the directory "/parser/readme/en".

   6. Login into the GUI with your old password:
       http://www.domain.test/parser12x/parser.php
      When you first access "Configuration" from the GUI menu, the file
      default.php is automatically converted, and new settings are added. The
      file defaults_indexer.php is not converted at this point, since its format
      has not changed.
      (Two new settings in version 1.2.x are automatically added when you edit
      your profiles.)

   7. Check all of your settings:
         1. Change all instances of "/parser" into "/parser12x".
         2. Turn off Stealth Mode for now.
         3. Read the section "Notes regarding various changes" below and make
            changes where necessary.

   8. Now you may check to see whether the new parser correctly outputs your
      pages:
      http://www.domain.test/parser12x/parser.php?file=/demo-en/index.htm .

   9. When you are satisfied with the results of your test, go back to
      "Configuration" in the GUI, and change all instances of "/parser12x" back
      to "/parser". If you are using Stealth Mode, turn in back on now.

  10. Rename your old and the new parser directory:
        /parser_old (your old phpCMS)
        /parser (new phpCMS 1.2.x)

All set and ready to go!


_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/
                       Notes regarding various changes
_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/


_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/
                                    PLUG-INS
_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/

    * Copy all of your plug-ins into the new plug-ins directory
      "/parser12x/plugs/"
    * Plug-in sXform:
         1. find:
            while (list($k,$v) = each($HTTP_GET_VARS))
         2. replace with:
            reset($HTTP_GET_VARS);
            while (list($k,$v) = each($HTTP_GET_VARS))
         3. find:
            while (list($k,$v) = each($HTTP_POST_VARS))
         4. replace with:
            reset($HTTP_POST_VARS);
            while (list($k,$v) = each($HTTP_POST_VARS))


_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/
                                  SCRIPTS
_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/

    * The Auto-Gallery script needs some adjustments when running in Secure
      Stealth-Mode:

    * In autogallery.inc.php:

         1. find:
            // create new query-string
            foreach($q as $key => $val ) {
            $str .= "$key=$val&";
            }
         2. replace with:
            // create new query-string
            foreach($q as $key => $val ) {
            // phpCMS 1.2.x safe-stealth-mode modification:
            if ($key != "file") {
            $str .= "$key=$val&";
            }
            }


_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/
                                        SEARCH
_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/

In version 1.2.0 both the stop words file and the file containing the indexed
words are stored in a different format. There are two things you may do:

   1. Best solution: re-index your site!
   2. 2nd best: open "words.db" and "stop.db" with a good text editor, and
      convert them all to lowercase.

    * New Feature: you may define <SEARCH_MIDDLE> in your tags file. This is put
      between <SEARCH_PREV> and <SEARCH_NEXT> on your search results page. If
      undefined this will be replaced with " " (a space). Have a look at the
      demo to see how this may be used.
    * New Feature: In the new file "nono.db" you may define search terms that
      are forbidden. If a visitor types one of these into your search, s/he will
      be presented with a special message.


_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/
                              Mail2Crypt SPAM Proofing
_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/

SPAM proofing, introduced in v1.1.9 as "PAX MailCrypt", has been re-implemented
independent of PAX. In order to use Mail2Crypt you no longer need to have
"parse PAX TAGS" activated.
Also, because of a collision of names, MailCrypt was renamed to "Mail2Crypt".

    * In future versions of phpCMS the tag identifier will change exclusively to
      "MAIL2CRYPT". In v1.2.x, however, you may still use "MAILCRYPT".
    * The JavaScript file was renamed to js_mail2crypt.js.
    * The tag <!-- MAILCRYPT include ---> is no longer needed and may be deleted.
      In order to include the JS file, the Parser automatically adds a <script>
      tag to the <head> of your pages where necessary.


_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/
                       Instructions for geeks (via symlinks)
_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/

Another way of updating phpCMS, is via "symlinks". This only works under
[Linux|Unix], of course. Here is a nice how-to from [TOM], but unfortunately
in German:
http://phpcms.de/forum/index.en.html?topic=1509.0
Feel free to ask in the forum, if you want to do this and have problems with it.