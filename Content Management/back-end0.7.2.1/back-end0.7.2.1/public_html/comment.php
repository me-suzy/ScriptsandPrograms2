<?php

   /* $Id: comment.php,v 1.10 2005/04/26 15:06:02 iclysdal Exp $ */

   require('./config.php');

   $pagetitle = pslgetText('Comment Display');   // Defines the page title.
   $xsiteobject = pslgetText('Comment Display'); // Defines The META TAG Page Type

   $auth->login_if(!$perm->have_perm('commentView'));

   $content = '';

   if (!empty($_POST['submit'])) {

      $submit = pslgetText(getRequestVar('submit', 'P'), '', true);
      $ary = clean($_POST, true);

      $name  = @$ary['name'];
      $email = @$ary['email'];
      $url   = @$ary['url'];

      if (!empty($ary['name'])) {
         $comment_name  = @$ary['name'];
         $comment_email = @$ary['email'];
         $comment_url   = @$ary['url'];

         $sess->register('comment_name');
         $sess->register('comment_email');
         $sess->register('comment_url');
      }


      $cmt = pslNew('Comment', $ary);

      switch($submit) {

         case 'preview':

         // when you are previewing a comment before submitting
         $ary['ip'] = gethostbyaddr($_SERVER['REMOTE_ADDR']);
         /* add the poster's IP addr */

         $ary['replying_to'] = $cmt->formatComment($ary);
         $content .= $cmt->getForm($ary);
         break;

         case 'save':
         case 'Submit Comment':

         $content .= "<br />Click <a href=\"" . $return_link . "\">here</a> to go back<br />\n";
         if ($ary['parent_id']) {
            $content .= "<a href=\"{$_PSL['rooturl']}/comment.php?submit=view&story_id=$ary[story_id]&comment_id=$ary[parent_id]&mode=flat\">" . pslgetText('View Parent Comment') . "</a>\n";
         }

         $content .= getTitlebar('100%', 'Submitted Comment');
         $ary['ip'] = $_SERVER['REMOTE_ADDR'];
         /* add the poster's IP addr */

         $id = $cmt->update($ary);
         if ($id) {

            // expire cache for this story_id
            if (function_exists('jpcache_gc')) {
               jpcache_gc('string', '-story_id-' . $ary['story_id'], '100');
            }
            $content .= getMessage($cmt->getMessage());
            $comment_ary = $cmt->getCommentArray($id);
            $content .= $cmt->formatComment($comment_ary);
            // send email for new submission.
            if ($_PSL['commentnotify']) {
               $mail_ary['tpl'] = "emailNotifyComment";
               $mail_ary['vars'] = $ary;
               $success = emailNotify($mail_ary);
            }
         } else {
            $content .= getError($cmt->getMessage());
         }
         header("Location: $return_link");
         break;

      }


   } elseif (!empty($_GET['submit'])) {

      $submit = pslgetText(getRequestVar('submit', 'G'), '', true);
      $ary = clean($_GET);

      if (empty($ary['parent_id'])) {
         $ary['parent_id'] = 0;
      }

      if ($sess->is_registered("comment_name")) {
         $ary['name'] = $comment_name;
         $ary['email'] = $comment_email;
      }

      $cmt = pslNew("Comment", $ary);

      switch($submit) {
         case "report":
         $story_id = clean($_GET['story_id']);
         $comment_id = clean($_GET['comment_id']);
         $content = $cmt->reportComment($story_id, $comment_id);
         break;
         
         case "post":

         if (!$perm->have_perm('commentPost')) {
            $auth->auth['error'] = pslgetText("Login required to post comments");
            $auth->login_if(!$perm->have_perm('user'));
         }
         if ($ary['parent_id'] != 0) {
            $parent_ary = $cmt->getCommentArray($ary['parent_id']);
            $ary['replying_to'] = $cmt->formatComment($parent_ary);
         } else {
            $story = pslNew('Story');
            $ary['replying_to'] = $story->getStory($ary['story_id'], 'full');
         }

         if (!isset($parent_ary['subject']) || $parent_ary['subject'] == '') {
            //$ary[subject] = pslgetText("No Subject Given");
         } elseif(preg_match ("/Re\:/i", $parent_ary['subject'])) {
            $ary['subject'] = $parent_ary['subject'];
         } else {
            $ary['subject'] = pslgetText('Re: ') . $parent_ary['subject'];
         }

         $ary['action_url'] = $_PSL['phpself'];
         $ary['siteowner'] = $_PSL['site_owner'];

         // session variables
         // if(isset($name)) {
         //    $ary['name'] = $name;
         // }
         // if(isset($email)) {
         //   $ary['email'] = $email;
         // }
         // if(isset($url)) {
         //    $ary['url'] = $url;
         // }

         // either start a new object or getForm not reuse template vars.
         $cmt = pslNew('Comment', $ary);

         $content .= $cmt->getForm($ary);
         break;

         case 'view':
         case 'Change':

         // the basic viewing of the comments

         $content .= getTitlebar('100%', pslgetText('Comments'));
         // print_r($ary);
         $content .= $cmt->getAllComments($ary);
         break;

      }

   }

   if ($content == '') {
      $content = getTitlebar("100%", "Comment Error!");
      $content .= getError(pslgetText("You didn't supply a good submit value"));
   }


   if ((!isset($_GET['section'])) && (!isset($_GET['section_id']))) {
      $section = $_BE['CommentSection'];
      if(!$section) {
         $section = $_PSL['site_homesection'];
      }
      if(!$section) {
         $section = 'Home';
      }
      $ary['section'] = $section;
   } else {
      $pagetitle .= " - $section";
   }

   $block = pslNew('Block_i');

   $breadcrumb = breadcrumb($ary);

   $_PSL['metatags']['object'] = $xsiteobject;

   slashhead($pagetitle, $_PSL['metatags']);

   $leftblocks = $block->getBlocks($ary, "left");
   $centerblocks = $block->getBlocks($ary, "center");
   $rightblocks = $block->getBlocks($ary, "right");

   if (empty($leftblocks)) {
      if (empty($rightblocks)) {
         // $centerblocks  = $block->getBlocks($ary);
         $tplfile = "index1col.tpl";
      } else {
         $tplfile = 'index2colright.tpl';
      }
   } elseif (empty($rightblocks)) {
      $tplfile = 'index2colleft.tpl';
   } else {
      $tplfile = 'index3col.tpl';
   }

   $template = pslNew("slashTemplate", $_PSL['templatedir']);
   $template->debug = 0;
   $template->set_file(array(
   'index' => $tplfile //"index3col.tpl"
   ));

   if (!empty($GLOBALS['QUERY_STRING'])) {
      $QUERY_STRING = '?'.$GLOBALS['QUERY_STRING'];
   } else {
      $QUERY_STRING = '';
   }

   $template->set_var(array(
   'QUERYSTRING' => $QUERY_STRING,
      'ROOTDIR' => $_PSL['rooturl'],
      'IMAGEDIR' => $_PSL['imageurl'],
      'BREADCRUMB' => $breadcrumb,
      'STORY_COLUMN' => $content,
      'LEFT_BLOCK_COLUMN' => $leftblocks,
      'CENTER_BLOCK_COLUMN' => $centerblocks,
      'RIGHT_BLOCK_COLUMN' => $rightblocks ));

   $template->parse('OUT', 'index');
   $template->p('OUT');

   slashfoot();
   page_close();

?>
