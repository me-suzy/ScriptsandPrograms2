<?php
   // $Id: regions.php,v 1.8 2005/03/27 10:28:35 krabu Exp $
   /**
    * Bibliography - display references by category
    *
    * @package     Back-End
    * @copyright   Copyright (C) 2003 OpenConcept Consulting
    * @version     $Id: regions.php,v 1.8 2005/03/27 10:28:35 krabu Exp $
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
   unset($seclev);
   require_once('config.php');

   // Security: Don't use User Vars
   $pagetitle = pslGetText('Bibliography'); // The name to be displayed in the header
   $xsiteobject = pslGetText('Information');
   // Defines The META TAG Page Type

   /*****************************
    START OF PAGE
    *****************************/

   $bib = pslNew('BE_Bibliography');

   $region = (isset($_GET['region'])) ? clean($_GET['region']) : '';
   $country = (isset($_GET['country'])) ? clean($_GET['country']) : '';
   $content = $bib->regionList($region, $country);

   $ary = array();
   $ary['section'] = 'bibRegion';

   // getUserTemplates();
   $chosenTemplate = getUserTemplates('', $ary['section']);

   generatePage($ary, $pagetitle, '', $content);
   page_close();

?>