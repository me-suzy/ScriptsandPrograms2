<?php
   // $Id: gallery.php,v 1.13 2005/05/25 20:43:19 mgifford Exp $
   $pagetitle = 'Galleries';
   // The name to be displayed in the header
   $xsiteobject = 'Gallery Page';
   // This Defines The META Tag Object Type

   require('./config.php');

   # debug('BE_currentLanguage',$BE_currentLanguage);
   # debug('BE_default',$_BE['Default_language']);

   if (!empty($_GET['login'])) {
      $auth->login_if(!$perm->have_perm('user'));
   }

   // Objects
   // $articleObj = pslNew('BE_Article');
   $galleryObj = pslNew('BE_Gallery');
   $sectionObj = pslNew('BE_Section');

   $image = (!empty($image)) ? $image : null;
   $section = (!empty($section)) ? $section : null;
   $getRequestValue = getRequestValue($image, $section, $type = 'gallery');

   // store the section and article in an array to pass to the Block later on
   $ary['section'] = $getRequestValue['section'];
   $ary['image'] = $getRequestValue['article'];

   // display list of all galleries in all sections
   if ($ary['section'] == 'Home' OR (empty($ary['section']) AND empty($ary['image'])) OR (($ary['section'] == '/' AND $ary['image'] == '/')) OR ($ary['section'] == '' AND $ary['image'] == '') ) {


      $galleries = $galleryObj->getAllGalleriesInAllSections();
      $ary['section'] = 'Home';
   } else {
      // $storyInfo = $sectionObj->getSection($ary['section']);
      // generate the subsections
      // $subSectionInfo = $sectionObj->getSubSections($ary['section']);
      // render the list of galleries for this section
      $galleries = $galleryObj->getGalleriesForSection($ary['section'], $ary['image']);
      // returns global $spotlightImage
   }

   // get the section object

   $sectionRec = $sectionObj->extractSection($ary['section'], $BE_currentLanguage);
   if (empty($sectionObj)) {
      echo "Could not find section: '{$ary['section']}'";
   }

   $sectionID = $sectionRec['sectionID'];
   $sectionName = $sectionRec['title'];

   if ($ary['section'] != 'home') {
      $pagetitle = $sectionName;
   }

   if (empty($sectionName)) {
      $sectionName = $ary['section'];
   }

   $articleTemplate = (!empty($articleTemplate)) ? $articleTemplate : null;
   $sectionTemplate = (!empty($sectionTemplate)) ? $sectionTemplate : null;
   $chosenTemplate = getUserTemplates($articleTemplate, $sectionTemplate);



   // setup the template for the index page
   $tplfile = 'BE_bodyGalleries.tpl';
   $template = pslNew('slashTemplate', $_PSL['templatedir']);
   $template->debug = 0;
   $template->set_file('index', $tplfile);

   $todaysDate = psl_dateLong();
   /*
    $theDate = date (F);
    if ($BE_currentLanguage=='fr')
    $todaysDate = date ("j ") . pslgetText(date (F), $_PSL['classdir'] . '/locale/' . $BE_currentLanguage . '.php') . date (", Y");
    else
    $todaysDate = pslgetText(date (F), $_PSL['classdir'] . '/locale/' . $BE_currentLanguage . '.php') . date (" j, Y");
    */

   $storyInfo = (!empty($storyInfo)) ? $storyInfo : null;
   $subSectionInfo = (!empty($subSectionInfo)) ? $subSectionInfo : null;
   $galleries = (!empty($galleries)) ? $galleries : null;
   $spotlightImage = (!empty($spotlightImage)) ? $spotlightImage : null;
   $todaysDate = (!empty($todaysDate)) ? $todaysDate : null;

   if ($perm->have_perm('gallery') || $perm->have_perm('root')) {
      $adminUrl = $_PSL['adminurl'];
      $imageUrl = $_PSL['imageurl'];
      $newImage = "<br /><a href=\"$adminUrl/$_BE[galleryAdmin_file]?submit=new\" title=\"New Image\" onmouseover=\"document.newImage.src='$imageUrl/BE/buttons/btn_add_over.gif'\" onmouseout=\"document.newImage.src='$imageUrl/BE/buttons/btn_add_dim.gif'\"><img src=\"$imageUrl/BE/buttons/btn_add_norm.gif\" width=\"23\" height=\"23\" alt=\"New Image\" name=\"newImage\" /></a>\n\t";
      $helpGallery = "<a href=\"http://manual.back-end.org/index.php/GalleryAdmin\" title=\"Help\" onmouseover=\"document.Help.src='$imageUrl/BE/buttons/btn_info_over.gif'\" onmouseout=\"document.Help.src='$imageUrl/BE/buttons/btn_info_dim.gif'\" target=\"_blank\"><img src=\"$imageUrl/BE/buttons/btn_info_norm.gif\" width=\"23\" height=\"23\" alt=\"Help\" name=\"Help\" /></a>\n\t<br />";
   }

   $template->set_var(array(
      'STORY_COLUMN' => $storyInfo,
      'SUB_SECTION_INFO' => $subSectionInfo,
      'GALLERIES' => $galleries,
      'SPOTLIGHT_IMAGE' => $spotlightImage,
      'TODAYS_DATE' => $todaysDate,
      'NEW_GALLERY' => $newImage,
      'HELP_GALLERY' => $helpGallery
   ));

   $storyText = $template->parse('OUT', 'index');

   // render the standard header
   $_PSL['metatags']['object'] = $xsiteobject;

   /* I have no convenient way to get the current section and current article
    to my Blocks so they know how to generate themselves. The use of
    _BE['currentSection'] is a mere convenience given the time I've got to
    solve this problem.

    We need a better way to let blocks know for what section they're generating
    themselves. We also need a simple, well-defined interface to a general-
    purpose caching mechanism. Blocks could be deciding themselves when they
    really need updating, based on certain session state information that's
    globallay accessible. PSB 2002-08-28
    */

   # debug ('section',$ary['section']);

   $_BE['currentSection'] = $ary['section'];
   $sectionURLname = (!empty($sectionURLname)) ? $sectionURLname : null;
   $_BE['currentSectionURLname'] = $sectionURLname;
   $_BE['currentGallery'] = $ary['image'];

   // generate the page
   generatePage($ary, $pagetitle, '', $storyText);

   // close the page
   page_close();

?>