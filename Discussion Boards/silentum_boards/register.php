<?
	/*
	Silentum Boards v1.4.3
	register.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	require_once("permission.php");
	require_once("settings.php");

	$error = "";
	$displaypage = 1;
	$user_counter = myfile('objects/id_users.txt'); $user_counter = $user_counter[0];

	if($config['enable_registration'] != 1) {
	include('board_top.php');
	echo navigation($txt['Navigation']['Registration_Deactivated'][0]);
	echo get_message('Registration_Deactivated');
	include("board_bottom.php");
	exit;
	}
	if($user_logged_in == 1) {
	include("board_top.php");
	echo navigation("<a href=\"index.php?page=register\">Register</a>\tAccess Denied");
	echo get_message('Already_Logged_In','<br /><br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	exit;
	}
	elseif($user_counter >= $config['max_registrations'] && $config['max_registrations'] != -1) {
	include('board_top.php');
	echo navigation($txt['Navigation']['Max_Registrations'][0]);
	echo get_message('Max_Registrations');
	}
	else {

	if($method == "register") {

	isset($_POST['newuser_name']) ? $new_name = nlbr($_POST['newuser_name']) : $new_name = "";
	isset($_POST['newuser_email']) ? $new_email = nlbr($_POST['newuser_email']) : $new_email = "";

	$password = chr(rand(ord("a"), ord("z"))).get_random_number(1).chr(rand(ord("A"), ord("Z"))).chr(rand(ord("A"), ord("Z"))).get_random_number(1).chr(rand(ord("a"), ord("z"))).chr(rand(ord("a"), ord("z"))).get_random_number(1).chr(rand(ord("A"), ord("Z"))).chr(rand(ord("a"), ord("z")));

	$new_mailname = mysslashes($new_name);

	if(trim($new_name) == "") $error = 'You must enter a user name.';
	elseif(check_name($new_name,-1) == 1) $error = 'That user name already exists. Please choose another one.';
	elseif(stristr($new_name, 'bitch')) $error = 'Your user name contains a banned word. Banned word: \'bitch\'.';
	elseif(stristr($new_name, 'cock')) $error = 'Your user name contains a banned word. Banned word: \'cock\'.';
	elseif(stristr($new_name, 'dildo')) $error = 'Your user name contains a banned word. Banned word: \'dildo\'.';
	elseif(stristr($new_name, 'fag')) $error = 'Your user name contains a banned word. Banned word: \'fag\'.';
	elseif(stristr($new_name, 'fuck')) $error = 'Your user name contains a banned word. Banned word: \'fuck\'.';
	elseif(stristr($new_name, 'gay')) $error = 'Your user name contains a banned word. Banned word: \'gay\'.';
	elseif(stristr($new_name, 'goatse')) $error = 'Your user name contains a banned word. Banned word: \'goatse\'.';
	elseif(stristr($new_name, 'nigga')) $error = 'Your user name contains a banned word. Banned word: \'nigga\'.';
	elseif(stristr($new_name, 'nigger')) $error = 'Your user name contains a banned word. Banned word: \'nigger\'.';
	elseif(stristr($new_name, 'penis')) $error = 'Your user name contains a banned word. Banned word: \'penis\'.';
	elseif(stristr($new_name, 'shit')) $error = 'Your user name contains a banned word. Banned word: \'shit\'.';
	elseif(stristr($new_name, 'slut')) $error = 'Your user name contains a banned word. Banned word: \'slut\'.';
	elseif(stristr($new_name, 'tubgirl')) $error = 'Your user name contains a banned word. Banned word: \'tubgirl\'.';
	elseif(strlen($new_name) > 20) $error = 'Your user name is too many characters.';
	elseif(strlen($new_name) < 4) $error = 'Your user name must be at least 4 characters.';
	elseif(!preg_match("/^[A-Z0-9_ ]+$/i",$new_name)) $error = 'Your user name can only contain alphanumeric characters, underscores, and spaces.';
	elseif(trim($new_email) == "") $error = 'You must enter an e-mail address.';
	elseif(!verify_email_address($new_email)) $error = 'You must enter a valid e-mail address.';
	elseif(check_email($new_email,-1) == 1) $error = 'That e-mail address is already registered to an account. Please choose another one.';
	elseif($new_email != $new_email2) $error = 'Your e-mail address and e-mail address confirmation do not match.';
	elseif($tos != "yes") $error = 'You must accept the Terms of Service.';
	else {

	$displaypage = 0;
	$reg_date = date("YmdHisA");
	$usernumber = myfile("objects/id_users.txt"); $new_id = $usernumber[0]+1;

	$successful = "<strong>You are now registered as <em>".$new_name."</em>.</strong><br /><br />Check your e-mail inbox for your account password. Please note that it may take up to 5 minutes to arrive.";

	$password1 = mycrypt($password);
	$user_counter = myfile("objects/id_users.txt");

	$towrite = "$new_name\n$new_id\n$password1\n$new_email\n3\n0\n$reg_date\n".$config['default_timezone']."\n\n\n0,1,1,1\n\n\n\n0\nstylesheets/blue.css\n0\n\n\n\n$new_email";
	myfwrite("members/$new_id.txt",$towrite,"w"); myfwrite("objects/id_users.txt",$new_id,"w");

	$search = array('{USERNAME}','{boardNAME}','{USERPW}','{bolded}');
	$replace = array($new_mailname,$config['board_name'],$password1,$config['board_url'].'/index.php');

	$to = $newuser_email;
	$subject = "Welcome to ".$config['board_name']."!";
	$message = "Here is your account information:\n\nUser Name: $newuser_name\nPassword: $password\n\nMake sure to keep track of your information!\n\nYou may change your password once you login:\n".$config['board_url'];
	$headers = "From: ".$config['webmasters_email_address']."" . "\r\n" .
   "Reply-To: ".$config['webmasters_email_address']."" . "\r\n" .
   "X-Mailer: PHP/" . phpversion();

	mail($to, $subject, $message, $headers);

	$logging = explode(',',$config['record_options']);
	if(in_array(10,$logging)) {
	record("11","New Registration: $new_name (ID: $new_id) [IP: %2]");
	}

	include("board_top.php");
	echo navigation("Registration Complete");
?>

<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" style="text-align: left"><span class="heading">Registration Complete</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: center"><span class="normal"><br /><?=$successful?><br /><br /></span></td>
	</tr>
</table>
</object><br />

<?
	}
	}
	if($displaypage == 1) {
	include("board_top.php");
	echo navigation("Register");
?>

<script type="text/javascript">
function textCounter(field,cntfield,maxlimit) {
if (field.value.length > maxlimit)
field.value = field.value.substring(0, maxlimit);
else
cntfield.value = maxlimit - field.value.length;
}
</script>
<form action="index.php?page=register" method="post" name="register"><input name="method" type="hidden" value="register" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="4" style="text-align: left"><span class="heading">Register</span></td>
	</tr>
<? if($error != "") echo "	<tr>
		<td class=\"error\" colspan=\"4\" style=\"text-align: left\"><span class=\"heading\">Error: $error</span></td>
	</tr>
"; ?>
	<tr>
		<td class="one" style="text-align: left; width: 20%" valign="middle"><span class="normal"><strong>User Name</strong> <acronym title="Choose the name you want to appear on your posts. Maximum 20 characters.">(?)</acronym></span></td>
		<td class="one" style="text-align: left; width: 30%" valign="middle"><input class="textbox" maxlength="20" name="newuser_name" size="20" tabindex="1" type="text" value="<?=$newuser_name?>" /></td>
		<td class="one" style="text-align: left; width: 20%" valign="middle"><span class="normal"><strong>E-mail Address</strong> <acronym title="You must enter a valid e-mail address to receive your password.">(?)</acronym></span></td>
		<td class="one" style="text-align: left; width: 30%" valign="middle"><input class="textbox" maxlength="100" name="newuser_email" size="30" tabindex="3" type="text" value="<?=$newuser_email?>" /></td>
	</tr>
	<tr>
		<td class="two" style="text-align: left" valign="middle"><span class="normal"><strong>I Accept the <a href="index.php?page=terms_of_service">Terms of Service</a></strong></span></td>
		<td class="two" style="text-align: left" valign="middle"><span class="normal"><select class="textbox" name="tos" tabindex="2"><option value="no">No, I do not accept</option><option value="yes">Yes, I accept</option></select></span></td>
		<td class="two" style="text-align: left" valign="middle"><span class="normal"><strong>E-mail Address Confirmation</strong></span></td>
		<td class="two" style="text-align: left" valign="middle"><input class="textbox" maxlength="100" name="new_email2" size="30" tabindex="4" type="text" value="<?=$new_email2?>" /></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><span class="normal"><strong>Please note that you may need to change some of your mail settings because often the e-mail gets sent to the &quot;Bulk&quot; or &quot;Spam&quot; folder.</strong><br /><br /><input class="button" tabindex="5" type="submit" value="Register" /></span></td>
	</tr>
</table>
</object>
</form>
<?
	}
	}
?>