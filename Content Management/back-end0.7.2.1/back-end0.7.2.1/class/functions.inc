<?php
   /**
    * Functions shared between phpSlash and Back-End
    *
    * @package     phpSlash
    * @version     0.7 $Id: functions.inc,v 1.87 2005/06/15 01:20:47 mgifford Exp $
    * @copyright   Copyright (C) 2003-5 OpenConcept Consulting
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


   /***************************************************************************
    function   : getError->returns a template with $msg
    Parameters : $msg->The message you want to return to someone
    ****************************************************************************/
   function getError ($msg) {
      if (pslClassExists('slashTemplate')) {
         return getMessage($msg, 'error');
      } else {
         return sprintf('<div class="error">ERROR: %s</div>', $msg);
      }
   }


   /***************************************************************************
    function   : getMessage->return a template with $msg
    Parameters : $msg->The message you want to return to someone
    $tpl->template to use ( "$tpl + ".tpl")
    ****************************************************************************/
   function getMessage($msg, $tpl = '', $translate = true) {

      /* Templates */
      $templ = & pslNew('slashTemplate');
      $template = 'message.tpl';

      if ($tpl == '') {
         $templ->set_file('template', $template);
      } elseif ($templ->set_file('template' , basename($tpl) .'.tpl' )) {
      } else {
         $templ->set_file('template', $template);
      }

      $templ->set_var(array(
      'ERROR' => $translate ? pslgetText($msg) :
      $msg ));

      $msg = $templ->parse('OUT', 'template');

      return $msg;
   }


   /***************************************************************************
    function   : error->Outputs a template with $msg
    Parameters : $msg->The message you want to return to someone
    ****************************************************************************/
   function pslError($msg) {
      echo getError($msg);
   }


   /***************************************************************************
    function   : message->Outputs a template with $msg
    Parameters : $msg->The message you want to return to someone
    $tpl->template to use ( "$tpl + ".tpl")
    ****************************************************************************/
   function message($msg, $tpl = '') {
      return getMessage($msg, $tpl);
   }


   /***************************************************************************
    function   : arg_separator->call this function to get the current arg
    separator default & and make it RFC1866
    Parameters : $mode->What kind of arg separator (& or &amp;)
    Notes:     : maybe will move to $sess->arg_separator() ?
    ****************************************************************************/
   function arg_separator($mode) {
      $arg_separator = get_cfg_var('arg_separator');
      if (strlen($arg_separator) > 0 ) {
         # If somone set it ...
         if ($mode == 1) {
            $arg_separator = htmlentities($arg_separator);
         }
         # ... e.g. to Ž&Ž, replace it lokal with Ž&amp;Ž.

      } else {

         # The default in PHP is Ž&Ž so we replace it lokal with Ž&amp;Ž
         if ($mode == 1) {
            $arg_separator = '&amp;';
         } else {
            $arg_separator = '&';
         }
      }
      return $arg_separator;
   }


   /***************************************************************************
    function   : stripBadHTML->rids a string of unwanted HTML.  Wanted HTML
    goes in the global $approvedtags array (set
    in config.php)
    Parameters : $string->The string to examine / manipuate
    ****************************************************************************/
   function stripBadHTML($str) {

      global $_PSL;

      $reg = array();

      $approvedtags = $_PSL['approvedtags'];

      $str = stripslashes($str);
      $str = eregi_replace("<[[:space:]]*([^>]*)[[:space:]]*>", "<\\1>", $str);

      // Didn't account for missing quote: <a href="link>link</a>
      // $str = eregi_replace("<a([^>]*)href=\"?([^\"]*)\"?([^>]*)>",
      //                      "<a href=\\2>", $str);
      $str = eregi_replace("<a([^>]*)href=\"?([^\"]*)\"*([^>]*)>", "<a href=\\2>", $str);

      // It should be "<a href=\"\\2\">" but the eregi doesn't work properly without the ""'s
      $tmp = '';

      while (eregi("<([^> ]*)([^>]*)>", $str, $reg)) {
         $i = strpos($str, $reg[0]);
         $l = strlen($reg[0]);

         if ($reg[1][0] == '/') {
            $tag = strtolower(substr($reg[1], 1));
         } else {
            $tag = strtolower($reg[1]);
         };

         if ($a = $approvedtags[$tag]) {

            if ($reg[1][0] == '/') {
               $tag = "</$tag>";
            } elseif ($a == 1) {
               $tag = "<$tag>";
            } else {
               $tag = "<$tag " . trim($reg[2]) . '>';
            };

         } else {
            $tag = '';
         };

         $tmp .= substr($str, 0, $i) . $tag;
         $str = substr($str, $i+$l);
      }
      $str = $tmp . $str;

      // Squash PHP tags unconditionally
      $str = ereg_replace('<\?', '', $str);

      return $str;
   }


   /***************************************************************************
    function   : getTitleBar
    Parameters : width => table width
    title => title
    ****************************************************************************/
   function getTitleBar($width, $title) {

      global $_PSL;

      /* Templates */

      $stempl = & pslNew('slashTemplate');
      // $stempl->debug = 1;

      $stempl->set_file (array('titlebar' => 'titlebar.tpl' ));

      $stempl->set_var(array(
         'SKIN'     => $_PSL['skin'],
         'IMAGEDIR' => $_PSL['imageurl'],
         'WIDTH'    => $width,
         'TITLE'    => pslgetText($title)
      ));
      $stempl->parse('TITLE', 'titlebar');

      return $stempl->get('TITLE');

   }
   // end getTitleBar

   /***************************************************************************
    function   : titlebar->echoes out the contents returned from
    getTitlebar()
    Parameters : $width->table width
    $title->title for the bar
    ****************************************************************************/


   function titlebar($width, $title) {
      echo getTitleBar($width, $title);
   }


   function getFancyBox($width='', $title='', $contents, $link = '', $box_type = '', $var_ary = '', $id = '', $ordernumLower=null, $ordernumHigher=null) {

      global $_PSL, $perm;

      $template = 'fancybox.tpl';
      $align = '';

      /* Templates */
      $templ = & pslNew('slashTemplate');
      // $templ->debug = 1;

      if ($box_type == '') {
         $templ->set_file('fancybox', $template);
      } elseif ($templ->set_file('fancybox' , basename($box_type) . 'box.tpl')) {
      } else {
         $templ->set_file('fancybox', $template);
      }

      // If the contents are empty don't generate an empty box
      if (!$contents) {
         return null;
      }

      if ($link == '') {
         $templ->set_var(array(
            'LINK_OPEN' => '',
            'LINK_CLOSE' => ''
         ));
      } else {
         $templ->set_var(array(
            'LINK_OPEN' => '<a href="' . $link . '">',
            'LINK_CLOSE' => '</a>'
         ));
      }

      $title = pslgetText($title);

      // Setup admin links

      if (isset($id) && !empty($id) && $perm->have_perm('block')) {
         $adminLinks = be_generateAdminLinks(array(
                  'name'        => 'id',
                  'title'       => 'Block',
                  'id'          => $id,
                  'objectTitle' => $title,
                  'actions'     => array('edit','view','delete'),
                  'target'      => 'blockAdmin.php',
                  'confText'    => 'This action will affect all sections.  Are you sure you wish to delete'
               ));
         $reorderDesc = ' Block ' . $id . ' ('. $title . ')';

         $raiseBlock = $lowerBlock = null;

         if($ordernumHigher != 0) {

/*
Disabled because this does not presently work for different columns.  Each column needs to be treated as a unique instance.
             $raiseBlock = '<a href="' . $_PSL['rooturl'] .  '/admin/blockAdmin.php?submit=reorder&amp;id='. $id . '&amp;ordernum=' . ($ordernumHigher+1) . '"
            title="Raise' . $reorderDesc . '".
            onmouseover="document.getElementById(\'reorder'. $id . '\') src=\'/images/BE/buttons/btn_up_over.gif\'"
            onmouseout="document.getElementById(\'reorder'. $id . '\').src=\'/images/BE/buttons/btn_up_dim.gif\'">
            <img src="/images/BE/buttons/btn_up_norm.gif" width="23" height="11"  alt="Raise' . $reorderDesc . '" id="reorder' . $id . '" /></a>';
*/

         }

         if($ordernumLower != 0) {

/*
Disabled because this does not presently work for different columns.  Each column needs to be treated as a unique instance.
            $lowerBlock = '<a href="' . $_PSL['rooturl'] .  '/admin/blockAdmin.php?submit=reorder&amp;id='. $id . '&amp;ordernum=' . ($ordernumLower-1) . '"
            title="Lower' . $reorderDesc . '".
            onmouseover="document.getElementById(\'reorder'. $id . '\') src=\'/images/BE/buttons/btn_down_over.gif\'"
            onmouseout="document.getElementById(\'reorder'. $id . '\').src=\'/images/BE/buttons/btn_down_dim.gif\'">
            <img src="/images/BE/buttons/btn_down_norm.gif" width="23" height="11"  alt="Lower' . $reorderDesc . '" id="reorder' . $id . '" /></a>';
*/

         }

         $adminLinks .= $raiseBlock . $lowerBlock;

      } else {
         $adminLinks = '';
      }

      $templ->set_var(array(
         'IMAGEDIR' => $_PSL['imageurl'],
         'SKIN'     => $_PSL['skin'],
         'WIDTH'    => $width,
         'ALIGN'    => $align,
         'TITLE'    => $title,
         'CONTENTS' => $contents,
         'ADMIN'    => $adminLinks
      ));

      if (is_array($var_ary)) {
         $templ->set_var($var_ary);
      }
      // debug('templ_var', $templ->varvals);

      return $templ->parse('FANCYBOX', 'fancybox');

   } // end getFancyBox


   function fancybox($width, $title, $contents, $link = '', $box_type = '', $id = '') {
      echo getFancyBox($width, $title, $contents, $link, $box_type, $id);
   }


   /******************************************************************************
    function   : generateID
    Parameters : generates an id for all new items to store in a DB.
    *******************************************************************************/
   function generateID($sequence) {

      if ($sequence) {
         $sl_q = & pslNew('BEDB');
         $nextID = $sl_q->nextid($sequence);
         return $nextID;
      } else {
         // TODO: Shouldn't be an echo
         echo "<br /><br /><strong></strong><div class=\"error\">You need to pass in the sequence to generateID!</div></strong><br /><br />\n";
         exit;
      }
   }


   /***************************************************************************
    Function   : logwrite()
    Use:       : Logs actions for admin evaluation later (adds time to entry)
    Parameters : $description => A short description of the logged data
    (ie: Admin Login, Story Added)
    $data        => The actual data to log
    (ie: admin login by $author_name ($author_id)
    (ie: Story $story_title added by $author_name)
    ****************************************************************************/
   function logwrite($description, $data) {
      global $_PSL, $auth;

      if (!empty($_PSL['use_infolog'])) {
         // Init vars (we don't like warnings :)
         $timestamp = time();
         $id = generateID('psl_infolog');
         $sl_q = pslNew('slashDB');

         // Deal with arrays
         if (is_array($data)) {
            $tmp = "Array data:\n";
            foreach ($data as $key => $val) {
               $tmp .= "$key=$val\n";
            }
            $data = $tmp;
         }

         // clean() thing now becasue we can't clean() arrays
         $description = clean($description);
         $data = nl2br(clean($data));
         $userID = $auth->auth['uid'];

         // log it
         // Should add userID - $authObj = pslNew('auth'); $userID = $authObj->auth['uid'];
         $sql = "INSERT INTO psl_infolog (id,date_created,description,data,userID)
            VALUES('$id','$timestamp','$description','$data','$userID')";
         $sl_q->query($sql);

      } elseif ($_PSL['debug']) {
         echo 'Infolog: Log requested but logging has been disabled by config.';
      }
   }


   /**
    * return a string detailing structure of $var
    *
    * requires output buffering (built into PHP4)
    *
    * @author Matthew Leingang (sympleko@users.sourceforge.net)
    * @see print_r
    * @param mixed
    * @return string
    */
   function get_r($var) {
      ob_start();
      print_r($var);
      $ans = ob_get_contents();
      ob_end_clean();
      return $ans;
   }


   /**
    * Generate diagnostic information or messages
    *
    * @access public
    * @author Matthew Leingang (sympleko@users.sourceforge.net)
    * @param string name of the variable or function you're debugging
    * @param mixed  message or variable to be debugged
    * @param destination of message:
    *        'now' - output immediately
    *        'log' - log in db
    *        'mail' - mail to whomever
    *        'delayed','queue','push' - add output to next debug call
    *        other - return as string
    *        (optional; default - specified in config file)
    * @param int   maximum recursion level
    *        (applies only to 'now' debugs')
    *        (-1 for no limit to recursion depth)
    *        (optional; default - specified in config file)
    * @global bool $_PSL['debug'] debug if true
    * @global bool $_PSL['debug_type'] default destination of output
    * @global bool $_PSL['debug.mailto'] recipient of debug e-mail if sent
    * @global bool $_PSL['debug.max_recursion_level'] maximum level of recurstion
    * @global bool $_PSL['debug.escape_html'] if true, escape HTML in output
    * @return string debug output string, or '' if debug is shut off
    **/
   function debug($name, $msg='', $type = 'default', $max_level = NULL) {
      GLOBAL $_PSL;

      static $ans;

      if (!isset($_PSL['debug']) || !$_PSL['debug']) {
         return '';
      }
      if (is_int($type)) {
         // max level specified but no type
         return debug($name, $msg, 'default', $type);
      }
      if (is_null($max_level)) {
         $max_level = $_PSL['debug.max_recursion_level'];
      }
      if ('default' == $type) {
         $type = $_PSL['debug_type'];
      }
      $escapeHtmlFlag = isset($_PSL['debug.escape_html']) ? $_PSL['debug.escape_html'] : false;

      if(!isset($ans)) {
         if (function_exists('debug_backtrace')) {
            $back = debug_backtrace();
            // print_r($back);
            $ans  = '<small>' . (empty($back[1]['class']) ? basename($back[0]['file']) : $back[1]['class']);
            $ans .= empty($back[1]['function']) ? '' : '::'.$back[1]['function'];
            $ans .= '(line ' . $back[0]['line'] . ') </small>';
         } else {
  	       $ans = null;
  	      }
      }

  	   $ans .= debug_iterator($name,$msg,$type,$max_level,$escapeHtmlFlag);

      switch($type) {
         case 'log':
         logwrite($name, get_r($msg));
         $ans = true;
         break;
         case 'now':
         echo $ans;
         # debug_msg("$ans"); // Pretty debugging
         $ans = null;
         break;
         case 'mail':
         if ($_PSL['debug.mailto']) {
            mail($_PSL['debug.mailto'], // pac - I suspect this could overload many mail servers...
            "PHPSlash Debug Output",
               $ans,
               "X-Generator: " . $_PSL['phpself']);
         }
         $ans = null;
  	      break;
  	      case 'delayed':
  	      case 'queue':
  	      case 'push':
  	      // don't do anything yet.
         return NULL;
         break;
      }
      return $ans;
   }


   /**
    * Traverse a data structure, dumping into nice HTML
    *
    * @author Matthew Leingang (sympleko@users.sourceforge.net)
    * @access private
    * @see    debug
    * @param  string message or variable name
    * @param  mixed  variable to diagnose
    * @param  string destination of message:
    * @param  int    maximum recursion level
    * @param  int    current level of recursion
    * @param  bool   escape HTML output
    * @return string debug output string
    *                '' if _PSL['debug'] is off, or recursion level is exceeded
    **/
   function debug_iterator($msg, $var, $type, $max_level = NULL, $level = 0, $escapeHtmlFlag = true) {
      GLOBAL $_PSL;
      $str = '';
      $brnl = "<br />\n";

      if (is_null($max_level)) {
         $max_level = $_PSL['debug.max_recursion_level'];
      }
      if (!($level <= $max_level) || !$_PSL['debug']) {
         return '';
      }
      #   if ($escapeHtmlFlag) {
      #      $msg = htmlentities($msg);
      #   }
      if ($level == 0) {
         $debug = '<strong>Debug: %s</strong>';
      } else {
         $debug = 'Debug: %s';
      }
      switch(true) {

      case is_array($var):
         $str .= sprintf("<small>$debug: Array</small>" . $brnl, $msg);
         foreach ($var as $key => $val) {
 #           if ($key == 'db' || $key == 'psl') // PAC: hack so we dont show the whole $db and $_PSL thangs
 #              $str .= sprintf("<small>Debuga %s: (%s)</small>" . $brnl, $msg, $key);
 #           else
               $str .= debug_iterator($msg . '[' . $key . ']', $val, $type, $max_level, $level+1, $escapeHtmlFlag);
         }
         break;

      case is_object($var):
         $str .= sprintf("<small>$debug: Object of class <em>%s</em></small>" . $brnl,
            $msg, get_class($var));
         foreach(get_object_vars($var) as $key => $val) {
            if ($key == 'db' || $key == 'psl') // PAC: hack so we dont show the whole $db and $_PSL thangs
            $str .= sprintf("<small>Debugb %s: (%s)</small>" . $brnl, $msg, $key);
            else
               $str .= debug_iterator($msg . '.' . $key, $val, $type, $max_level, $level+1, $escapeHtmlFlag);
         }
         break;

      case !isset($var):
         $str .= sprintf("<small>$debug: <em>NULL</em></small>" . $brnl, $msg);
         break;

      default:
         if ($escapeHtmlFlag) {
            $var = @htmlentities($var); // supress any errors
         }
         $str .= sprintf("<small>$debug: %s</small>" . $brnl, $msg, $var);
      }

      return $str;
   }

   /**
    * dump contents of array into HTML
    *
    * @access private
    * @deprec debug now calls debug_iterator instead
    * @param  string title of array
    * @param  array  array to debug
    * @return void
    **/
   function debug_array($name, $ary) {

      global $_PSL;

      if ($_PSL['debug']) {
         // && is_array($ary) && (sizeof($ary) > 0) ) {
         if (!is_array($ary) || count($ary) == 0) {
            echo "<small><strong>Debug: $name:</strong><em>empty</em><br />\n";
         } else {
            echo "<small><strong>Debug: $name:</strong><br />\n";
            foreach ($ary as $key => $val) {
#            while (list($key, $val) = @each($ary )) {
               echo $name .'[' .$key ."] = $val<br />\n";
            }
         }
#         @reset($ary );
         echo '</small>';
      }
   }


   /**
   * Used by methods within classes to work out where they were called from
   */
   function debug_calledFrom($listArgs=false) {
      global $_PSL;
      if (!isset($_PSL['debug']) || !$_PSL['debug']) {
         return false;
      }

      if (function_exists('debug_backtrace')) {
         $back = debug_backtrace();
         // echo "<pre>"; print_r($back); echo "</pre>";

         $ans = '<small>'.@$back[1]['class'].'::<strong>'.@$back[1]['function']. '</strong>';
         if ($listArgs && count($back[1]['args'])) {
            $ans .= ' (';
            foreach ($back[1]['args'] as $v) {
               $ans .= (!isset($v) ? '<em>NULL</em>' : (is_scalar($v) ? $v : (is_array($v) ? '['.@implode(',',$v).']' : 'object')) . ';');
            }
            $ans .= ')';
         }
         $ans .= '{'.basename($back[0]['file']). '/' .$back[0]['line'].'}';
         $ans .= ' CalledFrom:'.@$back[2]['class'].'::'.@$back[2]['function']. ' {'.basename($back[1]['file']). '/'. $back[1]['line'].'}';
         if (isset($back[2])) {
            $ans .= ' {CalledFrom:'.@$back[3]['class'].'::'.@$back[3]['function']. ' ('.basename($back[2]['file']). '/'. $back[2]['line'].'}]';
         }
         $ans .= "</small><br />\n";

         echo $ans;

         return true;

      }

      return false;

   }

   /******************************************************************************
    function   : slashhead->Wrapper to getHeader

    Replaces the 0.5x slashhead.inc file
    Used to generate the header at the top of the
    each page
    Parameters : $title->Title of the page being displayed
    $metaobject->Extra stuff about the page (Admin, Home, Poll)
    $section->if supplied, generates blocks for this section
    *******************************************************************************/
   function slashhead($title, $metaobject = '', $section = '', $tpl = '') {

      echo getHeader($title, $metaobject, $section, $tpl);

   }


   /******************************************************************************
    function   : getHeader->return header html to caller

    Replaces the 0.5x slashhead.inc file
    Used to generate the header at the top of the
    each page
    Parameters : $title->Title of the page being displayed
    $metaobject->Extra stuff about the page (Admin, Home, Poll)
    Accepts an array of name/value pairs for metatags.
    Alternative navbar template or menu can be passed in
    navbar_ary[tpl] or navbar_ary[menu_ary].
    $section->if supplied, generates blocks for this section
    Accepts an array to pass to getBlocks
    $tpl->optional alternate template
    *******************************************************************************/
   function getHeader($title, $metaobject = '', $section = '', $tpl = '') {

      global $_PSL, $_BE, $BE_currentLanguage, $toplevelSections, $subSectionInfo;

      /* Templates */

      $templ = pslNew('slashTemplate');
      $default_template = 'slashHead.tpl';
      // $templ->debug = 1;

      if ($tpl == '') {
         $templ->set_file('header', $default_template);
      } elseif ($templ->set_file('header' , basename($tpl) . '.tpl')) {
      } else {
            $templ->set_file('header', $default_template);
      }

      $QUERY_STRING = (!empty($GLOBALS['QUERY_STRING'])) ? '?'.$GLOBALS['QUERY_STRING'] : '';

      // Allow for a section/article specific graphic header
      $sectionURLname = (isset($_BE['currentSectionURLname'])) ? strtolower($_BE['currentSectionURLname']) : null;
      $articleURLname = (isset($_BE['currentArticleURLname'])) ? strtolower($_BE['currentArticleURLname']) : null;

      // Allows for section/Article specific graphics to be inserted into the
      // header always try for a filename so if it is missing we know which file
      // we wanted
      if (file_exists($_PSL['basedir'] . '/images/headers/' . $BE_currentLanguage . '/' . $sectionURLname . '_' . $articleURLname . '.gif')) {
         $be_headerImage = $_PSL['rooturl'] . '/images/headers/' . $BE_currentLanguage . '/' . $sectionURLname . '_' . $articleURLname . '.gif';
      } else {
         $be_headerImage = $_PSL['rooturl'] . '/images/headers/' . $BE_currentLanguage . '/' . $sectionURLname . '.gif';
      }

      //nh: Avoid Namespace Collsion with FreeTrade, their version only has
      //    2 arguments, so if there are more, the call is legit
      if (defined('FREETRADE_VERSION') && ($section == '')) {
         return(ftgetHeader($title, $metaobject));
      }

      $navbar_ary = array();
      $navbar_ary['tpl'] = $navbar_ary['menu_ary'] = '';
      $dropDownMenu = '';

      if (!$title) {
         return pslgetText('NO TITLE');
      };

      // CHECK TIME YYY
      if (count($_BE['Language_array']) > 1) {
         $languageObj = & pslNew('BE_Language');
         $langPicker = $languageObj->languagePicker();
      } else {
        $langPicker = '';
      }

      // echo "<pre>"; print_r($_BE); echo "</pre>";

      if (!empty($sectionURLname) || !empty($articleURLname)) {
         $loginURL = '/' . $_BE['article_file'] . '/' . $sectionURLname . '/' . $articleURLname . '/?login=yes';
      } else {
         $loginURL = '/login.php';
      }

      $templ->set_block ('header', 'each_metatag', 'metatag_block');
      if (is_array($metaobject)) {

         $templ->set_var(array (
            'metatag_block' =>  '',
            'XSITEOBJECT'   => (!empty($metaobject['object'])) ? $metaobject['object'] : 'website'
         ));
         unset($metaobject['object']);

         if (!empty($metaobject['navbar_ary'])) {
            $navbar_ary = $metaobject['navbar_ary'];
         }
         unset($metaobject['navbar_ary']);

         foreach ($metaobject AS $key => $value) {
            if (isset($value) && !empty($value)) {
               $templ->set_var (array (
                  'METANAME'        => $key,
                  'METADESCRIPTION' => $value
               ));
            }
            $templ->parse ('metatag_block', 'each_metatag', true);
         }
      } else {
         $templ->set_var(array (
            'metatag_block' =>  '',
            'XSITEOBJECT'   => (!empty($metaobject)) ? $metaobject : 'website'
         ));
      }

      // the subsite_info block is used to allow for variations in the template
      // depending on whether the user is in a supsite or not.
      //   - the home_info block is used if we're not in a subsite
      //   - the subsite_info is used when in a subsite.
      // The main purpose is to give a link back to the main site when in a
      // subsite; it also gives an opportunity to link to a subsite-specific
      // logo (eg {IMAGE_DIR}/logo{SUBSITE_URL}.png).
      $templ->set_block('header', 'subsite_info');
      $templ->set_block('header', 'home_info');

      if (!$_PSL['module']['BE_Subsite']) {
         $templ->set_var('subsite_info', '');
      } else {
         global $_BE, $BE_subsite;
         # debug('getHeader BE_subsite',$BE_subsite);
         if (isset($BE_subsite['subsite_id'])) {
            if (!isset($BE_subsite['subsite_name'])) $BE_subsite['subsite_name'] = '';
            if (!isset($BE_subsite['URLname'])) $BE_subsite['URLname'] = '';

            $templ->set_var('home_info', '');
            // -mg - an orphan block related to breadcrumbs??

            $templ->set_var(array(
               'SITE_ROOT'    => $_BE['subsite.roothost'].$_PSL['rooturl'],
               'SUBSITE_NAME' => $BE_subsite['subsite_name'],
               'SUBSITE_URL'  => $BE_subsite['URLname']
            ));
         } else {
            $templ->set_var('subsite_info', '');
         }
      }

      /* Do not embedded in another application.
       * See NOTE ON EMBEDDING PHPSLASH in public_html/config.php
       * This part also generates the css menu if required by config
       */

      if (!defined('PHPSLASH_CLIENT')) {

         if ($_PSL['module']['NavBar']) {
         	$navbarObj = pslNew('NavBar');
            $navbarTpl = (isset($navbar_ary['tpl']) && !empty($navbar_ary['tpl'])) ? $navbar_ary['tpl'] : null;
            $navbarMenu = (isset($navbar_ary['menu_ary']) && !empty($navbar_ary['menu_ary'])) ? $navbar_ary['menu_ary'] : $_PSL['menuitem'];
            $navbarObj = pslNew('NavBar');
            $navbarContent = $navbarObj->getNavBar($navbarTpl, $navbarMenu);
            // Optional admin navbar - would need to be uncommented to be available.
            // $navbarObj = pslNew('NavBar');
            // $navbarAdminContent = $navbarObj->getNavBar($navbarTpl, $_PSL['menuadmin']); // Additional option tpl=navbarBlockh
            $navbarAdminContent = '';
         } else {
            $navbarContent = $navbarAdminContent = '';
         }

         if (isset($_BE['useJavascriptDD']) && ($_BE['useJavascriptDD'] == 'domMenu' || $_BE['useJavascriptDD'] == 'inline')) {
            // default time 24hrs
            $cacheExpiryTime = $_PSL['cacheExpiryTime'];
            $jsObj = & pslNew('BE_javascriptDD');
            $dropDownMenu = $jsObj->javascriptDDmenu($cacheExpiryTime, $_BE['useJavascriptDD']);

         }
      }

      global $templateExtension, $breadcrumb;

      if (empty($sectionURLname)) {
         $sectionURLname = (!empty($section)) ? $section : str_replace(array('/', '.php'), '', $_PSL['phpself']);
      }

      // Create language switching links
      if (count($_BE['Language_array']) > 1) {
         if(isset($_BE['languagedomains']) && is_array($_BE['languagedomains'])) {
            $languageDomains = array_flip($_BE['languagedomains']);
         }
         foreach ($_BE['Language_array'] AS $language) {
            $link = (!empty($languageDomains[$language])) ? 'http://' . $languageDomains[$language] : null;
            $link .= $_PSL['rooturl'] . $_SERVER['SCRIPT_NAME'];
            $link .= (!empty($_BE['currentSection'])) ? '/' . $_BE['currentSection'] : null;
            $link .= (!empty($_BE['currentArticle'])) ? '/' . $_BE['currentArticle'] : null;
            $link .= (!empty($QUERY_STRING)) ? $QUERY_STRING . '&amp;language=' . $language : '?language=' . $language;
            // global $sess;
            // $link .= '&amp;' . $sess->name . '=' . $sess->id();

            $templ->set_var('SWITCH_LANGUAGE_'.strtoupper($language),  $link);
         }
      }

      $templ->set_var(array(
         'LANGUAGE_ID'            => $BE_currentLanguage,
         'SERVER_NAME'            => $_SERVER['SERVER_NAME'],
         'VERSION'                => $_BE['version'],
         'LANGUAGE'               => $langPicker,
         'CURRENT_LANGUAGE'       => $BE_currentLanguage,
         'JAVASCRIPT_MENU'        => $dropDownMenu, # to be phased out
         'DROPDOWN_MENU'          => $dropDownMenu,
         'PHPSELF'                => $_PSL['phpself'],
         'TODAYS_DATE'            => psl_dateLong(time()),
         'BE_SECTION'             => $sectionURLname,
         'BE_ARTICLE'             => $articleURLname,
         'BE_HEADER_IMG'          => $be_headerImage,
         'DISPLAY_TITLE'          => $title, // Duplicates SITETITLE
         'TOP_LEVEL_SECTIONS'     => $toplevelSections,
         'SUB_SECTION_INFO'       => $subSectionInfo,
         'SUBSITE_TITLE'          => be_getSubsiteTitle(),
         'ABSOLUTE_URL'           => $_PSL['absoluteurl'],
         'TEMPLATE_EXT'           => $templateExtension,
         'BREADCRUMB'             => $breadcrumb,
         'LOGIN_URL'              => $loginURL,

         'QUERYSTRING'       => $QUERY_STRING,
         'SITE_NAME'         => pslgetText($_PSL['site_name']),
         'SITE_OWNER'        => pslgetText($_PSL['site_owner']),
         'SITE_SLOGAN'       => pslgetText($_PSL['site_slogan']),
         'SITE_TITLE'        => pslgetText($_PSL['site_title']),
         'SECTION'           => isset($_PSL['section']) ? $_PSL['section'] : null,
         'ROOTDIR'           => $_PSL['rooturl'],
         'IMAGEDIR'          => $_PSL['imageurl'],
         'SITETITLE'         => $title,
         'SEARCH_ACTION_URL' => $_BE['search_url'],
         'NAVBAR'            => $navbarContent,
         'ADMIN_NAVBAR'      => $navbarAdminContent,
         'SKIN'              => $_PSL['skin']
      ));

      $out = $templ->parse('OUT', 'header');

      return $out;

   }


   /******************************************************************************
    function   : slashfoot->Wrapper to getFooter

    Replaces the 0.5x slashfoot.inc file
    Used to generate the footer on the bottom of
    each page

    debug.template = true in public_html/config.ini.php for template loging to
    work.  On top of that, a modified version of phplib/php/template.inc specific
    to BE is required for template loging to work.  The template loging feature
    includes an external log with templates used while debug.template = true and
    adds, for each page viewed, templates viewed for that page at the footer
    -- Julien Lamarche.

    Parameters : None
    *******************************************************************************/
   function slashfoot($tpl = '', $lastModifiedDate = '') {
      echo getFooter($tpl, $lastModifiedDate);
   }


   /******************************************************************************
    function   : getFooter->returns html to caller

    Replaces the 0.5x slashfoot.inc file
    Used to generate the footer on the bottom of
    each page

    Parameters : optional template (no .tpl)
    *******************************************************************************/
   function getFooter($tpl = '', $lastModifiedDate = '') {

      global $_PSL, $templateExtension, $scriptTimer;
      $elapsedTime = $lastModified = null;

      /* Templates */
      $default_template = 'slashFoot.tpl';

      $templ = & pslNew('slashTemplate'); //, $_PSL['templatedir']);
      // $templ->debug = 1;

      if ($tpl == '') {
         $templ->set_file('footer', $default_template);
      } elseif ($templ->set_file('footer' , basename($tpl) . '.tpl' )) {
      } else {
         $templ->set_file('footer', $default_template);
      }

      /* Do not call the NavBar if embedded in another application.
       * See NOTE ON EMBEDDING PHPSLASH in public_html/config.php
       */
      $dropDownMenu = '';
      if (!defined('PHPSLASH_CLIENT')) {
         if ($_PSL['module']['NavBar']) {
            $navbarObj = pslNew('NavBar');
            $navbarContent      = $navbarObj->getNavBar('navbarFooter', 'menuitem');
            $navbarObj = pslNew('NavBar'); // WHY DUPLICATE?
            $navbarAdminContent = $navbarObj->getNavBar('navbarBlock', 'menuadmin');
            unset($navbarObj);
            $templ->set_var(array(
               'NAVBAR'       => $navbarContent,
               'ADMIN_NAVBAR' => $navbarAdminContent
            ));
         }

         global $_BE, $BE_currentLanguage;
         if ($_BE['useJavascriptDD'] ==  'css') {
            // default time 24hrs
            $cacheExpiryTime = $_PSL['cacheExpiryTime'];

            // Initialize fileCache for this language
            $cacheObject = new fileCache('cssDropDownMenu', $BE_currentLanguage, $cacheExpiryTime);
            $dropDownMenu = $cacheObject->retrieve();
            if (empty($dropDownMenu)) {
               // Generate new css menu and cache it
               $cssMenuGenObj = & pslNew('BE_cssMenuGenerator');
               $dropDownMenu = $cssMenuGenObj->makeUlmenu();
               $cacheObject->store($dropDownMenu);
            }
         }

      }

      if (!empty($scriptTimer)) {
         $scriptTimer->stop('main');
         $elapsedTime = round($scriptTimer->get_current('main'), 4);
         $templ->set_var('TIMER', $elapsedTime);
      }

      if (!empty($lastModifiedDate)) {
         $lastModified = pslgetText('Last Modified') . ' ' . psl_dateLong($lastModifiedDate);
         $templ->set_var('LAST_MODIFIED', $lastModified);
      }

      // specify template variable
      if (!empty($templateExtension)) {
         $templ->set_var('TEMPLATE_EXT', $templateExtension);
      }

      // All of these variables should be accessible to all templates - mg Mar2005
      $templ->set_var(array(
         'ROOTDIR'       => $_PSL['rooturl'],
         'BASEURL'       => $_PSL['rooturl'],
         'IMAGEDIR'      => $_PSL['imageurl'],
         'ABSOLUTEURL'   => $_PSL['absoluteurl'],
         'REQUEST_URI'   => $_SERVER['REQUEST_URI'],
         'DROPDOWN_MENU' => $dropDownMenu
      ));

      return $templ->parse('OUT', 'footer');

   }


   /******************************************************************************
    function   : clean->removes nasty things that hurt databases
    Parameters : $dirty->string or array to clean up
    $allow_html->if true, then we don't convert HTML characters
    like < and > into &gt; and &lt;
    *******************************************************************************/
   function clean($dirty, $allow_html = false) {
      if (empty($dirty)) {
         return NULL;
      }
      if (is_array($dirty)) {
         foreach ($dirty as $key => $val) {
            if (is_scalar($val)) { //PAC Hack for BE_History which passes arrays through GET
               if ($allow_html) {
                  $clean[$key] = str_replace("'", '&#039;', (stripslashes($val)));
               } else {
                  $clean[$key] = str_replace("'", '&#039;', (htmlspecialchars(stripslashes($val))));
               }
            } elseif (is_array($val)) {
               $clean[$key] = clean($val, $allow_html);
            } else { // Ignore $val where null or Object
               $clean[$key] = NULL;
            }
         }
      } else { // assume its a string
         if ($allow_html) {
            $clean = str_replace('\'', '&#039;', (stripslashes($dirty)));
         } else {
            $clean = str_replace('\'', '&#039;', (htmlspecialchars(stripslashes($dirty))));
         }
      }

      return $clean;
   }


   /******************************************************************************
    function   : dirty->remove protective measures added to data for db storage
    use before outputting to browser
    Parameters : $clean->string or array to dirty for browser
    *******************************************************************************/
   function dirty ($clean) {
      $dirty = str_replace('&#039;', "'", (stripslashes($clean)));
      return $dirty;
   }


   /*************  TEMP MAIL LIST STUFF ********************/


   //Begin function for email this is stupid, needs to be shorter
   function format_mail($str, $char_len, $scrub) {
      //BEGIN FUNCTION

      if ($scrub) {
         //Open if Scrub

         $str = stripslashes($str);

         // lh: Hopefully this is the short replacement
         $str = eregi_replace('<(/?)(a|b|br|i|p)+[^>]*>', '', $str);
         $str = eregi_replace("\n|\r|[[:space:]]+|10|13|&#....;", ' ', $str);

         // lh: Please delete the stuff below if my replacement is verified
         /*
          $str = eregi_replace('<br />','',$str);
          $str = eregi_replace('<i>','',$str);
          $str = eregi_replace('</i>','',$str);
          $str = eregi_replace('<b>','',$str);
          $str = eregi_replace('</b>','',$str);
          $str = eregi_replace('<p>','',$str);
          $str = eregi_replace('</p>','',$str);
          $str = eregi_replace('\n',' ',$str);
          $str = eregi_replace('\t',' ',$str);
          $str = eregi_replace('\r\n',' ',$str);
          $str = eregi_replace("<a([^>]*)href=\"?([^\"]*)\"?([^>]*)>",'',$str);
          $str = eregi_replace('</a>','',$str);
          $str = eregi_replace('</A>','',$str);
          $str = ereg_replace('\r\n',' ',$str);
          $str = eregi_replace("[[:space:]]+", ' ', $str);
          $str = eregi_replace('10', ' ', $str);
          $str = eregi_replace('13', ' ', $str);
          $str = eregi_replace("&#....;", ' ', $str);
          $str = eregi_replace("<img([^>]*)src=\"?([^\"]*)\"?([^>]*)>",'',$str);
          */

         $str = stripslashes($str);
      }
      //Close if Scrub
      while (strlen($str) != 0) {
         //Open While
         if (strlen($str) > $char_len) {
            $len = strrpos(substr($str, 0, $char_len - 1), ' ');
         } else {
            $len = 0;
         }
         // in case of no spaces
         if (!$len) {
            $len = $char_len - 1;
         }
         $str_out = substr($str, 0, $len) . "\n";
         $str = ltrim(substr($str, $len, strlen($str)));
      }
      //End While
      return $str_out;
   }


   /******************************************************************************
    function   : breadcrumb->
    Parameters : $ary->cmd line array
    returns    : templated html for a "breadcrumb" link and
    sets global name variables
    *******************************************************************************/
   function breadcrumb ($ary) {

      global $_PSL;

      $as = $_PSL['amp'];
      if (!empty($ary['section_id'])) {
         $sectionObj = & pslNew('Section');
         $ary['section'] = $sectionObj->getName($ary['section_id']);
         $_PSL['section'] = $ary['section'];
      } else {
         // if there's no section_id, then default to home_section_id.
         $ary['section_id'] = $_PSL['home_section_id'];
      }

      if (!empty($ary['topic_id'])) {
         $top = & pslNew('Topic');
         $ary['topic'] = $top->getName($ary['topic_id']);
         $_PSL['topic'] = $ary['topic'];
      }
      if (!empty($ary['author_id'])) {
         $author = & pslNew('Author');
         $ary['author'] = $author->getName($ary['author_id']);
         $_PSL['author'] = $ary['author'];
      }

      if (($ary['section_id'] != $_PSL['home_section_id']) OR (!empty($ary['topic_id'])) OR (!empty($ary['author_id'])) ) {

         $delimeter = '->';
         $section_link = '';
         $topic_link = '';

         $template = & pslNew('slashTemplate'); //, $_PSL['templatedir']);
         $template->debug = 0;
         $template->set_file(array('breadcrumb' => 'breadcrumb.tpl'));

         $template->set_block ('breadcrumb', 'each_crumb', 'crumb_block');

         /* setup the first link to the homepage */
         $template->set_var(array(
            'PHP_SELF' => $_PSL['phpself'],
            'DELIMETER' => '',
            'LINK' => '',
            'SERVER_NAME' => $_SERVER['SERVER_NAME'],
            'NAME' => pslgetText('Home'),
            'ROOTURL' => $_PSL['rooturl'],
            'MAINPAGE' => $_PSL['mainpage']
         ));

         $template->parse ('crumb_block', 'each_crumb', true);

         if ($ary['section_id'] != $_PSL['home_section_id']) {

            $template->set_var(array(
               'DELIMETER' => $delimeter,
               'LINK' => '?section_id=' . $ary['section_id'],
               'NAME' => $ary['section'] ));
            $section_link = $as . 'section_id=' . $ary['section_id'];
            $template->parse ('crumb_block', 'each_crumb', true);

         }

         if ($ary['topic_id'] != '') {

            $template->set_var(array(
            'DELIMETER' => $delimeter,
               'LINK' => '?topic_id=' . $ary['topic_id'] . $section_link,
               'NAME' => $ary['topic'] ));
            $topic_link = $as . 'topic_id=' . $ary['topic_id'];
            $template->parse ('crumb_block', 'each_crumb', true);

         }

         if (!empty($ary['author_id'])) {

            $template->set_var(array(
            'DELIMETER' => $delimeter,
               'LINK' => '?author_id=' . $ary[author_id] . $section_link . $topic_link,
               'NAME' => $ary[author] ));
            $template->parse ('crumb_block', 'each_crumb', true);

         }

         $breadcrumb = $template->parse('OUT', 'breadcrumb');

      } else {

         $breadcrumb = '';
      }

      return $breadcrumb;

   } // end of function breadcrumb


   /* from MailingList.class */
   function is_valid_email($address) {

      if (!$address) {
         return false;
      }
      if (eregi("([_\.0-9a-z-]+@)([0-9a-z][0-9a-z-]+\.)+([a-z]{2,3})", $address)) {
         $ary_address = ($address);
         if ($ary_address[0]) {
            return true;
         } else {
            return false;
         }
      } else {
         return false;
      }
   }


   /**
    * function psl_censor - returns a "censored" string
    *
    * The string is matched against an array of words not accepted.
    * Any matches are replaced by the "replacement" text.
    *
    * If the "censorfile" doesn't exist the string is returned untouched.
    *
    * The format of the censor file is:
    *
    *   < ?php
    *   $profan = array(
    *      badword1,
    *      badword2,
    *      lotsmore
    *   );
    *   ? >
    * This format is compatible with the phorum censor.php file.
    *
    * @param string string
    *
    * @param string replacement [@!#$]
    *
    * @param string censorfile  [_PSL[censorfile]
    *
    * @access public
    *
    * @return string string
    */
   function psl_censor($string, $replacement = '', $censorfile = '') {

      global $_PSL;

      if (!$replacement) {
         $replacement = pslgetText('@!#$');
      }
      if (!$censorfile) {
         $censorfile = $_PSL['censorfile'];
      }
      if (file_exists($censorfile)) {
         static $profan;
         if (!$profan) {
            include_once($censorfile);
         }
         $count = count($profan);
         for ($i = 0 ; $i < $count ; ++$i) {
            $string = eregi_replace($profan[$i], $replacement , $string);
         }
      }

      return $string;
   }


   /**
    * function pslgetText returns a "translated" string
    *
    * The string is matched against an array of translated strings.
    * Any matches are replaced by the "replacement" text.
    *
    * If the string isn't in the array, it is returned untouched.
    *
    * If the language file doesn't exist the string is returned untouched.
    *
    * The format of the language file is:
    *
    *   < ?php
    *   $pslstrings = array(
    *      'original_text' => 'translated_text',
    *      'more original text' => 'more translated text'
    *   );
    *   ? >
    *
    * @param string original
    * @param string languagefile  [_PSL[languagefile]
    * @param binary disableMessageStrike
    *
    * @access public
    *
    * @return string string
    */
   function pslgetText($string, $languagefile = '', $disableMessageStrike=false) {

      global $_PSL, $_BE;

      if (empty($string)) {
         return false;
      }

      $languagefile = (!empty($languagefile)) ? $languagefile : $_PSL['languagefile'];

      if ($languagefile) {
         static $pslstrings;
         if (!$pslstrings) {
            if (file_exists($languagefile)) {
               include_once($languagefile);
            } else {
               debug('File not found', $languagefile);
            }
         }
      }

      // echo "pslgetText:langfile=$languagefile:string=$string<br />\n";
      // return $string;

      if (!empty($pslstrings[$string])) {
         if (isset($_PSL['locale2htmlentities']) && $_PSL['locale2htmlentities']) {
            // $string = htmlentities($pslstrings[$string]);
            $string = be_entities($pslstrings[$string]); // Characterset-sensitive
         } else {
            $string = $pslstrings[$string];
         }
      } elseif (isset($_PSL['debug']) && $_PSL['debug'] && ($pslstrings)) {

         //Determine what path will be reported
         //If debug_backtrace() is available, use it to determine where the function got called
         //If not, just use the current URI
         if (function_exists('debug_backtrace')) {
            $vDebug = debug_backtrace();
            $vFiles = array();
	    // I think we want the calling function, which is why I put 1 instead of count($vDebug)
            for ($i=0;$i<1;++$i) {
               // skip the first, since it's always this func
               if ($i==0) { continue; }
               $aFile = $vDebug[$i];
               $vFiles[] = '(' . basename($aFile['file']) . ':' . $aFile['line'] . ')';
            } // for
           $pathToLog = implode(',',$vFiles);
         }
         else {
            $pathToLog = $_SERVER['REQUEST_URI'];  // needs to be available for command line results
         }

         writeToLogUniqueEntry($pathToLog . ': ' . $string, $_BE['debugStringLog']);

         // Do we strike out the message so the programmer
         // can see where work is required?
         if ($disableMessageStrike == true) {
            $string = $string;
         } else {
            $string = getMessage($string, 'messageStrike', false);
         }

      }

      return $string;

   } // end pslgetText


   /**
   * function writeToLogUniqueEntry is used to log unique entries (not enter the same entry twice)
   * It is at least used to log locale string and template usage
   *
   * It first looks for the string in question in the log
   * and if it is not there, logs it.
   * It is desirable for the translation
   * work if untranslated string only appear once.
   *
   * @param string string
   * @param string logPath
   * @author Julien Lamarche
   *
   * @access public
   *
   * @return void
   */
   function writeToLogUniqueEntry($string, $logPath) {

      if(!is_writable($logPath)) {
         return false;
      }

      // function config
      $dateTimeString = date('Y/m/d H:i:s'); //considered psl_dateTimeShort(time()) but seconds are important here


      //function initialization
      $logExists = false;
	  $stringAlreadyInLog = false;

	   // Search through file if it exists
	   if(file_exists($logPath)) {
	     $logExists = true;
	     $logEntries = file($logPath);

        do {
	        $entry = current($logEntries);
	        if(strstr($entry, $string))  $stringAlreadyInLog = true;
	     }
	     while(next($logEntries) && !$stringAlreadyInLog);
	     reset($logEntries);
	   }

	   // Write entry if it or the file does not exist
	   if(!$logExists || !$stringAlreadyInLog) {
	     $fileLogAppend = fopen($logPath, 'a');
	     fwrite($fileLogAppend, "$dateTimeString: $string\n");
	     fclose($fileLogAppend);
	   }

      return true;

   } // end writeToLogUniqueEntry


   /**
    * look up a keyword in localinfo
    *
    * @param string category (the constant, NOT the string!)
    * @param string keyword to lookup
    * @param string locale (defaults to the current locale for the given category
    * @return string looked-up answer, or $keyword if not found.
    */
   function psl_getLocalinfo($category, $keyword, $locale = false) {
      global $_PSL;
      static $localinfo, $catcodes;

      $locale = ($locale) ? $locale : $_PSL['language'];

      // Is this used other than in debugging?? - mg Mar2005
      // $fc = sprintf("psl_getLocalinfo(%s,%s,%s)", $category, $keyword, ($locale ? $locale : 'FALSE'));
      // debug($fc,'begin');

      $ans = $pslstrings = null;

      if (!$catcodes) {
         foreach(array('LC_CTYPE', 'LC_COLLATE', 'LC_MONETARY', 'LC_NUMERIC', 'LC_TIME') as $cat)
         $catcodes[constant($cat)] = $cat;
      }

      if (is_int($category)) {
         $catcode = $category;
         $category = $catcodes[$catcode];
      } else {
         $catcode = constant($category);
      }

      $locale || $locale = $_PSL['locale'][$category];
      if (!@is_array($localinfo[$locale][$category])) {
         $filename = sprintf('%s/%s.%s.php', $_PSL['localedir'], $locale, $category);
         if (!is_readable($filename)) {
            pslError("psl_getLocalinfo: file either nonexistent or not readable: $filename");
         } else {

            include_once($filename); // sets a variable $pslstrings
            $localinfo[$locale][$category] = $pslstrings;
         }
      }
      // debug('localinfo',$localinfo);
      if (!empty($localinfo[$locale][$category][$keyword])) {
         $ans = $localinfo[$locale][$category][$keyword];
      }
      if (!isset($ans)) {
         # debug($fc,"$keyword not set in locale[$category]");
         /* Hm. it's not in the localinfo array.  We'll try first looking
          in the localinfo for the en_US locale (sorry for the hegemony),
          and then just give up if that fails. */
         if ($locale != 'en_US')
            $ans = psl_getLocalinfo($category, $keyword, 'en_US');
         else
            $ans = $keyword;
      }
      # debug("$fc: answer",$ans);
      return $ans;
   }


   /**
    * format time, with keyword lookup and adjustment from system timezone to
    * user timezone
    *
    * @param string strftime format string OR localinfo LC_TIME keyword
    * @param integer timestamp
    * @return formatted string
    */
   function psl_strftime($fmt, $t = false) {
      global $_PSL;

      $oldlocale = setlocale(LC_TIME, 0);

      $locale = $_PSL['locale']['LC_TIME'];

      setlocale(LC_TIME, $locale);

      $fmt = psl_getLocalinfo('LC_TIME', $fmt);

      $strftime = (isset($_PSL['timezone']['engine']) && $_PSL['timezone']['engine']) ? 'tz_strftime' : 'strftime';

      $ans = $strftime($fmt, $t);

      setlocale(LC_TIME, $oldlocale);

      return $ans;

   }


   /**
    * parse date/time, with adjustment from user timezone to system timezone
    *
    * @param string date/time string
    * @return integer timestamp
    * @link http://www.php.net/strtotime
    */
   function psl_strtotime($str) {
      GLOBAL $_PSL;
      // loadLibrary("tz");
      $oldlocale = setlocale(LC_TIME, 0);
      $locale = $_PSL['locale']['LC_TIME'];
      setlocale(LC_TIME, $locale);
      $strtotime = ($_PSL['timezone']['engine'] ? 'tz_strtotime' : 'strtotime');
      $ans = $strtotime($str);
      setlocale(LC_TIME, $oldlocale);
      return $ans;
   }


   /**
    * return current date/time array in user timezone
    *
    * @param integer timestamp (defaults to current time)
    * @param boolean whether to return as associative array
    * @return array  simple or (if $is_assoc==true) associative array
    *                like the C tm structure
    * @link http://www.php.net/localtime
    */
   function psl_localtime($t = false, $is_assoc = 0) {
      GLOBAL $_PSL;
      // loadLibrary("tz");
      $oldlocale = setlocale(LC_TIME, 0);
      $locale = $_PSL['locale']['LC_TIME'];
      setlocale(LC_TIME, $locale);
      $localtime = ($_PSL['timezone']['engine'] ? 'tz_localtime' : 'localtime');
      $ans = $localtime($t, $is_assoc);
      setlocale(LC_TIME, $oldlocale);
      return $ans;
   }


   function psl_dateLong($t = false) {
      return psl_strftime('date_format_long', $t);
   }

   function psl_dateShort($t = false) {
      return psl_strftime('date_format_short', $t);
   }

   function psl_time12($t = false) {
      return psl_strftime('time_format_12', $t);
   }

   function psl_time24($t = false) {
      return psl_strftime('time_format_24', $t);
   }

   function psl_dateTimeLong($t = false) {
      return psl_strftime('date_time_format_long', $t);
   }

   function psl_dateTimeShort($t = false) {
      return psl_strftime('date_time_format_short', $t);
   }


   /**
    * function setLang returns languagefile
    *
    * If argument "new_lang" is set, attempt is made to use it for languagefile.
    *
    * Otherwise, if browser setting available, attempt to match appropriate
    * languagefile.
    *
    * default to using default languagefile. [$_PSL[languagefile]
    *
    * @param string new_lang
    *
    * @access public
    *
    * @return string lang
    */
   function setLang($new_lang = '') {
      global $_PSL;

      // If setLang has already been called, return that value
      if (empty($new_lang) && !empty($_PSL['setLang'])) {
         return $_PSL['setLang'];
      }

      # debug('http_accept_language', $_SERVER['HTTP_ACCEPT_LANGUAGE'], 'delayed');

      // start with default
      $lang = $_PSL['languagefile'];

      // current lang is default language unless changed below.
      $_PSL['lang'] = $_PSL['language'];

      if (!empty($new_lang)) {
         // directly set language
         if (file_exists($_PSL['localedir'] . '/' . basename($new_lang))) {
            $lang = $_PSL['localedir'] . '/' . basename($new_lang);
            $_PSL['lang'] = str_replace('.php', '', basename($new_lang));
         } elseif (file_exists($_PSL['localedir'] . '/' . basename($new_lang) . '.php')) {
            $lang = $_PSL['localedir'] . '/' . basename($new_lang) . '.php';
            $_PSL['lang'] = basename($new_lang);
         }
         // debug("setLang newlang=$new_lang",$lang);
      } elseif(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && $_SERVER['HTTP_ACCEPT_LANGUAGE']) {
	      // else try to get from browser
         $lang_ary = split("[,;]", $_SERVER['HTTP_ACCEPT_LANGUAGE']);
         $i = 0;
         $done = false;
         $count = count($lang_ary);
         while (($i < $count) AND (!$done) ) {
            $base_lang = split("[_-]", trim($lang_ary[$i]));
            # debug("base_lang = $base_lang[0]", $lang_ary[$i]);
            if (file_exists($_PSL['localedir'] . '/' . trim($lang_ary[$i]) . '.php')) {
               $lang = $_PSL['localedir'] . '/' . trim($lang_ary[$i]) . '.php';
               $_PSL['lang'] = trim($lang_ary[$i]);
               $done = true;
            } elseif (file_exists($_PSL['localedir'] . '/' . $base_lang[0]. '.php')) {
               // no dialect match - so try base language ( 'en' for 'en-US')
               $lang = $_PSL['localedir'] . '/' . $base_lang[0] . '.php';
               $_PSL['lang'] = $base_lang[0];
               $done = true;
            }
            ++$i;
         }

      }

      # debug("Returned $lang", $lang,'delayed');

      $_PSL['setLang'] = $lang;

      return $lang;
   }


   /**
    * function setLangTpl returns language template directory
    *
    * If argument "new_lang" is set, attempt is made to use it for
    * setting language template directory.
    *
    * Otherwise, if browser setting available, attempt to match appropriate
    * languagefile template directory.
    *
    * default to using default templatedir. [$_PSL[templatedir]
    *
    * @param string new_lang
    *
    * @access public
    *
    * @return string templatedir
    */
   function setLangTpl($new_lang = '') {
      global $_PSL, $_BE;
      static $templatedir;

      // Make sure this is only called once
      if (isset($templatedir) && !empty($templatedir)) {
         return $templatedir;
      }

      // start with default
      $templatedir = $_PSL['templatedir'];

      if ($new_lang) {
         // directly set language
         if (@is_dir($templatedir . '/' . basename($new_lang))) {
            $templatedir = $templatedir . '/' . basename($new_lang);
         }
      } elseif(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && $_SERVER['HTTP_ACCEPT_LANGUAGE']) {
         // else attempt to get from browser
         $lang_ary = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
         $i = 0;
         $done = false;
         while (($i < count($lang_ary)) AND (!$done) ) {
            if (strlen($lang_ary[$i]) > 0) {
               $base_lang = split("[_-]", trim($lang_ary[$i]));
               if (@is_dir($_PSL['templatedir'] . '/' . trim($lang_ary[$i]))) {
                  $templatedir = $_PSL['templatedir'] . '/' . trim($lang_ary[$i]);
                  $done = true;
               } elseif (@is_dir($_PSL['templatedir'] . '/' . $base_lang[0])) {
                  $templatedir = $_PSL['templatedir'] . '/' . $base_lang[0];
                  $done = true;
               }
            }
            ++$i;
         }

         if (!$done) {
            // no browser pref
            $templatedir .= '/' . basename($_PSL['language']);
         }

      } else {
         // no browser pref
         $templatedir .= '/' . basename($_PSL['language']);
      }

      # debug("Returned $templatedir",$templatedir,'delayed');
      return $templatedir;
   }


      /**
       * Look for an action of the form "submit_XYZ" in HTTP form parameters
       * The 'submit' parameter, which normally is the name of the main submit button,
       * will recieve the value of the XYZ
       * This allows to check which submit button was pressed, based on its name/id rather than value,
       * which is language dependent.
       * @param array ref: the CGI parameters
       * @return the value of the submit action
       */
      function decodeAction(&$ary) {

        // Legacy code to check for old submits & $_GET requests
        if (isset($ary['submit']) && $ary['submit']) {
           return pslgetText($ary['submit'],'',true);
        }

        // Check for new action specific submit links
        if(isset($ary) && is_array($ary)) {
           foreach ($ary as $param => $value) {
             if (substr($param,0,7) == 'submit_' && $value) {
               $ary['submit'] = substr($param,7);
               return substr($param,7);
             }
           }
        }

        // No valid submit links.
        return null;
      }


   /**
    * function setSkinTpl returns skin template directory
    *
    * If argument "new_skin" is set, attempt is made to use it for
    * setting skin template directory.
    *
    * Otherwise, if browser cookie available, attempt to match appropriate
    * skin template directory.
    *
    * new_skin = directory name that contains the .tpl files.
    * method   = method for phpslash to remember skin setting.
    [""] (blank) default is to only change skin for this pageview.
    "cookie" - set a cookie with skin choice
    *
    * "skin" directories are assumed to be subdirectories of the original
    * "templatedir".
    *
    * default to using default templatedir. [$_PSL[templatedir]
    *
    * Also currently attempts to append the skinname to imageurl
    *
    *
    * @param string new_skin
    *
    * @param string method
    *
    * @access public
    *
    * @return string templatedir
    */
   function setSkinTpl($new_skin = '', $method = '') {
      global $_PSL;

      // start with default
      $templatedir = $_PSL['templatedir'];

      // no directory traversal
      if ($new_skin == '..') {
         $new_skin = $_PSL['defaultskin'];
      }
      if ($new_skin) {


         if ($method == 'userpref') {
            // remove skin name from templatedir path
            $templatedir = substr($templatedir, 0, strrpos($templatedir, '/') );
         }

         // check if skin templatedir exists
         if (@is_dir($templatedir . '/' . basename($new_skin))) {
            if ($method == 'cookie') {
               setcookie('skin', "$new_skin", time()+15552000, '/', str_replace('www.','', $_SERVER['SERVER_NAME']));
               if (strstr($_SERVER['QUERY_STRING'], '&skin=')) {
                  $query_string = substr_replace ($_SERVER['QUERY_STRING'], '', strpos($_SERVER['QUERY_STRING'], '&skin='));
               } elseif(strstr($_SERVER['QUERY_STRING'], 'skin=')) {
                  $query_string = substr_replace ($_SERVER['QUERY_STRING'], '', strpos($_SERVER['QUERY_STRING'], 'skin='));
               } else {
                  $query_string = '';
               }
               $_SERVER['QUERY_STRING'] = $query_string;
               if ($query_string != '') {
                  $query_string = '?' . $query_string;
               }
               header('Location: '.$_PSL['phpself'].$query_string);
            }
         } elseif (@is_dir($templatedir . '/' . basename($_PSL['defaultskin']))) {
            $new_skin = $_PSL['defaultskin'];
            // delete the cookie to reset to default skin
            if (!empty($_COOKIE['skin'])) {
               setcookie('skin', '', time()+15552000, '/', ereg_replace('www.', '', $_SERVER['SERVER_NAME']));
            }
         }

      } elseif (!empty($_COOKIE['skin'])) {
         if (@is_dir($templatedir . '/' . clean($_COOKIE['skin']))) {
            $new_skin = clean($_COOKIE['skin']);
         } else {
            // dir not found, delete this cookie and set to default
            setcookie('skin', '', time()+15552000, '/', ereg_replace('www.', '', $_SERVER['SERVER_NAME']));
            $new_skin = $_PSL['defaultskin'];
         }
      } else {
         $new_skin = $_PSL['defaultskin'];
      }

      // keep up with what skin is assigned
      $_PSL['skin'] = $new_skin;

      // append skin
      $templatedir .= '/' .$new_skin;

      return $templatedir;
   }


   /**
    * function set_TZ - setup time zone environment
    *
    * To function, requires that safe_mode not preclude setting the TZ environment variable
    *
    * @param string string for new time zone
    * @param string '' for no saving or "cookie" to save a cookie
    * @access public
    *
    * @return name of the time zone set, or false if no environment variable is set.
    */
   function set_TZ($new_tzname = '', $method = '') {
      global $_PSL;
      $fn = 'set_TZ';
      $default_TZ = '';
      $host = '';

      // debug("$fn(\"$new_tzname\",\"$method\")","begin");

      // default: value in _PSL array.
      if (!empty($_PSL['timezone']['name'])) {
         $default_TZ = $_PSL['timezone']['name'];
      }
      if (!$default_TZ && !empty($_PSL['timezone']['native']) && !empty($_PSL['timezone']['default'])) {
         $default_TZ = $_PSL['timezone']['default'];
      }
      // start with cookie value if it's set
      $cookie_TZ = clean(@$_COOKIE['TZ']);
      if (!empty($cookie_TZ)) {
         $tzname = $cookie_TZ;
      }
      // override if an argument is passed.
      if ($new_tzname)
         $tzname = $new_tzname;
      // check it.  tz_select returns a success value.
      if (empty($tzname) || !tz_select($tzname)) {
         /* Try the default.  Yes, I *do* want a single equals there.  If
          *  $default_TZ is true, this will succeed. */
         if ($tzname = $default_TZ) {
            if (!tz_select($tzname)) {
               // complain if the default is set, because it's bad.
               echo getError("specified default time zone is invalid: $default_TZ");
               return false;
            }
         } else {
            // given timezone is bad and no default, so just exit quietly
            return false;
         }
      }
      //$host = str_replace('www.','',$_SERVER['SERVER_NAME']);
      if (('cookie' == $method) && ($tzname != $cookie_TZ)) {
         // save the cookie
         setcookie('TZ', $tzname, time() + 15552000, '/', $host);
      } elseif (!empty($cookie_TZ) && $tzname != $cookie_TZ) {
         // you gave me a bad cookie; I need to delete it
         // setcookie('TZ','','','/',$host);
         setcookie('TZ');
      }
      return $tzname;
   }


   /**
    * function str_html - returns a "html/ascii" string
    *
    * The string is converted to its ascii html equivalent
    *
    * "nospam" becomes "&#110;&#111;&#115;&#112;&#97;&#109;"
    *
    * @param string string
    *
    * @access public
    *
    * @return string ordhtml
    */
   function str_html($string) {
      if ($string != '') {
         $ordhtml = "&#" . implode(';&#', unpack('C*char', $string)) .";";
         return $ordhtml;
      } else {
         return false;
      }
   }


    /**
    * function pslgetAllPerms returns an array of all perms/groups
    *
    * @access public
    *
    * @return array perms_ary
    */
   function pslgetAllPerms() {
      global $_PSL;

      if (isset($_PSL['perm_array']) && is_array($_PSL['perm_array'])) {
         return $_PSL['perm_array'];

      } else {
         # debug("pslgetAllPerms", "groups");

         // Default Expiry Time
         $secondsFromNow = 14000;
         $cacheExpiryDate = date('Y-m-d H:i:s', time()+$secondsFromNow);

         // Initialize fileCache
#         $cacheObject = pslNew('fileCache', 'allGroups', '', $cacheExpiryDate);
         $cacheObject = new fileCache('allGroups', '', $cacheExpiryDate);

         // Try to retrieve cached value
         $cachedGroups = unserialize($cacheObject->retrieve());

         if (!empty($cachedGroups)) {
            $_PSL['perm_array'] = $cachedGroups;
            return $_PSL['perm_array'];
         }

         // Retrieve values from database
         $db = & pslNew('BEDB');

         $q = '
            SELECT group_name, group_id
            FROM psl_group
            ORDER BY group_name';
         $db->query($q);

         while ($db->next_record()) {
            $perm_name = $db->f('group_name');
            $_PSL['perm_array'][$perm_name] = $db->f('group_id');
         }

         // store cached values
         $cacheObject->store(serialize($_PSL['perm_array']));

         /*
          debug("pslgetAllPerms", "sections");
          $q = "SELECT section_id
          FROM psl_section
          ORDER BY section_name";
          $db->query($q);
          */ /*
         while ($db->next_record()) {
            $perm_name = $db->f('section_id');
            $_PSL['perm_array'][$perm_name] = true;
         }

         debug('pslgetAllPerms', "perms");
         $q = "SELECT permission_name
            FROM psl_permission
            ORDER BY permission_name";
         $db->query($q);

         while ($db->next_record()) {
            $perm_name = $db->f('permission_name');
            $_PSL['perm_array'][$perm_name] = true;
         }
          */
         # debug("pslgetAllPerms::_PSL['perm_array']", $_PSL['perm_array']);

         return $_PSL['perm_array'];

      }
   } // end pslgetAllPerms


   /**
    * emailNotify - send email Notification
    *
    *
    * @access public
    * @param $ary -
    *    ary['tpl'] = template to use ( no .tpl extenstion or path)
    *    ary['vars'] = additional variables to parse ( HTTP_POST_VARS)
    *    ary['mail_to'] = optional mail to name
    *    ary[mail_to_address'] = option mail to address
    *
    *    These variables are also available to the template:
    *       SITE_NAME, SITE_OWNER, ROOTURL, and REMOTE_ADDR
    *
    * @return success state
    */
   function emailNotify($ary) {

      global $REMOTE_ADDR, $_PSL;

      if (!is_array($ary)) {
         return false;
      }

      $template = pslNew('slashTemplate'); //, $_PSL['templatedir'], 'remove'); // NEw copy

      if (!$template->set_file('emailnotify' , basename($ary['tpl']) . '.tpl' )) {
         return false;
      }

      $template->set_var(array(
      'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'],
         'ROOTURL' => 'http://' . $_PSL['rootsubdomain'] . $_PSL['rootdomain'] . $_PSL['rooturl'],
         'SITE_HOST' => 'http://' . $_PSL['rootsubdomain'] . $_PSL['rootdomain'] . $_PSL['rooturl'],
         'MAILTONAME' => @$ary['mail_to'],
         'MAILTOADDRESS' => @$ary['mail_to_address'],
         'SITE_NAME' => $_PSL['site_name'],
         'SITE_OWNER' => $_PSL['site_owner']
      ));

      // let the template parse any tag variables submitted.
      if (is_array($ary['vars'] )) {
         foreach (@$ary['vars'] AS $key => $val) {
            $template->set_var(strtoupper($key), $ary['vars'][$key]);
         }
      }

      // parse the to_block to the mail_to variable
      $template->set_block('emailnotify', 'to_block', 'mail_to');
      $template->parse('mail_to', 'to_block', true);

      $mail_to = trim($template->get_var('mail_to'));
      # debug('mail_to', $mail_to);

      // parse the subject_block to the mail_subject variable
      $template->set_block('emailnotify', 'subject_block', 'mail_subject');
      $template->parse('mail_subject', 'subject_block', true);

      $mail_subject = trim($template->get_var('mail_subject'));
      # debug('mail_subject', $mail_subject);

      // parse the headers_block to the mail_headers variable
      $template->set_block('emailnotify', 'headers_block', 'mail_headers');
      $template->parse('mail_headers', 'headers_block', true);

      $mail_headers = trim($template->get_var('mail_headers'));
      # debug('mail_headers', $mail_headers);

      // parse the body_block to the mail_body variable
      $template->set_block('emailnotify', 'body_block', 'mail_body');
      $template->parse('mail_body', 'body_block', true);

      $mail_body = $template->get_var('mail_body');
      # debug('mail_body', $mail_body);

      if (mail($mail_to, $mail_subject, $mail_body, $mail_headers)) {
         // $this->message = 'Email Confirmation sent';
         $success = true;
      } else {
         // $this->message = 'Email Confirmation failure';
         $success = false;
      }
      return $success;
   }


/**
* displayOptions - display option fields
*
* @access private
* @param string module - author, block, story, etc.
* @param object tpl_object - template object
* @param string template - template variable used in set_file
* @param array options_ary - previously stored options name => value
* @param $BE_params - additional parameters array[
*            'languageID' => 'xx' - Use language-sensitive options
*            'total'      => n  - Override optioncount - 1 = don't add that extra option at the end
*
* @return tpl_object
*/
function displayOptions($module, $tpl_object='', $template='', $options_ary, $BE_params = NULL) {

   global $_PSL;

   if(!is_object($tpl_object)) {
      $tpl_object = & pslNew('slashTemplate');
   }

   // set options template
   if (isset($BE_params['languageID'])) {
      $templateFile = (!empty($template)) ? $template : 'BE_optionTypesLang.tpl';
      $tpl_object->set_file('optionfile', $templateFile);
      $tpl_object->set_var('SEL_LANGUAGE_ID', $BE_params['languageID']);
   } else {
      $templateFile = (!empty($template)) ? $template : 'optionTypes.tpl';
      $tpl_object->set_file('optionfile', $templateFile);
   }

   // which module's options?
   $default_options = 'default_'.$module.'_options';
   $optioncount = $module.'_optioncount';
   $optioncnt = 0;
   # debug('optioncount', $_PSL[$optioncount]);
   // available default option types
   $dtype_ary = array( 'text',
                       'radio',
                       'select');

   // initialize array and count
   if(count($options_ary) == 0) {
      $options_ary = '';
   }

   if(!empty($_PSL[$default_options])) {
      $optioncnt = count($_PSL[$default_options]);
      debug('optioncnt', $optioncnt);
   }

   // set default option type blocks
   foreach($dtype_ary as $key) {
      $tpl_object->set_block('optionfile','type_'.$key, 'option'.$key);
      # debug('set_block', 'type_'.$key);
      # debug('set_block', 'option'.$key);

   }

   // process default options
   for ($i =0; $i < $optioncnt; ++$i) {

      if(is_array($options_ary)) {
         reset($options_ary);
      }

      // get data from default option array
      $dname = $_PSL[$default_options][$i]['name'];
      $ddescription = isset($_PSL[$default_options][$i]['description']) ? $_PSL[$default_options][$i]['description'] : '';
      $dvalue = $_PSL[$default_options][$i]['value'];
      $dtype = $_PSL[$default_options][$i]['type'];
      if (empty($dtype)) $dtype = 'text'; //$_PSL[$default_options][$i]['type'];
      $dchoices = '';
      # debug("default_option", $_PSL[$default_options][$i]);
      if(array_key_exists('choices', $_PSL[$default_options][$i])) {
         $dchoices = $_PSL[$default_options][$i]['choices'];
      }
      # debug("varvals", $tpl_object->varvals);
      # debug("varkeys", $tpl_object->varkeys);

      # debug("get_var", "option".$dtype);
      $thistype = $tpl_object->get_var('type_'.$dtype);
      # debug("thistype", $thistype);
      $tpl_object->set_var('optiontype', $thistype);
      // $tpl_object->varvals['optiontype'] = $thistype;
      # debug('optiontype',$tpl_object->varvals['optiontype'] );

      // set html form blocks
      @$tpl_object->set_block('optiontype', 'each_default_selected', 'dselected');
      @$tpl_object->set_block('optiontype', 'each_default_notselected', 'dnotselected');
      @$tpl_object->set_block('optiontype', 'each_default_choice', 'dchoices');
      @$tpl_object->set_block('optiontype', 'each_default_option', 'doptions');

      // is there a saved value for this option?
      if(is_array($options_ary) && array_key_exists($dname, $options_ary)) {
         // yes - load the saved value
         $dvalue = $options_ary[$dname];
      }
      # debug("dvalue", $dvalue);
      $tpl_object->set_var(array(
         'DOPTION_NAME'        => 'optionname[]',
         'DOPTION_NAME_VAL'    => $dname,
         'DOPTION_DESCRIPTION' => pslgetText($ddescription)
      ));

      // option choices
      if(is_array($dchoices)) {
         // choices available
         reset($dchoices);

	 // show current option value as selected/checked
         $tpl_object->set_var(array(
            'DOPTION_VALUE'       => "optionval[]",
            'DOPTION_VALUE_VAL'   => $dvalue,
            'DOPTION_VALUE_LABEL' => $dvalue
         ));

	      // If the current option value is one of the choices, show that label
         if(array_key_exists($dvalue,$dchoices)) {
            $tpl_object->set_var('DOPTION_VALUE_LABEL', $dchoices[$dvalue]);
	 }
         $tpl_object->parse("dchoices", "each_default_selected", true);

	      // display the choices
         foreach($dchoices as $key => $value) {
            $tpl_object->set_var(array(
               'DOPTION_VALUE'     => "optionval[]",
               'DOPTION_VALUE_VAL' => $key,
	            'DOPTION_VALUE_LABEL' => $value
            ));
            // selected or not
            if($dvalue == $key ) {
               // $tpl_object->parse("dchoices", "each_default_selected", true);
            } else {
               $tpl_object->parse("dchoices", "each_default_notselected", true);
            }
               $tpl_object->parse("dchoices", "each_default_choice", true);
         }
      } else {
         // no choices
         $tpl_object->set_var(array(
            'DOPTION_VALUE'     => "optionval[]",
            'DOPTION_VALUE_VAL' => $dvalue
         ));
      }
      if(is_array($options_ary) && array_key_exists($dname, $options_ary)) {
         // option processed - remove it from queue.
         // debug("unset", $dname);
         unset($options_ary[$dname]);
      }
      debug("parse", $dname);
      $tpl_object->parse("doptions", "each_default_option", true);

      $tpl_object->set_var(array(
         'selected' => '',
         'notselected' => '',
	      'dchoices' => ''
      ));
   }

   $tpl_object->set_block('optiontype','each_option', 'options');
   if (is_array($options_ary)) {
      reset($options_ary);
   }
   if(array_key_exists($optioncount, $_PSL)) {
      $optioncnt = $_PSL[$optioncount];
   }
   debug(__LINE__.'optioncnt', $optioncnt);
   $i = 1;

   if (is_array($options_ary)) {
      $countOptions = count($options_ary);
      for ($i =1; $i <= $countOptions ; ++$i) {
         $current=each($options_ary);
         // debug("current", $current);

	 if ($current) {
            $tpl_object->set_var(array(
               'OPTION_NAME'      => 'optionname[]',
               'OPTION_NAME_VAL'  => $current[0],
               'OPTION_VALUE'     => 'optionval[]',
               'OPTION_VALUE_VAL' => $current[1]
            ));
         }
	      $tpl_object->parse('options', 'each_option', true);
      }
      if( (count($options_ary) - $optioncnt) >= 0) {
         $optioncnt = $i;  // spit out one more;
      }
   }

   // BACK_END ADDITION - allow for case where extra options are not wanted
   if (isset($BE_params['total'])) {
      $i = $BE_params['total']+1;
   }
   debug(__LINE__.'i', $i);
   while ($i <= $optioncnt) {
      debug('i', $i);
      $tpl_object->set_var(array(
         'OPTION_NAME'      => 'optionname[]',
         'OPTION_NAME_VAL'  => '',
         'OPTION_VALUE'     => 'optionval[]',
         'OPTION_VALUE_VAL' => ''
      ));
      $tpl_object->parse('options', 'each_option', true);
      ++$i;
   }

   $options = $tpl_object->parse('OUT', 'optiontype');
#   debug("options", $options);

   return $options;

} // end of function displayOptions


/**
* parse_upload - parse file uploaded via forms
*
* @access private
* @param string $file_path path to file
*
* @return string
*/
function parse_upload($file_path){
   // Based on function from PSL 0.8 by  Luis M
   // - currenly dont try to replace control chars (text is cleaned up later)
   $data = file($file_path);
   if (!$data) {
      $binary_junk = "ERROR: Missing filename or filename not found. PATH: $file_path";
   } else {
      $binary_junk = implode("\n",$data);
   }

/*
    $fp = @fopen($file_path,"r"); // is file here?

    if ($fp) {
        $binary_junk = fread($fp, @filesize($file_path));
    } else {
        // this should call debug() here
        $binary_junk = "ERROR: Missing filename or filename not found. PATH: $file_path";
    }
    // you may want to do a lot of nice things to the text
    // before returning it... but, let's leave it as is for now
#    $binary_junk = ereg_replace("((\015\012)|(\015)|(\012))","<br />\n",$binary_junk);
#    $binary_junk = utf8_encode($binary_junk);
*/
    return $binary_junk;
}

?>
