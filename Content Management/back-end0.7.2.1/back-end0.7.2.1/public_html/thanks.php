<?php
   // $Id: thanks.php,v 1.10 2005/03/27 10:28:35 krabu Exp $
   /**
    * Profiles - Thanks message for submitting a nomination
    *
    * @package     Back-End
    * @copyright   Copyright (C) 2003 OpenConcept Consulting
    * @version     $Id: thanks.php,v 1.10 2005/03/27 10:28:35 krabu Exp $
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

   require_once('./config.php');

   $pagetitle = pslGetText('Thank you for submitting a nomination'); // The name to be displayed in the header
   $xsiteobject = pslGetText('Thank you');
   // Defines The META TAG Page Type

   /*****************************
    START OF PAGE
    *****************************/

   $pro = pslNew('BE_Profiles');

   $submit = pslgetText(getRequestVar('submit', 'PG'), '', true);

   if ($submit == 'save') {
      if ($pro->saveNom(clean($_POST))) {
      ?>

<p>Thank you for submitting a nomination for WLP's Directory of Women Leaders from the Global South.  Your submission will be reviewed by the Profiles committee before appearing in the <a href="viewProfiles.php">View Profiles</a> section.</p>
<p>If you would like to send a photo to accompany the nomination, please email it to us at <a href="mailto:wlp@learningpartnership.org">wlp@learningpartnership.org</a>.  If you would like to include a resume or CV in the profile, you may send it to the same email address.</p>

<p>Thank you, <br /><i>Women's Learning Partnership</i></p>

      <?php
      } else {
         echo '<p>Error Saving Changes.</p>';
         $content = $pro->newNom(clean($_POST), 'array');
      }
   } else {
      $content = $pro->indexList($level, $next);
      $content .= $pro->indexSearch($level, $next);
   }

   $ary = array();
   $ary['section'] = 'Home';
   $chosenTemplate = getUserTemplates();
   generatePage($ary, $pagetitle, '', $content);
   page_close();

?>