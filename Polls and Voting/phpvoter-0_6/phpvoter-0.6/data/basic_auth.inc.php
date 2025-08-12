<?php
/* Extremely simple authentication script */

/* This is no protection at all, everyone is allowed access. */

// Authenticate user by username and password.
function authenticate($user, $pass) {
	if ($user == "user" && $pass == "pass") {
		return true;
	} else {
		return false;
	}
}

// Returns an array with username and password.
function getUser() {
	$autharray = array("user", "pass");
        return($autharray);
}	

// Shows a login box
function login() {
	
}

?>
