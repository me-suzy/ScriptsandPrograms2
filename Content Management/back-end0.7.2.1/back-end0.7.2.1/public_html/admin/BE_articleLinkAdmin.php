<?php

   /* $id: storyAdmin.php,v 1.0 2000/04/25 12:08:03 ajay Exp $ */

   // TODO: Move to generatePage

   require('./config.php');

   $pagetitle = pslgetText('Article-Link Administration'); // The name to be displayed in the header
   $xsiteobject = pslgetText('Administration');            // Defines The META TAG Page Type

   /* DEBUG */
   # debug( "HTTP_POST_VARS", $_POST);
   # debug( "HTTP_GET_VARS", $_GET);
   # debug( "section_del", $section_del );
   # debug( "section_ary", $section_ary );
   # debug( "description", $description );

   $link = pslNew('BE_Link');

   if ($perm->have_perm('story') || $perm->have_perm('root')) {
      $submit = pslgetText(getRequestVar('submit', 'PG'), '', true);
      $linkID = getRequestVar('linkID','PG');

      switch ($submit) {

         case 'delete':
         $link->deleteArticleLink($linkID);
         $storyInfo = $link->getTitlesForArticlesGroups(clean($_GET['list']), clean($_GET['next']), clean($_GET['orderby']));
         break;

         case 'save':
         $success = $link->saveArticleLink(clean($_POST));
         if ($success == false) {
            slashhead($pagetitle, $xsiteobject);
            $headerDisplayed = 'yes';
            $link->newArticleLink(clean($_POST), 'array');
         } else {
            slashhead($pagetitle, $xsiteobject);
            $headerDisplayed = 'yes';
            $storyInfo = $link->getTitlesForArticlesGroups(clean($_GET['list']), clean($_GET['next']), clean($_GET['orderby']));
         }
         break;

         case 'modify':
         slashhead($pagetitle, $xsiteobject);
         $headerDisplayed = 'yes';
         $link->getArticleLink($ary, $next);
         break;

         case 'edit':
         slashhead($pagetitle, $xsiteobject);
         $headerDisplayed = 'yes';
         $link->newArticleLink($linkID, 'database');
         break;

         case 'new':
         slashhead($pagetitle, $xsiteobject);
         $headerDisplayed = 'yes';
         $link->newArticleLink(clean($_POST), 'array');
         break;

         default:
         slashhead($pagetitle, $xsiteobject);
         $headerDisplayed = 'yes';
         $storyInfo = $link->getTitlesForArticlesGroups(clean($_GET['list']), clean($_GET['next']), clean($_GET['orderby']));

      }

   } else {
      titlebar('100%', 'Error! Invalid Privileges');
      echo 'Sorry. You do not have the necessary privilege to view this page.';
   }

   if (empty($headerDisplayed)) slashhead($pagetitle, $xsiteobject);

   // setup the template for the index page
   $tplfile = 'BE_bodyAdmin.tpl';
   $template = pslNew('slashTemplate', $_PSL['templatedir']);
   $template->debug = 0;
   $template->set_file('index', $tplfile);

   // generate the output for the entire page
   $template->set_var(array(
      'ROOTDIR' => $_PSL['rooturl'],
      'IMAGEDIR' => $_PSL['imageurl'],
      'STORY_COLUMN' => $storyInfo,
      'USERS_ONLINE' => $usersOnline
   ));
   $template->parse('OUT', 'index');

   // render the contents for this page
   $template->p('OUT');

   slashfoot();

   page_close();

?>