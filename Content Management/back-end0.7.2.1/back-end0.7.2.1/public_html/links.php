<?php
   // $Id: links.php,v 1.21 2005/05/29 14:27:54 iclysdal Exp $
   /**
    * List of links
    *
    * Lists all links in given section, or all links in the site
    *
    * @package     Back-End on phpSlash
    * @copyright   2002 - Mike Gifford
    * @version     $Id: links.php,v 1.21 2005/05/29 14:27:54 iclysdal Exp $
    *
    */

   require('./config.php');

   $pagetitle = pslgetText('Links');   // The name to be displayed in the header
   $xsiteobject = pslgetText('Link Page'); // This Defines The META Tag Object Type
   $_PSL['metatags']['object'] = $xsiteobject; // render the standard header

   if (!empty($_GET['login'])) {
      $auth->login_if($_GET['login']);
   }

   $storyText = $content = '';

   // Objects
   $linkObj = & pslNew('BE_Link');
   $sectionObj = & pslNew('BE_Section');

   if (isset($ary['lnk_i']) && empty($ary['lnk_i'])) {
      $ary['lnk_i'] = 0;
   }
   if (isset($ary['lnk_n']) && empty($ary['lnk_n'])) {
      $ary['lnk_n'] = -1;
   }

   $section = (!empty($section)) ? $section : null;
   $getRequestValue = getRequestValue($section, '', 'link');

   // store the section and article in an array to pass to the Block later on
   $ary['section'] = $getRequestValue['section'];
   $ary['link']    = $getRequestValue['link'];

   $ary['orderby'] = getRequestVar('orderby','G');
   $ary['dir']     = getRequestVar('dir','G');
   $ary['lnk_i']   = getRequestVar('lnk_i','G');
   $ary['lnk_n']   = getRequestVar('lnk_n','G');
   $ary['search']  = getRequestVar('search','G');

   # debug('ary', $ary);

#   if ((empty($ary['section']) || $ary['section'] == $_BE['default_section']) && empty($ary['link'])) {
   if ((empty($ary['section']) || ($ary['section_id'] == $_PSL['home_section_id'])) && empty($ary['link'])) {

      // Generate full directory of Links
      $links = $linkObj->generateLinkIndex('', $ary['lnk_i'], $ary['lnk_n'], $ary['orderby'], $ary['dir'], $ary['search']);

      $ary['section'] = $_BE['default_section'];
      $ary['section_id'] = $_PSL['home_section_id'];

   } else {
      $sectionRec = $sectionObj->extractSection($ary['section']);

      $sectionName = $sectionRec['title'];
      $sectionOrderby = (isset($sectionRec['orderby'])) ? $sectionRec['orderby'] : NULL;
      $sectionLogic = (isset($sectionRec['ascdesc'])) ? $sectionRec['ascdesc'] : NULL;

      $newAscDesc = ($sectionLogic == 'asc' || $sectionLogic == 'ASC') ? 1 : -1;
      $dir = isset($_GET['dir']) ? clean($_GET['dir']) : $newAscDesc;

      // Generate Section page, with Links block underneath

      // PAC: The output here is very similar to what's produced by index.php. Is it unneeded duplication?
      # debug('BE_link section', 'not empty');
      $storyInfo = $sectionObj->getSection($sectionRec['sectionID']);

      $param['section']         = $sectionRec['sectionID'];
      $param['first']           = (isset($_GET['sec_i']) && !empty($_GET['sec_i'])) ? clean($_GET['sec_i']) : 0;
      $param['count']           = (isset($_GET['sec_n']) && !empty($_GET['sec_n'])) ? clean($_GET['sec_n']) : -1;
      $param['orderby']         = $sectionOrderby;
      $param['logic']           = $newAscDesc;
      $param['type']            = 'link';
      $param['sectionTemplate'] = $template;
      $param['parentURLname']   = $sectionURLname;

      $subSectionInfo = $sectionObj->getSubSections($param); // generate the subsection list

      $links = $linkObj->generateLinkIndexForSection($sectionRec['sectionID'], $ary['lnk_i'], $ary['lnk_n'], '', $sectionOrderby, $dir);
      // render the list of links for this section

   }

   # debug('BE_link', 'Getting sections');

   // get the section object
   debug('lang',$BE_currentLanguage);
#   $sectionRec = $sectionObj->extractSection($ary['section_id'], $BE_currentLanguage);
   $sectionRec = $sectionObj->extractSection($ary['section_id']);
# debug('$sectionRec.count',count($sectionRec));
   if (empty($sectionRec)) $storyText .= getError(pslgettext('be_section_fetch_err') . "'$ary[section]'");

// Have home section at top of links path too
#   $breadcrumb = $sectionObj->breadcrumb($ary['section_id'], 'Links', 'link');
   $breadcrumb = $sectionObj->breadcrumb($ary['section_id'], 'Links', 'index');

   if ($ary['section'] != $_BE['default_section'] && !empty($sectionName)) {
      $pagetitle = $sectionName;
   }

   if (empty($sectionName)) {
      $sectionName = $ary['section'];
   }

   // If templates are defined, checks if they exist and formats them correctly
   // (requires register_globals = On)
   # debug('BE_link', 'TidyUp');

   // if ($BE_currentLanguage == 'fr') {$todaysDate = date ('j ') . pslgetText(date('F')) . date (', Y');
   // } else { $todaysDate = pslgetText(date('F')) . date (' j, Y');}

   $todaysDate = psl_dateLong();

   // setup the template for the index page
   $template = pslNew('slashTemplate'); //, $_PSL['templatedir']);
   $template->set_file('index', 'BE_bodyLinks.tpl');

   // $template->debug = true;

   $storyInfo = (!empty($storyInfo)) ? $storyInfo : null;
   $subSectionInfo = (!empty($subSectionInfo)) ? $subSectionInfo : null;

   $template->set_var(array(
      'STORY_COLUMN'     => $storyInfo,
      'SUB_SECTION_INFO' => $subSectionInfo,
      'LINKS'            => $links,
      'TODAYS_DATE'      => $todaysDate
   ));
   $storyText .= $template->parse('OUT', 'index');


   // If templates are defined, checks if they exist and formats them correctly
   $chosenTemplate = getUserTemplates('', 'links');

   # debug('BE_Link ary', $ary);

   $_BE['currentSection'] = $ary['section'];
   $_BE['currentSectionURLname'] = ''; // $sectionURLname; = not defined. PAC
   $_BE['currentLink'] = $ary['link'];

   /*
   // ian, may29/05 -- why does this exist?  if the section is the default
   // section, blocks will show up -- if there is no section called Links,
   // nothing will.  I'm commenting this out for now.  If nobody restores
   // it with a good reason in the next few weeks, it should be deleted from
   // the code.
   if ($ary['section'] == $_BE['default_section']) {
      $ary['section'] = 'Links';
   }
   */

   // generate the page
   generatePage($ary, $pagetitle, $breadcrumb, $storyText);

   // close the page
   page_close();
?>
