<?php
/**
* Globally available Back-end functions
*
* To help you find functions, this file is divided into several sections
* - INFO ON CURRENT ENVIRONMENT
* - FUNCTIONS THAT GENERATE HTML FOR INCLUSION IN BACK-END PAGES
* - URL INTERPRETATION, GENERATION, MANIPULATION
* - LOCALE: LANGUAGE AND DATE/TIME
* - STRING & VARIABLE MANIPULATION
* - DB HELPER FUNCTIONS
* - FILE/CACHE MANAGEMENT
*
*
* @see BE_Page.inc - for generatePage etc
*
* DEVELOPERS!
* If you have to add a new function - please make sure that you add it to the appropriate section
* of this file.
* If possible, work by extending an existing function rather than adding a new one (which will only
* be used by your code, at least to start with)
*
* @todo Rename them so they all start be_
*
* @package     Back-End
* @version     $Id: BE_functions.inc,v 1.128 2005/06/21 22:48:37 iclysdal Exp $
* @copyright   Copyright (C) 2004 OpenConcept Consulting
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
*
*/


// ** INFO ON CURRENT ENVIRONMENT         *******************************************************

   /**
   * @return string
   */
   function be_getCurrentAuthorName() {
      global $auth;

      return $auth->auth['dname'];

   } // end be_getCurrentAuthorID


   /**
   * @return string
   */
   function getTrace() {
      if(!function_exists('debug_backtrace')) {
         return pslgetText('The function debug_backtrace doesn\'t exist');
      }
      $vDebug = debug_backtrace();
      $vFiles = array();
      $count_vDebug = count($vDebug);
      if ($count_vDebug == 0) {
        return false;
      }

      for ($i=0;$i<$count_vDebug;$i++) {
        // skip the first one, since it's always this func
        if ($i==0) { continue; }
        $aFile = $vDebug[$i];
        $vFiles[] = basename($aFile['file']).':'.$aFile['function'].':'.$aFile['line'];
      } // for

      $vTraceStr = "\n<ul><li>" . implode("</li>\n<li>",$vFiles) . "\n</li></ul>\n";

      return $vTraceStr;

   }

   /**
   * @return string
   */
   function be_getCurrentAuthorID() {
      global $auth;

      return $auth->auth['uid'];

   } // end be_getCurrentAuthorID


   /**
   * @return string
   */
   function be_getCurrentAuthorEmail() {
      global $auth;

      return $auth->auth['email'];

   } // end be_getCurrentAuthorEmail


   /**
   * @return string
   */
   function be_getCurrentSection() {
      global $_BE;

      return $_BE['currentSection'];

   } // end be_getCurrentSection


   /**
   * @return string
   */
   function be_getCurrentSectionURLname() {
      global $_BE;

      return $_BE['currentSectionURLname'];

   } // end be_getCurrentSectionURLname


   /**
    * Checks whether we are currently in a subsite
    *
    * Subsites are managed in the BE_Subsite.class file
    *
    * @author Peter Cruickshank
    * @return boolean
    * @access public
    */
   function be_inSubsite() {
      global $BE_subsite;

      return (isset($BE_subsite['inSubsite']) && $BE_subsite['inSubsite']);

   } // end be_inSubsite


// ** FUNCTIONS THAT GENERATE HTML FOR INCLUSION IN BACK-END PAGES ******************************


      /**
       * Create section select boxes
       *
       * Result is cached for later use since the generation code is pretty DB heavy
       *
       * @param string $objectType='section'
       * @param string $adminOption='edit'
       * @param integer $sectionID=NULL
       * @param array $selectedSections
       * @param slashTemplate &$template
       * @param string $templateBlockName = 'select_section_row'
       * @param integer $subsiteID
       * @access public
       */
      function generateSectionSelectInputField($objectType='section', $adminOption='edit', $sectionID=NULL, $selectedSections, &$template, $templateBlockName = 'select_section_row', $subsiteID=NULL) {
         global $_BE, $BE_subsite, $_PSL;

         # debug_calledFrom();

         if(empty($subsiteID)) {
            $subsiteID = (!empty($BE_subsite['subsite_id'])) ? $BE_subsite['subsite_id'] : '';
         }

         // Default expiry time
         $cacheExpiryTime = $_PSL['cacheExpiryTime'];
         $cacheExpiryDate = date('Y-m-d H:i:s', time() + $cacheExpiryTime);

         //  Create the cache object
         $sectionHash = md5(serialize($selectedSections));
         $cacheObject = new fileCache('sectionSelectInputField', "$objectType-$adminOption-$sectionID-$sectionHash-$templateBlockName-$subsiteID", $cacheExpiryDate);

         //  Try to retrieve the cached top level sections
         $sectionSelectInputField = $cacheObject->retrieve();
         if(!empty($sectionSelectInputField)) {
            $template->set_block($templateBlockName, $templateBlockName.'s');
            $template->set_var(array($templateBlockName.'s'=>$sectionSelectInputField));
            return;
         }

         $sectionObj = & pslNew('BE_Section');

         // Create array to edit an object (in drawing the section list for sections,
         // the current sectionID should be excluded)
         if ($objectType=='section' && $adminOption != 'new') {
            $allSections_ary = $sectionObj->extractAllSections($sectionID, 'full', '', '', '', $subsiteID);
            $excludeSection = $sectionID;
            $selectedSections_ary = (is_array($selectedSections)) ? $selectedSections : array($selectedSections);
         } else {
            $allSections_ary = $sectionObj->extractAllSections('', 'full', '', '', '', $subsiteID);
            $excludeSection = NULL;
            $selectedSections_ary = ($adminOption != 'new') ? $selectedSections : array($selectedSections);
         }

         // $template->debug = 1;

         // Build indented parent-section selection box
         if (@$_BE['indentedSectionAdmins']) {

            be_displayIndentedSections($template, $sectionObj, $selectedSections_ary, $excludeSection, $templateBlockName, $subsiteID);

         } else {
            // Simple list of sections

            foreach ($allSections_ary AS $v) {
               # debug("newSection allSecs($k)",$v);

               $sel_sectionID = $v['sectionID'];
               if (in_array($sel_sectionID, $selectedSections_ary)) {
                  $selected = 'selected="selected"';
               } elseif ($adminOption == 'new' && $sectionID == $selectedSections) {
                  $selected = 'selected="selected"';
               } else {
                  $selected = '';
               }

               $template->set_var(array(
                  'SEL_SECTION_ID' => $v['sectionID'],
                  'SEL_SECTION_URLNAME' => $v['URLname'],
                  'SECTIONPREFIX' => isset($_BE['subsite.sectionprefix']) ? $_BE['subsite.sectionprefix'] : '',
                  'SEL_SECTION_TITLE' => $v['title'],
                  'SELECTED' => $selected
               ));
               $template->parse($templateBlockName . 's', $templateBlockName, true);
            }
         }

         $sectionSelectInputField = $template->get($templateBlockName.'s');
         $cacheObject->store($sectionSelectInputField);

      } //end generateSelectInputField


      /**
       *  This function is used by block, article, section, etc admins to
       * display sections in an indented hierarchical manner that makes
       * it easier to figure what belongs where when you have 500+ sections
       *
       * @access public
       */
      function be_displayIndentedSections(&$template, $sectionObj, $selectedSections, $excludeSection = '', $templateBlockName, $subsiteID=null, $parentBlock=null) {
         global $BE_subsite, $BE_subsite;

         $article2section = $sectionObj->extractSkeleton();

         if(empty($subsiteID)) {
            $subsiteID = (!empty($BE_subsite['subsite_id'])) ? $BE_subsite['subsite_id'] : '';
         }

         $allSections = $sectionObj->extractAllSections($excludeSection, 'full', 'sectionID', 'asc', '', $subsiteID);

         // $template->debug = 1;
         if (!empty($parentBlock)) {
            $template->set_block($parentBlock, $templateBlockName, $templateBlockName.'s');
         } else {
            $template->set_block($templateBlockName, $templateBlockName.'s');
         }

         foreach($allSections AS $v) {
            if($v['parentSectionID'] < 1) {
               be_displayIndentedSectionsRecursive($template, $v, $allSections, $article2section, $selectedSections, 0, $templateBlockName);
            }
         }

      } // end be_displayIndentedSections


      /**
      * @access private
      */
      function be_displayIndentedSectionsRecursive(&$template, $section, $sectionAry, $article2section, $selectedSections = '', $indent = 0, $templateBlockName = 'select_section_row') {
         global $_BE;

         $indentStr = str_repeat('-----',$indent);
         $indentSpace = str_repeat('&nbsp;&nbsp;',$indent);
         $selected = ' ';
         $sel_sectionID = $section['sectionID'];
         if(is_array($selectedSections)) {
            if(in_array($sel_sectionID, $selectedSections)) {
               $selected = 'selected="selected"';
            }
         }



         $sectionTitle = (!empty($section['title'])) ? $section['title']: pslgetText('No Title Defined');
         $sectionLink = (!empty($section['URLname'])) ? $section['URLname']: $section['sectionID'];

         $template->set_var(array(
            'INDENT'                => $indentStr,
            'INDENTDEPTH'           => $indent,
            'INDENTSPACE'           => $indentSpace,
            'SELECTED'              => $selected,
            'SEL_SECTION_ID'        => $section['sectionID'],
            'SEL_SECTION_TITLE'     => $sectionTitle,
            'SEL_SECTION_URLNAME'   => $sectionLink,
            'SECTIONPREFIX'         => isset($_BE['subsite.sectionprefix']) ? $_BE['subsite.sectionprefix'] : '',

            # Kept for psl blocks and backward compatibility with templates
            'SECTION_ID'            => $section['sectionID'],
            'SECTION_NAME'          => $section['title']
         ));

         $template->parse($templateBlockName . 's', $templateBlockName, true);

         if (!empty($section['sectionID'])) {
            // These next two lines only work if articles exist for this section
            $skel = @$article2section[$section['sectionID']];
            $children = @$skel['children'];

            // Do stuff if in there are in fact some articles
            if(is_array($children)) {
               foreach($children AS $child) {
                  if (isset($child) && !empty($sectionAry[$child])) {
                     be_displayIndentedSectionsRecursive($template, $sectionAry[$child], $sectionAry, $article2section, $selectedSections, $indent+1, $templateBlockName);
                  }
               }
            }
         }

      } // end be_displayIndentedSectionsRecursive


   /**
   * A whole new error-reporting model
   * @since 2004 sometime
   * @access public
   * @return htmlstring
   */
   function be_generateFieldProblemsMessage($problemFields) {
      $message = pslgetText('The following fields have problems') . ': <ul>';
      foreach ($problemFields as $field => $problem) {
         $message .= '<li>' . pslgetText($field) . ' ' . pslgetText('is') . ' ' . pslgetText($problem) . '</li>';
      }
      $message .= '</ul>';

      return $message;
   } // end be_generateFieldProblemsMessage


   /**
   * Generated admin buttons for inclusion in pages
   *
   * This assumes that all modules recognise submit actions 'new', 'delete' and 'edit'
   * - it's not difficult to add other actions if needed though.
   * Requires button-image names to be tied into the action name.
   *
   * @param array $param Reference to object to be included in generated url link
   *                   id     integer
   *                   name   (eg sectionID, articleID, linkID). Default itemID
   *                   title  Name to appear on rollovers (eg Section, Link) - will be translated to current language. Default = 'Item'
   *                   sectionID (Optional) -  to preserve in the link that's generated
   *                   actions Ordered list of buttons to generate. Values can currently be 'delete', 'edit', 'add'.
   *                          Unrecognised actions are ignored
   *                   target The url for the buttons to link to (default is $_PSL['phpself'])
   *                   targetUrl (Optional) - default is $_PSL['adminurl']
   *                   dom_id (Optional) Used to ensure that the image id is unique in the DOM so image rollover works
   *                             eg for add/help buttons at top and bottom of screen, you'd use 'top' and then 'bottom'
   *                   manual reference to relevant part of online manual. Adding this will generate a help button after all the others
   *
   * @return htmlstring Series of buttons
   * @access public
   */
   function be_generateAdminLinks($param) {
      global $_PSL, $_BE;

      $manualUrl  = $_BE['manual.url'];

      $imageUrl   = $_PSL['imageurl'];
      $imageSize  = empty($_BE['adminbutton.width'])  ? '' : ' width="' . $_BE['adminbutton.width'] . '"';
      $imageSize  .= empty($_BE['adminbutton.height']) ? '' : ' height="' . $_BE['adminbutton.height'] . '"';

      $objectId    = isset($param['id']) ? $param['id']   : '';

      $buttonTitle = isset($param['title'])  ? $param['title']: 'Item';
      $objectName  = isset($param['name'])   ? $param['name'] : 'itemID';
      $target      = isset($param['target']) ? $param['target'] : $_PSL['phpself'];
      $sectionID   = isset($param['sectionID']) ? '&amp;sectionID='.$param['sectionID'] : '';
      $adminUrl    = isset($param['targetUrl']) ? $param['targetUrl'] : $_PSL['adminurl'];
      $addParamName   = isset($param['addParamName'])  ? $param['addParamName'] : '';
      $addParamValue  = isset($param['addParamValue']) ? $param['addParamValue'] : '';
      $confText  = isset($param['confText']) ? $param['confText'] : pslgetText('Are you sure you wish to delete');

      $uniqueId   = isset($param['dom_id']) ? '_' . $param['dom_id'] : '';
      $buttonId   = $objectName . $objectId . $uniqueId;

      $buttons = '';
      $confirm = '';
      if (isset($param['actions']) && is_array($param['actions'])) {
         foreach ($param['actions'] as $action) {
            $objectTitle = (isset($param['objectTitle'])  && $action != 'new') ? ' (' . $param['objectTitle'] . ')' : '';

            // Allow for old psl submit values
            $submitValue  = (isset($param['submitValue'][$action])) ? $param['submitValue'][$action] : $action;

            switch ($action) {
            case 'delete':
               $confirm = ' onclick="return confirm(\'' . $confText  . ' '. strtolower(addslashes($buttonTitle)) . ' id ' . $objectId . addslashes($objectTitle) . '?\');" onkeypress="return confirm(\'' . $confText  . ' '. strtolower(addslashes($buttonTitle)) . addslashes($objectTitle) . '?\');"';
            case 'new':
            case 'edit':
            case 'copy':
            case 'view':
               $description = pslgetText(ucfirst($action)) . ' ' . pslgetText(ucfirst($buttonTitle)) . ' ';
               $description .= ($action != 'new') ? $objectId . $objectTitle : '';
               $button = "\t<a href=\"$adminUrl/$target?submit=$submitValue";
               $button .= (!empty($objectId)) ? "&amp;$objectName=$objectId" : '';
               $button .= (!empty($addParamName) && $addParamValue) ? "&amp;$addParamName=" . htmlentities(urlencode($addParamValue)) : '';
               $button .= $sectionID . '" title="' . $description . '"
                  onmouseover="document.getElementById(\'' . $action . $buttonId . '\').src=\'' . $imageUrl . '/BE/buttons/btn_' . $action . '_over.gif\'"
                  onmouseout="document.getElementById(\'' . $action . $buttonId . '\').src=\'' . $imageUrl . '/BE/buttons/btn_' . $action . '_dim.gif\'"' . $confirm. '>
                  <img src="' . $imageUrl . '/BE/buttons/btn_' . $action . '_norm.gif"' . $imageSize . '  alt="' . $description . '" id="' . $action . $buttonId . '" class="' . $action .'Icon" />
                  </a>' . "\n";
               break;
            default:
               # debug('be_gAL invalid action',$action);
            }
            $buttons .= $button;
         }
      }

      if (isset($param['manual']) && !empty($manualUrl)) {
         // A help button has been requested, and there is a manual to refer it to...
         $button = "\t<a href=\"$manualUrl/$param[manual]\" target=\"_blank\" title=\"" . pslgetText('Help') . ' ' . pslgetText(ucfirst($buttonTitle)) . "\"" .
                  " onmouseover=\"document.getElementById('Help$buttonId').src='$imageUrl/BE/buttons/btn_info_over.gif'\"".
                  " onmouseout=\"document.getElementById('Help$buttonId').src='$imageUrl/BE/buttons/btn_info_dim.gif'\">" .
                  "<img src=\"$imageUrl/BE/buttons/btn_info_norm.gif\" $imageSize  alt=\"" .
                  pslgetText('Help') . "\" id=\"Help$buttonId\" class=\"helpIcon\" /></a>\n";
         $buttons .= $button;
      }

      return $buttons;

   } // end be_generateAdminLinks


   /**
   * Please use dateInput2 which will replace this function in due course.
   *
   * @deprecated June 2004
   * @see dateInput2 below
   *
   * function dateInput($day, $month, $year, $dayName = 'day', $monthName = 'month', $yearName = 'year', $initialState = 'today') {
      // global $pslstrings; // This should be able to bring in $pslstrings['January']; from class/locale/en.php but it isn't working
      // Set defaults
      if ($initialState == 'today') {
         if (empty($day) OR $day == 0) $day = date('d');
            if (empty($month) OR $month == 0) $month = date('m');
            if (empty($year) OR $year == 0) $year = date('Y');
         }
      // Define Months
      $monthDef[1] = pslgetText('January');
      $monthDef[2] = pslgetText('February');
      $monthDef[3] = pslgetText('March');
      $monthDef[4] = pslgetText('April');
      $monthDef[5] = pslgetText('May');
      $monthDef[6] = pslgetText('June');
      $monthDef[7] = pslgetText('July');
      $monthDef[8] = pslgetText('August');
      $monthDef[9] = pslgetText('September');
      $monthDef[10] = pslgetText('October');
      $monthDef[11] = pslgetText('November');
      $monthDef[12] = pslgetText('December');

      // Day Dropdown
      $return = "\n<select name=\"$dayName\">\n" ;
      $return .= "        \n<option class=\"form_select\" value=\"0\">Day\n</option>" ;
      for ($ii = 1; $ii <= 31; ++$ii) {
         if ($ii == $day ) {
            $sel = ' selected="selected"' ;
         } else {
            $sel = '' ;
         }
         $return .= "        \n<option class=\"form_select\" value=\"".$ii."\"".$sel.">".$ii."</option>\n" ;
      }
      $return .= "\n</select>\n";

      // Month Dropdown
      $return .= "\n<select name=\"".$monthName."\">\n" ;
      $return .= "        \n<option class=\"form_select\" value=\"0\">Month</option>\n" ;
      for ($ii = 1; $ii <= 12; ++$ii) {
         if ($ii == $month ) {
            $sel = ' selected="selected"' ;
         } else {
            $sel = '' ;
         }
         $return .= "        \n<option class=\"form_select\" value=\"".$ii."\"".$sel.">".$monthDef[$ii]."</option>\n" ;
      }
      $return .= "\n</select>\n";

      // Year Field
      $return .= "\n<input class=\"form_input\" type=\"text\" name=\"$yearName\" size=\"4\" value=\"$year\" />";

      return $return;

   } // end dateInput
*/

   /**
    * Creates HTML form input fields to specify a date/time. Resolution of the form
    * is specified by $_BE['DateInputResolution']. Times are adjusted based on
    * user timezone, i.e. the input timestamp is expected to be in the
    * server's timezone, form times are adjosted to be in the user's timezone.
    *
    * @param int $initialValues - Unix timestamp for initial (selected) field values
    * @param array $fieldNames - array of field names to use for Year, Mon, Day, Hour, Min, Sec
    * @param string $initialState - 'today' | anything - if 'today' any empty field will be set to now
    * @param boolean $showTimeNow
    * @param string $dateResolution
    * @return HTML string
    * @access public
    */
   // function dateInput2($initialValues, $fieldNames, $initialState = 'today', $showTimeNow = true, $dateResolution = NULL) {
   function dateInput2($initialTimestamp, $fieldNames, $initialState = 'today', $showTimeNow = true, $dateResolution = null) {
      global $_BE;

// echo "<br>A) 1 $initialTimestamp, 2 $fieldNames, 3 $initialState, 4 $showTimeNow, 5 $dateResolution now = " . time();

      if(empty($dateResolution) && !empty($_BE['DateInputResolution'])) {
         $dateResolution = $_BE['DateInputResolution'];
      }

      // global $pslstrings; // This should be able to bring in $pslstrings['January']; from class/locale/en.php but it isn't working

      debug("dateInput2: initialTimestamp for $fieldNames[0]", $initialTimestamp);

      // calculate initial values
      if (empty($initialTimestamp) || $initialTimestamp == 0) {
         $initialValuesString = '0000:00:00:-1:-1:-1';
      } else {
         // $initialValuesString = psl_strftime("%Y:%m:%d:%H:%M:%s", $initialTimestamp);
         $initialValuesString = strftime("%Y:%m:%d:%H:%M:%s", $initialTimestamp);
      }

// echo "<br>B) 1 $initialTimestamp, 2 $fieldNames, 3 $initialState, 4 $showTimeNow, 5 $dateResolution $initialValuesString";

      $initialValues = (!empty($initialValuesString)) ? split(':', $initialValuesString) : array();

      // Set defaults

      if ($initialState == 'today') {
         if($showTimeNow) {
            // $dateFormats = array('Y', 'm', 'd', 'H', 'i', 's');
           $dateFormats = array('%Y', '%m', '%d', '%H', '%M', '%s');
         } else {
            // $dateFormats = array('Y', 'm', 'd');
            // $timeFormats = array('H', 'i', 's');
            $dateFormats = array('%Y', '%m', '%d');
            $timeFormats = array('%H', '%M', '%s');

            foreach($timeFormats AS $field => $format) {
               $field+=3;
               if (empty($initialValues[$field]) ||
                  (($initialValues[$field] == 0) && ($field <= $_BE['DATE_DAY'])) ||
                  (($initialValues[$field] == -1) && ($field > $_BE['DATE_DAY']))) {
                  $initialValues[$field]=-1;
               }
            }
         }

         foreach ($dateFormats AS $field => $format) {
            if (empty($initialValues[$field]) ||
               (($initialValues[$field] == 0) && ($field <= $_BE['DATE_DAY'])) ||
               (($initialValues[$field] == -1) && ($field > $_BE['DATE_DAY']))) {
               // $initialValues[$field] = date($format);
               $initialValues[$field] = strftime($format);
            }
         }
      }
// echo "<br>C) 1 $initialTimestamp, 2 $fieldNames, 3 $initialState, 4 $showTimeNow, 5 $dateResolution "; print_r($initialValues);
      // Define Months
      $monthDef[1] = pslgetText('January');
      $monthDef[2] = pslgetText('February');
      $monthDef[3] = pslgetText('March');
      $monthDef[4] = pslgetText('April');
      $monthDef[5] = pslgetText('May');
      $monthDef[6] = pslgetText('June');
      $monthDef[7] = pslgetText('July');
      $monthDef[8] = pslgetText('August');
      $monthDef[9] = pslgetText('September');
      $monthDef[10] = pslgetText('October');
      $monthDef[11] = pslgetText('November');
      $monthDef[12] = pslgetText('December');

      // Year Field
      $return = "\n<input class=\"form_input\" type=\"text\" name=\"{$fieldNames[$_BE['DATE_YEAR']]}\" size=\"4\" value=\"{$initialValues[$_BE['DATE_YEAR']]}\" />";

      // Month Dropdown
      if ($dateResolution >= $_BE['DATE_MONTH'] && isset($fieldNames[$_BE['DATE_MONTH']])) {
         $return .= "\n<select name=\"{$fieldNames[$_BE['DATE_MONTH']]}\">\n";
         $return .= "        \n<option class=\"form_select\" value=\"0\">Month</option>\n" ;
         for ($ii = 1; $ii <= 12; ++$ii) {
            if ($ii == $initialValues[$_BE['DATE_MONTH']]) {
               $sel = ' selected="selected" ';
            } else {
               $sel = '';
            }
            $return .= "        \n<option class=\"form_select\" value=\"$ii\"$sel>$monthDef[$ii]</option>\n";
         }
         $return .= "\n</select>\n";
      }

      // Day Dropdown
      if ($dateResolution >= $_BE['DATE_DAY'] && isset($fieldNames[$_BE['DATE_DAY']])) {
         $return .= "\n<select name=\"{$fieldNames[$_BE['DATE_DAY']]}\">\n";
         $return .= "        \n<option class=\"form_select\" value=\"0\">Day</option>\n" ;
         for ($ii = 1; $ii <= 31; ++$ii) {
            if ($ii == $initialValues[$_BE['DATE_DAY']]) {
               $sel = ' selected="selected" ' ;
            } else {
               $sel = '' ;
            }
            $return .= "        \n<option class=\"form_select\" value=\"$ii\"$sel>$ii</option>\n" ;
         }
         $return .= "\n</select>\n";
      }

      // Hour Dropdown
      if ($dateResolution >= $_BE['DATE_HOUR'] && isset($fieldNames[$_BE['DATE_HOUR']])) {
         $return .= "&nbsp;\n<select name=\"{$fieldNames[$_BE['DATE_HOUR']]}\">\n";
         $return .= "        \n<option class=\"form_select\" value=\"-1\">Hr</option>\n" ;
         for ($ii = 0; $ii <= 23; ++$ii) {
            if ($ii == $initialValues[$_BE['DATE_HOUR']]) {
               $sel = ' selected="selected" ' ;
            } else {
               $sel = '' ;
            }
            $return .= "        \n<option class=\"form_select\" value=\"$ii\"$sel>".sprintf('%02d', $ii)."</option>\n";
         }
         $return .= "\n</select>\n";
      }

      // Minute Dropdown
      if ($dateResolution >= $_BE['DATE_MINUTE'] && isset($fieldNames[$_BE['DATE_MINUTE']])) {
         $return .= ":\n<select name=\"{$fieldNames[$_BE['DATE_MINUTE']]}\">\n";
         $return .= "        \n<option class=\"form_select\" value=\"-1\">Min</option>\n" ;
         for ($ii = 0; $ii <= 59; ++$ii) {
            if ($ii == $initialValues[$_BE['DATE_MINUTE']]) {
               $sel = ' selected="selected" ' ;
            } else {
               $sel = '' ;
            }
            $return .= "        \n<option class=\"form_select\" value=\"$ii\"$sel>".sprintf('%02d', $ii)."</option>\n";
         }
         $return .= "\n</select>\n";
      }

      // Second Dropdown
      if ($dateResolution >= $_BE['DATE_SECOND'] && isset($fieldNames[$_BE['DATE_SECOND']])) {
         $return .= ":\n<select name=\"{$fieldNames[$_BE['DATE_SECOND']]}\">\n";
         $return .= "        \n<option class=\"form_select\" value=\"-1\">Sec</option>\n" ;
         for ($ii = 0; $ii <= 59; ++$ii) {
            if ($ii == $initialValues[$_BE['DATE_SECOND']]) {
               $sel = ' selected="selected" ' ;
            } else {
               $sel = '' ;
            }
            $return .= "        \n<option class=\"form_select\" value=\"$ii\"$sel>".sprintf('%02d', $ii)."</option>\n";
         }
         $return .= "\n</select>\n";
      }

      return $return;

   } // end dateInput2


   /**
    * Parses a unix timestamp from HTML form input fields. Resolution of the form
    * is specified by $_BE['DateInputResolution']. Times are adjusted based on
    * user timezone, i.e. form times are expected to be in the user's
    * timezone, the timestamp returned is in the server's timezone.
    *
    * @param array array of form values for Year, Mon, Day, Hour, Min, Sec
    * @return Int Unix timestamp
    * @access public
    */
   function dateInput2timestamp($formValues) {
      global $_BE;

      // quickly catch a null date based on year
      if (empty($formValues[$_BE['DATE_YEAR']]) || $formValues[$_BE['DATE_YEAR']] == '0000') {
         return 0;
      }

      // convert empty markers to valid values
      for ($field = $_BE['DATE_MONTH']; $field <= $_BE['DATE_SECOND']; ++$field) {
         if ($field <= $_BE['DATE_DAY']) {
            // month, day
            if (empty($formValues[$field]) || ($formValues[$field] == 0)) {
               $formValues[$field] = 1;
            }
         } else {
            // hour, minute, second
            if (empty($formValues[$field]) || ($formValues[$field] == -1)) {
               $formValues[$field] = 0;
            }
         }
      }

      $result = strtotime($formValues[$_BE['DATE_YEAR']] . '-' . $formValues[$_BE['DATE_MONTH']] . '-' . $formValues[$_BE['DATE_DAY']]);
      // $result = psl_strtotime($formValues[$_BE['DATE_YEAR']] . '-' . $formValues[$_BE['DATE_MONTH']] . '-' . $formValues[$_BE['DATE_DAY']]);

      // ignore hms fields if we have a null date so far
      if ($result != 0) {
         if ($_BE['DateInputResolution'] >= $_BE['DATE_HOUR']) {
            $result += $formValues[$_BE['DATE_HOUR']] * 60 * 60;
         }
         if ($_BE['DateInputResolution'] >= $_BE['DATE_MINUTE']) {
            $result += $formValues[$_BE['DATE_MINUTE']] * 60;
         }
         if ($_BE['DateInputResolution'] >= $_BE['DATE_SECOND']) {
            $result += $formValues[$_BE['DATE_SECOND']];
         }
      }

      return $result;

   } // end dateInput2timestamp


// ** URL INTERPRETATION, GENERATION, MANIPULATION *********************************************


   /**
    * Create a URLname based on the title
    *
    * @see cleanURL which seems to do pretty much the same thing
    *
    * @author Mike Gifford
    * @return string
    * @access public
    */
   function autoURLname($fulltitle, $length=20) {

      // Make URL friendly title
      $clean = eregi_replace('[^a-z0-9_]', '', str_replace(array('  ', ' ', '%20'), '_', trim($fulltitle)));

      // Shorten title length after cleaning out the junk.
      $shortertitle = substr($clean,0,$length);

      // Remove bad characters - htmlentities shouldnt be needed here, but better be safe
      $shortertitle = htmlentities($shortertitle);

      //Make sure that we return something - if the title is made up entirely of bad characters
      if (empty($shortertitle)) {
         $shortertitle = uniqid('Item');
      }

      return $shortertitle;

   } // end autoURLname


   /**
    * Ensure that the URLname is unique for the article/section
    * by adding a number onto its end if necessary.
    *
    * TODO - either retire uniqueURLname or bring over language/subsite info
    *
    * @author Mike Gifford
    * @return string
    * @access public
    */
   function uniqueURLname($URLname, $table) {

      $db = & pslNew('BEDB');

      $URLname = addslashes($URLname);

      $q = "SELECT URLname
              FROM $table
              WHERE URLname = '$URLname'";

      $db->query($q);

      // If it URLname exists, add a number
      if ($db->next_record()) {
         global $URLcounter;
         if (!isset($URLcounter)) {
           $URLcounter = 1;
         } else {
            ++$URLcounter;
         }
         $newURLname = uniqueURLname($URLname . $URLcounter, $table);
      } else {
         $newURLname = $URLname;
      }

      return $newURLname;

   } // end uniqueURLname


   /**
    *  Returns a valid URLname in the current language, corresponding to
    * an article or section for which the URLname exists in some other
    * language.
    *
    * @return String
    * @access public
    */
   function translateURLname($URLname, $mode, $language=null) {
      global $BE_currentLanguage, $_BE;

      if (empty($URLname)) {
         return null;
      }

      // Figure out what type of table we're dealing with
      if($mode=='article') {
         $idVar = 'articleID';
         $table = $_BE['Table_articleText'];
      } else if ($mode=='section') {
         $idVar = 'sectionID';
         $table = $_BE['Table_sectionText'];
      } else {
         //  We can only handle sections and articles
         return null;
      }

      // Determine if there is a passed in language and if it is valid
      $language = (!empty($language) && in_array($language, $_BE['Language_array'])) ? $language : $BE_currentLanguage;

      // Get ID from URLname
      $query1 = "SELECT $idVar FROM $table WHERE URLname='$URLname'";
      $db = & pslNew('BEDB');
      $db2 = & pslNew('BEDB');
      $db->query($query1);
// echo "1 $query1";

      // Get and if found return a new language specific URLname
      if($db->next_record()) {
         $idValue = $db->f($idVar);
         $query2 = "SELECT URLname FROM $table WHERE $idVar='$idValue' AND languageID='$language'";
// echo "2 $query2";
         $db2->query($query2);
         if($db2->next_record()) {
            return $db2->f('URLname');
         }
      }

      //  No success
      return null;

   } //end translateURLname


// ** LOCALE: LANGUAGE AND DATE/TIME      *******************************************************


   /**
    * set the current current language
    *
    * Updates the $BE_currentLanguage session variable
    *
    * @global string $BE_currentLanguage
    * @param boolean $force Used by backend.php to force a change to the language we want
    * @access public
    */
   function setCurrentLanguage($force = false) {
      // BE_currentLanguage is a session-managed variable; it is registered
      // in startSession.inc
      global $_PSL, $_BE, $BE_currentLanguage;
      static  $alreadySet;

      // Don't be setting this more than once per page load
      if ($alreadySet && !$force) {
         return $BE_currentLanguage;
      }

      # debug('setCurrentLanguage old', $BE_currentLanguage);

      // Look at the $_SERVER['HTTP_HOST'] to see if it defines the language in $_BE['languagedomains']
      if (isset($_BE['languagedomains']) && is_array($_BE['languagedomains'])) {
         $host = explode('.', $_SERVER['HTTP_HOST']);
         if (count($host) > 1) {
            //Find one of the domain languages as a substring of $_SERVER['HTTP_HOST'].  Much more flexible
            $domain = $domain = $host[count($host)-2] . '.' . $host[count($host)-1];
            
            if (isset($_BE['languagedomains'][$domain]) && !empty($_BE['languagedomains'][$domain])) {
               $BE_currentLanguage = $_BE['languagedomains'][$domain];
            } else {
               $BE_currentLanguage = $_BE['Default_language'];
            }
   
         } else {
            $BE_currentLanguage = $_BE['Default_language'];
         }

         # debug('setCurrentLanguage from domain', $BE_currentLanguage);
      }

      // if the language isn't set, then see if we can find it in browser info
      if (empty($BE_currentLanguage)) {
         // split each seperate language entry
         if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $lang_ary = split(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
         } else {
            $lang_ary = array();
         }
         foreach($lang_ary as $lang) {
            // remove quantifier
            $lang = split(';', $lang);
            // check with long language entry 'en-us'
            if (in_array($lang[0], $_BE['Language_array'])) {
               $BE_currentLanguage = $lang[0];
               break;
            }

            // try again with short language entry 'en'
            $lang = split("[-_]", $lang[0]);
            if (in_array($lang[0], $_BE['Language_array'])) {
               $BE_currentLanguage = $lang[0];
               break;
            }
         } // end foreach

         // If still empty, try default
         if (empty($BE_currentLanguage)) {
            $BE_currentLanguage = $_BE['Default_language'];
         }
         # debug('setCurrentLanguage : autodetect language', $BE_currentLanguage );
      }

      // Set the correct character set if possible
      // - helps browsers work out what encoding is in use
      if (function_exists('mb_http_output') && isset($_BE['script'])) {
         $charSet = @$_BE['script'][$BE_currentLanguage]['charset'];
         if (!isset($charSet)) {
            $charSet = $_BE['script'][$_BE['Default_language']]['charset'];
         }
         mb_http_output($charSet);
      }

      // validate the language
      if (!in_array($BE_currentLanguage, $_BE['Language_array'])) {
         $BE_currentLanguage = $_BE['Default_language'];
      }

      // pac - this might only be needed if $BE_currentLanguage != $_BE['Default_language'?
      // set up all the features to use this language
      $_PSL['templatedir'] = $_BE['templatedir_base'];
      $_PSL['templatedir'] = setLangTpl($BE_currentLanguage);
      $_PSL['languagefile'] = setLang($BE_currentLanguage);

      # debug("setCurrentLanguage new templatedir", $_PSL['templatedir']);
      # debug("setCurrentLanguage new languagefile", $_PSL['languagefile']);

      // pac - not sure if this is needed?
      if (!empty($_GET['skin'])) {
         $_PSL['templatedir'] = setSkinTpl(clean($_GET['skin']), 'cookie');
      } else {
         $_PSL['templatedir'] = setSkinTpl('', 'cookie');
      }

      // set the locale for time display
      $_PSL['locale']['LC_TIME'] = (isset($_PSL['LC_TIME_ARY'][$BE_currentLanguage])) ? $_PSL['LC_TIME_ARY'][$BE_currentLanguage] : $BE_currentLanguage;


      # debug('setCurrentLanguage new', $BE_currentLanguage);

      //  Set the absoluteurl to the language-specific domain.
      if(isset($_BE['languagedomains']) && is_array($_BE['languagedomains'])) {

         $_PSL['absoluteurl'] = 'http://' . $_SERVER['HTTP_HOST'];
         $_PSL['absoluteurl'] .= (isset($_PSL['rooturl']) && !empty($_PSL['rooturl'])) ? $_PSL['rooturl'] : null;

      }

      $alreadySet = true;

   } // end setCurrentLanguage


   /**
    * get the current language
    *
    * Returns the BE_currentLanguage session variable
    *
    * @global $BE_currentLanguage
    * @access public
    *
    */
   function getCurrentLanguage() {
      global $BE_currentLanguage;

      return $BE_currentLanguage;
   } //end getCurrentLanguage


   function be_today() {
      return psl_dateLong(time());
   } // end be_today


   /**
    *  This function lists the available languages.  It has been moved
    * from the various admin classes into BE_function.inc to reduce
    * duplication in the code.
    */
   function getAvailableLanguages() {
      global $_BE, $_PSL, $BE_subsite;
      static $availableLanguages;

      // check if we can avoid a call to the db - should this be cached?? feb2005 mg
      if (!empty($availableLanguages)) {
         return $availableLanguages;
      }

      $db = & pslNew('BEDB');
      $langs = '';

      if($_PSL['module']['BE_Subsite'] && be_inSubsite()) {
         $query = "SELECT languageID from $_BE[Table_subsite_language] WHERE subsiteID='$BE_subsite[subsite_id]'";
         $db->query($query);
         $i=0;
         while($db->next_record()) {
            if($i>0) {
               $langs .= "','";
            }
            $langs .= $db->f('languageID');
         }
      }

      if(empty($langs)) {
         $langs = implode("','", $_BE['Language_array']);
      }

      $q1 = "SELECT languageID, name
             FROM   $_BE[Table_language]
             WHERE  languageID in ('$langs')
             ORDER BY name";

      $db->query($q1);
      while($db->next_record()) {
         $availableLanguages[$db->f('languageID')]=$db->f('name');
      }

      return $availableLanguages;
   }


// ** STRING & VARIABLE MANIPULATION      *******************************************************


   /**
    * Returns a variable from the request. Checks sources
    * in a predetermined order.
    *
    * Gets a value from $_POST, $_GET, $_COOKIE, etc, or null if undefined.
    * Checks the various arrays in a specific order.
    *
    * @todo Add support for everything other than $_POST and $_GET.
    *
    * @param string $varName the name of the variable to get
    * @param string $search the order of values to search. Specified as a string
    *     of letters ('E' for env, 'G' for get, 'P' for post, 'C' for cookie, 'S' for server).
    *
    * @return string or null
    * @access public
    */
   function getRequestVar($varName, $search) {
      // We store values in an array, this prevents an uber-repetitive switch
      $sources = array(
         'E' => &$_ENV,
         'G' => &$_GET,
         'P' => &$_POST,
         'C' => &$_COOKIE,
         'S' => &$_SERVER
      );

      // Check each
      $searchStringSize = strlen($search);
      for ($i = 0; $i < $searchStringSize; ++$i) {
         $source = substr($search, $i, 1);

         if (isset($sources[$source])) {
            // It's a valid source
            if (isset($sources[$source][$varName])) {
               // The value is set in that source
               debug('Using source: ' . $source, $varName);
               return $sources[$source][$varName];
            }
         // The value _isn't_ set in this source
         } else {
#            debug('Bad variable source: ' . $source, $varName);
         }
      }

      // Didn't find the value.
      debug('Didnt find in: '.$search, $varName);
      return null;
   }


   /**
    * Extract the field from the ary with translation to the specified type
    *
    * @author Peter Bojanic
    * @return any
    * @access public
    */
   function be_extractValue($ary, $field, $type) {
      $fieldValue = NULL;
      // by default, the value is assumed NULL
      if (is_array($ary)) {
         // if we got an array then simply extract the values into an array
         if (is_array($field)) {
            $valuesAry = array();
            foreach ($field as $fieldPart) {
               $valuesAry[] = $ary[$fieldPart];
            }
            return $valuesAry;
         }

         if (isset($ary[$field])) {
            $originalValue = $ary[$field];
            # debug("originalValue for $field type=$type",$originalValue);
            switch ($type) {
               case 'string':
               $fieldValue = trim($originalValue);
               break;

               case 'filename':
               # debug('filename',$originalValue);
               # debug("ary {$field}_save",$ary["{$field}_save"]);
               $fieldValue = basename(trim($originalValue));
               break;

               case 'integer':
               if (!$fieldValue = intval($originalValue)) {
                  $fieldValue = 0;
               }
               break;

               case 'numeric':
               if (!$fieldValue = floatval($originalValue)) {
                  $fieldValue = 0;
               }
               break;

               case 'date':
               // if (!$fieldValue = strtotime($originalValue)) { $fieldValue = 0;}
               // dates may be broken out into n parts using
               //  the dateInput2 method; we need to detect this
               //  condition by testing for the "fieldName_datePart"
               //  pattern
               // the field names have to match the ones in BE_Standard_i.formatForPage()
               if (isset($ary[$field.'_year']) && !empty($ary[$field.'_year'])) {
                  $fieldValue = dateInput2timestamp(
                     array($ary[$field.'_year'], $ary[$field.'_month'],
                           $ary[$field.'_day'], $ary[$field.'_hour'],
                           $ary[$field.'_minute'], $ary[$field.'_second']));
               } else {
                  // just a timestamp (?)
                  $fieldValue = $ary[$field];
               }
               break;

               case 'array':
               case 'children':
               //pass the array through unaltered
               $fieldValue = $originalValue;
               break;

               case 'boolean':
               $fieldValue = !empty($originalValue);
               break;
            }
         }
      }
      return $fieldValue;
   }


   /**
    * Converts text as input by user to nice html for storage
    *
    * Added by PAC as part of move to PSL base to ensure consistency in
    * translation. Now extended for wiki input
    *
    * @author Peter Cruickshank
    * @param string $text The text to convert
    * @param string $fmt Format [html]|plain|exttrans - No change if html (other than to strip out disallowed html).
    *                                                   Most changes for exttans
    * @return htmlstring
    * @access public
    */
   function be_makeHTML($text, $fmt = 'plain', $charSet=NULL, $htmlTidy = true) {
      global $_BE, $_PSL, $BE_currentLanguage;

      if (!isset($charSet)) {
         $charSet = (isset($_BE['script'][$BE_currentLanguage]['charset'])) ? $_BE['script'][$BE_currentLanguage]['charset'] : null;
         if (!isset($charSet) && !empty($charSet)) {
            $charSet = $_BE['script'][$_BE['Default_language']]['charset'];
         }
      }

      // Before we start, undo the HTML-entity-ization of quotes that
      // gets done in clean() for SQL-injection prevention.
      if (function_exists('html_entity_decode')) {
         $text = html_entity_decode($text, ENT_QUOTES);
      } else {
         $text = unhtmlentities($text);
      }

      if ($_BE['script'][$BE_currentLanguage]['charset'] == 'windows-1252') {
#         $text = EncodeMSWordEntities($text);
      }

      # debug('be_makeHTML in',$text);
      if ($fmt == 'exttrans') {
         $text = htmlentities($text, ENT_QUOTES,$charSet);
      }

      switch ($fmt) {
         case 'wiki':
         loadClass('WikiEngine');
         WikiEngine::transformHtml($text);
         break;

         case 'plain':
         $text = htmlentities($text, ENT_QUOTES,$charSet);
         // dropthru

         case 'exttrans':
         $text = nl2p($text);

         loadClass('WikiEngine');
         WikiEngine::transformText($text);

         // Let's play safe.
         if ($_BE['cleanUserInput'] == 'stripBadHTML') {
            $text = stripBadHTML($text);
         } elseif ($_BE['cleanUserInput'] == 'strip_tags') {
            if (isset($_PSL['approvedtags']) && is_array($_PSL['approvedtags'])) {
               $approvedTags = array_keys($_PSL['approvedtags']);
               $text = strip_tags($text, '<' . implode('><', $approvedTags) . '>');
            } else {
               $text = strip_tags($text);
            }
         }

         break;

         default:
         if ($htmlTidy) {
            // Let's play safe.
            if ($_BE['cleanUserInput'] == 'stripBadHTML') {
               $text = stripBadHTML($text);
            } elseif ($_BE['cleanUserInput'] == 'strip_tags') {
               if (isset($_PSL['approvedtags']) && is_array($_PSL['approvedtags'])) {
                  $approvedTags = array_keys($_PSL['approvedtags']);
                  $text = strip_tags($text, '<' . implode('><', $approvedTags) . '>');
               } else {
                  $text = strip_tags($text);
               }
            }
         }
         $text = br2p($text);
         $text = makeAmpersandEntities($text);
      }

      # debug('be_makeHTML out',$text);
      // $text = ereg_replace('&amp;', '&', $text);

      return $text;

   } // end be_makeHTML


   /**
   * Translate text into html-safe characters for the current language's character set
   *
   * Use as a substitute for htmlentities/htmlspecialchars
   *
   * @author Peter Cruickshank
   * @return string
   */
   function be_entities($text, $charSet=NULL) {
      if (!isset($charSet)) {
         global $_BE, $BE_currentLanguage;
         $charSet = @$_BE['script'][$BE_currentLanguage]['charset'];
         if (!isset($charSet)) {
            $charSet = $_BE['script'][$_BE['Default_language']]['charset'];
         }
      }
      # debug("be_makeHTML in/$charSet",$text);

      if (empty($charSet)) { // Just use the default
         $text = htmlentities($text, ENT_QUOTES);
      } else {
         $text = htmlentities($text, ENT_QUOTES, $charSet);
      }
      return $text;
   } // end be_entities


   /**
    *  Used to convert all ampersands that are not already part of valid XHTML
    * entities into the XHTML ampersand entity.
    *
    *  I'm sure that there's a more efficient way to do this with regular
    * expressions, and any regex god who wants to write it is more than welcome
    * to do so.
    *
    * @author  Ian Clysdale
    * @return  The string provided, with non-entity ampersands converted to &amp;
    */
   function makeAmpersandEntities($text) {
      $text = str_replace("&", "&amp;", $text);
      $text = str_replace("&amp;#", "&#", $text);
      
      $validEntities = getEntityList();
      foreach($validEntities as $entity) {
            $text = str_replace('&amp;'.$entity, '&'.$entity, $text);
      }
            
      return $text;
   }
   
   /**
    *  Escapes all valid XHTML entities -- i.e., converts &eacute; to 
    * &amp;eacute.  We do this before displaying text for users to edit,
    * since otherwise the browser will represent the entity that they
    * are editing as the actual symbol, and when they save the symbol
    * will be saved instead of the entity.
    *
    * @author Ian Clysdale
    * @return The string provided, with entities escaped.
    */
   function escapeEntities($text) {
      $validEntities = getEntityList();
      foreach($validEntities as $entity) {
         $text = str_replace('&'.$entity, '&amp;'.$entity, $text);
      }
      return $text;
   }
      
   /**
    *  Returns an array of valid XHTML entities, from the XHTML spec.
    *
    *  Made into a function so that we can access a single instance of the
    * list from multiple functions, without loading it into global memory
    * if it isn't needed, since it's rather large.  (Which is also why
    * it isn't in BE_config.php, and simply stored as an array variable in
    * the massive $_BE variable.)
    *
    *  Those doing site-specific performance tweaks may want to remove
    * many or all of the values here, if they don't expect to have them
    * used in their site.
    *
    *  @return An array of valid XHTML entities.
    */
    
   function getEntityList() {
      return array(
         // Latin-1 characters, from xhtml-lat1.ent
         'nbsp',
         'iexcl',
         'cent',
         'pound',
         'curren',
         'yen',
         'brvbar',
         'sect',
         'uml',
         'copy',
         'ordf',
         'laquo',
         'not',
         'shy',
         'reg',
         'macr',
         'deg',
         'plusmn',
         'sup2',
         'sup3',
         'acute',
         'micro',
         'para',
         'middot',
         'cedil',
         'sup1',
         'ordm',
         'raquo',
         'frac14',
         'frac12',
         'frac34',
         'iquest',
         'Agrave',
         'Aacute',
         'Acirc',
         'Atilde',
         'Auml',
         'Aring',
         'AElig',
         'Ccedil',
         'Egrave',
         'Eacute',
         'Ecirc',
         'Euml',
         'Igrave',
         'Iacute',
         'Icirc',
         'Iuml',
         'ETH',
         'Ntilde',
         'Ograve',
         'Oacute',
         'Ocirc',
         'Otilde',
         'times',
         'Oslash',
         'Ugrave',
         'Uacute',
         'Ucirc',
         'Uuml',
         'Yacute',
         'THORN',
         'szlig',
         'agrave',
         'aacute',
         'acirc',
         'atilde',
         'auml',
         'aring',
         'aelig',
         'egrave',
         'eacute',
         'ecirc',
         'euml',
         'igrave',
         'iacute',
         'icirc',
         'iuml',
         'eth',
         'ntilde',
         'ograve',
         'oacute',
         'ocirc',
         'ouml',
         'divide',
         'oslash',
         'ugrave',
         'uacute',
         'ucirc',
         'uuml',
         'yacute',
         'thorn',
         'yuml',
         
         // Special characters, from xhtml-special.ent
         'quot',
         //'amp', // - removed so that we don't double-encode
         'lt',
         'gt',
         'apos',
         'OElig',
         'oelig',
         'Scaron',
         'scaron',
         'Yuml',
         'circ',
         'tilde',
         'ensp',
         'emsp',
         'thinsp',
         'zwnj',
         'zwj',
         'lrm',
         'rlm',
         'ndash',
         'mdash',
         'lsquo',
         'rsquo',
         'sbquo',
         'ldquo',
         'rdquo',
         'bdquo',
         'dagger',
         'Dagger',
         'permil',
         'lsaquo',
         'rsaquo',
         'euro',
         'fnof',
         
         // Symbols, from xhtml-symbol.ent
         'Alpha',
         'Beta',
         'Gamma',
         'Delta',
         'Epsilon',
         'Zeta',
         'Eta',
         'Theta',
         'Iota',
         'Kappa',
         'Lambda',
         'Mu',
         'Nu',
         'Xi',
         'Omicron',
         'Pi',
         'Rho',
         'Sigma',
         'Tau',
         'Upsilon',
         'Phi',
         'Chi',
         'Psi',
         'Omega',
         'alpha',
         'beta',
         'gamma',
         'delta',
         'epsilon',
         'zeta',
         'eta',
         'theta',
         'iota',
         'kappa',
         'lambda',
         'mu',
         'nu',
         'xi',
         'omicron',
         'pi',
         'rho',
         'sigma',
         'tau',
         'upsilon',
         'phi',
         'chi',
         'psi',
         'omega',
         'thetasym',
         'upsih',
         'piv',
         'bull',
         'hellip',
         'prime',
         'Prime',
         'oline',
         'frasl',
         'weierp',
         'image',
         'real',
         'trade',
         'alefsym',
         'larr',
         'uarr',
         'rarr',
         'darr',
         'harr',
         'crarr',
         'lArr',
         'uArr',
         'rArr',
         'dArr',
         'hArr',
         'forall',
         'part',
         'exist',
         'empty',
         'nabla',
         'isin',
         'notin',
         'ni',
         'prod',
         'sum',
         'minus',
         'lowast',
         'radic',
         'prop',
         'infin',
         'ang',
         'and',
         'or',
         'cap',
         'cup',
         'int',
         'there4',
         'sim',
         'cong',
         'asymp',
         'ne',
         'equiv',
         'le',
         'ge',
         'sub',
         'sup',
         'nsub',
         'sube',
         'supe',
         'oplus',
         'otimes',
         'perp',
         'sdot',
         'lceil',
         'rceil',
         'lfloor',
         'rfloor',
         'lang',
         'rang',
         'loz',
         'spades',
         'clubs',
         'hearts',
         'diams');
   }
   
   /**
    * For users with PHP 4.3.0 you may do this: html_entity_decode($a);
    * For reverting from htmlentities
    *
    * @param string $string
    * @return string
    */
   function unhtmlentities($string) {
      $trans_tbl = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
      $trans_tbl[chr(146)] = '&#8217;'; // right single quotation mark
      $trans_tbl['-'] = '&#150;';
      $trans_tbl["'"] = '&#150;';
      $trans_tbl['<'] = '&lt;';
      $trans_tbl['>'] = '&gt;';
      $trans_tbl[' '] = '&nbsp;';
      $trans_tbl = array_flip ($trans_tbl);

      return strtr ($string, $trans_tbl);

   } // end unhtmlentities


	function EncodeMSWordEntities ($string) {
		$trans_tbl = array() ;

		$trans_tbl[chr(34)]  = '&#34;';		// quote
		$trans_tbl[chr(38)]  = '&#38;';		// ampersand
		$trans_tbl[chr(60)]  = '&#60;';		// less-than
		$trans_tbl[chr(62)]  = '&#62;';		// more-than
		$trans_tbl[chr(128)] = '&#8364;';	// euro
		$trans_tbl[chr(129)] = '&#8364;'; 	//	euro
		$trans_tbl[chr(130)] = '&#8218;'; 	//	low quote
		$trans_tbl[chr(131)] = '&#402;'; 	//	florin
		$trans_tbl[chr(132)] = '&#8222;'; 	// double low quote
		$trans_tbl[chr(133)] = '&#8230;'; 	//	ellipsis
		$trans_tbl[chr(134)] = '&#8224;';	//	dagger
		$trans_tbl[chr(135)] = '&#8225;'; 	//	double dagger
		$trans_tbl[chr(136)] = '&#710;'; 	//	circumflex
		$trans_tbl[chr(137)] = '&#8240;'; 	//	per thousand
		$trans_tbl[chr(138)] = '&#352;'; 	//	S caron
		$trans_tbl[chr(139)] = '&#8249;'; 	//	left angle quote
		$trans_tbl[chr(140)] = '&#338;'; 	//	OE ligature
		$trans_tbl[chr(142)] = '&#381;'; 	//	Z caron # possibly &eacute;
		$trans_tbl[chr(145)] = '&#8216;'; 	//	left single quote
		$trans_tbl[chr(146)] = '&#8217;'; 	//	right single quote
		$trans_tbl[chr(147)] = '&#8220;'; 	//	left double quote
		$trans_tbl[chr(148)] = '&#8221;'; 	//	right double quote
		$trans_tbl[chr(149)] = '&#8226;'; 	//	bullet
		$trans_tbl[chr(150)] = '&#8211;'; 	//	en dash
		$trans_tbl[chr(151)] = '&#8212;'; 	//	em dash
		$trans_tbl[chr(152)] = '&#732;'; 	//	small tilde
		$trans_tbl[chr(153)] = '&#8482;'; 	//	trademark
		$trans_tbl[chr(154)] = '&#353;'; 	//	small s caron
		$trans_tbl[chr(155)] = '&#8250;'; 	//	right angle quote
		$trans_tbl[chr(156)] = '&#339;'; 	//	oe ligature
		$trans_tbl[chr(158)] = '&#382;'; 	// small z caron
		$trans_tbl[chr(159)] = '&#376;'; 	//	Y with diaeresis

		for ( $i=160; $i<=255; ++$i) {
			$trans_tbl[chr($i)] = '&#' . $i . ';';
		}

		return strtr ( $string , $trans_tbl ) ;

	}


   /**
    * Search the inputString with the regular expression in searchString
    * and return the part specified by whichPart
    * Not Used - depreciated - mg May2005
    *
    * @author Peter Bojanic
    * @return string
    * @access public
   function be_extractPart($inputString, $searchString, $whichPart) {
      if (preg_match($searchString, $inputString, $matches)) {
         return $matches[$whichPart];
      } else {
         return '';
      }
   } // end be_extractPart
    */

   /**
    * replacement for php's nl2br tag that produces more designer friendly html
    *
    * Modified from: http://www.php-editors.com/contest/1/51-read.html
    *
    * @param string $text
    * @param string $cssClass
    * @return string
    */
    function nl2p($text, $cssClass=''){

      // Return if there are no line breaks.
      if (!strstr($text, "\n")) {
         return $text;
      }

      // Add Optional css class
      if (!empty($cssClass)) {
         $cssClass = ' class="' . $cssClass . '" ';
      }

      // put all text into <p> tags
      $text = '<p' . $cssClass . '>' . $text . '</p>';

      // replace all newline characters with paragraph
      // ending and starting tags
      $text = str_replace("\n", "</p>\n<p" . $cssClass . '>', $text);

      // remove empty paragraph tags & any cariage return characters
      $text = str_replace(array('<p' . $cssClass . '></p>', '<p></p>', "\r"), '', $text);

      return $text;

   } // end nl2p


  /**
    * expanding on the nl2p tag above to convert user contributed
    * <br />'s to <p>'s so it displays more nicely.
    *
    * @param string $text
    * @param string $cssClass
    * @return string
    */
    function br2p($text, $cssClass=''){

      if (!eregi('<br', $text)) {
         return $text;
      }

      if (!empty($cssClass)) {
         $cssClass = ' class="' . $cssClass . '" ';
      }

      // put all text into <p> tags
      $text = '<p' . $cssClass . '>' . $text . '</p>';

      // replace all break tags with paragraph
      // ending and starting tags
      $text = str_replace(array('<br>', '<br />', '<BR>', '<BR />'), "</p>\n<p" . $cssClass . '>', $text);

      // remove empty paragraph tags
      $text = str_replace(array('<p' . $cssClass . '></p>', '<p></p>', "<p>\n</p>"), '', $text);

      return $text;

   }


   /** stristr_reverse()
    * provides everything before the instance of the needle
    * @ Returns String
    */
   function stristr_reverse($haystack, $needle) {
     $pos = strpos($haystack, $needle) + strlen($needle);
     return substr($haystack, 0, $pos);
   }


   /** get_extension()
    * Return everything after the . of the file name (including the .)
    * @ Returns String
    */
   function get_extension($filename) {
      return strtolower(strrchr($filename, '.'));
   }


   /** strip_extension()
    * Return everything before the . of the file name (not including the .)
    * @ Returns String
    */
   function strip_extension($filename) {
      return strtolower(stristr_reverse($filename, '.'));
   }


// ** DB HELPER FUNCTIONS                 *******************************************************


   /**
    *  returns the section ID for a given name
    */
   function getSectionID($sectionName) {
      if (empty($sectionName)) {
         return null;
      }

      $sectionDB = & pslNew('BEDB');
      $query = "SELECT sectionID FROM be_sectionText WHERE URLname = '$sectionName'";
      $response = '';
      if ($sectionDB->query($query)) {
         while ($sectionDB->next_record()) {
            $response = $sectionDB->Record['sectionID'];
         }
      }

      return $response;

   } // end getSectionID

   /**
   * @param integer $authorId
   * @return string
   */
   function be_getAuthorName($authorId) {
      global $auth;

      $authorId = intval($authorId);
      if (empty($authorId)) {
         return '';
      }

      $unamedb = & pslNew('BEDB');
      $query = 'SELECT author_name FROM psl_author where author_id = '. $authorId;
      if (!$unamedb->query($query)) {
         return false;
      }
      if ($unamedb->next_record()) {
         $authorName = $unamedb->Record['author_name'];
      }

      return @$authorName;

   } // end be_getAuthorName


   /**
    * function selectColumn - returns the select statement clause
    *
    * determine if column is a text field or a numeric field
    * and return the correct wherepart for a select statement
    * for the specified column options
    *
    * @todo Extend to deal with situation where the URLname only has to be unique within
    *       a local. Will need info on table being queried...
    *
    * @param mixed column Value for comparison
    * @param string numericFieldName
    * @param string textFieldName
    * @param string operation comparison operator, eg =
    * @return string
    * @access public
    *
    */
   function selectColumn($column, $numericFieldName, $textFieldName, $operation='=') {

      if (empty($column) || empty($numericFieldName) || empty($textFieldName) || empty($operation)) {
         return false;
      }

      if (!empty($column) && is_numeric($column)) {
         // is a numeric field
         $wherePart = $numericFieldName . ' ' . $operation . ' ' . $column . ' ';
      } else {
         // is a text field
         $wherePart = $textFieldName . ' ' . $operation . ' \'' . $column . '\' ';
      }

      # debug('selectColumn wherepart',$wherePart);

      return $wherePart;

   } // end selectColumn


   /**
    *  Returns the title of the subsite section.  Sets the SUBSITE_TITLE template
    * variable set in getHeader()
    */
   function be_getSubsiteTitle() {
      global $BE_subsite, $BE_currentLanguage, $_BE;
      $query = "SELECT title FROM $_BE[Table_sectionText] WHERE sectionID='$BE_subsite[sectionID]' AND languageID='$BE_currentLanguage'";
      $db = & pslNew('BEDB');
      $db->query($query);
      $db->next_record();
      return $db->Record['title'];
   }


   /**
     *  Returns the subsite name, keeping a global table to reduce database
     * lookups.   Used by various admin functions.
     */
   function be_getSubsiteName($subsiteID) {
      static $globalSubsiteNameTable;

      $db = & pslNew('BEDB');
      if($globalSubsiteNameTable[$subsiteID]=='') {
         $query = "SELECT name FROM be_subsites WHERE subsite_id='$subsiteID'";
         $db->query($query);
         $db->next_record();
         $globalSubsiteNameTable[$subsiteID]=$db->f('name');
      }
      return $globalSubsiteNameTable[$subsiteID];
   }


   /**
    *  Intializes DB_DataObjects
    */
   function initializeDBDataObjects() {
      global $_PSL, $_DB_DATAOBJECT;

      if (!empty($_DB_DATAOBJECT['CONFIG']))
         return;

      require_once('PEAR.php');

      define('DB_DATAOBJECT_NO_OVERLOAD',true);
      $options = &PEAR::getStaticProperty('DB_DataObject','options');
      $config = parse_ini_file($_PSL['classdir'] . '/DO/BE_DO.ini', true);
      $db_name = $_PSL['DB_Database'];
      $config['DB_DataObject']["ini_".$db_name] = $_PSL['classdir'] . '/DO/be7.ini';
      foreach($config as $class=>$values) {
         foreach ($values as $k=>$v) {
            //hackery because of the names
            @eval("\$v = \"$v\";");
            $values[$k] = $v;
         }
         $options = &PEAR::getStaticProperty($class,'options');
         $options = $values;
      }

      require_once('DB/DataObject.php');
   }


// ** FILE/CACHE MANAGEMENT               *******************************************************


   /**
    * Cleans temporary directory of dynamic pdf files
    *
    * @param string $dir
    * @param string $prefix
    * @param string $suffix
    *
    */
   function cleanTempFiles($dir, $prefix = 'tmp', $suffix = '.pdf') {
      //Delete temporary files
      $t = time();
      $h = opendir($dir);
      while ($file = readdir($h)) {
         if (substr($file, 0, 3) == $prefix and substr($file, -4) == $suffix) {
            $path = $dir.'/'.$file;
            if ($t-filemtime($path) > 3600)
            @unlink($path);
         }
      }
      closedir($h);
   } // end cleanTempFiles


   // copied from http://ca3.php.net/manual/en/function.imagecreatefromjpeg.php
   function LoadJpeg($imgname) {
      $im = @imagecreatefromjpeg($imgname); /* Attempt to open */
      if (!$im) { /* See if it failed */
         $im  = imagecreate(150, 30); /* Create a blank image */
         $bgc = imagecolorallocate($im, 255, 255, 255);
         $tc  = imagecolorallocate($im, 0, 0, 0);
         imagefilledrectangle($im, 0, 0, 150, 30, $bgc);
         /* Output an errmsg */
         imagestring($im, 1, 5, 5, "Error loading $imgname", $tc);
      }
      return $im;
   }


   function checkMutex($mutexName=null) {
      if (empty($mutexName)) {
         return false;
      }
      $db = & pslNew('BEDB');
      $query = "SELECT mutexName, mutexTime FROM be_mutex WHERE mutexName='$mutexName'";
      $db->query($query);
      if($db->next_record()) {
         $twoMinutesAgo = mktime() - 120;
         if(isset($this->db->Record['mutexTime']) && $this->db->Record['mutexTime'] > $twoMinutesAgo) {

            return true;

         } else {
            //  Delete old mutexes.
            $query = "DELETE FROM be_mutex WHERE mutexName='$mutexName'";
            $db->query($query);
         }
      }

      return false;

   }


   function addMutex($mutexName=null) {
      if (empty($mutexName)) {
         return false;
      }
      $db = & pslNew('BEDB');
      $time = mktime();
      $query = "INSERT INTO be_mutex(mutexName, mutexTime) VALUES('$mutexName', '$time')";
      $db->query($query);
   }


   function removeMutex($mutexName=null) {
      if (empty($mutexName)) {
         return false;
      }
      $db = & pslNew('BEDB');
      $query = "DELETE FROM be_mutex WHERE mutexName='$mutexName'";
      $db->query($query);
   }

   /* htmlarea returns '<br />' instead of '' if nothing is written. */
   function isEmptyHtml($content = null) {
      if (!$content) return true;
      if (trim($content) == '<br />') return true;
      return false;
   }

?>
