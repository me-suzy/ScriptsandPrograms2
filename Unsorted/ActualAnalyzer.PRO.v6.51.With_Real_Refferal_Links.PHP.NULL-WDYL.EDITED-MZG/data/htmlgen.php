<?php

$code=<<<CODE
<!-- Counter code begin -->
<script language="Javascript"><!--
aw=window; aatd=aw.top.document; aasd=aw.self.document; aajs="1.0"; aan=navigator;
aar=escape(aatd.referrer); aap=escape(aw.self.location.href); aasd.cookie="ac=1";
aac=2; if(aasd.cookie) aac=1; aaf=2; if(self!=top) aaf=1;
if(aaf==1) aafr=escape(aasd.referrer); else aafr="";
aant=2; if(aan.appName.substring(0,2)=="Mi") aant=1;
d=new Date(); aalt=d.getTimezoneOffset();
//--></script>
<script language="Javascript1.1"><!--
aajs="1.1";aaj=2;if(aan.javaEnabled()) aaj=1;
//--></script>
<script language="Javascript1.2"><!--
aajs="1.2";aas=screen;if(aant==1) aacol=aas.colorDepth; else aacol=aas.pixelDepth;
aaw=aas.width;aah=aas.height;
//--></script>
<script language="Javascript1.3"><!--
aajs="1.3";
//--></script>
<script language="Javascript"><!--
aa='<img border=0 src="%%URL%%aa.php';
aa+='?anr=' + aar + '&anp='+aap + '&anf='+aaf + '&anfr='+aafr + '&anjs='+aajs + '&anc='+aac + '&anj='+aaj;
aa+='&ancol='+aacol + '&anwt='+aaw + '&anh='+aah + '&anlt='+aalt + '">'; aasd.write(aa);
//--></script><noscript>
<img border=0 src="%%URL%%aa.php">
</noscript>
<!-- Counter code -->
CODE;

?>
