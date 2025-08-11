<?php
   /* $Id: profile.php,v 1.10 2005/04/10 17:00:52 mgifford Exp $ */
   /**
    * Back-End User Self-Administration
    *
    * Permissions are shared with phpSlash author admin
    *
    * @package     Back-End on phpSlash
    * @copyright   2003 - Mike Gifford
    * @version     $Id: profile.php,v 1.10 2005/04/10 17:00:52 mgifford Exp $
    */

   // don't cache this page
   $cachetimeout = -1;

   require('./config.php');

   $pagetitle = pslgetText('My Preferences'); // header title
   $xsiteobject = pslgetText('Profile');
   // Defines The META TAG Page Type

   /*****************************
    START OF PAGE
    *****************************/
   $author = pslNew('Author');

   if (isset($_GET['confirm']) && !empty($_GET['confirm'])) {
      $confirm_success = $author->confirmAuthor(clean($_GET['confirm']));
   }

   $auth->login_if(!$perm->have_perm('user'));

   $content = '';

   if ($perm->have_perm('user')) {

      $ary = array();
      if (!empty($_POST)) {
         $ary = clean($_POST, true);
         $ary['submit'] = pslgetText($ary['submit'], '', true);
      }

      $submit = pslgetText(getRequestVar('submit', 'PG'), '', true);

      switch ($submit ) {
         case 'view':
         $content .= $author->editAuthor($ary['viewid'], 'BE_userViewProfile', true);//userViewProfile
         break;
         case 'update':
         if ($perm->have_perm('authorprofileSave')) {
            if ($author->saveProfile($ary)) {
               $content .= getMessage('Profile Updated');

               // expire cache for this session
               if (function_exists('jpcache_gc'))
               jpcache_gc('string', '-slashSess-' . $sess->id, '100');

            } else {
               $content .= getError('Profile not updated');
            }
         }


         case 'edit':
         default:
         $content .= $author->editAuthor($auth->auth['uid'], 'authorProfile');
         $content .= $author->listComment($auth->auth['uid']);
         $content .= $author->listArticle($auth->auth['uid']);
      }

   } else {
      $content = getTitlebar('100%', 'Error! Invalid Privileges');
      $content .= 'Sorry. You do not have the necessary privilege to view this page.';
   }

   $ary['section'] = 'Admin';
   $_BE['currentSection'] = $ary['section'];
   $_PSL['metatags']['object'] = $xsiteobject;


   $chosenTemplate = getUserTemplates();

   $breadcrumb = '<a href="' . $_PSL['rooturl'] . '">' . pslgetText('Home') . '</a> &#187; ' . $pagetitle;

   // generate the page
   generatePage($ary, $pagetitle, $breadcrumb, $content);

   page_close();

?>