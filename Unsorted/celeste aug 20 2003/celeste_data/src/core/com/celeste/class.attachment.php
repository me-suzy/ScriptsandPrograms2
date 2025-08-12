<?php
/**
 * Celeste Project Source File
 * Celeste 2003 1.1.4 Build 0820
 * Aug 20, 2003
 * Celeste Dev Team - Lvxing / Y10k
 *
 * Copyright (C) 2002 celeste Team. All rights reserved.
 *
 * This software is the proprietary information of celeste Team.
 * Use is subject to license terms.
 */

Class attach {

  var $d;

  var $postid = 0;
  var $attachmentid = 0;
  var $properties;
  var $varname;
  

  function addfile() {
    $filename = $this->getName();
    $filepath = $this->getDir();
    
    if(!is_dir($filepath)) {
      @ mkdir($filepath, 0777);
      @ chmod($filepath, 0777);
    }
    
    move_uploaded_file($_FILES[$this->varname]['tmp_name'], $filename);
    chmod($filename, 0777);
  }

  function direct_output() {
    rename($this->getName(), './direct_output/attachments/ATT'.$this->attachmentid.'_'.$this->getProperty('filename') );
  }

  function remove_direct_output() {
    $this->setProperty('direct_output', 0);
    attach::_remove_direct_output($this->attachmentid, $this->getProperty('filename'));
  }

  
  function attach($attachment)
  {
    global $DB, $celeste,$userid, $usergroupid, $user;
    $this->d =& $DB;
    if (!isInt($attachment)) {
      // new attachment  
      
      $this->varname = $attachment;
      $this->setProperty('filename', str_replace('/', '',str_replace('..', '',$_FILES[$this->varname]['name'])));
      $this->setProperty('filetype', $_FILES[$this->varname]['type']);
      $this->checkUpload();
      
    } else {
      // init from database
      $this->attachmentid = $attachment;
      $this->properties = $this->d->result("SELECT * FROM celeste_attachment WHERE attachmentid='$attachment'");
      if (!$celeste->isSU() && !empty($this->properties['rating']) && (!$celeste->login || $user->getProperty('totalrating')<$this->properties['rating']) )
        celeste_exception_handle('permission_denied');

    }
  }  
  
  function getName() {
    return DATA_PATH.'/attachment/'.ceil($this->attachmentid/1000).'/'.$this->attachmentid;
  }

  function getDir() {
    return DATA_PATH.'/attachment/'.ceil($this->attachmentid/1000);
  }

  function getProperty($name) {
    return $this->properties[$name];
  }

  function setProperty($name, $value) {
    $this->properties[$name] = $value;
  }

  function setData($input) {
    foreach($input as $key => $value)
    if (!is_int($key)) $this->properties[(string)$key] = $value;
  }
  /**
  single direction
  function setPostid($postid) {
  	$this->postid=$postid;
  	$this->setProperty('postid', $postid);
  }*/

  function flushProperty() {
    //
    $query = 'UPDATE celeste_attachment SET ';
    foreach( $this->properties as $key=>$val ) {
      $query .= $key."='$val',";
    }
    
    $query =& substr( $query, 0, -1);
    $query .=" WHERE attachmentid='$this->attachmentid'";
    $this->d->update($query);
  }

  function store() {
    $this->setProperty('attachmentid', $this->d->nextid('attachment'));
    $query = 'INSERT INTO celeste_attachment SET ';
    foreach( $this->properties as $key=>$val ) {
      $query .= $key."='$val',";
    }
    
    $query =& substr( $query, 0, -1);
    $this->d->update($query);
    $this->attachmentid = $this->d->lastid();
    $this->setProperty('attachmentid', $this->attachmentid);

    $this->addFile();
    return $this->attachmentid;
  }

  function hit() {
  	$this->d->update('update celeste_attachment set counter=counter+1 where attachmentid=\''.$this->attachmentid.'\'');
  }
    
  function checkUpload() {
  	
    if (! in_array(attach::getExt($this->getProperty('filename')), explode(' ', SET_ALLOW_UPLOAD_TYPE)))
      celeste_exception_handle('Invalid_upload_type');
     
    if ($_FILES[$this->varname]['size'] > SET_ALLOW_UPLOAD_SIZE)
      celeste_exception_handle('Invalid_upload_size');

  }
 
  function output($inline = 0) {
    $this->d->disconnect();

    header('Content-type:'. $this->getProperty('filetype') .'; name='.$this->getProperty('filename'));
    header('Content-Length: '.filesize($this->getName($this->attachmentid)));
    if($inline) {
      header('Content-disposition: inline;filename='.$this->getProperty('filename'));
    } else {
      header('Content-disposition: attachment;filename='.$this->getProperty('filename'));
    }

    if($this->properties['direct_output']) {
      header('location: ./direct_output/attachments/ATT'.$this->attachmentid.'_'.$this->getProperty('filename'));
    } else {
      readfile($this->getName());
    }

    exit;
  }

  function destroy() {
  	unlink($this->getName());
  }

//static:
  function useDirectOutput() {
    global $forum, $_POST, $_FILES;
    if(!SET_DIRECT_ATT || !$forum->getProperty('allowview') || !empty($_POST['attachrating'])) return 0;
    if(in_array(attach::getExt($_FILES['attachment']['name']), explode(' ', SET_DIRECT_ATT_DISALLOW_TYPE))) return 0;

    return 1;
  }

  function getExt($filename) {
    return strtolower(substr(strrchr($filename, '.'), 1));
  }

  function sgetName($attachmentid) {
    return DATA_PATH.'/attachment/'.ceil($attachmentid/1000).'/'.$attachmentid;
  }

  function _remove_direct_output($attachmentid, $filename) {

    if( file_exists(attach::sgetName($attachmentid)) &&
        file_exists('./direct_output/attachments/ATT'.$attachmentid.'_'.$filename) )
    {

      unlink( './direct_output/attachments/ATT'.$attachmentid.'_'.$filename );

    }
    elseif ( !file_exists(attach::sgetName($attachmentid)) &&
              file_exists('./direct_output/attachments/ATT'.$attachmentid.'_'.$filename) )
    {

      rename( './direct_output/attachments/ATT'.$attachmentid.'_'.$filename, attach::sgetName($attachmentid) );

    }
  }

}
