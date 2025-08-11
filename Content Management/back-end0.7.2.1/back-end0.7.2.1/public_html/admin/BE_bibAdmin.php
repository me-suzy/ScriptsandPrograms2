<?php
   // $Id: BE_bibAdmin.php,v 1.15 2005/06/16 19:36:27 mgifford Exp $
   /**
    * @package     Back-End
    * @copyright   Copyright (C) 2003 OpenConcept Consulting
    * @version     $Id: BE_bibAdmin.php,v 1.15 2005/06/16 19:36:27 mgifford Exp $
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
   $pagetitle = pslgetText('Bibliography Administration'); // The name to be displayed in the header
   $xsiteobject = pslgetText('Administration');            // Defines The META TAG Page Type

   /*****************************
    START OF PAGE
    *****************************/

   $auth->login_if(!$perm->have_perm('bibliography'));

   $bib = pslNew('BE_Bibliography');

   $submit = pslgetText(getRequestVar('submit', 'PG'), '', true);
   $next = getRequestVar('submit', 'PG');
   $nextPerson = getRequestVar('nextPerson', 'PG');
   $delete_people = getRequestVar('delete_people', 'PG');
   $numberPeople = getRequestVar('numberPeople', 'P');

   switch ($submit) {
      case 'genMLA':
      $bib->genAllMLA();
      break;

      case 'delete':
      $content = $bib->deleteBib($bibID);
      $content .= $bib->listBib($author_id, $next);
      break;

      case 'save':
      $success = $bib->saveBib(clean($_POST));
      if ($success == false) {
         $content = '<big>Error Saving Changes..</big>';
         $content .= $bib->newBib(clean($_REQUEST), 'array');
      } else {
         $content = '<big>Changes Saved...</big>';
         $content .= $bib->listBib($author_id, $next);
      }
      break;

      case 'edit':
      $_POST[bibID] = $bibID;
      $content = $bib->newBib(clean($_REQUEST), 'database');
      break;

      case 'new':
      $content = $bib->newBib(clean($_REQUEST), 'new');
      break;

      case 'Add':
      case 'add':
      // add authors to an edit
      $_POST['numberPeople'] = $nextPerson;
      $newfirst = "firstName$nextPerson";
      $newmiddle = "middleName$nextPerson";
      $newlast = "lastName$nextPerson";

      // strip spaces(?!?)
      $_POST[$newfirst] = @trim($$newfirst);
      $_POST[$newmiddle] = @trim($$newmiddle);
      $_POST[$newlast] = @trim($$newlast);

      $content = $bib->newBib(clean($_REQUEST), 'array');
      break;

      case 'Del':
      case 'del':
      // delete authors link
      if (is_array($delete_people)) {
         /// print "start: $numberPeople<br /><pre>\n";
         // print_r($delete_people);
         // print "</pre>\n";
         $good = 1;
         for($old = 1 ; $old <= $numberPeople ; $old++) {

            if (!in_array($old, $delete_people)) // means we keep it!
            {
               // not same position - replace old values with new
               if ($old != $good)
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

                  $_POST[$goodfirstvar] = $oldfirstvar;
                  $_POST[$goodmiddlevar] = $oldmiddlevar;
                  $_POST[$goodlastvar] = $oldlastvar;
                  $_POST[$goodrolevar] = $oldrolevar;
                  $_POST[$goodroleidvar] = $oldroleidvar;
                  $_POST[$goodprofileidvar] = $oldprofileidvar;

                  // print "push: $old to $good<br />";
               } else {
                  // print "keep: $old<br />";
               }

               $good++;

            } else {
               print "toast: $old<br />";
            }
         }
         $numberPeople = $good - 1;
         $_POST['numberPeople'] = $numberPeople;

         //echo "final: $numberPeople<br />";
      }
      $content = $bib->newBib(clean($_REQUEST), 'array');

      break;

      default:
      $content = $bib->listBib(clean($_POST), $next);
   }

   $ary = array();
   $ary['section'] = 'admin';
   $chosenTemplate = getUserTemplates('',$ary['section']);

   $breadcrumb = '<a href="' . $_PSL['rooturl'] . '/">' . pslgetText('Home') . '</a> &#187; ' .$pagetitle;

   generatePage($ary, $pagetitle, $breadcrumb, $content);
   page_close();

?>
