<?php

############################################################

array_push($sql_statements,"
create table netjuke_groups (
    id   int(11) not null auto_increment,
    name varchar(30) not null,
    primary key (id),
    unique index netjuke_groups_webindex (name)
)
");


############################################################

array_push($sql_statements,"
create table netjuke_users (
    email                varchar(75) not null,
    password             varchar(60) not null,
    name                 varchar(100) not null,
    gr_id                int(11) not null references netjuke_groups,
    created              datetime not null,
    updated              datetime not null,
    nickname             varchar(30),
    web_site             varchar(255),
    img_src              text,
    login_cnt            int(11) not null default '0',
    local_music_dir      text,
    primary key (email),
    index netjuke_users_webloginindex (email, password)
)
");


############################################################

array_push($sql_statements,"
create table netjuke_userprefs (
    us_email             varchar(75) not null references netjuke_users on delete cascade on update cascade,
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
    font_size            int(11) not null,
    inv_icn              varchar(1) not null,
    primary key (us_email)
)
");

############################################################

array_push($sql_statements,"
create table netjuke_sessions (
    session_id           varchar(40) not null,
    remote_addr          varchar(60) not null,
    created              datetime not null,
    updated              datetime not null,
    email                varchar(75),
    name                 varchar(100),
    gr_id                int(11),
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
    font_size            int(11),
    default_pl           int(11),
    inv_icn              varchar(1) default 'f',
    primary key (session_id)
)
");

############################################################

array_push($sql_statements,"
create table netjuke_plists (
    id                   int(11) not null auto_increment,
    us_email             varchar(75) not null references netjuke_users on delete cascade on update cascade,
    created              datetime not null default 'now',
    sequence             int(11) not null default 1,
    title                varchar(100) not null,
    comment              text,
    shared_list          varchar(1) default 'f',
    primary key (id)
)
");

############################################################

array_push($sql_statements,"
create table netjuke_plists_tracks (
    id                   int(11) not null auto_increment,
    us_email             varchar(75) not null references netjuke_users on delete cascade on update cascade,
    pl_id                int(11) not null references netjuke_plists on delete cascade on update cascade,
    tr_id                int(11) not null references netjuke_tracks on delete cascade on update cascade,
    sequence             int(11) not null default 1,
    comment              varchar(255),
    primary key (id)
)
");

############################################################

array_push($sql_statements,"
create table netjuke_plists_fav (
    id                   int(11) not null auto_increment,
    us_email             varchar(75) not null references netjuke_users on delete cascade on update cascade,
    pl_id                int(11) not null references netjuke_plists on delete cascade on update cascade,
    created              datetime not null default 'now',
    sequence             int(11) not null default 1,
    comment              varchar(255),
    primary key (id)
)
");

############################################################

?>