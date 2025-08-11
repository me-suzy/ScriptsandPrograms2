<?php

   /* $Id: groupAdmin.php,v 1.12 2005/05/13 18:12:47 mgifford Exp $ */

   require('./config.php');

   $pagetitle = pslgetText('Group Administration'); // header title
   $xsiteobject = pslgetText('Administration');     // Defines The META TAG Page Type
   $_PSL['metatags']['object'] = $xsiteobject;

   $content = null;
   $ary = array();

   /*****************************
    START OF PAGE
    *****************************/

   $auth->login_if(!$perm->have_perm('groupList'));

   /* DEBUG */
   # debug('HTTP_POST_VARS', $_POST);
   # debug('HTTP_GET_VARS', $_GET);
   # debug('group_name', $group_name);
   # debug('group_description', $group_description);
   /* DEBUG */

   $group = pslNew('Group');

   if (!empty($_POST['submit'])) {
      $submit = pslgetText(getRequestVar('submit', 'P'), '', true);
   } elseif (!empty($_GET['submit'])) {
      $submit = pslgetText(getRequestVar('submit', 'G'), '', true);
      $group_id = getRequestVar('group_id', 'GP');
      $permission_id = getRequestVar('permission_id', 'GP');
   } else {
      $submit = '';
   }

   if ($perm->have_perm('groupList')) {
      switch ($submit) {

         case 'delete':
         if ($perm->have_perm('groupDelete')) {
            $success = $group->deleteGroup($group_id);
            if ($success) {
               $content .= getMessage(pslgetText($group->getMessage()));
            } else {
               $content .= getError(pslgetText($group->getMessage()));
            }
         }
         if ($perm->have_perm('groupNew')) {
            $content .= $group->newGroup();
         }
         if ($perm->have_perm('groupList')) {
            $content .= $group->listGroup();
         }
         if ($perm->have_perm('permissionNew')) {
            $content .= $group->editPermission();
         }
         if ($perm->have_perm('permissionList')) {
            $content .= $group->listPermission();
         }
         break;
         case 'submit':
         if ($perm->have_perm('groupSave')) {
            $success = $group->saveGroup(clean($_POST));
            if ($success) {
               $content .= getMessage(pslgetText($group->getMessage()));
            } else {
               $content .= getError(pslgetText($group->getMessage()));
            }
         }
         if ($perm->have_perm('groupNew')) {
            $content .= $group->newGroup();
         }
         if ($perm->have_perm('groupList')) {
            $content .= $group->listGroup();
         }
         if ($perm->have_perm('permissionNew')) {
            $content .= $group->editPermission();
         }
         if ($perm->have_perm('permissionList')) {
            $content .= $group->listPermission();
         }
         break;

         case 'edit':
         $ary['group_id'] = clean($_GET['group_id']);
         // debug('ary[group_id]', $ary['group_id']);
         if ($perm->have_perm('groupEdit')) {
            $content .= $group->editGroup($ary);
         }
         if ($perm->have_perm('groupList')) {
            $content .= $group->listGroup();
         }
         break;

         case 'deleteperm':
         if ($perm->have_perm('permissionDelete')) {
            $success = $group->deletePermission($permission_id);
            if ($success) {
               $content .= getMessage(pslgetText($group->getMessage()));
            } else {
               $content .= getError(pslgetText($group->getMessage()));
            }
         }
         if ($perm->have_perm('groupNew')) {
            $content .= $group->newGroup();
         }
         if ($perm->have_perm('groupList')) {
            $content .= $group->listGroup();
         }
         if ($perm->have_perm('permissionNew')) {
            $content .= $group->editPermission();
         }
         if ($perm->have_perm('permissionList')) {
            $content .= $group->listPermission();
         }
         break;
         case 'submitperm':
         if ($perm->have_perm('permissionSave')) {
            $success = $group->savePermission(clean($_POST));
            if ($success) {
               $content .= getMessage(pslgetText($group->getMessage()));
            } else {
               $content .= getError(pslgetText($group->getMessage()));
            }
         }
         if ($perm->have_perm('groupNew')) {
            $content .= $group->newGroup();
         }
         if ($perm->have_perm('groupList')) {
            $content .= $group->listGroup();
         }
         if ($perm->have_perm('permissionNew')) {
            $content .= $group->editPermission();
         }
         if ($perm->have_perm('permissionList')) {
            $content .= $group->listPermission();
         }
         break;

         case 'editperm':
         $ary['permission_id'] = clean($_GET['permission_id']);
         // debug('ary[permission_id]', $ary['permission_id']);
         if ($perm->have_perm('permissionEdit')) {
            $content .= $group->editPermission($ary);
         }
         if ($perm->have_perm('permissionList')) {
            $content .= $group->listPermission();
         }
         break;

         default:
         if ($perm->have_perm('groupNew')) {
            $content .= $group->newGroup();
         }
         if ($perm->have_perm('groupList')) {
            $content .= $group->listGroup();
         }
         if ($perm->have_perm('permissionNew')) {
            $content .= $group->editPermission();
         }
         if ($perm->have_perm('permissionList')) {
            $content .= $group->listPermission();
         }
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