<?
$getfields=@mysql_query("show tables");
while($fields=@mysql_fetch_row($getfields)){
$keys[$fields[0]]=1;
}
if(system_value("version")<.09){
@mysql_query("delete from levels");
@mysql_query("create unique index uniqueness on levels(username,upline)");
@mysql_query("update users set rebuild_stats_cache='YES'");
} 
if (system_value("version")<.13){
@mysql_query("alter table email_ads drop run_current");
@mysql_query("alter table ptc_ads drop run_current");
@mysql_query("alter table rotating_ads drop run_current");
@mysql_query("create index clicks on ptc_ads(clicks)");
@mysql_query("create index views on ptc_ads(views)");
@mysql_query("create index clicks on rotating_ads(clicks)");
@mysql_query("create index views on rotating_ads(views)");
@mysql_query("create index clicks on email_ads(clicks)");
@mysql_query("alter table redemptions add phpcode blob not null");
@mysql_query("alter table redemptions add auto char(3) not null");
@mysql_query("alter table mass_mailer add ad_html blob not null");
@mysql_query("alter table accounting drop index uniqueness");
@mysql_query("create unique index uniqueness on ".$mysql_prefix."accounting(unixtime,username,description,type)");
}
if (system_value("version")<.16){
@mysql_query("CREATE TABLE ".$mysql_prefix."last_login (
  username char(16) not null,
  time bigint not null,
  primary key (username),
  key time(time)
) TYPE=MyISAM");
@mysql_query("alter table accounting modify transid char(14) not null");
}
if (system_value("version")<.23){
@mysql_query("alter table interest modify keyword char(34) not null");
@mysql_query("alter table interest modify username char(32) not null");
@mysql_query("alter table accounting add comm char(1) not null, add key comm(comm)");}
if (system_value("version")<.24){
@mysql_query("alter table last_login modify username char(64) not null");
@mysql_query("insert into ".$mysql_prefix."last_login (username,time) select username,time from ".$mysql_prefix."click_counter");
}
if (system_value("version")<.27){
@mysql_query("update ".$mysql_prefix."users set free_refs='1' where free_refs='YES'");
@mysql_query("alter table ".$mysql_prefix."users modify free_refs int not null");
@mysql_query("CREATE TABLE ".$mysql_prefix."free_refs (
 username char(16) not null,
 key username(username)
) TYPE=MyISAM");
}
if (system_value("version")<.29){
@mysql_query("alter table ".$mysql_prefix."free_refs drop primary key");
@mysql_query("create index username on ".$mysql_prefix."free_refs(username)");}

if (system_value("version")<.32){
@mysql_query("drop table ".$mysql_prefix."browsers");
@mysql_query("CREATE TABLE ".$mysql_prefix."browsers (
md5agent char(32) not null,
agent varchar(255) not null,
block char(1) not null,
primary key md5agent(md5agent),
key block(block)
) TYPE=MyISAM");
}
if (system_value("version")<.33){
@mysql_query("CREATE TABLE ".$mysql_prefix."ips (
ip char(64) not null,
primary key ip(ip)
) TYPE=MyISAM");
@mysql_query("CREATE TABLE ".$mysql_prefix."emails (
address char(64) not null,
primary key address(address)
) TYPE=MyISAM");
}
if (system_value("version")<.37){
@mysql_query("CREATE TABLE ".$mysql_prefix."ref_counter (
 username char(16) not null,
 counter int not null,
 primary key (username),
 key counter(counter)
) TYPE=MyISAM");
@mysql_query("drop table ".$mysql_prefix."user_clicks");
}
if (system_value("version")<.38){
@mysql_query("alter table ".$mysql_prefix."mass_mailer drop keywords");
@mysql_query("alter table ".$mysql_prefix."mass_mailer add is_html char(1) not null");
}
@mysql_query("replace into ".$mysql_prefix."system_values set name='version',value='.39'");
