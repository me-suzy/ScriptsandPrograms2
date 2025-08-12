# --------------------------------------------------------------
#
# $Id: mysql.sql,v 1.3 2005/05/05 12:50:17 raoul Exp $
#
# Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
# License:	GNU GPL (see COPYING)
# Website:	http://genu.org/
#
# --------------------------------------------------------------

# ANSWERS

CREATE TABLE genu_answers (
	answer_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	question_id SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	answer_text VARCHAR(64) NOT NULL DEFAULT '',
	answer_votes SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (answer_id)
) TYPE=MyISAM;

# CATEGORIES

CREATE TABLE genu_categories (
	category_id TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
	category_name VARCHAR(16) NOT NULL DEFAULT '',
	category_image VARCHAR(255) NOT NULL DEFAULT '',
	category_news INT(10) UNSIGNED NOT NULL DEFAULT '0',
	category_posts INT(10) UNSIGNED NOT NULL DEFAULT '0',
	category_level ENUM('0','1','2') NOT NULL DEFAULT '0',
	PRIMARY KEY (category_id)
) TYPE=MyISAM;

# COMMENTS

CREATE TABLE genu_comments (
	comment_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	reply_id INT(10) UNSIGNED NOT NULL DEFAULT '0',
	news_id SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	user_id SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	comment_subject VARCHAR(64) NOT NULL DEFAULT '',
	comment_text TEXT NOT NULL,
	comment_creation INT(11) NOT NULL DEFAULT '0',
	comment_edition INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (comment_id)
) TYPE=MyISAM;

# NEWS

CREATE TABLE genu_news (
	news_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	category_id TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
	user_id SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	news_active ENUM('0','1') NOT NULL DEFAULT '0',
	news_subject VARCHAR(64) NOT NULL DEFAULT '',
	news_text TEXT NOT NULL,
	news_source VARCHAR(255) NOT NULL DEFAULT '',
	news_comments SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	news_date INT(11) NOT NULL DEFAULT '0',
	news_month VARCHAR(2) NOT NULL DEFAULT '',
	news_year VARCHAR(4) NOT NULL DEFAULT '',
	PRIMARY KEY (news_id),
	FULLTEXT KEY news_subject (news_subject),
	FULLTEXT KEY news_text (news_text)
) TYPE=MyISAM;

# POSTS

CREATE TABLE genu_posts (
	post_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	thread_id INT(10) UNSIGNED NOT NULL DEFAULT '0',
	category_id TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
	user_id SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	post_subject VARCHAR(64) NOT NULL DEFAULT '',
	post_text TEXT NOT NULL,
	post_creation INT(11) NOT NULL DEFAULT '0',
	post_edition INT(11) NOT NULL DEFAULT '0',
	post_active ENUM('0','1') NOT NULL DEFAULT '0',
	PRIMARY KEY (post_id)
) TYPE=MyISAM;

# QUESTIONS

CREATE TABLE genu_questions (
	question_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	question_text VARCHAR(255) NOT NULL DEFAULT '',
	question_votes SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	question_date INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (question_id)
) TYPE=MyISAM;

# SESSIONS

CREATE TABLE genu_sessions (
	session_id VARCHAR(32) NOT NULL DEFAULT '',
	session_value VARCHAR(255) NOT NULL DEFAULT '',
	session_expiry INT(11) NOT NULL DEFAULT '0'
) TYPE=MyISAM;

# SETTINGS

CREATE TABLE genu_settings (
	sitename VARCHAR(64) NOT NULL DEFAULT '',
	siteurl VARCHAR(255) NOT NULL DEFAULT '',
	language ENUM('dutch','english','french','german','italian','polish','spanish') NOT NULL DEFAULT 'english',
	language_unique ENUM('0','1') NOT NULL DEFAULT '0',
	template ENUM('default','original') NOT NULL DEFAULT 'default',
	template_unique ENUM('0','1') NOT NULL DEFAULT '0',
	news_per_page TINYINT(2) UNSIGNED NOT NULL DEFAULT '7',
	comments_per_page TINYINT(2) UNSIGNED NOT NULL DEFAULT '7',
	headlines_per_backend TINYINT(2) UNSIGNED NOT NULL DEFAULT '7',
	threads_per_page TINYINT(2) UNSIGNED NOT NULL DEFAULT '20',
	posts_per_page TINYINT(2) UNSIGNED NOT NULL DEFAULT '20',
	news_order ENUM('news_date','news_month','news_year') NOT NULL DEFAULT 'news_date',
	allow_html ENUM('0','1') NOT NULL DEFAULT '0',
	allow_smilies ENUM('0','1') NOT NULL DEFAULT '1',
	submit_news ENUM('0','1') NOT NULL DEFAULT '1',
	send_news ENUM('0','1') NOT NULL DEFAULT '1',
	register_users ENUM('0','1') NOT NULL DEFAULT '1',
	sender_email VARCHAR(64) NOT NULL DEFAULT '',
	sender_name VARCHAR(16) NOT NULL DEFAULT '',
	date_format VARCHAR(64) NOT NULL DEFAULT 'D, M jS Y, g:i a',
	date_offset TINYINT(3) NOT NULL DEFAULT '0'
) TYPE=MyISAM;

# SMILIES

CREATE TABLE genu_smilies (
	smiley_id TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
	smiley_code VARCHAR(16) NOT NULL DEFAULT '',
	smiley_image VARCHAR(255) NOT NULL DEFAULT '',
	PRIMARY KEY (smiley_id)
) TYPE=MyISAM;

# USERS

CREATE TABLE genu_users (
	user_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	user_level ENUM('0','1','2','3','4') NOT NULL DEFAULT '1',
	user_name VARCHAR(16) NOT NULL DEFAULT '',
	user_password VARCHAR(32) NOT NULL DEFAULT '',
	user_email VARCHAR(64) NOT NULL DEFAULT '',
	user_viewemail ENUM('0','1') NOT NULL DEFAULT '0',
	user_website VARCHAR(255) NOT NULL DEFAULT '',
	user_location VARCHAR(64) NOT NULL DEFAULT '',
	user_occupation VARCHAR(64) NOT NULL DEFAULT '',
	user_age VARCHAR(3) NOT NULL DEFAULT '',
	user_comments SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	user_posts SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	user_creation INT(11) NOT NULL DEFAULT '0',
	user_ip VARCHAR(15) NOT NULL DEFAULT '',
	user_language ENUM('dutch','english','french','german','italian','polish','spanish') NOT NULL DEFAULT 'english',
	user_template ENUM('default','original') NOT NULL DEFAULT 'default',
	user_date_format VARCHAR(64) NOT NULL DEFAULT 'D, M jS Y, g:i a',
	user_date_offset TINYINT(3) NOT NULL DEFAULT '0',
	user_lastvisit INT(11) NOT NULL DEFAULT '0',
	user_key VARCHAR(32) NOT NULL DEFAULT '',
	PRIMARY KEY (user_id)
) TYPE=MyISAM;

# VOTES

CREATE TABLE genu_votes (
	vote_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	question_id SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	user_ip VARCHAR(15) NOT NULL DEFAULT '',
	vote_date INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (vote_id)
) TYPE=MyISAM;