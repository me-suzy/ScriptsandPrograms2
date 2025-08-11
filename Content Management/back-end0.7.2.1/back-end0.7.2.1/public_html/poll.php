<?php
/**
* Allow users to view a list of Polls and their results
*
* Lists all polls in the site (ie not by section)
*
* Converted for multilingual code and other fixes - pac, March 2004
* - Redisplay poll if there's an error message?
*
* Admin changes
* - select language poll is to be shown in
* - merge templates
*
* @package     Back-End on phpSlash
* @copyright   2004 - Open Concept
* @version     $Id: poll.php,v 1.16 2005/06/09 17:34:47 mgifford Exp $
*
*/

require('./config.php');

$pagetitle     = pslgetText('Polls');
$xsiteobject   = pslgetText('Poll Booth'); //Defines The META TAG Page Type


if (!empty($_GET['login'])) {
   $auth->login_if($_GET['login']);
}

// Objects
$pollObj = pslNew('Poll');
$sectionObj = pslNew('BE_Section');

# debug('HTTP_POST_VARS', $_POST);
# debug('HTTP_GET_VARS', $_GET);

/* setting up the possible comment variables... */

/* the comment stuff is using 'story_id' so we have to funky
fix it here. */
if (empty($ary['question_id']) && !empty($ary['story_id'])) {
   $question_id = $ary['story_id'];
} elseif(!empty($ary['question_id'])) {
   if ($ary['question_id'] == 'current') {
      $question_id = $pollObj->getCurrent();
   } else {
      $question_id = $ary['question_id'];
   }
} else {
   $question_id = '';
}

if (!empty($ary['mode'])) {
   $cmtary['mode'] = $ary['mode'];
} else {
   $cmtary['mode'] = '';
}
if (!empty($ary['order'])) {
   $cmtary['order'] = $ary['order'];
} else {
   $cmtary['order'] = '';
}
$cmtary['question_id'] = $question_id;


// Process actions -------------------------------------------------------------------------------

$content = '';

   if (empty($ary['submit'])) {
      $ary['submit'] = '';
   } else {
      $ary['submit'] = pslgetText($ary['submit'], '', true);
   }

# debug('poll submit',$ary['submit']);
# debug('poll question_id',$question_id);

switch ($ary['submit']) {
case 'vote':
   if ($pollObj->vote($question_id, $ary['answer_id'], $_SERVER['REMOTE_ADDR'])) {
      $content .= pslgetText('VOTE: ') . $pollObj->message . "<br />\n";
   } else {
      $content .= getError($pollObj->getMessage());
   }
   /* NOTE:  there's no "break" here, cause after you vote, we
             roll down and "viewresults"
      pac -  It might be better if the code al
   */

case 'viewresults':

   /* we register the "return link" in case they post a comment */
   $return_link = $_SERVER['REQUEST_URI'];
   $sess->register('return_link');

   $content .= $pollObj->resultPage($cmtary);
   break;

case 'viewbooth':
   $content .= getTitlebar ('100%', 'View Pollbooth');
   $content .= "<div align=\"center\">\n";
   // getFancyBox($width='', $title='', $contents, $link = '', $box_type = '', $var_ary = '', $id = '', $ordernumLower=null, $ordernumHigher=null) {
   $content .= getFancybox(210, sprintf(pslgetText("%s Poll"), $_PSL['site_name']), $pollObj->getPollBooth($question_id), 'nc', 'headless');
   $content .= "</div>\n";
   break;

case 'list':
   $content .= $pollObj->listPolls ($ary['min']);
   break;

default:
   if ($question_id) {
      $content .= $pollObj->resultPage($cmtary);
   } else {
      if (empty($ary['min'])) {
         $ary['min'] = '';
      }
      $content .= $pollObj->listPolls ($ary['min']);
   }
}

# debug('poll content',strlen($content));

// Output result -------------------------------------------------------------------------------

$ary['section'] = 'Polls';
//  In subsites, we can't count on the petition section existing,
// so we'll just set it to the home section.
if(be_inSubsite()) {
   $ary['section'] = $BE_subsite['URLname'];
}

$chosenTemplate = getUserTemplates('', $ary['section']);

$breadcrumb = $sectionObj->breadcrumb($ary['section'], 'Polls', 'polls');

generatePage($ary, $pagetitle, $breadcrumb, $content); // $storyText);

page_close();

?>
