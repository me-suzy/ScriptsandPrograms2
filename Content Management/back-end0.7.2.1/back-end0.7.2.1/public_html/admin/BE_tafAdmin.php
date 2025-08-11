<?php
   /**
    *  Tell-A-Friend e-mail alert service, which allows e-cards and more
    * general mailing of Back-End stories, actions, and petitions, with
    * optional message text, images, and multiple versions available
    * for different stories/actions/petitions.

    * @author:  Ian Clysdale <iclysdale@cupe.ca>
    */
   require('./config.php');

   if (!empty($_GET['login'])) {
      $auth->login_if($_GET['login']);
   }

   $content = null;

   // Objects
   $tafObj = pslNew('BE_TAF_admin');
   $sectionObj = pslNew('BE_Section');

   //  Set page variables
   $ary['section'] = 'Admin';

   $pageTitle = pslgetText('Tell a friend');
   $xsiteobject = 'Tell A Friend';
   $breadcrumb = $sectionObj->breadcrumb($ary['section'], $pageTitle);

   if(!$perm->have_perm('taf')) {
      $content = pslgetText('You do not have the permissions to change the tell-a-friend messages.');
      return generatePage($ary, $pageTitle, $breadcrumb, $content);
   }

   //  Read the submit and petitionID variables.
   $submit = decodeAction($_POST);
   if(empty($submit)) {
      $submit = decodeAction($_GET);
   }

   $index = getRequestVar('index', 'G');
   $count = getRequestVar('count', 'G');
   $cardID = getRequestVar('cardID', 'G');

   switch($submit) {
      case 'save':
         $content = $tafObj->saveCard(clean($_POST));
         break;
      case 'new':
      case 'edit':
         $content = $tafObj->editCard($cardID);
         break;
      case 'delete':
         $content = $tafObj->deleteCard($cardID, $index, $count);
         break;
      default:
         $content = $tafObj->listCards($index, $count);
   }

   generatePage($ary, $pageTitle, $breadcrumb, $content);

   page_close();

?>
