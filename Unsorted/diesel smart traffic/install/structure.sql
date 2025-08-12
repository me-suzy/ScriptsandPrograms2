create table banners(
			id int(11) not null auto_increment,
			cid integer,
			burl varchar(255),
			btext text,
			type varchar(255),
			status integer,
			isize varchar(255),
			weight integer,
			primary key(id)
);
create table campaigns(
			id int(11) not null auto_increment,
			user_id integer,
			url varchar(255),
			group_id integer,
			ikeys varchar(255),
			title varchar(255),
			status integer,
			rdate integer,
			primary key(id)
);
create table unsubscribers(
			id int(11) not null auto_increment,
			member_id integer,
			primary key(id)
);
create table camp_groups(
			id int(11) not null auto_increment,
			cid integer, guid integer,
			primary key(id)
);
create table credits_set(
			id int(11) not null auto_increment,
			user_id integer,
			rate varchar(255),
			primary key(id)
);
create table payment_modes(
			id int(11) not null auto_increment,
			amount float(11,2),
			credits float(11,2),
			idesc varchar(255),
			pay_link varchar(255),
			primary key(id)
);
create table members_credits(
			id int(11) not null auto_increment,
			user_id integer,
			credits_num float(11,2),
			primary key(id)
);
create table logs(
			id int(11) not null auto_increment,
			user_id integer,
			idate integer,
			primary key(id)
);
create table members_policy(
			id int(11) not null auto_increment,
			user_id integer,
			trusted integer,
			expirable integer,
			approval_required integer,
			free integer,
			primary key(id)
);
create table clicks(
			id int(11) not null auto_increment,
			cid integer,
			ref integer,
			bid integer,
			idate integer,
			ifrom varchar(255),
			url varchar(255),
			primary key(id)
);
create table prev(
			id int(11) not null auto_increment,
			cid integer,
			prev_number float(11,2),
			primary key(id)
);
create table previews(
			id int(11) not null auto_increment,
			cid integer,
			ref integer,
			idate integer,
			ifrom varchar(255),
			camp_id integer,
			primary key(id)
);
create table menus(	
			id int(11) not null auto_increment,
			topic VARCHAR(100),
			link VARCHAR(100),
			fadm enum('N','Y'),
			 PRIMARY KEY (id)
);
create table groups(
			id INTEGER DEFAULT '0' not null auto_increment,
			PRIMARY KEY id (id),
			pid integer default '-1' Not null,
			topic varchar(100)
);
create table members(
			id int(11) not null auto_increment,
			login varchar(255),
			pswd varchar(255),
			fname varchar(255),
			lname varchar(255),
			email varchar(255),
			city varchar(255),
			state varchar(255),
			country varchar(255),
			zip varchar(255),
			phone varchar(255),
			fax varchar(255),
			prcode varchar(255),
			status integer,
			rdate integer,
			primary key(id)
);
create table keywords(
			id int(11) not null auto_increment,
			cid integer,
			keyword text,
			ppc float(7,2),
			primary key(id)
);
create table banns(	
			id int(11) not null auto_increment,
			address VARCHAR(100),
			PRIMARY KEY (id)
);
create table sysvars(
			id int(11) not null auto_increment,
			description text,
			name varchar(255),
			value text,
			vtype varchar(255),
			primary key(id)
);
create table event(
			id int(11) not null auto_increment,
			sender varchar(255),
			title varchar(255),
			contents text,
			type varchar(255),
			user_id integer,
			credits float(11,2),
			status integer,
			rdate integer,
			primary key(id)
);

create table camp_modes(
			id int(11) not null auto_increment,
			cid integer,
			locked integer,
			banner integer,
			start integer,
			exit integer,
			popup integer,
			popunder integer,
			ppc integer,
			primary key(id)
);

CREATE TABLE safelistdata (
  id int(11) NOT NULL auto_increment,
  uid int(11) NOT NULL default '0',
  email varchar(60) NOT NULL default '',
  safemail varchar(60) NOT NULL default '',
  password varchar(30) NOT NULL default '',
  fname varchar(30) NOT NULL default '',
  lname varchar(30) NOT NULL default '',
  rdate int(11) NOT NULL default '0',
  ldate int(11) NOT NULL default '0',
  sdate int(11) NOT NULL default '0',
  status int(3) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY uid (uid),
  KEY status (status)
) TYPE=MyISAM COMMENT='Multisafelist emails database.';

