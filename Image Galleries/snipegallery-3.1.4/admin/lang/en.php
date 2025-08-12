<?php
$LANG_ADMIN_IMPORT_ZIP="Import Zipped Files";
$LANG_ADMIN_IMPORT_TXT="This utility will allow you to upload a .zip file containing multiple images and automatically import them into a specific gallery.  This can be very handy when you are working with large numbers of images.  The configuration file allows <b>".$cfg_max_import_munge." images to be automatically thumbnailed</b>.  If your zip file contains more than ".$cfg_max_import_munge." images, the remaining images will be imported but no thumbnails will be created. Descriptive fields are optional - if you enter values, those values will be applied to all of the images imported. ";

$LANG_ADMIN_IMPORT_1="Step One: Select Gallery & Upload File";
$LANG_ADMIN_IMPORT_2="Load images in";

$LANG_IMG_FIELD[0] = "Select File";
$LANG_IMG_FIELD[1] = "Photographer";
$LANG_IMG_FIELD[2] = "Location";
$LANG_IMG_FIELD[3] = "Description";
$LANG_IMG_FIELD[4] = "Keywords";
$LANG_IMG_FIELD[5] = "Photo Date";
$LANG_IMG_FIELD[6] = "Publish";
$LANG_IMG_FIELD[7] = "Image Title";
$LANG_IMG_FIELD[8] = "In Gallery";
$LANG_IMG_FIELD[9] = "Select File";
$LANG_IMG_FIELD[10] = "Generate thumbnails";
$LANG_IMG_FIELD[11] = "Use image file creation dates as photo date";
$LANG_IMG_FIELD[12] = "Upload and Import";
$LANG_IMG_FIELD[13] = "Uploading";
$LANG_IMG_FIELD[14] = "Filesize";
$LANG_IMG_FIELD[15] = "Added";
$LANG_IMG_FIELD[16] = "use crop tool";
$LANG_IMG_FIELD[17] = "From Fullsize Image";
$LANG_IMG_FIELD[18] = "generate thumbnail";
$LANG_IMG_FIELD[19] = "Dimensions";
$LANG_IMG_FIELD[20] = "Image Information";
$LANG_IMG_FIELD[21] = "Continue";
$LANG_IMG_FIELD[22] = "change image";
$LANG_IMG_FIELD[23] = "Thumbnail";
$LANG_IMG_FIELD[24] = "Yes";
$LANG_IMG_FIELD[25] = "No";

$LANG_ADMIN_FILE_IMPORTED="Files Imported";
$LANG_ADMIN_FILES_IMPORTED = "Your file (".$_FILES['userfile']['name'].") has been uploaded.  ".$total_files." files unpacked:";

$LANG_ADMIN_CURRENT_ALBUM="Current Album";
$LANG_ADMIN_THUMB_ACCEPT="Thumbnail Accepted";
$LANG_ADMIN_CROP_IMG="Crop Image";
$LANG_ADMIN_SEL_THUMB = "Select Thumbnail Image";
$LANG_ADMIN_SEL_THUMB_TXT = "To select a portion of this image to use as a thumbnail, move the yellow box over the part of the image that you wish to set as your thumbnail.  If you wish to change the size of the box, hold down the shift key while dragging the sides of the yellow box.  If you wish to constrain the proportion of the box to keep your thumbnails all the same size, click on the <b>Proportional</b> radio button and then resize the yellow box.";
$LANG_ADMIN_CROP_TXT = "To crop the image, hold down the shift key while dragging the sides of the yellow box.  If you wish to constrain the proportion of the box to keep your thumbnails all the same size, click on the <b>Proportional</b> radio button and then resize the yellow box.";
$LANG_ADMIN_THUMB_ACCEPT_TXT = "Your thumbnail has been generated and resized.  Review the new image below.  If you wish to select a new area of the original image, simply go back to the <b><a href=\"crop.php?gallery_id=".$_REQUEST['gallery_id']."&page=".$_REQUEST['page']."&image_id=".$_REQUEST['image_id']."\">thumbnail tool</a></b>.";

$LANG_ADMIN_CROPTOOL_NOTE="Note: If the yellow cropping box appears to the left of the image instead of over it, hit your browser's reload/refresh button to clear your cache.  This will usually resolve the issue.";

$LANG_ADMIN_CROPTOOL_ANY="Any Dimensions";

$LANG_ADMIN_CROPTOOL_PROPORT="Proportional";
$LANG_ADMIN_LABEL_SET_THUMB="Set Thumbnail";
$LANG_ADMIN_LABEL_SET_CROP="Crop Image";

$LANG_ADMIN_IMG_CROPPED="Image Cropped";
$LANG_ADMIN_IMG_CROPPED_TXT = "Your fullsize image has been cropped.  Review the new image below.  If you wish to select a new area of the original image, simply go back to the <b><a href=\"crop.php?gallery_id=".$_REQUEST['gallery_id']."&page=".$_REQUEST['page']."&image_id=".$_REQUEST['image_id']."&croptype=full\">crop tool</a></b>.";
$LANG_ADMIN_ADD_IMAGE="Add New Image";
$LANG_SEL_THUMB="Select Thumbnail";
$LANG_ADMIN_CREATE_ALBUM="Create Album";
$LANG_ADMIN_NO_ALBUMS="No albums or galleries have been created yet.  In order to add images, you must create at least one album with a gallery.";
$LANG_ADMIN_ADD_GALLERY="Add Gallery";
$LANG_ADMIN_DEL_GALLERY="Delete Gallery";
$LANG_ADMIN_ALBUM_TXT="Below are the image albums you have created in Snipe Gallery. To work with a gallery, simply click on the gallery name in the list below.";
$LANG_ADMIN_GALLERY_INTRO="To edit an image, click on the thumbnail.  To add a new image to this gallery,";
$LANG_ADMIN_GALLERY_DEL_CONFIRM="Are you SURE you wish to delete this image?";
$LANG_ADMIN_DEL="delete";
$LANG_ADMIN_NO_IMAGES_IN_GAL="There are no images listed in this gallery yet.  To add an image,";
$LANG_ADMIN_IMAGEEDIT_SAVED_HEAD="Image Edits Saved";
$LANG_ADMIN_IMAGEEDIT_SAVED_TXT='Your image edits have been saved.  <b><a href="image.php?image_id='.$_REQUEST['image_id'].'&gallery_id='.$_REQUEST['gallery_id'].'&page='.$_REQUEST['page'].'">View or edit this image again</a></b>, or <b><a href="view.php?gallery_id='.$_REQUEST['gallery_id'].'&page='.$_REQUEST['page'].'">return to the image gallery</a></b>.';

$LANG_GEN_VIEW_IMAGES_IN_GALLERY="View Images in";
$LANG_GEN_ALBUM="Album";
$LANG_GEN_CLICK_HERE="click here";
$LANG_GEN_CLICK_HERE_CAP="Click here";
$LANG_GEN_NO_TITLE="no title";
$LANG_GEN_UNTITLED="untitled";
$LANG_GEN_CREATED_ON="created on";
$LANG_GEN_NO_GALLERIES="no galleries";
$LANG_GEN_IMAGE_IN_GALLERY="There is <b>".$totalrows." image</b> posted";
$LANG_GEN_IMAGES_IN_GALLERY="There are <b>".$totalrows." images</b> posted";
$LANG_GEN_INVALID_PAGE_A="You have reached a page number that appears to be invalid.";
$LANG_GEN_INVALID_PAGE_B="to try the previous page, or";
$LANG_GEN_RETURN_TO_GAL="return to the gallery page";



$LANG_ERR_IMPORT_TEMPDIR="Temp directory could not be created.";
$LANG_ERR_ERROR="Error";
$LANG_ERR_IMAGE_HEAD="Error Adding Image";
$LANG_ERR_IMAGEEDIT_HEAD="Error Updating Image";
$LANG_ERR_IMPORT_NOIMG_HEAD="ERROR - No Images Found";
$LANG_ERR_IMPORT_NOIMG_TXT='Your selected file ('.$_FILES['userfile']['name'].')  does not appear to have any valid image files in it.  Please <a href="index.php">go back</a> and select a valid zip archive and try again.';
$LANG_ERR_IMPORT_FORMAT_HEAD="ERROR - Invalid File Format";
$LANG_ERR_IMPORT_FORMAT_TXT='Your selected file ('.$_FILES['userfile']['name'].')  does not appear to be a valid .zip archive.  Please <a href="index.php">go back</a> and select a valid zip archive and try again. </p><p>If you are using a mac and are attempting to upload a zip file, please make sure that your archive file has a file extension.';
$LANG_ERR_IMPORT_UNZIP_HEAD="Error Decompressing Zip";
$LANG_ERR_IMPORT_UNZIP_TXT = 'Your selected file ('.$dirname."/".$_FILES['userfile']['name'].') could not be decompressed.  please verify that it is an accepted zip file format and that your server is configured with an unzipping program.';

$LANG_ERR_IMAGE_TXT="Your image could not be copied to the server successfully.  Please check the your file paths and see the <B><a href=\"../settings/\">settings page</A></B> for more information.";

$LANG_ERR_IMG_TOPLVL='<b>You cannot add photos to top level albums</b>.  Please <b><a href="index.php">go back to the gallery listing page</a></b> and select a valid gallery.';
$LANG_ERR_NOIMAGE_HEAD="Error - No File Selected";
$LANG_ERR_NOIMAGE_CROP="You must specify the image ID that you wish to crop. ";
$LANG_ERR_NOIMAGE_TXT="Please go back and select an image to upload.";
$LANG_ERR_NO_GAL_ID="Error - no gallery ID specified.";
$LANG_ERR_GALLERIES="error retreiving galleries";
$LANG_ERR_DB_ERROR="A database error has occured.";
$LANG_ERR_FS_DEL_ERROR="Fullsize image could not be deleted - perhaps it has already been removed?";
$LANG_ERR_DEL_IMAGE_DB_ERROR="Image could not be deleted - A database error has occured";
$LANG_ERR_INVALID_FILETYPE_HEAD="ERROR: Invalid file type";
$LANG_ERR_INVALID_FILETYPE_TXT="Your image does not appear to be a valid gif, jpg, png or swf file. Please <b><a href=\"image.php?gallery_id=".$_REQUEST['form_gallery_id']."&add=new\">select an image</a></b> from your hard drive that is a valid gif, jpg or png.";
$LANG_ERR_INVALID_IMAGEID_HEAD="Invalid Image";
$LANG_ERR_INVALID_IMAGEID_TXT="The image you have requested appears to be invalid.  Perhaps it has already been deleted?";
$LANG_ERR_INVALID_FILETYPE="ERROR: Invalid file type";



$LANG_NAV_ADMIN="Administration Panel";
$LANG_NAV_EXPLAIN="Image Gallery Management Software";
$LANG_NAV_IMAGES="Images &amp; Galleries";
$LANG_NAV_FRAMES="Photo Frames";
$LANG_NAV_IMPORT="Import";
$LANG_NAV_SETTINGS="Settings";
$LANG_NAV_FAQ="FAQ";

$LANG_SUBNAV_VIEW_IMAGES="View Gallery Images";
$LANG_SUBNAV_ADD_IMAGES="Add Images to This Gallery";
$LANG_SUBNAV_DEL_GALLERY="Delete Gallery";
$LANG_SUBNAV_NEW_GALLERY="New Album";
$LANG_SUBNAV_EDIT_GALLERY="Edit Gallery";
$LANG_SUBNAV_EDIT_IMG="Edit Image";
$LANG_SUBNAV_THUMBTOOL="Thumbnail Tool";
$LANG_SUBNAV_CROPTOOL="Cropping Tool";
$LANG_SUBNAV_NEW_FRAMES="Create New Frame Set";
?>