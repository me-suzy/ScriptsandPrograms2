<?
//displaying the form for user parameters setup
function show_form($descr,$names,$values,$warn_message) {
	echo "
	<form action=index.php method=post>
	<table align=center width=600 cellpadding=0 cellspacing=0 border=1 bgcolor=#cccccc bordercolor=#999999>
	<tr><td colspan=2 align=center><b>Setup Your Data Please</b></td></tr>
	<tr><td colspan=2 align=center>
	File 'config.php' and directories 'resources/compile/' and 'resources/banners/' must be httpd writeable 
	(httpd user can write/delete files in this directory).</td></tr>
	";
	if(isset($warn_message))
		echo "<tr><td colspan=2><p style='color: #ff0000'>$warn_message</p></td></tr>";
		echo "
		<tr>
			<td width=\"250\">Parameter Name</td>
			<td width=\"200\">Parameter Value</td>
		</tr>
		";
	for($i=0; $i<sizeof($names); $i++) {
		echo "
			<tr>
				<td>".$descr[$i]."</td>
				<td><input type=text name='result[".$names[$i]."]' value='".$values[$i]."'></td>
			</tr>
		";
	}
echo "
	<tr>
		<td colspan=2 align=right>
		<input type=hidden name=cmd value=createconfig>
		<input type=submit value=' Install '></td>
	</tr>
	</form>
";
}

//read config.ini and return all data from config.ini
function getParameters($filename,&$descr,&$names,&$values){
	$data = file($filename);
	$i=0;
	while (list ($line_num, $line) = each ($data)) {
		$line  = explode ("//",$line);
		$descr[$i] = trim($line[1]);
		$line  = explode ("=",$line[0]);
		$names[$i]  = trim($line[0]);
		$values[$i] = trim($line[1]);
		$i++;
	}
}

//return 0-if success; 1 - if an error;
function clearDir($dirname){
	$d = dir($dirname);
	while (($entry = $d->read()) !== false) {
		if ($entry == "." || $entry == "..")
			continue;
		$entry=basename($entry);
		$fullname = $dirname."/".$entry;
		
		if (is_file($fullname)){
			if (unlink($fullname)==0)
				return 0;
		} else{
			if (clearDir($fullname)==0)
				return 0;	
			if (rmdir($fullname)==0)
				return 0;
		}
	}
	$d->close();
	
	return 1;
}

function makeBase($db_host,$db_login,$db_passw,$db_name,$filename,&$errors){
	$flag = 1;
	//try to connect to mysql
	$con=mysql_connect($db_host,$db_login,$db_passw);
	if(!$con){
		$flag=0;
		$errors[] = "Bad Parameters to Connection to mySQL!!<br>";
	}
	
	//try to select database
	if($flag){
		if(!mysql_select_db($db_name)){
			$flag=0;
			$errors[] = "Can't Select  $db_name!!<br>";
		}
	}
	
	$querys = file($filename);
	if(!$querys) {
		$flag=0;
		$warn_message = $warn_message."Can't open file $filename!!<br>";
	} else {
		$query = array();
		$i=0;
		$str = "";
		//get querys from dump and put their into $query
		while (list ($line_num, $line) = each ($querys)){
			if((strpos($line,"#") === false) || (strpos($line,"#")!=0)) {
				$str = $str.$line;
				if(strstr($str,";")) {
					$str = str_replace(";","",$str);
					$query[$i]= trim($str);
					$i++;
					$str = "";
				}
			}
			
		}
		
		$success=1;
		for($i=0;$i<sizeof($query);$i++){
			if(strlen($query[$i])>7) {
				$num=mysql_query($query[$i],$con);
				if(!$num) {
					$success=0; 
				}
			}
		}
		
		if(!$success) {
			$flag=0;
			$errors[] = "Can't create database $db_name!!<br>";
		}
	}
	
	if($flag) return 0;
	else return $errors;
}
?>