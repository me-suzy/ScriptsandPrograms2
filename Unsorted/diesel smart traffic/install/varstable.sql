drop table IF EXISTS sysvars;
create table sysvars(
			id int(11) not null auto_increment,
			description text,
			name varchar(255),
			value text,
			vtype varchar(255),
			primary key(id)
);