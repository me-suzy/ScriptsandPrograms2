<?php
   // $Id: BE_feedback.php,v 1.22 2005/06/13 15:15:22 mgifford Exp $
   /**
    * Search functionality business logic
    *
    * @package     Back-End on phpSlash
    * @copyright   2002 - Mike Gifford
    * @version     $Id: BE_feedback.php,v 1.22 2005/06/13 15:15:22 mgifford Exp $
    *
    */

   require('./config.php');

   $pageTitle = pslgetText('Send Feedback');
   $xsiteobject = 'Feedback Page'; // Defines The META TAG Page Type
   $_PSL['metatags']['object'] = $xsiteobject; // render the standard header


   if (!empty($_GET['login'])) {
      $auth->login_if($_GET['login']);
   }

   // Objects
   $sectionObj = pslNew('BE_Section');

   /****************
    * INITIALISATION
    *****************/
   $ary['section'] = (!isset($BE_subsite) || $BE_subsite['sectionID']==0) ? $_BE['FeedbackSection'] : $_BE['FeedbackSection'] . $BE_subsite['sectionID'];
   if (isset($_GET['debug']) && $_GET['debug']=='yes') { echo "{$ary['section']}"; }
   // Required to clean the QUERY_STRING field in the template
   $ary['query'] = clean(@$_GET['query']);
   $ary['min'] = clean(@$_GET['min']);

   /****************
    * CONSTRUCT PAGE
    *****************/

   $content = '';

   $feedbackName = 'feedbackName';
   $feedbackCity = 'feedbackCity';
   $feedbackIsMember = 'feedbackIsMember';
   $feedbackLocal = 'feedbackLocal';
   $feedbackKnowsMember = 'feedbackKnowsMember';
   $feedbackEmail = 'feedbackEmail';
   $feedbackComments = 'feedbackComments';
   $feedbackReferer = 'feedbackReferer';

   $submit = decodeAction($_REQUEST);

   if ($submit == 'send') {
      //  Read submitted variables
      $submittedName = (isset($_POST[$feedbackName]) && !empty($_POST[$feedbackName])) ? addslashes(clean($_POST[$feedbackName])) : null;
      $submittedCity = (isset($_POST[$feedbackCity]) && !empty($_POST[$feedbackCity])) ? addslashes(clean($_POST[$feedbackCity])) : null;
      $submittedIsMember = (isset($_POST[$feedbackIsMember]) && !empty($_POST[$feedbackIsMember])) ? clean($_POST[$feedbackIsMember]) : null;
      $submittedLocal = (isset($_POST[$feedbackLocal]) && !empty($_POST[$feedbackLocal])) ? addslashes(clean($_POST[$feedbackLocal])) : null;
      $submittedKnowsMember = (isset($_POST[$feedbackKnowsMember]) && !empty($_POST[$feedbackKnowsMember])) ? clean($_POST[$feedbackKnowsMember]) : null;
      $submittedEmail = (isset($_POST[$feedbackEmail]) && !empty($_POST[$feedbackEmail])) ? addslashes(clean($_POST[$feedbackEmail])) : null;
      $submittedComments = (isset($_POST[$feedbackComments]) && !empty($_POST[$feedbackComments])) ? addslashes(clean($_POST[$feedbackComments])) : null;
      $submittedReferer = (isset($_POST[$feedbackReferer]) && !empty($_POST[$feedbackReferer])) ? clean($_POST[$feedbackReferer]) : null;

      //  Read browser and user information
      $submittedBrowser = $_SERVER['HTTP_USER_AGENT'];
      $submittedUserIP = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : null;
      $submittedRemoteHost = (isset($_SERVER['REMOTE_HOST'])) ? $_SERVER['REMOTE_HOST'] : null;
      $submittedTime = time();

      //  Open up a new DB connection
      $db = pslNew('BEDB');
      $id = generateID('feedback');

      $query = "INSERT INTO {$_BE['FeedbackTable']} (id, SubmitterName, SubmitterEmail, Location, ReferringPage, CupeMember, CupeLocal, KnowsCupeMember, Comments, Browser, UserIP, RemoteHost,TimeSubmitted, subsite_id) VALUES ($id, '$submittedName', '$submittedEmail', '$submittedCity', '$submittedReferer', '$submittedIsMember', '$submittedLocal', '$submittedKnowsMember', '$submittedComments', '$submittedBrowser', '$submittedUserIP', '$submittedRemoteHost','$submittedTime', '{$BE_subsite['subsite_id']}');";
      $db->query($query);

      $template = pslNew('slashTemplate', $_PSL['templatedir'], 'remove');
      $template->set_file(array('feedbackThanks' => 'BE_feedbackThanks.tpl'));
      $template->set_var(array('ORIGINAL_REFERER' => $submittedReferer));
      $content = $template->parse('OUT', 'feedbackThanks', TRUE);

      $from = (is_valid_email($submittedEmail)) ? $submittedEmail : $_PSL['site_owner'];

      $body = pslgetText('Read the feedback at') . ": \n\n\thttp://" . $_PSL['rootdomain'] . $_PSL['adminurl'] . '/BE_feedbackAdmin.php?action=view&id=' . $id . " \n\n";
      $body .= ($from == $_PSL['site_owner']) ? pslgetText('No feedback required') : pslgetText('Feedback required');
      $body .= " \n\n" . html_entity_decode(stripslashes($submittedComments), ENT_QUOTES);

      //  Send the site owner a message saying that feedback was sent.
      mail($_PSL['site_owner'], $_PSL['site_name'] . ' ' . pslgetText('Feedback'), $body, 'From: ' . $from . "\n");

   } else {

      $template = pslNew('slashTemplate'); // , @$this->psl['templatedir'], 'remove');
      $template->set_file(array('feedback' => 'BE_feedback.tpl'));

      // TODO: Move this to template.
      $startFeedbackForm = '<form name="feedback" action="' . $_SERVER['PHP_SELF'] . '" method="post">';
      $nameInput = '<input name="' . $feedbackName . '" type="text" />';
      $cityInput = '<input name="' . $feedbackCity . '" type="text" />';
      $memberInput = '<input name="' . $feedbackIsMember . '" type="radio" value="1" /> '.pslgetText('Yes').' <input name="' . $feedbackIsMember . '" type="radio" value="0" /> '.pslgetText('No');
      $localInput = '<input name="' . $feedbackLocal . '" type="text" />';
      $knowMemberInput = '<input name="' . $feedbackKnowsMember . '" type="radio" value="1" /> '.pslgetText('Yes').' <input name="' . $feedbackKnowsMember . '" type="radio" value="0" /> '.pslgetText('No');
      $emailInput = '<input name="' . $feedbackEmail . '" type="text" />';
      $commentsInput = '<textarea name="' . $feedbackComments . '" cols=20 style="width:100%;height:90px;"></textarea>';
      $hiddenInput = "<input type=\"hidden\" name=\"$feedbackReferer\" value=\"" . @$_SERVER['HTTP_REFERER'] . '">';
      $submitInput = '<input type="submit" name="submit_send" value="Send Feedback" />';
      $endFeedbackForm = '</form>';

      $template->set_var(array(
         'START_FEEDBACK_FORM'  => $startFeedbackForm,
         'NAME_INPUT'           => $nameInput,
         'CITY_INPUT'           => $cityInput,
         'CUPE_MEMBER_INPUT'    => $memberInput,
         'CUPE_MEMBER_VARIABLE' => $feedbackIsMember,
         'LOCAL_INPUT'          => $localInput,
         'KNOW_MEMBER_INPUT'    => $knowMemberInput,
         'KNOW_MEMBER_VARIABLE' => $feedbackKnowsMember,
         'EMAIL_INPUT'          => $emailInput,
         'COMMENTS_INPUT'       => $commentsInput,
         'HIDDEN_INPUT'         => $hiddenInput,
         'SUBMIT_INPUT'         => $submitInput,
         'END_FEEDBACK_FORM'    => $endFeedbackForm
      ));

      $content = $template->parse('OUT', 'feedback', TRUE);

   }

   // If templates are defined, checks if they exist and formats them correctly
   $breadcrumb = $sectionObj->breadcrumb($ary['section'], pslgetText('Feedback'), 'feedback');
   $sectionRec = $sectionObj->extractSection($ary['section']);
   $sectionTemplate = $sectionRec['template'];
   $chosenTemplate = getUserTemplates('', $sectionTemplate);

   $_BE['currentSection'] = $ary['section'];

   // generate the page
   generatePage($ary, $pageTitle, $breadcrumb, $content);

   page_close();

?>
