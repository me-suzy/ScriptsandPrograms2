<?php include('config.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>FunGL</title>
<link rel="stylesheet" type="text/css" href="screenstyle.css" />
</head>
<body>
<div class="container">

<div class="titleblock">&nbsp;</div>

<div><ul class="navbar">
	<?php
	
//Copyright 2005 Fungl.com Do not resells or redistribute.
// 
// see http://fung.com or http://fungl.com/download/ for details
// Oh and Don’t resell or redistribute this software.
	
		switch($userpref->getPref($user->getUsername(), 'lvl')){
			case USER_LVL: 
				include('menu/user.php');
				break;
			case ADMIN_LVL:
				include('menu/admin.php');
				break;
			case SUPERADMIN_LVL:
				include('menu/admin.php');
				break;
			default:
				include('menu/guest.php');	
		}
	?>
</ul></div>

<div class="rightcontainer">
<?php
switch($_GET['page']){
	case 'signup':
		include('help/signup.php');
		break;
	case 'users':
		include('help/users.php');
		break;
	case 'project':
		include('help/project.php');
		break;
	case 'poll':
		include('help/poll.php');
		break;
	case 'question':
		include('help/question.php');
		break;
}
?>
  	<!-- <div class="rightbox linkbox">
  		<h2>Links</h2>
  		
  	</div> -->
</div>

<div class="content">
<?php
//Copyright 2005 Fungl.com Do not resells or redistribute.
// 
// see http://fung.com or http://fungl.com/download/ for details
// Oh and Don’t resell or redistribute this software.

switch($_GET['page']){
	case 'login':
		include('pages/login.php');
		break;
	case 'users':
		include('pages/users.php');
		break;
	case 'home':
		include('pages/home.php');
		break;
	case 'signup':
		include('pages/signup.php');
		break;
	case 'project':
		include('pages/project.php');
		break;
	case 'poll':
		include('pages/poll.php');
		break;
	case 'signup':
		include('pages/signup.php');
		break;
	case 'account':
		include('pages/account.php');
		break;
	case 'question':
		include('pages/question.php');
		break;
	default:
		include('pages/home.php');
}
?>
</div>

<div class="footer">
  <div class="right">
      <p>&copy; 2005 <a href="http://fungl.com/download/">FunGL Corp</a>.</p>
    </div>
    <p>&nbsp;</p>
    </div>

</div>
</body>
</html>