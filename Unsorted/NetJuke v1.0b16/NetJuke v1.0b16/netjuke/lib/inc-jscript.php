<?php

require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-lib_inc-common.php");

?>

if (self.name.substring(0,7).toLowerCase() != 'netjuke') self.name = 'NetjukeMain';

function SelectAll() {
   for (var i=0;i< document.playForm.elements.length;i++) {
      if (document.playForm.elements[i].name == 'val[]') {
         document.playForm.elements[i].checked = true;
      }
   }
}

function ResetAll() {
   for (var i=0;i< document.playForm.elements.length;i++) {
      if (document.playForm.elements[i].name == 'val[]') {
         document.playForm.elements[i].checked = false;
      }
   }
}

function Plist(mode) {
  if (mode == 'view') {
  
    if (document.viewForm.pl_id.options[document.viewForm.pl_id.options.selectedIndex].value != '') {
      return true;
    } else {
      alert('<?php echo  COMMON_PLIST_1 ?>');
      return false;
    }
  
  } else {
  
     var str = '';
     for (var i=0;i< document.playForm.elements.length;i++) {
        if (document.playForm.elements[i].name == 'val[]') {
           if (document.playForm.elements[i].checked == true) {
              if (str != '') { str = str + ','; }
              str = str + document.playForm.elements[i].value;
           }
        }
     }
     if (str != '') {
       if (mode == 'addto') {
         top.location.href = './pl-edit.php?do=addto&pl_id='
                             + document.viewForm.pl_id.options[document.viewForm.pl_id.options.selectedIndex].value
                             + '&val=' + str;
       } else {
         top.location.href = './play.php?do=play&val=' + str;
       }
     } else {
        alert("<?php echo  COMMON_NOTRACK ?>");
     }
  }
}

function BatchEdit(thisMode, thisForm, thisVal) {
  var str = '';
  for (var i=0;i< thisForm.elements.length;i++) {
    if (thisForm.elements[i].name == thisVal) {
      if (thisForm.elements[i].checked == true) {
        if (str != '') { str = str + ','; }
        str = str + thisForm.elements[i].value;
      }
    }
  }
  if (str != '') {
    if (thisMode == 'edit') {
      var newWin = window.open('<?php echo WEB_PATH?>/admin/tr-batch-edit.php?do=edit&val=' + str,'_blank','width=500,height=575,top=0,left=0,menubar=no,scrollbars=yes,resizable=yes');
      newWin.focus();
    } else {
      if (confirm('<?php echo  COMMON_BATCHEDIT_1 ?>')) {
        top.location.href = '<?php echo WEB_PATH?>/admin/tr-batch-edit.php?do=del_tr&val=' + str;
      }
    }
  } else {
    alert("<?php echo  COMMON_NOTRACK ?>");
  }
}

function TextPlistEdit(thisType, thisMode, thisForm, thisVal) {
  var str = '';
  if (thisType == 'radio') {
    var url = '<?php echo WEB_PATH?>/admin/radio-edit.php?do=edit&val=';
    var alertMsg = '<?php echo  COMMON_RADIOEDIT_1 ?>';
  } else {
    var url = '<?php echo WEB_PATH?>/admin/jukebox-edit.php?do=edit&val=';
    var alertMsg = '<?php echo  COMMON_JUKEBOXEDIT_1 ?>';
  }
  for (var i=0;i< thisForm.elements.length;i++) {
    if (thisForm.elements[i].name == thisVal) {
      if (thisForm.elements[i].checked == true) {
        if (str != '') { str = str + ','; }
        str = str + thisForm.elements[i].value;
      }
    }
  }
  if (str != '') {
    url = url + str;
    if (thisMode == 'replace') {
      if (confirm(alertMsg)) {
        top.location.href = url + '&replace=1';
      }
    } else {
        top.location.href = url;
    }
  } else {
    alert("<?php echo  COMMON_NOTRACK ?>");
  }
}
