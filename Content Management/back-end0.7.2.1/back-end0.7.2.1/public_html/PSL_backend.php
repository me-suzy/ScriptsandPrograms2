<?php

   /* backend.php->Methods for exporting information */
   /* $Id: PSL_backend.php,v 1.4 2005/03/11 16:18:15 mgifford Exp $ */

   /*
    * pass the query in the "Source Url" form field in the format of:
    *
    *       option1=setting1&option2=setting2
    *  Available query options:
    *
    *  section = section name
    *  topic   = topic name
    *  author  = author name
    *  date    = starting date in "to_days" format
    *  start   = "starting" point [0]
    *  max     = maximum returned [15]
    *  order   = [time], hits, title, etc.
    *  asc     = ASC, [DESC] ( Ascending or Descending)
    *  query   = urlencoded text string
    *
    *  view    = different export formats
    rss91   - RSS 0.91
    rss92   - RSS 0.92
    [rss1]  - RSS 1.0 - default
    html    - fancybox
    txt     - simple text output of title and intro
    block   - text delimited by %%
    js      - javascript
    jsdata  - javascript array
    mozilla - for adding to Netscape 6 My Sidebar
    opml    - OPML (http://radio.userland.com)
    wml     - WML - for WAP phones
    xhtmlb  - XHTML Basic
    *  d       = description - this is the intro text.
    // TODO: nh asks: Do we really need this?
    [d] - include description - default
    [s] - site name
    [l] - site name plus description
    [0] - empty description

    Some of the template views and options are based on those contained in HPE
    (http://sourceforge.net/projects/hpe/).  Thanks to Mike Krus.
    */

   // Get our config
   require('./config.php');

   // Make sure we don't propagate errors out the feed :)
   if ($_PSL['debug']) {
      ini_set('error_reporting', 'E_ALL');
   } else {
      ini_set('error_reporting', 'E_NONE');
   }

   // Object Creation
   $story = pslNew("Story");

   // Parse cmd line variables into array
   $ary = array();
   if (!empty($_GET)) {
      $ary = clean($_GET);
   }

   // Get those stories
   $stories = $story->extractStories($ary);

   // What to build for output
   switch($ary['view']) {
      case 'avantgo':
      $viewtpl = 'backendAV.tpl';
      break;
      case 'block':
      $viewtpl = 'backendBlock.tpl';
      break;
      case 'html':
      $viewtpl = 'backendHTML.tpl';
      break;
      case 'js':
      $viewtpl = 'backendJS.tpl';
      header("Content-type: application/x-javascript");
      break;
      case 'jsdata':
      $viewtpl = 'backendJSData.tpl';
      header("Content-type: application/x-javascript");
      break;
      case 'mozilla':
      $viewtpl = 'backendMozilla.tpl';
      $ary['target'] = '_content';
      break;
      case 'opml':
      $viewtpl = 'backendOpml.tpl';
      header("Content-type: text/xml");
      break;
      case 'rss91':
      $viewtpl = 'backendRSS91.tpl';
      header("Content-type: text/xml");
      break;
      case 'rss92':
      $viewtpl = 'backendRSS92.tpl';
      header("Content-type: text/xml");
      break;
      case 'txt':
      $viewtpl = 'backendTxt.tpl';
      break;
      case 'wml':
      $viewtpl = 'backendWml.tpl';
      header("Content-type: text/vnd.wap.wml");
      break;
      case 'xhtmlb':
      $viewtpl = 'backendXHTMLb.tpl';
      header("Content-type: text/html");
      break;
      case 'rss':
      case 'rss1':
      default:
      $viewtpl = 'backendRSS1.tpl';
      // RSS-1.0 needs a extra listing.
      $link_ary = array();
      break;
   }

   // Make a target (only used by Mozilla,JS and JSdata)
   if (empty($ary['target'])) {
      $ary['target'] = '_self';
   }

   // The date..
   $date = date("H:i m:d:y T");

   // Create the template object, and set some options for silent running
   $template = pslNew("slashTemplate", $_PSL['templatedir']);
   $template->debug = 0;
   $template->set_unknowns('remove');
   $template->halt_on_error = 'no';

   // Set the template file we need to use
   $template->set_file('storiesbackend', $viewtpl);

   $count = count($stories);

   // Start giving placeholders, these aren't parsed till later so
   // placeholders that crop up during the following set_block loops
   // will be parsed with these variables.
   $template->set_var(array(
   'SKIN' => $_PSL['skin'],
      'SITE_NAME' => $_PSL['site_name'],
      'SITE_TITLE' => $_PSL['site_title'],
      'SITE_SLOGAN' => $_PSL['site_slogan'],
      'SITE_OWNER' => $_PSL['site_owner'],
      'SITE_HOST' => $HTTP_HOST, // TODO: nh asks: Will this be around with register_globals=Off?
   'DESCRIPTION' => $_PSL['metatags']['description'],
      'PHP_SELF' => $_PSL['phpself'],
      'ROOTDIR' => $_PSL['rooturl'],
      'DATE' => $date,
      'IMAGEDIR' => $_PSL['imageurl'],
      'TARGET' => $ary['target'],
      'COUNT' => $count ));

   // Process the "each_story" block in the template
   $template->set_block("storiesbackend", "each_story", "stories");
   for ($i = 0 ; $i < $count; $i++) {
      switch($ary['d']) {
         case '0':
         $description = '';
         break;
         case 's':
         $description = $_PSL['site_name'] ;
         break;
         case 'l':
         $description = $_PSL['site_name'] . ' - ';
         $description .= htmlspecialchars(strip_tags($stories[$i]["intro_text"]));
         // $description .= format_mail(eregi_replace("<([font][^>]*)>", "",$stories[$i]["intro_text"]),"80", "scrub");
         break;
         case 'd':
         case '1':
         default:
         $description = htmlspecialchars(strip_tags($stories[$i]["intro_text"]));
         // $description = format_mail(eregi_replace("<([font][^>]*)>", "",$stories[$i]["intro_text"]),"80", "scrub");
         break;
      }

      $template->set_var(array(
      'STORY_ID' => $stories[$i]['story_id'],
         'TITLE' => htmlspecialchars(strip_tags($stories[$i]['title'])),
         'TITLE_HTML' => $stories[$i]['title'],
         'INTRO_TEXT' => $description,
         'INTRO_TEXT_HTML' => $stories[$i]['intro_text'],
         'IDX' => $i,
         'DATEF' => $stories[$i]['datef'],
         'LINK' => $_PSL['rooturl'] . '/article.php?story_id=' . $stories[$i][story_id] ));


      // Grab a copy of the link stuff we need for RSS-1.0
      if ($ary['view'] = 'rss1') {
         $link_ary[] = array('storyid' => $stories[$i]['story_id']);
      }

      $template->parse('stories', 'each_story', true);
   }

   // RSS-1.0 requires a item listing in the <channel> tag
   if ($ary['view'] = 'rss1') {
      $template->set_block('storiesbackend', 'each_item', 'items');
      $link_ary_cnt = count($link_ary);
      for ($i = 0; $i < $link_ary_cnt; $i++) {
         $template->set_var(array(
         'STORY_ID' => $link_ary[$i]['storyid'] ));
         $template->parse('items', 'each_item', true);
      }
   }

   // Send this stuff to the client
   $template->parse('OUT', 'storiesbackend');
   $template->p('OUT');

?>