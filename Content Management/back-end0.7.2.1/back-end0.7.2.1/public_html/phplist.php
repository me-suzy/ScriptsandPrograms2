<?php
   // $Id: phplist.php,v 1.4 2005/05/25 20:43:21 mgifford Exp $
   /**
    * Phplist interface
    *
    * Show mailing lists; provide subscribe/unsubscribe interface
    *
    * @package     Back-End on phpSlash
    * @copyright   2004 - Peter Bojanic
    * @version     $Id: phplist.php,v 1.4 2005/05/25 20:43:21 mgifford Exp $
    *
    * NOTE: This page is EXPERIMENTAL. It is not complete nor is the
    * current functionality completely debugged
    */

   global $_BE, $_PSL;
   $ary = Array();

   $pagetitle = 'Mailing Lists';
   // The name to be displayed in the header
   $xsiteobject = 'Mailing Lists';
   // This Defines The META Tag Object Type

   require('./config.php');

   /* ****
    * MERGE WITH PSL 0.7 - 12Feb03
    * - page_open is now dealt with by config.inc
    * - setCurrentLangauge and be_setSubsite are now dealt with by config.inc
    ** ***/

   if (!empty($_GET['login'])) {
      $auth->login_if($_GET['login']);
   }

   if (!$perm->have_perm("user")) {
         $storyText .= getTitlebar("100%", "Error! Invalid Privileges");
         $storyText .= getError(pslgetText("Sorry. You must be logged on to view this page."));
      generatePage($ary, $pagetitle, "", $storyText);
      // close the page
      page_close();
      exit;
   }

   // Objects
   $phplistObj = pslNew('BE_Phplist');
   $sectionObj = pslNew('BE_Section');
   $storyText = '';

   if (empty($ary['lnk_i'])) {
      $ary['lnk_i'] = 0;
   }
   if (empty($ary['lnk_n'])) {
      $ary['lnk_n'] = -1;
   }

   #error_reporting (E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

   // Calls function that pulls info from the url if it isn't included before hand
   //$getRequestValue = getRequestValue($link, $section, 'link');

   // store the section
   $ary['section'] = $_BE['default_section'];

   //debug('ary', $ary);

   $sectionRec = $sectionObj->extractSection($ary['section']);
   $sectionID = $sectionRec['sectionID'];
   $sectionName = $sectionRec['title'];
   $storyInfo = $sectionObj->getSection($sectionRec['sectionID']);

   //debug('BE_link', 'Getting sections');

   // get the section object
   $sectionRec = $sectionObj->extractSection($ary['section'], $BE_currentLanguage);
   if (empty($sectionRec)) $storyText .= getError(pslgettext('be_section_fetch_err') . "'$ary[section]'");

   $breadcrumb = $sectionObj->breadcrumb($ary['section'], 'Mailing Lists'); // PAC: was $breadcrumb_ary[]

   if (empty($sectionName))
      $sectionName = $ary['section'];

   $articleTemplate = (!empty($articleTemplate)) ? $articleTemplate : null;
   $sectionTemplate = (!empty($sectionTemplate)) ? $sectionTemplate : null;
   $chosenTemplate = getUserTemplates($articleTemplate, $sectionTemplate);

   // generate the output for the entire page

   // setup the template for the index page
   $template = pslNew('slashTemplate', $_PSL['templatedir']);
   $template->set_file('index', 'BE_phplist_user.tpl');
   #$template->debug = true;

   debug ('Current userid', be_getCurrentAuthorID());
   $lists = $phplistObj->getListsAsArray(be_getCurrentAuthorID());
   $email = be_getCurrentAuthorEmail();

   switch (clean($_GET['action'])) {

      case 'subscribe':
         $listid = clean($_GET['listid']);
         $phplistObj->subscribeUserToList(be_getCurrentAuthorID(), $listid, $email);
         $storyText .= "<br/>" . pslGetText("Subscribed to list") . " " . $listid;
         break;

      case "unsubscribe":
         $listid = clean($_GET['listid']);
         $phplistObj->unsubscribeUserFromList(be_getCurrentAuthorID(), $listid, $email);
         $storyText .= '<br/>' . pslGetText('Unsubscribed from list') . ' ' . $listid;
         break;
   } //swtich

   foreach ($lists as $listid=>$list) {
      debug('list id' , $listid);

      if ($list['entered']) {
         $link = "<a href='" . $_PSL['rooturl']. "/phplist.php?action=unsubscribe&listid=$listid'>" . pslgettext("Unsubscribe") . "</a>";
      } else {
         $link = "<a href='" . $_PSL['rooturl']. "/phplist.php?action=subscribe&listid=$listid'>" . pslgettext("Subscribe") . "</a>";
      }

      $template->set_var(array(
         'LIST_ID' => $listid,
         'LIST_NAME' => $list['name'],
         'LIST_DESCRIPTION' => $list['description'],
         'LINKS' => $link));
      $storyText .= $template->parse('OUT', "index");
   }

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
   $_BE['currentSection'] = $ary['section'];
   $_BE['currentSectionURLname'] = ''; // $sectionURLname; = not defined. PAC

   // generate the page
   generatePage($ary, $pagetitle, $breadcrumb, $storyText);

   // close the page
   page_close();

?>
