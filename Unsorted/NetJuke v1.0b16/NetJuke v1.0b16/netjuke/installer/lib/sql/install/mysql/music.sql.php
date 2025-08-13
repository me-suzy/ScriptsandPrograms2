<?php

############################################################ 

array_push($sql_statements,"
create table netjuke_artists (
    id   int(11) not null auto_increment,
    name varchar(100) default 'N/A' not null,
    img_src text,
    track_cnt int(11) not null default '0',
    primary key (id),
    index netjuke_artists_webindex (name)
)
");


############################################################ 

array_push($sql_statements,"
create table netjuke_albums (
    id   int(11) not null auto_increment,
    name varchar(100) default 'N/A' not null,
    img_src text,
    track_cnt int(11) not null default '0',
    primary key (id),
    index netjuke_albums_webindex (name)
)
");

############################################################ 

array_push($sql_statements,"
create table netjuke_genres (
    id   int(11) not null auto_increment,
    name varchar(100) default 'N/A' not null,
    img_src text,
    track_cnt int(11) not null default '0',
    primary key (id),
    index netjuke_genres_webindex (name)
)
");

############################################################ 

array_push($sql_statements,"
create table netjuke_tracks (
    id int(11) not null auto_increment,
    ar_id  int(11) not null references netjuke_artists  on delete set default on update cascade,
    al_id  int(11) not null references netjuke_albums   on delete set default on update cascade,
    ge_id  int(11) not null references netjuke_genres   on delete set default on update cascade,
    name         varchar(100) default 'N/A' not null,
    size         int(11) default '0',
    time         int(11) default '0',
    track_number int(11) default '0',
    year         smallint,
    date         datetime not null,
    bit_rate     int(11) default '0',
    sample_rate  int(11) default '0',
    kind         varchar(30) default 'N/A',
    location     text,
    dl_cnt       int(11) not null default '0',
    img_src      text,
    comments     text,
    lyrics       text,
    primary key (id),
    index netjuke_tracks_webindex (name)
)
");

############################################################ 

?>