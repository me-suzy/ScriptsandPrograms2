# --------------------------------------------------------------
#
# $Id: sqlite.sql,v 1.2 2005/05/05 12:50:17 raoul Exp $
#
# Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
# License:	GNU GPL (see COPYING)
# Website:	http://genu.org/
#
# --------------------------------------------------------------

# ANSWERS

CREATE TABLE genu_answers (
	answer_id INTEGER NOT NULL,
	question_id INTEGER NOT NULL DEFAULT '0',
	answer_text VARCHAR(64) NOT NULL DEFAULT '',
	answer_votes INTEGER NOT NULL DEFAULT '0',
	PRIMARY KEY (answer_id)
);

# CATEGORIES

CREATE TABLE genu_categories (
	category_id INTEGER NOT NULL,
	category_name VARCHAR(16) NOT NULL DEFAULT '',
	category_image VARCHAR(255) NOT NULL DEFAULT '',
	category_news INTEGER NOT NULL DEFAULT '0',
	category_posts INTEGER NOT NULL DEFAULT '0',
	category_level VARCHAR(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (category_id)
);

# COMMENTS

CREATE TABLE genu_comments (
	comment_id INTEGER NOT NULL,
	reply_id INTEGER NOT NULL DEFAULT '0',
	news_id INTEGER NOT NULL DEFAULT '0',
	user_id INTEGER NOT NULL DEFAULT '0',
	comment_subject VARCHAR(64) NOT NULL DEFAULT '',
	comment_text TEXT NOT NULL,
	comment_creation INTEGER NOT NULL DEFAULT '0',
	comment_edition INTEGER NOT NULL DEFAULT '0',
	PRIMARY KEY (comment_id)
);

# NEWS

CREATE TABLE genu_news (
	news_id INTEGER NOT NULL,
	category_id INTEGER NOT NULL DEFAULT '0',
	user_id INTEGER NOT NULL DEFAULT '0',
	news_active VARCHAR(1) NOT NULL DEFAULT '0',
	news_subject VARCHAR(64) NOT NULL DEFAULT '',
	news_text TEXT NOT NULL,
	news_source VARCHAR(255) NOT NULL DEFAULT '',
	news_comments INTEGER NOT NULL DEFAULT '0',
	news_date INTEGER NOT NULL DEFAULT '0',
	news_month VARCHAR(2) NOT NULL DEFAULT '',
	news_year VARCHAR(4) NOT NULL DEFAULT '',
	PRIMARY KEY (news_id)
);

# POSTS

CREATE TABLE genu_posts (
	post_id INTEGER NOT NULL,
	thread_id INTEGER NOT NULL DEFAULT '0',
	category_id INTEGER NOT NULL DEFAULT '0',
	user_id INTEGER NOT NULL DEFAULT '0',
	post_subject VARCHAR(64) NOT NULL DEFAULT '',
	post_text TEXT NOT NULL,
	post_creation INTEGER NOT NULL DEFAULT '0',
	post_edition INTEGER NOT NULL DEFAULT '0',
	post_active VARCHAR(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (post_id)
);

# QUESTIONS

CREATE TABLE genu_questions (
	question_id INTEGER NOT NULL,
	question_text VARCHAR(255) NOT NULL DEFAULT '',
	question_votes INTEGER NOT NULL DEFAULT '0',
	question_date INTEGER NOT NULL DEFAULT '0',
	PRIMARY KEY (question_id)
);

# SESSIONS

CREATE TABLE genu_sessions (
	session_id VARCHAR(32) NOT NULL DEFAULT '',
	session_value VARCHAR(255) NOT NULL DEFAULT '',
	session_expiry INTEGER NOT NULL DEFAULT '0'
);

# SETTINGS

CREATE TABLE genu_settings (
	sitename VARCHAR(64) NOT NULL DEFAULT '',
	siteurl VARCHAR(255) NOT NULL DEFAULT '',
	language VARCHAR(7) NOT NULL DEFAULT 'english',
	language_unique VARCHAR(1) NOT NULL DEFAULT '0',
	template VARCHAR(8) NOT NULL DEFAULT 'default',
	template_unique VARCHAR(1) NOT NULL DEFAULT '0',
	news_per_page INTEGER NOT NULL DEFAULT '7',
	comments_per_page INTEGER NOT NULL DEFAULT '7',
	headlines_per_backend INTEGER NOT NULL DEFAULT '7',
	threads_per_page INTEGER NOT NULL DEFAULT '20',
	posts_per_page INTEGER NOT NULL DEFAULT '20',
	news_order VARCHAR(10) NOT NULL DEFAULT 'news_date',
	allow_html VARCHAR(1) NOT NULL DEFAULT '0',
	allow_smilies VARCHAR(1) NOT NULL DEFAULT '1',
	submit_news VARCHAR(1) NOT NULL DEFAULT '1',
	send_news VARCHAR(1) NOT NULL DEFAULT '1',
	register_users VARCHAR(1) NOT NULL DEFAULT '1',
	sender_email VARCHAR(64) NOT NULL DEFAULT '',
	sender_name VARCHAR(16) NOT NULL DEFAULT '',
	date_format VARCHAR(64) NOT NULL DEFAULT 'D, M jS Y, g:i a',
	date_offset INTEGER NOT NULL DEFAULT '0'
);

# SMILIES

CREATE TABLE genu_smilies (
	smiley_id INTEGER NOT NULL,
	smiley_code VARCHAR(16) NOT NULL DEFAULT '',
	smiley_image VARCHAR(255) NOT NULL DEFAULT '',
	PRIMARY KEY (smiley_id)
);

# USERS

CREATE TABLE genu_users (
	user_id INTEGER NOT NULL,
	user_level VARCHAR(1) NOT NULL DEFAULT '1',
	user_name VARCHAR(16) NOT NULL DEFAULT '',
	user_password VARCHAR(32) NOT NULL DEFAULT '',
	user_email VARCHAR(64) NOT NULL DEFAULT '',
	user_viewemail VARCHAR(1) NOT NULL DEFAULT '0',
	user_website VARCHAR(255) NOT NULL DEFAULT '',
	user_location VARCHAR(64) NOT NULL DEFAULT '',
	user_occupation VARCHAR(64) NOT NULL DEFAULT '',
	user_age VARCHAR(3) NOT NULL DEFAULT '',
	user_comments INTEGER NOT NULL DEFAULT '0',
	user_posts INTEGER NOT NULL DEFAULT '0',
	user_creation INTEGER NOT NULL DEFAULT '0',
	user_ip VARCHAR(15) NOT NULL DEFAULT '',
	user_language VARCHAR(7) NOT NULL DEFAULT 'english',
	user_template VARCHAR(8) NOT NULL DEFAULT 'default',
	user_date_format VARCHAR(64) NOT NULL DEFAULT 'D, M jS Y, g:i a',
	user_date_offset INTEGER NOT NULL DEFAULT '0',
	user_lastvisit INTEGER NOT NULL DEFAULT '0',
	user_key VARCHAR(32) NOT NULL DEFAULT '',
	PRIMARY KEY (user_id)
);

# VOTES

CREATE TABLE genu_votes (
	vote_id INTEGER NOT NULL,
	question_id INTEGER NOT NULL DEFAULT '0',
	user_ip VARCHAR(15) NOT NULL DEFAULT '',
	vote_date INTEGER NOT NULL DEFAULT '0',
	PRIMARY KEY (vote_id)
);