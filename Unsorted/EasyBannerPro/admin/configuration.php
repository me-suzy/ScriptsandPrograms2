<?PHP

#################################################
##                                             ##
##              Easy Banner Pro                ##
##       http://www.phpwebscripts.com/         ##
##       e-mail: info@phpwebscripts.com        ##
##                                             ##
##                 Version 2.8                 ##
##             copyright (c) 2003              ##
##                                             ##
##  This script is not freeware nor shareware  ##
##    Please do no distribute it by any way    ##
##                                             ##
#################################################


include("./common.php");
include('./_head.txt');

check_session ('config');
if ($HTTP_POST_VARS) ok($HTTP_POST_VARS);
form();

#################################################################################
#################################################################################
#################################################################################

function form() {
global $info;
include("../data/data.php");

for ($x=1;$x<=3;$x++)
{ $ratio[$x] = $s["ratio$x"]*100;
  $q = dq("select catname,catid from $s[pr]categories where size = $x",1);
  while ($categories = mysql_fetch_row($q))
  { if ($categories[1]==1) $kategorie[$x] = $categories[0];
    else $kategorie[$x] .= ",$categories[0]"; }
}
while (list($k,$v) = each ($s))
{ $s[$k] = ereg_replace ("[\]",'',$v);
  $s[$k] = htmlspecialchars($s[$k]);
  if (!$s[$k]) unset($s[$k]);
}

echo $info;
?>
<span class="text13blue"><b>Configuration</b><br></span>
<span class="text13">Never use backslash (\) in any of your values<br>
If you already have some users and need to change a value marked by * you must use function 'Fix ads' to update ads of all users<br></span>
<br>
<form method="POST" action="configuration.php">
<table border="0" width="98%" cellspacing="2" cellpadding="4" class="table1">
<tr>
<td colspan=2 align="center"><span class="text13blue"><b>Your License</b></span><br>
<span class="text13">If you don't remember these data, <a target="_blank" href="http://www.phpwebscripts.com/scripts/owner.php">click here</a> to find them out</span></td></tr>
<tr>
<td align="left"><span class="text13">Your username at PHPWebScripts.com members area</span></td>
<td align="left"><INPUT class="field1" maxLength=15 size=15 name="p_user" value="<?PHP echo $s[p_user]; ?>"></td>
</tr>
<tr>
<td align="left"><span class="text13">Your password at PHPWebScripts.com members area</span></td>
<td align="left"><INPUT class="field1" maxLength=15 size=15 name="p_pass" value="<?PHP echo $s[p_pass]; ?>"></td>
</tr>
<tr>
<td align="left"><span class="text13">Name of the domain you purchased license for</span><br><span class="text10">Let it blank if you have an unlimited license</span></td>
<td align="left"><INPUT class="field1" maxLength=255 size=60 name="p_domain" value="<?PHP echo $s[p_domain]; ?>"><br>
<span class="text10">Correct: mydomain.com<br>Wrong: www.mydomain.com, http://mydomain.com/</span></td>
</tr>
<tr>
<td colspan=2 align="center"><span class="text13blue"><b>Mysql Database Data</b></span></td></tr>
<tr>
<td align="left"><span class="text13">Database host</span></td>
<td align="left"><INPUT class="field1" maxLength=30 size=30 name="dbhost" value="<?PHP echo $s[dbhost]; ?>"></td>
</tr>
<tr>
<td align="left"><span class="text13">Database username</span></td>
<td align="left"><INPUT class="field1" maxLength=30 size=30 name="dbusername" value="<?PHP echo $s[dbusername]; ?>"></td>
</tr>
<tr>
<td align="left"><span class="text13">Database password</span></td>
<td align="left"><INPUT class="field1" maxLength=30 size=30 name="dbpassword" value="<?PHP echo $s[dbpassword]; ?>"></td>
</tr>
<tr>
<td align="left"><span class="text13">Name of your database</span></td>
<td align="left"><INPUT class="field1" maxLength=30 size=30 name="dbname" value="<?PHP echo $s[dbname]; ?>"></td>
</tr>
<tr>
<td align="left"><span class="text13">Password is not needed (It should be unchecked on 99% servers!)</span></td>
<td align="left">
<input type="checkbox" value="1" name="nodbpass"<?PHP if ($s[nodbpass]) echo ' checked'; ?>>
</td></tr>
<tr>
<td align="left"><span class="text13">Prefix of all tables</span></td>
<td align="left"><INPUT class="field1" maxLength=3 size=5 name="pr" value="<?PHP echo $s[pr]; ?>" disabled></td>
</tr>
<tr>
<td colspan=2 align="center"><span class="text13blue"><b>Global Settings</b></span></td></tr>
<tr>
<td align="left"><span class="text13">Full path to the folder where your scripts live. No trailing slash, no back slash.</span></td>
<td align="left"><INPUT class="field1" maxLength=100 size=60 name="phppath" value="<?PHP echo $s[phppath]; ?>"><br><span class="text10">Sample for Linux: /htdocs/sites/user/html/myexchangesystem<br>Sample for Windows: C:/somefolder/domain.com/myexchangesystem</span></td>
</tr>
<tr>
<td align="left" valign="top"><span class="text13">URL of your home page<br></span><span class="text10">Surfer gets this URL after clicking on your logo next to ads of your users</span></td>
<td align="left"><INPUT class="field1" maxLength=100 size=60 name="homepage" value="<?PHP echo $s[homepage]; ?>"><br><span class="text10">Sample: http://www.yourdomain.com/myexchangesystem/index.html<br>Never enter: http://www.yourdomain.com/myexchangesystem/index.php</span></td>
</tr>
<tr>
<td align="left"><span class="text13">URL of the directory where your php scripts are installed. No trailing slash. *</span></td>
<td align="left"><INPUT class="field1" maxLength=100 size=60 name="phpurl" value="<?PHP echo $s[phpurl]; ?>"><br><span class="text10">Sample: http://www.yourdomain.com/myexchangesystem/php</span></td>
</tr>
<tr>
<td align="left"><span class="text13">Your email address</span></td>
<td align="left"><INPUT class="field1" maxLength=70 size=60 name="adminemail" value="<?PHP echo $s[adminemail]; ?>"></td>
</tr>
<tr>
<td align="left"><span class="text13">Second email</span><br><span class="text10">Enter another admin email if you want to get all email messages to 2 addresses</span></td>
<td align="left"><INPUT class="field1" maxLength=200 size=60 name="adminemail1" value="<?PHP echo $s[adminemail1]; ?>"></td>
</tr>
<tr>
<td align="left"><span class="text13">Path to mysqldump</span><br><span class="text10">Optional need for database backup</span></td>
<td align="left"><INPUT class="field1" maxLength=50 size=60 name="sqldump" value="<?PHP echo $s[sqldump]; ?>"></td>
</tr>
<tr>
<td align="left"><span class="text13">If you delete any account but the user keeps the exchange code online, what should the system display on this place?</span></td>
<td align="left"><SELECT class="field1" size=1 name="after">
<OPTION value=1<?PHP if ($s[after]) echo ' selected' ?>>Display all ads as normally</option>
<OPTION value=0<?PHP if (!$s[after]) echo ' selected' ?>>Display only default ads (not ads of users)</option>
</select></td>
</tr>
<tr>
<td align="left"><span class="text13">Upload banners of your users to your server?</span><br><span class="text10">Yes means a better control over banners but also higher traffic for your server. *</span></td>
<?PHP if ($s[uploadban]==1) $uploadbanner="<OPTION value=1 selected>Yes, upload these to my server</option><OPTION value=0>No, leave them on servers of my users</option>"; else $uploadbanner="<OPTION value=1>Yes, upload it to my server</option><OPTION value=0 selected>No, leave it on servers of my members</option>"; ?>
<td align="left"><select class="field1" size=1 name="uploadban"><?PHP echo $uploadbanner; ?></select></td>
</tr>
<tr>
<td align="left"><span class="text13">Select where should be opened the target after a surfer clicks on an ad *<br><span class="text10">Valid only for pictures, not for a complete (raw) HTML code.</span></td>
<td align="left"><SELECT class="field1" size=1 name="target">
<OPTION value="_blank"<?PHP if ($s[target]=="_blank") echo ' selected' ?>>Open target in a new window</option>
<OPTION value="_top"<?PHP if ($s[target]=="_top") echo ' selected' ?>>Open target in the same window</option>
</select></td>
</tr>
<tr>
<td align="left"><span class="text13">Number of free credits for referring a new user.</span></td>
<td align="left"><INPUT class="field1" maxLength=5 size=5 name="forref" value="<?PHP echo $s[forref]; ?>"></td>
</tr>
<tr>
<td align="left"><span class="text13">These free credits will be added to account no.<br></span>
<span class="text10">Each user may have up to 3 accounts, please select into which of these 3 accounts should be added these free credits<br></span>
</td>
<td align="left"><span class="text13">
<input type="radio" name="whereref" value="1"<?PHP if ($s[whereref]==1) echo ' checked'; ?>> 1 &nbsp;&nbsp;&nbsp; 
<input type="radio" name="whereref" value="2"<?PHP if ($s[whereref]==2) echo ' checked'; ?>> 2 &nbsp;&nbsp;&nbsp; 
<input type="radio" name="whereref" value="3"<?PHP if ($s[whereref]==3) echo ' checked'; ?>> 3
</span></td></tr>
<tr>
<td align="left"><span class="text13">Add these free credits automatically or manually?<br></span>
<span class="text10">If automatically, the referring user gets free credits immediately once the new user joined. If manually, admin can decide if the free credits will be added or not.<br></span>
</td>
<td align="left"><span class="text13">
<input type="radio" name="aff_manually" value="0"<?PHP if (!$s[aff_manually]) echo ' checked'; ?>> Automatically &nbsp;&nbsp;&nbsp; 
<input type="radio" name="aff_manually" value="1"<?PHP if ($s[aff_manually]) echo ' checked'; ?>> Manually &nbsp;&nbsp;&nbsp; 
</span></td></tr>
<tr>
<tr>
<td align="left"><span class="text13">Which method should be used to find a random ad to display?<br>
<span class="text10">Method 1 is faster, however it does not work properly on some servers. If ads are not shown randomly, use method 2.<br></span>
</td>
<td align="left"><span class="text13">
<input type="radio" name="way2" value="0"<?PHP if (!$s[way2]) echo ' checked'; ?>> 1 &nbsp;&nbsp;&nbsp; 
<input type="radio" name="way2" value="1"<?PHP if ($s[way2]) echo ' checked'; ?>> 2
</span></td></tr>
<tr>
<td align="left"><span class="text13">Place day-by-day graphs</td>
<td align="left"><span class="text13">
<input type="radio" name="graph_vert" value="0"<?PHP if (!$s[graph_vert]) echo ' checked'; ?>> Horizontally&nbsp;&nbsp;&nbsp; 
<input type="radio" name="graph_vert" value="1"<?PHP if ($s[graph_vert]) echo ' checked'; ?>> Vertically
</span></td></tr>
<tr>
<td align="left"><span class="text13">Which date and time format should be used?</td>
<td align="left"><SELECT class="field1" name="ustime">
<?PHP if ($s[ustime]) $ustime="<OPTION value=1 selected>USA (2002-08-13, 3:25 pm)</option><OPTION value=0>Europe (13/8/2002, 15:25)</option>";
      else $ustime="<OPTION value=1>USA (2002-08-13, 3:25 pm)</option><OPTION value=0 selected>Europe (13/8/2002, 15:25)</option>";
echo $ustime; ?>
</select></td>
</tr>
<tr>
<td align="left"><span class="text13">If there is any difference between time on the server and your local time, write it here. Write only hours!</span></td>
<td align="left"><INPUT class="field1" maxLength=5 size=5 name="timeplus" value="<?PHP echo $s[timeplus]/3600; ?>"><span class="text10">Sample: Time on server is 8:00 but your local time is 10:00,<br>you will write number 2; time on server is 10:00 but your local time is 8:00, you will write number -2</span></td>
</tr>
<tr>
<td align="left"><span class="text13">Allow banners in flash (swf) format?</span><br>
<span class="text10">Please read note about flash banners in the Manual.</span></td>
<td align="left">
<input type="checkbox" value="1" name="flash"<?PHP if ($s[flash]) echo ' checked'; ?>>
</td></tr>
<tr>
<td align="left"><span class="text13">Allow users to enter a plain (raw) html as their ad code (not recommended) </td>
<td align="left">
<input type="checkbox" value="1" name="raw"<?PHP if ($s[raw]) echo ' checked'; ?>></td></tr>
<tr>
<td align="left"><span class="text13">Get an email when a new user joined your exchange system </td>
<td align="left">
<input type="checkbox" value="1" name="inew"<?PHP if ($s[inew]) echo ' checked'; ?>></td></tr>
<tr>
<td align="left"><span class="text13">Get an email when an user updated users details </td>
<td align="left">
<input type="checkbox" value="1" name="ichange"<?PHP if ($s[ichange]) echo ' checked'; ?>></td></tr>
<tr>
<td align="left"><span class="text13">Automatically accept new users</span><br><span class="text10">If not checked, each new user has to be approved by admin. Unaccepted users can show ads and earn credits but their ads are not shown.</span></td>
<td align="left">
<input type="checkbox" value="1" name="a_accept"<?PHP if ($s[a_accept]) echo ' checked'; ?>>
</td></tr>
<tr>
<td align="left"><span class="text13">Get an email when an user added or updated an ad </td>
<td align="left">
<input type="checkbox" value="1" name="iad"<?PHP if ($s[iad]) echo ' checked'; ?>>
</td></tr>
<tr>
<td align="left"><span class="text13">Enable all accounts which have ad(s) ready</span><br><span class="text10">If checked, each account will be enabled immediately once it has at least one ad adjusted. Otherwise if an ad is modified, the account will be disabled to let admin review these changes.</span></td>
<td align="left">
<input type="checkbox" value="1" name="adautoapr"<?PHP if ($s[adautoapr]) echo ' checked'; ?>>
</td></tr>
<tr>
<td align="left"><span class="text13">Enable all sponsored accounts which have ad(s) ready</td>
<td align="left">
<input type="checkbox" value="1" name="s_adautoapr"<?PHP if ($s[s_adautoapr]) echo ' checked'; ?>>
</td></tr>
<tr>
<td align="left"><span class="text13">Allow users enable/disable their accounts</span><br>
<span class="text10">Each account may be enabled/disabled by admin. By checking this box, you do allow users to disable their accounts (for example to save impressions for more intensive campaign) so all accounts will need to be anabled by both admin and user to show get their ads to the rotation.</span></td>
<td align="left">
<input type="checkbox" value="1" name="enablebyuser"<?PHP if ($s[enablebyuser]) echo ' checked'; ?>>
</td></tr>
<tr>
<td align="left"><span class="text13">Run the regular daily job automatically</span><br>
<span class="text10">Please read the Manual for more info.</span></td>
<td align="left">
<input type="checkbox" value="1" name="nocron"<?PHP if ($s[nocron]) echo ' checked'; ?>>
</td></tr>
<tr>
<td align="left"><span class="text13">Secret word<br></span><span class="text10">It is a "password" for your script 'rebuild.php' when doing its daily job. It may contain letters and numbers.</span></td>
<td align="left"><INPUT class="field1" maxLength=15 size=15 name="secretword" value="<?PHP echo $s[secretword]; ?>"></td>
</tr>
<tr>
<td align="left"><span class="text13">Top.php is a list of members with the highest number of impressions for the current month. Set how many users should this script show.</span></td>
<td align="left"><INPUT class="field1" maxLength=7 size=7 name="top" value="<?PHP echo $s[top]; ?>"></td>
</tr>
<tr>
<td align="left"><span class="text13">List.php is a list of all members and their ads. Set how many members should be listed on each page.</span></td>
<td align="left"><INPUT class="field1" maxLength=7 size=7 name="userlist" value="<?PHP echo $s[userlist]; ?>"></td>
</tr>
<tr><td align="left"><span class="text13">Maximum number of incoming impressions from one IP address to count for each user daily (anti-cheat protection)</span></td>
<td align="left">
<INPUT class="field1" maxLength=10 size=7 name="count_ip" value="<?PHP echo $s[count_ip]; ?>">
</td></tr>
<tr><td align="left"><span class="text13">Show only a default ad if the number above was reached</span><br>
<span class="text10">If some user reaches the number of incoming impressions above, the system shows only a default ad on pages of this user. If not checked, ads will be displayed as normally, only the incoming hits will be not counted.</span></td>
<td align="left"><INPUT type="checkbox" value="1" name="def_only"<?PHP if ($s[def_only]) echo ' checked'; ?>>
</td></tr>
<tr>
<td align="left"><span class="text13">Default "advantage" value for new exchange accounts</span></td>
<td align="left"><select class="field1" name="def_adv">
<OPTION value="0"<?PHP if (!$s[def_adv]) echo ' selected' ?>>0</option>
<OPTION value="1"<?PHP if ($s[def_adv]==1) echo ' selected' ?>>1</option>
<OPTION value="2"<?PHP if ($s[def_adv]==2) echo ' selected' ?>>2</option>
<OPTION value="3"<?PHP if ($s[def_adv]==3) echo ' selected' ?>>3</option>
<OPTION value="4"<?PHP if ($s[def_adv]==4) echo ' selected' ?>>4</option>
<OPTION value="5"<?PHP if ($s[def_adv]==5) echo ' selected' ?>>5</option>
</select></td>
</tr>
<tr>
<td align="left"><span class="text13">Default "advantage" value for new sponsored accounts</span></td>
<td align="left"><select class="field1" name="def_adv_s">
<OPTION value="0"<?PHP if (!$s[def_adv_s]) echo ' selected' ?>>0</option>
<OPTION value="1"<?PHP if ($s[def_adv_s]==1) echo ' selected' ?>>1</option>
<OPTION value="2"<?PHP if ($s[def_adv_s]==2) echo ' selected' ?>>2</option>
<OPTION value="3"<?PHP if ($s[def_adv_s]==3) echo ' selected' ?>>3</option>
<OPTION value="4"<?PHP if ($s[def_adv_s]==4) echo ' selected' ?>>4</option>
<OPTION value="5"<?PHP if ($s[def_adv_s]==5) echo ' selected' ?>>5</option>
</select></td>
</tr>
<tr><td align="left"><span class="text13">Allow users to move impressions between sizes</span><br>
<span class="text10">Example: User earns impressions in size 1 but wants to use them in size 2. If checked, you must enter also a ratio below.</span></td>
<td align="left"><INPUT type="checkbox" value="1" name="allow_move"<?PHP if ($s[allow_move]) echo ' checked'; ?>>
</td></tr>
<tr>
<td align="left"><span class="text13">Ratio for moving impressions between sizes<br></span><span class="text10">Example: If you enter 2:4:5 and an user wants to move impressions from size 1, he/she gets for every 2 earned credits 4 impressions of size 2 or 5 impressions of size 3.</span></td>
<td align="left"><span class="text13">Size 1 : Size 2 : Size 3</span><br>
<INPUT class="field1" maxLength=5 size=5 name="move1" value="<?PHP echo $s[move1]; ?>"> :
<INPUT class="field1" maxLength=5 size=5 name="move2" value="<?PHP echo $s[move2]; ?>"> :
<INPUT class="field1" maxLength=5 size=5 name="move3" value="<?PHP echo $s[move3]; ?>">
</td>
</tr>
</TR></TBODY></TABLE>
<br>

<!-- ##################################################################### -->
 
<?PHP
for ($x=1;$x<=3;$x++)
{ ?>
  <table border="0" width="98%" cellspacing="2" cellpadding="4" class="table1">
  <tr>
  <td colspan=2 align="center"><span class="text13blue"><b>Banner Size <?PHP echo $x ?></b></span></td>
  </tr>
  <?PHP
  if ($x != 1) 
  { echo '<tr>
    <td align="left"><span class="text13">Use this ad size</td>
    <td align="left">
    <input type="checkbox" value="1" name="use'.$x.'"';
    if ($s["use$x"]) echo ' checked';
    echo '></td></tr>';
  }
  ?>
  <tr>
  <td align="left"><span class="text13">Width of the ad *</span></td>
  <td align="left"><INPUT class="field1" maxLength=4 size=5 name="w<?PHP echo $x ?>" value="<?PHP echo $s["w$x"]; ?>"></td>
  </tr>
  <tr>
  <td align="left"><span class="text13">Height of the ad *</span></td>
  <td align="left"><INPUT class="field1" maxLength=4 size=5 name="h<?PHP echo $x ?>" value="<?PHP echo $s["h$x"]; ?>"></td>
  </tr>
  <tr>
  <td align="left"><span class="text13">Exchange ratio<br></span><span class="text10">How many impressions gets every user for showing 100 ads.</span></td>
  <td align="left"><INPUT class="field1" maxLength=5 size=5 name="ratio<?PHP echo $x ?>" value="<?PHP echo $ratio[$x]; ?>"></td>
  </tr>
  <tr>
  <td align="left"><span class="text13">Apply this ratio to all current accounts.</span><br>
  <span class="text10">The new ratio will be applied to all accounts in this ad size except accounts which have banned sliding ratio. If you check this box, the ratio will be applied also to these accounts with individual settings.</span></td>
  <td align="left"><input type="checkbox" value="1" name="useratio<?PHP echo $x ?>"></td>
  </tr>
  <tr>
  <td align="left"><span class="text13">Allow sliding ratio<br></span></td>
  <td align="left"><input type="checkbox" value="1" name="sliding_r<?PHP echo $x ?>"<?PHP if ($s["sliding_r$x"]) echo ' checked'; ?>></td>
  </tr>
  <tr>
  <td align="left"><span class="text13">Impressions for click<br></span>
  <span class="text10">How many impressions (if any) have to get each account as a bonus for each click on pages of this account</span></td>
  <td align="left"><INPUT class="field1" maxLength=5 size=5 name="forclick<?PHP echo $x ?>" value="<?PHP echo $s["forclick$x"]; ?>"></td>
  </tr>
  <tr>
  <td align="left"><span class="text13">Apply this value to all current accounts.</span><br>
  <span class="text10">The new impressions for click value will be applied to all accounts in this ad size except accounts which have individual settings of impressions for click. If you check this box, the ratio will be applied also to these accounts with individual settings.</span></td>
  <td align="left"><input type="checkbox" value="1" name="useforclick<?PHP echo $x ?>"></td>
  </tr>
  <tr>
  <td align="left"><span class="text13">Categories separated by comma<br></span><span class="text10">Leave blank for no categories.</span></td>
  <td align="left"><INPUT class="field1" size=60 name="cat<?PHP echo $x ?>" value="<?PHP echo $kategorie[$x]; ?>"><br><span class="text10">Sample: Category1,Category2,Category3</span></td>
  </tr>
  <tr>
  <td align="left"><span class="text13">Maximum size of members banners.<br></span><span class="text10">Note: The banner size is checked only if banners are stored on your server.</span></td>
  <td align="left"><INPUT class="field1" maxLength=10 size=10 name="bannermax<?PHP echo $x ?>" value="<?PHP echo $s["bannermax$x"]; ?>"> <span class="text13">bytes</td>
  </tr>
  <tr>
  <td align="left"><span class="text13">Number of free credits you want to give to every new account.</span></td>
  <td align="left"><INPUT class="field1" maxLength=5 size=5 name="freecredit<?PHP echo $x ?>" value="<?PHP echo $s["freecredit$x"]; ?>"></td>
  </tr>
  <tr>
  <td align="left"><span class="text13">Sponsored accounts - price</span><br><span class="text10">Numbers only. Sample: 10 or 12.56</span><br></td>
  <td align="left"><span class="text13">
  1000 impressions: <INPUT class="field1" maxLength=7 size=7 name="pr_imp<?PHP echo $x ?>" value="<?PHP echo $s["pr_imp$x"]; ?>">
  100 clicks: <INPUT class="field1" maxLength=7 size=7 name="pr_clicks<?PHP echo $x ?>" value="<?PHP echo $s["pr_clicks$x"]; ?>">
  </span></td>
  </tr>
  <tr><td colspan="2" align="center"><span class="text13blue">Logo<br></span>
  <span class="text10">Set up a logo which will be next to banners of your users. It may be useful to propagate your exchange system.
  If you decide to have the logo on the left or on the right of banners, height of the logo and height of ads must be the same.
  Conversely, if you decide to have the logo below or over banners, width of your logo and width of ads must be the same.
  </span></td></tr>
  <tr>
  <td align="left"><span class="text13">Height of the logo *<br><span class="text10">It must be set not depending if you have picture or text logo.</span></td>
  <td align="left"><INPUT class="field1" maxLength=4 size=5 name="logoh<?PHP echo $x ?>" value="<?PHP echo $s["logoh$x"]; ?>"></td>
  </tr>
  <tr>
  <td align="left"><span class="text13">Width of the logo *<br><span class="text10">It must be set not depending if you have picture or text logo.</span></td>
  <td align="left"><INPUT class="field1" maxLength=4 size=5 name="logow<?PHP echo $x ?>" value="<?PHP echo $s["logow$x"]; ?>"></td>
  </tr>
  <tr>
  <td align="left"><span class="text13">Where should be the logo?<br><span class="text10">It must be set not depending if you have picture or text logo.<br>It is based on the template you selected above.</span></td>
  <?PHP if ($s["logoleft$x"]) $kdejelogo="<OPTION value=1 selected>Left or right of ads</option><OPTION value=0>Below or over ads</option>"; else $kdejelogo="<OPTION value=1>Left or right of ads</option><OPTION value=0 selected>Below or over ads</option>"; ?>
  <td align="left"><SELECT class="field1" size=1 name="logoleft<?PHP echo $x ?>"><?PHP echo $kdejelogo; ?></select></td>
  </tr>
  <tr>
  <td align="center" colspan="2"><span class="text13">The logo may be a picture or a text (html). If you want to use a picture, fill in the following values.</span></td>
  </tr>
  <tr>
  <td align="left"><span class="text13">URL of the picture *</span></td>
  <td align="left"><INPUT class="field1" maxLength=70 size=60 name="logo<?PHP echo $x ?>" value="<?PHP echo $s["logo$x"]; ?>"></td>
  </tr>
  <tr>
  <td align="left"><span class="text13">URL which get a surfer by click on the logo (likely your home page) *</span></td>
  <td align="left"><INPUT class="field1" maxLength=70 size=60 name="logolink<?PHP echo $x ?>" value="<?PHP echo $s["logolink$x"]; ?>"></td>
  </tr>
  <tr>
  <td align="left"><span class="text13">Alt tag for the logo *</span></td>
  <td align="left"><INPUT class="field1" maxLength=70 size=60 name="logoalt<?PHP echo $x ?>" value="<?PHP echo $s["logoalt$x"]; ?>"></td>
  </tr>
  <tr>
  <td align="center" colspan="2"><span class="text13">If you want to use a text based logo, write the complete html to this field.<br>Advanced users may use it also for a logo based on a picture. *</span><br>
  <textarea class="field1" name="htmllogo<?PHP echo $x ?>" rows="15" cols="95"><?PHP if (!$s["logo$x"]) echo $s["htmllogo$x"]; ?></textarea></td>
  </tr>
  </TBODY></TABLE><br>
  <?PHP
}
?>

<!-- ##################################################################### -->

<INPUT type=submit value="Save all values" name=D1 class="button1">
</FORM>
</center>
<?PHP
include('./_footer.txt');
exit;
}

#################################################################################
#################################################################################
#################################################################################

function ok($in) {
global $s,$info;
$m = md5('ch'); // kvuli kontrole licence
if (!$in[dbhost]) $f_chyba .= "Mysql database host is missing.<br>\n";
if (!$in[dbname]) $f_chyba .= "Name of your mysql database is missing.<br>\n";
if (!$in[nodbpass])
{ if (!$in[dbusername]) $f_chyba .= "Mysql database username is missing.<br>\n";
  if (!$in[dbpassword]) $f_chyba .= "Password to mysql database is missing.<br>\n"; }
if ($f_chyba) problem("$f_chyba<br><br>Can't continue. Please go back and try again.");
$x = trim($m($in)); if ($x) $chyba[] = $m($in);
if (!$in[phppath]) $chyba[] = "Full path to your php folder.";
if (!eregi("^http://..*",$in[homepage])) $chyba[] = "URL of your site must begin with 'http://'";
if (!eregi("^http://..*",$in[phpurl])) $chyba[] = "URL of your php directory must begin with 'http://'";
if (!$in[adminemail]) $chyba[] = "Your email address.";
if (!$in[secretword]) $chyba[] = "Your 'secret word'.";

if (!$in[count_ip]) $in[count_ip] = 25;
if ($in[timeplus]) $in[timeplus] = $in[timeplus]*3600;
if (!$in[wait]) $in[wait] = 14;
if (!$in[whereref]) $in[whereref] = 1;

for ($x=1;$x<=3;$x++)
{ if ($in["cat$x"]) $in["usecats$x"] = 1; else $in["usecats$x"] = 0;  // musi byt tady - to usecats se musi nastavit vzdy
  if ( (($x==2) OR ($x==3)) AND (!$in["use$x"]) ) continue;
  if (!$in["w$x"]) $chyba[] = "Width of ad $x.";
  if (!$in["h$x"]) $chyba[] = "Height of ad $x.";
  if ($in["logo$x"])
  { if (!eregi("^http://..*",$in["logo$x"])) $chyba[] = "URL of your logo #$x must begin with http://.";
  if (!eregi("^http://..*",$in["logolink$x"])) $chyba[] = "URL which gets a surfer by click on the logo #$x must begin with http://.";
  }
  if ( (($in["logo$x"]) OR ($in["htmllogo$x"])) AND ((!$in["logow$x"]) OR (!$in["logoh$x"])) )
  $chyba[] = "Width and/or height of logo $x.";
  if (!$in["ratio$x"]) $chyba[] = "Exchange ratio #$x.";
  $in["ratio$x"] = $in["ratio$x"]/100;
  if ($in["logoleft$x"]) { $in["totalw$x"] = $in["w$x"] + $in["logow$x"]; $in["totalh$x"] = $in["h$x"]; }
  else { $in["totalw$x"] = $in["w$x"]; $in["totalh$x"] = $in["h$x"] + $in["logoh$x"]; }
  if ( ($in["logo$x"]) AND ($in["logolink$x"]) )
  $in["htmllogo$x"] = '<a target="'.$in[target].'" href="'.$in["logolink$x"].'"><img alt="'.$in["logoalt$x"].'" width='.$in["logow$x"].' height='.$in["logoh$x"].' border=0 src="'.$in["logo$x"].'">';
}

dq("DELETE FROM $s[pr]categories",1);
for ($x=1;$x<=3;$x++)
{ if ($in["cat$x"]) 
  { $categories = explode (",",$in["cat$x"]); reset ($categories);
    while (list($k,$v) = each ($categories)) 
    { $k++; $v = replace_once_text($v);
      $q = dq("insert into $s[pr]categories values('$x','$k','$v')",1);
    }
  }
  $ratio=$in["ratio$x"]; $oldratio=$s["ratio$x"];
  if ($in["useratio$x"]) dq("update $s[pr]stats$x set exratio = '$ratio'",1);
  else dq("update $s[pr]stats$x set exratio='$ratio' where no_slide = '0'",1);
  $forclick = $in["forclick$x"]; $oldforclick=$s["forclick$x"];
  if ($in["useforclick$x"]) dq("update $s[pr]stats$x set forclick = '$forclick'",1);
  else dq("update $s[pr]stats$x set forclick='$forclick' where forclick = '$oldforclick'",1);
}

$in[pr] = $s[pr];
unset ($in[submit],$in[D1],$in[cat1],$in[cat2],$in[cat3]);
reset ($in);
while (list($k,$v) = each($in)) 
{ $v = ereg_replace("[\]",'',$v);
  $v = ereg_replace("''","'",$v);
  $in[$k] = ereg_replace("'","\'",$v);
  if (!$in[$k]) $in[$k] = 0;
}
foreach ($in as $k => $v) $data .= "\$s[$k] = '$v';\n";
$data = "<?PHP\n
$data
?>";
if (!$sb = fopen("$in[phppath]/data/data.php","w")) problem("Cannot write to file '$in[phppath]/data/data.php'. Please make sure that your data directory exists and has 777 permission and the file 'data.php' inside has permission 666. Can't continue.");
$zapis = fwrite ($sb, $data,100000); fclose($sb);
if ($chyba)
$info = eot('Your configuration has been updated however there were some errors.<br>These fields are blank or contain wrong values:',join('<br>',$chyba));
else $info = iot('Your configuration has been successfully updated');
form();
}

#################################################################################


function hlaseni($text) {
echo "<span class=\"text13blue\">$text</span><br>";
}

#################################################################################
#################################################################################
#################################################################################


?>