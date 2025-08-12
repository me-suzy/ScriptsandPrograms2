PHPSitemap currently processes HTML and PDF files.
You can extend it by writing a new SitemapReader function
in SitemapReaders.php and adding a corresponding entry in 
the $smFileReaders array, located in CSitemapDirectory.php.

The file sitemapper.php has more information on usage
and the various output formats. Writing your own
output formatter (dubbed 'SitemapSaver') is simple.
Look in CSitemapSaver.php for some examples.

PHPSitemap allows you to change display format
and indexing rules on a per-directory basis.

It's pretty easy to achieve. All you do is save 
a config file into a branch of the directory tree
with the name "sm.conf," specifying exclusions, etc.

Here are the variables --

$SM_SORT_METHOD		- sort by [filename] or [title]?
$SM_SORT_ORDER		- sort [asc]ending or [desc]ending?
$SM_DIR_PLACEMENT	- list directories first [top] or after files [bottom]?
$SM_INDEX_SUBDIRS	- list contents in subdirectories [true] or not [false]?

[SM_PROCESS_FILE_TYPES]	- file types (extensions) to process
[SM_EXCLUDE_ENTRIES]	- subdirectories/files to ignore 
			- (relative to config file's location)

An example file --

-------------[COPY BELOW]

$SM_SORT_METHOD = title
$SM_SORT_ORDER = asc
$SM_DIR_PLACEMENT = top
$SM_INDEX_SUBDIRS = true

[SM_PROCESS_FILE_TYPES]
html
htm
php

[SM_EXCLUDE_ENTRIES]
js
img
images

-------------[COPY ABOVE]

Assuming you've unzipped these files on your server
in a directory named "sitemap," you can copy and paste 
the above into a file, save it to your document root
as "sm.conf," and run http://yourserver.com/sitemap/sitemapper.php.

That's it. You're done.

To change display/indexing rules on a per-directory basis --

Save the config file into the subdirectory in question,
change the variables, and run sitemapper.php again.

NOTES:

* If you delete a variable from a config file, it will be 
  inherited from the parent directory's config file.
  For this reason, make sure the document root's sm.conf
  has defined all variables.
  
* Directories will be ignored if an index file isn't found.
  PHPSitemap uses the $smIndexFilePriority array
  in CSitemapDirectory.php to determine index files.
  Update it according to your needs. As is, it will find
  index.html, index.htm, and index.php.

* pdfinfo.exe was ripped from the htDig distribution.

* Only tested this on Windows! Well, if you want to
  call it testing. It ran. Good enough for me.

* You can edit the code and redistribute it, but this file
  must go with it AS IS. That's all I ask. Thanks.
  
* Lastly, and most importantly, Temet Nosce! Know Thyself!  

  Free astral projection, self-knowledge, and journey to enlightenment courses
  http://mysticweb.org/

  A Treatise on Revolutionary Psychology
  http://www.gnosticweb.com/documents/EN_Revolutionary_Psychology.pdf

  The Great Rebellion
  http://www.gnosticweb.com/documents/EN_The_Great_Rebellion.pdf
  
  The Book of Thomas the Contender
  http://www.gnosis.org/naghamm/bookt.html
  
  The Gospel of Thomas
  http://www.gnosis.org/naghamm/gthlamb.html
  
  Dhammapada, Wisdom of the Buddha 
  http://www.theosociety.org/pasadena/dhamma/dham-hp.htm

  Bhagavad Gita
  http://www.sacred-texts.com/hin/gita/

  Lao Tzu: Tao Te Ching
  http://www.hm.tyg.jp/~acmuller/contao/laotzu.htm

  Heraclitus on the Logos
  http://www.fred.net/tzaka/logos.html
