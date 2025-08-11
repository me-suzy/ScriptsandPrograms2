<?php
   // $Id: BE_profileAdmin.php,v 1.13 2005/04/13 15:05:14 mgifford Exp $
   /**
    * @package     Back-End
    * @copyright   Copyright (C) 2003 OpenConcept Consulting
    * @version     $Id: BE_profileAdmin.php,v 1.13 2005/04/13 15:05:14 mgifford Exp $
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
   require_once('config.php');

   // Security: Don't use User Vars
   $pagetitle = pslgetText('Profile Administration'); // The name to be displayed in the header
   $xsiteobject = pslgetText('Administration');       // Defines The META TAG Page Type

   /*****************************
    START OF PAGE
    *****************************/

   $auth->login_if(!$perm->have_perm('bibliography'));

   $pro = pslNew('BE_Profiles');

   $content = null;
   $ary = array();

   $submit = pslgetText(getRequestVar('submit', 'PG'), '', true);
   $profileID = getRequestVar('profileID', 'PG');
   $author_id = getRequestVar('author_id', 'PG');
   $next = getRequestVar('next', 'PG');
   $numberPeople = getRequestVar('numberPeople', 'PG');

   switch ($submit) {
      case 'photo':
      $content = $pro->photoPro($profileID);
      break;

      case 'photoDelete':
      $content = $pro->photoProDelete($profileID);
      break;

      case 'merge':
      $content = $pro->mergePro();
      break;

      case 'delete':
      $content = $pro->deletePro($profileID);
      $content .= $pro->listPro($author_id, $next);
      break;

      case 'save':
      case 'SAVE':
      $success = $pro->savePro(clean($_POST));
      if ($success == false) {
         $content = '<p><big>Error Saving Changes..</big></p>';
         $pro->newPro(clean($_POST), 'array');
      } else {
         $content = '<p><big>Changes Saved...</big></p>';
         $content .= $pro->listPro($author_id, $next);
      }
      break;

      case 'edit':
      $_POST['profileID'] = clean($profileID);
      $content = $pro->newPro(clean($_POST), 'database');
      break;

      case 'new':
      $content = $pro->newPro(clean($_POST), 'new');
      break;

      case 'Add':
      case 'add':
      // add authors to an edit
      $_POST['numberPeople'] = $numberPeople + 1;
      $content = $pro->newPro(clean($_POST), 'array');
      break;

      case 'Del':
      case 'del':
      // delete authors link

      //print "start: $numberPeople<br /><PRE>\n";
      //print_r($delete_people);
      //print "</PRE>\n";
      $good = 1;
      for($old = 1 ; $old <= $numberPeople ; $old++) {
         if (!in_array($old, $delete_people)) // means we keep it!
         {
            if ($old != $good) // not same position
            {
               $oldfirstvar = "firstName$old";
               $oldmiddlevar = "middleName$old";
               $oldlastvar = "lastName$old";
               $oldrolevar = "role$old";
               $oldroleidvar = "roleid$old";
               $oldprofileidvar = "profileID$old";

               $goodfirstvar = "firstName$good";
               $goodmiddlevar = "middleName$good";
               $goodlastvar = "lastName$good";
               $goodrolevar = "role$good";
               $goodroleidvar = "roleid$good";
               $goodprofileidvar = "profileID$good";

               $_POST[$goodfirstvar] = $$oldfirstvar;
               $_POST[$goodmiddlevar] = $$oldmiddlevar;
               $_POST[$goodlastvar] = $$oldlastvar;
               $_POST[$goodrolevar] = $$oldrolevar;
               $_POST[$goodroleidvar] = $$oldroleidvar;
               $_POST[$goodprofileidvar] = $$oldprofileidvar;

               //print "push: $old to $good<br />";
            } else {
               //print "keep: $old<br />";
            }

            $good++;
         } else {
            //print "toast: $old<br />";
         }
      }
      $numberPeople = $good - 1;
      $_POST['numberPeople'] = $numberPeople;

      //echo "final: $numberPeople<br />";
      $content = $pro->newPro(clean($_POST), 'array');

      break;

      default:
      $content = $pro->listPro($profileID, $next);
   }

   echo '&nbsp;';

   $ary['section'] = 'admin';

   $chosenTemplate = getUserTemplates('',$ary['section']);

   $breadcrumb = '<a href="' . $_PSL['rooturl'] . '/">' . pslgetText('Home') . '</a> &#187; ' . $pagetitle;

   generatePage($ary, $pagetitle, $breadcrumb, $content);
   page_close();

?>