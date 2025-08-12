# --------------------------------------------------------------
#
# $Id: pgsql.sql,v 1.4 2005/05/05 12:50:17 raoul Exp $
#
# Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
# License:	GNU GPL (see COPYING)
# Website:	http://genu.org/
#
# --------------------------------------------------------------

# ANSWERS

CREATE TABLE genu_answers (
	answer_id SERIAL,
	question_id INT2 NOT NULL DEFAULT '0',
	answer_text VARCHAR(64) NOT NULL DEFAULT '',
	answer_votes INT2 NOT NULL DEFAULT '0',
	PRIMARY KEY (answer_id)
);

# CATEGORIES

CREATE TABLE genu_categories (
	category_id SERIAL,
	category_name VARCHAR(16) NOT NULL DEFAULT '',
	category_image VARCHAR(255) NOT NULL DEFAULT '',
	category_news INT4 NOT NULL DEFAULT '0',
	category_posts INT4 NOT NULL DEFAULT '0',
	category_level INT CHECK (category_level IN ('0','1','2')) NOT NULL DEFAULT '0',
	PRIMARY KEY (category_id)
);

# COMMENTS

CREATE TABLE genu_comments (
	comment_id SERIAL,
	reply_id INT4 NOT NULL DEFAULT '0',
	news_id INT2 NOT NULL DEFAULT '0',
	user_id INT2 NOT NULL DEFAULT '0',
	comment_subject VARCHAR(64) NOT NULL DEFAULT '',
	comment_text TEXT NOT NULL,
	comment_creation INT4 NOT NULL DEFAULT '0',
	comment_edition INT4 NOT NULL DEFAULT '0',
	PRIMARY KEY (comment_id)
);

# NEWS

CREATE TABLE genu_news (
	news_id SERIAL,
	category_id INT2 NOT NULL DEFAULT '0',
	user_id INT2 NOT NULL DEFAULT '0',
	news_active INT CHECK (news_active IN ('0','1')) NOT NULL DEFAULT '0',
	news_subject VARCHAR(64) NOT NULL DEFAULT '',
	news_text TEXT NOT NULL,
	news_source VARCHAR(255) NOT NULL DEFAULT '',
	news_comments INT2 NOT NULL DEFAULT '0',
	news_date INT4 NOT NULL DEFAULT '0',
	news_month VARCHAR(2) NOT NULL DEFAULT '',
	news_year VARCHAR(4) NOT NULL DEFAULT '',
	PRIMARY KEY (news_id)
);

# POSTS

CREATE TABLE genu_posts (
	post_id SERIAL,
	thread_id INT4 NOT NULL DEFAULT '0',
	category_id INT2 NOT NULL DEFAULT '0',
	user_id INT2 NOT NULL DEFAULT '0',
	post_subject VARCHAR(64) NOT NULL DEFAULT '',
	post_text TEXT NOT NULL,
	post_creation INT4 NOT NULL DEFAULT '0',
	post_edition INT4 NOT NULL DEFAULT '0',
	post_active INT CHECK (post_active IN ('0','1')) NOT NULL DEFAULT '0',
	PRIMARY KEY (post_id)
);

# QUESTIONS

CREATE TABLE genu_questions (
	question_id SERIAL,
	question_text VARCHAR(255) NOT NULL DEFAULT '',
	question_votes INT2 NOT NULL DEFAULT '0',
	question_date INT4 NOT NULL DEFAULT '0',
	PRIMARY KEY (question_id)
);

# SESSIONS

CREATE TABLE genu_sessions (
	session_id VARCHAR(32) NOT NULL DEFAULT '',
	session_value VARCHAR(255) NOT NULL DEFAULT '',
	session_expiry INT4 NOT NULL DEFAULT '0'
);

# SETTINGS

CREATE TABLE genu_settings (
	sitename VARCHAR(64) NOT NULL DEFAULT '',
	siteurl VARCHAR(255) NOT NULL DEFAULT '',
	language VARCHAR(7) CHECK (language IN ('dutch','english','french','german','italian','polish','spanish')) NOT NULL DEFAULT 'english',
	language_unique INT CHECK (language_unique IN ('0','1')) NOT NULL DEFAULT '0',
	template VARCHAR(8) CHECK (template IN ('default','original')) NOT NULL DEFAULT 'default',
	template_unique INT CHECK (template_unique IN ('0','1')) NOT NULL DEFAULT '0',
	news_per_page INT2 NOT NULL DEFAULT '7',
	comments_per_page INT2 NOT NULL DEFAULT '7',
	headlines_per_backend INT2 NOT NULL DEFAULT '7',
	threads_per_page INT2 NOT NULL DEFAULT '20',
	posts_per_page INT2 NOT NULL DEFAULT '20',
	news_order VARCHAR(10) CHECK (news_order IN ('news_date','news_month','news_year')) NOT NULL DEFAULT 'news_date',
	allow_html INT CHECK (allow_html IN ('0','1')) NOT NULL DEFAULT '0',
	allow_smilies INT CHECK (allow_smilies IN ('0','1')) NOT NULL DEFAULT '1',
	submit_news INT CHECK (submit_news IN ('0','1')) NOT NULL DEFAULT '1',
	send_news INT CHECK (send_news IN ('0','1')) NOT NULL DEFAULT '1',
	register_users INT CHECK (register_users IN ('0','1')) NOT NULL DEFAULT '1',
	sender_email VARCHAR(64) NOT NULL DEFAULT '',
	sender_name VARCHAR(16) NOT NULL DEFAULT '',
	date_format VARCHAR(64) NOT NULL DEFAULT 'D, M jS Y, g:i a',
	date_offset INT2 NOT NULL DEFAULT '0'
);

# SMILIES

CREATE TABLE genu_smilies (
	smiley_id SERIAL,
	smiley_code VARCHAR(16) NOT NULL DEFAULT '',
	smiley_image VARCHAR(255) NOT NULL DEFAULT '',
	PRIMARY KEY (smiley_id)
);

# USERS

CREATE TABLE genu_users (
	user_id SERIAL,
	user_level INT CHECK (user_level IN ('0','1','2','3','4')) NOT NULL DEFAULT '1',
	user_name VARCHAR(16) NOT NULL DEFAULT '',
	user_password VARCHAR(32) NOT NULL DEFAULT '',
	user_email VARCHAR(64) NOT NULL DEFAULT '',
	user_viewemail INT CHECK (user_viewemail IN ('0','1')) NOT NULL DEFAULT '0',
	user_website VARCHAR(255) NOT NULL DEFAULT '',
	user_location VARCHAR(64) NOT NULL DEFAULT '',
	user_occupation VARCHAR(64) NOT NULL DEFAULT '',
	user_age VARCHAR(3) NOT NULL DEFAULT '',
	user_comments INT2 NOT NULL DEFAULT '0',
	user_posts INT2 NOT NULL DEFAULT '0',
	user_creation INT4 NOT NULL DEFAULT '0',
	user_ip VARCHAR(15) NOT NULL DEFAULT '',
	user_language VARCHAR(7) CHECK (user_language IN ('dutch','english','french','german','italian','polish','spanish')) NOT NULL DEFAULT 'english',
	user_template VARCHAR(8) CHECK (user_template IN ('default','original')) NOT NULL DEFAULT 'default',
	user_date_format VARCHAR(64) NOT NULL DEFAULT 'D, M jS Y, g:i a',
	user_date_offset INT2 NOT NULL DEFAULT '0',
	user_lastvisit INT4 NOT NULL DEFAULT '0',
	user_key VARCHAR(32) NOT NULL DEFAULT '',
	PRIMARY KEY (user_id)
);

# VOTES

CREATE TABLE genu_votes (
	vote_id SERIAL,
	question_id INT2 NOT NULL DEFAULT '0',
	user_ip VARCHAR(15) NOT NULL DEFAULT '',
	vote_date INT4 NOT NULL DEFAULT '0',
	PRIMARY KEY (vote_id)
);