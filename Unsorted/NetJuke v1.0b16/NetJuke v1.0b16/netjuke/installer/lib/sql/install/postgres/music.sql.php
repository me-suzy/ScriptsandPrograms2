<?php

############################################################

array_push($sql_statements,"
create sequence netjuke_artists_seq
	start 1
	increment 1
");

array_push($sql_statements,"
create table netjuke_artists (
    id                   int4 not null
	                          unique 
                              primary key
                              default nextval('netjuke_artists_seq'),
    name                 varchar(100) default 'N/A',
    img_src              text,
    track_cnt            int4 not null default '0'
)
");

array_push($sql_statements,"create index netjuke_artists_webindex on netjuke_artists (name)");

############################################################

array_push($sql_statements,"
create sequence netjuke_albums_seq
	start 1
	increment 1
");

array_push($sql_statements,"
create table netjuke_albums (
    id                   int4 not null
	                          unique
                              primary key 
                              default nextval('netjuke_albums_seq'),
    name                 varchar(100) default 'N/A',
    img_src              text,
    track_cnt            int4 not null default '0'
)
");

array_push($sql_statements,"create index netjuke_albums_webindex on netjuke_albums (name)");

############################################################

array_push($sql_statements,"
create sequence netjuke_genres_seq
	start 1
	increment 1
");

array_push($sql_statements,"
create table netjuke_genres (
    id                   int4 not null
	                          unique
                              primary key 
                              default nextval('netjuke_genres_seq'),
    name                 varchar(100) default 'N/A',
    img_src              text,
    track_cnt            int4 not null default '0'
)
");

array_push($sql_statements,"create index netjuke_genres_webindex on netjuke_genres (name)");

############################################################

array_push($sql_statements,"
create sequence netjuke_tracks_seq
	start 1
	increment 1
");

array_push($sql_statements,"
create table netjuke_tracks (
    id                   int4 not null
	                          unique
                              primary key 
                              default nextval('netjuke_tracks_seq'),
    ar_id                int4 not null
	                          default '1'
							  references netjuke_artists (id)
							             on delete set default
										 on update cascade,
    al_id                int4 not null
	                          default '1'
							  references netjuke_albums (id)
							             on delete set default
										 on update cascade,
    ge_id                int4 not null
	                          default '1'
							  references netjuke_genres (id)
							             on delete set default
										 on update cascade,
    name                 varchar(100) default 'N/A',
    size                 int4 default '0',
    time                 int4 default '0',
    track_number         int4 default '0',
    year                 smallint,
    date                 timestamp default 'now',
    bit_rate             int4 default '0',
    sample_rate          int4 default '0',
    kind                 varchar(30) default 'N/A',
    location             text,
    dl_cnt               int4 default '0',
    img_src              text,
    comments             text,
    lyrics               text
)
");

array_push($sql_statements,"create index netjuke_tracks_webindex on netjuke_tracks (name)");
array_push($sql_statements,"create index netjuke_tracks_locationindex on netjuke_tracks (location)");

############################################################

?>