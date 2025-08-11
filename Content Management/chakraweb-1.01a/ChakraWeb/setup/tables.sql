create table sysvarint
(
    var_key char(32) not null,
    var_data int(11) unsigned not null default 0,
    primary key (var_key)
);

create table syssessions
(
    sess_id char(32) not null,

    ip_addr char(20) not null,
    first_used int(11) unsigned not null,
    last_used int(11) unsigned not null,
    user_id int(3) unsigned not null default 0,

    sess_data text,
    primary key (sess_id)
);


create table sysuids
(
    ui_code char(32) not null,
    ui_id int(3) unsigned not null,
    primary key (ui_code)
);

create table sysmember
(
    m_id int(3) unsigned not null,
    m_level int(3) unsigned not null default 1,
    m_lid char(6) not null default 'en',
    m_ccode int(3) not null default 0,
    m_name varchar(32) not null,
    m_fullname varchar(255),
    m_password varchar(32) not null,
    m_email varchar(60),
    m_homepage varchar(255),
    m_startpage varchar(255) default '/index.html',

    m_view_email int(3) not null default 1,
    m_view_profile int(3) not null default 1,
    m_theme varchar(32) not null,

    m_photo int(3) not null default 0,
    m_desc text,
    m_page text,

    m_visit int(3) not null default 0,
    m_hits int(3) not null default 0,

    primary key (m_id),
    unique (m_name),
    unique(m_email)
);


create table web_folder
(
    folder_lid char(6) not null,
    folder_id int(3) not null,
    folder_name varchar(255) not null,
    folder_label varchar(255) not null,
    folder_title varchar(255) not null,
    folder_desc text,
    folder_keywords text,
    folder_robots varchar(64) not null,
    folder_sidebar text,
    folder_parent int(3) not null,
    folder_show int(3) not null default 1,
    folder_active int(3) not null default 1,
    read_level int(3) not null default 0,
    write_level int(3) not null default 9,
    folder_order int(3) default 9999,

    upload_by varchar(20) not null,
    upload_on datetime not null default '1980-00-01 00:00:00',
    update_on datetime not null default '1980-00-01 00:00:00',

    primary key (folder_lid, folder_id),
    unique (folder_lid, folder_parent, folder_name)
);

create table web_page
(
    page_lid char(6) not null,
    page_id int(3) not null,
    folder_id int(3) not null,
    page_name varchar(255) not null,
    page_title varchar(255),
    page_desc text,
    page_keywords text,

    page_robots varchar(64) not null,
    page_author varchar(64) not null,

    page_content text,
    page_seealso_title varchar(255),
    page_seealso text,

    page_external int(3) not null default 0,
    page_src_title varchar(255),
    page_src_url varchar(255),
    page_src_home varchar(255),
    page_src_homeurl varchar(255),

    page_redirect varchar(255),

    page_show int(3) not null default 1,
    page_active int(3) not null default 1,
    page_order int(3) not null default 9999,
    page_type int(3) default 0,

    page_rating float default '0.0',
    page_votes int(3) unsigned default 0,
    page_hits int(3) unsigned default 0,

    upload_by varchar(20),
    upload_on datetime not null default '1980-00-01 00:00:00',
    update_on datetime not null default '1980-00-01 00:00:00',

    primary key (page_lid, page_id),
    unique (folder_id, page_lid, page_name)
);


create index link_folder_idx on web_folder (folder_name, folder_parent);

create table advtext
(
    adv_key char(32) not null,
    adv_lid char(6) not null,
    adv_title varchar(255),
    adv_text text,
    adv_active int(3) not null default 1,
    adv_hits int(3) default 0,

    primary key (adv_key, adv_lid)
);

create table advrnd
(
    adv_id int(3) unsigned not null,
    adv_key char(32) not null,
    adv_lid char(6) not null,
    adv_title varchar(255),
    adv_text text,
    adv_active int(3) not null default 1,
    adv_hits int(3) default 0,

    primary key (adv_id)
);


create table feedback
(
    fb_id int(3) unsigned not null,
    fb_lid char(6) not null,
    fb_fullname varchar(255),
    fb_email varchar(60),
    fb_content text,
    fb_ushow int(3) default 0,
    fb_utestimonial int(3) default 0,
    fb_show int(3) default 0,
    fb_testimonial int(3) default 0,
    upload_on datetime default '1980-00-01 00:00:00',
    primary key (fb_id)
);

create table news
(
    news_id int(3) unsigned not null,
    news_lid char(6) not null,
    news_title varchar(255),
    news_desc text,
    news_content text,
    news_show int(3) default 1,

    upload_on datetime default '1980-00-01 00:00:00',
    primary key (news_id)
);

create table comment
(
    comm_id int(3) unsigned not null,
    page_lid char(6) not null,
    page_id int(3) not null,

    comm_content text,
    comm_show int(3) default 1,
    m_id int(3) unsigned not null,
    upload_on datetime default '1980-00-01 00:00:00',

    primary key (comm_id)
);


create table link
(
    link_id int(3) not null,
    page_lid char(6) not null,
    page_id int(3) not null,
    link_url varchar(255) not null,
    link_title varchar(255),
    link_desc text,

    link_note varchar(255),
    link_show int(3) default 0,
    link_active int(3) default 0,
    link_great int(3) default 0,
    link_order int(3) default 0,

    m_id int(3) unsigned not null,
    upload_on datetime not null default '1980-00-01 00:00:00',

    primary key (link_id),
    unique (page_lid, page_id, link_url)
);

create table macrotext
(
    mac_key char(32) not null,
    mac_lid char(6) not null,
    mac_title varchar(255),
    mac_content text,
    mac_active int(3) not null default 1,
    primary key (mac_key, mac_lid)
);

create table service
(
    svc_id int(3) not null,
    svc_lid char(6) not null,
    svc_name varchar(255),
    svc_desc text,
    svc_default int(3) not null default 0,
    svc_level int(3) not null default 1,
    svc_order int(3) not null default 9999,
    svc_active int(3) not null default 1,
    primary key (svc_id)
);

create table svcmember
(
    svc_id int(3) not null,
    m_id int(3) not null,
    primary key (svc_id, m_id)
);

