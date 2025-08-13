CREATE TABLE wing 
(
	ID INT NOT NULL DEFAULT '0' auto_increment,
	Title VARCHAR(255) NOT NULL,
	Parent INT NOT NULL,
	Private CHAR(1) NOT NULL,
	Description VARCHAR(255) NOT NULL,
	PRIMARY KEY(ID),
	INDEX Title(Title),
	INDEX Parent(Parent)
);



INSERT INTO wing (ID, Title, Parent, Private) 
VALUES (1, 'Suggested Links', 0, 'Y');



CREATE TABLE item
(
	ID int NOT NULL DEFAULT '0' auto_increment,
	Wing INT NOT NULL,
	Title VARCHAR(255) NOT NULL,
	URL VARCHAR(255) NOT NULL,
	Description VARCHAR(255) NOT NULL,
	Created DATETIME NOT NULL,
	Creator VARCHAR(32) NOT NULL,
	LinkCount INT NOT NULL DEFAULT 0,
	LastChecked DATETIME NOT NULL,
	PRIMARY KEY(ID),
	INDEX Wing(Wing),
	INDEX Title(Title)
);

