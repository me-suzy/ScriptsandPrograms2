true's image gallery
version 0.08.1
(c) 2004 TrueLyFE, tpa ]at] toothpastealien : com
Licensed under the LGPL. Based off of code by tomchan [ta] hkstar (DOT) com.

Simple image gallery lister. Widely improved upon Image Gallery. Read the readme below
if you can't figure it out.

PLEASE, RENAME admin.php OR REMOVE IT WHEN YOU ARE DONE -
OTHERWISE PEOPLE COULD POTENTIALLY CREATE GALLERIES YOU DON'T WANT! Since 0.04 this is
even more important - as people can remove/reindex your existing galleries!


In this version:
 * Images seperated by pages
 * Constrain by height as well as or instead of width (width comes first, see config.php)
 * Per-gallery comment via commentfile in original picture directory (see config.php)
 * Information about image (resolution, size, etc)
 * View list/pictures in asceding/descending order 
 
Planned Features:
 * Hitcounting
 * Per-gallery configuration changes
 * Reduce dependency on JPEG images, allow other types
 * EXIF data 

Possible Features:
 * Multi-level image gallery based on existing directory tree (directory-based gallery)
 * Dynamic thumbnailing (autodetect and create thumbs, hash existing for authenticity,
    remove stale thumbs)



Changelog:
v0.08
 * can include or otherwise use in existing pages now
 * .1 - hosted through <img> tag in script-generated page possible now, allows for showing
    image data along with image, or along with an included page - will be made better later

v?.previous
 * nobody cares anymore