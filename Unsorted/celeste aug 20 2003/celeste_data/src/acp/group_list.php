<?php
/**
 * Celeste Project Source File
 * Celeste 2003 1.1.3 Build 0811
 * Aug 11, 2003
 * Celeste Dev Team - Lvxing / Y10k
 *
 * Copyright (C) 2002 celeste Team. All rights reserved.
 *
 * This software is the proprietary information of celeste Team.
 * Use is subject to license terms.
 */

$acp->newFrm('Group List');

$acp->newTbl('Group List');
$acp->newRow2('Title [ Name ]', 'View Forums', 'Create Topics', 'Upload', 'CE Tags', 'HTML', 'Delete Topics', 'Edit Topics', 'Move Topics', 'Edit Posts', 'Delete Posts', 'Rate Posts', 'Set Elite', 'Announce', 'Admin');

$rs = $DB->query("SELECT * FROM celeste_usergroup ORDER BY usergroupid ASC");

while($ug = $rs->fetch()) {


  $acp->newMenuRow('<a href="'.$_SERVER['PHP_SELF'].'?prog=group::edit&ugid='.$ug['usergroupid'].'">'.$ug['title'].'</a> [ '.$ug['groupname'].' ] ', getIndicatePic($ug['allowview']), getIndicatePic($ug['allowcreatetopic']), getIndicatePic($ug['allowupload']), getIndicatePic($ug['allowcetag']), getIndicatePic($ug['allowhtml']), getIndicatePic($ug['deltopic']), getIndicatePic($ug['edittopic']), getIndicatePic($ug['movetopic']), getIndicatePic($ug['editpost']), getIndicatePic($ug['deletepost']), getIndicatePic($ug['rate']), getIndicatePic($ug['elite']), getIndicatePic($ug['announce']), getIndicatePic($ug['admin']));

} // end of 'while($ug = $rs->fetch()) {'
