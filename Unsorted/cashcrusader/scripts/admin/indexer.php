#!/usr/local/bin/php
<?php
mysql_connect("database.myecom.net","","");
mysql_select_db("");
$gettables=@mysql_query("show tables");
while($tables=@mysql_fetch_row($gettables)){
$getindexes=@mysql_query("show index from $tables[0]");
while($indexes=@mysql_fetch_row($getindexes)){
$keys[$indexes[4]]=1;
}
$getfields=@mysql_query("describe $tables[0]");
while($fields=@mysql_fetch_row($getfields)){
if (!$keys[$fields[0]]){
echo $tables[0].".".$fields[0]."\n";}
@mysql_query("create index key_$fields[0] on $tables[0]($fields[0])");
}}
