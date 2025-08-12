<html>
<head><title>FunGL install script</title></head>
<body>
<?php
$pear_path = dirname(__FILE__)."/../PEAR/";
// Set the path so we can include the pear files 
if(eregi("^WIN",PHP_OS)){
	// for windows
	set_include_path(".;".$pear_path);
}else{
	// for unix
	set_include_path(".:".$pear_path);
}
require_once 'DB.php';

if($_GET['action'] == 'install'){
	// build dsn
	$dsn = "mysql://".$_POST['user'].":".$_POST['pass']."@".$_POST['host']."/".$_POST['db'];
	
	// is config.php writeable
	if(!is_writable('../config.php')){
		die("config.php is not writeable");
	}
	
	// connect to the databases
	$db = &DB::connect($dsn);
	if (PEAR::isError($db)) {
		echo "Connection to the database failed, check your info: $dsn<br/>";
    	die($db->getMessage());
	}
	echo "Connected to the database<br/>";
	echo "Creating tables in database...<br/>";        
    $handle = fopen ("data.sql", "r");
    $contents = fread ($handle, filesize ("data.sql"));
    $contents = str_replace("__PREFIX__", $_POST['prefix'], $contents);
    sendquery($contents, $db);
    fclose ($handle);
    echo "Tables created!<br/><br/>";
	
	// write the dsn to the config.php
	$handle = fopen ("../config.php", "r");
    $contents = fread ($handle, filesize ("../config.php"));
    $contents = str_replace("__DSN__", $dsn, $contents);
    $contents = str_replace("__PREFIX__", $_POST['prefix'], $contents);
    fclose ($handle);
    
    $handle = fopen ("../config.php", "w");
    fwrite ($handle, $contents);
    fclose ($handle);
	
	echo "you should now be able to use FunGL<br/>";
	echo "A admin user with following info has been created:<br/>";
	echo "Username: admin<br/>";
	echo "Password: admin<br/>";
	echo "<br/>";
	echo "We advise that you change the password of the admin user soon.<br/>";
	echo "Remember to make config.php writeprotected again.<br/>";
	echo "You should delete the install folder from the webserver to avoid breakins";
	
}else{
	?>
	
<h1><a href="http://fungl.com">FunGL</a> install script</h1>
	Make shure that the config.php file is writeable.<br/>
	Enter the database info in the form below<br/>
	When you click install FunGL will be installed.
	<form action="?action=install" method="post">
		<h3>Database setup</h3>
		Database host:<br/>
		<input value="localhost" type="text" name="host"/><br/>
		Database port:<br/>
		<input value="3306" type="text" name="port"/><br/>
		Database username:<br/>
		<input value="" type="text" name="user"/><br/>
		Database password:<br&/>
		<input value="" type="text" name="pass"/><br/>
		Database:<br/>
		<input value="" type="text" name="db"/><br/>
		Table prefix:<br&/>
		<input value="" type="text" name="prefix"/><br/>
		
		<h3>FunGL info</h3>
		
		<input type="submit" name="submit" value="Install FunGL"/>
	</form>
	<?php
}
function sendquery($query, &$db) {
	$array = explode( '; ', $query );
    foreach( $array as $value )  {
    	$value=trim($value);
        if ($value != "") {
        	if( !$res = $db->query( $value )) {
            	if (PEAR::isError($res)) {
    				die($res->getMessage());
				}
            } 
        }
    }
    return $result;
}

?>
</body>
</html>