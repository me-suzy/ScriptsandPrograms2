<?php
/**
 * Table Definition for be_sections
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_LanguageDataObject.class');

class DO_Be_sections extends BE_LanguageDataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_sections';                     // table name
    var $sectionID;                       // int(5)  not_null primary_key multiple_key unsigned auto_increment
    var $URLname;                         // string(255)  not_null primary_key multiple_key
    var $author_id;                       // int(5)  unsigned
    var $subsiteID;                       // int(5)  not_null multiple_key
    var $dateCreated;                     // int(10)  not_null unsigned
    var $dateModified;                    // int(10)  not_null unsigned
    var $dateAvailable;                   // int(10)  not_null unsigned
    var $dateRemoved;                     // int(10)  not_null unsigned
    var $dateForSort;                     // int(10)  not_null unsigned
    var $content_type;                    // string(8)  not_null
    var $main_languageID;                 // string(2)  not_null
    var $hide;                            // int(2)  not_null multiple_key unsigned
    var $deleted;                         // int(2)  not_null unsigned
    var $restrict2members;                // int(5)  not_null unsigned
    var $showSections;                    // int(2)  
    var $showArticles;                    // int(2)  
    var $showLinkSubmit;                  // int(2)  
    var $pollID;                          // int(5)  unsigned
    var $hitCounter;                      // int(10)  not_null unsigned
    var $priority;                        // int(5)  
    var $redirect;                        // string(255)  
    var $commentID;                       // int(7)  
    var $orderbySections;                 // string(55)  
    var $orderbySectionsLogic;            // string(4)  
    var $orderbyArticles;                 // string(55)  
    var $orderbyArticlesLogic;            // string(4)  
    var $orderbyLinks;                    // string(55)  
    var $orderbyLinksLogic;               // string(4)  

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return BE_LanguageDataObject::staticGet('DO_Be_sections',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    var $_languageTable = 'be_sectionText';

    function getID() { return $this->sectionID; }


      /**
       * Generate a breadcrumb link to the article
       *
       * Hierarchy takes current subsite into account
       *
       * NB This may have to be extended to work with including article path for links
       *
       * @param integer $sectionID
       * @param string $articleName  Optional final portion for breadcrumb string
       * @param string $type  [index] | link Which php file to use for breadcrumb URLs
       * @return htmlstring
       */
    function breadcrumb($articleName = NULL, $type = 'index', $haveUserPerm) {
         global $_BE, $_PSL;

         # debug("BE_Section breadcrumb for $sectionID", $articleName);

         # nothing to return if display is suppressed
         if (!$_BE['displayBreadcrumbs']) {
            return '';
         }

         if ($type == 'link') {
            $self = $_PSL['absoluteurl'].'/'.$_BE['link_file'];
         } else {
           if (@$_PSL['jpcache.enable'] != 'static' || $haveUserPerm) {
               $self = $_PSL['absoluteurl'].'/'.$_BE['article_file'];
            }
         }

         # debug('sec breadcrumb self',$self);

         $parents = $this->getJoin('parent');
         if (!$parents) {
            if (isset($articleName)) {
               $path = "<a href=\"$self\">" . pslgettext('Home') . '</a>';
            } else {
               $path = pslgettext('Home');
            }

         } else {
           $done = false;
           $first = true;
           $knownIds = array();
           $knownIds[$this->sectionID] = $this;
           while ($parents and !$done) {

             $parents = $parents[0];
             if (isset($knownIds[$parents->sectionID]) && $knownIds[$parents->sectionID]) 
               break; // ancestry loop?
             $knownIds[$parents->sectionID] = $parents;
             
             if (empty($parents->URLname) || $parents->URLname == $_BE['default_section'] || $parents->URLname == 'home') {
                  // we've reached the top
                  $sec = pslgetText('Home');
                  $done = true;
             } elseif (empty($parents->URLname)) {
                  $sec = pslgetText('Home');
                  $done = true;
               } else {
                  $sec = $parents->_text->title;
               }

               // Add link into front of breadcrumb - make into link unless no article AND we're at the start
               # debug("breadcrumb parents now i",$i);
               if (isset($articleName) || (!$first && !(empty($path) && $done) ) ) {

                  if (strlen($sec) > $_BE['cutOffLength']) {
                     $sec = substr($sec, 0, $_BE['cutOffLength']-2) . '... ';
                  }
                  $name = ($parents->URLname) ? $parents->URLname : $parents->sectionID;

                  $path = '<a href="' . $self . '/' . strtolower($name) . '" class="breadcrumb">' . $sec . '</a>' . @$sep . @$path;

               } else {
                  $path = $sec . $sep . $path; // Don't truncate if at end
               }
               $sep = $_BE['bread_delimiter'];
               $first = false;
               $parents = $parents->getJoin('parent');
            }
         }

         // Finish off with Article name, if any
         if (isset($articleName)) {
            $articleName = $_BE['bread_delimiter'].$articleName;
         }

         // $path = "<span class=\"breadcrumb\">$path$articleName</span>";
         $path = $path . $articleName;

         return $path;

      } // end breadcrumb

    function getByName($sectionName) {
      if (is_int($sectionName)) {
        return $this->get($sectionName);
      }
      $this->ensureLanguages(null);
      $this->_text->URLname = $sectionName;
      $found = $this->find(true);
      return $found;
    }


      /**
       * Returns true if the section exists, and false otherwise.
       *
       * @return array
       */
      function checkForURLname($URLname, $language=null) {

         if (empty($URLname)) {
            return false;
         }

         $sectionLang = new DO_be_sectionText;
         $sectionLang->URLname = $URLName;
         $res = $sectionLang->find();
         return ($res>0);
      } // end checkForURLname

}
