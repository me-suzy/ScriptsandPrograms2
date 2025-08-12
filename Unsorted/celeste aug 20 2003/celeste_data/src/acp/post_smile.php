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

include( DATA_PATH.'/settings/smile.inc.php' );
import('modify_setting');

if(!empty($_POST['acpSubmit']) || !empty($_POST['import_submit'])) {

  if(!empty($_POST['acpSubmit'])) {
    $smileTagsIden = 'smileTags';
    $smileImgsIden = 'smileImgs';
    $smileTags = array();
    $smileImgs = array();
  } else {
    $smileTagsIden = 'importSmileTags';
    $smileImgsIden = 'importSmileImgs';
  }

  foreach($_POST[$smileTagsIden] as $k => $v) {
    if($v) {
      $smileTags[] = $v;
      $smileImgs[] = '<img src=images/smiles/'.$_POST[$smileImgsIden][$k].' border=0>';
    }
  }

  $m = new modify_setting( DATA_PATH.'/settings/smile.inc.php' );
  $m->cover();
  $m->setArray('smileTags', $smileTags);
  $m->setArray('smileImgs', $smileImgs);
  $m->save();

  /******************************************************************
   *  build SMILE HELP page
   */
  import('template');
  $t->preload('showsmiles');
  $t->preload('indi_smile');
  $t->retrieve();

  $showsmiles =& $t->get('showsmiles');
  $indi_smile =& $t->get('indi_smile');
  $t->setRoot($showsmiles);

  $smileTags = array();
  $smileImgs = array();
  include( DATA_PATH.'/settings/smile.inc.php' );
  foreach($smileTags as $key => $smileTag) {
    $indi_smile->set('smileTag', $smileTag);
    if (preg_match('/src\=http/i', $smileImgs[$key])) {
      $indi_smile->set('smileImg', $smileImgs[$key]);
    } else {
      $indi_smile->set('smileImg', str_replace('src=', 'src='.SET_FORUM_URL.'/', $smileImgs[$key]));
    }
    $indi_smile->parse(true);
  }
  $showsmiles->parseBlock('smile_list', $indi_smile);
  $OUTPUTPAGE = $showsmiles->parse();

  writetofile('./help/showsmiles.html', $OUTPUTPAGE);
  unset($OUTPUTPAGE);
  unset($indi_smile);
  unset($showsmiles);
  unset($t);

  /**
   *  end of 'build SMILE HELP page'
   ******************************************************************/
  acp_success_redirect('You have updated the smile tags successfully', 'prog=post::smile');

} else {

  $acp->newFrm('Smile Tags');
  $acp->setFrmBtn();

  $js_clear_all = '<script>function st_clear_all() {'.
                  '  var myform = this.document.forms[0];'.
                  '  for(i=0;i<myform.elements.length-1;i++){'.
                  '    myform.elements[i].value = "";'.
                  '  }'.
                  '}</script>';
  $acp->newTbl('Edit Smile Tags');
  $acp->newRow('Smile Tags'.$js_clear_all, 'Smile Images');
  $acp->newRow('<center>Leave the tag blank to remove a smile tag, or [<a href="javascript:st_clear_all();">CLEAR ALL</a>]</center>');

  $tag_imgs = array();
  $index = 0;
  if(isset($smileTags)) {
    foreach($smileTags as $key => $smileTag) {
      $index++;
      $img = str_replace('<img src=images/smiles/', '', $smileImgs[$key]);
      $img = str_replace(' border=0>', '', $img);
      $acp->newRow($acp->frm->get($acp->frm->frmText('smileTags['.$index.']', $smileTag, 30, 60)),
                   $acp->frm->get($acp->frm->frmText('smileImgs['.$index.']', $img, 30, 60)).$smileImgs[$key]);
      $tag_imgs[] = $img;
    }
  } else {
    $acp->newRow('<center>There is no censored words defined before.</center>');
  }

  $acp->newTbl('Add new smile tags');
  $index++;
  $acp->newRow($acp->frm->get($acp->frm->frmText('smileTags['.$index.']', '', 30, 60)),
               $acp->frm->get($acp->frm->frmText('smileImgs['.$index.']', '', 30, 60)));
  $index++;
  $acp->newRow($acp->frm->get($acp->frm->frmText('smileTags['.$index.']', '', 30, 60)),
               $acp->frm->get($acp->frm->frmText('smileImgs['.$index.']', '', 30, 60)));
  $index++;
  $acp->newRow($acp->frm->get($acp->frm->frmText('smileTags['.$index.']', '', 30, 60)),
               $acp->frm->get($acp->frm->frmText('smileImgs['.$index.']', '', 30, 60)));
  $index++;
  $acp->newRow($acp->frm->get($acp->frm->frmText('smileTags['.$index.']', '', 30, 60)),
               $acp->frm->get($acp->frm->frmText('smileImgs['.$index.']', '', 30, 60)));


  /**
   * import from 'images/smiles'
   */
  $index = 0;
  $acp->newFrm('Import Smile Tags');
  $acp->setFrmBtn('          Import !          ', 'import_submit');
  $acp->newTbl('Import smile tags');
  $acp->newRow('<center>Upload your image files to "images/smiles/" then import them here</center>');
  $dir = dir('./images/smiles');
  while($img = $dir->read()) {
    $ext = substr($img, -4);
    if($ext == '.gif' || $ext == '.jpg' || $ext == '.jpeg' || $ext == '.bmp' || $ext == '.png') {
      // image file
      if(!in_array($img, $tag_imgs)) {
        // not added yet
        $index++;
        $acp->newRow($img.'<img src=images/smiles/'.$img.' border=0>'.$acp->frm->get($acp->frm->frmHid('importSmileImgs['.$index.']', $img)), $acp->frm->get($acp->frm->frmText('importSmileTags['.$index.']', '[s:'.$img.']', 30, 60)));
      }
    }
  }
  $dir->close();


}
