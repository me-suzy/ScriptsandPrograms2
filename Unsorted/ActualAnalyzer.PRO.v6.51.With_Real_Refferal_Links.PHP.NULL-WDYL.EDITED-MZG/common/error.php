<?php

class error {

var $flag;
var $log;
var $mselog;
var $file;
var $fileold;
var $rf;

function error($rf) {
  $this->rf=$rf;
  $this->mselog=500000;    //max size of errors log
  $this->flag=false;
  $this->file=$rf.'errors.php';
  $this->fileold=$rf.'errsold.php';
}

//accumulate errors
/*-------------------------------------------------------*/
function reason($str) {
  if(!$this->flag) {
    $this->log=date("j F Y - H:i:s",time());
    $this->flag=true;
  }
  $this->log.='|'.$str;
}

//output errors to error.log
/*-------------------------------------------------------*/
function log_out() {
  if(file_exists($this->file)) {
    $exist=true;
    $fsize=filesize($this->file);
    if($fsize>($this->mselog/2)) {
      if(file_exists($this->fileold)) {
        if(!unlink($this->fileold)) {$err->reason('error.php|log_out|can\'t delete file '.$this->fileold);return;}
      }
      if(!rename($this->file,$this->fileold)) {$this->reason('error.php|log_out|can\'t rename '.$this->file.' to '.$this->fileold);return;}
      $exist=false;
    }
  }
  else $exist=false;
  $file=fopen($this->file,'a');
  if(!$file) {$this->reason('error.php|log_out|can\'t open the file '.$this->file);return;}
  flock($file,LOCK_EX);
  if(!$exist) {if(!fwrite($file,"<?php die('Access restricted. Click <a href=elog.php>here</a> for viewing the log of errors.');?>\n")) {$this->reason('error.php|log_out|can\'t write header into the file '.$this->file);return;}}
  if(!fwrite($file,$this->log."\n")) {$this->reason('error.php|log_out|can\'t attach errors to the file '.$this->file);return;}
  flock($file,LOCK_UN);
  fclose($file);
}

//output errors to screen
/*-------------------------------------------------------*/
function scr_out() {
  global $pagehtml;

  $vars = array();

  require $this->rf.'data/err_tpl.php';

  //array with error data
  $errarr = preg_split("/\|/",$this->log);

  $pagehtml='';
  $vars['TIME']=$errarr[0];
  tparse($top,$vars);

  //errors on levels
  $max=sizeof($errarr);
  $level=1;
  for($i=$max;$i>1;$i-=3) {
    $vars['FILE']=$errarr[$i-3];
    $vars['FUNCT']=$errarr[$i-2];
    $vars['DESC']=$errarr[$i-1];
    $vars['LEVEL']=$level;
    $level++;
    tparse($center,$vars);
  }

  tparse($bottom,$vars);

  //output HTML page
  out();

  exit;
}

//output error picture
/*-------------------------------------------------------*/
function scr_pic() {
  Header("Location: {$this->rf}data/error.gif");
  exit;
}

}

?>
