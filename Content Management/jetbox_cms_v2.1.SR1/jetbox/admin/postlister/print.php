<?php
require("functions.php");
?>

<html><head>
<style type=text/css><!--
body { background: white; font-family: helvetica; font-size: 10pt }
--></style>
</head>



<body onLoad=self.print()>
<pre>

<script language=javascript><!--
afsender = opener.document.forms[0].skrivAfsender.value
emne = opener.document.forms[0].skrivEmne.value
tekst = opener.document.forms[0].skrivEbrev.value

afsender = afsender.replace(/</g, "&lt;")
afsender = afsender.replace(/>/g, "&gt;")
modtager = location.search.replace(/?/, "")
emne = emne.replace(/</g, "&lt;")
emne = emne.replace(/>/g, "&gt;")
tekst = tekst.replace(/</g, "&lt;")
tekst = tekst.replace(/>/g, "&gt;")

document.write("<b><?php echo $s52; ?></b> "+afsender+"<br>")
document.write("<b><?php echo $s66; ?></b> "+modtager+"<br>")
document.write("<b><?php echo $s53; ?></b> "+emne+"<hr>")
document.write(tekst)
// --></script>

</pre>
</body></html>