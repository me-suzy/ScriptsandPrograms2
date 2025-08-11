<?php
    // $Id: BE_feedbackAdmin.php,v 1.27 2005/06/13 15:15:23 mgifford Exp $
    /**
    * Search functionality business logic
    *
    * @package     Back-End on phpSlash
    * @copyright   2002 - Mike Gifford
    * @version     $Id: BE_feedbackAdmin.php,v 1.27 2005/06/13 15:15:23 mgifford Exp $
    *
    */

    require('./config.php');

    global $_BE, $_PSL;

    $pagetitle = pslgetText('View Feedback');
    $xsiteobject = pslgetText('Feedback Page'); // Defines The META TAG Page Type

    // Check privileges; require login
    $auth->login_if(!$perm->have_perm('story') && !$perm->have_perm('feedback'));

    $getRequestValue = getRequestValue(); //$section, $article);

    if (!empty($_GET['login'])) {
        $auth->login_if(true);
    }

    // Require admin privileges!!! @TODO!

    /****************
    * INITIALISATION
    *****************/
    $ary['section'] = $_BE['FeedbackSection'];

    $content = '';

    $ary['query'] = (isset($_GET['query'])) ? clean($_GET['query']) : null;
    $ary['min'] = (isset($_GET['min'])) ? clean($_GET['min']) : null;
    $action = (isset($_GET['action'])) ? clean($_GET['action']) : null;

    /****************
    * CONSTRUCT PAGE
    *****************/

    $db = pslNew('BEDB');
    $authorObj = pslNew('Author');

    if ($action == 'delete') {
        $id = clean($_GET['id']);
        if ($id == '') {
            $content = 'Could not get ID to delete.';
        } else {
            $query = "DELETE FROM {$_BE['FeedbackTable']} WHERE id=$id";
            if (!($db->query($query))) {
                $content = 'Could not delete the comment.';
            } else {
                $content = 'Comment deleted - click <a href="' . $_SERVER['PHP_SELF'] . '">here</a> to go back to the list of comments.';
            }
        }
    }
    else if ($action == 'view') {
        $id = clean($_GET['id']);
        if ($id == '') {
            $content = 'Could not get ID to view.';
        } else {
            $query = "SELECT * FROM {$_BE['FeedbackTable']} WHERE id = $id";
            $db->query($query);
            $db->next_record();

            $startResponseForm = '<form name="feedbackResponse" method="post" action="' . $_SERVER['PHP_SELF'] . '?action=respond">';
            $endResponseForm = '</form>';
            $respondButton = '<input type="hidden" name="id" value="' . $id . '"><input type="submit" name="submit_respond" value="'.pslgetText('Respond').'">';
            $id = $db->Record['id'];
            // $date = date("F j, Y, g:i a", $db->Record['TimeSubmitted']);
            $date = psl_dateTimeLong($db->Record['TimeSubmitted']);
            $from = '<input type="text" name="submitterName" value="' . $db->Record["SubmitterName"] . '">';
            $email = '<input type="text" name="submitterEmail" value="' . $db->Record["SubmitterEmail"] . '">';
            $referal = $db->Record['ReferringPage'];
            if ($db->Record['CupeMember'] == 1) {
                $isMember = pslgetText('Yes');
            } else {
                $isMember = pslgetText('No');
            }
            $local = $db->Record['CupeLocal'];
            if ($db->Record['KnowsCupeMember'] == 1) {
                $knowsMember = pslgetText('Yes');
            } else {
                $knowsMember = pslgetText('No');
            }
            if ($db->Record['Responded'] == 1) {
                $responded = pslgetText('Yes');
            } else {
                $responded = pslgetText('No');
            }
            $respondedBy = $db->Record['RespondedBy'];
            if ($db->Record['TimeRespondedTo'] != '') {
                // $responseDate = date("F j, Y, g:i a", $db->Record['TimeRespondedTo']);
                $responseDate = psl_dateTimeLong($db->Record['TimeRespondedTo']);
            }
            $browser = $db->Record['Browser'];
            $userIP = $db->Record['UserIP'];
            $remoteHost = $db->Record['RemoteHost'];
            //$comment = $db->Record['Comments'];
            $comment = str_replace("\n", '<br />', stripslashes($db->Record['Comments']));
            $response = '<textarea name="response" cols=20 style="width: 100%; height: 240px">' . $db->Record['Response'] . '</textarea>';
            $forwardFeedback = '<form name="forwardFeedback" method="post" action="' . $_SERVER['PHP_SELF'] . '?action=forward"><input type="hidden" name="id" value="' . $id . '"><input type="submit" Name="submit_forward" value="'.pslgetText('Forward to:').'"><input type="text" name="recipient" value="' . $db->Record["ForwardedTo"] . '">';
            $forwardComments = '<textarea name="forwardComments" cols="20" style="width: 100%; heigh:120px">' . $db->Record['ForwardComments'] . '</textarea></form><br /><br /><br /><br />';

            $template = pslNew('slashTemplate', $_PSL['templatedir'], 'remove');
            $template->set_file(array('feedbackResponse' => 'BE_feedbackResponse.tpl'));
            $template->set_var(array(
               'START_RESPONSE_FORM' => $startResponseForm,
               'END_RESPONSE_FORM'   => $endResponseForm,
               'RESPOND_BUTTON'      => $respondButton,
               'ID'                  => $id,
               'DATE'                => $date,
               'FROM'                => $from,
               'EMAIL'               => $email,
               'REFERAL'             => $referal,
               'ISMEMBER'            => $isMember,
               'LOCAL'               => $local,
               'KNOWSMEMBER'         => $knowsMember,
               'RESPONDEDTO'         => $responded,
               'RESPONDEDBY'         => $respondedBy,
               'RESPONSEDATE'        => $responseDate,
               'BROWSER'             => $browser,
               'USERIP'              => $userIP,
               'REMOTEHOST'          => $remoteHost,
               'COMMENT'             => $comment,
               'RESPONSE'            => $response,
               'FORWARD_FEEDBACK'    => $forwardFeedback,
               'FORWARD_COMMENTS'    => $forwardComments
            ));
            $content = $template->parse('OUT', 'feedbackResponse', TRUE);
        }
    }
    else if ($action == 'forward') {
        $id = clean($_POST['id']);
        $recipient = clean($_POST['recipient']);
        $forwardComments = clean($_POST['forwardComments']);
        $query = "SELECT Comments FROM $_BE[FeedbackTable] WHERE id='$id'";
        $db->query($query);
        $db->next_record();
        $comments = stripslashes($db->Record['Comments']);

        $query = "UPDATE $_BE[FeedbackTable] SET ForwardedTo = '$recipient', ForwardComments = '" . addslashes($forwardComments) . "' WHERE id = $id";
        $db->query($query);

        $message = $auth->auth['dname'] . ' ' . pslgetText('has asked you to look at a piece of feedback.  If a response is required, please go to') . ": \n\n\thttp://" . $_PSL['rootdomain'] . $_PSL['adminurl'] . '/BE_feedbackAdmin.php?action=view&id=' . $id . "\n\n";
        if (!empty($forwardComments)) {
            $message .= pslgetText('Comments from forwarder') . ": \n$forwardComments\n\n";
        }
        $message .= pslgetText('Content of feedback') . ":\n" . $comments;
        $respondedBy = $auth->auth['uname'];

        mail($recipient, $_PSL['site_name'] . ' ' . pslgetText('Feedback'), html_entity_decode($message, ENT_QUOTES), 'From: ' . $auth->auth['dname'] . ' <' . $authorObj->getEmail($auth->auth['uid']) . ">\n");

        // Go back to the list.
        page_close();
        Header('Location: ' . $_SERVER['PHP_SELF']);

    }
    else if ($action == 'respond') {
        $id = clean($_POST['id']);
        if ($id == '') {
            $content = pslgetText('Could not get ID to respond to.');
        } else {
            //  Update name/e-mail which might have been fixed.
            $submitterName = clean($_POST['submitterName']);
            $submitterEmail = clean($_POST['submitterEmail']);

            //  Grab the response.
            $response = clean($_POST['response']);

            //  Get the response name and time.
            $respondedBy = $auth->auth['uname'];
            $responseDate = time();

            // Update the database
            $query = "
                UPDATE
                {$_BE['FeedbackTable']}
                SET
                SubmitterName = '" . addslashes($submitterName) . "',
                SubmitterEmail = '$submitterEmail',
                Response='" . addslashes($response) . "',
                RespondedBy='$respondedBy',
                TimeRespondedTo = $responseDate,
                Responded=1
                WHERE
                id = $id";
            if (!$db->query($query)) {
                $content = 'Could not store the response.';
            } else {
                $responseMessage = '';
                // Original message
                $query = "SELECT Comments FROM {$_BE['FeedbackTable']} WHERE id='$id'";
                $db->query($query);
                if($db->next_record() && !empty($db->Record['Comments'])) {
                   $responseMessage .= '> ' . wordwrap(html_entity_decode($db->Record['Comments'], ENT_QUOTES), 73, "\n> ") . "\n\n";
                }

                $responseMessage .= wordwrap(html_entity_decode($response, ENT_QUOTES));

                // Mail the response
                mail($submitterName . "<" . $submitterEmail . ">", "Response To Your Feedback", $responseMessage, 'From: ' . $auth->auth['dname'] . ' <' . $authorObj->getEmail($auth->auth['uid']) . ">\n");

                // Go back to the list.
                page_close();
                Header('Location: ' . $_SERVER['PHP_SELF']);

            }
        }
    } else {
        //  list
        //  Initialize the template
        $template = pslNew('slashTemplate', $_PSL['templatedir'], 'remove');
        $template->set_file(array('feedbackList' => 'BE_feedbackList.tpl'));
        $template->set_block('feedbackList', 'feedback_row', 'feedback_rows');
        if (isset($BE_subsite['subsite_id']) && !empty($BE_subsite['subsite_id'])) {
           $query = "SELECT * FROM {$_BE['FeedbackTable']} WHERE subsite_id='{$BE_subsite['subsite_id']}' ORDER BY TimeSubmitted DESC";
        } else {
           $query = "SELECT * FROM {$_BE['FeedbackTable']} ORDER BY TimeSubmitted DESC";
        }
        $db->query($query);

        //  We need to separate this into pages and order by date
        $ii = 0;
        $numToDisplay = 25;

        $page = (isset($_GET['page']) && !empty($_GET['page'])) ? clean($_GET['page']) : 1;

        //  There has GOT to be a better way to do this
        for($jump = 0; $jump < (($page-1) * $numToDisplay); $jump++) {
            $db->next_record();
        }

        while ($db->next_record() && $ii < $numToDisplay) {
            $id = $db->Record['id'];
            $submitterName = $db->Record['SubmitterName'];
            $timeSubmitted = $db->Record['TimeSubmitted'];
            $respondLink = '<a href="' . $_SERVER['PHP_SELF'] . '?action=view&id=' . $id . '">'.pslgetText('Respond').'</a>';
            $deleteLink = '<a href="' . $_SERVER['PHP_SELF'] . '?action=delete&id=' . $id . '">'.pslgetText('Delete').'</a>';
            if ($db->Record['Responded'] == '1') {
                $responseInfo = pslgetText('Response by ') . $db->Record['RespondedBy'] . ', ' . date("F j, Y, g:i a", $db->Record["TimeRespondedTo"]);
            }
            else if ($db->Record['ForwardedTo'] != '') {
                $responseInfo = pslgetText('Forwarded to ') . $db->Record['ForwardedTo'];
            } else {
                $responseInfo = pslgetText('No Response');
            }

            $template->set_var(array(
                'ALTERNATING_CLASS' => ($ii %2),
                'ITEM_ID' => $id,
                'SUBMITTER_NAME' => $submitterName,
                'RESPOND_LINK' => $respondLink,
                'DELETE_LINK' => $deleteLink,
                # 'DATE_SUBMITTED' => date("F j, Y, g:i a", $timeSubmitted),
                'DATE_SUBMITTED' => psl_dateTimeLong($timeSubmitted),
                'RESPONSE_INFO' => $responseInfo
             ));
            $template->parse('feedback_rows', 'feedback_row', true);
            $ii++;
        }
        $numPages = ceil($db->num_rows()/$numToDisplay);
        $pageInfo = "Page $page of $numPages";

        $previousPage = $nextPage = null;

        if ($page > 1) {
            $previousPage = '<a href="' . $_SERVER['PHP_SELF'] . "?page=" . ($page-1) . '">Previous Page</a>';
        }
        if ($page < $numPages) {
            $nextPage = '<a href="' . $_SERVER['PHP_SELF'] . '?page=' . ($page+1) . '">Next Page</a>';
        }
         $helpIcon = be_generateAdminLinks(array(
            'title'       => 'Feedback',
            'manual'      => 'FeedbackAdmin'
         ));
        $template->set_var(array(
           'TITLEBAR'      => getTitlebar('100%', pslgetText('Feedback Admin')),
           'HELP_ICON'  => $helpIcon,
           'PAGE_INFO'     => $pageInfo,
           'PREVIOUS_PAGE' => $previousPage,
           'NEXT_PAGE'     => $nextPage
        ));
        $content = $template->parse('OUT', 'feedbackList', TRUE);
    }

   $ary['section'] = 'admin';
   $_BE['currentSection'] = $ary['section'];
   $chosenTemplate = getUserTemplates('', $ary['section']);

    // render the standard header
    $_PSL['metatags']['object'] = $xsiteobject;

   $breadcrumb = '<a href="' . $_PSL['rooturl'] . '/">' . pslgetText('Home') . '</a> &#187; ' .$pagetitle;

    // generate the page
    generatePage($ary, $pagetitle, $breadcrumb, $content);

    page_close();

?>
