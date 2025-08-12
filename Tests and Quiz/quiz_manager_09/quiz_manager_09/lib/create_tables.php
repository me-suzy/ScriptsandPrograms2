<?
	include("config.php");
	include("quiz_lib.php");
	$dbh = Connect_Database();
	$query = "
create table quizes(
	id     int(5) primary key auto_increment,
	name   varchar(64),
	question   varchar(128),
	answer1   varchar(128),
	answer2   varchar(128),
	answer3   varchar(128),
	answer4   varchar(128),
	created	  datetime
	start date,
	end date,
	index(start),
	index(end)
)";

	$dbc = mysql_query($query);

	$query = "
create table votes(
	quiz_id    int(5) not null,
	email      varchar(64) not null,
        vote       int(1) not null,
        IP         varchar(16) not null,
        created    datetime,
	index(quiz_id),
	index(ip)
)";

	$dbc = mysql_query($query);


?>