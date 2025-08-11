<?php
   // $Id: linkSubmit.php,v 1.9 2005/03/11 16:18:17 mgifford Exp $
   /**
    * Links
    *
    * Currently, link-administration is not aware of subsites, so must
    * be carried out by a superuser
    *
    * @package     Back-End on phpSlash
    * @copyright   2002 - Mike Gifford
    * @version     $Id: linkSubmit.php,v 1.9 2005/03/11 16:18:17 mgifford Exp $
    *
    */

   require('./config.php');

   $pagetitle = pslgetText('Submit a Link'); // The name to be displayed in the header
   $xsiteobject = 'Link Page';
   // Defines The META TAG Page Type

   $content = '';

   if (!empty($_GET['login'])) {
      $auth->login_if($_GET['login']);
   }

   if (empty($_POST['sectionID'])) {
      $requestValue = getRequestValue();
      $sectionObj = pslNew('BE_Section');
      $sectionRec = $sectionObj->extractSection($requestValue['section'], $BE_currentLanguage);
      $_POST['sectionID'] = intval($sectionRec['sectionID']);
   }
   $_POST['subSectionID'] = array(clean($_POST['sectionID']));

   $linkObj = pslNew('BE_Link_admin');

   $submit = pslgetText(getRequestVar('submit', 'PG'), '', true);
   $linkID = pslgetText(getRequestVar('linkID', 'PG'), '', true);

   # debug('articleID',$linkID);

   switch ($submit) {
      case 'preview':
      $content .= getTitlebar('100%', pslgettext('be_previews'));
      $_POST['name'] = clean($_POST['author_id']);
      $content .= $linkObj->showLink(clean($_POST));
      $content .= getTitlebar('100%', pslgettext('Edit'));
      $content .= $linkObj->generateSubmitNewLink(clean($_POST), 'array');
      $showList = false;
      break;

      case 'save':
      if ($linkObj->saveLink(clean($_POST), true)) {
         $content .= pslgettext('Link submitted.');
      } else {
         $content .= $linkObj->message;
         $content .= $linkObj->generateSubmitNewLink(clean($_POST), 'array');
      }
      break;

      default:
      $content .= $linkObj->generateSubmitNewLink(clean($_POST), 'array');
      break;
   }

   $_PSL['metatags']['object'] = $xsiteobject;

   // If templates are defined, checks if they exist and formats them correctly
   $chosenTemplate = getUserTemplates('', 'links');

   // generate the page
   generatePage($ary, $pagetitle, $breadcrumb, $content);

   page_close();

?>