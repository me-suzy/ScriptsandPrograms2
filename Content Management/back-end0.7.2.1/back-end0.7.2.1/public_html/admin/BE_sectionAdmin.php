<?php
   // $Id: BE_sectionAdmin.php,v 1.35 2005/06/21 19:20:36 mgifford Exp $
   /**
    * Back-End Section Administration
    *
    * Equivalent phpSlash file: sectionAdmin.php - the two files should be kept compatible
    *
    * Permissions are shared with phpSlash sections
    *
    * @package     Back-End on phpSlash
    * @copyright   2002 - Mike Gifford
    * @version     $Id: BE_sectionAdmin.php,v 1.35 2005/06/21 19:20:36 mgifford Exp $
    */

   require('./config.php');

   /* header title */
   $pagetitle = pslgetText('Section Administration');

   /* Defines The META TAG Page Type */
   $xsiteobject = pslgetText('Administration');
   $_PSL['metatags']['object'] = $xsiteobject;

   /*****************************
    START OF PAGE
    *****************************/

   $auth->login_if(!$perm->have_perm('sectionList'));

   $content = null;
   $ary = array();
   $showMap = true;

   /* DEBUG */
   # debug( "_POST", $_POST);
   # debug( "_GET", $_GET);
   # debug( "section_del", $section_del );
   # debug( "section_ary", $section_ary );
   # debug( "description", $description );

   $section    = pslNew('BE_Section_admin');

   // $submit     = pslgetText(getRequestVar('submit', 'PG'), '', true);
   $submit = decodeAction($_REQUEST);

   $sectionID  = getRequestVar('sectionID','PG');
   $itemID     = getRequestVar('itemID','PG');
   $next       = getRequestVar('next','PG');
   $languageID = getRequestVar('languageID','PG');

   // $languageID = getRequestVar('languageID','PG');

   if (empty($sectionID)) {
      $sectionID = $itemID;
   }

   # debug('submit', $submit);

   switch ($submit) {

      // VERSION HISTORY ACTIONS
      case 'history':
      if ($_PSL['module']['BE_History'] && $perm->have_perm('sectionSave')) {
         $history = pslNew('BE_HistorySection', $section);
         $content .= $history->showHistory($sectionID,$sectionID); //,$articleID,$languageID);
         $showMap = false;
      }
      break;

      case 'viewversion':
      if ($_PSL['module']['BE_History'] && $perm->have_perm('sectionSave')) {
         $history = pslNew('BE_HistorySection', $section);
         $content .= $history->showVersion($sectionID, clean($_GET['version']));
         $content .= $history->showHistory($sectionID,$sectionID); //,$articleID,$languageID);
         $showMap = false;
      }
      break;

      case 'revertversion':
      if ($_PSL['module']['BE_History'] && $perm->have_perm('sectionSave')) {
         $history = pslNew('BE_HistorySection', $section);
         $content .= $history->revertToVersion($sectionID, clean($_GET['version']));
         $content .= $history->showHistory($sectionID,$sectionID); //,$articleID,$languageID);
         $showMap = false;
      }
      break;

      case 'newmajorversion':
      if ($_PSL['module']['BE_History'] && $perm->have_perm('sectionSave')) {
         $history = pslNew('BE_HistorySection', $section);
         $history->setKey($sectionID);
         $history->newMajorVersion();
         $content .= $history->showHistory($sectionID,$sectionID); //,$articleID,$languageID);
         $showMap = false;
      }
      break;

      case 'compare':
      case 'diff':
      if ($_PSL['module']['BE_History'] && $perm->have_perm('sectionSave')) {
         # debug($submit, clean($_GET));
         $history = pslNew('BE_HistorySection', $section);
         $content .= $history->showDiff($sectionID, $sectionID, $languageID, clean(@$_GET['versions']));
         $content .= $history->showHistory($sectionID, $sectionID); //,$articleID,$languageID);
         $showMap = false;
      }
      break;

      // EDIT ACTIONS
      case 'delete':
      if ($perm->have_perm('sectionDelete')) {
         if (!$section->deleteSection($sectionID)) {
            $content .= $section->message;
         }
      }
      break;

      case 'save':
      if ($perm->have_perm('sectionSave')) {
#         $success = $section->saveSection(clean($_POST, TRUE)); // Cleaning is done within class
//Hack to hopefully prevent mistimed aborts - uncomment to activate
//Does not work in safe mode:
#set_time_limit(90);
#ignore_user_abort(true);
         $success = $section->saveSection($_POST);
         if ($success == false) {
            // pac - yes, I know the treatment of error messages is inconsistent
            #            $content .= getError(pslgettext('be_section_save_err'));
            $content .= $section->message;
#            $content .= $section->newSection(clean($_POST, TRUE), 'array');
            $content .= $section->newSection($_POST, 'array');
            $showMap = false;
         } else {
            // Jump to display of edited text rather than list -mg
            if (function_exists('jpcache_gc')) {
               // Clear internal cache
               jpcache_gc('string', clean($_POST['URLname']), '100');

               // If updating a current section, clear sectionID and children
               if (!empty($sectionID)) {
                  jpcache_gc('string', clean($_POST['sectionID']), '100');
                  $sectionSkeletonAry = $section->extractSkeleton();
                  $childSectionIDs = $sectionSkeletonAry[$sectionID]['children'];
               } else {
                   $childSectionIDs = array();
               }

               $relatedSectionsAry = clean(array_merge($childSectionIDs, $_POST['subSectionID']));

               foreach ($relatedSectionsAry as $relatedSectionID) {
                  $sectionDetails = $section->getSkeletonItem($relatedSectionID);
                  jpcache_gc('string', $relatedSectionID, '100');
                  jpcache_gc('string', $sectionDetails['URLname'], '100');

                  // Clear static cache
                  if ($_PSL['jpcache.enable'] == 'static') {
                     if (is_dir($_PSL['basedir'] . '/' . strtolower($sectionDetails['URLname']))) {
                        $JPCACHE_DIR = $_PSL['basedir'] . '/' . strtolower($sectionDetails['URLname']);
                        jpcache_gc('regex', "/.*/", '100');
                        jpcache_gc('string', '.html', '100');
                        rmdir($JPCACHE_DIR);
                     }
                     if (is_dir($_PSL['basedir'] . '/' . $relatedSectionID)) {
                        $JPCACHE_DIR = $_PSL['basedir'] . '/' . $relatedSectionID;
                        jpcache_gc('regex', "/.*/", '100');
                        jpcache_gc('string', '.html', '100');
                        rmdir($JPCACHE_DIR);
                     }
                  }

               }

               // Clear static cache
               if ($_PSL['jpcache.enable'] == 'static') {
                  $JPCACHE_DIR = $_PSL['basedir'] . '/' . strtolower(clean($_POST['URLname']));
                  jpcache_gc('regex', "/.*/", '100');
               }
            }

            if (!empty($_POST['slashSess'])) {
               $passSession = '?slashSess=' . clean($_POST['slashSess']);
            } else {
               $passSession = ''; // Done through cookies
            }

            // Redirect after successful save
            $URLname   = getRequestVar('URLname','P');
            Header("Location: " . $_PSL['rooturl'] . '/' . $_BE['article_file'] . '/' . $URLname . $passSession);

         }
      }
      break;

      case 'modify': // pac - not used?
      $content .= $section->getSections($ary, $next);
      $showMap = false;
      break;

      case 'preview':
      // echo "<pre>"; print_r($_REQUEST); echo "</pre>";
      // $_POST['name'] = clean($_POST['author_id']); // pac - needed?
      $content .= $section->showSection($_REQUEST);
      $content .= $section->newSection(clean($_REQUEST, true), 'array');
      $showMap = false;
      break;

      case 'edit':
      $content = $section->newSection(clean($_REQUEST, true), 'database');
      $showMap = false;
      break;

      case 'new':
      $content = $section->newSection(clean($_REQUEST, true), 'new');
      $showMap = false;
      break;


      case 'idDelete':
      if ($section->deleteSectionByID(clean($_POST['checkedSection']))) {
            $content .= getMessage($section->getMessage());
      }
      break;

      case 'idRestore':
      if ($section->restoreSectionByID(clean($_POST['checkedSection']))) {
            $content .= getMessage($section->getMessage());
      }
      break;

      case 'cancel':
      $content .= getMessage('Action cancelled');
      default:
      // Just show the map
   }

   $viewDeleted = (isset($_GET['viewDeleted']) && $_GET['viewDeleted']=='yes') ? TRUE : NULL;

   if ($showMap && $perm->have_perm('sectionList')) {
      $content .= $section->getSectionMap('html', $viewDeleted, 'Section Admin');
   }

   if ($content == '') {
      $content = getTitlebar('100%', 'Error! Invalid Privileges');
      $content .= getError(pslGetText('Sorry. You do not have the necessary privilege to view this page.'));
   }

   $ary['section'] = 'admin';

   $chosenTemplate = getUserTemplates('',$ary['section']);

   // Check if special header needed for wysiwyg editor
   $wysiwygHeader = ($submit == 'edit' || $submit == 'preview' || $submit == 'new');
   if (isset($_BE['HTMLAREA']) && $_BE['HTMLAREA'] && $wysiwygHeader) {
      if ($_BE['HTMLAREA'] == 2) {
         $chosenTemplate['header'] = 'slashHead-htmlarea2';
      } elseif($_BE['HTMLAREA'] == 'FCKeditor') {
         $chosenTemplate['header'] = 'slashHead-fckeditor';
      } else {
         $chosenTemplate['header'] = 'slashHead-htmlarea3';
      }
   } else {
      $chosenTemplate['header'] = 'slashHead';
   }

   $breadcrumb = '<a href="' . $_PSL['rooturl'] . '/">' . pslgetText('Home') . '</a> &#187; ' . $pagetitle;

   // generate the page
   generatePage($ary, $pagetitle, $breadcrumb, $content);

   page_close();

?>
