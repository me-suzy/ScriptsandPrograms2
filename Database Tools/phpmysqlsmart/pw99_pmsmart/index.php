<?session_start();?>
<!--
**********************************************
* 2005 by Alexander Ottitzky
* http://www.powerweb99.at
* for free use, no warranty at all
* please do not remove copyright
**********************************************
-->
<html>
<head>
<title>PHP MySQL Smart by PowerWeb99.at</title>
<style>
.dbs{
	font-weight: normal; 
	color: black;
	font-size: 12px; 
	font-family: Verdana; 
	text-decoration: none;
}
.dbs a{
	font-weight: normal; 
	color: black;
	font-size: 12px; 
	font-family: Verdana; 
	text-decoration: none;
}	
.tbs{
	font-weight: normal; 
	color: black;
	font-size: 10px; 
	font-family: Verdana; 
	text-decoration: none;
}
.tbs a{
	font-weight: normal; 
	color: black;
	font-size: 10px; 
	font-family: Verdana; 
	text-decoration: none;
}	
.fls{
	font-weight: normal; 
	color: black;
	font-size: 10px; 
	font-family: Verdana; 
	text-decoration: none;
}
.fls a{
	font-weight: normal; 
	color: black;
	font-size: 10px; 
	font-family: Verdana; 
	text-decoration: none;
}	
.buttons {
	font-family:Arial;
	font-size:11;
	border-style:solid;
	border-width:1px;
	border-color:black;
	background:#F2F2F2; 
	margin:1px;
	height:25px;
	width:100%;
	v-align:middle
}
</style>

<script>
function add2board(a){
	document.me.sql.value = document.me.sql.value + a;
}
</script>
</head>
<body bgcolor=white>
<?
$user = $_REQUEST[user];
$pass = $_REQUEST[pass];
$host = $_REQUEST[host];

if($_REQUEST[disconnect]=="disconnect"){
	unset($_REQUEST);
	session_unset();
	session_destroy();	
}

if($_SESSION[LOGIN] || ( $user && $host)){
	if(!$user) $user = $_SESSION[LOGIN][USER];
	if(!$pass) $pass = $_SESSION[LOGIN][PASS];
	if(!$host) $host = $_SESSION[LOGIN][HOST];
	$con = @mysql_connect($host, $user, $pass) or die("<center><b>Connection Error</b></center>");
}

function usedtime() {
	list($msec,$sec)=explode(' ',microtime());
	return $msec+$sec;
}

$tstart=usedtime();

function dblist($db=false, $tb=false){
	global $con;
	$a="<table border=0 width=100% class=dbs>";
	$result = mysql_list_dbs($con);
	while ($rs = mysql_fetch_object($result)) {
		$b="";
		$tmp = $rs->Database;
		if($db==$rs->Database) {
			$tmp="<b>$tmp</b>";
			$b.=tablelist($db, $tb);
		}
		$a.="<tr><td><a href='index.php?db=$rs->Database' title='select database'>$tmp</a><a href='javascript:add2board(\" $rs->Database \")' title='write dbname to sql-box'> &raquo;</a>$b</td></tr>\n";		
	}
	mysql_free_result($result);	  	
	$a.="</table>";
	return $a;
}

function tablelist($db, $tb=false){
	global $con;
	mysql_select_db($db,$con) or die("select error!");
	$a="<table border=0 width=100% class=tbs cellspacing=0 cellpadding=2>";
	$result = mysql_list_tables($db,$con);	
	for($i=0; $i < mysql_num_rows($result); $i++) {
		$b="";
		$table = mysql_tablename($result,$i);
		$tmp = $table;
		if($tb==$table) {
			$b.=fieldlist($db, $tb);
			$tmp="<b>$tmp</b>";
			
		}
		$a.="<tr><td width=5> </td><td width=*><a href='index.php?db=$db&tb=$table' title='select table'>$tmp</a><a href='javascript:add2board(\" $table \")' title='write tablename to sql-box'> &raquo;</a>$b</td></tr>\n";
	}
	$a.="</table>";
	mysql_free_result($result);
	return $a;
}

function fieldlist($db, $tb){
	global $con;
	mysql_select_db($db,$con) or die("select error!");
    $result = mysql_query("select * from $tb limit 1") or die("error!");
    $rs = mysql_fetch_object($result);
    $a="<table border=0 width=100% class=fls>";
    for ($i=0;$i<mysql_num_fields($result); $i++){
        $feld = mysql_field_name($result,$i);
        $len = mysql_field_len($result,$i);
        $type = mysql_field_type($result,$i);
        $flag = mysql_field_flags($result,$i);
        $a.=("<tr><td width=5>&nbsp;</td><td width=*>$feld<a href='javascript:add2board(\" $feld \")' title='write fieldname to sql-box'> &raquo;</a><br><i>$type($len),$flag</i></td></tr>\n");

    }
	$a.="</table>";
	mysql_free_result($result);
	return $a;
}

function backup($table){
	global $con;				
	$a ="DELETE FROM $table;\n";
	$sql= ("select * from $table");
	$result = mysql_query($sql) or die("SQL ERROR:<hr>".$sql);
	while($rs = mysql_fetch_object($result)) {		         
		$b = ("insert into $table values (");
		foreach($rs as $tmp1 => $key){
			$key=str_replace("\n","<br>",$key);
			$key=str_replace("\r","<br>",$key);
			$b.= "'".addslashes($key)."',";
		}
		$a.= substr($b,0,strlen($b)-1).");\n";
	}
	mysql_free_result($result);
	$a."\n";	
	return $a;			
}

if ($con){
	$_SESSION[LOGIN][USER] = $user;
	$_SESSION[LOGIN][PASS] = $pass;
	$_SESSION[LOGIN][HOST] = $host;
	$current_database = $_REQUEST[db];
	$current_table = $_REQUEST[tb];
	$sql = stripslashes($_REQUEST[sql]);
	$max = $_REQUEST[max];
	if(!$max || !is_numeric($max)) $max=50;
	if(!$maxrow || !is_numeric($maxrow)) $maxrow=200;
	
	echo("<table width=100% border=0>");

    
    echo("<tr><td colspan=2 bgcolor=#f1f1f1 valign=top class=dbs align=right><b>PHP MySQL Smart 2.0</b><hr size=1 color=black></td></tr>");
	echo("<tr><td width=20% bgcolor=#f1f1f1 valign=top class=dbs>");
	echo(dblist($current_database, $current_table));
	echo("<hr size=1 color=#EFC986>click on  &raquo; to drop name to sql-box</td><td width=80% valign=top align=center class=tbs><form name=me method=post action=index.php?db=$current_database&tb=$current_table>");
	
	if($current_database){
		$backup = "<input type=submit value='backup database' name='backup' class=buttons>";
		if($current_table) $backup = "<input type=submit value='backup table' name='backup' class=buttons>";
		echo("
		<table width=100% class=tbs  border=0 cellspacing=0 cellpadding=2>
		<tr><td rowspan=5 width=75% bgcolor=EFC986><textarea name=sql style='width:100%' rows=7>$sql</textarea></td>
		<td width=25% bgcolor=#f1f1f1><input type=submit value='execute sql query' name=execute class=buttons></td>
		</tr>
		<tr><td align=center bgcolor=#f1f1f1>$backup</td></tr>
		<tr><td align=center bgcolor=#f1f1f1><input type=submit value='help' name=help class=buttons></td></tr>
		<tr><td align=center bgcolor=#f1f1f1><input type=submit value='disconnect' name=disconnect class=buttons></td></tr>
		<tr><td align=center bgcolor=#f1f1f1>data limit <input type=text value='$max' name=max size=3 maxlength=4 ></td></tr>
		<table>");	
	}
	
	if($sql && $_REQUEST[execute]=="execute sql query"){
		//SELECT 
		if(eregi("select",$sql)){
			
			$result = mysql_query($sql) or die("<div class=tbs align=left><b>SQL Error</b><br>".mysql_error()."</div>");
			$rs = mysql_fetch_array($result);
			$a.="<tr>\n<td bgcolor=#EFC986><b>#</b></td>\n";
			for ($i=0;$i<mysql_num_fields($result); $i++){
				$feld = mysql_field_name($result,$i);
				$a.="<td bgcolor=EFC986><b>$feld</b></td>\n";
			}
			$a.="</tr>\n";
			mysql_free_result($result);	
			$result = mysql_query($sql) or die("<b>SQL Error</b><br>".mysql_error());
			while($rs = mysql_fetch_array($result)) {
				$rn++;
				$a.="<tr><td bgcolor=#EFC986 >$rn</td>\n";
				for($j=0;$j<$i;$j++){
					$back="#f1f1f1";
					if (bcmod($rn,2)==0) $back="#f8f8f8";
					$value = $rs[$j];
					if($max!=1 && strlen($value)>$max) $value=substr($value,0,$max)."...";
					if (isset($value)) {
						$a.="<td bgcolor=\"$back\">$value</td>";
					}else{
						$a.="<td bgcolor=\"$back\">NULL</td>";
					}
				}
				$a.="</tr>\n";
			}
			mysql_free_result($result);	
			echo("<br>$sql<table width=100% cellspacing=1 cellpadding=2 class=tbs style='border-style:solid; border-width:1px 1px 1px 1px; border-color:#EFC986;'>$a</table>");	
		}else{
			$sqlarray = explode(";",$sql);
        	foreach($sqlarray as $sql){
				if(trim($sql)){
					mysql_query($sql) or die("<div class=tbs align=left><b>SQL Error</b><br>".mysql_error()."</div>");
					echo("<div class=tbs align=left>$sql<br>Row(s) affected: ".mysql_affected_rows()."</div>");
				}
			}
		}
	}
	
	if($_REQUEST[backup]){		
		if($current_table){
			$output = backup($current_table);
		}else{
			$trs = mysql_list_tables($current_database,$con);
			$tables=array();
			for($i=0; $i < mysql_num_rows($trs); $i++) {
				$output.= backup(mysql_tablename($trs,$i));
			}
		}
		
		echo("
		<table width=100% class=tbs  border=0 cellspacing=0 cellpadding=2>
		<tr><td><textarea name=output style='width:100%' rows=20 wrap='off'>$output</textarea></td></tr>
		<table>
		");	
	}
	
	if($_REQUEST[help]){
		echo("
			<table width='100%' border=0 align = center class='dbs'>
			<tr><td>
			<ul>
			<b>DATABASE OR TABLE BROWSING</b><bR>
			<li>Just klick on a database and all tables are shown. By klicking a table, you will get a list of all fields.<br>
			<li>Klicking the '&raquo;' will write database/table or fieldname in the sql-textbox.
			<br><br>
			<b>EXECUTING SQL</b><bR>
			<li>Write a sql query into the textfield and klick 'execute sql query'.<br>
			<li>You can seperate a couple of statements by an ';'.
			<br><br>
			<b>BACKUP</b><bR>
			<li>select a database or a table and klick 'backup'. Then a new textfield with all delete and insert statements will appear. Copy the content of the textfield and save it to any file you want as a backup of your database or table.
			<br><br>
			<b>SQL TEMPLATES</b><bR>
			
			<li>Here are some examples of SQL Queries to copy and paste (modify):<br><br>
			<ul>
			<li>ADD FIELD: ALTER TABLE tablename ADD fieldname INT
			<br>Other types than int: varchar(255), text, char(1), bigint, date
			<br><li>DELETE FIELD: ALTER TABLE tablename DROP fieldname
			<br><li>CREATE NEW DATABASE: CREATE DATABASE dbname
			<br><li>DELETE DATABASE: DROP DATABASE dbname
			<br><li>CREATE TABLE (with id as autoincrement key): <br>
			CREATE TABLE tablename (<br>
			id INT UNSIGNED NOT NULL AUTO_INCREMENT,<br>
			person VARCHAR (255) DEFAULT '0',<br>
			PRIMARY KEY(id), UNIQUE(id), INDEX(id)<br>
			)
			<br><li>DELETE TABLE: DROP TABLE tablename
			</ul>
			</ul>
			</td>
			</table>
		");
	}
	echo("</form></td></tr></table>");
    mysql_close();
    $tend=usedtime();
	echo("<div align=center class=fls>".round($tend-$tstart,2)." sec.</div>");
	
} else {

	echo("
	<form name=me method=post action=index.php>
	<table width=300 class= dbs align=center border=0 bgcolor=#f1f1f1>
	<tr>
	<td colspan=2 align=right><b>PHP MySQL Smart 2.0</b><hr size=1 color=black></td>
	</tr>
	<tr>
	<td width=20%>Host</td><td><input type='text' name='host' maxlength=100 style='width:100%'></td>
	</tr>
	<tr>
	<td>User</td><td><input type='text' name='user' maxlength=100 size=20 style='width:100%'></td>
	</tr>
	<tr>
	<td>Pass</td><td><input type='password' name='pass' maxlength=100 size=20 style='width:100%'></td>
	</tr>
	<tr>
	<td align=center colspan=2><input type=submit value=connect name=connect class=buttons></td>
	</tr>
	</table>
	</form>
	<div class=fls align=center>2005 &copy; by <a href='http://www.powerweb99.at' target='_blank'>www.powerweb99.at</a></div>
	");

}
?>
</body>
</html>