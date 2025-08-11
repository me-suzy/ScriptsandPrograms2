<?php
   // $Id: BE_publications.php,v 1.11 2005/03/17 18:40:08 mgifford Exp $
   /**
    * Displays information on strikes and lockouts
    *
    * @package     Back-End on phpSlash
    * @copyright   2002 - Mike Gifford
    * @version     $Id: BE_publications.php,v 1.11 2005/03/17 18:40:08 mgifford Exp $
    *
    */

   global $_BE, $_PSL, $auth;

   $pageTitle = 'News By E-Mail';
   $xsiteobject = 'Strikes Page';
   #Defines The META TAG Page Type

   require('./config.php');

   /* ****
    * MERGE WITH PSL 0.7 - 12Feb03
    * - page_open is now dealt with by config.inc
    * - setCurrentLangauge and be_setCurrentLocal are now dealt with by config.inc
    ** ***/

   $getRequestValue = getRequestValue(); //$section, $article);

   if (!empty($_GET['login'])) {
      $auth->login_if(!$perm->have_perm('user'));
   }

   /****************
    * INITIALISATION
    *****************/
   $ary['section'] = 'newsbyemail';

   // Required to clean the QUERY_STRING field in the template
   $ary['query'] = clean($_GET['query']);
   $ary['min'] = clean($_GET['min']);

   /****************
    * CONSTRUCT PAGE
    *****************/

   // This should eventually be moved to a class.
   $template = pslNew("slashTemplate", $_PSL['templatedir']);
   $template->set_file(array('publicationList' => 'BE_publicationList.tpl'));

   $db = & pslNew('BEDB');
   $db2 = & pslNew('BEDB');
   $error = '';
   $userID = $auth->auth['uid'];
   if ($_POST['Subscribe'] == 'save') {
      //  Delete any list memberships associated with this user
      $query = "DELETE FROM be_user2phplist WHERE authorID='$userID'";
      $db2->query($query);
      $query = "SELECT phpListUserID FROM be_user2phpuser WHERE beUserID='$userID'";
      $db->query($query);
      while ($db->next_record()) {
         $userExists = 1;
         $deleteMe = $db->Record['phpListUserID'];
         $query = "DELETE from phplist_listuser where userid='$deleteMe'";
         $db2->query($query);
      }

      $query = "SELECT id from phplist_list WHERE active='1'";
      $db->query($query);
      while ($db->next_record()) {
         $publicationID = $db->Record['id'];
         $subscribeEmail = '';
         $subscribeMember = 'subscribeMember_'.$publicationID;
         $subscribeMember = clean($_POST[$subscribeMember]);
         if ($subscribeMember) {
            $subscribeEmail = $auth->auth['uname'] . '@' . $_PSL['rootdomain'];
         }
         $subscribeOther = 'subscribeOther_'.$publicationID;
         $subscribeOther = clean($_POST[$subscribeOther]);
         if ($subscribeOther) {
            $subscribeCheck = 'subscribeAddress_'.$publicationID;
            $subscribeEmail = clean($_POST[$subscribeCheck]);
         }

         if ($subscribeEmail != "") {
            $phpUserID = "";
            $query = "SELECT id FROM phplist_user_user,be_user2phpuser WHERE be_user2phpuser.phpListUserID = phplist_user_user.id AND be_user2phpuser.beUserID='$userID' AND phplist_user_user.email='$subscribeEmail'";
            $db2->query($query);
            if ($db2->next_record()) {
               $phpUserID = $db2->Record['id'];
            } else {
               $phpUserID = generateID("phplist_user_user");
               $query = "INSERT INTO phplist_user_user(id,email,confirmed,htmlemail) VALUES('$phpUserID','$subscribeEmail','1','0')";
               $db2->query($query);
               $query = "INSERT INTO be_user2phpuser(beUserID,phpListUserID) VALUES('$userID','$phpUserID')";
               $db2->query($query);
            }

            $query = "INSERT INTO phplist_listuser(userid,listid) VALUES('$phpUserID','$publicationID')";
            $db2->query($query);
            $query = "INSERT INTO be_user2phplist(authorID,publicationID,phplistUser,subscribedAsMember,subscribedAsOther,subscribedAddress) VALUES('$userID','$publicationID','$phpUserID','$subscribeMember','$subscribeOther','$subscribeEmail')";
            $db2->query($query);
         }
      }
   }
   //  get each publication
   $template->set_block('publicationList', 'each_publication', 'pub_rows');
   $query = "SELECT id,name,description FROM phplist_list WHERE active='1' ORDER by listorder";
   $db->query($query);
   while ($db->next_record()) {
      $publicationName = $db->Record['name'];
      $publicationID = $db->Record['id'];
      $publicationDescription = $db->Record['description'];
      $query = "SELECT subscribedAsMember,subscribedAsOther,subscribedAddress FROM be_user2phplist WHERE publicationID = '$publicationID' and authorID = '$userID'";
      $db2->query($query);
      $subscribedMemberCheck = '';
      $subscribedOtherCheck = '';
      $subscribedAddress = '';
      if ($db2->next_record()) {
         if ($db2->Record['subscribedAsMember'] == '1') $subscribedMemberCheck = ' checked ';
         if ($db2->Record['subscribedAsOther'] == '1') $subscribedOtherCheck = ' checked ';
         if ($db2->Record['subscribedAsOther'] == '1') $subscribedAddress = $db2->Record['subscribedAddress'];
      }
      if ($subscribedAddress == '') {
         $subscribedAddress = $auth->auth['email'];
      }
      $template->set_var(array(
         'PUBLICATION_NAME' => $publicationName,
         'PUBLICATION_DESCRIPTION' => $publicationDescription,
         'PUBLICATION_ID' => $publicationID,
         'SUBSCRIBED_MEMBER_CHECK' => $subscribedMemberCheck,
         'SUBSCRIBED_OTHER_CHECK' => $subscribedOtherCheck,
         'SUBSCRIBED_ADDRESS' => $subscribedAddress ));
      $template->parse('pub_rows', 'each_publication', true);
   }
   $template->set_var(array(
   'SUBMIT_URL' => $_SERVER['PHP_SELF'] ));
   $content = $template->parse('OUT', 'publicationList');

   // If templates are defined, checks if they exist and formats them correctly
   $_BE['currentSection'] = $ary['section'];
   $sectionObj = pslNew('BE_Section');
   $breadcrumb = $sectionObj->breadcrumb($ary['section']);
   $sectionRec = $sectionObj->extractSection($ary['section']);
   $sectionTemplate = $sectionRec['template'];
   $chosenTemplate = getUserTemplates('', $sectionTemplate);

   // render the standard header
   $_PSL['metatags']['object'] = $xsiteobject;


   // generate the page
   generatePage($ary, $pageTitle, $breadcrumb, $content);

   page_close();

?>