CREATE TABLE al_conf (
  name varchar(16) NOT NULL default '',
  value varchar(255) NOT NULL default '',
  PRIMARY KEY  (name)
) TYPE=MyISAM;

INSERT INTO al_conf VALUES ('admin_name', '');
INSERT INTO al_conf VALUES ('admin_email', '');
INSERT INTO al_conf VALUES ('admin_pass', '');
INSERT INTO al_conf VALUES ('link_banners', '1');
INSERT INTO al_conf VALUES ('link_buttons', '1');
INSERT INTO al_conf VALUES ('link_thumbs', '0');
INSERT INTO al_conf VALUES ('moderate_new', '0');
INSERT INTO al_conf VALUES ('notify_ban', '1');
INSERT INTO al_conf VALUES ('notify_new', '1');
INSERT INTO al_conf VALUES ('confirm_new', '1');
INSERT INTO al_conf VALUES ('hotlink', '1');
INSERT INTO al_conf VALUES ('verify_new', '0');
INSERT INTO al_conf VALUES ('desc_min', '0');
INSERT INTO al_conf VALUES ('name_min', '5');
INSERT INTO al_conf VALUES ('desc_max', '50');
INSERT INTO al_conf VALUES ('name_max', '20');
INSERT INTO al_conf VALUES ('count_clicks', '1');
INSERT INTO al_conf VALUES ('unique_cookie', '1');
INSERT INTO al_conf VALUES ('unique_ip', '1');
INSERT INTO al_conf VALUES ('find_host', '1');