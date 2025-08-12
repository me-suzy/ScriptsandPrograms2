/*
//============================================================+
// File name   : postgresql_db_structure.sql
// Begin       : 2004-04-28
// Last Update : 2005-07-06
//
// Description : TCExam database structure.
// Database    : PostgreSQL 8 / MySQL 4.1
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Tecnick.com S.r.l.
//               Via Ugo Foscolo n.19
//               09045 Quartu Sant'Elena (CA)
//               ITALY
//               www.tecnick.com
//               info@tecnick.com
//============================================================+
*/

INSERT INTO tce_users (user_regdate,user_ip,user_name,user_password,user_level) VALUES ('2001-11-11 00:00:00', '0.0.0.0', 'anonymous', '05e573554d095a5a3201590037017eff', 0);
INSERT INTO tce_users (user_regdate,user_ip,user_name,user_password,user_level) VALUES ('2000-01-01 00:00:00', '127.0.0.0', 'admin', '81dc9bdb52d04dc20036dbd8313ed055', 10);