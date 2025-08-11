<?php
/**
* Page management/generation functions
*
* Should be used by all Back-End pages (ie files in the public_html tree)
*
* See also BE_functions.inc for the generic functions
*
* @package     Back-End
* @author      Mike Gifford various
* @copyright   Copyright (C) 2005 OpenConcept Consulting
* @version     $Id: BE_Page.inc,v 1.51 2005/06/23 12:13:53 krabu Exp $
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

/*
* Global variables used in this file documented here in the hopes that someone can make sense of them all
* (and hopefully get rid of some)
*/

/**
* @global Array $chosenTemplate Set in getUserTemplates, used by generatePage
*/
global $chosenTemplate;

   /**
    * Common method to initialize a Back-End page before starting any rendering
    *
    * @param string   $xsiteobject
    *
    * @access public
    *
    * @return nothing
    */
   function initializePage($xsiteobject) {
      global $_PSL, $auth;
      $_PSL['metatags']['object'] = $xsiteobject;

      if (!empty($_GET['login'])) {
         $auth->login_if(clean($_GET['login']));
      }
   }


   /**
    * Work out current domain name if we're running a multi-domain site
    *
    * This function is called before the session is started - its main function is to have an appropriate value
    * for the session cookie domain
    *
    * @author Peter Cruickshank
    * @access public
    */
   function be_domainInit() {
      global $_PSL, $_BE;

      $host = explode('.', $_SERVER['HTTP_HOST']);
      if (count($host) > 1) {
         #Little flexibility: $domain = $host[count($host)-2] . '.' . $host[count($host)-1];

    //Find one of the domain languages as a substring of $_SERVER['HTTP_HOST'].  Much more flexible
    $domain = $domain = $host[count($host)-2] . '.' . $host[count($host)-1];
    $nbLangs = count($_BE['languagedomains']);
    $foundIt = false;
    $haystack = strtolower($_SERVER['HTTP_HOST']);
    reset($_BE['languagedomains']);

    while(!$foundIt) {
       $oneLangDomain = each($_BE['languagedomains']);

       //  If we can't find anything that matches any of our domains, take
       // the last one on the list.
       if($oneLangDomain == false) {
          $foundIt= true;
       } else {
          $compareDomains = strpos($haystack, strtolower($oneLangDomain['key']));
          if(!(is_bool($compareDomains))) {
             $foundIt = TRUE;
          }
          $domain = $oneLangDomain['key'];
       }
    }
    reset($_BE['languagedomains']);

         #   echo "be_domainLanguage domain=$domain:";
         $_PSL['rootdomain'] = $domain;
         //Update other config stuff too
         $_BE['subsite.roothost'] = $_PSL['rootsubdomain'].$_PSL['rootdomain']; // eg www.cupe.ca even if we're currently in 4601.cupe.ca
         $_BE['subsite.urlbase'] = $_PSL['rootdomain'].$_PSL['rooturl']; // eg cupe.ca/public_html/ - ie url without subdomain info
      }
      // Could translate the PSL settings here too?
   }


   /**
    * Common method to generate a Back-End page
    *
    * Interfaces PSL functions
    *
    * @param array    $ary PSL page information (section, topic etc)
    * @param string   $pageTitle
    * @param htmlstring $breadcrumb
    * @param array $storyArray The main page body variables
    * @param string   $storyInfo Used for PDF, print and email versions only
    *
    * @access public
    *
    * @return nothing
    */
   function generatePage(&$ary, $pageTitle, $breadcrumb, $story, $storyInfo = '', $lastModifiedDate = '') {

      generateHTMLPage($ary, $pageTitle, $breadcrumb, $story, $lastModifiedDate);

   } // generatePage


   /**
    *  Generates the HTML version of the page.  This was ripped out of generatePage so that it could
    * easily be called both when generating HTML and e-mail versions of the page, but attached to
    * the e-mail version.
    */
   function generateHTMLPage($ary, $pageTitle, $breadcrumb, $story, $lastModifiedDate = '') {
      global $chosenTemplate, $templateExtension, $_PSL, $_BE;

      $section = (isset($ary['section']) && !empty($ary['section'])) ? $ary['section'] : null;

      $header = & getHeader($pageTitle, $_PSL['metatags'], $section, $chosenTemplate['header']);

      // CHECK TIME YYY
      // Get blocks
      $blockObj = & pslNew('Block_i');
      $leftblocks         = $blockObj->getBlocks($ary, 'left');
      $centerbottomblocks = $blockObj->getBlocks($ary, 'centerbottom');
      $centerblocks       = $blockObj->getBlocks($ary, 'center');
      $centertopblocks    = $blockObj->getBlocks($ary, 'centertop');
      $rightblocks        = $blockObj->getBlocks($ary, 'right');
      unset($blockObj);

      // Template selection over to function getUserTemplates
      if (empty($leftblocks)) {
         if (empty($rightblocks)) {
            $tplfile = (!empty($chosenTemplate['tpl1col'])) ? $chosenTemplate['tpl1col'] : 'index2colright';
         } else {
            $tplfile = (!empty($chosenTemplate['tpl2col-right'])) ? $chosenTemplate['tpl2col-right'] : 'index2colright';
         }
      } elseif (empty($rightblocks)) {
         $tplfile = (!empty($chosenTemplate['tpl2col-left'])) ? $chosenTemplate['tpl2col-left'] : 'index2colleft';
      } else {
         $tplfile = (!empty($chosenTemplate['tpl3col'])) ? $chosenTemplate['tpl3col'] : 'index3col';
      }

      // Append .tpl extension
      $tplfile = $tplfile . '.tpl';

      # debug('generateBody template directory and tplfile', $_PSL['templatedir'] . '/' . $tplfile);
      # debug('generateBody story', $story);

      $template = & pslNew('slashTemplate'); //, $_PSL['templatedir']);
      // $template->debug = 1;

      $template->set_file('index', $tplfile );

      // TODO register_globals?
      if (!empty($_SERVER['QUERY_STRING'])) {
         $QUERY_STRING = '?' . $_SERVER['QUERY_STRING'];
      } else {
         $QUERY_STRING = '';
      }

      // Allow for array or string input - for backward compatibility
      if (is_array($story)) {
         $template->set_var($story);
      } else {
         $template->set_var('STORY_COLUMN', $story);
      }

      $template->set_var(array(
         'QUERYSTRING'                => $QUERY_STRING,
         'ROOTDIR'                    => $_PSL['rooturl'],
         'IMAGEDIR'                   => $_PSL['imageurl'],
         'BREADCRUMB'                 => $breadcrumb,
         'LEFT_BLOCK_COLUMN'          => $leftblocks,
         'CENTER_TOP_BLOCK_COLUMN'    => $centertopblocks,
         'CENTER_BLOCK_COLUMN'        => $centerblocks,
         'CENTER_BOTTOM_BLOCK_COLUMN' => $centerbottomblocks,
         'RIGHT_BLOCK_COLUMN'         => $rightblocks,
         # Setup in block where it belongs
         # 'BANNER'                     => $banner,
         'TITLE'                      => $pageTitle,
         'TEMPLATE_EXT'               => $templateExtension,
         'TODAYS_DATE'                => psl_dateLong(time())
      ));

      echo $header;

      $template->pparse('OUT', 'index');

      // render the standard footer
      slashfoot($chosenTemplate['footer'], $lastModifiedDate);

      /* debug.template = true in public_html/config.ini.php for template loging to
       * work.  On top of that, a modified version of phplib/php/template.inc
       * specific to BE is required for template loging to work.  The template loging
       * feature includes an external log with templates used while debug.template = true
       * and adds, for each page viewed, templates viewed for that page at the footer
         -- Julien Lamarche.
       */

      if(@$_PSL['debug.templates']) {
         echo "<!--\n";
         $templatesUsed = addUsedTemplate(NULL);
         echo $templatesUsed;
         echo "-->\n";
      }


      //DEBUG TOOLS
/*
      echo "<h4>General Info</h4>";
      // Find execution time
      global $scriptTimer;  echo '<hr /><p>ScriptTimer: ' . $scriptTimer->get_current('main') . "</p>\n";

      // Get included files
      // $included_files = get_included_files(); echo "\n<ul>"; foreach($included_files as $filename) { echo "\r<li>$filename</li>\n"; } echo "</ul>\n";
      $included_files = get_included_files();
      echo "<h4>Included Files</h4>\n<ol><li>", implode("<li>",$included_files), "</ol>";



      echo "\n<pre>"; var_dump(debug_backtrace()); echo "\n</pre>";

      echo "<h4>Included Files</h4>\n<ol>";
      foreach (debug_backtrace() as $file) {
         echo"<li>$file[file] <em>$file[function]</em> # $file[line]";  // $file[args]
      }
      echo "</ol>";
//

      echo "\n<pre>"; var_dump(get_defined_vars()); echo "\n</pre>";

      // Check Memory usage
      if (function_exists(memory_get_usage)) echo 'Memory Usage: ' . number_format(round( memory_get_usage() / ( int )1024, 4 )) . " Kb<br/>\n";

      echo "<pre>"; var_dump($GLOBALS); echo "</pre>";
      echo "<h4>Globals</h4>\n<ol><li>", implode("<li>",array_keys($GLOBALS)), "</ol>";

      echo '<div>Memory Usage at: '. __FILE__ .'/'. __LINE__ .': ' .number_format(memory_get_usage()/1024) . " Kb</div>\n";

      */

   } // end generateHTMLPage


   /**
    * Find if the templates exist for the article/section template variables defined for the
    * given article/section.  Function searches for parent/grandparent section definitions if a
    * section is not visible.  Arrays are cached to speed up performance.
    *
    * @global array $chosenTemplate
    * @param string $articleTemplate
    * @param string $sectionTemplate
    * @param string $secondsFromNow
    * @return array Elements set:[header], [body], [footer]
    *
    */
   function getUserTemplates($articleTemplate = '', $sectionTemplate = '', $secondsFromNow = 14000) {
      global $_PSL, $_BE, $sectionID, $chosenTemplate;

      // Define $userDrivenTemplateAry
      $userDrivenTemplateAry = (isset($_BE['userDrivenTemplateAry']) && is_array($_BE['userDrivenTemplateAry'])) ? $_BE['userDrivenTemplateAry'] : null;
      if (isset($chosenTemplate['header']) && !empty($chosenTemplate['header'])) {
         $userDrivenTemplateAry['header'] = $chosenTemplate['header'];
      }

      // Verify that the list of user defined files is an array
      if (!is_array($userDrivenTemplateAry)) {
         return $chosenTemplate;
      }

      // Define template file
      $templateFile = (!empty($articleTemplate)) ? strtolower($articleTemplate) : strtolower($sectionTemplate);

      // Find user defined templates for section/article if defined
      if (!empty($templateFile)) {
         $selectedTemplateAry = findUserTemplates($templateFile, $secondsFromNow, $userDrivenTemplateAry);

      // return $userDrivenTemplateAry if neither $templateFile or $sectionID is defined
      } else {
         if (empty($sectionID)) {
            return $userDrivenTemplateAry;
         }
      }

      // Initialize fileCache class and check for stored value
      $cacheExpiryDate = date('Y-m-d H:i:s', time()+$secondsFromNow);
#      $cacheObject = pslNew('fileCache', 'userTemplatesSections', "$templateFile-$sectionID", $cacheExpiryDate);
      $cacheObject = new fileCache('userTemplatesSections', "$templateFile-$sectionID", $cacheExpiryDate);
      $cachedTemplates = $cacheObject->retrieve();
      if (!empty($cachedTemplates)) {
         return unserialize($cachedTemplates);
      }

      // Check for valid sectionID and return chosen templates if not present
      if (empty($sectionID)) {
         return $selectedTemplateAry;
      }

      // How far back do you want to go? Default provides parent & grandparent
      $ancestorDepth = $_BE['AncestorTemplateDepth'];
      if ($ancestorDepth > 0) {
          $ancestorTemplateAry = findAncestorsUserTemplates($sectionID, $secondsFromNow, $userDrivenTemplateAry, $ancestorDepth);
      } else {
         return $selectedTemplateAry;
      }

// echo "<br />selectedTemplateAry "; print_r($selectedTemplateAry);

      foreach($userDrivenTemplateAry AS $shortName => $templateName) {
         // the section's template exists
         if (!empty($selectedTemplateAry[$shortName]) && $selectedTemplateAry[$shortName] != $templateName) {
            $chosenTemplateAry[$shortName] = $selectedTemplateAry[$shortName];
// echo "<br /><br />shortName $shortName " .$selectedTemplateAry[$shortName] .' != ' . $templateName . '<br />';

         } else {
            for ($i=$ancestorDepth ; $i > 0 ; $i--) {

               $ii = $i-1;
// echo "<br /><br />ancestorTemplateAry ($i $ii ) [{$ancestorTemplateAry[$i][$shortName]}] "; print_r($ancestorTemplateAry[$i]);

               // An ancestor's template exists, but the parent section does not replace the child
               if (!empty($ancestorTemplateAry[$ii][$shortName]) && $ancestorTemplateAry[$ii][$shortName] != $templateName) {
                  // && $chosenTemplateAry[$shortName] != $templateName) {
                  $chosenTemplateAry[$shortName] = $ancestorTemplateAry[$ii][$shortName];

// echo "<br />   $i $ii a) " . $chosenTemplateAry[$shortName] .' = ' . $ancestorTemplateAry[$ii][$shortName];

               // if the chosentemplate hasn't been defined
               } elseif (!isset($chosenTemplateAry[$shortName])) {
                  $chosenTemplateAry[$shortName] = $templateName;

// echo "<br />   $i $ii b)" . $chosenTemplateAry[$shortName] .' = ' . $ancestorTemplateAry[$ii][$shortName];
               } else {
                  // $chosenTemplateAry[$shortName] = $ancestorTemplateAry[$ii][$shortName];
// echo "<br />   $i $ii c)" . $chosenTemplateAry[$shortName] .' = ' . $ancestorTemplateAry[$ii][$shortName];
               }
            }
         }
      }

      // Generate new template list and store values
      $cacheObject->store(serialize($chosenTemplateAry));
// echo "<br />chosenTemplateAry "; print_r($chosenTemplateAry);

      return $chosenTemplateAry;

   }


   function findUserTemplates($templateFile = '', $secondsFromNow=14000, $userDrivenTemplateAry) {

      $cacheExpiryDate = date('Y-m-d H:i:s', time()+$secondsFromNow);

      // Initialize fileCache class and check for stored value
#      $cacheObject = pslNew('fileCache', 'userTemplates', "$templateFile", $cacheExpiryDate);
      $cacheObject = new fileCache('userTemplates', "$templateFile", $cacheExpiryDate);
      $cachedTemplates = $cacheObject->retrieve();
      if (!empty($cachedTemplates)) {
// echo "<br />CACHED $templateFile <pre>"; print_r(unserialize($cachedTemplates)); echo "</pre>";
         return unserialize($cachedTemplates);
      }

      foreach($userDrivenTemplateAry AS $shortName => $baseTemplateName) {
         $selectedTemplateAry[$shortName] = check4ExistingTemplate($baseTemplateName, $templateFile);
      }

      // Generate new template list and store values
      $cacheObject->store(serialize($selectedTemplateAry));

// echo "<br />UNCACHED $templateFile <pre>"; print_r($selectedTemplateAry); echo "</pre>";

      return $selectedTemplateAry;

   }


   function findAncestorsUserTemplates($sectionID, $secondsFromNow='', $userDrivenTemplateAry, $ancestorDepth = 2) {

      $templateAry = array();

      $sectionObj = pslNew('BE_Section');
      $skeleton = $sectionObj->extractSkeleton();

      $currentSection = $sectionID;

      //  Check to so many levels for ancestors
      for ($i=0 ; $i < $ancestorDepth ; $i++) {
         $currentSection = @$skeleton[$currentSection]['parentSectionID'];
         if (isset($currentSection) && $currentSection != 0) {
           if (!empty($skeleton[$currentSection]['template'])) {
               $templateAry = findUserTemplates($skeleton[$currentSection]['template'], $secondsFromNow, $userDrivenTemplateAry);
           }
         }
         $ancestorTemplateAry[$i] = $templateAry;
      }

      return $ancestorTemplateAry;

   } // end getExistingTemplate


   /**
    * The script that identifies if article/section templates actually exist
    *
    * @param array $skeleton
    * @param
    * @return string
    *
    */
   function check4ExistingTemplate($baseTemplateName, $templateFile = '') {
      global $_PSL, $BE_subsite;

      if (!empty($templateFile)) {
         $append = $templateFile;
      } else {
         $append = NULL;
      }

      $templateName = $baseTemplateName . '-' . basename($append);

      if(be_inSubsite()) {
         if(isset($BE_subsite['URLname'])) {
            $subsiteTemplate = $_PSL['templatedir'] . '/subsites/' . $BE_subsite['URLname'] . "/$templateName.tpl";
            if(file_exists($subsiteTemplate)) {
               return $templateName;
            }
         }
      }

      $templateFile = $_PSL['templatedir'] . '/' . $templateName . '.tpl';
      if (file_exists($templateFile)) {
         return $templateName;
      }

      // return the default
      return $baseTemplateName;

   } // end _getExistingTemplate


   /**
    * Pull section and article info from url or parameters
    *
    * Looks for /s after url first, then looks at GET vars
    * If all else fails, guesses the section-name from the Locals URLname, if set
    *
    * @param mixed $section Id or URL of section
    * @param mixed $article Id or URL of article
    * @param string $type [article]|link|gallery
    * @return array
    *           [article] name or ID of article (or link or gallery)
    *           [section] name or ID of section
    * @access public
    *
    * TODO: Change article to more generic object
    */
   function getRequestValue($section = '', $article = '', $type = 'article') {
      global $_PSL, $_BE;

      // default - pulls section/article from human readable url
      if (empty($section) && empty($article)) {

         // if these are not defined in a previous page look in the PATH_INFO
         $phpSelf = (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'];
         $scriptName = $_SERVER['SCRIPT_NAME'];
         if ($_BE['cgiWorkAround']) {
            $abbreviatedScriptName = ereg_replace("(\.php?)(.*)", "\\1", $scriptName);
         } else {
            $abbreviatedScriptName = ereg_replace("(\.php)(.*)", "\\1", $scriptName);
         }

         $paramPathPart = str_replace($abbreviatedScriptName, '', $phpSelf);

         if ( !$_BE['cgiWorkAround'] && (strpos($paramPathPart, '?') !== false) ) {
            $paramPathPart = substr($paramPathPart, 0, strpos($paramPathPart, '?'));
         }
#        echo "<!-- abbreviatedScriptName=$abbreviatedScriptName paramPathPart=$paramPathPart  phpSelf=$phpSelf PHP_SELF=$_SERVER[PHP_SELF] -->";# print_r($_SERVER);
         // Check if it contains any /'s and parse it if there are
         if ($paramPathPart != $phpSelf && (strpos($paramPathPart,'/') !== FALSE)) {
            @list($nothing, $ary['section'], $ary['article']) = split('/', $paramPathPart);
            // is there a GET (question mark) in the section name?
            $ary['section'] = clean($ary['section']);
            $ary['article'] = clean($ary['article']);

            if (!empty($ary['section'])) {
               return $ary;
            }

         }
      }

      // Find alternate section defintions through GET & subsite info
      // or default to home section
      if (empty($section)) {
         // check the URL for variables passed through get
         if (isset($_GET['section']) && !empty($_GET['section'])) {
            $section = clean($_GET['section']);
         } elseif(isset($_PSL['module']['BE_Subsite']) && $_PSL['module']['BE_Subsite']) {
            global $BE_subsite;
            if (!empty($BE_subsite['URLname'])) {
               $section = $BE_subsite['URLname'];
            }
         } else {
            $section = $_BE['default_section'];
         }
      }

      // Find alternate article & other object defintions through GET
      if (empty($article) AND isset($_GET['article'])) {
         $article = clean($_GET['article']);
      } else {
         $type = (isset($_GET['type']) && !empty($_GET['type'])) ? clean($_GET['type']) : $type;
         if (isset($_GET[$type]) && !empty($_GET[$type])) {
            $ary[$type] = clean($_GET[$type]);
         } else {
            $ary[$type] = $article;
         }
      }

      # debug('getRequestValue section', $section);
      # debug('getRequestValue article', $article);

      $ary['section'] = (isset($section)) ? $section : null;
      $ary['article'] = (isset($article)) ? $article : null;

      return $ary;

   } // end getRequestValue



   /**
    * This function keeps track of template files used and always returns the cumullative result
    * The format is a string, one file per line.  Again, we want a unique entry.
    * Its global purpose is to be able to add an HTML comment at the end of a page
    * with the templates files used.
    * Like the template and locale string usage loging, we just want unique entries
    *
    * @param string   $fileName  file name of template file.  Can be NULL value
    *
    * @access public
    * @authour Julien Lamarche
    *
    * @return string The cumulative value to date
    */
   function addUsedTemplate($fileName) {
      static $templatesUsed;
      if(!isset($templatesUsed))  $templatesUsed = '';

      if($fileName != null) {
         // Search if this template has been used before
         if(!strstr($templatesUsed, $fileName)) {
           $templatesUsed .= trim($fileName) . "\n";
         }
      }

      return $templatesUsed;
   }

?>
