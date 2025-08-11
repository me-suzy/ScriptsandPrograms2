<?php

   /* $Id: PSL_search.php,v 1.7 2005/04/07 00:09:30 mgifford Exp $ */

   // TODO: search.php needs to have it's logic ripped out and put into an object

   require('./config.php');

   $pagetitle = pslgetText('Search');
   $xsiteobject = pslgetText('Search Page');
   #Defines The META TAG Page Type

   // page_open(array("sess"=>"slashSess","auth"=>"slashAuth","perm"=>"slashPerm"));

   $maxsearchresults = $_PSL['search_maxresults'];

   if ($_GET['use_comments'] == 'on' && $_PSL['allow_comment_search']) {
      $search_comments = TRUE;
   } else {
      $search_comments = FALSE;
   }

   $topic_image_id = "18";
   $topic_image_name = "";
   $topic_image_width = "";
   $topic_image_height = "";

   /*
    *  This is how the get*ID functions work.
    *  - if id, return it else...
    *  - if NO name, return nothing, else
    *  - check the DB for the *name* and return the id
    */
   function getTopicID ($id, $name) {

      global $topic_image_id;
      global $topic_image_name;
      global $topic_image_width;
      global $topic_image_height;

      $db = pslNew("slashDB");

      if ($id == "") {
         if ($name == "") {
            /* no id, no name, we set the default
             image stuff and return nothing */
            $q = "SELECT topic_id,
               image,
               width,
               height
               FROM psl_topic
               WHERE topic_id = '$topic_image_id'";
            $db->query($q);
            $db->next_record();
            $topic_image_name = $db->f('image');
            $topic_image_width = $db->f('width');
            $topic_image_height = $db->f('height');
            return "";

         } else {
            /* no id, WITH name, we set the image stuff
             and return the ID */

            $name = clean($name);
            $q = "SELECT topic_id,
               topic_name,
               image,
               width,
               height
               FROM psl_topic
               WHERE topic_name = '$name'";
            $db->query($q);
            $db->next_record();
            $topic_image_name = $db->f('image');
            $topic_image_width = $db->f('width');
            $topic_image_height = $db->f('height');
            return $db->f('topic_id');
         }

      } else {
         /* we HAVE an ID so we'll just set the topic
          image stuff and move on */

         $q = "SELECT topic_id,
            image,
            width,
            height
            FROM psl_topic
            WHERE topic_id = '$id'";
         $db->query($q);
         $db->next_record();
         $topic_image_name = $db->f('image');
         $topic_image_width = $db->f('width');
         $topic_image_height = $db->f('height');
         return $db->f('topic_id');
      }
   }
   function getSectionID ($id, $name) {

      if ($id == "") {
         if ($name == "") {
            return "";
         } else {
            $name = clean($name);
            $db = pslNew("slashDB");
            $q = "SELECT section_id from psl_section WHERE section_name = '$name'";
            $db->query($q);
            $db->next_record();
            return $db->f('section_id');
         }
      } else {
         return clean($id);
      }
   }
   function getAuthorID ($id, $name) {

      if ($id == "") {
         if ($name == "") {
            return "";
         } else {
            $name = clean($name);
            $db = pslNew("slashDB");
            $q = "SELECT author_id from psl_author WHERE author_name = '$name'";
            $db->query($q);
            $db->next_record();
            return $db->f('author_id');
         }
      } else {
         return clean($id);
      }
   }

   $ary = array();
   if (!empty($_GET)) {
      $ary = clean($_GET);
   }

   $search_topic_id = getTopicID($ary['topic_id'], $ary['topic_name']);
   $search_section_id = getSectionID($ary['section_id'], $ary['section_name']);
   $search_author_id = getAuthorID($ary['author_id'], $ary['author_name']);

   /*** DEBUG ***/

   # debug("HTTP_POST_VARS", $_POST);
   # debug("HTTP_GET_VARS", $_GET);

   /*************/

   $db = pslNew("slashDB");
   $db->debug = false;

   $block = pslNew("Block_i");

   /*************PAGE START*******************/
   if ((empty($ary['section'])) AND (empty($ary['section_id']))) {
      $section = $_PSL['site_homesection'];
      if (!$section) {
         $section = "Home";
      }
      $ary['section'] = $section;
   } else {
      $pagetitle .= $ary['section'];
   }

   $query = $ary['query'];

   $breadcrumb = breadcrumb($ary);

   $_PSL['metatags']['object'] = $xsiteobject;

   slashhead($pagetitle, $_PSL['metatags']);

   /* Templates */
   $templ = pslNew("slashTemplate" , $_PSL[templatedir]);
   $templ->debug = false;
   $templ->set_file ("searchpage", "searchPage.tpl");

   titlebar("100%", sprintf(pslgetText("Searching %s"), htmlentities($query)));

   // Required to clean the QUERY_STRING field in the template
   if (!isset ($query)) {
      $query = "";
   }

   $templ->set_var (array (
   'TOPIC_IMAGE_SRC' => $_PSL['imageurl']."/topics/$topic_image_name",
      'TOPIC_WIDTH' => $topic_image_width,
      'TOPIC_HEIGHT' => $topic_image_height,
      'TOPIC_ALT_TEXT' => $topic_alttext,
      'QUERY_STRING' => $query,
      'ACTION_URL' => $_PSL['phpself'] ));

   /* print the topic select box */

   $templ->set_block ("searchpage", "each_topic", "topic_block");
   $templ->set_var (array (
   'TOPIC_VALUE' => "",
      'TOPIC_TEXT' => pslgetText("All Topics")
   ));

   // TODO: Move quotes below into template
   if ($search_topic_id == "") {
      $templ->set_var ('TOPIC_SELECTED', "selected=\"selected\"");
   } else {
      $templ->set_var ('TOPIC_SELECTED', "");
   }
   $templ->parse ("topic_block", "each_topic", true);

   $db->query ("SELECT DISTINCT psl_topic.topic_id,
      topic_name
      FROM psl_topic, psl_topic_lut
      WHERE  psl_topic.topic_id = psl_topic_lut.topic_id
      ORDER BY topic_name");

   while ($db->next_record()) {
      $templ->set_var (array (
      'TOPIC_VALUE' => $db->f('topic_id'),
         'TOPIC_TEXT' => $db->f('topic_name')
      ));

      if ($search_topic_id == $db->f('topic_id')) {
         $templ->set_var ('TOPIC_SELECTED', "selected=\"selected\"");
      } else {
         $templ->set_var ('TOPIC_SELECTED', "");
      }
      $templ->parse("topic_block", "each_topic", true);
   }

   /* print the section select box */

   $templ->set_block ("searchpage", "each_section", "section_block");
   $templ->set_var (array (
   'SECTION_VALUE' => "",
      'SECTION_TEXT' => pslgetText("All Sections")
   ));
   if ($search_section_id == "") {
      $templ->set_var ('SECTION_SELECTED', "selected=\"selected\"");
   } else {
      $templ->set_var ('SECTION_SELECTED', "");
   }
   $templ->parse ("section_block", "each_section", true);

   $db->query ("SELECT DISTINCT psl_section.section_id,
      section_name
      FROM psl_section,psl_section_lut
      WHERE psl_section_lut.section_id = psl_section.section_id
      ORDER BY section_name");

   while ($db->next_record()) {
      $templ->set_var (array (
      'SECTION_VALUE' => $db->f('section_id'),
         'SECTION_TEXT' => $db->f('section_name')
      ));

      if ($search_section_id == $db->f('section_id')) {
         $templ->set_var ('SECTION_SELECTED', "selected=\"selected\"");
      } else {
         $templ->set_var ('SECTION_SELECTED', "");
      }
      $templ->parse ("section_block", "each_section", true);
   }

   /* print out the authors select box */

   $templ->set_block ("searchpage", "each_author", "author_block");
   $templ->set_var (array (
   'AUTHOR_VALUE' => "",
      'AUTHOR_TEXT' => pslgetText("All Authors")
   ));
   if ($search_author_id == "") {
      $templ->set_var ('AUTHOR_SELECTED', "selected=\"selected\"");
   } else {
      $templ->set_var ('AUTHOR_SELECTED', "");
   }
   $templ->parse ("author_block", "each_author", true);

   $db->query ("SELECT DISTINCT psl_author.author_id,
      author_name
      FROM psl_author, psl_story
      WHERE psl_author.author_id = psl_story.user_id
      ORDER BY author_id");

   while ($db->next_record()) {
      $templ->set_var (array (
      'AUTHOR_VALUE' => $db->f('author_id'),
         'AUTHOR_TEXT' => $db->f('author_name')
      ));

      if ($search_author_id == $db->f('author_id')) {
         $templ->set_var ('AUTHOR_SELECTED', "selected=\"selected\"");
      } else {
         $templ->set_var ('AUTHOR_SELECTED', "");
      }
      $templ->parse("author_block", "each_author", true );
   }

   /* print matches */

   if ($ary['min'] == "") {
      $ary['min'] = 0;
   }

   $prev = $ary['min'] - $maxsearchresults;

   $templ->set_block('searchpage', 'previous_match');
   $templ->set_block('searchpage', 'next_match');
   $templ->set_block('searchpage', 'no_match');

   if ($prev >= 0) {

      $prev_matches_url = $_PSL['phpself'] . "?min=" . ($min - $maxsearchresults);

      /* Only put the necessary stuff on the query URL */
      if ($query != "")
         $prev_matches_url .= "&amp;query=$query";
      if ($search_topic_id != "")
         $prev_matches_url .= "&amp;topic_id=$search_topic_id";
      if ($search_section_id != "")
         $prev_matches_url .= "&amp;section_id=$search_section_id";
      if ($search_author_id != "")
         $prev_matches_url .= "&amp;author_id=$search_author_id";

      $templ->set_var (array (
      'PREV_MATCHES_URL' => $prev_matches_url,
         'PREV_COUNT' => $min ));
      #  $templ->parse ('searchpage', 'previous_match',true);

   } else {
      $templ->set_var('previous_match', '');
   }

   $q = "SELECT DISTINCT story.story_id,
      psl_author.author_id,
      story.title,
      psl_author.author_name,
      story.date_available,
      commentcount.count AS commentcount,
      psl_author.url \n";

   if ($search_section_id != "") {
      $q .= ",sections.section_id
         ,sections.story_id";
   }

   if ($search_topic_id != "") {
      $q .= ",topics.topic_id
         ,topics.story_id ";
   }

   $q .= "        FROM psl_story story,
      psl_author,
      psl_commentcount commentcount";

   if ($search_comments) {
      $q .= ",psl_comment";
   }

   if ($search_section_id != "") {
      $q .= ",psl_section_lut sections ";
   }
   if ($search_topic_id != "") {
      $q .= ",psl_topic_lut topics ";
   }

   $now = time();
   $q .= "\n       WHERE story.user_id = psl_author.author_id
      AND story.story_id = commentcount.count_id
      AND '$now' >= story.date_available \n";

   if ($query != "") {
      $q .= " AND (story.title LIKE \"%$query%\"
         OR story.intro_text LIKE \"%$query%\"
         OR story.body_text LIKE \"%$query%\"";
      /* Here is the comment search clause */
      if ($search_comments) {
         $q .= " OR psl_comment.comment_text LIKE \"%$query%\")";
         $q .= " AND story.story_id = psl_comment.story_id ";
      } else {
         $q .= ") ";
      }
   }

   if ($search_author_id != "")
      $q .= " AND psl_author.author_id = \"$search_author_id\" ";

   /* section and topic search are re-enabled --Daniel Serodio */

   if ($search_section_id != "")
      $q .= " AND sections.section_id = \"$search_section_id\"
      AND sections.story_id = story.story_id";
   if ($search_topic_id != "")
      $q .= " AND topics.topic_id=\"$search_topic_id\"
      AND topics.story_id=story.story_id";

   $q .= "\n    ORDER BY story.date_available DESC LIMIT $ary[min],$maxsearchresults";

   // echo "<PRE>$q</PRE><br />\n";

   $db->query ($q);

   $templ->set_block ("searchpage", "each_match", "match_block");

   if ($db->num_rows() != 0) {

      $shown_matches = 0;
      $templ->set_var('no_match', '');

      while ($db->next_record ()) {

         $story_url = $_PSL['rooturl'] . "/article.php?story_id=" . $db->f('story_id');
         if (!empty($_SERVER['QUERY_STRING'])) {
            $story_url .= $_PSL['amp'] . $_SERVER['QUERY_STRING'];
         }

         $templ->set_var (array (
         'STORY_URL' => $story_url,
            'STORY_TITLE' => $db->f('title'),
            'AUTHOR_URL' => str_html($db->f('url')),
            'AUTHOR_NAME' => $db->f('author_name'),
            'DATE' => psl_dateTimeLong($db->f('date_available')),
            'COMMENTCOUNT' => $db->f('commentcount')
         ));
         $templ->parse ("match_block", "each_match", true);
         $shown_matches ++;
      }

      if ($shown_matches >= ($maxsearchresults - 1)) {

         $min += $maxsearchresults;

         $more_matches_url = $_PSL['phpself']."?min=$min";

         /* Only put the necessary stuff on the query URL */
         if ($query != "")
            $more_matches_url .= "&amp;query=$query";
         if ($search_topic_id != "")
            $more_matches_url .= "&amp;topic_id=$topic_id";
         if ($search_section_id != "")
            $more_matches_url .= "&amp;section_id=$section_id";
         if ($search_author_id != "")
            $more_matches_url .= "&amp;author_id=$author_id";

         $templ->set_var ('MORE_MATCHES_URL', $more_matches_url);

         $templ->parse ('MATCHES', 'searchpage', true);
         $templ->parse ('MATCHES', 'next_match', true);

      } else {

         $templ->set_var('next_match', '');
         $templ->parse ('MATCHES', 'searchpage', true);

      }
   } else {
      // num_rows == 0

      $templ->set_var('next_match', '');
      $templ->set_var('match_block', '');
   }

   $allstories = $templ->parse ("OUT", "searchpage");
   $leftblocks = $block->getBlocks($ary, "left");
   $centerblocks = $block->getBlocks($ary, "center");
   $rightblocks = $block->getBlocks($ary, "right");

   if (empty($leftblocks)) {
      if (empty($rightblocks)) {
         // $centerblocks  = $block->getBlocks($ary);
         // $tplfile = "index1col.tpl";
         // default to 2 column for transparent upgrade
         $rightblocks = $block->getBlocks($ary);
         $tplfile = "index2colright.tpl";
      } else {
         $tplfile = "index2colright.tpl";
      }
   } elseif (empty($rightblocks)) {
      $tplfile = "index2colleft.tpl";
   } else {
      $tplfile = "index3col.tpl";
   }

   $template = pslNew("slashTemplate", $_PSL['templatedir']);
   $template->debug = 0;
   $template->set_file(array(
   'index' => $tplfile //"index3col.tpl"
   ));

   if (!empty($_SERVER['QUERY_STRING'])) {
      $QUERY_STRING = "?".$_SERVER['QUERY_STRING'];
   }

   $template->set_var(array(
   'QUERYSTRING' => $QUERY_STRING,
      'ROOTDIR' => $_PSL['rooturl'],
      'IMAGEDIR' => $_PSL['imageurl'],
      'BREADCRUMB' => $breadcrumb,
      'STORY_COLUMN' => $allstories,
      'LEFT_BLOCK_COLUMN' => $leftblocks,
      'CENTER_BLOCK_COLUMN' => $centerblocks,
      'RIGHT_BLOCK_COLUMN' => $rightblocks ));

   $template->parse('OUT', "index");
   $template->p('OUT');

   slashfoot();
   page_close();

?>
