CREATE TABLE contact (
    id VARCHAR(32) PRIMARY KEY,
    since TIMESTAMP,
    first_name VARCHAR(32),
    last_name VARCHAR(32),
    email VARCHAR(64),
    pref_lang VARCHAR(2)
  );

CREATE TABLE email_confirm (
    id VARCHAR(32) PRIMARY KEY,
    since TIMESTAMP,
    confirmed Boolean,
    code VARCHAR(32),
    nb_confirm INTEGER,
    send Boolean
  );

CREATE TABLE log (
    id VARCHAR(32) PRIMARY KEY,
    since TIMESTAMP,
    ip VARCHAR(32),
    agent VARCHAR(255),
    since_confirm TIMESTAMP,
    ip_confirm VARCHAR(32),
    agent_confirm VARCHAR(255),
    referer_confirm VARCHAR(255)
  );

CREATE TABLE submission (
    id VARCHAR(32) PRIMARY KEY,
    since TIMESTAMP,
    subject VARCHAR(255),
    message TEXT,
    send Boolean
  );

CREATE TABLE visual_confirm (
    id VARCHAR(32) PRIMARY KEY,
    since TIMESTAMP,
    code VARCHAR(8)
  );

CREATE TABLE counter (
    key VARCHAR(32) PRIMARY KEY,
    value INTEGER,
    last TIMESTAMP
  );

CREATE TABLE config (
    key VARCHAR(32) PRIMARY KEY,
    value TIMESTAMP
  );

CREATE INDEX idx_email_confirm_code ON email_confirm (code);
CREATE INDEX idx_visual_confirm_since ON visual_confirm (since);

INSERT INTO counter (key,value) VALUES ('index.php', 0);
INSERT INTO counter (key,value) VALUES ('need_confirm.php', 0);
INSERT INTO counter (key,value) VALUES ('confirm.php', 0);
INSERT INTO counter (key,value) VALUES ('jfunctions.js.php', 0);
INSERT INTO counter (key,value) VALUES ('visual_confirm_create', 0);
INSERT INTO counter (key,value) VALUES ('visual_confirm_render', 0);
INSERT INTO counter (key,value) VALUES ('email_confirm_send', 0);
INSERT INTO counter (key,value) VALUES ('confirm_receive', 0);
INSERT INTO counter (key,value) VALUES ('email_submission_send', 0);
INSERT INTO counter (key,value) VALUES ('error_validation_require_field', 0);
INSERT INTO counter (key,value) VALUES ('error_validation_email', 0);
INSERT INTO counter (key,value) VALUES ('error_validation_visual_confirm', 0);
INSERT INTO counter (key,value) VALUES ('error_validation_ticket_used', 0);
INSERT INTO counter (key,value) VALUES ('error_integrity', 0);
INSERT INTO counter (key,value) VALUES ('error_ticket_expire', 0);
INSERT INTO counter (key,value) VALUES ('invalid_md5', 0);
INSERT INTO counter (key,value) VALUES ('prune_run', 0);
INSERT INTO counter (key,value) VALUES ('prune_record', 0);

INSERT INTO config (key,value) VALUES ('init', 0);
INSERT INTO config (key,value) VALUES ('last_prune', 0)
