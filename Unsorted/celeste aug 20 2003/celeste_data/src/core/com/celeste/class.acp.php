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

class frm_obj_resource {
  var $obj = array();
  var $autoFree = 1;


  function setAutoFree($autoFree = 1) {
    $this->autoFree = $autoFree;
  }
 
  /**
   * return a free resource id
   */
  function _genRid() {
    static $rid;
    return ++$rid;
  }

  /**
   * access resource
   */
  function get($rid) {
    if($this->autoFree) {
      $t = $this->obj[$rid];
      $this->free($rid);
      return $t;
    } else
      return $this->obj[$rid];
  }

  /**
   * release memory
   */
  function free($rid) {
    unset($this->obj[$rid]);
  }

  /**
   * Form Button
   */
  function frmBtn($title, $btnName = 'btn', $btnType = 'button', $event = '') {
    $rid = $this->_genRid();
    $this->obj[$rid] = "<input type='$btnType' value='$title' name='$btnName' $event>";
    return $rid;
  }

  /**
   * Form alternative options, just show 'yes or no'
   */
  function frmAnOp($fieldName, $default = 0) {
    $rid = $this->_genRid();
    if(1 == $default) {
      $this->obj[$rid] = "<input type='radio' name='$fieldName' value='1' checked class='checkboxAndRadio'> Yes &nbsp; &nbsp; &nbsp; <input type='radio' name='$fieldName' value='0' class='checkboxAndRadio'> No";
    } else {
      $this->obj[$rid] = "<input type='radio' name='$fieldName' value='1' class='checkboxAndRadio'> Yes &nbsp; &nbsp; &nbsp; <input type='radio' name='$fieldName' value='0' checked class='checkboxAndRadio'> No";
    }
    return $rid;
  }

  /**
   * Form text field
   */
  function frmText($fieldName, $default = '', $size = 60, $maxlength = 255, $event = '') {
    $rid = $this->_genRid();
    $this->obj[$rid] = "<input type='text' name='$fieldName' value='$default' size='$size' maxlength='$maxlength' $event>";
    return $rid;
  }

  /**
   * Form text area
   */
  function frmTextarea($fieldName, $default = '', $rows = 10, $cols = 60) {
    $rid = $this->_genRid();
    $this->obj[$rid] = "<textarea name='$fieldName' rows='$rows' cols='$cols'>$default</textarea>";
    return $rid;
  }

  /**
   * Form list selection
   * @para default set a default value, 0 for blank, 1 for 3rd arg, 2 for 4th arg...
   */
  function frmList($fieldName, $default = 0) {
    $num_args = func_num_args();
    if($num_args > 2) {
      $rid = $this->_genRid();
      $this->obj[$rid] = $default ? '' : "<option value='0'></option>";
      for($i = 2; $i < $num_args; $i++) {
        $tmp = func_get_arg($i);
        $this->obj[$rid] .= sprintf("<option value='%s' %s>%s</option>",
                                    $i-1, (($i-1)==$default ? 'selected' : ''), $tmp);
      }
      $this->obj[$rid] = "<select name='$fieldName'>". $this->obj[$rid] ."</select>";
    }
    return $rid;
  }

  function frmSpan($name_prefix, $appendix, $span_min = '', $span_max = '', $size = 10) {
    $rid = $this->_genRid();
    $this->obj[$rid] = $this->get($this->frmText($name_prefix.'_min', $span_min ? $span_min : '', $size));
    $this->obj[$rid].= $appendix.' to ';
    $this->obj[$rid].= $this->get($this->frmText($name_prefix.'_max', $span_max ? $span_max : '', $size));
    $this->obj[$rid].= $appendix;

    return $rid;
  }

  function frmDateSpan($name_prefix, $date_from = '', $date_to = '') {
    return $this->frmSpan($name_prefix, ' days ago ', $date_from, $date_to);
  }

  /**
   * Form hidden field
   */
  function frmHid($fieldName, $value) {
    $rid = $this->_genRid();
    $this->obj[$rid] = "<input type='hidden' name='$fieldName' value='$value'>";
    return $rid;
  }

  /**
   * text: string
   */
  function str($str) {
    $rid = $this->_genRid();
    $this->obj[$rid] = $str;
    return $rid;
  }


} // end of 'class obj_resource {'



class ACP {

  var $content;

  var $frmContent = '';
  var $frmHandle  = '';
  var $frmName    = '';
  var $frmEncType = '';
  var $frmTitle   = '';
  var $frmBtnName = '';
  var $frmBtnTitle= '';
  var $frmBtnEvent= '';
  var $num_tbls   = 0;
  var $num_forms  = 0;
  var $tbls       = array();

  // form resources
  var $frm;

  function ACP() {
    $this->frm = new frm_obj_resource;
  }

  /**
   * Form - level 2
   */
  function newFrm($frmTitle, $frmHandle = '', $frmName = 'acpFrm', $frmEncType = '') {
    if($this->num_forms > 0) {
      $this->_plotFrm();
      $this->num_forms--;
    }
    // init
    $this->frmTitle    = $frmTitle;
    $this->frmHandle   = $frmHandle;
    $this->frmName     = $frmName;
    $this->frmEncType  = $frmEncType;
    $this->frmBtnTitle = '';
    $this->frmBtnName  = '';
    $this->tbls        = array();
    $this->num_forms++;
  }

  function setFrmBtn($title = 'Submit !', $name = 'acpSubmit', $event = '') {
    $this->frmBtnTitle = $title;
    $this->frmBtnName  = $name;
    $this->frmBtnEvent = $event;
  }

  function _plotFrm() {
    if($this->num_tbls > 0) {
      $this->frmContent .= "</table></td></tr></table><p>";
      $this->num_tbls--;
    }

    $frmEncType   = ($this->frmEncType ? "enctype='$this->frmEncType'" : "");
    
    $this->content .= "<a name='$this->frmName'>";
    $this->content .= "<form action='$this->frmHandle' method='post' name='$this->frmName' $this->frmEncType>";
    $this->content .= "<table width=98% cellspacing=0 cellpadding=0 border=0><tr>";
    $this->content .= "<td> &nbsp; <font class='pagetitle'>$this->frmTitle</font></td></tr></table><hr><br>";

    if(count($this->tbls) > 2) {
      $nav = array();
      foreach($this->tbls as $tblName => $title) {
        $nav[] = "<a href='#$tblName' class='formmenu'>$title</a>";
      }
      $this->content .= '<center>'.join(' | ', $nav).'</center><p>';
    }

    $this->content .= $this->frmContent;
    $this->frmContent = '';

    if($this->frmBtnName) {
      /**
       * submit button
       */
      $this->content .= "<table width=96% cellspacing=2 cellpadding=4 border=0 align=center><tr><td><hr size=1 width=100%></td></tr>";
      $this->content .= "<tr><td><center><input type='submit' name='$this->frmBtnName' value='$this->frmBtnTitle' $this->frmBtnEvent></center>";
      $this->content .= "</td></tr></table>";
    }
    $this->content .= "</form><p><hr><p>";
  }

  /**
   * Table - level 3
   */
  function newTbl($title = 'Options', $tblName = 'tbl') {
    if($this->num_tbls > 0) {
      $this->frmContent .= "<tr><td colspan=32><hr size=1 width=100%></td></tr></table></td></tr><p>";
      $this->num_tbls--;
    }
    // --
    $this->tbls[$tblName] = $title;
    $this->frmContent .= "<a name='$tblName'>";
    $this->frmContent .= "<table width=96% cellspacing=2 cellpadding=4 border=0 align=center>";
    $this->frmContent .= "<tr><td colspan=32> <font class=tblTitle>$title</font> (<a href='#top'>top</a>) <hr size=1 width=100%></td></tr>";
    $this->num_tbls++;
  }

  /**
   * Row - level 4
   */
  function newRow($title, $field = 0, $description = '') {
    static $bgcolor;
    if(is_int($field) && $field == 0) {
      if($this->num_tbls > 0) {
        $this->frmContent .= sprintf("<tr bgcolor='%s'><td colspan=32>%s</td>",
                                        (1==++$bgcolor%2 ? '#ffffff' : '#F7F7F7'), $title);
      } else {
        $this->frmContent .= $title.' <br><br>';
      }
    } else {
      $description && $description = '<br>'.$description;
      $this->frmContent .= sprintf("<tr bgcolor='%s'><td width='40%%'><b>%s</b> : %s</td>",
                                      (1==++$bgcolor%2 ? '#ffffff' : '#F7F7F7'),
                                      $title, $description);
      $this->frmContent .= "<td>".(is_int($field) ? $this->frm->get($field) : $field)."</td></tr>";
    }
  }

  function newMenuRow() {
    $cols = func_get_args();
    foreach($cols as $key => $value) {
      if(is_int($value))
        $cols[$key] = $this->frm->get($value);
    }
    $this->frmContent .= '<tr bgcolor=#ffffff><td>'.join('</td><td>', $cols).'</td></tr>';
    $this->frmContent .= '<tr bgcolor=#ffffff><td colspan=32><hr size=1 width=100%></td></tr>';
  }

  function newRow2() {
    static $bgcolor;
    $cols = func_get_args();
    foreach($cols as $key => $value) {
      if(is_int($value))
        $cols[$key] = $this->frm->get($value);
    }
    $this->frmContent .= '<tr bgcolor='.(1==++$bgcolor%2 ? '#ffffff' : '#F3F3F3').'><td>'.join('</td><td>', $cols).'</td></tr>';
  }

  /**
   * plot, build output page
   */
  function plot() {

    $this->_plotFrm();
// print page header
?>
<html>
<head>
<link rel="stylesheet" href="images/acp/acp.css" type="text/css" />
<META content="text/html; charset=<?=SET_DEFAULT_CHARSET?>" http-equiv=Content-Type>
<title>Celeste Admin CP Component</title>
<body  text="#000000" link="#000000" vlink="#000000" alink="#000000" topmargin=0 leftmargin=0>
<?php
// end of 'print page header'

print $this->content;
?>
<p><center>
<font color=gray>Celeste Admin Control Panel - <a href="http://www.celestesoft.com" target=_blank>Celeste 2003</a></font>
</center>
</body>
</html>
<?php
  }

} // end of 'class ACP {'

?>