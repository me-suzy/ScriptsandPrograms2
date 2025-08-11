<?php

   /* $id: storyAdmin.php,v 1.0 2000/04/25 12:08:03 ajay Exp $ */

   $pageTitle = 'Gallery Administration'; // The name to be displayed in the header
   $xsiteobject = 'Administration';       // Defines The META TAG Page Type

   require('./config.php');

   $ary = getRequestVar('ary','PG');
   $next = getRequestVar('next','PG');

   $galleryObj = pslNew('BE_Gallery');
   $content = null;

   /*****************************
    START OF PAGE
    *****************************/

   /* DEBUG */
   # debug( "HTTP_POST_VARS", $_POST);
   # debug( "HTTP_GET_VARS", $_GET);
   # debug( "section_del", $section_del );
   # debug( "section_ary", $section_ary );
   # debug( "description", $description );


   if ($perm->have_perm('gallery') || $perm->have_perm('root')) {
      $submit = pslgetText(getRequestVar('submit', 'PG'), '', true);
      $imageID = getRequestVar('imageID', 'PG');

      switch ($submit) {

         case 'delete':
         $galleryObj->deleteGallery($imageID);
         $storyInfo = $galleryObj->getGalleries();
         // $galleryObj->getGalleries($ary,$next);
         // Header("Location: " . $_PSL['adminurl']);
         break;

         case 'save':
         $success = $galleryObj->saveGallery(clean($_POST));
         if ($success == false) {
            // slashhead($pageTitle, $xsiteobject);
            $headerDisplayed = 'yes';
            $storyInfo = $galleryObj->newGallery(clean($_POST), 'array');
         } else {
            // print_r($_POST);
            $content .= $galleryObj->getGalleriesForSection(clean($_POST['sectionID']), clean($_POST['imageID']));
            // returns global $spotlightImage
            // echo "<a href=\"" . $_PSL['rooturl'] . "/gallery.php\">Sections</a>";
            // $storyInfo = $galleryObj->getGalleries();
            if (empty($imageID))
               $imageID = clean($_POST['imageID']);

            Header("Location: " . $_PSL['rooturl'] . "/" . $_BE['gallery_file'] . "/" . clean($_POST['subSectionID'][0]) . "/" . $imageID);
         }
         break;

         case 'modify':
         // slashhead($pageTitle, $xsiteobject);
         $headerDisplayed = 'yes';
         $storyInfo = $galleryObj->getGalleries($ary, $next);
         break;

         case "preview":
         // slashhead($pageTitle, $xsiteobject);
         $headerDisplayed = 'yes';
         $_POST['name'] = clean($_POST['author_id']);
         $galleryObj->showGallery(clean($_POST));
         titlebar('100%', 'Edit Gallery');
         $_POST['title'] = stripslashes(clean($_POST['title']));
         $_POST['intro_text'] = stripslashes(clean($_POST['intro_text']));
         $_POST['body_text'] = stripslashes(clean($_POST['body_text']));
         $storyInfo = $galleryObj->newGallery(clean($_POST), 'array');
         break;

         case 'edit':
         // slashhead($pageTitle, $xsiteobject);
         $headerDisplayed = 'yes';
         if (empty($_GET['imageID']))
            $_GET['imageID'] = clean($_POST['imageID']);

         $storyInfo = $galleryObj->newGallery(clean($_GET), 'database');
         break;

         case 'new':
         // slashhead($pageTitle, $xsiteobject);
         $headerDisplayed = 'yes';
         $storyInfo = $galleryObj->newGallery(clean($_GET), 'array');
         break;

         default:
         // slashhead($pageTitle, $xsiteobject);
         $headerDisplayed = 'yes';
         $adminUrl = $_PSL['adminurl'];
         $imageUrl = $_PSL['imageurl'];
         $newImage = "<br /><a href=\"$adminUrl/$_BE[galleryAdmin_file]?submit=new\" title=\"New Image\" onmouseover=\"document.newImage.src='$imageUrl/BE/buttons/btn_add_over.gif'\" onmouseout=\"document.newImage.src='$imageUrl/BE/buttons/btn_add_dim.gif'\"><img src=\"$imageUrl/BE/buttons/btn_add_norm.gif\" width=\"23\" height=\"23\" alt=\"New Image\" name=\"newImage\" /></a>\n\t";
         $helpGallery = "<a href=\"http://manual.back-end.org/index.php/GalleryAdmin\" title=\"Help\" onmouseover=\"document.Help.src='$imageUrl/BE/buttons/btn_info_over.gif'\" onmouseout=\"document.Help.src='$imageUrl/BE/buttons/btn_info_dim.gif'\" target=\"_blank\"><img src=\"$imageUrl/BE/buttons/btn_info_norm.gif\" width=\"23\" height=\"23\" alt=\"Help\" name=\"Help\" /></a>\n\t<br />";
         // $storyInfo = $galleryObj->getGalleries();
         $storyInfo = $galleryObj->getAllGalleriesInAllSections();
      }

   } else {
      titlebar('100%', 'Error! Invalid Privileges');
      $content .= 'Sorry. You do not have the necessary privilege to view this page.';
   }

   // setup the template for the index page
   $tplfile = 'BE_bodyGalleries.tpl';
   $template = pslNew('slashTemplate', $_PSL['templatedir']);
   // $template->debug = 1;
   $template->set_file('index', $tplfile);

   $ary['section'] = 'admin';
   $_BE['currentSection'] = $ary['section'];
   $chosenTemplate = getUserTemplates('', $ary['section']);

   // generate the output for the entire page
   $template->set_var(array(
      'ROOTDIR' => $_PSL['rooturl'],
      'IMAGEDIR' => $_PSL['imageurl'],
      'STORY_COLUMN' => $storyInfo,
      'NEW_GALLERY' => $newImage,
      'HELP_GALLERY' => $helpGallery
   ));

   $content = $template->parse('OUT', 'index');

   $ary['section'] = 'admin';

   $chosenTemplate = getUserTemplates('',$ary['section']);

   $breadcrumb = '<a href="' . $_PSL['rooturl'] . '/">' . pslgetText('Home') . '</a> &#187; ' . $pagetitle;

   // generate the page
   generatePage($ary, $pageTitle, $breadcrumb, $content);

   page_close();

?>