<?php
   // $Id: BE_config.php,v 1.147 2005/06/22 20:40:33 mgifford Exp $
   /**
    * Back-End configuration file
    *
    * @package     Back-End
    * @version     0.7 $Id: BE_config.php,v 1.147 2005/06/22 20:40:33 mgifford Exp $
    * @copyright   Copyright (C) 2003 OpenConcept Consulting
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

   // Script timer
   require_once($_PSL['classdir'] . '/BE_phpTimer.class');
   $scriptTimer = & pslSingleton('phpTimer');
   $scriptTimer->start('main');
   /* Use Example
   global $scriptTimer;  echo '<hr /><p>ScriptTimer: ' . $scriptTimer->get_current('main') . "</p>\n";
   $included_files = get_included_files(); echo "\n<ul>"; foreach($included_files as $filename) { echo "\r<li>$filename</li>\n"; } echo "</ul>\n";
   // Example use of memory tracking (print it all at the end)
#$startUsage[] = 'Memory Usage before BE_functions.inc : '. __FILE__ .'/'. __LINE__ .': ' .number_format(memory_get_usage()/1024) . ' Kb';
   */

   if(!function_exists('memory_get_usage')) {
      function memory_get_usage() {
         return null;
      }
   }

   require_once($_PSL['classdir'] . '/BE_functions.inc');
   require_once($_PSL['classdir'] . '/BE_Page.inc');

   // GENERAL SETTINGS

   // BE Version - PSL Version number defined in config.php
   $_BE['version'] = '0.7.2.1';

   /**#@+
   * Hard-wired usergroups
   */
   define('BE_USER_NOBODY', 20);
   define('BE_USER_USER', 21);
   define('BE_USER_SUPERUSER', 24);
   define('BE_USER_CONTENTPROVIDER', 200);
   define('BE_USER_CONTENTMANAGER', 204);
   /**#@-*/

   // Array of user data options available (in uppercase) for the BE_userEdit.tpl variables
   // These values will need to be added to the mysql table psl_author manually.
   $_BE['userRegistrationData']  = array('author_name', 'author_realname', 'email', 'url', 'quote','defaultCommentThreshold', 'question','answer');

   $_BE['article_file'] = $_PSL['mainpage']; // main file name - this is the starting point for all other scripts.

   // the url to the upload directory, no trailing slash! must be in a public location
   $_BE['uploadurl'] = $_PSL['rooturl'] . '/updir';
   $_BE['uploaddir'] = $_PSL['basedir'] . '/updir';

   // Breadcrumbs
   $_BE['displayBreadcrumbs'] = true;
   // Display Breadcrumbs - Optional - turn off (false) to conserve server resources
   if ($_BE['displayBreadcrumbs']) {
      $_BE['bread_delimiter'] = ' &#187; ';

      // breadcrumb delimiter - length at which titles get cut off in Breadcrumb
      $_BE['cutOffLength'] = 20;

      // show article link in breadcrumb navigation - leave blank for no display
      $_BE['breadshowpage'] = 'yes';
   }

   // Number of articles to list before being prompted for a next/previous button
   $_BE['defaultDisplayLimit'] = 10; // Default Display Limit (sidebars, sub-articles/sections, etc)
   $_BE['cmt_list'] = $_PSL['search_maxresults'];

   $_BE['indentedSectionAdmins'] = true;
   $_BE['defaultBlockDisplayLimit'] = 5;

   $_BE['error_section'] = 'ErrorDoc';

   // Array of files to be redirected to new locations.  Moved from errordocument.php
   $_BE['urlRedirectArray'] = array(
      #  "/OldURL" ==> '/NewURL',
      '/BE_article.php3' => '/' . $_BE['article_file'],
      '/index.php3' => '/' . $_BE['article_file'],
      '/index.php' => '/' . $_BE['article_file'],
      '/BE_link.php3' => '/links.php',
      '/BE_gallery.php3' => '/gallery.php'
   );

   $_BE['input.defaultformat'] = 'html'; // Format used for non HtmlArea input - html | wiki | text | exttrans

   // comment out for no WYSIWYG editor
   $_BE['HTMLAREA'] = 3; // 'FCKeditor';

   // Control process to clean html submitted by users with the list of approvedtags
   // $_BE['dontStripBadHTML'] = false;
   $_BE['cleanUserInput'] = 'strip_tags'; // stripBadHTML - use phpSlash's function with values defined in config.ini.php,
   // strip_tags - use php's function with values defined in config.ini.php,
   // false - no cleaning

   // work around for CGI php error not recognizing *.php/
   $_BE['cgiWorkAround'] = false;
   // Set to true only if *.php/ is not recorgnised

   // Enter here the blocks which will appear on all pages
   // The blocks are only applied to sections which are created *after* changes are made here
   $_BE['universalBlocks'] = array(1);
   // array(153) would make the 'Administration' block universal

   // Default values of options for Articles. See config.php for examples and explainations
   $_PSL['default_article_options'] = array(array(
      'name' => 'Author',
      'value' => '',
      'type' => 'text'
   ));

   // Standard keywords to always show in the keyword-selection popup
   $_BE['static_keywords']['en'] = array('Back-End', 'CMS');
   $_BE['static_keywords']['fr'] = array('Back-End', 'SGC');

   $_BE['Table_Prefix'] = 'be';
   // Table Prefix to use in database
   // Allows for a bit more flexibility

   // BE4.x Tables to perhaps reintroduce
   // $_BE['Table_useronline'] = $_BE['Table_Prefix'] . '_useronline';

   $_BE['templatedir_base'] = $_PSL['basedir'] . '/templates'; // below $_PSL['templatedir'];

   // Users can eliminate which templates are searched for by the function getUserTemplates()
   // by commenting lines in this array (this will reduce server load if you aren't using them).
   // getUserTemplates() searches the BE_default directory for templates defined in the section
   // or article template field, ie. 'home' will search for slashHead-home.tpl, slashFoot-home.tpl, etc.
   // This function is also recursive so that parent & grandparent definitions can be pulled in.
   $_BE['userDrivenTemplateAry'] = array(
      'header'        => 'slashHead',
      'footer'        => 'slashFoot',
      'tpl1col'       => 'index1col',
      'tpl2col-right' => 'index2colright',
      'tpl2col-left'  => 'index2colleft',
      'tpl3col'       => 'index3col',
      'linkIndex'     => 'BE_linkIndex',
      'articleIndex'  => 'BE_articleIndex',
      'subsection'    => 'BE_subsection',
      'section'       => 'BE_section',
      'sectionLinks'  => 'BE_sectionLinks'
   );

   //  When inheriting templates, how many layers up should
   // Back-End look?  The default of 2 looks at the parent
   // and grand-parent.
   $_BE['AncestorTemplateDepth'] = 2;

   //  Should we add one to alternating class values?  If this is set
   // to 1, then the ALTERNATING_CLASS template variables for rows will
   // go 0-2-1-2-1-2-etc; otherwise, they will go 0-1-0-1-0-1-etc.
   $_BE['IncrementAlternatingClass'] = 1;

   // Once the navigation is stable you can turn this to off.
   $_BE['useJavascriptDD'] = 'css'; // 'inline' | 'domMenu' | 'css'  | false

   // Display Home link and Misc BE options in dropdown
   $_BE['JavascriptDD_display'] = array('Home', 'Misc');

   // Maximum depth for the CSS menu is 2 because of limitations with CSS markup
   // 0 -> No menu (i suppose)
   // 1 -> Menu with top items only
   // 2 -> First sub-menu
   $_BE['maxCSSmenuDepth'] = 2;

   // Where to find the online manual - no help button is shown if this is empty
   $_BE['manual.url'] = 'http://manual.back-end.org/index.php';

   // Admin button sizes. Optional - <img> width + height are not set if these are empty
   $_BE['adminbutton.width']  = 23;
   $_BE['adminbutton.height'] = 23;

   $_BE['manual.url']   = 'http://manual.back-end.org/index.php';
   $_BE['adminbutton.height'] = 23;
   $_BE['adminbutton.width']  = 23;

   //  Debug output file listing all pslGetText requests which could not
   // be translated.
   $_BE['debugStringLog'] = '/tmp/beStringLog';

   // Date/Time Formatting:
   //
   // Date/Time output formatting is controlled using psl functions.
   // The formats are specified in $_PSL['classdir'].'/locale/<language>.LC_TIME.php'.
   // In general date_time_format_short is used in lists and
   // date_time_format_long is used in items.
   //
   // Date/times are always stored in the database assuming the current
   // server timezone. If the [timezone] engine is enabled in config.ini.php
   // then date/times are converted from server timezone to the user timezone
   // before display, and from user timezone to server timezone on input.

   // Max nb of articles per section on the sitemap.php page
   $_BE['siteMap.ArticlesPerSection'] = 3;

   // sitemap orderby & sort logic
   $_BE['siteMap.orderby'] = 'priority';
   $_BE['siteMap.ascdesc'] = 'desc';


   // CLASS REQUIREMENTS/REPLACEMENTS  =================================================

   addClassReplacement('BEDB', 'slashDB');
   addClassReplacement('Author', 'BE_User');

   require_once($_PSL['classdir'] . '/BE_DB.class');
   require_once($_PSL['classdir'] . '/fileCache.class.php');   // Used for general caching

   // *******************
   // BACK-END INSERT - LDAP CONFIG
   // Authentication Type (auth_type:integer)
   // *******************

   if ($_PSL['auth_type'] == '2') {

      // Apply default settings if necessary
      if (empty($_PSL['LDAP_Host'])) $_PSL['LDAP_Host'] = 'localhost';
      if (empty($_PSL['LDAP_Port'])) $_PSL['LDAP_Port'] = '389';
      if (empty($_PSL['LDAP_Base_dn'])) $_PSL['LDAP_Base_dn'] = 'dc=localhost,dc=back-end,dc=org';
      if (empty($_PSL['LDAP_Search_detail'])) $_PSL['LDAP_Search_detail'] = 'cn';
      if (empty($_PSL['LDAP_BE_uid'])) $_PSL['LDAP_BE_uid'] = 'uid'; //name of uid in the ldap that coresponds to BE uid

      // user and password for updating LDAP directory
      //   $_PSL['LDAP_edit_user']       = '';
      //   $_PSL['LDAP_edit_user_pass']  = 'secret';
      //   $_PSL['LDAP_edit_user_dn']    = 'cn=Manager,dc=waiter,dc=thepinecone,dc=com';

   } // END BACK-END INSERT - LDAP CONFIG


   // *******************
   // MODULE: BE_LANGUAGE
   // *******************

   // Includes for BE_Language
   if ($_PSL['module']['BE_Language']) {

      $_BE['Default_language'] = $_PSL['language'];
      $lang = $_BE['Default_language']; // Override psl setting

      //  Should Back-End fall back on the default language, if the
      // request language isn't present?
      $_BE['Language_Fallback'] = true;

      // There must be at least one entry, 2 char ISO language codes are used.
      $_BE['Language_array'] = array($_BE['Default_language'], 'fr');

      // For each language above, define a URLname if different (lower case)
       // PAC: This approach assumes that Default_language is not french!?
      $_BE['Language_site_homesection'] = array(
         $_BE['Default_language'] => 'home',
         'fr' => 'accueil'
      );

      // For each language above, define how you want to select/identify the new language
      $_BE['Language_Switching'] = array(
         'en' => 'English',
         'fr' => 'fran&ccedil;ais'
      );

      // Date constants and Input resolution
      $_BE['DATE_YEAR']   = 0;
      $_BE['DATE_MONTH']  = 1;
      $_BE['DATE_DAY']    = 2;
      $_BE['DATE_HOUR']   = 3;
      $_BE['DATE_MINUTE'] = 4;
      $_BE['DATE_SECOND'] = 5;

      // Specify the resolution used for forms that have date/time entry
      // e.g. Article admin
      $_BE['DateInputResolution'] = $_BE['DATE_MINUTE'];

      //If defined, is used to work out what language to use from the current domain
      // $_BE['languagedomains'] = array ('back-end.org'     => 'en',
      //                                 'french.back-end.org' => 'fr');

      // MySQL Tables
      $_BE['Table_language'] = $_BE['Table_Prefix'] . '_language';

      // Add in info to support mixed Bi-directional output
      foreach ($_BE['Language_array'] as $languageID) {
         $scriptDef = $_PSL['classdir'] . "/locale/script_$languageID.php";
         if (file_exists($scriptDef)) {
            include_once($scriptDef);
         } else {
            // Default to western characteristics for now
            $_BE['script'][$languageID] = array(
               'lang'   => $languageID,
               'charset'=> 'iso-8859-1',
               'left'   => 'left',
               'right'  => 'right',
               'dir'    => 'ltr'
            );
         }
      }
      if (!isset($_BE['script'][$_BE['Default_language']])) {
         // Just in case the scipt_en.php file is there, but screwed
         $_BE['script'][$_BE['Default_language']] = array(
                     'lang'   => $_BE['Default_language'],
                     'charset'=> 'iso-8859-1',
                     'left'   => 'left',
                     'right'  => 'right',
                     'dir'    => 'ltr'
                     );
      }
      # debug(__FILE__.__LINE__." Charsets",$_BE['script']);
   } // END MODULE: BE_LANGUAGE


   // ******************
   // MODULE: BE_SECTION
   // ******************

   if ($_PSL['module']['BE_Section']) {

      // Disabled and moved to - Block_render_BE_linkedArticles.class - mg May2005
      // $_BE['allowArticleLinks'] = false; // Allow for link to articles from related articles

      // Should be moved to be a block - mg May2005
      $_BE['showSectionLinks'] = false; // True if related links are to be shown on Section page

      $_BE['default_section'] = $_PSL['site_homesection']; // If a section isn't specified, which should be used

      $_BE['sectionshierarchical'] = true; // Only allow sections to have one parent - required to support section-based security

      $_BE['sectionMarkDelete'] = true; // Don't actually delete Sections, just mark them deleted

      // Max number of sections to list per page.
      $_BE['maxSectionsPerPage'] = $_BE['defaultDisplayLimit'];

      // Max number of links to list per page.
      $_BE['maxLinksPerPage'] = $_BE['defaultDisplayLimit'];

      // Used by article lists (spotlight, new articles, sub-articles)
      $_BE['defaultArticlesOrder'] = 'dateCreated'; // Sort By: title, articleID, spotlight, dateCreated, dateModified, dateAvailable, dateRemoved, hitCounter, priority, URLname
      $_BE['defaultArticlesLogic'] = 'desc'; // either asc or desc
      $_BE['secondaryArticlesOrder'] = 'title';
      $_BE['secondaryArticlesLogic'] = 'asc';

      $_BE['Table_sections'] = $_BE['Table_Prefix'] . '_sections';
      $_BE['Table_sectionText'] = $_BE['Table_Prefix'] . '_sectionText';
      $_BE['Table_section2section'] = $_BE['Table_Prefix'] . '_section2section';

      // Menu setting for BE_Section
      $_PSL['menuadmin'][] = array(
         'name' => 'Section',
         'link' => $_PSL['adminurl'] . '/BE_sectionAdmin.php',
         'perm' => 'section',
         'module' => 'BE_Section',
         'group' => 'content',
         'order' => 21
      );

   } // END MODULE: BE_SECTION


   // ******************
   // MODULE: BE_ARTICLE
   // ******************

   if ($_PSL['module']['BE_Article']) {

      $_BE['Table_articles'] = $_BE['Table_Prefix'] . '_articles';
      $_BE['Table_articleText'] = $_BE['Table_Prefix'] . '_articleText';
      $_BE['Table_articleTextOptions'] = $_BE['Table_Prefix'] . '_articleTextOptions';
      $_BE['Table_article2section'] = $_BE['Table_Prefix'] . '_article2section';
      $_BE['Table_keyword2article'] = $_BE['Table_Prefix'] . '_keyword2article';

      $_BE['inlineImages'] = true;
      $_BE['inlineDocuments'] = true;

      $_BE['articleMarkDelete'] = true; // Don't actually delete Articles, just mark them deleted

      // Menu setting for BE_Article
      $_PSL['menuadmin'][] = array(
         'name' => 'Article',
         'link' => $_PSL['adminurl'] . '/BE_articleAdmin.php',
         'perm' => 'story',
         'module' => 'BE_Article',
         'group' => 'content',
         'order' => 22
      );

   } // END MODULE: BE_ARTICLE


   // ******************
   // MODULE: BE_BLOCK
   // ******************
   if ($_PSL['module']['Block']) {
      $_BE['defaultBlockType'] = 'html'; // default new block type
//Mar05 cleanup
      require_once($_PSL['classdir'] . '/BE_Block.class');
      require_once($_PSL['classdir'] . '/BE_Block_i.class');

      addClassReplacement('Block', 'BE_Block');
      addClassReplacement('Block_i', 'BE_Block_i');
      addClassReplacement('Block_admin', 'BE_Block_admin');

      $_BE['blockTypeDescription'] = array(
         'html'                   => 'Hard-coded HTML content',
         'rss'                    => 'RSS feed',
         'poll'                   => 'Poll question',
         'login'                  => 'Login box',
         'navbar'                 => 'My CUPE bar',
         'url'                    => 'Entire contents of a page',
         'BE_petitions'           => 'Petitions',
         'BE_petitionSigners'     => 'Petition Signatures',
         'BE_action'              => 'Online actions',
         'BE_upcomingEvents'      => 'Upcoming events',
         'BE_myHeadlines'         => 'Custom RSS headlines',
         'BE_strikes'             => 'Ongoing strikes',
         'BE_banner'              => 'Rotating advertising banner',
         'BE_sectionList'         => 'Top-level sections',
         'BE_spotlightArticles'   => 'Spotlight articles',
         'BE_newArticles'         => 'New articles',
         'BE_relatedKeywords'     => 'Related articles',
         'BE_recentPopular'       => 'Most popular articles');

   } // END MODULE: BE_BLOCK


   // ******************
   // MODULE: Default Search Settings
   // ******************
   $_BE['search_class'] = 'BE_Search';
   $_BE['search_url'] = $_PSL['rooturl'].'/search.php';
   $_BE['search_section'] = 'Home';


   // ******************
   // MODULE: BE_ADVANCED_SEARCH
   // ******************
   if (@$_PSL['module']['BE_advancedSearch']) {
      $_BE['search_url'] = $_PSL['rooturl'] . '/search.php';
   } // END MODULE: BE_ADVANCED_SEARCH


   // ******************
   // MODULE: BE_GOOGLE_SEARCH
   // ******************
   if (@$_PSL['module']['BE_googleSearch']) {

      // Use google class in search.php
      $_BE['search_class'] = 'BE_GoogleSearch';
   } // END MODULE: BE_GOOGLE_SEARCH


   // ******************
   // MODULE: BE_LINK
   // ******************

   // Includes for BE_Link
   if (@$_PSL['module']['BE_Link']) {

      $_BE['link_file'] = 'links.php'; // link file name
      $_BE['link_submit_file'] = 'linkSubmit.php'; // link file name
      $_BE['recursivelyInheritLinks'] = false; // Not sure what this does

      // If true, a `Submit a Link` link will be available to users for sections
      // that allow it.
      $_BE['showLinkSubmit'] = false;

      // A link submission notification email will be sent to the following address
      // if set.
      $_BE['linkAdminEmail'] = $_PSL['site_owner'];

      // MySQL Tables
      $_BE['Table_links'] = $_BE['Table_Prefix'] . '_link';
      $_BE['Table_linkText'] = $_BE['Table_Prefix'] . '_linkText';
      $_BE['Table_link2section'] = $_BE['Table_Prefix'] . '_link2section';

      $_BE['Table_link2articlesGroup'] = $_BE['Table_Prefix'] . '_link2articlesGroup';
      $_BE['Table_link2articlesGroupText'] = $_BE['Table_Prefix'] . '_link2articlesGroupText';

      // Link validation table.
      $_BE['Table_linkTextValidation'] = $_BE['Table_Prefix'] . '_linkTextValidation';

      // Menu setting for BE_Link
      $_PSL['menuitem'][] = array(
         'name' => 'Links',
         'link' => $_PSL['rooturl'] . '/' . $_BE['link_file'],
         'perm' => 'nobody',
         'module' => 'BE_Link',
         'group' => 'content',
         'order' => 23
      );

      // Menu setting for BE_LinkAdmin
      $_PSL['menuadmin'][] = array(
         'name' => 'Links',
         'link' => $_PSL['adminurl'] . '/BE_linkAdmin.php',
         'perm' => 'linkAdmin',
         'module' => 'BE_Link',
         'group' => 'content',
         'order' => 23
      );

   } else {
      // Clear out link-related options
      $_BE['allowArticleLinks'] = false;
      $_BE['showSectionLinks'] = false;

   } // END MODULE: BE_LINK


   // ******************
   // MODULE: BE_UPLOAD
   // ******************

   if (@$_PSL['module']['BE_Upload']) {
      $_BE['acceptableFileTypes'] = array('.wpd', '.doc', '.rtf', '.xls', '.txt', '.html', '.htm', '.pdf', '.gif', '.jpg', '.jpeg', '.png', '.gz', '.zip', '.rar', '.ppt', '.mp3', '.bmp', '.swf', '.sxc', '.sxd', '.sxi', '.sxg', '.sxc', '.sxp', '.sxw', '.svw', '.wmv', '.avi', '.wav');

      // Is this database enabled?  true if enabled (false has not been tested)
      $_BE['UploadDatabaseEnabled'] = true; //  <===========

      // MySQL Table
      $_BE['Table_upload'] = $_BE['Table_Prefix'] . '_upload';

      if ($_BE['UploadDatabaseEnabled']) {
         $_DB['DB_Host'] = $_PSL['DB_Host'];
         $_DB['DB_Database'] = $_PSL['DB_Database'];
         $_DB['DB_User'] = $_PSL['DB_User'];
         $_DB['DB_Password'] = $_PSL['DB_Password'];
      }

      // Menu setting for uploadAdmin
      $_PSL['menuadmin'][] = array(
         'name' => 'Upload',
         'link' => $_PSL['adminurl'] . '/BE_uploadAdmin.php',
         'perm' => 'upload',
         'module' => 'BE_Upload',
         'group' => 'layout',
         'order' => 71
      );

   } // END MODULE: BE_UPLOAD


   // ******************
   // MODULE: BE_EditTemplate
   // ******************

   if (isset($_PSL['module']['BE_EditTemplate']) && $_PSL['module']['BE_EditTemplate']) {

      // Set variables (which files to view/edit, template directories
      $_BE['editTemplate.editingPermissions'] = array('.tpl', '.txt', '.css'); // ('*.html,*.htm,*.txt') separated with comma
      // $_BE['editTemplate.rootDirectory'] =  $_BE['templatedir_base']; // $_PSL['basedir'] . '/templates';
      $_BE['editTemplate.subsiteTemplates'] = array(
         'index3col.tpl', 'index1col.tpl', 'index2colleft.tpl',
         'index2colright.tpl', 'slashHead.tpl', 'slashFoot.tpl',
         'index.tpl', 'BE_articleIndex.tpl'
      );

      $_BE['editTemplate.cssEditor'] = true;

      $_BE['editTemplate.backup'] = true;  // false, true, 'timestamp'

      // Menu setting for editTemplate
      $_PSL['menuadmin'][] = array(
         'name' => 'Templates',
         'link' => $_PSL['adminurl'] . '/BE_editTemplateAdmin.php',
         'perm' => 'template',
         'module' => 'BE_EditTemplate',
         'group' => 'layout',
         'order' => 72
      );

   } // END MODULE: BE_EditTemplate



   // ******************
   // MODULE: BE_EditLocale
   // ******************

   if (isset($_PSL['module']['BE_EditLocale']) && $_PSL['module']['BE_EditLocale']) {

      $_BE['editTemplate.cssEditor'] = true;

      $_BE['editTemplate.backup'] = true;  // false, true, timestamp

      // Menu setting for editTemplate
      $_PSL['menuadmin'][] = array(
         'name' => 'Locale',
         'link' => $_PSL['adminurl'] . '/BE_editLocaleAdmin.php',
         'perm' => 'template',
         'module' => 'BE_EditLocale',
         'group' => 'layout',
         'order' => 73
      );

   } // END MODULE: BE_EditLocale



   // ******************
   // MODULE: BE_GALLERY
   // ******************

   // Includes for BE_Gallery
   if (@$_PSL['module']['BE_Gallery']) {

      // Params
      // PAC - should move to $_BE['gallery.vars']
      $galleryVars['thumbnails_per_row'] = 10;
      $galleryVars['max_file_size'] = 1024 * 1024; // 1 Mb
      $galleryVars['max_search_results'] = 32;
      $galleryVars['xscale'] = 0.15; // Thumbnail default is 0.1 or 10%
      $galleryVars['yscale'] = 0.15; // Thumbnail default is 0.1 or 10%
      $galleryVars['maxThumbWidth'] = 80; // Desired default pixel width/height
      $galleryVars['maxThumbHeight'] = 80; // Desired default pixel width/height
      $galleryVars['dbORfile'] = 'db'; // Presently only set up to save/retrieve from db

      $_BE['gallery_file'] = 'gallery.php';
      $_BE['galleryAdmin_file'] = 'BE_galleryAdmin.php';

      // MySQL
      $_BE['Table_images'] = $_BE['Table_Prefix'] . '_images';
      $_BE['Table_imageText'] = $_BE['Table_Prefix'] . '_imageText';
      $_BE['Table_image2section'] = $_BE['Table_Prefix'] . '_image2section';

      // Menu setting for BE_Gallery
      $_PSL['menuadmin'][] = array(
         'name' => 'Gallery',
         'link' => $_PSL['adminurl'] . '/BE_galleryAdmin.php',
         'perm' => 'gallery',
         'module' => 'BE_Gallery',
         'group' => 'content',
         'order' => 24
      );
      $_PSL['menuitem'][] = array(
         'name' => 'Gallery',
         'link' => $_PSL['rooturl'] . '/' . $_BE['gallery_file'],
         'perm' => 'nobody',
         'module' => 'BE_Gallery',
         'group' => 'content',
         'order' => 24
   );

   } // END MODULE: BE_GALLERY


   // ******************
   // MODULE: BE_Categories
   // ******************

   if (@$_PSL['module']['BE_Categories']) {

      // Hardcoded to save a DB query
      $_BE['category']['types'] = array('CATDIVN', 'CATISSUE', 'CATSECT'); // <==============

      // MySQL Tables
      $_BE['Table_categories'] = $_BE['Table_Prefix'] . '_categories';
      $_BE['Table_category2item'] = $_BE['Table_Prefix'] . '_category2item';

   } // END MODULE: BE_Categories


   // ******************
   // MODULE: BE_Subsite
   // ******************
   // Must be defined last, so that it can manipulate the other modules' data

   if (@$_PSL['module']['BE_Subsite']) {

      /*
       * General settings
       */

      // Values used for mapping subsites to sections
      // These values can be changed to match your configuration

      // What is added in front of numeric Subsite ids to make
      // them into valid saction URLnames
      $_BE['subsite.sectionprefix'] = 'Subsite';

      // How deep to go when building up section paths for subsites
      $_BE['subsite.maxdepth'] = 5;

      $_BE['subsite.pathseparator'] = ':';

      // Could be used later to give alternative ways of tracking subsites - eg through pseudo-paths instead of sub-domains
      #   $_BE['subsite.source']   = 'subdomain';    // subdomain | path - Where to find which subsite is being used

      // These entries are used for holding global settings while the user is in a subsite
      // Used internally. DO not alter.
      $_BE['subsite.roothost'] = $_PSL['rootsubdomain'].$_PSL['rootdomain'];
      $_BE['subsite.urlbase'] = $_PSL['rootdomain'].$_PSL['rooturl']; // eg cupe.ca/public_html/ - ie url without subdomain info

      $_BE['subsite.rooturl'] = $_PSL['rooturl'];
      // Record path to site home even when overridden for subsite.
      $_BE['subsite.uploaddir'] = $_BE['uploaddir'];
      $_BE['subsite.uploadurl'] = $_BE['uploadurl'];
      $_BE['subsite.default_section'] = $_BE['default_section'];
      $_BE['subsite.defaultsubsite'] = 'DefaultSubsite';

      // MySQL Tables
      $_BE['Table_subsites'] = $_BE['Table_Prefix'] . '_subsites';
      $_BE['Table_subsite_types'] = $_BE['Table_Prefix'] . '_subsite_types';
      $_BE['Table_subsite_block_lut'] = $_BE['Table_Prefix'] . '_subsite_block_lut';

      /*
       * Author/subsite permssions
       */

      #   $_PSL['module']['Author']        = false; // Disable default user-management module

      // MySQL Tables
      $_BE['Table_group'] = 'psl_group';
      $_BE['Table_author_group_lut'] = 'psl_author_group_lut'; //$_BE['Table_Prefix'] . '_author2localrights'; // psl_author_group_lut

      // Implicit requirements: BEDB,slashTemplate, (also LDAP if that is enabled)

      // Menu setting for BE_Subsite Directory
      // Removed from default BE menu -12june03:mg
      $_PSL['menuitem'][] = array(
         'name'   => 'Subsite',
         'link'   => $_PSL['rooturl'] . '/BE_subsite.php',
         'perm'   => 'nobody',
         'module' => 'BE_Subsite',
         'group' => 'subsite',
         'order' => 80
      );
      // Menu setting for BE_SubsiteAdmin
      $_PSL['menuadmin'][] = array(
         'name'   => 'Subsite',
         'link'   => "http://{$_PSL['rootsubdomain']}{$_PSL['rootdomain']}/admin/BE_subsiteAdmin.php",
         'perm'   => 'subsite',
         'module' => 'BE_Subsite',
         'group' => 'subsite',
         'order' => 80
      );

   } // END MODULE: BE_Subsite


   // ******************
   // MODULE: Register
   // ******************
   // if (isset($_PSL['module']['BE_Register']) && $_PSL['module']['BE_Register']) {
   // No config right now
   // } // END MODULE: Register


   // ******************
   // MODULE: BE_Events
   // ******************

   if (isset($_PSL['module']['BE_Events']) && $_PSL['module']['BE_Events']) {
      $_BE['EventsFile'] = 'events.php';

      // Name of section to show events calendar in
      $_BE['EventsSection'] = 'Home';

      // Show Public Add Article Link
      $_BE['EventsPublicAddLink'] = false;

      //Allow anonymously posted drafts to be visible
      $_BE['EventsDraftVisible'] = false;

      // Default calendar name
      $_BE['EventsDefaultCalendarName'] = 'default';

      // Default calendar is included in all calendars
      $_BE['EventsDefaultIncludedInAll'] = false;

      // Set default calendar to show all calendars
      $_BE['EventsDefaultIncludesAll'] = false;

      // Links to events should follow parent calendar
      $_BE['EventsLinksFollowParentCalendar'] = true;

      // Uncomment this to sent email notices for anonymously submitted events
      // $_BE['EventsDraftEmailNotice'] = $_PSL['site_owner'];

      // Menu settings for BE_Events
      $_PSL['menuitem'][] = array(
         'name' => 'Events',
         'link' => $_PSL['rooturl'] . '/' . $_BE['EventsFile'],
         'perm' => 'nobody',
         'module' => 'BE_Events',
         'group' => 'content',
         'order' => 25
      );

   } // END MODULE: BE_Events


   // ******************
   // MODULE: BE_Phplist
   // ******************
   if (isset($_PSL['module']['BE_Phplist']) && $_PSL['module']['BE_Phplist']) {

      $_BE['phplist']['link'] = $_PSL['rooturl'] . '/lists?p=subscribe';

      // User menu settings for BE_Phplist
      $_PSL['menuitem'][] = array(
         'name' => 'Newsletter',
         'link' => $_BE['phplist']['link'],
         'perm' => 'nobody',
         'module' => 'BE_Phplist',
         'group' => 'misc',
         'order' => 61
      );

   } // END MODULE: BE_Phplist


   // ******************
   // MODULE: BE_Poll
   // ******************
   if (@$_PSL['module']['Poll']) {
      addClassReplacement('Poll','BE_Poll');
   } // END MODULE: BE_Poll


   // ******************
   // MODULE: BE_History
   // ******************
   if (@$_PSL['module']['BE_History']) {
      $_BE['Table_history'] = 'be_history';
   } // END MODULE: BE_History


   // ******************
   // MODULE: BE_BlockCache
   // ******************
   if (@$_PSL['module']['BE_BlockCache']) {
      // Note: Both the source files mentioned below define a number of private internal classes
      $_BE['Table_blockcache'] = 'be_blockcache';

      // If your block type IDs are different from the default, you may need
      // to change some of these values.
      $_BE['blockTypeID']['BE_action'] = 90;
      $_BE['blockTypeID']['BE_myHeadlines'] = 91;
      $_BE['blockTypeID']['BE_newForYou'] = 92;
      $_BE['blockTypeID']['BE_upcomingEvents'] = 111;
      $_BE['blockTypeID']['BE_strikes'] = 94;
      $_BE['blockTypeID']['BE_spotlightArticles'] = 101;
      $_BE['blockTypeID']['BE_newArticles'] = 102;
      $_BE['blockTypeID']['BE_whatsPopular'] = 106;
      $_BE['blockTypeID']['BE_recentPopular'] = 112;
      $_BE['blockTypeID']['BE_relatedKeywords'] = 107;
   } // END MODULE: BE_BlockCache


   // ******************
   // MODULE: BE_Contact
   // ******************

   if ($_PSL['module']['BE_Contact']) {
      $_BE['Table_contact'] = $_BE['Table_Prefix'] . '_contact';
      $_BE['Table_contactType'] = $_BE['Table_Prefix'] . '_contactType';

     // Menu settings for BE_Contact
      $_PSL['menuadmin'][] = array(
         'name' => 'Contact',
         'link' => $_PSL['adminurl'] . '/BE_contactAdmin.php',
         'perm' => 'contact',
         'module' => 'BE_Contact',
         'group' => 'action',
         'order' => 58
      );
      $_PSL['menuadmin'][] = array(
         'name' => 'Followup',
         'link' => $_PSL['adminurl'] . '/BE_followupAdmin.php',
         'perm' => 'action',
         'module' => 'BE_Followup',
         'group' => 'action',
         'order' => 59
      );

   } // END MODULE: BE_Contact


   // ******************
   // MODULE: ECclient
   // ******************

   if (isset($_PSL['module']['ECclient']) && $_PSL['module']['ECclient']) {
      define('XML_RPC_SITE', 'services.flora.ca');
      define('XML_RPC_LOCATION', '/ec_tools/server.php');
   } // END MODULE: ECclient


   // ******************
   // MODULE: BE_Fax
   // ******************

   if (isset($_PSL['module']['BE_Fax']) && $_PSL['module']['BE_Fax']) {
      $_BE['FaxModule'] = 'BE_MyFax'; // or BE_Hylafax
      $_BE['FaxPassword'] = NULL;
      $_BE['FaxBillingCode'] = "Action_Fax_";
      $_BE['FaxBillingCodeUseActionNo'] = true;
      $_BE['MailAllowsSpace'] = false;
      $_BE['FaxSender'] = $_PSL['site_owner'];
   }
   // END MODULE: BE_Fax


   // ******************
   // MODULE: BE_Action
   // ******************

   if (isset($_PSL['module']['BE_Action']) && $_PSL['module']['BE_Action']) {
      $_BE['Action_MPLookupURL'] = 'http://www.parl.gc.ca/information/about/people/house/PostalCode.asp?lang=E&txtPostalCode=';

      $_BE['actionFile'] = 'action.php';

      $_BE['Action_MPLookupPathToImage'] = '/html/body/table/tr/td/img[@alt]/@src';
      $_BE['Action_MPLookupPathToRiding'] = '/html/body/table/tr/td/p/font/b/text()[1]';
      $_BE['Action_MPLookupPathToParty'] = '/html/body/table/tr/td/p/font/b/text()[2]';
      $_BE['Action_MPLookupPathToName'] = '/html/body/table/tr/td/p/font/b/a/text()';
      $_BE['Action_MPLookupPathToParlAddress'] = '/html/body/table/tr/td/p/font//b[text()="Parliamentary Address"]/following::p[1]/text()';
      $_BE['Action_MPLookupPathToRidingAddress'] = '/html/body/table/tr/td/p/font//b[text()="Constituency Address"]/following::p[1]/text()';
      $_BE['AddressRegexps'] = array("/(?s)(.+)\n([-\w']+), ?([-\w']+)(?:, ?([-\w']+))?\n([A-Z][0-9][A-Z] ?[0-9][A-Z][0-9])(?:\n([-\w':]+)\n)?/"
                           => array('','address','city','province','country','postalCode','country'),
                           "/^Telephone: *([0-9\(\)\- ]+)/m" => array('','telephone'),
                           "/^Fax: *([0-9\(\)\- ]+)/m" => array('','fax'),
                           "/^E-Mail: *([\w0-9@.-_]+)/m" => array('','email'),
                           );
      $_BE['AddressDefaultCountry'] = 'Canada';
      $_BE['ActionShowCounterMin'] = 100;
      $_BE['ActionShowHitsMin'] = 50;

      // MySQL Tables
      $_BE['Table_target'] = $_BE['Table_Prefix'] . '_target';
      $_BE['Table_targetType'] = $_BE['Table_Prefix'] . '_targetType';
      $_BE['Table_action'] = $_BE['Table_Prefix'] . '_action';
      $_BE['Table_actionType'] = $_BE['Table_Prefix'] . '_actionType';
      $_BE['Table_targetType'] = $_BE['Table_Prefix'] . '_targetType';
      $_BE['Table_actionText'] = $_BE['Table_Prefix'] . '_actionText';
      $_BE['Table_action2section'] = $_BE['Table_Prefix'] . '_action2section';
      $_BE['Table_action2contact'] = $_BE['Table_Prefix'] . '_action2contact';

      // Menu settings for BE_Action
      $_PSL['menuitem'][] = array(
         'name' => 'Action',
         'link' => $_PSL['rooturl'] . '/' . $_BE['actionFile'],
         'perm' => 'nobody',
         'module' => 'BE_Action',
         'group' => 'action',
         'order' => 51
      );

      // Menu settings for BE_Action
      $_PSL['menuadmin'][] = array(
         'name' => 'Action',
         'link' => $_PSL['adminurl'] . '/BE_actionAdmin.php',
         'perm' => 'action',
         'module' => 'BE_Action',
         'group' => 'action',
         'order' => 52
      );

   } // END MODULE: BE_Action


   // ******************
   // MODULE: BE_Petition
   // ******************

   if(@$_PSL['module']['BE_Petition']) {

     // User-driven templates
      $_BE['userDrivenTemplateAry'] = $_BE['userDrivenTemplateAry'] + array(
         'petitionSign'              =>  'BE_petitionSign',
         'petitionInviteFriends'     =>  'BE_petitionInviteFriends',
         'petitionViewSignatures'    =>  'BE_petitionViewSignatures'
      );

      // MySQL Tables
      //  We should perhaps change the table prefix to be_ for consistency,
      // but that would break backwards compatibility.
      $_PET['Table_Prefix'] = 'pet_';
      $_BE['Table_petition']         = $_PET['Table_Prefix'] . 'petition';
      $_BE['Table_petitionText']     = $_PET['Table_Prefix'] . 'petitionText';
      $_BE['Table_petition2section'] = $_PET['Table_Prefix'] . 'petition2section';
      $_BE['Table_petition2contact'] = $_PET['Table_Prefix'] . 'petition2contact';

      //  We're not currently storing invitations, and maybe should be.
      // Previously, they were stored in this _alert table.
      $_BE['Table_alert'] = $_PET['Table_Prefix'] . 'alert';

      //  This is the list of countries.  It would be potentially nice to
      // bring back the automatically generated drop-down list, using this.
      $_BE['Table_country'] = $_PET['Table_Prefix'] . 'country';

      // Should some or all of these messages be customizable in the admin
      // interface?  Many of them would be more effective tailored to specific
      // campaigns.  In the case of the invitation subject, we might want to
      // let the person inviting specify that.  However, at least we've
      // centralized all hard-coded strings into one place so that we can
      // easily pull them out.
      $_BE['Petition_petitionNotFoundMessage'] = 'We could not find the petition you asked for.';
      $_BE['Petition_insertPetitionFailedMessage']='We could not save this petition - please check all the fields and try again.';
      $_BE['Petition_updatePetitionFailedMessage']='We could not save this petition - please check all the fields and try again.';
      $_BE['Petition_verificationErrorMessage']='Your signature could not be verified.  If you are copying or pasting from e-mail, please make sure that you get the whole link.';
      $_BE['Petition_verificationSuccessMessage']='Thank you for signing our petition.  Please ask your friends to sign it, too.';
      $_BE['Petition_signatureMissingFieldsMessage']='You must fill in all of the mandatory fields.';
      $_BE['Petition_emailVerificationRequired'] = 'Thank you for signing our petition.  In order to increase the credibility of this petition, we have sent a link to the e-mail address that you provide, which you must click in order to finish signing this petition.';
      $_BE['Petition_signatureComplete'] = 'Thank you for signing our petition.';
      $_BE['Petition_alreadySignedPetitionMessage'] = 'You seem to have already signed this petition - thank you for your interest, and please tell your friends.';
      $_BE['Petition_confirmationEmailSubject'] = 'Confirm your petition signature.';
      $_BE['Petition_invitationMissingFieldsMessage'] = 'You must enter your name and e-mail address, so that we can tell your friends who the invitation is from.';
      $_BE['Petition_invitationNoRecipientsMessage'] = 'You didn\'t enter anyone we should send the message to.';
      $_BE['Petition_invitationSentMessage'] = 'Invitations have been sent to your friends.  Thank you very much for your support.';
      $_BE['Petition_invitationEmailSubject'] = 'Please sign this petition.';
      $_BE['Petition_requiresEmailVerification']=true;

      $_BE['Petition_defaultConfirmEmail']='Dear [FIRST_NAME],

We think that you signed our petition, available at
   [HOME_URL]

To confirm your signature, please go to the following link:
   [VERIFY_URL]

If you did not sign this petition, this means that someone else entered your e-mail address - but don\'t worry: unless you click on the confirmation link, your signature will not be counted.';

      $_BE['Petition_defaultAlertEmail']='You should enter in something here that will appear at the top of every e-mail that people send to friends inviting them to sign the petition.  It should BRIEFLY explain why this petition is important and why they should take the time to sign it.';

      $_BE['Petition_signaturesOrderDesc'] = 'DESC';
      $_BE['Petition_signaturesOrderBy']   = 'p2c.dateVerified';
      $_BE['Petition_signaturesCount']     = 50;
      $_BE['defaultPetitionsOrder']        = 'pet.dateCreated';
      $_BE['defaultPetitionsLogic']        = 'DESC';
      $_BE['defaultPetitionsDisplayCount'] = 20;

      $_BE['Petition_pageName']            = 'BE_petition.php';
      $_BE['Petition_section']             = 'Petitions';

      $_BE['showPrivateSignatures']        = false;

      // Menu setting for BE_Petition
      $_PSL['menuitem'][] = array(
         'name' => 'Petitions',
         'link' => $_PSL['rooturl'] . '/' . $_BE['Petition_pageName'],
         'perm' => 'nobody',
         'module' => 'BE_Petition',
         'group' => 'action',
         'order' => 54
      );
      // Menu setting for BE_PetitionAdmin
      $_PSL['menuadmin'][] = array(
         'name' => 'Petitions',
         'link' => $_PSL['adminurl'] . '/BE_petitionAdmin.php',
         'perm' => 'action',
         'module' => 'BE_Petition',
         'group' => 'action',
         'order' => 55
      );

   } // end BE_Petition


   // ******************
   // MODULE: BE_Followup
   // ******************

   if (isset($_PSL['module']['BE_Followup']) && $_PSL['module']['BE_Followup']) {

      $_BE['Table_followup'] = $_BE['Table_Prefix'] . '_followup';
      $_BE['Table_followup2contact'] = $_BE['Table_Prefix'] . '_followup2contact';

      // Not presently used
      // $_BE['Table_followup2group']        = $_BE['Table_Prefix'] . '_followup2group';

      $_BE['allowSendingFollowupEmail'] = false; // Set to true to send messages to eAction & ePetition

   } // END MODULE: BE_Followup


   // ******************
   // MODULE: BE_Catalog
   // ******************

   if (isset($_PSL['module']['BE_Catalog']) && $_PSL['module']['BE_Catalog']) {
      $_BE['catalog.uploadurl'] = $_BE['uploadurl'] . '/catalog';
      $_BE['catalog.uploaddir'] = $_BE['uploaddir'] . '/catalog';
      $_BE['catalog.email_to']  = $_PSL['site_owner'];
      $_BE['catalog.email_name']  = $_PSL['site_name'];

      $_BE['Table_catalog'] = $_BE['Table_Prefix'] . '_catalog';
      $_BE['Table_catalogText'] = $_BE['Table_Prefix'] . '_catalogText';
      $_BE['Table_keyword2catalog'] = $_BE['Table_Prefix'] . '_keyword2catalog';

      $_PSL['menuitem'][] = array(
         'name' => 'Catalog',
         'link' => $_PSL['rooturl'] . '/catalog.php',
         'perm' => 'nobody',
         'module' => 'BE_Catalog',
         'group' => 'misc',
         'order' => 60
      );
      $_PSL['menuadmin'][] = array(
         'name' => 'Catalog',
         'link' => $_PSL['adminurl'] . '/BE_catalogAdmin.php',
         'perm' => 'root', //##### TODO: Set up catalog Group
         'module' => 'BE_Catalog',
         'group' => 'misc',
         'order' => 60
      );

   } // END MODULE: BE_Catalog


   // ******************
   // MODULE: BE_Bibliography
   // ******************

   if (isset($_PSL['module']['BE_Bibliography']) && $_PSL['module']['BE_Bibliography']) {
      // MySQL Tables
      $_BE['Table_bib'] = $_BE['Table_Prefix'] . '_bib';
      $_BE['Table_bib2category'] = $_BE['Table_Prefix'] . '_bib2category';
      $_BE['Table_bib2country'] = $_BE['Table_Prefix'] . '_bib2country';
      $_BE['Table_bib2keywords'] = $_BE['Table_Prefix'] . '_bib2keywords';
      $_BE['Table_bib2profile2role'] = $_BE['Table_Prefix'] . '_bib2profile2role';
      $_BE['Table_bib2region'] = $_BE['Table_Prefix'] . '_bib2region';
      $_BE['Table_bib_category'] = $_BE['Table_Prefix'] . '_bib_category';
      $_BE['Table_bib_country'] = $_BE['Table_Prefix'] . '_bib_country';
      $_BE['Table_bib_language'] = $_BE['Table_Prefix'] . '_bib_language';
      $_BE['Table_bibMLA'] = $_BE['Table_Prefix'] . '_bibMLA';
      $_BE['Table_bib_region'] = $_BE['Table_Prefix'] . '_bib_region';
      $_BE['Table_bib_types'] = $_BE['Table_Prefix'] . '_bib_types';
      $_BE['Table_country2region'] = $_BE['Table_Prefix'] . '_country2region';
      $_BE['Table_profession'] = $_BE['Table_Prefix'] . '_profession';
      $_BE['Table_profile'] = $_BE['Table_Prefix'] . '_profile';
      $_BE['Table_profile2category'] = $_BE['Table_Prefix'] . '_profile2category';
      $_BE['Table_profile2country'] = $_BE['Table_Prefix'] . '_profile2country';
      $_BE['Table_profile2keywords'] = $_BE['Table_Prefix'] . '_profile2keywords';
      $_BE['Table_profile2nationality'] = $_BE['Table_Prefix'] . '_profile2nationality';
      $_BE['Table_profile2profession'] = $_BE['Table_Prefix'] . '_profile2profession';
      $_BE['Table_profile2region'] = $_BE['Table_Prefix'] . '_profile2region';
      $_BE['Table_profile2spokenLanguages'] = $_BE['Table_Prefix'] . '_profile2spokenLanguages';
      $_BE['Table_profile_keywords'] = $_BE['Table_Prefix'] . '_profile_keywords';
      $_BE['Table_profile_photo'] = $_BE['Table_Prefix'] . '_profile_photo';
      $_BE['Table_profile_role'] = $_BE['Table_Prefix'] . '_profile_role';
      $_BE['Table_profile2upload'] = $_BE['Table_Prefix'] . '_profile2upload';
      $_BE['Table_publisher'] = $_BE['Table_Prefix'] . '_publisher';

    // Menu settings for BE_Bibliography
      $_PSL['menuitem'][] = array(
         'name' => 'Bibliography',
         'link' => $_PSL['rooturl'] . '/bibliography.php',
         'perm' => 'nobody',
         'module' => 'BE_Bibliography',
         'group' => 'misc',
         'order' => 61
      );

      $_PSL['menuitem'][] = array(
         'name' => 'Profile',
         'link' => $_PSL['rooturl'] . '/profiles.php',
         'perm' => 'nobody',
         'module' => 'BE_Bibliography',
         'group' => 'misc',
         'order' => 62
      );

      // Admin settings for BE_Bibliography
      $_PSL['menuadmin'][] = array(
         'name' => 'Bibliography',
         'link' => $_PSL['adminurl'] . '/BE_bibAdmin.php',
         'perm' => 'bibliography',
         'module' => 'BE_Bibliography',
         'group' => 'misc',
         'order' => 63
      );
      $_PSL['menuadmin'][] = array(
         'name' => 'Profile',
         'link' => $_PSL['adminurl'] . '/BE_profileAdmin.php',
         'perm' => 'bibliography',
         'module' => 'BE_Bibliography',
         'group' => 'misc',
         'order' => 64
      );

   } // END MODULE: BE_Bibliography


   // ******************
   // MODULE: BE_Feedback
   // ******************

   if(isset($_PSL['module']['BE_Feedback']) && $_PSL['module']['BE_Feedback']) {
      $_BE['FeedbackSection'] = 'Feedback';
      $_BE['FeedbackTable'] = 'be_feedback';

      // Public menu settings for BE_Feedback
      $_PSL['menuitem'][] = array(
         'name' => 'Feedback',
         'link' => $_PSL['rooturl'] . '/BE_feedback.php',
         'perm' => 'nobody',
         'module' => 'BE_Feedback',
         'group' => 'misc',
         'order' => 65
      );

      // Admin menu settings for BE_Feedback
      $_PSL['menuadmin'][] = array(
         'name' => 'View Feedback',
         'link' => $_PSL['adminurl']. '/BE_feedbackAdmin.php',
         'perm' => 'subsite',
         'module' => 'BE_Feedback',
         'group' => 'misc',
         'order' => 66
      );

   } // END MODULE: BE_Feedback


   // ******************
   // MODULE: BE_TAF
   // ******************
   if(isset($_PSL['module']['BE_TAF']) && $_PSL['module']['BE_TAF']) {
      // Admin menu settings for Tell-A-Friend
      $_PSL['menuadmin'][] = array(
         'name' => 'Tell-A-Friend',
         'link' => $_PSL['adminurl']. '/BE_tafAdmin.php',
         'perm' => 'taf',
         'module' => 'BE_TAF',
         'group' => 'action',
         'order' => 55
      );
   } // END MODULE: BE_TAF


   // ******************
   // MODULE: Comment
   // ******************

   if(isset($_PSL['module']['Comment']) && $_PSL['module']['Comment']) {
      $_BE['DefaultAnonymousCommentRating'] = 0;
      $_BE['DefaultAuthenticatedCommentRating'] = 1;
   } // END MODULE: Comment


   // ******************
   // MODULE: phpOpenTracker
   // ******************
   if(isset($_PSL['module']['phpOpenTracker']) && $_PSL['module']['phpOpenTracker']) {

      // With multiple clients using phpOpenTracker, set the clientID:
      $_BE['phpOpenTracker.client_id'] = 1;

      $_PSL['menuadmin'][] = array(
         'name' => 'Statistics',
         'link' => $_PSL['adminurl']. '/BE_tracker.php',
         'perm' => 'root',
         'module' => 'phpOpenTracker',
         'group' => 'admin',
         'order' => 105
      );

   } // END MODULE phpOpenTracker


   // ******************
   // MODULE: BE_Downloads
   // ******************
   if (isset($_PSL['module']['BE_Downloads']) && $_PSL['module']['BE_Downloads']) {
      $_BE['Download.imageSize'] = 28;
   } // END MODULE: BE_Downloads


   // BACK-END MENU SETTINGS ====================================================

   // -- MAIN MENU

   $_PSL['menuitem'][] = array(
      'name' => 'Site Map',
      'link' => $_PSL['rooturl'] . '/sitemap.php',
      'perm' => 'nobody',
      'module' => 'BE_Section',
      'group' => 'content',
      'order' => 101
   );
   $_PSL['menuitem'][] = array(
      'name' => 'About',
      'link' => $_PSL['rooturl'] . '/about.php',
      'perm' => 'nobody',
      'module' => 'About',
      'group' => 'content',
      'order' => 102
   );
   $_PSL['menuitem'][] = array(
      'name' => 'Admin',
      'link' => $_PSL['rooturl'] . '/login.php',
      'perm' => 'user',
      'module' => '',
      'group' => 'admin',
      'order' => 103
   );


   // -- ADMIN/TASK MENU

   /* Additional Admin menu options

   // Mostly moved into parameters above

   // The following is hidden until there is some admin implemented
   $_PSL['menuadmin'][] = array(
      'name' => 'Version Admin',
      'link' => $_PSL['adminurl']. '/BE_versionAdmin.php',
      'perm' => 'section', // For now. May need separate historuy/version permission
      'module' => 'BE_History',
      'group' => 'content',
      'order' => 28
   );

   // Edit Spotlight Stories - useful for large sites
   // 19Aug03:mg
   $_PSL['menuadmin'][] = array(
      'name'  => 'Edit spotlight stories',
      'link'   => $_PSL['adminurl'].'/BE_spotlightAdmin.php',
      'perm'   => 'subsite',
      'module' => 'BE_Article',
      'group' => 'content',
      'order' => 29
   );

   // Edit Categories
   // Menu setting for BE_CategoryAdmin  - pac: activiate when the code has been written
   $_PSL['menuitem'][] = array(
      'name'   => 'Category',
      'link'   => $_PSL['adminurl'] . '/BE_categoryAdmin.php',
      'perm'   => 'category',
      'module' => 'BE_Subsite',
      'group' => 'content',
      'order' => 29
   );

   */


   // FINAL ACTIONS =================================================================


   if ($_BE['cgiWorkAround']) {
      $_BE['article_file'] = $_BE['article_file'] . '?';
      $_BE['link_file'] = $_BE['link_file'] . '?';
      $_BE['gallery_file'] = @$_BE['gallery_file'] . '?';
      $phpPathInfo = $_SERVER['QUERY_STRING'];
      $_SERVER['SCRIPT_NAME'] = $_SERVER['PHP_SELF'];
   }

   // Work out language from domain name - before the session has been intialised, so that
   // to cookie domain is correctly initialised by $sess->start
   // - ie this code has to be placed *before* the page_open()
   if (isset($_BE['languagedomains']) AND is_array($_BE['languagedomains'])) {
      // We're in a multi-domain CMS: Update the domain setting for session cookies
      be_domainInit();
   }

   # debug('be_config psl', $_PSL);
   # debug('be_config be', $_BE);
?>
