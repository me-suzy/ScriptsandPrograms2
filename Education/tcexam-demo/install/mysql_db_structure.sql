/*
============================================================
File name   : mysql_db_structure.sql
Begin       : 2004-04-28
Last Update : 2005-02-08

Description : TCExam database structure.
Database    : MySQL 4.1

Author: Nicola Asuni

(c) Copyright:
              Tecnick.com S.r.l.
              Via Ugo Foscolo n.19
              09045 Quartu Sant'Elena (CA)
              ITALY
              www.tecnick.com
              info@tecnick.com
============================================================
*/

/* Create Tables */

CREATE TABLE tce_sessions (
	cpsession_id Varchar(32) NOT NULL,
	cpsession_expiry Datetime NOT NULL,
	cpsession_data Text NOT NULL,
	PRIMARY KEY (cpsession_id)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_general_ci
ROW_FORMAT = Dynamic;

CREATE TABLE tce_users (
	user_id Bigint(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	user_regdate Datetime NOT NULL,
	user_ip Varchar(15) NOT NULL,
	user_name Varchar(255) NOT NULL,
	user_email Varchar(255),
	user_password Varchar(255) NOT NULL,
	user_regnumber Varchar(255),
	user_firstname Varchar(255),
	user_lastname Varchar(255),
	user_birthdate Date,
	user_birthplace Varchar(255),
	user_ssn Varchar(255),
	user_level Smallint(3) UNSIGNED NOT NULL DEFAULT 1,
	PRIMARY KEY (user_id)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_general_ci
ROW_FORMAT = Dynamic;

CREATE TABLE tce_subjects (
	subject_id Bigint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
	subject_name Varchar(255) NOT NULL,
	subject_description Text,
	subject_enabled Bool NOT NULL DEFAULT '0',
	PRIMARY KEY (subject_id)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_general_ci
ROW_FORMAT = Dynamic;

CREATE TABLE tce_questions (
	question_id Bigint(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	question_subject_id Bigint(10) UNSIGNED NOT NULL,
	question_description Text NOT NULL,
	question_enable_textarea Bool NOT NULL DEFAULT '0',
	question_enabled Bool NOT NULL DEFAULT '0',
	PRIMARY KEY (question_id)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_general_ci
ROW_FORMAT = Dynamic;

CREATE TABLE tce_answers (
	answer_id Bigint(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	answer_question_id Bigint(10) UNSIGNED NOT NULL,
	answer_description Text NOT NULL,
	answer_isright Bool NOT NULL DEFAULT '0',
	answer_enabled Bool NOT NULL DEFAULT '0',
	PRIMARY KEY (answer_id)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_general_ci
ROW_FORMAT = Dynamic;

CREATE TABLE tce_tests (
	test_id Bigint(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	test_name Varchar(255) NOT NULL,
	test_description Text NOT NULL,
	test_begin_time Datetime,
	test_end_time Datetime,
	test_ip_range Varchar(255) NOT NULL DEFAULT '*.*.*.*',
	test_duration_time Smallint(10) UNSIGNED NOT NULL DEFAULT 0,
	test_num_mquestions Smallint(10) UNSIGNED NOT NULL DEFAULT 0,
	test_num_tquestions Smallint(10) UNSIGNED NOT NULL DEFAULT 0,
	test_num_answers Smallint(10) UNSIGNED NOT NULL DEFAULT 0,
	test_score_right Decimal(10,3) NOT NULL DEFAULT 0,
	test_score_wrong Decimal(10,3) NOT NULL DEFAULT 0,
	test_score_unanswered Decimal(10,3) NOT NULL DEFAULT 0,
	PRIMARY KEY (test_id)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_general_ci
ROW_FORMAT = Dynamic;

CREATE TABLE tce_test_subjects (
	tsubj_test_id Bigint UNSIGNED NOT NULL,
	tsubj_subject_id Bigint UNSIGNED NOT NULL,
	PRIMARY KEY (tsubj_test_id,tsubj_subject_id)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_general_ci
ROW_FORMAT = DEFAULT;

CREATE TABLE tce_tests_users (
	testuser_id Bigint UNSIGNED NOT NULL AUTO_INCREMENT,
	testuser_test_id Bigint UNSIGNED NOT NULL,
	testuser_user_id Bigint UNSIGNED NOT NULL,
	testuser_status Smallint UNSIGNED NOT NULL DEFAULT 0,
	testuser_creation_time Datetime NOT NULL,
	PRIMARY KEY (testuser_id)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_general_ci
ROW_FORMAT = DEFAULT;

CREATE TABLE tce_tests_logs (
	testlog_id Bigint(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	testlog_testuser_id Bigint(10) UNSIGNED NOT NULL,
	testlog_user_ip Varchar(15),
	testlog_question_id Bigint(10) UNSIGNED NOT NULL,
	testlog_answer_id Bigint(10) UNSIGNED,
	testlog_answer_text Text,
	testlog_score Decimal(10,3),
	testlog_creation_time Datetime,
	testlog_display_time Datetime,
	testlog_change_time Datetime,
	PRIMARY KEY (testlog_id)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_general_ci
ROW_FORMAT = Dynamic;

CREATE TABLE tce_tests_logs_answers (
	logansw_testlog_id Bigint UNSIGNED NOT NULL,
	logansw_answer_id Bigint UNSIGNED NOT NULL,
	PRIMARY KEY (logansw_testlog_id,logansw_answer_id)
) ENGINE = InnoDB
CHARACTER SET utf8 COLLATE utf8_general_ci
ROW_FORMAT = DEFAULT;

/* Create Alternate Keys */

ALTER TABLE tce_users ADD UNIQUE ak_user_name (user_name);
ALTER TABLE tce_users ADD UNIQUE ak_user_regnumber (user_regnumber);
ALTER TABLE tce_users ADD UNIQUE ak_user_ssn (user_ssn);
ALTER TABLE tce_subjects ADD UNIQUE ak_subject_name (subject_name);
ALTER TABLE tce_questions ADD UNIQUE ak_question (question_subject_id,question_description(255));
ALTER TABLE tce_answers ADD UNIQUE ak_answer (answer_question_id,answer_description(255));
ALTER TABLE tce_tests ADD UNIQUE ak_test_name (test_name);
ALTER TABLE tce_tests_users ADD UNIQUE ak_testuser (testuser_test_id,testuser_user_id);
ALTER TABLE tce_tests_logs ADD UNIQUE ak_testuser_question (testlog_testuser_id,testlog_question_id);

/* Create FOREIGN KEYs */

ALTER TABLE tce_tests_users ADD CONSTRAINT rel_user_tests FOREIGN KEY (testuser_user_id) REFERENCES tce_users (user_id) ON DELETE CASCADE ON UPDATE  RESTRICT;
ALTER TABLE tce_questions ADD CONSTRAINT rel_subject_questions FOREIGN KEY (question_subject_id) REFERENCES tce_subjects (subject_id) ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE tce_test_subjects ADD CONSTRAINT rel_subject_tests FOREIGN KEY (tsubj_subject_id) REFERENCES tce_subjects (subject_id) ON DELETE  RESTRICT ON UPDATE NO ACTION;
ALTER TABLE tce_answers ADD CONSTRAINT rel_question_answers FOREIGN KEY (answer_question_id) REFERENCES tce_questions (question_id) ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE tce_tests_logs ADD CONSTRAINT rel_question_logs FOREIGN KEY (testlog_question_id) REFERENCES tce_questions (question_id) ON DELETE  RESTRICT ON UPDATE NO ACTION;
ALTER TABLE tce_tests_logs_answers ADD CONSTRAINT rel_answer_logs FOREIGN KEY (logansw_answer_id) REFERENCES tce_answers (answer_id) ON DELETE  RESTRICT ON UPDATE NO ACTION;
ALTER TABLE tce_tests_logs ADD CONSTRAINT rel_useranswer_logs FOREIGN KEY (testlog_answer_id) REFERENCES tce_answers (answer_id) ON DELETE  RESTRICT ON UPDATE NO ACTION;
ALTER TABLE tce_tests_users ADD CONSTRAINT rel_test_users FOREIGN KEY (testuser_test_id) REFERENCES tce_tests (test_id) ON DELETE CASCADE ON UPDATE  RESTRICT;
ALTER TABLE tce_test_subjects ADD CONSTRAINT rel_test_id_subjects FOREIGN KEY (tsubj_test_id) REFERENCES tce_tests (test_id) ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE tce_tests_logs ADD CONSTRAINT rel_testuser_logs FOREIGN KEY (testlog_testuser_id) REFERENCES tce_tests_users (testuser_id) ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE tce_tests_logs_answers ADD CONSTRAINT rel_testlog_answers FOREIGN KEY (logansw_testlog_id) REFERENCES tce_tests_logs (testlog_id) ON DELETE CASCADE ON UPDATE NO ACTION;
