<?php
  // $Id: BE_articleAdmin.php,v 1.53 2005/06/19 10:41:18 krabu Exp $
   /**
   * Back-End Articles Administration
   *
   * Equivalent phpSlash file: storyAdmin.php - the two files should be kept compatible
   *
   * Permissions are shared with phpSlash stories
   *
   * @package     Back-End on phpSlash
   * @copyright   2002-5 - Open Concept Consulting
   * @version     $Id: BE_articleAdmin.php,v 1.53 2005/06/19 10:41:18 krabu Exp $
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

   require('./config.php');

   $pagetitle = pslgetText('Article Administration'); // The name to be displayed in the header
   $xsiteobject = pslgetText('Administration');   // Defines The META TAG Page Type
   $_PSL['metatags']['object'] = $xsiteobject;

   // error_reporting (E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

   #$auth->login_if(!$perm->have_perm('story'));

   $content = null;
   $ary = array();
   $showList = true;

   // $submit     = pslgetText(getRequestVar('submit', 'PG'), '', true);
   $submit = decodeAction($_REQUEST);

   $sectionID  = getRequestVar('sectionID','PG');
   $URLname    = eregi_replace('[^a-z0-9_]','', str_replace(array('  ',' ','%20','#'),'_',getRequestVar('URLname_'.$BE_currentLanguage,'P')));
   $itemID     = getRequestVar('itemID','PG');
   $articleID  = getRequestVar('articleID','PG');
   $articleID  = (!empty($articleID)) ? $articleID : $itemID;
   $languageID = getRequestVar('languageID','PG');

#   $auth->login_if(!$perm->have_perm('storyList', $sectionID));

   $articleObj = pslNew('BE_Article_admin');
   $sectionObj = pslNew('BE_Section');

   # $languageID = getRequestVar('languageID','PG');

   # debug('submit', $submit);

   switch ($submit) {

      // VERSION HISTORY ACTIONS
      case 'history':
      if ($_PSL['module']['BE_History'] && $perm->have_perm('storyEdit', $sectionID)) {
         $history = pslNew('BE_HistoryArticle',$articleObj);
         $content .= $history->showHistory($sectionID,$itemID,$languageID);
         $showList = false;
      }
      break;

      case 'viewversion':
      if ($_PSL['module']['BE_History'] && $perm->have_perm('storyEdit', $sectionID)) {
         $history = pslNew('BE_HistoryArticle',$articleObj);
         $content .= $history->showVersion($itemID, clean($_GET['version']));
         $content .= $history->showHistory($sectionID,$itemID,$languageID);
         $showList = false;
      }
      break;

      case 'revertversion':
      if ($_PSL['module']['BE_History'] && $perm->have_perm('storyEdit', $sectionID)) {
         $history = pslNew('BE_HistoryArticle',$articleObj);
         $content .= $history->revertToVersion($itemID,clean($_GET['version']));
         $content .= $history->showHistory($sectionID,$itemID,$languageID);
         $showList = false;
      }
      break;

      case 'newmajorversion':
      if ($_PSL['module']['BE_History'] && $perm->have_perm('storyEdit', $sectionID)) {
         $history = pslNew('BE_HistoryArticle',$articleObj);
         $history->setKey($itemID);
         $history->newMajorVersion();
         $content .= $history->showHistory($sectionID,$itemID,$languageID);
         $showList = false;
      }
      break;

      case 'compare':
      case 'diff':
      if ($_PSL['module']['BE_History'] && $perm->have_perm('storyEdit', $sectionID)) {
         # debug($submit, clean($_GET));
         $history = pslNew('BE_HistoryArticle',$articleObj);
         $content .= $history->showDiff($sectionID, $itemID,$languageID, clean(@$_GET['versions']));
         $content .= $history->showHistory($sectionID,$itemID,$languageID);
         $showList = false;
      }
      break;

      // EDIT RELATED ACTIONS
      case 'delete':
      if ($perm->have_perm('storyDelete', $sectionID)) {
         if (!$articleObj->deleteArticle($articleID, '', $sectionID)) {
            $content .= getError(pslgetText('be_article_delete_err'));
         } else {
            if (!empty($sectionID)) {
               $sectionSkeletonAry = $sectionObj->extractSkeleton();
               $section = (!empty($sectionSkeletonAry[$sectionID]['URLname'])) ? $sectionSkeletonAry[$sectionID]['URLname'] : $sectionID;
               Header('Location: ' . $_PSL['rooturl'] . '/' . $_BE['article_file'] . '/' . $section);
            }
         }
      }
      break;

      case 'save':
         if ($perm->have_perm('storySave', $sectionID)) {
#         $success = $articleObj->saveArticle(clean($_POST, TRUE)); // Cleaning is done within class
//Hack to hopefully prevent mistimed aborts - uncomment to activate
//Does not work in safe mode:
#set_time_limit(90);
#ignore_user_abort(true);

         $articleID = clean($_POST['articleID']);
         $oldParentSections =  $articleObj->extractSectionsForArticle($articleID);
         $success = $articleObj->saveArticle($_POST);
         if ($success == false) {
            $content .= $articleObj->message;
#            $content .= $articleObj->newArticle(clean($_POST, TRUE), 'array');
            $content .= $articleObj->newArticle($_POST, 'array');
         } else {

            // echo "<pre>"; print_r($_POST); echo "</pre>";

            $newParentSections = clean($_POST['subSectionID']);
            if (is_array($oldParentSections) && !is_array($newParentSections)) {
               $oldAndNewParentSections = $oldParentSections;
            } elseif (!is_array($oldParentSections) && is_array($newParentSections)) {
               $oldAndNewParentSections = $newParentSections;
            } elseif (is_array($oldParentSections) && is_array($newParentSections)) {
               $oldAndNewParentSections = array_merge($oldParentSections, $newParentSections);
            } else {
               $oldAndNewParentSections = array();
            }

            // Clear internal cache
            if (function_exists('jpcache_gc')) {

               // Legacy caches - probably not required - mg May 2005
               if (!empty($_POST['sectionID'])) {
                  jpcache_gc('string', clean($_POST['sectionID']), '100');
               }
               if (!empty($_POST['URLname'])) {
                  jpcache_gc('string', clean($_POST['URLname']), '100');
               }

               // Find valid articleURLname & clear article cache
               jpcache_gc('string', $articleID, '100'); // This may not be used
               if(is_array($_BE['Language_array'])) {
                  foreach($_BE['Language_array'] AS $aLanguageID) {
                     if (!empty($_POST['URLname_' . $aLanguageID])) {
                        $articleURLname = clean($_POST['URLname_' . $aLanguageID]);
                        jpcache_gc('string', $articleURLname, '100');
                     }
                  }
               }

               // Clear parent section cache & static cache
               foreach ($oldAndNewParentSections AS $relatedSectionID) {
                  // $sectionDetails = $sectionObj->getSkeletonItem($relatedSectionID);
                  $sectionURLnames = $sectionObj->extractURLnames($relatedSectionID);

                  // echo "<pre>"; print_r($sectionURLnames); echo "</pre>";

                  // Find valid sectionURLname & clear section cache
                  jpcache_gc('string', $relatedSectionID, '100');
                  foreach($sectionURLnames AS $itemName) {
                     if(!empty($itemName)) {
                        $sectionURLname = $itemName;
                        jpcache_gc('string', $sectionURLname, '100');
                     }
                  }

                  // Clear static cache
                  if ($_PSL['jpcache.enable'] == 'static') {

                     // Note that this will have to address multiple url names too
                     if (is_dir($_PSL['basedir'] . '/' . strtolower($sectionDetails['URLname']))) {
                        $JPCACHE_DIR = $_PSL['basedir'] . '/' . strtolower($sectionDetails['URLname']);
                        jpcache_gc('regex', "/.*/", '100');
                        jpcache_gc('string', 'index.html', '100');
                     }
                     if (is_dir($_PSL['basedir'] . '/' . $relatedSectionID)) {
                        $JPCACHE_DIR = $_PSL['basedir'] . '/' . $relatedSectionID;
                        jpcache_gc('regex', "/.*/", '100');
                        jpcache_gc('string', 'index.html', '100');
                     }

                  }

               }

            } else {

               // If empty dig through language_array for valid URLname
               if(is_array($_BE['Language_array'])) {
                  foreach($_BE['Language_array'] AS $aLanguageID) {
                     if (!empty($_POST['URLname_' . $aLanguageID])) {
                        $articleURLname = clean($_POST['URLname_' . $aLanguageID]);
                     }
                  }
               }


               // Find valid sectionURLname
               foreach ($oldAndNewParentSections AS $relatedSectionID) {
                  $sectionURLnames = $sectionObj->extractURLnames($relatedSectionID);
                  if(is_array($sectionURLnames)) {
                     foreach($sectionURLnames AS $itemName) {
                        if(!empty($itemName)) {
                           $sectionURLname = $itemName;
                        }
                     }
                  }
               }

            }

            if (!empty($_POST['slashSess'])) {
               $passSession = '?slashSess=' . clean($_POST['slashSess']);
            } else {
               $passSession = null; // Done through cookies
            }

            // Override existing articleURLname using current language
            $articleURLname = (!empty($_POST['URLname_' . $BE_currentLanguage])) ? $_POST['URLname_' . $BE_currentLanguage] : @$articleURLname;

            $sectionURLname = (!empty($sectionURLname)) ? $sectionURLname : $relatedSectionID;
            Header('Location: ' . $_PSL['rooturl'] . '/' . $_BE['article_file'] . '/' . $sectionURLname . '/' . $articleURLname . $passSession);
         }
      }

      break;

      case 'preview':
      if ($perm->have_perm('storyEdit||storyNew', $sectionID)) {
         $_POST['name'] = clean($_POST['author_id']);
         //pac - not used?
#         $content .= $articleObj->showArticle(clean($_POST, true));
#         $content .= $articleObj->newArticle(clean($_POST, true), 'array');
         $content .= $articleObj->showArticle($_POST);
         $content .= $articleObj->newArticle($_POST, 'array');
         $showList = false;
      }
      break;

      case 'edit':
      case 'back': // Back from history screen
      // case 'modify': // option not currently used

      if ($perm->have_perm('storyEdit', $sectionID)) {
         $_POST['articleID'] = $articleID;
         $_POST['sectionID'] = $sectionID;
         $content .= $articleObj->newArticle(clean($_POST, true), 'database');
         $showList = false;
      }
      break;

      case 'copy':
      if ($perm->have_perm('storyNew', $sectionID)) {
      $subsiteObj = pslNew('BE_Subsite');
      $subsitePerms = $subsiteObj->findSubsitePerms4User($perm->auth->auth['uid']);
      $subsites = implode('-', array_keys($subsitePerms));
      if (is_array($subsitePerms) && !empty($subsitePerms)) {
         $_POST['articleID'] = $articleID;
         $content .= $articleObj->copyArticle2subsite(clean($_POST), $subsitePerms);
         $showList = false;
      }
      }
      break;

      case 'saveCopy':
      if ($perm->have_perm('storySave', $sectionID)) {
      $subsiteObj = pslNew('BE_Subsite');
      $subsitePerms = $subsiteObj->findSubsitePerms4User($perm->auth->auth['uid']);
      if (is_array($subsitePerms) && !empty($subsitePerms)) {
         $_POST['articleID'] = $articleID;
         $content .= $articleObj->saveArticle2subsite(clean($_POST));
         $showList = false;
      }
      }
      break;

      case 'new':
      if ($perm->have_perm('storyNew', $sectionID)) {
#         $_REQUEST['sectionID'] = $sectionID;
         $content .= $articleObj->newArticle(clean($_REQUEST, true), 'new');
         $showList = false;
      }
      break;

      case 'idDelete':
      if ($perm->have_perm('storyDelete', $sectionID)) {
      if ($articleObj->deleteArticleByID(clean($_POST['checkedArticle']))) {
            $content .= getMessage($articleObj->getMessage());
      }
      }
      break;

      case 'idRestore':
      if ($perm->have_perm('storyDelete', $sectionID)) {
      if ($articleObj->restoreArticleByID(clean($_POST['checkedArticle']))) {
            $content .= getMessage($articleObj->getMessage());
         }
      }
      break;

      case 'cancel':
      $content .= getMessage('Action cancelled');
      default:
      // Just show the list
   }

   $ary['section'] = 'admin';

   $chosenTemplate = getUserTemplates('',$ary['section']);
// print_r($chosenTemplate);

   if ($showList && $perm->have_perm('storyList', $sectionID)) {
      debug(__FILE__.__LINE__.' showList',$showList);

      $first = (isset($_GET['art_i']) && !empty($_GET['art_i'])) ? clean($_GET['art_i']) : 0;
      $count = (isset($_GET['art_n']) && !empty($_GET['art_n'])) ? clean($_GET['art_n']) : -1;
      $orderby = (isset($_GET['orderby']) && !empty($_GET['orderby'])) ? clean($_GET['orderby']) : 'id';
      $orderbyLogic = (isset($_GET['logic']) && !empty($_GET['logic'])) ? clean($_GET['logic']) : 'desc';
      $viewDeleted = (isset($_GET['viewDeleted']) && $_GET['viewDeleted']=='yes') ? TRUE : NULL;

      // Manipulate sort logic so that if you click twice on the title that the sort order changes
      $refererURL = (isset($_SERVER['HTTP_REFERER'])) ? parse_url($_SERVER['HTTP_REFERER']) : null;
      if (!empty($refererURL) && isset($_GET['orderby']) && ereg('orderby='.$_GET['orderby'], $refererURL['query']) &&
         ereg('art_i='.@$_GET['art_i'], @$refererURL['query']) &&
         ereg('art_n='.@$_GET['art_n'], @$refererURL['query'])) {
         if (isset($_GET['logic']) && $_GET['logic'] == 'desc') {
            $orderbyLogic = 'asc';
         } elseif (isset($_GET['logic']) && $_GET['logic'] == 'asc') {
            $orderbyLogic = 'desc';
         }
      }

      $content .= $articleObj->getArticles('', '', $first, $count, $orderby, TRUE, $orderbyLogic, $viewDeleted, 'admin');

   }

   if ($content == '') {
      $content = getTitlebar('100%', 'Error! Invalid Privileges');
      $content .= getError(pslGetText('Sorry. You do not have the necessary privilege to view this page.'));
   }

   $chosenTemplate = getUserTemplates('',$ary['section']);

   // Check if special header needed for wysiwyg editor
   $wysiwygHeader = ($submit == 'edit' || $submit == 'preview' || $submit == 'new');
   if (!empty($_BE['HTMLAREA']) && $wysiwygHeader) {
      if($_BE['HTMLAREA'] == 'FCKeditor') {
         $chosenTemplate['header'] = 'slashHead-fckeditor';
      } else {
         $chosenTemplate['header'] = 'slashHead-htmlarea3';
      }
   } else {
      $chosenTemplate['header'] = 'slashHead';
   }

   $breadcrumb = '<a href="' . $_PSL['rooturl'] . '/">' . pslgetText('Home') . '</a>'.$_BE['bread_delimiter']. $pagetitle;

   // generate the page
   generatePage($ary, $pagetitle, $breadcrumb, $content);

   page_close();

?>
