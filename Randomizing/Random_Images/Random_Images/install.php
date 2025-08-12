<?php
include ('include.php');
$sql="create table Ran_Img (set_id int(3) not null auto_increment,class varchar(6) not null,url varchar(155) not null,alt varchar(155) not null,caption varchar(155) primary key(id),unique id (id))";
$sql=mysql_query($sql);

mysql_close($conn);
?>