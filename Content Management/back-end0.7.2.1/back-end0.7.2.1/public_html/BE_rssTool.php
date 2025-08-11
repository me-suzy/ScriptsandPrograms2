<?php
   /* RSS Tool
    Designedurl for caching & manipulating remote data
    - RSS files
    - HTML->RSS
    - HTML Blocks

    Includes
    - Validating RSS files before replacing the existing RSS cache
    - Catching the start/stop locations for remote html

    // Grab variables from URL
    // Examples
    // - http://be5/BE_rssTool.php?action=rssCache&url=http://back-end.org/backend.php?section=1
    // - http://be5/BE_rssTool.php?action=html2rss&start=Top&stop=Bottom&startLI=LI&stopLI=LI&url=http://back-end.org/index.php
    // - http://be5/BE_rssTool.php?action=htmlCache&start=Top&stop=Bottom&url=http://back-end.org/index.php

    Thanks to Nasir Simbolon <nasir@3wsi.com>,<http://debian.3wsi.net> from latest_news.php

    CREATE TABLE be_rsstool (
    md5 varchar(50) NOT NULL default '0',
    url varchar(255) NOT NULL default '',
    dateCreated int(10) unsigned NOT NULL default '0',
    dateModified int(10) unsigned NOT NULL default '0',
    dateRemoved int(10) unsigned NOT NULL default '0',
    requests text NOT NULL,
    data text NOT NULL,
    PRIMARY KEY  (md5),
    KEY md5 (md5,dateRemoved)
    ) TYPE=MyISAM COMMENT='rss and html cache';

    */

   // don't cache this page with jpacache
   $cachetimeout = -1;
   include("config.php");

   // Set Variables

   $pageTitle = "RSS Tool";
   // The name to be displayed in the header
   $xsiteobject = "RSS Tool";
   // This Defines The META Tag Object Type

   $content = '';
   $show = '';
   $rssCacheChecked = '';
   $html2rssChecked = '';
   $htmlCacheChecked = '';
   $validXML = false;
    $totalData = '';

   // $contents = str_replace("\n"," ",$contents);

   if (isset($_PSL['phpself']))
      $phpself = $_PSL['phpself'];
   else
      $phpself = $_SERVER['PHP_SELF'];

   // Switch between file & db storage - md5 lookup only used with db
   $storage = 'db'; // $storage = 'file';

   $_BE['BE_rssTool'] = 'be_rsstool';

   // Default Expiry Time
   $secondsFromNow = 6000; // 500 minutes
   $expiryUnixTime = time()+$secondsFromNow;

   // Functions

   // Takes URL grabs the content
   function grabURL($url) {
      $content = '';
      if (!$fp = fopen($url , "r")) {
         // fopen remote file
         // if fopen doesn't work try fsockopen
         $fp = fsockopen("$url", 80, &$errno, &$errstr, 30);
         if (!$fp) {
            echo " can not open url: $url";
            return false;
         } else {
            fputs($fp, "GET / HTTP/1.0\n\n");
            while (!feof($fp)) {
               $contents .= fgets($fp, 128);
            }
            fclose($fp);
         }
      } else {
         while (!feof($fp)) // read content
         $content .= fgets($fp, 1024);

         fclose($fp); //close connection
      }
      // echo "<p>!+:! $content !+!:</p>";
      return $content;
   }

   // Cleans up the URL & returns it as an array
   function cleanURL($urlNew = '') {
      if (isset($urlNew))
         $url['absoluteURL'] = 'http://' . str_replace('http://', '', $urlNew);
      elseif (isset($_GET['url']))
         $url['absoluteURL'] = 'http://' . str_replace('http://', '', clean($_GET['url']));
      else
         return false;

      $urlArray = parse_url($url['absoluteURL']);
      $url['domain'] = $urlArray['scheme'] . '://' . $urlArray['host'];
      $url['path'] = $urlArray['path'];

      // Need some funky reg ex to parse out file.html, index.php, list.asp, page.cfm
      // $url['pathMinusPage'] = ereg_replace($urlArray['path'] , '', $urlArray['path']);
      return $url;
   }

   // parse html either into a smaller chunk or into an array so it can be formatted in pieces
   function parseReturnedContent($url, $content, $start = '', $stop = '', $startLI = '', $stopLI = '', $startTitle = '', $stopTitle = '', $startLink = '', $stopLink = '', $startDate = '', $stopDate = '', $startDescription = '', $stopDescription = '') {
      // echo "<br />url " . htmlentities($url['absoluteURL']) . "<br />start " . htmlentities($start) . "<br />stop " . htmlentities($stop) . "<br />startLI " . htmlentities($startLI) . "<br />stopLI " . htmlentities($stopLI);

      // print_r($url);

      //cut the top of page
      if (!empty($start))
         $content = ereg_replace("^.+$start" , " " , $content);

      //cut the bottom of page
      if (!empty($stop))
         $content = ereg_replace("$stop.+$" , " " , $content);

      // echo "<p>$content</p>";

      if (!empty($startLI)) {
         // Search for items
         //now get all news by  explode content by $startLI
         $contentArray = explode("$startLI" , $content);
         // prepare for segments
         for ($i = 1 ; $i < count ($contentArray) ; $i++) {
            //cut the bottom of item
            $contentArray[$i] = ereg_replace("$stopLI.+$" , " " , $contentArray[$i]);

            // add absoluteURL to href
            // $contentArray[$i] = ereg_replace(" href=\"" ," href=\"" . $url['absoluteURL'], $contentArray[$i]);

            if (isset($startTitle))
               $title[$i] = strip_tags(ereg_replace("$stopTitle.+$" , " " , ereg_replace("$stopTitle.+$" , " " , ereg_replace("^.+$startTitle" , " " , $contentArray[$i]))));

            if (isset($startLink)) {
               // echo "<br /> 0) " . $contentArray[$i];

               $link[$i] = trim(strip_tags(ereg_replace("$stopLink.+$" , " " , ereg_replace("$stopLink.+$" , " " , ereg_replace("^.+$startLink" , " " , $contentArray[$i])))));
               // echo " 1) " . $link[$i];

               if (!eregi("^http://", $link[$i])) {
                  $link[$i] = $url['domain'] . '/' . $url['path'] . '/' . $link[$i];
               }
               // echo " 2) " . $link[$i];
            }

            if (isset($startDescription))
               $description[$i] = strip_tags(ereg_replace("$stopDescription.+$" , " " , ereg_replace("$stopDescription.+$" , " " , ereg_replace("^.+$startDescription" , " " , $contentArray[$i]))));
            if (isset($startDate))
               $date[$i] = strip_tags(ereg_replace("$stopDate.+$" , " " , ereg_replace("$stopDate.+$" , " " , ereg_replace("^.+$startDate" , " " , $contentArray[$i]))));

            // echo "<p> $description[$i] " . htmlentities($startDescription) . "  " . htmlentities($stopDescription) . " $contentArray[$i] </p>";

            // Build Final Array
            $finalContentArray[$i]['item'] = strip_tags($contentArray[$i], "<p> <br> <hr>");
            $finalContentArray[$i]['title'] = $title[$i];
            $finalContentArray[$i]['link'] = $link[$i];
            $finalContentArray[$i]['description'] = $description[$i];
            $finalContentArray[$i]['date'] = $date[$i];
         }
         return $finalContentArray;

      } else {
         // Search for html block
         //strip <img>
         $content = strip_tags($content, "<p> <br> <hr> <a>");
         // add absoluteURL to href
         $content = ereg_replace(" href=\"" , " href=\"" . $url['absoluteURL'] , $content);
         return $content;
      }
   }

   // Clean up URL to make sure it is consistently formatted
   $url = cleanURL(clean($_GET['url']));

   // Add DB Class
   $dbObj = & pslSingleton('BEDB');

   if (isset($_GET['md5'])) {
      // Use md5 value to find data
      $md5value = clean($_GET['md5']);
      $q = "SELECT * FROM " . $_BE['BE_rssTool'] . " WHERE md5='" . clean($_GET['md5']) . "'";

      // perform the query
      if (!$dbObj->query($q))
         echo "Problems connecting to db";

      // fail if no record found
      if (!$dbObj->next_record())
         $noCache = true; // return false;

      // fail if record has expired
      if ($dbObj->Record['dateRemoved'] > time()) {
         $useCache = true; // return false;
      } else {
         $useCache = false;
      }

      if (empty($dbObj->Record['data']))
         $useCache = false;

      if ($useCache) {
         // echo "Using Cached Data <br />";
         echo $dbObj->Record['data'];
         $useForm = clean($_GET['form']);
         if (empty($useForm)) {
            exit();
         } else {
            echo "<p>Using Cached Data <br /></p>";
            // $_GET = unserialize($dbObj->Record['requests']);
            if (!empty($dbObj->Record['url']) AND empty($url['absoluteURL']))
               $url['absoluteURL'] = $dbObj->Record['url'];
            $_GET = unserialize($dbObj->Record['requests']);
            // print_r($_GET); echo '<br />' . $dbObj->Record['requests'];
         }
      } else {
         $_GET = unserialize($dbObj->Record['requests']);
         if (!empty($dbObj->Record['url']) AND empty($url['absoluteURL']))
            $url['absoluteURL'] = $dbObj->Record['url'];
         // print_r($_GET);
         $updateCache = true;
         $reloadCache = true;
      }

   } else {
      // Generate md5 value to get data

      $md5value = md5(serialize(clean($_GET)));

      $q = "SELECT * FROM " . $_BE['BE_rssTool'] . " WHERE md5='$md5value'";

      // echo "$q";

      // perform the query
      if (!$dbObj->query($q))
         echo "Problems connecting to db";

      // fail if no record found
      if (!$dbObj->next_record())
         $noCache = true; // return false;

      // fail if record has expired
      if ($dbObj->Record['dateRemoved'] > time()) {
         $useCache = true; // return false;
      } else {
         $useCache = false;
      }

      if (empty($dbObj->Record['data']))
         $useCache = false;

      if ($useCache) {
         // echo "Using Cached Data <br />";
         echo $dbObj->Record['data'];
         if (empty($_GET['form']))
            exit();
      } else {
         echo "Need to update rss file";
         if (!empty($dbObj->Record['url']) AND empty($url['absoluteURL']))
            $url['absoluteURL'] = $dbObj->Record['url'];
         $updateCache = true;
         $reloadCache = true;
      }
   }

   $start = clean(stripslashes($_GET['start']));
   $stop = clean(stripslashes($_GET['stop']));
   $startLI = clean(stripslashes($_GET['startLI']));
   $stopLI = clean(stripslashes($_GET['stopLI']));
   $startTitle = clean(stripslashes($_GET['startTitle']));
   $stopTitle = clean(stripslashes($_GET['stopTitle']));
   $startLink = clean(stripslashes($_GET['startLink']));
   $stopLink = clean(stripslashes($_GET['stopLink']));
   $startDate = clean(stripslashes($_GET['startDate']));
   $stopDate = clean(stripslashes($_GET['stopDate']));
   $startDescription = clean(stripslashes($_GET['startDescription']));
   $stopDescription = clean(stripslashes($_GET['stopDescription']));

   // Set fileCache variables
   if ($storage != 'db') {
      $cacheExpiryDate = date('Y-m-d H:i:s', $expiryUnixTime);
      $newCacheFileName = $_PSL['basedir']. "/updir/fileCache/cache.".urlencode($url['absoluteURL'])."-".$md5value;

      // Check if the cached file is valid
      if (is_file($newCacheFileName) AND ($expiryUnixTime > filemtime($newCacheFileName))) {
         // echo "file is valid and has not expired";
         $reloadCache = false;
      } elseif (is_file($newCacheFileName) AND ($expiryUnixTime < filemtime($newCacheFileName))) {
         // echo "file is valid, but has expired";
         $reloadCache = true;
      } elseif (!is_file($newCacheFileName)) {
         // echo "file doesn't exist";
         $reloadCache = true;
      }
   }

   if ($reloadCache AND ($_GET['action'] == 'rssCache')) {
      if (!isset($_GET['md5']))
         echo "<h1>rssCache</h1> ";

      // We can't delete a valid xmlCache until we know that the new one is valid (or forced)

      // Check that cache file is there and is relatively up-to-date
      // $cachedFile = $_PSL['basedir'] . '/rssCache.' . urlencode($url['absoluteURL']);
      // if (is_writeable($cachedFile) AND (filemtime($cachedFile)+3000 < time())) {

      // Check that this is valid xml (don't over write valid xml with junk)
      // http://www.sitepoint.com/article/560/2

      if ($content = grabURL($url['absoluteURL'])) {

         // Create an XML parser
         $xml_parser = xml_parser_create();

         // Set the functions to handle opening and closing tags
         xml_set_element_handler($xml_parser, "startElement", "endElement");

         // Set the function to handle blocks of character data
         xml_set_character_data_handler($xml_parser, "characterData");

         if (xml_parse($xml_parser, $content)
         // Handle errors in parsing
         or die(sprintf("XML error: %s at line %d",
            xml_error_string(xml_get_error_code($xml_parser)),
            xml_get_current_line_number($xml_parser))))
         $validXML = true;

         // Free up memory used by the XML parser
         xml_parser_free($xml_parser);

         if (isset($_GET['force']) AND $_GET['force'] == true)
            $force = true;
         else
            $force = false;

         // Either this is a valid xml file or your forcing it to be cached
         if ($validXML OR ($force))
         $data2cache = $content;

      }

   } elseif ($reloadCache AND ($_GET['action'] == 'html2rss')) {
      if (!isset($_GET['md5']))
         echo "<h1>html2rss</h1>";

      if ($content = grabURL($url['absoluteURL'])) {

         $rssLinks = parseReturnedContent($url, $content, $start, $stop, $startLI, $stopLI,
            $startTitle, $stopTitle, $startLink, $stopLink, $startDate, $stopDate, $startDescription, $stopDescription);
      }
      // echo "<pre>"; print_r($rssLinks); echo "</pre>";


      if (empty($ary['max']))
      $ary['max'] = 15;

      // What to build for output
      switch($ary['view']) {
         case 'block':
         $viewtpl = 'BE_rssTool_backendBlock.tpl';
         break;
         case 'html':
         $viewtpl = 'BE_rssTool_backendHTML.tpl';
         break;
         case 'js':
         $viewtpl = 'BE_rssTool_backendJS.tpl';
         break;
         case 'jsdata':
         $viewtpl = 'BE_rssTool_backendJSData.tpl';
         break;
         case 'mozilla':
         $viewtpl = 'BE_rssTool_backendMozilla.tpl';
         $ary['target'] = '_content';
         break;
         case 'opml':
         $viewtpl = 'BE_rssTool_backendOpml.tpl';
         break;
         case 'rss91':
         $viewtpl = 'BE_rssTool_backendRSS91.tpl';
         break;
         case 'rss92':
         $viewtpl = 'BE_rssTool_backendRSS92.tpl';
         break;
         case 'txt':
         $viewtpl = 'BE_rssTool_backendTxt.tpl';
         break;
         case 'wml':
         $viewtpl = 'BE_rssTool_backendWml.tpl';
         break;
         case 'rss':
         case 'rss1':
         default:
         $viewtpl = 'BE_rssTool_backendRSS1.tpl';
         // RSS-1.0 needs a extra listing.
         $link_ary = array();
         break;
      }

      // Make a target (only used by Mozilla,JS and JSdata)
      if (empty($ary['target']))
      $ary['target'] = '_self';

      // The date..
      $date = date("H:i m:d:y T");

      // Create the template object, and set some options for silent running
      $template = new Template($_PSL['templatedir']);
      $template->debug = 0;
      $template->set_unknowns('remove');
      $template->halt_on_error = 'no';

      // Set the template file we need to use
      $template->set_file('storiesbackend', $viewtpl);

      $agentString = "Back-End CMS http://www.back-end.org/backend.php?rssv=" . $_BE['version'];

      if (!empty($_PSL['rooturl']))
         $rootDir = $_PSL['absoluteurl'] . '/' . $_PSL['rooturl'];
      else
         $rootDir = $_PSL['absoluteurl'];

      // Start giving placeholders, these aren't parsed till later so
      // placeholders that crop up during the following set_block loops
      // will be parsed with these variables.
      $template->set_var(array(
      'SITE_NAME' => $_PSL['site_name'],
         'SITE_TITLE' => $_PSL['site_title'],
         'SITE_SLOGAN' => $_PSL['site_slogan'],
         'SITE_OWNER' => $_PSL['site_owner'],
         'SITE_HOST' => $_PSL['absoluteurl'], // was $HTTP_HOST
      'DESCRIPTION' => $_PSL['metatags']['description'],
         'PHP_SELF' => $_PSL['phpself'],
         'ROOTDIR' => $rootDir,
         'DATE' => $date,
         'IMAGEDIR' => $_PSL['absoluteurl'] . $_PSL['imageurl'],
         'TARGET' => $ary['target'],
         'COUNT' => count($rssLinks),
         'MAIN_FILE' => $mainFile,
         'agentString' => $agentString ));

      // Process the "each_story" block in the template
      $template->set_block("storiesbackend", "each_story", "stories");
      for ($i = 0 ; $i < count($rssLinks); $i++) {

         if ($ary['type'] != 'psl') {
            $rssLinks[$i]["intro_text"] = $rssLinks[$i]["description"];
            $rssLinks[$i]["datef"] = $rssLinks[$i]["date"];
            if (eregi("^http://", $rssLinks[$i]["link"]))
               $rssLinks[$i]['story_id'] = $rssLinks[$i]["link"];
            else
               $rssLinks[$i]['story_id'] = $_PSL['absoluteurl'] . '/' . $rssLinks[$i]["link"];
         }

         switch($ary['d']) {
            case '0':
            $description = '';
            break;
            case 's':
            $description = $_PSL['site_name'] ;
            break;
            case 'l':
            $description = $_PSL['site_name'] . ' - ';
            $description .= htmlspecialchars(strip_tags($rssLinks[$i]["intro_text"]));
            // $description .= format_mail(eregi_replace("<([font][^>]*)>", "",$$rssLinks[$i]["intro_text"]),"80", "scrub");
            break;
            case 'd':
            case '1':
            default:
            $description = htmlspecialchars(strip_tags($rssLinks[$i]["intro_text"]));
            // $description = format_mail(eregi_replace("<([font][^>]*)>", "",$rssLinks[$i]["intro_text"]),"80", "scrub");
            break;
         }

         $template->set_var(array(
         #  'STORY_ID'    => $rssLinks[$i]['story_id'],
         'URL' => $rssLinks[$i]['link'],
            'TITLE' => htmlspecialchars($rssLinks[$i]['title']),
            'INTRO_TEXT' => $description,
            'IDX' => $i,
            'DATEF' => $rssLinks[$i]['datef'] ));

         // Grab a copy of the link stuff we need for RSS-1.0
         if ($ary['view'] = 'rss1') {
            $link_ary[] = array('storyid' => $rssLinks[$i]['story_id']);
         }

         $template->parse('stories', 'each_story', true);

      }


      // RSS-1.0 requires a item listing in the <channel> tag
      if ($ary['view'] = 'rss1') {
         $template->set_block('storiesbackend', 'each_item', 'items');
         for ($i = 0; $i < count($link_ary); $i++) {
            $template->set_var(array(
            'STORY_ID' => $link_ary[$i]['storyid'] ));
            $template->parse('items', 'each_item', true);
         }
      }

      // Send this stuff to the client
      $returnedContent = $template->parse('OUT', 'storiesbackend');

      if (!empty($returnedContent))
         $data2cache = $returnedContent;

   } elseif ($reloadCache AND ($_GET['action'] == 'htmlCache')) {
      if (!isset($_GET['md5']))
         echo "<h1>htmlCache</h1>";

      if ($content = grabURL($url['absoluteURL'])) {
         $returnedContent = parseReturnedContent($url, $content, $start, $stop, '', '', '', '', '', '');
         if (!empty($returnedContent))
            $data2cache = $returnedContent;
      }
   }

   if ($_GET['final'] != 'on') {
      // Form for testing
      if ($_GET['action'] == 'rssCache')
         $rssCacheChecked = 'checked';
      elseif($_GET['action'] == 'html2rss')
         $html2rssChecked = 'checked';
      elseif($_GET['action'] == 'htmlCache')
         $htmlCacheChecked = 'checked';

      echo "
         <form action=\"$phpself\" method=\"get\">
         <br />URL <input type=\"text\" name=\"url\" size=\"50\" maxlength=\"250\" value=\"" . clean($_GET['url']) . "\" />
         <br />Start Capture <input type=\"text\" name=\"start\" size=\"50\" maxlength=\"250\" value=\"" . stripslashes(clean($_GET['start'])) . "\" />
         <br />Stop Capture <input type=\"text\" name=\"stop\" size=\"50\" maxlength=\"250\" value=\"" . stripslashes(clean($_GET['stop'])) . "\" />
         <br />Start List Item <input type=\"text\" name=\"startLI\" size=\"50\" maxlength=\"250\" value=\"" . stripslashes(clean($_GET['startLI'])) . "\" />
         <br />Stop List Item <input type=\"text\" name=\"stopLI\" size=\"50\" maxlength=\"250\" value=\"" . stripslashes(clean($_GET['stopLI'])) . "\" />

         <br />Start Title <input type=\"text\" name=\"startTitle\" size=\"50\" maxlength=\"250\" value=\"" . stripslashes(clean($_GET['startTitle'])) . "\" />
         <br />End Title <input type=\"text\" name=\"stopTitle\" size=\"50\" maxlength=\"250\" value=\"" . stripslashes(clean($_GET['stopTitle'])) . "\" />
         <br />Start Link <input type=\"text\" name=\"startLink\" size=\"50\" maxlength=\"250\" value=\"" . stripslashes(clean($_GET['startLink'])) . "\" />
         <br />End Link <input type=\"text\" name=\"stopLink\" size=\"50\" maxlength=\"250\" value=\"" . stripslashes(clean($_GET['stopLink'])) . "\" />
         <br />Start Description <input type=\"text\" name=\"startDescription\" size=\"50\" maxlength=\"250\" value=\"" . stripslashes(clean($_GET['startDescription'])) . "\" />
         <br />End Description <input type=\"text\" name=\"stopDescription\" size=\"50\" maxlength=\"250\" value=\"" . stripslashes(clean($_GET['stopDescription'])) . "\" />
         <br />Start Date <input type=\"text\" name=\"startDate\" size=\"50\" maxlength=\"250\" value=\"" . stripslashes(clean($_GET['startDate'])) . "\" />
         <br />End Date <input type=\"text\" name=\"stopDate\" size=\"50\" maxlength=\"250\" value=\"" . stripslashes(clean($_GET['stopDate'])) . "\" />

         <br /><input type=\"radio\" name=\"action\" value=\"rssCache\" $rssCacheChecked /> rssCache
         <input type=\"radio\" name=\"action\" value=\"html2rss\" $html2rssChecked /> html2rss
         <input type=\"radio\" name=\"action\" value=\"htmlCache\" $htmlCacheChecked /> htmlCache
         <br /><input type=\"checkbox\" name=\"final\" /> Final Version (No Form)
         <br /><input name=\"submit\" value=\"submit\" type=\"submit\" />
         </form>
         ";
   }

   if (!isset($_GET['md5']))
      echo "<p>" . $_PSL['rootdomain'] . "/$phpself?md5=$md5value </p><p></p>";

   // Cache data
   if ($storage == 'db') {
      // Storing to DB
      if ($updateCache AND !$noCache) {
         // Update Cache

         $sql = "UPDATE " . $_BE[BE_rssTool] . " SET dateModified = '" . time() . "', " . " dateRemoved = '$expiryUnixTime', " . " data = '" . addslashes($data2cache) . "' ";

         if (!empty($url[absoluteURL]))
         $sql .= ", url = '".$url[absoluteURL]."'";

         if (!empty($_GET[url]))
         $sql .= ", requests = '".serialize(clean($_GET))."' ";

         $sql .= "WHERE md5 = '$md5value' ";

         if ($dbObj->query($sql))
            if (!isset($_GET['md5']))
            echo "<p>DB Updated<br /></p>";

      } elseif($noCache) {
         // Insert into cache
         $sql2 = "
            INSERT INTO " . $_BE['BE_rssTool'] . "(
            md5,
            url,
            dateCreated,
            dateModified,
            dateRemoved,
            requests,
            data
            ) VALUES (
            '$md5value',
            '$url[absoluteURL]',
            '" . time() . "',
            '" . time() . "',
            '$expiryUnixTime',
            '" . serialize(clean($_GET)) . "',
            '" . addslashes($data2cache) . "')
            ";
         // echo "<pre>$sql2</pre>";
         if ($dbObj->query($sql2))
            echo "<p>Record inserted into DB<br /></p>";

      }

      echo "$data2cache";

   } else {
      echo "Storing to file";
      // Start fileCache
      if (isset($_PSL['classdir']))
         include_once($_PSL['classdir'] . '/fileCache.class.php');
      else
         include_once('fileCache.class.php');

      $cacheObject = new fileCache('cache', urlencode($url['absoluteURL']). '-' . $md5value, $cacheExpiryDate);
      if ($rssCache = $cacheObject->retrieve()) {
         echo $rssCache;
         if (empty($_GET['form']))
         exit();
      } else {
         echo $data2cache;
      }

      $cacheObject->store($data2cache); // End fileCache

      // echo $totalData;
   }

?>