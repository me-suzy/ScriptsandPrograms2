<?php
/* htaccess authentication script */

/* Uses Apaches builtin .htaccess authentication */

// Authenticate user by username and password.
function authenticate($user, $pass) {
	if ($user && $pass) {
		return true;
	} else {
		return false;
	}
}

// Returns an array with username and password.
// Shamelessly stolen from PHP manual comment by dylan@capebyronimports.com.au
function getUser() {
        $headers = getallheaders();
        $auth = explode( " ", $headers['Authorization'] );
	if ($auth == '') {
	   $auth = explode( " " , $headers[authorization] );
	}

        $authdec = base64_decode( $auth[1] );
        $autharray = explode( ":", $authdec );
        return($autharray);
}

// Shows a login box
function login() {
	
}

?>
