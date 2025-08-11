<?php

   /* $Id: article.php,v 1.8 2005/03/17 18:40:08 mgifford Exp $ */

   require('./config.php');

   $pagetitle = pslgetText("Articles");
   // The name to be displayed in the header
   $xsiteobject = pslgetText("Articles");
   // Defines The META TAG Page Type

   /*************************************
    START OF PAGE
    *************************************/

   $db = & pslSingleton("slashDB");
   $story = & pslNew("Story");

   $return_link = $_SERVER["REQUEST_URI"];
   $sess->register("return_link");

   // correction for register_globals OFF
   $story_id = $ary['story_id'];
   $submit = $ary['submit'];

   $story_html = $story->getStory($story_id, "full", $ary);

   $t = pslNew("slashTemplate", $_PSL['templatedir'], "remove");

   if ($story_html) {

      $title = $story->getTitle($story_id);
      $ttitle = stripslashes(ereg_replace("<([^>]*)>", "", $title));

      switch($submit) {

         case "pda":

         $t->set_file(article, "article-pf.tpl");
         $t->set_var(array(
         'STORY_URL' => $_PSL['rooturl']."/article.php?story_id=".$story_id,
            'SITENAME' => $_PSL['site_name'],
            'PAGETITLE' => $pagetitle,
            'TITLE' => $ttitle,
            'NAME' => $story->story_ary["name"],
            'REALNAME' => $story->story_ary["realname"],
            'DATEF' => $story->story_ary["datef"],
            'DEPT' => $story->story_ary["dept"],
            'INTRO_TEXT' => $story->story_ary["intro_text"],
            'BODY_TEXT' => $story->story_ary["body_text"],
            'XSITEOBJECT' => $xsiteobject ));
         $t->parse('PAGE', 'article');
         $t->p('PAGE');
         break;

         case "wml":
         header("Content-type: text/vnd.wap.wml");
         $t->set_file(article, "article-wml.tpl");
         $t->set_var(array(
         'STORY_URL' => $_PSL['rooturl']."/article.php?story_id=".$story_id,
            'SITENAME' => $_PSL['site_name'],
            'PAGETITLE' => $pagetitle,
            'TITLE' => $ttitle,
            'NAME' => $story->story_ary["name"],
            'REALNAME' => $story->story_ary["realname"],
            'DATEF' => $story->story_ary["datef"],
            'DEPT' => $story->story_ary["dept"],
            'INTRO_TEXT' => $str = ereg_replace("<([^>]+)>", "", $story->story_ary["intro_text"]),
            'BODY_TEXT' => $str = ereg_replace("<([^>]+)>", "", $story->story_ary["body_text"]),
            'XSITEOBJECT' => $xsiteobject ));
         $t->parse('PAGE', 'article');
         $t->p('PAGE');
         break;

         case "xhtmlb":
         header("Content-type: text/html");
         $t->set_file(article, "article-xhtmlb.tpl");
         $t->set_var(array(
         'STORY_URL' => $_PSL['rooturl']."/article.php?story_id=".$story_id,
            'SITENAME' => $_PSL['site_name'],
            'PAGETITLE' => $pagetitle,
            'TITLE' => $ttitle,
            'NAME' => $story->story_ary["name"],
            'REALNAME' => $story->story_ary["realname"],
            'DATEF' => $story->story_ary["datef"],
            'DEPT' => $story->story_ary["dept"],
            'INTRO_TEXT' => $str = ereg_replace("<([^>]+)>", "", $story->story_ary["intro_text"]),
            'BODY_TEXT' => $str = ereg_replace("<([^>]+)>", "", $story->story_ary["body_text"]),
            'XSITEOBJECT' => $xsiteobject ));
         $t->parse('PAGE', 'article');
         $t->p('PAGE');
         break;

         case "print":

         $p_intro_text = eregi_replace("<img([^>]*)src=\"?([^\"]*)\"?([^>]*)>", "", $story->story_ary["intro_text"]);
         $p_intro_text = eregi_replace("<a ([^>]*)href=\"?([^\"]*)\"?([^>]*)>", "", $p_intro_text);
         $p_intro_text = eregi_replace("</a>", "", $p_intro_text);
         $p_intro_text = stripslashes($p_intro_text);

         $p_body_text = eregi_replace("<img([^>]*)src=\"?([^\"]*)\"?([^>]*)>", "", $story->story_ary["body_text"]);
         $p_body_text = eregi_replace("<a ([^>]*)href=\"?([^\"]*)\"?([^>]*)>", "", $p_body_text);
         $p_body_text = eregi_replace("</a>", "", $p_body_text);
         $p_body_text = stripslashes($p_body_text);

         $t->set_file(article, "article-pf.tpl");
         $t->set_var(array(
         'STORY_URL' => $_PSL['rooturl']."/article.php?story_id=".$story_id,
            'SITENAME' => $_PSL['site_name'],
            'PAGETITLE' => $pagetitle,
            'TITLE' => $ttitle,
            'NAME' => $story->story_ary["name"],
            'REALNAME' => $story->story_ary["realname"],
            'DATEF' => $story->story_ary["datef"],
            'DEPT' => $story->story_ary["dept"],
            'INTRO_TEXT' => $p_intro_text,
            'BODY_TEXT' => $p_body_text,
            'XSITEOBJECT' => $xsiteobject ));
         $t->parse('PAGE', 'article');
         $t->p('PAGE');
         break;

         case "email":

         $emailform = "";
         $emailform = $story->emailStory($story_id, $action);

         default:

         if (!isset($ary['parent_id'])) {
            $ary['parent_id'] = 0;
         }

         $t->set_file(article, "article.tpl");

         $breadcrumb = breadcrumb($ary);

         $_PSL['metatags']['object'] = $xsiteobject;
         // override description metatag to include first 150 chars of article
         $_PSL['metatags']['description'] = substr(strip_tags($story->story_ary["intro_text"]), 0, 150);

         // $titlebar = getTitlebar("100%", $title);  // here if you need it.

         $related = $story->getRelated($story_id);
         if ($perm->have_perm('story')) {
            if (($auth->auth['uid'] == $story->story_ary['user_id']) OR ($perm->have_perm('storyeditor'))) {
               $related .= "<a href=\"".$_PSL['adminurl']."/storyAdmin.php?submit=edit&story_id=".$story_id."\">".pslgetText('Modify Story')."</a>";
            }
         }
         $arrows = $story->getNextPrev($story_id);

         $block = & pslSingleton("Block_i");
         $leftblocks = $block->getBlocks($ary, "left" );
         $centerblocks = $block->getBlocks($ary, "center" );
         $rightblocks = $block->getBlocks($ary, "right" );

         if (empty($leftblocks)) {
            if (empty($rightblocks)) {
               // $centerblocks = $block->getBlocks($ary);
               // $centerblocks .= $related;
               // $tplfile = "index1col.tpl";
               // default to 2 column for transparent upgrade
               $rightblocks = $block->getBlocks($ary);
               $rightblocks .= $related;
               $tplfile = "index2colright.tpl";

            } else {
               $tplfile = "index2colright.tpl";
               $rightblocks = $related . $rightblocks;
            }
         } elseif (empty($rightblocks)) {
            $tplfile = "index2colleft.tpl";
            $leftblocks .= $related;
         } else {
            $tplfile = "index3col.tpl";
            $rightblocks = $related . $rightblocks;
         }

         $t->set_file(article, $tplfile); //"article.tpl");

         /* Now the comments */

         if ($_PSL['module']['Comment']) {
            $cmtary['mode'] = $ary['mode'];
            $cmtary['order'] = $ary['order'];
            $cmtary['story_id'] = $ary['story_id'];
            $cmtary['parent_id'] = $ary['parent_id'];
            $cmt = pslNew("Comment", $cmtary);
            $comments = $cmt->getAllComments($cmtary);
         } else {
            $comments = '';
         }

         $t->set_var('COMMENTS', $comments);

         /* Display the story */

         $t->set_var(array(
         'TITLE' => $title,
            'BREADCRUMB' => $breadcrumb,
            'STORY_ID' => $story_id,
            'ROOTDIR' => $_PSL['rooturl'],
            'IMAGEDIR' => $_PSL['imageurl'],
            'STORY_COLUMN' => $emailform."\n".$story_html."\n".$comments,
            'LEFT_BLOCK_COLUMN' => $leftblocks,
            'CENTER_TOP_BLOCK_COLUMN' => $centertopblocks,
            'RIGHT_BLOCK_COLUMN' => $rightblocks,
            //          'HEADER'     => $header,
         //          'EMAILFORM'  => $emailform,
         //          'STORY'      => $story_html,
         //          'ARROWS'     => $arrows,
         //          'FOOTER'     => $footer,
         //          'RELATED'    => $related,
         ));


         $updatearticlehits = $_PSL["article_updatehits"];
         if (($updatearticlehits) AND ($story_html) ) {
            $q = "UPDATE psl_story
               SET hits = hits + 1
               WHERE story_id = $story_id";
            $db->query($q);
         }

         slashhead($ttitle, $_PSL['metatags']);

         debug('HTTP_COOKIE_VARS', $_COOKIE);

         $t->parse('PAGE', 'article');
         $t->p('PAGE');
         slashfoot();

      }
      /* end of switch */

   } else {
      slashhead('Article', $xsiteobject);
      titlebar('100%', 'I am sorry, no Article Number '.htmlentities($story_id).' found.');
      echo "<br /><br />Maybe you're looking for a Poll?  Try this  <a href=\"" . $_PSL['rooturl'] . "/poll.php?submit=viewbooth&question_id=".htmlentities($story_id)."\">link</a>.<br /><br />\n";
      echo "If that doesn't work, then use the <a href=\"" . $_PSL['rooturl'] . "/search.php\">Search</a> Page.";
      slashfoot();
   }

   page_close();

?>