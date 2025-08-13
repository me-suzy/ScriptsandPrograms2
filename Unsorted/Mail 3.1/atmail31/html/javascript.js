
sub javascript
{
print<<_EOF;
<script language="JavaScript">

function MM_popupMsg(theMsg) { //v2.0
  alert(theMsg);
}

function MM_displayStatusMsg(msgStr) { //v2.0
  status=msgStr;
  document.MM_returnValue = true;
}

</script>
</head>
_EOF
}
