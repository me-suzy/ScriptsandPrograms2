<?php

   /* $Id: authorAdmin.php,v 1.16 2005/04/13 15:05:14 mgifford Exp $ */
   /**
    * Back-End User Administration
    *
    * Equivalent phpSlash file: authorAdmin.php - the two files should be kept compatible
    *
    * Permissions are shared with phpSlash author admin
    *
    * @package     Back-End on phpSlash
    * @copyright   2003 - Mike Gifford
    * @version     $Id: authorAdmin.php,v 1.16 2005/04/13 15:05:14 mgifford Exp $
    */


   require('./config.php');

   $pagetitle = pslgetText('User Administration'); // header title
   $xsiteobject = pslgetText('Administration');             // Defines The META TAG Page Type
   $_PSL['metatags']['object'] = $xsiteobject;

   $auth->login_if(!$perm->have_perm('author'));

   $author = pslNew('BE_User'); // Can't use $user cos its already taken
   $parm = $content = $message = null;
   $ary = array();

   // Fetch expected variables.
   $submit = pslgetText(getRequestVar('submit', 'PG'), '', true);
   $author_id = getRequestVar('author_id', 'PG');
   $deleteid = getRequestVar('deleteid', 'PG');
   $curr = getRequestVar('curr', 'G');

   $showList = true;

   // error_reporting (E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

   switch ($submit) {

      case 'delete': // Display screen
      if ($perm->have_perm('authorDelete')) {
         $success = $author->confirmDelete(clean($_POST));
         if ($success) {
            $showList = false;
            $content .= $success;
         }
      }
      break;

      case 'edit': // Display screen
      if ($perm->have_perm('authorEdit')) {
         $content .= $author->editAuthor($author_id);
         $showList = false;
      }
      break;

      case 'find': // Display screen
      if ($perm->have_perm('authorList')) {
         $content .= $author->findAuthor();
         $showList = false;
      }
      break;

      case 'new': // Display screen
      if ($perm->have_perm('authorNew')) {
         $content .= $author->editAuthor();
         $showList = false;
      }
      break;

      // Process actions from previous screen

      case 'add': // after add user to Local
      if ($perm->have_perm('authorEdit')) {
         $author->addAuthor(clean($_POST));
      }
      break;

      case 'confirm':
      // after confirmDelete
      if ($perm->have_perm('authorDelete')) {
         $success = $author->deleteAuthor($deleteid, $auth->auth['uid']);
      }
      break;


      // after newUser
      case 'create':
      // after editUser
      case 'update':
      if ($perm->have_perm('authorNew')) {
         $success = $author->saveAuthor(clean($_POST));
         if (!$success) {
            $content .= $author->editAuthor(clean($_POST));
            $showList = false;
         }
      }
      break;

      case 'lostpw':
      // if ($perm->have_perm('authorLostPW') {
      if ($perm->have_perm('authorList')) {
         $success = $author->lostpw(clean($_GET), $auth->auth['uid']);
         if ($success) {
            $content .= getMessage($author->message);
         } else {
            $content .= getError($author->message);
         }
      }
      break;


      case 'remove':
      // after findUser - local
      if ($perm->have_perm('authorEdit')) {
         $success = $author->removeAuthor(clean($_POST));
      }
      break;

      case 'search':
      // after findUser - global
      $parm = (isset($_POST)) ? clean($_POST) : null;
      break;

      // General navigation

      case 'back':
      case 'Back':
      $parm = max (0, $curr - $_PSL['search_maxresults']);
      break;

      case 'next':
      case 'Next':
      $parm = $curr + $_PSL['search_maxresults'];
      break;

      case 'cancel':
      $message .= getMessage(pslGetText('Action cancelled'));

      default:
      $parm = 0;

   }

   if ($showList) {
      if ($perm->have_perm('authorList')) {
         $content .= $author->listAuthors($parm);
      }
   }

   $content = $author->message . $message . $content;

   if ($content == '') {
      $content = getTitlebar('100%', 'Error! Invalid Privileges');
      $content .= pslgetText('Sorry. You do not have the necessary privilege to view this page.');
   }

   $ary['section'] = 'admin';

   $chosenTemplate = getUserTemplates('',$ary['section']);

   $breadcrumb = '<a href="' . $_PSL['rooturl'] . '/">' . pslgetText('Home') . '</a> &#187; ' .$pagetitle;

   // generate the page
   generatePage($ary, $pagetitle, $breadcrumb, $content);

   page_close();

?>