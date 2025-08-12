<?php
echo '<table align="right" border="0" bordercolor="#7F9FCD"><tr><td align="right"><b><script language="JavaScript" src="getcount.php?id=708f721b78"></script></b></td></tr></table><h1>Welcome to '.$title.'!</h1>
<table border=0 width="100%">
  <tr>
    <td align="left" rowspan=3 width="80%" class="h2">Get a free hit counter for your web site.
    Currently, <b>'.$previd.'</b> counters are served. People use them to get great statistics for their Web Pages.<br><br>
    <b>Why '.$title.'?</b>
    <li>Easy to read statistical graphs and tables.</li>
    <li>No ads are displayed on your website.</li>
    <li>Incredibly easy to use - no software to install, just a simple SCRIPT entry.</li>
    </td>
  </tr>
  <tr>
    <td align="right">Version <b>2.0.3</b><br><a href="setup.php">
    <img src="images/freegraph.gif" alt="Sign Up!" width="217" height="185" border="0"></a></td>
  </tr>
</table>
Counters can help you get a beat on what your users are doing. My web counter is truly the best Free 
Hit Counter that you will ever find.
<div align="center"><form action="login.php?act=login" method="post">
<table border=0 width="100%">
  <tr>
    <td align="left" class="boldtitle"><b>Username:<br><input type="text" name="usr" size=20></b></td>
    <td align="left" class="boldtitle"><b>Password:<br><input type="password" name="pwd" size=20></b></td>
    <td align="right"><input type="submit" class="button" name="button" value="Login"></td>
  </tr>
</table><b>Want to see a demo?</b> Login with the username, "<i>demo</i>" and the password, "<i>canada</i>".
</div>
</form>
[<b><a href="setup.php">Sign Up Now!</a></b>] 
[<a href="login.php">Login</a>] 
[<a href="details.php">View Counter Details</a>] 
[<a href="mailto:'.$contactaddress.'">Contact Admin</a>]<br>
[<a href="sitelist.php">Web Site Listings</a>] 
[<a href="index.php?p=help" target="_blank">Help!</a>]';
?>