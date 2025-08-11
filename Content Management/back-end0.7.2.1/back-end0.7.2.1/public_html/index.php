<?php
   // $Id: index.php,v 1.56 2005/05/31 13:16:58 mgifford Exp $
   /**
   * Front-end/homepage
   *
   * @package     Back-End
   * @copyright   2002-5 - OpenConcept.ca
   * @version     $Id: index.php,v 1.56 2005/05/31 13:16:58 mgifford Exp $
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
   * @global string $BE_currentLanguage
   * @global array $ary
   * @global string $articleURLname
   * @global string $sectionURLname
   * @global string $toplevelSections
   * @global array $subSectionInfo
   *
   */

// Check initial memory usage
#   $startUsage[] = 'Memory Usage at: '. __FILE__ .'/'. __LINE__ .': ' .number_format(memory_get_usage()/1024) . ' Kb';

   $pageTitle = 'Home'; // The name to be displayed in the header
   $xsiteobject = 'Home Page'; // This Defines The META Tag Object Type

   require('./config.php');

#  $startUsage[] = 'Memory Usage after config at: '. __FILE__ .'/'. __LINE__ .': ' .number_format(memory_get_usage()/1024) . ' Kb';

   // Register return link for comments
   $return_link = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;
   $sess->register('return_link');

   $section =  (isset($section))  ? $section  : null;
   $article =  (isset($article))  ? $article  : null;
   $type =     (isset($type))     ? $type     : null;
   $page =     (isset($page))     ? $page     : null;
   $language = (isset($language)) ? $language : null;

   // store the section and article in an array to pass to the Block later on
   $ary = getRequestValue(@$section, @$article, @$type, @$page, @$language);

   if (!empty($_GET['login'])) {
      $auth->login_if(!$perm->have_perm('user'));
   }


   // Initialise variables that might be used later
   $articleTemplate = $sectionTemplate = $subsectionInfo = $articles = $link2articles = $articleURLname = $sectionLinks = $subSectionInfo = $spotlightArticles = $sectionSpecificContent = NULL;

   // Objects
   $articleObj  = & pslNew('BE_Article');
   $sectionObj  = & pslNew('BE_Section');
   $templateObj = & pslNew('slashTemplate');

   // $templateObj->debug = 1;

   // get the section object in current, then default, then any language
   $cacheExpiryDate = date('Y-m-d H:i:s', time() + $_PSL['cacheExpiryTime']);
   $fileCache = new fileCache('sectionRec', $ary['section'] . '-' . $BE_subsite['subsite_id'] . '-' . $BE_currentLanguage);
   $sectionRecEncoded = $fileCache->retrieve();
   if(!empty($sectionRecEncoded)) {
      $sectionRec = unserialize(base64_decode($sectionRecEncoded));
// echo "A) $ary[section] "; print_r($sectionRec);
   } else {
      $sectionRec = $sectionObj->extractSection($ary['section'], $BE_currentLanguage);

// echo "B) $ary[section] "; print_r($sectionRec);
      if (empty($sectionRec) || !is_array($sectionRec)) {
         $languageChoice = (!empty($_GET['language'])) ? clean($_GET['language']) : null;
         $translatedURLname = translateURLname($ary['section'], 'section', $languageChoice);
         if(!empty($translatedURLname)) {
            $ary['section'] = $translatedURLname;
            $sectionRec = $sectionObj->extractSection($ary['section'], $BE_currentLanguage);
         }
      }
// echo "C) $ary[section] "; print_r($sectionRec);
      if($_BE['Language_Fallback']) {
         // This should be handled within extractSection I think - PAC
         $sectionRec = (!is_array($sectionRec) && ($BE_currentLanguage != $_BE['Default_language'])) ? $sectionObj->extractSection($ary['section'], $_BE['Default_language']) : $sectionRec;
         $sectionRec = (!is_array($sectionRec)) ? $sectionObj->extractSection($ary['section']) : $sectionRec;
      }

      $fileCache->store(base64_encode(serialize($sectionRec)));
   }

   // This variable should be accessible to the slashHead.tpl file.
   // There may be a better place to put this block so that it is more widely
   // accessible or turn it off it is not used. -mg
   // PAC: How about adding a new column 'head' alongside left, right and center?
   //      - there's some block-related code in getHeader aleaady
   $toplevelSections = $sectionObj->getToplevelSections('article');

# $startUsage[] = 'Memory Usage at: '. __FILE__ .'/'. __LINE__ .': ' .number_format(memory_get_usage()/1024) . ' Kb';

   $sectionID            = $sectionRec['sectionID'];
   $sectionURLname       = $sectionRec['URLname'];
   $sectionName          = $sectionRec['title'];
   $showArticles         = $sectionRec['showArticles'];
   $showSections         = $sectionRec['showSections'];
   $showLinkSubmit       = $_BE['showLinkSubmit'] && $sectionRec['showLinkSubmit'];
   $sectionTemplate      = $sectionRec['template'];
   $dateModified         = $sectionRec['dateModified'];
   $dateForSort          = $sectionRec['dateForSort'];
   $orderbySections      = $sectionRec['orderbySections'];
   $orderbySectionsLogic = $sectionRec['orderbySectionsLogic'];
   $orderbyArticles      = $sectionRec['orderbyArticles'];
   $orderbyArticlesLogic = $sectionRec['orderbyArticlesLogic'];
   $orderbyLinks         = $sectionRec['orderbyLinks'];
   $orderbyLinksLogic    = $sectionRec['orderbyLinksLogic'];

   // echo "<pre>"; print_r($sectionRec); echo "</pre>";

   $sectionName = (!empty($sectionName)) ? $sectionName : $ary['section'] ;

   // Redirect section if specified
   if (!empty($sectionRec['redirect'])) {
      if (function_exists('jpcache_gc')) {
         echo '<html><meta http-equiv="refresh" content="2;url=' . $sectionRec['redirect'] . '"><body><script type="text/javascript">window.location="' . $sectionRec['redirect'] . '";</script><a href="' . $sectionRec['redirect'] . '">' . pslgetText('Please click here to be forwarded to the correct location') . '</body></html>';
         page_close();
      } else {
         // Save session variables and redirect to new URL
         page_close();
         header("Location: {$sectionRec['redirect']}");
      }
      exit();
   }

   // if there is an article, get it
   if (!empty($ary['article'])) {
      # debug('showing article', $ary['article']);

      // get the article object
      $articleRec = $articleObj->extractArticle($ary['article'], $BE_currentLanguage);

      if(empty($articleRec)) {
         $ary['article'] = translateURLname($ary['article'],'article');
         $articleRec = $articleObj->extractArticle($ary['article'], $BE_currentLanguage);
      }

      if($_BE['Language_Fallback']) {
         // Search in Alternate Language if it isn't available in current language
         $articleRec = ($articleRec) ? $articleRec : $articleObj->extractArticle($ary['article'], '');
      }

      // If Article Does Not Exist - Save session variables and redirect to new URL with error message
      if (empty($articleRec)) {
         $error = urlencode(pslgettext('be_article_fetch_err') . ' ' . $ary['article']);
         page_close();
         header("Location: {$_PSL['rooturl']}/search.php?query={$ary['article']}&error=$error");
         exit;
      }

      //  Check to see if we have a redirect to an attached download.
      $mainpage = $articleObj->getMainPage($articleRec['articleTextID']);
      if($mainpage) {

         if (function_exists('jpcache_gc')) {
            echo '<html><meta http-equiv="refresh" content="2;url=' . $mainpage . '"><body><script type="text/javascript">window.location="' . $mainpage . '";</script><a href="' . $mainpage . '">' . pslgetText('Please click here to be forwarded to the correct location') . '</body></html>';
            page_close();
         } else {
            // Save session variables and redirect to new URL
            page_close();
            header("Location: {$mainpage}");
         }
         exit();
      }


      $articleID       = $articleRec['articleID'];
      $articleURLname  = $articleRec['URLname'];
      $articleName     = $articleRec['title'];
      $articleTemplate = $articleRec['template'];
      $chosenTemplate  = getUserTemplates($articleTemplate, $sectionTemplate);
      $metaKeywords    = $articleRec['meta_keywords'];
      $metaDesc        = $articleRec['meta_description'];
      $dateModified    = $articleRec['dateModified'];
      $articleName     = (isset($articleName) && !empty($articleName)) ? $articleName : $ary['article'];
      $pageTitle       = $articleName;

      $headerLastModifiedDate = gmdate("D, d M Y H:i:s \G\M\T", $dateModified);
      header("Last-Modified: $headerLastModifiedDate");

      if (isset($articleRec['dateRemoved']) && !empty($articleRec['dateRemoved'])) {
         $_PSL['metatags']['expires'] = gmdate("D, d M Y H:i:s \G\M\T", $articleRec['dateRemoved']);
      }

      // Article-specific breadcrumb
      $breadcrumb = ($_BE['displayBreadcrumbs']) ? $sectionObj->breadcrumb($sectionID, $articleName) : NULL;

      // setting up the possible comment variables...
      $ary['rating'] = (isset($_GET['rating'])) ? clean($_GET['rating']) : null;
      $cmtary['mode']      = (isset($ary['mode']) && !empty($ary['mode'])) ? $ary['mode'] : NULL;
      $cmtary['order']     = (isset($ary['order']) && !empty($ary['order'])) ? $ary['order'] : NULL;
      $cmtary['story_id']  = (isset($langDependentComments) && !empty($langDependentComments)) ? $articleRec['commentIDtext'] : $articleRec['commentID'];
      $cmtary['parent_id'] = (isset($ary['parent_id']) && !empty($ary['parent_id'])) ? $ary['parent_id'] : 0;
      $defaultCommentThreshold = (isset($auth->auth['defaultCommentThreshold'])) ? $auth->auth['defaultCommentThreshold'] : $_BE['defaultDisplayLimit'];
      $cmtary['rating']    = (isset($ary['rating'])) ? $ary['rating'] : $defaultCommentThreshold;

      // render the main story section
      # debug('getting article', $ary['article']);
      $storyInfo = $articleObj->getArticle($ary['article'], '', '', $cmtary, $sectionID);

      // If it is a restricted article, but the user is not a member
      if (!empty($articleRec['restrict2members']) && !$perm->have_perm('Member')){
         $metaKeywords = strip_tags(pslgetText('This article is for members only'));
         $articleName = strip_tags(pslgetText('This article is for members only'));
         $storyInfo = pslgetText('This article is for members only');
      }

      if (!($perm->have_perm('story') && !$perm->have_perm('root'))) {
         $count = $articleObj->countArticle($articleRec['articleID'], $articleRec['hitCounter']);
      }

   } else {

      // if no section and no article
      if (empty($sectionRec)) {
         $sectionError = urlencode(pslgettext('be_section_fetch_err') . ' ' . $ary['section']);
         page_close();
         header("Location: {$_PSL['rooturl']}/search.php?query={$ary['section']}&error=$sectionError");
         exit();
      }

      $pageTitle = empty($sectionName) ? $pageTitle : $sectionName;
      $breadcrumb = ($_BE['displayBreadcrumbs']) ? $sectionObj->breadcrumb($sectionID) : NULL;
      $metaKeywords = $sectionRec['meta_keywords'];
      $metaDesc = $sectionRec['meta_description'];
      $chosenTemplate = getUserTemplates('', $sectionTemplate);

      // render the main body of the section
      $storyInfo = $sectionObj->getSection($sectionID, $sectionRec);

      // If it is a restricted section, but the user is not a member
      if (!empty($sectionRec['restrict2members']) && !$perm->have_perm('Member')){
         $metaKeywords = strip_tags(pslgetText('This section is for members only'));
         $articleName = strip_tags(pslgetText('This section is for members only'));
         $storyInfo = pslgetText('This section is for members only');
      }

      // render the list of sub sections for this section
      if ($showSections == '1') {
         $param['section'] = $sectionID;
         $param['first'] = (isset($_GET['sec_i']) && !empty($_GET['sec_i'])) ? clean($_GET['sec_i']) : 0;
         $param['count'] = (isset($_GET['sec_n']) && !empty($_GET['sec_n'])) ? clean($_GET['sec_n']) : -1;
         $param['orderby'] = (isset($_GET['orderby']) && !empty($_GET['orderby'])) ? clean($_GET['orderby']) : $orderbySections;
         $param['logic'] = (isset($_GET['logic']) && !empty($_GET['logic'])) ? clean($_GET['logic']) : $orderbySectionsLogic;
         $param['type'] = 'article';
         $param['parentURLname'] = $sectionURLname;
         $param['noPaginationRequired'] = true;
         $subSectionInfo = $sectionObj->getSubSections($param);
      }

      // render the list of articles for this section
      if ($showArticles == '1') {
         $index = (isset($_GET['art_i']) && !empty($_GET['art_i'])) ? clean($_GET['art_i']) : 0;
         $count = (isset($_GET['art_n']) && !empty($_GET['art_n'])) ? clean($_GET['art_n']) : -1;
         $orderbyArticles = (isset($_GET['orderby']) && !empty($_GET['orderby'])) ? clean($_GET['orderby']) : $orderbyArticles;
         $orderbyArticlesLogic = (isset($_GET['logic']) && !empty($_GET['logic'])) ? clean($_GET['logic']) : $orderbyArticlesLogic;
         $articles = $articleObj->getArticles($sectionID, $sectionURLname, $index, $count, $orderbyArticles, '', $orderbyArticlesLogic, '', '', '');
      }

      if (!($perm->have_perm('story'))) {
         $count = $sectionObj->countArticle($sectionID, $sectionRec['hitCounter']);
      }

      if (!empty($articleRec['dateRemoved'])) {
         $_PSL['metatags']['expires'] = gmdate("D, d M Y H:i:s \G\M\T", $sectionRec['dateRemoved']);
      }

   }

   // content footer should not be displayed if there isn't an active article or section
   // Reference to !isSearchEngine() has been removed as it isn't important
   if (!empty($ary['section']) || !empty($ary['article'])) {

      $templateObj->set_file('contentFooter', 'BE_contentFooter.tpl');

      if ($showLinkSubmit) {
         $submitLink = "| <a href=\"{$_PSL['rooturl']}/{$_BE['link_submit_file']}?section={$ary['section']}\">" . pslgetText('Submit a Link') . '</a>';
      } else {
         $submitLink = NULL;
      }

      $templateObj->set_var(array(
         'ROOTDIR'      => $_PSL['rooturl'],
         'ARTICLE_FILE' => $_BE['article_file'],
         'SECTION'      => $ary['section'],
         'ARTICLE'      => $ary['article'],
         'SUBMIT_LINK'  => $submitLink
      ));

      $contentFooter = $templateObj->parse('OUT', 'contentFooter');

   } else {
      $contentFooter = NULL;
   }

#   $startUsage[] = 'Memory Usage after content build at: '. __FILE__ .'/'. __LINE__ .': ' .number_format(memory_get_usage()/1024) . ' Kb';

   $todaysDate = psl_dateLong(time());

   $templateExtension = (isset($articleTemplate) && !empty($articleTemplate)) ? $articleTemplate : $sectionTemplate;

   // place the output for the primary ('story') content section into an array and pass it to index*tpl
   $storyArray = array(
      'STORY_COLUMN'        => $storyInfo, // smartText($storyInfo),
      'SUB_SECTION_INFO'    => $subSectionInfo,
      'ARTICLES'            => $articles,
      'SPOTLIGHT'           => $spotlightArticles,
      'SECTION_CONTENT'     => $sectionSpecificContent,
      'CONTENT_FOOTER'      => $contentFooter,
      'IMAGEDIR'            => $_PSL['imageurl'],
      'ROOTDIR'             => $_PSL['rooturl'],
      'TODAYS_DATE'         => $todaysDate,
      'LINK2ARTICLES'       => $link2articles,
      'TEMPLATE_EXT'        => $templateExtension,
      'BREADCRUMB'          => $breadcrumb,
      # 'SECTION_LINKS'     => $sectionLinks,
      'SECTION_LINKS_INDEX' => $sectionLinks
   );

   // render the standard header & metatags
   $_PSL['metatags']['object'] = $xsiteobject;
   $_PSL['metatags']['keywords'] = (isset($metaKeywords) && !empty($metaKeywords)) ? $metaKeywords : $_PSL['metatags']['keywords'];
   $_PSL['metatags']['description'] = (isset($metaDesc) && !empty($metaDesc)) ? $metaDesc : $_PSL['metatags']['description'];
   $_PSL['metatags']['date'] = gmdate("D, d M Y H:i:s \G\M\T", $dateModified);

   /* I have no convenient way to get the current section and current article
    to my Blocks so they know how to generate themselves. The use of
    _BE['currentSection'] is a mere convenience given the time I've got to
    solve this problem.

    We need a better way to let blocks know for what section they're generating
    themselves. We also need a simple, well-defined interface to a general-
    purpose caching mechanism. Blocks could be deciding themselves when they
    really need updating, based on certain session state information that's
    globallay accessible. PSB 2002-08-28
    */
   $_BE['currentSection'] = $ary['section'];
   $_BE['currentSectionURLname'] = $sectionURLname;
   $_BE['currentArticleURLname'] = $articleURLname;
   $_BE['currentArticle'] = (isset($articleID) && !empty($articleID)) ? $articleID : NULL;

// echo "<pre>"; var_dump($GLOBALS); echo "</pre>";
   // generate the page
#$startUsage[] = 'Memory Usage before GP at: '. __FILE__ .'/'. __LINE__ .': ' .number_format(memory_get_usage()/1024) . ' Kb';

   generatePage($ary, $pageTitle, $breadcrumb, $storyArray, $storyInfo, $dateModified);

#$startUsage[] = 'Memory Usage after GP at: '. __FILE__ .'/'. __LINE__ .': ' .number_format(memory_get_usage()/1024) . ' Kb';
#   foreach ($startUsage as $v) {
#       echo $v . "<br>\n";
#   }


   # debug('generated page', $sectionID);

   // close the page
   page_close();
?>
