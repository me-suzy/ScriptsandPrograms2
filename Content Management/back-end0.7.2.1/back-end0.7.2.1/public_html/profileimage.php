<?php
   // $Id: profileimage.php,v 1.8 2005/03/27 10:28:35 krabu Exp $
   /**
    * Profiles - display images associated with profiles.  This file should display the profile image.
    *
    * @package     Back-End
    * @copyright   Copyright (C) 2003 OpenConcept Consulting
    * @version     $Id: profileimage.php,v 1.8 2005/03/27 10:28:35 krabu Exp $
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

   $_DB['uploadTable'] = 'WLPupload';

   if ($debug && $_GET['breakme']) {
      echo "I'm outputing something to break the headers.<br />\n";
      echo "This will allow me to see what exactly I'm sending.<br />\n";
      echo "Including errors :)<br />\n";
   }

   if (isset($_GET['ID'])) {
      $img = pslNew('BE_ProfileImage');
   } elseif (isset($_GET['uploadID'])) {
      $img = pslNew('BE_UploadImage');
   }

?>