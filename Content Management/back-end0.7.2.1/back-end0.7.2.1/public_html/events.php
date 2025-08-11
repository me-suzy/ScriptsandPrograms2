<?php
   /**
    * Events Calendar
    *
    * Displays information on events, and allows submission of new events.
    *
    * @package     Back-End on phpSlash
    * @copyright   2003 - Ian Clysdale, Canadian Union of Public Employees
    * @version     $Id: events.php,v 1.25 2005/05/25 20:43:19 mgifford Exp $
    *
    */

   require('./config.php');

   $pageTitle = pslgetText('Events');
   $xsiteobject = pslgetText('Events'); // Defines The META TAG Page Type
   $_PSL['metatags']['object'] = $xsiteobject; // render the standard header

   //  Don't show the spotlight blocks
   $_BE['BE_noSpotlightBlocks'] = true;

   if (!empty($_GET['login'])) {
      $auth->login_if($_GET['login']);
   }

   $content = '';

   // Objects
   $db = & pslSingleton('BEDB');
   $eventObj = pslNew('BE_Events');
   $sectionObj = pslNew('BE_Section');

   /****************
    * INITIALISATION
    *****************/

   // Required to clean the QUERY_STRING field in the template
   $ary['query'] = isset($_GET['query']) ? clean($_GET['query']) : '';
   $ary['min'] = isset($_GET['min']) ? clean($_GET['min']) : '';
   $ary['section'] = $_BE['EventsSection'];
   //  In subsites, we can't count on the action section existing,
   // so we'll just set it to the home section.
   if(be_inSubsite()) {
      $ary['section'] = $BE_subsite['URLname'];
   }


   $_BE['currentSection'] = $ary['section'];

   /****************
    * CONSTRUCT PAGE
    *****************/

   $chosenTemplate = getUserTemplates('', $ary['section']);

   $input = clean($_REQUEST);
   // $submit = (isset($input['submit'])) ? pslgetText($input['submit'], '', true) : NULL;
   $submit = decodeAction($input);

   $template = pslNew('slashTemplate', $_PSL['templatedir']);
   $template->debug = 0;
   $template->set_file(array(
      'editEvent' => 'BE_upcomingEventsEdit.tpl',
      'viewEvent' => 'BE_upcomingEventsView.tpl'
   ));

   $calendar = (isset($input['calendar']) && !empty($input['calendar'])) ? $input['calendar'] : $_BE['EventsDefaultCalendarName'];
   // Get eventID or eventid
   $eventID  = (isset($input['eventID']) && !empty($input['eventID'])) ? $input['eventID'] : null;
   if (empty($eventID) && isset($input['eventid']) && !empty($input['eventid'])) {
      $eventID  = $input['eventid'];
   }


   // By default, append the calendar
   $showCalendar = 1;
   $breadcrumb = $sectionObj->breadcrumb($ary['section'], pslGetText('Event'), 'event');

   if ($submit == 'addEvent' || $submit == 'new') {
      $content = $eventObj->addEvent($calendar);
      $showCalendar = 0;
   }
   elseif ($submit == 'saveEvent' || $submit == 'save') {
      $content = $eventObj->saveEvent($calendar, $eventID, $input);
      $eventInfo = $eventObj->getEventInformation($calendar, $eventID);
      $input['calendarTime'] = $eventInfo['calendarTime'];
   }
   elseif ($submit == 'viewEvent' || $submit == 'view') {
      $eventInfo = $eventObj->getEventInformation($calendar, $eventID);
      $input['calendarTime'] = $eventInfo['calendarTime'];
      $breadcrumb = $sectionObj->breadcrumb($ary['section'], $eventInfo['name'][$BE_currentLanguage]);
      $content = $eventObj->viewEvent($calendar, $eventID);
   }
   elseif ($submit == 'editEvent' || $submit == 'edit') {
      if ($eventID == '') {
         $index = (isset($input['event_i']) && !empty($input['event_i'])) ? clean($input['event_i']) : 0;
         $count = (isset($input['event_n']) && !empty($input['event_n'])) ? clean($input['event_n']) : $_BE['defaultDisplayLimit'];
         $content = $eventObj->listEvents($calendar, $index, $count);
      } else {
         $content = $eventObj->editEvent($calendar, $eventID);
      }
      $showCalendar = 0;
   }
   elseif ($submit == 'deleteEvent' || $submit == 'delete') {
      $eventInfo = $eventObj->getEventInformation($calendar, $eventID);
      $input['calendarTime'] = $eventInfo['calendarTime'];
      $content = $eventObj->deleteEvent($calendar, $eventID);
   }
   elseif ($submit == 'list' || $submit == pslgetText('Search')) {
      $content = $eventObj->listEvents(); // getUpcomingEvents
      $showCalendar = 0;
   }

   if ($showCalendar) {
   	  $calendarTime = (isset($input['calendarTime'])) ? $input['calendarTime'] : null;
      $content .= $eventObj->showCalendar($calendar, $calendarTime);
   }

   // generate the page
   generatePage($ary, $pageTitle, $breadcrumb, $content);

   page_close();

?>
