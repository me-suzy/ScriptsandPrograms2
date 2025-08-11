<?php

   /* $Id: commentAdmin.php,v 1.14 2005/04/26 15:07:01 iclysdal Exp $ */

   /* TODO: */

   require('./config.php');

   $pagetitle = pslgetText('Comment Administration'); // The name to be displayed in the header
   $xsiteobject = pslgetText('Administration');       // Defines The META TAG Page Type
   $_PSL['metatags']['object'] = $xsiteobject;

   $content = null;
   $ary = array();

   /*****************************
    START OF PAGE
    *****************************/

   $auth->login_if(!$perm->have_perm('comment'));

   /* DEBUG */
   # debug('HTTP_POST_VARS', $_POST);
   # debug('HTTP_GET_VARS', $_GET);
   # debug('BulkDelete Array', clean($_POST[bulkdel_ary]));

   /* END DEBUG */

   $cmt = pslNew('Comment');

   $action = pslgetText(getRequestVar('action', 'PG'), '', true);

   if ($perm->have_perm('comment')) {
      switch ($action) {

         case 'delete':
         if ($cmt->delete(clean($_GET['comment_id']), clean($_GET['story_id']))) {
            $content .= pslgetText('The comment was deleted') . "<br />\n";

            // expire cache for this story_id
            if (function_exists('jpcache_gc')) {
               jpcache_gc('string', '-story_id-' . clean($_GET['story_id']), '100');
            }

         } else {
            $content .= getError($cmt->getMessage());
         }
         $redirectUrl = $cmt->getArticleURL(clean($_GET['story_id']));
         Header("Location: $redirectUrl");
         die;         
         break;

         case pslgetText('Show Pending'):
         $content .= $cmt->showPending(clean($_GET['story_id']));
         $redirectUrl = $cmt->getArticleURL(clean($_GET['story_id']));
         Header("Location: $redirectUrl");
         die;         
         break;

         case pslgetText('Bulk Delete Comments'):
         $bulkdel_ary = clean($_POST['bulkdel_ary']);
         if (count($bulkdel_ary) == 0) {
            $content .= getMessage('You did not select any items to delete!');
         } else {
            for ($i = 0 ; $i < count($bulkdel_ary) ; $i++) {
               if ($cmt->delete($bulkdel_ary[$i], clean($_POST['story_id']))) {
                  $content .= sprintf(pslgetText("Comment # %s has been deleted."), $bulkdel_ary[$i])."\n";
                  // expire cache for this story_id
                  if (function_exists('jpcache_gc')) {
                     jpcache_gc('string', '-story_id-' . clean($_POST['story_id']), '100');
                  }

               } else {
                  $content .= pslgetText('There was an ERROR deleting comment #') . $bulkdel_ary[$i] . "\n";
               }
            }
         }
         break;

         case 'markpend':
         $content .= getTitlebar('100%', 'Mark Comment Pending');
         if ($cmt->markPending(clean($_GET['comment_id']), clean($_GET['story_id']))) {
            $content .= getMessage(sprintf(pslgetText("Marked comment # %s as pending"), clean($_GET['comment_id'])));
         } else {
            $content .= getError(sprintf(pslgetText("There was an ERROR marking comment # %s as pending."), clean($_GET['comment_id'])));
         }
         $redirectUrl = $cmt->getArticleURL(clean($_GET['story_id']));
         Header("Location: $redirectUrl");
         die;         
         break;
         case 'unmarkpend':
         $content .= getTitlebar('100%', 'Unmark Pending Comment');
         if ($cmt->unmarkPending(clean($_GET['comment_id']), clean($_GET['story_id']))) {
            $content .= getMessage(sprintf(pslgetText("Unmarked pending comment %s"), clean($_GET['comment_id'])));
         } else {
            $content .= getError('There was an ERROR unmarking the comment as pending.');
         }
                  $redirectUrl = $cmt->getArticleURL(clean($_GET['story_id']));
         Header("Location: $redirectUrl");
         die;         
         break;

         case 'changeRating':
         $content .= getTitlebar('100%', 'Changed comment rating');
         if($cmt->changeRating(clean($_GET['comment_id']), clean($_GET['story_id']), clean($_GET['newRating']))) {
            $content .= getMessage(sprintf(pslGetText("Changed rating to %s"), clean($_GET['newRating'])));
         } else {
            $content .= getError('There was an ERROR changing the comment rating.');
         }
         $redirectUrl = $cmt->getArticleURL(clean($_GET['story_id']));
         Header("Location: $redirectUrl");
         die;         
         break;
         
         case 'reparentupone':
         if ($cmt->reparentUp(clean($_GET['comment_id']), clean($_GET['story_id']))) {
            $content .= getMessage('The comment was moved up one level.');

            // expire cache for this story_id
            if (function_exists('jpcache_gc')) {
               jpcache_gc('string', '-story_id-' . clean($_GET['story_id']), '100');
            }

         } else {
            $content .= getError($cmt->getMessage());
         }
         $redirectUrl = $cmt->getArticleURL(clean($_GET['story_id']));
         Header("Location: $redirectUrl");
         die;         
         break;

         case 'reparentuptop':
         if ($cmt->reparentTop(clean($_GET['comment_id']), clean($_GET['story_id']))) {
            $content .= getMessage('The comment was moved to the top.');

            // expire cache for this story_id
            if (function_exists('jpcache_gc')) {
               jpcache_gc('string', '-story_id-' . clean($_GET['story_id']), '100');
            }

         } else {
            $content .= getError($cmt->getMessage());
         }
         $redirectUrl = $cmt->getArticleURL(clean($_GET['story_id']));
         Header("Location: $redirectUrl");
         die;         
         break;

         case 'edit':
         $content .= getTitlebar('100%', 'Edit Comment');
         $returned = $cmt->commentEdit(clean($_GET));
         if ($returned) {
            $content .= $returned;
         } else {
            $content .= getError($cmt->getMessage());
         }
         break;

         case 'update':
         if ($cmt->update(clean($_POST))) {
            $content .= getMessage($cmt->getMessage());

            // expire cache for this story_id
            if (function_exists('jpcache_gc')) {
               jpcache_gc('string', '-story_id-' . clean($_POST['story_id']), '100');
            }

         } else {
            $content .= getError($cmt->getMessage());
         }
         $returned = $cmt->commentEdit();
         if ($returned) {
            $content .= $returned;
         } else {
            $content .= getError($cmt->getMessage());
         }
         break;

         default:
         $content .= getTitlebar('100%', 'Error! No Action');
         $content .= "This is how the Comment Administration works.  You tell the \$action variable what you want to do, and then I go about doing it.  You're getting this message because I didn't find an \$action variable that I could use.  This is what \$action is right now:  '$action'\n";
      }

   } else {
      $content = getTitlebar('100%', 'Error! Invalid Privileges');
      $content .= 'Sorry. You do not have the necessary privilege to view this page.';
   }

   $ary['section'] = 'admin';

   $chosenTemplate = getUserTemplates('',$ary['section']);

   $breadcrumb = '<a href="' . $_PSL['rooturl'] . '/">' . pslgetText('Home') . '</a> &#187; ' . $pagetitle;

   // generate the page
   generatePage($ary, $pagetitle, $breadcrumb, $content);

   page_close();

?>