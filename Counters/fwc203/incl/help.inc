<?php
echo '<h1>Help</h1><h2><b>Signing Up</b></h2>
<p><b>How do I create a new account?</b><br>
Accounts are created by clicking &quot;Sign Up Now!&quot; on the front page. From
there you are required to enter a username, password, your real name and a valid
email address. The username and password are case-sensitive and can only contain
the following characters: &quot;a-z&quot;, &quot;0-9&quot;, &quot;.&quot; and
&quot;_&quot;. The password must have a length greater then 4 characters.</p>
<p><b>What does missing fields mean?</b><br>
If you receive &quot;Missing fields&quot; error, you have not filled in a text
box correctly. Click &quot;Back&quot; to make the correction.</p>
<p><b>I keep getting &quot;Invalid username&quot; or &quot;Invalid
password&quot;.</b><br>
Your username and password can only contain the following characters:
&quot;a-z&quot;, &quot;0-9&quot;, &quot;.&quot; and &quot;_&quot;. The password
must have a length greater then 4 characters.</p>
<p><br></p>
<h2>Logging On</h2>
<p><b>I forgot my password, what can I do?<br>
</b>For maximum security, your password is encrypted with your password.
Basically this means, '.$title.' will not let you in until you have entered a
valid password that matches the password in our database. Since '.$title.' is an
open source project, anyone would have the potential to download the source code
and figure out a way to steal passwords. All you can do is request the site
admin manually change your password.</p>
<p><b>Why do I get &quot;Invalid username/password&quot;?<br>
</b>You have either entered a username incorrectly or your password incorrectly.
For security purposes, we cannot tell you which one is correct or incorrect.</p>
<p><b>What does &quot;Time out&quot; error mean?<br>
</b>You will only receive this message if your doing something your not suppose
to do or are somewhere your not suppose to be.</p>
<p><b>Why do I get &quot;Your IP address has changed&quot; errors?<br>
</b>When you logon to your account, your username and IP Address are logged.
Every time you access a page from the Account Control Panel, '.$title.' matches
your IP Address to your logged IP Address. If your IP Address does not match the
logged IP Address. The system will automatically boot you.</p>
<p>The only times you may receive this message is when your Internet connection
is terminated or when your attempting to view someone else\'s Account Control
Panel.</p>
<p><br></p>
<h2>Account Control Panel</h2>
<p><b>How do I create a new counter?<br>
</b>New counters can be created by clicking &quot;Create New Counter&quot; at
your Account Control Center. If the admin has chosen to limit the amount of
counters per account, you will see the amount of counters you have remaining in the
Edit Counter Settings frame above the site ID select box. If you have reached
the maximum counters, you will get a error.</p>
<p><b>How do I change my password, name and email address</b>?<br>
In your Account Control Panel, click &quot;Edit Account Settings&quot;.</p>
<p><b>Where do I view my counter statistics?<br>
</b>Under &quot;Manage Counters&quot; you will find a button labeled
&quot;Statistics&quot;. Click it. Once you choose the site ID on the following page,
'.$title.' will retrieve your stats and display them in a graph and table. Your
graph and table may not be visible until the second day.</p>
<p><b>How do I delete a counter?<br>
</b>Counters can be deleted by selecting the site ID you wish to delete under
&quot;Edit Counter Settings&quot;, and clicking Delete. Once you confirm counter
deletion, the counter will be gone forever. If you have no active counters, you
will not be able to see the Edit, Delete or HTML Code buttons.</p>
<p><b>I want to change my counter count or style.<br>
</b>Choose a site ID under &quot;Edit Counter Settings&quot; and click Edit. The
following page will display the current counter settings. By clicking &quot;Save
Settings&quot;, your settings are changed immediately.</p>
<p><b>How do I get the HTML code for my counter?<br>
</b>Under &quot;Edit Counter Settings&quot;, choose a site ID and click
&quot;HTML Code&quot;. Go to #HTML Code for more information on inserting the
code into your web pages. </p>
<p><b>Why did I get a &quot;Time out&quot; error while I was logged in?<br>
</b>This means you have been booted by our system. '.$title.' automatically
deletes loggon information periodically to keep our system clean. Not a major problem, just login
again and your back in business.</p>
<p><b>Do I need to logout?<br>
</b>It is HIGHLY recommended that you logout when your finished. If not, your
account will be vulnerable to hacking attempts.</p>
<p><br></p>
<h2>HTML Code</h2>
<p><b>How do I get the HTML code for my counter?<br>
</b>In your Account Control Center, under &quot;Edit Counter Settings&quot;, 
choose a site ID and click&quot;HTML Code&quot;.</p>
<p><b>How do I insert the code into my web page?<br>
</b>1. You will need to open the web page in a text editor.<br>
2. Copy the code from '.$title.'.<br>
3. Scroll to the position where you want the counter.<br>
4. Paste the code.<br>
5. Save the web page.<br>
6. Your done! Try it out see if it works.</p>
<p><br></p>
<h2>Counter Details</h2>
<p><b>How do I make the counter details private?<br>
</b>All counter details are public. However, you can change your name and email
from the Account Control Panel.</p>';
?>