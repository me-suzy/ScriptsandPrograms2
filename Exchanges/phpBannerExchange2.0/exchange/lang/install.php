<?
$file_rev="041305";
$file_lang="en";
////////////////////////////////////////////////////////
//                 phpBannerExchange                  //
//                   by: Darkrose                     //
//              (darkrose@eschew.net)                 //
//                                                    //
// You can redistribute this software under the terms //
// of the GNU General Public License as published by  //
// the Free Software Foundation; either version 2 of  //
// the License, or (at your option) any later         //
// version.                                           //
//                                                    //
// You should have received a copy of the GNU General //
// Public License along with this program; if not,    //
// write to the Free Software Foundation, Inc., 59    //
// Temple Place, Suite 330, Boston, MA 02111-1307 USA //
//                                                    //
//     Copyright 2004 by eschew.net Productions.      //
//   Please keep this copyright information intact.   //
////////////////////////////////////////////////////////

// If you translate this file, *PLEASE* send it to me
// at darkrose@eschew.net

//Install and upgrade will share some common variables..
$LANG_yes="Yes";
$LANG_no="No";
$LANG_title="phpBannerExchange Installer";
$LANG_install_verbage="<b>Welcome to the phpBannerExchange 2.0 Installer!</b><p>This script will guide you through the process of installing or upgrading to phpBannerExchange 2.0. This process should be relatively painless and only take a few minutes. At this point, you should be up to step <b>7</b> in the <a href=\"../docs/install.php#quickstart\">install guide</a>. Please refer to this guide if you have any questions regarding the install process.";
$LANG_install_version_found="We have located a previous version of the software! <a href=\"install.php?install=3&page=2\">Click here</a> to upgrade.";
$LANG_install_version_donno="We are unable to locate a config.php file with version branding, so we are assuming this is a clean installation. If this is not the case, please choose an installation method from below.";
$LANG_install_install="New Installation";
$LANG_install_instdesc="Click this link if you have never installed phpBannerExchange on your server or wish to perform a clean install of the script. <b>WARNING:</b> This action will remove any previous phpBannerExchange tables if they exist in the database!";
$LANG_install_upgrade="Upgrade from 1.x to 2.0";
$LANG_install_upgdesc="Use this option to upgrade your current installation of phpBannerExchange 1.2 to the latest version of phpBannerExchange.";

$LANG_install_rcupgrade="Upgrade from 2.0 RCx to 2.0";
$LANG_install_rcupgdesc="Use this option if you have an older version of phpBannerExchange 2.0 installed (such as 2.0 RC1), and you would like to upgrade to the latest version.";

$LANG_varedit_dirs="Define the system variables. These are your global parameters that define things such as your exchange ratio, exchange name, administrator e-mail address, etc. See the <a href=\"../docs/install.php\">installation instructions</a> for details.";

// headers
$LANG_varedit_dbhead="Database Information";
$LANG_varedit_pathing="Paths & Admin Information";
$LANG_varedit_bannerhead="Banners";
$LANG_varedit_anticheathead="Anti-cheat Information";
$LANG_varedit_refncredits="Referrals and Credits";
$LANG_varedit_misc="Miscellaneous Options";

$LANG_varedit_dbhost="Database Host";
$LANG_varedit_dblogin="Database Login";
$LANG_varedit_dbpass="Database Password";
$LANG_varedit_dbname="Database Name";
$LANG_varedit_baseurl="Base Exchange URL";
$LANG_varedit_baseurl_note="do not include trailing slash";
$LANG_varedit_exchangename="Exchange Name";
$LANG_varedit_sitename="Site Name";
$LANG_varedit_adminname="Admin Name";
$LANG_varedit_adminemail="Admin Email";
$LANG_varedit_width="Banner Width";
$LANG_varedit_height="Banner Height";
$LANG_varedit_pixels="pixels";
$LANG_starting_credits="Starting Credits";
$LANG_varedit_imgpos="Exchange Image Position";
$LANG_varedit_duration="Duration";
$LANG_varedit_duration_msg="Seconds..";
$LANG_varedit_showtext="Show Exchange Link";
$LANG_varedit_defrat="Default Ratio";
$LANG_varedit_showimage="Show Exchange Image";
$LANG_varedit_imageurl="Exchange Image URL";
$LANG_varedit_imageurl_msg="full URL required";
$LANG_varedit_sendemail="Send Admin Email";
$LANG_varedit_usepages="Use Page Numbering";
$LANG_varedit_usemd5="Use MD5 Encrypted Passwords";
$LANG_varedit_topnum="Top x will display";
$LANG_varedit_topnum_other="Accounts";
$LANG_varedit_upload="Allow Uploads";
$LANG_varedit_maxsize="Maximum Filesize";
$LANG_varedit_uploadpath="Upload Path (No trailing slashes)";
$LANG_varedit_upurl="Upload directory URL";
$LANG_varedit_referral="Referral Program";
$LANG_varedit_bounty="Referral Bounty";
$LANG_varedit_usegzhandler="Use GZip Handler";
$LANG_varedit_usedbrand="Use mySQL dbrand()";
$LANG_varedit_usedbrand_warn="ONLY mySQL 4+!";
$LANG_varedit_maxbanners="Maximum Banners";
$LANG_varedit_basepath="Base Path";
$LANG_varedit_sellcredits="Sell Credits";
$LANG_varedit_anticheat="Anti-Cheat method";
$LANG_varedit_cookies="Cookies";
$LANG_varedit_db="Database";
$LANG_varedit_none="None";
$LANG_varedit_reqapproval="Require Banner Approval";
$LANG_varedit_usegz="Use gZip/Zend code";
$LANG_varedit_userand="Use mySQL4 rand()";
$LANG_varedit_userandwarn="Requires mySQL 4 or greater";
$LANG_varedit_logclicks="Log Clicks";
$LANG_left="Left";
$LANG_right="Right";
$LANG_top="Top";
$LANG_bottom="Bottom";
$LANG_varedit_reqbanapproval="Require Banner Approval";
$LANG_varedit_dateformat="Date Format";

$LANG_varedit_submit="Submit";
$LANG_varedit_reset="Reset";

$LANG_fput_error_config="The configuration file could not be written. This is usually caused by incorrect permissions or your host does not allow file writes via scripts. Check to insure you have chmod the file to 777. Please contact your hosting provider if you need assistance with this issue or for help with chmodding files.";
$LANG_fput_chmod="The script tried to chmod the file and could not. This could be due to the file not existing in the expected location (check the base_path) variable, or the system is not properly configured to handle chmods from a script. Try chmodding the file manually. Please contact your hosting provider if you need assistance with this issue or for help with chmodding files.";
$LANG_fput_success="The config file has been successfully written! The installer script is now ready to move on to the next part of the install.";
$LANG_db_problem="There was a problem connecting to the database you specified in your config file. Please check your database and insure the <b>$dbname</b> database exists.";
$LANG_db_noconnect="There was a problem connecting to the database! Please insure you have sufficient rights to access the database specified in the configuration file and the password is correct.";

$LANG_tables_created="All tables successfully created!";
$LANG_upgrade_db="Upgrading tables, please wait.";
$LANG_upgrade_done="Tables upgraded!";
$LANG_admin_add_instructions="Create your administrator account. phpBannerExchange supports multiple logins, additional logins can be created by logging in to the Administration Control Panel after the script has been installed.";
$LANG_admin_login="Adminstrator Login";
$LANG_admin_pass="Password";
$LANG_again="Again";
$LANG_password_mismatch="The passwords you entered do not match. Please press the back button and try again.";

$LANG_install_complete="The installation of phpBannerExchange 2.0 is complete! You may now log in to the <a href=\"../admin/\">Administrator Control Panel</a> with your Administrator login and password.<p><b>IMPORTANT! MAKE SURE YOU DELETE THE ENTIRE INSTALL DIRECTORY FROM YOUR SERVER!</b>";

$LANG_continue="Continue";

$LANG_install_oldvarupgrade="<b>Note</b>: You <b>MUST</b> use a different database name if you are upgrading from phpBannerExchange version 1.x to avoid data loss! The installer can import the old account information, BUT it must reside in a separate database!";
$LANG_install_oldverupg="The installer is now ready to upgrade your old accounts. This may take a while depending on how many accounts are in your old database. To continue, enter your old database name below:";
?>