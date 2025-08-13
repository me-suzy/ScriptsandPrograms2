<?
// The two following path settings point the script to the
// folder that contains your galleries.
//	
// $docroot is the full physical path to your document root.
// Use $HTTP_SERVER_VARS['DOCUMENT_ROOT'] here unless your
// images are located under a virtual or aliased directory,
// in which case you would enter the path to that directory.
$docroot = $HTTP_SERVER_VARS['DOCUMENT_ROOT'];

// $galleryroot is the path, relative to your document root,
// under which your gallery images reside - each folder with
// prepared images under this folder would represent a gallery
//
// For example, all your gallery folders would be under
// http://www.mydomain.com$galleryroot", and the concantation 
// of $docroot and $galleryroot would be the physical path 
// to this folder.
$galleryroot = "/galleries";

// Flag that indicates which images are thumbs.  All thumbnail images must have the same
// name as their larger counterpart, but with a common flag just before the file extension.
// You can specify what you want the flag to be here, your thumbs must then be named accordingly.
// (e.g. if $thumbFlag = "-01", the thumb for "image.jpg" would be "image-01.jpg")
$thumbflag = "-01";

// The default template used by the script if no template is specified in the query string
$defaulttemplate = "default";

// Admin username and password - login for admin.php.  You should protect the admin page.
// admin.php is an inefficient script that would drain resources quickly if you made it
// available to the public.  protect it, even if you don't mind people seeing all your galleries
$adminuser = "joe";
$adminpass = "shmoe";
?>
