<?php
	//Revised July 6, 2005
	//Revised by JF
	//Revision Number 9
	
	include_once("../includes/functions.php");
	
	//Constant Definitions
	define('ACCOUNTS_FIELD_NUM', 11);
	define('BLOCKED_EXT_NUM', 1);
	define('BLOCKED_NAME_NUM', 3);
	define('CATEGORIES_FIELD_NUM', 3);
	define('DATA_FIELD_NUM', 23);
	define('EXCESS_FIELD_NUM', 9);
	define("FILES_NUM", 2);
	define('INVENTORY_FIELD_NUM', 6);
	define('RESOLUTION_FIELD_NUM', 5);
	define('SECURITY_FIELD_NUM', 6);
	define('STATUS_FIELD_NUM', 5);
	define('PROBLEM_FIELD_NUM', 3);
	define('SETTINGS_FIELD_NUM', 18);
	define('PRIORITIES_FIELD_NUM', 2);

    $server = $_POST['server'];
    $database = $_POST['database'];
    $databaseName = $_POST['databaseName'];
    $databasePassword = $_POST['databasePassword'];
    $databasePrefix = $_POST['databasePrefix'];
    $databasePrefix = !preg_match('/_$/', $databasePrefix) ? $databasePrefix."_" : $databasePrefix;
	
	if (@mysql_connect($server,$database,$databasePassword))
    {
        //Valid Login
        if (!@mysql_select_db($databaseName)) {
             mysql_query("create database $databaseName") or die(mysql_error());
             mysql_select_db($databaseName);
        }
        
		$fp=fopen("../config.php",'w+');
		fwrite($fp, "<?php\n
				define('DB_PREFIX', '".$databasePrefix."');\n
				define('DB_HOST', '" . $server . "');\n
				define('DB_UNAME', '" . $database . "');\n
				define('DB_DBNAME', '" . $databaseName . "');\n
				define('DB_PASS', '" . $databasePassword . "');
			?>");
      fclose($fp);

        
        //Create Tables
        // create an array of the tables in the database $databaseName
        $tables=mysql_list_tables($databaseName);
		$flag=0;
		while ($r=mysql_fetch_row($tables)) {
         $flag=1;
 	     $list[]=$r[0];
        }
       if($flag==0) 
	   $list[]=array();
	   
	   print "<pre>";
	   
        echo "Creating Table Accounts.....";
        if (!in_array($databasePrefix."accounts",$list)) {
            $cmd="create table ".$databasePrefix."accounts (
            ID int not null  primary key auto_increment,
            User varchar(255) not null default '',
            Pass varchar(255) not null default '',
            FirstName varchar(255) not null default '',
            LastName varchar(255) not null default '',
            ComputerName varchar(255) not null default '',
            HelpDeskAddress blob not null,
            email_addr varchar(255) not null default '',
            securityLevel int(1) not null default '2',
            phoneNumber varchar(10) not null default '9999999999',
            phoneExt varchar(10) not null default '0'
            )";
            if (mysql_query($cmd)) {
                echo "Completed\n";
            } else {
                echo "Failed\n".mysql_error();
                exit;
            }
        } else {
            echo "Table Exists\n";
            
            //Check for each column indiviudally using an array
            $field_resource = mysql_query("show columns from ". $databasePrefix . "accounts");
            while ($arr = mysql_fetch_assoc($field_resource))
            	$field_array[] = $arr['Field'];
            	
            //Begin Field Checks
            if (count($field_array) != ACCOUNTS_FIELD_NUM)
            {
            	if (!in_array('ID', $field_array))		
            		@mysql_query("alter table " . $databasePrefix . "accounts add ID int not null primary key auto_increment");
            	if (!in_array('User', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "accounts add User varchar(255) not null default ''");
            	if (!in_array('Pass', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "accounts add Pass varchar(255) not null default ''");
            	if (!in_array('FirstName', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "accounts add FirstName varchar(255) not null default ''");
            	if (!in_array('LastName', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "accounts add LastName varchar(255) not null default ''");
            	if (!in_array('ComputerName', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "accounts add ComputerName varchar(255) not null default ''");
            	if (!in_array('HelpDeskAddress', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "accounts add HelpDeskAddress blob not null");
            	if (!in_array('email_addr', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "accounts add email_addr varchar(255) not null default ''");
            	if (!in_array('securityLevel', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "accounts add securityLevel int(1) not null default '2')");
            	if (!in_array('phoneNumber', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "accounts add phoneNumber varchar(10) not null default '9999999999'");
            	if (!in_array('phoneExt', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "accounts add phoneExt varchar(10) not null default '0'");
            }
        }
        echo "\n";
        
        echo "Creating Table Blocked Filenames.....";
        if (!in_array($databasePrefix . "blocked_fnames", $list)) {
        	$cmd = "create table " . $databasePrefix . "blocked_fnames (
        		stringValue varchar(30) not null default '',
        		position int(1) not null default '0',
        		id int not null auto_increment,
        		primary key(id)
        		)";
        	if (mysql_query($cmd)) {
        		echo "Completed\n";
        	}
        	else {
        		echo "Failed\n";
        	}
        }
        else {
        	echo "Table Exists\n";
        	
        	//Check for each column indiviudally using an array
            $field_resource = mysql_query("show columns from ". $databasePrefix . "blocked_fnames");
            while ($arr = mysql_fetch_assoc($field_resource))
            	$field_array[] = $arr['Field'];
            	
            //Begin Field Checks
            if (count($field_array) != BLOCKED_NAME_NUM)
            {
            	//nothing here yet - this is a new feature
            	
            	if (!in_array('id', $field_array))		
            		@mysql_query("alter table " . $databasePrefix . "blocked_fnames add id int not null primary key auto_increment");
            	if (!in_array('stringValue', $field_array))		
            		@mysql_query("alter table " . $databasePrefix . "blocked_fnames add stringValue varchar(30) not null default ''");
            	if (!in_array('position', $field_array))		
            		@mysql_query("alter table " . $databasePrefix . "blocked_fnames add position int(1) not null default '0'");
            }
        }
        
        echo "Creating Table Blocked File Extension.....";
        if (!in_array($databasePrefix . "blocked_fexts", $list)) {
        	$cmd = "create table " . $databasePrefix . "blocked_fexts (
        		stringValue varchar(30) not null default '' )";
        	if (mysql_query($cmd)) {
        		echo "Completed\n";
        	}
        	else {
        		echo "Failed\n";	
        	}
        }
        else {
        	echo "Table Exists\n";
        	
        	//Check for each column indiviudally using an array
            $field_resource = mysql_query("show columns from ". $databasePrefix . "blocked_fexts");
            while ($arr = mysql_fetch_assoc($field_resource))
            	$field_array[] = $arr['Field'];
            	
            //Begin Field Checks
            if (count($field_array) != BLOCKED_EXT_NUM)
            {
            	//nothing here yet - this is a new feature
            }
        }
        echo "\n";
        
        echo "Creating Table Categories......";
        if (!in_array($databasePrefix . "categories", $list)) {
        	$cmd = "create table " . $databasePrefix . "categories (
        		id int not null primary key auto_increment,
        		name varchar(30) not null default '',
        		priority int not null default '1')";
        	if (mysql_query($cmd)) {
        		echo "Completed";	
        	}
        	else {
        		echo " Failed";	
        	}
        	echo "\n";
        }
        else {
        	echo "Table Exists";
        	$field_resource = mysql_query("show columns from " . $databasePrefix . "categories");
        	while ($arr = mysql_fetch_assoc($field_resource))
        		$field_array[] = $arr;
        	
        	if (count($field_array) != CATEGORIES_FIELD_NUM)
        	{
        		//this is a new table - no upgrading needed 	
        	}
        }
        echo "\n";
		
        echo "Creating Table Data.....";
        if (!in_array($databasePrefix."data",$list)) {
            $cmd ="create table ".$databasePrefix."data (
            ID int not null primary key auto_increment,
            FirstName varchar(255) not null default '',
			EMail varchar(255) not null default '',
            LastName varchar(255) not null default '',
            category int not null default '0',
            descrip blob not null,
            status int not null default '0',
            staff int not null default '0',
            mainDate varchar(255) not null default '',
            priority int not null default '1',
			platform VARCHAR(50) NOT NULL,
			os VARCHAR(50) NOT NULL,
			ipaddress VARCHAR(50) NOT NULL,
			browser VARCHAR(50) NOT NULL,
			bversion VARCHAR(50) NOT NULL,
			uastring VARCHAR(50) NOT NULL,
			partNo int null,
			phoneNumber varchar(10) null default '9999999999',
			phoneExt varchar(10) null default '',
			`ticketVisi` int(1) NOT NULL default '1',
			pageView int not null default '0',
			regUser int not null default '0'
			)";
            if (mysql_query($cmd)) {
                echo "Completed\n";
            } else {
                echo "Failed\n".mysql_error();
                exit;
            }
        } else {
            echo "Table Exists\n";
            
            $field_resource = mysql_query("show columns from ". $databasePrefix . "data");
            $field_array = array();
            while ($arr = mysql_fetch_assoc($field_resource))
            	$field_array[] = $arr['Field'];
            if (count($field_array) < DATA_FIELD_NUM)
            {
	            if (!in_array('ID', $field_array))
	            	@mysql_query("alter table " . $databasePrefix . "data add ID int not null primary key auto_increment");
	            if (!in_array('FirstName', $field_array))
	            	@mysql_query("alter table " . $databasePrefix . "data add FirstName varchar(255) not null default ''");
	            if (!in_array('EMail', $field_array))
	            	@mysql_query("alter table " . $databasePrefix . "data add EMail varhcar(255) not null default ''");
	            if (!in_array('LastName', $field_array))
	            	@mysql_query("alter table " . $databasePrefix . "data add LastName varhcar(255) not null default ''");
	            if (!in_array('catagory', $field_array))
	            	@mysql_query("alter table " . $databasePrefix . "data add category int not null default '0'");
	            if (!in_array('descrip', $field_array))
	            	@mysql_query("alter table " . $databasePrefix . "data add descrip blob not null");
	            if (!in_array('status', $field_array))
	            	@mysql_query("alter table " . $databasePrefix . "data add status int not null default '0'");
	            if (!in_array('staff', $field_array))
	            	mysql_query("alter table  " . $databasePrefix . "data add staff int not null default '0'");
	            if (!in_array('mainDate', $field_array))
	            	@mysql_query("alter table " . $databasePrefix . "data add mainDate varchar(255) not null default ''");
	            if (!in_array('priority', $field_array))
	            	@mysql_query("alter table " . $databasePrefix . "data add priority int not null default '1'");
	            if (!in_array('platform', $field_array))
	            	@mysql_query("alter table " . $databasePrefix . "data add platform varhcar(50) not null");
	            if (!in_array('os', $field_array))
	            	@mysql_query("alter table " . $databasePrefix . "data add os varchar(50) not null");
	            if (!in_array('ipaddress', $field_array))
	            	@mysql_query("alter table " . $databasePrefix . "data add ipaddress varchar(50) not null");
	            if (!in_array('browser', $field_array))
	            	@mysql_query("alter table " . $databasePrefix . "data add browser varchar(50) not null");
	            if (!in_array('bversion', $field_array))
	            	@mysql_query("alter table " . $databasePrefix . "data add bversion varchar(50) not null");
	            if (!in_array('uastring', $field_array))
	            	@mysql_query("alter table " . $databasePrefix . "data add uastring varchar(50) not null");
	            if (!in_array('partNo', $field_array))
	            	@mysql_query("alter table " . $databasePrefix . "data add partNo int null");
	            if (!in_array('phoneNumber', $field_array))
	            	@mysql_query("alter table " . $databasePrefix . "data add phoneNumber varchar(10) null default '9999999999'");
	            if (!in_array('phoneExt', $field_array))
	            	@mysql_query("alter table " . $databasePrefix . "data add phoneExt varchar(10) null default ''");
	            if (!in_array('ticketVisi', $field_array))
	            	@mysql_query("alter table " . $databasePrefix . "data add `ticketVisi` int(1) NOT NULL default '1'");
	            if (!in_array('pageView', $field_array))
	            	@mysql_query("alter table " . $databasePrefix . "data add pageView int NOT NULL default '0'");
            	if (!in_array('regUser', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "data add regUser int not null default '0'");
            }	
        }
        //these are lesser upgrades - column changes
        @mysql_query("alter table " . $databasePrefix . "data change PCatagory category int not null default '1'");
        @mysql_query("alter table " . $databasePrefix . "data change priority priority int not null default '0'");
        @mysql_query("alter table " . $databasePrefix . "data change status status int not null default '0'");
        echo "\n";
        
        echo "Creating Table Excess.....";
        if (!in_array($databasePrefix."excess",$list)) {
            $cmd ="create table ".$databasePrefix."excess (
            ID int not null  primary key auto_increment,
            FirstName varchar(255) not null default '',
            LastName varchar(255) not null default '',
            partNum blob not null,
            serial blob not null,
            location blob not null,
            descrip blob not null,
            date varchar(255) not null default '0',
            `price` decimal(10,2) NOT NULL default '0.00')";
            if (mysql_query($cmd)) {
                echo "Completed\n";
            } else {
                echo "Failed\n".mysql_error();
                exit;
            }
        } else {
            echo "Table Exists\n";
            $field_resource = mysql_query("show columns from ". $databasePrefix . "excess");
            $field_array = array();
            while ($arr = mysql_fetch_assoc($field_resource))
            	$field_array[] = $arr['Field'];
            	
            if (count($field_array) < EXCESS_FIELD_NUM)
            {
            	if (!in_array('ID', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "excess add ID int not null primary key auto_increment");
            	if (!in_array('FirstName', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "excess add FirstName varchar(255) not null default ''");
            	if (!in_array('LastName', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "excess add LastName varchar(255) not null default ''");
            	if (!in_array('partNum', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "excess add partNum blob not null");
            	if (!in_array('serial', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "excess add serial blob not null");
            	if (!in_array('location', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "excess add location blob not null");
            	if (!in_array('descrip', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "excess add descrip blob not null");
            	if (!in_array('date', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "excess add date varchar(255) not null default '0'");
            	if (!in_array('price', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "excess add `price` decimal(10,2) NOT NULL default '0.00')");
            }
        }
        echo "\n";
        
        echo "Creating Table Files.........";
        if (!in_array($databasePrefix."files",$list)) {
        	$cmd = "create table " . $databasePrefix . "files (
        	id int not null default '0',
        	name varchar(255) not null default '',
        	primary key(id, name)
        	)";
        	if (mysql_query($cmd)) {
                echo "Completed\n";
            } else {
                echo "Failed\n".mysql_error();
                exit;
            }
        } else {
            echo "Table Exists\n";
            $field_resource = mysql_query("show columns from ". $databasePrefix . "files");
            $field_array = array();
            while ($arr = mysql_fetch_assoc($field_resource))
            	$field_array[] = $arr['Field'];
            	
            if (count($field_array) < FILES_NUM)
            {
            	//this is not yet needed, cause they dont have this as of yet
            	if (!in_array('id', $field_array))
            		mysql_query("alter table " . DB_PREFIX . "files add id int not null primary key");
            	if (!in_array('name', $field_array))
            		mysql_query("alter table " . DB_PREFIX . " add name varchar(255) not null default '' primary key");
            }	
        }

        echo "Creating Table Inventory.....";
        if (!in_array($databasePrefix."inventory",$list)) {
            $cmd="create table ".$databasePrefix."inventory (
            ID int not null primary key auto_increment,
            UserName varchar(255) not null default '',
            FirstName varchar(255) not null default '',
            LastName varchar(255) not null default '',
            CompName varchar(255) not null default '',
            Office varchar(255) not null default '')";
            if (mysql_query($cmd)) {
                echo "Completed\n";
            } else {
                echo "Failed\n".mysql_error();
                exit;
            }
        } else {
            echo "Table Exists\n";
            $field_resource = mysql_query("show columns from ". $databasePrefix . "inventory");
            while ($arr = mysql_fetch_assoc($field_resource))
            	$field_array[] = $arr['Field'];
            	
            if (count($field_array) < INVENTORY_FIELD_NUM)
            {
            	if (!in_array('ID', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "inventory add ID int not null primary key auto_increment");
            	if (!in_array('UserName', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "inventory add UserName varchar(255) not null default ''");
            	if (!in_array('FirstName', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "inventory add FirstName varchar(255) not null default ''");
            	if (!in_array('LastName', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "inventory add LastName varchar(255) not null default ''");
            	if (!in_array('CompName', $databasePrefix))
            		@mysql_query("alter table " . $databasePrefix . "inventory add CompName varchar(255) not null default ''");
            	if (!in_array('Office', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "inventory add Office varchar(255) not null default ''");
            }
        }
        echo "\n";
		
        echo "Creating Table Resolution.....";
        if (!in_array($databasePrefix."resolution",$list)) {
            $cmd="CREATE TABLE ".$databasePrefix."resolution (
  				resid int(11) NOT NULL auto_increment,
  				id int(11) NOT NULL default '0',
  				solution text NOT NULL,
  				resdate varchar(50) NOT NULL default '',
  				PRIMARY KEY  (resid)
			)";
            if (mysql_query($cmd)) {
                echo "Completed\n";
            } else {
                echo "Failed\n".mysql_error();
                exit;
            }
        } else {
            echo "Table Exists\n";
            $field_resource = mysql_query("show columns from ". $databasePrefix . "resolution");
            $field_array = array();
            while ($arr = mysql_fetch_assoc($field_resource))
            	$field_array[] = $arr['Field'];
            	
            if (count($field_array) < RESOLUTION_FIELD_NUM)
            {
            	if (!in_array('resid', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "resolution add resid int(11) not null auto_increment primary key");
            	if (!in_array('id', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "resolution add id int(11) not null default '0'");
            	if (!in_array('solution', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "resolution add solution text not null");
            	if (!in_array('resdate', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "resolution add resdate varchar(50) not null default ''");	
            }
        }
        echo "\n";
	   
	   
        echo "Creating Table Security.....";
        if (!in_array($databasePrefix."security",$list)) {
            $cmd="create table ".$databasePrefix."security (
            ID int not null primary key auto_increment,
            Name1 blob not null,
            Name2 blob not null,
            Date varchar(255) not null default '0',
            Descrip blob not null)";
            if (@mysql_query($cmd)) {
                echo "Completed\n";
            } else {
                echo "Failed\n".mysql_error();
                exit;
            }
        } else {
            echo "Table Exists\n";
            $field_resource = mysql_query("show columns from ". $databasePrefix . "security");
            $field_array = array();
            while ($arr = mysql_fetch_assoc($field_resource))
            	$field_array[] = $arr['Field'];
            	
            if (count($field_array) < SECURITY_FIELD_NUM)
            {
            	if (!in_array('ID', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "Security add ID int not null primary key auto_increment");
            	if (!in_array('Name1', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "Security add Name1 blob not null");
            	if (!in_array('Name2', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "Security add Name2 blob not null");
            	if (!in_array('Date', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "Security add Date varchar(255) not null default '0'");
            	if (!in_array('Descrip', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "Security add Descrip blob not null");
            }
        }
        echo "\n";
		
        echo "Creating Table Settings.....";
        $size = DetermineSize(ini_get('upload_max_filesize'));	
        if (!in_array($databasePrefix."settings",$list)) {
            $cmd="CREATE TABLE `".$databasePrefix."settings` (
            	 `navigation` CHAR(1) NOT NULL default 'B',
            	 `helpdesk` CHAR(1) NOT NULL default 'S',
                 `result_page` INT NOT NULL default '10',
                 `hdticket` INT(1) NOT NULL ,
                 `hdemail` INT(1) NOT NULL default '1',
                 `email_type` INT(1) NOT NULL default '1',
                 `req_image` INT(1) NOT NULL default '1',
                 `hdemail_up` INT(1) NOT NULL default '1',
                 `hdemail_create` int(1) not null default '1',
                 `hdemail_close` int(1) not null default '1',
                 `ticketAccessModify` int(1) NOT NULL default '0',
                 `show_kb` int(1) not null default '1',
                 `allow_enduser_reg` int(1) NOT NULL default '1',
                 max_file_size bigint not null default '$size',
                 enable_file_blocking int(1) not null default '1',
                 user_defined_priorities int(1) not null default '0',
                 ticket_lookup int(1) not null default '1',
                 HD_from varchar(50) not null default ''
                 )";
            if (mysql_query($cmd)) {
                echo "Completed\n";
            } else {
                echo "Failed\n".mysql_error();
                exit;
            }
        } else {
            echo "Table Exists\n";
            $field_resource = mysql_query("show columns from ". $databasePrefix . "settings");
            $field_array = array();
            while ($arr = mysql_fetch_assoc($field_resource))
            	$field_array[] = $arr['Field'];
            if (count($field_array) < SETTINGS_FIELD_NUM)
            {
            	if (!in_array('navigation', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "settings add `navigation` char(1) not null default 'B'");
            	if (!in_array('helpdesk', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "settings add `helpdesk` char(1) not null");
            	if (!in_array('result_page', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "settings add `result_page` int not null default '5'");
            	if (!in_array('hdticket', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "settings add `hdticket` int(1) not null");
            	if (!in_array('hdemail', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "settings add `hdemail` int(1) not null");
            	if (!in_array('email_type', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "settings add `email_type` int(1) not null");
            	if (!in_array('req_image', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "settings add `req_image` int(1) not null");
            	if (!in_array('hdemail_up', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "settings add `hdemail_up` int(1) not null");
            	if (!in_array('hdemail_create', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "settings add `hdemail_create` int(1) not null default '0'");
            	if (!in_array('hdemail_close', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "settings add `hdemail_close` int(1) not null default '0'");
            	if (!in_array('ticketAccessModify', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "settings add `ticketAccessModify` int(1) not null default '0'");
            	if (!in_array('show_kb', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "settings add `show_kb` int(1) not null default '1'");
            	if (!in_array('allow_enduser_reg', $field_array))
            		 @mysql_query("alter table " . $databasePrefix . "settings add `allow_enduser_reg` int(1) NOT NULL default '1'");
            	if (!in_array('max_file_size', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "settings add max_file_size bigint not null default '$size'");
            	if (!in_array('enable_file_blocking', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "settings add enable_file_blocking int(1) not null default '1'");
            	if (!in_array('user_defined_priorities', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "settings add user_defined_priorities int(1) not null default '0'");
            	if (!in_array('ticket_lookup', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "settings add ticket_lookup int(1) not null default '1'");
            	if (!in_array('HD_from', $field_array))
            		@mysql_query("alter table " . $databasePrefix . "settings add HD_from varchar(50) not null default ''");
            }
        }
        echo "\n";
        
        echo "Creating Table Status......";
        if (!in_array($databasePrefix . "status", $list)) {
        	$cmd = "create table " . $databasePrefix	 . "status (
        	id int not null auto_increment primary key,
        	name varchar(255) not null default '',
        	position int not null default '0',
        	icon varchar(10) not null default '',
        	color varchar(10) not null default ''
        	)";
        	
        	if (mysql_query($cmd)) {
        		echo "Completed\n";
        		$cmd = "insert into " . $databasePrefix . "status values(1, 'New', 1, 'red.jpg', 'red'), (2, 'Open', 2, 'yellow.jpg', 'yellow'), (3, 'Closed', 3, 'green.jpg', 'green')";
        		mysql_query($cmd) or die(mysql_error());
        	}
        	else
        		echo "Failed " . mysql_error() . "\n";
        }
        else {
        	echo "Table Exists\n";
        	while ($arr = mysql_fetch_assoc($field_resource))
            	$field_array[] = $arr['Field'];
            	
            if (count($field_array) != STATUS_FIELD_NUM) {
            	if (!in_array('id', $field_array))
            		mysql_query("alter table " . $databasePrefix . "status add id int not null primary key auto_increment");
            	if (!in_array('name', $field_array))
            		mysql_query("alter table " . $databasePrefix . "status add name varchar(255) not null default ''");
            	if (!in_array('position', $field_array))
            		mysql_query("alter table " . $databasePrefix . "status add position int not null default '0'");
            	if (!in_array('icon', $field_array))
            		mysql_query("alter table " . $databasePrefix . "status add icon varchar(10) not null default ''");
            	if (!in_array('color', $field_array))
            		mysql_query("alter table " . $databasePrefix . "status add color varchar(10) not null default ''");
            }
        }
        echo "\n";
		
         echo "Creating Table priorities.....";
        if (!in_array($databasePrefix."priorities",$list)) {
            $cmd="CREATE TABLE `".$databasePrefix."priorities` (
			`pid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
			`priority` VARCHAR(50) NOT NULL,
			severity int not null default '1'
			)"; 
            if (mysql_query($cmd)) {
                echo "Completed\n";
                
                //insert our default 3 priorities
                $cmd = "insert into " . $databasePrefix . "priorities(priority, severity) values('Low', 1), ('Medium', 2), ('High', 3)";
                mysql_query($cmd) or die(mysql_error());
            } else {
                echo "Failed\n".mysql_error();
            }
        } else {
            echo "Table Exists\n";
            $field_resource = mysql_query("show columns from ". $databasePrefix . "priorities");
            $field_array = array();
            while ($arr = mysql_fetch_assoc($field_resource))
            	$field_array[] = $arr['Field'];
			if (count($field_array))
			{
				if (!in_array('pid', $field_array))
					@mysql_query("alter table " . $databasePrefix . "priorities add `pid` int not null auto_increment primary key");
				if (!in_array('priority', $field_array))
					@mysql_query("alter table " . $databasePrefix . "priorities add `priority` varchar(50) not null");
			}
        }
        echo "\n";
		
        echo "Database Setup Complete";
	}
    else
    {
        //Invalid Login
        $msg="Invalid Login - Please Try Again";
    }
    
    print "</pre>";
?>
<body>
<div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Click Here 
  to Continue to the <a href="accountsetup.php"><br>
  Accounts Setup Page (Step 2 of the Setup Process).</a><br/>
  If you are upgrading you may stop here, unless you wish to add new accounts</font> </div>
</body>