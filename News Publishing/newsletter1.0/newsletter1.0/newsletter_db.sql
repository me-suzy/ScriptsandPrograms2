#
#   author: Konstantion Atanasov
#   version: 1.0
#   project: newsletter;
#       


# status - 0 not send 
# status - 1 send 
#
# article_type - text or html
#

CREATE TABLE IF NOT EXISTS newsletter (

    id int not null auto_increment primary key,
    datetime_posted datetime not null,
    datetime_send   datetime default null,
    article longtext default null,
    article_type varchar(15) default 'text/plain', 
    status smallint not null default 0

) Type=MyISAM;


#
#    status - 0 unsubscribed 
#    status - 1 subscribed 
#    status - 2 confirmed

CREATE TABLE IF NOT EXISTS newsletter_users (

    email    varchar(200) not null primary key,
    datetime_subscribed datetime not null,
    name     varchar(100) not null default '...',
    
    status smallint not null default 0 

) Type=MyISAM;



#
#     store settings 
#
#

CREATE TABLE IF NOT EXISTS newsletter_settings (

    id int not null auto_increment primary key,
    welcome_email     text default null,
    welcome_email_subject varchar(250) default null,
    confirm_email     text default null,
    confirm_email_subject varchar(250) default null,
    unsubscribe_email text default null,
    unsubscribe_email_subject varchar(250) default null,
    newsletter_email_footer text default null,
    newsletter_email_subject varchar(250) default null
   
) Type=MyISAM;

INSERT INTO newsletter_settings(welcome_email) VALUES('...');

