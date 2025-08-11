<?php
   /**
    *  Tell-A-Friend e-mail alert service, which allows e-cards and more
    * general mailing of Back-End stories, actions, and petitions, with
    * optional message text, images, and multiple versions available
    * for different stories/actions/petitions.
    *
    *  TAF can take the following (GET) query arguments:
    *    - sectionID
    *    - articleID
    *    - actionID
    *    - petitionID
    *
    *  The program flow is as follows:
    *    - The user is asked to enter *their* personal information,
    *      and (if there's more than one e-card corresponding to
    *      the requested section/action/petition, they select which
    *      card they want to use).
    *    - They enter the e-mail addresses they want to send the message
    *      to, and can potentially customize the message (which in
    *      turn can already contain their name/email/so on, because
    *      we got that on the previous screen.)
    *    - They click "Send", it generates and sends the e-mails,
    *      and it sends them back to the page that they started from.
    *
    * @author:  Ian Clysdale <iclysdale@cupe.ca>
    */
   require('./config.php');

   if (!empty($_GET['login'])) {
      $auth->login_if($_GET['login']);
   }


   // Objects
   $tafObj = pslNew('BE_TAF');

   $petitionID = getRequestVar('petitionID','PG');
   $actionID = getRequestVar('actionID', 'PG');
   $sectionID = getRequestVar('sectionID', 'PG');
   $articleID = getRequestVar('articleID', 'PG');

   $content = '';
   $pageTitle = pslgetText('Tell a friend');

   if(isset($petitionID) && $petitionID!='') {
   	$petitionObj = pslNew('BE_Petition');
	   $chosenTemplate = getUserTemplates('', $petitionObj->getSectionTemplate($petitionID));
	   $ary['section'] = $petitionObj->getSectionName($petitionID);
	   $sectionObj = pslNew('BE_Section');
	   $breadcrumb = $sectionObj->breadcrumb($ary['section'], $pageTitle);
   //} elseif(isset($actionID)) {
   	// @TODO: I need to figure out how to do that for actions.
   } elseif (!empty($sectionID)) {

      //  Get the the sectionID if passed the section name.
      if(!is_numeric($sectionID)) {
         $sectionURLname = $sectionID;
         $sectionID = getSectionID($sectionID);
      }

   	$sectionObj = pslNew('BE_Section');
   	$sectionRec = $sectionObj->extractSection($sectionID, $BE_currentLanguage);
   	$chosenTemplate = getUserTemplates('', $sectionRec['template']);
      $ary['section'] = $sectionURLname;
      $breadcrumb = $sectionObj->breadcrumb($ary['section'], $pageTitle);
   }

   $xsiteobject = 'Tell A Friend';

//   $breadcrumb = $sectionObj->breadcrumb($ary['section'], 'Tell A Friend');

   //  Read the submit and petitionID variables.
   $submit = decodeAction($_POST);

   switch($submit) {
   	case 'send':
   	   $content = $tafObj->sendCard($_POST);
   	   break;
   	case 'select':
   	   $content = $tafObj->createCard($_POST);
   	   break;
      default:
         $content = $tafObj->selectCard($articleID, $sectionID, $actionID, $petitionID);
   }

   generatePage($ary, $pageTitle, $breadcrumb, $content);

   page_close();
?>


