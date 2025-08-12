<html><head><title>System information page</title>
<SCRIPT LANGUAGE="JavaScript">
<!--
function display() {
window.onerror=null;
colors = window.screen.colorDepth;
document.form.navigator.value = navigator.appName;
document.form.version.value = navigator.appVersion;
document.form.colordepth.value = window.screen.colorDepth;
document.form.width.value = window.screen.width;
document.form.height.value = window.screen.height;
document.form.codename.value = navigator.appCodeName;
document.form.platform.value = navigator.platform;
if (navigator.javaEnabled() < 1) document.form.java.value="No";
if (navigator.javaEnabled() == 1) document.form.java.value="Yes";
if(navigator.javaEnabled() && (navigator.appName != "Microsoft Internet Explorer")) {
vartool=java.awt.Toolkit.getDefaultToolkit();
addr=java.net.InetAddress.getLocalHost();
host=addr.getHostName();
ip=addr.getHostAddress();
alert("Your host name is '" + host + "'\nYour IP address is " + ip); 
   }
}
//--></script>
</head>
<body OnLoad="display()">
<?php global $hostname,$referral,$connection,$blah;$hostname=@gethostbyaddr($REMOTE_ADDR);?>
<form name=form><center>
<table border=0 width="98%">
<tr><td align="right">Current resolution:</td><td><input type=text size=4 maxlength=4 name=width> x <input type=text size=4 maxlength=4 name=height></td></tr>
<tr><td align="right">Browser:</td><td><input type=text size=35 maxlength=20 name=navigator></td></tr>
<tr><td align="right">Version:</td><td><input type=text size=35 maxlength=20 name=version></td></tr>
<tr><td align="right">Color depth:</td><td><input type=text size=2 maxlength=2 name=colordepth> bit</td></tr>
<tr><td align="right">Code name:</td><td><input type=text size=15 maxlength=15 name=codename></td></tr>
<tr><td align="right">Platform:</td><td><input type=text size=15 maxlength=15 name=platform></td></tr>
<tr><td align="right">Java enabled:</td><td><input type=text size=3 maxlength=3 name=java></td></tr>
<tr><td colspan=2><script language="JavaScript"> var version = 1; </script><script language="JavaScript1.1"> var version = 1.1; </script><script language="JavaScript1.2"> var version = 1.2; </script><script language="JavaScript1.3"> var version = 1.3; </script><script language="JavaScript1.4"> var version = 1.4; </script><script language="JavaScript1.5"> var version = 1.5; </script><script language="JavaScript1.6"> var version = 1.6; </script><script language="JavaScript1.7"> var version = 1.7; </script><script language="JavaScript"> document.write('Your browser supports JavaScript ' + version); </script></td></tr>
<tr><td colspan=2>Your IP Address is: <?php echo "$REMOTE_ADDR";?></td></tr>
<tr><td colspan=2>Your Hostmask is (IP address in word format): <?php echo "$hostname";?></td></tr>
<tr><td colspan=2 align="center">This script is provided by <a href="http://www.therealms.net/" target="_blank">Douglas, TheRealms.net</a>.</td></tr>
</table></center></form>
</body></html>