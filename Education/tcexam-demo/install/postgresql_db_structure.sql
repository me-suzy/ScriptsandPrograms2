/*
============================================================
File name   : postgresql_db_structure.sql
Begin       : 2004-04-28
Last Update : 2005-02-08

Description : TCExam database structure.
Database    : PostgreSQL 7.4

Author: Nicola Asuni

(c) Copyright:
              Tecnick.com S.r.l.
              Via Ugo Foscolo n.19
              09045 Quartu Sant'Elena (CA)
              ITALY
              www.tecnick.com
              info@tecnick.com
//============================================================
*/

/* Create Tables */

CREATE TABLE "tce_sessions" (
	"cpsession_id" Varchar(32) NOT NULL,
	"cpsession_expiry" Timestamp NOT NULL,
	"cpsession_data" Text NOT NULL,
	CONSTRAINT "PK_tce_sessions_cpsession_id" PRIMARY KEY ("cpsession_id")
) WITHOUT OIDS;

CREATE TABLE "tce_users" (
	"user_id" BigSerial NOT NULL,
	"user_regdate" Timestamp NOT NULL,
	"user_ip" Varchar(15) NOT NULL,
	"user_name" Varchar(255) NOT NULL,
	"user_email" Varchar(255),
	"user_password" Varchar(255) NOT NULL,
	"user_regnumber" Varchar(255),
	"user_firstname" Varchar(255),
	"user_lastname" Varchar(255),
	"user_birthdate" Date,
	"user_birthplace" Varchar(255),
	"user_ssn" Varchar(255),
	"user_level" Smallint NOT NULL DEFAULT 1,
	CONSTRAINT "PK_tce_users_user_id" PRIMARY KEY ("user_id")
) WITHOUT OIDS;

CREATE TABLE "tce_subjects" (
	"subject_id" BigSerial NOT NULL,
	"subject_name" Varchar(255) NOT NULL,
	"subject_description" Text,
	"subject_enabled" Boolean NOT NULL DEFAULT '0',
	CONSTRAINT "PK_tce_subjects_subject_id" PRIMARY KEY ("subject_id")
) WITHOUT OIDS;

CREATE TABLE "tce_questions" (
	"question_id" BigSerial NOT NULL,
	"question_subject_id" Bigint NOT NULL,
	"question_description" Text NOT NULL,
	"question_enable_textarea" Boolean NOT NULL DEFAULT '0',
	"question_enabled" Boolean NOT NULL DEFAULT '0',
	CONSTRAINT "PK_tce_questions_question_id" PRIMARY KEY ("question_id")
) WITHOUT OIDS;

CREATE TABLE "tce_answers" (
	"answer_id" BigSerial NOT NULL,
	"answer_question_id" Bigint NOT NULL,
	"answer_description" Text NOT NULL,
	"answer_isright" Boolean NOT NULL DEFAULT '0',
	"answer_enabled" Boolean NOT NULL DEFAULT '0',
	CONSTRAINT "PK_tce_answers_answer_id" PRIMARY KEY ("answer_id")
) WITHOUT OIDS;

CREATE TABLE "tce_tests" (
	"test_id" BigSerial NOT NULL,
	"test_name" Varchar(255) NOT NULL,
	"test_description" Text NOT NULL,
	"test_begin_time" Timestamp,
	"test_end_time" Timestamp,
	"test_ip_range" Varchar(255) NOT NULL DEFAULT '*.*.*.*',
	"test_duration_time" Smallint NOT NULL DEFAULT 0,
	"test_num_mquestions" Smallint NOT NULL DEFAULT 0,
	"test_num_tquestions" Smallint NOT NULL DEFAULT 0,
	"test_num_answers" Smallint NOT NULL DEFAULT 0,
	"test_score_right" Numeric(10,3) NOT NULL DEFAULT 0,
	"test_score_wrong" Numeric(10,3) NOT NULL DEFAULT 0,
	"test_score_unanswered" Numeric(10,3) NOT NULL DEFAULT 0,
	CONSTRAINT "PK_tce_tests_test_id" PRIMARY KEY ("test_id")
) WITHOUT OIDS;

CREATE TABLE "tce_test_subjects" (
	"tsubj_test_id" Bigint NOT NULL,
	"tsubj_subject_id" Bigint NOT NULL,
	CONSTRAINT "pk_tce_test_subjects" PRIMARY KEY ("tsubj_test_id","tsubj_subject_id")
) WITHOUT OIDS;

CREATE TABLE "tce_tests_users" (
	"testuser_id" BigSerial NOT NULL,
	"testuser_test_id" Bigint NOT NULL,
	"testuser_user_id" Bigint NOT NULL,
	"testuser_status" Smallint NOT NULL DEFAULT 0 CHECK (testuser_status IN (0,1,2,3,4)),
	"testuser_creation_time" Timestamp NOT NULL,
	CONSTRAINT "pk_tce_tests_users" PRIMARY KEY ("testuser_id")
) WITHOUT OIDS;

CREATE TABLE "tce_tests_logs" (
	"testlog_id" BigSerial NOT NULL,
	"testlog_testuser_id" Bigint NOT NULL,
	"testlog_user_ip" Varchar(15),
	"testlog_question_id" Bigint NOT NULL,
	"testlog_answer_id" Bigint,
	"testlog_answer_text" Text,
	"testlog_score" Numeric(10,3),
	"testlog_creation_time" Timestamp,
	"testlog_display_time" Timestamp,
	"testlog_change_time" Timestamp,
	CONSTRAINT "PK_tce_tests_logs_testlog_id" PRIMARY KEY ("testlog_id")
) WITHOUT OIDS;

CREATE TABLE "tce_tests_logs_answers" (
	"logansw_testlog_id" Bigint NOT NULL,
	"logansw_answer_id" Bigint NOT NULL,
	CONSTRAINT "pk_tce_tests_logs_answers" PRIMARY KEY ("logansw_testlog_id","logansw_answer_id")
) WITHOUT OIDS;


/* Create Alternate Keys */

ALTER TABLE "tce_users" ADD CONSTRAINT "ak_user_name" UNIQUE ("user_name");
ALTER TABLE "tce_users" ADD CONSTRAINT "ak_user_regnumber" UNIQUE ("user_regnumber");
ALTER TABLE "tce_users" ADD CONSTRAINT "ak_user_ssn" UNIQUE ("user_ssn");
ALTER TABLE "tce_subjects" ADD CONSTRAINT "ak_subject_name" UNIQUE ("subject_name");
ALTER TABLE "tce_questions" ADD CONSTRAINT "ak_question" UNIQUE ("question_subject_id","question_description");
ALTER TABLE "tce_answers" ADD CONSTRAINT "ak_answer" UNIQUE ("answer_question_id","answer_description");
ALTER TABLE "tce_tests" ADD CONSTRAINT "ak_test_name" UNIQUE ("test_name");
ALTER TABLE "tce_tests_users" ADD CONSTRAINT "ak_testuser" UNIQUE ("testuser_test_id","testuser_user_id");
ALTER TABLE "tce_tests_logs" ADD CONSTRAINT "ak_testuser_question" UNIQUE ("testlog_testuser_id","testlog_question_id");

/* Create FOREIGN KEYs */

ALTER TABLE "tce_tests_users" ADD CONSTRAINT "rel_user_tests" FOREIGN KEY ("testuser_user_id") REFERENCES "tce_users" ("user_id") ON UPDATE RESTRICT ON DELETE CASCADE;
ALTER TABLE "tce_questions" ADD CONSTRAINT "rel_subject_questions" FOREIGN KEY ("question_subject_id") REFERENCES "tce_subjects" ("subject_id") ON DELETE CASCADE;
ALTER TABLE "tce_test_subjects" ADD CONSTRAINT "rel_subject_tests" FOREIGN KEY ("tsubj_subject_id") REFERENCES "tce_subjects" ("subject_id") ON DELETE RESTRICT;
ALTER TABLE "tce_answers" ADD CONSTRAINT "rel_question_answers" FOREIGN KEY ("answer_question_id") REFERENCES "tce_questions" ("question_id") ON DELETE CASCADE;
ALTER TABLE "tce_tests_logs" ADD CONSTRAINT "rel_question_logs" FOREIGN KEY ("testlog_question_id") REFERENCES "tce_questions" ("question_id") ON DELETE RESTRICT;
ALTER TABLE "tce_tests_logs_answers" ADD CONSTRAINT "rel_answer_logs" FOREIGN KEY ("logansw_answer_id") REFERENCES "tce_answers" ("answer_id") ON DELETE RESTRICT;
ALTER TABLE "tce_tests_logs" ADD CONSTRAINT "rel_useranswer_logs" FOREIGN KEY ("testlog_answer_id") REFERENCES "tce_answers" ("answer_id") ON DELETE RESTRICT;
ALTER TABLE "tce_tests_users" ADD CONSTRAINT "rel_test_users" FOREIGN KEY ("testuser_test_id") REFERENCES "tce_tests" ("test_id") ON UPDATE RESTRICT ON DELETE CASCADE;
ALTER TABLE "tce_test_subjects" ADD CONSTRAINT "rel_test_id_subjects" FOREIGN KEY ("tsubj_test_id") REFERENCES "tce_tests" ("test_id") ON DELETE CASCADE;
ALTER TABLE "tce_tests_logs" ADD CONSTRAINT "rel_testuser_logs" FOREIGN KEY ("testlog_testuser_id") REFERENCES "tce_tests_users" ("testuser_id") ON DELETE CASCADE;
ALTER TABLE "tce_tests_logs_answers" ADD CONSTRAINT "rel_testlog_answers" FOREIGN KEY ("logansw_testlog_id") REFERENCES "tce_tests_logs" ("testlog_id") ON DELETE CASCADE;
