
------------------------------------------------------------------------
                NETJUKE TOOLKIT: Custom Development Tools
------------------------------------------------------------------------

The Netjuke Toolkit is a set of custom Open Source tools that ease the
tasks of the developers involved in the Netjuke Audio Streaming Jukebox.

The server-side components are powered by PHP 4, and an increasing choice
of databases. The interface is Web-based and can be accessed through a
Javascript & Cookies enabled browser. 

Please see the provided LICENSE.txt file for more information on the
BSD License governing this software and its related custom components.

Please see the provided CREDITS.txt document for more information on
the people and organizations who made, and are making, this project a
reality.


Provided Modules:

- SQL

  + sql-orig
  
    Original SQL for the netjuke database backend. Includes both the
    scripts used in the install process, and the ones involved in the
    upgrade process (by versions).
    
    Please keep in mind that some of the values in the provided data.sql
    scripts are meant to be searched and replaced by the installer.
  
  + db-delete_records.sql
  
    Deletes all the music related data, except for the mandatory "N/A"
    records for artists, albums and genre. Leaves all the user-related
    data untouched.
  
  + db-drop_tables.sql
  
    Simple sql script top drop the netjuke related tables in the 
    appropriate order as to satisfy the foreign key constraints.
  
  + db-drop_sequences.sql
  
    Same as above, but for databases that support sequences.

- PHP
  
  + release-cleanup.php
  
    Scans the given directory recursively for directories named CVS
    and files or dirs starting with a dot. Used to remove a ll the
    invisible files and CVS related data when releasing a new version.

  + locale-orig
  
    Contains the original locale files before being ran through the
    locale-translate.php script described below.
  
  + locale-translate.php
  
    Utility to help translating entire language packs to ASCII values
    that do not require dynamic html entities translation to be displayed
    in a web browser, when used in combination with the iso-8859-1 HTML
    charset. This tool is especially for translators who would like to
    increase the language base supported by the Netjuke. Simply run the
    script from a php enabled http server, and follow the provided
    inline instructions (see php source).

  + docs-text2html.php
    
    Utility to "convert" text files to html. The only thing done to
    each file is to wrap its content in an html <pre> tag, which helps
    the file to be displayed as intended in an html browser. Remember
    that the <pre> tag does not support word wrap. Simply run the
    script from a php enabled http server, and follow the provided
    inline instructions (see php source).
  
  + getid3-testbed
  
    ID3 check script, and sample MP3 and OGG files for testing purposes.
    Most of the binary content of the provided audio files has been
    stripped to keep the filesize extremely low (and the obvious...).


For the latest version of this software, see:

- Preferred Site: http://netjuke.sourceforge.net/
-  Official Site: http://netjuke.artekopia.org/
- Alternate Site: http://netjuke.tekartists.com/


If you need help with using or installing this software, please refer
to the web site(s) listed above to get in touch with the related user
and developer communities directly, through the provided public forums
and mailing lists. These are the best ways to get quick technical
support, or to get involved with the development of the Netjuke and
its related custom components.

For critical operations, we advise to get commercial support or
integration services from people actually involved in the development
of this software application (TekArtists, or other individuals and
organizations officially listed in the provided credits document).


Thank you for trying and/or using the Netjuke.

We hope you will enjoy this software as much as we do!

