<?php
   // $Id: infologAdmin.php,v 1.17 2005/04/13 15:05:14 mgifford Exp $

   require('./config.php');

   // Header title
   $pagetitle = pslgetText('Administration Log');

   // Defines The META TAG Page Type
   $xsiteobject = pslgetText('Administration');
   $_PSL['metatags']['object'] = $xsiteobject;

   $content = null;
   $ary = array();

   $log = pslNew('Infolog');

   /*****************************
    START OF PAGE
    *****************************/
   $auth->login_if(!$perm->have_perm('infologList'));

   # debug( 'HTTP_POST_VARS' , $_POST);
   # debug( 'HTTP_GET_VARS' , $_GET);

   $first = (isset($_GET['log_i']) && !empty($_GET['log_i'])) ? clean($_GET['log_i']) : 0;
   $count = (isset($_GET['log_n']) && !empty($_GET['log_n'])) ? clean($_GET['log_n']) : -1;
	$orderby = (isset($_GET['orderby']) && !empty($_GET['orderby'])) ? clean($_GET['orderby']) : 'id';
	$orderbyLogic = (isset($_GET['logic']) && !empty($_GET['logic'])) ? clean($_GET['logic']) : 'desc';

   // Manipulate sort logic so that if you click twice on the title that the sort order changes
   $refererURL = parse_url($_SERVER['HTTP_REFERER']);
   if (ereg('orderby='.@$_GET['orderby'], @$refererURL['query']) &&
      ereg('log_i='.@$_GET['log_i'], @$refererURL['query']) &&
      ereg('log_n='.@$_GET['log_n'], @$refererURL['query'])) {
      if (isset($_GET['logic']) && $_GET['logic'] == 'desc') {
         $orderbyLogic = 'asc';
      } elseif (isset($_GET['logic']) && $_GET['logic'] == 'asc') {
         $orderbyLogic = 'desc';
      }
   }

   if ($perm->have_perm('infologList')) {
      $submit = pslgetText(getRequestVar('submit', 'P'), '', true);
      $search = pslGetText(getRequestVar('search', 'PG'), '', true);
      switch ($submit) {
         case 'Delete':
         case 'IdDelete':
         # debug('loginfoadmin::infolog_ary', $infolog_ary);
         if ($log->deleteLogByID(clean($_POST['infolog_ary']))) {
            $content .= getMessage($log->getMessage());
         }
         $content .= $log->pageOut($first, $count, $orderby, $orderbyLogic, $search);
         break;
         case 'keyDelete':
         if ($log->deleteLogByKeyword(clean($_POST['infolog_delete_key']))) {
            $content .= getMessage($log->getMessage());
         }
         $content .= $log->pageOut($first, $count, $orderby, $orderbyLogic, $search);
         break;
         case 'allDelete':
         if ($log->deleteAllLogs()) {
            $content .= getMessage($log->getMessage());
         }
         $content .= $log->pageOut($first, $count, $orderby, $orderbyLogic, $search);
         break;
         default:
         $content .= $log->pageOut($first, $count, $orderby, $orderbyLogic, $search);
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