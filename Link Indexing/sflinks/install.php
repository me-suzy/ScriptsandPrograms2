<?php
	include('configure.php');
	print "installing...";

	$sql="CREATE TABLE $table (
  	id int(10) NOT NULL auto_increment,
  	name varchar(30) NOT NULL,
  	url varchar(255) NOT NULL,
  	email  varchar(255) NOT NULL,	
  	img varchar(255) NOT NULL,
	PRIMARY KEY  (id),
  	UNIQUE KEY id (id)
	)";

		$result = mysql_query($sql) or print ("Can't create the table '$table' in the database.<br />" . $sql . "<br />" . mysql_error());
	
		if ($result != false) 
			{
			echo "Table '$table' was created!<br><br>\n";
			echo "Now be sure to delete this file from your webspace (install.php) and go add some links!!<br><br>\n";
			}
?>