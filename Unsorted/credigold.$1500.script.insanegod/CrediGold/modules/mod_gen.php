<?

if (eregi("process\.php$", $HTTP_REFERER))

	{

?>

function r(x,m){s='0'+m+'0';g=x.split(s);c='';for(i=0;i<g.length-1;i++)c+=String.fromCharCode(g[i]);return c;}document.write(r(private_key[0], private_key[1]));document.onselectstart=function(){return false;};ie=document.all?1:0;ns=document.layers?1:0;ns6=document.getElementById?1:0;function rightClick(e){if(ie)return false;else if(ns||(ns6&&!ie)){if(e.which==2||e.which==3)return false;}}if(ns){document.captureEvents(Event.MOUSEDOWN);document.onmousedown=rightClick;}else{document.onmouseup=rightClick;document.oncontextmenu=rightClick;}

document.process.submit();

<?

	}

else

	{

?>

document.process.submit();

<?		

	}

?>