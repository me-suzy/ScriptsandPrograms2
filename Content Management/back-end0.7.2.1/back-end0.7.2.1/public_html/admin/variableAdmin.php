<?php

   // $Id: variableAdmin.php,v 1.11 2005/04/13 15:05:15 mgifford Exp $

   require('./config.php');

   # header title
   $pagetitle = pslgetText('Administration');

   #Defines The META TAG Page Type
   $xsiteobject = pslgetText('Administration');
   $_PSL['metatags']['object'] = $xsiteobject;

   if (!is_object($sess)) {
      page_open($_PSL['page_features']);
   }

   /*****************************
    START OF PAGE
    *****************************/

   $auth->login_if(!$perm->have_perm('variableList'));

   /* DEBUG */

   # debug("HTTP_POST_VARS" , $_POST);
   # debug("HTTP_GET_VARS" , $_GET);
   # debug("variable_name", $variable_name);

   /* DEBUG */

   $variable = pslNew('Variable');

   $content = null;
   $ary = array();

   if (!empty($_POST['submit'])) {
      $submit = pslgetText(getRequestVar('submit', 'P'), '', true);
      $variable_ary = clean($_POST['variable_ary']);
      $variable_name = clean($_POST['variable_name']);
      $variable_id = clean($_POST['variable_id']);
      $description = clean($_POST['description']);
      $variable_value = clean($_POST['variable_value']);
      $variable_group = clean($_POST['variable_group']);
   } elseif (!empty($_GET['submit'])) {
      $submit = pslgetText(getRequestVar('submit', 'G'), '', true);
   } else {
      $submit = '';
   }

   if ($perm->have_perm('variableList')) {
      switch ($submit) {
         case 'delete':
         if ($perm->have_perm('variableDelete')) {
            while (list($key, $val ) = each($variable_ary ) ) {
               $success = $variable->deleteVariable($variable_ary[$key]);
            }
         }
         break;
         case 'new':
         if ($perm->have_perm('variableSave')) {
            $success = $variable->saveVariable(clean($_POST));
         }
         break;
         case 'update':
         if ($perm->have_perm('variableEdit')) {
            reset ($variable_id);
            while (list($key, $val ) = each($variable_id ) ) {
               $ary['variable_id'] = $variable_id[$key];
               $ary['variable_name'] = $variable_name[$key];
               $ary['description'] = $description[$key];
               $ary['value'] = $variable_value[$key];
               $ary['variable_group'] = $variable_group[$key];

               if ($variable->saveVariable($ary)) {
                  $content .= "<em>$variable_name[$key]</em> has been updated<br />\n";
               } else {
                  $content .= "<em>$variable_name[$key]</em> has <strong>not</strong> been updated<br />\n";
               }

            }
         }
         break;
         default:
         break;
      }
      if ($perm->have_perm('variableNew')) {
         $content .= $variable->newVariable();
      }
      if ($perm->have_perm('variableList')) {
         $content .= $variable->listVariable();
      }

   } else {

      $content = getTitlebar('100%', 'Error! Invalid Privileges');
      $content .= 'Sorry. You do not have the necessary privilege to view this page.';

   }

   $ary['section'] = 'admin';

   $chosenTemplate = getUserTemplates('',$ary['section']);

   $breadcrumb = '<a href="' . $_PSL['rooturl'] . '/">' . pslgetText('Home') . '</a> &#187; ' .$pagetitle;

   // generate the page
   generatePage($ary, $pagetitle, $breadcrumb, $content);

   page_close();

?>