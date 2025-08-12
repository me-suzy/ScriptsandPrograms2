CREATE TABLE download (
   id int(4) NOT NULL auto_increment,
   title varchar(32),
   filename varchar(32),
   downloads int(8) DEFAULT '1',
   UNIQUE id (id, title, filename)
);

INSERT INTO download VALUES ( '1', 'test file #1', 'testfile1.zip', '1');
INSERT INTO download VALUES ( '1', 'test file #2', 'testfile2.zip', '1');
INSERT INTO download VALUES ( '1', 'test file #3', 'testfile3.zip', '1');
