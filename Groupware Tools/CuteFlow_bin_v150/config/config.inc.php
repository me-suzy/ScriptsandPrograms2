<?php
	//--- database configuration
	$DATABASE_HOST = "localhost";
	$DATABASE_DB = "Cuteflow";
	$DATABASE_PWD = "";
	$DATABASE_UID = "root";	
	
	//--- gui settings
	$DEFAULT_LANGUAGE = "en";
	$OPEN_DETAILS_IN_SEPERATE_WINDOW = true;
	$DEFAULT_SORT_COL = "COL_CIRCULATION_NAME";
	
	$CIRCULATION_COLUMNS = array("COL_CIRCULATION_NAME", "COL_CIRCULATION_STATION", 
									"COL_CIRCULATION_PROCESS_DAYS", "COL_CIRCULATION_PROCESS_START", 
									"COL_CIRCULATION_SENDER");
									
	$SHOW_POSITION_IN_MAIL = true;
	
	//--- days of delay
   	$DELAY_NORMAL = 7;
   	$DELAY_INDERMIDIATE = 10;
   	$DELAY_LATE = 12;
	
	//--- Server information
	$CUTEFLOW_SERVER = "http://10.2.4.6/cuteflow/";
	
	$SMTP_USE_AUTH = "";		//--- "y" for using smtp authentification otherwise ""
	
	$SMTP_SERVER = "192.1.2.168";
	$SMTP_PORT = "25";
	$SMTP_USERID = "m8755-14";
	$SMTP_PWD = "pwd";
	
	//--- Substitute person
	$SEND_TO_AFTER_DAYS = 4;
	
	//--- mail informations
	$SYSTEM_REPLY_ADDRESS = "CuteFlow_System-no_reply_allowed";
	
	$MAIL_ADDTIONALTEXT_DEFAULT = "";
?>