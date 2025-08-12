<?php
include 'classes/HEADER.PHP';
include 'CNFG.php';
$head = "UPLOAD A CHARACTER FOR USE ON THE BACKGROUND";
myheader($head);



?>
<SCRIPT LANGUAGE='JavaScript'>
function id_type() {
   var FILE = document.LOADING.LOAD;
   if (FILE.value != ""){
      document.all.IMAGE.innerHTML = 'IMAGE FILE FOR ' + FILE.value;
      document.all.RECORD.innerHTML = 'NAME FOR ' + FILE.value;
      }
   }
</SCRIPT>
<FORM NAME='LOADING' METHOD='POST' ENCTYPE='multipart/form-data' ACTION='UL_PROCESSING.PHP'>
<TABLE ALIGN = CENTER>
<TR>
<TD>Upload image of:
</TD>
<TD>
<SELECT NAME='LOAD' onChange='id_type()'>
<OPTION SELECTED>SELECT ITEM TO UPLOAD</OPTION>
<OPTION VALUE='CHARACTER'>CHARACTER</OPTION>>
<OPTION VALUE='SPEAKER'>SPEAKER</OPTION>>
<OPTION VALUE='BACKGROUND'>BACKGROUND</OPTION>>
</SELECT>
</TD>
</TR>
<TR>
<TD><DIV ID="IMAGE"> </DIV>
</TD>
<TD>
<INPUT TYPE='FILE' NAME='IMG'></INPUT>
</TD>
</TR>
<TR>
<TD><DIV ID="RECORD"> </DIV>
</TD>
<TD>
<INPUT TYPE='TEXT' NAME='NAME'></INPUT>
</TD>
</TR>
<TR>
<TD COLSPAN=2>
<INPUT TYPE='SUBMIT' VALUE='UPLOAD IMAGE'></INPUT>
</TD>
</TR>
</TABLE>
</FORM>
</BODY>
</HTML>