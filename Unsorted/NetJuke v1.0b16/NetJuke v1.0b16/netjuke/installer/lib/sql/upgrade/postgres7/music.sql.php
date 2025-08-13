<?php

############################################################

array_push($sql_statements,"alter table netjuke_tracks add column year smallint");
array_push($sql_statements,"alter table netjuke_tracks add column lyrics text");

############################################################

?>