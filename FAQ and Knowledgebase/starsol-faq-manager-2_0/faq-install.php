<?php

############################################################################
############################################################################
##                                                                        ##
## This script is copyright Rupe Parnell (Starsol.co.uk) 2004 - 2005.     ##
##                                                                        ##
## Distribution of this file, and/or any other files in this package, via ##
## any means, withour prior written consent of the author is prohibited.  ##
##                                                                        ##
## Starsol.co.uk takes no responsibility for any damages caused by the    ##
## usage of this script, and does not guarantee compability with all      ##
## servers.                                                               ##
##                                                                        ##
## Please use the contact form at                                         ##
## http://www.starsol.co.uk/support.php if you need any help or have      ##
## any questions about this script.                                       ##
##                                                                        ##
############################################################################
############################################################################

require_once('faq-functions.php');

if ($_POST[step]){
	$step = $_POST[step];
} elseif ($_GET[step]){
	$step = $_GET[step];
} else {
	$step = '1';
}

switch ($step) {

	case "1":

		admin_header();

		echo'<h1>Starsol FAQ Manager Installer</h1>'."\n\n";

		echo'<p>Welcome to the Starsol FAQ installer. In a few short steps, this script (<i>faq-install.php</i>) will have the Starsol FAQ Manager script completely set up for you. If you are having problems running this script, Starsol Scripts offers an <a href="http://www.starsol.co.uk/scripts/script-installation.html" target="_blank">installation service</a> for a very reasonable price.</p>'."\n\n";

		echo'<h3>Step 1:</h3>'."\n\n".'<p>Please enter your desired username and password for logging in to the admin area (you can change these later).</p>'."\n\n";

		echo'<table class="list-table">'."\n";
		echo'<form action="'.$_SERVER[PHP_SELF].'" method="post">'."\n".'<input type="hidden" name="step" value="2" />'."\n";
		echo'<tr><td class="list-cell">Username</td><td class="list-cell"><input type="text" size="20" maxlength="255" name="admin_username" value="" /></td></tr>'."\n";
		echo'<tr><td class="list-cell">Password</td><td class="list-cell"><input type="password" size="20" maxlength="255" name="admin_password" value="" /></td></tr>'."\n";
		echo'<tr><td class="list-cell" colspan="2"><input type="submit" value="Continue" /></td></tr>'."\n";
		echo'</form>'."\n".'</table>'."\n\n";

		admin_footer();

	break;

	case "2":

		admin_header();

		echo'<h1>Starsol FAQ Manager Installer</h1>'."\n\n";

		if (!$_POST[admin_username]){
			echo'<p>Sorry, you did not specify an admin username. Please go back and try again.</p>'."\n\n";
			admin_footer();
			exit;
		}
		if (!$_POST[admin_password]){
			echo'<p>Sorry, you did not specify an admin password. Please go back and try again.</p>'."\n\n";
			admin_footer();
			exit;
		}

		echo'<h3>Step 2:</h3>'."\n\n".'<p>Now enter the name of your website, the domain name of your website, and your MySQL database information:</p>'."\n\n";

		echo'<table class="list-table">'."\n";
		echo'<form action="'.$_SERVER[PHP_SELF].'" method="post">'."\n".'<input type="hidden" name="step" value="3" />'."\n";
		echo'<input type="hidden" name="admin_username" value="'.$_POST[admin_username].'" /><input type="hidden" name="admin_password" value="'.$_POST[admin_password].'" />'."\n";
		echo'<tr><td class="list-cell">Website Name <span class="smallprint">(Examples: Starsol Scripts, Mojoo Directory)</span></td><td class="list-cell"><input type="text" size="20" name="site_name" value="" /></td></tr>'."\n";
		echo'<tr><td class="list-cell">Website Domain <span class="smallprint">(Examples: starsol.co.uk, mojoo.com)</span></td><td class="list-cell"><input type="text" size="20" name="site_domain" value="" /></td></tr>'."\n";
		echo'<tr><td class="list-cell">Database Location</td><td class="list-cell"><input type="text" size="20" name="db_location" value="localhost" /></td></tr>'."\n";
		echo'<tr><td class="list-cell">Database Username</td><td class="list-cell"><input type="text" size="20" name="db_username" value="" /></td></tr>'."\n";
		echo'<tr><td class="list-cell">Database Password</td><td class="list-cell"><input type="password" size="20" name="db_password" /></td></tr>'."\n";
		echo'<tr><td class="list-cell">Database Name</td><td class="list-cell"><input type="text" size="20" name="db_database" value="" /></td></tr>'."\n";
		echo'<tr><td class="list-cell">Database Prefix</td><td class="list-cell"><input type="text" size="20" name="db_prefix" value="starsol_faq_" /></td></tr>'."\n";
		echo'<tr><td class="list-cell" colspan="2"><input type="submit" value="Continue" /></td></tr>'."\n";
		echo'</form>'."\n".'</table>'."\n\n";

		admin_footer();

	break;

	case "3":

		admin_header();

		echo'<h1>Starsol FAQ Manager Installer</h1>'."\n\n";

		if (!$_POST[admin_username]){
			echo'<p>Sorry, your admin username provided in step 1 was not specified in your last form post. Please go back and try again.</p>'."\n\n";
			admin_footer();
			exit;
		}
		if (!$_POST[admin_password]){
			echo'<p>Sorry, your admin password provided in step 1 was not specified in your last form post. Please go back and try again.</p>'."\n\n";
			admin_footer();
			exit;
		}
		if (!$_POST[site_name]){
			echo'<p>Sorry, you did not specify your website name. Please go back and try again.</p>'."\n\n";
			admin_footer();
			exit;
		}
		if (!$_POST[site_domain]){
			echo'<p>Sorry, you did not specify the domain name of your website. If you do not have a domain name and are hosted on a static IP address, enter the static IP address.</p>'."\n\n";
			admin_footer();
			exit;
		}
		if (!$_POST[db_location]){
			echo'<p>Sorry, you did not specify the location of your MySQL database. If you do not know this, try entering <i>localhost</i>. Please go back and try again.</p>'."\n\n";
			admin_footer();
			exit;
		}
		if (!$_POST[db_username]){
			echo'<p>Sorry, you did not specify the username for your MySQL database. If you do not know this, try entering the username you use for FTP. Please go back and try again.</p>'."\n\n";
			admin_footer();
			exit;
		}
		if (!$_POST[db_password]){
			echo'<p>Sorry, you did not specify the password for your MySQL database. Please go back and try again.</p>'."\n\n";
			admin_footer();
			exit;
		}
		if (!$_POST[db_database]){
			echo'<p>Sorry, you did not specify the name of your MySQL database. Please go back and try again.</p>'."\n\n";
			admin_footer();
			exit;
		}

		$new_entry = '<?php'."\n\n".'$db_location = "'.$_POST[db_location].'";'."\n".'$db_username = "'.$_POST[db_username].'";'."\n".'$db_password = "'.$_POST[db_password].'";'."\n".'$db_database = "'.$_POST[db_database].'";'."\n".'$db_prefix = "'.$_POST[db_prefix].'";';
		$new_entry .= "\n\n".'$site_name = "'.$_POST[site_name].'";'."\n".'$site_domain = "'.$_POST[site_domain].'";';
		$new_entry .= "\n\n".'$rating_switch = "1";';
		$new_entry .= "\n\n".'$admin_username = "'.$_POST[admin_username].'";'."\n".'$admin_password = "'.$_POST[admin_password].'";'."\n\n".'?>';

		$fl=fopen('faq-variables.php','w'); 
		if (!fwrite($fl,$new_entry)){
			echo'<p>Sorry, an error occured when trying to edit the <i>faq-variables.php</i> file. Please make certain that <i>faq-install.php</i> is in the same directory as <i>faq-variables.php</i>, and that <i>faq-variables.php</i> is chmoded to 666. Then, please go back and try again.</p>'."\n\n";
			admin_footer();
			exit;
		}
		fclose($fl);

		$conn = mysql_connect($_POST[db_location],$_POST[db_username],$_POST[db_password]); 
		if (!$conn) deal_with_mysql_error('MySQL Database Connection Error. '.mysql_error(),'clean'); 
		mysql_select_db($_POST[db_database],$conn) or deal_with_mysql_error('MySQL Database Selection Error. '.mysql_error(),'clean');

		@mysql_query('CREATE TABLE IF NOT EXISTS '.$_POST[db_prefix].'c (uin int(11) NOT NULL auto_increment, name varchar(255) NOT NULL default "", UNIQUE KEY name (name), KEY uin (uin)) TYPE=MyISAM') or deal_with_mysql_error ('Create Categories Table MySQL Error (faq-install.php). '.mysql_error(),'clean');
		@mysql_query('CREATE TABLE IF NOT EXISTS '.$_POST[db_prefix].'q (uin int(11) NOT NULL auto_increment, qu text NOT NULL, an text NOT NULL, category varchar(255) NOT NULL default "", rating int(3) NOT NULL default "0", rc int(11) NOT NULL default "0", KEY uin (uin)) TYPE=MyISAM') or deal_with_mysql_error ('Create Questions Table MySQL Error (faq-install.php). '.mysql_error(),'clean');
		@mysql_query('CREATE TABLE IF NOT EXISTS '.$_POST[db_prefix].'ratings (uin int(11) NOT NULL auto_increment, qu int(11) NOT NULL, rating enum("0","1") default "0", ip varchar(15) NOT NULL default "0.0.0.0", epoch int(11) NOT NULL default "0", KEY uin (uin)) TYPE=MyISAM') or deal_with_mysql_error ('Create Ratings Table MySQL Error (faq-install.php). '.mysql_error(),'clean');

		@mysql_close();

		echo'<p>Congratulations, the Starsol FAQ Manager script has been installed successfully. Please now DELETE <i>faq-install.php</i> from your server.<br /><br />'."\n\n".'<a href="faq-admin.php">Click here</a> to proceed to the administration area.</p>'."\n\n";

		admin_footer();

	break;
}

?>