<?php
   /**
    *  Rewrite of the PET Petition Tools to work within a Back-End style
    * module framework.  Done by Ian Clysdale <ian@clysdale.ca> for
    * OpenConcept Consulting, based on phPetition developed by Mike
    * Gifford.
    */
   require('./config.php');

   if (!empty($_GET['login'])) {
      $auth->login_if($_GET['login']);
   }

   $content = '';

   // Objects
   $petitionObj = pslNew('BE_Petition');
   $sectionObj = pslNew('BE_Section');

   //  Read the submit and petitionID variables.
   $submit = pslGetText(getRequestVar('submit','PG'),'',true);
   $petitionID = getRequestVar('petitionID','PG');
   if(empty($petitionID)) {
      //  We allow the short form p to give us shorter verify URLs, but when
      // they click on the verify URL we want them to be in the right section.
      $petitionID = getRequestVar('p', 'PG');
   }
   // If it exists, then people have called a petition, and we want that one.
   $urlAry = getRequestValue();
   if(isset($urlAry['section']) && !empty($urlAry['section']) && strtolower($urlAry['section']) != $_BE['default_section'] && $urlAry['section'] !=$BE_subsite['URLname']) {
      if(!isset($submit) || empty($submit)) {
         $submit = 'viewPetition';
      }
      $p = $petitionObj->getPetitionID($urlAry['section']);
      if($p>0) {
         $petitionID = $p;
      }
   }


   // Section is defined in config.php if it can be read from the URL.
   //  Set Page Variables
   if(isset($petitionID)) {
      $pageTitle = $petitionObj->getPetitionTitle($petitionID);
      $ary['section'] = $petitionObj->getSectionName($petitionID);
   }
   if(empty($ary['section'])) {
      $ary['section'] = $_BE['Petition_section'];
   }

   //  In subsites, we can't count on the petition section existing,
   // so we'll just set it to the home section.
   if(@$_PSL['module']['BE_Subsite'] && be_inSubsite()) {
      $ary['section'] = $BE_subsite['URLname'];
   }

   $petitionID = (isset($petitionID)) ? $petitionID : null;

   $chosenTemplate = getUserTemplates('', $petitionObj->getSectionTemplate($petitionID));
   $_BE['primaryTemplate'] = $petitionObj->getSectionTemplate($petitionID);

   if(empty($pageTitle)) {
      $pageTitle = pslGetText('Petitions');
   }
   $xsiteobject = 'Petitions';

   $breadcrumb = $sectionObj->breadcrumb($ary['section'], 'Petitions', 'petitions');

   switch($submit) {
      case 'viewPetition':
         $content = $petitionObj->viewPetition($petitionID);
         break;
      case 'signPetition':
         $_POST['petitionID'] = $petitionID;
         $content = $petitionObj->signPetition($petitionID, clean($_POST));
         break;
      case 'inviteFriends':
         $content = $petitionObj->inviteFriends($petitionID);
         break;
      case 'sendToFriends':
         $_POST['petitionID'] = $petitionID;
         $content = $petitionObj->sendToFriends($petitionID, clean($_POST));
         break;
      case 'viewSignatures':
         $_GET['petitionID'] = $petitionID;
         $content = $petitionObj->viewSignatures($petitionID, clean($_GET));
         break;
      case 'verify':
         $_GET['petitionID'] = $petitionID;
         $content = $petitionObj->verifySignature($petitionID, clean($_GET));
         break;
      default:
         $content = $petitionObj->listPetitions($_GET);
   }

   generatePage($ary, $pageTitle, $breadcrumb, $content);

   page_close();
?>


