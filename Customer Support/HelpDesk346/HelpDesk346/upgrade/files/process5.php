<?php
	define('DB_PREFIX', $_SESSION['prefix'] . "_");

	if (!isset($_SESSION['dataset']))
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// analysze accounts table
		$res = mysql_query("show columns from " . $_SESSION['prefix'] . "_accounts") or die(mysql_error());
		while ( $row = mysql_fetch_assoc( $res ) )
			$arr[] = $row['Field'];

		//email_addr
		if (!in_array('email_addr', $arr))
			mysql_query("alter table " . $_SESSION['prefix'] . "_accounts add email_addr varchar(255) not null default ''");
			
		 // securityLevel
		 if (!in_array('securityLevel', $arr))
		 	mysql_query("alter table " . $_SESSION['prefix'] . "_accounts add securityLevel int(1) not null default '2'");
		 
		 // phoneNumber
		 if (!in_array('phoneNumber', $arr))
		 	mysql_query("alter table " . $_SESSION['prefix'] . "_accounts add phoneNumber varchar(10) not null default '0'");
		 
		 // phoneExt
		 if (!in_array('phoneExt', $arr))
		 	mysql_query("alter table " . $_SESSION['prefix'] . "_accounts add phoneExt varchar(10) not null default '0'");
		 	
		 // clear the array
		 $arr = array();
		 
		 //////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 // analyze the data table
		 $res = mysql_query("show columns from " . $_SESSION['prefix'] . "_data") or die(mysql_error());
		 while ( $row = mysql_fetch_assoc( $res ) )
		 	$arr[] = $row['Field'];
		 	
		 // phoneNumber
		 if (!in_array('phoneNumber', $arr))
		 	mysql_query("alter table " . $_SESSION['prefix'] . "_data add phoneNumber int(13) not null default '0'");
		 
		 // phoneExt
		 if (!in_array('phoneExt', $arr))
		 	mysql_query("alter table " . $_SESSION['prefix'] . "_data add phoneExt varchar(10) not null default '0'");
		 
		 // ticketVisi
		 if (!in_array('ticketVisi', $arr))
		 	mysql_query("alter table " . $_SESSION['prefix'] . "_data add ticketVisi int(1) not null default '1'");
		 
		 // pageView
		 if (!in_array('pageView', $arr))
		 	mysql_query("alter table " . $_SESSION['prefix'] . "_data add pageView int not null default '0'");
		 
		 // regUser
		 if (!in_array('regUser', $arr))
		 	mysql_query("alter table " . $_SESSION['prefix'] . "_data add regUser int not null default '0'");
		 	
		 $arr = array();
		
		// update the data table approproately
		$cmd = "update " . $_SESSION['prefix'] . "_data set staff = 0 where staff = ''";
		mysql_query($cmd) or die(mysql_error());
		
		// select all the distinct staff members from the data table
		$q = "select distinct staff from " . $_SESSION['prefix'] . "_data where staff <> 0";
		$s = mysql_query($q) or die(mysql_error());
		
		while( $r = mysql_fetch_assoc( $s ) )
		{
			// with this staff name we need to gather the id from accounts
			$uname = mysql_result($s, 0);
			
			// get the information about the user
			$q = "select id from " . $_SESSION['prefix'] . "_accounts where user = '$uname'";
			$set = mysql_query($q) or die(mysql_error());
			if (mysql_num_rows($s)) {
				
				// store the id
				$id = mysql_result($set, 0);
				
				// perform the update
				$cmd = "update " . $_SESSION['prefix'] . "_data set staff = $id where staff = '$uname'";
				mysql_query($cmd) or die(mysql_error());
			}
		}
		
		// alter the columns
		$cmd = "alter table " . $_SESSION['prefix'] . "_data change staff staff int not null default '0'";
        mysql_query($cmd) or die(mysql_error());
        
        $cmd = "alter table " . $_SESSION['prefix'] . "_data change phoneNumber phoneNumber int(13) not null default '0'";
        mysql_query($cmd) or die(mysql_error());
        
        $cmd = "alter table " . $_SESSION['prefix'] . "_data change phoneExt phoneExt int(10) not null default '0'";
        mysql_query($cmd) or die(mysql_error());
	}
	
	if (isset($_POST['submit'])) {
		// add the phone number from global - after verification
		$phone = preg_replace('/[^\d]/', '', $_POST['phone']);
		
		//if (strlen($phone) < 10	) {
		//	$error_msg = "Invalid Phone Number - Must be at least 10 Numeric Digits";	
		//}
		//else {
			$cmd = "update " . $_SESSION['prefix'] . "_accounts set phoneNumber = " . intval($phone) . " where phoneNumber = 0";
			mysql_query($cmd) or die(mysql_error());
			
			unset($_SESSION['dataset']);
			header( "Location: finish.php" );
		//}
	}
?>