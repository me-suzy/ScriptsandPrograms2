<?php

############################################################

array_push($sql_statements,"
create sequence netjuke_groups_seq
	start 1
	increment 1
");

array_push($sql_statements,"
create table netjuke_groups (
    id                   int4 not null
	                          unique
                              primary key 
                              default nextval('netjuke_groups_seq'),
    name                 varchar(30) not null unique
)
");

############################################################

array_push($sql_statements,"
create table netjuke_users (
    email                varchar(75) not null
	                                 unique
                                     primary key,
    password             varchar(60) not null,
    name                 varchar(100) not null,
    gr_id                int4 not null
	                          references netjuke_groups (id),
    created              timestamp not null,
    updated              timestamp not null,
    nickname             varchar(30),
    web_site             varchar(255),
    img_src              text,
    login_cnt            int not null default '0',
    local_music_dir      text
)
");

array_push($sql_statements,"create index netjuke_users_webloginindex on netjuke_users (email, password)");

############################################################

array_push($sql_statements,"
create table netjuke_userprefs (
    us_email             varchar(75) not null
	                                 unique
                                     primary key
									 references netjuke_users (email)
									            on delete cascade
												on update cascade,
    bgcolor              varchar(6) not null,
    text                 varchar(6) not null,
    link                 varchar(6) not null,
    alink                varchar(6) not null,
    vlink                varchar(6) not null,
    td_border            varchar(6) not null,
    td_header            varchar(6) not null,
    td_header_fc         varchar(6) not null,
    td_content           varchar(6) not null,
    font_face            varchar(100) not null,
    font_size            int4 not null,
    inv_icn              varchar(1) not null
)
");

############################################################

array_push($sql_statements,"
create table netjuke_sessions (
    session_id           varchar(40) not null
	                                 unique
                                     primary key,
    remote_addr          varchar(60) not null,
    created              timestamp not null,
    updated              timestamp not null,
    email                varchar(75),
    name                 varchar(100),
    gr_id                int4,
    nickname             varchar(30),
    bgcolor              varchar(6),
    text                 varchar(6),
    link                 varchar(6),
    alink                varchar(6),
    vlink                varchar(6),
    td_border            varchar(6),
    td_header            varchar(6),
    td_header_fc         varchar(6),
    td_content           varchar(6),
    font_face            varchar(100),
    font_size            int4,
    default_pl           int4,
    inv_icn              varchar(1) default 'f'
)
");

############################################################

array_push($sql_statements,"
create sequence netjuke_plists_seq
	start 1
	increment 1
");

array_push($sql_statements,"
create table netjuke_plists (
    id                   int4 not null
	                          unique
                              primary key 
                              default nextval('netjuke_plists_seq'),
    us_email             varchar(75) not null
									 references netjuke_users (email)
									            on delete cascade
												on update cascade,
    created              timestamp not null default 'now',
    sequence             int4 not null default 1,
    title                varchar(100) not null,
    comment              varchar(512),
    shared_list          varchar(1) default 'f'
)
");

############################################################

array_push($sql_statements,"
create sequence netjuke_plists_tracks_seq
	start 1
	increment 1
");

array_push($sql_statements,"
create table netjuke_plists_tracks (
    id                   int4 not null
	                          unique
                              primary key 
                              default nextval('netjuke_plists_tracks_seq'),
    us_email             varchar(75) not null
									 references netjuke_users (email)
									            on delete cascade
												on update cascade,
    pl_id                int4 not null
							  references netjuke_plists (id)
									     on delete cascade
									     on update cascade,
    tr_id                int4 not null
	                          default 1
							  references netjuke_tracks (id)
									     on delete cascade
									     on update cascade,
    sequence             int4 not null default 1,
    comment              varchar(255)
)
");

############################################################

array_push($sql_statements,"
create sequence netjuke_plists_fav_seq
	start 1
	increment 1
");

array_push($sql_statements,"
create table netjuke_plists_fav (
    id                   int4 not null unique
                              primary key 
                              default nextval('netjuke_plists_tracks_seq'),
    us_email             varchar(75) not null
									 references netjuke_users (email)
									            on delete cascade
												on update cascade,
    pl_id                int4 not null
							  references netjuke_plists (id)
									     on delete cascade
									     on update cascade,
    created              timestamp not null default 'now',
    sequence             int4 not null default 1,
    comment              varchar(255)
)
");

############################################################

?>