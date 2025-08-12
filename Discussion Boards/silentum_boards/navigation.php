<?
	/*
	Silentum Boards v1.4.3
	navigation.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	require_once("permission.php");

	if($page == "faqs") {

	echo navigation("FAQs");

	$logging = explode(',',$config['record_options']);
	if(in_array(1,$logging)) {
	record("10","%1: FAQs Viewed [IP: %2]");
	}
?>

<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2" style="text-align: left"><span class="heading">FAQs</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="2" style="text-align: left"><span class="heading">Last Updated: 2005-October-13</span></td>
	</tr>
	<tr>
		<td class="heading3" colspan="2" style="text-align: left"><span class="heading">Table of Contents</span></td>
	</tr>
	<tr>
		<td class="two" style="text-align: left; width: 50%"><span class="heading">A. <a href="#A">Registration/Login</a></span></td>
		<td class="two" style="text-align: left; width: 50%"><span class="heading">C. <a href="#C">Profile Information</a></span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left" valign="top">
		<ul class="square">
			<li><span class="normal"> How do I register a new account?</span></li>
			<li><span class="normal">Why do I need to enter my e-mail address?</span></li>
			<li><span class="normal">What are the Terms of Service?</span></li>
			<li><span class="normal">Where's my password at?</span></li>
			<li><span class="normal">I keep getting 'Invalid Password.' when I try to log in, but I know my password is correct. Why am I getting this error?</span></li>
			<li><span class="normal">I lost or forgot my password, what do I do now?</span></li>
			<li><span class="normal">How do I logout?</span></li>
			<li><span class="normal">Why can't I stay logged in?</span></li>
		</ul></td>
		<td class="one" style="text-align: left" valign="top">
		<ul class="square">
			<li><span class="normal">How do I edit my profile information?</span></li>
			<li><span class="normal">How do I change my password?</span></li>
			<li><span class="normal">What are the unacceptable passwords?</span></li>
			<li><span class="normal">How do I change my registration e-mail address?</span></li>
			<li><span class="normal">What is my stylesheet?</span></li>
			<li><span class="normal">What are the different statuses?</span></li>
			<li><span class="normal">What is my status determined by?</span></li>
			<li><span class="normal">What is Karma and how do I get more?</span></li>
			<li><span class="normal">What does &quot;Close Account&quot; do?</span></li>
			<li><span class="normal">How do I become a Moderator?</span></li>
			<li><span class="normal">What are the requirements for becoming a Moderator?</span></li>
			<li><span class="normal">Why was I banned/suspended?</span></li>
			<li><span class="normal">What's the difference between a banning and a suspension?</span></li>
		</ul></td>
	</tr>
	<tr>
		<td class="two" style="text-align: left"><span class="heading">B. <a href="#B">Posting/Moderations</a></span></td>
		<td class="two" style="text-align: left"><span class="heading">D. <a href="#D">Miscellaneous</a></span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left" valign="top">
		<ul class="square">
			<li><span class="normal">Why can't I post new topics or polls?</span></li>
			<li><span class="normal">Someone told me I have a title. How can I view it?</span></li>
			<li><span class="normal">How do I use HTML in a post?</span></li>
			<li><span class="normal">Why isn't my HTML appearing in my messages?</span></li>
			<li><span class="normal">What does &quot;Quote&quot; do?</span></li>
			<li><span class="normal">What does &quot;Report&quot; do?</span></li>
			<li><span class="normal">What is a moderation?</span></li>
			<li><span class="normal">When do my moderations go away?</span></li>
			<li><span class="normal">How do I edit/delete a post?</span></li>
			<li><span class="normal">What are smilies?</span></li>
			<li><span class="normal">How do I add smilies to my posts?</span></li>
			<li><span class="normal">When I tried to post, I got 'An error has occurred.'. Why did this happen?</span></li>
		</ul></td>
		<td class="one" style="text-align: left" valign="top">
		<ul class="square">
			<li><span class="normal">Can I use the source code for the Silentum Boards on my site?</span></li>
			<li><span class="normal">What is my &quot;Note Box&quot; for?</span></li>
			<li><span class="normal">How do I delete my notes?</span></li>
			<li><span class="normal">How can I find a post I made a long time ago?</span></li>
			<li><span class="normal">Which fonts are used on the boards?</span></li>
			<li><span class="normal">Someone hacked my account and got me banned! What can I do?</span></li>
			<li><span class="normal">I found an exploit in the board code, what do I do?</span></li>
			<li><span class="normal">I received a weird error on one of the pages. What should I do?</span></li>
			<li><span class="normal">I have a question about the message boards that isn't answered here, where do I go?</span></li>
		</ul></td>
	</tr>
	<tr>
		<td class="heading2" colspan="2" style="text-align: left"><span class="heading"><a name="A" />A. Registration/Login</span></td>
	</tr>
	<tr>
		<td class="one" colspan="2" style="text-align: left">
		<ul class="square">
			<li><span class="normal"><span class="heading">How do I register a new account?</span><br />
			To register, click on the &quot;Register&quot; link. Once the registration page loads, fill out all the required information and click the &quot;Register&quot; button at the bottom.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">Why do I need to enter my e-mail address?</span><br />
			A valid e-mail address is required for registration so that your randomly generated password can be sent to you. It's also to verify you're an actual member and not just spamming registrations.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">What are the Terms of Service?</span><br />
			The <a href="index.php?page=terms_of_service">Terms of Service</a> are the rules of the board. It's a good idea to read them before registering and posting so you know what is restricted on the boards.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">Where's my password at?</span><br />
			Your password is randomly generated and sent to the e-mail address you entered at the time of registration. Before you register, you may have to change your mail options to allow &quot;Bulk&quot; or &quot;Spam&quot; e-mails. Sometimes the e-mail may take up to 10 minutes to arrive.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">I keep getting 'Invalid Password.' when I try to log in, but I know my password is correct. Why am I getting this error?</span><br />
			All passwords are case sensitive. Make sure your Caps Lock button is not on and your user name is also correct.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">I lost or forgot my password, what do I do now?</span><br />
			Passwords are non-recoverable. If you lose or forget your password, you must make a new account. <ins>Write your password down!</ins><br /><br /></span></li>

			<li><span class="normal"><span class="heading">How do I logout?</span><br />
			You can logout either by clicking the &quot;Logout&quot; link next to the &quot;User CP&quot; link on the menu, or by clicking the link inside your &quot;User CP&quot;.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">Why can't I stay logged in?</span><br />
			Since the Silentum Boards use cookies, you'll have to check your browser options and make sure all cookies are accepted. If that doesn't work, make sure the Automatic Login option at the &quot;Login&quot; page is set to &quot;Yes&quot;. This will ensure you will stay logged in on that computer until your cookies are cleared or until you log out. If that <strong>still</strong> doesn't work, then try refreshing your browser, clearing your cookies, or clearing your history.</span></li>
		</ul></td>
	</tr>
	<tr>
		<td class="heading2" colspan="2" style="text-align: left"><span class="heading"><a name="B" />B. Posting/Moderations</span></td>
	</tr>
	<tr>
		<td class="one" colspan="2" style="text-align: left">
		<ul class="square">
			<li><span class="normal"><span class="heading">Why can't I post new topics or polls?</span><br />
			Either you're not logged in, suspended/banned, or you're trying to post on a restricted board.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">Someone told me I have a title. How can I view it?</span><br />
			Your title will appear in parenthesis after your name on all of your posts. If you don't see it, make sure the &quot;Display Titles&quot; option is checked under your &quot;User Preferences&quot;.<br /><br /></span></li>
		
			<li><span class="normal"><span class="heading">How do I use HTML in a post?</span><br />
			Here is a list of the HTML you may use in your posts, signature, and information:
			<object>
			<table style="width: 90%">
				<tr>
					<td class="two" style="width: 30%"><span class="normal"><strong>&lt;b&gt;</strong>bold text<strong>&lt;/b&gt;</strong></span></td>
					<td class="one" style="width: 60%"><span class="normal">creates... <strong>bold text</strong></span></td>
				</tr>
				<tr>
					<td class="two" style="width: 30%"><span class="normal"><strong>&lt;strong&gt;</strong>strong text<strong>&lt;/strong&gt;</strong></span></td>
					<td class="one" style="width: 60%"><span class="normal">creates... <strong>strong text</strong> (Valid XHTML)</span></td>
				</tr>
				<tr>
					<td class="two" style="width: 30%"><span class="normal"><strong>&lt;i&gt;</strong>italic text<strong>&lt;/i&gt;</strong></span></td>
					<td class="one" style="width: 60%"><span class="normal">creates... <em>italic text</em></span></td>
				</tr>
				<tr>
					<td class="two" style="width: 30%"><span class="normal"><strong>&lt;em&gt;</strong>emphasized text<strong>&lt;/em&gt;</strong></span></td>
					<td class="one" style="width: 60%"><span class="normal">creates... <em>emphasized text</em> (Valid XHTML)</span></td>
				</tr>
				<tr>
					<td class="two" style="width: 30%"><span class="normal"><strong>&lt;u&gt;</strong>underlined text<strong>&lt;/u&gt;</strong></span></td>
					<td class="one" style="width: 60%"><span class="normal">creates... <ins>underlined text</ins></span></td>
				</tr>
				<tr>
					<td class="two" style="width: 30%"><span class="normal"><strong>&lt;ins&gt;</strong>inserted text<strong>&lt;/ins&gt;</strong></span></td>
					<td class="one" style="width: 60%"><span class="normal">creates... <ins>inserted text</ins> (Valid XHTML)</span></td>
				</tr>
				<tr>
					<td class="two" style="width: 30%"><span class="normal"><strong>&lt;s&gt;</strong>strikeout text<strong>&lt;/s&gt;</strong></span></td>
					<td class="one" style="width: 60%"><span class="normal">creates... <del>strikeout text</del></span></td>
				</tr>
				<tr>
					<td class="two" style="width: 30%"><span class="normal"><strong>&lt;del&gt;</strong>deleted text<strong>&lt;/del&gt;</strong></span></td>
					<td class="one" style="width: 60%"><span class="normal">creates... <del>deleted text</del> (Valid XHTML)</span></td>
				</tr>
				<tr>
					<td class="two" style="width: 30%"><span class="normal"><strong>&lt;red&gt;</strong>red text<strong>&lt;/red&gt;</strong></span></td>
					<td class="one" style="width: 60%"><span class="normal">creates... <span style="color: #ff0000">red text</span></span></td>
				</tr>
				<tr>
					<td class="two" style="width: 30%"><span class="normal"><strong>&lt;green&gt;</strong>green text<strong>&lt;/green&gt;</strong></span></td>
					<td class="one" style="width: 60%"><span class="normal">creates... <span style="color: #00cc00">green text</span></span></td>
				</tr>
				<tr>
					<td class="two" style="width: 30%"><span class="normal"><strong>&lt;blue&gt;</strong>blue text<strong>&lt;/blue&gt;</strong></span></td>
					<td class="one" style="width: 60%"><span class="normal">creates... <span style="color: #0000ff">blue text</span></span></td>
				</tr>
			</table>
			</object>
			<br /><br /></span></li>

			<li><span class="normal"><span class="heading">Why isn't my HTML appearing in my messages?</span><br />
			Make sure you have &quot;Enable Basic HTML&quot; checked underneath your message. If it still doesn't appear, make sure you end all the tags you begin. If you leave any tags open, the HTML will not work.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">What does &quot;Quote&quot; do?</span><br />
			If you want to repeat someone's message without typing it all out, you can click the &quot;Quote&quot; link next to that post. It will automatically be inserted in your new reply.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">What does &quot;Report&quot; do?</span><br />
			If you see a message that violates the <a href="index.php?page=terms_of_service">Terms of Service</a>, click &quot;Report&quot; next to that message. It will be sent directly to a queue where Moderators and Administrators will take the appropriate action.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">What is a moderation?</span><br />
			A moderation is a post you made that got deleted for violating the <a href="index.php?page=terms_of_service">Terms of Service</a>. You may view all your moderations under your &quot;User CP&quot;.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">When do my moderations go away?</span><br />
			Your moderations will stay on your account forever.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">How do I edit/delete a post?</span><br />
			You cannot edit or delete a post unless you're a Moderator or an Administrator.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">What are smilies?</span><br />
			Smilies are small icons which express emotions. The following smilies can be used at the Silentum Boards:
			<object>
			<table width="50%">
				<tr>
					<td class="two" style="text-align: center; width: 5%" valign="middle"><span class="normal"><strong>*:)*</strong></span></td>
					<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal">creates... <img alt="*:)*" class="icon" src="images/smilies/smilie_1.png" title="*:)*" /></span></td>
					<td class="two" style="text-align: center; width: 5%" valign="middle"><span class="normal"><strong>*:(*</strong></span></td>
					<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal">creates... <img alt="*:(*" class="icon" src="images/smilies/smilie_2.png" title="*:(*" /></span></td>
				</tr>
				<tr>
					<td class="two" style="text-align: center; width: 5%" valign="middle"><span class="normal"><strong>*~)*</strong></span></td>
					<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal">creates... <img alt="*~)*" class="icon" src="images/smilies/smilie_3.gif" title="*~)*" /></span></td>
					<td class="two" style="text-align: center; width: 5%" valign="middle"><span class="normal"><strong>*:D*</strong></span></td>
					<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal">creates... <img alt="*:D*" class="icon" src="images/smilies/smilie_4.png" title="*:D*" /></span></td>
				</tr>
				<tr>
					<td class="two" style="text-align: center; width: 5%" valign="middle"><span class="normal"><strong>*;)*</strong></span></td>
					<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal">creates... <img alt="*;)*" class="icon" src="images/smilies/smilie_5.png" title="*;)*" /></span></td>
					<td class="two" style="text-align: center; width: 5%" valign="middle"><span class="normal"><strong>*:P*</strong></span></td>
					<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal">creates... <img alt="*:P*" class="icon" src="images/smilies/smilie_6.png" title="*:P*" /></span></td>
				</tr>
				<tr>
					<td class="two" style="text-align: center; width: 5%" valign="middle"><span class="normal"><strong>*8)*</strong></span></td>
					<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal">creates... <img alt="*8)*" class="icon" src="images/smilies/smilie_7.png" title="*8)*" /></span></td>
					<td class="two" style="text-align: center; width: 5%" valign="middle"><span class="normal"><strong>*:DP*</strong></span></td>
					<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal">creates... <img alt="*:DP*" class="icon" src="images/smilies/smilie_8.gif" title="*:DP*" /></span></td>
				</tr>
				<tr>
					<td class="two" style="text-align: center; width: 5%" valign="middle"><span class="normal"><strong>*V:(*</strong></span></td>
					<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal">creates... <img alt="*V:(*" class="icon" src="images/smilies/smilie_9.png" title="*V:(*" /></span></td>
					<td class="two" style="text-align: center; width: 5%" valign="middle"><span class="normal"><strong>*:o*</strong></span></td>
					<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal">creates... <img alt="*:o*" class="icon" src="images/smilies/smilie_10.gif" title="*:o*" /></span></td>
				</tr>
				<tr>
					<td class="two" style="text-align: center; width: 5%" valign="middle"><span class="normal"><strong>*:x*</strong></span></td>
					<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal">creates... <img alt="*:x*" class="icon" src="images/smilies/smilie_11.png" title="*:x*" /></span></td>
					<td class="two" style="text-align: center; width: 5%" valign="middle"><span class="normal"><strong>*:8*</strong></span></td>
					<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal">creates... <img alt="*:8*" class="icon" src="images/smilies/smilie_12.png" title="*:8*" /></span></td>
				</tr>
				<tr>
					<td class="two" style="text-align: center; width: 5%" valign="middle"><span class="normal"><strong>*:$*</strong></span></td>
					<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal">creates... <img alt="*:$*" class="icon" src="images/smilies/smilie_13.gif" title="*:$*" /></span></td>
					<td class="two" style="text-align: center; width: 5%" valign="middle"><span class="normal"><strong>*:/*</strong></span></td>
					<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal">creates... <img alt="*:/*" class="icon" src="images/smilies/smilie_14.png" title="*:/*" /></span></td>
				</tr>
				<tr>
					<td class="two" style="text-align: center; width: 5%" valign="middle"><span class="normal"><strong>*:V*</strong></span></td>
					<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal">creates... <img alt="*:V*" class="icon" src="images/smilies/smilie_15.png" title="*:V*" /></span></td>
					<td class="two" style="text-align: center; width: 5%" valign="middle"><span class="normal"><strong>*:`(*</strong></span></td>
					<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal">creates... <img alt="*:`(*" class="icon" src="images/smilies/smilie_16.gif" title="*:`(*" /></span></td>
				</tr>
			</table>
			</object><br />
			Thanks to <a href="http://www.jms101.btinternet.co.uk/">Jason's Smiley Collection</a> for these great smilies<br /><br /></span></li>

			<li><span class="normal"><span class="heading">How do I add smilies to my posts?</span><br />
			Click the smilie you want to add to the left of the message box, or type in the corresponding code mentioned above. Make sure you have &quot;Enable Smilies&quot; checked before you post your message. You must have JavaScript enabled if you want to add the smilies simply by clicking them.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">When I tried to post, I got 'An error has occurred.'. Why did this happen?</span><br />
			Most likely, you tried to post in a topic that already got deleted, locked, or moved. If that's not the case, you may be suspended or banned.</span></li>
		</ul>
		</td>
	</tr>
	<tr>
		<td class="heading2" colspan="2" style="text-align: left"><span class="heading"><a name="C" />C. Profile Information</span></td>
	</tr>
	<tr>
		<td class="one" colspan="2" style="text-align: left">
		<ul class="square">
			<li><span class="normal"><span class="heading">How do I change my profile information?</span><br />
			Click on the &quot;User CP&quot; link after you log in. From there, click &quot;User Preferences&quot;.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">How do I change my password?</span><br />
			Once you're logged in, click on the &quot;User CP&quot; link on the main menu. Then click the &quot;User Preferences&quot; link. In the right column are two fields to change your password.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">What are the unacceptable passwords?</span><br />
			The unacceptable passwords are <strong>123456</strong>, <strong>abcdef</strong>, <strong>dragon</strong>, <strong>password</strong>, <strong>pikachu</strong>, <strong>pokemon</strong>, and <strong>qwerty</strong>. Your password also cannot be the same as your user name or smaller than 6 characters. These rules are in effect for security reasons.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">How do I change my registration e-mail address?</span><br />
			You cannot change your registration e-mail address. It is tied to your account to prevent fraudulent registrations.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">What is my stylesheet?</span><br />
			Your stylesheet is the color scheme for the boards. You can click the &quot;Previewer&quot; link next to the list under &quot;User Preferences&quot; to preview all the different stylesheets.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">What are the different statuses?</span>
			<object>
			<table style="width: <?=$twidth?>">
				<tr>
					<td class="one" valign="top"><span class="normal"><strong><?=$config['status_banned']?></strong><br />User has been banned for violating the Terms of Service. Account will not be restored.</span></td>
				</tr>
				<tr>
					<td class="two" valign="top"><span class="normal"><strong><?=$config['status_suspended']?></strong><br />User's topic/poll posting privileges have been revoked. Account restored after 72 hours. Any further violations during this time will result in a ban.</span></td>
				</tr>
				<tr>
					<td class="one" valign="top"><span class="normal"><strong><?=$config['status_closed']?></strong><br />User has chosen to close their account. User can no longer post.</span></td>
				</tr>
				<tr>
					<td class="two" valign="top"><span class="normal"><strong>-1: Negative Karma</strong><br />User has negative Karma. Post limit of 10. Cannot post topics or polls.</span></td>
				</tr>
				<tr>
					<td class="one" valign="top"><span class="normal"><strong>0: No Karma</strong> <img alt="Stars" class="stars" src="images/stars/star_0.png" title="Stars" /><br />User has 0 Karma. Post limit of 10.</span></td>
				</tr>
				<tr>
					<td class="two" valign="top"><span class="normal"><strong>1: New User</strong> <img alt="Stars" class="stars" src="images/stars/star_1.png" title="Stars" /><br />User has between 1 and 4 Karma. Post limit of 30. User can now report posts.</span></td>
				</tr>
				<tr>
					<td class="one" valign="top"><span class="normal"><strong>2: Short Term User</strong> <img alt="Stars" class="stars" src="images/stars/star_2.png" title="Stars" /><br />User has between 5 and 14 Karma. Post limit of 60.</span></td>
				</tr>
				<tr>
					<td class="two" valign="top"><span class="normal"><strong>3: Regular User</strong> <img alt="Stars" class="stars" src="images/stars/star_3.png" title="Stars" /><br />User has between 15 and 49 Karma. No posting limits.</span></td>
				</tr>
				<tr>
					<td class="one" valign="top"><span class="normal"><strong>4: Experienced User</strong> <img alt="Stars" class="stars" src="images/stars/star_4.png" title="Stars" /><br />User has between 50 and 99 Karma. No posting limits.</span></td>
				</tr>
				<tr>
					<td class="two" valign="top"><span class="normal"><strong>5: Long Term User</strong> <img alt="Stars" class="stars" src="images/stars/star_5.png" title="Stars" /><br />User has between 100 and 199 Karma. No posting limits.</span></td>
				</tr>
				<tr>
					<td class="one" valign="top"><span class="normal"><strong>6: Veteran User</strong> <img alt="Stars" class="stars" src="images/stars/star_6.png" title="Stars" /><br />User has between 200 and 349 Karma. No posting limits.</span></td>
				</tr>
				<tr>
					<td class="two" valign="top"><span class="normal"><strong>7: Skilled User</strong> <img alt="Stars" class="stars" src="images/stars/star_7.png" title="Stars" /><br />User has between 350 and 599 Karma. No posting limits.</span></td>
				</tr>
				<tr>
					<td class="one" valign="top"><span class="normal"><strong>8: Advanced User</strong> <img alt="Stars" class="stars" src="images/stars/star_8.png" title="Stars" /><br />User has between 600 and 999 Karma. No posting limits.</span></td>
				</tr>
				<tr>
					<td class="two" valign="top"><span class="normal"><strong>9: Superior User</strong> <img alt="Stars" class="stars" src="images/stars/star_9.png" title="Stars" /><br />User has between 1000 and 1499 Karma. No posting limits.</span></td>
				</tr>
				<tr>
					<td class="one" valign="top"><span class="normal"><strong>10: Prime User</strong> <img alt="Stars" class="stars" src="images/stars/star_10.png" title="Stars" /><br />User has more than 1500 Karma. No posting limits.</span></td>
				</tr>
				<tr>
					<td class="two" valign="top"><span class="normal"><strong><?=$config['status_moderator']?></strong> <img alt="Stars" class="stars" src="images/stars/star_moderator.png" title="Stars" /><br />User can moderate posts and topics, and can view user maps.</span></td>
				</tr>
				<tr>
					<td class="one" valign="top"><span class="normal"><strong><?=$config['status_administrator']?></strong> <img alt="Stars" class="stars" src="images/stars/star_administrator.png" title="Stars" /><br />User controls most aspects, such as boards, categories, censored words, statuses, and more.</span></td>
				</tr>
				<tr>
					<td class="two" valign="top"><span class="normal"><strong><?=$config['status_host']?></strong> <img alt="Stars" class="stars" src="images/stars/star_host.png" title="Stars" /><br />User has the same permissions as Administrators, but can also edit user data, board databases, records, settings, and more.</span></td>
				</tr>
			</table>
			</object><br /><br /></span></li>

			<li><span class="normal"><span class="heading">What is my status determined by?</span><br />
			Your status is determined by the amount of Karma you have.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">What is Karma and how do I get more?</span><br />
			Karma is a numerical value that determines your status on the boards. Your account gains 1 Karma every day at exactly 12:00am (midnight) PST as long as you've viewed the boards that day. Closed accounts and banned/suspended users will not gain Karma.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">What does &quot;Close Account&quot; do?</span><br />
			&quot;Close Account&quot; will permanently revoke your posting privileges. You will still be able to access all other member-only areas and features.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">How do I become a Moderator?</span><br />
			About once a year, the Moderator Applications board is open. Simply post an application on that board, and wait until the Host announces the new Moderators.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">Are there any requirements to become a Moderator?</span><br />
			You must have at least 100 Karma, have few to no moderations, and be helpful on the boards.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">Why was I banned/suspended?</span><br />
			Most likely, you've had many posts or a few severe posts which have violated the <a href="index.php?page=terms_of_service">Terms of Service</a>. Check your <a href="index.php?page=moderations">moderations</a>, then re-read the <a href="index.php?page=terms_of_service">Terms of Service</a> and make sure you understand them.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">What's the difference between a banning and a suspension?</span><br />
			Suspended users can still post replies. Their account will also be restored after 72 hours, assuming no further violations are posted.</span></li>
		</ul></td>
	</tr>
	<tr>
		<td class="heading2" colspan="2" style="text-align: left"><span class="heading"><a name="D" />D. Miscellaneous</span></td>
	</tr>
	<tr>
		<td class="one" colspan="2" style="text-align: left">
		<ul class="square">
			<li><span class="normal"><span class="heading">Can I use the source code for the Silentum Boards on my site?</span><br />
			Yes, you can download the Silentum Boards <a href="http://www.hypersilence.net">here</a>.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">What is my &quot;Note Box&quot; for?</span><br />
			Whenever you violate the <a href="index.php?page=terms_of_service">Terms of Service</a> severely enough to get your post deleted, you will be sent a note. You'll know when you get one, because a yellow bar will appear underneath the board name informing you that you have a note.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">How do I delete my notes?</span><br />
			Check the box next to the note you want to delete and click &quot;Delete Checked Notes&quot;.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">How can I find a post I made a long time ago?</span><br />
			Click &quot;Search&quot; on the navigation menu. Type in a relevant word from the post and hit the &quot;Search&quot; button. You may also need to change some of the search options.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">Which fonts are used on the boards?</span><br />
			The primary font used on the boards is Verdana and the font used for the board name is Georgia.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">Someone hacked my account and got me banned! What can I do?</span><br />
			Sorry, but nothing can be done if your account gets hacked. Try to make difficult passwords for your accounts and change them often. Also, if you get an e-mail or instant message asking for your password, <strong>never give it out</strong>.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">I found an exploit in the board code, what do I do?</span><br />
			Anyone who uses an exploit will be immediately banned, so report it to an Administrator or the Host.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">I received a weird error on one of the pages. What should I do?</span><br />
			Write down the error, which page you received it on, and what you were trying to do and report it to one of the Administrators or the Host.<br /><br /></span></li>

			<li><span class="normal"><span class="heading">I have a question about the message boards that isn't answered here, where do I go?</span><br />
			Post your question on the appropriate board.</span></li>
		</ul></td>
	</tr>
</table>
</object><br />
<?
	}
	if($page == "terms_of_service") {

	echo navigation("Terms of Service");

	$logging = explode(',',$config['record_options']);
	if(in_array(1,$logging)) {
	record("10","%1: Terms of Service Viewed [IP: %2]");
	}
?>

<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2" style="text-align: left"><span class="heading">Terms of Service</span></td>
	</tr>
	<tr>
		<td class="heading2" style="text-align: left"><span class="heading">Last Updated: 2005-October-13</span></td>
	</tr>
	<tr>
		<td class="heading3" style="text-align: left"><span class="heading">Violating the Terms of Service</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left"><span class="normal">Please note that depending on the severity of your violation, any of these steps may be skipped.<br /></span>
		<ul class="square">
			<li><span class="normal">First offense - Post/topic deletion and a possible 1 Karma loss.</span></li>
			<li><span class="normal">Second offense - Post/topic deletion and a possible 1 Karma loss.</span></li>
			<li><span class="normal">Third offense - Post/topic deletion, possible 1 Karma loss, and a possible suspension (5 Karma loss).</span></li>
			<li><span class="normal">Fourth offense - Post/topic deletion, possible 1 Karma loss, and a possible suspension (5 Karma loss).</span></li>
			<li><span class="normal">Fifth offense - Post/topic deletion, possible 1 Karma loss, and a definite suspension (5 Karma loss).</span></li>
			<li><span class="normal">Sixth offense - Your account is banned and under some circumstances, deleted.</span></li>
		</ul>
		<span class="normal">By registering and posting at the <?=$config['board_name']?>, you agree not to perform any of the following:</span></td>
	</tr>
	<tr>
		<td class="heading3" style="text-align: left"><span class="heading">A. Flaming</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left"><span class="normal">Sexually explicit, racial or general hate comments directed towards another user.<br /></span>
		<ul class="square">
			<li><span class="normal">Direct insults (You're an idiot.)</span></li>
			<li><span class="normal">Indirect insults (X Member is really stupid.)</span></li>
		</ul></td>
	</tr>
	<tr>
		<td class="heading3" style="text-align: left"><span class="heading">B. Censor Bypassing</span></td>
	</tr>
		<tr>
		<td class="one" style="text-align: left"><span class="normal">Changing letters to bypass a censored word. (For these examples, let's assume "train" is a banned word.)<br /></span>
		<ul class="square">
			<li><span class="normal">Replacing a letter with another similar letter, number, or symbol (You're a tr41n.)</span></li>
			<li><span class="normal">Adding spaces or characters inside the word (That's so t r.ain)</span></li>
			<li><span class="normal">Not censoring out the entire word (What the t*ain?)</span></li>
		</ul></td>
	</tr>
	<tr>
		<td class="heading3" style="text-align: left"><span class="heading">C. Inciting</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left"><span class="normal">Posts which incite another user to violate the board rules, or are made to intentionally annoy another user.<br /></span>
		<ul class="square">
			<li><span class="normal">Orders (Go jump off a cliff and die.)</span></li>
			<li><span class="normal">Trolling (This game sucks.)</span></li>
			<li><span class="normal">Telling anyone to &quot;STFU&quot;, &quot;GTFO&quot;, or &quot;FOAD&quot;</span></li>
		</ul></td>
	</tr>
	<tr>
		<td class="heading3" style="text-align: left"><span class="heading">D. Disruptive Posts</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left"><span class="normal">Posts which disrupt a user's screen, browser, or computer.<br /></span>
		<ul class="square">
			<li><span class="normal">Massive amounts of random letters (asdfghjklasdfghjklasdfghjkl...)</span></li>
			<li><span class="normal">Blank or one character posts</span></li>
			<li><span class="normal">Posting your entire message in all uppercase letters (HELLO EVERYONE)</span></li>
			<li><span class="normal">Intentionally misspelling words (hy gys huws itt gonig!?1)</span></li>
			<li><span class="normal">Excessive &quot;leet&quot; or AOL talk (1 4/\/\ 50 /\/\U(H 13373|2 7H4|\| 411 U)</span></li>
			<li><span class="normal">Posts which cause a horizontal scroll bar</span></li>
			<li><span class="normal">Signatures longer than 5 lines or larger than 350 characters</span></li>
		</ul></td>
	</tr>
	<tr>
		<td class="heading3" style="text-align: left"><span class="heading">E. Illegal/Inappropriate Posts</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left"><span class="normal">Posts which offend users, encourage or show use of illegal activities, or use sexually explicit terms.<br /></span>
		<ul class="square">
			<li><span class="normal">ROMs/hacks (Does anyone know where I can get this ROM?)</span></li>
			<li><span class="normal">Pornography (Check out my nude pic!)</span></li>
			<li><span class="normal">Serial/registration codes and cracks (Does anyone have a serial code for this program I can use?)</span></li>
		</ul></td>
	</tr>
	<tr>
		<td class="heading3" style="text-align: left"><span class="heading">F. Off Topic Posts</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left"><span class="normal">Posts which do not fit the board description.<br /></span>
		<ul class="square">
			<li><span class="normal">Off Topic (Do you have any codes for Super Mario World?) on the Sports board</span></li>
		</ul></td>
	</tr>
	<tr>
		<td class="heading3" style="text-align: left"><span class="heading">G. Spamming/Flooding/Multiple Posting</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left"><span class="normal">3 or more of the same post in one topic or advertisements. Feel free to put a link to your web site in your signature, but do not advertise it in your actual post.<br /></span>
		<ul class="square">
			<li><span class="normal">Advertising (Check out my awesome web site! *link*)</span></li>
		</ul></td>
	</tr>
	<tr>
		<td class="heading3" style="text-align: left"><span class="heading">H. Exploits</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left"><span class="normal">Altering code or using features to do something that was unintended in the board design. If you find an exploit, please immediately report it to an Administrator or the Host. If you are caught using an exploit, you will be banned.<br /></span>
		<ul class="square">
			<li><span class="normal">Giving yourself Moderator powers</span></li>
			<li><span class="normal">Editing other users' information and/or posts</span></li>
			<li><span class="normal">Performing any other task which wasn't intended to be done</span></li>
		</ul></td>
	</tr>
</table>
</object><br />
<?
	}
	if($page == "search") {

	if($config['enable_search'] != 1) {
	include("board_top.php");
	echo navigation("<a href=\"index.php?page=search\">Search</a>\tUnavailable");
	echo get_message('Not_Available','<br /><br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	exit;
	}
	if($user_data['status'] == "4") {
	echo navigation("<a href=\"index.php?page=search\">Search</a>\tAccess Denied");
	echo get_message('Banned','<br /><br />'.sprintf($txt['Links']['User_Control_Panel'],"<a href=\"index.php?page=user_cp\">",'</a>').'<br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	exit;
	}
	else
	if($search != "yes" || $selection == "" || $searchfor == "") {

	echo navigation("Search");

	$logging = explode(',',$config['record_options']);
	if(in_array(1,$logging)) {
	record("10","%1: Search Used [IP: %2]");
	}
?>

<form action="index.php?page=search" method="post"><input name="search" type="hidden" value="yes" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="5" style="text-align: left"><span class="heading">Search</span></td>
	</tr>
	<tr>
		<td class="two" style="text-align: left; width: 33%" valign="top"><span class="normal"><strong>Search Tips</strong><br />
		- Separate multiple word searches with spaces<br />
		- Don't use common words (a, an, and, i, the, etc.)<br />
		- Narrow down your search by changing the search options</span></td>
		<td class="one" colspan="2" style="text-align: left; width: 67%" valign="top"><fieldset><legend><span class="normal"><strong>Search String</strong> (Case insensitive)</span></legend>
		<input class="textbox" maxlength="80" name="searchfor" size="40" type="text" /></fieldset>
		</td>
	</tr>
	<tr>
		<td class="heading2" colspan="3" style="text-align: left"><span class="heading">Search Options</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left"><fieldset><legend><span class="normal"><strong>Search</strong></span></legend>
		<select class="textbox" name="selection" size="1"><option value="all">All Boards</option><?
	$boards = myfile("objects/boards.txt"); $category = myfile("objects/categories.txt");
	for($j = 0; $j < sizeof($category); $j++) {
	$act_category = myexplode($category[$j]);
	echo "";
	for($i = 0; $i < sizeof($boards); $i++) {
	$act_board = myexplode($boards[$i]);
	if($act_board[5] == $act_category[0]) {
	echo "<option value=\"$act_board[0]\">$act_board[1]</option>";
	}
	}
	}
?>
		</select></fieldset></td>
		<td class="one" style="text-align: left; width: 34%"><fieldset><legend><span class="normal"><strong>Search In</strong></span></legend><select class="textbox" name="soption1"><option value="1">Posts and Topic Titles</option><option value="2">Only Posts</option><option value="3">Only Topic Titles</option></select></fieldset></td>
		<td class="one" style="text-align: left width: 33%"><fieldset><legend><span class="normal"><strong>Maximum Age</strong></span></legend><select class="textbox" name="age"><option value="-1">Any Age</option><option value="1">1 day</option><option value="2">2 days</option><option value="3">3 days</option><option value="4">4 days</option><option value="5">5 days</option><option value="6">6 days</option><option value="7">1 week</option><option value="14">2 weeks</option><option value="21">3 weeks</option><option value="30">1 month</option><option value="60">2 months</option><option value="90">3 months</option><option value="180">6 months</option><option value="365">1 year</option><option value="730">2 years</option><option value="1095">3 years</option></select></fieldset></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Search" /></td>
	</tr>
</table>
</object>
</form>
<?
	}

	else {

	$x1 = 0;
	$x2 = 0;
	$tosearch = "";
	$result = array();
	$searchfor = explode(' ',$searchfor);

	if($selection == "all") {
	$board_file = myfile("objects/boards.txt");
	for($i = 0; $i < sizeof($board_file); $i++) {
	$act_board = myexplode($board_file[$i]);
	$act_board_rights = explode(',',$act_board[10]);
	$right = 0;
	if($user_logged_in != 1) {
	if($act_board_rights[6] == 1) $right = 1;
	}
	elseif(check_right($act_board[0],0) == 1) $right = 1;

	if($right == 1) {
	$act_board_topics_file = myfile("boards/$act_board[0].topics.txt");
	for($j = 0; $j < sizeof($act_board_topics_file); $j++) {
	$tosearch[$x1] = "$act_board[0].".killnl($act_board_topics_file[$j]);
	$x1++;
	}
	}
	}
	}
	else {
	if($board_data = get_board_data($selection)) {
	$right = 0;
	if($user_logged_in != 1) {
	if($board_data['rights'][6] == 1) $right = 1;
	}
	elseif(check_right($act_board[0],0) == 1) $right = 1;

	if($right == 1) {
	$act_board_topics_file = myfile("boards/$selection.topics.txt");
	for($j = 0; $j < sizeof($act_board_topics_file); $j++) {
	$tosearch[$x1] = "$selection.".killnl($act_board_topics_file[$j]);
	$x1++;
	}
	}
	}
	}

	switch($soption1) {

	default:
	for($i = 0; $i < sizeof($tosearch); $i++) {
	if($act_topic_file = myfile("boards/$tosearch[$i].txt")) {
	$found = 0;
	$act_topic_data = myexplode($act_topic_file[0]); $act_topic_lpost = myexplode($act_topic_file[sizeof($act_topic_file)-1]);
	if($age == -1 || get_time_string($act_topic_lpost[2])+3600*24*$age >= time()) {
	for($j = 0; $j < sizeof($searchfor); $j++) {
	if(stristr($act_topic_data[1],$searchfor[$j])) {
	$result[$x2] = $tosearch[$i];
	$x2++; $found = 1;
	break;
	}
	}
	if($found != 1) {
	for($j = 1; $j < sizeof($act_topic_file); $j++) {
	$act_post = myexplode($act_topic_file[$j]);
	if($age == -1 || get_time_string($act_post[2])+3600*24*$age >= time()) {
	for($k = 0; $k < sizeof($searchfor); $k++) {
	if(stristr($act_post[3],$searchfor[$k])) {
	$result[$x2] = $tosearch[$i];
	$x2++;
	break 2;
	}
	}
	}
	}
	}
	}
	}
	}
	break;

	case "2":
	for($i = 0; $i < sizeof($tosearch); $i++) {
	if($act_topic_file = myfile("boards/$tosearch[$i].txt")) {
	$act_topic_lpost = myexplode($act_topic_file[sizeof($act_topic_file)-1]);
	if($age == -1 || get_time_string($act_topic_lpost[2])+3600*24*$age >= time()) {
	for($j = 1; $j < sizeof($act_topic_file); $j++) {
	$act_post = myexplode($act_topic_file[$j]);
	if($age == -1 || get_time_string($act_post[2])+3600*24*$age >= time()) {
	for($k = 0; $k < sizeof($searchfor); $k++) {
	if(stristr($act_post[3],$searchfor[$k])) {
	$result[$x2] = $tosearch[$i];
	$x2++;
	break 2;
	}
	}
	}
	}
	}
	}
	}
	break;

	case "3":
	for($i = 0; $i < sizeof($tosearch); $i++) {
	if($act_topic_file = myfile("boards/$tosearch[$i].txt")) {
	$act_topic_data = myexplode($act_topic_file[0]); $act_topic_lpost = myexplode($act_topic_file[sizeof($act_topic_file)-1]);
	if($age == -1 || get_time_string($act_topic_lpost[2])+3600*24*$age >= time()) {
	for($j = 0; $j < sizeof($searchfor); $j++) {
	if(stristr($act_topic_data[1],$searchfor[$j])) {
	$result[$x2] = $tosearch[$i];
	$x2++; $found = 1;
	break;
	}
	}
	}
	}
	}
	break;
	}

	echo navigation("<a href=\"index.php?page=search\">Search</a>\tSearch Complete");
?>

<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="3" style="text-align: left"><span class="heading">Search Complete</span></td>
	</tr>
<?
	$results = sizeof($result);
	if($results == 0) echo "	<tr>
		<td class=\"one\" style=\"text-align: center\"><span class=\"normal\"><br /><strong>No posts were found. Try an alternative spelling or a broader search string.</strong><br /><br /><a href=\"index.php?page=search\">Search Again</a><br /><br /></span></td>
	</tr>";
	else {
	if($results >= 50) echo "	<tr>
		<td class=\"error\" colspan=\"3\" style=\"text-align: left\"><span class=\"heading\">More than 50 results were found. You may want to narrow your search down.</span></td>
	</tr>";
	if($results == 1) $pluralize = "result"; else $pluralize = "results";
	echo "	<tr>
		<td class=\"heading2\" colspan=\"3\" style=\"text-align: left\"><span class=\"heading\">Your search found ".$results." ".$pluralize."</span></td>
	</tr>
	<tr>
		<td class=\"heading3\" style=\"text-align: center; width: 5%\"><span class=\"heading\">Rank</span></td>
		<td class=\"heading3\" style=\"text-align: left; width: 30%\"><span class=\"heading\">Board</span></td>
		<td class=\"heading3\" style=\"text-align: left; width: 65%\"><span class=\"heading\">Topic</span></td>
	</tr>";
	for($h = 0; $h < $results; $h++) {
	$rows_per_color = 1;
	switch($ctr++) {
	case 0:
	$bgcolor = "one";
	break;
	case ($rows_per_color):
	$bgcolor = "two";
	break;
	case ($rows_per_color * 2):
	$bgcolor = "one";
	$ctr = 1;
	break;
	}
	$act_result = explode(".",$result[$h]);
	if($config['enable_censor'] == 1) $topictitle = censor(get_thread_name($act_result[0],$act_result[1])); else $topictitle = get_thread_name($act_result[0],$act_result[1]);
	$rank = $h+1;
	echo "
	<tr>
		<td class=\"".$bgcolor."\" style=\"text-align: center\"><span class=\"normal\">$rank</span></td>
		<td class=\"".$bgcolor."\" style=\"text-align: left\"><span class=\"normal\"><strong>".get_board_name($act_result[0])."</strong></span></td>
		<td class=\"".$bgcolor."\" style=\"text-align: left\"><span class=\"normal\"><a href=\"index.php?method=topic&amp;board=$act_result[0]&amp;thread=$act_result[1]\">".$topictitle."</a></span></td>
	</tr>";
	}
	echo "
	<tr>
		<td class=\"heading1\" colspan=\"3\" style=\"text-align: left\"><span class=\"heading\">Still didn't find what you were looking for? <a href=\"index.php?page=search\">Search again</a></span></td>
	</tr>";
	}
	echo "
</table>
</object><br />
";
	}
	}
	if($page == "top_10") {
	if($config['enable_top_10'] != 1) {
	echo navigation("<a href=\"index.php?page=top_10\">Top 10 Users</a>\tUnavailable");
	echo get_message('Not_Available','<br /><br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	exit;
	}
	if($user_data['status'] == "4") {
	echo navigation("<a href=\"index.php?page=top_10\">Top 10 Users</a>\tAccess Denied");
	echo get_message('Banned','<br /><br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	exit;
	}
	else {

	function cmpkarma($a,$b) {
	if($a['karma'] == $b['karma']) return 0;
	return ($a['karma'] > $b['karma']) ? -1 : 1;
	}

	function cmpname($a,$b) {
	return strcasecmp($a['name'],$b['name']);
	}

	$x = 0;

	$membernumber = myfile("objects/id_users.txt"); $membernumber = $membernumber[0] + 1;

	for($i = 1; $i < $membernumber; $i++) {
	if($act_member = myfile("members/$i.txt")) {
	if(killnl($act_member[4]) != 5) {
	$member[$x]["name"] = killnl($act_member[0]);
	$member[$x]["status"] = killnl($act_member[4]);
	$member[$x]["regdat"] = killnl($act_member[6]);
	$member[$x]["karma"] = killnl($act_member[16]);
	$x++;
	}
	}
	}

	$membernumber = sizeof($member);
	$sortmethod = 'karma';
	switch($sortmethod) {
	case "karma":
	usort($member,"cmpkarma");
	break;
	default:
	nix();
	break;
	}

	$member_per_page = 10;

	$pagenumber = ceil(10/$member_per_page);

	if(!$z) $z = 1; $z2 = $z * $member_per_page; $y = $z2-$member_per_page; if($z2 > 10) $z2 = 10;

	echo navigation("Top 10 Users");

	$logging = explode(',',$config['record_options']);
	if(in_array(1,$logging)) {
	record("10","%1: Top 10 Users Viewed [IP: %2]");
	}

	if($z >= 2 || $z <= 0) {
	header("Location: index.php");
	exit;
	}
	else {
?>

<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="5"><span class="heading">Top 10 Users</span></td>
	</tr>
	<tr>
		<td class="heading2" style="text-align: center; width: 10%"><span class="heading">Rank</span></td>
		<td class="heading2" style="text-align: center; width: 40%"><span class="heading">User Name</span></td>
		<td class="heading2" style="text-align: center; width: 10%"><span class="heading">Karma</span></td>
		<td class="heading2" style="text-align: center; width: 40%"><span class="heading">Registration Date</span></td>
	</tr>
<?
	for($i = $y; $i < $z2; $i++) {

	$rows_per_color = 1;
	switch($ctr++) {
	case 0:
	$bgcolor = "one";
	break;
	case ($rows_per_color):
	$bgcolor = "two";
	break;
	case ($rows_per_color * 2):
	$bgcolor = "one";
	$ctr = 1;
	break;
	}
?>
	<tr>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><? if($i+1 == 1 || $i+1 == 2 || $i+1 == 3) echo "<strong>"; ?><? if($i+1 == 1) echo "<span style=\"color: #c9c919\">"; ?><? if($i+1 == 2) echo "<span style=\"color: #a0a0a0\">"; ?><? if($i+1 == 3) echo "<span style=\"color: #a67d3d\">"; ?><?=$i+1?><? if($i+1 == 1 || $i+1 == 2 || $i+1 == 3) echo "</span>"; ?><? if($i+1 == 1 || $i+1 == 2 || $i+1 == 3) echo "</strong>"; ?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><? if($i+1 == 1 || $i+1 == 2 || $i+1 == 3) echo "<strong>"; ?><? if($i+1 == 1) echo "<span style=\"color: #c9c919\">"; ?><? if($i+1 == 2) echo "<span style=\"color: #a0a0a0\">"; ?><? if($i+1 == 3) echo "<span style=\"color: #a67d3d\">"; ?><? if($member[$i]["name"] != "") echo $member[$i]["name"]; else echo "-"; ?><? if($i+1 == 1 || $i+1 == 2 || $i+1 == 3) echo "</span>"; ?><? if($i+1 == 1 || $i+1 == 2 || $i+1 == 3) echo "</strong>"; ?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><? if($i+1 == 1 || $i+1 == 2 || $i+1 == 3) echo "<strong>"; ?><? if($i+1 == 1) echo "<span style=\"color: #c9c919\">"; ?><? if($i+1 == 2) echo "<span style=\"color: #a0a0a0\">"; ?><? if($i+1 == 3) echo "<span style=\"color: #a67d3d\">"; ?><? if($member[$i]["karma"] != "") echo $member[$i]["karma"]; else echo "-"; ?><? if($i+1 == 1 || $i+1 == 2 || $i+1 == 3) echo "</span>"; ?><? if($i+1 == 1 || $i+1 == 2 || $i+1 == 3) echo "</strong>"; ?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><? if($i+1 == 1 || $i+1 == 2 || $i+1 == 3) echo "<strong>"; ?><? if($i+1 == 1) echo "<span style=\"color: #c9c919\">"; ?><? if($i+1 == 2) echo "<span style=\"color: #a0a0a0\">"; ?><? if($i+1 == 3) echo "<span style=\"color: #a67d3d\">"; ?><? if($member[$i]["regdat"] != ".. ::" && $member[$i]["regdat"] != "" ) echo makeregdate($member[$i]["regdat"]); else echo "-"; ?><? if($i+1 == 1 || $i+1 == 2 || $i+1 == 3) echo "</span>"; ?><? if($i+1 == 1 || $i+1 == 2 || $i+1 == 3) echo "</strong>"; ?></span></td>
	</tr>
<?
	}
	}
?>
</table>
</object><br />
<?
	}
	}
?>