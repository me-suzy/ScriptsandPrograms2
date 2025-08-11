<?php
   /**
    *  Rewrite of the PET Petition Tools to work within a Back-End style
    * module framework.  Done by Ian Clysdale <ian@clysdale.ca> for
    * OpenConcept Consulting, based on phPetition developed by Mike
    * Gifford.
    */
   require('./config.php');

   //  Set Page Variables
   $ary = array();
   $ary['section'] = 'Admin';
   $pagetitle = pslgetText('Petition Administration');
   $xsiteobject = 'Administration';
   $chosenTemplate = getUserTemplates('', $ary['section']);

   $petitionAdmin = pslNew('BE_Petition_admin');
   $auth->login_if(!$perm->have_perm('action'));

   // $submit = pslgetText(getRequestVar('submit','PG'),'',true);
   $submit = decodeAction($_REQUEST);

   $petitionID = getRequestVar('petitionID','PG');

   $content = '';
   switch($submit) {
      case 'new':
         $content = $petitionAdmin->newPetition();
         break;
      case 'edit':
         $content = $petitionAdmin->newPetition($petitionID);
         break;
      case 'save':
         $content = $petitionAdmin->savePetition($petitionID, $_POST);
         break;
      case 'delete':
         $content = $petitionAdmin->deletePetition($petitionID);
         $content = $petitionAdmin->listPetitions($_GET);
         break;
      case 'exportParticipants':
         $content = $petitionAdmin->exportParticipants($petitionID, $_GET['mode']);
         if($_GET['mode']=='CSV') {
            Header('Content-Type: application/csv');
            Header('Content-Disposition: attachment; filename=petition.csv');
         }
         echo $content;
         die;
         break;
      case 'hideSignature':
         $petitionAdmin->hideSignature($petitionID, $_GET['contactID']);
         $content = $petitionAdmin->viewSignatures($petitionID, clean($_GET));
         break;
      default:
         $content = $petitionAdmin->listPetitions($_GET);
   }

   $breadcrumb = '<a href="' . $_PSL['rooturl'] . '/">' . pslgetText('Home') . '</a> &#187; ' . $pagetitle;

   generatePage($ary, $pagetitle, $breadcrumb, $content);

   page_close();
?>


