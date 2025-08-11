<?php
   // $Id: backend.php,v 1.33 2005/06/17 16:08:10 mgifford Exp $
   /**
   * backend.php->Methods for exporting information
   *
   * pass the query in the "Source Url" form field in the format of:
   *
   *       option1=setting1&option2=setting2
   *
   *  BE & PSL queries:
   *  view    = different export formats
   *             rss91   - RSS 0.91
   *             rss92   - RSS 0.92
   *             [rss1]  - RSS 1.0 - default
   *            html    - fancybox
   *            txt     - simple text output of title and intro
   *            block   - text delimited by %%
   *            js      - javascript
   *            jsdata  - javascript array
   *            mozilla - for adding to Netscape 6 My Sidebar
   *            opml    - OPML (http://radio.userland.com)
   *            wml     - WML - for WAP phones
   *  section = section name
   *  d = link description
   *    0 - none
   *    s - site title
   *    l - site title & description
   *
   *  BE only queries:
   *  newArticles = Show the latest posted articles
   *  max     = maximum returned [15]
   *  order = priority, title, articleID, spotlight, dateCreated, dateModified, dateAvailable, dateRemoved, dateForSort, hitCounter, URLname, priority, random
   *  lang    = two char language code. Defaults to site default language
   *
   *  Available to PSL queries only:
   *  topic   = topic name
   *  author  = author name
   *  date    = starting date in "to_days" format
   *  start   = "starting" point [0]
   *  order   = [time], hits, title, etc.
   *  asc     = ASC, [DESC] ( Ascending or Descending)
   *  query   = urlencoded text string
   *
   *  Some of the template views and options are based on those contained in HPE
   *  (http://sourceforge.net/projects/hpe/).  Thanks to Mike Krus.
   *
   * @package     Back-End
   * @author      Mike Gifford
   * @author      Peter Cruickshank
   * @version     $Id: backend.php,v 1.33 2005/06/17 16:08:10 mgifford Exp $
   * @copyright   Copyright (C) 2002-5 OpenConcept Consulting
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

   // Uncomment this if you don't want this script to be cached
   // $cachetimeout = -1;

   // Get our config
   require('./config.php');

   // This needs to over-ride the settings in the config.ini.php for a valid
   // return.  Comment out the next line to see the template start/stop markers
   $_PSL['debug.templates'] = false;

   $_BE['rss.useHTMLentities'] = false;


  /*
   * Initialisation and set up defaults
   */

   // Make sure we don't propagate errors out the feed :)
   if (!@$_PSL['debug']) {
      ini_set('error_reporting', 'E_NONE');
   }


   // Parse cmd line variables into array
   foreach ($_GET as $key => $val) {
      $ary[clean($key)] = clean(addslashes(trim($val)));
   }

   if (empty($ary['max'])) {
      $ary['max'] = 15;
   }

   // Make a target (only used by Mozilla,JS and JSdata)
   if (empty($ary['target'])) {
      $ary['target'] = '_self';
   }

   // Deal with confusions over spelling. USA rules!!
   if (!empty($ary['colour'])) {
      $ary['color'] = & $ary['colour'];
   }

   if (!empty($_BE['rss_title'])) {
      $title = $_BE['rss_title'];
   } else {
      $title = pslgetText($_PSL['site_title']);
   }

   // Default to javascript if script ends in .js
#   if (strstr($_SERVER['SCRIPT_NAME'],'.js') == '.js') {
   if (substr($_SERVER['SCRIPT_NAME'],-3,3) == '.js') {
      $ary['view'] = 'js';
   }

   if (empty($ary['lang'])) {
      $ary['lang'] = $BE_currentLanguage;
   }

  /*
   * Fetch the data to be used (all params are passed by reference)
   * - fixes ary[type] if necessary
   */

   fetchData($ary, $mainFile, $rssLinks);

#   echo "<pre>rssLinks:"; print_r($rssLinks); echo "</pre>";
   debug('ary',$ary);

  /*
   * Now put together and output the formatted data
   */

   $viewtpl  = chooseTemplate($ary);
   debug('viewtpl',$viewtpl);

   $agentString = str_replace('.', '', $_BE['version']);

   // The date..
   $date = date('H:i m:d:y T');
   $dateZ = gmdate("Y-m-d\\TH:i:s\\Z");

   // Create the template object, and set some options for silent running
   $rootDir = $_PSL['absoluteurl'];

   $template = pslNew('slashTemplate');
   $template->set_file('storiesbackend', $viewtpl);

   // Start giving placeholders, these aren't parsed till later so
   // placeholders that crop up during the following set_block loops
   // will be parsed with these variables.

   debug('rootDir', $rootDir);
   debug('mainFile', $mainFile);
   $template->set_var(array(
      'SITE_NAME'    => conditionText(pslgetText($_PSL['site_name'])),
      'SITE_TITLE'   => conditionText($title),
      'SITE_SLOGAN'  => conditionText(pslgetText($_PSL['site_slogan'])),
      'SITE_OWNER'   => $_PSL['site_owner'],
      'SITE_HOST'    => $_PSL['absoluteurl'],
      'DESCRIPTION'  => conditionText(pslgetText($_PSL['metatags']['description'])),
      'PHP_SELF'     => and2amp($_PSL['phpself']),
      'FULL_URL'     => $_PSL['absoluteurl'] . and2amp($_SERVER['REQUEST_URI']),
      'ROOTDIR'      => $rootDir,
      'DATE'         => $date,
      'DATE_Z'       => $dateZ,
      'IMAGEDIR'     => $_PSL['absoluteurl'] . $_PSL['imageurl'],
      'TARGET'       => $ary['target'],
      'COUNT'        => count($rssLinks),
      'MAIN_FILE'    => $mainFile,
      'AGENT_STRING' => $agentString
   ));

   // Set up for output
   $template->set_block('storiesbackend', 'each_story', 'stories');
   if ($ary['view'] = 'rss1') {
      $template->set_block('storiesbackend', 'each_item', 'items');
      Header('Content-type: text/xml; charset=iso-8859-1');
   } elseif($ary['view'] = 'js') {
      Header('Content-type: application/x-javascript; charset=iso-8859-1');
   } else {
      Header('Content-type: text/xml; charset=iso-8859-1');
   }

   $linkCount = count($rssLinks);
   for ($i = 0 ; $i < $linkCount; ++$i) {

      // Description: Level of detail to show (see head of file for explanation)
      switch(@$ary['d']) {
      case '0':
         $description = '';
         break;
      case 's':
         $description = $_PSL['site_name'];
         break;
      case 'l':
         $description = $_PSL['site_name'] . ' - ' . $rssLinks[$i]['intro_text'];
         break;
      case 'd':
      case '1':
      default:
         $description = $rssLinks[$i]['intro_text'];
      }

      // Title
      $linkTitle = conditionText($rssLinks[$i]['title']);
      if (!empty($ary['color'])) {
         $linkTitle = '<font color="' . $ary['color'] . '">' . $linkTitle . '</font>';
      }

      // Datetime
      $dateCreatedZ  = $dateModifiedZ = null;
      if (!empty($rssLinks[$i]['datef'])) {
         $dateCreatedZ=gmdate('Y-m-d\\TH:i:s\\Z',$rssLinks[$i]['datef']);
      }
      if (!empty($rssLinks[$i]['dateModified'])) {
         $dateModifiedZ=gmdate('Y-m-d\\TH:i:s\\Z',$rssLinks[$i]['dateModified']);
      }

      $template->set_var(array(
         'STORY_ID'        => $rssLinks[$i]['story_id'],
         'PAGE_REF'        => $rssLinks[$i]['pageRef'],
         'TITLE'           => $linkTitle,
         'INTRO_TEXT'      => conditionText($description),  // htmlspecialchars?
         'CONTENT'         => and2amp($rssLinks[$i]['content']),
         'IDX'             => $i,
         'DATEF'           => $rssLinks[$i]['datef'],
         'DATE_CREATED_Z'  => $dateCreatedZ,
         'DATE_MODIFIED_Z' => $dateModifiedZ,
      ));

      // Grab a copy of the link stuff we need for RSS-1.0
      if ($ary['view'] = 'rss1') {
         $template->parse('items','each_item',true);
      }

      $template->parse('stories', 'each_story', true);
   }

   page_close();

   // Send this stuff to the client
   $out = $template->parse('OUT', 'storiesbackend');

   echo $out;

   exit();


// ============================ SUPPORTING FUNCTIONS


   /**
   * Pull out and collate the data to be used in backend generation
   */
   function fetchData(&$ary, &$mainFile, &$rssLinks) { //, &$templatedir) {
      global $_PSL, $_BE, $BE_currentLanguage;
      debug('$BE_currentLanguage',$BE_currentLanguage);
      debug('ary[lang]',$ary['lang']);
      if ($BE_currentLanguage != $ary['lang']) {
         $BE_currentLanguage = $ary['lang'];
         setCurrentLanguage(true); // Nice. A function that operates through side-effects
         $ary['lang'] = $BE_currentLanguage; // Bad value in $ary[lang] would now be fixed
      }

      // Object Creation
      switch (@$ary['type']) {

      case 'psl':
         // choose psl stories or
         $storyObj = new Story;
         $rssLinks = $storyObj->extractStories($ary);
         $mainFile = $_PSL['mainpage'];

         $linkCount = count($rssLinks);
         for($i=0;$i<$linkCount;++$i) {
            $rssLinks[$i]['pageRef'] = $_PSL['mainpage'] . '?article=' . $rssLinks[$i]['story_id'];
         }
         break;

      case 'events':
         // BE events

         // TODO: Extend RSS Feeds to offer richer data with the RSS
         // Events Module - http://web.resource.org/rss/1.0/modules/event/

         $eventObj = pslNew('BE_Events');
         if(empty($ary['calendar'])) {
            $ary['calendar'] = 'default';
         }
         if(empty($ary['daysToDisplay'])) {
            $ary['daysToDisplay'] = 30;
         }

         $rssLinks = $eventObj->getUpcomingEvents($ary['calendar'], $ary['daysToDisplay'], $ary['max']);

         $linkCount = count($rssLinks);
         for ($i = 0 ; $i < $linkCount; ++$i) {
            $rssLinks[$i]['title'] = $rssLinks[$i]['name'];
            $rssLinks[$i]['intro_text'] = $rssLinks[$i]['description'];
            $rssLinks[$i]['datef'] = $rssLinks[$i]['startDate'];
            $rssLinks[$i]['story_id'] = $rssLinks[$i]['eventID'];

            $rssLinks[$i]['pageRef'] = $_BE['EventsFile'] . '?submit=viewEvent&amp;calendar=' . $ary['calendar'] . '&amp;eventid=' . $rssLinks[$i]['eventID'];
         }
         break;

      case 'actions':

         // BE actions
         $actionObj = pslNew('BE_Action');
         $rssLinks = $actionObj->getActions($BE_currentLanguage);

         $linkCount = count($rssLinks);
         for ($i = 0 ; $i < $linkCount; ++$i) {
            $rssLinks[$i]['intro_text'] = $rssLinks[$i]['blurb'];
            $rssLinks[$i]['datef'] = $rssLinks[$i]['dateCreated'];
            $rssLinks[$i]['story_id'] = $rssLinks[$i]['actionID'];

            $rssLinks[$i]['pageRef'] = 'action.php?submit=show&amp;actionID=' . $rssLinks[$i]['actionID'];
         }
         break;

      default:
         // BE articles

         $ary['type'] = 'articles';  // Clear out any invalid value

         $articleObj = pslNew('BE_Article');
         $sectionObj = pslNew('BE_Section');

         $wherePart = " article.hide = '0' AND article.deleted='0' AND article.restrict2members='0' ";
         $wherePart .= "AND text.languageID = '$BE_currentLanguage' ";

         if ($_PSL['module']['BE_Subsite']) {
            global $BE_subsite;
            if (be_inSubsite()) {
               $subsiteID = $BE_subsite['subsite_id'];
               $sectionID = $BE_subsite['sectionID'];
            } else {
               $sectionID = 0;
            }

            $sectionArray = $sectionObj->getSkeletonItem($sectionID);
            // $title = pslgetText($_PSL['site_title']) . ' : ' . $sectionArray['title'];
            if (be_inSubsite()) {
               $title = $sectionArray['title'];
            }

            $wherePart .= "AND article.subsiteID='$subsiteID'";
/*
            //PAC Apr05: This seems pretty hacky - why hardwire RSS91 here?
            //- Removed since no one said this feature is used
            $subsiteTemplatedir = $templatedir . '/subsites/' . $sectionArray['URLname'];
            if (is_file($subsiteTemplatedir . '/BE_backendRSS91.tpl')) {
               $templatedir = (!empty($subsiteID)) ? $subsiteTemplatedir : $templatedir;
            }
*/
         }

         $mainFile = $_BE['article_file'] . '/'; // BE_config will have put a ? in there if CGI

         // Specify Order & Logic
         if (!empty($ary['asc']) || !empty($ary['logic']) || !empty($ary['order']) || !empty($ary['orderby'])) {

            $logic = (!empty($ary['asc'])) ? $ary['asc'] : (!empty($ary['logic'])) ? $ary['logic'] : 'desc';
            $orderby = (!empty($ary['order'])) ? $ary['order'] : (!empty($ary['orderby'])) ? $ary['orderby'] : 'dateCreated';
            $orderPart = $articleObj->getOrderByClause($orderby, $logic);

         } else {
            $orderPart = 'article.dateCreated DESC ';
         }

         // Latest in section
         if (!empty($ary['section'])) {

            // Get Section Title
            if (is_numeric($ary['section'])) {
               $sectionArray = $sectionObj->getSkeletonItem($ary['section']);
               $title = $title . ' : ' . $sectionArray['title'];
            }

            $wherePart .= 'AND ' . selectColumn($ary['section'], 'sectionArticle.sectionID', 'sectionText.URLname') . ' ';
            $rssLinks = $articleObj->extractArticles($wherePart, $orderPart, $ary['max'], '');

         // newarticles
         } elseif (@$ary['newArticles']) {

            $rssLinks = $articleObj->extractArticles($wherePart, $orderPart, $ary['max'], '');

         }

         // check if there are spotlight articles
         if (empty($rssLinks)) {
            $spotlightWherePart = $wherePart . "AND text.spotlight='1' ";
            $rssLinks = $articleObj->extractArticles($spotlightWherePart, $orderPart, $ary['max'], '');

            // If there aren't any spotlight articles, at least show the new ones
            if (count($rssLinks) == 0) {
               $rssLinks = $articleObj->extractArticles($wherePart, $orderPart, $ary['max'], '');
            }
         }

         $sectionSkeletonAry = $sectionObj->extractSkeleton();
         $article2Section = $articleObj->extractSkeletonByArticle();
   #      debug("article2Section",$article2Section);

         $linkCount = count($rssLinks);
         debug('linkCount',$linkCount);
         for ($i = 0 ; $i < $linkCount; ++$i) {
   #         debug("articleID/$i",$rssLinks[$i]['articleID']);
            $rssLinks[$i]['intro_text'] = $rssLinks[$i]['blurb'];
            $rssLinks[$i]['datef']      = $rssLinks[$i]['dateCreated'];
            $rssLinks[$i]['story_id']   = $rssLinks[$i]['articleID'];

            $sectionID      = $article2Section[$rssLinks[$i]['articleID']];
            $sectionURLname = empty($sectionSkeletonAry[$sectionID]['URLname']) ? $sectionID : $sectionSkeletonAry[$sectionID]['URLname'];
            $articleURLname = empty($rssLinks[$i]['URLname']) ? $rssLinks[$i]['articleID'] : $rssLinks[$i]['URLname'];
            $rssLinks[$i]['pageRef'] = $sectionURLname . '/' . $articleURLname;
         }

      } // esac

      // Ensure there is something to display
      if (!count($rssLinks)) {
         $rssLinks[] = array(
            'story_id' => '',
            'pageRef'  => '',
            'title'    => pslgetText('No content'),
            'intro_text' => pslgetText('At the moment, there is no relevant content to display'),
            'content'  => pslgetText('At the moment, there is no relevant content to display')
            );
      }

   }


   /**
   * Does look up to work out the name of the template file to use
   *
   * @return string
   */
   function chooseTemplate(&$ary) {
      global $_PSL, $_BE, $BE_currentLanguage;
      // What to build for output
      switch(@$ary['view']) {
      case 'block':
         $viewtpl = 'BE_backendBlock.tpl';
         break;
      case 'html':
         if ($ary['tpl'] != '') $viewtpl = 'BE_backendHTML-'.$ary['tpl'].'.tpl';
         else $viewtpl = 'BE_backendHTML.tpl';
         break;
      case 'js':
         $viewtpl = 'BE_backendJS.tpl';
         break;
      case 'jsdata':
         $viewtpl = 'BE_backendJSData.tpl';
         break;
      case 'mozilla':
         $viewtpl = 'BE_backendMozilla.tpl';
         $ary['target'] = '_content';
         break;
      case 'opml':
         $viewtpl = 'BE_backendOpml.tpl';
         break;
      case 'rss91':
         $viewtpl = 'BE_backendRSS91.tpl';
         break;
      case 'rss92':
         $viewtpl = 'BE_backendRSS92.tpl';
         break;
      case 'txt':
      case 'text':
         $viewtpl = 'backendTxt.tpl';
         break;
      case 'wml':
         $viewtpl = 'BE_backendWml.tpl';
         break;
      case 'atom':
         $viewtpl = 'BE_backendAtom.tpl';
         break;
      case 'rss':
      case 'rss1':
      default:
         $ary['view'] = 'rss1';
         $viewtpl = 'BE_backendRSS1.tpl'; // RSS-1.0 needs a extra listing.
         break;
      }

      return $viewtpl;
   }


   /**
   * Cleans the text as required
   *
   * @return string
   */
   function conditionText($string, $format) {
      global $_BE;

      if (empty($string)) {
         return null;
      }

      if (empty($format)) {
         $format = $_BE['rss.useHTMLentities'];
      }

      $string = strip_tags(trim($string));

      switch ($format) {
         case 'htmlentities':
         case true:
            $string = htmlentities($string, ENT_QUOTES);
            break;

         case 'htmlspecialchars':
         case true:
            $string = htmlspecialchars($string);
            break;

         case 'plain':
            $string = $string;
            break;

         case 'nohtmlentities':
         case false:
         default:
            $string = unhtmlentities($string);
            break;


      }

      return $string;

   }


   /**
   * Converts single &'s to &amp; without converting &amp; to &amp;amp;
   *
   * @return string
   */
   function and2amp($string) {
      return str_replace(array('&amp;', '&'), array('&amp;', '&amp;'), $string);
   }


?>
