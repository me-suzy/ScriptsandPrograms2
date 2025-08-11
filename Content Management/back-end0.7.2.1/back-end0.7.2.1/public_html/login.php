<?php
   /**
   * Login page/Administration front-end
   *
   * @package     Back-End
   * @copyright   2002-5 - Open Concept Consulting
   * @version     0.7 $Id: login.php,v 1.49 2005/05/25 20:43:20 mgifford Exp $
   * @copyright   Copyright (C) 2003 OpenConcept Consulting
   *
   * This file is part of Back-End.
   *
   * Back-End is free software; you can redistribute it and/or modify
   * it under the terms of the GNU General Public License as published by
   * the Free Software Foundation; either version 2 of the License, or
   * (at your option) any later version.
   *
   * Back-End is distributed in the hope that it will be useful,
   * but WITHOUT ANY WARRANTY; without even the implied warranty of
   * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   * GNU General Public License for more details.
   *
   * You should have received a copy of the GNU General Public License
   * along with Back-End; if not, write to the Free Software
   * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
   */

   // don't cache this page
   $cachetimeout = -1;

   require('./config.php');

  /*
   * PREPROCESSING ACTIONS
   */
/* Commented out since BE_QuestionPasswordRecovery is not available
   if(isset($_PSL['module']['BE_QuestionPasswordRecovery']) && $_PSL['module']['BE_QuestionPasswordRecovery']) {
      // moved before config.php as it wasn't being redirected
      if(!empty($_REQUEST['lostpw2'])) {
         Header("Location: /lostpw2.php?username=$_REQUEST[username]");
         die("\n");
      }
   }
*/

   // This belongs in with the install crutch in config.php??
   if ($_PSL['rootdomain'] != $_SERVER['HTTP_HOST']) {
#      debug("login.php root domain and http_host don't match", 'root domain: ' .$_PSL['rootdomain'] . ', http host ' . $_SERVER['HTTP_HOST']);
   }

   // echo '<pre>'; print_r($sess); echo '</pre>';
   // echo '<pre>'; print_r($_POST); echo '</pre>';

   // Check that the rootdomain will return a valid cookie_host
#   $rootDomainArray = explode ('.', $_PSL['rootdomain']);
#   if (count($rootDomainArray) < 3) {
      // Warning no longer seems to be required.
      // echo pslgetText('You may have trouble loging in with the rootdomain') . ' ' . $_PSL['rootdomain'] . ' ' . pslgetText('as cookie_host protocoll requires two periods are present (ie. www.example.com)');
#   }

   /*
   * LOGOUT PROCESSING
   */

   // If a logged in user is logging out
   if (!empty($_GET['logout']) && $perm->have_perm('user')) {
      if (isset($_COOKIE['user_info'])) {
         debug('login.php cookie', $_COOKIE['user_info']);
         $user_info = unserialize(base64_decode($_COOKIE['user_info']));
         if (!array_key_exists('preferences', $user_info)) {
            debug('login.php cookie-preferences', $user_info);

            // strip the rooturl down to its path for the cookie path.
            $rooturl_ary = parse_url($_PSL['absoluteurl']);
            setcookie('user_info', '', time()-31536000, @$rooturl_ary['path'] , '' , '');

            // $sess->delete();
            $_COOKIE['user_info'] = '';
         } else {
            debug('login.php no cookie-preferences', $user_info);
         }
      } else {
#         debug('login.php logging out - no cookie');
      }

      // expire cache for this session
      //  do this only if caching is currently enabled
      if (function_exists('jpcache_gc')) {
         jpcache_gc('string', '-slashSess-' . $sess->id, '100');
      }

      $username = $auth->auth['uname'];
      // $sess->delete();  // may be needed for phplib session4.inc - mg:Doesn't seem to be

      // discard prefs when logging out.
      $auth->unauth(); // PAC
      $auth->auth['preferences'] = '';
      $auth->auth['error'] = sprintf(pslgetText('%s logged out.'), $username);
      $auth->auth['uid'] = 'nobody';
      $auth->auth['perm'] = '';

      // load the public user info.
      $author = & pslNew('Author');
      $aid = $author->getId($auth->auth['uid']);
      $auth->auth['perm'] = $auth->get_userperms($aid);
      $author_ary['author_id'] = $aid;
      $author_ary = $author->getAuthor($author_ary);
      $auth->auth['dname'] = $author_ary['author_realname'];
      $auth->auth['uname'] = $author_ary['author_name'];
      $auth->auth['url'] = $author_ary['url'];
#      debug('login.php loaded public user info', $auth->auth['uname']);

      // get rid of session variables and use the db author record.
      if ($sess->is_registered('comment_name')) {
         $sess->unregister('comment_name');
         $_SESSION['comment_name'] = null;
         unset($comment_name);
      }
      if ($sess->is_registered('comment_email')) {
         $sess->unregister('comment_email');
         $_SESSION['comment_email'] = null;
         unset($comment_email);
      }
      if ($sess->is_registered('comment_url')) {
         $sess->unregister('comment_url');
         $_SESSION['comment_url'] = null;
         unset($comment_url);
      }

      $_GET['logout'] = '';

   } else {

      debug('login.php not logging out', 'user=' . $perm->have_perm('user'));

   } // END (!empty($_GET['logout']) && $perm->have_perm('user'))

   // ian@CUPE - allow logouts to redirect rather than going to the login page
   if (isset($_GET['redirect']) && !empty($_GET['redirect'])) { // $auth->auth['uid'] == '' &&
      Header('Location: ' . clean($_GET['redirect'],true));
   } else {
      debug('login.php login no redirect header', 'empty');
   }


  /*
   * ACTIONS FROM LOGIN FORM
   */

   if (isset($_POST['cancel'])) {
      $sess->delete();
      Header('Location: ' . $_PSL['absoluteurl'] .'/'); // go home
      die("\n"); // Kill script if not dead already, possible memory leak
   }

   if (!empty($_POST['username'])) {
      # debug('login.php POST', $_POST);
      if ($_POST['challenge'] != $_POST['response']) {
         # debug('login.php POST the challenge DOES NOT EQUAL the response', 'challenge ' . $_POST['challenge'] . ', response ' . $_POST['response']);
         logwrite('Login', 'Failed Login Request ' . clean($_POST['username']));
      }
   }


   // ian@CUPE -  Used for block login
   if (isset($_POST['redirect'])) {
      $loginRedirect = clean($_POST['redirect']);
      $sess->register('loginRedirect');
      debug('login.php login redirect', $loginRedirect);
   } else {
      debug('login.php login no redirect', null);
   }


   if(isset($_POST['secretblockvariable']) && $_POST['secretblockvariable']=='true') {
      $auth->auth['uid'] = 'form';
      $auth->start();
      debug('login.php using post variable secretblockvariable' . $auth->auth['uid'] , $_POST);
   }

  /*
   * CHECK LOGIN HAS WORKED
   */

   // Check to see if user is logged in
   $permValue = $perm->have_perm('user');
   if (!$permValue) {
      debug('second login',$auth->auth['perm']);
      // Forces a second login attempt if user does not have user permissions

      // old kludge for phplib to accept login from an external form such as the login block.
      // If new auth had replaced existing phplib auth. 08/30/2004
      // Added back in as it seems to be required by firefox 1.0
      if (isset($_POST['username'])) { // this would be present if a login is in progress
          $auth->auth['uid'] = 'form'; // this is what auth seems to want
          $auth->start();
          $permValue = $perm->have_perm('user');
      }

      // login overrides the generatePage function below
      $auth->login_if(true);
      debug('login.php auth', $auth);
      debug('login.php no permValue', $permValue);
   } else {
      debug('login.php permValue exists - user logged in', $permValue);
   }

   // echo '<pre>'; print_r($auth->auth); echo '</pre>';
   // Log the login
   if (!empty($auth->auth['uid'])) {
      logwrite('Login', "Logged in user {$auth->auth['dname']} ({$auth->auth['uname']})");
   }

   // ian@CUPE -- redirect when logged in if requested
   if (isset($loginRedirect) && !empty($loginRedirect)) {
      Header('Location: ' . urldecode($loginRedirect));
      $loginRedirect = '';
      debug('login.php loginRedirect exists', $loginRedirect);

      // Skip the rest of the page.
      generatePage($ary, $pagetitle, '', $content, '');
      page_close();
      die;
   }

   debug('login.php loginRedirect does not exists', null);

  /*
   * START OF ADMIN PAGE GENERATION
   */

   // Initialise variables
   $pagetitle = pslgetText('Administration');        // The name to be displayed in the header
   $xsiteobject = pslgetText('Administration Page'); // This Defines The META Tag Object Type
   $_PSL['metatags']['object'] = $xsiteobject; // render the standard header

   $content = '';
   $ary = array();
   if (!empty($_GET)) {
      $ary = clean($_GET);
      debug('login.php clean $_GET', clean($_GET));
   }

   $tpl = & pslNew('slashTemplate'); //, $_PSL['templatedir']);
#   $tpl->debug = 1;
   $tpl->set_file('admin', 'admin.tpl');
   $tpl->set_block('admin','root_actions');


  /*
   * ADMIN ACTIONS/INFORMATION ONLY AVAILABLE TO ROOT
   *
   *  TODO - set up a clearer upgrade path.
   *  When users upgrade their code they should be directed to an upgrade page
   *  Which will load the Upgrade sql and also do any php conversions required.
   *  This should be much more straight forward than it is now.
   *  This needs better docs...
   */

   if ($perm->have_perm('root')) {
      $adminObj = & pslNew('BE_Admin');

      $checkConfiguration = $adminObj->checkConfiguration();
      $checkDBupgrade = $adminObj->checkDbUpgrade();

      $clearCache    = $adminObj->clearCache();
      $clearCounters = $adminObj->clearCounters();
      $check4updates = $adminObj->check4updates();
      $systemDetails = $adminObj->systemDetails();

      $optimizeDB = $adminObj->optimizeDB();
      $backupDB   = $adminObj->backupDB();
      $upgradeDB  = $adminObj->upgradeDB();

      // $adminObj->upgradeDB714();

      $tpl->set_var(array(
         'CONFIG_WARNING'   => $checkConfiguration . $checkDBupgrade,

         'CLEAR_CACHE'      => $clearCache,
         'CLEAR_COUNTER'    => $clearCounters,
         'LATEST_VERSION'   => $check4updates,
         'SYSTEM_DETAILS'   => $systemDetails,

         'OPTIMIZE_DB'      => $optimizeDB,
         'BACKUP_DB'        => $backupDB,
         'DB_UPGRADE'       => $upgradeDB,
         'ROOTDIR'          => $_PSL['absoluteurl']
      ));

      $tpl->parse('root_actions','root_actions');

   } else {
      $tpl->clear_var('root_actions');
   }

  /*
   * Generally Available information
   */

   $welcomeMsg = pslgetText('Welcome') . ' ' . $auth->auth['uname'];

   //TODO: These two database calls should be wrapped in relevant objects

   $bedbObj = & pslSingleton('BEDB');
   // Count Total Article Hits
   $q = '
      SELECT SUM(hitCounter) AS articleSum
      FROM ' . $_BE['Table_articles'];
   $bedbObj->query($q);
   $bedbObj->next_record();
   $articleSum = intval($bedbObj->Record['articleSum']); // Could be NULL if there are no articles

   // Count Total Section Hits
   $q = '
      SELECT SUM(hitCounter) AS sectionSum
      FROM ' . $_BE['Table_sections'];
   $bedbObj->query($q);
   $bedbObj->next_record();
   $sectionSum = intval($bedbObj->Record['sectionSum']);

   // Display Total Section/Article Hits
   // Should be templated..
   $totalHits = '<p>' . pslgetText('Total Article Hits:') . ' ' . $articleSum . '</p>';
   $totalHits .= '<p>' . pslgetText('Total Section Hits:') . ' ' . $sectionSum . '</p>';
   $totalHits .= '<p>' . pslgetText('Grand Total:') . ' ' . ($articleSum + $sectionSum) . '</p>';


   // generate the output for the primary ('admin') content section
   $tpl->set_var(array(
      'WELCOME'          => $welcomeMsg,
      'TOTAL_HITS'       => $totalHits,
      'ROOTDIR'          => $_PSL['absoluteurl']
   ));


  /*
   * PUT THE PAGE TOGETHER
   */

   $content = $tpl->parse('OUT', 'admin');

   // generate the page
   $ary['section'] = 'Admin';
   $_BE['currentSectionURLname'] = $ary['section'];

   $sectionObj = & pslSingleton('BE_Section');
   $breadcrumb = $sectionObj->breadcrumb('', 'Admin', 'admin');

   // Some may prefer these blocks.
   $chosenTemplate = getUserTemplates('admin', 'admin');

   generatePage($ary, $pagetitle, $breadcrumb, $content, '');

   page_close();
?>
