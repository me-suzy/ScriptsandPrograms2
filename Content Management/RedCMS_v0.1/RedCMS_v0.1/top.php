<?php

  session_start();

   /* This script was written by Sam "Rederovski"
   *
   * email: sam@rederovski.co.uk
   * url: www.rederovski.co.uk | www.redcms.co.uk
   *
   * GNU General Public License details http://www.gnu.org/copyleft/gpl.html
   *
   * Desc: RedCMS is a content management script that comes in various modules.
   * See the site for more info.
   *
   * Please refer to readme.txt for installation help
   *
   */

  include"redcms_functions.php";

  require"setup.php";

 // Add your html templates below here!

?>

<link rel="stylesheet" type="text/css" href="<?php echo $site; ?>redStyles/default.css">

<?php

  if($_SESSION['redThemePath'] != NULL) {

?>

<link rel="stylesheet" type="text/css" href="<?php echo $site; ?>redStyles/<?php echo $_SESSION['redThemePath']; ?>">

<?php

  }

?>

<html>
<head>
<title>Rederovski.co.uk</title>

</head>
<body>

<div id="head"><img src="<?php echo $site; ?>/img/logo.gif" alt="rederovski.co.uk"><?php if($l) { echo'<img src="' . $site . 'img/logo_' . $l . '.gif" alt="' . $l . '">'; } ?></div>

<div id="nav">

<a href='<?php echo $site; ?>index.php'>Home</a> &nbsp;
<a href='<?php echo $site; ?>journal.php'>Journal</a> &nbsp;
<a href='<?php echo $site; ?>news.php'>News</a> &nbsp;
<a href='<?php echo $site; ?>members.php'>Members</a> &nbsp;
<a href='<?php echo $site; ?>files.php'>Downloads</a> &nbsp;
<a href='<?php echo $site; ?>stats.php'>Stats</a> &nbsp;

<?php

  if(loggedIn() != 'TRUE') {

    echo "<a href='" . $site . "login.php'>Login?</a> &nbsp;";
    echo "<a href='" . $site . "register.php'>Register</a> &nbsp;";

  } else {

    if($_SESSION['redUserLevel'] == '10') {

       echo "<a href='" . $site . "admin.php'>Admin</a> &nbsp;";

    }

    echo "<a href='" . $site . "messenger.php'>Messenger</a> &nbsp;";
    echo "<a href='" . $site . "profile.php'>My Profile</a> &nbsp;";
    echo "<a href='" . $site . "logout.php'>Logout?</a> &nbsp;";

  }

  echo "<br><br>";

?>



</div>

<div id="main">