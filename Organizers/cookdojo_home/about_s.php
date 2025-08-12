<?php
	$text_scroll = "<table width=60% align=center><tr><td class=normal>";
	$text_scroll .= "<img src=\"images/logo.jpg\">";
	$text_scroll .= "<br><br><b>Copyright &copy; Cookdojo.com 2000-". date('Y') .". All Rights Reserved.</b>";
	$text_scroll .= "<br><br>This program is released to public domain.  You are free to use it.";
	$text_scroll .= "<br>You may redistribute this code under the following  conditions:";
	$text_scroll .= "<br>1. You must distribute all of its original files.";
	$text_scroll .= "<br>2. Each file must be in its original condition (no modification). ";
	$text_scroll .= "<br>3. You may not make profit from the redistribution of this program.";
	$text_scroll .= "<br><br><br><br><b>SOFTWARE DISCLAIMER</b>";
	$text_scroll .= "<br><br>THIS SOFTWARE IS PROVIDED BY COOKDOJO.COM \"AS IS\" AND ANY EXPRESSED OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A  PARTICULAR PURPOSE ARE DISCLAIMED.  IN NO EVENT SHALL COOKDOJO.COM OR ITS CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR  SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.";
	$text_scroll .= "</td></tr></table>";
	
?>

<html>

<style type="text/css">
.normal {
    font-family : Verdana, Arial, Helvetica, sans-serif;
    font-size : 11px;
    color : #003366
}
</style>

<SCRIPT LANGUAGE="JavaScript">

var swidth=760;

var sheight=200;

var sspeed=1;
var restart=sspeed;
var rspeed=sspeed;

sbcolor="";

var singletext=new Array();
singletext[0]='<?php echo $text_scroll ?>';

if (singletext.length>1)ii=1;else ii=0;
function goup(){if(sspeed!=rspeed*8){sspeed=sspeed*2;restart=sspeed;}}
function godown(){if(sspeed>rspeed){sspeed=sspeed/2;restart=sspeed;}}
function start(){if (document.all){iens6div.style.top=sheight;iescroll(iens6div);}
else if (document.layers){document.ns4div.document.ns4div1.top=sheight;document.ns4div.document.ns4div1.visibility='show';ns4scroll(document.ns4div.document.ns4div1);}
else if (document.getElementById){document.getElementById('iens6div').style.top=sheight;ns6scroll(document.getElementById('iens6div'));}}
function iescroll(whichdiv){iediv=eval(whichdiv);sizeup=iediv.offsetHeight;if(iediv.style.pixelTop>0&&iediv.style.pixelTop<=sspeed){iediv.style.pixelTop=0;setTimeout("iescroll(iediv)",100);}if(iediv.style.pixelTop>=sizeup*-1){iediv.style.pixelTop-=sspeed;setTimeout("iescroll(iediv)",100);}else{iediv.style.pixelTop=sheight;iediv.innerHTML=singletext[ii];if(ii==singletext.length-1)ii=0;else ii++;}}
function ns4scroll(whichlayer){ns4layer=eval(whichlayer);sizeup=ns4layer.document.height;if(ns4layer.top>0&&ns4layer.top<=sspeed){ns4layer.top=0;setTimeout("ns4scroll(ns4layer)",100);}if (ns4layer.top>=sizeup*-1){ns4layer.top-=sspeed;setTimeout("ns4scroll(ns4layer)",100);}else{ns4layer.top=sheight;ns4layer.document.write(singletext[ii]);ns4layer.document.close();if(ii==singletext.length-1)ii=0;else ii++;}}
function ns6scroll(whichdiv){ns6div=eval(whichdiv);sizeup=ns6div.offsetHeight;if(parseInt(ns6div.style.top)>0&&parseInt(ns6div.style.top)<=sspeed){ns6div.style.top=0;setTimeout("ns6scroll(ns6div)",100);}if (parseInt(ns6div.style.top)>=sizeup*-1){ns6div.style.top=parseInt(ns6div.style.top)-sspeed;setTimeout("ns6scroll(ns6div)",100);}else{ns6div.style.top=sheight;ns6div.innerHTML=singletext[ii];if(ii==singletext.length-1)ii=0;else ii++;}}
</script>

</HEAD>


<BODY onLoad="start()" bgcolor="#D8DDDF">


<script language="JavaScript">if(document.layers){document.write('<ilayer id="ns4div" width='+swidth+' height='+sheight+' bgcolor='+sbcolor+'><layer id="ns4div1" width='+swidth+' height='+sheight+' onmouseover="sspeed=0;" onmouseout="sspeed=restart">');document.write(singletext[0]);document.write('</layer></ilayer>')}
if(document.getElementById||document.all){document.write('<div style="position:relative;overflow:hidden;width:'+swidth+';height:'+sheight+';clip:rect(0 '+swidth+' '+sheight+' 0);background-color:'+sbcolor+';" onmouseover="sspeed=0;" onmouseout="sspeed=restart"><div id="iens6div" style="position:relative;width:'+swidth+';">');document.write(singletext[0]);document.write('</div></div>');}</script>


</body></html>